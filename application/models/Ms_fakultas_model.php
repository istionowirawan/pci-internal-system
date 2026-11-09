<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_fakultas_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function simpan_data_fakultas() {
		$c="SELECT id FROM ms_fakultas WHERE fakultas='".$_POST['txtNamaFakultas']."'";
		$qc=$this->db->query($c);
		
		if($qc->num_rows()>0) {
			echo "Nama Fakultas sudah pernah diinput";
		} else {
			$q="INSERT INTO ms_fakultas (fakultas, user_input, date_input)
			VALUES (
			'".$_POST['txtNamaFakultas']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			$query = $this->db->query($q);
			if($query) {
				echo "Simpan Berhasil";
			}
		}
	}
	
	public function cari_data_fakultas() {
		$c = "SELECT * FROM ms_fakultas";
		$query = $this->db->query($c);
		return $query;
	}
	
	public function simpan_data_jurusan() {
		$c="SELECT id FROM ms_jurusan WHERE fakultas_id='".$_POST['txtIdFakultas']."' AND jurusan='".$_POST['txtJurusan']."'";
		$qc=$this->db->query($c);
		
		if($qc->num_rows()>0) {
			echo "Nama Jurusan pada fakultas ini sudah pernah diinput";
		} else {
			$q="INSERT INTO ms_jurusan (fakultas_id, jurusan, user_input, date_input)
			VALUES (
			'".$_POST['txtIdFakultas']."',
			'".$_POST['txtJurusan']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			$query = $this->db->query($q);
			if($query) {
				echo "Simpan Berhasil";
			}
		}
	}
	
	public function tampil_data_fakultas() {
		$c = "SELECT a.id, a.fakultas, b.jurusan 
		FROM ms_fakultas a
		LEFT JOIN ms_jurusan b
		ON a.id=b.fakultas_id
		ORDER BY a.fakultas ASC, b.jurusan ASC
		";
		$query = $this->db->query($c);
		return $query;
	}
	
}