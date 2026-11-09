<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rpt_pembayaran_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_keu_active() {
		$query = $this->db->query("SELECT tahun FROM ms_tahun_ajaran_keu WHERE status='1'");
		return $query->row(0)->tahun;
	}
	
	public function fakultas_list() {
		$query = $this->db->query("SELECT * FROM ms_fakultas ORDER BY fakultas ASC");
		return $query;
	}
	
	public function jurusan_list($data) {
		$query = $this->db->query("SELECT * FROM ms_jurusan WHERE fakultas_id='".$data['idfak']."' ORDER BY jurusan ASC");
		return $query;
	}
	
	public function datasiswa($fak,$jur,$ta) {
		$q = "SELECT DISTINCT d.id, a.id_formulir, a.no_formulir, a.nama_depan, a.nama_keluarga, 
		b.fakultas_id, b.jurusan_id, b.tahun_ajaran, 
		d.jenis_bayar, d.uang_pangkal, d.uang_semester, d.jatuh_tempo, d.detail_pembayaran  
		FROM trx_biodata_siswa a
		LEFT JOIN trx_formulir b
		ON a.no_formulir=b.no_formulir
		LEFT JOIN trx_biodata_pembayaran d
		ON a.no_formulir=d.no_formulir
		WHERE b.fakultas_id='".$fak."'
		AND b.jurusan_id='".$jur."'
		AND b.tahun_ajaran='".$ta."'
		ORDER BY a.no_formulir ASC";
		//echo $q;
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_usm($fak,$jur,$ta) {
		$q = "SELECT a.id, a.tahun_ajaran, a.id_jurusan, b.no_form, b.id_set_usm_makul, b.no_set_usm_makul, b.nilai_makul 
		FROM trx_usm a
		JOIN trx_usm_detail b
		ON a.id=b.id_header
		WHERE a.tahun_ajaran='".$ta."'
		AND a.id_fakultas='".$fak."'
		AND a.id_jurusan='".$jur."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_pembayaran($fak,$jur,$ta) {
		$q = "SELECT a.id, a.id_trx_bio_byr, a.no_formulir, a.bayar, a.no_trx, a.tgl_bayar
		FROM trx_pembayaran a
		JOIN trx_formulir c
		ON a.no_formulir=c.no_formulir
		WHERE c.fakultas_id='".$fak."'
		AND c.jurusan_id='".$jur."'
		AND c.tahun_ajaran='".$ta."'
		";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_pembayaran($data) {
		for($a=0; $a<count($data['txtIdTrx']); $a++) {
			$c = "SELECT id,id_trx_bio_byr FROM trx_pembayaran WHERE id_trx_bio_byr='".$data['txtIdTrx'][$a]."'";
			$cQuery = $this->db->query($c);
			
			if($cQuery->num_rows()>0) {
				$q = "UPDATE trx_pembayaran SET 
				bayar='".$data['txtBayar'][$a]."',
				no_trx='".$data['txtNoTrx'][$a]."',
				tgl_bayar='".$data['txtTglByr'][$a]."',
				user_input='".$this->session->userdata('pmb_userid')."',
				tgl_input=now()
				WHERE id_trx_bio_byr='".$cQuery->row(0)->id_trx_bio_byr."'";
				$query = $this->db->query($q);
			} else {
				$q = "INSERT INTO trx_pembayaran (id_trx_bio_byr, no_formulir, bayar, no_trx, tgl_bayar, user_input, tgl_input)
				VALUES (
				'".$data['txtIdTrx'][$a]."',
				'".$data['txtNoForm'][$a]."',
				'".$data['txtBayar'][$a]."',
				'".$data['txtNoTrx'][$a]."',
				'".$data['txtTglByr'][$a]."',
				'".$this->session->userdata('pmb_userid')."',
				now()
				)";
				$query = $this->db->query($q);
			}
		}
		
		return $query;
	}
	
}
?>