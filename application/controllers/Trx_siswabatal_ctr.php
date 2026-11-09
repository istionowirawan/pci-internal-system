<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trx_siswabatal_ctr extends CI_Controller {

	public function index()
	{
		$this->load->model('Trx_siswabatal_model');
		$data['ta_active'] = $this->Trx_siswabatal_model->ta_active();
		$data['data_mahasiswa'] = $this->Trx_siswabatal_model->data_mahasiswa($data['ta_active']);
		
		$this->load->view('humas/Trx_siswabatal_view',$data);
	}
	
	public function cari_ms_pembayaran() {
		$this->load->model('Trx_siswabatal_model');
		$data_pembayaran = $this->Trx_siswabatal_model->cari_ms_pembayaran($_POST);
		echo json_encode($data_pembayaran->result());
	}
	
	
	public function simpan_biodata_siswa() {
		//print_r($_POST);
		$this->load->model('Trx_siswabatal_model');
		$hasilsimpan = $this->Trx_siswabatal_model->simpan_biodata_siswa($_POST);
		echo $hasilsimpan;
	}
	
	public function cari_fakjur() {
		//print_r($_POST);
		$this->load->model('Trx_siswabatal_model');
		$datafakjur = $this->Trx_siswabatal_model->cari_fakjur($_POST);
		echo json_encode($datafakjur->result());
	}
	
	public function simpan_siswabatal() {
		//print_r($_POST);
		$this->load->model('Trx_siswabatal_model');
		$hasilsimpan = $this->Trx_siswabatal_model->simpan_siswabatal($_POST);
		echo $hasilsimpan;
	}
	
	public function data_mhs_batal() {
		$this->load->model('Trx_siswabatal_model');
		$data['ta_active'] = $this->Trx_siswabatal_model->ta_active();
		$data['mhs_batal'] = $this->Trx_siswabatal_model->mhs_batal($data['ta_active']->tahun);
		
		$this->load->view('humas/Trx_siswabatal_data',$data);
	}
	
}
?>