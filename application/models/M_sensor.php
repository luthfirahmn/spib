<?php
class M_sensor extends CI_Model 
{
	function __construct(){
		parent::__construct();
	}

	function sensor($ap_id_user){ 
		return $this->db->query("
		SELECT a.*, b.`site_name`
		FROM `sys_jenis_sensor` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		LEFT JOIN ms_user_regions d ON a.`ms_regions_id`= d.`ms_regions_id`
		WHERE d.ms_users_id='$ap_id_user';
		")->result();
	}
	
	function sensor_detail($id){ 
		return $this->db->query("
		SELECT a.*, b.`site_name`
		FROM `sys_jenis_sensor` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		LEFT JOIN ms_user_regions d ON a.`ms_regions_id`= d.`ms_regions_id`
		WHERE a.id='$id';
		")->row();
	}

	function region($ms_users_id){ 
		return $this->db->query("
		SELECT b.id, b.site_name 
		FROM ms_user_regions a
		LEFT JOIN `ms_regions` b ON a.`ms_regions_id`=b.`id`
		WHERE ms_users_id='$ms_users_id'
		")->result();
	}

	function simpan($body){
		$this->db->trans_begin();
		$this->db->insert('sys_jenis_sensor', $body);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
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