	<script>
	$(document).ready(function() {
		
		
		$("#btnAddNew").click(function() {
			$(".kel_detail_makul").hide();
				
			$("#frmDataMatakul").slideToggle(500, function() {
				$("#txtKodeMakul").focus();
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
		
		$("#txtJurusan").change(function() {
			$(".kel_detail_makul").show('500');
			$("#txtKodeMakul").focus();
		});
		
		$("#frmDataMatakul").submit(function() {
			var dataform = $(this).serialize();
			
				if($("#btnSimpan").val()=="Simpan Data Mata Kuliah") {
				$.ajax({
					type: "POST",
					url: "Ms_matakul_ctr/simpan_data_makul/",
					data: dataform,
					success: function(data, status) {
						if(data==1) {
							alert("Simpan Berhasil");
						} else {
							alert("Simpan Gagal!");
						}
						$("#content_adm").load("Ms_matakul_ctr");
					}
				});
			} else {
				//Update data
				$.ajax({
					type: "POST",
					url: "Ms_matakul_ctr/update_data_makul/",
					data: dataform,
					success: function(data, status) {
						if(data==1) {
							alert("Update Berhasil");
						} else {
							alert("Update Gagal!");
						}
						$("#content_adm").load("Ms_matakul_ctr");
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
		
		$(".btnEditMakul").click(function() {
			var iddosen = "idmakul="+$(this).attr("alt");
			
			$.ajax({
				type: "POST",
				url: "Ms_matakul_ctr/edit_data_makul/",
				data: iddosen,
				success: function(data, status) {
					//alert(data);
					$("#frmDataMatakul").show(500);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							$("#txtKodeMakul").val(obj[c].kdmk);
							$("#txtMakulIndo").val(obj[c].nmkmk);
							$("#txtMakulEng").val(obj[c].nmkmk_eng);
							$("#txtMakulChn").val(obj[c].nmkmk_ch);
							$("#txtSKS").val(obj[c].sks);
							$("#txtSemester").val(obj[c].semester)
							$("#txtFakultas").val(obj[c].id_fakultas);
							
							var idfakultas = "idfakultas="+obj[c].id_fakultas;
							var id_jur = obj[c].id_jurusan;
							
							$.ajax({
								type: "POST",
								url: "Ms_matakul_ctr/cari_jurusan/",
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
							
							$("#txtKodeMakul").attr("readonly","readonly");
							$("#btnSimpan").val("Update Data Mata Kuliah");
						}
					}
					//$("#content_adm").load("Ms_dosen_ctr");
					
					$('html,body').animate({
						scrollTop: 0
					}, 700);
				}
			});
			
		});
		
		
		$(".headjurusan").click(function() {
			var jurid = ($(this).attr("alt"));
			if($(".headmakul"+jurid).is(":visible")==false) {
				$(".headmakul"+jurid).slideDown(500);
				$(".detailmakul"+jurid).slideDown(500);
			} else {
				$(".headmakul"+jurid).slideUp(500);
				$(".detailmakul"+jurid).slideUp(500);
			}
		});
		
	});
</script>
				
<style>
#tblSekolah tr td {
	vertical-align:middle;
}
.headjurusan {
	background-color:#3F5AB5;
	color:#F7FF6D;
	font-size:13px;
	cursor:pointer;
}
</style>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>
	
<div class='header-popup' >
Data Mata Pelajaran
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNew' class='btn btn-danger btn-sm' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Tambah Mata Pelajaran Baru</button>
		<br><br>
		<form action="" method="POST" name="frmDataMatakul" id="frmDataMatakul" style='display:none;' >
			<table class="table table-hover" id='tblMataKul' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Fakultas : </td>
					<td>
						<select class='form-control input-sm' name='txtFakultas' id='txtFakultas' required >
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
						<select class='form-control input-sm' name='txtJurusan' id='txtJurusan' required >
						<option value=''>== Pilih Jurusan ==</option>
						</select>
					</td>
				</tr>
				
				<tr class='kel_detail_makul'>
					<td align='right' width='15%' >Kode Mata Pelajaran : </td>
					<td><input type='text' class='form-control input-sm' name='txtKodeMakul' id='txtKodeMakul' required ></td>
				</tr>
				
				<tr class='kel_detail_makul'>
					<td align='right' >Nama Mata Pelajaran (Indo) : </td>
					<td><input type='text' class='form-control input-sm' name='txtMakulIndo' id='txtMakulIndo' required ></td>
				</tr>
				
				<tr class='kel_detail_makul'>
					<td align='right' >Nama Mata Pelajaran (Eng) : </td>
					<td><input type='text' class='form-control input-sm' name='txtMakulEng' id='txtMakulEng' ></td>
				</tr>
				
				<tr class='kel_detail_makul'>
					<td align='right' >Nama Mata Pelajaran (Ch) : </td>
					<td><input type='text' class='form-control input-sm' name='txtMakulChn' id='txtMakulChn' ></td>
				</tr>
				
				<tr class='kel_detail_makul'>
					<td align='right' >SKS : </td>
					<td><input type='number' class='form-control input-sm' name='txtSKS' id='txtSKS' ></td>
				</tr>
				
				<tr class='kel_detail_makul'>
					<td align='right' >Periode (semester) : </td>
					<td>
						<select class='form-control input-sm' name='txtSemester' id='txtSemester' required >
						<option value=''>== Pilih Periode ==</option>
						<?php
						for($a=1; $a<=8; $a++) {
							echo "<option value='".$a."'>".$a."</option>";
						}
						?>
						</select>
					</td>
				</tr>
				
				<tr class='kel_detail_makul'>
					<td align='right' ></td>
					<td><input id='btnSimpan' type='submit' class='btn btn-primary btn-block' value='Simpan Data Mata Kuliah' ></td>
				</tr>
				</div>
			</table>
		</form>
		
		
		<div id='list_data_sekolah' class="table-responsive" >
			<table class="table table-bordered table-striped" >
				<?php
				foreach($header_jurusan->result() as $jur) {
					
					echo "<tr align='left' class='headjurusan' alt='".$jur->id_jurusan."' title='Click me to see makul ".$jur->jurusan."' style='background-color:blue;' >
						<td colspan='7'>
						".$jur->fakultas." ( ".$jur->jurusan." )
						</td>
					</tr>";
					
					
					echo "<tr align='center' class='header-popup-tbl headmakul".$jur->id_jurusan."' style='display:none;background-color:#6071C6' >
						<td>Kode Mata Kuliah</td>
						<td>Nama Mata Kuliah (Indo)</td>
						<td>Nama Mata Kuliah (Eng)</td>
						<td>Nama Mata Kuliah (Ch)</td>
						<td>SKS</td>
						<td>Periode</td>
						<td>Edit</td>
					</tr>
					";
					
					foreach($data_matakul->result() as $obj) {
						if($jur->id_jurusan==$obj->id_jurusan) {
						
							echo "<tr align='center' class='detailmakul".$jur->id_jurusan."' style='display:none;' >";
							echo "<td>".$obj->kdmk."</td>";
							echo "<td>".$obj->nmkmk."</td>";
							echo "<td>".$obj->nmkmk_eng."</td>";
							echo "<td>".$obj->nmkmk_ch."</td>";
							echo "<td>".$obj->sks."</td>";
							echo "<td>".$obj->semester."</td>";
							echo "<td><input type='button' value='Edit' alt='".$obj->id."' class='btn-primary btn-sm btn-block btnEditMakul' ></td>";
							echo "</tr>";
						}
					}
					
					echo "<tr><td colspan='7'></td></tr>";
					
				}
				?>
			</table>
		</div>
		
	</div>
	
</div>