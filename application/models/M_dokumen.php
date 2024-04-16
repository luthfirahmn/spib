<?php
class M_dokumen extends CI_Model 
{
	function __construct(){
		parent::__construct();
	}

	function dokumen($ap_id_user){ 
		return $this->db->query("
		SELECT a.*, b.`site_name`, c.`lookup_name` AS jenis 
		FROM `ms_documentations` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		LEFT JOIN `ms_lookup_values` c ON a.`category_id`=c.`lookup_code`
		LEFT JOIN ms_user_regions d ON a.`ms_regions_id`= d.`ms_regions_id`
		WHERE d.ms_users_id='$ap_id_user';
		")->result();
	}

	function dokumen_detail($id){ 
		return $this->db->query("
		SELECT a.*, b.`site_name`, c.`lookup_name` AS jenis 
		FROM `ms_documentations` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		LEFT JOIN `ms_lookup_values` c ON a.`category_id`=c.`lookup_code`
		where a.id='$id'
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

	function kategori(){ 
		$this->db->select('lookup_code, lookup_name');
		$this->db->from('ms_lookup_values');
		$query = $this->db->get()->result();

		return $this->db->get_where('ms_lookup_values', array('lookup_config' => 'DOCUMENT_CATEGORY'))->result();
	}

	function simpan($body){
		$this->db->trans_begin();
		$this->db->insert('ms_documentations', $body);

		$doc=$this->db->query('select max(id) as id from ms_documentations')->row();
		$ms_documentations_id=$doc->id;

		$this->db->query("INSERT INTO `ms_documentation_files`(ms_documentations_id, `name`) SELECT '$ms_documentations_id', `name` FROM files_temp");
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function hapus($id){
		$this->db->trans_begin();
		$this->db->delete('ms_documentation_files', array('ms_documentations_id' => $id));
		$this->db->delete('ms_documentations', array('id' => $id));
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function get_files($ms_documentations_id)
	{
		return $this->db->query("select * from ms_documentation_files where ms_documentations_id='$ms_documentations_id' ")->result();
	}

	// function is_valid($u, $p)
	// {
	// 	return $this->db->get_where('ms_users', 
	// 		array(
	// 			'username' 	=> $u,
	// 			'password'	=> $p
	// 		)
	// 	);
	// }

	// function list_user(){
	// 	return $this->db->query('
	// 	SELECT a.*, b.role_name FROM ms_users a
	// 	LEFT JOIN ms_roles b ON a.ms_roles_id=b.id
	// 	')->result();
	// }



	// 	return $query;
	// }

	// function role(){ 
	// 	$this->db->select('id, role_name');
	// 	$this->db->from('ms_roles');
	// 	$query = $this->db->get()->result();

	// 	return $query;
	// }

	

	// function detail_user($id){
	// 	return $this->db->query("
	// 	SELECT a.*, b.id as role_id, b.role_name FROM ms_users a
	// 	LEFT JOIN ms_roles b ON a.ms_roles_id=b.id
	// 	where a.id='$id'
	// 	")->row();
	// }

	// function edit($body, $site, $id_user){
	// 	$this->db->trans_begin();
	// 	$this->db->delete('ms_users', array('id' => $id_user));
	// 	$this->db->delete('ms_user_regions', array('ms_users_id' => $id_user));
	// 	$this->db->insert('ms_users', $body);
	// 	$user=$this->db->query('select max(id) as id from ms_users')->row();
	// 	$ms_users_id=$user->id;

	// 	for($i=0; $i<sizeof($site); $i++){
	// 		$detail = array(
	// 			'ms_users_id' 	=> $ms_users_id,
	// 			'ms_regions_id' => $site[$i]
	// 		);
			
	// 		$this->db->insert('ms_user_regions', $detail);
	// 	}
		
	// 	if ($this->db->trans_status() === FALSE){
	// 		$this->db->trans_rollback();
	// 	}else{
	// 		$this->db->trans_commit();
	// 	}

	// 	return $this->db->trans_status();
	// }

	

	// function menu($roles_id){
	// 	return $this->db->query("
	// 	SELECT a.* 
	// 	FROM ms_menus a
	// 	LEFT JOIN ms_accesscontrols b ON a.id=b.ms_menus_id
	// 	WHERE a.parent='0' AND b.ms_roles_id='$roles_id' AND b.view='1';
	// 	")->result();
	// }

	// function submenu($roles_id){
	// 	return $this->db->query("
	// 	SELECT a.* 
	// 	FROM ms_menus a
	// 	LEFT JOIN ms_accesscontrols b ON a.id=b.ms_menus_id
	// 	WHERE a.parent<>'0' AND b.ms_roles_id='$roles_id' AND b.view='1';
	// 	")->result();
	// }

	// function cek_data($username, $email){
	// 	return $this->db->query("
	// 	SELECT * FROM ms_users where username='$username' or email='$email';
	// 	")->result();
	// }
}