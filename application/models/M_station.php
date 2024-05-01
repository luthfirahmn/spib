<?php
class M_station extends CI_Model 
{
	function __construct(){
		parent::__construct();
	}

	function station($ap_id_user, $site_id){ 
		return $this->db->query("
		SELECT a.*, b.`site_name`
		FROM `ms_stasiun` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		LEFT JOIN ms_user_regions d ON a.`ms_regions_id`= d.`ms_regions_id`
		WHERE d.ms_users_id='$ap_id_user' and a.`ms_regions_id`='$site_id';
		")->result();
	}
	
	function station_detail($id){ 
		return $this->db->query("
		SELECT a.*, b.`site_name`
		FROM `ms_stasiun` a 
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

}