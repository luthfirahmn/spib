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
		$keterangan = $this->input->post("keterangan");
		$tanggal = $this->input->post("tanggal");
		$waktu = $this->input->post("waktu");

		$db_site = $this->change_connection($site_id);

		$data = $this->M_data->list($db_site, $instrument_id, $keterangan, $tanggal, $waktu);

		if (empty($data)) {
			$columns = "";
		} else {

			$columns = array_keys((array) reset($data));
		}
		$result = array(
			// "draw" => $_POST['draw'],
			"recordsTotal" => count($data),
			"recordsFiltered" => count($data),
			"columns" => $columns,
			"data" => $data
		);

		// Output the response as JSON
		echo json_encode($result);
	}


	public function download_all()
	{
		$site_id = $this->input->get("ms_regions_id");
		$instrument_id = $this->input->get("instrument_id");
		$keterangan = $this->input->get("keterangan");
		$tanggal = $this->input->get("tanggal");
		$waktu = $this->input->post("waktu");

		// Ganti koneksi database berdasarkan $site_id
		$db_site = $this->change_connection($site_id);

		// Ambil data dari model berdasarkan parameter yang diberikan
		$data = $this->M_data->list($db_site, $instrument_id, $keterangan, $tanggal, $waktu, $download = 1);

		// Load library PHPExcel
		$this->load->library('PHPExcel');

		// Buat objek PHPExcel
		$objPHPExcel = new PHPExcel();

		// Set properti
		$objPHPExcel->getProperties()->setCreator("Your Name")
			->setLastModifiedBy("Your Name")
			->setTitle("Data Export")
			->setSubject("Data Export")
			->setDescription("Data Export")
			->setKeywords("data")
			->setCategory("Data");

		// Buat worksheet baru
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();

		// Tambahkan header
		$columns = array_keys((array) reset($data));
		foreach ($columns as $key => $column) {
			$sheet->setCellValueByColumnAndRow($key, 1, $column);
		}

		// Tambahkan data
		$row = 2;
		foreach ($data as $item) {
			$col = 0;
			foreach ($item as $value) {
				$sheet->setCellValueByColumnAndRow($col, $row, $value);
				$col++;
			}
			$row++;
		}


		// Save Excel file to a temporary location
		$tempFilePath = FCPATH . 'assets/temp/report_data.xlsx';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($tempFilePath);
		// Return the path to the Excel file
		echo base_url('assets/temp/report_data.xlsx');
	}


	public function delete_data()
	{
		$instrument_id = $this->input->post('instrument_id');
		$ms_regions_id = $this->input->post('ms_regions_id');
		$keterangan = $this->input->post('keterangan');
		$tanggal = $this->input->post('tanggal');
		$waktu = $this->input->post("waktu");

		$kode_instrument = $this->db->get_where("tr_instrument", array('id' => $instrument_id))->row()->kode_instrument;
		try {
			$db_site = $this->change_connection($ms_regions_id);

			$db_site->trans_start();
			if (!empty($tanggal)) {
				if ($waktu == 'jam') {
					$ddt = "AND t1.tanggal = '$tanggal'";
				} else {
					$ddt = "AND DATE_FORMAT(t1.tanggal, '%Y-%m') = '$tanggal'";
				}
			} else {
				$ddt = "";
			}

			if (!empty($keterangan)) {
				$kt = "AND t1.keterangan = '$keterangan'";
			} else {
				$kt = "";
			}

			$query = $db_site->query("SELECT id FROM data  WHERE kode_instrument = '$kode_instrument' AND {$kt} {$ddt}");
			$result = $query->result();
			if (!empty($result)) {
				foreach ($result as $row) {

					$db_site->query("DELETE FROM data_value WHERE data_id = '$row->id'");

					$db_site->query("DELETE FROM data WHERE id = '$row->id'");
				}
			}

			$db_site->trans_complete();

			if ($db_site->trans_status() === FALSE) {
				throw new Exception('Gagal menyelesaikan transaksi.');
			}

			$response['error'] = false;
			$response['message'] = 'Data berhasil dihapus.';
		} catch (Exception $e) {
			$response['error'] = true;
			$response['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
		}

		echo json_encode($response);
	}

	public function instrument()
	{
		$ms_stasiun_id  = $this->input->get('ms_stasiun_id');
		echo $this->M_data->instrument($ms_stasiun_id);
	}

	public function stasiun()
	{
		$ms_regions_id  = $this->input->get('ms_regions_id');
		echo $this->M_data->stasiun($ms_regions_id);
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
		$nama_instrument = $this->db->get_where("tr_instrument", array('id' => $add_instrument))->row()->nama_instrument;
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

					$sensor_id = $data_id[1];

					$data_primer = $sensor['value'];

					switch ($nama_instrument) {
						case "Pressure":
							$data_jadi = $data_primer / 100;
							break;
						case "Hall Effect":
							$derajat_angin = $data_primer;

							$data_jadi = $derajat_angin;
							break;
						default:
							$data_jadi = $data_primer;
							break;
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


	public function download_template($id_instrument)
	{

		$query = $this->db->query("SELECT 
										t1.jenis_sensor
									FROM sys_jenis_sensor t1
									WHERE t1.id IN (SELECT t2.jenis_sensor_mentah
													FROM tr_koefisien_sensor_non_vwp  t2
													WHERE t2.jenis_sensor_mentah = t1.id
													AND t2.tr_instrument_id = '$id_instrument')
									ORDER BY t1.id ASC
									");
		$this->load->library('PHPExcel');

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('Data');

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'No');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Tanggal');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Jam');

		$col = 3;
		foreach ($query->result_array() as $row_data) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $row_data['jenis_sensor']);
			$col++;
		}

		$row = 2;
		$col = 0;
		$no = 1;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $no);
		$col++;

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, date('Y-m-d'));
		$col++;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, '00:00:00');
		$col++;

		foreach ($query->result_array() as $sensor_data) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
			$col++;
		}

		$row++;
		$no++;
		$col = 0;

		$get_instrument = $this->db->get_where("tr_instrument", array('id' => $id_instrument))->row();
		$get_stasiun = $this->db->get_where("ms_stasiun", array('id' => $get_instrument->ms_stasiun_id))->row()->nama_stasiun;
		$get_site = $this->db->get_where("ms_regions", array('id' => $get_instrument->ms_regions_id))->row()->site_name;

		$nama_file = $get_site . '_' . $get_stasiun . '_' . $get_instrument->nama_instrument . '.xlsx';
		$tempFilePath = FCPATH . 'assets/temp/' . $nama_file;
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($tempFilePath);
		echo base_url('assets/temp/' . $nama_file);
	}


	public function proses_upload_data()
	{
		// Load library PHPExcel
		$this->load->library('PHPExcel');

		// Konfigurasi upload
		$config['upload_path'] =  FCPATH . 'assets/temp/';
		$config['allowed_types'] = 'xlsx|xls';
		$this->load->library('upload', $config);


		$site = $_POST['site'];
		$stasiun = $_POST['stasiun'];
		$instrument = $_POST['instrument'];

		if (!$this->upload->do_upload('file')) {
			$error = array('error' => true, 'message' => $this->upload->display_errors());
			echo json_encode($error);
		} else {
			$data = $this->upload->data();
			$file_path = $data['full_path'];

			$objPHPExcel = PHPExcel_IOFactory::load($file_path);

			$sheet = $objPHPExcel->getActiveSheet();



			$query = $this->db->query("SELECT 
										t1.id,
										(SELECT nama_instrument FROM tr_instrument WHERE id = '$instrument' LIMIT 1) nama_instrument,
										(SELECT kode_instrument FROM tr_instrument WHERE id = '$instrument' LIMIT 1) kode_instrument
									FROM sys_jenis_sensor t1
									WHERE t1.id IN (SELECT t2.jenis_sensor_mentah
													FROM tr_koefisien_sensor_non_vwp  t2
													WHERE t2.jenis_sensor_mentah = t1.id
													AND t2.tr_instrument_id = '$instrument')
									ORDER BY t1.id ASC
									");
			$jenis_sensor_ids = $query->result_array();

			// Array untuk menyimpan hasil
			$result = array();

			// Loop untuk membaca baris-baris data
			foreach ($sheet->getRowIterator() as $index => $row) {
				if ($index < 2) continue;

				$jenis_sensor_id = $jenis_sensor_ids[0]['id'];
				$nama_instrument = $jenis_sensor_ids[0]['nama_instrument'];
				$kode_instrument = $jenis_sensor_ids[0]['kode_instrument'];

				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				$data_row = array();

				foreach ($cellIterator as $cell) {
					$data_row[] = $cell->getValue();
				}

				$data_primer = $data_row[3];

				switch ($nama_instrument) {
					case "Pressure":
						$data_jadi = $data_primer / 100;
						break;
					case "Hall Effect":
						$derajat_angin = $data_primer;

						// if ($derajat_angin > 337.5 || $derajat_angin < 22.5) {
						// 	$arah_angin = "Utara";
						// } elseif ($derajat_angin >= 22.5 && $derajat_angin < 67.5) {
						// 	$arah_angin = "Timur Laut";
						// } elseif ($derajat_angin >= 67.5 && $derajat_angin < 112.5) {
						// 	$arah_angin = "Timur";
						// } elseif ($derajat_angin >= 112.5 && $derajat_angin < 157.5) {
						// 	$arah_angin = "Tenggara";
						// } elseif ($derajat_angin >= 157.5 && $derajat_angin < 202.5) {
						// 	$arah_angin = "Selatan";
						// } elseif ($derajat_angin >= 202.5 && $derajat_angin < 247.5) {
						// 	$arah_angin = "Barat Daya";
						// } elseif ($derajat_angin >= 247.5 && $derajat_angin < 292.5) {
						// 	$arah_angin = "Barat";
						// } elseif ($derajat_angin >= 292.5 && $derajat_angin < 337.5) {
						// 	$arah_angin = "Barat Laut";
						// }

						$data_jadi = $derajat_angin;
						break;
					default:
						$data_jadi = $data_primer;
						break;
				}


				$db_site = $this->change_connection($site);

				$data_array = array(
					"kode_instrument" => $kode_instrument,
					"tanggal" => $data_row[1],
					"jam" => $data_row[2],
					"keterangan" => "MANUAL",
					"created_by" => $this->session->userdata('ap_id_user'),
					"updated_by" => $this->session->userdata('ap_id_user'),
				);
				$query = $db_site->insert('data', $data_array);

				$last_insert_id = $db_site->insert_id();

				$sensor_data = array(
					'data_id' => $last_insert_id,
					'sensor_id' => $jenis_sensor_id,
					'data_primer' => $data_primer,
					'data_jadi' => $data_jadi,
					"created_by" => $this->session->userdata('ap_id_user'),
					"updated_by" => $this->session->userdata('ap_id_user'),
				);


				$query = $db_site->insert('data_value', $sensor_data);


				$result[] = $sensor_data;
			}

			unlink($file_path);

			echo json_encode(array('error' => false));
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
