<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rpt_khs_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_pci_active() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci WHERE status='1'");
		return $query->row(0);
	}
	
	public function tahun_ajaran_pci_list() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci GROUP BY tahun");
		return $query;
	}
	
	public function fakultas_list() {
		$query = $this->db->query("SELECT * FROM ms_fakultas ORDER BY fakultas ASC");
		return $query;
	}
	
	public function jurusan_list($data) {
		$query = $this->db->query("SELECT * FROM ms_jurusan WHERE fakultas_id='".$data['idfak']."' ORDER BY jurusan ASC");
		return $query;
	}
	
	public function data_grade() {
		$query = $this->db->query("SELECT * FROM ms_grade");
		return $query;
	}
	
	public function data_nilai_mahasiswa($data) {
		$q="SELECT a.tahun_ajaran, a.semester, a.id_fakultas, d.fakultas, a.id_jurusan, e.jurusan, a.id_makul,f.kdmk, f.nmkmk, f.nmkmk_ch, f.sks,
		a.nim_mahasiswa, c.nama_depan, c.nama_keluarga, a.nilai_akhir, a.grade
		FROM trx_pci_nilai_posting a
		LEFT JOIN 
			(SELECT x.nim, x.nama_depan, x.nama_keluarga
			FROM trx_biodata_siswa x
			UNION 
			SELECT y.nim, y.nama_depan, y.nama_keluarga 
			FROM trx_biodata_siswa_temp y 
			) as c
		ON a.nim_mahasiswa=c.nim
		LEFT JOIN ms_fakultas d
		ON a.id_fakultas=d.id
		LEFT JOIN ms_jurusan e
		ON a.id_jurusan=e.id
		LEFT JOIN ms_matakuliah f
		ON a.id_makul=f.id
		WHERE a.tahun_ajaran='".$data['txtTa']."'
		AND a.semester='".$data['txtSms']."'
		AND a.id_fakultas='".$data['txtFak']."'
		AND a.id_jurusan='".$data['txtJur']."'
		GROUP BY a.nim_mahasiswa";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function jml_sks_semester($data) {
		$q = "SELECT id_fakultas, id_jurusan, kdmk, nmkmk, semester, sks, sum(sks) as jmlsks
		FROM `ms_matakuliah` 
		WHERE id_fakultas='".$data['txtFak']."'
		AND id_jurusan='".$data['txtJur']."'
		GROUP BY semester";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_nilai_posting($data) {
		$q = "SELECT a.tahun_ajaran, a.semester, a.id_fakultas, d.fakultas, a.id_jurusan, e.jurusan, a.id_makul,f.kdmk, f.nmkmk, f.nmkmk_ch, f.sks,
		a.nim_mahasiswa, c.nama_depan, a.nilai_akhir, a.grade, g.bobot
		FROM trx_pci_nilai_posting a
		LEFT JOIN 
			(SELECT x.nim, x.nama_depan, x.nama_keluarga
			FROM trx_biodata_siswa x
			UNION 
			SELECT y.nim, y.nama_depan, y.nama_keluarga 
			FROM trx_biodata_siswa_temp y 
			) as c
		ON a.nim_mahasiswa=c.nim
		LEFT JOIN ms_fakultas d
		ON a.id_fakultas=d.id
		LEFT JOIN ms_jurusan e
		ON a.id_jurusan=e.id
		LEFT JOIN ms_matakuliah f
		ON a.id_makul=f.id
		LEFT JOIN ms_grade g
		ON a.grade=g.grade
		WHERE a.tahun_ajaran='".$data['txtTa']."'
		AND a.semester='".$data['txtSms']."'
		AND a.id_fakultas='".$data['txtFak']."'
		AND a.id_jurusan='".$data['txtJur']."'";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function nama_direktur_pci() {
		$q='SELECT nama FROM ms_direktur_pci ORDER BY id DESC';
		$query = $this->db->query($q);
		return $query->row(0)->nama;
	}
	
	
	public function data_nilai_sementara($data) {
		$q="SELECT a.tahun_ajaran, a.semester, a.id_fakultas, a.id_jurusan, d.jurusan, b.nim_mahasiswa, c.nama_depan 
FROM trx_pci_nilai a
LEFT JOIN trx_pci_nilai_detail b
ON a.id=b.id_trx
LEFT JOIN 
(SELECT x.nim, x.nama_depan, x.nama_keluarga
FROM trx_biodata_siswa x
UNION 
SELECT y.nim, y.nama_depan, y.nama_keluarga 
FROM trx_biodata_siswa_temp y 
) as c
ON b.nim_mahasiswa=c.nim
LEFT JOIN ms_jurusan d
ON a.id_jurusan=d.id
WHERE a.tahun_ajaran='".$data['txtTa']."'
AND a.semester='".$data['txtSms']."'
AND a.id_fakultas='".$data['txtFak']."'
AND a.id_jurusan='".$data['txtJur']."'
GROUP BY b.nim_mahasiswa";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_nilai_temp($data) {
		$q="SELECT DISTINCT a.tahun_ajaran, a.semester, a.id_fakultas, a.id_jurusan, a.id_makul, e.kdmk, e.nmkmk, e.nmkmk_ch, e.sks, c.kode_makul,
b.nim_mahasiswa, b.tugas, b.utp, b.uap, b.absensi,
d.tugas as ptugas, d.utp as putp, d.uap as puap, d.absensi as pabsensi
FROM trx_pci_nilai a
LEFT jOIN trx_pci_nilai_detail b
ON a.id=b.id_trx
LEFT JOIN set_makul_kelas c
ON a.id_makul=c.id_makul
LEFT JOIN set_makul_kelas_persentase d
ON c.id=d.id_trx
LEFT JOIN ms_matakuliah e
ON c.kode_makul=e.kdmk
WHERE a.tahun_ajaran='".$data['txtTa']."'
AND a.semester='".$data['txtSms']."'
AND a.id_fakultas='".$data['txtFak']."'
AND a.id_jurusan='".$data['txtJur']."'";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>