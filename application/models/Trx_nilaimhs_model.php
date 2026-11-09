<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Trx_nilaimhs_model extends CI_Model {
	
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
		//$q="SELECT * FROM ms_dosen ORDER BY nama";
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
		$q="SELECT a.id_makul, e.nmkmk, e.sks, a.id_dosen, a.nik_dosen, a.id_kelas, a.kode_kelas,b.nmkls,b.nmkls_ch, b.id_fakultas, c.fakultas, b.id_jurusan, d.jurusan  
		FROM set_makul_kelas a
		LEFT JOIN ms_kelas b
		ON a.id_kelas=b.id
		LEFT JOIN ms_fakultas c
		ON b.id_fakultas=c.id
		LEFT JOIN ms_jurusan d
		ON b.id_jurusan=d.id
		LEFT JOIN ms_matakuliah e 
		ON a.id_makul=e.id
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
		$q = "SELECT a.id, a.tahun_ajaran, a.semester, a.nik_dosen, a.id_makul, a.id_kelas, a.stat_posting, b.nim_mahasiswa, b.tugas, b.utp, b.uap, b.absensi 
		FROM trx_pci_nilai a
		LEFT JOIN trx_pci_nilai_detail b
		ON a.id=b.id_trx
		WHERE a.tahun_ajaran='".$tahun."'
		AND a.semester='".$semester."'
		AND a.nik_dosen='".$nikdosen."'
		AND a.id_makul='".$idmakul."'
		AND a.id_kelas='".$idkelas."'";
		
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
	
	public function simpan_nilai($data) {
		$lastid = 0;
		
		//cek apakah sudah ada data yang sama sudah pernah disimpan?
		$c = "SELECT id FROM trx_pci_nilai 
		WHERE tahun_ajaran='".$data['txtTa']."'
		AND semester='".$data['txtSms']."'
		AND nik_dosen='".$data['txtDosen']."'
		AND id_fakultas='".$data['txtFakultas']."'
		AND id_jurusan='".$data['txtJurusan']."'
		AND id_makul='".$data['txtMakul']."'
		AND id_kelas='".$data['txtKelas']."'";
		$cekdata = $this->db->query($c);
		
		if($cekdata->num_rows()>0) {
			//Jika data tersebut merupakan data lama, hapus data detail
			$lastid = $cekdata->row(0)->id;
			$hapusDetail = "DELETE FROM trx_pci_nilai_detail WHERE id_trx='".$lastid."'";
			$qHapusDetail = $this->db->query($hapusDetail);
		} else {
			//Jika data tersebut merupakan data baru, cari id terakhir di table nilai ditambah 1
			$c = "SELECT id FROM trx_pci_nilai ORDER BY id DESC LIMIT 1";
			$cari = $this->db->query($c);
			if($cari->num_rows()>0) {
				$lastid = $cari->row(0)->id;
				$lastid += 1;
			} else {
				$lastid = 1;
			}
			
			$q = "INSERT INTO trx_pci_nilai (id, tahun_ajaran, semester, nik_dosen, id_fakultas, id_jurusan, id_makul, sks, id_kelas, user_input, date_input)
			VALUES (
			'".$lastid."',
			'".$data['txtTa']."',
			'".$data['txtSms']."',
			'".$data['txtDosen']."',
			'".$data['txtFakultas']."',
			'".$data['txtJurusan']."',
			'".$data['txtMakul']."',
			'".$data['txtSKS']."',
			'".$data['txtKelas']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			$query = $this->db->query($q);
		}
		
		for($a=0; $a< count($data['txtNim']); $a++) {
			$q2 = "INSERT INTO trx_pci_nilai_detail (id_trx, nim_mahasiswa, tugas, utp, uap, absensi)
			VALUES (
			'".$lastid."',
			'".$data['txtNim'][$a]."',
			'".$data['txtTugas'][$a]."',
			'".$data['txtUTP'][$a]."',
			'".$data['txtUAP'][$a]."',
			'".$data['txtAbsenMhs'][$a]."'
			)";
			$query2 = $this->db->query($q2);
		}
		
		return $query2;
	}
	
	
	public function posting_nilai($data) {
		if($data['txtTrxId']>=0) {
			for($a=0; $a<count($data['txtNim']); $a++) {
				$q = "INSERT INTO trx_pci_nilai_posting (id_header, tahun_ajaran, semester, nim_mahasiswa, 
				nik_dosen, id_fakultas, id_jurusan, id_makul, sks, id_kelas,
				persen_tugas, persen_utp, persen_uap, persen_absensi,
				nilai_tugas, nilai_utp, nilai_uap, nilai_absensi, nilai_akhir, grade, user_posting, date_posting)
				VALUES (
				'".$data['txtTrxId']."',
				'".$data['txtTa']."',
				'".$data['txtSms']."',
				'".$data['txtNim'][$a]."',
				'".$data['txtDosen']."',
				'".$data['txtFakultas']."',
				'".$data['txtJurusan']."',
				'".$data['txtMakul']."',
				'".$data['txtSKS']."',
				'".$data['txtKelas']."',
				'".$data['txtPrcTugas'][$a]."',
				'".$data['txtPrcUTP'][$a]."',
				'".$data['txtPrcUAP'][$a]."',
				'".$data['txtPrcAbsensi'][$a]."',
				'".$data['txtTugas'][$a]."',
				'".$data['txtUTP'][$a]."',
				'".$data['txtUAP'][$a]."',
				'".$data['txtAbsenMhs'][$a]."',
				'".$data['txtNA'][$a]."',
				'".$data['txtGrade'][$a]."',
				'".$this->session->userdata('pmb_userid')."',
				now()
				)";
				$query = $this->db->query($q);
			}
			
			if($query) {
				$q2="UPDATE trx_pci_nilai SET stat_posting='1'
				WHERE tahun_ajaran='".$data['txtTa']."'
				AND semester='".$data['txtSms']."'
				AND nik_dosen='".$data['txtDosen']."'
				AND id_makul='".$data['txtMakul']."'
				AND id_kelas='".$data['txtKelas']."'";
				$query2 = $this->db->query($q2);
				
				$qLog = "INSERT INTO trx_pci_nilai_posting_log 
				(id_trx, tahun_ajaran, semester, id_jurusan, id_makul, action, tgl_posting, user_posting)
				VALUES 
				('".$data['txtTrxId']."',
				'".$data['txtTa']."',
				'".$data['txtSms']."',
				'".$data['txtJurusan']."',
				'".$data['txtMakul']."',
				'posting',
				now(),
				'".$this->session->userdata('pmb_userid')."'
				)";
				$queryLog = $this->db->query($qLog);
			}
			
			return $query2;
		} else {
			return -1;
		}
	}
	
	
	public function data_grade() {
		$q="SELECT * FROM ms_grade ORDER BY grade ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function undo_posting($data) {
		$q="SELECT * FROM trx_pci_nilai WHERE id='".$data['idnilai']."'";
		$query = $this->db->query($q);
		
		$queryPosting=0;
		if($query->num_rows()>0) {
			//simpan ke dalam log 
			$qLog = "INSERT INTO trx_pci_nilai_posting_log 
			(id_trx, tahun_ajaran, semester, id_jurusan, id_makul, action, tgl_posting, user_posting)
			VALUES 
			('".$query->row(0)->id."',
			'".$query->row(0)->tahun_ajaran."',
			'".$query->row(0)->semester."',
			'".$query->row(0)->id_jurusan."',
			'".$query->row(0)->id_makul."',
			'undo',
			now(),
			'".$this->session->userdata('pmb_userid')."'
			)";
			$queryLog = $this->db->query($qLog);
			
			//update trx_pci_nilai header
			$qNilai = "UPDATE trx_pci_nilai SET stat_posting='0' WHERE id='".$data['idnilai']."'";
			$queryNilai = $this->db->query($qNilai);
			
			//delete trx_pci_nilai_posting 
			$qPosting = "DELETE FROM trx_pci_nilai_posting WHERE id_header='".$data['idnilai']."'";
			$queryPosting = $this->db->query($qPosting);
		}
		
		return $queryPosting;
	}
	
}
?>