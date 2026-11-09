<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_aktifasi_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function user_non_verfied() {
		$query = $this->db->query("SELECT a.*, b.jabatan, c.nama_dept 
		FROM ms_user a
		LEFT JOIN ms_jabatan b
		ON a.id_jabatan=b.level
		LEFT JOIN ms_dept c
		ON a.id_dept=c.id
		WHERE id_jabatan > '".$this->session->userdata('pmb_jabatan')."'
		ORDER BY approve_by ASC,
		nama ASC");
		return $query;
	}
	
	public function aktifasi_update($data) {
		$q="UPDATE ms_user SET approve_by='".$this->session->userdata('pmb_userid')."' WHERE id='".$data['userid']."'";
		$query = $this->db->query($q);
	}
	
	public function block_update($data) {
		$rubahAktif=0;
		if($data['status']=="Aktif") {
			$rubahAktif=0;
		} else {
			$rubahAktif=1;
		}
		$q="UPDATE ms_user SET active='".$rubahAktif."' WHERE id='".$data['userid']."'";
		$query = $this->db->query($q);
	}
}