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
        $fecha = date_create($this->datos[0]['fecha_soli']);
        $fecha_base = date_create('01-04-2019');
        if ($fecha >= $fecha_base) {
        $this->MultiCell(105, $height,  "ALMACENES CONSUMIBLES O ROTABLES\n(ESPECIFICACIÓN TÉCNICA)", 0, 'C', 0, '', '');
      }else{
        $this->MultiCell(105, $height,  "ALMACENES CONSUMIBLES O ROTABLES", 0, 'C', 0, '', '');
      }

        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 17, 10, 36);
    }
    function Footer() {
        $this->setY(-15);
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        //set style for cell border
        $line_width = 0.85 / $this->getScaleFactor();
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
        $this->Ln(2);
        $cur_y = $this->GetY();
        //$this->Cell($ancho, 0, 'Generado por XPHS', 'T', 0, 'L');
        $this->Cell($ancho, 0, 'Usuario: '.$_SESSION['_LOGIN'], '', 0, 'L');
        $pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
        $this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
        $this->Cell($ancho, 0, $_SESSION['_REP_NOMBRE_SISTEMA'], '', 0, 'R');
        $this->Ln();
        //   $fecha_rep = date("d-m-Y H:i:s");
        //  $this->Cell($ancho, 0, "Fecha : ".$fecha_rep, '', 0, 'L');
        $this->Ln($line_width);
        $this->Ln();
        $barcode = $this->getBarcode();
        $style = array(
            'position' => $this->rtl?'R':'L',
            'align' => $this->rtl?'R':'L',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false,
            'text' => false,
            'position' => 'R'
        );
        $this->write1DBarcode($barcode, 'C128B', $ancho*2, $cur_y + $line_width+5, '', (($this->getFooterMargin() / 3) - $line_width), 0.3, $style, '');

    }
    function reporteRequerimiento()
    {
      $fecha = date_create($this->datos[0]['fecha_soli']);
      $fecha_base = date_create('01-04-2019');
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
        $tamano_letra = 11;
        $cantidad_caracteres = strlen($this->datos[0]['observaciones_sol']);
        if ($cantidad_caracteres >= 747) {
            $tamano_letra = 8;
        }
        $this->SetFont('times', '', 11);
        if ($fecha >= $fecha_base) {
        $this->Cell(0, 6, ' Condición:'.' '.$this->datos[0]['condicion'], 1, 1, 'L', 0, '', 0);
        if ($this->datos[0]['fecha_soli'] >= $this->datos[0]['fecha_salida']) {
          if ($this->datos[0]['tipo_de_adjudicacion'] == 'Ninguno') {
            $this->MultiCell(0, 6, ' <b>Método de selección de adjudicación: </b>'.$this->datos[0]['metodo_de_adjudicación'], 1, 'L', 0, 1, '', '',true, 0, true);
          } else {
            $this->MultiCell(0, 6, ' <b>Método de selección de adjudicación: </b>'.$this->datos[0]['metodo_de_adjudicación'].'  <b>Tipo de adjudicación: </b>'.$this->datos[0]['tipo_de_adjudicacion'], 1, 'L', 0, 1, '', '',true, 0, true);
          }
        }
        $this->ln();
        }
        $this->MultiCell(0, 10, ' Motivo: ' . $this->datos[0]['motivo_solicitud'] . "\n", 1, 'J', 0, '', '');
        $this->ln();
        $this->SetFont('times', '', $tamano_letra);
        $this->MultiCell(0, 10, ' Observaciones: ' . $this->datos[0]['observaciones_sol'] . "\n", 1, 'J', 0, '', '');
        $this->ln(13);
        $this->SetFont('times', 'B', 11);

        $salto='';
        if ($cantidad_caracteres >= 200 && $cantidad_caracteres < 300){
            $salto= $this->Ln(4);
        }elseif($cantidad_caracteres >= 300 && $cantidad_caracteres < 500){
            $salto = $this->Ln(10);
        }elseif ($cantidad_caracteres >= 500  && $cantidad_caracteres < 600 ) {
            $salto = $this->Ln(15);
        }elseif ($cantidad_caracteres >= 600  && $cantidad_caracteres < 747 ) {
            $salto = $this->Ln(19);
        }elseif ($cantidad_caracteres >= 747 ) {
            $salto = $this->Ln(17);
        }

        if ($fecha >= $fecha_base) {
            $salto;
        $this->Cell(0, 6, ' Especificación Técnica Material a Solicitar', 1, 1, 'L', 0, '', 0);
      }else{
        $salto;
        $this->Cell(0, 6, ' Detalle', 1, 1, 'L', 0, '', 0);
      }
        $this->ln(0.10);
        $this->SetFont('', 'B', 10);

        if ($this->datos[0]['fecha_soli'] >= $this->datos[0]['fecha_salida']) {
        $conf_det_tablewidths = array(15, 45, 50, 55, 55);
        $conf_det_tablealigns = array('C', 'C', 'C', 'C', 'C', 'C');

        $this->tablewidths = $conf_det_tablewidths;
        $this->tablealigns = $conf_det_tablealigns;


        $RowArray = array(

            'Nro.',
            'Número de Parte',
            'Número Parte Alterno',
            'Referencia',
            'Descripcion',
            'Cantidad',
            'Unidad Medida'
        );

        $this->MultiRow($RowArray, false, 1);
        $this->SetFont('', '', 10);
        $conf_det_tablewidths = array(15, 45, 50, 55, 55);
        $conf_det_tablealigns = array('C', 'C', 'C', 'C', 'C', 'C');
      } else {
        $conf_det_tablewidths = array(45, 50, 55, 55);
        $conf_det_tablealigns = array('C', 'C', 'C', 'C', 'C');

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
            $conf_det_tablewidths = array(45, 50, 55, 55);
            $conf_det_tablealigns = array('C', 'C', 'C', 'C');
      }

        $this->tablewidths = $conf_det_tablewidths;
        $this->tablealigns = $conf_det_tablealigns;
        $y = 165;
        if ($this->datos[0]['fecha_soli'] >= $this->datos[0]['fecha_salida']) {
        $itemnum = 1;
        foreach ($this->datos as $Row) {

            $RowArray = array(
                'Nro.' => $itemnum,
                'Número de Parte' => $Row['nro_parte'],
                'Número Parte Alterno' => $Row['pn_cotizacion'],
                'Referencia' => $Row['referencia'],
                'Descripcion' => $Row['descripcion'],
                'Cantidad' => $Row['cantidad_sol'],
                'Unidad Medida' => $Row['unidad_medida']
            );
            $this->MultiRow($RowArray);
            $itemnum ++;
        }
      } else {
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
      }

        $this->ln();
        $RT = 2;
        if (count($this->datos) > 8) {
            $RT= 40;
        }
        $this->ln($RT);
        $esta = 'borrador';
        $funcionario_solicitante = $this->datos[0]['desc_funcionario1'];

        if ($this->datos[0]['estado'] != 'borrador') {

            $qr1 = $this->codigoQr('Funcionario Solicitante: ' . $this->datos[0]['desc_funcionario1'] . "\n" . 'Nro. Pedido: ' . $this->datos[0]['nro_tramite'] . "\n" . 'Tipo Solicitud: ' . $this->datos[0]['tipo_solicitud'] . "\n" ,'primera_firma');
            $fun =  $funcionario_solicitante;
        }
        if($this->datos[0]['estado'] != 'revision' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr2 = $this->codigoQr('Encargado: '.$this->datos2[0]['visto_ag']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n",'csegunda_firma');
            $frev =  $this->datos2[0]['visto_ag'];
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
    function generarReporte() {
        $this->SetMargins(15,40,15);
        $this->setFontSubsetting(false);
        $this->AddPage();
        $this->SetMargins(15,40,15);
        $this->reporteRequerimiento();


    }
}
?>
