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


	public function column()
	{

		$site_id = $this->input->post("ms_regions_id");
		$instrument_id = $this->input->post("instrument_id");
		$keterangan = $this->input->post("keterangan");
		$tanggal = $this->input->post("tanggal");
		$waktu = $this->input->post("waktu");

		$db_site = $this->change_connection($site_id);

		$data = $this->M_data->get_column($db_site, $instrument_id, $keterangan, $tanggal, $waktu, 1, 1);

		if (empty($data['data'])) {
			$columns = "";
		} else {
			$columns = array_keys((array) reset($data['data']));
		}

		echo json_encode($columns);
	}
	public function list()
	{
		$site_id = $this->input->post("ms_regions_id");
		$instrument_id = $this->input->post("instrument_id");
		$keterangan = $this->input->post("keterangan");
		$tanggal = $this->input->post("tanggal");
		$waktu = $this->input->post("waktu");

		$db_site = $this->change_connection($site_id);

		$length = $this->input->post("length");
		$start = $this->input->post("start");

		$data = $this->M_data->list($db_site, $instrument_id, $keterangan, $tanggal, $waktu, $start, $length);
		// pre($data);
		// pre($data);
		$result = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $data['recordsTotal'],
			"recordsFiltered" => $data['recordsFiltered'],
			"data" => $data['data']
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
		$data = $this->M_data->list($db_site, $instrument_id, $keterangan, $tanggal, $waktu, 1, -1, $download = 1);

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
		$columns = array_keys((array) reset($data['data']));
		foreach ($columns as $key => $column) {
			$sheet->setCellValueByColumnAndRow($key, 1, $column);
		}

		// Tambahkan data
		$row = 2;
		foreach ($data['data'] as $item) {
			$col = 0;
			foreach ($item as $value) {
				$sheet->setCellValueByColumnAndRow($col, $row, $value);
				$col++;
			}
			$row++;
		}
		$site_name = $this->db->get_where('ms_regions', ['id' => $site_id])->row()->site_name;
		$filename = $site_name . '_' . $data['data'][0]['kode_instrument'] . '_' . $data['data'][0]['nama_instrument'];
		// Save Excel file to a temporary location
		$tempFilePath = FCPATH . 'assets/temp/' . $filename . '.xlsx';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($tempFilePath);
		// Return the path to the Excel file
		echo base_url('assets/temp/' . $filename . '.xlsx');
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
					$ddt = "AND tanggal = '$tanggal'";
				} else {
					$ddt = "AND DATE_FORMAT(tanggal, '%Y-%m') = '$tanggal'";
				}
			} else {
				$ddt = "";
			}

			if (!empty($keterangan)) {
				$kt = "AND keterangan = '$keterangan'";
			} else {
				$kt = "";
			}

			$query = $db_site->query("SELECT id FROM data  WHERE kode_instrument = '$kode_instrument' {$kt} {$ddt}");
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

				$data_mentah_raw = $this->input->post('data_mentah');

				foreach ($data_mentah_raw  as $row) {
					$id_sensor = $row['id'];
					$data_mentah = $row['value'];
					$data_value = array(
						'data_id' => $last_id,
						'sensor_id' => $id_sensor,
						'data_primer' => $data_mentah,
						'data_jadi' => "",
						"created_by" => $this->session->userdata('ap_id_user'),
						"updated_by" => $this->session->userdata('ap_id_user'),
					);
					$insert_data_value = $db_site->insert("data_value", $data_value);
					if (!$insert_data_value) {

						throw new Exception("Gagal insert data value");
					}
				}


				$data_jadi_raw = $this->input->post('data_jadi');

				foreach ($data_jadi_raw  as $row) {
					$id_sensor = $row['id'];
					$data_jadi = $row['value'];
					$data_value = array(
						'data_id' => $last_id,
						'sensor_id' => $id_sensor,
						'data_primer' => 0,
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
			$db_site->rollback();
			echo json_encode(["error" => true, "message" => $e->getMessage()]);
		}
	}

	public function hitung()
	{
		try {
			$instrument_id = $_POST["instrument"];
			$data_mentah = $_POST["data_mentah"];

			$data = array();
			$data["data_mentah"] = $data_mentah;
			$data["instrument_id"] = $instrument_id;
			$hasil = $this->hitung_data($data);
			if (!$hasil) {
				throw new Exception("Formula belum tersedia");
			}

			echo json_encode(["error" => false, "data" => $hasil]);
		} catch (Exception $e) {
			echo json_encode(["error" => true, "message" => $e->getMessage()]);
		}
	}

	private function hitung_data($data)
	{
		try {

			$get_instrument = $this->db->get_where("tr_instrument", array('id' => $data["instrument_id"]))->row();

			$type_instrument_name = $this->db->get_where("tr_instrument_type", array('id' => $get_instrument->tr_instrument_type_id))->row()->name;


			$koefisien_arr = $this->db->get_where("tr_koefisien", array(
				'tr_instrument_id' => $get_instrument->id,
				'tr_instrument_type_id' => $get_instrument->tr_instrument_type_id
			))->row()->parameter;


			$koefisien = json_decode($koefisien_arr, true);

			$query = $this->db->query("SELECT 
											t1.id,
											t1.tr_koefisien_id,
											t1.jenis_sensor_jadi,
											t2.jenis_sensor nama_sensor,
											t2.var_name kode_sensor_jadi,
											t2.unit_sensor
										 FROM tr_koefisien_sensor_non_vwp t1
										 INNER JOIN  sys_jenis_sensor t2 ON t1.jenis_sensor_jadi = t2.id
										 WHERE t1.tr_instrument_id = {$data['instrument_id']}
										 AND t1.jenis_sensor_jadi != 0
										 ORDER BY t1.id ASC
										 ");
			$data_jadi = $query->result();
			// pre($data_jadi);

			$hasil = formula($type_instrument_name, $data_jadi, $data, $koefisien);

			if (!$hasil) {
				throw new Exception();
			}
			return $hasil;
		} catch (Exception $e) {
			return false;
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, '0:00:00');
		$col++;

		foreach ($query->result_array() as $sensor_data) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
			$col++;
		}

		// Atur format kolom tanggal
		$objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);

		// Atur format kolom jam
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);

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


		$site = $_POST['site'];
		$stasiun = $_POST['stasiun'];
		$instrument = $_POST['instrument'];

		$db_site = $this->change_connection($site);

		if (!$db_site) {
			echo json_encode(["error" => true, "message" => "Koneksi database ke site bermasalah"]);
		}

		$db_site->trans_begin();
		try {
			$this->load->library('PHPExcel');

			// Konfigurasi upload
			$config['upload_path'] =  FCPATH . 'assets/temp/';
			$config['allowed_types'] = 'xlsx|xls';
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('file')) {
				throw new Exception($this->upload->display_errors());
			}
			$data = $this->upload->data();
			$file_path = $data['full_path'];

			$objPHPExcel = PHPExcel_IOFactory::load($file_path);

			$sheet = $objPHPExcel->getActiveSheet();

			$sensor_mentah = [];
			foreach ($sheet->getRowIterator() as $index => $row) {

				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				$data_row = array();
				foreach ($cellIterator as  $cell) {
					$var_name = $this->db->get_where("sys_jenis_sensor", array('jenis_sensor' => $cell->getValue()))->row();
					if (isset($var_name->var_name)) {
						$data_row[] = $var_name->var_name;
					} else {
						$data_row[] = $cell->getValue();
					}
				}

				$start_index = 3;
				$sensor_mentah = array_slice($data_row, $start_index);

				break;
			}

			foreach ($sheet->getRowIterator() as $index => $row) {

				if ($index < 2) continue;

				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				$data_row = array();

				foreach ($cellIterator as $cell) {
					if ($cell->getColumn() == 'B') {
						if (is_numeric($cell->getValue())) {

							$date = PHPExcel_Shared_Date::ExcelToPHP($cell->getValue());

							$formattedDate = date('Y-m-d', $date);
						} else {
							$formattedDate = $cell->getValue();
						}


						$data_row[] = $formattedDate;
					} elseif ($cell->getColumn() == 'C') {

						// pre($cell->getValue());
						if (is_numeric($cell->getValue())) {
							$time = PHPExcel_Shared_Date::ExcelToPHP($cell->getValue());
							// date_default_timezone_set('Asia/Jakarta');
							$formattedTime = date('H:i:s', $time);
						} else {
							$formattedTime = $cell->getValue();
						}

						$data_row[] = $formattedTime;
					} else {
						$data_row[] = $cell->getValue();
					}
				}

				$start_index = 3;
				$value_mentah = array_slice($data_row, $start_index);

				$data = [];

				$data = array();
				$data_mentah_raw = array_combine($sensor_mentah, $value_mentah);
				$data["data_mentah"] = $data_mentah_raw;
				$data["instrument_id"] = $instrument;
				$hasil = $this->hitung_data($data);
				// pre($hasil);
				if (!$hasil) {
					throw new Exception("Formula belum tersedia");
				}
				$kode_instrument = $this->db->get_where("tr_instrument", array('id' => $instrument))->row()->kode_instrument;
				$data = array(
					'kode_instrument' => $kode_instrument,
					'tanggal' => $data_row[1],
					'jam' => $data_row[2],
					'keterangan' => "MANUAL",
					"created_by" => $this->session->userdata('ap_id_user'),
					"updated_by" => $this->session->userdata('ap_id_user'),
				);

				$db_site->insert("data", $data);
				$last_id = $db_site->insert_id();

				if ($last_id < 1) {
					throw new Exception("Data gagal di input");
				}

				foreach ($data_mentah_raw as $index => $row) {
					$id_sensor = $this->db->get_where("sys_jenis_sensor", array('var_name' => $index))->row()->id;

					$data_value = array(
						'data_id' => $last_id,
						'sensor_id' => $id_sensor,
						'data_primer' => $row,
						'data_jadi' => "",
						"created_by" => $this->session->userdata('ap_id_user'),
						"updated_by" => $this->session->userdata('ap_id_user'),
					);
					$insert_data_value = $db_site->insert("data_value", $data_value);
					if (!$insert_data_value) {
						throw new Exception("Gagal insert data value");
					}
				}



				foreach ($hasil  as $row) {
					$data_value = array(
						'data_id' => $last_id,
						'sensor_id' => $row['id_sensor'],
						'data_primer' => 0,
						'data_jadi' => $row['hasil'],
						"created_by" => $this->session->userdata('ap_id_user'),
						"updated_by" => $this->session->userdata('ap_id_user'),
					);
					$insert_data_value = $db_site->insert("data_value", $data_value);
					if (!$insert_data_value) {

						throw new Exception("Gagal insert data value");
					}
				}
			}

			$db_site->trans_commit();
			unlink($file_path);

			echo json_encode(array('error' => false, "message" => "data berhasil diinput"));
		} catch (Exception $e) {
			$db_site->rollback();
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
