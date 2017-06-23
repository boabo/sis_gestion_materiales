<?php
require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RRequemientoMaterielesIng extends  ReportePDF {
    var $url_archivo;
    public $firma;
    function Header()
    {
        $this->Ln(8);
        $this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        $this->SetFontSize(15);
        $this->SetFont('', 'B');
        $this->MultiCell(105, 25, "\n" . 'REQUERIMIENTO DE MATERIALES' . "\n" . 'INGENIERÍA', 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, 25, "\n" . 'R-OA-01' . "\n" . 'Rev. Original' . "\n" . '03/11/16', 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 17, 15, 36);
    }
    // Page footer
    function reporteRequerimiento()
    {
        /*$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);*/
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
        $this->SetFont('', 'B', 11);
        $this->Cell(15, 0, 'N°', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Número de Parte', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Referencia', 1, 0, 'C', 0, '', 0);
        $this->Cell(61, 0, 'Descripcion', 1, 0, 'C', 0, '', 0);
        $this->Cell(20, 0, 'Cantidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'U/M', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $numero = 1;
        $pagina = 0;
        foreach ($this->datos as $Row) {
            $parte = $Row['nro_parte'];
            $referencia = $Row['referencia'];
            $descripcion = $Row['descripcion'];
            $cantidad = $Row['cantidad_sol'];
            $unidad = $Row['unidad_medida'];
            $tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1" >
    <tr>
        <td width="54"  align="center">$numero</td>
        <td width="123" align="center">$parte</td>
        <td width="124" align="center">$referencia</td>
        <td width="218" align="justify">$descripcion</td>
        <td width="70"  align="center">$cantidad</td>
        <td width="70"  align="center">$unidad</td>
    </tr>
</table>
EOD;
            $this->SetFont('times', '', 9);
            $this->writeHTML($tbl, false, false, false, false, '');
            $numero++;
            $pagina++;
        }
        $this->ln();
        $motivo = $this->datos[0]['motivo_solicitud'];
        $justificacion= $this->datos[0]['justificacion'];
        $tipo_solicitud= $this->datos[0]['tipo_solicitud'];
        $fecha_requerida= $this->datos[0]['fecha_requerida'];
        $observaciones_sol = $this->datos[0]['observaciones_sol'];

        $html = <<<EOD
 <table cellspacing="0" cellpadding="1" border="1">
  <tr>
    <th colspan="2" > <b>Motivo de la Solicitud</b></th>
  </tr>
  <tr>
    <th colspan="2" style="text-align:justify">$motivo</th>
  </tr>
  <tr>
    <td width="220"  align="center" ><b>Justificación de Necesidad</b></td>
    <td width="220"  align="center"><b>Tipo de Solicitud</b></td>
    <td width="220"  align="center"><b>Fecha Requerida de Llegada</b></td>
  </tr>
  <tr>
    <td width="220"  align="center" >$justificacion</td>
    <td width="220"  align="center">$tipo_solicitud</td>
    <td width="220"  align="center">$fecha_requerida</td>
  </tr>
  <tr>
    <th colspan="3" style="text-align:justify"><b>Observaciones:</b> $observaciones_sol</th>
  </tr>
</table>
EOD;
        //var_dump($pagina);exit;
        $this->SetFont('times', '', 11);
        $this->writeHTML($html, true, false, false, false, '');
    /*    $this->firmas();
    }
    function firmas(){*/
        $funcionario_solicitante = $this->datos[0]['desc_funcionario1'];
        $fecha_ab = 'Fecha: '.$this->datos[0]['fecha_fir'];
        $Revisado_vb= $this->datos2[0]['funcionario_bv'];
        $VB_DAC= $this->datos2[1]['funcionario_bv'];
        $Abastecimiento = 'Abastecimiento';
        $esta = 'borrador';

        $fecha_sol ='Fecha: '.$this->datos[0]['fecha_solicitud'];
        $fecha_vb = 'Fecha: '. $this->datos2[0]['fecha_ini'];
        $fecha_dac = 'Fecha: '.$this->datos2[1]['fecha_ini'];

        //var_dump($this->datos3[0]); exit;

        if ($this->datos[0]['estado'] != 'borrador') {
            $qr1 = $this->codigoQr('Funcionario Solicitante: '.$this->datos[0]['desc_funcionario1']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']. "\n" .'Estado: '.$esta."\n".'Fecha de de la Solicitud: '.$this->datos[0]['fecha_solicitud']."\n",'primera_firma');
            $fun =  $funcionario_solicitante;
            $fe_so =$fecha_sol;
        }
        if($this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr2 = $this->codigoQr('Encargado: '.$this->datos2[0]['funcionario_bv']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n".'Estado: '.$this->datos2[0]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$this->datos[0]['fecha_solicitud']."\n",'segunda_firma');
            $frev =  $Revisado_vb;
            $fe_vo=$fecha_vb;
        }
        if( $this->datos[0]['estado_firma'] != 'vobo_aeronavegabilidad' and $this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr3 = $this->codigoQr('Encargado: '.$VB_DAC."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n".'Estado: '.$this->datos2[1]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$this->datos[0]['fecha_solicitud']."\n",'tercera_firma');
            $dac =  $VB_DAC;
            $fe_dac=$fecha_dac;
        }
        if($this->datos[0]['estado'] != 'revision' and $this->datos[0]['estado'] != 'borrador') {
            $qr4 = $this->codigoQr('Encargado: '.$this->datos3[0]['funcionario_bv']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n".'Estado: '.$this->datos3[0]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$this->datos[0]['fecha_solicitud']."\n",'cuarta_firma');
            $fab =  $this->datos3[0]['funcionario_bv'];
            $fe_ab = $fecha_ab;
        }
        $tbl = <<<EOD
        
        <table cellspacing="0" cellpadding="1" border="1">
        <tr>
        <td align="center" > <b>Solicitado Por:</b> $fun</td>
        <td align="center" > <b>V.B. Encargado Mantenimiento:</b> $frev</td>
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
            	<td align="justify"  colspan="2"><b> Evaluado y Analizado por AOC - 121</b> 
            	</td>
        </tr>
        </table>
        
EOD;
        $tbl2 = <<<EOD
      
        <table cellspacing="0" cellpadding="1" border="1">
         <tr>
        <td align="center" > <b>V.B. DAC:</b> $dac</td>
        <td align="center" > <b>Recibido:</b> $fab</td>
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

       /* if ($pagina >= 20 ){
            $this->setY($this->getY() + 30);
        }*/
        $this->SetFont('times', '', 11);
        $this->writeHTML($tbl, true, false, false, false, '');
        if ( $pagina >= 10 && $pagina <= 11){
            $this->setY($this->getY() + 30);
        }

        /*if ($pagina >= 6 && $pagina <= 7 ){
            $this->setY($this->getY() + 33);
        }*/
        /*if ($pagina >= 4 && $pagina <= 5 ){
            $this->setY($this->getY() + 33);
        }*/
        $this->writeHTML($tbl2, true, false, false, false, '');

    }
    function  codigoQr ($cadena,$ruta){
        $barcodeobj = new TCPDF2DBarcode($cadena, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/".$ruta.".png");
            imagedestroy($im);
        } else {
            echo 'An error occurred.';
        }
        $url =  dirname(__FILE__) . "/../../reportes_generados/".$ruta.".png";
        return $url;
    }

    function setDatos($datos,$datos2,$datos3) {
        $this->datos = $datos;
        $this->datos2 = $datos2;
        $this->datos3 = $datos3;

    }
    function generarReporte() {
        $this->SetMargins(15,40,15);
        //$this->SetAutoPageBreak(true,500);

      //  $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->AddPage();
        $this->SetMargins(15,40,15);
        $this->reporteRequerimiento();

    }

}

?>