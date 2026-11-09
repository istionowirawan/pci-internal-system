<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<table class="table table-bordered table-striped" >
	
	<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
		<td>No Formulir</td>
		<td>Nama </td>
		
		<?php
		foreach($makulusm->result() as $mk) {
			echo "<td width='150' >".$mk->nama_makul."</td>";
		}
		?>
	</tr>
	
	<?php
	foreach($makulusm->result() as $mk2) {
		echo "<input type='hidden' name='txtKodeMakul[]' id='txtKodeMakul".$mk2->id_set_usmmakul."' value='".$mk2->id_set_usmmakul."' >";
	}
	foreach($datasiswa->result() as $obj) {
		echo "<tr>";
		echo "<td>";
			echo $obj->no_formulir;
			echo "<input type='hidden' name='txtNoForm[]' id='txtNoForm".$obj->no_formulir."' value='".$obj->no_formulir."' >";
		echo "</td>";
		echo "<td>".$obj->nama_depan ."</td>";
		
		foreach($makulusm->result() as $mk2) {
			echo "<td>";
			echo "<input type='hidden' name='txtNosetmakul_".$mk2->id_set_usmmakul."[]' id='txtNosetmakul_".$mk2->id_set_usmmakul."' value='".$mk2->no_makul."' >";
			
			$isiNilai = "";
			foreach($data_usm->result() as $isi) {
				if($isi->no_form==$obj->no_formulir) {
					//echo $isi->id_set_usm_makul . "==" . $mk2->id_set_usmmakul . "<br>";
					if($isi->id_set_usm_makul==$mk2->id_set_usmmakul) {
						$isiNilai = $isi->nilai_makul;
						break;
					}
				}
			}
			echo "<input type='number' name='txtNilai_".$mk2->id_set_usmmakul."[]' id='txtNilai_".$mk2->id_set_usmmakul."' class='form-control input-sm'  max='1000' min='0' value='".$isiNilai."'  >";
			echo "</td>";
			//echo "<td>".$namaTxt."</td>";
		}
	
		echo "</tr>";
	}
	
	$jmlmk = $makulusm->num_rows() +2;
	echo "<tr>
		<td align='center' colspan='".$jmlmk."' >
			<button id='btnSimpanNilaiUSM' class='btn btn-warning form-control' >
			 <span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'> Simpan</span>
			</button>
		</td>
	</tr>";
	
	?>
	
	
	
</table>
