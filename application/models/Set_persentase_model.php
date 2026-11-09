<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Set_persentase_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_pci_active() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci WHERE status='1'");
		return $query->row(0);
	}
	
	public function show_dosen_makul($data) {
		$q="SELECT a.*, (b.nama) as nama_dosen, b.nama_ch, c.nmkls, c.nmkls_ch, d.nmkmk, d.nmkmk_ch 
		FROM set_makul_kelas a
		LEFT JOIN ms_dosen b
		ON a.id_dosen=b.id
		LEFT JOIN ms_kelas c
		ON a.id_kelas=c.id
		LEFT JOIN ms_matakuliah d
		ON a.id_makul=d.id
		WHERE a.tahun_ajaran='".$data->tahun."'
		AND a.semester='".$data->semester."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_persentase($data) {
		
		for($a=0; $a< count($data['txtIdSetMakulKelas']); $a++) {
			$c = "SELECT id FROM set_makul_kelas_persentase WHERE id_trx='".$data['txtIdSetMakulKelas'][$a]."' LIMIT 1";
			$cQuery = $this->db->query($c);
			$jmldata = $cQuery->num_rows();
			
			if($jmldata > 0) {
				$d = "DELETE FROM set_makul_kelas_persentase WHERE id_trx='".$data['txtIdSetMakulKelas'][$a]."'";
				$delQuery = $this->db->query($d);
			}
			
			$q="INSERT INTO set_makul_kelas_persentase
			(id_trx,pertemuan,absensi,tugas,utp,uap,tahun_ajaran,semester,user_input,date_input)
			VALUES 
			(
			'".$data['txtIdSetMakulKelas'][$a]."',
			'".$data['txtPert'][$a]."',
			'".$data['txtAbsensi'][$a]."',
			'".$data['txtTugas'][$a]."',
			'".$data['txtUTP'][$a]."',
			'".$data['txtUAP'][$a]."',
			'".$data['txtTahunAjaran']."',
			'".$data['txtSemester']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			$query = $this->db->query($q);
			
		}
		return $query;
	}
	
	public function isi_set_persen($ta) {
		$q = "SELECT * FROM set_makul_kelas_persentase WHERE tahun_ajaran='".$ta->tahun."' AND semester='".$ta->semester."'";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>