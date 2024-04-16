<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main_menu extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
	}
	
	public function index(){
		// $data['menu'] = $this->db->query("select * from ms_menus where parent='0'")->result();
		// $data['submenu'] = $this->db->query("select * from ms_menus where parent<>'0'")->result();
		// $this->load->view('main_menu', $data);

		$this->load->view('main_menu');
	}
}