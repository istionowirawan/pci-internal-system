<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}

//print_r($rekap_jualform->result());
//echo $infoRpt;
?>

<h5 align='left' ><u>Rekap Penjualan Formulir</u></h5>

<?php
echo "<h6 align='left' >Harga Formulir : Rp. ". number_format($biaya_form->row(0)->biaya) ."</h6>";

if($infoRpt=="detail") {
	echo "<h6 align='left' >Jumlah Terjual : ". number_format($detail_jualform->num_rows()) . " buah</h6>";
	$jml = $detail_jualform->num_rows() * $biaya_form->row(0)->biaya;
	echo "<h6 align='left' >Total Pendapatan : Rp.". number_format($jml) ."</h6>";
} else {
	$jmlTerjual=0;
	foreach($rekap_jualform->result() as $row) {
		$jmlTerjual+=$row->jmldata;
	}
	
	echo "<h6 align='left' >Jumlah Terjual : ". number_format($jmlTerjual) . " buah</h6>";
	$jml = $jmlTerjual * $biaya_form->row(0)->biaya;
	echo "<h6 align='left' >Total Pendapatan : Rp.". number_format($jml) ."</h6>";
}
?>

<table class="table table-bordered table-striped" >
	
	<?php
	$nourut=1;
	if($infoRpt=="detail") {
		echo "<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
			<td>No.</td>
			<td>Fakultas</td>
			<td>Jurusan</td>
			<td>Tanggal Beli</td>
			<td>No Formulir</td>
		</tr>";
	
		foreach($detail_jualform->result() as $row) {
			echo "<tr>";
			echo "<td>".$nourut."</td>";
			echo "<td>".$row->fakultas."</td>";
			echo "<td>".$row->jurusan."</td>";
			echo "<td>".$row->tanggal_beli."</td>";
			echo "<td>".$row->no_formulir."</td>";
			echo "</tr>";
			$nourut++;
		}
	} else {
		echo "<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
			<td>No.</td>
			<td>Fakultas</td>
			<td>Jurusan</td>
			<td>Jumlah Terjual</td>
		</tr>";
		
		foreach($rekap_jualform->result() as $row) {
			echo "<tr>";
			echo "<td>".$nourut."</td>";
			echo "<td>".$row->fakultas."</td>";
			echo "<td>".$row->jurusan."</td>";
			echo "<td>".$row->jmldata."</td>";
			echo "</tr>";
			$nourut++;
		}
	}
	
	?>
	
	
	
</table>