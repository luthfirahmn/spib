<?php 

class Data_model extends CI_Model{
	function tampil_data(){
		return $this->db->get('transaksi');
	}
	
	public function getAll(){
        return $this->db->query("SELECT m.nama, r.*, s.tgl_surat, s.no_iduji FROM dks_rinci r
		LEFT JOIN dks s ON s.nosurat=r.nosurat
		LEFT JOIN master_nasabah m ON m.ktp=r.nik
		WHERE s.no_iduji!='' AND r.rc!='00'")->result();
    }
	
	public function master_nasabah($batch){
        return $this->db->query("SELECT m.ktp, m.nama, m.npwp, m.ktp_p, m.nama_p, m.badan_hukum, m.nama_badan_hukum, 
								m.jns_kelamin AS kelamin, m.gaji_pokok AS gaji, m.harga_rumah, m.nilai_kpr, m.suku_bunga_kpr AS suku_bunga, 
								m.tenor, m.angsuran, m.nilai_flpp, m.nama_perumahan, m.agn_alamat AS alamat, m.agn_kodepos AS kode_pos, 
								m.luas_tanah, m.luas_bangunan, m.kd_jns_kpr AS jenis_kpr, m.no_sp3k, m.tgl_sp3k, w.nama AS kota, 
								m.pekerjaan, d.no_iduji
								FROM master_nasabah m
								LEFT JOIN dks_rinci r ON m.`ktp`= r.`nik`
								LEFT JOIN dks d ON r.`nosurat`=d.`nosurat`
								LEFT JOIN wilayah w ON m.`agn_kota_kab`=w.`kode`
								WHERE m.ktp IN (SELECT nik FROM uji_nik WHERE batch_intrnal='$batch')")->result();
    }
	
	public function nasabah($nik){
        return $this->db->query("SELECT * FROM master_nasabah WHERE ktp IN '$nik'")->result();
    }
	
	public function dashboard(){
        return $this->db->query("SELECT * FROM dashboard")->row();
    }
}