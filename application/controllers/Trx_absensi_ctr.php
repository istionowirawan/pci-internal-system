<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trx_absensi_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Trx_absensi_model');
		$data['ta_pci_active'] = $this->Trx_absensi_model->tahun_ajaran_pci_active();
		$data['dosen_ajar'] = $this->Trx_absensi_model->show_dosen_ajar();
		$this->load->view('pci/TrxAbsensi_view',$data);
	}
	
	public function cari_makul_filter() {
		$this->load->model('Trx_absensi_model');
		$dataMakulFilter = $this->Trx_absensi_model->cari_makul_dosen($_POST);
		echo json_encode($dataMakulFilter->result());
	}
	
	public function cari_kelas_filter() {
		$this->load->model('Trx_absensi_model');
		$dataKelasFilter = $this->Trx_absensi_model->cari_kelas_dosen($_POST);
		echo json_encode($dataKelasFilter->result());
	}
	
	public function cari_data_siswa() {
		$nikdosen = $this->uri->segment(3);
		$idmakul = $this->uri->segment(4);
		$idkelas = $this->uri->segment(5);
		//echo $nikdosen . " = " . $idmakul . " = " . $idkelas;
		
		$this->load->model('Trx_absensi_model');
		$data['ta_pci_active'] = $this->Trx_absensi_model->tahun_ajaran_pci_active();
		$data['datasiswa'] = $this->Trx_absensi_model->cari_data_siswa($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['isi_absensi'] = $this->Trx_absensi_model->cari_isi_absensi($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['jml_pertemuan_makul'] = $this->Trx_absensi_model->cari_jml_pertemuan($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		
		$this->load->view('pci/TrxAbsensi_detail',$data);
	}
	
	
	public function hapus_makul_kelas() {
		$this->load->model('Trx_absensi_model');
		$result = $this->Trx_absensi_model->hapus_data_kelas($_POST);
		echo $result;
	}
	
	public function simpan_absensi() {
		//print_r($_POST);
		$this->load->model('Trx_absensi_model');
		$result = $this->Trx_absensi_model->simpan_absensi($_POST);
		echo $result;
	}
		
}
?>