<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Set_mapelusm_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Set_mapelusm_model');
		$data['ta_active'] = $this->Set_mapelusm_model->tahun_ajaran_humas_active();
		$data['fakultas_list'] = $this->Set_mapelusm_model->fakultas_list();
		$this->load->view('humas/Set_mapelusm_view',$data);
	}
	
	public function jurusan_list() {
		//print_r($_POST);
		$this->load->model('Set_mapelusm_model');
		$data_jurusan_list = $this->Set_mapelusm_model->jurusan_list($_POST);
		echo json_encode($data_jurusan_list->result());
	}
	
	public function simpan_mapelusm() {
		//print_r($_POST);
		$this->load->model('Set_mapelusm_model');
		$result = $this->Set_mapelusm_model->simpan_mapelusm($_POST);
		echo $result;
	}
	
	public function cari_mapel_usm() {
		//print_r($_POST);
		$this->load->model('Set_mapelusm_model');
		$data_makul_usm = $this->Set_mapelusm_model->cari_mapel_usm($_POST);
		echo json_encode($data_makul_usm->result());
	}
	
}
?>