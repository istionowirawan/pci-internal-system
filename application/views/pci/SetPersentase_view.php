<script>
	$(document).ready(function() {
		
		$("#frmSetPersentase").submit(function() {
			var dataform = $(this).serialize();
			
			$.ajax({
				type: "POST",
				url: "Set_persentase_ctr/simpan_persentase/",
				data: dataform,
				success: function(data, status) {
					//alert(data);
					$("#content_adm").hide();
					
					if(data==1) {
						alert("Simpan Setting Persentase Berhasil!")
					} else {
						alert("Simpan Setting Persentase Gagal!")
					}
					$("#content_adm").load("Set_persentase_ctr");
					$("#content_adm").slideDown('500');
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
Setting Persentase Absensi dan Bobot Ujian ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		
		<form action="" method="POST" name="frmSetPersentase" id="frmSetPersentase" >
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
			</table>
			
			<table class="table table-bordered table-striped" id='tblSetPersentase' >
				<?php
				echo "<tr align='center' style='background-color:#6172C7;color:#ffffff;' >";
				echo "<td>Nik Pengajar</td>";
				echo "<td>Nama Pengajar</td>";
				echo "<td>Mata Pelajaran</td>";
				echo "<td>Kelas</td>";
				echo "<td>Jml Pertemuan</td>";
				echo "<td>Absensi (%)</td>";
				echo "<td>Tugas (%)</td>";
				echo "<td>UTP (%)</td>";
				echo "<td>UAP (%)</td>";
				echo "</tr>";
				
				foreach($dosen_makul->result() as $row) {
					echo "<tr align='center'>";
					
					echo "<td>".$row->nik_dosen."</td>";
					
					$namach = "";
					if(!empty($row->nama_ch)) {
						$namach = " (".$row->nama_ch.") ";
					}
					
					echo "<td>".$row->nama_dosen . $namach ."</td>";
					echo "<td>".$row->kode_makul . " (".$row->nmkmk.") " ."</td>";
					echo "<td>".$row->kode_kelas . " (".$row->nmkls.") " ."</td>";
					
					$jmlPert = "";
					$jmlAbsensi = "";
					$jmlTugas = "";
					$jmlUTP = "";
					$jmlUAP = "";
					foreach($isi_set_persen->result() as $obj) {
						if($obj->id_trx==$row->id) {
							$jmlPert = $obj->pertemuan;
							$jmlAbsensi = $obj->absensi;
							$jmlTugas = $obj->tugas;
							$jmlUTP = $obj->utp;
							$jmlUAP = $obj->uap;
							break;
						}
					}
					
					echo "<input type='hidden' class='form-control' name='txtIdSetMakulKelas[]' id='txtIdSetMakulKelas".$row->id."' value='".$row->id."' >";
					echo "<td><input type='number' class='form-control' name='txtPert[]' id='txtPert".$row->id."' max='100' value='".$jmlPert."' ></td>";
					echo "<td><input type='number' class='form-control' name='txtAbsensi[]' id='txtAbsensi".$row->id."' max='100' value='".$jmlAbsensi."' ></td>";
					echo "<td><input type='number' class='form-control' name='txtTugas[]' id='txtTugas".$row->id."' max='100' value='".$jmlTugas."' ></td>";
					echo "<td><input type='number' class='form-control' name='txtUTP[]' id='txtUTP".$row->id."' max='100' value='".$jmlUTP."' ></td>";
					echo "<td><input type='number' class='form-control' name='txtUAP[]' id='txtUAP".$row->id."' max='100' value='".$jmlUAP."' ></td>";
					
					echo "</tr>";
				}
				
				echo "<tr align='center'>";
				echo "<td colspan='9' ><input type='submit' class='form-control btn btn-primary btn-sm' id='btnSubmit' value='Simpan' ></td>";
				echo "</tr>";
				?>
				
				
			</table>
		</form>
		
		
	</div>
	
</div>