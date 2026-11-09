<?php
require('../fpdf.php');

class PDF extends FPDF
{
	function Header()
	{
		$almimg = 'image/logo_icon.png';
		$this->Image('logo_pb.png',10,8,33);
		$this->SetFont('Arial','B',15);
		$this->Cell(80);
		$this->Cell(30,10,'aaa',1,0,'C');
		$this->Ln(20);
	}

	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

$pdf=new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
for($i=1;$i<=40;$i++)
    $pdf->Cell(0,10,'Printing line number '.$i,0,1);
$pdf->Output();

?>