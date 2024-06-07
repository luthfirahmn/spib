<?php
class M_station extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function station($ap_id_user, $site_id)
	{
		return $this->db->query("
		SELECT a.*, b.`site_name`, (SELECT COUNT(*) FROM tr_instrument t1 WHERE t1.ms_stasiun_id = a.id) count 
		FROM `ms_stasiun` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		LEFT JOIN ms_user_regions d ON a.`ms_regions_id`= d.`ms_regions_id`
		WHERE d.ms_users_id='$ap_id_user' and a.`ms_regions_id`='$site_id'
		
		;
		")->result();
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
		ORDER BY 
		CASE 
			WHEN b.id = 5 THEN 0 
			ELSE 1
		END,
		b.id ASC
		")->result();
	}

	function stasiun_type()
	{
		return $this->db->query("
		select * from ms_lookup_values where lookup_config='STASIUN_TYPE'
		")->result();
	}
}
