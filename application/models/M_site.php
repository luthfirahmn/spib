<?php class M_site extends CI_Model
{

	function site($ap_id_user)
	{
		return $this->db->query("SELECT a.`ms_users_id`, a.`ms_regions_id`, b.id, b.`site_name`, c.`site_name` AS nama_site, b.foto
	  FROM `ms_user_regions` a
	  LEFT JOIN ms_sites b ON a.`ms_regions_id`=b.`ms_regions_id`
	  LEFT JOIN `ms_regions` c ON a.`ms_regions_id`=c.id
	  WHERE a.ms_users_id='$ap_id_user'
	  ORDER BY 
		CASE 
			WHEN c.id = 5 THEN 0 
			ELSE 1
		END,
		c.id ASC
		")->result();
	}

	function detailsite($idsite)
	{
		return $this->db->query("SELECT * FROM ms_sites a WHERE id='$idsite'")->row();
	}

	function provinsi()
	{
		return $this->db->query("SELECT * FROM wilayah WHERE LENGTH(kode)=2")->result();
	}

	function kota($kode)
	{
		$query = $this->db->query("SELECT * FROM wilayah WHERE LENGTH(kode)=4 AND kode LIKE '$kode%'");
		return json_encode(array('result' => $query->result()));
	}

	function kecamatan($kode)
	{
		$query = $this->db->query("SELECT * FROM wilayah WHERE LENGTH(kode)=6 AND kode LIKE '$kode%'");
		return json_encode(array('result' => $query->result()));
	}

	function kelurahan($kode)
	{
		$query = $this->db->query("SELECT * FROM wilayah WHERE LENGTH(kode)=10 AND kode LIKE '$kode%'");
		return json_encode(array('result' => $query->result()));
	}
}
