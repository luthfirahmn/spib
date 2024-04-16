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

// 	function is_valid($u, $p)
// 	{
// 		return $this->db->get_where('ms_users', 
// 			array(
// 				'username' 	=> $u,
// 				'password'	=> $p
// 			)
// 		);
// 	}

// 	function list_user(){
// 		return $this->db->query('
// 		SELECT a.*, b.role_name FROM ms_users a
// 		LEFT JOIN ms_roles b ON a.ms_roles_id=b.id
// 		')->result();
// 	}

// 	function region(){ 
// 		$this->db->select('id, site_name');
// 		$this->db->from('ms_regions');
// 		$query = $this->db->get()->result();

// 		return $query;
// 	}



// 	function simpan($body, $site){
// 		$this->db->trans_begin();
// 		$this->db->insert('ms_users', $body);

// 		$user=$this->db->query('select max(id) as id from ms_users')->row();
// 		$ms_users_id=$user->id;

// 		for($i=0; $i<sizeof($site); $i++){
// 			$detail = array(
// 				'ms_users_id' 	=> $ms_users_id,
// 				'ms_regions_id' => $site[$i]
// 			);
			
// 			$this->db->insert('ms_user_regions', $detail);
// 		}
		
// 		if ($this->db->trans_status() === FALSE){
// 			$this->db->trans_rollback();
// 		}else{
// 			$this->db->trans_commit();
// 		}

// 		return $this->db->trans_status();
// 	}

// 	function detail_user($id){
// 		return $this->db->query("
// 		SELECT a.*, b.id as role_id, b.role_name FROM ms_users a
// 		LEFT JOIN ms_roles b ON a.ms_roles_id=b.id
// 		where a.id='$id'
// 		")->row();
// 	}

// 	function edit($body, $site, $id_user){
// 		$this->db->trans_begin();
// 		$this->db->delete('ms_users', array('id' => $id_user));
// 		$this->db->delete('ms_user_regions', array('ms_users_id' => $id_user));
// 		$this->db->insert('ms_users', $body);
// 		$user=$this->db->query('select max(id) as id from ms_users')->row();
// 		$ms_users_id=$user->id;

// 		for($i=0; $i<sizeof($site); $i++){
// 			$detail = array(
// 				'ms_users_id' 	=> $ms_users_id,
// 				'ms_regions_id' => $site[$i]
// 			);
			
// 			$this->db->insert('ms_user_regions', $detail);
// 		}
		
// 		if ($this->db->trans_status() === FALSE){
// 			$this->db->trans_rollback();
// 		}else{
// 			$this->db->trans_commit();
// 		}

// 		return $this->db->trans_status();
// 	}

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