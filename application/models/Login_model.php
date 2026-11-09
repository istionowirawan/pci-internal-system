<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

	if (!defined('BASEPATH'))
		exit('No direct script access allowed');

	class login_model extends CI_Model {
		
		function __construct() {
			parent::__construct();
		}
		
		public function show_all_dept() {
			$query = $this->db->query("SELECT * FROM ms_dept");
			return $query;
		}
		public function show_all_position() {
			$query = $this->db->query("SELECT * FROM ms_jabatan WHERE level>2");
			return $query;
		}
		
		public function cek_login($data) {
			$query = $this->db->query("SELECT * FROM ms_user 
			WHERE username='".madSafety($data['txtUsername'])."' 
			AND password='".md5($data['txtPassword'])."'
			AND active='1'
			AND approve_by>0");
			return $query;
		}
		
		public function cek_signup($data) {
			$s = "SELECT id FROM ms_user WHERE username='".madSafety($data['txtUsernameSignup'])."'";
			$search = $this->db->query($s);
			if($search->num_rows()>0) {
				echo "Username is not available";
			} else {
				$q = "INSERT INTO ms_user (username, password, nama, id_dept, id_jabatan, active, pic, create_date) 
				VALUES
				(
				'".madSafety($data['txtUsernameSignup'])."',
				'".md5($data['txtPasswordSignup'])."',
				'".$data['txtNameSignup']."',
				'".$data['txtDeptSignup']."',
				'".$data['txtPosSignup']."',
				'1',
				'guest.png',
				now())";
				$query = $this->db->query($q);
				if($query) {
					echo "Simpan Sukses";
				}
			}
		}
		
		
	}
?>