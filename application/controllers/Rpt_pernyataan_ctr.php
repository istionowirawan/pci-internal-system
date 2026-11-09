<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_pernyataan_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Rpt_pernyataan_model');
		$data['ta_pci_active'] = $this->Rpt_pernyataan_model->tahun_ajaran_pci_active();
		$data['data_makul_kelas'] = $this->Rpt_pernyataan_model->show_makul_kelas($data['ta_pci_active']);
		$data['data_makul_mahasiswa'] = $this->Rpt_pernyataan_model->show_makul_mahasiswa($data['ta_pci_active']);
		$data['data_mahasiswa'] = $this->Rpt_pernyataan_model->show_data_mahasiswa($data['ta_pci_active']);
		$this->load->view('humas/Rpt_pernyataan_view',$data);
	}
	
	public function cari_fakjur() {
		//print_r($_POST);
		$this->load->model('Rpt_pernyataan_model');
		$datafakjur = $this->Rpt_pernyataan_model->cari_fakjur($_POST);
		echo json_encode($datafakjur->result());
	}
	
	public function cari_makul_fak_jur() {
		//print_r($_POST);
		$this->load->model('Rpt_pernyataan_model');
		$data['ta_pci_active'] = $this->Rpt_pernyataan_model->tahun_ajaran_pci_active();
		$makulfakjur = $this->Rpt_pernyataan_model->cari_makul_fak_jur($_POST,$data['ta_pci_active']);
		echo json_encode($makulfakjur->result());
	}
	
	public function tampil_srt_pernyataan() {
		$noform = $this->uri->segment(3);
		
		$this->load->model('Rpt_pernyataan_model');
		$data['data_srt_pernyataan'] = $this->Rpt_pernyataan_model->data_srt_pernyataan($noform);
		$data['data_pembayaran'] = $this->Rpt_pernyataan_model->data_pembayaran($noform);
		$data['data_ayah_ibu'] = $this->Rpt_pernyataan_model->data_ayah_ibu($noform);
		$data['noform'] = $noform;
		$this->load->view('humas/Rpt_pernyataan_detail',$data);
	}
	
	
}
?>