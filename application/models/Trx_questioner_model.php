<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Trx_questioner_model extends CI_Model {
	
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
	
	public function tampil_questioner() {
		$q="SELECT a.id, a.judul, a.aktif, (b.id) as issue_id, b.issue, b.tipe 
FROM ms_questioner a
LEFT JOIN ms_questioner_issue b
ON a.id=b.questioner_id
WHERE a.aktif='1'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function simpan_trx_questioner($data) {
		$mixTglLahir = $data['txtTahunLahir'] . "-" . $data['txtBlnLahir'] . "-" . $data['txtTglLahir'];
		$mixTglIsi = $data['txtTahunIsi'] . "-" . $data['txtBlnIsi'] . "-" . $data['txtTglIsi'];
		
		$lastid = 1;
		$c = "SELECT * FROM trx_questioner ORDER BY id DESC LIMIT 1";
		$cari = $this->db->query($c);
		if($cari->num_rows()>0) {
			$lastid = $cari->row(0)->id;
			$lastid += 1;
		}
		
		$q = "INSERT INTO trx_questioner (id, tanggal_isi, fakultas_id, jurusan_id, asal_sekolah, peran_ortu, 
		nama_ortu, nama_calon, 	tmp_lahir, tgl_lahir, 
		alamat, telepon, hp, email, hp_ortu,
		soal_id, banyak_soal, user_input, date_input)
		VALUES (
		'".$lastid."',
		'".$mixTglIsi."',
		'".$data['txtFakultas']."',
		'".$data['txtJurusan']."',
		'".$data['txtAsalSekolah']."',
		'".$data['txtPeranOrtu']."',
		'".$data['txtNamaOrtu']."',
		'".$data['txtCalonMahasiswa']."',
		'".$data['txtTempatLahir']."',
		'".$mixTglLahir."',
		'".$data['txtAlamat']."',
		'".$data['txtTelepon']."',
		'".$data['txtHP']."',
		'".$data['txtEmail']."',
		'".$data['txtHPOrtu']."',
		'".$data['txtId_soal']."',
		'".$data['txtByksoal']."',
		'".$this->session->userdata('pmb_userid')."',
		now()
		)";
		
		$query = $this->db->query($q);
		if($query) {
			$err=0;
			
			for($a=0; $a < $data['txtByksoal']; $a++) {
				$noIssueId = $data['txtIssueId'][$a];
				
				$ansMix="";
				for($b=0; $b < count($data['txtAns_'.$noIssueId]); $b++) {
					$ansMix .= $data['txtAns_'.$noIssueId][$b] . ";";
				}
				
				$q2 = "INSERT INTO trx_questioner_answer (trx_id, question_id, issue_id, answer, user_input, date_input)
				VALUES
				('".$lastid."',
				'".$data['txtId_soal']."',
				'".$noIssueId."',
				'".$ansMix."',
				'".$this->session->userdata('pmb_userid')."',
				now()
				)";
				
				
				$query2 = $this->db->query($q2);
				if($query2) {
					$err++;
				}
				
			}
			if($err==0) {
				echo "Simpan Berhasil";
			}
		}
		//return $data;
		
	}
	
	public function update_trx_questioner($data) {
		$mixTglLahir = $data['txtTahunLahir'] . "-" . $data['txtBlnLahir'] . "-" . $data['txtTglLahir'];
		$mixTglIsi = $data['txtTahunIsi'] . "-" . $data['txtBlnIsi'] . "-" . $data['txtTglIsi'];
		
		$q = "UPDATE trx_questioner SET
		tanggal_isi='".$mixTglIsi."', 
		fakultas_id='".$data['txtFakultas']."', 
		jurusan_id='".$data['txtJurusan']."', 
		asal_sekolah='".$data['txtAsalSekolah']."', 
		peran_ortu='".$data['txtPeranOrtu']."', 
		nama_ortu='".$data['txtNamaOrtu']."', 
		nama_calon='".$data['txtCalonMahasiswa']."', 	
		tmp_lahir='".$data['txtTempatLahir']."', 
		tgl_lahir='".$mixTglLahir."', 
		alamat='".$data['txtAlamat']."', 
		telepon='".$data['txtTelepon']."', 
		hp='".$data['txtHP']."', 
		email='".$data['txtEmail']."', 
		hp_ortu='".$data['txtHPOrtu']."',
		soal_id='".$data['txtId_soal']."', 
		banyak_soal='".$data['txtByksoal']."', 
		user_input='".$this->session->userdata('pmb_userid')."', 
		date_input=now()
		WHERE id='".$data['txtIdTrxQuestioner']."'";
		$query = $this->db->query($q);
		
		if($query) {
			$err=0;
			
			$q3 = "DELETE FROM trx_questioner_answer WHERE trx_id='".$data['txtIdTrxQuestioner']."'";
			$query3 = $this->db->query($q3);
			
			for($a=0; $a < $data['txtByksoal']; $a++) {
				$noIssueId = $data['txtIssueId'][$a];
				
				$ansMix="";
				for($b=0; $b < count($data['txtAns_'.$noIssueId]); $b++) {
					$ansMix .= $data['txtAns_'.$noIssueId][$b] . ";";
				}
				
				$q2 = "INSERT INTO trx_questioner_answer (trx_id, question_id, issue_id, answer, user_input, date_input)
				VALUES
				('".$data['txtIdTrxQuestioner']."',
				'".$data['txtId_soal']."',
				'".$noIssueId."',
				'".$ansMix."',
				'".$this->session->userdata('pmb_userid')."',
				now()
				)";
				
				
				$query2 = $this->db->query($q2);
				if($query2) {
					$err++;
				}
				
			}
			if($err==0) {
				echo "Update Berhasil";
			}
		}
		//return $data;
	}
	
	
	public function tampil_trx_questioner($kriteria,$katacari) {
		$q = "SELECT b.fakultas, c.jurusan, a.* 
		FROM trx_questioner a 
		LEFT JOIN ms_fakultas b 
		ON a.fakultas_id=b.id 
		LEFT JOIN ms_jurusan c 
		ON a.jurusan_id=c.id ";
		if($kriteria!="") {
			if ($kriteria!="all") {
				$q .= " WHERE ".$kriteria." LIKE '%".$katacari."%'";
			}
		}
		$q .= " ORDER BY a.id DESC";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function hapus_questioner_trx($data) {
		$q="DELETE FROM trx_questioner WHERE id='".$data['trxid']."'";
		$q2="DELETE FROM trx_questioner_answer WHERE trx_id='".$data['trxid']."'";
		$query = $this->db->query($q);
		$query2 = $this->db->query($q2);
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
	
	public function header_tbl_trx_questioner() {
		$q = "SHOW COLUMNS FROM trx_questioner";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function edit_questioner_data($trxid) {
		$q = "SELECT * FROM trx_questioner WHERE id='".$trxid."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function edit_questioner_answer($trxid) {
		$q = "SELECT * FROM trx_questioner_answer WHERE trx_id='".$trxid."'";
		$query = $this->db->query($q);
		return $query;
	}
	
}