<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_pembayaran_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function cari_data_fakultas() {
		$q="SELECT * FROM ms_fakultas ORDER BY fakultas ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_data_jurusan($data) {
		$q="SELECT * FROM ms_jurusan WHERE fakultas_id='".$data['idFakultas']."' ORDER BY jurusan ASC";
		$query = $this->db->query($q);
		return $query;
	}
	public function cari_data_jurusan2($idFakultas) {
		$q="SELECT * FROM ms_jurusan WHERE fakultas_id='".$idFakultas."' ORDER BY jurusan ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_data_ta() {
		$q="SELECT * FROM ms_tahun_ajaran ORDER BY tahun ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_ms_pembayaran() {
		$q="SELECT * FROM ms_pembayaran ORDER BY tahun_ajaran";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function data_ms_pembayaran_cicil($thnajr) {
		$q="SELECT * FROM ms_pembayaran_kredit WHERE tahun_ajaran='".$thnajr."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function update_cicil($data) {
		$q="UPDATE ms_pembayaran_kredit 
		SET 
		harga_cicil='".$data['hrgcicil']."', 
		date_input=now(), 
		user_input='".$this->session->userdata('pmb_userid')."' 
		WHERE id='".$data['idcicil']."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function update_pembayaran($data) {
		$q="UPDATE ms_pembayaran 
		SET 
		uang_pangkal='".$data['txtUangPangkal']."', 
		uang_semester='".$data['txtUangSemester']."', 
		date_input=now(), 
		user_input='".$this->session->userdata('pmb_userid')."' 
		WHERE tahun_ajaran='".$data['thnajr']."'";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>