<?php
$rootpath = $_SERVER['DOCUMENT_ROOT']."/pmb/pdftable/"; 
define('FPDF_FONTPATH', $rootpath.'font/');
require($rootpath.'lib/pdftable.inc.php');

$imgpath = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/logo_icon.jpg";

function bobot_nilai_grade($grade) {
	if($grade=="A") {
		return 4;
	} else if ($grade=="B") {
		return 3;
	} else if ($grade=="C") {
		return 2;
	} else if ($grade=="D") {
		return 1;
	} else if ($grade=="E") {
		return 0;
	}
}

$noAr=0;

foreach($data_nilai_mahasiswa->result() as $row) {
	$content[$noAr]="";
	
	//Logo
	$content[$noAr].="
	<table border=0 align=center width=100%>
	<tr>
		<td rowspan=2 valign=middle ><img src=".$imgpath." width=75 height=100></td>
	</tr>

	<tr>
		<td align='l' size=5 margin=5 cellpadding=5 >
		BIRO ADMINISTRASI AKADEMIK<br>
		PAHOA COLLEGE INDONESIA<br>
		JL. KI HAJAR DEWANTARA NO.1<BR>
		SUMMARECON SERPONG<BR>
		TELP. 021-54203355, 54210707 (HUNTING)<BR>
		FAKS. 021-54210700
		</td>
	</tr>

	<tr>
	<td colspan=2 ><font style='bold' size='11' >KETERANGAN STUDI PESERTA PROGRAM</font></td>
	</tr>


	</table>
	";


	//HEADER
	$content[$noAr].="
	<table border=0 align=center width=100%>

	<tr>
	<td width='5'></td>
	<td align='l' width='30' > 
		Nama <br>
		Nomor Induk Peserta <br>
		Prog. Studi <br>
	</td>

	<td width='2'> 
		: <br> 
		: <br>
		: <br>
	</td>

	<td align='l' width='100' > 
		".$row->nama_depan." <br> 
		".$row->nim_mahasiswa." <br>
		".$row->jurusan." <br>
	</td>



	<td align='l' width='25' > 
		Tahun Akademik <br>
		Periode <br>
		Masa Studi S/D <br>
	</td>

	<td width='2'> 
		: <br> 
		: <br>
		: <br>
	</td>

	<td align='l' > 
		".$row->tahun_ajaran." <br> 
		".($row->semester%2==0?'Genap':'Ganjil')." <br>
		 <br>
	</td>
	</tr>

	</table>
	";
		

		//CONTENT
		$content[$noAr] .= "<table border=0 align=center width=100%>";

		$content[$noAr] .= "<tr>
			<td border='1111' width='6' >No</td>
			<td border='1111' width='20' >Kode Mata Pelajaran</td>
			<td border='1111' >Nama Mata Pelajaran</td>
			<td border='1111' width='12' >Kredit (sk)</td>
			<td border='1111' width='12' >Nilai (huruf)</td>
			<td border='1111' width='12' >Nilai (angka)</td>
			<td border='1111' width='12' >Bobot Kualitas (skN)</td>
			<td border='1111' width='30' >Keterangan</td>
		</tr>";

		$nourut=1;
		$jmlSKN=0;
		$totalSKS=0;
		$bobot=0;


		foreach($data_nilai_posting->result() as $isimapel) {
			
			if($row->nim_mahasiswa == $isimapel->nim_mahasiswa) {
				$isiSKN = $isimapel->sks*bobot_nilai_grade($isimapel->grade);
				$jmlSKN += $isiSKN;
				$totalSKS += $isimapel->sks;
				
				$content[$noAr] .= "<tr>
					<td border='1111' >".$nourut."</td>
					<td border='1111' >".$isimapel->kdmk."</td>
					<td border='1111' >".$isimapel->nmkmk."</td>
					<td border='1111' >".$isimapel->sks."</td>
					<td border='1111' >".$isimapel->grade."</td>
					<td border='1111' >".bobot_nilai_grade($isimapel->grade)."</td>
					<td border='1111' >".$isiSKN."</td>
					<td border='1111' >	</td>
				</tr>";
				
				$nourut++;
			}
		}

		$content[$noAr] .= "<tr>
			<td ></td>
			<td ></td>
			<td ></td>
			<td border='1111' >".$totalSKS."</td>
			<td ></td>
			<td ></td>
			<td border='1111' >".$jmlSKN."</td>
			<td ></td>
		</tr>";

		$content[$noAr] .= "<tr><td></td></tr>";

		$content[$noAr].="</table>";


	//Footer
	$content[$noAr] .= "<table border=0 align=center width=100%>";

	$content[$noAr] .= "<tr>
		<td border='1111' colspan='2' >Hasil Studi Periode Diperoleh</td>
		<td border='1111' colspan='3' >Hasil Studi Kumulatif</td>
		<td></td>
		<td border='1111' rowspan='2' >Beban Maksimum Semester Berikutnya</td>
		<td width='30'></td>
		<td align='l' rowspan='3' width='50' valign='top' >
			Tangerang,<br>
			Direktur Pahoa College Indonesia<br><br><br><br><br>
			Prof. Dr. Ir. Dali Santun Naga, MMSI
		</td>
	</tr>";

	$content[$noAr] .= "<tr>
		<td border='1111' >Esk</td>
		<td border='1111' >Ips</td>
		<td border='1111' >Kredit Diambil</td>
		<td border='1111' >Kredit Diperoleh</td>
		<td border='1111' >Ipk</td>
		<td ></td>
	</tr>";

	$content[$noAr] .= "<tr>
		<td border='1111' >".$totalSKS."</td>
		<td border='1111' >".number_format(round($jmlSKN/$totalSKS,1),2)."</td>
		<td border='1111' >isi1</td>
		<td border='1111' >isi2</td>
		<td border='1111' >isi3</td>
		<td></td>
		<td border='1111' >isi4</td>
		
	</tr>";
	$content[$noAr].="</table>";
	$noAr++;
}


$p = new PDFTable("P","mm","A4");
$p->SetMargins(10,10,10);
$p->AddPage();
$p->SetTitle("KHS - PDF");
$p->SetFont('Arial','',8);
$p->htmltable($content[0]);

$p->AliasNbPages();
$p->output('PCI_KHS.pdf','I');
?>