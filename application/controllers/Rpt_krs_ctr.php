<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_krs_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Rpt_krs_model');
		$data['ta_pci_active'] = $this->Rpt_krs_model->tahun_ajaran_pci_active();
		$data['ta_pci_list'] = $this->Rpt_krs_model->tahun_ajaran_pci_list();
		$data['fakultas_list'] = $this->Rpt_krs_model->fakultas_list();
		$this->load->view('pci/Rptkrs_view.php',$data);
	}
	
	public function jurusan_list() {
		//print_r($_POST);
		$this->load->model('Rpt_krs_model');
		$data_jurusan_list = $this->Rpt_krs_model->jurusan_list($_POST);
		echo json_encode($data_jurusan_list->result());
	}
	
	public function cetak_krs_pdf() {
		//print_r($_GET);
		$this->load->model('Rpt_krs_model');
		//$this->load->library('pdf_chinese');
		$data['data_krs_mahasiswa'] = $this->Rpt_krs_model->data_krs_mahasiswa($_GET);
		$data['data_krs_mahasiswa_detail'] = $this->Rpt_krs_model->data_krs_mahasiswa_detail($_GET);
		$data['direktur_pci'] = $this->Rpt_krs_model->nama_direktur_pci();
		$data['data_grade'] = $this->Rpt_krs_model->data_grade();
		$data['detail_master_dana'] = $this->Rpt_krs_model->detail_master_dana();
		$this->load->view('pci/Rptkrs_pdf',$data);
	}
	
	public function cetak_krs_web() {
		//print_r($_GET);
		$this->load->model('Rpt_krs_model');
		//$this->load->library('pdf_chinese');
		$data['data_krs_mahasiswa'] = $this->Rpt_krs_model->data_krs_mahasiswa($_GET);
		$data['data_krs_mahasiswa_detail'] = $this->Rpt_krs_model->data_krs_mahasiswa_detail($_GET);
		$data['direktur_pci'] = $this->Rpt_krs_model->nama_direktur_pci();
		$data['data_grade'] = $this->Rpt_krs_model->data_grade();
		$data['detail_master_dana'] = $this->Rpt_krs_model->detail_master_dana();
		$this->load->view('pci/Rptkrs_web',$data);
	}
	
	
}
?>