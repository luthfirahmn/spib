<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Koneksi extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->database();
	}
	
	public function index(){
		$hostname	= $this->input->post('database_host');
		$username	= $this->input->post('database_username');
		$password	= $this->input->post('database_password');
		$database	= $this->input->post('database_name');
		$port		= $this->input->post('database_port');

		
		$second_db_params = $this->switchDatabase($hostname, $username, $password, $database, $port);
		$this->db2 = $this->load->database($second_db_params, TRUE);

		if ($this->db2->initialize()) {
            $hasil=array(
				'rc'		=>'00',
				'err_desc'	=>'Database connection established'
			);
        } else {
            $hasil=array(
				'rc'		=>'99',
				'err_desc'	=>'Unable to connect to the database.'
			);
        }
		
		echo json_encode($hasil);
	}

	
}