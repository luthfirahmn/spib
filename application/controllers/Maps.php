<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Maps extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->dbutil();
        $this->load->database();
        $this->load->model('M_akses');
        $this->load->model('M_station');
    }


    public function index()
    {


        $roles_id = $this->session->userdata('roles_id');
        $ap_id_user = $this->session->userdata('ap_id_user');
        $data['hak_akses'] = $this->M_akses->hak_akses($roles_id, 'Maps');
        $data['station'] = $this->M_station->station($ap_id_user);
        $this->load->view('maps/index', $data);
    }
}