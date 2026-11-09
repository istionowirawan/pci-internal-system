<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Trx_absensi_model extends CI_Model {
	
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
		$q="SELECT a.id_makul, a.kode_makul, b.nmkmk, b.nmkmk_ch, b.sks 
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
		$q="SELECT a.id_makul, a.id_dosen, a.nik_dosen, a.id_kelas, a.kode_kelas,b.nmkls,b.nmkls_ch 
		FROM set_makul_kelas a
		LEFT JOIN ms_kelas b
		ON a.id_kelas=b.id
		WHERE a.id_makul='".$data['idmakul']."'
		AND a.nik_dosen='".$data['nikdosen']."'";
		$query = $this->db->query($q);
		return $query;
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
		
		$q = "SELECT a.id, a.tahun_ajaran, a.semester, a.nim_mhs, b.id_makul_kelas, c.nama_depan 
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
		WHERE a.tahun_ajaran='".$tahun."'
		AND a.semester='".$semester."'
		AND b.id_makul_kelas='".$idMakulKelas."'
		ORDER BY a.nim_mhs ASC";

		$query = $this->db->query($q);
		return $query;
		//return $q;
	}
	
	
	public function cari_isi_absensi($nikdosen,$idmakul,$idkelas,$tahun,$semester) {
		$q = "SELECT a.id, a.tahun_ajaran, a.semester, a.nik_dosen, a.id_makul, a.id_kelas, b.nim_mahasiswa, b.sakit, b.ijin, b.alpha, b.telat 
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
	
	public function cari_jml_pertemuan($nikdosen,$idmakul,$idkelas,$tahun,$semester) {
		$q = "SELECT a.id_trx, b.nik_dosen, b.id_kelas, b.id_makul, b.tahun_ajaran,b.semester, a.pertemuan 
		FROM set_makul_kelas_persentase a
		LEFT JOIN set_makul_kelas b
		ON a.id_trx=b.id
		WHERE b.tahun_ajaran='".$tahun."'
		AND b.semester='".$semester."'
		AND b.nik_dosen='".$nikdosen."'
		AND b.id_makul='".$idmakul."'
		AND b.id_kelas='".$idkelas."'";
		
		$query = $this->db->query($q);
		if($query->num_rows()>0) {
			return $query->row(0)->pertemuan;
		} else {
			return 0;
		}
	}
	
	
	public function simpan_absensi($data) {
		$lastid = 1;
		$t = "SELECT id FROM trx_pci_absensi ORDER BY id DESC";
		$tQuery = $this->db->query($t);
		if($tQuery->num_rows()>0) {
			$lastid = $tQuery->row(0)->id;
			$lastid += 1;
		}
		
		$cariAbsensi = "SELECT id FROM trx_pci_absensi 
		WHERE tahun_ajaran='".$data['txtTa']."'
		AND semester='".$data['txtSms']."'
		AND nik_dosen='".$data['txtDosen']."'
		AND id_makul='".$data['txtMakul']."'
		AND id_kelas='".$data['txtKelas']."'";
		$queryCariAbsensi = $this->db->query($cariAbsensi);
		
		if($queryCariAbsensi->num_rows()>0) {
			$lastid = $queryCariAbsensi->row(0)->id;
			
			//UPDATE
			$q = "UPDATE trx_pci_absensi SET 
			tahun_ajaran='".$data['txtTa']."', 
			semester='".$data['txtSms']."', 
			nik_dosen='".$data['txtDosen']."', 
			id_makul='".$data['txtMakul']."', 
			id_kelas='".$data['txtKelas']."', 
			user_input='".$this->session->userdata('pmb_userid')."', 
			date_input=now()
			WHERE id='".$lastid."'";
			$query = $this->db->query($q);
		} else {
			
			//SIMPAN
			$q = "INSERT INTO trx_pci_absensi (id, tahun_ajaran, semester, nik_dosen, id_makul, id_kelas, user_input, date_input)
			VALUES (
			'".$lastid."',
			'".$data['txtTa']."',
			'".$data['txtSms']."',
			'".$data['txtDosen']."',
			'".$data['txtMakul']."',
			'".$data['txtKelas']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			$query = $this->db->query($q);
		}
		
		
		if($query) {
			$cari = "SELECT id FROM trx_pci_absensi_detail WHERE id_trx='".$lastid."'";
			$queryCari = $this->db->query($cari);
			if($queryCari->num_rows()>0) {
				$hapus = "DELETE FROM trx_pci_absensi_detail WHERE id_trx='".$lastid."'";
				$queryHapus = $this->db->query($hapus);
			}
			
			for($a=0; $a< count($data['txtNim']); $a++) {
				$qDetail = "INSERT INTO trx_pci_absensi_detail (id_trx, nim_mahasiswa, sakit, ijin, alpha, telat)
				VALUES (
				'".$lastid."',
				'".$data['txtNim'][$a]."',
				'".$data['txtSakit'][$a]."',
				'".$data['txtIjin'][$a]."',
				'".$data['txtAlpha'][$a]."',
				'".$data['txtTelat'][$a]."'
				)";
				$queryDetail = $this->db->query($qDetail);
			}
		}
		
		return $query;
	}
	
}
?>