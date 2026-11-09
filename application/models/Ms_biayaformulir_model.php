<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_biayaformulir_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function cari_tahun_ajaran() {
		$q="SELECT a.*,b.biaya, b.update_date, b.update_by, c.nama 
		FROM ms_tahun_ajaran a
		LEFT JOIN ms_biaya_formulir b
		ON a.tahun=b.tahun_ajaran
		LEFT JOIN ms_user c 
		ON b.update_by=c.id
		ORDER BY a.tahun ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function update_biayaformulir($data) {
		$cari="SELECT * FROM ms_biaya_formulir WHERE tahun_ajaran='".$data['thnajar']."'";
		$hasil_cari = $this->db->query($cari);
		if($hasil_cari->num_rows()>0) {
			$q = "UPDATE ms_biaya_formulir 
			SET biaya='".$data['biayaform']."',
			update_date=now(),
			update_by='".$this->session->userdata('pmb_userid')."'
			WHERE tahun_ajaran='".$data['thnajar']."'";
			$update = $this->db->query($q);
		} else {
			$q = "INSERT INTO ms_biaya_formulir 
			(tahun_ajaran,biaya,update_date,update_by)
			VALUES
			(
			'".$data['thnajar']."',
			'".$data['biayaform']."',
			now(),
			'".$this->session->userdata('pmb_userid')."'
			)";
			$simpan = $this->db->query($q);
		}
	}
}
?>