<?php class M_site extends CI_Model{ 

	function site(){ 
      return $this->db->query("select * from ms_sites where ms_regions_id='1'")->row();
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