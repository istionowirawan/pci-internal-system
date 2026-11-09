<script>
	$(document).ready(function() {
		
		
		$("#txtDosen").change(function() {
			var nikdosen = "nikdosen="+$(this).val();
			if(nikdosen=="") {
				$("#rowMakul").hide();
				$("#rowKelas").hide();
				$("#frmAbsensiDetail").hide();
			} else {
				$.ajax({
					type: "POST",
					url: "Trx_absensi_ctr/cari_makul_filter/",
					data: nikdosen,
					success: function(data, status) {
						
						var obj = jQuery.parseJSON( data );
						if(obj.length>0) {
							$("#txtMakul").empty();
							$("#txtMakul").append("<option value=''>== Pilih Mata Kuliah ==</option>");
							for(var c=0; c < obj.length; c++) {
								$("#txtMakul").append("<option alt='"+obj[c].kode_makul+"' value='"+obj[c].id_makul+"'>"+obj[c].nmkmk + " ("+obj[c].nmkmk_ch+")" +"</option>");
							}
							$("#rowMakul").show('500');
						}
					}
				});
				
				$("#rowKelas").hide();
				$("#frmAbsensiDetail").hide();
				return false;
			}
		});
		
		
		$("#txtMakul").change(function() {
			var idmakul = "idmakul="+$(this).val();
			idmakul += "&nikdosen="+$("#txtDosen").val();
			
			if(idmakul=="") {
				$("#rowKelas").hide();
				$("#frmAbsensiDetail").hide();
			} else {
				$.ajax({
					type: "POST",
					url: "Trx_absensi_ctr/cari_kelas_filter/",
					data: idmakul,
					success: function(data, status) {
						
						var obj = jQuery.parseJSON( data );
						if(obj.length>0) {
							$("#txtKelas").empty();
							$("#txtKelas").append("<option value=''>== Pilih Kelas ==</option>");
							for(var c=0; c < obj.length; c++) {
								$("#txtKelas").append("<option alt='"+obj[c].kode_kelas+"' value='"+obj[c].id_kelas+"'>"+obj[c].nmkls + " ("+obj[c].nmkls_ch+")" +"</option>");
							}
							$("#rowKelas").show('500');
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
				$("#frmAbsensiDetail").hide();
			} else {
				$("#cont_data_mahasiswa").load("Trx_absensi_ctr/cari_data_siswa/"+nikdosen+"/"+idmakul+"/"+idkelas, function() {
					$("#frmAbsensiDetail").show('500');
					
					$("#frmAbsensiDetail").submit(function() {
						var dataform = $(this).serialize();
						dataform += "&txtTa="+$("#txtTahunAjaran").val();
						dataform += "&txtSms="+$("#txtSemester").val();
						dataform += "&txtDosen="+$("#txtDosen").val();
						dataform += "&txtMakul="+$("#txtMakul").val();
						dataform += "&txtKelas="+$("#txtKelas").val();
						
						$.ajax({
							type: "POST",
							url: "Trx_absensi_ctr/simpan_absensi/",
							data: dataform,
							success: function(data, status) {
								//alert(data);
								if(data==1) {
									alert("Simpan Makul Mahasiswa Sukses!")
								} else {
									alert("Simpan Makul Mahasiswa Gagal!")
								}
								$("#content_adm").load("Trx_absensi_ctr");
							}
						});
						
						return false;
					});
					
				});
			}
			
		});
		
	});
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>
	
<div class='header-popup' >
Input Absensi Peserta Didik ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		
		<form action="" method="POST" name="frmTrxAbsensi" id="frmTrxAbsensi" >
			<table class="table table-hover" id='tblKelas' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Tahun Ajaran : </td>
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
						<select class='form-control' name='txtMakul' id='txtMakul' required >
						</select>
					</td>
				</tr>
				
				
				<tr id='rowKelas' style='display:none;' >
					<td align='right' >Kelas : </td>
					<td>
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