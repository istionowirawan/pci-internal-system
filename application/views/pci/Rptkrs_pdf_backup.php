<?php
$rootpath = $_SERVER['DOCUMENT_ROOT']."/pmb/pdftable/"; 
define('FPDF_FONTPATH', $rootpath.'font/');
require($rootpath.'lib/pdftable.inc.php');
//require($_SERVER['DOCUMENT_ROOT'].'/pmb/tfpdf/tfpdf.php');

$p = new PDFTable("P","mm",array(200,200));
$p->SetMargins(10,15,10);
$p->SetTitle("KRS - PDF");

//$p->AddFont('simkai');
//$p->AddFont('simkai', 'I');
//$p->AddFont('simkai', 'B');

$p->SetFont('Arial','',7);

$imgpath = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/logo_icon.jpg";

$noAr=0;

//张献荣教授
if($data_krs_mahasiswa->num_rows()>0) {
	foreach($data_krs_mahasiswa->result() as $row) {
		
		$content[$noAr]="";	
				
		//Logo
		$content[$noAr].="
		<table border=0 align=center width=100%>
		<tr>
			<td valign=top ><img src=".$imgpath." width=75 height=100></td>
			<td align='l' size=5 margin=5 cellpadding=5  valign=top >
			BIRO ADMINISTRASI AKADEMIK<br>
			PAHOA COLLEGE INDONESIA<br>
			JL. KI HAJAR DEWANTARA NO.1<BR>
			SUMMARECON SERPONG<BR>
			TELP. 021-54203355, 54210707 (HUNTING)<BR>
			FAKS. 021-54210700".$rootpath."font/
			</td>
		</tr>
		";

		$content[$noAr].="
		<tr>
		<td colspan='2' ><font style='bold' size='11' >KETERANGAN STUDI PESERTA PROGRAM</font></td>
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
			".$row->nim_mhs." <br>
			".$row->jurusan." <br>
		</td>



		<td align='l' width='25' > 
			Tahun Akademik <br>
			Periode <br>
			Status <br>
		</td>

		<td width='2'> 
			: <br> 
			: <br>
			: <br>
		</td>

		<td align='l' > 
			".$row->tahun_ajaran." <br> 
			".($row->semester%2==0?'Genap':'Ganjil')." <br>
			".$row->kode_dana." <br>
		</td>
		</tr>

		</table>
		";

		$content[$noAr].="
		<table border=1 width=100%>

		<tr>
		<td rowspan='2' width='10' ><font style='bold'>No</font></td>
		<td rowspan='2' width='25' ><font style='bold'>Kode Mata Pelajaran</font></td>
		<td rowspan='2'><font style='bold'>Nama Mata Pelajaran</font></td>
		<td rowspan='2' width='20' ><font style='bold'>Bobot SK</font></td>
		<td colspan='2'><font style='bold'>Paraf Guru</font></td>
		</tr>

		<tr>
		<td width='20' ><font style='bold'>UTP</font></td>
		<td width='20' ><font style='bold'>UAP</font></td>
		</tr>";
		
		$nourut=1;
		$jmlsk=0;
		foreach($data_krs_mahasiswa_detail->result() as $det) {
			if($det->nim_mhs==$row->nim_mhs) {
				$content[$noAr].="<tr>
				<td>".$nourut."</td>
				<td>".$det->kode_makul."</td>
				<td align='left' >". $det->nmkmk_ch . " " . $det->nmkmk ."</td>
				<td>".$det->sks."</td>
				<td></td>
				<td></td>
				</tr>";
				$jmlsk+=$det->sks;
				//<td>".$det->nmkmk_ch . " " . $det->nmkmk ."</td>
				
				$nourut++;
			}
		}
		
		$content[$noAr].="<tr>
		<td border='0000'></td>
		<td border='0000'></td>
		<td border='0000'></td>
		<td><font style='bold'>".$jmlsk." sk</font></td>
		<td border='0000'></td>
		<td border='0000'></td>
		</tr>";
		
		$content[$noAr] .="</table>";




		//Footer
		$content[$noAr] .= "<table border=0 width=100%>";

		$content[$noAr] .= "<tr>
			<td align='left' valign='top' colspan='2' >
			<font size='5'>
			CATATAN<BR>
			
			1. APABILA TERDAPAT KESALAHAN, KEKURANGAN/KELEBIHAN SK, HARAP LAPOR KE BIRO AKADEMIK MASING-MASING KAMPUS DENGAN MEMBAWA FOTOKOPI KRS (KARTU RENCANA STUDI)
			</font>
			</td>
			
			<td rowspan='3' width='35' ></td>
			
			<td align='l' width='40' valign='top' rowspan='3' >
				<br>
				Tangerang, ".date("d M Y")."<br>
				Direktur Pahoa College Indonesia<br><br><br><br><br>
				".$direktur_pci."
			</td>
			
			</tr>";
		
		$content[$noAr] .= 	"<tr>
			<td align='left' colspan='2'><font size='5'>2. KSPP INI BERLAKU SEBAGAI TANDA BUKTI UNTUK MENGIKUTI UTS, UAS & UJIAN SKRIPSI / TUGAS AKHIR / TESIS / DISERTASI</font></td>
		</tr>";
		
		$content[$noAr] .= 	"<tr>
			<td align='left' valign='top' width='10' >
				<font size='5'>
				3. STATUS: 
				</font>
			</td>";
			
		$content[$noAr] .= "<td align='left' valign='top'><font size='5'>";
		foreach($detail_master_dana->result() as $dana) {
			$content[$noAr] .= $dana->kode_dana . " " . $dana->ket_dana;
			$content[$noAr] .= "<br>";
		}
		$content[$noAr] .= "</font></td>";
				
		$content[$noAr] .= 	"
			</td>
		</tr>";
			
			
			
			 
		//$reportSubtitle = iconv('UTF-8', 'windows-1252', $reportSubtitle);

		$noAr++;
	}
}
	
//$p = new PDFTable("P","mm","A4");
//$p = new tFPDF();


if($data_krs_mahasiswa->num_rows()>0) {
	for($a=0; $a<count($content); $a++) {
		$p->AddPage();
		$p->htmltable($content[$a]);
	}
}

$p->AliasNbPages();
$p->output("PCI_KRS_".$_GET['txtTa']."_sms".$_GET['txtSms'].".pdf","I");
?>