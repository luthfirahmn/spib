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

            if ($row->stasiun_type == 'GEOLOGI' || $row->stasiun_type == 'SEISMOLOGI') {
                $first_instrument = explode(',', $row->kode_instruments)[0];
                $kode_instruments = "'" . $first_instrument . "'";

                $result = $db_site->query("
					SELECT 
						t3.jenis_sensor, t3.unit_sensor, t2.data_jadi
					FROM (
						SELECT id FROM temp_data t1
						WHERE t1.kode_instrument = $kode_instruments
						ORDER BY t1.tanggal DESC, t1.jam DESC LIMIT 1
					) t1 
					INNER JOIN temp_data_value t2 ON t1.id = t2.data_id
					INNER JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
					WHERE t2.data_jadi != '' AND t2.data_primer = 0
				")->result();
            } else {
                $kode_instruments = "'" . str_replace(",", "','", $row->kode_instruments) . "'";

                $result = $db_site->query("
					SELECT 
						t3.jenis_sensor, t3.unit_sensor, t2.data_jadi
					FROM (
						SELECT id FROM temp_data t1
						WHERE t1.kode_instrument IN ($kode_instruments)
						ORDER BY t1.tanggal DESC, t1.jam DESC LIMIT 1
					) t1 
					INNER JOIN temp_data_value t2 ON t1.id = t2.data_id
					INNER JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
					WHERE t2.data_jadi != '' AND t2.data_primer = 0
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
