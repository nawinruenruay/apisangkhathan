<?php
require('rotate.php');

class PDF extends PDF_Rotate
{
function RotatedText($x,$y,$txt,$angle)
{
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}

function RotatedImage($file,$x,$y,$w,$h,$angle)
{
    //Image rotated around its upper-left corner
    $this->Rotate($angle,$x,$y);
    $this->Image($file,$x,$y,$w,$h);
    $this->Rotate(0);
}
}

$pdf=new PDF();
$pdf->AddPage();
$pdf->AddFont('angsana','','angsa.php');
$pdf->SetFont('angsana','',16);
	$pdf->Cell( 15  , 70 , iconv( 'UTF-8','cp874' , 'คะแนน' ),1,0,'C' );
	$pdf->RotatedText(15,70,'Hello!',90);
	$pdf->Cell( 0  , 70 , iconv( 'UTF-8','cp874' , 'ลายมือชื่อ' ),1,0,'C' );

$pdf->Output();
?>