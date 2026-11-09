<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}

?>

<table class="table table-bordered table-striped" >
	
	<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
		<td width='100' >No Formulir</td>
		<td>Nama </td>
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
		
		echo "</tr>";
	}
	
	?>
	
	
	
</table>
