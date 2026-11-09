<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ms_questioner_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function simpan_judul_questioner() {
		$q="INSERT INTO ms_questioner (judul, user_input, date_input)
		VALUES (
		'".$_POST['txtJudulPertanyaan']."',
		'".$this->session->userdata('pmb_userid')."',
		now()
		)";
		$query = $this->db->query($q);
		if($query) {
			echo "Simpan Berhasil";
		}
	}
	
	public function tampil_judul_questioner() {
		$q="SELECT * FROM ms_questioner";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function update_active_judul($data) {
		$q="UPDATE ms_questioner SET aktif='0'";
		$query = $this->db->query($q);
		
		$q2="UPDATE ms_questioner SET aktif='1' WHERE id='".$data['idjudul']."'";
		$query2 = $this->db->query($q2);
	}
	
	public function cari_judul_questioner($id) {
		$q="SELECT * FROM ms_questioner WHERE id='".$id."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function cari_issue_questioner($id) {
		$q = "SELECT a.id, a.questioner_id, a.issue, a.tipe, b.answer_no, b.answer_detail, count(b.issue_id) as jml_answer 
		FROM ms_questioner_issue a 
		LEFT JOIN ms_questioner_answer b
		ON a.id=b.issue_id
		WHERE a.questioner_id='".$id."'
		GROUP BY b.issue_id";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function simpan_issue_questioner($data) {
		$lastid=1;
		
		$q = "SELECT id FROM ms_questioner_issue ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($q);
		
		if($query->num_rows()>0) {
			$lastid = $query->row(0)->id;
			$lastid += 1;
		}
		
		$q2 = "INSERT INTO ms_questioner_issue (id, questioner_id, issue, tipe, user_input, date_input)
		VALUES (
		'".$lastid."',
		'".$data['txtQuestId']."',
		'".$data['txtIssue']."',
		'".$data['txtType']."',
		'".$this->session->userdata('pmb_userid')."',
		now()
		)";
		$query2 = $this->db->query($q2);
		
		
		for($a=0; $a< count($data['txtIssueAnswer']); $a++) {
			$q3 = "INSERT INTO ms_questioner_answer (issue_id, answer_no, answer_detail, user_input, date_input)
			VALUES 
			(
			'".$lastid."',
			'".$data['txtIssueNo'][$a]."',
			'".$data['txtIssueAnswer'][$a]."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)";
			$query3 = $this->db->query($q3);
		}
		
	}
	
	public function hapus_issue_questioner($data) {
		$q="DELETE FROM ms_questioner_issue WHERE id='".$data['idissue']."'";
		$query = $this->db->query($q);
		
		$q2="DELETE FROM ms_questioner_answer WHERE issue_id='".$data['idissue']."'";
		$query2 = $this->db->query($q2);
	}
	
	public function show_detail_question($data) {
		$q="SELECT a.id, a.questioner_id, a.issue, a.tipe, b.answer_no, b.answer_detail
		FROM ms_questioner_issue a 
		LEFT JOIN ms_questioner_answer b
		ON a.id=b.issue_id
		WHERE a.id='".$data['idissue']."'
		ORDER BY b.answer_no ASC";
		$query = $this->db->query($q);
		return $query;
	}
	
		
	
}