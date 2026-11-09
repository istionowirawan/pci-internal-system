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
		<td>Nama Tionghoa</td>
		<td>Telp Mahasiswa</td>
	</tr>
	
	<?php
	foreach($data_siswa->result() as $obj) {
		echo "<tr>";
		echo "<td>";
			echo $obj->no_formulir;
			echo "<input type='hidden' name='txtNoform[]' id='txtNoform".$obj->no_formulir."' value='".$obj->no_formulir."' >";
		echo "</td>";
		echo "<td>";
			echo $obj->nim . "<input type='hidden' name='txtNim[]' width='100' value='".$obj->nim."' >";
		echo "</td>";
		echo "<td>".$obj->nama_depan ."</td>";
		echo "<td>".$obj->nama_keluarga ."</td>";
		echo "<td><input name='txtNmTio[]' type='text' class='form-control input-sm' value='".$obj->nama_tionghoa."' > </td>";
		echo "<td><input name='txtTelpBaru[]' type='text' class='form-control input-sm' value='".$obj->telp_baru."' > </td>";
		
		echo "</tr>";
	}
	
	echo "<tr>";
	echo "<td colspan='6' ><input id='btnSimpan' type='submit' class='btn btn-primary input-sm form-control' value='Simpan' ></td>";
	echo "</tr>";
	?>
		
</table>

