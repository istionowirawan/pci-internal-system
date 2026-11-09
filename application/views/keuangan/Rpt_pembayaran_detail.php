<script>
$(document).ready(function() {
	$( function() {
		$(".clsTglByr").datepicker({
			dateFormat: "yy-mm-dd"
		});
	});
	
	$(".clsTxtBayar").blur(function() {
		var idtrx = $(this).attr("alt");
		
		var sisa = $("#txtTotTagihan"+idtrx).val() - $(this).val();
		$("#lblSisa"+idtrx).text(sisa);
		
		if(sisa==0) {
			$("#rowAwal"+idtrx).css("background-color","");
			$("#lblStatLunas"+idtrx).text("Lunas");
		} else {
			$("#rowAwal"+idtrx).css("background-color","#f9d6d6");
			$("#lblStatLunas"+idtrx).text("Blm");
		}
	});
	
	$(".clsTxtBayar").dblclick(function() {
		var idtrx = $(this).attr("alt");
		var tagihan = $("#txtTotTagihan"+idtrx).val();
		$(this).val(tagihan);
	});
	
	$(".clsTglByr").dblclick(function() {
		var idtrx = $(this).attr("alt");
		
		var d = new Date();
		var month = d.getMonth()+1;
		var day = d.getDate();
		
		month = (month<10 ? '0' : '') + month;
		day = (day<10 ? '0' : '') + day;
		
		var tglsekarang = d.getFullYear() + "-" + month + "-" + day;
		$(this).val(tglsekarang);
	});	
	
	$('#tblPembayaranDetail').DataTable({
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Laporan_Pembayaran'
            },
            {
                extend: 'pdfHtml5',
                title: 'Laporan Pembayaran Pahoa College Indonesia',
				orientation: 'landscape',
                pageSize: 'A4'
            },
			{
                extend: 'print',
                title:'Laporan Pembayaran Pahoa College Indonesia'
            }
        ]
	});
});
</script>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}

?>

<table class="table table-bordered table-striped" id='tblPembayaranDetail' >
	<thead>
	<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
		<td width='70' >No Formulir</td>
		<td>Nama </td>
		<td width='50' >Jenis Bayar</td>
		<td width='90' >Uang Pangkal</td>
		<td width='90' >Uang Semster</td>
		<td width='90' >Tot.Tagihan</td>
		<td width='90' >Jatuh Tempo</td>
		<td>Bayar</td>
		<td>No.Trx</td>
		<td >Tgl.Byr</td>
		<td width='50'>Stat Lunas</td>
		<td>Sisa</td>
	</tr>
	</thead>
	
	<tbody>
	<?php
	foreach($datasiswa->result() as $obj) {
		$bayar = "";
		$notrx = "";
		$tglbyr = "";
		foreach($data_pembayaran->result() as $brs) {
			if($brs->id_trx_bio_byr==$obj->id) {
				$bayar = $brs->bayar;
				$notrx = $brs->no_trx;
				$tglbyr = $brs->tgl_bayar;
				break;
			}
		}
		
		if($tglbyr=="0000-00-00") {
			$tglbyr="";
		}
		
		if($bayar>=$obj->detail_pembayaran) {
			echo "<tr alt='".$obj->id."' id='rowAwal".$obj->id."' class='rowAwal' >";
		} else {
			echo "<tr style='background-color:#f9d6d6;' alt='".$obj->id."' id='rowAwal".$obj->id."' class='rowAwal' >";
		}
		
		echo "<td>";
			echo $obj->no_formulir;
			echo "<input type='hidden' name='txtIdTrx[]' id='txtIdTrx".$obj->id."' value='".$obj->id."' >";
			echo "<input type='hidden' name='txtNoForm[]' id='txtNoForm".$obj->id."' value='".$obj->no_formulir."' >";
		echo "</td>";
		echo "<td>".$obj->nama_depan ."</td>";
		echo "<td>".$obj->jenis_bayar."</td>";
		echo "<td>".number_format($obj->uang_pangkal)."</td>";
		echo "<td>".number_format($obj->uang_semester)."</td>";
		echo "<td>";
			echo number_format($obj->detail_pembayaran);
			echo "<input type='hidden' class='txtTotTagihan' alt='".$obj->id."' id='txtTotTagihan".$obj->id."' value='".$obj->detail_pembayaran."' >";
		echo "</td>";
		echo "<td>".$obj->jatuh_tempo."</td>";
		
		
		echo "<td>".$bayar."</td>";
		echo "<td>".$notrx."</td>";
		echo "<td>".$tglbyr."</td>";
		
		//echo "<td><input type='number' name='txtBayar[]' value='".$bayar."' class='form-control input-sm clsTxtBayar' alt='".$obj->id."' value='".$bayar."' title='double klik untuk menulis otomatis pembayaran sesuai tagihan' > </td>";
		//echo "<td><input type='text' name='txtNoTrx[]' value='".$notrx."' class='form-control input-sm' > </td>";
		//echo "<td><input type='text' name='txtTglByr[]' value='".$tglbyr."' class='form-control input-sm clsTglByr' alt='".$obj->id."' style='width:100px;' title='double klik untuk menulis tanggal hari ini otomatis' > </td>";
		
		echo "<td>";
			echo "<label class='lblStatLunas' id='lblStatLunas".$obj->id."' alt='".$obj->id."' >";
			if($bayar>=$obj->detail_pembayaran) {
				echo "Lunas";
			} else {
				echo "Blm";
			}
			echo "</label>";
		echo "</td>";
		
		echo "<td>";
			echo "<label class='lblSisa' id='lblSisa".$obj->id."' alt='".$obj->id."' >";
			echo number_format($obj->detail_pembayaran-$bayar);
			echo "</label>";
		echo "</td>";
		
		echo "</td>";
		
		echo "</tr>";
	}
	
	?>
	</tbody>
	
</table>
