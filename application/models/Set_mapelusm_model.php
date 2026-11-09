<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Set_mapelusm_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_humas_active() {
		$query = $this->db->query("SELECT tahun FROM ms_tahun_ajaran WHERE status='1'");
		return $query->row(0)->tahun;
	}
	
	public function fakultas_list() {
		$query = $this->db->query("SELECT * FROM ms_fakultas ORDER BY fakultas ASC");
		return $query;
	}
	
	public function jurusan_list($data) {
		$query = $this->db->query("SELECT * FROM ms_jurusan WHERE fakultas_id='".$data['idfak']."' ORDER BY jurusan ASC");
		return $query;
	}
	
	public function simpan_mapelusm($data) {
		$cekdataExist = "SELECT id 
		FROM set_makul_usm 
		WHERE tahun_ajaran='".$data['txtTa']."' 
		AND fakultas_id='".$data['txtFak']."'
		AND jurusan_id='".$data['txtJur']."'";
		$qCekExist = $this->db->query($cekdataExist);
		
		if($qCekExist->num_rows()>0) {
			//update data detail
			$idheaderExist = $qCekExist->row(0)->id;
			
			$hapusDetail = "DELETE FROM set_makul_usm_detail WHERE id_header='".$idheaderExist."'";
			$qHpsDetail = $this->db->query($hapusDetail);
			
			if($qHpsDetail) {
				for($b=0; $b< count($data['txtNoUrut']); $b++) {
					$q3 = "INSERT INTO set_makul_usm_detail (id_header, no_makul, nama_makul)
					VALUES (
					'".$idheaderExist."',
					'".$data['txtNoUrut'][$b]."',
					'".$data['txtNamaMapel'][$b]."'
					)";
					
					$query3 = $this->db->query($q3);
				}
				
				return $query3;
			}
		} else {
			//Simpan data baru
			$lastid=1;
			
			$cariid = $this->db->query("SELECT id FROM set_makul_usm ORDER BY id DESC LIMIT 1");
			if($cariid->num_rows()>0) {
				$lastid = $cariid->row(0)->id;
				$lastid += 1;
			}
			
			$q = "INSERT INTO set_makul_usm (id, tahun_ajaran, fakultas_id, jurusan_id, user_input, date_input)
			VALUES (
			'".$lastid."',
			'".$data['txtTa']."',
			'".$data['txtFak']."',
			'".$data['txtJur']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			
			$query = $this->db->query($q);
			
			if($query) {
				for($a=0; $a< count($data['txtNoUrut']); $a++) {
					$q2 = "INSERT INTO set_makul_usm_detail (id_header, no_makul, nama_makul)
					VALUES (
					'".$lastid."',
					'".$data['txtNoUrut'][$a]."',
					'".$data['txtNamaMapel'][$a]."'
					)";
					
					$query2 = $this->db->query($q2);
				}
				
				return $query2; 
			} else {
				return $query;
			}
		}
	}
	
	public function cari_mapel_usm($data) {
		$q = "SELECT a.id, a.tahun_ajaran, a.fakultas_id, a.jurusan_id, b.no_makul, b.nama_makul 
		FROM set_makul_usm a
		LEFT JOIN set_makul_usm_detail b
		ON a.id=b.id_header
		WHERE a.fakultas_id='".$data['idfak']."' 
		AND a.jurusan_id='".$data['idjur']."'
		AND a.tahun_ajaran='".$data['ta']."'
		ORDER BY id_header ASC, no_makul ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>