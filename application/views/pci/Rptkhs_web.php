<html>
<head>
<title>Report KHS Web</title>
<style>
.bordertd {
	outline: thin solid black;
}
table tr td {
	padding:3px;
	font-size:11px;
}
.headtr td {
	font-weight:bold;
}

</style>
</head>

<body>
<?php
$imgpath = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/logo_icon3.jpg";
$sigmapath = $_SERVER['DOCUMENT_ROOT']. "/pmb/icons/sigma.jpg";
$ttdDali = "/pmb/image/ttd_dali.png";

$noAr=0;
//echo $data_nilai_mahasiswa->num_rows();

if($data_nilai_mahasiswa->num_rows()>0) {
	foreach($data_nilai_mahasiswa->result() as $row) {
		echo"<table border='0' align='center' width='100%' style='margin-top:6px;' >";
		echo "<tr>";
		echo "<td></td>";
		//echo "<td class='bordertd' width='200' align='center' height='30px' >FR-PCI-07. Rev.00 ".date('d F Y')."</td>";
		echo "<td class='bordertd' width='200' align='center' height='30px' >FR-PCI-07. Rev.00 01 November 2015</td>";
		echo "</tr>";
		echo "<tr><td></td></tr>";
		echo "</table>";

		echo"<table border='0' align=center width=100%>";
		
		echo"
		<tr align='center'>
			<td rowspan=6 valign=top ></td>
			<td size=5 margin=5 cellpadding=5 rowspan='6' valign=top >
			</td>
			
			<td class='bordertd' width='10'  >Bobot</td>
			<td colspan='2' class='bordertd'  >Kualitas</td>
		</tr>";

		foreach($data_grade->result() as $gr) {
			echo"
			<tr align='center'>
				<td width='10' class='bordertd'  >".$gr->bobot."</td>
				<td width='8' class='bordertd' style='padding:1px 5px 1px 5px;'  >".$gr->grade."</td>
				<td width='50' class='bordertd' style='padding:1px 5px 1px 5px;'  >".$gr->range_from . "-" . $gr->range_to ."</td>
			</tr>";
		}
		
		/*echo"
		<tr>
		<td colspan=6 ><font style='bold' size='11' >KETERANGAN STUDI PESERTA PROGRAM</font></td>
		</tr>";*/
		
		echo"
		<tr>
		<td colspan=6 ></td>
		</tr>";
		
		echo"</table>";


		//HEADER
		$tahunmasuk = substr($row->nim_mahasiswa,3,2);
		$tahunmasuk = $tahunmasuk+2000;
		
		$smsMhs = (((2000 + (int)substr($row->tahun_ajaran,2,2)) - $tahunmasuk) * 2) + 1;
		if($row->semester%2==0) {
			$smsMhs+=1;
		}
		
		if($row->id_jurusan=="10") {
			$tahunmasuk = $tahunmasuk+3;
		} else {
			$tahunmasuk = $tahunmasuk+2;
		}
		$thnmsk = $tahunmasuk . "-" . ($tahunmasuk+1);
		
		echo"
		<table border='0' align=center width=100%>

		<tr>
		<td rowspan='3' width='10' ></td>
		<td width='50'> Nama </td>
		<td width='5' > : </td>
		<td > ".$row->nama_depan . " " . (strlen($row->nama_keluarga)>1?$row->nama_keluarga:'') ."</td>
		
		<td width='120' > Tahun Akademik </td>
		<td width='5' > : </td>
		<td width='100' >".$row->tahun_ajaran."</td>
		</tr>
		
		<tr>
		<td > Nim </td>
		<td > : </td>
		<td > ".$row->nim_mahasiswa."</td>
		
		<td > Periode </td>
		<td > : </td>
		<td >".($row->semester%2==0?'Genap':'Ganjil')."</td>
		</tr>
		
		<tr>
		<td > Jurusan </td>
		<td > : </td>
		<td > ".$row->jurusan."</td>
		
		<td > Masa Studi S/D</td>
		<td > : </td>
		<td >".$thnmsk."</td>
		</tr>
		
		
		</table>
		";
			

			//CONTENT
			echo "<table border='0' align=center width=100%>";

			echo "<tr align='center' class='headtr' >
				<td class='bordertd' width='25' >No</td>
				<td class='bordertd' width='70' >Kode Mata Pelajaran</td>
				<td class='bordertd' >Nama Mata Pelajaran</td>
				<td class='bordertd' width='55' >Kredit (sk)</td>
				<td class='bordertd' width='55' >Nilai (huruf)</td>
				<td class='bordertd' width='55' >Nilai (angka)</td>
				<td class='bordertd' width='55' >Bobot Kualitas (skN)</td>
				<td class='bordertd' width='50' >Keterangan</td>
			</tr>";

			$nourut=1;
			$jmlSKN=0;
			$totalSKS=0;
			$bobot=0;


			foreach($data_nilai_posting->result() as $isimapel) {
				
				if($row->nim_mahasiswa == $isimapel->nim_mahasiswa) {
					$isiSKN = $isimapel->sks*$isimapel->bobot;
					$jmlSKN += $isiSKN;
					$totalSKS += $isimapel->sks;
					
					echo "<tr align='center' >
						<td class='bordertd' >".$nourut."</td>
						<td class='bordertd' >".$isimapel->kdmk."</td>
						<td class='bordertd' align='left' >( ".$isimapel->nmkmk_ch . " ) " . $isimapel->nmkmk."</td>
						<td class='bordertd' >".$isimapel->sks."</td>
						<td class='bordertd' >".$isimapel->grade."</td>
						<td class='bordertd' >".$isimapel->bobot."</td>
						<td class='bordertd' >".$isiSKN."</td>
						<td class='bordertd' >	</td>
					</tr>";
					
					$nourut++;
				}
			}

			echo "<tr align='center'>
				<td ></td>
				<td ></td>
				<td ></td>
				<td class='bordertd' >".$totalSKS."</td>
				<td ></td>
				<td ></td>
				<td class='bordertd' >".$jmlSKN."</td>
				<td ></td>
			</tr>";
		
			echo "<tr><td></td></tr>";

			echo"</table>";


		//Footer
		echo "<table border=0 align=center width=100%>";

		echo "<tr align='center'>
			<td class='bordertd' colspan='2' >Hasil Studi Periode Diperoleh</td>
			<td class='bordertd' colspan='3' >Hasil Studi Kumulatif</td>
			<td width='30' ></td>
			<td class='bordertd' rowspan='2' width='130' >Beban Maksimum Semester Berikutnya</td>
			<td ></td>
			<td rowspan='3' width='250' valign='top' >
				Tangerang, ".date("d F Y")."<br>
				Direktur Pahoa College Indonesia
				<img src='".$ttdDali."' alt='".$ttdDali."' title='".$ttdDali."' width='100' height='100' style='position:absolute;margin-left:-120px;margin-top:-20px;' ><br>
				<br><br><br><br>".$direktur_pci."
			</td>
		</tr>";
		
		//$reportSubtitle = iconv('UTF-8', 'windows-1252', $reportSubtitle);
		echo "<tr align='center'>
			<td class='bordertd' width='70' >sk</td>
			<td class='bordertd' width='70' >Ips</td>
			<td class='bordertd' width='110' >Kredit Diambil</td>
			<td class='bordertd' width='110' >Kredit Diperoleh</td>
			<td class='bordertd' width='110' >Ipk</td>
			<td ></td>
		</tr>";
		
		$qM = "SELECT a.nim_mahasiswa, a.sks, a.nilai_akhir, a.grade, b.bobot, (sks*bobot) as tot_nilai 
		FROM trx_pci_nilai_posting a INNER JOIN ms_grade b ON a.grade=b.grade WHERE a.nim_mahasiswa='".$row->nim_mahasiswa."'";
		$queryM = $this->db->query($qM);
		
		$jmlKreditDiambil=0;
		$jmlKreditDiperoleh=0;
		$totNilai=0;
		$jmlMapel=0;
		$jmlIPK=0;
		foreach($queryM->result() as $rm) {
			$jmlKreditDiambil+=$rm->sks;
			if($rm->grade=="A" || $rm->grade=="B" || $rm->grade=="C") {
				$jmlKreditDiperoleh+=$rm->sks;
			}
			$totNilai+=$rm->tot_nilai;
			$jmlMapel++;
		}
		//$jmlIPK=number_format(round($totNilai/$jmlKreditDiambil),2);
		$jmlIPK=number_format((float)round($totNilai/$jmlKreditDiambil,2),2,'.','');
		
		echo "<tr align='center'>
			<td class='bordertd' >".$totalSKS."</td>
			<td class='bordertd' >".number_format(round($jmlSKN/$totalSKS,2),2)."</td>
			<td class='bordertd' >".$jmlKreditDiambil."</td>
			<td class='bordertd' >".$jmlKreditDiperoleh."</td>
			<td class='bordertd' >".$jmlIPK."</td>
			<td></td>";
		
		$smsNext = 0;
		$smsNext = $smsMhs+1;
		$statBebanNext=false;
		
		if($smsNext==2 && $row->jurusan=="Pendidikan Guru Bahasa Mandarin") {
			echo "<td class='bordertd' >18</td>"; 			//Jumlah SKS hardcode, permintaan lovenia tanggal 18 Jan 2017
			$statBebanNext=true;
		} else {
			foreach($jml_sks_semester->result() as $jsms) {
				if($smsNext==$jsms->semester) {
					echo "<td class='bordertd' >". $jsms->jmlsks ."</td>";
					$statBebanNext=true;
					break;
				}
			}
		}
		if($statBebanNext==false) {
			echo "<td class='bordertd' > - </td>";
		}
		
		echo "</tr>";
		echo"</table>";
		$noAr++;
		
		if($noAr%2==0) {
			echo "<div style='page-break-after:always;'></div>";
		} else {
			//echo "==>".$nourut;
			if($nourut > 7) {
				echo "<br>";
			} else if($nourut > 6) {
				echo "<br><br>";
			} else if($nourut > 5) {
				echo "<br><br><br>";
			} else if($nourut > 4) {
				echo "<br><br><br><br>";
			} else if($nourut > 3) {
				echo "<br><br><br><br><br>";
			} else {
				echo "<br><br><br><br><br>";
			}
			
		}
	}
	
	
}
?>

</body>
</html>