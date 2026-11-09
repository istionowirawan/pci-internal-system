<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_dosen_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('ms_dosen_model');
		$data['data_dosen'] = $this->ms_dosen_model->tampil_data_dosen();
		$this->load->view('msDosen_view',$data);
	}
	
	public function simpan_data_dosen() {
		$this->load->model('ms_dosen_model');
		$result = $this->ms_dosen_model->simpan_data_dosen($_POST);
		echo $result;
	}
	
	public function active_data_dosen() {
		$this->load->model('ms_dosen_model');
		$result = $this->ms_dosen_model->active_data_dosen($_POST);
		print_r($result);
	}
	
	public function edit_data_dosen() {
		$this->load->model('ms_dosen_model');
		$datadosen = $this->ms_dosen_model->edit_data_dosen($_POST);
		echo json_encode($datadosen->result());
	}
	
	public function update_data_dosen() {
		$this->load->model('ms_dosen_model');
		$result = $this->ms_dosen_model->update_data_dosen($_POST);
		echo $result;
	}
	
		
}
?>