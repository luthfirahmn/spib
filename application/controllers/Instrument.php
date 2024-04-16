<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Instrument extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_instrument');
		$this->load->model('M_akses');
		$this->load->dbutil();
		$this->load->database();
	}
	
	public function index(){
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['hak_akses']=$this->M_akses->hak_akses($roles_id,'Instrument');
		$data['instrument']=$this->M_instrument->instrument($ap_id_user);
		$this->load->view('instrument/index', $data);
	}

	public function tambah(){
		$this->db->query('truncate table files_temp_instrument');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['region']=$this->M_instrument->region($ap_id_user);
		$data['type']=$this->M_instrument->type();
		$this->load->view('instrument/tambah', $data);
	}

	
	public function tambah_proses(){
		$site = $this->input->post('ms_regions_id');
		$body=array(
			'name' 	=> $this->input->post('name'),
			'type'	=> $this->input->post('type')
		);

		$status=$this->M_instrument->simpan($body, $site);
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		$this->db->query('truncate table files_temp_instrument');
		redirect('Instrument');
	}

	public function edit(){
		$id = $this->input->get('id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['instrument']=$this->M_instrument->instrument_detail($id);
		$data['type']=$this->M_instrument->type();
		$data['region']=$this->M_instrument->region_detail($id);
		$this->load->view('instrument/edit', $data);
	}

	public function edit_proses(){
		$id = $this->input->post('id');
		$site = $this->input->post('ms_regions_id');
		$body=array(
			'id'	=> $id,
			'name' 	=> $this->input->post('name'),
			'type'	=> $this->input->post('type')
		);

		$status=$this->M_instrument->edit($body, $site, $id);

		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		$this->db->query('truncate table files_temp_instrument');
		redirect('Instrument');
	}

	function hapus(){
		$id = $this->input->get('id');
		$status=$this->M_instrument->hapus($id);
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal! (Cannot delete or update a parent row: a foreign key constraint fails )');
		}
		
		redirect('Instrument');
	}

	public function createzip(){
		$this->load->library('zip');
		$id = $this->input->get('id');
		$query = $this->M_instrument->get_files($id);
  
		foreach ($query as $row){
			$fileName = FCPATH.'/assets/upload/instruments_files/'.$row->name;
			$this->zip->read_file($fileName);
		}
  
		$this->zip->download('arsip.zip');
   	}

   	public function parameter()
	{
		$tr_instrument_parameter = $this->input->get('id');
		
		echo $this->M_instrument->parameter($tr_instrument_parameter);
	}

	public function simpan_parameter(){
		$tr_instrument_type_id = $this->input->post('idInstrument');
		$nama_parameter = str_replace(" ", "-", $this->input->post('nama_parameter'));
		
		$status=$this->M_instrument->simpan_parameter($tr_instrument_type_id, $nama_parameter);

		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		$this->db->query('truncate table files_temp_instrument');
		redirect('Instrument');
	}
}