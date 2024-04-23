<?php
class M_data extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function region($ms_users_id)
	{
		return $this->db->query("
		SELECT b.id, b.site_name 
		FROM ms_user_regions a
		LEFT JOIN `ms_regions` b ON a.`ms_regions_id`=b.`id`
		WHERE ms_users_id='$ms_users_id'
		ORDER BY b.id
		")->result();
	}

	function instrument($ms_regions_id)
	{
		$query = $this->db->query("SELECT id, nama_instrument FROM `tr_instrument` WHERE ms_regions_id='$ms_regions_id'");
		return json_encode(array('result' => $query->result()));
	}

	function list($db_site, $instrument_id)
	{
		$kode_instrument = $this->db->get_where("tr_instrument", array('id' => $instrument_id))->row()->kode_instrument;

		$query = $db_site->query("
						SELECT t1.id, t1.kode_instrument, t1.tanggal, t1.jam, t1.keterangan
						FROM data t1
						WHERE t1.kode_instrument = '$kode_instrument'
		");
		$data = $query->result_array();

		foreach ($data as $key => $value) {
			$sensor = $db_site->query("
				SELECT t3.jenis_sensor, t3.unit_sensor, CASE WHEN t2.data_primer IS NOT NULL THEN t2.data_primer ELSE t2.data_jadi END as val_sensor
				FROM data_value t2
				LEFT JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
				WHERE t2.data_id = '" . $value['id'] . "'
			")->result_array();
			foreach ($sensor as $row) {
				$data[$key][$row['jenis_sensor']] = $row['val_sensor'];
			}
		}

		return $data;
	}

	function get_sensor_by_instrument_id($instrument_id)
	{
		$query = $this->db->query("SELECT 
											t1.id,
										CASE WHEN t4.jenis_sensor IS NOT NULL THEN t4.id ELSE t3.id END as id_sensor,
										CASE WHEN t4.jenis_sensor IS NOT NULL THEN t4.jenis_sensor ELSE t3.jenis_sensor END as jenis_sensor,
										CASE WHEN t4.jenis_sensor IS NOT NULL THEN 'jadi' ELSE 'mentah' END as flag
								   FROM tr_koefisien_sensor_non_vwp t1
								   LEFT JOIN sys_jenis_sensor t3 ON t1.jenis_sensor_mentah = t3.id
								   LEFT JOIN sys_jenis_sensor t4 ON t1.jenis_sensor_jadi = t4.id
								   WHERE t1.tr_instrument_id = '$instrument_id'
								");
		return json_encode(array('result' => $query->result()));
	}


	function insert_data($post)
	{

		$add_instrument = $this->input->post('add_instrument');
		$add_site = $this->input->post('add_site');
		$add_tanggal = $this->input->post('add_tanggal');
		$add_jam = $this->input->post('add_jam');
		$hitung_sensor = $this->input->post('hitung_sensor');

		return json_encode(['error' => false, 'message' => 'Data berhasil ditambah']);
	}
}
