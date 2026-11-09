<script>
	$(document).ready(function() {
		
		$("#txtFak").change(function() {
			$("#lblFak").text($("#txtFak option:selected").text());
			//$("#lblFak").show();
			//$("#txtFak").hide();
			
			var idfak = "idfak="+$("#txtFak").val();
			$.ajax({
				type: "POST",
				url: "Trx_pembayaran_ctr/jurusan_list/",
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
			
			$("#data_siswa_usm").load("Trx_pembayaran_ctr/tampil_siswa_usm/"+$("#txtFak").val()+"/"+$("#txtJur").val()+"/"+$("#txtTa").val(), function() {
				$("#btnSimpanByr").click(function() {
					$("#frmPembayaran").submit();
				});
			});			
		});
		
		$("#frmPembayaran").submit(function() {
			var dataform = $(this).serialize();
			
			$.ajax({
				type: "POST",
				url: "Trx_pembayaran_ctr/simpan_pembayaran/",
				data: dataform,
				success: function(data, status) {
					//alert(data);
					if(data==1) {
						alert("Simpan Pembayaran Sukses!");
					} else {
						alert("Simpan Pembayaran Gagal! \n" + data);
					}
					$("#content_adm").load("Trx_pembayaran_ctr");
					
				}
			});
			
			return false;
		});
		
	});
	
</script>

<div class='header-popup' >
Pembayaran Keuangan Mahasiswa Baru
</div>

<div class="isi-popup" id="printableArea" >
	<form action="" method="POST" name="frmPembayaran" id="frmPembayaran" >

		<table class="table borderless"  >
			
			<tr>
				<td align='right' width='120px' >Tahun Ajaran : </td>
				<td align='left' ><?php echo $ta_keu_active; ?><input type='hidden' name='txtTa' id='txtTa' value='<?php echo $ta_keu_active; ?>' ></td>
			</tr>
			
			<tr id='rowFakultas' >
				<td align='right' >Fakultas : </td>
				<td align='left' >
					<select class='form-control input-sm' name='txtFak' id='txtFak' >
					<option value=''>== Pilih Fakultas ==</option>
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
			
		</table>
	
		<div class="table-responsive" id='data_siswa_usm' ></div>
	
	</form>
</div>
