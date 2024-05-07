<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Role extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_role');
		$this->load->dbutil();
		$this->load->database();
	}
	
	public function index(){
		$data['role']=$this->M_role->role();
		$data['akses']=$this->M_role->accesscontrols('1');
		$this->load->view('role/index', $data);
	}

	public function edit_proses(){
		$ms_roles_id	= $this->input->post('ms_roles_id');
		$akses			= $this->M_role->accesscontrols($ms_roles_id);
		$view			= $this->input->post('view');
		$insert			= $this->input->post('insert');
		$update			= $this->input->post('update');
		$delete			= $this->input->post('delete');

		foreach($akses as $rec){
			$body=array(
				'view' 		=> (isset($view[$rec->id])) ? 1 : 0,
				'insert' 	=> (isset($insert[$rec->id])) ? 1 : 0,
				'update'	=> (isset($update[$rec->id])) ? 1 : 0,
				'delete'	=> (isset($delete[$rec->id])) ? 1 : 0
			);

			$this->db->where('id', $rec->id);
			$this->db->update('ms_accesscontrols', $body);
		}
		
		redirect('Role');
	}

	public function detail(){
		$ms_roles_id	= $this->input->post('ms_roles_id');
		$data['akses']	= $this->M_role->accesscontrols($ms_roles_id);

		$tagihan=array(
			'rc'		=>'00',
			'err_desc'	=>'Sukses',					
			'tabel'		=> $this->load->view('role/data', $data, true)
		);

		echo json_encode($tagihan);
	}

	// public function tambah(){
	// 	$this->load->view('region/tambah');
	// }
	
	public function tambah_proses(){
		$role_name 	= $this->input->post('nama_role');
		$body=array(
			'role_name' 	=> $role_name,
			'status' 		=> $this->input->post('status'),
			'created_by'	=> $this->session->userdata('ap_nama'),
			'created_at'	=> date("Y-m-d h:i:s"),
			'updated_by'	=> $this->session->userdata('ap_nama'),
			'updated_at'	=> date("Y-m-d h:i:s")
		);

		$status=$this->M_role->simpan($body, $role_name);
		if($status){
			$this->session->set_flashdata('success', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		
		redirect('Role');
	}

	// public function edit(){
	// 	$id=$this->input->get('id');
	// 	$data['regional']=$this->M_role->detail_regional($id);
	// 	$this->load->view('region/edit', $data);
	// }

	

	// function hapus(){
	// 	$id = $this->input->get('id');
	// 	$this->db->delete('ms_regions', array('id' => $id));
	// 	$this->session->set_flashdata('success', 'Sukses!');
	// 	redirect('Region');
	// }
}