<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rpt_jualform_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_humas_active() {
		$query = $this->db->query("SELECT tahun FROM ms_tahun_ajaran WHERE status='1'");
		return $query->row(0)->tahun;
	}
	
	public function fakultas_list() {
		$query = $this->db->query("SELECT * FROM ms_fakultas ORDER BY fakultas ASC");
		return $query;
	}
	
	public function jurusan_list($data) {
		$q="SELECT * FROM ms_jurusan WHERE fakultas_id='".$data['idfak']."' ORDER BY jurusan ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function tampil_rekap_jualform($fak,$jur,$ta) {
		$q = "SELECT a.id, a.tanggal_beli, a.no_formulir, a.fakultas_id, b.fakultas, a.jurusan_id, c.jurusan, a.tahun_ajaran, count(a.jurusan_id) as jmldata 
		FROM trx_formulir a
		JOIN ms_fakultas b
		ON a.fakultas_id=b.id
		JOIN ms_jurusan c
		ON a.jurusan_id=c.id
		WHERE a.tahun_ajaran='".$ta."'";
		
		if($fak != "semua") {
			$q .= " AND a.fakultas_id='".$fak."'";
		}
		if($jur != "semua") {
			$q .= " AND a.jurusan_id='".$jur."'";
		}
		$q .= " GROUP BY a.jurusan_id 
		ORDER BY a.fakultas_id ASC, a.jurusan_id ASC, a.tanggal_beli ASC";
		//echo $q;
		$query = $this->db->query($q);
		return $query;
	}
	
	public function tampil_detail_jualform($fak,$jur,$ta) {
		$q = "SELECT a.id, a.tanggal_beli, a.no_formulir, a.fakultas_id, b.fakultas, a.jurusan_id, c.jurusan, a.tahun_ajaran 
		FROM trx_formulir a
		JOIN ms_fakultas b
		ON a.fakultas_id=b.id
		JOIN ms_jurusan c
		ON a.jurusan_id=c.id
		WHERE a.tahun_ajaran='".$ta."'";
		
		if($fak != "semua") {
			$q .= " AND a.fakultas_id='".$fak."'";
		}
		if($jur != "semua") {
			$q .= " AND a.jurusan_id='".$jur."'";
		}
		$q .= "ORDER BY a.fakultas_id ASC, a.jurusan_id ASC, a.tanggal_beli ASC";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function biaya_form($ta) {
		$q = "SELECT tahun_ajaran, biaya FROM ms_biaya_formulir WHERE tahun_ajaran='".$ta."'";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>