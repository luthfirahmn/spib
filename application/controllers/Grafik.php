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
                'jam' => $data['jam']
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

    public function getDataJadi()
    {
        try {
            $stasiun = $_GET['stasiun'];
            $regions_id = $_GET['regions_id'];
            if ($regions_id == '') {
                throw new Exception('Site harus dipilih');
            }
            $query = $this->db->query("
            SELECT tt1.id,tt1.jenis_sensor,tt1.unit_sensor
                FROM sys_jenis_sensor tt1
            WHERE tt1.id IN (
                SELECT t1.jenis_sensor_jadi
                FROM tr_koefisien_sensor_non_vwp t1
                INNER JOIN tr_instrument t2 ON t2.id = t1.tr_instrument_id
                INNER JOIN ms_stasiun t3 ON t2.ms_stasiun_id = t3.id
                WHERE t3.id = '{$stasiun}'
                AND t3.ms_regions_id = {$regions_id}
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
            $select_data = isset($_POST['select_data']) ? $_POST['select_data'] : [];

            if ($ms_regions_id == '' || $stasiun == '' || empty($select_data)) {
                throw new Exception('Site, Stasiun, Data harus dipilih');
            }

            $waktu = $_POST['waktu'];
            $periode = $_POST['periode'];
            $tipe_data = $_POST['tipe_data'];

            $data_tambah = isset($_POST['data_tambah']) ? $_POST['data_tambah'] : [];

            $db_site = $this->change_connection($ms_regions_id);

            $select_data_str = implode(',', $select_data);
            $query = $this->db->query("
                SELECT t1.kode_instrument,t1.nama_instrument, t3.id as sensor_id, t3.jenis_sensor, t3.unit_sensor
                FROM tr_instrument t1
                INNER JOIN tr_koefisien_sensor_non_vwp t2 ON t2.tr_instrument_id = t1.id
                INNER JOIN sys_jenis_sensor t3 ON t2.jenis_sensor_jadi = t3.id
                WHERE t1.ms_regions_id = {$ms_regions_id}
                AND t2.jenis_sensor_jadi IN ($select_data_str)
                AND t1.ms_stasiun_id = {$stasiun}
            ");
            $result = $query->result();

            if (!empty($data_tambah)) {
                $data_tambah_str = implode("','", $data_tambah);
                if (in_array('Tinggi Muka Air', $data_tambah)) {
                    $stasiun_dt_tambah = 'AWLR1';
                }

                if (in_array('Rainfall', $data_tambah)) {
                    $stasiun_dt_tambah = 'KLIM1';
                }

                $query = $this->db->query("
                    SELECT t1.kode_instrument,t1.nama_instrument, t3.id as sensor_id, t3.jenis_sensor, t3.unit_sensor
                    FROM tr_instrument t1
                    INNER JOIN tr_koefisien_sensor_non_vwp t2 ON t2.tr_instrument_id = t1.id
                    INNER JOIN sys_jenis_sensor t3 ON t2.jenis_sensor_jadi = t3.id
                    WHERE t1.ms_regions_id = {$ms_regions_id}
                    AND t3.jenis_sensor IN ('" . $data_tambah_str . "')
                    AND t1.kode_instrument LIKE '%{$stasiun_dt_tambah}%'
                ");


                $dt_result = $query->result();

                // $select_data = array();
                foreach ($dt_result as $row) {
                    $result[] = (object) array(
                        'nama_instrument' => $row->nama_instrument,
                        'kode_instrument' => $row->kode_instrument,
                        'sensor_id' => $row->sensor_id,
                        'jenis_sensor' => $row->jenis_sensor,
                        'unit_sensor' => $row->unit_sensor
                    );
                }
            }
            foreach ($result as $row) {

                $detail = [];

                if ($periode == 'Jam') {
                    $ddt = "AND t1.tanggal = '$waktu'
                            AND t1.jam LIKE '%:00:00'";
                    $kt = !empty($tipe_data) ? "AND t1.keterangan = '$tipe_data'" : "";

                    $query = $db_site->query("
                        SELECT t1.tanggal, t1.jam, FORMAT(t2.data_jadi, 2) as data_jadi
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

                $row->detail = $detail;
            }
            // pre($result);
            echo json_encode(['error' => false, 'data' => $result, 'periode' => $periode]);
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
