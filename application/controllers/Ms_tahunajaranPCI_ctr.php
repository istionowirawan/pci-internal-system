<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_tahunajaranPCI_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('mainmenu_model');
		$data['taPCI_data'] = $this->mainmenu_model->show_data_tahunajaranPCI();
		$data['ta_pci_active'] = $this->mainmenu_model->tahun_ajaran_pci_active();
		$this->load->view('msTahunajaranPCI_view',$data);
	}
	public function update_thn_ajr_pci() 
	{
		$this->load->model('mainmenu_model');
		$result = $this->mainmenu_model->update_tahunajaran_pci($_POST);
	}	
}
?>