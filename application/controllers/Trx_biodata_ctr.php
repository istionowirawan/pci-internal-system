<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trx_biodata_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Trx_biodata_model');
		$data['data_fakultas'] = $this->Trx_biodata_model->cari_data_fakultas();
		$data['data_ta'] = $this->Trx_biodata_model->cari_data_ta();
		$data['data_sekolah'] = $this->Trx_biodata_model->cari_data_sekolah();
		
		$this->load->view('humas/trxBiodata_view',$data);
	}
	
	
	public function tampil_data() {
		$this->load->model('Trx_biodata_model');
		
		$filterKriteria="";
		$filterCari="";
		if($this->uri->segment(3)!=null) {
			$filterKriteria=$this->uri->segment(3);
		}
		if($this->uri->segment(4)!=null) {
			$filterCari=$this->uri->segment(4);
		}
		
		$data['data_trxFormulir'] = $this->Trx_biodata_model->tampil_trx_formulir($filterKriteria,$filterCari);
		$data['header_trx'] = $this->Trx_biodata_model->header_tbl_trx_formulir();
		$this->load->view('humas/trxBiodata_take_formulir',$data);
	}
	
	
	public function edit_formulir_trx() {
		$trxid = $this->uri->segment(3);
		$this->load->model('Trx_biodata_model');
		
		$data['data_fakultas'] = $this->Trx_biodata_model->cari_data_fakultas();
		$data['data_trx_formulir'] = $this->Trx_biodata_model->edit_formulir_data($trxid);
		$data['data_jurusan'] = $this->Trx_biodata_model->cari_data_jurusan2($data['data_trx_formulir']->row(0)->fakultas_id);
		$data['data_ta'] = $this->Trx_biodata_model->cari_data_ta();
		$data['data_sekolah'] = $this->Trx_biodata_model->cari_data_sekolah();
		
		$this->load->view('humas/trxBiodata_view',$data);
		
	}
	
	
	public function cari_ms_pembayaran() {
		$this->load->model('Trx_biodata_model');
		$data_pembayaran = $this->Trx_biodata_model->cari_ms_pembayaran($_POST);
		echo json_encode($data_pembayaran->result());
	}
	
	
	public function simpan_biodata_siswa() {
		//print_r($_POST);
		$this->load->model('Trx_biodata_model');
		$hasilsimpan = $this->Trx_biodata_model->simpan_biodata_siswa($_POST);
		echo $hasilsimpan;
	}
	
	public function simpan_biodata_pendidikan() {
		//print_r($_POST);
		$this->load->model('Trx_biodata_model');
		$hasilsimpan = $this->Trx_biodata_model->simpan_biodata_pendidikan($_POST);
		echo $hasilsimpan;
	}
	
	public function simpan_biodata_anggota() {
		//print_r($_POST);
		$this->load->model('Trx_biodata_model');
		$hasilsimpan = $this->Trx_biodata_model->simpan_biodata_anggota($_POST);
		echo $hasilsimpan;
	}
	
	public function simpan_biodata_keluarga() {
		//print_r($_POST);
		$this->load->model('Trx_biodata_model');
		$hasilsimpan = $this->Trx_biodata_model->simpan_biodata_keluarga($_POST);
		echo $hasilsimpan;
	}
	
	public function simpan_biodata_dana() {
		//print_r($_POST);
		$this->load->model('Trx_biodata_model');
		$hasilsimpan = $this->Trx_biodata_model->simpan_biodata_dana($_POST);
		echo $hasilsimpan;
	}
	
	public function simpan_biodata_pembayaran() {
		//print_r($_POST);
		$this->load->model('Trx_biodata_model');
		$hasilsimpan = $this->Trx_biodata_model->simpan_biodata_pembayaran($_POST);
		echo $hasilsimpan;
	}
	
	
	
	public function tampil_data_biodata() {
		$this->load->model('Trx_biodata_model');
		
		$filterKriteria="";
		$filterCari="";
		$nomorhal = 0;
		$page_show = 10;
		
		if($this->uri->segment(3)!=null) {
			$nomorhal=$this->uri->segment(3);
		}
		if($this->uri->segment(4)!=null) {
			$filterKriteria=$this->uri->segment(4);
		}
		if($this->uri->segment(5)!=null) {
			$filterCari=$this->uri->segment(5);
		}
		
		$data['data_trxBiodata'] = $this->Trx_biodata_model->tampil_trx_biodata($filterKriteria,$filterCari,$nomorhal,$page_show);
		
		$data['jmlhal_trxBiodata'] = $this->Trx_biodata_model->jmlhal_trx_biodata($filterKriteria,$filterCari);
		$data['hal_active'] = $nomorhal;
		$data['show_row'] = $page_show;
		
		$data['header_trx'] = $this->Trx_biodata_model->header_tbl_trx_biodata();
		$this->load->view('humas/trxBiodata_data',$data);
	}
	
	public function hapus_biodata_trx() {
		//print_r($_POST);
		$this->load->model('Trx_biodata_model');
		$hasil = $this->Trx_biodata_model->hapus_biodata_trx($_POST);
		echo $hasil;
	}
	
	public function edit_biodata_trx() {
		$idform = $this->uri->segment(3);
		$this->load->model('Trx_biodata_model');
		
		$data['data_fakultas'] = $this->Trx_biodata_model->cari_data_fakultas();
		$data['data_trx_formulir'] = $this->Trx_biodata_model->edit_formulir_data($idform);
		$data['data_jurusan'] = $this->Trx_biodata_model->cari_data_jurusan2($data['data_trx_formulir']->row(0)->fakultas_id);
		$data['data_ta'] = $this->Trx_biodata_model->cari_data_ta();
		
		$data['data_trx_biodata'] = $this->Trx_biodata_model->edit_biodata_data($idform);
		$data['data_trx_biokeluarga'] = $this->Trx_biodata_model->edit_biodata_keluarga($idform);
		$data['data_trx_biodana'] = $this->Trx_biodata_model->edit_biodata_dana($idform);
		$data['stat_edit'] = "Edit";
		$data['data_sekolah'] = $this->Trx_biodata_model->cari_data_sekolah();
		
		$this->load->view('humas/trxBiodata_view',$data);
	}
	
	public function edit_biodata_pend() {
		$this->load->model('Trx_biodata_model');
		$data_bio_pend = $this->Trx_biodata_model->edit_biodata_pendidikan($_POST);
		echo json_encode($data_bio_pend->result());
	}
	public function edit_biodata_anggota() {
		$this->load->model('Trx_biodata_model');
		$data_bio_anggota = $this->Trx_biodata_model->edit_biodata_anggota($_POST);
		echo json_encode($data_bio_anggota->result());
	}
	public function edit_biodata_pembayaran() {
		$this->load->model('Trx_biodata_model');
		$data_bio_pmbyran = $this->Trx_biodata_model->edit_biodata_pembayaran($_POST);
		echo json_encode($data_bio_pmbyran->result());
	}
	
	
	
	
	public function show_bio_pend() {
		$idform = $this->uri->segment(3);
		$this->load->model('Trx_biodata_model');
		$data['detail_biodata'] = $this->Trx_biodata_model->show_bio_pend($idform);
		$data['head_detail'] = $this->Trx_biodata_model->header_bio_pend($idform);
		$data['jenis_detail'] = "Pembayaran";
		$this->load->view('humas/trxBiodata_detail',$data);
	}
	public function show_bio_anggota() {
		$idform = $this->uri->segment(3);
		$this->load->model('Trx_biodata_model');
		$data['detail_biodata'] = $this->Trx_biodata_model->show_bio_anggota($idform);
		$data['head_detail'] = $this->Trx_biodata_model->header_bio_anggota($idform);
		$data['jenis_detail'] = "Anggota";
		$this->load->view('humas/trxBiodata_detail',$data);
	}
	public function show_bio_keluarga() {
		$idform = $this->uri->segment(3);
		$this->load->model('Trx_biodata_model');
		$data['detail_biodata'] = $this->Trx_biodata_model->show_bio_keluarga($idform);
		$data['head_detail'] = $this->Trx_biodata_model->header_bio_keluarga($idform);
		$data['jenis_detail'] = "Keluarga";
		$this->load->view('humas/trxBiodata_detail',$data);
	}
	public function show_bio_pembayaran() {
		$idform = $this->uri->segment(3);
		$this->load->model('Trx_biodata_model');
		$data['detail_biodata'] = $this->Trx_biodata_model->show_bio_pembayaran($idform);
		$data['head_detail'] = $this->Trx_biodata_model->header_bio_pembayaran($idform);
		$data['jenis_detail'] = "Pembayaran";
		$this->load->view('humas/trxBiodata_detail',$data);
	}
	
}
?>