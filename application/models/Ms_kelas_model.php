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

class Ms_kelas_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tampil_data_kelas() {
		$q="SELECT a.id, a.id_fakultas, b.fakultas, a.id_jurusan, c.jurusan, a.kdkls, a.nmkls_ch, a.nmkls, a.nmkls_ch, a.aktif 
FROM ms_kelas a
LEFT JOIN ms_fakultas b
ON a.id_fakultas=b.id
LEFT JOIN ms_jurusan c
ON a.id_jurusan=c.id";
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
	
	public function simpan_data_kelas() {
		$q="INSERT INTO ms_kelas (id_fakultas, id_jurusan, kdkls, nmkls, nmkls_ch, user_input, date_create)
		VALUES (
		'".$_POST['txtFakultas']."',
		'".$_POST['txtJurusan']."',
		'".$_POST['txtKodeKelas']."',
		'".madSafety($_POST['txtKelasIndo'])."',
		'".madSafety($_POST['txtKelasChn'])."',
		'".$this->session->userdata('pmb_userid')."',
		now()
		)";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function edit_data_kelas($data) {
		$c = "SELECT * FROM ms_kelas WHERE id='".$data['idkelas']."'";
		$cari = $this->db->query($c);
		return $cari;
	}
	public function update_data_kelas() {
		$q="UPDATE  ms_kelas SET 
		id_fakultas='".$_POST['txtFakultas']."',
		id_jurusan='".$_POST['txtJurusan']."',
		nmkls='".madSafety($_POST['txtKelasIndo'])."', 
		nmkls_ch='".madSafety($_POST['txtKelasChn'])."', 
		user_input='".$this->session->userdata('pmb_userid')."', 
		date_create=now() 
		WHERE
		kdkls='".$_POST['txtKodeKelas']."'";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	
}