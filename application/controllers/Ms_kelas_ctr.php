<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_kelas_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('ms_kelas_model');
		$data['data_kelas'] = $this->ms_kelas_model->tampil_data_kelas();
		$data['data_fakultas'] = $this->ms_kelas_model->data_fakultas();
		$this->load->view('pci/msKelas_view',$data);
	}
	
	public function simpan_data_kelas() {
		$this->load->model('ms_kelas_model');
		$result = $this->ms_kelas_model->simpan_data_kelas($_POST);
		echo $result;
	}
	
	public function edit_data_kelas() {
		$this->load->model('ms_kelas_model');
		$datakelas = $this->ms_kelas_model->edit_data_kelas($_POST);
		echo json_encode($datakelas->result());
	}
	
	public function update_data_kelas() {
		$this->load->model('ms_kelas_model');
		$result = $this->ms_kelas_model->update_data_kelas($_POST);
		echo $result;
	}
	
	public function cari_jurusan() {
		$this->load->model('ms_kelas_model');
		$data_jur = $this->ms_kelas_model->cari_jurusan($_POST);
		echo json_encode($data_jur->result());
	}
		
}
?>