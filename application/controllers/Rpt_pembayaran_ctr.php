<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_pembayaran_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Rpt_pembayaran_model');
		$data['ta_keu_active'] = $this->Rpt_pembayaran_model->tahun_ajaran_keu_active();
		$data['fakultas_list'] = $this->Rpt_pembayaran_model->fakultas_list();
		$this->load->view('keuangan/Rpt_pembayaran_view',$data);
	}
	
	public function jurusan_list() {
		//print_r($_POST);
		$this->load->model('Rpt_pembayaran_model');
		$data_jurusan_list = $this->Rpt_pembayaran_model->jurusan_list($_POST);
		echo json_encode($data_jurusan_list->result());
	}
	
	public function tampil_siswa_usm() {
		//print_r($_POST);
		$fak = $this->uri->segment(3);
		$jur = $this->uri->segment(4);
		$ta = $this->uri->segment(5);
		
		$this->load->model('Rpt_pembayaran_model');
		$data['datasiswa'] = $this->Rpt_pembayaran_model->datasiswa($fak,$jur,$ta);
		$data['data_usm'] = $this->Rpt_pembayaran_model->data_usm($fak,$jur,$ta);
		$data['data_pembayaran'] = $this->Rpt_pembayaran_model->data_pembayaran($fak,$jur,$ta);
		
		$this->load->view('keuangan/Rpt_pembayaran_detail',$data);
	}
	
	
	
	
}
?>