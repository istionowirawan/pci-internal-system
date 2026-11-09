<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mainmenu extends CI_Controller {

	public function index()
	{
		$this->load->model('mainmenu_model');
		$data['jabatanuser'] = $this->mainmenu_model->load_ms_jabatan();
		$data['deptuser'] = $this->mainmenu_model->load_ms_dept();
		$this->load->view('Mainmenu_view',$data);
	}
	public function logout() {
		$this->session->sess_destroy();
		redirect('login', 'refresh');
	}
	public function back_to_login() {
		redirect('login', 'refresh');
	}
	
	public function menu_humas() {
		$this->load->model('mainmenu_model');
		$data['ta'] = $this->mainmenu_model->tahun_ajaran_active();
		$this->load->view('Mainmenu_humas',$data);
	}
	public function menu_keuangan() {
		$this->load->model('mainmenu_model');
		$data['ta'] = $this->mainmenu_model->tahun_ajaran_keu_active();
		$this->load->view('Mainmenu_keuangan',$data);
	}
	public function menu_pci() {
		$this->load->model('mainmenu_model');
		$data['ta'] = $this->mainmenu_model->tahun_ajaran_pci_active();
		$this->load->view('Mainmenu_pci',$data);
	}
	
	public function change_pass() {
		$this->load->view('changepass_view');
	}
	public function update_pass() {
		$username = $this->input->post('txtUsername');
		$newpass = $this->input->post('txtNewpass');
		
		$query = $this->db->query("UPDATE ms_user SET password='".md5($newpass)."' WHERE username='".$username."'");
		if($query==1) {
			echo "Your password has been updated";
			$newdata = array('pmb_password'  =>  $newpass);
			$this->session->set_userdata($newdata);	
		}
	}
	
	
	public function tampilan_awal() {
		$this->load->view('Tampilan_awal');
	}
}