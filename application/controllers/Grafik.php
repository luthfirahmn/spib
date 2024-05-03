<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Grafik extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->dbutil();
        $this->load->database();
        $this->load->model('M_akses');
        $this->load->model('M_grafik');
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
}