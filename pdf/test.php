<?php
require_once __DIR__ . '\vendor\autoload.php';
use \Mpdf\Mpdf;

// Create an Mpdf instance
$mpdf = new Mpdf();

// Example two-dimensional array
$tableData = [
    ['Name', 'Age', 'Country', 'Skills'],
    ['John Doe', 25, 'USA', ['PHP', 'JavaScript']],
    ['Jane Smith', 30, 'Canada', ['Python', 'HTML']],
    // ... more rows
];

// HTML code for the table
$html = '';

// Function to generate table rows
function generateTableRows($data) {
    $html = '';
    foreach ($data as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            if (is_array($cell)) {
                // If the cell is an array (nested data), create a nested table
                $html .= '<td><table border="0" cellpadding="3" cellspacing="0">';
                foreach ($cell as $nestedCell) {
                    $html .= '<tr><td>' . $nestedCell . '</td></tr>';
                }
                $html .= '</table></td>';
            } else {
                // If the cell is a scalar value, display it directly
                $html .= '<td>' . $cell . '</td>';
            }
        }
        $html .= '</tr>';
    }
    return $html;
}

// Function to check if a new page is needed and add it
function checkAndAddPage($mpdf, $html) {
    // Check if there are more than 10 rows
    if (substr_count($html, '<tr>') > 10) {
        $mpdf->AddPage();
        return true;
    }
    return false;
}

// Generate and add the table rows
$html .= '<table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Country</th>
                    <th>Skills</th>
                </tr>
            </thead>
            <tbody>';

$html .= generateTableRows($tableData);
$html .= '</tbody></table>';

// Check if a new page is needed and add it
checkAndAddPage($mpdf, $html);

// Add the HTML content to the PDF
$mpdf->WriteHTML($html);

// Output the PDF
$mpdf->Output();
