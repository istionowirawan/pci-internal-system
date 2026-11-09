<html>
<head>
<title>Report KHS Temp Web</title>
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

<body id="printableArea" >
<?php
$imgpath = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/logo_icon3.jpg";
$sigmapath = $_SERVER['DOCUMENT_ROOT']. "/pmb/icons/sigma.jpg";

$noAr=0;

//print_r($data_nilai_mahasiswa->result());
$progstudi = "";

if($data_nilai_mahasiswa->num_rows()>0) {
	foreach($data_nilai_mahasiswa->result() as $row) {
		echo "";

		//Logo
		echo "<table border=0 align=center width=100%>";
		
		/*
		echo "
		<tr>
			<td rowspan=6 valign=top ><img src=".$imgpath." width=13 height=17 ></td>
			<td align='l' size=5 margin=5 cellpadding=5 rowspan='6' valign=top >
			BIRO ADMINISTRASI AKADEMIK<br>
			PAHOA COLLEGE INDONESIA<br>
			JL. KI HAJAR DEWANTARA NO.1<BR>
			SUMMARECON SERPONG<BR>
			TELP. 021-54203355, 54210707 (HUNTING)<BR>
			FAKS. 021-54210700
			</td>
			
			<td class='bordertd'  >Bobot</td>
			<td colspan='2' class='bordertd'  >Kualitas</td>
		</tr>";
		*/
		
		echo "
		<tr align='center' >
			<td rowspan=6 valign=top ></td>
			<td align='l' size=5 margin=5 cellpadding=5 rowspan='6' valign=top >
			</td>
			
			<td class='bordertd' width='10' >Bobot</td>
			<td colspan='2' class='bordertd'  >Kualitas</td>
		</tr>";
		
		foreach($data_grade->result() as $gr) {
			echo "
			<tr align='center'>
				<td width='10' class='bordertd'  >".$gr->bobot."</td>
				<td width='8' class='bordertd' style='padding:1px 5px 1px 5px;'  >".$gr->grade."</td>
				<td width='50' class='bordertd' style='padding:1px 5px 1px 5px;'  >".$gr->range_from . "-" . $gr->range_to ."</td>
			</tr>";
		}
		
		/*echo "
		<tr>
		<td colspan=6 ><font style='bold' size='11' >KETERANGAN STUDI PESERTA PROGRAM</font></td>
		</tr>";*/
		
		echo "
		<tr>
		<td colspan=6 ></td>
		</tr>";
		
		echo "</table>";


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
		<td > ".$row->nama_depan ."</td>
		
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
		$progstudi = $row->jurusan;

			//CONTENT
			echo "<table border=0 align=center width=100%>";

			echo "<tr align='center' class='headtr' >
				<td class='bordertd' width='6' >No</td>
				<td class='bordertd' width='20' >Kode Mata Pelajaran</td>
				<td class='bordertd' >Nama Mata Pelajaran</td>
				<td class='bordertd' width='12' >Kredit (sk)</td>
				<td class='bordertd' width='12' >Nilai (huruf)</td>
				<td class='bordertd' width='12' >Nilai (angka)</td>
				<td class='bordertd' width='12' >Bobot Kualitas (skN)</td>
				<td class='bordertd' width='30' >Keterangan</td>
			</tr>";

			$nourut=1;
			$jmlSKN=0;
			$totalSKS=0;
			$bobot=0;
			
			$totNilai=0;

			foreach($data_nilai_temp->result() as $isimapel) {
				
				if($row->nim_mahasiswa == $isimapel->nim_mahasiswa) {
					
					$totNilai = ($isimapel->tugas * ($isimapel->ptugas/100)); 
					$totNilai += ($isimapel->utp * ($isimapel->putp/100)); 
					$totNilai += ($isimapel->uap * ($isimapel->puap/100)); 
					$totNilai += ($isimapel->absensi * ($isimapel->pabsensi/100));
					
					$qGrade = "SELECT grade, range_from, range_to, bobot FROM ms_grade 
					WHERE range_from <= ".$totNilai." AND range_to >= ".$totNilai;
					$queryGrade = $this->db->query($qGrade);
					
					$isiSKN = $isimapel->sks*$queryGrade->row(0)->bobot;
					$jmlSKN += $isiSKN;
					$totalSKS += $isimapel->sks;
					
					echo "<tr align='center' >
						<td class='bordertd' >".$nourut."</td>
						<td class='bordertd' >".$isimapel->kdmk."</td>
						<td class='bordertd' align='left' >( ".$isimapel->nmkmk_ch . " ) " .$isimapel->nmkmk."</td>
						<td class='bordertd' >".$isimapel->sks."</td>
						<td class='bordertd' >".$queryGrade->row(0)->grade."</td>
						<td class='bordertd' >".$queryGrade->row(0)->bobot."</td>
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

			echo "</table>";


		//Footer
		echo "<table border=0 align=center width=100%>";

		echo "<tr align='center'>
			<td class='bordertd' colspan='2' >Hasil Studi Periode Diperoleh</td>
			<td class='bordertd' colspan='3' >Hasil Studi Kumulatif</td>
			<td width='30' ></td>
			<td class='bordertd' rowspan='2' width='100' >Beban Maksimum Semester Berikutnya</td>
			<td ></td>
			<td rowspan='3' width='250' valign='top' >
				Tangerang, ".date("d F Y")."<br>
				Direktur Pahoa College Indonesia<br><br><br><br><br>
				".$direktur_pci."
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
		
		$qNs = "SELECT *, (sks*bobot) as tot_nilai FROM (
SELECT *, 
    (SELECT h.grade FROM ms_grade h WHERE h.range_from <= nilai_akhir AND h.range_to >= nilai_akhir) as grade,
    (SELECT g.bobot FROM ms_grade g WHERE g.range_from <= nilai_akhir AND g.range_to >= nilai_akhir) as bobot 
FROM (
SELECT DISTINCT b.nim_mahasiswa, b.tugas, b.utp, b.uap, b.absensi, a.id_makul, c.kode_makul, 
d.tugas as ptugas, d.utp as puts, d.uap as puap, d.absensi as pabsensi, e.sks,
(
    (b.tugas * (d.tugas/100)) + (b.utp * (d.utp/100)) + (b.uap * (d.uap/100)) + (b.absensi * (d.absensi/100))
) 
as nilai_akhir
FROM trx_pci_nilai a
LEFT JOIN trx_pci_nilai_detail b
ON a.id=b.id_trx
JOIN set_makul_kelas c
ON a.id_makul=c.id_makul
LEFT JOIN set_makul_kelas_persentase d
ON c.id=d.id_trx
LEFT JOIN ms_matakuliah e
ON c.kode_makul=e.kdmk
WHERE nim_mahasiswa='".$row->nim_mahasiswa."'
) as t1
) as t2";
//echo $qNs;
		$queryNs = $this->db->query($qNs);
		
		//$qM = "SELECT a.nim_mahasiswa, a.sks, a.nilai_akhir, a.grade, b.bobot, (sks*bobot) as tot_nilai 
		//FROM trx_pci_nilai_posting a INNER JOIN ms_grade b ON a.grade=b.grade WHERE a.nim_mahasiswa='".$row->nim_mahasiswa."'";
		//$queryM = $this->db->query($qM);
		
		$jmlKreditDiambil=0;
		$jmlKreditDiperoleh=0;
		$totNilai=0;
		$jmlMapel=0;
		$jmlIPK=0;
		foreach($queryNs->result() as $rm) {
			$jmlKreditDiambil+=$rm->sks;
			if($rm->grade=="A" || $rm->grade=="B" || $rm->grade=="C") {
				$jmlKreditDiperoleh+=$rm->sks;
			}
			$totNilai+=$rm->tot_nilai;
			$jmlMapel++;
		}
		$jmlIPK=number_format((float)round($totNilai/$jmlKreditDiambil,2),2,'.','');
		
		
		echo "<tr align='center' >
			<td class='bordertd' >".$totalSKS."</td>
			<td class='bordertd' >".number_format(round($jmlSKN/$totalSKS,1),2)."</td>
			<td class='bordertd' >".$jmlKreditDiambil."</td>
			<td class='bordertd' >".$jmlKreditDiperoleh."</td>
			<td class='bordertd' >".$jmlIPK."</td>
			<td></td>";
		
		
		$smsNext = 0;
		$smsNext = $smsMhs+1;
		$statBebanNext=false;
		foreach($jml_sks_semester->result() as $jsms) {
			if($smsNext==$jsms->semester) {
				echo "<td class='bordertd' >". $jsms->jmlsks ."</td>";
				$statBebanNext=true;
				break;
			}
		}
		if($statBebanNext==false) {
			echo "<td class='bordertd' > - </td>";
		}
		
		//echo "<td>".$jml_sks_semester."</td>";
		echo "</tr>";
		
		
		echo "</table>";
		$noAr++;
		
		if($noAr%2==0) {
			echo "<div style='page-break-after:always;'></div>";
		} else {
			if($progstudi=="Ekonomi dan Perdagangan Internasional") {
				echo "<br><br>";
			} else {
				echo "<br><br><br><br>";
			}
			
		}
	}
}


?>

<script language="javascript">
//printDiv('printableArea');
function printDiv(divName) {
	 var printContents = document.getElementById(divName).innerHTML;
	 var originalContents = document.body.innerHTML;

	 document.body.innerHTML = printContents;

	 window.print();

	 document.body.innerHTML = originalContents;
}
</script>

</body>
</html>