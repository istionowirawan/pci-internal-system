<script>
	$(document).ready(function() {
		
		$("#btnAddNewFakultas").click(function() {
			$("#frmJurusan").hide(500);
			$("#frmFakultas").slideToggle(500, function() {
				$("#txtNamaFakultas").focus();
				
				$("#frmFakultas").submit(function() {
					var dataform = $(this).serialize();
					$.ajax({
						type: "POST",
						url: "Ms_fakultas_ctr/simpan_fakultas/",
						data: dataform,
						success: function(data, status) {
							show_warningbox_fakultas(data);
						}
					});
					
					return false;
				});
				
			});
		});
		
		$("#btnAddNewJurusan").click(function() {
			$("#frmFakultas").hide(500);
			$("#frmJurusan").slideToggle(500, function() {
				$.ajax({
					type: "POST",
					url: "Ms_fakultas_ctr/cari_fakultas/",
					success: function(data, status) {
						var obj = jQuery.parseJSON( data );
						if(obj.length>0) {
							$("#txtIdFakultas").empty();
							$("#txtIdFakultas").append("<option value=''>++ Pilih Fakultas ++</option>");
							for(var c=0; c < obj.length; c++) {
								$("#txtIdFakultas").append("<option value='"+obj[c].id+"'>"+obj[c].fakultas+"</option>");
							}
						}
					}
				});
				
				
				$("#frmJurusan").submit(function() {
					var dataform = $(this).serialize();
					$.ajax({
						type: "POST",
						url: "Ms_fakultas_ctr/simpan_jurusan/",
						data: dataform,
						success: function(data, status) {
							show_warningbox_jurusan(data);
						}
					});
					
					return false;
				});
				
			});
		});
		
		function show_warningbox_fakultas(data) {
			$("#txtInfoMsg").text(data);
			$("#txtInfoBox").slideDown(1000, function() {
				$("#txtInfoBox").fadeOut(5000);
			});
			if(data=="Simpan Berhasil") {
				$("#content_adm").load("Ms_fakultas_ctr");
			}
		}
		
		function show_warningbox_jurusan(data) {
			$("#txtInfoMsg2").text(data);
			$("#txtInfoBox2").slideDown(1000, function() {
				$("#txtInfoBox2").fadeOut(5000);
			});
			if(data=="Simpan Berhasil") {
				$("#content_adm").load("Ms_fakultas_ctr");
			}
		}
		
	});
</script>
				
<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Master Fakultas & Jurusan PCI
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNewFakultas' class='btn btn-danger' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Add new Fakultas</button>
		<button id='btnAddNewJurusan' class='btn btn-warning' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Add new Jurusan</button>
		
		<br><br>
		
		<form action="" method="POST" name="frmFakultas" id="frmFakultas" style='display:none;' >
			<table class="table table-hover" id='tblSekolah' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Nama Fakultas : </td>
					<td><input type='text' class='form-control' name='txtNamaFakultas' id='txtNamaFakultas' required ></td>
				</tr>
				
				<tr>
					<td align='right' ></td>
					<td><input type='submit' class='btn btn-primary btn-block' value='Simpan Fakultas' ></td>
				</tr>
				
			</table>
		</form>
		
		
		<form action="" method="POST" name="frmJurusan" id="frmJurusan" style='display:none;' >
			<table class="table table-hover" id='tblSekolah' >
				
				<tr id='txtInfoBox2' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg2' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Pilih Fakultas : </td>
					<td>
						<select class='form-control' name='txtIdFakultas' id='txtIdFakultas' required >
						<option value=''>== Pilih Fakultas ==</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Jurusan : </td>
					<td><input type='text' class='form-control' name='txtJurusan' id='txtJurusan' required ></td>
				</tr>
				
				<tr>
					<td align='right' ></td>
					<td><input type='submit' class='btn btn-primary btn-block' value='Simpan Jurusan' ></td>
				</tr>
				
			</table>
		</form>
		
		
		
		<div id='list_data_fakultas' class="table-responsive" >
			<form action="" method="POST" name="frmAktifasi" id="frmAktifasi" >
				<table class="table table-bordered" >
					<tr align='center' class='header-popup-tbl' >
						<td>Fakultas</td>
						<td>Jurusan</td>
					</tr>
					
					<?php
					$newFakultas=-1;
					foreach($data_fakultas->result() as $obj) {
						$spanrow = $this->db->query("SELECT * FROM ms_jurusan WHERE fakultas_id='".$obj->id."'");
						
						echo "<tr align='center'>";
						
						if($newFakultas==-1 || $newFakultas!=$obj->id) {
							$newFakultas=$obj->id;
							echo "<td rowspan='".$spanrow->num_rows()."'>".$obj->fakultas."</td>";
						}
						
						echo "<td>".$obj->jurusan."</td>";
						echo "</tr>";
					}
					?>
					
				</table>
			</form>
		</div>
		
	</div>
	
</div>