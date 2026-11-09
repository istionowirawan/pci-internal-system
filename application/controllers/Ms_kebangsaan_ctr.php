<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_kebangsaan_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Ms_kebangsaan_model');
		$data['data_kebangsaan'] = $this->Ms_kebangsaan_model->show_data_kebangsaan();
		$this->load->view('msKebangsaan_view',$data);
	}
		
}
?>