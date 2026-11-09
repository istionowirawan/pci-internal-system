<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_aktifasi_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('ms_aktifasi_model');
		$data['data_non_verfied'] = $this->ms_aktifasi_model->user_non_verfied();
		$this->load->view('msAktifasi_view',$data);
	}
	
	public function aktifasi_user() {
		$this->load->model('ms_aktifasi_model');
		$aktifasi_do = $this->ms_aktifasi_model->aktifasi_update($_POST);
	}
	
	public function block_user() {
		$this->load->model('ms_aktifasi_model');
		$block_do = $this->ms_aktifasi_model->block_update($_POST);
		//echo $block_do;
	}
		
}
?>