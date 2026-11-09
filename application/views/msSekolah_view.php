<script>
	$(document).ready(function() {
		
		$("#btnAddNew").click(function() {
			$("#frmSekolah").slideToggle(500, function() {
				$("#txtKodeSkul").focus();
				$( "#txtNamaSkul" ).autocomplete({
					source: arDataSekolah
				});
				
				$("#txtKodeSkul").blur(function() {
					for(var a=0; a < arKodeSekolah.length; a++) {
						if($(this).val()==arKodeSekolah[a]) {
							$(this).val('');
							show_warningbox('Kode Sekolah Sudah Pernah Digunakan');
						}
					}
				});
				
				$("#txtNamaSkul").blur(function() {
					for(var a=0; a < arDataSekolah.length; a++) {
						if($(this).val()==arDataSekolah[a]) {
							$(this).val('');
							show_warningbox('Nama Sekolah Sudah Pernah Digunakan');
						}
					}
				});
				
			});
		});
		
		$("#frmSekolah").submit(function() {
			var dataform = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "Ms_sekolah_ctr/simpan_sekolah/",
				data: dataform,
				success: function(data, status) {
					$("#content_adm").load("Ms_sekolah_ctr");
				}
			});
			
			return false;
		});
		
		function show_warningbox(data) {
			$("#txtInfoMsg").text(data);
			$("#txtInfoBox").slideDown(1000, function() {
				$("#txtInfoBox").fadeOut(5000);
			});
		}
		
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
Data Sekolah
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNew' class='btn btn-danger' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Add new school</button>
		<br><br>
		<form action="" method="POST" name="frmSekolah" id="frmSekolah" style='display:none;' >
			<table class="table table-hover" id='tblSekolah' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Kode Sekolah : </td>
					<td><input type='text' class='form-control' name='txtKodeSkul' id='txtKodeSkul' required ></td>
				</tr>
				
				<tr>
					<td align='right' >Nama Sekolah : </td>
					<td><input type='text' class='form-control' name='txtNamaSkul' id='txtNamaSkul' required ></td>
				</tr>
				
				<tr>
					<td align='right' >Jenjang</td>
					<td>
						<select class='form-control' name='txtJenjang' id='txtJenjang' required >
						<option value='' >== Pilih Jenjang Sekolah ==</option>
						<option value='SMA' >SMA</option>
						<option value='D1' >D1</option>
						<option value='D2' >D2</option>
						<option value='D3' >D3</option>
						<option value='S1' >S1</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td align='right' >Alamat : </td>
					<td><textarea class='form-control' name='txtAlamatSkul' id='txtAlamatSkul' required ></textarea></td>
				</tr>
				
				<tr>
					<td align='right' >Telepon : </td>
					<td><input type='text' class='form-control' name='txtTelpSkul' id='txtTelpSkul' required ></td>
				</tr>
				
				<tr>
					<td align='right' ></td>
					<td><input type='submit' class='btn btn-primary btn-block' value='Simpan Data Sekolah' ></td>
				</tr>
				
			</table>
		</form>
		
		
		<div id='list_data_sekolah' class="table-responsive" >
			<form action="" method="POST" name="frmAktifasi" id="frmAktifasi" >
				<table class="table table-bordered" >
					<tr align='center' class='header-popup-tbl' >
						<td>Kode Sekolah</td>
						<td>Nama Sekolah</td>
						<td>Jenjang</td>
						<td>Alamat</td>
						<td>Telp</td>
					</tr>
					
					<?php
					echo "<script>";
					echo "var arKodeSekolah = new Array();";
					echo "var arDataSekolah = new Array();";
					echo "</script>";
					
					foreach($data_sekolah->result() as $obj) {
						echo "<tr align='center'>";
						echo "<td>".$obj->kode."</td>";
						echo "<td>".$obj->nama_sekolah."</td>";
						echo "<td>".$obj->jenjang."</td>";
						echo "<td>".$obj->alamat."</td>";
						echo "<td>".$obj->telp."</td>";
						echo "</tr>";
						
						echo "<script>";
						echo "arKodeSekolah.push('".$obj->kode."');";
						echo "arDataSekolah.push('".$obj->nama_sekolah."');";
						echo "</script>";
					}
					?>
				</table>
			</form>
		</div>
		
	</div>
	
</div>