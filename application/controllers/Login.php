<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$this->load->model('login_model');
		$data['dept'] = $this->login_model->show_all_dept();
		$data['pos'] = $this->login_model->show_all_position();
		
		$this->load->view('login_view',$data);
	}
	
	public function login_cek() {
		$this->load->model('login_model');
		$pass = $this->input->post('txtPassword');
		$hasilcek = $this->login_model->cek_login($_POST);
		
		if($hasilcek->num_rows()>0) {
			foreach ($hasilcek->result() as $row)
			{
				$newdata = array(
					'pmb_userid'  =>  $row->id,
					'pmb_username'  =>  $row->username,
					'pmb_password'  =>  $pass,
					'pmb_nama'  =>  $row->nama,
					'pmb_dept'  =>  $row->id_dept,
					'pmb_jabatan'  =>  $row->id_jabatan,
					'pmb_active'  =>  $row->active,
					'pmb_pic' => $row->pic
				);
			}
			$this->session->set_userdata($newdata);
		}
		
		echo $hasilcek->num_rows();
	}
	public function user_signup() {
		$this->load->model('login_model');
		$hasilcek = $this->login_model->cek_signup($_POST);
		echo $hasilcek;
	}
}
