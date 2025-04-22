<?php

require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat; // <-- Importar NumberFormat
use PhpOffice\PhpSpreadsheet\IOFactory;

// 1) Capturar y validar input
$fecha_inicio = ($_POST['tipo'] ?? '') === 'completo'
    ? null
    : ($_POST['fecha_inicio'] ?? '');
$fecha_fin = ($_POST['tipo'] ?? '') === 'completo'
    ? null
    : ($_POST['fecha_fin'] ?? $fecha_inicio);

if (
    $fecha_inicio !== null
    && (
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_inicio)
        || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fin)
    )
) {
    http_response_code(400);
    exit('Formato de fecha inválido');
}

// 2) Obtener datos
$data = ReportsController::ctrGenerateReport($fecha_inicio, $fecha_fin);
if (!is_array($data)) {
    http_response_code(500);
    exit('Error al obtener datos');
}

// 3) Crear Spreadsheet y propiedades
$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()
    ->setCreator('Sistema UNIMO')
    ->setTitle('Reporte de Incidentes')
    ->setDescription('Generado el ' . date('d/m/Y H:i'))
    ->setCompany('Universidad Montrer');

$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Incidentes');

// 4) Encabezados y estilos de cabecera
$headers = [
    'Urgencia','Fecha Reporte','Reportado Por',
    'Objeto','Área','Descripción',
    'Estado','Fecha Cierre','Tiempo Abierto'
];
$col = 'A';
foreach ($headers as $text) {
    $cell = $col . '1';
    $sheet->setCellValue($cell, $text);
    $sheet->getStyle($cell)->applyFromArray([
        'font' => [
            'bold'  => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size'  => 12,
            'name'  => 'Arial',
        ],
        'fill' => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4F81BD'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => ['rgb' => 'D9D9D9'],
            ],
        ],
    ]);
    $col++;
}

// 5) Freeze pane y auto-filter
$sheet->freezePane('A2');
$sheet->setAutoFilter('A1:I1');

// 6) Poblar datos y aplicar estilos
$rowNum = 2;
foreach ($data as $item) {
    $urgencia    = match ($item['urgency']) {
        'urgent'    => 'Urgente',
        'immediate' => 'Inmediata',
        default     => 'Sin Urgencia',
    };
    $fechaRep    = (new DateTime($item['dateCreated']))->format('d-m-Y H:i');
    $fechaCierre = $item['fecha_cierre']
        ? (new DateTime($item['fecha_cierre']))->format('d-m-Y H:i')
        : '';
    $estado      = $item['statusEvidence'] == 1 ? 'Abierto' : 'Cerrado';
    $inicio      = new DateTime($item['dateCreated']);
    $fin         = $item['statusEvidence'] == 1
        ? new DateTime()
        : new DateTime($item['fecha_cierre']);
    $diff        = $inicio->diff($fin);
    $tiempo      = "{$diff->days} días {$diff->h} hrs";

    $sheet->setCellValue("A{$rowNum}", $urgencia)
          ->setCellValue("B{$rowNum}", $fechaRep)
          ->setCellValue("C{$rowNum}", $item['reportado_por'])
          ->setCellValue("D{$rowNum}", $item['objeto'])
          ->setCellValue("E{$rowNum}", $item['area'])
          ->setCellValue("F{$rowNum}", $item['description'])
          ->setCellValue("G{$rowNum}", $estado)
          ->setCellValue("H{$rowNum}", $fechaCierre)
          ->setCellValue("I{$rowNum}", $tiempo);

    // Formato de fecha
    $sheet->getStyle("B{$rowNum}:H{$rowNum}")
          ->getNumberFormat()
          ->setFormatCode('dd-mm-yyyy hh:mm');

    // Ajuste de texto en descripción
    $sheet->getStyle("F{$rowNum}")
          ->getAlignment()
          ->setWrapText(true);

    // Sombreado alterno
    if ($rowNum % 2 === 0) {
        $sheet->getStyle("A{$rowNum}:I{$rowNum}")
              ->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setRGB('E9F1F7');
    }

    // Colores según urgencia/estado
    $colorMap = [
        1 => ['urgent'=>'9C0006','immediate'=>'FF6600','no-urgency'=>'0000FF'],
        0 => ['urgent'=>'00B050','immediate'=>'FF9900','no-urgency'=>'00B0F0'],
    ];
    $status = $item['statusEvidence'];
    $urg    = $item['urgency'];
    if (isset($colorMap[$status][$urg])) {
        $sheet->getStyle("A{$rowNum}:I{$rowNum}")
              ->getFont()
              ->getColor()
              ->setRGB($colorMap[$status][$urg]);
    }

    $rowNum++;
}

// calcular última fila con datos
$lastRow = $rowNum - 1;

// 7) Forzar Columna F como texto
$sheet->getStyle("F2:F{$lastRow}")
      ->getNumberFormat()
      ->setFormatCode(NumberFormat::FORMAT_TEXT);

// 8) Ajustar ancho y alineaciones finales
foreach (range('A','I') as $c) {
    $sheet->getColumnDimension($c)->setAutoSize(true);
    $sheet->getStyle("{$c}1:{$c}{$lastRow}")
          ->getAlignment()
          ->setVertical(Alignment::VERTICAL_TOP)
          ->setHorizontal(Alignment::HORIZONTAL_LEFT);
}

// 9) Enviar al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
$filename = ($_POST['tipo'] ?? '') === 'completo'
    ? 'reporte_incidentes_completo.xlsx'
    : ($fecha_inicio === $fecha_fin
        ? "reporte_incidentes_{$fecha_inicio}.xlsx"
        : "reporte_incidentes_{$fecha_inicio}_a_{$fecha_fin}.xlsx");
header('Content-Disposition: attachment; filename="'. $filename .'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
