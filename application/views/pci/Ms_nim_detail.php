<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}

?>

<table class="table table-bordered table-striped" >
	
	<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
		<td width='100' >No Formulir</td>
		<td>Nim </td>
		<td>Nama depan</td>
		<td>Nama Keluarga</td>
	</tr>
	
	<?php
	foreach($data_siswa->result() as $obj) {
		echo "<tr>";
		echo "<td>";
			echo $obj->no_formulir;
			echo "<input type='hidden' name='txtId[]' id='txtId".$obj->id."' value='".$obj->id."' >";
		echo "</td>";
		echo "<td>";
			if($obj->nim!="") {
				echo $obj->nim . "<input type='hidden' name='txtNim[]' class='form-control input-sm' value='".$obj->nim."' >";
			} else {
				echo "<input type='text' name='txtNim[]' class='form-control input-sm' >";
			}
		echo "</td>";
		echo "<td>".$obj->nama_depan ."</td>";
		echo "<td>".$obj->nama_keluarga ."</td>";
		
		echo "</tr>";
	}
	
	echo "<tr>";
	echo "<td colspan='4' ><input id='btnSimpan' type='submit' class='btn btn-primary input-sm form-control' value='Simpan' ></td>";
	echo "</tr>";
	?>
		
</table>

