<?php
require_once __DIR__ . '/vendor/autoload.php';
$api_url = 'https://mua.kpru.ac.th/apiequipment/Report/TestReports';
$response = file_get_contents($api_url);
$data = json_decode($response, true);
$chk = '';
$m = new \Mpdf\Mpdf([
    'default_font_size' => 16,
    'default_font' => 'sarabun',
    'format' => [297, 210],
]);
function checkAndAddPage($m, $rowNumber)
{
    // Check if the current row number is a multiple of 10
    if ($rowNumber % 10 == 0 && $rowNumber > 0) {
        $m->AddPage();
        return true;
    }
    return false;
}
$j = 0;
foreach ($data as $d) {

    $m->AddPageByArray([
        'margin-left' => 15,
        'margin-right' => 15,
        'margin-top' => 5,
        'margin-bottom' => 5,
    ]);
    $m->setY(5);
$date = date('Y-m-d H:i:s');
$m->SetFont('sarabun', '', 10);
$m->WriteCell(0, 0,  'พิมพ์เมื่อ '.$date, '', 0, 'R');
$m->Ln();
    $m->Image('./assets/logo.png', 143.5, 5, 10, 13, 'PNG');
    $m->Ln(3);
    $m->SetFont('sarabun', 'B', 14);
    $m->WriteCell(0, 0,  'การตรวจสอบครุภัณฑ์คงเหลือ ประจำปีงบประมาณ พ.ศ. ' . $d['yearpd'], '', 0, 'C');
    $m->Ln(5);
    $m->WriteCell(0, 0, 'ประเภทงบประมาณ ' . $d['money'], '', 0, 'C');
    $m->Ln(5);
    $m->WriteCell(0, 0, 'หน่วยพัสดุ ' . $d['main'], '', 0, 'C');
    $m->Ln(5);
    $m->WriteCell(0, 0, 'มหาวิทยาลัยราชภัฏกำแพงเพชร', '', 0, 'C');

    $m->SetFont('sarabun', '', 14);
    $m->Ln(10);
    $datas = $d['data'];
    $html = '<table cellspacing="0"cellpadding="0"  style="font-size:16px;border-collapse: collapse;width:100%;">
    <thead >
        <tr style="background-color: #dddddd;">
            <th style="border: 1px solid #000;">ลำดับ</th>
            <th style="border: 1px solid #000;">หมายเลขครุภัณฑ์</th>
            <th style="border: 1px solid #000;">รายการ</th>
            <th style="border: 1px solid #000;">จำนวน</th>
            <th style="border: 1px solid #000;">ราคาต่อหน่วย</th>
            <th style="border: 1px solid #000;">มูลค่ารวม</th>
            <th style="border: 1px solid #000;">ประเภทเงิน</th>
            <th style="border: 1px solid #000;">ใช้ประจำที่ไหน</th>
            <th style="border: 1px solid #000;">สถานะ</th>
        </tr>
    </thead>
    <tbody cellspacing="0"cellpadding="0"  style="font-size:16px;border-collapse: collapse;width:100%;">';
    if (count($datas) !== 0) {
        $i = 0;
        foreach ($datas as $f) {
            $j++;
            $i++;
            if ($i === 16) {
                $html .= '</tbody></table>';
                $m->WriteHTML($html);
                $m->AddPage();
                $m->setY(5);
$date = date('Y-m-d H:i:s');
$m->SetFont('sarabun', '', 10);
$m->WriteCell(0, 0,  'พิมพ์เมื่อ '.$date, '', 0, 'R');
$m->Ln();
                $m->Image('./assets/logo.png', 143.5, 5, 10, 13, 'PNG');
                
                $m->Ln(3);
                $m->SetFont('sarabun', 'B', 14);
                $m->WriteCell(0, 0,  'การตรวจสอบครุภัณฑ์คงเหลือ ประจำปีงบประมาณ พ.ศ. ' . $d['yearpd'], '', 0, 'C');
                $m->Ln(5);
                $m->WriteCell(0, 0, 'ประเภทงบประมาณ ' . $d['money'], '', 0, 'C');
                $m->Ln(5);
                $m->WriteCell(0, 0, 'หน่วยพัสดุ ' . $d['main'], '', 0, 'C');
                $m->Ln(5);
                $m->WriteCell(0, 0, 'มหาวิทยาลัยราชภัฏกำแพงเพชร', '', 0, 'C');

                $m->SetFont('sarabun', '', 14);
                $m->Ln(10);
                $html = '<table cellspacing="0"cellpadding="0"  style="font-size:16px;border-collapse: collapse;width:100%;">
    <thead >
        <tr style="background-color: #dddddd;">
            <th style="border: 1px solid #000;">ลำดับ</th>
            <th style="border: 1px solid #000;">หมายเลขครุภัณฑ์</th>
            <th style="border: 1px solid #000;">รายการ</th>
            <th style="border: 1px solid #000;">จำนวน</th>
            <th style="border: 1px solid #000;">ราคาต่อหน่วย</th>
            <th style="border: 1px solid #000;">มูลค่ารวม</th>
            <th style="border: 1px solid #000;">ประเภทเงิน</th>
            <th style="border: 1px solid #000;">ใช้ประจำที่ไหน</th>
            <th style="border: 1px solid #000;">สถานะ</th>
        </tr>
    </thead>
    <tbody cellspacing="0"cellpadding="0"  style="font-size:16px;border-collapse: collapse;width:100%;">';
            }
            $html .= '<tr cellspacing="0" cellpadding="0" style="font-size:16px; border-collapse: collapse; width:100%;">';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;white-space: nowrap;text-align: center;"> ' . $i . ' </td>';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;text-align: left;width:3cm;padding-left:2mm;"> ' . $f['pid'] . ' </td>';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;white-space: nowrap;text-align: left;padding-left:1mm;"> ' . $f['pname'] . ' </td>';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;white-space: nowrap;text-align: right;"> ' . $f['qty'] . ' </td>';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;white-space: nowrap;text-align: right;"> ' . $f['price'] . ' </td>';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;white-space: nowrap;text-align: right;"> ' . $f['price'] . ' </td>';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;white-space: nowrap;text-align: left;padding-left:1mm;"> ' . $d['money'] . ' </td>';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;white-space: nowrap;text-align: left;padding-left:1mm;"> ' . $d['main'] . ' </td>';
            $html .= '<td style="border: 1px solid #000; border-collapse: collapse;white-space: nowrap;text-align: center;"> ' . $f['pstatus_name'] . ' </td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $m->WriteHTML($html);
    }


    $m->SetY(160);
    $m->SetFont('sarabun', '', 14);
    $m->WriteCell(0, 0,  'ลายมือชื่อ ..................................................', '', 0, 'L');
    $m->Ln(5);
    $m->WriteCell(13, 0,  '', '', 0, 'L');
    $m->WriteCell(1, 0,  '(', '', 0, 'L');
    $m->WriteCell(40, 0,  '..................................................', '', 0, 'L');
    $m->WriteCell(0, 0,  ')', '', 0, 'L');
    $m->Ln(5);
    $m->WriteCell(28, 0,  '', '', 0, 'L');
    $m->SetFont('sarabun', 'B', 12);
    $m->WriteCell(0, 0,  'ผู้จัดทำ', '', 0, 'L');
    $m->Ln(6);
    $m->SetFont('sarabun', '', 14);
    $m->WriteCell(14, 0,  '', '', 0, 'L');
    $m->WriteCell(0, 0,  '............../.................../..................', '', 0, 'L');


    $m->SetY(160);
    $m->setX(110);
    $m->SetFont('sarabun', '', 14);
    $m->WriteCell(0, 0,  'ลายมือชื่อ ..................................................', '', 0, 'L');
    $m->Ln(5);
    $m->setX(110);
    $m->WriteCell(13, 0,  '', '', 0, 'L');
    $m->WriteCell(1, 0,  '(', '', 0, 'L');
    $m->WriteCell(40, 0,  '..................................................', '', 0, 'L');
    $m->WriteCell(0, 0,  ')', '', 0, 'L');
    $m->Ln(5);
    $m->setX(110);
    $m->WriteCell(27, 0,  '', '', 0, 'L');
    $m->SetFont('sarabun', 'B', 12);
    $m->WriteCell(0, 0,  'ผู้ตรวจสอบ', '', 0, 'L');
    $m->Ln(6);
    $m->setX(110);
    $m->SetFont('sarabun', '', 14);
    $m->WriteCell(14, 0,  '', '', 0, 'L');
    $m->WriteCell(0, 0,  '............../.................../..................', '', 0, 'L');


    $m->SetY(160);
    $m->setX(210);
    $m->SetFont('sarabun', '', 14);
    $m->WriteCell(0, 0,  'ลายมือชื่อ ..................................................', '', 0, 'L');
    $m->Ln(5);
    $m->setX(210);
    $m->WriteCell(13, 0,  '', '', 0, 'L');
    $m->WriteCell(1, 0,  '(', '', 0, 'L');
    $m->WriteCell(40, 0,  '..................................................', '', 0, 'L');
    $m->WriteCell(0, 0,  ')', '', 0, 'L');
    $m->Ln(5);
    $m->setX(210);
    $m->WriteCell(27, 0,  '', '', 0, 'L');
    $m->SetFont('sarabun', 'B', 12);
    $m->WriteCell(0, 0,  'ผู้ตรวจสอบ', '', 0, 'L');
    $m->Ln(6);
    $m->setX(210);
    $m->SetFont('sarabun', '', 14);
    $m->WriteCell(14, 0,  '', '', 0, 'L');
    $m->WriteCell(0, 0,  '............../.................../..................', '', 0, 'L');


}
$m->Output();
