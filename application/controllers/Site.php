<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Site extends MY_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_site');
		$this->load->dbutil();
		$this->load->database();
	}
	
	public function index(){
		$site=$this->M_site->site();
		$kota=json_decode($this->M_site->kota($site->provinsi));
		$data['kota']=$kota->result;
		$kecamatan=json_decode($this->M_site->kecamatan($site->kabupaten));
		$data['kecamatan']=$kecamatan->result;
		$kelurahan=json_decode($this->M_site->kelurahan($site->kecamatan));
		$data['kelurahan']=$kelurahan->result;
		$data['site']=$site;
		$data['provinsi']=$this->M_site->provinsi();
		$this->load->view('site/index', $data);
	}

	public function kota()
	{
		$kode  = $this->input->get('kode');
		echo $this->M_site->kota($kode);
	}

	public function kecamatan()
	{
		$kode  = $this->input->get('kode');
		echo $this->M_site->kecamatan($kode);
	}

	public function kelurahan()
	{
		$kode  = $this->input->get('kode');
		echo $this->M_site->kelurahan($kode);
	}

	public function edit_proses(){
		$id='1';
		$body=array(
			'site_name' 		=> $this->input->post('site_name'),
			'desa' 	=> $this->input->post('kelurahan'),
			'kecamatan'		=> $this->input->post('kecamatan'),
			'kabupaten'		=> $this->input->post('kota'),
			'provinsi'	=> $this->input->post('provinsi'),
			'elev_tanggul_utama'	=> $this->input->post('elev_tanggul_utama'),
			'elev_tanggul_pembantu'			=> $this->input->post('elev_tanggul_pembantu'),
			'elev_pelimpah'			=> $this->input->post('elev_pelimpah'),
			'elev_pelimpah_pembantu'			=> $this->input->post('elev_pelimpah_pembantu'),
			'elev_normal'			=> $this->input->post('elev_normal'),
			'elev_siaga3'			=> $this->input->post('elev_siaga3'),
			'elev_siaga2'			=> $this->input->post('elev_siaga2'),
			'elev_siaga1'			=> $this->input->post('elev_siaga1'),
			'batas_kritis_vwp'			=> $this->input->post('batas_kritis_vwp')
		);

		$this->db->where('ms_regions_id', $id);
		$this->db->update('ms_sites', $body);
		redirect('Site');
	}

}