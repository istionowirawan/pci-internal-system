<script>
	$(document).ready(function() {
		
		$("#btnAddNew").click(function() {
			$("#frmDataKelas").slideToggle(500, function() {
				$("#txtKodeKelas").focus();
			});
		});
		
		$("#frmDataKelas").submit(function() {
			var dataform = $(this).serialize();
			
			if($("#btnSimpan").val()=="Simpan Data Kelas") {
				$.ajax({
					type: "POST",
					url: "Ms_kelas_ctr/simpan_data_kelas/",
					data: dataform,
					success: function(data, status) {
						if(data==1) {
							alert("Simpan Berhasil");
						} else {
							alert("Simpan Gagal!");
						}
						$("#content_adm").load("Ms_kelas_ctr");
					}
				});
			} else {
				//Update data
				$.ajax({
					type: "POST",
					url: "Ms_kelas_ctr/update_data_kelas/",
					data: dataform,
					success: function(data, status) {
						if(data==1) {
							alert("Update Berhasil");
						} else {
							alert("Update Gagal!");
						}
						$("#content_adm").load("Ms_kelas_ctr");
					}
				});
			}
			
			return false;
		});
		
		function show_warningbox(data) {
			$("#txtInfoMsg").text(data);
			$("#txtInfoBox").slideDown(1000, function() {
				$("#txtInfoBox").fadeOut(5000);
			});
		}
		
		$(".btnEditKelas").click(function() {
			var idkelas = "idkelas="+$(this).attr("alt");
			
			$.ajax({
				type: "POST",
				url: "Ms_kelas_ctr/edit_data_kelas/",
				data: idkelas,
				success: function(data, status) {
					//alert(data);
					$("#frmDataKelas").show(500);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							$("#txtKodeKelas").val(obj[c].kdkls);
							$("#txtKelasIndo").val(obj[c].nmkls);
							$("#txtKelasChn").val(obj[c].nmkls_ch);
							
							$("#txtKodeKelas").attr("readonly","readonly");
							$("#btnSimpan").val("Update Data Kelas");
							
							$("#txtFakultas").val(obj[c].id_fakultas);
							
							var idfakultas = "idfakultas="+obj[c].id_fakultas;
							var id_jur = obj[c].id_jurusan;
							
							$.ajax({
								type: "POST",
								url: "Ms_kelas_ctr/cari_jurusan/",
								data: idfakultas,
								success: function(data, status) {
									var objk = jQuery.parseJSON( data );
									if(objk.length>0) {
										$("#txtJurusan").empty();
										$("#txtJurusan").append("<option value=''>-- Pilih Jurusan --</option>");
										for(var d=0; d < objk.length; d++) {
											if(id_jur==objk[d].id) {
												$("#txtJurusan").append("<option selected value='"+objk[d].id+"'>"+objk[d].jurusan+"</option>");
											} else {
												$("#txtJurusan").append("<option value='"+objk[d].id+"'>"+objk[d].jurusan+"</option>");
											}
										}
											
									}
								}
							});
						}
					}
					//$("#content_adm").load("Ms_dosen_ctr");
				}
			});
			
		});
		
		
		$("#txtFakultas").change(function() {
			var idfakultas = "idfakultas=" + $(this).val();
			
			$.ajax({
				type: "POST",
				url: "Ms_matakul_ctr/cari_jurusan/",
				data: idfakultas,
				success: function(data, status) {
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						$(".kel_detail_makul").hide('200');
						
						$("#txtJurusan").empty();
						$("#txtJurusan").append("<option value=''>-- Pilih Jurusan --</option>");					
						for(var c=0; c < obj.length; c++) {
							$("#txtJurusan").append("<option value='"+obj[c].id+"'>"+obj[c].jurusan+"</option>");
						}
					}
				}
			});
		});
		
	});
</script>
				
<style>
#tblSekolah tr td {
	vertical-align:middle;
}
</style>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>
	
<div class='header-popup' >
Data Kelas
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNew' class='btn btn-danger btn-sm' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Tambah Kelas Baru</button>
		<br><br>
		<form action="" method="POST" name="frmDataKelas" id="frmDataKelas" style='display:none;' >
			<table class="table table-hover table-striped" id='tblKelas' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Fakultas : </td>
					<td>
						<select class='form-control' name='txtFakultas' id='txtFakultas' required >
						<option value=''>== Pilih Fakultas ==</option>
						<?php
						foreach($data_fakultas->result() as $row) {
							echo "<option value='".$row->id."' >".$row->fakultas."</option>";
						}
						?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Jurusan : </td>
					<td>
						<select class='form-control' name='txtJurusan' id='txtJurusan' required >
						<option value=''>== Pilih Jurusan ==</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Kode Kelas : </td>
					<td><input type='text' class='form-control' name='txtKodeKelas' id='txtKodeKelas' required ></td>
				</tr>
				
				<tr>
					<td align='right' >Nama Kelas (Indo) : </td>
					<td><input type='text' class='form-control' name='txtKelasIndo' id='txtKelasIndo' required ></td>
				</tr>
				
				<tr>
					<td align='right' >Nama Kelas (Ch) : </td>
					<td><input type='text' class='form-control' name='txtKelasChn' id='txtKelasChn' ></td>
				</tr>
				
				<tr>
					<td align='right' ></td>
					<td><input id='btnSimpan' type='submit' class='btn btn-primary btn-block' value='Simpan Data Kelas' ></td>
				</tr>
				
			</table>
		</form>
		
		
		<div id='list_data_kelas' class="table-responsive" >
			<form action="" method="POST" name="frmKelas" id="frmKelas" >
				<table class="table table-bordered table-striped" >
					<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
						<td>Fakultas</td>
						<td>Jurusan</td>
						<td>Kode Kelas</td>
						<td>Nama Kelas (Indo)</td>
						<td>Nama Kelas (Ch)</td>
						<td>Edit</td>
					</tr>
					
					<?php
					foreach($data_kelas->result() as $obj) {
						echo "<tr align='center'>";
						echo "<td>".$obj->fakultas."</td>";
						echo "<td>".$obj->jurusan."</td>";
						echo "<td>".$obj->kdkls."</td>";
						echo "<td>".$obj->nmkls."</td>";
						echo "<td>".$obj->nmkls_ch."</td>";
						echo "<td><input type='button' value='Edit' alt='".$obj->id."' class='btn-primary btn-sm btn-block btnEditKelas' ></td>";
						echo "</tr>";
					}
					?>
				</table>
			</form>
		</div>
		
	</div>
	
</div>