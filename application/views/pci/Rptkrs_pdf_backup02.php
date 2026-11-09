<?php
$rootpath = $_SERVER['DOCUMENT_ROOT']."/pmb/pdftable/"; 
define('FPDF_FONTPATH', $rootpath.'font/');
require($rootpath.'lib/pdftable.inc.php');
//require($_SERVER['DOCUMENT_ROOT'].'/pmb/tfpdf/tfpdf.php');

//$p = new PDFTable("P","mm",array(200,200));
$p = new PDFTable("P","mm","A4");
$p->SetMargins(10,20,10);
$p->SetTitle("Kartu Rencana Studi (KRS) PCI");

//$p->AddFont('simkai');
//$p->AddFont('simkai', 'I');
//$p->AddFont('simkai', 'B');

//$p->AddBig5Font();
//$p->AddFont('Helvetica','I');
//$p->SetFont('Big5','',20); 
//$p->SetFont('Arial','',7);

//$p->AddGBFont('simkai','Â¿Â¬ÃŒÃ¥_GB2312');
//$p->AddGBFont('simkai','Ã?Â¿Ã?Â¬Ã?Å?Ã?Â¥_GB2312');
//$p->AddGBFont('simkai','Ã?Â¿Ã?Â¬Ã?Å?Ã?Â¥_GB2312');

/*
$p->AddGBFont('simsun','ËÎÌå');
$p->AddGBFont('simhei','ºÚÌå');
$p->AddGBFont('simkai','¿¬Ìå_GB2312');
$p->AddGBFont('sinfang','·ÂËÎ_GB2312');
*/

$p->AddGBFont('simsun','&#23435;&#20307;');
$p->AddGBFont('simhei','&#40657;&#20307;');
$p->AddGBFont('simkai','&#26999;&#20307;_GB2312');
$p->AddGBFont('sinfang','&#20223;&#23435;_GB2312');

//$p->Cell(60,7,iconv('utf-8','GB2312','ÕâÀïÏÔÊ¾²»ÁËÖÐÎÄ'));

$p->defaultFontFamily = 'Arial';
$p->defaultFontStyle  = 'Italic';
$p->defaultFontSize   = 7;

//$p->SetFont('simkai','',7);
$imgpath = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/logo_icon3.jpg";
$ttdDali = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/ttd_dali.jpg";

$noAr=0;

//张献荣教授
if($data_krs_mahasiswa->num_rows()>0) {
	foreach($data_krs_mahasiswa->result() as $row) {
		
		$content[$noAr]="";	
				
		//Logo
		$content[$noAr].="
		<table border=1 align=center width=100%>
		<tr>
			<td valign=top ><img src=".$imgpath." width=13 height=17 ></td>
			<td align='l' size=5 margin=5 cellpadding=5  valign=top >
			BIRO ADMINISTRASI AKADEMIK<br>
			PAHOA COLLEGE INDONESIA<br>
			JL. KI HAJAR DEWANTARA NO.1<BR>
			SUMMARECON SERPONG<BR>
			TELP. 021-54203355, 54210707 (HUNTING)<BR>
			FAKS. 021-54210700
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
			".$row->nama_depan . " " . (strlen($row->nama_keluarga)>1?$row->nama_keluarga:'')." <br> 
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
		$jmlCountMapel=0;
		
		foreach($data_krs_mahasiswa_detail->result() as $det) {
			if($det->nim_mhs==$row->nim_mhs) {
				$content[$noAr].="
				<tr>
				<td>".$nourut."</td>
				<td>".$det->kode_makul."</td>
				<td align='left'  >"; 
				$p->SetFont('simkai','',7);
				$content[$noAr].= " ( " . $det->nmkmk_ch . " ) ";
				
				$p->SetFont('Arial','',7);
				$content[$noAr].= "  " . $det->nmkmk ."</td>
				<td>".$det->sks."</td>
				<td></td>
				<td></td>
				</tr>";
				$jmlsk+=$det->sks;
				//<td>".$det->nmkmk_ch . " " . $det->nmkmk ."</td>
				
				$nourut++;
				$jmlCountMapel++;
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
		$content[$noAr] .= "<table border=0 align=center width=100%>";

		$content[$noAr] .= "<tr>
			<td align='left' valign='top' rowspan='3' >
			<font size='5'>
			CATATAN<BR>
			1. APABILA TERDAPAT KESALAHAN, KEKURANGAN/KELEBIHAN SK, HARAP LAPOR KE BIRO AKADEMIK MASING-MASING KAMPUS DENGAN MEMBAWA FOTOKOPI KRS (KARTU RENCANA STUDI)
			<br><br>
			2. KSPP INI BERLAKU SEBAGAI TANDA BUKTI UNTUK MENGIKUTI UTS, UAS & UJIAN SKRIPSI / TUGAS AKHIR / TESIS / DISERTASI</font>
			<br><br>
			3. STATUS:<br>
			";
			
		foreach($detail_master_dana->result() as $dana) {
			$content[$noAr] .= $dana->kode_dana . " " . $dana->ket_dana;
			$content[$noAr] .= "<br>";
		}
			
		$content[$noAr] .= "</font>
			</td>
			
			<td rowspan='2' width='35' ></td>
			
			<td align='l' width='40' valign='top' colspan='2' >
				<br>
				Tangerang, ".date("d F Y")."<br>
				Direktur Pahoa College Indonesia
			</td>
			
			</tr>";
		
		$content[$noAr] .= 	"<tr>
			<td width='5'>&nbsp;</td>
			<td>
			<img src=".$ttdDali." width=80 height=80 >
			</td>
		</tr>";
		
		$content[$noAr] .= 	"<tr>";
		$content[$noAr] .= "<td valign='top'><font size='5'>";
		$content[$noAr] .= "</font></td>";
		$content[$noAr] .= 	"
			<td colspan='2' >".$direktur_pci."</td>
		</tr>";
			
		$content[$noAr] .= "</table>";
			
			 
		//$reportSubtitle = iconv('UTF-8', 'windows-1252', $reportSubtitle);

		$noAr++;
	}
}
	
//$p = new PDFTable("P","mm","A4");
//$p = new tFPDF();


if($data_krs_mahasiswa->num_rows()>0) {
	for($a=0; $a<count($content); $a++) {
		if($a%2==0) {
			$p->AddPage();
		} else {
			//$p->ln(40);
			$angka = $data_krs_mahasiswa->num_rows();
			$p->ln($jmlCountMapel * 3);
			
			//ini di comment atau di munculkan kembali
			if($row->jurid=="10") {
				//$p->ln(20);
			} else {
				$p->ln(20);
			}
		}
		$p->htmltable($content[$a]);
		
		
	}
}

$p->AliasNbPages();
$p->output("PCI_KRS_".$_GET['txtTa']."_sms".$_GET['txtSms']."_".date("Ymd").".pdf","I");
?>