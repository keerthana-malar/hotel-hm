<?php
require 'PHPExcel.php';

// Create a new PHPExcel object
$excel = new PHPExcel();

// Create a worksheet
$excel->setActiveSheetIndex(0);
$sheet = $excel->getActiveSheet();

// HTML content that you want to convert to Excel
$html = '<h1>Excel Content</h1><p>This is your Excel content.</p>';

// Convert HTML to plain text (you can use a library like strip_tags or similar)
$text = strip_tags($html);

// Set the content in a cell
$sheet->setCellValue('A1', $text);

// Create a writer and save the file
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('order_details.xls');
