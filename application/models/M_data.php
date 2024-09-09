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
		ORDER BY 
		CASE 
			WHEN b.id = 5 THEN 0 
			ELSE 1
		END,
		b.id ASC
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


	function get_column($db_site, $instrument_id, $keterangan, $tanggal, $waktu, $download = 0)
	{
		$instrument_data = $this->db->get_where("tr_instrument", array('id' => $instrument_id))->row();
		$kode_instrument = $instrument_data->kode_instrument;
		$is_perubahan = $this->db->get_where("ms_regions", array('id' => $instrument_data->ms_regions_id))->row()->is_perubahan;

		if ($waktu == 'jam') {
			$ddt = "AND t1.tanggal = '$tanggal'
					AND t1.jam LIKE '%:00:00'";
		} else {

			$ddt = "AND DATE_FORMAT(t1.tanggal, '%Y-%m') = '$tanggal' AND t1.jam LIKE '%:00:00' ";
		}



		if (!empty($keterangan)) {
			$kt = "AND t1.keterangan = '$keterangan'";
		} else {
			$kt = "";
		}

		$query = $db_site->query("
						SELECT t1.id, t1.kode_instrument, (SELECT nama_instrument FROM " . $this->db->database . ".tr_instrument WHERE t1.kode_instrument = kode_instrument LIMIT 1) nama_instrument, t1.tanggal, t1.jam, t1.keterangan
						FROM data t1
						WHERE t1.kode_instrument = '$kode_instrument'
						{$kt}
						{$ddt}
						LIMIT 1
		");

		$data = $query->result_array();

		if (!empty($data)) {

			$no = 1;
			foreach ($data as $key => $value) {

				$data_mentah = $db_site->query("
						SELECT t3.jenis_sensor, t3.unit_sensor, t2.data_primer as val_sensor
						FROM data_value t2
						LEFT JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
						WHERE t2.data_id = '" . $value['id'] . "'
						AND t2.data_jadi = ''
						ORDER BY t2.id ASC
					")->result();
				// $data_mentah_arr = [];
				foreach ($data_mentah as $row) {
					$unit_sensor = $row->unit_sensor != '-' ? $row->unit_sensor : '';
					$jenis_sensor_head = 'P - ' . $row->jenis_sensor . ' (' . $unit_sensor . ')';
					// $jenis_sensor_head = $row->jenis_sensor;
					$data[$key][$jenis_sensor_head] = '';
				}

				$sensor = $db_site->query("
					SELECT t2.sensor_id, t3.jenis_sensor, t3.unit_sensor, t2.data_jadi as val_sensor
					FROM data_value t2
					LEFT JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
					WHERE t2.data_id = '" . $value['id'] . "'
					AND t2.data_jadi != ''
					ORDER BY t2.id ASC
				")->result_array();

				foreach ($sensor as $row) {
					$replace_val_sensor = str_replace(',', '', $row['val_sensor']);

					$is_zero  = $replace_val_sensor == 0 ? true : false;

					$float_val_sensor = (float)$replace_val_sensor;

					if ($float_val_sensor === 0 && $is_zero === false) {
						$data[$key]['Status'] = '';
					} else {
						$index_jenis_sensor = $row['jenis_sensor'] . ' (' . $row['unit_sensor'] . ')';
						$data[$key][$index_jenis_sensor] = $row['val_sensor'];
						if ($is_perubahan) {
							if ($download) {
								$data[$key]['Perubahan ' . $index_jenis_sensor] = '';
							} else {
								$data[$key]['Perubahan ' . $index_jenis_sensor] = '';
							}
						}
					}
				}

				$no++;
			}

			return ['data' => $data];
		} else {
			return ['data' => $data];
		}
	}

	function list($db_site, $site_id, $instrument_id, $keterangan, $tanggal, $waktu, $start, $length, $download = 0)
	{
		$instrument_data = $this->db->get_where("tr_instrument", array('id' => $instrument_id))->row();
		$kode_instrument = $instrument_data->kode_instrument;
		$is_perubahan = $this->db->get_where("ms_regions", array('id' => $instrument_data->ms_regions_id))->row()->is_perubahan;

		if ($length >= 10) {
			$length = $length + 1;
		}

		$start_datetime = $tanggal . ' 01:00:00';
		$end_datetime = date('Y-m-d H:i:s', strtotime($tanggal . ' +1 day 00:00:00'));


		if ($waktu == 'jam') {

			$ddt = "AND CONCAT(t1.tanggal, ' ', t1.jam) >= '$start_datetime' AND CONCAT(t1.tanggal, ' ', t1.jam) <= '$end_datetime'";
		} else {

			$ddt = "AND DATE_FORMAT(t1.tanggal, '%Y-%m') = '$tanggal'";
		}


		if (!empty($keterangan)) {
			$kt = "AND t1.keterangan = '$keterangan'";
		} else {
			$kt = "";
		}
		$query = $db_site->query("
						SELECT t1.id, t1.kode_instrument, 
						(SELECT nama_instrument FROM " . $this->db->database . ".tr_instrument WHERE t1.kode_instrument = kode_instrument AND ms_regions_id = {$site_id} LIMIT 1) nama_instrument, 
						t1.tanggal, t1.jam, t1.keterangan
						FROM data t1
						WHERE t1.kode_instrument = '$kode_instrument'
						{$kt}
						{$ddt}
						ORDER BY t1.tanggal DESC, t1.jam DESC, t1.id DESC
						" . ($length != -1 ? "LIMIT $start, $length" : "") . "
			");

		$data = $query->result_array();
		$recordsTotal = $query->num_rows();
		if ($recordsTotal >= 10) {
			$recordsTotal = $recordsTotal - 1;
		}

		$query = $db_site->query("
					SELECT t1.id, t1.kode_instrument, t1.tanggal, t1.jam, t1.keterangan
					FROM data t1
					WHERE t1.kode_instrument = '$kode_instrument'
					{$kt}
					{$ddt}
					ORDER BY t1.tanggal DESC, t1.jam DESC, t1.id DESC
			");

		$recordsFiltered = $query->num_rows();
		if ($recordsFiltered >= 10) {
			$recordsFiltered = $recordsFiltered - 1;
		}
		if (!empty($data)) {
			$index = $start + 1;
			foreach ($data as $key => $value) {
				$data_mentah = $db_site->query("
					SELECT t3.jenis_sensor, t3.unit_sensor, t2.data_primer as val_sensor
					FROM data_value t2
					LEFT JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
					WHERE t2.data_id = '" . $value['id'] . "'
					AND t2.data_jadi = ''
				")->result();
				foreach ($data_mentah as $row) {
					$unit_sensor = $row->unit_sensor != '-' ? $row->unit_sensor : '';
					$jenis_sensor_head = 'P - ' . $row->jenis_sensor . ' (' . $unit_sensor . ')';

					$data[$key][$jenis_sensor_head] = number_format($row->val_sensor, 3);
				}

				$sensor = $db_site->query("
					SELECT t2.sensor_id, t3.jenis_sensor, t3.unit_sensor, t2.data_jadi as val_sensor
					FROM data_value t2
					LEFT JOIN " . $this->db->database . ".sys_jenis_sensor t3 ON t2.sensor_id = t3.id
					WHERE t2.data_id = '" . $value['id'] . "'
					AND t2.data_jadi != ''
				")->result_array();

				foreach ($sensor as $row) {
					$st = $tanggal . ' 01:00:00';
					$dt = "AND CONCAT(t1.tanggal, ' ', t1.jam) >= '$st'";

					$data_id_before = isset($data[$key + 1]['id']) ? 'AND t1.id = ' . $data[$key + 1]['id'] : $dt;
					$query = $db_site->query("
						SELECT id, 
						(
							SELECT t2.data_jadi as val_sensor
							FROM data_value t2
							WHERE t2.data_id = t1.id
							AND t2.sensor_id = {$row['sensor_id']}
							AND t2.data_primer = 0
							LIMIT 1
						) val_sensor
							FROM data t1
							WHERE t1.kode_instrument = '$kode_instrument'
							{$data_id_before}
						
						
					");
					$data_before = $query->row();

					$replace_val_sensor = str_replace(',', '', $row['val_sensor']);

					$is_zero  = $replace_val_sensor == 0 ? true : false;

					$float_val_sensor = (float)$replace_val_sensor;

					if ($float_val_sensor === 0 && $is_zero === false) {
						$data[$key]['Status'] = $replace_val_sensor;
					} else {
						$replace_val_before = str_replace(',', '', $data_before->val_sensor);
						$hit = $float_val_sensor - (float)$replace_val_before;
						$hitung = number_format($hit, 3);
						$trend = ($hitung > 0) ? 'naik' : 'turun';
						if ($hitung > 0) {
							$trend = 'naik';
						} else if ($hitung < 0) {
							$trend = 'turun';
						} else {
							$trend = '';
						}
						$index_jenis_sensor = $row['jenis_sensor'] . ' (' . $row['unit_sensor'] . ')';
						$data[$key][$index_jenis_sensor] = $row['val_sensor'];
						if ($is_perubahan) {

							if ($download) {
								if ($trend == 'naik') {
									$data[$key]['Perubahan ' . $index_jenis_sensor] = '+' . $hitung;
								} else if ($trend == 'turun') {
									$data[$key]['Perubahan ' . $index_jenis_sensor] =  $hitung;
								} else {
									$data[$key]['Perubahan ' . $index_jenis_sensor] = '0';
								}
							} else {

								if ($trend == 'naik') {
									$data[$key]['Perubahan ' . $index_jenis_sensor] = '<i class="ti ti-arrow-up-circle text-red-700 "></i><span class="text-red-700 text-sm align-middle"> +' . $hitung . '</span>';
								} else if ($trend == 'turun') {
									$data[$key]['Perubahan ' . $index_jenis_sensor] = '<i class="ti ti-arrow-down-circle text-green-700 "></i><span class="text-green-700 text-sm align-middle"> -' . $hitung . '</span>';
								} else {
									$data[$key]['Perubahan ' . $index_jenis_sensor] = '<i class="ti ti-arrows-sort text-black"></i><span class="text-black text-sm align-middle"> 0</span>';
								}
							}
						}
					}
				}

				$data[$key]['no'] = $index++;
			}


			if ($recordsTotal >= 10) {
				array_pop($data);
			}
			return ['data' => $data, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered];
		} else {
			if ($recordsTotal >= 10) {
				array_pop($data);
			}
			return ['data' => $data, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered];
		}
	}

	function get_sensor_by_instrument_id($instrument_id)
	{
		$query = $this->db->query("SELECT 
        t1.id,
        t1.jenis_sensor,
        t1.var_name
    FROM sys_jenis_sensor t1
    JOIN tr_koefisien_sensor_non_vwp t2 ON t2.jenis_sensor_mentah = t1.id
    WHERE t2.tr_instrument_id = '$instrument_id'
    ORDER BY t2.id ASC
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
