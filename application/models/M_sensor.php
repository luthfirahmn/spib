<?php
class M_sensor extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function sensor($ap_id_user, $site_id)
	{
		return $this->db->query("

		SELECT t1.*,
			(SELECT site_name FROM ms_regions WHERE id = {$site_id}) site_name
		FROM sys_jenis_sensor t1
		INNER JOIN sys_jenis_sensor_region t2 ON t2.sys_jenis_sensor_id = t1.id
		WHERE t2.ms_regions_id = {$site_id}
		")->result();
	}

	function sensor_detail($id)
	{
		return $this->db->query("
		SELECT a.*, b.`site_name`
		FROM `sys_jenis_sensor` a 
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

	function region_detail($ms_users_id, $sys_jenis_sensor_id)
	{
		return $this->db->query("
		SELECT b.id, b.site_name, 
		COALESCE (
		(
			SELECT ms_regions_id FROM `sys_jenis_sensor_region` WHERE `ms_regions_id`=b.id AND `sys_jenis_sensor_id`='$sys_jenis_sensor_id'
		), '99') AS pilih
		FROM ms_user_regions a
		LEFT JOIN `ms_regions` b ON a.`ms_regions_id`=b.`id`
		WHERE a.ms_users_id='$ms_users_id'
		")->result();
	}

	function simpan($body, $site)
	{
		$this->db->trans_begin();
		$this->db->insert('sys_jenis_sensor', $body);

		$sensor = $this->db->query('select max(id) as id from sys_jenis_sensor')->row();
		$sys_jenis_sensor_id = $sensor->id;

		for ($i = 0; $i < sizeof($site); $i++) {
			$detail = array(
				'ms_regions_id' 		=> $site[$i],
				'sys_jenis_sensor_id' => $sys_jenis_sensor_id
			);

			$this->db->insert('sys_jenis_sensor_region', $detail);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	// function hapus($id){
	// 	$this->db->trans_begin();
	// 	$this->db->delete('sys_jenis_sensor_files', array('sys_jenis_sensor_id' => $id));
	// 	$this->db->delete('sys_jenis_sensor', array('id' => $id));

	// 	if ($this->db->trans_status() === FALSE){
	// 		$this->db->trans_rollback();
	// 	}else{
	// 		$this->db->trans_commit();
	// 	}

	// 	return $this->db->trans_status();
	// }

	// function get_files($sys_jenis_sensor_id)
	// {
	// 	return $this->db->query("select * from sys_jenis_sensor_files where sys_jenis_sensor_id='$sys_jenis_sensor_id' ")->result();
	// }
}
