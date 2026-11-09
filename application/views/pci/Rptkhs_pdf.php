<?php
$rootpath = $_SERVER['DOCUMENT_ROOT']."/pmb/pdftable/"; 
define('FPDF_FONTPATH', $rootpath.'font/');
require($rootpath.'lib/pdftable.inc.php');
//require($_SERVER['DOCUMENT_ROOT'].'/pmb/tfpdf/tfpdf.php');

$imgpath = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/logo_icon3.jpg";
$sigmapath = $_SERVER['DOCUMENT_ROOT']. "/pmb/icons/sigma.jpg";
$ttdDali = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/ttd_dali.jpg";

$noAr=0;
$jmlMapel = 0;
if($data_nilai_mahasiswa->num_rows()>0) {
	foreach($data_nilai_mahasiswa->result() as $row) {
		$content[$noAr]="";
		
		$content[$noAr].="<table border='0' align=center width=100%>";
		$content[$noAr].="<tr>";
		$content[$noAr].="<td></td>";
		//$content[$noAr].= "<td border='1111' width='50' height='3px' >FR-PCI-07. Rev.00 ".date('d F Y')."</td>";
		$content[$noAr].= "<td border='1111' width='50' height='3px' >FR-PCI-07. Rev.00 01 November 2015</td>";
		$content[$noAr].="</tr>";
		$content[$noAr].="<tr><td border='0000' height='1' ></td></tr>";
		$content[$noAr].="</table>";
		
		//Logo
		$content[$noAr].="<table border=0 align=center width=100%>";
		
		/*
		$content[$noAr].="
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
			
			<td border='1111' size=6 >Bobot</td>
			<td colspan='2' border='1111' size=6 >Kualitas</td>
		</tr>";
		*/
		
		$content[$noAr].="
		<tr>
			<td rowspan=6 valign=top ></td>
			<td align='l' size=5 margin=5 cellpadding=5 rowspan='6' valign=top >
			</td>
			
			<td border='1111' size=6 >Bobot</td>
			<td colspan='2' border='1111' size=6 >Kualitas</td>
		</tr>";

		foreach($data_grade->result() as $gr) {
			$content[$noAr].="
			<tr>
				<td width='10' border='1111' size=6 >".$gr->bobot."</td>
				<td width='8' border='1111' size=6 >".$gr->grade."</td>
				<td width='15' border='1111' size=6 >".$gr->range_from . "-" . $gr->range_to ."</td>
			</tr>";
		}
		
		/*$content[$noAr].="
		<tr>
		<td colspan=6 ><font style='bold' size='11' >KETERANGAN STUDI PESERTA PROGRAM</font></td>
		</tr>";*/
		
		$content[$noAr].="
		<tr>
		<td colspan=6 ></td>
		</tr>";
		
		$content[$noAr].="</table>";


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
			".$row->nama_depan . " " . (strlen($row->nama_keluarga)>1?$row->nama_keluarga:'') ." <br> 
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
			".$thnmsk." <br>
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
			$jmlCountMapel=0;
		
			foreach($data_nilai_posting->result() as $isimapel) {
				
				if($row->nim_mahasiswa == $isimapel->nim_mahasiswa) {
					$isiSKN = $isimapel->sks*$isimapel->bobot;
					$jmlSKN += $isiSKN;
					$totalSKS += $isimapel->sks;
					
					$content[$noAr] .= "<tr>
						<td border='1111' >".$nourut."</td>
						<td border='1111' >".$isimapel->kdmk."</td>
						<td border='1111' align='left' >".$isimapel->nmkmk."</td>
						<td border='1111' >".$isimapel->sks."</td>
						<td border='1111' >".$isimapel->grade."</td>
						<td border='1111' >".$isimapel->bobot."</td>
						<td border='1111' >".$isiSKN."</td>
						<td border='1111' >	</td>
					</tr>";
					
					$nourut++;
					$jmlCountMapel++;
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

		$content[$noAr] .= 
		"<tr>
			<td border='1111' colspan='2' >Hasil Studi Periode Diperoleh</td>
			<td border='1111' colspan='3' >Hasil Studi Kumulatif</td>
			<td></td>
			<td border='1101' rowspan='1' >Beban Maksimum Semester Berikutnya</td>
			<td width='30'></td>
			<td align='l' rowspan='1' width='50' colspan='2' valign='top' >
				Tangerang, ".date("d F Y")."<br>
				Direktur Pahoa College Indonesia<br>
			</td>
		</tr>";
		
		//$reportSubtitle = iconv('UTF-8', 'windows-1252', $reportSubtitle);
		$content[$noAr] .= "<tr>
			<td border='1111' >sk</td>
			<td border='1111' >Ips</td>
			<td border='1111' >Kredit Diambil</td>
			<td border='1111' >Kredit Diperoleh</td>
			<td border='1111' >Ipk</td>
			<td border='0101' ></td>
			<td border='0111' ></td>
			<td border='0000' ></td>";
			
		$content[$noAr] .= 	"<td rowspan='2' border='0000' width='5' > &nbsp;</td>
			<td align=center rowspan='2' border='0000' >
			<img align='center' src=".$ttdDali." width=80 height=80 >
			</td>";
			
		$content[$noAr] .= "</tr>";
		
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
		
		$content[$noAr] .= "<tr>
			<td border='1111' >".$totalSKS."</td>
			<td border='1111' >".number_format(round($jmlSKN/$totalSKS,2),2)."</td>
			<td border='1111' >".$jmlKreditDiambil."</td>
			<td border='1111' >".$jmlKreditDiperoleh."</td>
			<td border='1111' >".$jmlIPK."</td>
			<td></td>";
			
		$smsNext = 0;
		$smsNext = $smsMhs+1;
		$statBebanNext=false;
		foreach($jml_sks_semester->result() as $jsms) {
			if($smsNext==$jsms->semester) {
				$content[$noAr] .= "<td border='1111' >". $jsms->jmlsks ."</td>";
				$statBebanNext=true;
				break;
			}
		}
		if($statBebanNext==false) {
			$content[$noAr] .= "<td border='0111' > - </td>";
		}
		
		$content[$noAr] .= 
		"<tr>
		<td colspan='8'></td>
		<td colspan='2' align='left' >".$direktur_pci."</td>
		<tr>";
		
		
		
		$content[$noAr] .= "</tr>";
		$content[$noAr].="</table>";
		$noAr++;
	}
}

//$p = new tFPDF();
$p = new PDFTable("P","mm","A4");
//$p = new PDFTable("P","mm",array(200,200));
$p->SetMargins(10,15,10);
$p->SetTitle("Kartu Hasil Studi (KHS) PCI");
$p->SetFont('Arial','',7);

if($data_nilai_mahasiswa->num_rows()>0) {
	for($a=0; $a<count($content); $a++) {
		if($a%2==0) {
			$p->AddPage();
		} else {
			$angka = $data_nilai_mahasiswa->num_rows();
			$p->ln($jmlCountMapel * 3);
			//ini di comment atau di munculkan kembali
			if($row->id_jurusan=="10") {
				//$p->ln(20);
			} else {
				$p->ln(20);
			}
		}
		$p->htmltable($content[$a]);
	}
}

$p->AliasNbPages();
$p->output("PCI_KHS_".$_GET['txtTa']."_sms".$_GET['txtSms']."_".date("Ymd").".pdf","I");
?>