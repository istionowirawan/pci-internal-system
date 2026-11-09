<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Set_makul_mhs_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_pci_active() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci WHERE status='1'");
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
		AND a.semester='".$dataTA->semester."'
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
		WHERE tbl_a.tahun_ajaran='2015-2016'
		AND tbl_a.semester='2'
		GROUP BY tbl_a.nim_mhs
		ORDER BY tbl_a.nim_mhs ASC";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function show_data_mahasiswa() {
		$q = "SELECT a.*,b.sumber_dana FROM trx_biodata_siswa a
			LEFT JOIN trx_biodata_dana b
			ON a.id_formulir=b.id_formulir 
			WHERE a.nim  != ''
			UNION ALL 
			SELECT a.*,b.sumber_dana 
			FROM trx_biodata_siswa_temp a 
			LEFT JOIN trx_biodata_dana_temp b 
			ON a.id_formulir=b.id_formulir
			WHERE a.nim != ''
			ORDER BY id DESC";
			
		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_makul_mahasiswa($data) {
		$cek = "SELECT id FROM set_makul_mahasiswa WHERE nim_mhs='".$data['txtNimMahasiswa']."' 
		AND tahun_ajaran='".$data['txtTahunAjaran']."'
		AND semester='".$data['txtSemester']."'";
		$queryCek = $this->db->query($cek);
		
		
		//jalankan proses simpan jika belum pernah diinput
		if($queryCek->num_rows()==0) {
		
			$lastid = 1;
			$qlast = "SELECT id FROM set_makul_mahasiswa ORDER BY id DESC LIMIT 1";
			$queryLastId = $this->db->query($qlast);
			if($queryLastId->num_rows()>0) {
				$lastid = $queryLastId->row(0)->id;
				$lastid += 1;
			}
			
			$qHead = "INSERT INTO set_makul_mahasiswa (id, tahun_ajaran, semester, nim_mhs, user_input, date_input) VALUES 
			(
			'".$lastid."',
			'".$data['txtTahunAjaran']."',
			'".$data['txtSemester']."',
			'".$data['txtNimMahasiswa']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			$queryHead = $this->db->query($qHead);
			
			if($queryHead) {
				$cDetail = "SELECT id FROM set_makul_mahasiswa_detail WHERE id_trx='".$lastid."' LIMIT 1";
				$queryDetail = $this->db->query($cDetail);
				
				if($queryDetail->num_rows()>0) {
					$hDetail = "DELETE FROM set_makul_mahasiswa_detail WHERE id_trx='".$lastid."'";
					$hapusDetail = $this->db->query($hDetail);
				}
				
				for($a=0; $a < count($data['chxMapel']); $a++) {
					$q = "INSERT INTO set_makul_mahasiswa_detail ( id_trx, id_makul_kelas ) 
					VALUES ('".$lastid."','".$data['chxMapel'][$a]."')";
					$query = $this->db->query($q);
				}
			}
		
			return $query;
		
		} else {
			return "2";
		}
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
	
}
?>