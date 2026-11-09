<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Set_makul_kelas_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_pci_active() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci WHERE status='1'");
		return $query->row(0);
	}
	
	public function show_data_makul($data) {
		/*$q="SELECT a.*,(b.id) as id_tbl_set  
		FROM ms_matakuliah a
		LEFT JOIN set_makul_kelas b
		ON a.id=b.id_makul
		WHERE a.id_fakultas='".$data['idfakultas']."' 
		AND a.id_jurusan='".$data['idjurusan']."'
		AND b.id is NULL
		ORDER BY a.nmkmk ASC";*/
		
		$q="SELECT * FROM ms_matakuliah 
		WHERE id_fakultas='".$data['idfakultas']."'
		AND id_jurusan='".$data['idjurusan']."'
		AND semester='".$data['txtPeriode']."'
		ORDER BY nmkmk ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function show_data_periode($data) {
		$q="SELECT id_fakultas, id_jurusan, semester 
		FROM ms_matakuliah
		WHERE id_fakultas='".$data['idfakultas']."'
		AND id_jurusan='".$data['idjurusan']."'
		GROUP BY semester
		ORDER BY semester ASC";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_fakultas() {
		$q="SELECT id, fakultas FROM ms_fakultas";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_jurusan($data) {
		$q="SELECT id, fakultas_id, jurusan FROM ms_jurusan WHERE fakultas_id='".$data['idfakultas']."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function show_data_kelas() {
		$query = $this->db->query("SELECT * FROM ms_kelas ORDER BY nmkls ASC");
		return $query;
	}
	
	public function show_data_dosen() {
		$query = $this->db->query("SELECT * FROM ms_dosen WHERE status='1' ORDER BY nama ASC");
		return $query;
	}
	
	public function simpan_makul_kelas($data) {
		$q = "INSERT INTO set_makul_kelas (tahun_ajaran, semester, id_makul, kode_makul, periode, id_kelas, kode_kelas, id_dosen, nik_dosen, user_input, date_input)
		VALUES
		(
		'".$data['txtTahunAjaran']."',
		'".$data['txtSemester']."',
		'".$data['txtIdMakul']."',
		'".$data['txtMakul']."',
		'".$data['txtPeriode']."',
		'".$data['txtIdKelas']."',
		'".$data['txtKelas']."',
		'".$data['txtIdDosen']."',
		'".$data['txtDosen']."',
		'".$this->session->userdata('pmb_userid')."',
		now()
		)
		";
		$query = $this->db->query($q);
		
		return $query;
	}
	
	public function load_makul_kelas($dataTA) {
		$q = "SELECT a.*, b.nmkmk, c.nmkls, (d.nama) as nama_dosen, (d.nama_ch) as nama_dosen_ch 
		FROM set_makul_kelas a
		LEFT JOIN ms_matakuliah b
		ON a.id_makul=b.id
		LEFT JOIN ms_kelas c
		ON a.id_kelas=c.id
		LEFT JOIN ms_dosen d
		ON a.id_dosen=d.id
		WHERE a.tahun_ajaran='".$dataTA->tahun."' 
		AND a.semester='".$dataTA->semester."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function hapus_data_kelas($data) {
		$q = "DELETE FROM set_makul_kelas WHERE id='".$data['idMakulKls']."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	
}
?>