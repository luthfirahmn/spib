<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Site extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Data_model');
		$this->load->model('M_site');
		$this->load->model('M_akses');
		$this->load->dbutil();
		$this->load->database();
	}

	public function index()
	{
		$ap_id_user 	= $this->session->userdata('ap_id_user');
		$roles_id = $this->session->userdata('roles_id');
		$data['hak_akses'] = $this->M_akses->hak_akses($roles_id, 'Site');
		$data['site']	= $this->M_site->site($ap_id_user);
		$this->load->view('site/index', $data);
	}

	public function detail()
	{
		$idsite  = $this->input->get('id');
		$ms_regions_id  = $this->input->get('region');

		if ($idsite == null) {
			$data['provinsi'] = $this->M_site->provinsi();
			$data['ms_regions_id'] = $ms_regions_id;
			$this->load->view('site/add', $data);
		} else {
			$site = $this->M_site->detailsite($idsite);
			$kota = json_decode($this->M_site->kota($site->provinsi));
			$data['kota'] = $kota->result;
			$kecamatan = json_decode($this->M_site->kecamatan($site->kabupaten));
			$data['kecamatan'] = $kecamatan->result;
			$kelurahan = json_decode($this->M_site->kelurahan($site->kecamatan));
			$data['kelurahan'] = $kelurahan->result;
			$data['site'] = $site;
			$data['provinsi'] = $this->M_site->provinsi();
			$this->load->view('site/detail', $data);
		}
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

	public function add_proses()
	{
		$temp = FCPATH . '/assets/upload/sensor/';

		$nama_file       = $this->input->post('site_name');
		$fileupload      = $_FILES['file']['tmp_name'];
		$ImageName       = $_FILES['file']['name'];
		$ImageType       = $_FILES['file']['type'];

		if (!empty($fileupload)) {
			$ImageExt       = substr($ImageName, strrpos($ImageName, '.'));
			$ImageExt       = str_replace('.', '', $ImageExt); // Extension
			$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
			$NewImageName   = str_replace(' ', '', $nama_file . '.' . $ImageExt);

			move_uploaded_file($_FILES["file"]["tmp_name"], $temp . $NewImageName); // Menyimpan file

			$foto = $NewImageName;
		} else {
			$foto = '';
		}

		$body = array(
			'ms_regions_id'			=> $this->input->post('ms_regions_id'),
			'site_name' 			=> $this->input->post('site_name'),
			'desa' 					=> $this->input->post('kelurahan'),
			'kecamatan'				=> $this->input->post('kecamatan'),
			'kabupaten'				=> $this->input->post('kota'),
			'provinsi'				=> $this->input->post('provinsi'),
			'elev_tanggul_utama'	=> $this->input->post('elev_tanggul_utama'),
			'elev_tanggul_pembantu'	=> $this->input->post('elev_tanggul_pembantu'),
			'elev_pelimpah'			=> $this->input->post('elev_pelimpah'),
			'elev_pelimpah_pembantu' => $this->input->post('elev_pelimpah_pembantu'),
			'elev_normal'			=> $this->input->post('elev_normal'),
			'elev_siaga3'			=> $this->input->post('elev_siaga3'),
			'elev_siaga2'			=> $this->input->post('elev_siaga2'),
			'elev_siaga1'			=> $this->input->post('elev_siaga1'),
			'batas_kritis_vwp'		=> $this->input->post('batas_kritis_vwp'),
			'foto'					=> $foto
		);

		$status = $this->db->insert('ms_sites', $body);
		redirect('Site');
	}

	public function edit_proses()
	{
		$temp = FCPATH . '/assets/upload/sensor/';

		$nama_file       = $this->input->post('site_name');
		$fileupload      = $_FILES['file']['tmp_name'];
		$ImageName       = $_FILES['file']['name'];
		$ImageType       = $_FILES['file']['type'];

		if (!empty($fileupload)) {
			$ImageExt       = substr($ImageName, strrpos($ImageName, '.'));
			$ImageExt       = str_replace('.', '', $ImageExt); // Extension
			$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
			$NewImageName   = str_replace(' ', '', $nama_file . '.' . $ImageExt);

			move_uploaded_file($_FILES["file"]["tmp_name"], $temp . $NewImageName); // Menyimpan file

			$foto = $NewImageName;
		} else {
			$foto = '';
		}

		$id = $this->input->post('idsite');
		$body = array(
			'site_name' 			=> $this->input->post('site_name'),
			'desa' 					=> $this->input->post('kelurahan'),
			'kecamatan'				=> $this->input->post('kecamatan'),
			'kabupaten'				=> $this->input->post('kota'),
			'provinsi'				=> $this->input->post('provinsi'),
			'elev_tanggul_utama'	=> $this->input->post('elev_tanggul_utama'),
			'elev_tanggul_pembantu'	=> $this->input->post('elev_tanggul_pembantu'),
			'elev_pelimpah'			=> $this->input->post('elev_pelimpah'),
			'elev_pelimpah_pembantu' => $this->input->post('elev_pelimpah_pembantu'),
			'elev_normal'			=> $this->input->post('elev_normal'),
			'elev_siaga3'			=> $this->input->post('elev_siaga3'),
			'elev_siaga2'			=> $this->input->post('elev_siaga2'),
			'elev_siaga1'			=> $this->input->post('elev_siaga1'),
			'batas_kritis_vwp'		=> $this->input->post('batas_kritis_vwp'),
			'foto'					=> $foto
		);

		$this->db->where('id', $id);
		$this->db->update('ms_sites', $body);
		redirect('Site');
	}
}
