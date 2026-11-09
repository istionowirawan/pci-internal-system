<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rpt_krs_model extends CI_Model {
	
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
	
	public function data_krs_mahasiswa($data) {
		$q="SELECT t1.*, f.jurusan FROM 
(
SELECT a.id, a.tahun_ajaran, IFNULL(e.fakultas_id,4) as fakid, IFNULL(e.jurusan_id,10) as jurid, 
a.semester, a.nim_mhs, c.nama_depan, c.nama_keluarga, h.sumber_dana, i.kode_dana
FROM set_makul_mahasiswa a
LEFT JOIN 
    (SELECT x.no_formulir, x.nim, x.nama_depan, x.nama_keluarga
    FROM trx_biodata_siswa x
    UNION 
    SELECT y.no_formulir, y.nim, y.nama_depan, y.nama_keluarga 
    FROM trx_biodata_siswa_temp y 
    ) as c
ON a.nim_mhs=c.nim
LEFT JOIN trx_formulir e
ON c.no_formulir=e.no_formulir
LEFT JOIN 
    (SELECT v.no_formulir,v.sumber_dana FROM trx_biodata_dana v
    UNION
    SELECT w.no_formulir, w.sumber_dana FROM trx_biodata_dana_temp w) as h
ON c.no_formulir=h.no_formulir
LEFT JOIN ms_dana i
ON i.ket_dana=h.sumber_dana
) as t1
LEFT JOIN ms_jurusan f
ON t1.jurid=f.id
WHERE t1.tahun_ajaran='".$data['txtTa']."'
AND t1.semester='".$data['txtSms']."'
AND t1.fakid='".$data['txtFak']."'
AND t1.jurid='".$data['txtJur']."'
		";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function data_krs_mahasiswa_detail($data) {
		$q="SELECT t1.*, f.jurusan FROM 
(
SELECT a.id, a.tahun_ajaran, IFNULL(e.fakultas_id,4) as fakid, IFNULL(e.jurusan_id,10) as jurid, 
a.semester, a.nim_mhs, c.nama_depan, b.id_makul_kelas, d.kode_kelas, d.kode_makul, g.nmkmk, g.nmkmk_ch, g.sks
FROM set_makul_mahasiswa a
LEFT JOIN set_makul_mahasiswa_detail b
ON a.id=b.id_trx
LEFT JOIN 
    (SELECT x.no_formulir, x.nim, x.nama_depan, x.nama_keluarga
    FROM trx_biodata_siswa x
    UNION 
    SELECT y.no_formulir, y.nim, y.nama_depan, y.nama_keluarga 
    FROM trx_biodata_siswa_temp y 
    ) as c
ON a.nim_mhs=c.nim
LEFT JOIN set_makul_kelas d
ON b.id_makul_kelas=d.id
LEFT JOIN trx_formulir e
ON c.no_formulir=e.no_formulir
LEFT JOIN ms_matakuliah g
ON d.kode_makul=g.kdmk
) as t1
LEFT JOIN ms_jurusan f
ON t1.jurid=f.id
WHERE t1.tahun_ajaran='".$data['txtTa']."'
AND t1.semester='".$data['txtSms']."'
AND t1.fakid='".$data['txtFak']."'
AND t1.jurid='".$data['txtJur']."'
		";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function nama_direktur_pci() {
		$q='SELECT nama FROM ms_direktur_pci ORDER BY id DESC';
		$query = $this->db->query($q);
		return $query->row(0)->nama;
	}
	
	public function detail_master_dana() {
		$q='SELECT * FROM ms_dana';
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>