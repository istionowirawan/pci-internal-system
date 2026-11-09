<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Trx_usm_model extends CI_Model {
	
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
		$query = $this->db->query("SELECT * FROM ms_jurusan WHERE fakultas_id='".$data['idfak']."' ORDER BY jurusan ASC");
		return $query;
	}
	
	public function tampil_siswa_usm($fak,$jur,$ta) {
		$q = "SELECT DISTINCT a.id_formulir, a.no_formulir, a.nama_depan, a.nama_keluarga, b.fakultas_id, b.jurusan_id, b.tahun_ajaran 
		FROM trx_biodata_siswa a
		LEFT JOIN trx_formulir b
		ON a.no_formulir=b.no_formulir
		WHERE b.fakultas_id='".$fak."'
		AND b.jurusan_id='".$jur."'
		AND b.tahun_ajaran='".$ta."'
		ORDER BY a.no_formulir ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function makul_usm_diujikan($fak,$jur,$ta) {
		$q = "SELECT a.tahun_ajaran, a.fakultas_id, a.jurusan_id, (b.id) as id_set_usmmakul, b.no_makul, b.nama_makul 
		FROM set_makul_usm a
		LEFT JOIN set_makul_usm_detail b
		ON a.id=b.id_header
		WHERE a.fakultas_id='".$fak."'
		AND a.jurusan_id='".$jur."'
		AND a.tahun_ajaran='".$ta."'
		ORDER BY b.no_makul ASC";
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
	
	public function simpan_usm($data) {
		//return $data;
		$lastid = 0;
		
		$caridata = "SELECT id FROM trx_usm 
		WHERE tahun_ajaran='".$data['txtTa']."'
		AND id_fakultas='".$data['txtFak']."'
		AND id_jurusan='".$data['txtJur']."'";
		$queryCari = $this->db->query($caridata);
		
		if($queryCari->num_rows()>0) {
			$lastid = $queryCari->row(0)->id;
			$hapusDetail = "DELETE FROM trx_usm_detail WHERE id_header='".$lastid."'";
			$queryHapusDetail = $this->db->query($hapusDetail);
		} else {
			$q_getid = "SELECT id FROM trx_usm ORDER BY id DESC";
			$query_getid = $this->db->query($q_getid);
			if($query_getid->num_rows()>0) {
				$lastid = $query_getid->row(0)->id;
			}
			$lastid += 1;
			
			$q="INSERT INTO trx_usm (id,tahun_ajaran,id_fakultas,id_jurusan,user_input,date_input) VALUES 
			(
			'".$lastid."',
			'".$data['txtTa']."',
			'".$data['txtFak']."',
			'".$data['txtJur']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			$query = $this->db->query($q);
		}
		
		$hasil = 0;
		for($a=0; $a< count($data['txtNoForm']); $a++) {
			
			for($b=0; $b< count($data['txtKodeMakul']); $b++) {
				$kdmk = $data['txtKodeMakul'][$b];
				$nosetmk = "txtNosetmakul_" . $kdmk;
				$nilaimk = "txtNilai_".$kdmk;
				$q2="INSERT INTO trx_usm_detail (id_header,no_form,id_set_usm_makul,no_set_usm_makul,nilai_makul,user_input,date_input) VALUES 
				(
				'".$lastid."',
				'".$data['txtNoForm'][$a]."',
				'".$data['txtKodeMakul'][$b]."',
				'".$data[$nosetmk][$a]."',
				'".$data[$nilaimk][$a]."',
				'".$this->session->userdata('pmb_userid')."',
				now()
				)";
				$query2 = $this->db->query($q2);
				$hasil = $query2;
			}
		}
		
		return $hasil;
	}
	
	
}
?>