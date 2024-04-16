<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Data extends MY_Controller
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


		$this->load->view('data/index', $data);
	}

	public function list()
	{
		$site_id = $this->input->post("ms_regions_id");
		$instrument_id = $this->input->post("instrument_id");

		$db_site = $this->change_connection($site_id);

		$data = $this->M_data->list($db_site, $instrument_id);

		if (empty($data)) {
			$columns = "";
		} else {

			$columns = array_keys((array) reset($data));
		}

		$result = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => count($data),
			"recordsFiltered" => count($data),
			"columns" => $columns,
			"data" => $data
		);

		// Output the response as JSON
		echo json_encode($result);
	}

	public function instrument()
	{
		$ms_regions_id  = $this->input->get('ms_regions_id');
		echo $this->M_data->instrument($ms_regions_id);
	}

	public function sensor()
	{
		$instrument_id  = $this->input->get('instrument_id');
		echo $this->M_data->get_sensor_by_instrument_id($instrument_id);
	}


	public function add_data()
	{
		$add_instrument = $this->input->post('add_instrument');
		$add_site = $this->input->post('add_site');
		$add_tanggal = $this->input->post('add_tanggal');
		$add_jam = $this->input->post('add_jam');
		$kode_instrument = $this->db->get_where("tr_instrument", array('id' => $add_instrument))->row()->kode_instrument;
		$db_site = $this->change_connection($add_site);


		$db_site->trans_begin();
		try {
			if ($db_site) {
				$data = array(
					'kode_instrument' => $kode_instrument,
					'tanggal' => $add_tanggal,
					'jam' => $add_jam,
					'keterangan' => "MANUAL",
					"created_by" => $this->session->userdata('ap_id_user'),
					"updated_by" => $this->session->userdata('ap_id_user'),
				);

				$db_site->insert("data", $data);
				$last_id = $db_site->insert_id();

				if ($last_id < 1) {
					throw new Exception("Data gagal di input");
				}

				$hitung_sensor = $this->input->post('hitung_sensor');

				foreach ($hitung_sensor as $sensor) {
					$data_id = explode('_', $sensor['id']);
					$sensor_id = $data_id[2];
					$flag = $data_id[3];

					if ($flag == 'jadi') {
						$data_primer = "NULL";
						$data_jadi = $sensor['value'];
					} else {
						$data_primer = $sensor['value'];
						$data_jadi = "NULL";
					}

					$data_value = array(
						'data_id' => $last_id,
						'sensor_id' => $sensor_id,
						'data_primer' => $data_primer,
						'data_jadi' => $data_jadi,
						"created_by" => $this->session->userdata('ap_id_user'),
						"updated_by" => $this->session->userdata('ap_id_user'),
					);
					$insert_data_value = $db_site->insert("data_value", $data_value);

					if (!$insert_data_value) {

						throw new Exception("Gagal insert data value");
					}
				}

				$db_site->trans_commit();

				echo json_encode(["error" => false, "message" => "data berhasil diinput"]);
			} else {
				throw new Exception("Koneksi db gagal");
			}
		} catch (Exception $e) {
			echo json_encode(["error" => true, "message" => $e->getMessage()]);
		}
	}


	public function change_connection($id_regions)
	{

		$query = $this->db->query("SELECT * FROM ms_regions WHERE id = '$id_regions'");
		$result = $query->row();

		$second_db_params = $this->switchDatabase($result->database_host, $result->database_username, $result->database_password, $result->database_name, $result->database_port);
		$this->db2 = $this->load->database($second_db_params, TRUE);

		if ($this->db2->initialize()) {
			return $this->db2;
		} else {
			return false;
		}
	}
}