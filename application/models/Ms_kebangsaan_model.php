<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_kebangsaan_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function show_data_kebangsaan() {
		$query = $this->db->query("SELECT a.*, b.username, (c.username) as userEdit 
		FROM ms_nationality a
		LEFT JOIN ms_user b
		ON a.created_by=b.id
		LEFT JOIN ms_user c
		ON a.created_by=c.id
		");
		return $query;
	}
	
}
?>