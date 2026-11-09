<?php
function madSafety($string) {
	$string = stripslashes($string);
	$string = strip_tags($string);
	//$string = mysql_real_escape_string($string);
	return $string;
}

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Trx_biodata_model extends CI_Model {
	
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
	
	public function tampil_trx_formulir($kriteria,$katacari) {
		$q = "SELECT b.fakultas, c.jurusan, a.*, (d.id) as bio_id 
		FROM trx_formulir a 
		LEFT JOIN ms_fakultas b 
		ON a.fakultas_id=b.id 
		LEFT JOIN ms_jurusan c 
		ON a.jurusan_id=c.id 
		LEFT JOIN trx_biodata_siswa d
		ON a.id=d.id_formulir
		WHERE d.id IS NULL ";
		if($kriteria!="") {
			if ($kriteria!="all") {
				$q .= " AND ".$kriteria." LIKE '%".$katacari."%'";
			}
		}
		$q .= " ORDER BY a.id DESC";
		//echo $q;
		$query = $this->db->query($q);
		return $query;
	}
	public function header_tbl_trx_formulir() {
		$q = "SHOW COLUMNS FROM trx_formulir";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function edit_formulir_data($trxid) {
		$q = "SELECT * FROM trx_formulir WHERE id='".$trxid."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function cari_ms_pembayaran($data) {
		$cari_tahun_ajar = "SELECT * FROM ms_tahun_ajaran WHERE status='1'";
		$query = $this->db->query($cari_tahun_ajar); 
		
		if($data['jenisPmbyr']=="Cash") {
			$q = "SELECT * FROM ms_pembayaran WHERE tahun_ajaran='".$query->row(0)->tahun."'";
		} else {
			$q = "SELECT * FROM ms_pembayaran a
			LEFT JOIN ms_pembayaran_kredit b
			ON a.tahun_ajaran=b.tahun_ajaran
			WHERE a.tahun_ajaran='".$query->row(0)->tahun."'
			ORDER BY b.no_cicil ASC";
		}
		$query2 = $this->db->query($q);
		return $query2;
	}
	
	public function simpan_biodata_siswa($data) {
		$cari = "SELECT * FROM trx_biodata_siswa WHERE id_formulir='".$data['idNoform']."'";
		$qcari = $this->db->query($cari);
		
		if($qcari->num_rows()>0) {
			$q="UPDATE trx_biodata_siswa 
			SET 
			no_formulir='".$data['txtNoform']."',
			nama_keluarga='".$data['txtNamaKeluarga']."', 
			nama_depan='".$data['txtNamaDepan']."',
			warga_negara='".$data['txtWarga']."',
			status_pernikahan='".$data['txtStatus']."',
			ktp='".$data['txtKTP']."',
			agama='".$data['txtAgama']."',
			pendidikan_terakhir='".$data['txtPendAkhir']."',
			pekerjaan='".$data['txtPekerjaan']."',
			nama_perusahaan='".$data['txtPerusahaan']."',
			kuliah_pci_dari='".$data['txtKuliahDari']."',
			kuliah_pci_sampai='".$data['txtKuliahHingga']."',
			kemampuan_mandarin='".$data['txtTingkatMandarin']."',
			program_pendidikan='".$data['txtProgramPend']."',
			user_input='".$this->session->userdata('pmb_userid')."',
			date_input=now()
			WHERE id_formulir='".$data['idNoform']."'";
		} else {
			$q="INSERT INTO trx_biodata_siswa 
			(id_formulir, no_formulir, nama_keluarga, nama_depan,warga_negara,status_pernikahan,ktp,agama,pendidikan_terakhir,pekerjaan,nama_perusahaan,
			kuliah_pci_dari,kuliah_pci_sampai,kemampuan_mandarin,program_pendidikan,user_input,date_input)
			VALUES
			(
			'".$data['idNoform']."',
			'".$data['txtNoform']."',
			'".$data['txtNamaKeluarga']."',
			'".$data['txtNamaDepan']."',
			'".$data['txtWarga']."',
			'".$data['txtStatus']."',
			'".$data['txtKTP']."',
			'".$data['txtAgama']."',
			'".$data['txtPendAkhir']."',
			'".$data['txtPekerjaan']."',
			'".$data['txtPerusahaan']."',
			'".$data['txtKuliahDari']."',
			'".$data['txtKuliahHingga']."',
			'".$data['txtTingkatMandarin']."',
			'".$data['txtProgramPend']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)
			";
		}
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function simpan_biodata_pendidikan($data) {
		$cari = "SELECT * FROM trx_biodata_pendidikan WHERE id_formulir='".$data['idNoform']."'";
		$qcari = $this->db->query($cari);
		
		if($qcari->num_rows()>0) {
			$hapus = "DELETE FROM trx_biodata_pendidikan WHERE id_formulir='".$data['idNoform']."'";
			$qhapus = $this->db->query($hapus);
		}
		
		for($a=0; $a < count($data['txtRiwayat']); $a++) {
			$q="INSERT INTO trx_biodata_pendidikan 
			(id_formulir, no_formulir, riwayat, sekolah_id, periode_dari, periode_sampai, jabatan, user_input, date_input)
			VALUES
			(
			'".$data['idNoform']."',
			'".$data['txtNoform']."',
			'".$data['txtRiwayat'][$a]."',
			'".$data['txtPendid'][$a]."',
			'".$data['txtPeriodeDari'][$a]."',
			'".$data['txtPeriodeKe'][$a]."',
			'".$data['txtJabatan'][$a]."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)
			";
			$query = $this->db->query($q);
		}
		
		return $query;
	}
	
	
	public function simpan_biodata_anggota($data) {
		$cari = "SELECT * FROM trx_biodata_anggota WHERE id_formulir='".$data['idNoform']."'";
		$qcari = $this->db->query($cari);
		
		if($qcari->num_rows()>0) {
			$hapus = "DELETE FROM trx_biodata_anggota WHERE id_formulir='".$data['idNoform']."'";
			$qhapus = $this->db->query($hapus);
		}
		
		for($a=0; $a < count($data['txtNamaKeluarga']); $a++) {
			$q="INSERT INTO trx_biodata_anggota 
			(id_formulir, no_formulir, nama, hubungan, telepon, user_input, date_input)
			VALUES
			(
			'".$data['idNoform']."',
			'".$data['txtNoform']."',
			'".$data['txtNamaKeluarga'][$a]."',
			'".$data['txtHubungan'][$a]."',
			'".$data['txtTelpfaks'][$a]."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)
			";
			$query = $this->db->query($q);
		}
		
		return $query;
	}
	
	
	
	public function simpan_biodata_keluarga($data) {
		$cari = "SELECT * FROM trx_biodata_keluarga WHERE id_formulir='".$data['idNoform']."'";
		$qcari = $this->db->query($cari);
		
		if($qcari->num_rows()>0) {
			$q="UPDATE trx_biodata_keluarga 
			SET 
			no_formulir='".$data['txtNoform']."',
			nama='".$data['txtNamaKel']."', 
			warga_negara='".$data['txtWarganegaraKel']."',
			hubungan='".$data['txtHubunganKel']."',
			alamat='".$data['txtAlamatKel']."',
			nama_atasan='".$data['txtAtasanKel']."',
			telepon_rumah='".$data['txtTelpKel']."',
			telepon_kantor='".$data['txtTelpkantorKel']."',
			telepon_faks='".$data['txtFaksKel']."',
			email='".$data['txtEmailKel']."',
			user_input='".$this->session->userdata('pmb_userid')."',
			date_input=now()
			WHERE id_formulir='".$data['idNoform']."'";
		} else {
			$q="INSERT INTO trx_biodata_keluarga 
			(id_formulir, no_formulir, nama, warga_negara,hubungan,alamat,nama_atasan,telepon_rumah,telepon_kantor,telepon_faks,
			email,user_input,date_input)
			VALUES
			(
			'".$data['idNoform']."',
			'".$data['txtNoform']."',
			'".$data['txtNamaKel']."',
			'".$data['txtWarganegaraKel']."',
			'".$data['txtHubunganKel']."',
			'".$data['txtAlamatKel']."',
			'".$data['txtAtasanKel']."',
			'".$data['txtTelpKel']."',
			'".$data['txtTelpkantorKel']."',
			'".$data['txtFaksKel']."',
			'".$data['txtEmailKel']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)
			";
		}
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function simpan_biodata_dana($data) {
		$cari = "SELECT * FROM trx_biodata_dana WHERE id_formulir='".$data['idNoform']."'";
		$qcari = $this->db->query($cari);
		
		if($qcari->num_rows()>0) {
			$q="UPDATE trx_biodata_dana 
			SET 
			no_formulir='".$data['txtNoform']."',
			sumber_dana='".$data['txtSumberDana']."',
			user_input='".$this->session->userdata('pmb_userid')."',
			date_input=now()
			WHERE id_formulir='".$data['idNoform']."'";
		} else {
			$q="INSERT INTO trx_biodata_dana 
			(id_formulir, no_formulir, sumber_dana,user_input,date_input)
			VALUES
			(
			'".$data['idNoform']."',
			'".$data['txtNoform']."',
			'".$data['txtSumberDana']."',
			'".$this->session->userdata('pmb_userid')."',
			now()
			)
			";
		}
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function simpan_biodata_pembayaran($data) {
		$cari = "SELECT * FROM trx_biodata_pembayaran WHERE id_formulir='".$data['idNoform']."'";
		$qcari = $this->db->query($cari);
		
		if($qcari->num_rows()>0) {
			$hapus = "DELETE FROM trx_biodata_pembayaran WHERE id_formulir='".$data['idNoform']."'";
			$qhapus = $this->db->query($hapus);
		}
		
		for($a=0; $a < count($data['txtNoCicilan']); $a++) {
			if($data['txtJenisPembayaran']=="Kredit") {
				if($data['txtNoCicilan'][$a]==1) {
					$q="INSERT INTO trx_biodata_pembayaran 
					(id_formulir, no_formulir, jenis_bayar, nomor_pembayaran, detail_pembayaran, jatuh_tempo, uang_pangkal, uang_semester, user_input, date_input)
					VALUES
					(
					'".$data['idNoform']."',
					'".$data['txtNoform']."',
					'".$data['txtJenisPembayaran']."',
					'".$data['txtNoCicilan'][$a]."',
					'".$data['txtDetailBayar'][$a]."',
					'".$data['txtJatuhTempo'][$a]."',
					'".$data['txtUangPangkal']."',
					'".$data['txtUangSemester']."',
					'".$this->session->userdata('pmb_userid')."',
					now()
					)
					";					
				} else {
					$q="INSERT INTO trx_biodata_pembayaran 
					(id_formulir, no_formulir, jenis_bayar, nomor_pembayaran, detail_pembayaran, jatuh_tempo, user_input, date_input)
					VALUES
					(
					'".$data['idNoform']."',
					'".$data['txtNoform']."',
					'".$data['txtJenisPembayaran']."',
					'".$data['txtNoCicilan'][$a]."',
					'".$data['txtDetailBayar'][$a]."',
					'".$data['txtJatuhTempo'][$a]."',
					'".$this->session->userdata('pmb_userid')."',
					now()
					)
					";	
				}
				$query = $this->db->query($q);
			} else {
				$q="INSERT INTO trx_biodata_pembayaran 
				(id_formulir, no_formulir, jenis_bayar, nomor_pembayaran, detail_pembayaran, jatuh_tempo, uang_pangkal, uang_semester, user_input, date_input)
				VALUES
				(
				'".$data['idNoform']."',
				'".$data['txtNoform']."',
				'".$data['txtJenisPembayaran']."',
				'".$data['txtNoCicilan'][$a]."',
				'".$data['txtDetailBayar'][$a]."',
				'".$data['txtJatuhTempo'][$a]."',
				'".$data['txtUangPangkal']."',
				'".$data['txtUangSemester']."',
				'".$this->session->userdata('pmb_userid')."',
				now()
				)
				";
				$query = $this->db->query($q);
			}
		}
		
		return $query;
	}
	
	
	public function jmlhal_trx_biodata($kriteria,$katacari) {
		$q = "SELECT a.*,b.sumber_dana FROM trx_biodata_siswa a
		LEFT JOIN trx_biodata_dana b
		ON a.id_formulir=b.id_formulir";
		
		if($kriteria!="") {
			if ($kriteria!="all") {
				$q .= " WHERE a.".$kriteria." LIKE '%".$katacari."%'";
			}
		}
		
		if($this->session->userdata('pmb_dept')==4) {
			$q .= " AND a.nim  != ''";
		} else {
			//$q .= " AND a.nim  LIKE ''";
		}
		
		
		//$q .= " ORDER BY a.id DESC";
		
		if($this->session->userdata('pmb_dept')==4) {
			$q .= " UNION ALL 
			SELECT a.*,b.sumber_dana 
			FROM trx_biodata_siswa_temp a 
			LEFT JOIN trx_biodata_dana_temp b 
			ON a.id_formulir=b.id_formulir";
			
			if($kriteria!="") {
				if ($kriteria!="all") {
					$q .= " WHERE a.".$kriteria." LIKE '%".$katacari."%'";
				}
			}
			
			if($this->session->userdata('pmb_dept')==4) {
				$q .= " AND a.nim != ''";
			}
			
			
			$q .= " ORDER BY id DESC";
			
		}
		//echo $q;
		
		$query = $this->db->query($q);
		return $query->num_rows();
	}
	
	public function tampil_trx_biodata($kriteria,$katacari,$page_now,$page_show) {
		$q = "SELECT a.*,b.sumber_dana FROM trx_biodata_siswa a
		LEFT JOIN trx_biodata_dana b
		ON a.id_formulir=b.id_formulir";
		
		if($kriteria!="") {
			if ($kriteria!="all") {
				$q .= " WHERE a.".$kriteria." LIKE '%".$katacari."%'";
			}
		}
		
		if($this->session->userdata('pmb_dept')==4) {
			$q .= " AND a.nim  != ''";
		} else {
			//$q .= " AND a.nim LIKE ''";
		}
		
		
		//$q .= " ORDER BY a.id DESC";
		
		if($this->session->userdata('pmb_dept')==4) {
			$q .= " UNION ALL 
			SELECT a.*,b.sumber_dana 
			FROM trx_biodata_siswa_temp a 
			LEFT JOIN trx_biodata_dana_temp b 
			ON a.id_formulir=b.id_formulir";
			
			if($kriteria!="") {
				if ($kriteria!="all") {
					$q .= " WHERE a.".$kriteria." LIKE '%".$katacari."%'";
				}
			}
			
			if($this->session->userdata('pmb_dept')==4) {
				$q .= " AND a.nim != ''";
			}
			
			$q .= " ORDER BY id DESC";
			
		}
		if($kriteria=="") {
			$q .= " LIMIT ".($page_now*$page_show).",".$page_show;
		}
		//echo $q;
		
		$query = $this->db->query($q);
		return $query;
	}
	public function header_tbl_trx_biodata() {
		$q = "SHOW COLUMNS FROM trx_biodata_siswa";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	
	public function show_bio_pend($idform) {
		$q = "SELECT * FROM trx_biodata_pendidikan WHERE id_formulir='".$idform."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function header_bio_pend($idform) {
		$q = "SHOW COLUMNS FROM trx_biodata_pendidikan";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function show_bio_anggota($idform) {
		$q = "SELECT * FROM trx_biodata_anggota WHERE id_formulir='".$idform."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function header_bio_anggota($idform) {
		$q = "SHOW COLUMNS FROM trx_biodata_anggota";
		$query = $this->db->query($q);
		return $query;
	}
	
	public function show_bio_keluarga($idform) {
		$q = "SELECT * FROM trx_biodata_keluarga WHERE id_formulir='".$idform."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function header_bio_keluarga($idform) {
		$q = "SHOW COLUMNS FROM trx_biodata_keluarga";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function show_bio_pembayaran($idform) {
		$q = "SELECT * FROM trx_biodata_pembayaran WHERE id_formulir='".$idform."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function header_bio_pembayaran($idform) {
		$q = "SHOW COLUMNS FROM trx_biodata_pembayaran";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	
	public function hapus_biodata_trx($data) {
		$q="DELETE FROM trx_biodata_siswa WHERE id_formulir='".$data['idform']."'";
		$query = $this->db->query($q);
		
		$q2="DELETE FROM trx_biodata_pendidikan WHERE id_formulir='".$data['idform']."'";
		$query2 = $this->db->query($q2);
		
		$q3="DELETE FROM trx_biodata_pembayaran WHERE id_formulir='".$data['idform']."'";
		$query3 = $this->db->query($q3);
		
		$q4="DELETE FROM trx_biodata_keluarga WHERE id_formulir='".$data['idform']."'";
		$query4 = $this->db->query($q4);
		
		$q5="DELETE FROM trx_biodata_dana WHERE id_formulir='".$data['idform']."'";
		$query5 = $this->db->query($q5);
		
		$q6="DELETE FROM trx_biodata_anggota WHERE id_formulir='".$data['idform']."'";
		$query6 = $this->db->query($q6);
		
		return $query6;
	}
	
	
	public function edit_biodata_data($idform) {
		$q = "SELECT * FROM trx_biodata_siswa WHERE id_formulir='".$idform."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function edit_biodata_keluarga($idform) {
		$q = "SELECT * FROM trx_biodata_keluarga WHERE id_formulir='".$idform."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function edit_biodata_dana($idform) {
		$q = "SELECT * FROM trx_biodata_dana WHERE id_formulir='".$idform."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function edit_biodata_pendidikan($data) {
		$q = "SELECT * FROM trx_biodata_pendidikan WHERE id_formulir='".$data['idform']."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function edit_biodata_anggota($data) {
		$q = "SELECT * FROM trx_biodata_anggota WHERE id_formulir='".$data['idform']."'";
		$query = $this->db->query($q);
		return $query;
	}
	public function edit_biodata_pembayaran($data) {
		$q = "SELECT * FROM trx_biodata_pembayaran WHERE id_formulir='".$data['idform']."'";
		$query = $this->db->query($q);
		return $query;
	}
	
	
	public function cari_data_sekolah() {
		$q = "SELECT * FROM ms_sekolah";
		$query = $this->db->query($q);
		return $query;
	}
}
?>