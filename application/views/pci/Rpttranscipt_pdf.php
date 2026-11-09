<?php
$rootpath = $_SERVER['DOCUMENT_ROOT']."/pmb/pdftable/"; 
define('FPDF_FONTPATH', $rootpath.'font/');
require($rootpath.'lib/pdftable.inc.php');
$imgpath = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/logo_icon.jpg";
$ttdDali = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/ttd_dali.jpg";

$noAr=0;
$content[$noAr] = "";

foreach($data_transcipt_mhs->result() as $mhs) {
$content[$noAr] = "";

/*
$content[$noAr].= "<table border=0 align=center width=100%>";
$content[$noAr].= "<tr>";
$content[$noAr].= "<td valign=top ><img src=".$imgpath." width=75 height=100></td>";
$content[$noAr].= "<td align='l' size=5 margin=5 cellpadding=5 valign=top >
		BIRO ADMINISTRASI AKADEMIK<br>
		PAHOA COLLEGE INDONESIA<br>
		JL. KI HAJAR DEWANTARA NO.1<BR>
		SUMMARECON SERPONG<BR>
		TELP. 021-54203355, 54210707 (HUNTING)<BR>
		FAKS. 021-54210700
		</td>";
$content[$noAr].= "</tr>";
$content[$noAr].= "</table>";
*/


//Isi Content Header
	$content[$noAr] .= "<table border=0 align=center width=100%>";
	$content[$noAr] .= "<tr><td height='20' ></td></tr>";
	$content[$noAr] .= "
	<tr>
	<td colspan='4'><font style='bold' size='12' >TRANSCIPT OF ACADEMIC RECORD</font></td>
	</tr>

	<tr>
	<td colspan='4'><font style='bold' size='10' >( T R A N S L A T I O N )</font></td>
	</tr>

	<tr><td colspan='4'></td></tr>
	";

//Isi Content header2
	$content[$noAr] .= "
	<tr>
	<td width='70'></td>
	<td width='15' align='left' >Nama</td>
	<td width='3' > : </td>
	<td align='left' >".$mhs->nama_depan . " " . (strlen($mhs->nama_keluarga)>1?$mhs->nama_keluarga:'')."</td>
	</tr>
	
	<tr>
	<td></td>
	<td align='left' >Nim</td>
	<td width='3' > : </td>
	<td align='left' >".$mhs->nim."</td>
	</tr>
	
	<tr>
	<td></td>
	<td align='left' >Fakultas</td>
	<td width='3' > : </td>
	<td align='left' >".$mhs->fakultas."</td>
	</tr>
	
	<tr>
	<td></td>
	<td align='left' >Jurusan</td>
	<td width='3' > : </td>
	<td align='left' >".$mhs->jurusan."</td>
	</tr>
	
	<tr><td colspan='4'></td></tr>
	";
	
	$content[$noAr] .= "<table border=1 align=center width=100%>";
	
	$content[$noAr] .= "
	<tr>
	<td width='10'><font style='bold' size='8' >No.</font></td>
	<td width='25'><font style='bold' size='8' >Kode Makul</font></td>
	<td><font style='bold' size='8' >Mata Kuliah</font></td>
	<td width='15'><font style='bold' size='8' >SKS</font></td>
	<td width='15'><font style='bold' size='8' >Grade</font></td>
	<td width='15'><font style='bold' size='8' >Bobot</font></td>
	</tr>";
	
	$totalSKS=0;
	$totMakul=0;
	$totBobot=0;
	$nourut=1;
	foreach($data_transcipt_nilai->result() as $row) {
		if($row->nim_mahasiswa==$mhs->nim) {
			$content[$noAr] .= "<tr>";
			$content[$noAr] .= "<td>".$nourut."</td>";
			$content[$noAr] .= "<td>".$row->kdmk."</td>";
			$content[$noAr] .= "<td>".$row->nmkmk."</td>";
			$content[$noAr] .= "<td>".$row->sks."</td>";
			$content[$noAr] .= "<td>".$row->grade."</td>";
			$content[$noAr] .= "<td>".($row->sks*$row->bobot)."</td>";
			$content[$noAr] .= "</tr>";
			$totalSKS+=$row->sks;
			$totMakul+=1;
			$totBobot+=($row->sks*$row->bobot);
			$nourut++;
		}
	}
	
	$content[$noAr] .= "<tr>
	<td colspan='3' ><font style='bold' size='8' >Total</font></td>
	<td>".$totalSKS."</td>
	<td></td>
	<td></td>
	</tr>";
	
	$ipk=0;
	if($totBobot != 0) {
		$ipk = number_format(round($totBobot/$totalSKS,2),2);
		//$ipk = $totBobot . "/ " . $totalSKS;
	} else {
		$ipk = 0;
	}
	$content[$noAr] .= "<tr>
	<td colspan='1' align='left' border='0000' colspan='5' >
		Indeks Prestasi Komulatif (IPK) : ".$ipk."
	<br></td>
	</tr>";
	
	$content[$noAr] .= "</table>";
	
	
//$data_transcipt_mhs

$content[$noAr] .= "</table>";

//Footer
$content[$noAr] .= "<table border=0 align=center width=100%>";

$foot=0;
foreach($data_grade->result() as $gr) {
	$content[$noAr].="
		<tr align='center' >
			<td width='10' border='1111' size=6 >".$gr->bobot."</td>
			<td width='8' border='1111' size=6 >".$gr->grade."</td>
			<td width='15' border='1111' size=6 >".$gr->range_from . "-" . $gr->range_to ."</td>";
		
	if($foot==0) {
		$content[$noAr].="<td></td>";
		$content[$noAr].="<td width='46' valign='top' colspan='2' >Tangerang, ".date("d M Y")."</td>";
		//$content[$noAr].="<td>".$direktur_pci."</td>";
	} else if ($foot==1) {
		$content[$noAr].="<td></td>";
		$content[$noAr].="<td valign='top' colspan='2' >Direktur Pahoa College Indonesia</td>";
	} else if ($foot==2) {
		$content[$noAr].="<td></td><td width='6'></td>";
		$content[$noAr].="<td valign='top' align=center rowspan='3' >
			<img align='center' src=".$ttdDali." width='80' height='70' >
			</td>";
	}
	
		/*if($foot==0) {
			$content[$noAr].="
					<td width='140'></td>
					<td align='l' valign='top' >
						Tangerang, ".date("d M Y")."<br>
						Direktur Pahoa College Indonesia
						".$direktur_pci."
					</td>";
		}*/
		
	$content[$noAr].="</tr>";
	$foot++;
}

$content[$noAr].="<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td colspan='2'>".$direktur_pci."</td>
</tr>";


$content[$noAr] .= "</table>";
$noAr++;

}


$pdf = new PDFTable("P","mm","A4");
$pdf->SetMargins(15,15,15);
$pdf->SetTitle("Transcipt PCI");
$pdf->SetFont('Arial','',7);

if(count($content)>0) {
	for($a=0; $a<count($content); $a++) {
		$pdf->AddPage();
		$pdf->htmltable($content[$a]);
	}
}

$pdf->AliasNbPages();
$pdf->output("PCI_Transcipt_Mhs".$kirim['txtTa'].".pdf","I");
?>