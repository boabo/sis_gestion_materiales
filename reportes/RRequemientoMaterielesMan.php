<?php

class RRequemientoMaterielesMan extends  ReportePDF
{
    function Header()
    {
        $this->ln(5);
        $height = 20;
        //cabecera del reporte
        $this->Cell(40, $height, '', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(16);
        $this->SetFont('', 'B');
        $this->Cell(105, $height, 'COMITÉ DE EVALUACIÓN', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $y = $this->getY();
        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 10, $y, 36);
        $this->ln(20);
        $this->customy = $this->getY();
        $this->reporteRequerimiento();
    }
    function reporteRequerimiento(){
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Datos Generales', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(15, 7, ' Fecha', 1, 0, 'C', 0, '', 0);
        $this->Cell(60, 7,  $this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(60, 7, ' Matrícula', 1, 0, 'L', 0, '', 0);
        $this->Cell(0, 7,  $this->datos[0]['matricula'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(0, 7, ' Repuesto a Solicitar', 1, 0, 'L', 0, '', 0);
        $this->ln();

        $this->SetFont('times', '', 11);
        $this->Cell(36, 7, ' Descripción', 1, 0, 'L', 0, '', 0);
        foreach ($this->datos as $Row) {
            $this->Cell(35, 7, ' '.$Row['descripcion'], 0, 0, 'L', 0, '', 0);
        }
        $this->ln(0.10);
        $this->Cell(36, 7, '', 0, 0, 'L', 0, '', 0);
        $this->Cell(0, 7, '', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(36, 7, ' Número de Parte', 1, 0, 'L', 0, '', 0);
        foreach ($this->datos as $Row) {
            $this->Cell(35, 7, ' ' .$Row['nro_parte'], 0, 0, 'L', 0, '', 0);
        }
        $this->ln(0.10);
        $this->Cell(36, 7, '', 0, 0, 'L', 0, '', 0);
        $this->Cell(0, 7, '', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Motivo de la Solicitud', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, $this->datos[0]['motivo_solicitud'], 1, 'J', 0, '', '');
        $this->ln(12);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Justificación de Necesidad', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(50, 7, 'Tipo de Falla', 1, 0, 'C', 0, '', 0);
        $this->Cell(50, 7,  'Tipo de Reporte', 1, 0, 'C', 0, '', 0);
        $this->Cell(50, 7,  'tipo de Solicitud', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7,  'MEL', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(50, 7,$this->datos[0]['tipo_falla'] , 1, 0, 'C', 0, '', 0);
        $this->Cell(50, 7, $this->datos[0]['tipo_reporte'] , 1, 0, 'C', 0, '', 0);
        $this->Cell(50, 7, $this->datos[0]['tipo_solicitud'] , 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7, $this->datos[0]['mel'] , 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, ' Observaciones: '. $this->datos[0]['observaciones_sol']."\n", 1, 'J', 0, '', '');
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(0, 7, ' Evaluado y Analizado Por: ', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'BU', 11);
        $this->Cell(65, 0, 'Unidad Control y Seguimiento', 0, 0, 'C', 0, '', 0);
        $this->Cell(65, 0,  'Unidad Linea / Hagar / Taller', 0, 0, 'C', 0, '', 0);
        $this->Cell(0, 0,  'Unidad Control Calidad ', 0, 0, 'C', 0, '', 0);
        $this->ln(0.10);
        $this->Cell(65, 35, '', 1, 0, 'C', 0, '', 0);
        $this->Cell(65, 35,  '', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 35,  '', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'BU', 11);
        $this->Cell(30, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(60, 0, 'Depto. de Mantenimiento', 0, 0, 'C', 0, '', 0);
        $this->Cell(60, 0,  'Gerencia de Mantenimiento', 0, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, '', 0, 0, 'C', 0, '', 0);
        $this->ln(0.10);
        $this->Cell(30, 35, '', 1, 0, 'C', 0, '', 0);
        $this->Cell(60, 35, '', 1, 0, 'C', 0, '', 0);
        $this->Cell(60, 35,  '', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 35, '', 1, 1, 'C', 0, '', 0);
        $this->ln(2);
        $this->SetFont('times', '', 11);
        $this->Cell(0, 7, ' Evaluado y Analizado por OAC - 121', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'B', 11);
        $this->Cell(65, 0, ' V.B. DAC', 0, 0, 'L', 0, '', 0);
        $this->Cell(65, 0,  ' V.B. Gerencia de Operaciones', 0, 0, 'L', 0, '', 0);
        $this->Cell(0, 0,  ' Recibido Abastecimiento', 0, 0, 'L', 0, '', 0);
        $this->ln(0.10);
        $this->Cell(65, 35, '', 1, 0, 'L', 0, '', 0);
        $this->Cell(65, 35,  '', 1, 0, 'L', 0, '', 0);
        $this->Cell(0, 35,  '', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(65, 7, ' Fecha:', 1, 0, 'L', 0, '', 0);
        $this->Cell(65, 7,  ' Fecha:', 1, 0, 'L', 0, '', 0);
        $this->Cell(0, 7,  ' Fecha:', 1, 0, 'L', 0, '', 0);
        $this->ln();
    }

    function setDatos($datos) {
        $this->datos = $datos;
        //var_dump($this->datos);exit;

    }
    function generarReporte() {
        $this->setFontSubsetting(false);

    }

}

?>