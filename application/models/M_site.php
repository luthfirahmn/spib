<?php class M_site extends CI_Model{ 

	function site($ap_id_user){ 
      return $this->db->query("SELECT a.* FROM ms_sites a
	  LEFT JOIN `ms_user_regions` b ON a.`ms_regions_id`=b.ms_regions_id
	  WHERE b.`ms_users_id`='$ap_id_user'")->row();
   }
	
   function provinsi(){
		return $this->db->query("SELECT * FROM wilayah WHERE LENGTH(kode)=2")->result();
	}

   	function kota($kode){
		$query=$this->db->query("SELECT * FROM wilayah WHERE LENGTH(kode)=4 AND kode LIKE '$kode%'");
		return json_encode(array('result' => $query->result()));
	}

	function kecamatan($kode)
	{
		$query=$this->db->query("SELECT * FROM wilayah WHERE LENGTH(kode)=6 AND kode LIKE '$kode%'");
		return json_encode(array('result' => $query->result()));
	}
	
	function kelurahan($kode)
	{
		$query=$this->db->query("SELECT * FROM wilayah WHERE LENGTH(kode)=10 AND kode LIKE '$kode%'");
		return json_encode(array('result' => $query->result()));
	}
}
?>