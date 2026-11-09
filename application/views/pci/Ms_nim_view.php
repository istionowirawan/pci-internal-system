<script>
	$(document).ready(function() {
		$("#txtFak").change(function() {
			$("#lblFak").text($("#txtFak option:selected").text());
			$("#lblFak").show();
			$("#txtFak").hide();
			
			var idfak = "idfak="+$("#txtFak").val();
			$.ajax({
				type: "POST",
				url: "Ms_nim_ctr/jurusan_list/",
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
			$("#lblJur").show();
			$("#txtJur").hide();
			
			$("#data_siswa_detail").load("Ms_nim_ctr/tampil_siswa_nim/"+$("#txtFak").val()+"/"+$("#txtJur").val()+"/"+$("#txtTa").val(), function() {
				$("#btnSimpan").click(function() {
					
					var r = confirm("Simpan Nim yang telah diisi?\n\rPerhatian : Nim yang telah disimpan tidak dapat di ubah kembali");
					if (r == true) {
					
						$("#frmNim").submit(function() {
							var dataform = $(this).serialize();
							//alert(dataform);
							
							$.ajax({
								type: "POST",
								url: "Ms_nim_ctr/simpan_nim/",
								data: dataform,
								success: function(data, status) {
									//alert(data);
									if(data==1) {
										alert("Simpan Nim Sukses!")
									} else {
										alert("Simpan Nim Gagal!")
									}
									$("#content_adm").load("Ms_nim_ctr");
								}
							});
							
							return false;
						});
						
					} else {
						return false;
					}
					
				});
				
			});
			
			
		});
		
				
		
		
		
	});
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Master Nomor Induk Mahasiswa ( <?php echo $ta_pci_active; ?> )
</div>

<div class="isi-popup" >
	<div class="table-responsive">
		<form action="" method="POST" name="frmNim" id="frmNim" >
			
			<table class="table borderless"  >
			
				<tr>
					<td align='right' width='120px' >Tahun Ajaran : </td>
					<td align='left' ><?php echo $ta_pci_active; ?><input type='hidden' name='txtTa' id='txtTa' value='<?php echo $ta_pci_active; ?>' ></td>
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
			
			<div id='data_siswa_detail' ></div>
		
		</form>
	</div>
	
</div>