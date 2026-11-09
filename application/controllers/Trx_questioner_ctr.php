<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trx_questioner_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Trx_questioner_model');
		$data['data_fakultas'] = $this->Trx_questioner_model->cari_data_fakultas();
		$data['data_questioner'] = $this->Trx_questioner_model->tampil_questioner();
		$this->load->view('humas/trxQuestioner_view',$data);
	}
	
	public function cari_data_jurusan() {
		$this->load->model('Trx_questioner_model');
		$hasil = $this->Trx_questioner_model->cari_data_jurusan($_POST);
		echo json_encode($hasil->result());
	}
	
	public function simpan_trx_questioner() {
		$this->load->model('Trx_questioner_model');
		$hasil = $this->Trx_questioner_model->simpan_trx_questioner($_POST);
		//print_r($hasil);
	}
	
	public function tampil_data() {
		$this->load->model('Trx_questioner_model');
		
		$filterKriteria="";
		$filterCari="";
		if($this->uri->segment(3)!=null) {
			$filterKriteria=$this->uri->segment(3);
		}
		if($this->uri->segment(4)!=null) {
			$filterCari=$this->uri->segment(4);
		}
		
		$data['data_trxQuestioner'] = $this->Trx_questioner_model->tampil_trx_questioner($filterKriteria,$filterCari);
		$data['header_trx'] = $this->Trx_questioner_model->header_tbl_trx_questioner();
		$this->load->view('humas/trxQuestioner_data',$data);
	}
	
	public function hapus_questioner_trx() {
		$this->load->model('Trx_questioner_model');
		$hasil = $this->Trx_questioner_model->hapus_questioner_trx($_POST);
	}
	
	
	public function edit_questioner_trx() {
		$trxid = $this->uri->segment(3);
		$this->load->model('Trx_questioner_model');
		$data['data_fakultas'] = $this->Trx_questioner_model->cari_data_fakultas();
		$data['data_questioner'] = $this->Trx_questioner_model->tampil_questioner();
		$data['data_trx_questioner'] = $this->Trx_questioner_model->edit_questioner_data($trxid);
		$data['data_answer_questioner'] = $this->Trx_questioner_model->edit_questioner_answer($trxid);
		$data['data_jurusan'] = $this->Trx_questioner_model->cari_data_jurusan2($data['data_trx_questioner']->row(0)->fakultas_id);
		
		$this->load->view('humas/trxQuestioner_view',$data);
		
	}
	
	public function update_trx_questioner() {
		//print_r($_POST);
		$this->load->model('Trx_questioner_model');
		$hasil = $this->Trx_questioner_model->update_trx_questioner($_POST);
		//print_r($hasil);
	}
	
}
?>