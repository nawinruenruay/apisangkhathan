<?php
// require_once __DIR__ . '\vendor\autoload.php';
// $data = $Connect_Modelss::show('555');
require_once __DIR__ . '/vendor/autoload.php';

// $TYPE = $_GET['type'];
// $m->WriteCell(0, 23, 'แบบคำร้องเป็นผู้ขาดแคลนทุนทรัพย์อย่างแท้จริง', '', 1, 'C');
// $m->WriteCell(0, 0, $data[0]['PNAME'] . $data[0]['NAME'], '', 0, 'L');
// $ID = $_GET['fin'];
$ii = $_GET['type'];
$api_url = 'https://mua.kpru.ac.th/apiequipment/Report/exportadd?room='.$ii;
$response = file_get_contents($api_url);
$data = json_decode($response, true);

$m = new \Mpdf\Mpdf([
    'default_font_size' => 16,
    'default_font' => 'sarabun',
    'format' => [297, 210],
]);

// $m->AddPage();
// $m->WriteCell(0, 0,  $api_urls, '', 0, 'L');
$i = 0;

$m->AddPageByArray([
    'margin-left' => 15,
    'margin-right' => 15,
    'margin-top' => 5,
    'margin-bottom' => 5,
]);
$m->setY(5);
$date = date('Y-m-d H:i:s');
$m->SetFont('sarabun', '', 10);
$m->WriteCell(0, 0,  'พิมพ์เมื่อ ' . $date, '', 0, 'R');
$m->Ln();
$m->Image('./assets/logo.png', 143.5, 5, 10, 13, 'PNG');
$m->Ln(3);
$m->SetFont('sarabun', 'B', 14);
$m->WriteCell(0, 0,  'รายงานที่อยู่ครุภัณฑ์', '', 0, 'C');
$m->Ln(5);
// $m->WriteCell(0, 0, 'ประเภทครุภัณฑ์ ' . $data[0]['ptype_name'], '', 0, 'C');
// $m->Ln(5);
$m->WriteCell(0, 0, 'หน่วยพัสดุ ' . $data[0]['main_aname'], '', 0, 'C');
$m->Ln(5);
$m->WriteCell(0, 0, 'มหาวิทยาลัยราชภัฏกำแพงเพชร', '', 0, 'C');


$m->SetFont('sarabun', '', 14);
$m->Ln(10);
$html = '<table  cellspacing="0"cellpadding="0"  style="font-size:16px;border-collapse: collapse;width:100%;">
    <thead >
        <tr style="background-color: #dddddd;">
            <th style="border: 1px solid #000;">ลำดับ</th>
            <th style="border: 1px solid #000;">หมายเลขครุภัณฑ์</th>
            <th style="border: 1px solid #000;">รายการ</th>
            <th style="border: 1px solid #000;">ห้อง</th>
            <th style="border: 1px solid #000;">รายละเอียดที่อยู่</th>
        </tr>
    </thead>
    <tbody cellspacing="0"cellpadding="0"  style="font-size:16px;border-collapse: collapse;width:100%;">';
foreach ($data as $d) {
    $i++;
    if ($j === 15) {
        $html .= '</tbody></table>';
        $m->WriteHTML($html);
        $m->AddPage();
        $m->setY(5);
        $date = date('Y-m-d H:i:s');
        $m->SetFont('sarabun', '', 10);
        $m->WriteCell(0, 0,  'พิมพ์เมื่อ ' . $date, '', 0, 'R');
        $m->Ln();
        $m->Image('./assets/logo.png', 143.5, 5, 10, 13, 'PNG');
        $m->Ln(3);
        $m->SetFont('sarabun', 'B', 14);
        $m->WriteCell(0, 0,  'รายงานที่อยู่ครุภัณฑ์', '', 0, 'C');
        $m->Ln(5);
        $m->WriteCell(0, 0, 'หน่วยพัสดุ ' . $data[0]['main_aname'], '', 0, 'C');
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
            <th style="border: 1px solid #000;">ห้อง</th>
            <th style="border: 1px solid #000;">รายละเอียดที่อยู่</th>
        </tr>
    </thead>
    <tbody cellspacing="0"cellpadding="0"  style="font-size:16px;border-collapse: collapse;width:100%;">';
    $j = 0;
    }
    $html .= '<tr cellspacing="0" cellpadding="0" style="font-size:18px; border-collapse: collapse;width:100%;">';
    $html .= '<td style="border: 1px solid #000; border-collapse: collapse;text-align: center;width:5%;"> ' . $i . ' </td>';
    $html .= '<td style="border: 1px solid #000; border-collapse: collapse;text-align: left;padding-left:2mm;width:10%"> ' . $d['pid'] . ' </td>';
    $html .= '<td style="border: 1px solid #000; border-collapse: collapse;text-align: left;padding-left:2mm;white-space:wrap;width:25%"> ' . $d['pname'] . ' </td>';
    $html .= '<td style="border: 1px solid #000; border-collapse: collapse;text-align: left;padding-left:2mm;width:10%"> ' . $d['ROOM_NAME'] . ' </td>';
    $html .= '<td  style="border: 1px solid #000; border-collapse: collapse;text-align: left;padding-left:2mm;width:50%;white-space:wrap;"> ' . $d['update_detail'] . ' </td>';
    $html .= '</tr>';
    $j++;
}
$html .= '</tbody></table>';
$m->WriteHTML($html);
$m->Ln(5);
$m->WriteCell(3, 0,  '', '', 0, 'L');
$m->WriteCell(0, 0,  'จำนวน ' . $i . ' รายการ', '', 0, 'L');
$m->Output();
