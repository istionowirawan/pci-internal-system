<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_questioner_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('ms_questioner_model');
		$data['judul_questioner'] = $this->ms_questioner_model->tampil_judul_questioner();
		$this->load->view('humas/msQuestioner_view',$data);
	}
	
	public function simpan_questioner_judul() {
		$this->load->model('ms_questioner_model');
		$result = $this->ms_questioner_model->simpan_judul_questioner($_POST);
	}
	
	public function update_judul_active() {
		$this->load->model('ms_questioner_model');
		$result = $this->ms_questioner_model->update_active_judul($_POST);
	}
	
	public function modify_questioner() {
		$idQuest = $this->uri->segment(3);
		$this->load->model('ms_questioner_model');
		
		$data['data_header'] = $this->ms_questioner_model->cari_judul_questioner($idQuest);
		$data['data_issue'] = $this->ms_questioner_model->cari_issue_questioner($idQuest);
		$data['id_questioner'] = $idQuest;
		
		$this->load->view('humas/msQuestioner_detail',$data);
	}
	
	public function simpan_issue_questioner() {
		//print_r($_POST);
		$this->load->model('ms_questioner_model');
		$result = $this->ms_questioner_model->simpan_issue_questioner($_POST);
	}
	
	public function hapus_issue_questioner() {
		$this->load->model('ms_questioner_model');
		$result = $this->ms_questioner_model->hapus_issue_questioner($_POST);
	}
	
	public function show_detail_question() {
		$this->load->model('ms_questioner_model');
		$hasil = $this->ms_questioner_model->show_detail_question($_POST);
		echo json_encode($hasil->result());
	}
		
}
?>