<script>
	$(document).ready(function() {
		
		$("#txtFak").change(function() {
			$("#lblFak").text($("#txtFak option:selected").text());
			$("#lblFak").show();
			$("#txtFak").hide();
			
			var idfak = "idfak="+$("#txtFak").val();
			$.ajax({
				type: "POST",
				url: "Rpt_jualform_ctr/jurusan_list/",
				data: idfak,
				success: function(data, status) {
					//alert(data);
					if(data=="semua") {
						$("#txtJur").empty();
						$("#txtJur").append("<option value=''>== Pilih Jurusan ==</option>");
						$("#txtJur").append("<option alt='semua' value='semua'>semua</option>");
						$("#rowJurusan").show();
					} else {
						var obj = jQuery.parseJSON( data );
						if(obj.length>0) {
							$("#txtJur").empty();
							$("#txtJur").append("<option value=''>== Pilih Jurusan ==</option>");
							$("#txtJur").append("<option alt='semua' value='semua'>semua</option>");
							for(var c=0; c < obj.length; c++) {
								$("#txtJur").append("<option alt='"+obj[c].id+"' value='"+obj[c].id+"'>"+obj[c].jurusan +"</option>");
							}
							
							$("#rowJurusan").show();
						}
					}
				}
			});
			
		});
		
		$("#txtJur").change(function() {
			$("#lblJur").text($("#txtJur option:selected").text());
			$("#lblJur").show();
			$("#txtJur").hide();
			$("#rowBtn").show();
		});
		
		$("#btnRekap").click(function() {
			$("#div_detail").load("Rpt_jualform_ctr/tampil_rekap_jualform/"+$("#txtFak").val()+"/"+$("#txtJur").val()+"/"+$("#txtTa").val()+"/rekap", function() {
				
			});
		});
		
		$("#btnDetail").click(function() {
			$("#div_detail").load("Rpt_jualform_ctr/tampil_rekap_jualform/"+$("#txtFak").val()+"/"+$("#txtJur").val()+"/"+$("#txtTa").val()+"/detail", function() {
				
			});
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
		$("#rptRekapJualForm").addClass("mn_active");
		
		$("#content_adm").hide();
		$("#content_adm").load("Rpt_jualform_ctr",function() {
			$("#content_adm").slideDown(500);
		});
	}
</script>

<div class='header-popup' >
Report Rekap Penjulan Formulir
</div>

<p style='margin-top:10px;margin-right:10px;' align='right' >
<button type="button" class="btn btn-default" aria-label="Left Align" onclick="refresh_page()" >
  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Refresh
</button>
<button type="button" class="btn btn-default" aria-label="Left Align" onclick="printDiv('div_detail')" >
  <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
</button>
</p>

<div class="isi-popup" >
	<form action="" method="POST" name="frmKeputusanRapat" id="frmKeputusanRapat" >

		<table class="table borderless"  >
			
			<tr>
				<td align='right' width='120px' >Tahun Ajaran : </td>
				<td align='left' ><?php echo $ta_active; ?><input type='hidden' name='txtTa' id='txtTa' value='<?php echo $ta_active; ?>' ></td>
			</tr>
			
			<tr id='rowFakultas' >
				<td align='right' >Fakultas : </td>
				<td align='left' >
					<select class='form-control input-sm' name='txtFak' id='txtFak' >
					<option value=''>== Pilih Fakultas ==</option>
					<option value='semua' >semua</option>
					<?php
					foreach($fakultas_list->result() as $fkl) {
						echo "<option value='".$fkl->id."' >".$fkl->fakultas."</option>";
					}
					?>
					</select>	
					<label id='lblFak' style='display:none;text-align:left;' ></label>
				</td>
			</tr>
			
			<tr id='rowJurusan' style='display:none;' >
				<td align='right' >Jurusan : </td>
				<td align='left' >
					<select class='form-control input-sm' name='txtJur' id='txtJur' >
					<option value=''>== Pilih Jurusan ==</option>
					</select>	
					<label id='lblJur' style='display:none;' ></label>
				</td>
			</tr>
			
			<tr id='rowBtn' style='display:none;' >
				<td align='right' ></td>
				<td align='left' >
					<button type="button" id='btnRekap' class="btn btn-primary btn-sm">Rekap Penjualan Formulir</button>
					<button type="button" id='btnDetail' class="btn btn-primary btn-sm">Detail Penjualan Formulir</button>
				</td>
			</tr>
			
		</table>
	
		<div class="table-responsive" id='div_detail' ></div>
	
	</form>
</div>
