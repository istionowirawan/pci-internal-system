<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_grade_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function show_data_grade() {
		$query = $this->db->query("SELECT * FROM ms_grade");
		return $query;
	}
	
	public function update_grade($data) {
		$q="UPDATE ms_grade SET range_from='".$data['range_from']."', range_to='".$data['range_to']."' WHERE id='".$data['id']."'";
		$query = $this->db->query($q);
		return $query;
	}
}
?>