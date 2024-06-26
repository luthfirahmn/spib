<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Grafik extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Data_model');
        $this->load->model('M_data');
        $this->load->model('M_akses');
        $this->load->model('M_grafik');
        $this->load->dbutil();
        $this->load->database();
    }


    public function index()
    {
        $roles_id = $this->session->userdata('roles_id');
        $ap_id_user = $this->session->userdata('ap_id_user');
        $data['hak_akses'] = $this->M_akses->hak_akses($roles_id, 'Grafik');
        //$data['station'] = $this->M_grafik->grafik($ap_id_user);
        $data['region'] = $this->M_grafik->region($ap_id_user);
        $data['station'] = $this->db->get_where('ms_lookup_values', ['lookup_config' => 'STASIUN_TYPE'])->result();
        $this->load->view('grafik/index', $data);
    }


    public function getInstrumentData()
    {
        $instrument = $this->input->post('instrument');
        $region_id = $this->input->post('region_id');
        $kode_instrument = $this->db->get_where("tr_instrument", array('id' => $instrument))->row()->kode_instrument;

        $db_site = $this->change_connection($region_id);

        $sensor = $db_site->query("
        SELECT t3.jenis_sensor, t3.unit_sensor, t2.data_jadi as val_sensor, t1.jam
        FROM data_value t2
        LEFT JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
        INNER JOIN data t1 ON t1.id = t2.data_id
        WHERE t1.kode_instrument = '{$kode_instrument}'
        AND t2.data_jadi != ''
    ")->result_array();

        $grouped_data = array();

        foreach ($sensor as $data) {
            if (!isset($grouped_data[$data['jenis_sensor']])) {
                $grouped_data[$data['jenis_sensor']] = array(
                    'jenis_sensor' => $data['jenis_sensor'],
                    'data' => array()
                );
            }
            $grouped_data[$data['jenis_sensor']]['data'][] = array(
                'val_sensor' => $data['val_sensor'],
                'jam' => substr($data['jam'], 0, 5)
            );
        }

        // Ubah array menjadi indeks numerik
        $grouped_data = array_values($grouped_data);
        // Outputkan data yang telah dikelompokkan
        echo json_encode($grouped_data);
    }

    public function getStasiunChange()
    {
        $ms_stasiun_id  = $this->input->get('ms_stasiun_id');
        $query = $this->db->query("SELECT * FROM ms_stasiun WHERE id = {$ms_stasiun_id}");
        $stasiun = $query->result();

        foreach ($stasiun as $row) {
            switch ($row->stasiun_type) {
                case  'Geologi':
                    $data['status'] = true;
                    $data['elevasi'] = true;
                    $data['elevasi_data'] = true;
                    break;
                default:
            }
        }

        echo $this->M_data->instrument($ms_stasiun_id);
    }

    public function getElevation()
    {
        try {
            $stasiun = $_GET['stasiun'];
            $regions_id = $_GET['regions_id'];
            if ($regions_id == '') {
                throw new Exception('Site harus dipilih');
            }
            $query = $this->db->query("
            SELECT t1.elevasi_sensor
            FROM tr_instrument_instalasi t1
            WHERE t1.tr_instrument_id IN (
                                        SELECT id
                                        FROM tr_instrument 
                                        WHERE ms_regions_id = {$regions_id}
                                        AND ms_stasiun_id = {$stasiun}
                                        )
            GROUP BY t1.elevasi_sensor
            ");
            $result = $query->result();
            if (!$result) {
                throw new Exception('Tidak ada data didalam stasiun tersebut, pilih stasiun lain');
            }

            echo json_encode(['error' => false, 'data' => $result]);
        } catch (Exception $e) {
            echo json_encode(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    public function getInstrument()
    {
        try {
            $stasiun = $_GET['stasiun'];
            $regions_id = $_GET['regions_id'];
            $flag = $_GET['flag'];
            $elevasi = $_GET['elevasi'];

            if ($regions_id == '') {
                throw new Exception('Site harus dipilih');
            }

            if ($flag == 'by_elevasi') {
                $kondisi = "AND t1.id IN (SELECT tr_instrument_id FROM tr_instrument_instalasi WHERE elevasi_sensor = {$elevasi})";
            } else {
                $kondisi = "";
            }
            $query = $this->db->query("
            SELECT t1.id,t1.kode_instrument,t1.nama_instrument
            FROM tr_instrument t1
            WHERE t1.ms_regions_id = {$regions_id}
            AND  t1.ms_stasiun_id = {$stasiun}
            $kondisi
            ");
            $results = $query->result();
            if (!$results) {
                throw new Exception('Tidak ada data didalam stasiun tersebut, pilih stasiun lain');
            }
            $grouped_results = [];

            foreach ($results as $row) {
                $nama_instrument = $row->nama_instrument;

                if (preg_match('/^SMR-(\d+)\.\d+$/', $nama_instrument, $matches)) {
                    $group_name = "SMR-{$matches[1]}";
                } else {
                    $group_name = $nama_instrument;
                }

                // Menambahkan ke grup yang sesuai
                if (!isset($grouped_results[$group_name])) {
                    $grouped_results[$group_name] = [];
                }
                $grouped_results[$group_name][] = [
                    'id' => $row->id,
                    'kode_instrument' => $row->kode_instrument,
                    'nama_instrument' => $nama_instrument
                ];
            }


            $processed_results = [];
            foreach ($grouped_results as $group_name => $group_items) {
                if (count($group_items) === 1) {
                    $processed_results[] = $group_items[0];
                } else {
                    // Jika grup memiliki lebih dari satu item, tambahkan nama grup saja
                    $processed_results[] =  [
                        'id' => $group_name,
                        'kode_instrument' => $group_name,
                        'nama_instrument' => $group_name
                    ];
                }
            }
            echo json_encode(['error' => false, 'data' => $processed_results]);
        } catch (Exception $e) {
            echo json_encode(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getGroupKey($kode_instrument)
    {
        if (strpos($kode_instrument, 'SMR-') === 0) {
            return substr($kode_instrument, 0, 5);
        } else {
            return substr($kode_instrument, 0, 4);
        }
    }

    public function getDataJadi()
    {
        try {
            $instrument_id = $_GET['instrument_id'];

            if (strpos($instrument_id, 'SMR') !== false) {
                $inst = $instrument_id . '.';
                $con = "t2.nama_instrument LIKE '{$inst}%'";
            } else {
                $con = "t2.id = {$instrument_id}";
            }
            $query = $this->db->query("
            SELECT tt1.id,tt1.jenis_sensor,tt1.unit_sensor
                FROM sys_jenis_sensor tt1
            WHERE tt1.id IN (
                SELECT t1.jenis_sensor_jadi
                FROM tr_koefisien_sensor_non_vwp t1
                INNER JOIN tr_instrument t2 ON t2.id = t1.tr_instrument_id
                WHERE {$con}
                )
            ");
            $result = $query->result();
            if (!$result) {
                throw new Exception('Tidak ada data didalam stasiun tersebut, pilih stasiun lain');
            }

            echo json_encode(['error' => false, 'data' => $result]);
        } catch (Exception $e) {
            echo json_encode(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    public function getSensor()
    {
        $region_id = $_GET['ms_regions_id'];

        $query = $this->db->get_where('ms_stasiun', ['ms_regions_id' => $region_id])->result();

        echo json_encode($query);
    }
    public function getImageStasiun($id)
    {
        $image = $this->db->get_where("ms_stasiun", ["id" => $id])->row()->foto;
        $url = base_url('assets/upload/station/') . $image;

        echo json_encode(['imageUrl' => $url]);
    }

    public function filter()
    {
        try {
            $stasiun = $_POST['stasiun'];
            $ms_regions_id = $_POST['ms_regions_id'];
            $instrument_id = $_POST['pilih_instrument'];
            $select_data = isset($_POST['select_data']) ? $_POST['select_data'] : "";

            if ($ms_regions_id == '' || $stasiun == '' || $select_data == "") {
                throw new Exception('Site, Stasiun, Data harus dipilih');
            }

            $waktu = $_POST['waktu'];
            $periode = $_POST['periode'];
            $tipe_data = $_POST['tipe_data'];

            $data_tambah = isset($_POST['data_tambah']) ? $_POST['data_tambah'] : [];

            $db_site = $this->change_connection($ms_regions_id);

            if (strpos($instrument_id, 'SMR') !== false) {
                $inst = $instrument_id . '.';
                $con = "t1.nama_instrument LIKE '{$inst}%'";
            } else {
                $con = "t1.id = {$instrument_id}";
            }

            // $select_data_str = implode(',', $select_data);
            $query = $this->db->query("
                SELECT t1.kode_instrument,t1.nama_instrument, t3.id as sensor_id, t3.jenis_sensor, t3.unit_sensor
                FROM tr_instrument t1
                INNER JOIN tr_koefisien_sensor_non_vwp t2 ON t2.tr_instrument_id = t1.id
                INNER JOIN sys_jenis_sensor t3 ON t2.jenis_sensor_jadi = t3.id
                WHERE t1.ms_regions_id = {$ms_regions_id}
                AND t2.jenis_sensor_jadi = ($select_data)
                AND t1.ms_stasiun_id = {$stasiun}
                AND {$con}
                ORDER BY t1.id
            ");
            $results = $query->result();

            $row_data = [];

            foreach ($results as $result) {
                if ($result) {
                    $result->data_type = 'data_utama';
                    $row_data[] = $result;
                }
            }

            if (!empty($data_tambah)) {
                $data_tambah_cek = 0;
                if (in_array('Tinggi Muka Air', $data_tambah)) {
                    $data_tambah_cek = 1;
                }
                if (in_array('Rainfall', $data_tambah)) {
                    $data_tambah_cek = 1;
                }

                if ($data_tambah_cek != 0) {
                    foreach ($data_tambah as $row_data_tambah) {

                        if ($row_data_tambah == 'Tinggi Muka Air') {
                            $stasiun_dt_tambah = 'AWLR1';
                        } else if ($row_data_tambah == 'Rainfall') {
                            $stasiun_dt_tambah = 'KLIM1';
                        } else {
                        }

                        $query = $this->db->query("
                    SELECT t1.kode_instrument,t1.nama_instrument, t3.id as sensor_id, t3.jenis_sensor, t3.unit_sensor
                    FROM tr_instrument t1
                    INNER JOIN tr_koefisien_sensor_non_vwp t2 ON t2.tr_instrument_id = t1.id
                    INNER JOIN sys_jenis_sensor t3 ON t2.jenis_sensor_jadi = t3.id
                    WHERE t1.ms_regions_id = {$ms_regions_id}
                    AND t3.jenis_sensor  = '{$row_data_tambah}'
                    AND t1.kode_instrument LIKE '%{$stasiun_dt_tambah}%'
                    ");


                        $dt_result = $query->result();

                        foreach ($dt_result as $row_data_tambah) {
                            if ($result->unit_sensor == $row_data_tambah->unit_sensor) {
                                $row_data[] = (object) array(
                                    'data_type' => 'data_utama',
                                    'nama_instrument' => $row_data_tambah->nama_instrument,
                                    'kode_instrument' => $row_data_tambah->kode_instrument,
                                    'sensor_id' => $row_data_tambah->sensor_id,
                                    'jenis_sensor' => $row_data_tambah->jenis_sensor,
                                    'unit_sensor' => $row_data_tambah->unit_sensor
                                );
                            } else {
                                $row_data[] = (object) array(
                                    'data_type' => 'data_tambahan',
                                    'nama_instrument' => $row_data_tambah->nama_instrument,
                                    'kode_instrument' => $row_data_tambah->kode_instrument,
                                    'sensor_id' => $row_data_tambah->sensor_id,
                                    'jenis_sensor' => $row_data_tambah->jenis_sensor,
                                    'unit_sensor' => $row_data_tambah->unit_sensor
                                );
                            }
                        }
                    }
                }


                $elevasi_awlr = [];
                if (in_array('Elevasi Puncak', $data_tambah)) {
                    $elevasi_awlr[] .= 'elevasi_puncak';
                }

                if (in_array('Elevasi Spillway', $data_tambah)) {
                    $elevasi_awlr[] .= 'elevasi_spillway';
                }

                if (in_array('Batas Kritis', $data_tambah)) {
                    $elevasi_awlr[] .= 'elevasi_batas_kritis';
                }


                if (!empty($elevasi_awlr)) {
                    $elevasi_awlr_jadi = implode(",", $elevasi_awlr);
                    $query = $this->db->query("
                    SELECT {$elevasi_awlr_jadi}
                    FROM ms_sites t1
                    WHERE t1.ms_regions_id = {$ms_regions_id}
                ");
                    $row_elevasi_awlr = $query->row_array();

                    $unit_elevasi_awlr = 'm';
                    foreach ($row_elevasi_awlr as $index => $row) {
                        $index_name = ucwords(str_replace('_', ' ', $index));
                        if ($result->unit_sensor == $unit_elevasi_awlr) {
                            $row_data[] = (object) array(
                                'data_type' => 'data_utama',
                                'nama_instrument' => 'Site',
                                'kode_instrument' => $index_name,
                                'sensor_id' => 'elevasi_awlr',
                                'jenis_sensor' => $index_name,
                                'unit_sensor' =>  $unit_elevasi_awlr,
                                'value' => $row
                            );
                        } else {
                            $row_data[] = (object) array(
                                'data_type' => 'data_tambahan',
                                'nama_instrument' => 'Site',
                                'kode_instrument' => $index_name,
                                'sensor_id' => 'elevasi_awlr',
                                'jenis_sensor' => $index_name,
                                'unit_sensor' =>  $unit_elevasi_awlr,
                                'value' => $row
                            );
                        }
                    }
                }
            }

            foreach ($row_data as $row) {
                $detail = [];

                if ($row->sensor_id == 'elevasi_awlr') {

                    if ($periode == 'Jam') {
                        $row_pertama = $row_data[0]->detail;
                        if (!empty($row_pertama)) {
                            foreach ($row_pertama as $index => $row_pertama_val) {
                                $detail[$index] = (object)array(
                                    'tanggal' => $row_pertama_val->tanggal,
                                    'jam' => substr($row_pertama_val->jam, 0, 5),
                                    'data_jadi' => $row->value,
                                );
                            }
                        }
                    } else {
                        $row_pertama = $row_data[0]->detail;
                        if (!empty($row_pertama)) {
                            foreach ($row_pertama as $index => $row_pertama_val) {
                                $detail[$index] = (object) array(
                                    'tanggal' => $row_pertama_val->tanggal,
                                    'data_jadi' => $row->value,
                                );
                            }
                        }
                    }
                } else {
                    if ($periode == 'Jam') {
                        $ddt = "AND t1.tanggal = '$waktu'
                                AND t1.jam LIKE '%:00:00'";
                        $kt = !empty($tipe_data) ? "AND t1.keterangan = '$tipe_data'" : "";

                        $query = $db_site->query("
                            SELECT t1.tanggal, DATE_FORMAT(t1.jam, '%H:%i') AS jam, FORMAT(t2.data_jadi, 2) as data_jadi
                            FROM data t1
                            INNER JOIN data_value t2 ON t2.data_id = t1.id
                            WHERE t1.kode_instrument = '{$row->kode_instrument}'
                            AND t2.sensor_id = {$row->sensor_id}
                            AND t2.data_jadi != ''
                            {$kt}
                            {$ddt}
                            ORDER BY t1.tanggal ASC, t1.jam ASC
                        ");
                        $detail = $query->result();
                    } else {
                        $ddt = "AND DATE_FORMAT(t1.tanggal, '%Y-%m') = '$waktu'";

                        if (!empty($keterangan)) {
                            $kt = "AND t1.keterangan = '$tipe_data'";
                        } else {
                            $kt = "";
                        }

                        $query = $db_site->query("
                            SELECT 
                                DATE(t1.tanggal) as tanggal,
                                FORMAT(AVG(t2.data_jadi), 2) as data_jadi
                            FROM data t1
                            LEFT JOIN data_value t2 ON t1.id = t2.data_id
                            WHERE t1.kode_instrument = '{$row->kode_instrument}'
                            AND t2.sensor_id = {$row->sensor_id}
                            AND t2.data_jadi != ''
                            {$kt}
                            {$ddt}
                            GROUP BY DATE(t1.tanggal)
                            ORDER BY tanggal ASC
                        ");
                        $detail = $query->result();
                    }
                }


                $row->detail = $detail;
            }

            $data_utama = array();
            $data_tambahan = array();

            foreach ($row_data as $item) {
                if ($item->data_type === 'data_utama') {
                    $data_utama[] = $item;
                } else {
                    $data_tambahan[] = $item;
                }
            }
            echo json_encode(['error' => false, 'data' => $data_utama, 'data_tambah' => $data_tambahan, 'periode' => $periode]);
        } catch (Exception $e) {
            echo json_encode(['error' => true, 'msg' => $e->getMessage()]);
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
}
