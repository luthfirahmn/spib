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
											t2.var_name kode_sensor_jadi
										 FROM tr_koefisien_sensor_non_vwp t1
										 INNER JOIN  sys_jenis_sensor t2 ON t1.jenis_sensor_jadi = t2.id
										 WHERE t1.tr_instrument_id = {$data['instrument_id']}
										 AND t1.jenis_sensor_jadi != 0
										 ");
			$data_jadi = $query->result();
			// pre($data_jadi);
			$hasil = [];
			switch ($type_instrument_name) {
				case "Pressure":
					foreach ($data_jadi as $row) {
						$action = $this->pressure($row, $data["data_mentah"], $koefisien);
						if (!$action) {
							throw new Exception();
						}
						$hasil[] = $action;
					}
					break;
				case "Ultrasonic":
					foreach ($data_jadi as $row) {
						$action = $this->ultrasonic($row, $data["data_mentah"], $koefisien);
						if (!$action) {
							throw new Exception();
						}
						$hasil[] = $action;
					}
					break;
				case "Hall Effect":
					foreach ($data_jadi as $row) {
						$action = $this->hall_effect($row, $data["data_mentah"], $koefisien);
						if (!$action) {
							throw new Exception();
						}
						$hasil[] = $action;
					}
					break;
				case "Standard":
					foreach ($data_jadi as $row) {
						$action = $this->standard($row, $data["data_mentah"], $koefisien);
						if (!$action) {
							throw new Exception();
						}
						$hasil[] = $action;
					}
					break;
				case "Tipping Bucket":
					foreach ($data_jadi as $row) {
						$action = $this->tipping_bucket($row, $data["data_mentah"], $koefisien);
						if (!$action) {
							throw new Exception();
						}
						$hasil[] = $action;
					}
					break;
				case "Open Stand Pipe":
					foreach ($data_jadi as $row) {
						$action = $this->open_stand_pipe($row, $data["data_mentah"], $koefisien);
						if (!$action) {
							throw new Exception();
						}
						$hasil[] = $action;
					}
					break;
				case "Seismograph":
					foreach ($data_jadi as $row) {
						$action = $this->seismograph($row, $data["data_mentah"], $koefisien);
						if (!$action) {
							throw new Exception();
						}
						$hasil[] = $action;
					}
					break;
				case "Accelerograph":
					foreach ($data_jadi as $row) {
						$action = $this->accelerograph($row, $data["data_mentah"], $koefisien);
						if (!$action) {
							throw new Exception();
						}
						$hasil[] = $action;
					}
					break;
				default:
					throw new Exception();
			}

			return $hasil;
		} catch (Exception $e) {
			return false;
		}
	}

	public function pressure($data_jadi, $data_mentah, $koefisien)
	{
		try {
			switch ($data_jadi->kode_sensor_jadi) {
				case "tinggi_muka_air":
					$hitung = (($data_mentah["ketinggian_air"] / 100) + 0) + $koefisien["elevasi_sensor"];
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "debit_rembesan":
					$hitung = $koefisien["konstanta_v"] * pow(($data_mentah["ketinggian_air"] / 100) + 0, 2.5) * 1000;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				default:
					throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function ultrasonic($data_jadi, $data_mentah, $koefisien)
	{
		try {
			switch ($data_jadi->kode_sensor_jadi) {
				case "tinggi_muka_air":
					$hitung = $koefisien["elevasi_sensor"] - (($data_mentah["ketinggian_air"] / 100) + 0);
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "status_siaga":
					if (($data_mentah["status_siaga"]) >= 4) {
						$hitung = "-";
					} elseif (($data_mentah["status_siaga"]) < 4 and ($data_mentah["status_siaga"]) >= 3) {
						$hitung = "Normal";
					} elseif (($data_mentah["status_siaga"]) < 3 and ($data_mentah["status_siaga"]) >= 2) {
						$hitung = "Waspada";
					} elseif (($data_mentah["status_siaga"]) < 2 and ($data_mentah["status_siaga"]) >= 1) {
						$hitung = "Siaga";
					} elseif (($data_mentah["status_siaga"]) < 1) {
						$hitung = "Awas";
					}
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "evaporation":
					$hitung = $koefisien["elevasi_sensor"] - (($data_mentah["ketinggian_air"] / 100) + 0);
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				default:
					throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function hall_effect($data_jadi, $data_mentah, $koefisien)
	{
		try {
			switch ($data_jadi->kode_sensor_jadi) {
				case "wind_direction":
					$arah_mata_angin = $data_mentah["derajat_arah_angin"] + 0;

					if ($arah_mata_angin > 337.5 || $arah_mata_angin < 22.5) {
						$arah_angin = "Utara";
					} elseif ($arah_mata_angin >= 22.5 && $arah_mata_angin < 67.5) {
						$arah_angin = "Timur Laut";
					} elseif ($arah_mata_angin >= 67.5 && $arah_mata_angin < 112.5) {
						$arah_angin = "Timur";
					} elseif ($arah_mata_angin >= 112.5 && $arah_mata_angin < 157.5) {
						$arah_angin = "Tenggara";
					} elseif ($arah_mata_angin >= 157.5 && $arah_mata_angin < 202.5) {
						$arah_angin = "Selatan";
					} elseif ($arah_mata_angin >= 202.5 && $arah_mata_angin < 247.5) {
						$arah_angin = "Barat Daya";
					} elseif ($arah_mata_angin >= 247.5 && $arah_mata_angin < 292.5) {
						$arah_angin = "Barat";
					} elseif ($arah_mata_angin >= 292.5 && $arah_mata_angin < 337.5) {
						$arah_angin = "Barat Laut";
					}

					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $arah_angin];
					break;
				default:
					throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function standard($data_jadi, $data_mentah, $koefisien)
	{
		try {
			switch ($data_jadi->kode_sensor_jadi) {
				case "wind_speed":
					$hitung = $data_mentah["wind_speed"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "wind_direction":
					$hitung = $data_mentah["wind_direction"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "air_temperature":
					$hitung = $data_mentah["air_temperature"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "air_humidity":
					$hitung = $data_mentah["air_humidity"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "air_pressure":
					$hitung = $data_mentah["air_pressure"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "solar_radiation":
					$hitung = $data_mentah["solar_radiation"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				case "kedalaman_air":
					$hitung = $data_mentah["kedalaman_air"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				default:
					throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function tipping_bucket($data_jadi, $data_mentah, $koefisien)
	{
		try {
			switch ($data_jadi->kode_sensor_jadi) {
				case "rainfall":
					$hitung = ($data_mentah["knock"] + 0) * $koefisien["resolusi_sensor"];
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;

				default:
					throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function open_stand_pipe($data_jadi, $data_mentah, $koefisien)
	{
		try {
			switch ($data_jadi->kode_sensor_jadi) {
				case "tinggi_muka_air":
					$hitung = $koefisien["elevasi_top_pipa"] - ($data_mentah["kedalaman_air"] + 0);
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;

				default:
					throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function seismograph($data_jadi, $data_mentah, $koefisien)
	{
		try {
			switch ($data_jadi->kode_sensor_jadi) {
				case "seismometer":
					$hitung = $data_mentah["seismometer"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				default:
					throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function accelerograph($data_jadi, $data_mentah, $koefisien)
	{
		try {
			switch ($data_jadi->kode_sensor_jadi) {
				case "accelerometer":
					$hitung = $data_mentah["accelerometer"] + 0;
					return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor, 'hasil' => $hitung];
					break;
				default:
					throw new Exception();
			}
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
