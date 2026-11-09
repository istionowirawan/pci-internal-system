<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	$string = addslashes($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_matakul_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tampil_data_matakul() {
		$q="SELECT a.id, a.kdmk, a.nmkmk, a.nmkmk_ch, a.sks, a.semester, a.nmkmk_eng, b.fakultas, a.id_jurusan, c.jurusan 
		FROM ms_matakuliah a
		LEFT JOIN ms_fakultas b
		ON a.id_fakultas=b.id
		LEFT JOIN ms_jurusan c
		ON a.id_jurusan=c.id
		ORDER BY a.semester ASC, a.kdmk ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function header_jurusan() {
		$q="SELECT a.id_fakultas, c.fakultas, a.id_jurusan, b.jurusan
		FROM ms_matakuliah a
		JOIN ms_jurusan b
		ON a.id_jurusan=b.id
		JOIN ms_fakultas c
		ON a.id_fakultas=c.id
		GROUP BY a.id_jurusan";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_data_makul() {
		$q="INSERT INTO ms_matakuliah (id_fakultas, id_jurusan, kdmk, nmkmk, nmkmk_eng, nmkmk_ch, sks, semester, user_input, date_create)
		VALUES (
		'".$_POST['txtFakultas']."',
		'".$_POST['txtJurusan']."',
		'".$_POST['txtKodeMakul']."',
		'".madSafety($_POST['txtMakulIndo'])."',
		'".madSafety($_POST['txtMakulEng'])."',
		'".madSafety($_POST['txtMakulChn'])."',
		'".$_POST['txtSKS']."',
		'".$_POST['txtSemester']."',
		'".$this->session->userdata('pmb_userid')."',
		now()
		)";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function edit_data_makul($data) {
		$c = "SELECT * FROM ms_matakuliah WHERE id='".$data['idmakul']."'";
		$cari = $this->db->query($c);
		return $cari;
	}
	public function update_data_makul() {
		$q="UPDATE  ms_matakuliah SET 
		id_fakultas='".madSafety($_POST['txtFakultas'])."', 
		id_jurusan='".madSafety($_POST['txtJurusan'])."', 
		nmkmk='".madSafety($_POST['txtMakulIndo'])."', 
		nmkmk_ch='".madSafety($_POST['txtMakulChn'])."', 
		nmkmk_eng='".madSafety($_POST['txtMakulEng'])."', 
		sks='".$_POST['txtSKS']."',
		semester='".$_POST['txtSemester']."',
		user_input='".$this->session->userdata('pmb_userid')."', 
		date_create=now() 
		WHERE
		kdmk='".$_POST['txtKodeMakul']."'";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_fakultas() {
		$q="SELECT id, fakultas FROM ms_fakultas";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_jurusan($data) {
		$q="SELECT id, fakultas_id, jurusan FROM ms_jurusan WHERE fakultas_id='".$data['idfakultas']."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	
}