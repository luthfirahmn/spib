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


	function stasiun($ms_regions_id)
	{
		$query = $this->db->query("SELECT id, nama_stasiun FROM `ms_stasiun` WHERE ms_regions_id='$ms_regions_id'");
		return json_encode(array('result' => $query->result()));
	}


	function instrument($ms_stasiun_id)
	{
		$query = $this->db->query("SELECT id, nama_instrument FROM `tr_instrument` WHERE ms_stasiun_id='$ms_stasiun_id'");
		return json_encode(array('result' => $query->result()));
	}

	function list($db_site, $instrument_id, $keterangan, $tanggal, $waktu, $download = 0)
	{
		$kode_instrument = $this->db->get_where("tr_instrument", array('id' => $instrument_id))->row()->kode_instrument;

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
		$query = $db_site->query("
						SELECT t1.id, t1.kode_instrument, t1.tanggal, t1.jam, t1.keterangan
						FROM data t1
						WHERE t1.kode_instrument = '$kode_instrument'
						{$kt}
						{$ddt}
						ORDER BY t1.tanggal ASC, t1.jam ASC
		");
		$data = $query->result_array();
		if (!empty($data)) {

			$perubahan = 0;
			$trend = null;
			foreach ($data as $key => $value) {
				$sensor = $db_site->query("
					SELECT t3.jenis_sensor, t3.unit_sensor, t2.data_jadi as val_sensor
					FROM data_value t2
					LEFT JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
					WHERE t2.data_id = '" . $value['id'] . "'
				")->result_array();

				foreach ($sensor as $row) {
					$hitung = $row['val_sensor'] - $perubahan;
					$trend = ($hitung > 0) ? 'naik' : 'turun';
					$data[$key][$row['jenis_sensor']] = $row['val_sensor'];
					if ($download) {
						if ($trend == 'naik') {
							$data[$key]['Perubahan ' . $row['jenis_sensor']] = 'Naik (+' . $hitung . ')';
						} else {
							$data[$key]['Perubahan ' . $row['jenis_sensor']] = 'Turun (-' . $hitung . ')';
						}
					} else {

						if ($trend == 'naik') {
							$data[$key]['Perubahan ' . $row['jenis_sensor']] = '<i class="ti ti-arrow-up-circle text-red-700 "></i><span class="text-red-700 text-sm align-middle"> +' . $hitung . '</span>';
						} else {
							$data[$key]['Perubahan ' . $row['jenis_sensor']] = '<i class="ti ti-arrow-down-circle text-green-700 "></i><span class="text-green-700 text-sm align-middle"> -' . $hitung . '</span>';
						}
					}

					$perubahan =  $row['val_sensor'];
				}
			}
			return $data;
		} else {
			return [];
		}
	}

	function get_sensor_by_instrument_id($instrument_id)
	{
		$query = $this->db->query("SELECT 
										t1.id,
										t1.jenis_sensor
									FROM sys_jenis_sensor t1
									WHERE t1.id IN (SELECT t2.jenis_sensor_mentah
													FROM tr_koefisien_sensor_non_vwp  t2
													WHERE t2.jenis_sensor_mentah = t1.id
													AND t2.tr_instrument_id = '$instrument_id')
									ORDER BY t1.id ASC
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
