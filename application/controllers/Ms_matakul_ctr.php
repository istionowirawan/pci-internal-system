<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_matakul_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('ms_matakul_model');
		$data['data_matakul'] = $this->ms_matakul_model->tampil_data_matakul();
		$data['data_fakultas'] = $this->ms_matakul_model->data_fakultas();
		$data['header_jurusan'] = $this->ms_matakul_model->header_jurusan();
		$this->load->view('pci/msMatakul_view',$data);
	}
	
	public function simpan_data_makul() {
		$this->load->model('ms_matakul_model');
		$result = $this->ms_matakul_model->simpan_data_makul($_POST);
		echo $result;
	}
	
	public function edit_data_makul() {
		$this->load->model('ms_matakul_model');
		$datamakul = $this->ms_matakul_model->edit_data_makul($_POST);
		echo json_encode($datamakul->result());
	}
	
	public function update_data_makul() {
		$this->load->model('ms_matakul_model');
		$result = $this->ms_matakul_model->update_data_makul($_POST);
		echo $result;
	}
	
	public function cari_jurusan() {
		$this->load->model('ms_matakul_model');
		$data_jur = $this->ms_matakul_model->cari_jurusan($_POST);
		echo json_encode($data_jur->result());
	}
	
		
}
?>