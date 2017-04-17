<?php
require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RRequemientoMaterielesIng extends  ReportePDF {
    var $url_archivo;
    public $firma;
    function Header()
    {
        $height = 25;
        $this->ln(5);
        $this->MultiCell(40, $height, '', 1, 'C', 0, '', '');
        //$this->Cell(40, $height, '', 1, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(15);
        $this->SetFont('', 'B');
        $this->MultiCell(105, $height, "\n" . 'REQUERIMIENTO DE MATERIALES' . "\n" . 'INGENIERÍA', 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, $height, "\n" . 'R-OA-01' . "\n" . 'Rev. Original' . "\n" . '03/11/16', 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 17, 15, 36);
        $this->Ln(20);
    }

    function reporteRequerimiento()
    {

        $this->SetFont('times', 'B', 11);
        $this->Cell(60, 7, ' Datos Generales', 1, 0, 'L', 0, '', 0);
        $this->SetFont('times', '', 11);
        $this->Cell(85, 7, ' Nro: ' . $this->datos[0]['justificacion'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7, $this->datos[0]['nro_justificacion'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(40, 0, 'Fecha: '.$this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(75, 0, 'Matrícula: '.$this->datos[0]['matri'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Pedido N°: '.$this->datos[0]['nro_tramite'], 1, 1, 'C', 0, '', 0);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Material a Solicitar', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('', 'B', 12);
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

        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Motivo de la Solicitud', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 7, $this->datos[0]['motivo_solicitud'] . "\n", 1, 'J', 0, '', '');
        $this->ln(10);
        $this->SetFont('times', '', 11);
        $this->Cell(65, 0, 'Justificación de Necesidad:', 1, 0, 'C', 0, '', 0);
        $this->Cell(65, 0, 'Tipo de Solicitud:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Fecha Requerida de Llegada:', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(65, 7, $this->datos[0]['justificacion'], 1, 0, 'C', 0, '', 0);
        $this->Cell(65, 7, $this->datos[0]['tipo_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7, $this->datos[0]['fecha_requerida'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, 'Observaciones: ' . $this->datos[0]['observaciones_sol'] . "\n", 1, 'J', 0, '', '');
        $RT = 0;
        $RT2=10;
        $this->ln($RT2);


        $funcionario_solicitante = $this->datos[0]['desc_funcionario1'];
        $fecha_ab = 'Fecha: '.$this->datos[0]['fecha_fir'];
        $Revisado_vb= $this->datos2[0]['funcionario_bv'];
        $VB_DAC= $this->datos2[1]['funcionario_bv'];
        $Abastecimiento = 'Abastecimiento';//$this->datos2[0]['funcionario_bv'];
        $esta = 'borrador';
        $fecha_sol ='Fecha: '.$this->datos[0]['fecha_solicitud'];
        $fecha_vb = 'Fecha: '. $this->datos2[0]['fecha_ini'];
        $fecha_dac = 'Fecha: '.$this->datos2[1]['fecha_ini'];

        $cadena_qr = 'Funcionario Solicitante: ' . $this->datos[0]['desc_funcionario1'] . "\n" . 'Nro. Pedido: ' . $this->datos[0]['nro_tramite'] . "\n" . 'Tipo Solicitud: ' . $this->datos[0]['tipo_solicitud'] . "\n" . 'Estado: ' . $esta . "\n" . 'Fecha de de la Solicitud: ' . $this->datos[0]['fecha_solicitud'] . "\n";
        $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');
        //$nombre_archivo = $this->objParam->getParametro('nombre_archivo');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/".$this->objParam->getParametro('nombre_archivo').".png");
            imagedestroy($im);

        } else {
            echo 'An error occurred.';
        }
        $ur =  dirname(__FILE__) . "/../../reportes_generados/". $this->objParam->getParametro('nombre_archivo') . ".png";

        $cadena_qr2 = 'Encargado: '.$this->datos2[0]['funcionario_bv']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n".'Estado: '.$this->datos2[0]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$this->datos[0]['fecha_solicitud']."\n";

        $barcodeobj2 = new TCPDF2DBarcode($cadena_qr2, 'QRCODE,M');
        $png2 = $barcodeobj2->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im2 = imagecreatefromstring($png2);
        if ($im2 !== false) {
            header('Content-Type: image/png');
            imagepng($im2, dirname(__FILE__) . "/../../reportes_generados/".$this->objParam->getParametro('nombre_archivo_vb').".png");
            imagedestroy($im2);

        } else {
            echo 'An error occurred.';
        }
        $ur2 =  dirname(__FILE__) . "/../../reportes_generados/". $this->objParam->getParametro('nombre_archivo_vb') . ".png";

        $cadena_qr3 = 'Encargado: '.$VB_DAC."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n".'Estado: '.$this->datos2[1]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$this->datos[0]['fecha_solicitud']."\n";
        $barcodeobj3 = new TCPDF2DBarcode($cadena_qr3, 'QRCODE,M');
        $png3 = $barcodeobj3->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im3 = imagecreatefromstring($png3);
        if ($im3 !== false) {
            header('Content-Type: image/png');
            imagepng($im3, dirname(__FILE__) . "/../../reportes_generados/".$this->objParam->getParametro('nombre_archivo3').".png");
            imagedestroy($im3);

        } else {
            echo 'An error occurred.';
        }

        $ur3=  dirname(__FILE__) . "/../../reportes_generados/" . $this->objParam->getParametro('nombre_archivo3') . ".png";


        if ($this->datos[0]['estado'] != 'borrador') {

           $qr1 = $ur;
           $fun =  $funcionario_solicitante;
           $fe_so =$fecha_sol;
        }
        if($this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr2 = $ur2;
            $frev =  $Revisado_vb;
            $fe_vo=$fecha_vb;
        }

        if( $this->datos[0]['estado_firma'] != 'vobo_aeronavegabilidad' and $this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr3 = $ur3;
            $dac =  $VB_DAC;
            $fe_dac=$fecha_dac;

        }

        if($this->datos[0]['estado'] != 'revision' and $this->datos[0]['estado'] != 'borrador') {
            $qr4 = $ur;
            $fab =  $Abastecimiento;
            $fe_ab = $fecha_ab;
        }
            $tbl = <<<EOD
        
        <table cellspacing="0" cellpadding="1" border="1">
        <tr>
        <td align="center" > Solicitado Por: $fun</td>
        <td align="center" > V.B. Encargado Mantenimiento: $frev</td>
        </tr>
        <tr>
           <td align="center" > 
            <br><br>
            <img  style="width: 100px;" src="$qr1" alt="Logo">
            <br>  $fe_so <br>
        </td>
        <td align="center" >
        <br><br>
            <img  style="width: 100px;" src="$qr2" alt="Logo">
            <br> $fe_vo <br>
         </td>
         </tr>
         <tr>
            	<td align="justify"  colspan="2">Evaluado y Analizado por AOC - 121 
            	<br></td>
        </tr>

        </table>
        
EOD;
        $tbl2 = <<<EOD
        
        <table cellspacing="0" cellpadding="1" border="1">
         <tr>
        <td align="center" > V.B. DAC: $dac</td>
        <td align="center" >  Recibido: $fab</td>
        </tr>
        <tr>
        <td align="center" > 
            <br><br>
            <img  style="width: 100px;" src="$qr3" alt="Logo">
            <br> $fe_dac<br>
            </td>
        <td align="center" >
        <br><br>
            <img  style="width: 100px;" src="$qr4" alt="Logo">
            <br>$fe_ab <br>
         </td>
         </tr>
        
        </table>
        
EOD;
        $this->writeHTML($tbl, true, false, false, false, '');
        $this->ln($RT);
        $this->writeHTML($tbl2, true, false, false, false, '');
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