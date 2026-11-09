<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_fakultas_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('ms_fakultas_model');
		$data['data_fakultas'] = $this->ms_fakultas_model->tampil_data_fakultas();
		$this->load->view('msFakultas_view',$data);
	}
	
	public function simpan_fakultas() {
		$this->load->model('ms_fakultas_model');
		$result = $this->ms_fakultas_model->simpan_data_fakultas($_POST);
	}
	
	public function cari_fakultas() {
		$this->load->model('ms_fakultas_model');
		$hasil = $this->ms_fakultas_model->cari_data_fakultas($_POST);
		echo json_encode($hasil->result());
	}	
	
	public function simpan_jurusan() {
		$this->load->model('ms_fakultas_model');
		$result = $this->ms_fakultas_model->simpan_data_jurusan($_POST);
	}
}
?>