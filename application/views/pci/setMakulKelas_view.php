<script>
	$(document).ready(function() {
		
		$("#btnAddNew").click(function() {
			$("#frmSetMakulKelas").slideToggle(500, function() {
				$("#txtDosenNik").focus();
			});
		});
		
		
		$("#frmSetMakulKelas").submit(function() {
			var dataform = $(this).serialize();
			dataform+="&txtIdMakul="+$('#txtMakul option:selected').attr('alt');
			dataform+="&txtIdKelas="+$('#txtKelas option:selected').attr('alt');
			dataform+="&txtIdDosen="+$('#txtDosen option:selected').attr('alt');;
			//alert(dataform);
			
			$.ajax({
				type: "POST",
				url: "Set_makul_kelas_ctr/simpan_makul_kelas/",
				data: dataform,
				success: function(data, status) {
					//alert(data);
					if(data==1) {
						alert("Simpan Makul Sukses!");
					} else {
						alert("Simpan Makul Gagal!");
					}
					$("#content_adm").load("Set_makul_kelas_ctr");
				}
			});
			
			return false;
		});
		
		
		$(".btnHapusMakulKls").click(function() {
			var idMakulKls = "idMakulKls="+$(this).attr("alt");
			
			if (confirm('Jika Anda menghapus data ini, maka semua data yang berhubungan dengan kelas ini akan ikut terhapus!\nAnda yakin hapus data ini???')) {
				
				$.ajax({
					type: "POST",
					url: "Set_makul_kelas_ctr/hapus_makul_kelas/",
					data: idMakulKls,
					success: function(data, status) {
						//alert(data);
						if(data==1) {
							alert("Hapus Makul Sukses!")
						} else {
							alert("Hapus Makul Gagal!")
						}
						$("#content_adm").load("Set_makul_kelas_ctr");
					}
				});
			}
			return false;
		});
		
		
		$("#txtFakultas").change(function() {
			var idfakultas = "idfakultas=" + $(this).val();
			
			$.ajax({
				type: "POST",
				url: "Set_makul_kelas_ctr/cari_jurusan/",
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
			var idfakjur = "idfakultas=" + $("#txtFakultas").val();
			idfakjur += "&idjurusan="+$(this).val();
			
			$.ajax({
				type: "POST",
				url: "Set_makul_kelas_ctr/cari_periode/",
				data: idfakjur,
				success: function(data, status) {
					//alert(data);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						
						$("#txtPeriode").empty();
						$("#txtPeriode").append("<option value=''>-- Pilih Periode --</option>");					
						for(var c=0; c < obj.length; c++) {
							$("#txtPeriode").append("<option value='"+obj[c].semester+"' alt='"+obj[c].semester+"' >" + obj[c].semester+"</option>");
						}
					}
				}
			});
			
			
		});
		
		$("#txtPeriode").change(function() {
			var idfakjur = "idfakultas=" + $("#txtFakultas").val();
			idfakjur += "&idjurusan="+$("#txtJurusan").val();
			idfakjur += "&txtPeriode="+$(this).val();
			
			$.ajax({
				type: "POST",
				url: "Set_makul_kelas_ctr/cari_makul/",
				data: idfakjur,
				success: function(data, status) {
					//alert(data);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						
						$("#txtMakul").empty();
						$("#txtMakul").append("<option value=''>-- Pilih Makul --</option>");					
						for(var c=0; c < obj.length; c++) {
							$("#txtMakul").append("<option value='"+obj[c].kdmk+"' alt='"+obj[c].id+"' > ("+obj[c].kdmk + ") " + obj[c].nmkmk+"</option>");
						}
					}
					
				}
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
Setting Kelas MataPelajaran ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNew' class='btn btn-danger btn-sm' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Setting Mata Pelajaran</button>
		<br><br>
		<form action="" method="POST" name="frmSetMakulKelas" id="frmSetMakulKelas" style='display:none;' >
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
				
				<tr>
					<td align='right' width='15%' >Periode : </td>
					<td>
						<select class='form-control input-sm' name='txtPeriode' id='txtPeriode' required >
						<option value=''>== Pilih Periode ==</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td align='right' >Mata Pelajaran : </td>
					<td>
						<select class='form-control input-sm' name='txtMakul' id='txtMakul' required >
						<?php
						echo "<option value='' alt='' >== Pilih Makul ==</option>";
						//foreach($data_makul->result() as $row) {
							//echo "<option value='".$row->kdmk."' alt='".$row->id."' >".$row->nmkmk . " - " . $row->nmkmk_ch." ( ". $row->sks ." sks )"."</option>";
						//}
						?>
						</select>
					</td>
				</tr>
				
				
				<tr>
					<td align='right' >Kelas : </td>
					<td>
						<select class='form-control input-sm' name='txtKelas' id='txtKelas' required >
						<?php
						echo "<option value='' alt='' >== Pilih Kelas ==</option>";
						foreach($data_kelas->result() as $row) {
							echo "<option value='".$row->kdkls."' alt='".$row->id."' >".$row->nmkls . " - " . $row->nmkls_ch."( ".$row->kdkls." )"."</option>";
						}
						?>
						</select>
					</td>
				</tr>
				
				
				<tr>
					<td align='right' >Pengajar : </td>
					<td>
						<select class='form-control input-sm' name='txtDosen' id='txtDosen' required >
						<?php
						echo "<option value='' alt='' >== Pilih Pengajar ==</option>";
						foreach($data_dosen->result() as $row) {
							echo "<option value='".$row->nik."' alt='".$row->id."' >".$row->nama;
							if($row->nama_ch!="") {
								echo " ( ".$row->nama_ch." )";
							}
							echo "</option>";
						}
						?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td colspan='2'><input type='submit' class='btn btn-primary btn-sm form-control' name='btnSimpan' id='btnSimpan' value='Simpan' ></td>
				</tr>
				
				
			</table>
		</form>
		
		
		<div id='list_makul_kelas' class="table-responsive" >
			<form action="" method="POST" name="frmMakulKelas" id="frmMakulKelas" >
				<table class="table table-bordered table-striped" >
					<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
						<td>Tahun Ajaran</td>
						<td>Semester</td>
						<td>Kode Mapel</td>
						<td>Nama Mapel</td>
						<td>Periode</td>
						<td>Kode Kelas</td>
						<td>Nama Kelas</td>
						<td>Nama Pengajar</td>
						<td>Hapus</td>
					</tr>
					
					<?php
					foreach($data_makul_kelas->result() as $obj) {
						echo "<tr align='center'>";
						echo "<td>".$obj->tahun_ajaran."</td>";
						echo "<td>".($obj->semester%2==0?"Genap":"Ganjil")."</td>";
						echo "<td>".$obj->kode_makul."</td>";
						echo "<td>".$obj->nmkmk."</td>";
						echo "<td>".$obj->periode."</td>";
						echo "<td>".$obj->kode_kelas."</td>";
						echo "<td>".$obj->nmkls."</td>";
						echo "<td>".$obj->nama_dosen;
							if(trim($obj->nama_dosen_ch)!="") {
								echo " (".$obj->nama_dosen_ch.")";
							}
						echo "</td>";
						echo "<td><input type='button' value='Hapus' alt='".$obj->id."' class='form-control btn-danger input-sm btn-block btnHapusMakulKls' title='Hapus data'  ></td>";
						echo "</tr>";
					}
					?>
				</table>
			</form>
		</div>
		
		
		
	</div>
	
</div>