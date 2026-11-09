<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>
		
<form action="" method="POST" name="frmRptNilaiDetail" id="frmTrxNilaiDetail" style='display:none;' >
	<table class="table table-bordered" >
		<tr align='center' class='header-popup-tbl'  >
			<td width='80px' style='border-color: black;' >Nip Peserta Didik</td>
			<td style='border-color: black;'>Nama Peserta Didik</td>
			<td width='60px' style='border-color: black;'>Tugas</td>
			<td width='60px' style='border-color: black;'>UTP</td>
			<td width='60px' style='border-color: black;'>UAP</td>
			<td width='60px' style='border-color: black;'>Absensi</td>
			<td width='60px' style='border-color: black;'>Nilai Akhir</td>
			<td width='60px' style='border-color: black;'>Grade</td>
		</tr>
		
		<?php
		$persen=0;
		$urut=0;
		foreach($datasiswa->result() as $obj) {
			
			$isiTugas="";
			$isiUTP="";
			$isiUAP="";
			$isiAbsenMhs="";
			$isiNA=0;
			$isiGrade="";
			
			foreach($isi_nilai->result() as $nil) {
				if($nil->nim_mahasiswa == $obj->nim_mhs) {
					$isiTugas = $nil->nilai_tugas;
					$isiUTP = $nil->nilai_utp;
					$isiUAP = $nil->nilai_uap;
					$isiAbsenMhs = $nil->nilai_absensi;
					$isiNA = $nil->nilai_akhir;
					$isiGrade = $nil->grade;
					break;
				}
			}
			
			if($urut%2==0) {
				echo "<tr>";
			} else {
				echo "<tr class='active'>";
			}
			$urut++;
			
			echo "<td align='center' style='border-color: black;' >".$obj->nim_mhs."<input type='hidden' name='txtNim[]' id='txtNim".$obj->nim_mhs."' value='".$obj->nim_mhs."' ></td>";
			echo "<td align='left' style='border-color: black;' >".$obj->nama_depan . " " . (strlen($obj->nama_keluarga)>1?$obj->nama_keluarga:'')."</td>";
			
			echo "<td align='center' style='border-color: black;' >".$isiTugas."</td>";			
			echo "<td align='center' style='border-color: black;'>".$isiUTP."</td>";
			echo "<td align='center' style='border-color: black;'>".$isiUAP."</td>";
			echo "<td align='center' style='border-color: black;'>".$isiAbsenMhs."</td>";
			echo "<td align='center' style='border-color: black;'>".$isiNA."</td>";
			echo "<td align='center' style='border-color: black;'>".$isiGrade."</td>";
			
			echo "</tr>";
			
		}
		
		echo "<input type='hidden' value='".$trx_nilai_id."' name='txtTrxId' id='txtTrxId' >";
		
		?>
	</table>
</form>