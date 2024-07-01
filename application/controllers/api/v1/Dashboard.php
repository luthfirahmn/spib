<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Dashboard extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Data_model');
        $this->load->model('M_data');
        $this->load->model('M_akses');
        $this->load->dbutil();
        $this->load->database();

        //$this->load->model('member_model', 'member', TRUE);
    }

    function token_get()
    {
        $salt = 'spib';
        $bearer = md5(md5($salt));
        echo $bearer;
    }

    private function check_token()
    {
        $salt = 'spib';
        $bearer = md5(md5($salt));
        // bc87eb30ae9f2a6ca6ee13fa917d7e6b
        if (!$this->input->get_request_header('Authorization')) {
            $this->response([
                'status' => false,
                'message' => 'Authorization header tidak ditemukan.'
            ], 401);
        }

        $authorization_header = $this->input->get_request_header('Authorization');
        $token = explode(" ", $authorization_header)[1];

        if ($token !== $bearer) {
            $this->response([
                'status' => false,
                'message' => 'Token bearer tidak valid.'
            ], 401);
        }
    }
    //Menampilkan data kontak
    function index_post()
    {

        try {
            $this->check_token();
            $nama_site = $this->post("nama_site");
            $kode_instrument = $this->post("kode_instrument");
            $data_mentah = $this->post("data_mentah");
            $tanggal = $this->post("tanggal");
            $jam = $this->post("jam");
            $kalibrasi = $this->post("kalibrasi");

            if ($nama_site === '' || $kode_instrument === '' || empty($data_mentah) || $tanggal === '' || $jam === '') {
                throw new Exception("Semua data harus terisi");
            }

            $get_site = $this->db->get_where("ms_regions", array('site_name' => $nama_site))->row();

            if (!$get_site) {
                throw new Exception("Site tidak tersedia");
            }

            $get_instrument = $this->db->get_where("tr_instrument", array('kode_instrument' => $kode_instrument, 'ms_regions_id' => $get_site->id))->row();

            if (!$get_instrument) {
                throw new Exception("Kode instrument tidak tersedia");
            }

            $type_instrument_name = $this->db->get_where("tr_instrument_type", array('id' => $get_instrument->tr_instrument_type_id))->row()->name;

            if (!$type_instrument_name) {
                throw new Exception("Tipe instrument tidak tersedia");
            }

            $koefisien_arr = $this->db->get_where("tr_koefisien", array(
                'tr_instrument_id' => $get_instrument->id,
                // 'tr_instrument_type_id' => $get_instrument->tr_instrument_type_id
            ))->row();

            if (!$koefisien_arr) {
                throw new Exception("Koefisien tidak tersedia");
            }

            $koefisien = json_decode($koefisien_arr->parameter, true);
            if (!empty($kalibrasi)) {
                foreach ($kalibrasi as $key => $value) {
                    if (array_key_exists($key, $koefisien)) {
                        $koefisien[$key] = $value;
                    }
                }
            }


            // Menampilkan hasil
            $query = $this->db->query("SELECT 
											t1.id,
											t1.tr_koefisien_id,
											t1.jenis_sensor_jadi,
											t2.jenis_sensor nama_sensor,
											t2.var_name kode_sensor_jadi,
                                            t2.unit_sensor
										 FROM tr_koefisien_sensor_non_vwp t1
										 INNER JOIN  sys_jenis_sensor t2 ON t1.jenis_sensor_jadi = t2.id
										 WHERE t1.tr_instrument_id = {$get_instrument->id}
										 AND t1.jenis_sensor_jadi != 0
                                         ORDER BY t1.id ASC
										 ");
            $data_jadi = $query->result();


            if (!$data_jadi) {
                throw new Exception("Data Jadi tidak tersedia");
            }


            $data = array();
            $data['instrument_id'] = $get_instrument->id;
            $data['data_mentah'] = $data_mentah;

            $hasil = formula($type_instrument_name, $data_jadi, $data, $koefisien, 'OTOMATIS');
            if (!$hasil) {
                throw new Exception("Formula belum tersedia");
            }

            $data_insert = array(
                'kode_instrument' => $kode_instrument,
                'tanggal' => $tanggal,
                'jam' => $jam,
                'keterangan' => "OTOMATIS",
                "created_by" => "SYSTEM",
                "updated_by" => "SYSTEM",
            );
            $add_data = $this->add_data($hasil, $get_site->id, $data_insert, $data_mentah);

            if (!$add_data) {
                throw new Exception("Gagal insert data");
            }
            $this->response(['status' => true, 'message' => 'Sukses menambah data'], 200);
        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    private function add_data($hasil, $site_id, $data_insert, $data_mentah)
    {
        $db_site = $this->change_connection($site_id);
        $db_site->trans_begin();
        try {
            $check_data = $db_site->get_where("temp_data", ['kode_instrument' => $data_insert['kode_instrument']])->row();

            if (!$check_data) {
                $db_site->insert("temp_data", $data_insert);
                $last_id = $db_site->insert_id();

                foreach ($hasil as $row) {
                    $data_value = array(
                        'data_id' => $last_id,
                        'sensor_id' => $row['id_sensor'],
                        'data_primer' => 0,
                        'data_jadi' =>  $row['hasil'],
                        "created_by" => "SYSTEM",
                        "updated_by" => "SYSTEM",
                    );
                    $insert_data_value = $db_site->insert("temp_data_value", $data_value);
                    if (!$insert_data_value) {
                        throw new Exception("Gagal insert data value");
                    }
                }
            } else {
                $db_site->where("id", $check_data->id);
                $db_site->update("temp_data", $data_insert);

                foreach ($hasil as $row) {
                    $check_data_value = $db_site->get_where("temp_data_value", ['data_id' => $check_data->id, 'sensor_id' => $row['id_sensor']])->row();

                    $data_value = array(
                        'data_id' => $check_data->id,
                        'sensor_id' => $row['id_sensor'],
                        'data_primer' => 0,
                        'data_jadi' =>  $row['hasil'],
                        "created_by" => "SYSTEM",
                        "updated_by" => "SYSTEM",
                    );
                    if (!$check_data_value) {
                        $insert_data_value = $db_site->insert("temp_data_value", $data_value);
                        if (!$insert_data_value) {
                            throw new Exception("Gagal insert data value");
                        }
                    } else {
                        $db_site->where('id', $check_data_value->id);
                        $update_data_value = $db_site->update("temp_data_value", $data_value);
                        if (!$update_data_value) {
                            throw new Exception("Gagal insert data value");
                        }
                    }
                }
            }

            $db_site->trans_commit();
            return true;
        } catch (Exception $e) {
            $db_site->rollback();
            return false;
        }
    }


    public function change_connection($id_regions)
    {

        $query = $this->db->query("SELECT * FROM ms_regions WHERE id = '$id_regions'");
        $result = $query->row();

        $second_db_params = $this->switchDatabase($result->database_host, $result->database_username, $result->database_password, $result->database_name, $result->database_port);
        $this->db2 = $this->load->database($second_db_params, TRUE);

        if ($this->db2->initialize()) {
            return $this->db2;
        } else {
            return false;
        }
    }

    private function switchDatabase($hostname, $username, $password, $database, $port)
    {
        $params = array(
            'hostname' => $hostname . ':' . $port,
            'username' => $username,
            'password' => $password,
            'database' => $database,
            // Other database configuration parameters
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt'  => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );

        return $params;
    }
}
