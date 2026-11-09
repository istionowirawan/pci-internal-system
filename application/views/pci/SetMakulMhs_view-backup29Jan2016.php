<script>
	var arNamaMahasiswa = new Array();
		
	$(document).ready(function() {
		$("#btnAddNew").click(function() {
			$("#frmSetMakulMhs").slideToggle(500, function() {
				$("#txtDosenNik").focus();
			});
		});
		
		
		$(".btnMapelPilih").click(function() {
			if($(this).css("background-color")=="rgb(255, 255, 255)") {
				$(this).css("background-color", "#FFF466");
			} else {
				$(this).css("background-color", "#FFFFFF");
			}
			hitung_jml_sks();
		});
		
		function hitung_jml_sks() {
			var jmlSksPilih = 0;
			$( ".btnMapelPilih" ).each(function() {
				if ($(this).css("background-color") == "rgb(255, 244, 102)" ) {
					jmlSksPilih+= Number($(this).attr("alt")) ;
				}
			});
			$("#txtJmlSks").text(jmlSksPilih);
		}
		
		$( "#txtNimMahasiswa" ).autocomplete({
			source: arNamaMahasiswa,
			focus: function( event, ui ) {
				$("#txtNimMahasiswa").val(ui.item.value);
				$("#txtNamaMahasiswa").val(ui.item.label);
			}, 
			select: function ( event, ui ) {
				$("#txtNamaMahasiswa").val(ui.item.label);
			}, 
			change: function ( event, ui ) {
				$("#txtNamaMahasiswa").val(ui.item.label);
			}
		});
		
		
		$("#frmSetMakulMhs").submit(function() {
			var dataform = $(this).serialize();
			
			var hitung=1;
			$( ".btnMapelPilih" ).each(function() {
				if ($(this).css("background-color") == "rgb(255, 244, 102)" ) {
					dataform += "&idMakul"+hitung+"="+$(this).attr("idmapel");
					hitung++;
				}
			});
			
			$.ajax({
				type: "POST",
				url: "Set_makul_mhs_ctr/simpan_makul_mhs/",
				data: dataform,
				success: function(data, status) {
					alert(data);
					/*if(data==1) {
						alert("Simpan Makul Sukses!")
					} else {
						alert("Simpan Makul Gagal!")
					}*/
					//$("#content_adm").load("Set_makul_kelas_ctr");
				}
			});
			
			return false;
		});
		
	});
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>
	
<div class='header-popup' >
Setting Kelas Matakuliah ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNew' class='btn btn-danger btn-sm' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Setting Mata Kuliah</button>
		<br><br>
		<form action="" method="POST" name="frmSetMakulMhs" id="frmSetMakulMhs" style='display:none;' >
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
						<?php echo $ta_pci_active->semester; ?>
						<input type='hidden' class='form-control' name='txtSemester' id='txtSemester' value='<?php echo $ta_pci_active->semester; ?>' required >
					</td>
				</tr>
				
				
				<tr>
					<td align='right' >Nim : </td>
					<td>
						<input type='text' class='form-control' name='txtNimMahasiswa' id='txtNimMahasiswa' placeholder='ketik nama mahasiswa' required >
					</td>
				</tr>
				
				<?php
				echo "<script>";
				echo "var nourut=0;";
				echo "</script>";
				foreach($data_mahasiswa->result() as $obj) {
					echo "<script>";
					echo "arNamaMahasiswa[nourut]={ label: '".$obj->nama_depan."', value: '".$obj->nim."' };";
					echo "nourut++;";
					echo "</script>";
				}
				?>
				
				<tr>
					<td align='right' >Nama : </td>
					<td>
						<input type='text' class='form-control' name='txtNamaMahasiswa' id='txtNamaMahasiswa' readonly >
					</td>
				</tr>
				
				<tr>
					<td align='right' >Pilih Mata Kuliah Dengan Kelas : </td>
					<td>
						<?php
						$jmlMakul = $data_makul_kelas->num_rows();
						$jmlMakulBagiTiga = ceil($jmlMakul/3);
						
						echo "<table class='table' >";
						$showMakul = 1;
						foreach($data_makul_kelas->result() as $row) {
							if($showMakul==1) {
								echo "<tr>";
							}
							
							echo "<td width='33%' style='border:none;' >";
							echo "<input type='button' class='form-control btn-xs btn-block btnMapelPilih' 
								style='background-color:#ffffff;height:50px;white-space:normal;' 
								title='Klik untuk memilih pelajaran'
								alt='".$row->sks."'
								idmapel='".$row->id."'
								value='".$row->kode_makul . " - ".$row->nmkmk . ", ".$row->sks." SKS (" .$row->kode_kelas. "-" .$row->nmkls.")' >";
							echo "</td>";
							
							if($showMakul==3) {
								echo "</tr>";
								$showMakul=0;
							}
							$showMakul++;
						}
						echo "</table>";
						?>
					</td>
				</tr>
				
				
				<tr>
					<td align='right'>Total SKS : </td>
					<td><label id='txtJmlSks' name='txtJmlSks' >0</label></td>
				</tr>
				
				<tr>
					<td colspan='2'><input type='submit' class='btn btn-primary btn-sm form-control' name='btnSimpan' id='btnSimpan' value='Simpan' ></td>
				</tr>
				
				
			</table>
		</form>
		
		
		<div id='list_makul_kelas' class="table-responsive" >
			<form action="" method="POST" name="frmMakulKelas" id="frmMakulKelas" >
				<table class="table table-bordered" >
					<tr align='center' class='header-popup-tbl' >
						<td>Tahun Ajaran</td>
						<td>Semester</td>
						<td>Kode Makul</td>
						<td>Nama Makul</td>
						<td>Kode Kelas</td>
						<td>Nama Kelas</td>
						<td>Hapus</td>
					</tr>
					
					<?php
					/*foreach($data_makul_kelas->result() as $obj) {
						echo "<tr align='center'>";
						echo "<td>".$obj->tahun_ajaran."</td>";
						echo "<td>".$obj->semester."</td>";
						echo "<td>".$obj->kode_makul."</td>";
						echo "<td>".$obj->nmkmk."</td>";
						echo "<td>".$obj->kode_kelas."</td>";
						echo "<td>".$obj->nmkls."</td>";
						echo "<td><input type='button' value='Hapus' alt='".$obj->id."' class='form-control btn-danger btn-sm btn-block btnHapusMakulKls' title='Hapus data'  ></td>";
						echo "</tr>";
					}*/
					?>
				</table>
			</form>
		</div>
		
		
		
	</div>
	
</div>