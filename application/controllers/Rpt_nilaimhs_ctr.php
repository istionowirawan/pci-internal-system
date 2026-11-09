<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_nilaimhs_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Rpt_nilaimhs_model');
		$data['ta_pci_active'] = $this->Rpt_nilaimhs_model->tahun_ajaran_pci_active();
		$data['dosen_ajar'] = $this->Rpt_nilaimhs_model->show_dosen_ajar();
		$this->load->view('pci/RptNilaimhs_view',$data);
	}
	
	public function cari_makul_filter() {
		$this->load->model('Rpt_nilaimhs_model');
		$dataMakulFilter = $this->Rpt_nilaimhs_model->cari_makul_dosen($_POST);
		echo json_encode($dataMakulFilter->result());
	}
	
	public function cari_kelas_filter() {
		$this->load->model('Rpt_nilaimhs_model');
		$dataKelasFilter = $this->Rpt_nilaimhs_model->cari_kelas_dosen($_POST);
		echo json_encode($dataKelasFilter->result());
	}
	
	public function cari_data_siswa() {
		$nikdosen = $this->uri->segment(3);
		$idmakul = $this->uri->segment(4);
		$idkelas = $this->uri->segment(5);
		//echo $nikdosen . " = " . $idmakul . " = " . $idkelas;
		
		$this->load->model('Rpt_nilaimhs_model');
		$data['ta_pci_active'] = $this->Rpt_nilaimhs_model->tahun_ajaran_pci_active();
		$data['datasiswa'] = $this->Rpt_nilaimhs_model->cari_data_siswa($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		//$data['jml_pertemuan_makul'] = $this->Rpt_nilaimhs_model->cari_jml_pertemuan($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['isi_absensi'] = $this->Rpt_nilaimhs_model->cari_isi_absensi($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['isi_nilai'] = $this->Rpt_nilaimhs_model->cari_isi_nilai($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		$data['trx_nilai_id'] = $this->Rpt_nilaimhs_model->cari_trx_nilai_id($nikdosen,$idmakul,$idkelas,$data['ta_pci_active']->tahun,$data['ta_pci_active']->semester);
		
		$this->load->view('pci/RptNilaimhs_detail',$data);
	}
	
	
	
}
?>