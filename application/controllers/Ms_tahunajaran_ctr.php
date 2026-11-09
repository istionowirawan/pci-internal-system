<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_tahunajaran_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('mainmenu_model');
		$data['ta_data'] = $this->mainmenu_model->show_data_tahunajaran();
		$this->load->view('msTahunajaran_view',$data);
	}
	public function update_thn_ajr() 
	{
		$this->load->model('mainmenu_model');
		$result = $this->mainmenu_model->update_tahunajaran($_POST);
	}

	public function show_ta_keu()
	{
		$this->load->model('mainmenu_model');
		$data['taKEU_data'] = $this->mainmenu_model->show_data_tahunajaranKEU();
		$data['ta_keu_active'] = $this->mainmenu_model->tahun_ajaran_keu_active();
		$this->load->view('msTahunajaranKEU_view',$data);
	}	
	public function update_thn_ajr_keu() 
	{
		$this->load->model('mainmenu_model');
		$result = $this->mainmenu_model->update_tahunajaran_keu($_POST);
	}
}
?>