<?php
defined('BASEPATH') or exit('No direct script access allowed');
class InstrumentData extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_data');
		$this->load->model('M_instrumentData');
		$this->load->model('M_akses');
		$this->load->dbutil();
		$this->load->database();
	}

	public function index()
	{
		$roles_id = $this->session->userdata('roles_id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['hak_akses'] = $this->M_akses->hak_akses($roles_id, 'InstrumentData');
		// $data['instrument'] = $this->M_instrumentData->instrument($ap_id_user);
		$data['region'] = $this->M_data->region($ap_id_user);
		$this->load->view('instrumentdata/index', $data);
	}
	public function list()
	{
		$ap_id_user  = $this->session->userdata('ap_id_user');
		$site_id = $this->input->post("ms_regions_id");
		$roles_id = $this->session->userdata('roles_id');

		$data['hak_akses'] = $this->M_akses->hak_akses($roles_id, 'InstrumentData');

		// Get DataTables parameters
		$draw = $this->input->post('draw');
		$start = $this->input->post('start');
		$length = $this->input->post('length');
		$search = $this->input->post('search')['value'];
		$order = $this->input->post('order');

		// Build query with pagination and search
		$this->db->select('a.*, b.site_name, c.name, d.nama_stasiun');
		$this->db->from('tr_instrument a');
		$this->db->join('ms_regions b', 'a.ms_regions_id = b.id');
		$this->db->join('tr_instrument_type c', 'a.tr_instrument_type_id = c.id');
		$this->db->join('ms_stasiun d', 'a.ms_stasiun_id = d.id');
		$this->db->join('ms_user_regions e', 'a.ms_regions_id = e.ms_regions_id');
		$this->db->where('e.ms_users_id', $ap_id_user);
		$this->db->where('b.id', $site_id);
		$this->db->group_by('a.id');

		// Apply search filter
		if ($search) {

			$this->db->like('a.kode_instrument', $search);
			$this->db->or_like('a.nama_instrument', $search);
			$this->db->or_like('b.site_name', $search);
			$this->db->or_like('c.name', $search);
			$this->db->or_like('d.nama_stasiun', $search);
		}

		// Apply ordering
		if (isset($order)) {
			foreach ($order as $ord) {

				$this->db->order_by("CASE WHEN b.id = 5 THEN 0 ELSE 1 END", '', FALSE);
				$this->db->order_by('b.id', 'ASC');
				$this->db->order_by($ord['column'], $ord['dir']);
			}
		}

		// Apply pagination
		$this->db->limit($length, $start);
		$query = $this->db->get();

		// Get total records without filtering
		$this->db->from('tr_instrument a');
		$this->db->join('ms_regions b', 'a.ms_regions_id = b.id');
		$this->db->join('tr_instrument_type c', 'a.tr_instrument_type_id = c.id');
		$this->db->join('ms_stasiun d', 'a.ms_stasiun_id = d.id');
		$this->db->join('ms_user_regions e', 'a.ms_regions_id = e.ms_regions_id');
		$this->db->where('e.ms_users_id', $ap_id_user);
		$this->db->where('b.id', $site_id);

		$total_records = $this->db->count_all_results();

		// Get filtered records
		$this->db->from('tr_instrument a');
		$this->db->join('ms_regions b', 'a.ms_regions_id = b.id');
		$this->db->join('tr_instrument_type c', 'a.tr_instrument_type_id = c.id');
		$this->db->join('ms_stasiun d', 'a.ms_stasiun_id = d.id');
		$this->db->join('ms_user_regions e', 'a.ms_regions_id = e.ms_regions_id');
		$this->db->where('e.ms_users_id', $ap_id_user);
		$this->db->where('b.id', $site_id);

		if ($search) {
			$this->db->like('a.kode_instrument', $search);
			$this->db->or_like('a.nama_instrument', $search);
			$this->db->or_like('b.site_name', $search);
			$this->db->or_like('c.name', $search);
			$this->db->or_like('d.nama_stasiun', $search);
		}

		$filtered_records = $this->db->count_all_results();

		$data = array();
		foreach ($query->result() as $row) {
			$db_site = $this->change_connection($row->ms_regions_id);

			$total_data = $db_site->query("SELECT COUNT(*) total_data FROM data WHERE data.kode_instrument = '{$row->kode_instrument}'")->row();
			$row->total_data = $total_data->total_data;

			$data_terkhir = $db_site->query("SELECT tanggal, jam FROM data WHERE data.kode_instrument = '{$row->kode_instrument}' ORDER BY tanggal DESC, jam DESC LIMIT 1")->row();

			if ($data_terkhir) {
				$datetime_string = $data_terkhir->tanggal . ' ' . $data_terkhir->jam;
				$datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime_string);
				$row->data_terakhir_masuk = $datetime->format('d M Y H:i');
			} else {
				$row->data_terakhir_masuk = 'No data found';
			}

			$data[] = array(
				'id' => $row->id,
				'region_name' => $row->site_name,
				'kode_instrument' => $row->kode_instrument,
				'nama_instrument' => $row->nama_instrument,
				'instrument_type' => $row->name,
				'nama_stasiun' => $row->nama_stasiun,
				'total_data' => $row->total_data,
				'data_terakhir_masuk' => $row->data_terakhir_masuk
			);
		}

		// Prepare JSON response
		$response = array(
			'draw' => intval($draw),
			'recordsTotal' => $total_records,
			'recordsFiltered' => $filtered_records,
			'data' => $data
		);

		echo json_encode($response);
	}


	public function tambah()
	{
		$this->db->truncate('temp_sensor');
		$this->db->truncate('temp_koefisien');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['region'] = $this->M_instrumentData->region($ap_id_user);
		$data['type'] = $this->M_instrumentData->type();
		$data['sensor'] = $this->M_instrumentData->sensor($ap_id_user);
		$this->load->view('instrumentdata/tambah', $data);
	}

	public function get_sensor($regions_id)
	{
		$sensor_data = $this->M_instrumentData->get_sensor($regions_id);

		echo json_encode($sensor_data);
	}

	public function getTypeStation()
	{
		$ms_regions_id  = $this->input->get('kode');
		echo $this->M_instrumentData->getTypeStation($ms_regions_id);
	}

	public function getNameType()
	{
		$id  = $this->input->get('kode');
		echo $this->M_instrumentData->getNameType($id);
	}

	public function getPositionStation()
	{
		$id  = $this->input->get('kode');
		echo $this->M_instrumentData->getPositionStation($id);
	}

	public function tempKoefisien()
	{
		$jenis_sensor 			= $this->input->post('modal_sensor');
		$jenis_sensor_mentah 	= $this->input->post('modal_data_mentah');
		$jenis_sensor_jadi	 	= $this->input->post('modal_data_jadi');

		$tr_instrument_type_id 	= $this->input->post('modal_type');
		$parameter = $this->M_instrumentData->parameter($tr_instrument_type_id);

		$opt = array();

		foreach ($parameter as $result) {
			foreach ($result as $key => $value) {
				$opt[$value] = $this->input->post($value);
			}
		}


		$data_parameter = json_encode($opt);

		$data = array(
			'ap_id_user' 			=> $this->session->userdata('ap_id_user'),
			'tr_instrument_type_id' => $this->input->post('modal_type'),
			'tmaw' 					=> $this->input->post('modal_tmaw'),
			'tmas' 					=> $this->input->post('modal_tmas'),
			'parameter'				=> $data_parameter
		);

		$status = $this->M_instrumentData->simpan_temp($data, $jenis_sensor, $jenis_sensor_mentah, $jenis_sensor_jadi);

		$notif = 'Sukses';
		echo $notif;
	}

	public function editTempKoefisien()
	{
		$id 					= $this->input->post('idTemptEdit');
		$jenis_sensor 			= $this->input->post('modal_sensor');
		$jenis_sensor_mentah 	= $this->input->post('modal_data_mentah');
		$jenis_sensor_jadi	 	= $this->input->post('modal_data_jadi');

		$tr_instrument_type_id 	= $this->input->post('modal_type');
		// pre($tr_instrument_type_id);
		$parameter = $this->M_instrumentData->parameter($tr_instrument_type_id);

		$opt = array();

		foreach ($parameter as $result) {
			foreach ($result as $key => $value) {
				$opt[$value] = $this->input->post($value);
			}
		}

		// print_r($opt);
		$data_parameter = json_encode($opt);

		$data = array(
			'ap_id_user' 			=> $this->session->userdata('ap_id_user'),
			'tr_instrument_type_id' => $this->input->post('modal_type'),
			'tmaw' 					=> $this->input->post('modal_tmaw'),
			'tmas' 					=> $this->input->post('modal_tmas'),
			'parameter'				=> $data_parameter
		);

		$status = $this->M_instrumentData->edit_temp($data, $jenis_sensor, $jenis_sensor_mentah, $jenis_sensor_jadi, $id);

		$notif = 'Sukses';
		echo $notif;
	}

	public function parameter()
	{
		$ap_id_user = $this->session->userdata('ap_id_user');
		$tr_instrument_type_id 	= $this->input->post('tr_instrument_type_id');
		$data['action'] 		= $this->input->post('action');
		$data['parameter']		= $this->M_instrumentData->parameter($tr_instrument_type_id);
		$data['sensor']			= $this->M_instrumentData->sensor($ap_id_user);
		$dataparameter = array(
			'rc'		=> '00',
			'err_desc'	=> 'Sukses',
			'tabel'		=> $this->load->view('instrumentdata/data_parameter', $data, true)
		);

		echo json_encode($dataparameter);
	}

	public function dataTempKoefisien()
	{
		$data['koefisien'] = $this->M_instrumentData->dataTempKoefisien();
		$datakoefisien = array(
			'rc'		=> '00',
			'err_desc'	=> 'Sukses',
			'tabel'		=> $this->load->view('instrumentdata/data_koefisien', $data, true)
		);

		echo json_encode($datakoefisien);
	}

	public function hapusTempKoefisien()
	{
		$id  = $this->input->get('id');
		$status = $this->M_instrumentData->hapus_temp($id);
		echo $status;
	}


	public function tambah_proses()
	{
		$tr_instrument_type_id = $this->input->post('tr_instrument_type_id');
		$type = $this->db->get_where('tr_instrument_type', array('id' => $tr_instrument_type_id))->row();
		$name_type = $type->type;

		$tr_instrument = array(
			'ms_regions_id' 			=> $this->input->post('ms_regions_id'),
			'kode_instrument'			=> $this->input->post('kode_instrument'),
			'nama_instrument'			=> $this->input->post('nama_instrument'),
			'tr_instrument_type_id'		=> $this->input->post('tr_instrument_type_id'),
			'ms_stasiun_id'				=> $this->input->post('ms_stasiun_id'),
			'tahun_pembuatan'			=> $this->input->post('tahun_pembuatan'),
			'tr_instrument_sensor_id'	=> $this->input->post('tr_instrument_sensor_id'),
			'latitude'					=> $this->input->post('latitude'),
			'longitude'					=> $this->input->post('longitude'),
			'created_at'				=> date("Y-m-d h:i:sa"),
			'updated_at'				=> date("Y-m-d h:i:sa")
		);

		$tr_instrument_instalasi = array(
			'zona_pemasangan'				=> $this->input->post('zona_pemasangan'),
			'latitude'						=> $this->input->post('latitude'),
			'longitude'						=> $this->input->post('longitude'),
			'tanggal_rekalibrasi'			=> $this->input->post('tanggal_rekalibrasi'),
			'tanggal_instalasi'				=> $this->input->post('tanggal_instalasi'),
			'tanggal_zero_reading'			=> $this->input->post('tanggal_zero_reading'),
			'elevasi_puncak'				=> $this->input->post('elevasi_puncak'),
			'elevasi_permukaan_saat_ini'	=> $this->input->post('elevasi_permukaan_saat_ini'),
			'elevasi_sensor'				=> $this->input->post('elevasi_sensor'),
			'elevasi_ground_water_level'	=> $this->input->post('elevasi_ground_water_level'),
			'kedalaman_sensor'				=> $this->input->post('kedalaman_sensor')
		);

		$tr_instrument_sensor = array(
			'nama_sensor'	=> $this->input->post('nama_sensor'),
			'serial_number'	=> $this->input->post('serial_number'),
			'range'			=> $this->input->post('range'),
			'output'		=> $this->input->post('output')
		);

		$status = $this->M_instrumentData->simpan($tr_instrument, $tr_instrument_instalasi, $name_type, $tr_instrument_sensor);
		if ($status) {
			$this->session->set_flashdata('warning', 'Sukses!');
		} else {
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		redirect('InstrumentData');
	}

	function hapus()
	{
		$id = $this->input->get('id');
		$status = $this->M_instrumentData->hapus($id);
		if ($status) {
			$this->session->set_flashdata('warning', 'Sukses!');
		} else {
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		redirect('InstrumentData');
	}

	public function edit()
	{
		$this->db->truncate('temp_sensor');
		$this->db->truncate('temp_koefisien');
		$id = $this->input->get('id');
		$ap_id_user = $this->session->userdata('ap_id_user');
		$data['detail'] = $this->M_instrumentData->instrument_detail($id);
		$data['region'] = $this->M_instrumentData->region($ap_id_user);
		$data['typeinstrument'] = $this->M_instrumentData->type();
		$data['sensor'] = $this->M_instrumentData->sensor($ap_id_user);

		$this->load->view('instrumentdata/edit', $data);
	}


	public function edit_proses()
	{
		$id = $this->input->post('id');
		$tr_instrument_type_id = $this->input->post('tr_instrument_type_id');
		$type = $this->db->get_where('tr_instrument_type', array('id' => $tr_instrument_type_id))->row();
		$name_type = $type->type;

		$tr_instrument = array(
			'ms_regions_id' 			=> $this->input->post('ms_regions_id'),
			'kode_instrument'			=> $this->input->post('kode_instrument'),
			'nama_instrument'			=> $this->input->post('nama_instrument'),
			'tr_instrument_type_id'		=> $this->input->post('tr_instrument_type_id'),
			'ms_stasiun_id'				=> $this->input->post('ms_stasiun_id'),
			'tahun_pembuatan'			=> $this->input->post('tahun_pembuatan'),
			'tr_instrument_sensor_id'	=> $this->input->post('tr_instrument_sensor_id'),
			'latitude'					=> $this->input->post('latitude'),
			'longitude'					=> $this->input->post('longitude'),
			'created_at'				=> date("Y-m-d h:i:sa"),
			'updated_at'				=> date("Y-m-d h:i:sa")
		);

		$tr_instrument_instalasi = array(
			'zona_pemasangan'				=> $this->input->post('zona_pemasangan'),
			'latitude'						=> $this->input->post('latitude'),
			'longitude'						=> $this->input->post('longitude'),
			'tanggal_rekalibrasi'			=> $this->input->post('tanggal_rekalibrasi'),
			'tanggal_instalasi'				=> $this->input->post('tanggal_instalasi'),
			'tanggal_zero_reading'			=> $this->input->post('tanggal_zero_reading'),
			'elevasi_puncak'				=> $this->input->post('elevasi_puncak'),
			'elevasi_permukaan_saat_ini'	=> $this->input->post('elevasi_permukaan_saat_ini'),
			'elevasi_sensor'				=> $this->input->post('elevasi_sensor'),
			'elevasi_ground_water_level'	=> $this->input->post('elevasi_ground_water_level'),
			'kedalaman_sensor'				=> $this->input->post('kedalaman_sensor')
		);

		$tr_instrument_sensor = array(
			'nama_sensor'	=> $this->input->post('nama_sensor'),
			'serial_number'	=> $this->input->post('serial_number'),
			'range'			=> $this->input->post('range'),
			'output'		=> $this->input->post('output')
		);

		$status = $this->M_instrumentData->edit($tr_instrument, $tr_instrument_instalasi, $name_type, $tr_instrument_sensor, $id);
		if ($status) {
			$this->session->set_flashdata('warning', 'Sukses!');
		} else {
			$this->session->set_flashdata('warning', 'Gagal!');
		}
		redirect('InstrumentData');
	}

	public function detailTempKoefisien()
	{
		$id = $this->input->get('id');
		$regions_id = $this->input->get('regions_id');
		$data = $this->M_instrumentData->detailTempKoefisien($id, $regions_id);
		$data['tabel_parameter'] = $this->load->view('instrumentdata/data_parameter_edit', $data, true);
		echo json_encode($data);

		// $datakoefisien=array(
		// 	'rc'		=>'00',
		// 	'err_desc'	=>'Sukses',					
		// 	'tabel'		=> $this->load->view('instrumentdata/edit_koefisien', $data, true)
		// );

		// echo json_encode($datakoefisien);
	}

	public function formkoefisien()
	{
		$ap_id_user 	= $this->session->userdata('ap_id_user');
		$data['region']	= $this->M_instrumentData->region($ap_id_user);
		$data['type']	= $this->M_instrumentData->type();
		$data['sensor']	= $this->M_instrumentData->sensor($ap_id_user);
		$tabel			= $this->load->view('instrumentdata/form_koefisien', $data, true);

		echo $tabel;
	}


	function change_connection($id_regions)
	{

		$query = $this->db->query("SELECT * FROM ms_regions WHERE id = '$id_regions'");
		$result = $query->row();

		$second_db_params = $this->switchDatabase($result->database_host, $result->database_username, $result->database_password, $result->database_name, $result->database_port);
		$this->db2 = $this->load->database($second_db_params, TRUE);

		if ($this->db2->initialize()) {
			return $this->db2;
		} else {
			return false;
		}
	}
}
