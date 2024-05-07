<?php
class M_role extends CI_Model 
{
	function __construct(){
		parent::__construct();
	}

	function role(){ 
		$this->db->select('id, role_name');
		$this->db->from('ms_roles');
		$query = $this->db->get()->result();

		return $query;
	}

	function accesscontrols($ms_roles_id)
	{
		return $this->db->query("
		SELECT a.*, b.menu_name FROM ms_accesscontrols a
		LEFT JOIN ms_menus b ON a.ms_menus_id=b.id
		WHERE a.ms_roles_id='$ms_roles_id'
		ORDER BY b.order ASC
		")->result();
	}


	function simpan($body, $role_name){
		$this->db->trans_begin();
		// $this->db->delete('ms_users', array('id' => $id_user));
		// $this->db->delete('ms_user_regions', array('ms_users_id' => $id_user));
		// $this->db->insert('ms_users', $body);

		$role=$this->db->query("select * from ms_roles where role_name='$role_name'")->result();

		if($role){}else{
			$this->db->insert('ms_roles', $body);
			$rl = $this->db->query('select max(id) as id from ms_roles')->row();
			$ms_roles_id = $rl->id;

			$this->db->query("
				INSERT INTO `ms_accesscontrols`(ms_roles_id, ms_menus_id)
				SELECT '$ms_roles_id', id FROM `ms_menus`
			");
		}
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

// 	function hapus($id_user){
// 		$this->db->trans_begin();
// 		$this->db->delete('ms_users', array('id' => $id_user));
// 		$this->db->delete('ms_user_regions', array('ms_users_id' => $id_user));
		
// 		if ($this->db->trans_status() === FALSE){
// 			$this->db->trans_rollback();
// 		}else{
// 			$this->db->trans_commit();
// 		}

// 		return $this->db->trans_status();
// 	}
}