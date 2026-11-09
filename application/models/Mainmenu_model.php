<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class mainmenu_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function tahun_ajaran_active() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran WHERE status='1'");
		return $query->row(0)->tahun;
	}
	public function show_data_tahunajaran() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran");
		return $query;
	}
	
	public function update_tahunajaran($data) {
		$query1 = $this->db->query("UPDATE ms_tahun_ajaran SET status='0'");
		$query2 = $this->db->query("UPDATE ms_tahun_ajaran SET status='1' WHERE id='".$data['ThnAjrID']."'");
		return $query2;
	}
	
	public function load_ms_jabatan() {
		$query = $this->db->query("SELECT * FROM ms_jabatan WHERE level='".$this->session->userdata('pmb_jabatan')."'");
		return $query;
	}
	public function load_ms_dept() {
		$query = $this->db->query("SELECT * FROM ms_dept WHERE id='".$this->session->userdata('pmb_dept')."'");
		return $query;
	}
	
	
	//PCI Tahun Ajaran
	public function show_data_tahunajaranPCI() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci");
		return $query;
	}
	public function update_tahunajaran_pci($data) {
		$query1 = $this->db->query("UPDATE ms_tahun_ajaran_pci SET status='0'");
		$query2 = $this->db->query("UPDATE ms_tahun_ajaran_pci SET status='1' WHERE id='".$data['ThnAjrID']."'");
		return $query2;
	}
	public function tahun_ajaran_pci_active() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_pci WHERE status='1'");
		return $query->row(0);
	}
	
	
	//KEUANGAN
	public function show_data_tahunajaranKEU() {
		$query = $this->db->query("SELECT * FROM ms_tahun_ajaran_keu GROUP BY tahun");
		return $query;
	}
	public function tahun_ajaran_keu_active() {
		$query = $this->db->query("SELECT tahun FROM ms_tahun_ajaran_keu WHERE status='1'");
		return $query->row(0);
	}
	public function update_tahunajaran_keu($data) {
		$query1 = $this->db->query("UPDATE ms_tahun_ajaran_keu SET status='0'");
		$query2 = $this->db->query("UPDATE ms_tahun_ajaran_keu SET status='1' WHERE id='".$data['ThnAjrID']."'");
		return $query2;
	}
	
	
}
?>