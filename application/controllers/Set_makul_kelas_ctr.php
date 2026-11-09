<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Set_makul_kelas_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Set_makul_kelas_model');
		$data['ta_pci_active'] = $this->Set_makul_kelas_model->tahun_ajaran_pci_active();
		$data['data_fakultas'] = $this->Set_makul_kelas_model->data_fakultas();
		//$data['data_makul'] = $this->Set_makul_kelas_model->show_data_makul();
		$data['data_kelas'] = $this->Set_makul_kelas_model->show_data_kelas();
		$data['data_dosen'] = $this->Set_makul_kelas_model->show_data_dosen();
		$data['data_makul_kelas'] = $this->Set_makul_kelas_model->load_makul_kelas($data['ta_pci_active']);
		$this->load->view('pci/setMakulKelas_view',$data);
	}
	
	public function simpan_makul_kelas() {
		$this->load->model('Set_makul_kelas_model');
		$result = $this->Set_makul_kelas_model->simpan_makul_kelas($_POST);
		echo $result;
		//print_r($result);
	}
	
	public function hapus_makul_kelas() {
		$this->load->model('Set_makul_kelas_model');
		$result = $this->Set_makul_kelas_model->hapus_data_kelas($_POST);
		echo $result;
	}
	
	public function cari_jurusan() {
		$this->load->model('Set_makul_kelas_model');
		$data_jur = $this->Set_makul_kelas_model->cari_jurusan($_POST);
		echo json_encode($data_jur->result());
	}
	
	public function cari_periode() {
		//print_r($_POST);
		$this->load->model('Set_makul_kelas_model');
		$data_periode = $this->Set_makul_kelas_model->show_data_periode($_POST);
		echo json_encode($data_periode->result());
	}
	
	public function cari_makul() {
		//print_r($_POST);
		$this->load->model('Set_makul_kelas_model');
		$data_makul = $this->Set_makul_kelas_model->show_data_makul($_POST);
		echo json_encode($data_makul->result());
	}
		
}
?>