<script>
	$(document).ready(function() {
		
		$("#txtFak").change(function() {
			$("#lblFak").text($("#txtFak option:selected").text());
			
			var idfak = "idfak="+$("#txtFak").val();
			$.ajax({
				type: "POST",
				url: "Set_mapelusm_ctr/jurusan_list/",
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
			
			$("#data_siswa_usm").load("Trx_keputusan_ctr/tampil_siswa_usm/"+$("#txtFak").val()+"/"+$("#txtJur").val()+"/"+$("#txtTa").val(), function() {
				
				$(".txtRapat").change(function() {
					var noform=$(this).attr("title");
					
					var datasimpan = "noform="+noform;
					datasimpan += "&hasil="+$(this).val();
					datasimpan += "&idfak="+$("#txtFak").val();
					datasimpan += "&idjur="+$("#txtJur").val();
					datasimpan += "&ta="+$("#txtTa").val();
					
					$.ajax({
						type: "POST",
						url: "Trx_keputusan_ctr/simpan_keputusan/",
						data: datasimpan,
						success: function(data, status) {
							//alert(data);
							if(data==1) {
								$("#ketHasilRpt_"+noform).fadeIn(function() {
									$(this).delay(1000).fadeOut(1000);
								});
							} else {
								alert("Error Simpan " + data);
							}
							
						}
					});
					
					
				});
				
				
				$(".txtRank").change(function() {
					var noform=$(this).attr("title");
					
					var datasimpan = "noform="+noform;
					datasimpan += "&hasil="+$(this).val();
					datasimpan += "&idfak="+$("#txtFak").val();
					datasimpan += "&idjur="+$("#txtJur").val();
					datasimpan += "&ta="+$("#txtTa").val();
					
					$.ajax({
						type: "POST",
						url: "Trx_keputusan_ctr/simpan_rank/",
						data: datasimpan,
						success: function(data, status) {
							//alert(data);
							if(data==1) {
								$("#ketPeringkat_"+noform).fadeIn(function() {
									$(this).delay(1000).fadeOut(1000);
								});
							} else {
								alert("Error Simpan " + data);
							}
							
						}
					});
					
				});
				
			});
			
			
		});
		
			
		
		
	});
</script>

<div class='header-popup' >
INPUT KEPUTUSAN RAPAT
</div>


<div class="isi-popup" >
	<form action="" method="POST" name="frmKeputusanRapat" id="frmKeputusanRapat" >

		<table class="table borderless"  >
			
			<tr>
				<td align='right' width='120px' >Tahun Ajaran : </td>
				<td align='left' ><?php echo $ta_active; ?><input type='hidden' name='txtTa' id='txtTa' value='<?php echo $ta_active; ?>' ></td>
			</tr>
			
			<tr id='rowFakultas' >
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
			
			
		</table>
	
		<div class="table-responsive" id='data_siswa_usm' ></div>
	
	</form>
</div>
