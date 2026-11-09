<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Trx_siswabatal_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function ta_active() {
		$q="SELECT * FROM ms_tahun_ajaran WHERE status='1' ORDER BY tahun ASC";
		$query = $this->db->query($q);
		return $query->row(0);
	}
	
	public function data_mahasiswa($dataTA) {
		$q = "SELECT a.id_formulir, a.no_formulir, a.nim, a.nama_keluarga, a.nama_depan, b.tahun_ajaran 
		FROM trx_biodata_siswa a
		LEFT JOIN trx_formulir b
		ON a.no_formulir=b.no_formulir
		WHERE b.tahun_ajaran='".$dataTA->tahun."'";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_fakjur($data) {
		$q="SELECT a.fakultas_id, b.fakultas, a.jurusan_id, c.jurusan 
		FROM trx_formulir a
		LEFT JOIN ms_fakultas b
		ON a.fakultas_id=b.id
		LEFT JOIN ms_jurusan c
		ON a.jurusan_id=c.id
		WHERE a.no_formulir='".$data['noform']."'";
		$query = $this->db->query($q);
		
		return $query;
	}
	
	public function simpan_siswabatal($data) {
		$c = "SELECT id FROM trx_mhs_batal WHERE noform='".$data['txtNoform']."'";
		$Cuery = $this->db->query($c);
		if($Cuery->num_rows()>0) {
			return 2;
		} else {
			$q="INSERT INTO trx_mhs_batal (noform, alasan, user_input, date_input)
			VALUES ('".$data['txtNoform']."','".$data['txtAlasan']."','".$this->session->userdata('pmb_userid')."',now())";
			$query = $this->db->query($q);
			return $query;
		}
	}
	
	public function mhs_batal($ta) {
		$q = "SELECT a.noform, a.alasan, a.date_input, b.nama_calon, b.tahun_ajaran 
		FROM trx_mhs_batal a
		LEFT JOIN trx_formulir b
		ON a.noform=b.no_formulir
		WHERE b.tahun_ajaran='".$ta."'";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>