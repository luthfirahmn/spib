<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class GetData extends REST_Controller
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

    function index_post()
    {

        try {
            $this->check_token();
            $nama_site = $this->post("nama_site");
            $kode_instrument = $this->post("kode_instrument");

            if ($nama_site === '' ||  empty($kode_instrument)) {
                throw new Exception("Semua data harus terisi");
            }

            $get_site = $this->db->get_where("ms_regions", array('site_name' => $nama_site))->row();

            if (!$get_site) {
                throw new Exception("Site tidak tersedia");
            }

            $db_site = $this->change_connection($get_site->id);

            $result_data = $this->get_latest_data_values($db_site, $get_site->id, $kode_instrument);

            $this->response(['status' => true, 'data' => $result_data], 200);
        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function get_latest_data_values($db_site, $region_id, $kode_instrument)
    {
        $data = [];
        foreach ($kode_instrument as $row) {
            $query = $db_site->query("SELECT data.id, data.kode_instrument, data.jam, data.tanggal,t3.nama_instrument
                                        FROM data
                                        INNER JOIN " . $this->db->database . ".tr_instrument t3 ON data.kode_instrument = t3.kode_instrument AND t3.ms_regions_id = {$region_id}
                                        WHERE data.kode_instrument = '{$row}'
                                        AND data.keterangan = 'OTOMATIS'
                                        ORDER BY data.tanggal DESC,data.jam DESC
                                        LIMIT 1");
            $result_data = $query->row();

            if ($result_data) {
                $temp_data = [
                    'nama_instrument' => $result_data->nama_instrument,
                    'kode_instrument' => $result_data->kode_instrument,
                    'tanggal' => $result_data->tanggal,
                    'jam' => $result_data->jam,
                    'detail' => []
                ];



                $query = $db_site->query("SELECT t1.data_jadi as nilai,t2.jenis_sensor,t2.unit_sensor
                                        FROM data_value t1
                                        INNER JOIN " . $this->db->database . ".sys_jenis_sensor t2 ON t1.sensor_id = t2.id
                                        WHERE t1.data_id = $result_data->id AND t1.data_jadi != '' AND t1.data_primer  = 0
            ");
                $result_data_value = $query->result();

                if ($result_data_value) {
                    $temp_data['detail'] = $result_data_value;
                }

                $data[] = $temp_data;
            }
        }

        return $data;
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
