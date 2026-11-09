<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rpt_nilaimhs_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_pci_active() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci WHERE status='1'");
		return $query->row(0);
	}
	
	public function show_dosen_ajar() {
		$q="SELECT a.id_dosen,a.nik_dosen, b.nama, b.nama_ch 
		FROM set_makul_kelas a
		LEFT JOIN ms_dosen b
		ON a.id_dosen=b.id
		WHERE b.status='1'
		GROUP BY a.nik_dosen
		ORDER BY b.nama ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_makul_dosen($data) {
		$q="SELECT a.id_makul, a.kode_makul, a.kode_kelas, b.nmkmk, b.nmkmk_ch, b.sks 
		FROM set_makul_kelas a
		LEFT JOIN ms_matakuliah b
		ON a.id_makul=b.id
		WHERE a.nik_dosen='".$data['nikdosen']."'
		GROUP BY a.id_makul
		ORDER BY b.nmkmk";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_kelas_dosen($data) {
		$q="SELECT a.id_makul, a.id_dosen, a.nik_dosen, a.id_kelas, a.kode_kelas,b.nmkls,b.nmkls_ch, b.id_fakultas, c.fakultas, b.id_jurusan, d.jurusan  
		FROM set_makul_kelas a
		LEFT JOIN ms_kelas b
		ON a.id_kelas=b.id
		LEFT JOIN ms_fakultas c
		ON b.id_fakultas=c.id
		LEFT JOIN ms_jurusan d
		ON b.id_jurusan=d.id
		WHERE a.id_makul='".$data['idmakul']."'
		AND a.nik_dosen='".$data['nikdosen']."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_jml_pertemuan($nikdosen,$idmakul,$idkelas,$tahun,$semester) {
		$q = "SELECT a.id_trx, b.nik_dosen, b.id_kelas, b.id_makul, b.tahun_ajaran,b.semester, a.pertemuan, a.absensi, a.tugas, a.utp, a.uap 
		FROM set_makul_kelas_persentase a
		LEFT JOIN set_makul_kelas b
		ON a.id_trx=b.id
		WHERE b.tahun_ajaran='".$tahun."'
		AND b.semester='".$semester."'
		AND b.nik_dosen='".$nikdosen."'
		AND b.id_makul='".$idmakul."'
		AND b.id_kelas='".$idkelas."'";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_isi_absensi($nikdosen,$idmakul,$idkelas,$tahun,$semester) {
		$q = "SELECT a.tahun_ajaran, a.semester, a.nik_dosen, a.id_makul, a.id_kelas, b.nim_mahasiswa, b.sakit, b.ijin, b.alpha, b.telat 
		FROM trx_pci_absensi a
		LEFT JOIN trx_pci_absensi_detail b
		ON a.id=b.id_trx
		WHERE a.tahun_ajaran='".$tahun."'
		AND a.semester='".$semester."'
		AND a.nik_dosen='".$nikdosen."'
		AND a.id_makul='".$idmakul."'
		AND a.id_kelas='".$idkelas."'";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_isi_nilai($nikdosen,$idmakul,$idkelas,$tahun,$semester) {
		$q = "SELECT a.tahun_ajaran, a.semester, a.nik_dosen, a.id_makul, a.id_kelas, a.stat_posting, 
		b.nim_mahasiswa, b.persen_tugas, b.persen_utp, b.persen_absensi,
		b.nilai_tugas, b.nilai_utp, b.nilai_uap, b.nilai_absensi,
		b.nilai_akhir, b.grade
		FROM trx_pci_nilai a
		LEFT JOIN trx_pci_nilai_posting b
		ON a.id=b.id_header
		WHERE a.tahun_ajaran='".$tahun."'
		AND a.semester='".$semester."'
		AND a.nik_dosen='".$nikdosen."'
		AND a.id_makul='".$idmakul."'
		AND a.id_kelas='".$idkelas."'
		AND a.stat_posting='1'";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_trx_nilai_id($nikdosen,$idmakul,$idkelas,$tahun,$semester) {
		$q = "SELECT id 
		FROM trx_pci_nilai
		WHERE tahun_ajaran='".$tahun."'
		AND semester='".$semester."'
		AND nik_dosen='".$nikdosen."'
		AND id_makul='".$idmakul."'
		AND id_kelas='".$idkelas."'";
		
		$query = $this->db->query($q);
		if($query->num_rows()>0) {
			return $query->row(0)->id;
		} else {
			return -1;
		}
	}
	
	public function cari_data_siswa($nikdosen,$idmakul,$idkelas,$tahun,$semester) {
		$c = "SELECT id FROM set_makul_kelas
		WHERE nik_dosen='".$nikdosen."'
		AND id_makul='".$idmakul."'
		AND id_kelas='".$idkelas."'";
		$cariId = $this->db->query($c);
		
		$idMakulKelas=0;
		if($cariId->num_rows()>0) {
			$idMakulKelas = $cariId->row(0)->id;
		}
		
		$q = "SELECT a.id, a.tahun_ajaran, a.semester, a.nim_mhs, b.id_makul_kelas, c.nama_depan, c.nama_keluarga 
		FROM set_makul_mahasiswa a
		LEFT JOIN set_makul_mahasiswa_detail b
		ON a.id=b.id_trx
		LEFT JOIN 
		(SELECT nim,nama_depan,nama_keluarga FROM trx_biodata_siswa
		WHERE nim!=''
		UNION ALL 
		SELECT nim,nama_depan,nama_keluarga FROM trx_biodata_siswa_temp
		WHERE nim!='') as c
		ON a.nim_mhs=c.nim
		WHERE a.tahun_ajaran='".$tahun."'
		AND a.semester='".$semester."'
		AND b.id_makul_kelas='".$idMakulKelas."'
		ORDER BY a.nim_mhs ASC";

		$query = $this->db->query($q);
		return $query;
		//return $q;
	}
	
	
	
}
?>