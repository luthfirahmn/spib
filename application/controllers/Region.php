<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Region extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_region');
		$this->load->dbutil();
		$this->load->database();
	}

	public function index()
	{
		$data['regional'] = $this->M_region->regional();
		$this->load->view('region/index', $data);
	}

	public function tambah()
	{
		$this->load->view('region/tambah');
	}

	public function tambah_proses()
	{
		$config['upload_path'] = FCPATH . '/assets/upload/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = 2000;


		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('logo_site')) {
			$logo_site = '';
		} else {
			$data =  $this->upload->data();
			$logo_site = $data['file_name'];
		}

		$body = array(
			'site_name' 		=> $this->input->post('site_name'),
			'database_name' 	=> $this->input->post('database_name'),
			'database_host'		=> $this->input->post('database_host'),
			'database_port'		=> $this->input->post('database_port'),
			'database_username'	=> $this->input->post('database_username'),
			'database_password'	=> $this->input->post('database_password'),
			'app_name'			=> $this->input->post('app_name'),
			'logo_site'			=> $data['file_name']
		);



		$status = $this->db->insert('ms_regions', $body);
		if ($status) {
			$region_id = $this->db->insert_id();

			$check_user = $this->db->query(
				"SELECT  t1.id
				FROM ms_users t1 
				WHERE t1.ms_roles_id = (SELECT id FROM ms_roles WHERE is_superadmin = 1 LIMIT 1) 
				AND t1.id NOT IN (SELECT ms_users_id FROM ms_user_regions WHERE ms_regions_id = {$region_id} AND ms_users_id = t1.id)"
			)->result();

			if ($check_user) {
				foreach ($check_user as $row) {
					$data_insert_user = array(
						'ms_users_id' => $row->id,
						'ms_regions_id' => $region_id,
					);
					$this->db->insert('ms_user_regions', $data_insert_user);
				}
			}


			$this->session->set_flashdata('success', 'Sukses!');
		} else {
			$this->session->set_flashdata('warning', 'Gagal!');
		}

		redirect('Region');
	}

	public function edit()
	{
		$id = $this->input->get('id');
		$data['regional'] = $this->M_region->detail_regional($id);
		$this->load->view('region/edit', $data);
	}

	public function edit_proses()
	{
		$id = $this->input->post('id');
		$body = array(
			'site_name' 		=> $this->input->post('site_name'),
			'database_name' 	=> $this->input->post('database_name'),
			'database_host'		=> $this->input->post('database_host'),
			'database_port'		=> $this->input->post('database_port'),
			'database_username'	=> $this->input->post('database_username'),
			'database_password'	=> $this->input->post('database_password'),
			'app_name'			=> $this->input->post('app_name')
		);

		$this->db->where('id', $id);
		$status = $this->db->update('ms_regions', $body);
		if ($status) {

			$check_user = $this->db->query(
				"SELECT  t1.id
				FROM ms_users t1 
				WHERE t1.ms_roles_id = (SELECT id FROM ms_roles WHERE is_superadmin = 1 LIMIT 1) 
				AND t1.id NOT IN (SELECT ms_users_id FROM ms_user_regions WHERE ms_regions_id = {$id} AND ms_users_id = t1.id)"
			)->result();

			if ($check_user) {
				foreach ($check_user as $row) {
					$data_insert_user = array(
						'ms_users_id' => $row->id,
						'ms_regions_id' => $id,
					);
					$this->db->insert('ms_user_regions', $data_insert_user);
				}
			}

			$this->session->set_flashdata('success', 'Sukses!');
		} else {
			$this->session->set_flashdata('warning', 'Gagal!');
		}

		redirect('Region');
	}

	function hapus()
	{
		$id = $this->input->get('id');
		$this->db->delete('ms_regions', array('id' => $id));
		$this->session->set_flashdata('success', 'Sukses!');
		redirect('Region');
	}
}
