<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CCTV extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_data');
		$this->load->model('M_akses');
		$this->load->dbutil();
		$this->load->database();
	}

	public function index()
	{
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['hak_akses'] = $this->M_akses->hak_akses($roles_id, 'Data');
		$data['region'] = $this->M_data->region($ap_id_user);


		$this->load->view('cctv/index', $data);
	}

	public function list($region_id)
	{
		try {
			$query = $this->db->query("SELECT * FROM ms_cctv WHERE ms_regions_id = '$region_id' ORDER BY id DESC");
			$result = $query->result();

			if (empty($result)) {
				echo json_encode(['error' => true, 'message' => 'Tidak Ada Data.']);
			} else {
				echo json_encode(['error' => false, 'data' => $result]);
			}
		} catch (Exception $e) {
			echo json_encode(['error' => true, 'message' => $e->getMessage()]);
		}
	}

	public function add_data()
	{
		// Load form validation library
		$this->load->library('form_validation');

		// Set validation rules
		$this->form_validation->set_rules('add_regions_id', 'Site', 'required');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required');
		$this->form_validation->set_rules('url', 'URL', 'trim|required');

		// Run validation
		if ($this->form_validation->run() == FALSE) {
			// Validation failed, return errors
			$errors = validation_errors();
			echo json_encode(array('error' => true, 'message' => $errors));
		} else {
			$data = array(
				'ms_regions_id' => $this->input->post('add_regions_id'),
				'lokasi' => $this->input->post('lokasi'),
				'url' => $this->input->post('url')
			);
			$inserted = $this->db->insert("ms_cctv", $data);
			if ($inserted) {
				echo json_encode(array('error' => false, 'message' => 'Data cctv berhasil ditambahkan'));
			} else {
				echo json_encode(array('error' => true, 'message' => 'Gagal menambahkan data cctv'));
			}
		}
	}



	function redirecting($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		echo $data;
	}


	public function delete_data($id)
	{
		try {
			$this->db->where('id', $id);
			$deleted = $this->db->delete("ms_cctv");

			if (!$deleted) {
				throw new Exception("Gagal menghapus data");
			}

			echo json_encode(array('error' => false, 'message' => 'Data berhasil dihapus'));
		} catch (Exception $e) {

			echo json_encode(array('error' => true, 'message' => $e->getMessage()));
		}
	}
}
