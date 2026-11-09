<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	$string = addslashes($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_namamand_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_pci_active() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci WHERE status='1'");
		return $query->row(0)->tahun;
	}
	
	public function fakultas_list() {
		$q="SELECT id, fakultas FROM ms_fakultas";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function jurusan_list($data) {
		$query = $this->db->query("SELECT * FROM ms_jurusan WHERE fakultas_id='".$data['idfak']."' ORDER BY jurusan ASC");
		return $query;
	}
	
	public function data_siswa($fak,$jur,$ta) {
		$q = "SELECT t.* FROM (
		SELECT a.id, a.id_formulir, a.no_formulir, a.nim, a.nama_depan, a.nama_keluarga, a.nama_tionghoa, a.telp_baru
		FROM trx_biodata_siswa a
		UNION ALL
		SELECT c.id, c.id_formulir, c.no_formulir, c.nim, c.nama_depan, c.nama_keluarga, c.nama_tionghoa, c.telp_baru
		FROM trx_biodata_siswa_temp c
		) as t
		WHERE t.no_formulir!=''
		GROUP BY t.no_formulir
		ORDER BY t.no_formulir ASC
		";

		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_namamand($data) {
		$statSimpan=0;
		for($a=0; $a< count($data['txtNoform']); $a++) {
			$q= "UPDATE trx_biodata_siswa SET 
			nama_tionghoa='".$data['txtNmTio'][$a]."',
			telp_baru='".$data['txtTelpBaru'][$a]."'			
			WHERE no_formulir='".$data['txtNoform'][$a]."'";
			$query = $this->db->query($q);
			$statSimpan = $query;
			
			$q2 = "UPDATE trx_biodata_siswa_temp SET 
			nama_tionghoa='".$data['txtNmTio'][$a]."',
			telp_baru='".$data['txtTelpBaru'][$a]."'			
			WHERE no_formulir='".$data['txtNoform'][$a]."'";
			$query2 = $this->db->query($q2);
			$statSimpan = $query2;
		}
		return $statSimpan;
	}
	
	
}