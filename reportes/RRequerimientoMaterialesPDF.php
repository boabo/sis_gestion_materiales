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



    }

    function reporteRequerimiento()
    {

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

        $this->SetFont('times', 'B', 12);
        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->Cell(15, 0, 'N°', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Número de Parte', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Referencia', 1, 0, 'C', 0, '', 0);
        $this->Cell(61, 0, 'Descripcion', 1, 0, 'C', 0, '', 0);
        $this->Cell(20, 0, 'Cantidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'U/M', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $numero = 1;

        foreach ($this->datos as $Row) {
              $parte = $Row['nro_parte'];
              $referencia = $Row['referencia'];
              $descripcion = $Row['descripcion'];
              $cantidad = $Row['cantidad_sol'];
              $unidad = $Row['unidad_medida'];
            $this->SetFont('', '', 12);
            $this->SetDrawColor(0, 0, 0, 50);
            $this->SetFillColor(0, 0, 0, 100);
            $this->SetTextColor(0, 0, 0, 100);
            $tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td width="54" align="center">$numero</td>
        <td width="123" align="center">$parte</td>
        <td width="124" align="center">$referencia</td>
        <td width="218" align="justify"> $descripcion</td>
        <td width="70" align="center">$cantidad</td>
        <td width="70" align="center">$unidad</td>
    </tr>
  

</table>
EOD;

            $this->writeHTML($tbl, false, false, false, false, '');
            $numero++;


        }




        $this->ln(10);
        $this->Cell(0, 15, '', 1, 0, 'L', 0, '', 0);
        $this->ln(0.10);
        $this->SetFont('times', 'B', 11);
        $this->SetFillColor(0, 0, 185);
        $this->SetTextColor(0, 0, 185);
        $this->MultiCell(40, 7, 'Motivo de la Solicitud:', 0, 'J', 0, '', '');
        $this->SetFont('times', '', 11);
        $this->SetFillColor(0, 0, 0, 100);
        $this->SetTextColor(0, 0, 0, 100);
        $this->MultiCell(0, 7, $this->datos[0]['motivo_solicitud']. "\n", 0, 'J', 0, '', '');
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
        $this->SetMargins(15,40,15);
        $this->setFontSubsetting(false);
        $this->AddPage();
        $this->SetMargins(15,40,15);
        $this->reporteRequerimiento();
    }

}

?>