<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_khs_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Rpt_khs_model');
		$data['ta_pci_active'] = $this->Rpt_khs_model->tahun_ajaran_pci_active();
		$data['ta_pci_list'] = $this->Rpt_khs_model->tahun_ajaran_pci_list();
		$data['fakultas_list'] = $this->Rpt_khs_model->fakultas_list();
		$this->load->view('pci/Rptkhs_view',$data);
	}
	
	public function jurusan_list() {
		//print_r($_POST);
		$this->load->model('Rpt_khs_model');
		$data_jurusan_list = $this->Rpt_khs_model->jurusan_list($_POST);
		echo json_encode($data_jurusan_list->result());
	}
	
	public function cetak_khs_pdf() {
		//print_r($_GET);
		$this->load->model('Rpt_khs_model');
		$data['data_nilai_mahasiswa'] = $this->Rpt_khs_model->data_nilai_mahasiswa($_GET);
		$data['data_nilai_posting'] = $this->Rpt_khs_model->data_nilai_posting($_GET);
		$data['jml_sks_semester'] = $this->Rpt_khs_model->jml_sks_semester($_GET);
		$data['direktur_pci'] = $this->Rpt_khs_model->nama_direktur_pci();
		$data['data_grade'] = $this->Rpt_khs_model->data_grade();
		$this->load->view('pci/Rptkhs_pdf',$data);
	}
	public function cetak_khs_web() {
		//print_r($_GET);
		$this->load->model('Rpt_khs_model');
		$data['data_nilai_mahasiswa'] = $this->Rpt_khs_model->data_nilai_mahasiswa($_GET);
		$data['data_nilai_posting'] = $this->Rpt_khs_model->data_nilai_posting($_GET);
		$data['jml_sks_semester'] = $this->Rpt_khs_model->jml_sks_semester($_GET);
		$data['direktur_pci'] = $this->Rpt_khs_model->nama_direktur_pci();
		$data['data_grade'] = $this->Rpt_khs_model->data_grade();
		$this->load->view('pci/Rptkhs_web',$data);
	}
	
	public function cetak_khstemp_pdf() {
		//print_r($_GET);
		
		$this->load->model('Rpt_khs_model');
		$data['data_nilai_mahasiswa'] = $this->Rpt_khs_model->data_nilai_sementara($_GET);
		$data['data_nilai_temp'] = $this->Rpt_khs_model->data_nilai_temp($_GET);
		$data['jml_sks_semester'] = $this->Rpt_khs_model->jml_sks_semester($_GET);
		$data['direktur_pci'] = $this->Rpt_khs_model->nama_direktur_pci();
		$data['data_grade'] = $this->Rpt_khs_model->data_grade();
		$this->load->view('pci/Rptkhstemp_pdf',$data);
		
	}
	public function cetak_khstemp_web() {
		//print_r($_GET);
		$this->load->model('Rpt_khs_model');
		$data['data_nilai_mahasiswa'] = $this->Rpt_khs_model->data_nilai_sementara($_GET);
		$data['data_nilai_temp'] = $this->Rpt_khs_model->data_nilai_temp($_GET);
		$data['jml_sks_semester'] = $this->Rpt_khs_model->jml_sks_semester($_GET);
		$data['direktur_pci'] = $this->Rpt_khs_model->nama_direktur_pci();
		$data['data_grade'] = $this->Rpt_khs_model->data_grade();
		$this->load->view('pci/Rptkhstemp_web',$data);
		
	}
	
}
?>