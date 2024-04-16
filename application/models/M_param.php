<?php class M_param extends CI_Model{ 

	function param(){ 
      return $this->db->get('parameter')->row();
   }
	
}
?>