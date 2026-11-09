<script>
	$(document).ready(function(){
		function tandai_menu_dipilih(menuPilih) {
			$( ".panel_setting_subjudul" ).removeClass( "mn_active" );
			menuPilih.addClass("mn_active");
		}
		
		$("#msTahunAjaran").click(call_msTahunAjaran);
		function call_msTahunAjaran() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Ms_tahunajaranPCI_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		
		$("#msKebangsaan").click(call_msKebangsaan);
		function call_msKebangsaan() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_kebangsaan_ctr");
		}
		
		$("#msMahasiswa").click(call_msMahasiswa);
		function call_msMahasiswa() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Trx_biodata_ctr/tampil_data_biodata",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		
		$("#msMatakul").click(call_msMatakul);
		function call_msMatakul() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Ms_matakul_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#msDosen").click(call_msDosen);
		function call_msDosen() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Ms_dosen_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#msKelas").click(call_msKelas);
		function call_msKelas() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Ms_kelas_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		
		$("#msGrade").click(call_msGrade);
		function call_msGrade() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Ms_grade_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		
		$("#msSettingMakulKelas").click(call_msSettingMakulKelas);
		function call_msSettingMakulKelas() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Set_makul_kelas_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#msSettingMakulMhs").click(call_msSettingMakulMhs);
		function call_msSettingMakulMhs() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Set_makul_mhs_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		
		$("#msSettingPersen").click(call_msSettingPersen);
		function call_msSettingPersen() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Set_persentase_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#trxAbsensi").click(call_trxAbsensi);
		function call_trxAbsensi() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Trx_absensi_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		
		$("#trxNilaiMhs").click(call_trxNilaiMhs);
		function call_trxNilaiMhs() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Trx_nilaimhs_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#rptNilai").click(call_rptNilaiMhs);
		function call_rptNilaiMhs() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Rpt_nilaimhs_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#rptKRS").click(call_rptKRS);
		function call_rptKRS() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Rpt_krs_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#rptKHS").click(call_rptKHS);
		function call_rptKHS() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Rpt_khs_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		
		$("#rptTranscipt").click(call_rptTranscipt);
		function call_rptTranscipt() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Rpt_transcipt_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#msNim").click(call_msNim);
		function call_msNim() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Ms_nim_ctr",function() {
				$("#content_adm").slideDown(500);
			});
			back_to_top();
		}
		
		$("#msNamaMand").click(call_msNamaMand);
		function call_msNamaMand() {
			tandai_menu_dipilih($(this));
			$("#content_adm").hide();
			$("#content_adm").load("Ms_namamand_ctr",function() {
				$("#content_adm").slideDown(500);
			});
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
		<?php 
		echo "School Year Active : " . $ta->tahun; 
		?>
	</div>
	
	<!-- ############################################ MASTER ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#ms_pci">
			<h4 class="panel-title" >
				Master
			</h4>
		</div>
		<div id="ms_pci" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="msTahunAjaran" >Tahun Ajaran Active</div>
			<!--<div class="panel-body panel_setting_subjudul" id="msKebangsaan" >Kebangsaan</div>-->
			<div class="panel-body panel_setting_subjudul" id="msNim" >Input NIP</div>
			<div class="panel-body panel_setting_subjudul" id="msMahasiswa" >Peserta didik</div>
			<div class="panel-body panel_setting_subjudul" id="msNamaMand" >Nama Tionghoa & no.telp</div>
			<div class="panel-body panel_setting_subjudul" id="msGrade" >Grade</div>
			<div class="panel-body panel_setting_subjudul" id="msMatakul" >Mata Pelajaran</div>
			<div class="panel-body panel_setting_subjudul" id="msDosen" >Pengajar</div>
			<div class="panel-body panel_setting_subjudul" id="msKelas" >Kelas</div>
			
		</div>
	</div>
	
	
	
	
	<!-- ############################################ SETTING ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#set_pci">
			<h4 class="panel-title" >
				Setting
			</h4>
		</div>
		<div id="set_pci" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="msSettingMakulKelas" >Setting Mapel per Kelas</div>
			<div class="panel-body panel_setting_subjudul" id="msSettingMakulMhs" >Kartu Rencana Studi (KRS)</div>
			<div class="panel-body panel_setting_subjudul" id="msSettingPersen" >Setting Persentase Absensi & Bobot Ujian</div>
		</div>
	</div>
	
	
	
	<!-- ############################################ Trx ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#trx_pci">
			<h4 class="panel-title" >
				Transaction
			</h4>
		</div>
		<div id="trx_pci" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="trxAbsensi" >Absensi</div>
			<div class="panel-body panel_setting_subjudul" id="trxNilaiMhs"  >Input Nilai</div>
		</div>
	</div>
	
	<!-- ############################################ Laporan ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#rpt_pci">
			<h4 class="panel-title" >
				Report
			</h4>
		</div>
		<div id="rpt_pci" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="rptNilai"  >Nilai</div>
			<div class="panel-body panel_setting_subjudul" id="rptKRS"  >KRS</div>
			<div class="panel-body panel_setting_subjudul" id="rptKHS"  >KHS</div>
			<div class="panel-body panel_setting_subjudul" id="rptTranscipt"  >Transcipt</div>
		</div>
	</div>
	
</div>