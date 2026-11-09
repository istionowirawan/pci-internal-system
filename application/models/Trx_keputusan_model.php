<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Trx_keputusan_model extends CI_Model {
	
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
		$q = "SELECT DISTINCT a.id_formulir, a.no_formulir, a.nama_depan, a.nama_keluarga, b.fakultas_id, b.jurusan_id, b.tahun_ajaran, c.keputusan, c.rank 
		FROM trx_biodata_siswa a
		LEFT JOIN trx_formulir b
		ON a.no_formulir=b.no_formulir
		LEFT JOIN trx_keputusan_rapat c
		ON a.no_formulir=c.noform
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
	
	
	public function data_keputusan() {
		$q = "SELECT id,keputusan,peringkat FROM ms_keputusan_rpt";
		$query = $this->db->query($q);
		return $query;
	}
	public function data_hasil_keputusan() {
		$q = "SELECT id,tahun_ajaran,id_fakultas,id_jurusan, noform, keputusan, rank FROM trx_keputusan_rapat";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_keputusan($data) {
		$c = "SELECT id FROM trx_keputusan_rapat 
		WHERE tahun_ajaran='".$data['ta']."'
		AND id_fakultas='".$data['idfak']."'
		AND id_jurusan='".$data['idjur']."'
		AND noform='".$data['noform']."'";
		$cari = $this->db->query($c);
		
		if($cari->num_rows()>0) {
			$nomorid = $cari->row(0)->id;
		
			$q = "UPDATE trx_keputusan_rapat 
			SET keputusan='".$data['hasil']."', 
			user_input='".$this->session->userdata('pmb_userid')."',
			date_input=now()
			WHERE id='".$nomorid."'";
		} else {
			$q = "INSERT INTO trx_keputusan_rapat 
			(tahun_ajaran, id_fakultas, id_jurusan, noform, keputusan, user_input, date_input) 
			VALUES
			('".$data['ta']."','".$data['idfak']."','".$data['idjur']."','".$data['noform']."','".$data['hasil']."','".$this->session->userdata('pmb_userid')."',now())";
		}
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_rank($data) {
		$c = "SELECT id FROM trx_keputusan_rapat 
		WHERE tahun_ajaran='".$data['ta']."'
		AND id_fakultas='".$data['idfak']."'
		AND id_jurusan='".$data['idjur']."'
		AND noform='".$data['noform']."'";
		$cari = $this->db->query($c);
		
		if($cari->num_rows()>0) {
			$nomorid = $cari->row(0)->id;
		
			$q = "UPDATE trx_keputusan_rapat 
			SET rank='".$data['hasil']."', 
			user_input='".$this->session->userdata('pmb_userid')."',
			date_input=now()
			WHERE id='".$nomorid."'";
		} else {
			$q = "INSERT INTO trx_keputusan_rapat 
			(tahun_ajaran, id_fakultas, id_jurusan, noform, rank, user_input, date_input) 
			VALUES
			('".$data['ta']."','".$data['idfak']."','".$data['idjur']."','".$data['noform']."','".$data['hasil']."','".$this->session->userdata('pmb_userid')."',now())";
		}
		
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>