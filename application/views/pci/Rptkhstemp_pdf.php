<?php
$rootpath = $_SERVER['DOCUMENT_ROOT']."/pmb/pdftable/"; 
define('FPDF_FONTPATH', $rootpath.'font/');
require($rootpath.'lib/pdftable.inc.php');
//require($_SERVER['DOCUMENT_ROOT'].'/pmb/tfpdf/tfpdf.php');

$imgpath = $_SERVER['DOCUMENT_ROOT']. "/pmb/image/logo_icon3.jpg";
$sigmapath = $_SERVER['DOCUMENT_ROOT']. "/pmb/icons/sigma.jpg";

$noAr=0;

//print_r($data_nilai_mahasiswa->result());
$progstudi = "";

if($data_nilai_mahasiswa->num_rows()>0) {
	foreach($data_nilai_mahasiswa->result() as $row) {
		$content[$noAr]="";

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
			".$thnmsk." <br>
		</td>
		</tr>

		</table>
		";
		$progstudi = $row->jurusan;

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
					
					$content[$noAr] .= "<tr>
						<td border='1111' >".$nourut."</td>
						<td border='1111' >".$isimapel->kdmk."</td>
						<td border='1111' align='left' >".$isimapel->nmkmk."</td>
						<td border='1111' >".$isimapel->sks."</td>
						<td border='1111' >".$queryGrade->row(0)->grade."</td>
						<td border='1111' >".$queryGrade->row(0)->bobot."</td>
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
				Tangerang, ".date("d F Y")."<br>
				Direktur Pahoa College Indonesia<br><br><br><br><br>
				".$direktur_pci."
			</td>
		</tr>";
		
		//$reportSubtitle = iconv('UTF-8', 'windows-1252', $reportSubtitle);
		$content[$noAr] .= "<tr>
			<td border='1111' >sk</td>
			<td border='1111' >Ips</td>
			<td border='1111' >Kredit Diambil</td>
			<td border='1111' >Kredit Diperoleh</td>
			<td border='1111' >Ipk</td>
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
		
		$content[$noAr] .= "<tr>
			<td border='1111' >".$totalSKS."</td>
			<td border='1111' >".number_format(round($jmlSKN/$totalSKS,1),2)."</td>
			<td border='1111' >".$totNilai."</td>
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
			$content[$noAr] .= "<td border='1111' > - </td>";
		}
			
		$content[$noAr] .= "</tr>";
		
		
		$content[$noAr].="</table>";
		$noAr++;
	}
}

//$p = new tFPDF();
$p = new PDFTable("P","mm","A4");
//$p = new PDFTable("P","mm",array(200,200));
$p->SetMargins(10,15,10);
$p->SetTitle("KHS Sementara PCI");
$p->SetFont('Arial','',7);

if($data_nilai_mahasiswa->num_rows()>0) {
	for($a=0; $a<count($content); $a++) {
		if($a%2==0) {
			$p->AddPage();
		} else {
			if($progstudi=="Ekonomi dan Perdagangan Internasional") {
				$p->ln(30);
			} else {
				$p->ln(35);
			}
		}
		$p->htmltable($content[$a]);
	}
}

$p->AliasNbPages();
$p->output("PCI_KHS_sementara_".$_GET['txtTa']."_sms".$_GET['txtSms']."_".date("Ymd").".pdf","I");
?>