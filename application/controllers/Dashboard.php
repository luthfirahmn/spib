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
			// Change database connection based on region_id
			$db_site = $this->change_connection($region_id);

			// Retrieve stations with instruments for the given region_id
			$stations = $this->get_stations_with_instruments($region_id);

			// Fetch the latest data values from the database
			$latest_data = $this->get_latest_data_values($db_site, $region_id);
			// pre($latest_data);
			// Organize the latest data by instrument code
			$data_by_instrument = array();
			foreach ($latest_data as $data) {
				$data_by_instrument[$data->kode_instrument] = (object) [
					'icon' => $data->icon,
					'instrument' => $data->nama_instrument,
					'value' => $data->data_jadi . ' ' . $data->unit_sensor,
					'last_update' => date('d M Y H:i', strtotime($data->tanggal . ' ' . $data->jam))
				];
			}
			$organized_data = array();
			foreach ($stations as $type => $station_list) {

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
			}
			return $organized_data;
		} catch (Exception $e) {
			return false;
		}
	}

	public function get_stations_with_instruments($region_id)
	{
		// Select the required columns
		$this->db->select('ms_stasiun.stasiun_type, ms_stasiun.nama_stasiun, tr_instrument.kode_instrument, tr_instrument.nama_instrument, tr_instrument.tr_instrument_type_id');
		$this->db->from('ms_stasiun');
		$this->db->join('tr_instrument', 'ms_stasiun.id = tr_instrument.ms_stasiun_id', 'inner');
		$this->db->where('ms_stasiun.ms_regions_id', $region_id); // Add region filter
		$this->db->order_by("FIELD(ms_stasiun.stasiun_type, 'KLIMATOLOGI', 'HIDROLOGI', 'EWS', 'GEOLOGI', 'SEISMOLOGI')");
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

		// Sort instruments by tr_instrument_type_id for GEOLOGI stations
		if (isset($stations['GEOLOGI'])) {
			foreach ($stations['GEOLOGI'] as $station_name => &$instruments) {
				uasort($instruments, function ($a, $b) use ($result) {
					$a_id = null;
					$b_id = null;
					foreach ($result as $row) {
						if ($row->nama_instrument == $a) {
							$a_id = $row->tr_instrument_type_id;
						}
						if ($row->nama_instrument == $b) {
							$b_id = $row->tr_instrument_type_id;
						}
					}
					return $a_id <=> $b_id;
				});
			}
		}
		return $stations;
	}

	public function get_latest_data_values($db_site, $region_id)
	{
		$sql = "SELECT t1.kode_instrument, t1.data_jadi, t2.icon, t2.unit_sensor,  t3.nama_instrument, t1.jam, t1.tanggal ,t1.sensor_id
        FROM (
            SELECT data.kode_instrument, data_value.data_jadi, data.jam, data.tanggal, data_value.sensor_id,
                   ROW_NUMBER() OVER (PARTITION BY data.kode_instrument ORDER BY data.tanggal DESC, data.jam DESC, data_value.id DESC) as rn
            FROM " . $db_site->database . ".data
            INNER JOIN " . $db_site->database . ".data_value ON data.id = data_value.data_id
           
            WHERE data_value.data_jadi != '' AND data_value.data_primer = 0
			AND data.keterangan = 'OTOMATIS'
			ORDER BY data_value.id DESC, data.tanggal DESC, data.jam DESC
        ) t1
		INNER JOIN sys_jenis_sensor t2 ON t1.sensor_id = t2.id
		INNER JOIN tr_instrument t3 ON t1.kode_instrument = t3.kode_instrument AND t3.ms_regions_id = {$region_id}
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
