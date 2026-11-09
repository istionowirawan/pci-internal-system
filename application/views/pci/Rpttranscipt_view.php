<script>
	$(document).ready(function() {
		
		$("#btnRefresh").click(function() {
			$("#content_adm").load("Rpt_transcipt_ctr");
		});
		
		$("#txtTa").change(function() {
			$("#lblTa").text($("#txtTa").val());
			$("#rowFakultas").show();
		});
		
		
		$("#txtFak").change(function() {
			$("#lblFak").text($("#txtFak option:selected").text());
			
			var idfak = "idfak="+$("#txtFak").val();
			$.ajax({
				type: "POST",
				url: "Rpt_transcipt_ctr/jurusan_list/",
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
			$("#lblJur").text($("#txtJur option:selected").text());
			$("#rowMhs").show();
		});
		
		$("#txtMhs").change(function() {
			$("#lblMhs").text($("#txtMhs option:selected").text());
			$("#rowBtn").show();
			
			if($("#txtMhs").val()=="pilih") {
				$("#rowListMhs").show(500);
				var dataform = "txtTa="+$("#txtTa").val();
				dataform += "&txtFak="+$("#txtFak").val();
				dataform += "&txtJur="+$("#txtJur").val();
				
				$.ajax({
					type: "POST",
					url: "Rpt_transcipt_ctr/list_mahasiswa/",
					data: dataform,
					success: function(data, status) {
						//alert(data);
						
						var obj = jQuery.parseJSON( data );
						if(obj.length>0) {
							var listMHS="";
							listMHS += "<table class='table table-bordered table-hover' border='1' >";
							for(var c=0; c < obj.length; c++) {
								if(c%3==0) {
									listMHS += "<tr>";
								}
								listMHS += "<td><input name='txtListMhs[]' type='checkbox' value='"+obj[c].nim+"' > ("+obj[c].nim+") "+obj[c].nama_depan+"</td>";
								if(c%3==2) {
									listMHS += "</tr>";
								}
							}
							listMHS += "</table>";
							$("#tdListMhs").html(listMHS);							
						}
					}
				});
				
				
			} else {
				$("#rowListMhs").hide(500);
			}
		});
		
		$("#btnRptTranscipt").click(function() {
			$("#frmRptTranscipt").submit();
			return false;
		});
		
		$("#frmRptTranscipt").submit(function() {
			var dataform = $(this).serialize();
			window.open("Rpt_transcipt_ctr/cetak_transcipt_pdf/?"+dataform,"_blank");
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
Report Transcipt Peserta Didik ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<p style='margin-top:10px;margin-right:10px;' align='right' >
<button type="button" class="btn btn-default" aria-label="Left Align" id='btnRefresh'  >
  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Refresh
</button>
</p>

<div class="isi-popup" id="printableArea" >
	<div class="table-responsive" style='text-align:left;font-size:12px;' >
		
		<form action="" method="POST" name="frmRptTranscipt" id="frmRptTranscipt" >
			<table class="table table-hover borderless" id='tblRptKHS' >
				
				<tr>
					<td align='right' width='130px' >Tahun Ajaran Masuk :</td>
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
				
				<tr id='rowMhs' style='display:none;' >
					<td align='right' >Pilih Peserta Didik : </td>
					<td>
						<select class='form-control input-sm' name='txtMhs' id='txtMhs' >
						<option value=''>== Pilih Peserta Didik ==</option>
						<option value='semua'>Semua Peserta Didik dengan kriteria diatas</option>
						<option value='pilih'>Pilih Peserta Didik tertentu</option>
						</select>	
						<label id='lblMhs' style='display:none;' ></label>
					</td>
				</tr>
				
				
				<tr id='rowListMhs' style='display:none;' >
					<td align='right' >List Peserta Didik : </td>
					<td id='tdListMhs' ></td>
				</tr>
				
				<tr id='rowBtn' style='display:none;' >
					<td align='right' ></td>
					<td>
						<button class='form-control btn btn-danger input-sm' name='btnRptTranscipt' id='btnRptTranscipt' >
						<span class="glyphicon glyphicon-align-left" aria-hidden="true"></span> Tampil PDF Transcipt
						</button>
					</td>
				</tr>
				
			</table>
		</form>
		
	</div>
	
</div>