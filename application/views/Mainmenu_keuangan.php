<script>
	$(document).ready(function(){
		function tandai_menu_dipilih(menuPilih) {
			$( ".panel_setting_subjudul" ).removeClass( "mn_active" );
			menuPilih.addClass("mn_active");
		}
		
		$("#msTahunAjaran").click(call_msTahunAjaran);
		function call_msTahunAjaran() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Ms_tahunajaran_ctr/show_ta_keu/");
			back_to_top();
		}
		
		$("#trxPembayaran").click(call_trxPembayaran);
		function call_trxPembayaran() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Trx_pembayaran_ctr");
			back_to_top();
		}
		
		$("#RptPembayaran").click(call_rptPembayaran);
		function call_rptPembayaran() {
			tandai_menu_dipilih($(this));
			$("#content_adm").load("Rpt_pembayaran_ctr");
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
		<?php echo "School Year Active : " . $ta->tahun; ?>
	</div>
	
	<!-- ############################################ MASTER ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#ms_keuangan">
			<h4 class="panel-title" >
				Master
			</h4>
		</div>
		<div id="ms_keuangan" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="msTahunAjaran" >Tahun Ajaran</div>
		</div>
	</div>
	
	
	
	<!-- ############################################ Trx ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#trx_keuangan">
			<h4 class="panel-title" >
				Transaction
			</h4>
		</div>
		<div id="trx_keuangan" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="trxPembayaran" >Pembayaran</div>
		</div>
	</div>
	
	<!-- ############################################ Laporan ############################################ -->
	<div class="panel panel-default">
		<div class="panel-heading panel_setting_judul" data-toggle="collapse" data-parent="#accordion" href="#rpt_keuangan">
			<h4 class="panel-title" >
				Report
			</h4>
		</div>
		<div id="rpt_keuangan" class="panel-collapse collapse in">
			<div class="panel-body panel_setting_subjudul" id="RptPembayaran" >Pembayaran</div>
		</div>
	</div>
	
</div>