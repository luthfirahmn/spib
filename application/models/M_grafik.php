<?php
class M_grafik extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function grafik($ap_id_user)
	{
		return $this->db->query("
		SELECT a.*, b.`site_name`
		FROM `ms_stasiun` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		LEFT JOIN ms_user_regions d ON a.`ms_regions_id`= d.`ms_regions_id`
		WHERE d.ms_users_id='$ap_id_user';
		")->result();
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
}
