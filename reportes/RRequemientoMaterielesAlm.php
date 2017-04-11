<?php
class RRequemientoMaterielesAlm extends  ReportePDF
{
    function Header()
    {
        $this->ln(8);
        $height = 50;
        //cabecera del reporte
        $this->Cell(70, $height, '', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(15);
        $this->SetFont('', 'B');
        $this->MultiCell(105, $height,  'ALMACENES CONSUMIBLES O ROTABLES', 0, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 17, 10, 36);
    }
    function reporteRequerimiento()
    {
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Restocking Request', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(45, 0, 'Documento:', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, $this->datos[0]['nro_tramite'], 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, 'Estado:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, $this->datos[0]['estado'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(45, 0, 'Fecha:', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, $this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, 'Fecha Requerida:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, $this->datos[0]['fecha_requerida'], 1, 1, 'C', 0, '', 0);
        $this->ln(2);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' General', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(45, 0, 'Tipo:', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, $this->datos[0]['tipo_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, 'Solicitante:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, $this->datos[0]['desc_funcionario1'], 1, 1, 'C', 0, '', 0);
        $this->ln(2);
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, ' Motivo: ' . $this->datos[0]['motivo_solicitud'] . "\n", 1, 'J', 0, '', '');
        $this->ln();
        $this->SetFont('times', '', 11);

        $this->MultiCell(0, 10, ' Observaciones: ' . $this->datos[0]['observaciones_sol'] . "\n", 1, 'J', 0, '', '');
        $this->ln(13);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 6, ' Detalle', 1, 1, 'L', 0, '', 0);
        $this->ln(0.10);
        $this->SetFont('', 'B', 10);

        $conf_det_tablewidths = array(45, 45, 50, 55, 25);
        $conf_det_tablealigns = array('C', 'C', 'C', 'C', 'C', 'C');

        $this->tablewidths = $conf_det_tablewidths;
        $this->tablealigns = $conf_det_tablealigns;


        $RowArray = array(

            'Número de Parte',
            'Número Parte Alterno',
            'Referencia',
            'Descripcion',
            'Cantidad',
            'Unidad Medida'
        );
        $this->MultiRow($RowArray, false, 1);
        $this->SetFont('', '', 10);
        $conf_det_tablewidths = array(45, 45, 50, 55, 25);
        $conf_det_tablealigns = array('C', 'C', 'C', 'C', 'C', 'C');
        $this->tablewidths = $conf_det_tablewidths;
        $this->tablealigns = $conf_det_tablealigns;
        $y = 165;
        foreach ($this->datos as $Row) {

            $RowArray = array(
                'Número de Parte' => $Row['nro_parte'],
                'Número Parte Alterno' => $Row['nro_parte_alterno'],
                'Referencia' => $Row['referencia'],
                'Descripcion' => $Row['descripcion'],
                'Cantidad' => $Row['cantidad_sol'],
                'Unidad Medida' => $Row['unidad_medida']
            );
            $this->MultiRow($RowArray);
        }

        $this->ln();
        $RT = 2;
        if (count($this->datos) > 8) {
            $RT= 40;
        }
        $this->ln($RT);

        $esta = 'borrador';
        $cadena_qr = 'Funcionario Solicitante: ' . $this->datos[0]['desc_funcionario1'] . "\n" . 'Nro. Pedido: ' . $this->datos[0]['nro_tramite'] . "\n" . 'Tipo Solicitud: ' . $this->datos[0]['tipo_solicitud'] . "\n" . 'Estado: ' . $esta . "\n" . 'Fecha de de la Solicitud: ' . $this->datos[0]['fecha_solicitud'] . "\n";

        //$cadena_qr = 'Encargado: '.$Revisado_vb."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n".'Estado: '.$this->datos2[0]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$this->datos[0]['fecha_solicitud']."\n";

        $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');
        //$nombre_archivo = $this->objParam->getParametro('nombre_archivo');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/". $this->objParam->getParametro('nombre_archivo'). ".png");
            imagedestroy($im);

        } else {
            echo 'An error occurred.';
        }
        $ur = dirname(__FILE__) . "/../../reportes_generados/". $this->objParam->getParametro('nombre_archivo'). ".png";
        $ur2 = dirname(__FILE__) . "/../../reportes_generados/". $this->objParam->getParametro('nombre_archivo') . ".png";
        
        $funcionario_solicitante = $this->datos[0]['desc_funcionario1'];

        $Revisado_vb= $this->datos[1]['desc_funcionario1'];// revizar


        if ($this->datos[0]['estado'] != 'borrador') {

            $qr1 = $ur;
            $fun =  $funcionario_solicitante;
        }
        if($this->datos[0]['estado'] != 'revision' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr2 = $ur2;
            $frev =  'Pastor Jaime LazarteVillagra';

        }
        $tbl = <<<EOD
        
        <table>
        <tr>
        <td align="center" > Solicitado Por: $fun</td>
        <td align="center" > Revisado Por: $frev </td>
        </tr>
        <tr>
           <td align="center" >     
            <br><br>
            <img  style="width: 95px;" src="$qr1" alt="Logo">
            <br><br>
        </td>
        <td align="center" >
        <br><br>
            <img  style="width: 95px;" src="$qr2" alt="Logo">
            <br><br>
         </td>
         </tr> 
        </table>
        
EOD;
        $this->writeHTML($tbl, true, false, false, false, '');
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