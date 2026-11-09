<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rpt_transcipt_model extends CI_Model {
	
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
	
	
	public function nama_direktur_pci() {
		$q='SELECT nama FROM ms_direktur_pci ORDER BY id DESC';
		$query = $this->db->query($q);
		return $query->row(0)->nama;
	}
	
	public function list_mahasiswa($data) {
		$q = "SELECT * FROM (
		SELECT a.*, IFNULL(b.fakultas_id,4) as fakultas_id, IFNULL(b.jurusan_id,10) as jurusan_id, IFNULL(b.tahun_ajaran,'2015-2016') as tahun_ajaran FROM
		(SELECT x.id_formulir, x.no_formulir, x.nim, x.nama_depan, x.nama_keluarga
		FROM trx_biodata_siswa x
		UNION 
		SELECT y.id_formulir, y.no_formulir, y.nim, y.nama_depan, y.nama_keluarga 
		FROM trx_biodata_siswa_temp y) as a
		LEFT JOIN trx_formulir b
		ON a.no_formulir=b.no_formulir
		) as t1
		WHERE t1.fakultas_id='".$data['txtFak']."'
		AND t1.jurusan_id='".$data['txtJur']."'
		AND t1.tahun_ajaran='".$data['txtTa']."'
		AND LENGTH(t1.nim)>3
		";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_transcipt_mhs($data) {
		$q = "SELECT * FROM (
		SELECT a.*, IFNULL(b.fakultas_id,4) as fakultas_id, IFNULL(b.jurusan_id,10) as jurusan_id, IFNULL(b.tahun_ajaran,'2015-2016') as tahun_ajaran FROM
		(SELECT x.id_formulir, x.no_formulir, x.nim, x.nama_depan, x.nama_keluarga
		FROM trx_biodata_siswa x
		UNION 
		SELECT y.id_formulir, y.no_formulir, y.nim, y.nama_depan, y.nama_keluarga 
		FROM trx_biodata_siswa_temp y) as a
		LEFT JOIN trx_formulir b
		ON a.no_formulir=b.no_formulir
		) as t1
		LEFT JOIN ms_fakultas s
		ON t1.fakultas_id=s.id
		LEFT JOIN ms_jurusan t
		ON t1.jurusan_id=t.id
		WHERE t1.fakultas_id='".$data['txtFak']."'
		AND t1.jurusan_id='".$data['txtJur']."'
		AND t1.tahun_ajaran='".$data['txtTa']."'
		AND LENGTH(t1.nim)>3";
		
		if(isset($data['txtListMhs'])) {
			if(count($data['txtListMhs'])>0) {
				$q.=" AND (";
				for($a=0; $a < count($data['txtListMhs']); $a++) {
					if($a==0) {
						$q .= " t1.nim='".$data['txtListMhs'][$a]."'";
					} else {
						$q .= " OR t1.nim='".$data['txtListMhs'][$a]."'";
					}
				}
				$q .= ")";
			}
		}
		$q .= " GROUP BY t1.nim";
		//return $q;
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_transcipt_nilai($data) {
		$q="SELECT a.id, a.tahun_ajaran, a.semester, a.nim_mahasiswa, b.nama_depan,
		a.id_fakultas, c.fakultas, a.id_jurusan, d.jurusan, a.id_makul, e.kdmk, e.nmkmk, 
		a.sks, a.id_kelas, a.nilai_akhir, a.grade, f.bobot 
		FROM trx_pci_nilai_posting a
		LEFT JOIN 
		(SELECT x.nim, x.nama_depan, x.nama_keluarga
		FROM trx_biodata_siswa x
		UNION 
		SELECT y.nim, y.nama_depan, y.nama_keluarga 
		FROM trx_biodata_siswa_temp y 
		) as b
		ON a.nim_mahasiswa=b.nim
		LEFT JOIN ms_fakultas c
		ON a.id_fakultas=c.id
		LEFT JOIN ms_jurusan d
		ON a.id_jurusan=d.id
		LEFT JOIN ms_matakuliah e
		ON a.id_makul=e.id
		LEFT JOIN ms_grade f
		ON a.grade=f.grade";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>