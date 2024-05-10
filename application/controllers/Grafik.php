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
