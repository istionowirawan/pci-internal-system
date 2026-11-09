<script>
	$(document).ready(function() {
		
		$("#btnAddNew").click(function() {
			$("#frmDataDosen").slideToggle(500, function() {
				$("#txtDosenNik").focus();
			});
		});
		
		$("#frmDataDosen").submit(function() {
			var dataform = $(this).serialize();
			
			if($("#btnSimpan").val()=="Simpan Data Dosen") {
				$.ajax({
					type: "POST",
					url: "Ms_dosen_ctr/simpan_data_dosen/",
					data: dataform,
					success: function(data, status) {
						if(data==1) {
							alert("Simpan Berhasil");
						} else {
							alert("Simpan Gagal!");
						}
						$("#content_adm").load("Ms_dosen_ctr");
					}
				});
			} else {
				//Update data
				$.ajax({
					type: "POST",
					url: "Ms_dosen_ctr/update_data_dosen/",
					data: dataform,
					success: function(data, status) {
						if(data==1) {
							alert("Update Berhasil");
						} else {
							alert("Update Gagal!");
						}
						$("#content_adm").load("Ms_dosen_ctr");
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
		
		$(".btnActiveDosen").click(function() {
			var iddosen = "iddosen="+$(this).attr("alt");
			
			$.ajax({
				type: "POST",
				url: "Ms_dosen_ctr/active_data_dosen/",
				data: iddosen,
				success: function(data, status) {
					//alert(data);
					if(data==1) {
						//alert("Status Dosen Berhasil diubah");
					} else {
						//alert("Status Dosen Gagal diubah!");
					}
					$("#content_adm").load("Ms_dosen_ctr");
				}
			});
			
			return false;
		});
		
		$(".btnEditDosen").click(function() {
			var iddosen = "iddosen="+$(this).attr("alt");
			
			$.ajax({
				type: "POST",
				url: "Ms_dosen_ctr/edit_data_dosen/",
				data: iddosen,
				success: function(data, status) {
					//alert(data);
					$("#frmDataDosen").show(500);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							$("#txtDosenNik").val(obj[c].nik);
							$("#txtNamaDosen").val(obj[c].nama);
							$("#txtNamaChinese").val(obj[c].nama_ch);
							$("#txtAlamatDosen").val(obj[c].alamat);
							$("#txtTelpDosen").val(obj[c].telp);
							$("#txtEmailDosen").val(obj[c].email);
							$("#txtGelar").val(obj[c].gelar);
							$("#txtLulusTahun").val(obj[c].lulus_tahun);
							$("#txtDosenNik").attr("readonly","readonly");
							
							$("#btnSimpan").val("Update Data Dosen");
						}
					}
					//$("#content_adm").load("Ms_dosen_ctr");
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
Data Dosen
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNew' class='btn btn-danger btn-sm' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Tambah dosen baru</button>
		<br><br>
		<form action="" method="POST" name="frmDataDosen" id="frmDataDosen" style='display:none;' >
			<table class="table table-hover" id='tblDataDosen' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >NIK : </td>
					<td><input type='text' class='form-control' name='txtDosenNik' id='txtDosenNik' required ></td>
				</tr>
				
				<tr>
					<td align='right' >Nama Pengajar : </td>
					<td><input type='text' class='form-control' name='txtNamaDosen' id='txtNamaDosen' required ></td>
				</tr>
				
				<tr>
					<td align='right' >Nama Chinese : </td>
					<td><input type='text' class='form-control' name='txtNamaChinese' id='txtNamaChinese' ></td>
				</tr>
				
				<tr>
					<td align='right' >Alamat : </td>
					<td><textarea class='form-control' name='txtAlamatDosen' id='txtAlamatDosen' ></textarea></td>
				</tr>
				
				<tr>
					<td align='right' >Telepon : </td>
					<td><input type='text' class='form-control' name='txtTelpDosen' id='txtTelpDosen' ></td>
				</tr>
				
				<tr>
					<td align='right' >Email : </td>
					<td><input type='email' class='form-control' name='txtEmailDosen' id='txtEmailDosen' ></td>
				</tr>
				
				<tr>
					<td align='right' >Gelar : </td>
					<td><input type='text' class='form-control' name='txtGelar' id='txtGelar' ></td>
				</tr>
				
				<tr>
					<td align='right' >Lulus tahun : </td>
					<td><input type='number' class='form-control' name='txtLulusTahun' id='txtLulusTahun' maxlength='4' ></td>
				</tr>
				
				<tr>
					<td align='right' ></td>
					<td><input id='btnSimpan' type='submit' class='btn btn-primary btn-block' value='Simpan Data Dosen' ></td>
				</tr>
				
			</table>
		</form>
		
		
		<div id='list_data_sekolah' class="table-responsive" >
			<form action="" method="POST" name="frmAktifasi" id="frmAktifasi" >
				<table class="table table-bordered table-striped" >
					<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
						<td>NIK</td>
						<td>Nama Dosen</td>
						<td>Nama Chinese</td>
						<td>Alamat</td>
						<td>Telp</td>
						<td>Email</td>
						<td>Gelar</td>
						<td>Lulusan Tahun</td>
						<td>Status</td>
						<td>Edit</td>
					</tr>
					
					<?php
					foreach($data_dosen->result() as $obj) {
						echo "<tr align='center'>";
						echo "<td>".$obj->nik."</td>";
						echo "<td>".$obj->nama."</td>";
						echo "<td>".$obj->nama_ch."</td>";
						echo "<td>".$obj->alamat."</td>";
						echo "<td>".$obj->telp."</td>";
						echo "<td>".$obj->email."</td>";
						echo "<td>".$obj->gelar."</td>";
						echo "<td>".$obj->lulus_tahun."</td>";
						echo "<td><input type='button' value='".($obj->status==1?"Active":"Non-active")."' alt='".$obj->id."' class='btn-danger btn-sm btn-block btnActiveDosen' title='Active atau NonActive-kan Dosen'  ></td>";
						echo "<td><input type='button' value='Edit' alt='".$obj->id."' class='btn-primary btn-sm btn-block btnEditDosen' title='Edit data ".$obj->nama."'  ></td>";
						echo "</tr>";
					}
					?>
				</table>
			</form>
		</div>
		
	</div>
	
</div>