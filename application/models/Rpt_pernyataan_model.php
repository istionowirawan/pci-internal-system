<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rpt_pernyataan_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_pci_active() {
		//$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci WHERE status='1'");
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran WHERE status='1'");
		return $query->row(0);
	}
	
	public function show_makul_kelas($dataTA) {
		$q = "SELECT a.*, b.nmkmk, b.sks, c.nmkls, (d.nama) as nama_dosen, (d.nama_ch) as nama_dosen_ch 
		FROM set_makul_kelas a
		LEFT JOIN ms_matakuliah b
		ON a.id_makul=b.id
		LEFT JOIN ms_kelas c
		ON a.id_kelas=c.id
		LEFT JOIN ms_dosen d
		ON a.nik_dosen=d.nik
		WHERE a.tahun_ajaran='".$dataTA->tahun."' 
		ORDER BY a.kode_makul ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function show_makul_mahasiswa($dataTA) {
		$q = "SELECT tbl_a.id,tbl_a.tahun_ajaran,tbl_a.semester,tbl_a.nim_mhs, tbl_b.nama_depan 
		FROM set_makul_mahasiswa tbl_a
		LEFT JOIN
		(SELECT nim,nama_depan FROM trx_biodata_siswa
		WHERE nim!=''
		UNION ALL 
		SELECT nim,nama_depan FROM trx_biodata_siswa_temp
		WHERE nim!='') as tbl_b
		ON tbl_a.nim_mhs=tbl_b.nim
		WHERE tbl_a.tahun_ajaran='".$dataTA->tahun."'
		GROUP BY tbl_a.nim_mhs
		ORDER BY tbl_a.nim_mhs ASC";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function show_data_mahasiswa($dataTA) {
		$q = "SELECT a.id, a.id_formulir, a.no_formulir, a.nim, a.nama_keluarga, a.nama_depan
		FROM trx_biodata_siswa a";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	
	public function update_makul_mahasiswa($data) {
		$cek = "SELECT id FROM set_makul_mahasiswa 
		WHERE nim_mhs='".$data['txtNimMahasiswa']."' 
		AND tahun_ajaran='".$data['txtTahunAjaran']."'
		AND semester='".$data['txtSemester']."'";
		$queryCek = $this->db->query($cek);
		
		$idEdit = $queryCek->row(0)->id;
		
		$cDetail = "SELECT id FROM set_makul_mahasiswa_detail WHERE id_trx='".$idEdit."' LIMIT 1";
		$queryDetail = $this->db->query($cDetail);
		
		if($queryDetail->num_rows()>0) {
			$hDetail = "DELETE FROM set_makul_mahasiswa_detail WHERE id_trx='".$idEdit."'";
			$hapusDetail = $this->db->query($hDetail);
		}
		
		for($a=0; $a < count($data['chxMapel']); $a++) {
			$q = "INSERT INTO set_makul_mahasiswa_detail ( id_trx, id_makul_kelas ) 
			VALUES ('".$idEdit."','".$data['chxMapel'][$a]."')";
			$query = $this->db->query($q);
		}
		
		return $query;
		
	}
	
	public function load_makul_mahasiswa($data) {
		$q = "SELECT a.*, b.nim_mhs, c.id_makul, c.kode_makul, c.kode_kelas, e.nmkls, e.nmkls_ch, d.kdmk, d.nmkmk, d.sks 
		FROM set_makul_mahasiswa_detail a
		LEFT JOIN set_makul_mahasiswa b
		ON a.id_trx=b.id
		LEFT JOIN set_makul_kelas c
		ON a.id_makul_kelas=c.id
		LEFT JOIN ms_matakuliah d
		ON c.kode_makul=d.kdmk
		LEFT JOIN ms_kelas e
		ON c.id_kelas=e.id
		WHERE a.id_trx='".$data['idtrx']."'";
		$query = $this->db->query($q);
		
		return $query;
	}
	
	
	public function edit_makul_mahasiswa($data) {
		$q = "SELECT a.id, a.tahun_ajaran, a.semester, a.nim_mhs,b.id_makul_kelas, c.nama_depan
FROM set_makul_mahasiswa a
LEFT JOIN set_makul_mahasiswa_detail b
ON a.id=b.id_trx
LEFT JOIN 
(SELECT nim,nama_depan FROM trx_biodata_siswa
WHERE nim!=''
UNION ALL 
SELECT nim,nama_depan FROM trx_biodata_siswa_temp
WHERE nim!='') as c
ON a.nim_mhs=c.nim
WHERE a.id='".$data['idtrx']."'";
		$query = $this->db->query($q);
		
		return $query;
	}
	
	public function cari_fakjur($data) {
		$q="SELECT a.id, a.no_formulir, a.fakultas_id, b.fakultas, a.jurusan_id, c.jurusan 
FROM trx_formulir a
LEFT JOIN ms_fakultas b
ON a.fakultas_id=b.id
LEFT JOIN ms_jurusan c
ON a.jurusan_id=c.id
WHERE a.no_formulir='".$data['noform']."'";
		$query = $this->db->query($q);
		
		return $query;
	}
	
	public function cari_makul_fak_jur($data,$dataTA) {
		$q="SELECT a.*, b.id_fakultas, b.id_jurusan, b.nmkmk, b.sks, c.nmkls, (d.nama) as nama_dosen, (d.nama_ch) as nama_dosen_ch, b.semester 
		FROM set_makul_kelas a
		LEFT JOIN ms_matakuliah b
		ON a.id_makul=b.id
		LEFT JOIN ms_kelas c
		ON a.id_kelas=c.id
		LEFT JOIN ms_dosen d
		ON a.nik_dosen=d.nik
		WHERE a.tahun_ajaran='".$dataTA->tahun."' 
		AND a.semester='".$dataTA->semester."'
		AND b.id_fakultas='".$data['id_fak']."'
		AND b.id_jurusan='".$data['id_jur']."'
		ORDER BY b.semester ASC, a.kode_makul ASC, a.kode_kelas ASC";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function data_srt_pernyataan($noform) {
		$q = "SELECT a.no_formulir, a.nama_calon, b.nama_depan, b.nama_keluarga, a.alamat, a.fakultas_id, c.fakultas, a.jurusan_id, d.jurusan, a.tahun_ajaran  
FROM trx_formulir a
LEFT JOIN trx_biodata_siswa b
ON a.no_formulir=b.no_formulir
JOIN ms_fakultas c
ON a.fakultas_id=c.id
JOIN ms_jurusan d
ON a.jurusan_id=d.id
WHERE a.no_formulir='".$noform."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_pembayaran($noform) {
		$q = "SELECT a.no_formulir, a.jenis_bayar, a.nomor_pembayaran, a.detail_pembayaran, a.uang_pangkal, a.jatuh_tempo, a.uang_semester 
		FROM trx_biodata_pembayaran a
		WHERE a.no_formulir='".$noform."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_ayah_ibu($noform) {
		$q="SELECT a.no_formulir, a.nama, a.hubungan 
		FROM trx_biodata_anggota a
		WHERE a.no_formulir='".$noform."'
		AND (a.hubungan='Ayah' OR a.hubungan='Ibu')
		ORDER BY a.hubungan ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>