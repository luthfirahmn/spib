<?php
class M_maps extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function station($ap_id_user)
	{
		// Query to fetch stations with their instruments
		$station = $this->db->query("
			SELECT a.*, 
				b.site_name, 
				GROUP_CONCAT(c.kode_instrument) AS kode_instruments,
				a.stasiun_type
			FROM ms_stasiun a 
			LEFT JOIN ms_regions b ON a.ms_regions_id = b.id
			LEFT JOIN ms_user_regions d ON a.ms_regions_id = d.ms_regions_id
			LEFT JOIN tr_instrument c ON a.id = c.ms_stasiun_id
			WHERE d.ms_users_id = '$ap_id_user'
			GROUP BY a.id, b.site_name, a.stasiun_type
		")->result();

		foreach ($station as $row) {
			$db_site = $this->change_connection($row->ms_regions_id);

			// For different stasiun_type, adjust the query accordingly
			if ($row->stasiun_type == 'GEOLOGI' || $row->stasiun_type == 'SEISMOLOGI') {
				// For 'Geologi' and 'Seismologi', only fetch data for the first instrument
				$first_instrument = explode(',', $row->kode_instruments)[0];
				$kode_instruments = "'" . $first_instrument . "'";

				$result = $db_site->query("
					SELECT 
						t3.jenis_sensor, t3.unit_sensor, data_value.data_jadi
					FROM (
						SELECT id FROM temp_data data
						WHERE data.kode_instrument = $kode_instruments
						ORDER BY data.tanggal DESC, data.jam DESC LIMIT 1
					) data 
					INNER JOIN temp_data_value data_value ON data.id = data_value.data_id
					INNER JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON data_value.sensor_id = t3.id
					WHERE data_value.data_jadi != '' AND data_value.data_primer = 0
				")->result();
			} else {
				// For other types, fetch data for all instruments
				$kode_instruments = "'" . str_replace(",", "','", $row->kode_instruments) . "'";

				$result = $db_site->query("
					SELECT 
						t3.jenis_sensor, t3.unit_sensor, data_value.data_jadi
					FROM (
						SELECT id FROM temp_data data
						WHERE data.kode_instrument IN ($kode_instruments)
						ORDER BY data.tanggal DESC, data.jam DESC LIMIT 1
					) data 
					INNER JOIN temp_data_value data_value ON data.id = data_value.data_id
					INNER JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON data_value.sensor_id = t3.id
					WHERE data_value.data_jadi != '' AND data_value.data_primer = 0
				")->result();
			}

			$row->sensor_data = $result;
		}

		return $station;
	}


	function station_detail($id)
	{
		return $this->db->query("
		SELECT a.*, b.`site_name`
		FROM `ms_stasiun` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		LEFT JOIN ms_user_regions d ON a.`ms_regions_id`= d.`ms_regions_id`
		WHERE a.id='$id';
		")->row();
	}

	function region($ms_users_id)
	{
		return $this->db->query("
		SELECT b.id, b.site_name 
		FROM ms_user_regions a
		LEFT JOIN `ms_regions` b ON a.`ms_regions_id`=b.`id`
		WHERE ms_users_id='$ms_users_id'
		")->result();
	}



	function change_connection($id_regions)
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

	private function switchDatabase($hostname, $username, $password, $database, $port)
	{
		$params = array(
			'hostname' => $hostname . ':' . $port,
			'username' => $username,
			'password' => $password,
			'database' => $database,
			// Other database configuration parameters
			'dbdriver' => 'mysqli',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => (ENVIRONMENT !== 'production'),
			'cache_on' => FALSE,
			'cachedir' => '',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'encrypt'  => FALSE,
			'compress' => FALSE,
			'stricton' => FALSE,
			'failover' => array(),
			'save_queries' => TRUE
		);

		return $params;
	}
}
