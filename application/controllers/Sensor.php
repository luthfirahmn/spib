<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sensor extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_sensor');
		$this->load->model('M_akses');
		$this->load->dbutil();
		$this->load->database();
	}
	
	public function index(){
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['hak_akses']=$this->M_akses->hak_akses($roles_id,'Sensor');
		//$data['sensor']=$this->M_sensor->sensor($ap_id_user);
		$data['region']=$this->M_sensor->region($ap_id_user);
		$this->load->view('sensor/index', $data);
	}

	public function list()
	{
		$ap_id_user 	= $this->session->userdata('ap_id_user');
		$site_id 		= $this->input->post("ms_regions_id");
		$roles_id 		= $this->session->userdata('roles_id');
		$data['sensor'] = $this->M_sensor->sensor($ap_id_user, $site_id);
		$data['hak_akses']=$this->M_akses->hak_akses($roles_id,'Sensor');

		$list=array(
			'rc'		=>'00',
			'err_desc'	=>'Sukses',
			'tabel'		=> $this->load->view('sensor/tabel', $data, true)
		);

		echo json_encode($list);
	}

	public function tambah(){
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['region']=$this->M_sensor->region($ap_id_user);
		$this->load->view('sensor/tambah', $data);
	}
	
	public function tambah_proses(){
		$body=array(
			'ms_regions_id' => $this->input->post('ms_regions_id'),
			'jenis_sensor' 	=> $this->input->post('jenis_sensor'),
			'unit_sensor'	=> $this->input->post('unit_sensor'),
			'var_name'		=> $this->input->post('var_name')
		);

		$status=$this->M_sensor->simpan($body);
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		redirect('Sensor');
	}

	public function edit(){
		$id = $this->input->get('id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['region']=$this->M_sensor->region($ap_id_user);
		$data['sensor']=$this->M_sensor->sensor_detail($id);
		$this->load->view('sensor/edit', $data);
	}

	public function edit_proses(){
		$id = $this->input->post('id');

		$body=array(
			'ms_regions_id' => $this->input->post('ms_regions_id'),
			'jenis_sensor' 	=> $this->input->post('jenis_sensor'),
			'unit_sensor'	=> $this->input->post('unit_sensor'),
			'var_name'		=> $this->input->post('var_name')
		);

		$this->db->where('id', $id);
		$status=$this->db->update('sys_jenis_sensor', $body);

		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		redirect('Sensor');
	}

	function hapus(){
		$id = $this->input->get('id');
		$status=$this->db->delete('sys_jenis_sensor', array('id' => $id));;
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		
		redirect('Sensor');
	}

}