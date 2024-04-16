<?php
class M_akses extends CI_Model 
{
	function __construct(){
		parent::__construct();
	}

	function hak_akses($roles_id, $controler){
		return $this->db->query("
		SELECT a.*, b.`controller` 
		FROM `ms_accesscontrols` a
		LEFT JOIN `ms_menus` b ON a.`ms_menus_id`=b.`id`
		WHERE a.ms_roles_id='$roles_id' AND b.`controller`='$controler'
		")->row();
	}
	
}