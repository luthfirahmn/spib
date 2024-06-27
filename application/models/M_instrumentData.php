<?php
class M_instrumentData extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function instrument($ap_id_user)
	{
		$instrument = $this->db->query("
			SELECT a.*, b.site_name, c.name, d.nama_stasiun FROM `tr_instrument` a
			INNER JOIN `ms_regions` b ON a.`ms_regions_id`=b.id
			INNER JOIN `tr_instrument_type` c ON a.`tr_instrument_type_id`=c.id
			INNER JOIN `ms_stasiun` d ON a.`ms_stasiun_id`=d.id
			INNER JOIN `ms_user_regions` e ON a.`ms_regions_id`=e.ms_regions_id
			WHERE e.`ms_users_id`='$ap_id_user'
			ORDER BY 
		CASE 
			WHEN b.id = 5 THEN 0 
			ELSE 1
		END,
		b.id ASC, a.kode_instrument ASC
		")->result();

		foreach ($instrument as $row) {
			$db_site = $this->change_connection($row->ms_regions_id);

			$total_data = $db_site->query("SELECT COUNT(*) total_data FROM data WHERE data.kode_instrument = '{$row->kode_instrument}'
									")->row();
			$row->total_data = $total_data->total_data;

			$data_terkhir = $db_site->query("
				SELECT tanggal, jam 
				FROM data 
				WHERE data.kode_instrument = '{$row->kode_instrument}' 
				ORDER BY tanggal DESC, jam DESC 
				LIMIT 1
			")->row();

			if ($data_terkhir) {
				// Combine tanggal and jam into a single datetime string
				$datetime_string = $data_terkhir->tanggal . ' ' . $data_terkhir->jam;

				// Convert the datetime string into a DateTime object
				$datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime_string);

				// Format the DateTime object into the desired format
				$row->data_terakhir_masuk = $datetime->format('d M Y H:i');
			} else {
				// Handle the case where no data was found
				$row->data_terakhir_masuk = 'No data found';
			}
		}
		return $instrument;
	}



	function getTypeStation($ms_regions_id)
	{
		$type = $this->db->query("
		SELECT a.* FROM `tr_instrument_type` a
		INNER JOIN `tr_instrument_tp_region` b ON a.`id`=b.`tr_instrument_type_id`
		WHERE b.`ms_regions_id`='$ms_regions_id'
		")->result();

		$station = $this->db->query("
		SELECT * FROM `ms_stasiun` WHERE ms_regions_id='$ms_regions_id'
		")->result();

		return json_encode(
			array(
				'type' 		=> $type,
				'station'	=> $station
			)
		);
	}

	function getNameType($id)
	{
		$type = $this->db->get_where('tr_instrument_type', array('id' => $id))->row();
		return json_encode($type);
	}

	function getPositionStation($id)
	{
		$station = $this->db->get_where('ms_stasiun', array('id' => $id))->row();
		return json_encode($station);
	}

	function region($ms_users_id)
	{
		return $this->db->query("
		SELECT b.id, b.site_name 
		FROM ms_user_regions a
		INNER JOIN `ms_regions` b ON a.`ms_regions_id`=b.`id`
		WHERE ms_users_id='$ms_users_id'
		ORDER BY 
		CASE 
			WHEN b.id = 5 THEN 0 
			ELSE 1
		END,
		b.id ASC
		")->result();
	}

	function sensor($user_id)
	{
		return $this->db->query("
		SELECT t1.id, CONCAT(t1.jenis_sensor, ' (',t1.unit_sensor,')') AS nama_sensor FROM `sys_jenis_sensor`  t1
		WHERE t1.id IN (SELECT t2.sys_jenis_sensor_id FROM sys_jenis_sensor_region t2 WHERE t2.ms_regions_id IN 
		(SELECT t3.ms_regions_id FROM ms_user_regions t3 WHERE t3.ms_users_id = {$user_id}));
		")->result();
	}

	function get_sensor($regions_id)
	{
		return $this->db->query("
		SELECT t1.id, CONCAT(t1.jenis_sensor, ' (',t1.unit_sensor,')') AS nama_sensor FROM `sys_jenis_sensor`  t1
		WHERE t1.id IN (SELECT t2.sys_jenis_sensor_id FROM sys_jenis_sensor_region t2 WHERE t2.ms_regions_id = {$regions_id});
		")->result();
	}


	function type()
	{
		return $query = $this->db->get('tr_instrument_type')->result();
	}

	function simpan_temp($data, $jenis_sensor, $jenis_sensor_mentah, $jenis_sensor_jadi)
	{
		$this->db->trans_begin();
		$this->db->insert('temp_koefisien', $data);

		$koefisien = $this->db->query('select max(id) as id from temp_koefisien')->row();
		$id_temp_koefisien = $koefisien->id;

		// if($jenis_sensor<>''){
		// 	for($i=0; $i<sizeof($jenis_sensor); $i++){
		// 		$detail = array(
		// 			'id_temp_koefisien' => $id_temp_koefisien,
		// 			'jenis_sensor' 		=> $jenis_sensor[$i]
		// 		);
		// 		$this->db->insert('temp_sensor', $detail);
		// 	}
		// }

		if ($jenis_sensor_mentah <> '') {
			for ($i = 0; $i < sizeof($jenis_sensor_mentah); $i++) {
				$detail = array(
					'id_temp_koefisien' 	=> $id_temp_koefisien,
					'jenis_sensor_mentah' 	=> $jenis_sensor_mentah[$i]
				);
				$this->db->insert('temp_sensor', $detail);
			}
		}

		if ($jenis_sensor_jadi <> '') {
			for ($i = 0; $i < sizeof($jenis_sensor_jadi); $i++) {
				$detail = array(
					'id_temp_koefisien' 	=> $id_temp_koefisien,
					'jenis_sensor_jadi' 	=> $jenis_sensor_jadi[$i]
				);
				$this->db->insert('temp_sensor', $detail);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function edit_temp($data, $jenis_sensor, $jenis_sensor_mentah, $jenis_sensor_jadi, $id)
	{
		// print_r($data);
		$this->db->trans_begin();
		$this->db->delete('temp_sensor', array('id_temp_koefisien' => $id));
		$this->db->update('temp_koefisien', $data, array('id' => $id));

		$id_temp_koefisien = $id;

		// if ($jenis_sensor <> '') {
		// 	for ($i = 0; $i < sizeof($jenis_sensor); $i++) {
		// 		$detail = array(
		// 			'id_temp_koefisien' => $id_temp_koefisien,
		// 			'jenis_sensor' 		=> $jenis_sensor[$i]
		// 		);
		// 		$this->db->insert('temp_sensor', $detail);
		// 	}
		// }

		if ($jenis_sensor_mentah <> '') {
			for ($i = 0; $i < sizeof($jenis_sensor_mentah); $i++) {
				$detail = array(
					'id_temp_koefisien' 	=> $id_temp_koefisien,
					'jenis_sensor_mentah' 	=> $jenis_sensor_mentah[$i]
				);
				$this->db->insert('temp_sensor', $detail);
			}
		}

		if ($jenis_sensor_jadi <> '') {
			for ($i = 0; $i < sizeof($jenis_sensor_jadi); $i++) {
				$detail = array(
					'id_temp_koefisien' 	=> $id_temp_koefisien,
					'jenis_sensor_jadi' 	=> $jenis_sensor_jadi[$i]
				);
				$this->db->insert('temp_sensor', $detail);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function hapus_temp($id)
	{
		$ap_id_user	= $this->session->userdata('ap_id_user');

		$this->db->trans_begin();

		$this->db->delete('temp_sensor', array('id_temp_koefisien' => $id));
		$this->db->delete('temp_koefisien', array(
			'id' 			=> $id,
			'ap_id_user'	=> $ap_id_user
		));

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function dataTempKoefisien()
	{
		$temp_item = array();
		$ap_id_user = $this->session->userdata('ap_id_user');
		$tempkoefisien = $this->db->query("
		SELECT a.*, b.`name`, b.type 
		FROM `temp_koefisien` a
		LEFT JOIN `tr_instrument_type` b ON a.`tr_instrument_type_id`=b.id
		WHERE a.`ap_id_user`='$ap_id_user'
		")->result();

		foreach ($tempkoefisien as $rec) {
			// $jenis_sensor = $this->db->query("
			// SELECT a.`jenis_sensor` as id, CONCAT(b.`jenis_sensor`,'(',b.`unit_sensor`,')') AS jenis_sensor 
			// FROM temp_sensor a
			// LEFT JOIN `sys_jenis_sensor` b ON a.`jenis_sensor`=b.`id`
			// WHERE a.`id_temp_koefisien`='$rec->id' AND a.`jenis_sensor` is NOT NULL
			// ")->result();

			$jenis_sensor_mentah = $this->db->query("
			SELECT a.`jenis_sensor_mentah` as id, CONCAT(b.`jenis_sensor`,'(',b.`unit_sensor`,')') AS jenis_sensor 
			FROM temp_sensor a
			LEFT JOIN `sys_jenis_sensor` b ON a.`jenis_sensor_mentah`=b.`id`
			WHERE a.`id_temp_koefisien`='$rec->id' AND a.`jenis_sensor_mentah` is NOT NULL
			ORDER BY a.id ASC
			")->result();

			$jenis_sensor_jadi = $this->db->query("
			SELECT a.`jenis_sensor_jadi` as id, CONCAT(b.`jenis_sensor`,'(',b.`unit_sensor`,')') AS jenis_sensor 
			FROM temp_sensor a
			LEFT JOIN `sys_jenis_sensor` b ON a.`jenis_sensor_jadi`=b.`id`
			WHERE a.`id_temp_koefisien`='$rec->id' AND a.`jenis_sensor_jadi` is NOT NULL
			ORDER BY a.id ASC
			")->result();

			$temp_item[] = array(
				'id'					=> $rec->id,
				'tr_instrument_type_id'	=> $rec->tr_instrument_type_id,
				'tmaw'					=> $rec->tmaw,
				'tmas'					=> $rec->tmas,
				'name'					=> $rec->name,
				'type'					=> $rec->type,
				// 'jenis_sensor'			=> $jenis_sensor,
				'jenis_sensor_mentah'	=> $jenis_sensor_mentah,
				'jenis_sensor_jadi'		=> $jenis_sensor_jadi,
				'parameter'				=> $rec->parameter
			);
		}

		// pre($temp_item);

		return $temp_item;
	}



	// function region_detail($tr_instrument_type_id){ 
	// 	return $this->db->query("
	// 	SELECT a.id, a.`site_name`, 
	// 	COALESCE (
	// 	(
	// 		SELECT ms_regions_id FROM `tr_instrument_tp_region` WHERE `ms_regions_id`=a.id AND `tr_instrument_type_id`='$tr_instrument_type_id'
	// 	), '99') AS pilih
	// 	FROM `ms_regions` a
	// 	")->result();
	// }

	function simpan($tr_instrument, $tr_instrument_instalasi, $name_type, $tr_instrument_sensor)
	{
		$ap_id_user = $this->session->userdata('ap_id_user');
		$this->db->trans_begin();
		$this->db->insert('tr_instrument', $tr_instrument);

		$instrument = $this->db->query('select max(id) as id from tr_instrument')->row();
		$tr_instrument_id = $instrument->id;

		$this->db->query("
		INSERT INTO tr_koefisien(tr_instrument_id, tr_instrument_type_id, parameter)
		SELECT '$tr_instrument_id', tr_instrument_type_id, parameter FROM `temp_koefisien` WHERE ap_id_user='$ap_id_user';
		");

		$koefisien = $this->db->query('select max(id) as id from tr_koefisien')->row();
		$tr_koefisien_id = $koefisien->id;

		if ($name_type == 'VWP') {
			$tr_instrument_instalasi['tr_instrument_id'] = $tr_instrument_id;
			$this->db->insert('tr_instrument_instalasi', $tr_instrument_instalasi);
		}

		$this->db->query("
		INSERT INTO `tr_koefisien_sensor_vwp`(tr_koefisien_id, jenis_sensor, tr_instrument_id)
		SELECT '$tr_koefisien_id', a.`jenis_sensor` , '$tr_instrument_id'
		FROM temp_sensor a
		left join temp_koefisien c on a.id_temp_koefisien=c.id
		WHERE c.ap_id_user='$ap_id_user' AND a.`jenis_sensor` IS NOT NULL
		");

		$this->db->query("
		INSERT INTO `tr_koefisien_sensor_non_vwp`(tr_koefisien_id, jenis_sensor_mentah, tr_instrument_id)
		SELECT '$tr_koefisien_id', a.`jenis_sensor_mentah`, '$tr_instrument_id'
		FROM temp_sensor a 
		LEFT JOIN temp_koefisien c ON a.id_temp_koefisien=c.id 
		WHERE c.ap_id_user='$ap_id_user' AND a.`jenis_sensor_mentah` IS NOT NULL
		");

		$this->db->query("
		INSERT INTO `tr_koefisien_sensor_non_vwp`(tr_koefisien_id, jenis_sensor_jadi, tr_instrument_id)
		SELECT '$tr_koefisien_id', a.jenis_sensor_jadi, '$tr_instrument_id'
		FROM temp_sensor a 
		LEFT JOIN temp_koefisien c ON a.id_temp_koefisien=c.id 
		WHERE c.ap_id_user='$ap_id_user' AND a.`jenis_sensor_jadi` IS NOT NULL
		");

		$tr_instrument_sensor['tr_instrument_id'] = $tr_instrument_id;
		$this->db->insert('tr_instrument_sensor', $tr_instrument_sensor);

		$this->db->truncate('temp_sensor');
		$this->db->truncate('temp_koefisien');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function hapus($id)
	{
		$this->db->trans_begin();
		$this->db->delete('tr_instrument_sensor', array('tr_instrument_id' => $id));
		$this->db->delete('tr_koefisien_sensor_non_vwp', array('tr_instrument_id' => $id));
		$this->db->delete('tr_koefisien_sensor_vwp', array('tr_instrument_id' => $id));
		$this->db->delete('tr_instrument_instalasi', array('tr_instrument_id' => $id));
		$this->db->delete('tr_koefisien', array('tr_instrument_id' => $id));
		$this->db->delete('tr_instrument', array('id' => $id));

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	public function parameter($tr_instrument_type_id)
	{
		return $this->db->query("select parameter_name from tr_instrument_parameter where tr_instrument_type_id='$tr_instrument_type_id'")->result();
	}

	function instrument_detail($id)
	{
		$ap_id_user = $this->session->userdata('ap_id_user');

		$this->db->trans_begin();

		$this->db->query("
		INSERT INTO temp_koefisien(ap_id_user, tr_instrument_type_id, parameter)
		SELECT '$ap_id_user', tr_instrument_type_id, parameter FROM `tr_koefisien` WHERE tr_instrument_id='$id'
		");

		$koefisien = $this->db->query('select max(id) as id from temp_koefisien')->row();
		$id_temp_koefisien = $koefisien->id;

		$this->db->query("
		INSERT INTO `temp_sensor`(id_temp_koefisien, jenis_sensor)
		SELECT '$id_temp_koefisien', jenis_sensor FROM `tr_koefisien_sensor_vwp` WHERE tr_instrument_id='$id'
		");

		$this->db->query("
		INSERT INTO `temp_sensor`(id_temp_koefisien, jenis_sensor_mentah)
		SELECT '$id_temp_koefisien', jenis_sensor_mentah FROM `tr_koefisien_sensor_non_vwp` WHERE tr_instrument_id='$id'
		");

		$this->db->query("
		INSERT INTO `temp_sensor`(id_temp_koefisien, jenis_sensor_jadi)
		SELECT '$id_temp_koefisien', jenis_sensor_jadi FROM `tr_koefisien_sensor_non_vwp` WHERE tr_instrument_id='$id'
		");






		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}


		$instrument = $this->db->query("
		SELECT a.* , b.`type`
		FROM `tr_instrument` a
		LEFT JOIN tr_instrument_type b ON a.`tr_instrument_type_id`=b.id
		where a.id='$id'
		")->row();

		$type = $this->db->query("
		SELECT a.* FROM `tr_instrument_type` a
		LEFT JOIN `tr_instrument_tp_region` b ON a.`id`=b.`tr_instrument_type_id`
		WHERE b.`ms_regions_id`='$instrument->ms_regions_id'
		")->result();

		$station = $this->db->query("
		SELECT * FROM `ms_stasiun` WHERE ms_regions_id='$instrument->ms_regions_id'
		")->result();

		$detail = array(
			'sensor'		=> $this->db->get_where('tr_instrument_sensor', array('tr_instrument_id' => $id))->row(),
			'instalasi' 	=> $this->db->get_where('tr_instrument_instalasi', array('tr_instrument_id' => $id))->row(),
			'instrument'	=> $instrument,
			'type'			=> $type,
			'station'		=> $station
		);

		return $detail;
	}


	public function detailTempKoefisien($id, $regions_id)
	{
		$temp_koefisien =  $this->db->query("
		SELECT a.*, b.`type` FROM `temp_koefisien` a
		LEFT JOIN `tr_instrument_type` b ON a.`tr_instrument_type_id`=b.id
		WHERE a.id='$id'
		")->row();

		$nama_sensor = $this->db->query("
		SELECT a.id, CONCAT(a.jenis_sensor, '(',a.unit_sensor,')') AS nama_sensor,
		COALESCE (
		(
			SELECT 'selected' FROM `temp_sensor` WHERE `jenis_sensor`=a.id AND `id_temp_koefisien`='$id' LIMIT 1
		), '') AS pilih
		FROM sys_jenis_sensor a
		")->result();

		$jenis_sensor_mentah = $this->db->query("
		SELECT a.id, CONCAT(a.jenis_sensor, '(',a.unit_sensor,')') AS nama_sensor,
		COALESCE (
		(
			SELECT 'selected' FROM `temp_sensor` WHERE `jenis_sensor_mentah`=a.id AND `id_temp_koefisien`='$id' ORDER BY id ASC LIMIT 1
		), '') AS pilih
		FROM sys_jenis_sensor a
		WHERE a.id IN (SELECT sys_jenis_sensor_id FROM sys_jenis_sensor_region WHERE ms_regions_id = {$regions_id})
		ORDER BY 
		(
			SELECT 
				id 
			FROM 
				`temp_sensor` 
			WHERE 
				`jenis_sensor_mentah` = a.id 
				AND `id_temp_koefisien` = '$id' 
			ORDER BY 
				id ASC 
			LIMIT 1
		) ASC;
		")->result();

		$jenis_sensor_jadi = $this->db->query("
		SELECT a.id, CONCAT(a.jenis_sensor, '(',a.unit_sensor,')') AS nama_sensor,
		COALESCE (
		(
			SELECT 'selected' FROM `temp_sensor` WHERE `jenis_sensor_jadi`=a.id AND `id_temp_koefisien`='$id' ORDER BY id ASC LIMIT 1
		), '') AS pilih
		FROM sys_jenis_sensor a
		WHERE a.id IN (SELECT sys_jenis_sensor_id FROM sys_jenis_sensor_region WHERE ms_regions_id = {$regions_id})
		ORDER BY 
		(
			SELECT 
				id 
			FROM 
				`temp_sensor` 
			WHERE 
				`jenis_sensor_jadi` = a.id 
				AND `id_temp_koefisien` = '$id' 
			ORDER BY 
				id ASC 
			LIMIT 1
		) ASC;
		")->result();

		$type = $this->db->query("
		SELECT a.*,
		COALESCE (
		(
			SELECT 'selected' FROM `temp_koefisien` WHERE tr_instrument_type_id=a.id AND id='$id' LIMIT 1
		), '') AS pilih
		FROM tr_instrument_type a
		")->result();

		$nm_sns = array();
		foreach ($nama_sensor as $rec) {
			$nm_sns[] = array(
				'value' 	=> $rec->id,
				'label'		=> $rec->nama_sensor,
				'selected'	=> ($rec->pilih == 'selected') ? TRUE : FALSE
			);
		}

		$sensor_jadi = array();
		foreach ($jenis_sensor_jadi as $rec) {
			$sensor_jadi[] = array(
				'value' 	=> $rec->id,
				'label'		=> $rec->nama_sensor,
				'selected'	=> ($rec->pilih == 'selected') ? TRUE : FALSE
			);
		}

		$sensor_mentah = array();
		foreach ($jenis_sensor_mentah as $rec) {
			$sensor_mentah[] = array(
				'value' 	=> $rec->id,
				'label'		=> $rec->nama_sensor,
				'selected'	=> ($rec->pilih == 'selected') ? TRUE : FALSE
			);
		}


		return array(
			'temp_koefisien' 		=> $temp_koefisien,
			'nama_sensor'	 		=> $nm_sns,
			'jenis_sensor_jadi'		=> $sensor_jadi,
			'jenis_sensor_mentah'	=> $sensor_mentah,
			'type'					=> $type,
			'parameter'				=> json_decode($temp_koefisien->parameter)
		);
	}

	function edit($tr_instrument, $tr_instrument_instalasi, $name_type, $tr_instrument_sensor, $id)
	{
		$ap_id_user = $this->session->userdata('ap_id_user');
		$this->db->trans_begin();

		$this->db->delete('tr_instrument_sensor', array('tr_instrument_id' => $id));
		$this->db->delete('tr_koefisien_sensor_non_vwp', array('tr_instrument_id' => $id));
		$this->db->delete('tr_koefisien_sensor_vwp', array('tr_instrument_id' => $id));
		$this->db->delete('tr_instrument_instalasi', array('tr_instrument_id' => $id));
		$this->db->delete('tr_koefisien', array('tr_instrument_id' => $id));

		//update
		$this->db->where('id', $id);
		$this->db->update('tr_instrument', $tr_instrument);

		$instrument = $this->db->query('select max(id) as id from tr_instrument')->row();
		$tr_instrument_id = $id;

		$this->db->query("
		INSERT INTO tr_koefisien(tr_instrument_id, tr_instrument_type_id, parameter)
		SELECT '$tr_instrument_id', tr_instrument_type_id, parameter FROM `temp_koefisien` WHERE ap_id_user='$ap_id_user';
		");

		$koefisien = $this->db->query('select max(id) as id from tr_koefisien')->row();
		$tr_koefisien_id = $koefisien->id;

		if ($name_type == 'VWP') {
			$tr_instrument_instalasi['tr_instrument_id'] = $tr_instrument_id;
			$this->db->insert('tr_instrument_instalasi', $tr_instrument_instalasi);
		}

		$this->db->query("
		INSERT INTO `tr_koefisien_sensor_vwp`(tr_koefisien_id, jenis_sensor, tr_instrument_id)
		SELECT '$tr_koefisien_id', a.`jenis_sensor` , '$tr_instrument_id'
		FROM temp_sensor a
		left join temp_koefisien c on a.id_temp_koefisien=c.id
		WHERE c.ap_id_user='$ap_id_user' AND a.`jenis_sensor` IS NOT NULL
		");

		$this->db->query("
		INSERT INTO `tr_koefisien_sensor_non_vwp`(tr_koefisien_id, jenis_sensor_mentah, tr_instrument_id)
		SELECT '$tr_koefisien_id', a.`jenis_sensor_mentah`, '$tr_instrument_id'
		FROM temp_sensor a 
		LEFT JOIN temp_koefisien c ON a.id_temp_koefisien=c.id 
		WHERE c.ap_id_user='$ap_id_user' AND a.`jenis_sensor_mentah` IS NOT NULL
		ORDER BY a.id ASC
		");

		$this->db->query("
		INSERT INTO `tr_koefisien_sensor_non_vwp`(tr_koefisien_id, jenis_sensor_jadi, tr_instrument_id)
		SELECT '$tr_koefisien_id', a.jenis_sensor_jadi, '$tr_instrument_id'
		FROM temp_sensor a 
		LEFT JOIN temp_koefisien c ON a.id_temp_koefisien=c.id 
		WHERE c.ap_id_user='$ap_id_user' AND a.`jenis_sensor_jadi` IS NOT NULL
		ORDER BY a.id ASC
		");

		$tr_instrument_sensor['tr_instrument_id'] = $tr_instrument_id;
		$this->db->insert('tr_instrument_sensor', $tr_instrument_sensor);

		$this->db->truncate('temp_sensor');
		$this->db->truncate('temp_koefisien');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
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
