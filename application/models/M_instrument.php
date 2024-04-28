<?php
class M_instrument extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function instrument($ap_id_user)
	{
		$get_subitem = $this->db->get('tr_instrument_type');
		foreach ($get_subitem->result() as $lp) {
			$trace_lot = $this->db->query("
			SELECT b.`site_name` FROM `tr_instrument_tp_region` a
			LEFT JOIN ms_regions b ON a.`ms_regions_id`=b.id
			WHERE a.`tr_instrument_type_id`='$lp->id'
			")->result();

			$temp_item[] = array(
				'id'		=> $lp->id,
				'name'		=> $lp->name,
				'type'		=> $lp->type,
				'region'	=> $trace_lot
			);
		}

		return $temp_item;
	}

	function instrument_detail($id)
	{
		return $this->db->get_where('tr_instrument_type', array('id' => $id))->row();
	}

	function region($ms_users_id)
	{
		return $this->db->query("
		SELECT b.id, b.site_name 
		FROM ms_user_regions a
		LEFT JOIN `ms_regions` b ON a.`ms_regions_id`=b.`id`
		WHERE ms_users_id='$ms_users_id'
		")->result();
	}

	function region_detail($tr_instrument_type_id)
	{
		return $this->db->query("
		SELECT a.id, a.`site_name`, 
		COALESCE (
		(
			SELECT ms_regions_id FROM `tr_instrument_tp_region` WHERE `ms_regions_id`=a.id AND `tr_instrument_type_id`='$tr_instrument_type_id'
		), '99') AS pilih
		FROM `ms_regions` a
		")->result();
	}

	function simpan($body, $site)
	{
		$this->db->trans_begin();
		$this->db->insert('tr_instrument_type', $body);

		$instrument = $this->db->query('select max(id) as id from tr_instrument_type')->row();
		$tr_instrument_type_id = $instrument->id;

		for ($i = 0; $i < sizeof($site); $i++) {
			$detail = array(
				'ms_regions_id' 		=> $site[$i],
				'tr_instrument_type_id' => $tr_instrument_type_id
			);

			$this->db->insert('tr_instrument_tp_region', $detail);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function edit($site, $id)
	{
		$this->db->trans_begin();

		for ($i = 0; $i < sizeof($site); $i++) {
			$query = $this->db->query("SELECT id FROM tr_instrument_tp_region WHERE ms_regions_id = {$site[$i]} AND tr_instrument_type_id={$id}");
			$count_res = $query->num_rows();
			if ($count_res < 1) {

				$detail = array(
					'ms_regions_id' 		=> $site[$i],
					'tr_instrument_type_id' => $id
				);

				$this->db->insert('tr_instrument_tp_region', $detail);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function hapus($id)
	{
		$this->db->trans_begin();
		$this->db->delete('tr_instrument_parameter', array('tr_instrument_type_id' => $id));
		$this->db->delete('tr_instrument_tp_region', array('tr_instrument_type_id' => $id));
		$this->db->delete('tr_instrument_type', array('id' => $id));

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}

	function type()
	{
		return $this->db->get_where('ms_lookup_values', array('lookup_config' => 'TYPE'))->result();
	}

	public function parameter($tr_instrument_type_id)
	{
		$query =  $this->db->get_where('tr_instrument_parameter', array('tr_instrument_type_id' => $tr_instrument_type_id));

		return json_encode(array('result' => $query->result()));
	}

	function simpan_parameter($tr_instrument_type_id, $nama_parameter)
	{
		$this->db->trans_begin();

		$this->db->delete('tr_instrument_parameter', array('tr_instrument_type_id' => $tr_instrument_type_id));

		for ($i = 0; $i < sizeof($nama_parameter); $i++) {
			$detail = array(
				'parameter_name' 		=> $nama_parameter[$i],
				'tr_instrument_type_id' => $tr_instrument_type_id
			);

			$this->db->insert('tr_instrument_parameter', $detail);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		return $this->db->trans_status();
	}
}
