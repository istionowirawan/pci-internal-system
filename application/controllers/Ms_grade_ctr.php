<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_grade_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Ms_grade_model');
		$data['data_grade'] = $this->Ms_grade_model->show_data_grade();
		$this->load->view('pci/msGrade_view',$data);
	}
	
	public function update_grade() {
		//print_r($_POST);
		$this->load->model('Ms_grade_model');
		$hasil = $this->Ms_grade_model->update_grade($_POST);
		echo $hasil;
	}
	
	
}
?>