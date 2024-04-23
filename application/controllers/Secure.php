<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Secure extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_param');
	}

	public function index()
	{
		if ($this->input->is_ajax_request()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[40]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|max_length[40]');
			$this->form_validation->set_message('required', '%s harus diisi !');

			if ($this->form_validation->run() == TRUE) {
				$username 	= $this->input->post('username');
				$password	= $this->input->post('password');

				$this->load->model('m_user');
				$validasi_login = $this->m_user->validasi_login($username, $password);



				if ($validasi_login->num_rows() > 0) {

					$data_user 	= $validasi_login->row();
					$menu 		= $this->m_user->menu($data_user->ms_roles_id);
					$submenu 	= $this->m_user->submenu($data_user->ms_roles_id);

					$list_region = $this->m_user->list_region($data_user->id);

					$session = array(
						'ap_id_user' 	=> $data_user->id,
						'ap_password' 	=> $data_user->password,
						'ap_nama' 		=> $data_user->nama,
						'menu'			=> $menu,
						'submenu'		=> $submenu,
						'roles_id'		=> $data_user->ms_roles_id,
						'list_region'	=> $list_region,
						'foto'			=> $data_user->foto,
						'jabatan'		=> $data_user->jabatan
					);
					$this->session->set_userdata($session);

					$URL_home = site_url('Dashboard');

					$json['status']		= 1;
					$json['url_home'] 	= $URL_home;
					echo json_encode($json);
				} else {
					$this->query_error("Login Gagal, Cek Kombinasi Username & Password !");
				}
			} else {
				$this->input_error();
			}
		} else {
			$this->load->view('login_page');
		}
	}


	function logout()
	{
		$this->session->unset_userdata('ap_id_user');
		$this->session->unset_userdata('ap_password');
		$this->session->unset_userdata('ap_nama');
		$this->session->unset_userdata('ap_level');
		$this->session->unset_userdata('ap_level_caption');
		redirect();
	}
}