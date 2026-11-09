<?php
function rubah_tanggal($originalDate) {
	$newDate = $originalDate;
	
	$tgl = date("d", strtotime($originalDate));
	$bln = nama_bulan(date("m", strtotime($originalDate)));
	$thn = date("Y", strtotime($originalDate));
	
	//setlocale(LC_ALL, 'id_ID');
	$newDate = $tgl . " " . $bln . " " . $thn;
	//$newDate = strftime("%d %B %Y",strtotime($originalDate));
	return $newDate;
}

function nama_bulan($bln) {
	$namabln = "";
	if($bln==1) {
		$namabln = "Januari";
	} else if ($bln==2) {
		$namabln = "Februari";
	} else if ($bln==3) {
		$namabln = "Maret";
	} else if ($bln==4) {
		$namabln = "April";
	} else if ($bln==5) {
		$namabln = "Mei";
	} else if ($bln==6) {
		$namabln = "Juni";
	} else if ($bln==7) {
		$namabln = "Juli";
	} else if ($bln==8) {
		$namabln = "Agustus";
	} else if ($bln==9) {
		$namabln = "September";
	} else if ($bln==10) {
		$namabln = "Oktober";
	} else if ($bln==11) {
		$namabln = "November";
	} else if ($bln==12) {
		$namabln = "Desember";
	}
	return $namabln;
}

echo "<h5><u>SURAT PERNYATAAN</u></h5>";

echo "<table border='0' class='table borderless' >";
	echo "<tr>";
	echo "<td colspan='3' align='right' >No.Form : " . $noform . "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td colspan='3' align='left' ><u>Data Siswa</u></td>";
	echo "</tr>";
	
	foreach($data_srt_pernyataan->result() as $row) {
		echo "<tr align='left' >";
		echo "<td width='200' >Nama Siswa</td>";
		echo "<td width='10' >:</td>";
		echo "<td >".$row->nama_depan . "" . (strlen($row->nama_keluarga)>1?$row->nama_keluarga:'') ."</td>";
		echo "</tr>";
		
		echo "<tr align='left' >";
		echo "<td >Alamat</td>";
		echo "<td >:</td>";
		echo "<td >".$row->alamat ."</td>";
		echo "</tr>";
	}
	
	foreach($data_ayah_ibu->result() as $ortu) {
		echo "<tr align='left' >";
		
		if($ortu->hubungan=='Ayah') {
			echo "<td >Nama Ayah</td>";
		} else {
			echo "<td >Nama Ibu</td>";
		}
		
		echo "<td >:</td>";
		echo "<td >".$ortu->nama ."</td>";
		echo "</tr>";		
	}
echo "</table>";


echo "<table border='0' class='table borderless' >";
	echo "<tr>";
	echo "<td colspan='4' align='left' ><br><u>Diterima di PAHOA COLLEGE INDONESIA</u></td>";
	echo "</tr>";
	
	foreach($data_srt_pernyataan->result() as $row2) {
		echo "<tr align='left' >";
		echo "<td width='200' >Fakultas</td>";
		echo "<td width='10' >:</td>";
		echo "<td colspan='2' >".$row2->fakultas ."</td>";
		echo "</tr>";
		
		echo "<tr align='left' >";
		echo "<td >Jurusan</td>";
		echo "<td >:</td>";
		echo "<td colspan='2' >".$row2->jurusan ."</td>";
		echo "</tr>";
		
		echo "<tr align='left' >";
		echo "<td >Tahun Ajaran</td>";
		echo "<td >:</td>";
		echo "<td colspan='2' >". $row2->tahun_ajaran ."</td>";
		echo "</tr>";
	}
	
	$tampilAwal=false;
	$totalTagihan = 0;
	$statTampilTotal = false;
	foreach($data_pembayaran->result() as $byr) {
		if($tampilAwal==false) {
			echo "<tr align='left' >";
			echo "<td >Uang Pangkal</td>";
			echo "<td >:</td>";
			echo "<td colspan='2' >Rp. ".number_format($byr->uang_pangkal) ."</td>";
			echo "</tr>";
			
			echo "<tr align='left' >";
			echo "<td >Uang Semester</td>";
			echo "<td >:</td>";
			echo "<td colspan='2' >Rp. ".number_format($byr->uang_semester) ."</td>";
			echo "</tr>";
			
			$totalTagihan = $byr->uang_pangkal+$byr->uang_semester;
			
			echo "<tr align='left' >";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td width='140' style='vertical-align:middle;margin:0px;' ><hr size='30' style='margin-top:0px;margin-bottom:0px;border: 2px solid #000;' ></td>";
			echo "<td>(+)</td>";
			echo "</tr>";
			$tampilAwal=true;
			
			echo "<tr align='left' style='font-size:13px;' >";
			echo "<td>Total Tagihan</td>";
			echo "<td>:</td>";
				echo "<td>Rp. ".number_format($totalTagihan) . "</td>";
				if($byr->jenis_bayar=="Cash") {
					echo "<td> (" . rubah_tanggal($byr->jatuh_tempo) . ")</td>";
				}
			echo "</tr>";
			$statTampilTotal=true;
		}
		
		
		if($byr->jenis_bayar=="Kredit") {
			echo "<tr align='left' style='font-weight:normal;' >";
			echo "<td>Kredit ".$byr->nomor_pembayaran."</td>";
			echo "<td>:</td>";
			echo "<td>Rp. ";
				if($byr->nomor_pembayaran==1) {
					$totCicil1 = $byr->detail_pembayaran;
					echo number_format($totCicil1);
				} else {
					echo number_format($byr->detail_pembayaran);
				}
			echo "</td>";
			echo "<td> (" . rubah_tanggal($byr->jatuh_tempo) . ")</td>";
			echo "</tr>";
		}
		
	}
	
echo "</table>";


echo "<table border='0' class='table borderless' >";
	echo "<tr align='left' >";
	echo "<td colspan='2' >
	Catatan : <br>
	<ul>
		<li>Bila kewajiban administrasi keuangan tidak dibayar penuh atau tidak dibayar sampai batas akhir waktu yang ditentukan, maka kami menyatakan <b>mengundurkan diri.</b></li>
	</ul>
	
	Pembayaran melalui : <br>
	<ul>
		<li>Rekening Bank Permata dengan nomor <b>0902375775</b> a.n <b>Yayasan Pendidikan dan Pengajaran Pahoa</b></li>
		
	</ul>
	</td>";
	echo "</tr>";
	
	$tgl = date("d");
	$bln = nama_bulan(date("m"));
	$thn = date("Y");
	$tglCetak = $tgl . " " . $bln . " " . $thn;
	
	echo "<tr align='left' >";
	echo "<td></td>";
	echo "<td width='230' >Tangerang, ".$tglCetak."</td>";
	echo "</tr>";
	
	echo "<tr align='left' >";
	echo "<td></td>";
	echo "<td>Yang menyatakan kesanggupan,</td>";
	echo "</tr>";
	
	echo "<tr align='center' >";
	echo "<td></td>";
	echo "<td>
	<br><br><br><br>
	<hr style='margin:0px;border: 2px solid #000;' >
	( <span style='white-space:pre;'>                                                    </span> )
	</td>";
	echo "</tr>";
	
echo "</table>";


?>