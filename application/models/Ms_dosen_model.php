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

class Ms_dosen_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tampil_data_dosen() {
		$q="SELECT * FROM ms_dosen";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function active_data_dosen($data) {
		$c = "SELECT status FROM ms_dosen WHERE id='".$data['iddosen']."'";
		$cari = $this->db->query($c);
		
		$statusubah = $cari->row(0)->status;
		if($statusubah==0) {
			$statusubah = 1;
		} else{
			$statusubah = 0;
		}
		
		$q = "UPDATE ms_dosen SET status='".$statusubah."' WHERE id='".$data['iddosen']."'";
		$query = $this->db->query($q);
		
		return $query;
	}
	
	public function edit_data_dosen($data) {
		$c = "SELECT * FROM ms_dosen WHERE id='".$data['iddosen']."'";
		$cari = $this->db->query($c);
		return $cari;
	}
	
	public function simpan_data_dosen() {
		$q="INSERT INTO ms_dosen (nik, nama, nama_ch, alamat, telp, email, gelar, lulus_tahun, created_date, created_by)
		VALUES (
		'".$_POST['txtDosenNik']."',
		'".madSafety($_POST['txtNamaDosen'])."',
		'".madSafety($_POST['txtNamaChinese'])."',
		'".madSafety($_POST['txtAlamatDosen'])."',
		'".$_POST['txtTelpDosen']."',
		'".$_POST['txtEmailDosen']."',
		'".$_POST['txtGelar']."',
		'".$_POST['txtLulusTahun']."',
		now(),
		'".$this->session->userdata('pmb_userid')."'
		)";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function update_data_dosen() {
		$q="UPDATE  ms_dosen SET 
		nama='".madSafety($_POST['txtNamaDosen'])."', 
		nama_ch='".madSafety($_POST['txtNamaChinese'])."', 
		alamat='".madSafety($_POST['txtAlamatDosen'])."', 
		telp='".$_POST['txtTelpDosen']."', 
		email='".$_POST['txtEmailDosen']."', 
		gelar='".$_POST['txtGelar']."', 
		lulus_tahun='".$_POST['txtLulusTahun']."', 
		created_date=now(), 
		created_by='".$this->session->userdata('pmb_userid')."'
		WHERE
		nik='".$_POST['txtDosenNik']."'";
		
		$query = $this->db->query($q);
		return $query;
	}
	
	
}