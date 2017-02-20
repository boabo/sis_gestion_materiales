<?php

class RRequerimientoMaterialesPDF extends  ReportePDF {
    private  $fun = array();

    function Header() {
        //$this->AddPage();
        $this->ln(5);
        $height = 25;
        //cabecera del reporte
        $this->Cell(40, $height, '', 1, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(16);

        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->SetFont('','B');
        $this->MultiCell(105, $height,"\n".'REQUERIMIENTO DE MATERIALES', 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);

        $this->SetFillColor(0, 0, 0, 100);
        $this->SetTextColor(0, 0, 0, 100);
        $this->MultiCell(0, $height,"\n".'R-OA-01'."\n".'Rev. Original'."\n". '03/11/2016', 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__).'/../../pxp/lib'.$_SESSION['_DIR_LOGO'], 17, 15, 36);
        $this->ln(20);
        $this->reporteRequerimiento();

    }

    function reporteRequerimiento()
    {

        $this->ln(12);
        $this->SetFont('times', 'B', 10);

        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->MultiCell(18, 10, 'Fecha de Pedido'. "\n", 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->SetDrawColor(0, 0, 0, 50);
        $this->SetFillColor(0, 0, 0, 100);
        $this->SetTextColor(0, 0, 0, 100);
        $this->MultiCell(20, 10, $this->datos[0]['fecha_solicitud'] . "\n", 1, 'C', 0, '', '');
        $this->SetFont('times', 'B', 10);

        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->MultiCell(18, 10, 'Solicitada por'. "\n", 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);

        $this->SetFillColor(0, 0, 0, 100);
        $this->SetTextColor(0, 0, 0, 100);
        $this->MultiCell(30, 10, $this->datos[0]['desc_funcionario1']. "\n", 1, 'C', 0, '', '');
        $this->SetFont('times', 'B', 10);

        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->MultiCell(18, 10, 'Matricula'. "\n", 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);

        $this->SetFillColor(0, 0, 0, 100);
        $this->SetTextColor(0, 0, 0, 100);
        $this->MultiCell(30, 10, $this->datos[0]['matri']. "\n", 1, 'C', 0, '', '');
        $this->SetFont('times', 'B', 10);

        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->MultiCell(18, 10, 'Numero de pedido'. "\n", 1, 'C', 0, '', '');
        $this->SetFont('times', 'B', 10);

        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255, 0, 0);
        $this->MultiCell(0, 10, $this->datos[0]['nro_tramite']. "\n", 1, 'C', 0, '', '');
        $this->ln(15);
        $this->SetFont('times', 'B', 10);
        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->Cell(15, 0, 'N°', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Número de Parte', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Referencia', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, 'Descripcion', 1, 0, 'C', 0, '', 0);
        $this->Cell(20, 0, 'Cantidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Unidad de Medida', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $numero = 1;
        foreach ($this->datos as $Row) {
            $this->SetFont('times', '', 10);
            $this->SetFillColor(0, 0, 0, 100);
            $this->SetTextColor(0, 0, 0, 100);

            $this->MultiCell(15, 0, $numero. "\n", 1, 'C', 0, '', '');
            $this->MultiCell(35, 0, $Row['nro_parte']. "\n", 1, 'C', 0, '', '');
            $this->MultiCell(35, 0, $Row['referencia']. "\n", 1, 'C', 0, '', '');
            $this->MultiCell(45, 0, $Row['descripcion']. "\n", 1, 'C', 0, '', '');
            $this->MultiCell(20, 0, $Row['cantidad_sol']. "\n", 1, 'C', 0, '', '');
            $this->MultiCell(0, 0, $Row['unidad_medida']. "\n", 1, 'C', 0, '', '');
            $this->ln();
            $numero++;
        }
        $this->ln(5);
        $this->Cell(0, 15, '', 1, 0, 'L', 0, '', 0);
        $this->ln(0.10);
        $this->SetFont('times', 'B', 11);
        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->MultiCell(40, 7, ' Motivo de la Solicitud:', 0, 'L', 0, '', '');
        $this->SetFont('times', '', 11);
        $this->SetFillColor(0, 0, 0, 100);
        $this->SetTextColor(0, 0, 0, 100);
        $this->MultiCell(0, 7, $this->datos[0]['motivo_solicitud']. "\n", 0, 'L', 0, '', '');
        $this->ln(18);
        $this->SetFont('times', 'B', 11);
        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->Cell(65, 0, 'Justificación de Necesidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(65, 0, 'Tipo de Solicitud', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Fecha Requerida de Llegada', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->SetFillColor(0, 0, 0, 100);
        $this->SetTextColor(0, 0, 0, 100);
        $this->Cell(65, 10, $this->datos[0]['justificacion'], 1, 0, 'C', 0, '', 0);
        $this->Cell(65, 10, $this->datos[0]['tipo_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 10, $this->datos[0]['fecha_requerida'], 1, 0, 'C', 0, '', 0);
        $this->ln(15);
        $this->SetFont('times', 'B', 11);
        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->Cell(65, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(65, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Sello Almacenes', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(65, 10, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(65, 10, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(0, 30, '', 1, 0, 'C', 0, '', 0);
        $this->ln(35);
        $this->Cell(0, 15, '', 1, 0, 'L', 0, '', 0);
        $this->ln(0.10);
        $this->SetFont('times', 'B', 11);
        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->MultiCell(30, 7, ' Observaciones:', 0, 'L', 0, '', '');
        $this->SetFont('times', '', 11);
        $this->SetFillColor(0, 0, 0, 100);
        $this->SetTextColor(0, 0, 0, 100);
        $this->MultiCell(0, 7, $this->datos[0]['observaciones_sol']. "\n", 0, 'L', 0, '', '');
        $this->ln(25);

        $this->SetFont('times', 'B', 11);
        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->Cell(0, 0, 'Sello y Firma Solicitante', 0, 0, 'C', 0, '', 0);
        $this->ln();




    }

    function setDatos($datos,$datos2) {
        $this->datos = $datos;
        $this->datos2 = $datos2;
    }
    function generarReporte() {
        $this->setFontSubsetting(false);
        $this->AddPage();
    }

}

?>