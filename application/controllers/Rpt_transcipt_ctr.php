<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_transcipt_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Rpt_transcipt_model');
		$data['ta_pci_active'] = $this->Rpt_transcipt_model->tahun_ajaran_pci_active();
		$data['ta_pci_list'] = $this->Rpt_transcipt_model->tahun_ajaran_pci_list();
		$data['fakultas_list'] = $this->Rpt_transcipt_model->fakultas_list();
		$this->load->view('pci/Rpttranscipt_view',$data);
	}
	
	public function jurusan_list() {
		//print_r($_POST);
		$this->load->model('Rpt_transcipt_model');
		$data_jurusan_list = $this->Rpt_transcipt_model->jurusan_list($_POST);
		echo json_encode($data_jurusan_list->result());
	}
	
	public function list_mahasiswa() {
		//print_r($_POST);
		$this->load->model('Rpt_transcipt_model');
		$data_mahasiswa_list = $this->Rpt_transcipt_model->list_mahasiswa($_POST);
		echo json_encode($data_mahasiswa_list->result());
		//echo $data_mahasiswa_list;
	}
	
	public function cetak_transcipt_pdf() {
		$this->load->model('Rpt_transcipt_model');
		$data['data_transcipt_mhs'] = $this->Rpt_transcipt_model->data_transcipt_mhs($_GET);
		$data['data_transcipt_nilai'] = $this->Rpt_transcipt_model->data_transcipt_nilai($_GET);
		$data['direktur_pci'] = $this->Rpt_transcipt_model->nama_direktur_pci();
		$data['data_grade'] = $this->Rpt_transcipt_model->data_grade();
		$data['kirim'] = $_GET;
		$this->load->view('pci/Rpttranscipt_pdf',$data);
	}
	
	
	
}
?>