<script>
	$(document).ready(function() {
		
		$("#btnRefresh").click(function() {
			$("#content_adm").load("Rpt_krs_ctr");
		});
		
		$("#txtTa").change(function() {
			//$("#txtTa").hide();
			$("#lblTa").text($("#txtTa").val());
			//$("#lblTa").show();
		});
		
		$("#txtSms").change(function() {
			//$("#txtSms").hide();
			$("#lblSms").text($("#txtSms").val());
			//$("#lblSms").show();
			$("#rowFakultas").show();
		});
		
		$("#txtFak").change(function() {
			//$("#txtFak").hide();
			$("#lblFak").text($("#txtFak option:selected").text());
			//$("#lblFak").show();
			
			var idfak = "idfak="+$("#txtFak").val();
			$.ajax({
				type: "POST",
				url: "Rpt_krs_ctr/jurusan_list/",
				data: idfak,
				success: function(data, status) {
					//alert(data);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						$("#txtJur").empty();
						$("#txtJur").append("<option value=''>== Pilih Jurusan ==</option>");
						for(var c=0; c < obj.length; c++) {
							$("#txtJur").append("<option alt='"+obj[c].id+"' value='"+obj[c].id+"'>"+obj[c].jurusan +"</option>");
						}
						
						$("#rowJurusan").show();
					}
					
				}
			});
			
		});
		
		
		$("#txtJur").change(function() {
			//$("#txtJur").hide();
			$("#lblJur").text($("#txtJur option:selected").text());
			//$("#lblJur").show();
			$("#rowBtn").show()
		});
		
		
		var tampilkrs="posting";
		$("#btnRptKRS").click(function() {
			tampilkrs="pdf";
			$("#frmRptKRS").submit();
			return false;
		});
		
		$("#btnWebKRS").click(function() {
			tampilkrs="web";
			$("#frmRptKRS").submit();
			return false;
		});
		
		$("#frmRptKRS").submit(function() {
			var dataform = $(this).serialize();
			
			if(tampilkrs=="pdf") {
				window.open("Rpt_krs_ctr/cetak_krs_pdf/?"+dataform,"_blank");
			} else {
				window.open("Rpt_krs_ctr/cetak_krs_web/?"+dataform,"_blank");
			}
			
			return false;
		});
		
	});
	
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>
	
<div class='header-popup' >
Report Kartu Rencana Studi (KRS) Peserta Didik ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<p style='margin-top:10px;margin-right:10px;' align='right' >
<button type="button" class="btn btn-default" aria-label="Left Align" id='btnRefresh'  >
  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Refresh
</button>
</p>

<div class="isi-popup" id="printableArea" >
	<div class="table-responsive" style='text-align:left;font-size:12px;' >
		
		<form action="" method="POST" name="frmRptKRS" id="frmRptKRS" >
			<table class="table table-hover borderless" id='tblRptKRS' >
				
				<tr>
					<td align='right' width='130px' >Tahun Ajaran :</td>
					<td>
						<select class='form-control input-sm' name='txtTa' id='txtTa' >
						<option value=''>== Pilih Tahun ==</option>
						<?php
						foreach($ta_pci_list->result() as $row) {
							echo "<option value='".$row->tahun."' >". $row->tahun . "</option>";
						}
						?>
						</select>
						<label id='lblTa' style='display:none;' ></label>
					</td>
				</tr>
				
				<tr>
					<td align='right' >Semester : </td>
					<td>
						<select class='form-control input-sm' name='txtSms' id='txtSms' >
						<option value=''>== Pilih Semester ==</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						</select>	
						<label id='lblSms' style='display:none;' ></label>
					</td>
				</tr>
				
				<tr id='rowFakultas' style='display:none;' >
					<td align='right' >Fakultas : </td>
					<td>
						<select class='form-control input-sm' name='txtFak' id='txtFak' >
						<option value=''>== Pilih Fakultas ==</option>
						<?php
						foreach($fakultas_list->result() as $fkl) {
							echo "<option value='".$fkl->id."' >".$fkl->fakultas."</option>";
						}
						?>
						</select>	
						<label id='lblFak' style='display:none;' ></label>
					</td>
				</tr>
				
				<tr id='rowJurusan' style='display:none;' >
					<td align='right' >Jurusan : </td>
					<td>
						<select class='form-control input-sm' name='txtJur' id='txtJur' >
						<option value=''>== Pilih Jurusan ==</option>
						</select>	
						<label id='lblJur' style='display:none;' ></label>
					</td>
				</tr>
				
				<tr id='rowBtn' style='display:none;' >
					<td align='right' ></td>
					<td>
						<button class='form-control btn btn-danger input-sm' name='btnRptKRS' id='btnRptKRS' style='height:70px;' >
						<span class="glyphicon glyphicon-book" aria-hidden="true"></span> Tampil KRS (PDF)
						</button>
						<br><br>
						<button class='form-control btn btn-warning input-sm' name='btnWebKRS' id='btnWebKRS' style='height:70px;' >
						<span class="glyphicon glyphicon-book" aria-hidden="true"></span> Tampil KRS (Web Version)
						</button>
					</td>
				</tr>
				
			</table>
		</form>
		
	</div>
	
</div>