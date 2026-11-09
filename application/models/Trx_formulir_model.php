<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Trx_formulir_model extends CI_Model {
	
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
	
	public function tampil_questioner() {
		$q="SELECT a.id, a.judul, a.aktif, (b.id) as issue_id, b.issue, b.tipe 
FROM ms_questioner a
LEFT JOIN ms_questioner_issue b
ON a.id=b.questioner_id
WHERE a.aktif='1'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function tampil_trx_questioner($kriteria,$katacari) {
		$q = "SELECT b.fakultas, c.jurusan, a.* 
		FROM trx_questioner a 
		LEFT JOIN ms_fakultas b 
		ON a.fakultas_id=b.id 
		LEFT JOIN ms_jurusan c 
		ON a.jurusan_id=c.id 
		WHERE take='0' ";
		if($kriteria!="") {
			if ($kriteria!="all") {
				$q .= " AND ".$kriteria." LIKE '%".$katacari."%'";
			}
		}
		$q .= " ORDER BY a.id DESC";
		$query = $this->db->query($q);
		return $query;
	}
	public function header_tbl_trx_questioner() {
		$q = "SHOW COLUMNS FROM trx_questioner";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	
	
	public function simpan_trx_formulir($data) {
		$mixTglLahir = $data['txtTahunLahir'] . "-" . $data['txtBlnLahir'] . "-" . $data['txtTglLahir'];
		$mixTglBeliFormulir = $data['txtTahunBeli'] . "-" . $data['txtBlnBeli'] . "-" . $data['txtTglBeli'];
		
		$lastid = 1;
		$c = "SELECT id FROM trx_formulir ORDER BY id DESC LIMIT 1";
		$cari = $this->db->query($c);
		if($cari->num_rows()>0) {
			$lastid = $cari->row(0)->id + 1;
		}
		
		$q = "INSERT INTO trx_formulir (id, tanggal_beli, no_formulir, fakultas_id, jurusan_id, asal_sekolah, peran_ortu, 
		nama_ortu, nama_calon, 	tmp_lahir, tgl_lahir, 
		alamat, telepon, hp, email, 
		tahun_ajaran, questioner_id, user_input, date_input)
		VALUES (
		'".$lastid."',
		'".$mixTglBeliFormulir."',
		'".$data['txtNoFormulir']."',
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
		'".$data['txtTahunAjaran']."',
		'".$data['txtIdQuestioner']."',
		'".$this->session->userdata('pmb_userid')."',
		now()
		)";
		$query = $this->db->query($q);
		
		if($query) {
			$q2 = "UPDATE trx_questioner SET take='1' WHERE id='".$data['txtIdQuestioner']."'";
			$query2 = $this->db->query($q2);
			if($q2) {
				echo $lastid;
			} else {
				echo "Simpan Gagal";
			}
		}
	}
	public function update_trx_formulir($data) {
		$mixTglLahir = $data['txtTahunLahir'] . "-" . $data['txtBlnLahir'] . "-" . $data['txtTglLahir'];
		$mixTglBeliFormulir = $data['txtTahunBeli'] . "-" . $data['txtBlnBeli'] . "-" . $data['txtTglBeli'];
		
		$q = "UPDATE trx_formulir SET 
		tanggal_beli='".$mixTglBeliFormulir."', 
		no_formulir='".$data['txtNoFormulir']."',
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
		tahun_ajaran='".$data['txtTahunAjaran']."', 
		questioner_id='".$data['txtIdQuestioner']."', 
		user_input='".$this->session->userdata('pmb_userid')."', 
		date_input=now()
		WHERE id='".$data['txtTrxIdEdit']."'";
		$query = $this->db->query($q);
		if($query) {
			echo "Update Success";
		}
	}
	
	
	
	
	
	public function tampil_trx_formulir($kriteria,$katacari) {
		$q = "SELECT b.fakultas, c.jurusan, a.* 
		FROM trx_formulir a 
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
	public function header_tbl_trx_formulir() {
		$q = "SHOW COLUMNS FROM trx_formulir";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	
	
	public function hapus_formulir_trx($data) {
		$q="DELETE FROM trx_formulir WHERE id='".$data['trxid']."'";
		$query = $this->db->query($q);
		
		$q2 = "UPDATE trx_questioner SET take='0' WHERE id='".$data['questionerid']."'";
		$query2 = $this->db->query($q2);
	}
	
	public function edit_questioner_data($trxid) {
		$q = "SELECT * FROM trx_questioner WHERE id='".$trxid."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function edit_formulir_data($trxid) {
		$q = "SELECT * FROM trx_formulir WHERE id='".$trxid."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function cek_no_form_double($data) {
		$q = "SELECT no_formulir FROM trx_formulir WHERE no_formulir='".$data['noform']."'";
		$query = $this->db->query($q);
		if($query->num_rows()>0) {
			return "No Form ".$data['noform']." Sudah Pernah dipakai!!!";
		} else {
			return "";
		}
		//return $query;
	}
	
	public function biaya_formulir() {
		$q = "SELECT a.*, b.status FROM ms_biaya_formulir a 
		LEFT JOIN ms_tahun_ajaran b
		ON a.tahun_ajaran=b.tahun
		WHERE b.status='1'";
		$query = $this->db->query($q);
		return $query;
	}
	
}
?>