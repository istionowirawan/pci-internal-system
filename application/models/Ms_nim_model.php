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

class Ms_nim_model extends CI_Model {
	
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
		$q = "SELECT a.id, a.id_formulir, a.no_formulir, a.nim, a.nama_depan, a.nama_keluarga, b.fakultas_id, b.jurusan_id, b.tahun_ajaran 
		FROM trx_biodata_siswa a
		LEFT JOIN trx_formulir b
		ON a.no_formulir=b.no_formulir
		WHERE b.fakultas_id='".$fak."'
		AND b.jurusan_id='".$jur."'
		AND b.tahun_ajaran='".$ta."'
		ORDER BY a.no_formulir ASC
		";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_nim($data) {
		$statSimpan=0;
		for($a=0; $a< count($data['txtNim']); $a++) {
			$q= "UPDATE trx_biodata_siswa SET nim='".$data['txtNim'][$a]."' WHERE id='".$data['txtId'][$a]."'";
			$query = $this->db->query($q);
			$statSimpan = $query;
		}
		return $statSimpan;
	}
	
	
}