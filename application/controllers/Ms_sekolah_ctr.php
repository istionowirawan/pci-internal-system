<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_sekolah_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('ms_sekolah_model');
		$data['data_sekolah'] = $this->ms_sekolah_model->tampil_data_sekolah();
		$this->load->view('msSekolah_view',$data);
	}
	
	public function simpan_sekolah() {
		$this->load->model('ms_sekolah_model');
		$result = $this->ms_sekolah_model->simpan_data_sekolah($_POST);
	}
		
}
?>