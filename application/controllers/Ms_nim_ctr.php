<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_nim_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Ms_nim_model');
		$data['ta_pci_active'] = $this->Ms_nim_model->tahun_ajaran_pci_active();
		$data['fakultas_list'] = $this->Ms_nim_model->fakultas_list();
		$this->load->view('pci/Ms_nim_view',$data);
	}
	
	public function jurusan_list() {
		//print_r($_POST);
		$this->load->model('Ms_nim_model');
		$data_jurusan_list = $this->Ms_nim_model->jurusan_list($_POST);
		echo json_encode($data_jurusan_list->result());
	}
	
	public function tampil_siswa_nim() {
		$fak = $this->uri->segment(3);
		$jur = $this->uri->segment(4);
		$ta = $this->uri->segment(5);
		
		$this->load->model('Ms_nim_model');
		$data['data_siswa'] = $this->Ms_nim_model->data_siswa($fak,$jur,$ta);
		$this->load->view('pci/Ms_nim_detail',$data);
	}
	
	public function simpan_nim() {
		//print_r($_POST);
		$this->load->model('Ms_nim_model');
		$hasil = $this->Ms_nim_model->simpan_nim($_POST);
		echo $hasil;
	}
	
}
?>