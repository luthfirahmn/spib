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

	public function index($region_id = null)
	{
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['hak_akses'] = $this->M_akses->hak_akses($roles_id, 'Data');
		$data['region'] = $this->M_data->region($ap_id_user);
		if ($region_id == null) {
			$region_id = $data['region'][0]->id;
		} else {
			$region_id = $region_id;
		}
		$all_data = $this->get_all_data($region_id);
		if ($all_data) {
			$data['nama_region'] = $this->db->get_where('ms_regions', 'id = ' . $region_id)->row();
			$data['station'] = $all_data;
		}


		$this->load->view('dashboard/index', $data);
	}


	public function get_all_data($region_id)
	{
		try {
			$db_site = $this->change_connection($region_id);
			$stations = $this->get_stations_with_instruments($region_id);
			$latest_data = $this->get_latest_data_values($db_site);

			$data_by_instrument = array();
			foreach ($latest_data as $data) {
				$data_by_instrument[$data->kode_instrument] = (object) [
					'icon' => $data->icon,
					'instrument' => $data->nama_instrument,
					'value' => $data->data_jadi . ' ' . $data->unit_sensor
				];
			}

			$organized_data = array();
			foreach ($stations as $type => $station_list) {
				$organized_data[$type] = array();
				foreach ($station_list as $station => $instruments) {
					$station_data = array();
					foreach ($instruments as $kode_instrument => $nama_instrument) {
						if (isset($data_by_instrument[$kode_instrument])) {
							$station_data[] = $data_by_instrument[$kode_instrument];
						}
					}
					if (!empty($station_data)) {
						$organized_data[$type][$station] = $station_data;
					}
				}
				if (empty($organized_data[$type])) {
					unset($organized_data[$type]);
				}
			}

			return $organized_data;
		} catch (Exception $e) {
			return false;
		}
	}




	public function get_stations_with_instruments($region_id)
	{
		$this->db->select('ms_stasiun.stasiun_type, ms_stasiun.nama_stasiun, tr_instrument.kode_instrument, tr_instrument.nama_instrument');
		$this->db->from('ms_stasiun');
		$this->db->join('tr_instrument', 'ms_stasiun.id = tr_instrument.ms_stasiun_id', 'left');
		$this->db->where('ms_stasiun.ms_regions_id', $region_id); // Add region filter
		$query = $this->db->get();

		$result = $query->result();
		$stations = array();
		foreach ($result as $row) {
			if (!isset($stations[$row->stasiun_type])) {
				$stations[$row->stasiun_type] = array();
			}
			if (!isset($stations[$row->stasiun_type][$row->nama_stasiun])) {
				$stations[$row->stasiun_type][$row->nama_stasiun] = array();
			}
			if ($row->kode_instrument) {
				$stations[$row->stasiun_type][$row->nama_stasiun][$row->kode_instrument] = $row->nama_instrument;
			}
		}
		return $stations;
	}

	public function get_latest_data_values($db_site)
	{
		$sql = "  SELECT t1.kode_instrument, t1.data_jadi, t1.icon, t1.unit_sensor,  t1.nama_instrument
        FROM (
            SELECT data.kode_instrument, data_value.data_jadi,sys_jenis_sensor.icon, sys_jenis_sensor.unit_sensor,   tr_instrument.nama_instrument,
                   ROW_NUMBER() OVER (PARTITION BY data.kode_instrument ORDER BY data.tanggal DESC, data.jam DESC) as rn
            FROM " . $db_site->database . ".data
            JOIN " . $db_site->database . ".data_value ON data.id = data_value.data_id
            JOIN sys_jenis_sensor ON data_value.sensor_id = sys_jenis_sensor.id
			JOIN tr_instrument ON data.kode_instrument = tr_instrument.kode_instrument
            WHERE data_value.data_jadi != ''
        ) t1
        WHERE t1.rn = 1";
		$query = $this->db->query($sql);
		return $query->result();
	}


	function _get_all_data($region_id)
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
										WHERE stasiun_type = 'KLIMATOLOGI'
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

				// $query = $this->db->query("
				// SELECT *
				// FROM 
				// tr_instrument t3
				// INNER JOIN " . $db_site->database . ".data t1 ON t3.kode_instrument = t1.kode_instrument
				// INNER JOIN " . $db_site->database . ".data_value t2 ON t1.id = t2.data_id
				// INNER JOIN sys_jenis_sensor t4 ON t4.id = t2.sensor_id
				// WHERE t2.data_jadi != ''
				// AND  t3.ms_stasiun_id = '$row->id'
				// ORDER BY t1.tanggal DESC, t1.jam DESC
				// ");
				// $result = $query->result();
			}
			$data['nama_region'] = $this->db->get_where("ms_regions", ['id' => $region_id])->row();

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
