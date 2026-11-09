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
					url: "Trx_nilaimhs_ctr/cari_makul_filter/",
					data: nikdosen,
					success: function(data, status) {
						
						var obj = jQuery.parseJSON( data );
						if(obj.length>0) {
							$("#txtMakul").empty();
							$("#txtMakul").append("<option value=''>== Pilih Mata Kuliah ==</option>");
							for(var c=0; c < obj.length; c++) {
								$("#txtMakul").append("<option alt='"+obj[c].kode_makul+"' value='"+obj[c].id_makul+"'>"+ obj[c].kode_makul + " - "+obj[c].nmkmk + " ("+obj[c].nmkmk_ch+")" +"</option>");
							}
							$("#rowMakul").show('500');
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
					url: "Trx_nilaimhs_ctr/cari_kelas_filter/",
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
							
							$("#rowFakultas").show('300');
							$("#lblFakultas").text(obj[0].fakultas);
							$("#rowJurusan").show(500);
							$("#lblJurusan").text(obj[0].jurusan);
							$("#rowSKS").show('300');
							$("#lblSKS").text(obj[0].sks);
							
							$("#txtSKS").val(obj[0].sks);
							$("#txtFakultas").val(obj[0].id_fakultas);
							$("#txtJurusan").val(obj[0].id_jurusan);
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
				
				$("#cont_data_mahasiswa").load("Trx_nilaimhs_ctr/cari_data_siswa/"+nikdosen+"/"+idmakul+"/"+idkelas, function() {
					$("#frmTrxNilaiDetail").show('500');
					
					var statusSimpan="simpan";
					$("#btnPosting").click(function() {
						statusSimpan="posting";
						$("#frmTrxNilaiDetail").submit();
					});
					
					$("#btnSimpan").click(function() {
						statusSimpan="simpan";
						$("#frmTrxNilaiDetail").submit();
					});
					
					$("#frmTrxNilaiDetail").submit(function() {
						//alert($(this).serialize());
						
						if(statusSimpan=="posting") {
							var r = confirm("Posting nilai?\nSetelah Nilai di posting, maka nilai tidak dapat di rubah kembali");
							if (r == true) {
								var dataposting = $(this).serialize();
								dataposting += "&txtTa="+$("#txtTahunAjaran").val();
								dataposting += "&txtSms="+$("#txtSemester").val();
								dataposting += "&txtDosen="+$("#txtDosen").val();
								dataposting += "&txtFakultas="+$("#txtFakultas").val();
								dataposting += "&txtJurusan="+$("#txtJurusan").val();
								dataposting += "&txtMakul="+$("#txtMakul").val();
								dataposting += "&txtKelas="+$("#txtKelas").val();
								dataposting += "&txtSKS="+$("#txtSKS").val();
								
								$.ajax({
									type: "POST",
									url: "Trx_nilaimhs_ctr/posting_nilai/",
									data: dataposting,
									success: function(data, status) {
										//alert(data);
										if(data==1) {
											alert("Posting Nilai Mahasiswa Sukses!")
										} else {
											alert("Posting Nilai Mahasiswa Gagal!")
										}
										$("#content_adm").load("Trx_nilaimhs_ctr");
										
									}
								});
							}
						} else {
							//alert($(this).serialize());
							var dataform = $(this).serialize();
							dataform += "&txtTa="+$("#txtTahunAjaran").val();
							dataform += "&txtSms="+$("#txtSemester").val();
							dataform += "&txtDosen="+$("#txtDosen").val();
							dataform += "&txtFakultas="+$("#txtFakultas").val();
							dataform += "&txtJurusan="+$("#txtJurusan").val();
							dataform += "&txtMakul="+$("#txtMakul").val();
							dataform += "&txtKelas="+$("#txtKelas").val();
							
							
							$.ajax({
								type: "POST",
								url: "Trx_nilaimhs_ctr/simpan_nilai/",
								data: dataform,
								success: function(data, status) {
									//alert(data);
									if(data==1) {
										alert("Simpan Nilai Mahasiswa Sukses!")
									} else {
										alert("Simpan Nilai Mahasiswa Gagal!")
									}
									$("#content_adm").load("Trx_nilaimhs_ctr");
									
								}
							});
						}
						return false;
					});
					
					
					//Jika mau Undo Posting
					$("#btnUndoPosting").click(function() {
						var a = confirm("Are you sure to undo this POSTING?\nWARNING : ALL UNDO POSTING WILL BE RECORDED");
						if(a==true) {
							var idnilai = "idnilai=" + $(this).attr("alt");
							$.ajax({
								type: "POST",
								url: "Trx_nilaimhs_ctr/undo_posting/",
								data: idnilai,
								success: function(data, status) {
									alert(data);
									if(data>=1) {
										alert("Undo Posting Berhasil!");
									} else {
										alert("Undo Posting Gagal!")
									}
									$("#content_adm").load("Trx_nilaimhs_ctr");
								}
							});
							
						}
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
Input Nilai Peserta Didik ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		
		<form action="" method="POST" name="frmTrxNilai" id="frmTrxNilai" >
			<table class="table table-hover" id='tblNilaiMhs' >
				
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
				
				<tr id='rowSKS' style='display:none;' >
					<td align='right' >SKS : </td>
					<td>
						<input type='hidden' id='txtSKS' name='txtSKS' >
						<label id='lblSKS' style='font-weight:normal;' ></label>
					</td>
				</tr>
				
				<tr id='rowFakultas' style='display:none;' >
					<td align='right' >Fakultas : </td>
					<td>
						<input type='hidden' id='txtFakultas' name='txtFakultas' >
						<label id='lblFakultas' style='font-weight:normal;' ></label>
					</td>
				</tr>
				
				<tr id='rowJurusan' style='display:none;' >
					<td align='right' >Jurusan : </td>
					<td>
						<input type='hidden' id='txtJurusan' name='txtJurusan' >
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