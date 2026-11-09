<script>
	$(document).ready(function() {
		
		
	});
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<table class="table" id='tblKelas' >
	<tr>
	<td align='right' width='15%' >Jumlah Pertemuan :</td>
	<td><?php echo $jml_pertemuan_makul; ?></td>
	</tr>
</table>

<form action="" method="POST" name="frmAbsensiDetail" id="frmAbsensiDetail" style='display:none;' >
	<table class="table table-bordered table-striped" >
		
		<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
			<td>Nip Peserta Didik</td>
			<td>Nama Peserta Didik</td>
			<td width='100px'>Sakit</td>
			<td width='100px'>Ijin</td>
			<td width='100px'>Alpha</td>
			<td width='100px'>Telat</td>
			<td width='100px'>Hadir</td>
			<td width='100px'>Kehadiran (%)</td>
		</tr>
		
		<?php
		$hitungTdkMasuk=0;
		$persentaseHadir=0;
		foreach($datasiswa->result() as $obj) {
			$hitungTdkMasuk=0;
			$persentaseHadir=0;
			
			echo "<tr>";
			echo "<td align='center' >".$obj->nim_mhs."<input type='hidden' name='txtNim[]' id='txtNim".$obj->nim_mhs."' value='".$obj->nim_mhs."' ></td>";
			echo "<td align='center' >".$obj->nama_depan."</td>";
			
			$isiAbsenSakit="";
			$isiAbsenIjin="";
			$isiAbsenAlpha="";
			$isiAbsenTelat="";
			foreach($isi_absensi->result() as $abs) {
				if($abs->nim_mahasiswa == $obj->nim_mhs) {
					$isiAbsenSakit = $abs->sakit;
					$isiAbsenIjin = $abs->ijin;
					$isiAbsenAlpha = $abs->alpha;
					$isiAbsenTelat = $abs->telat;
					break;
				}
			}
			
			echo "<td><input type='text' class='form-control' name='txtSakit[]' id='txtSakit".$obj->nim_mhs."' value='".$isiAbsenSakit."' ></td>";
			echo "<td><input type='text' class='form-control' name='txtIjin[]' id='txtIjin".$obj->nim_mhs."' value='".$isiAbsenIjin."' ></td>";
			echo "<td><input type='text' class='form-control' name='txtAlpha[]' id='txtAlpha".$obj->nim_mhs."' value='".$isiAbsenAlpha."' ></td>";
			echo "<td><input type='text' class='form-control' name='txtTelat[]' id='txtTelat".$obj->nim_mhs."' value='".$isiAbsenTelat."' ></td>";
			
			$hitungTdkMasuk+= $isiAbsenSakit;
			$hitungTdkMasuk+= $isiAbsenIjin;
			$hitungTdkMasuk+= $isiAbsenAlpha;
			
			echo "<td align='center'>".($jml_pertemuan_makul-$hitungTdkMasuk)." / ".$jml_pertemuan_makul."</td>";
			
			if($jml_pertemuan_makul==0) {
				$persentaseHadir = 0;
			} else {
				$persentaseHadir = round((($jml_pertemuan_makul-$hitungTdkMasuk)/$jml_pertemuan_makul)*100,0);
			}
			echo "<td align='center'>".$persentaseHadir."%</td>";
			echo "</tr>";
		}
		
		if($jml_pertemuan_makul>0) {
			echo "<tr align='center'>";
			echo "<td colspan='8' ><input type='submit' class='form-control btn btn-danger btn-sm' id='btnSubmit' value='Simpan' ></td>";
			echo "</tr>";
		} else {
			echo "<tr align='center'>";
			echo "<td colspan='8' ><div class='alert alert-danger' role='alert'>Persentase Absensi untuk Mapel ini belum di setting</div></td>";
			echo "</tr>";
		}
		?>
	</table>
</form>