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
        $this->SetFont('', '', 10);
        $conf_det_tablewidths = array(15,35, 35, 61, 20,20);
        $conf_det_tablealigns = array('C', 'C', 'C', 'C', 'C', 'C');

        $this->tablewidths = $conf_det_tablewidths;
        $this->tablealigns = $conf_det_tablealigns;


        $RowArray = array(
            'Nro',
            'Número de Parte',
            'Referencia',
            'Descripcion',
            'Cantidad',
            'U/M'
        );
        $numero = 1;
        $this->MultiRow($RowArray, false, 1);

        $this->SetFont('', '', 10);
        $conf_det_tablewidths = array(15,35, 35, 61, 20,20);
        $conf_det_tablealigns = array('C', 'C', 'C', 'J', 'C', 'C');
        $this->tablewidths = $conf_det_tablewidths;
        $this->tablealigns = $conf_det_tablealigns;

        foreach ($this->datos as $Row) {

            $RowArray = array(
                'Nro '=> $numero,
                'Número de Parte' => $Row['nro_parte'],
                'Referencia'=> $Row['referencia'],
                'Descripcion'=> $Row['descripcion']."\n",
                'Cantidad'=> $Row['cantidad_sol'],
                'Unidad Medida'=> $Row['unidad_medida']

            );
            $numero++;
            $this->MultiRow($RowArray);

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
        if (count($this->datos) > 3) {
             $RT= 30;
        }
        /*if (count($this->datos) > 20) {
            $RT= 50;
        }*/
        if (count($this->datos) > 6){
            $RT=0;
        }
        if (count($this->datos) > 7){
            $RT2=50;
        } if (count($this->datos) > 11){
        $RT2=10;
    }
        $this->ln($RT2);

        $esta = 'borrador';
        $cadena_qr = 'Funcionario Solicitante: ' . $this->datos[0]['desc_funcionario1'] . "\n" . 'Nro. Pedido: ' . $this->datos[0]['nro_tramite'] . "\n" . 'Tipo Solicitud: ' . $this->datos[0]['tipo_solicitud'] . "\n" . 'Estado: ' . $esta . "\n" . 'Fecha de de la Solicitud: ' . $this->datos[0]['fecha_solicitud'] . "\n";

        //$cadena_qr = 'Encargado: '.$Revisado_vb."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n".'Estado: '.$this->datos2[0]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$this->datos[0]['fecha_solicitud']."\n";

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
        $ur2 =  dirname(__FILE__) . "/../../reportes_generados/". $this->objParam->getParametro('nombre_archivo') . ".png";
        $ur3=  dirname(__FILE__) . "/../../reportes_generados/" . $this->objParam->getParametro('nombre_archivo') . ".png";

        $funcionario_solicitante = $this->datos[0]['desc_funcionario1'];

        $Revisado_vb= $this->datos2[0]['funcionario_bv'];
        $VB_DAC= $this->datos2[1]['funcionario_bv'];
        $Abastecimiento = 'Abastecimiento';//$this->datos2[0]['funcionario_bv'];

        if ($this->datos[0]['estado'] != 'borrador') {

           $qr1 = $ur;
           $fun =  $funcionario_solicitante;
        }
        if($this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr2 = $ur2;
            $frev =  $Revisado_vb;

        }

        if( $this->datos[0]['estado_firma'] != 'vobo_aeronavegabilidad' and $this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr3 = $ur3;
            $dac =  $VB_DAC;

        }

        if($this->datos[0]['estado'] != 'revision' and $this->datos[0]['estado'] != 'borrador') {
            $qr4 = $ur;
            $fab =  $Abastecimiento;
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
            <br><br>
        </td>
        <td align="center" >
        <br><br>
            <img  style="width: 100px;" src="$qr2" alt="Logo">
            <br><br>
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
            <br><br>
            </td>
        <td align="center" >
        <br><br>
            <img  style="width: 100px;" src="$qr4" alt="Logo">
            <br><br>
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