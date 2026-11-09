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
$imgpath = "/pmb/image/logo_icon3.jpg";
$sigmapath = $_SERVER['DOCUMENT_ROOT']. "/pmb/icons/sigma.jpg";
$ttdDali = "/pmb/image/ttd_dali.png";

$noAr=0;

//张献荣教授
if($data_krs_mahasiswa->num_rows()>0) {
	foreach($data_krs_mahasiswa->result() as $row) {
		
		echo "";	
				
		//Logo
		echo "
		<table border='0' align=center width=100%>
		<tr>
			<td valign=top rowspan='2' width='70' ><img src=".$imgpath." width='60' height='80' ></td>
			<td align='l' size=5 margin=5 cellpadding=5  valign=top rowspan='2' >
			BIRO ADMINISTRASI AKADEMIK<br>
			PAHOA COLLEGE INDONESIA<br>
			JL. KI HAJAR DEWANTARA NO.1<BR>
			SUMMARECON SERPONG<BR>
			TELP. 021-54203355, 54210707 (HUNTING)<BR>
			FAKS. 021-54210700
			</td>
			
			<td width=200 align='center' class='bordertd' height='30px' >FR-PCI-07. Rev.00 ".date('d F Y')."</td>
		</tr>
		";
		
		echo "
		<tr>
		<td></td>
		</tr>";
		
		echo "
		<tr>
		<td colspan='3' align='center' ><font size='5' ><b>KETERANGAN STUDI PESERTA PROGRAM</b></font></td>
		</tr>
		</table>
		";

		//HEADER
		echo "
		<table border='0' align='center' width='100%' >

			<tr>
				<td width='5'></td>
				<td width='120' >Nama</td>
				<td width='5' >:</td>
				<td width='100' >".$row->nama_depan . " " . (strlen($row->nama_keluarga)>1?$row->nama_keluarga:'')."</td>
				
				<td ></td>
				<td width='120' > Tahun Akademik </td>
				<td width='5' > : </td>
				<td width='100' >".$row->tahun_ajaran."</td>
			</tr>
			
			<tr>
				<td ></td>
				<td >Nomor Induk Peserta</td>
				<td > :</td>
				<td > ".$row->nim_mhs."</td>
				
				<td ></td>
				<td>Periode </td>
				<td>:</td>
				<td>".($row->semester%2==0?'Genap':'Ganjil')."</td>
			</tr>
			
			<tr>
				<td ></td>
				<td >Prog. Studi</td>
				<td > :</td>
				<td > ".$row->jurusan."</td>
				
				<td ></td>
				<td>Status </td>
				<td>:</td>
				<td>".$row->kode_dana."</td>
			</tr>
		
		</table>
		";

		echo "
		<table border=0 width='100%' >

		<tr>
		<td rowspan='2' width='30' align='center' class='bordertd' >No</td>
		<td rowspan='2' align='center' class='bordertd' >Kode Mata Pelajaran</td>
		<td rowspan='2' width='400' align='center' class='bordertd' >Nama Mata Pelajaran</td>
		<td rowspan='2' width='40' align='center' class='bordertd' >Bobot SK</td>
		<td colspan='2' width='40' align='center' class='bordertd' >Paraf Guru</td>
		</tr>

		<tr>
		<td class='bordertd' align='center' >UTP</td>
		<td class='bordertd' align='center' >UAP</td>
		</tr>";
		
		$nourut=1;
		$jmlsk=0;
		$jmlCountMapel=0;
		
		foreach($data_krs_mahasiswa_detail->result() as $det) {
			if($det->nim_mhs==$row->nim_mhs) {
				echo "
				<tr>
				<td align='center' class='bordertd' >".$nourut."</td>
				<td class='bordertd'>".$det->kode_makul . " ( ".$det->nmkmk_ch." ) " ."</td>
				<td align='left' class='bordertd'  >"; 
				
				//Nama Makul dalam tulisan mandarin
				//$p->SetFont('simkai','',7);
				//echo " ( " . $det->nmkmk_ch . " ) ";
				
				echo "  " . $det->nmkmk ."</td>
				<td align='center' class='bordertd' >".$det->sks."</td>
				<td class='bordertd'></td>
				<td class='bordertd'></td>
				</tr>";
				$jmlsk+=$det->sks;
				//<td>".$det->nmkmk_ch . " " . $det->nmkmk ."</td>
				
				$nourut++;
				$jmlCountMapel++;
			}
		}
		
		
		echo "<tr>
		<td border='0000'></td>
		<td border='0000'></td>
		<td border='0000'></td>
		<td align='center' class='bordertd' ><font style='bold'>".$jmlsk." sk</font></td>
		<td border='0000'></td>
		<td border='0000'></td>
		</tr>";
		
		echo "</table>";



		//Footer
		echo "<table border='0' align=center width=100%>";

		echo "<tr align='right' >
			<td align='left' valign='top' rowspan='3' width='450' >
			<font size='1'>
			CATATAN<BR>
			1. APABILA TERDAPAT KESALAHAN, KEKURANGAN/KELEBIHAN SK, HARAP LAPOR KE BIRO AKADEMIK MASING-MASING KAMPUS DENGAN MEMBAWA FOTOKOPI KRS (KARTU RENCANA STUDI)
			<br>
			2. KSPP INI BERLAKU SEBAGAI TANDA BUKTI UNTUK MENGIKUTI UTS, UAS & UJIAN SKRIPSI / TUGAS AKHIR / TESIS / DISERTASI</font>
			<br>
			3. STATUS:<br>
			";
			
		foreach($detail_master_dana->result() as $dana) {
			echo $dana->kode_dana . " " . $dana->ket_dana;
			echo "<br>";
		}
		echo "</font>
			</td>
		
			
			<td rowspan='2' width='30' ></td>
			
			<td valign='top' colspan='2' width='300'  >
				<br>
				Tangerang, ".date("d F Y")."<br>
				Direktur Pahoa College Indonesia
			</td>
			
			</tr>";
		
		echo 	"<tr>
			<td width='5'>&nbsp;</td>
			<td align='right' >
			<img src='".$ttdDali."' alt='".$ttdDali."' title='".$ttdDali."' width='115' height='115' style='position:absolute;margin-left:-120px;margin-top:-65px;' ><br>
			</td>
		</tr>";
		
		echo 	"<tr>";
		echo "<td valign='top'><font size='5'>";
		echo "</font></td >";
		echo 	"
			<td colspan='2' align='right' >".$direktur_pci."</td>
		</tr>";
			
		echo "</table>";
			
			 
		//$reportSubtitle = iconv('UTF-8', 'windows-1252', $reportSubtitle);

		$noAr++;
		
		if($noAr%2==0) {
			echo "<div style='page-break-after:always;'></div>";
		} else {
			echo "<br>";
		}
		
	}
}
	
?>