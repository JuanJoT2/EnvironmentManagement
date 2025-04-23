<?php
require_once '../fpdf/fpdf.php';
require_once '../../models/AdminModel.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $adminModel = new AdminModel();
    $ambiente = $adminModel->obtenerAmbientePorId($id);

    if ($ambiente) {
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode("Ficha del Ambiente: " . $ambiente['Nombre']), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, "Torre: " . $ambiente['Torre'], 0, 1);
        $pdf->Cell(0, 10, "Computadores: " . $ambiente['Computadores'], 0, 1);
        $pdf->Cell(0, 10, "TVs: " . $ambiente['Tvs'], 0, 1);
        $pdf->Cell(0, 10, "Sillas: " . $ambiente['Sillas'], 0, 1);
        $pdf->Cell(0, 10, "Mesas: " . $ambiente['Mesas'], 0, 1);
        $pdf->Cell(0, 10, "Tableros: " . $ambiente['Tableros'], 0, 1);
        $pdf->Cell(0, 10, "Nineras: " . $ambiente['Nineras'], 0, 1);
        $pdf->Ln(5);
        $pdf->MultiCell(0, 10, "Observaciones: " . utf8_decode($ambiente['Observaciones']));

        $pdf->Output("I", "Ambiente_{$ambiente['Nombre']}.pdf");
    } else {
        echo "Ambiente no encontrado.";
    }
} else {
    echo "ID de ambiente no proporcionado.";
}
