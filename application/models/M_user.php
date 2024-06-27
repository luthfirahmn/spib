<?php
class M_user extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function validasi_login($username, $password)
	{
		$pass = sha1($password);
		return $this->db->query("
		SELECT a.*, b.`ms_regions_id` 
		FROM `ms_users` a
		LEFT JOIN ms_user_regions b ON a.`id`=b.`ms_users_id`
		WHERE a.username='$username' AND a.password='$pass'
		");
	}

	function is_valid($u, $p)
	{
		return $this->db->get_where(
			'ms_users',
			array(
				'username' 	=> $u,
				'password'	=> $p
			)
		);
	}

	function list_user()
	{
		$ap_id_user = $this->session->userdata('ap_id_user');
		$roles_id = $this->session->userdata('roles_id');

		$where = ($roles_id == '1') ? "" : " AND a.ms_roles_id='$roles_id' ";
		return $this->db->query("
		SELECT a.*, b.role_name, GROUP_CONCAT(t3.site_name ORDER BY c.id ASC) AS region_name FROM ms_users a
		LEFT JOIN ms_roles b ON a.ms_roles_id=b.id
		LEFT JOIN `ms_user_regions` c ON a.`id`=c.`ms_users_id`
		LEFT JOIN ms_regions t3 ON t3.id = c.ms_regions_id
		WHERE c.ms_regions_id IN(SELECT ms_regions_id FROM ms_user_regions WHERE ms_users_id='$ap_id_user') $where
		GROUP BY a.id
		")->result();
	}

	function region()
	{
		$ap_id_user = $this->session->userdata('ap_id_user');

		return $this->db->query("
		SELECT a.id, a.site_name 
		FROM ms_regions a
		LEFT JOIN ms_user_regions b ON a.id=b.`ms_regions_id`
		WHERE b.`ms_users_id`='$ap_id_user'
		")->result();
	}

	function region_detail($ms_users_id)
	{
		$ap_id_user = $this->session->userdata('ap_id_user');
		return $this->db->query("
		SELECT a.id, a.`site_name`, 
		COALESCE (
		(
			SELECT ms_regions_id FROM ms_user_regions WHERE `ms_regions_id`=a.id AND `ms_users_id`='$ms_users_id'
		), '99') AS pilih
		FROM `ms_regions` a
		LEFT JOIN ms_user_regions b ON a.id=b.`ms_regions_id`
		WHERE b.`ms_users_id`='$ap_id_user'
		")->result();
	}

	function role()
	{
		$roles_id = $this->session->userdata('roles_id');
		$where = ($roles_id == '1') ? "" : "where id<>'1'";
		return $this->db->query("
		select id, role_name from ms_roles $where
		")->result();
	}

	function simpan($body, $site)
	{
		$this->db->trans_begin();
		$this->db->insert('ms_users', $body);

		$user = $this->db->query('select max(id) as id from ms_users')->row();
		$ms_users_id = $user->id;

		for ($i = 0; $i < sizeof($site); $i++) {
			$detail = array(
				'ms_users_id' 	=> $ms_users_id,
				'ms_regions_id' => $site[$i]
			);

			$this->db->insert('ms_user_regions', $detail);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function detail_user($id)
	{
		return $this->db->query("
		SELECT a.*, b.id as role_id, b.role_name FROM ms_users a
		LEFT JOIN ms_roles b ON a.ms_roles_id=b.id
		where a.id='$id'
		")->row();
	}

	function edit($body, $site, $id_user)
	{
		$this->db->trans_begin();
		$this->db->delete('ms_users', array('id' => $id_user));
		$this->db->delete('ms_user_regions', array('ms_users_id' => $id_user));
		$this->db->insert('ms_users', $body);
		//$user=$this->db->query('select max(id) as id from ms_users')->row();
		//$ms_users_id=$user->id;
		$ms_users_id = $id_user;

		for ($i = 0; $i < sizeof($site); $i++) {
			$detail = array(
				'ms_users_id' 	=> $ms_users_id,
				'ms_regions_id' => $site[$i]
			);

			$this->db->insert('ms_user_regions', $detail);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function hapus($id_user)
	{
		$this->db->trans_begin();
		$this->db->delete('ms_users', array('id' => $id_user));
		$this->db->delete('ms_user_regions', array('ms_users_id' => $id_user));

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function menu($roles_id)
	{
		return $this->db->query("
		SELECT a.* 
		FROM ms_menus a
		LEFT JOIN ms_accesscontrols b ON a.id=b.ms_menus_id
		WHERE a.parent='0' AND b.ms_roles_id='$roles_id' AND b.view='1'
		ORDER BY a.order ASC;
		")->result();
	}

	function submenu($roles_id)
	{
		return $this->db->query("
		SELECT a.* 
		FROM ms_menus a
		LEFT JOIN ms_accesscontrols b ON a.id=b.ms_menus_id
		WHERE a.parent<>'0' AND b.ms_roles_id='$roles_id' AND b.view='1'
		ORDER BY a.order ASC;
		")->result();
	}

	function cek_data($username, $email)
	{
		return $this->db->query("
		SELECT * FROM ms_users where username='$username' or email='$email';
		")->result();
	}

	function list_region($ms_users_id)
	{
		return $this->db->query("
		SELECT b.`app_name` as site_name, b.`logo_site`
		FROM `ms_user_regions` a 
		LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
		WHERE a.ms_users_id='$ms_users_id'
		ORDER BY a.id ASC
		")->result();
	}
}
