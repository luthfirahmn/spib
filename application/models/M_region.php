<?php class M_region extends CI_Model{ 

	function regional(){ 
      return $this->db->query("
         SELECT a.*, 
         (SELECT COUNT(*) FROM `ms_user_regions` WHERE ms_regions_id=a.id) AS qty_user 
         FROM `ms_regions` a
      ")->result();
   }
	
   function detail_regional($id){ 
      return $this->db->get_where('ms_regions', array('id' => $id))->row();
   }
}
?>