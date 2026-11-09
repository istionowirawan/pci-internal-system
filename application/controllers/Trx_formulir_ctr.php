<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trx_formulir_ctr extends CI_Controller {

	public function index()
	{
		/*
		$data['data_questioner'] = $this->Trx_questioner_model->tampil_questioner();
		$data['data_trx_questioner'] = $this->Trx_questioner_model->edit_questioner_data($trxid);
		$data['data_answer_questioner'] = $this->Trx_questioner_model->edit_questioner_answer($trxid);
		$data['data_jurusan'] = $this->Trx_questioner_model->cari_data_jurusan2($data['data_trx_questioner']->row(0)->fakultas_id);*/
		
		$this->load->model('Trx_formulir_model');
		$data['data_fakultas'] = $this->Trx_formulir_model->cari_data_fakultas();
		$data['data_ta'] = $this->Trx_formulir_model->cari_data_ta();
		$this->load->view('humas/trxFormulir_view',$data);
	}
	
	public function tampil_data() {
		$this->load->model('Trx_formulir_model');
		
		$filterKriteria="";
		$filterCari="";
		if($this->uri->segment(3)!=null) {
			$filterKriteria=$this->uri->segment(3);
		}
		if($this->uri->segment(4)!=null) {
			$filterCari=$this->uri->segment(4);
		}
		
		$data['data_trxQuestioner'] = $this->Trx_formulir_model->tampil_trx_questioner($filterKriteria,$filterCari);
		$data['header_trx'] = $this->Trx_formulir_model->header_tbl_trx_questioner();
		$this->load->view('humas/trxFormulir_take_questioner',$data);
	}
	
	public function cek_no_form_double() {
		$this->load->model('Trx_formulir_model');
		$stat_double = $this->Trx_formulir_model->cek_no_form_double($_POST);
		echo $stat_double;
	}
	
	
	
	public function simpan_trx_formulir() {
		//print_r($_POST);
		$this->load->model('Trx_formulir_model');
		$hasil = $this->Trx_formulir_model->simpan_trx_formulir($_POST);
		echo $hasil;
	}
	public function update_trx_formulir() {
		//print_r($_POST);
		$this->load->model('Trx_formulir_model');
		$hasil = $this->Trx_formulir_model->update_trx_formulir($_POST);
		echo $hasil;
	}
	
	
	
	
	
	public function tampil_data_formulir() {
		$this->load->model('Trx_formulir_model');
		
		$filterKriteria="";
		$filterCari="";
		if($this->uri->segment(3)!=null) {
			$filterKriteria=$this->uri->segment(3);
		}
		if($this->uri->segment(4)!=null) {
			$filterCari=$this->uri->segment(4);
		}
		
		$data['data_trxFormulir'] = $this->Trx_formulir_model->tampil_trx_formulir($filterKriteria,$filterCari);
		$data['header_trx'] = $this->Trx_formulir_model->header_tbl_trx_formulir();
		$this->load->view('humas/trxFormulir_data',$data);
	}
	public function hapus_formulir_trx() {
		$this->load->model('Trx_formulir_model');
		$hasil = $this->Trx_formulir_model->hapus_formulir_trx($_POST);
	}
	
	
	public function edit_questioner_trx() {
		$trxid = $this->uri->segment(3);
		$this->load->model('Trx_formulir_model');
		
		$data['data_fakultas'] = $this->Trx_formulir_model->cari_data_fakultas();
		$data['data_questioner'] = $this->Trx_formulir_model->tampil_questioner();
		$data['data_trx_questioner'] = $this->Trx_formulir_model->edit_questioner_data($trxid);
		$data['data_jurusan'] = $this->Trx_formulir_model->cari_data_jurusan2($data['data_trx_questioner']->row(0)->fakultas_id);
		$data['data_ta'] = $this->Trx_formulir_model->cari_data_ta();
		
		$this->load->view('humas/trxFormulir_view',$data);
		
	}
	
	public function edit_formulir_trx() {
		$trxid = $this->uri->segment(3);
		$this->load->model('Trx_formulir_model');
		
		$data['data_fakultas'] = $this->Trx_formulir_model->cari_data_fakultas();
		$data['data_questioner'] = $this->Trx_formulir_model->tampil_questioner();
		$data['data_trx_questioner'] = $this->Trx_formulir_model->edit_formulir_data($trxid);
		$data['data_jurusan'] = $this->Trx_formulir_model->cari_data_jurusan2($data['data_trx_questioner']->row(0)->fakultas_id);
		$data['data_ta'] = $this->Trx_formulir_model->cari_data_ta();
		$data['stat_edit'] = "Edit";
		
		$this->load->view('humas/trxFormulir_view',$data);
		
	}
	
	
	public function tampil_kuitansi() {
		$this->load->model('Trx_formulir_model');
		
		$trxid = $this->uri->segment(3);
		$data['data_trx_questioner'] = $this->Trx_formulir_model->edit_formulir_data($trxid);
		$data['data_biaya_form'] = $this->Trx_formulir_model->biaya_formulir();
		$this->load->view('humas/trxFormulir_kwitansi',$data);
	}
	
}
?>