<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_biayaformulir_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Ms_biayaformulir_model');
		$data['data_ta'] = $this->Ms_biayaformulir_model->cari_tahun_ajaran();
		$this->load->view('msBiayaformulir_view',$data);
	}
	
	public function update_biayaformulir() {
		$this->load->model('Ms_biayaformulir_model');
		$hasil = $this->Ms_biayaformulir_model->update_biayaformulir($_POST);
		print_r($hasil);
	}
	
}
?>