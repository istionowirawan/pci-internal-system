<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_hasilusm_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Trx_keputusan_model');
		$data['ta_active'] = $this->Trx_keputusan_model->tahun_ajaran_humas_active();
		$data['fakultas_list'] = $this->Trx_keputusan_model->fakultas_list();
		$this->load->view('humas/Rpt_hasilusm_view',$data);
	}
	
	public function jurusan_list() {
		//print_r($_POST);
		$this->load->model('Trx_keputusan_model');
		$data_jurusan_list = $this->Trx_keputusan_model->jurusan_list($_POST);
		echo json_encode($data_jurusan_list->result());
	}
	
	public function tampil_siswa_usm() {
		//print_r($_POST);
		$fak = $this->uri->segment(3);
		$jur = $this->uri->segment(4);
		$ta = $this->uri->segment(5);
		
		$this->load->model('Trx_keputusan_model');
		$data['datasiswa'] = $this->Trx_keputusan_model->tampil_siswa_usm($fak,$jur,$ta);
		$data['makulusm'] = $this->Trx_keputusan_model->makul_usm_diujikan($fak,$jur,$ta);
		$data['data_usm'] = $this->Trx_keputusan_model->data_usm($fak,$jur,$ta);
		
		$this->load->view('humas/Rpt_hasilusm_detail',$data);
	}
	
	
}
?>