<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trx_nilaimhs_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Trx_nilaimhs_model');
		$data['ta_pci_active'] = $this->Trx_nilaimhs_model->tahun_ajaran_pci_active();
		$data['dosen_ajar'] = $this->Trx_nilaimhs_model->show_dosen_ajar();
		$this->load->view('pci/TrxNilaimhs_view',$data);
	}
	
	public function cari_makul_filter() {
		$this->load->model('Trx_nilaimhs_model');
		$dataMakulFilter = $this->Trx_nilaimhs_model->cari_makul_dosen($_POST);
		echo json_encode($dataMakulFilter->result());
	}
	
	public function cari_kelas_filter() {
		$this->load->model('Trx_nilaimhs_model');
		$dataKelasFilter = $this->Trx_nilaimhs_model->cari_kelas_dosen($_POST);
		echo json_encode($dataKelasFilter->result());
	}
	
	public function cari_data_siswa() {
		$nikdosen = $this->uri->segment(3);
		$idmakul = $this->uri->segment(4);
		$idkelas = $this->uri->segment(5);
		//echo $nikdosen . " = " . $idmakul . " = " . $idkelas;
		
		$this->load->model('Trx_nilaimhs_model');
		$data['ta_pci_active'] = $this->Trx_nilaimhs_model->tahun_ajaran_pci_active();
		$data['datasiswa'] = $this->Trx_nilaimhs_model->cari_data_siswa($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['jml_pertemuan_makul'] = $this->Trx_nilaimhs_model->cari_jml_pertemuan($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['isi_absensi'] = $this->Trx_nilaimhs_model->cari_isi_absensi($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['isi_nilai'] = $this->Trx_nilaimhs_model->cari_isi_nilai($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['trx_nilai_id'] = $this->Trx_nilaimhs_model->cari_trx_nilai_id($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['data_grade'] = $this->Trx_nilaimhs_model->data_grade();
		
		$this->load->view('pci/TrxNilaimhs_detail',$data);
	}
	
	public function simpan_nilai() {
		//print_r($_POST);
		$this->load->model('Trx_nilaimhs_model');
		$result = $this->Trx_nilaimhs_model->simpan_nilai($_POST);
		echo $result;
	}
	
	public function posting_nilai() {
		//print_r($_POST);
		$this->load->model('Trx_nilaimhs_model');
		$result = $this->Trx_nilaimhs_model->posting_nilai($_POST);
		echo $result;
	}
	
	
	public function undo_posting() {
		//print_r($_POST);
		$this->load->model('Trx_nilaimhs_model');
		$result = $this->Trx_nilaimhs_model->undo_posting($_POST);
		echo $result;
	}
	
}
?>