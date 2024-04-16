<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dokumen extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_dokumen');
		$this->load->model('M_akses');
		$this->load->dbutil();
		$this->load->database();
		date_default_timezone_set("Asia/Jakarta");
	}
	
	public function index(){
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['hak_akses']=$this->M_akses->hak_akses($roles_id,'Dokumen');
		$data['dokumen']=$this->M_dokumen->dokumen($ap_id_user);
		$this->load->view('dokumen/index', $data);
	}

	public function tambah(){
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$this->db->query('truncate table files_temp');
		$data['kategori']=$this->M_dokumen->kategori();
		$data['region']=$this->M_dokumen->region($ap_id_user);
		$this->load->view('dokumen/tambah', $data);
	}

	public function imageUploadPost(){
		$config['upload_path']   = FCPATH.'/assets/upload/';
		$config['allowed_types'] = 'gif|jpg|png|docx|pdf|xlsx|pptx'; 
		$config['max_size']      = 1024;

		$this->load->library('upload', $config);
		if ($this->upload->do_upload('file')) {
			$data =  $this->upload->data();

			$body=array(
				'name' => $data['file_name']
			);

			$this->db->insert('files_temp', $body);
		}


		print_r('Image Uploaded Successfully.');
        exit;
	}
	
	public function tambah_proses(){	
		$body=array(
			'ms_regions_id' 	=> $this->input->post('ms_regions_id'),
			'title' 			=> $this->input->post('title'),
			'date'				=> $this->input->post('date'),
			'category_id'		=> $this->input->post('category_id'),
			'description'		=> $this->input->post('description'),
			'created_by'		=> $this->session->userdata('ap_nama'),
			'created_at'		=> date("Y-m-d h:i:s")
		);

		$status=$this->M_dokumen->simpan($body);
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		
		redirect('Dokumen');
	}

	public function edit(){
		$ap_id_user = $this->session->userdata('ap_id_user');
		$id = $this->input->get('id');
		$this->db->query('truncate table files_temp');
		$data['kategori']=$this->M_dokumen->kategori();
		$data['region']=$this->M_dokumen->region($ap_id_user);
		$data['dokumen']=$this->M_dokumen->dokumen_detail($id);
		$this->load->view('dokumen/edit', $data);
	}

	public function edit_proses(){
		$id = $this->input->post('id');
		$dokumen=$this->M_dokumen->dokumen_detail($id);

		$this->M_dokumen->hapus($id);

		$body=array(
			'ms_regions_id' 	=> $this->input->post('ms_regions_id'),
			'title' 			=> $this->input->post('title'),
			'date'				=> $this->input->post('date'),
			'category_id'		=> $this->input->post('category_id'),
			'description'		=> $this->input->post('description'),
			'created_by'		=> $dokumen->created_by,
			'created_at'		=> $dokumen->created_at,
			'updated_by'		=> $this->session->userdata('ap_nama'),
			'updated_at'		=> date("Y-m-d h:i:s")
		);

		$status=$this->M_dokumen->simpan($body);
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		
		redirect('Dokumen');
	}

	function hapus(){
		$id = $this->input->get('id');
		$status=$this->M_dokumen->hapus($id);
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		
		redirect('Dokumen');
	}

	public function createzip(){
		$this->load->library('zip');
		$id = $this->input->get('id');
		$dokumen=$this->M_dokumen->dokumen_detail($id);

		$query = $this->M_dokumen->get_files($id);
  
		foreach ($query as $row){
			$fileName = FCPATH.'/assets/upload/'.$row->name;
			$this->zip->read_file($fileName);
		}
  
		$this->zip->download($dokumen->title.'.zip');
   }
}