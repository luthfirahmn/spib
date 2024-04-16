<?php class M_region extends CI_Model{ 

	function regional(){ 
      return $this->db->get('ms_regions')->result();
   }
	
   function detail_regional($id){ 
      return$this->db->get_where('ms_regions', array('id' => $id))->row();
   }
}
?>