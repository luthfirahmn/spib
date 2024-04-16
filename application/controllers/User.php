<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_user');
		$this->load->model('M_akses');
		$this->load->dbutil();
		$this->load->database();
	}
	
	public function index(){
		$roles_id = $this->session->userdata('roles_id');
		$data['hak_akses']=$this->M_akses->hak_akses($roles_id,'User');
		$data['user']=$this->M_user->list_user();
		$this->load->view('user/index', $data);
	}

	public function tambah(){
		$data['role']=$this->M_user->role();
		$data['region']=$this->M_user->region();
		$this->load->view('user/tambah', $data);
	}
	
	public function tambah_proses(){
		$username	= $this->input->post('username');
		$email		= $this->input->post('email');
		$site		= $this->input->post('ms_regions_id');
		$cek_data	=$this->M_user->cek_data($username, $email);

		if($cek_data){
			$notif='Username/email sudah ada!';
		}else if(!empty($site)){
			$temp = FCPATH.'/assets/upload/';
		
			$nama_file       = $this->input->post('username');
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
					'ms_roles_id' 	=> $this->input->post('ms_roles_id'),
					'nama' 			=> $this->input->post('nama'),
					'username'		=> $this->input->post('username'),
					'password'		=> sha1($this->input->post('password')),
					'email'			=> $this->input->post('email'),
					'jabatan'		=> $this->input->post('jabatan'),
					'no_telp'		=> $this->input->post('no_telp'),
					'dinas'			=> $this->input->post('dinas'),
					'foto'			=> $foto
				);
		
				
				$status=$this->M_user->simpan($body, $site);
				if($status){
					$notif='Sukses';
				}else{
					$notif='Gagal';
				}
			}else{
				$notif='Harap Pilih Site!';
			}
			echo $notif;
		//redirect('User');
	}

	public function edit(){
		$id=$this->input->get('id');
		$data['user']=$this->M_user->detail_user($id);
		$data['role']=$this->M_user->role();
		$data['region']=$this->M_user->region_detail($id);
		$this->load->view('user/edit', $data);
	}

	public function edit_proses(){
		// $config['upload_path'] = FCPATH.'/assets/upload/';
        // $config['allowed_types'] = 'gif|jpg|png';
        // $config['max_size'] = 2000;
  
  
        // $this->load->library('upload', $config);
  
        // if (!$this->upload->do_upload('foto')) {
        //     $foto='';
        // } else {
        //     $data =  $this->upload->data();
        //     $foto=$data['file_name'];
        // }

		// $site=$this->input->post('ms_regions_id');
		// $id_user=$this->input->post('id_user');
		
		// $user=$this->db->query("select password from ms_users where id='$id_user'")->row();
		// $password=$user->password;

		// $body=array(
		// 	'ms_roles_id' 	=> $this->input->post('ms_roles_id'),
		// 	'nama' 			=> $this->input->post('nama'),
		// 	'username'		=> $this->input->post('username'),
		// 	'password'		=> $password,
		// 	'email'			=> $this->input->post('email'),
		// 	'jabatan'		=> $this->input->post('jabatan'),
		// 	'no_telp'		=> $this->input->post('no_telp'),
		// 	'dinas'			=> $this->input->post('dinas'),
		// 	'foto'			=> $foto
		// );

		

		// $status=$this->M_user->edit($body, $site, $id_user);
		// if($status){
		// 	$notif='Sukses';
		// }else{
		// 	$notif='Gagal';
		// }
		
		// echo $notif;







		$site		= $this->input->post('ms_regions_id');
		$id_user	=$this->input->post('id_user');
		$user		=$this->db->query("select password, foto from ms_users where id='$id_user'")->row();
		$password	=$user->password;

		if(!empty($site)){
			$temp = FCPATH.'/assets/upload/';
		
			$nama_file       = $this->input->post('username');
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
				$foto=$user->foto;
			}

			$body=array(
				'id'			=> $id_user,
				'ms_roles_id' 	=> $this->input->post('ms_roles_id'),
				'nama' 			=> $this->input->post('nama'),
				'username'		=> $this->input->post('username'),
				'password'		=> $password,
				'email'			=> $this->input->post('email'),
				'jabatan'		=> $this->input->post('jabatan'),
				'no_telp'		=> $this->input->post('no_telp'),
				'dinas'			=> $this->input->post('dinas'),
				'foto'			=> $foto
			);
	
			
	
			$status=$this->M_user->edit($body, $site, $id_user);
				if($status){
					$notif='Sukses';
				}else{
					$notif='Gagal';
				}
		}else{
			$notif='Harap Pilih Site!';
		}
			echo $notif;
	}

	function hapus(){
		$id = $this->input->get('id');
		$status=$this->M_user->hapus($id);
		if($status){
			$this->session->set_flashdata('success', 'Sukses!');
		}else{
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		
		redirect('User');
	}
}