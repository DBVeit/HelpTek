<?php
require_once '../../../../../vendor/autoload.php'; // Certifique-se de usar o autoload do Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

$writer = new Xlsx($spreadsheet);
$writer->save('hello_world.xlsx');

echo "Arquivo Excel 'hello_world.xlsx' criado com sucesso!";
?>
