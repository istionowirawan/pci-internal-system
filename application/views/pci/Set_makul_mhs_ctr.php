<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Set_makul_mhs_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Set_makul_mhs_model');
		$data['ta_pci_active'] = $this->Set_makul_mhs_model->tahun_ajaran_pci_active();
		$data['data_makul_kelas'] = $this->Set_makul_mhs_model->show_makul_kelas($data['ta_pci_active']);
		$data['data_makul_mahasiswa'] = $this->Set_makul_mhs_model->show_makul_mahasiswa($data['ta_pci_active']);
		$data['data_mahasiswa'] = $this->Set_makul_mhs_model->show_data_mahasiswa();
		$this->load->view('pci/setMakulMhs_view',$data);
	}
	
	public function simpan_makul_mhs() {
		//print_r($_POST);
		$this->load->model('Set_makul_mhs_model');
		$result = $this->Set_makul_mhs_model->simpan_makul_mahasiswa($_POST);
		echo $result;
	}
	
	public function load_makul_mhs() {
		$this->load->model('Set_makul_mhs_model');
		$dataMakulMhs = $this->Set_makul_mhs_model->load_makul_mahasiswa($_POST);
		//echo $dataMakulMhs;
		echo json_encode($dataMakulMhs->result());
	}
	
	public function edit_makul_mhs() {
		$this->load->model('Set_makul_mhs_model');
		$editMakulMhs = $this->Set_makul_mhs_model->edit_makul_mahasiswa($_POST);
		echo json_encode($editMakulMhs->result());
	}
	
	public function update_makul_mhs() {
		$this->load->model('Set_makul_mhs_model');
		$result = $this->Set_makul_mhs_model->update_makul_mahasiswa($_POST);
		echo $result;
	}
	
}
?>