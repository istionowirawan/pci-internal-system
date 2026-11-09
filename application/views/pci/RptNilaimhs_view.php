<script>
	$(document).ready(function() {
		$("#lblDosen").hide();
		$("#lblKelas").hide();
		$("#lblMakul").hide();
		
		$("#txtDosen").change(function() {
			var nikdosen = "nikdosen="+$(this).val();
			if(nikdosen=="") {
				$("#rowMakul").hide();
				$("#rowKelas").hide();
				$("#frmTrxNilai").hide();
			} else {
				$("#lblDosen").text($(this).val() + " - " + $("#txtDosen option:selected").text());
				$("#lblDosen").show();
				$("#txtDosen").hide();
				
				$.ajax({
					type: "POST",
					url: "Rpt_nilaimhs_ctr/cari_makul_filter/",
					data: nikdosen,
					success: function(data, status) {
						
						var obj = jQuery.parseJSON( data );
						if(obj.length>0) {
							$("#txtMakul").empty();
							$("#txtMakul").append("<option value=''>== Pilih Mata Kuliah ==</option>");
							for(var c=0; c < obj.length; c++) {
								$("#txtMakul").append("<option alt='"+obj[c].kode_makul+"' value='"+obj[c].id_makul+"'>"+obj[c].nmkmk + " ("+obj[c].nmkmk_ch+")" + "  " + obj[c].kode_kelas +"</option>");
							}
							
							$("#rowMakul").show('700');
						}
					}
				});
				
				$("#rowKelas").hide();
				$("#frmTrxNilaiDetail").hide();
				return false;
			}
		});
		
		
		$("#txtMakul").change(function() {
			var idmakul = "idmakul="+$(this).val();
			idmakul += "&nikdosen="+$("#txtDosen").val();
			
			if(idmakul=="") {
				$("#rowKelas").hide();
				$("#frmTrxNilaiDetail").hide();
			} else {
				$("#lblMakul").text("("+$("#txtMakul option:selected").attr("alt") + ") " + $("#txtMakul option:selected").text());
				$("#lblMakul").show();
				$("#txtMakul").hide();
				
				$.ajax({
					type: "POST",
					url: "Rpt_nilaimhs_ctr/cari_kelas_filter/",
					data: idmakul,
					success: function(data, status) {
						
						var obj = jQuery.parseJSON( data );
						if(obj.length>0) {
							//alert(data);
							$("#txtKelas").empty();
							$("#txtKelas").append("<option value=''>== Pilih Kelas ==</option>");
							for(var c=0; c < obj.length; c++) {
								$("#txtKelas").append("<option alt='"+obj[c].kode_kelas+"' value='"+obj[c].id_kelas+"'>"+obj[c].nmkls + " ("+obj[c].nmkls_ch+")" +"</option>");
							}
							$("#rowKelas").show('500');
							
							//alert(obj[0].fakultas);
							$("#rowFakultas").show('300');
							$("#lblFakultas").text(obj[0].fakultas);
							$("#rowJurusan").show('500');
							$("#lblJurusan").text(obj[0].jurusan);
							
						}
					}
				});
			}
		});
		
		
		$("#txtKelas").change(function() {
			
			var idkelas = $(this).val();
			var idmakul = $("#txtMakul").val();
			var nikdosen = $("#txtDosen").val();
			
			if(idkelas=="") {
				$("#frmTrxNilaiDetail").hide();
			} else {
				$("#lblKelas").text("(" + $(this).val() + ") " + $("#txtKelas option:selected").text());
				$("#lblKelas").show();
				$("#txtKelas").hide();
				
				$("#cont_data_mahasiswa").load("Rpt_nilaimhs_ctr/cari_data_siswa/"+nikdosen+"/"+idmakul+"/"+idkelas, function() {
					$("#frmTrxNilaiDetail").show('500');
				});
			}	
		});
		
		
	});
	
	function printDiv(divName) {
		 var printContents = document.getElementById(divName).innerHTML;
		 var originalContents = document.body.innerHTML;

		 document.body.innerHTML = printContents;

		 window.print();

		 document.body.innerHTML = originalContents;
	}
	
	function refresh_page() {
		$( ".panel_setting_subjudul" ).removeClass( "mn_active" );
		$("#rptNilai").addClass("mn_active");
		
		$("#content_adm").hide();
		$("#content_adm").load("Rpt_nilaimhs_ctr",function() {
			$("#content_adm").slideDown(500);
		});
	}
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>
	
<div class='header-popup' >
Report Nilai Peserta Didik ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<p style='margin-top:10px;margin-right:10px;' align='right' >
<button type="button" class="btn btn-default" aria-label="Left Align" onclick="refresh_page()" >
  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Refresh
</button>
<button type="button" class="btn btn-default" aria-label="Left Align" onclick="printDiv('printableArea')" >
  <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
</button>
</p>

<div class="isi-popup" id="printableArea" >
	<div class="table-responsive" style='text-align:left;font-size:12px;' >
		
		<form action="" method="POST" name="frmTrxNilai" id="frmTrxNilai" >
			<table class="table table-hover borderless" id='tblNilaiMhs' >
				
				<tr>
					<td align='right' width='130px' >Tahun Ajaran :</td>
					<td>
						<?php echo $ta_pci_active->tahun; ?>
						<input type='hidden' class='form-control' name='txtTahunAjaran' id='txtTahunAjaran' value='<?php echo $ta_pci_active->tahun; ?>' required >
					</td>
				</tr>
				
				<tr>
					<td align='right' >Semester : </td>
					<td>
						<?php echo ($ta_pci_active->semester%2==0?"Genap":"Ganjil"); ?>
						<input type='hidden' class='form-control' name='txtSemester' id='txtSemester' value='<?php echo $ta_pci_active->semester; ?>' required >
					</td>
				</tr>
				
				<tr>
					<td align='right' >Pengajar : </td>
					<td>
						<label id='lblDosen' style='font-weight:normal;' ></label>
						<select class='form-control' name='txtDosen' id='txtDosen' required >
						<?php
						echo "<option value='' alt='' >== Pilih Pengajar ==</option>";
						foreach($dosen_ajar->result() as $row) {
							echo "<option value='".$row->nik_dosen."' alt='".$row->id_dosen."' >".$row->nama;
							if($row->nama_ch!="") {
								echo " ( ".$row->nama_ch." )";
							}
							echo "</option>";
						}
						?>
						</select>
					</td>
				</tr>
				
				
				<tr id='rowMakul' style='display:none;' >
					<td align='right' >Mata Pelajaran : </td>
					<td>
						<label id='lblMakul' style='font-weight:normal;' ></label>
						<select class='form-control' name='txtMakul' id='txtMakul' required >
						</select>
					</td>
				</tr>
				
				
				<tr id='rowFakultas' style='display:none;' >
					<td align='right' >Fakultas : </td>
					<td>
						<label id='lblFakultas' style='font-weight:normal;' ></label>
					</td>
				</tr>
				
				<tr id='rowJurusan' style='display:none;' >
					<td align='right' >Jurusan : </td>
					<td>
						<label id='lblJurusan' style='font-weight:normal;' ></label>
					</td>
				</tr>
				
				
				<tr id='rowKelas' style='display:none;' >
					<td align='right' >Kelas : </td>
					<td>
						<label id='lblKelas' style='font-weight:normal;' ></label>
						<select class='form-control' name='txtKelas' id='txtKelas' required >
						</select>
					</td>
				</tr>
				
				
			</table>
		</form>
		
		<div class="table-responsive" id='cont_data_mahasiswa' >
			
		</div>
		
	</div>
	
</div>