<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Station extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_station');
		$this->load->model('M_akses');
		$this->load->dbutil();
		$this->load->database();
	}
	
	public function index(){
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['hak_akses']=$this->M_akses->hak_akses($roles_id,'Station');
		//$data['station']=$this->M_station->station($ap_id_user);
		$data['region']=$this->M_station->region($ap_id_user);
		$this->load->view('station/index', $data);
	}

	public function list()
	{
		$ap_id_user 	= $this->session->userdata('ap_id_user');
		$site_id 		= $this->input->post("ms_regions_id");
		$roles_id 		= $this->session->userdata('roles_id');
		$data['station'] = $this->M_station->station($ap_id_user, $site_id);
		$data['hak_akses']=$this->M_akses->hak_akses($roles_id,'Station');

		$list=array(
			'rc'		=>'00',
			'err_desc'	=>'Sukses',
			'tabel'		=> $this->load->view('station/tabel', $data, true)
		);

		echo json_encode($list);
	}

	public function tambah(){
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['region']=$this->M_station->region($ap_id_user);
		$this->load->view('station/tambah', $data);
	}

	
	public function tambah_proses(){
		$temp = FCPATH.'/assets/upload/station/';
		
		$nama_file       = $this->input->post('nama_stasiun');
		$fileupload      = $_FILES['foto']['tmp_name'];
		$ImageName       = $_FILES['foto']['name'];
		$ImageType       = $_FILES['foto']['type'];
		
		if (!empty($fileupload)){
			$ImageExt       = substr($ImageName, strrpos($ImageName, '.'));
			$ImageExt       = str_replace('.','',$ImageExt); // Extension
			$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
			$NewImageName   = str_replace(' ', '', $nama_file.'.'.$ImageExt);
		
			move_uploaded_file($_FILES["foto"]["tmp_name"], $temp.$NewImageName); // Menyimpan file
		
			$foto=$NewImageName;
		} else {
			$foto='';
		}

		$body=array(
			'ms_regions_id' 		=> $this->input->post('ms_regions_id'),
			'nama_stasiun' 			=> $this->input->post('nama_stasiun'),
			'foto'					=> $foto,
			'wilayah_sungai'		=> $this->input->post('wilayah_sungai'),
			'daerah_aliran_sungai'	=> $this->input->post('daerah_aliran_sungai'),
			'latitude'				=> $this->input->post('latitude'),
			'longitude'				=> $this->input->post('longitude'),
			'komunikasi'			=> $this->input->post('komunikasi'),
			'kontak_gsm'			=> $this->input->post('kontak_gsm'),
			'alamat_ip'				=> $this->input->post('alamat_ip'),
			'tahun_pembuatan'		=> $this->input->post('tahun_pembuatan'),
			'elevasi'				=> $this->input->post('elevasi')
		);

		$status=$this->db->insert('ms_stasiun', $body);
		if($status){
			$this->session->set_flashdata('success', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		redirect('Station');
	}

	public function edit(){
		$id = $this->input->get('id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['region']=$this->M_station->region($ap_id_user);
		$data['station']=$this->M_station->station_detail($id);
		$this->load->view('station/edit', $data);
	}

	public function edit_proses(){
		$temp = FCPATH.'/assets/upload/station/';
		
		$nama_file       = $this->input->post('nama_stasiun');
		$fileupload      = $_FILES['foto']['tmp_name'];
		$ImageName       = $_FILES['foto']['name'];
		$ImageType       = $_FILES['foto']['type'];
		
		if (!empty($fileupload)){
			$ImageExt       = substr($ImageName, strrpos($ImageName, '.'));
			$ImageExt       = str_replace('.','',$ImageExt); // Extension
			$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
			$NewImageName   = str_replace(' ', '', $nama_file.'.'.$ImageExt);

			if(file_exists($temp.$NewImageName)) {
				unlink($temp.$NewImageName); //remove the file
			}
			move_uploaded_file($_FILES["foto"]["tmp_name"], $temp.$NewImageName); // Menyimpan file
		
			$foto=$NewImageName;
		} else {
			$foto='';
		}

		$id=$this->input->post('id');
		$body=array(
			'ms_regions_id' 		=> $this->input->post('ms_regions_id'),
			'nama_stasiun' 			=> $this->input->post('nama_stasiun'),
			'wilayah_sungai'		=> $this->input->post('wilayah_sungai'),
			'daerah_aliran_sungai'	=> $this->input->post('daerah_aliran_sungai'),
			'latitude'				=> $this->input->post('latitude'),
			'longitude'				=> $this->input->post('longitude'),
			'komunikasi'			=> $this->input->post('komunikasi'),
			'kontak_gsm'			=> $this->input->post('kontak_gsm'),
			'alamat_ip'				=> $this->input->post('alamat_ip'),
			'tahun_pembuatan'		=> $this->input->post('tahun_pembuatan'),
			'elevasi'				=> $this->input->post('elevasi')
		);

		$this->db->where('id', $id);
		$status=$this->db->update('ms_stasiun', $body);
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		redirect('Station');
	}

	function hapus(){
		$id = $this->input->get('id');
		$status=$this->db->delete('ms_stasiun', array('id' => $id));;
		if($status){
			$this->session->set_flashdata('warning', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		
		redirect('Station');
	}

}