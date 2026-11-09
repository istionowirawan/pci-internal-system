<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Set_persentase_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Set_persentase_model');
		$data['ta_pci_active'] = $this->Set_persentase_model->tahun_ajaran_pci_active();
		$data['dosen_makul'] = $this->Set_persentase_model->show_dosen_makul($data['ta_pci_active']);
		$data['isi_set_persen'] = $this->Set_persentase_model->isi_set_persen($data['ta_pci_active']);
		$this->load->view('pci/SetPersentase_view',$data);
	}
	
	public function simpan_persentase() {
		//print_r($_POST);
		$this->load->model('Set_persentase_model');
		$result = $this->Set_persentase_model->simpan_persentase($_POST);
		echo $result;
	}
	
}
?>