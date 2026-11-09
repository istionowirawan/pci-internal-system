<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	$string = addslashes($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_sekolah_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function simpan_data_sekolah() {
		$q="INSERT INTO ms_sekolah (kode, nama_sekolah, jenjang, alamat, telp, user_input, tanggal_input)
		VALUES (
		'".$_POST['txtKodeSkul']."',
		'".madSafety($_POST['txtNamaSkul'])."',
		'".$_POST['txtJenjang']."',
		'".madSafety($_POST['txtAlamatSkul'])."',
		'".$_POST['txtTelpSkul']."',
		'".$this->session->userdata('pmb_userid')."',
		now()
		)";
		$query = $this->db->query($q);
		if($query) {
			echo "Simpan Berhasil";
		}
	}
	
	public function tampil_data_sekolah() {
		$q="SELECT * FROM ms_sekolah";
		$query = $this->db->query($q);
		return $query;
	}
	
}