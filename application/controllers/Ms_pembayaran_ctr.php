<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_pembayaran_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Ms_pembayaran_model');
		$data['data_fakultas'] = $this->Ms_pembayaran_model->cari_data_fakultas();
		$data['data_ta'] = $this->Ms_pembayaran_model->cari_data_ta();
		$data['data_ms_pembayaran'] = $this->Ms_pembayaran_model->data_ms_pembayaran();
		$this->load->view('msPembayaran_view',$data);
	}
	
	public function view_cicil_detail() {
		$thnajr = $this->uri->segment(3);
		
		$this->load->model('Ms_pembayaran_model');
		$data['data_ms_pembayaran_cicil'] = $this->Ms_pembayaran_model->data_ms_pembayaran_cicil($thnajr);
		$this->load->view('msPembayaran_cicil_view',$data);
	}
	
	public function update_ms_cicilan() {
		$this->load->model('Ms_pembayaran_model');
		$hasil = $this->Ms_pembayaran_model->update_cicil($_POST);
		echo $hasil;
	}
	
	public function update_ms_pembayaran() {
		$this->load->model('Ms_pembayaran_model');
		$hasil = $this->Ms_pembayaran_model->update_pembayaran($_POST);
		echo $hasil;
	}
	
}
?>