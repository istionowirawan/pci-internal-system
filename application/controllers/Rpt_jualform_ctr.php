<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_jualform_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Rpt_jualform_model');
		$data['ta_active'] = $this->Rpt_jualform_model->tahun_ajaran_humas_active();
		$data['fakultas_list'] = $this->Rpt_jualform_model->fakultas_list();
		$this->load->view('humas/Rpt_jualform_view',$data);
	}
	
	public function jurusan_list() {
		//print_r($_POST);
		if($_POST['idfak']=="semua") {
			echo "semua";
		} else {
			$this->load->model('Rpt_jualform_model');
			$data_jurusan_list = $this->Rpt_jualform_model->jurusan_list($_POST);
			echo json_encode($data_jurusan_list->result());
		}
	}
	
	public function tampil_rekap_jualform() {
		//print_r($_POST);
		$fak = $this->uri->segment(3);
		$jur = $this->uri->segment(4);
		$ta = $this->uri->segment(5);
		$infoRpt = $this->uri->segment(6);
		
		$this->load->model('Rpt_jualform_model');
		$data['rekap_jualform'] = $this->Rpt_jualform_model->tampil_rekap_jualform($fak,$jur,$ta);
		$data['detail_jualform'] = $this->Rpt_jualform_model->tampil_detail_jualform($fak,$jur,$ta);
		$data['biaya_form'] = $this->Rpt_jualform_model->biaya_form($ta);
		$data['infoRpt'] = $infoRpt;
		
		$this->load->view('humas/Rpt_jualform_detail',$data);
	}
	
	
}
?>