<script>
	$(document).ready(function(){
		function tandai_menu_dipilih(menuPilih) {
			$( ".panel_setting_subjudul" ).removeClass( "mn_active" );
			menuPilih.addClass("mn_active");
		}
		
		$("#msTahunAjaran").click(call_msTahunAjaran);
		function call_msTahunAjaran() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_tahunajaran_ctr");
		}
		
		$("#msAktifasi").click(call_msAktifasi);
		function call_msAktifasi() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_aktifasi_ctr");
		}
		
		$("#msSekolah").click(call_msSekolah);
		function call_msSekolah() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_sekolah_ctr");
		}
		
		$("#msFakultas").click(call_msFakultas);
		function call_msFakultas() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_fakultas_ctr");
		}
		
		$("#msQuestioner").click(call_msQuestioner);
		function call_msQuestioner() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_questioner_ctr");
		}
		
		$("#msBiayaFormulir").click(call_msBiayaFormulir);
		function call_msBiayaFormulir() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_biayaformulir_ctr");
		}
		
		//call_trxQuestioner();
		$("#trxQuestioner").click(call_trxQuestioner);
		function call_trxQuestioner() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_questioner_ctr");
		}
		
		//call_trxFormulir();
		$("#trxFormulir").click(call_trxFormulir);
		function call_trxFormulir() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_formulir_ctr");
		}
		
		//call_trxBiodata();
		$("#trxBiodata").click(call_trxBiodata);
		function call_trxBiodata() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_biodata_ctr");
		}
		
		//call_msPembayaran();
		$("#msPembayaran").click(call_msPembayaran);
		function call_msPembayaran() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_pembayaran_ctr");
		}
		
		
		$("#rptQuestioner").click(call_rptQuestioner);
		function call_rptQuestioner() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_questioner_ctr/tampil_data");
			back_to_top();
		}
		
		
		
		$("#rptFormulir").click(call_rptFormulir);
		function call_rptFormulir() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_formulir_ctr/tampil_data_formulir");
			back_to_top();
		}
		
		$("#rptBiodata").click(call_rptBiodata);
		function call_rptBiodata() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_biodata_ctr/tampil_data_biodata");
			back_to_top();
		}
		
		//call_setMapelUSM();
		$("#setMapelUSM").click(call_setMapelUSM);
		function call_setMapelUSM() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Set_mapelusm_ctr");
		}
		
		//call_trxUSM();
		$("#trxUSM").click(call_trxUSM);
		function call_trxUSM() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_usm_ctr");
			back_to_top();
		}
		
		
		//call_trxKeputusanRpt();
		$("#trxKeputusanRpt").click(call_trxKeputusanRpt);
		function call_trxKeputusanRpt() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_keputusan_ctr");
			back_to_top();
		}
		
		//call_rptPesertaUjian();
		$("#rptPesertaUjian").click(call_rptPesertaUjian);
		function call_rptPesertaUjian() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Rpt_pesertaujian_ctr");
			back_to_top();
		}
		
		
		//call_rptHasilUSM();
		$("#rptHasilUSM").click(call_rptHasilUSM);
		function call_rptHasilUSM() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Rpt_hasilusm_ctr");
			back_to_top();
		}
		
		
		//call_rptSuratPernyataan();
		$("#rptSuratPernyataan").click(call_rptSuratPernyataan);
		function call_rptSuratPernyataan() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Rpt_pernyataan_ctr");
			back_to_top();
		}
		
		
		//call_rptRekapJualForm();
		$("#rptRekapJualForm").click(call_rptRekapJualForm);
		function call_rptRekapJualForm() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Rpt_jualform_ctr");
			back_to_top();
		}
		
		
		//call_trxSiswaBatal();
		$("#trxSiswaBatal").click(call_trxSiswaBatal);
		function call_trxSiswaBatal() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_siswabatal_ctr");
			back_to_top();
		}
		
		$("#rptMhsBatalMasuk").click(call_rptMhsBatalMasuk);
		function call_rptMhsBatalMasuk() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_siswabatal_ctr/data_mhs_batal");
			back_to_top();
		}
		
		
		function back_to_top() {
			$('html,body').animate({
				scrollTop: 0
			}, 700);
		}
	});
</script>

<div class="collapse navbar-collapse" style='margin:0px;padding:0px;' id="menu_utama_elearning" >
	<div class="leftmenu_hiasan" id="leftmenu_top" style='text-align:left;padding-top:5px;padding-left:5px;' >
		<?php echo "School Year Active : " . $ta; ?>
	</div>
	
	<!-- ############################################ MASTER ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#ms_pmb">
			<h4 class="panel-title" >
				Master
			</h4>
		</div>
		<div id="ms_pmb" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="msTahunAjaran" >Tahun Ajaran</div>
			<div class="panel-body panel_setting_subjudul" id="msAktifasi"  >Aktifasi User</div>
			<div class="panel-body panel_setting_subjudul" id="msSekolah" >List Sekolah</div>
			<div class="panel-body panel_setting_subjudul" id="msFakultas" >Fakultas & Jurusan</div>
			<div class="panel-body panel_setting_subjudul" id="msQuestioner" >Pertanyaan Questioner</div>
			<div class="panel-body panel_setting_subjudul" id="msBiayaFormulir" >Biaya Formulir</div>
			<div class="panel-body panel_setting_subjudul" id="msPembayaran" >Master Pembayaran</div>
		</div>
	</div>
	
	<!-- ############################################ SETTING ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#set_pmb">
			<h4 class="panel-title" >
				Setting
			</h4>
		</div>
		<div id="set_pmb" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="setMapelUSM" title='Setting Mata Pelajaran Ujian Saringan Masuk' >Setting Mapel USM</div>
		</div>
	</div>
	
	
	<!-- ############################################ Trx ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#trx_pmb">
			<h4 class="panel-title" >
				Transaction
			</h4>
		</div>
		<div id="trx_pmb" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="trxQuestioner" >Questioner</div>
			<div class="panel-body panel_setting_subjudul" id="trxFormulir"  >Beli Formulir</div>
			<div class="panel-body panel_setting_subjudul" id="trxBiodata"  >Biodata Siswa</div>
			<div class="panel-body panel_setting_subjudul" id="trxUSM"  >Ujian Saringan Masuk</div>
			<div class="panel-body panel_setting_subjudul" id="trxKeputusanRpt"  >Keputusan Rapat</div>
			<div class="panel-body panel_setting_subjudul" id="trxSiswaBatal"  >Mhs Batal Masuk</div>
		</div>
	</div>
	
	
	<!-- ############################################ Report ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#rpt_pmb">
			<h4 class="panel-title" >
				Report
			</h4>
		</div>
		<div id="rpt_pmb" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="rptQuestioner" >Questioner</div>
			<div class="panel-body panel_setting_subjudul" id="rptFormulir"  >Pembelian Formulir</div>
			<div class="panel-body panel_setting_subjudul" id="rptRekapJualForm"  >Rekap Penjualan Formulir</div>
			<div class="panel-body panel_setting_subjudul" id="rptBiodata"  >Biodata Siswa</div>
			<div class="panel-body panel_setting_subjudul" id="rptPesertaUjian"  >Peserta Ujian</div>
			<div class="panel-body panel_setting_subjudul" id="rptHasilUSM"  >Hasil USM</div>
			<div class="panel-body panel_setting_subjudul" id="rptSuratPernyataan"  >Surat Pernyataan</div>
			<div class="panel-body panel_setting_subjudul" id="rptMhsBatalMasuk"  >Mhs Batal Masuk</div>			
		</div>
	</div>
	
	

</div>