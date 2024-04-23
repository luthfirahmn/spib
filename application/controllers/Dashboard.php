<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_akses');
		$this->load->model('M_data');
		$this->load->dbutil();
		$this->load->database();
	}

	public function index()
	{
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['hak_akses'] = $this->M_akses->hak_akses($roles_id, 'Data');
		$data['region'] = $this->M_data->region($ap_id_user);

		$all_data = $this->get_all_data($data['region'][0]->id);
		// pre($all_data);
		if ($all_data) {
			$data['awlr'] = $all_data['awlr'];
			$data['klimatologi'] = $all_data['klimatologi'];
		}


		$this->load->view('dashboard/index', $data);
	}


	function get_all_data($region_id)
	{
		try {
			// SUM(ts1.data_jadi) as total
			$db_site = $this->change_connection($region_id);
			$query = $this->db->query("SELECT t1.*, t2.elev_tanggul_utama,
												t2.elev_pelimpah,t2.elev_normal,t2.elev_siaga3,
												t2.elev_siaga2,t2.elev_siaga1,t2.batas_kritis_vwp
										FROM ms_stasiun t1
										LEFT JOIN  ms_sites t2 ON t1.ms_regions_id = t2.ms_regions_id
										WHERE t1.nama_stasiun LIKE '%AWLR%'
										AND t1.ms_regions_id = {$region_id}
									 ");
			$data['awlr'] = $query->result();
			foreach ($data['awlr'] as $key => $row) {
				$query = $this->db->query("SELECT t3.jenis_sensor, t3.unit_sensor, t3.icon, t1.kode_instrument,
												(SELECT SUM(ts2.data_jadi) as total  FROM  " . $db_site->database . ".data ts1 
												LEFT JOIN " . $db_site->database . ".data_value ts2 ON ts1.id = ts2.data_id
												WHERE ts1.kode_instrument = t1.kode_instrument
												AND ts2.data_jadi != 0
												GROUP BY ts2.data_id
												ORDER BY ts1.tanggal,ts1.jam DESC LIMIT 1) nilai
												FROM  tr_instrument t1 
												LEFT JOIN tr_koefisien_sensor_non_vwp t2 ON t2.tr_instrument_id = t1.id
												LEFT JOIN sys_jenis_sensor t3 ON t3.id = t2.jenis_sensor_jadi
												WHERE t1.ms_stasiun_id = '$row->id'
												AND t2.jenis_sensor_jadi != 0
											 ");

				$result = $query->result();
				$data['awlr'][$key]->details = $result;
			}



			$query = $this->db->query("SELECT t1.*
										FROM ms_stasiun t1
										WHERE nama_stasiun NOT LIKE '%AWLR%'
										AND t1.ms_regions_id = {$region_id}
										ORDER BY 
										CASE WHEN t1.stasiun_type LIKE '%NON_VWP%' THEN 0 ELSE 1 END,
										t1.stasiun_type;
									 ");
			$data['klimatologi'] = $query->result();
			foreach ($data['klimatologi'] as $key => $row) {
				$query = $this->db->query("SELECT t3.jenis_sensor, t3.unit_sensor, t3.icon, t1.kode_instrument,
				(SELECT SUM(ts2.data_jadi) as total  FROM  " . $db_site->database . ".data ts1 
				LEFT JOIN " . $db_site->database . ".data_value ts2 ON ts1.id = ts2.data_id
				WHERE ts1.kode_instrument = t1.kode_instrument
				AND ts2.data_jadi != 0
				GROUP BY ts2.data_id
				ORDER BY ts1.tanggal,ts1.jam DESC LIMIT 1) nilai
				FROM  tr_instrument t1 
				LEFT JOIN tr_koefisien_sensor_non_vwp t2 ON t2.tr_instrument_id = t1.id
				LEFT JOIN sys_jenis_sensor t3 ON t3.id = t2.jenis_sensor_jadi
				WHERE t1.ms_stasiun_id = '$row->id'
				AND t2.jenis_sensor_jadi != 0
			 ");

				$result = $query->result();
				$data['klimatologi'][$key]->details = $result;
			}


			return $data;
		} catch (Exception $e) {
			return false;
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
