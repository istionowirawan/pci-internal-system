<head>
<title>Cetak kwitansi</title>
<link rel="stylesheet" type="text/css" href="../../css/wira-style.css">
<style>
body {
	color: #000000;
	background-color:#ffffff;
}
.body_kwitansi {
	font-size:14px;
}
.body_kwitansi td {
	padding-top:5px;
	padding-bottom:5px;
}
.footer_kwitansi {
	margin-top:30px;
	font-size:14px;
}
</style>
</head>

<script>
window.print();
</script>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}

function Terbilang($satuan)
{
	$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	if ($satuan < 12)
	return " " . $huruf[$satuan];
	elseif ($satuan < 20)
	return Terbilang($satuan - 10) . "Belas";
	elseif ($satuan < 100)
	return Terbilang($satuan / 10) . " Puluh" . Terbilang($satuan % 10);
	elseif ($satuan < 200)
	return " Seratus" . Terbilang($satuan - 100);
	elseif ($satuan < 1000)
	return Terbilang($satuan / 100) . " Ratus" . Terbilang($satuan % 100);
	elseif ($satuan < 2000)
	return " seribu" . Terbilang($satuan - 1000);
	elseif ($satuan < 1000000)
	return Terbilang($satuan / 1000) . " Ribu" . Terbilang($satuan % 1000);
	elseif ($satuan < 1000000000)
	return Terbilang($satuan / 1000000) . " Juta" . Terbilang($satuan % 1000000);
	elseif ($satuan <= 1000000000)
	echo "Maaf Tidak Dapat di Prose Karena Jumlah Uang Terlalu Besar ";
}
?>

<body>
<table border='0' rules='' width='100%' >
	<tr>
		<td>
			<h2 style='margin:0px;'>PAHOA COLLEGE INDONESIA</h2>
			<div><hr></div>
		</td>
	</tr>
	
	<tr>
		<td style='font-size:14px;width:360px;' >
			Jl. Ki Hajar Dewantara No. 1, Summarecon Serpong<br>
			Tangerang 15810 Banten<br>
			Telp. +62-21-5420 3355/5421 0707<br>
			Faks. +6-21-5421 0700
		</td>
		
		<td ></td>
		
		<td style='vertical-align:top;' width='250px' >
			No. Formulir
			<div style='border-style: solid;border-width: 1px;padding:5px' >
			<?php echo $data_trx_questioner->row(0)->no_formulir; ?>
			</div>
		</td>
	</tr>
	
	<tr>
	<td colspan='3' align='center' style='padding-top:10px;' >
		<h1 style='margin:0px;'>KWITANSI</h1>
	</td>
	</tr>
	
	<tr>
	<td colspan='3' align='center' style='vertical-align:top;padding:0px;' >
		<hr style='height:4px;background-color:#000000;'>
	</td>
	</tr>
</table>


<table border='0' width='100%' class='body_kwitansi' >
	<tr>
		<td width='300px;' >Telah terima dari</td>
		<td width='15px;'> : </td>
		<td style='border-style: none none dotted none;' ><?php echo $data_trx_questioner->row(0)->nama_calon; ?></td>
	</tr>
	
	<tr>
		<td></td>
		<td></td>
		<td style='border-style: none none dotted none;' ><br></td>
	</tr>
	
	<tr>
		<td>Terbilang</td>
		<td> : </td>
		<td><?php echo Terbilang($data_biaya_form->row(0)->biaya); ?> Rupiah</td>
	</tr>
	
	<tr>
		<td>Untuk Pembayaran</td>
		<td> : </td>
		<td>Formulir Tahun Akademik <?php echo $data_trx_questioner->row(0)->tahun_ajaran; ?></td>
	</tr>
</table>



<table border='0' width='100%' class='footer_kwitansi' >
	<tr>
		<td style='width:360px;' ></td>
		<td ></td>
		<td width='250px' >
			Tangerang, <?php echo date("d F Y"); ?>
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td></td>
		<td>
			Diterima oleh,
		</td>
	</tr>
	
	<tr>
		<td height='80px' style='font-weight:bold;padding-left:15px;' >
			Jumlah
			<span style='border-style: solid;border-width:1px;padding:10px;padding-right:100px;margin-left:10px;' > Rp. 
			<?php echo number_format($data_biaya_form->row(0)->biaya); ?>
			</span>
		</td>
		<td></td>
		<td></td>
	</tr>
	
	<tr>
		<td></td>
		<td></td>
		<td style='text-align:center;' >
			<span style='float:left;' >(</span>
			<span><?php echo $this->session->userdata('pmb_nama'); ?></span>
			<span style='float:right;' >)</span>
		</td>
	</tr>
</table>



</body>