<?php
require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RCuandroComparativoPDF extends  ReportePDF
{
    private $proveedor = array();
    private  $nro;
    function Header()
    {
        $height = 15;
        //$this->ln(8);
        $this->MultiCell(35, $height, '', 0, 'C', 0, '', '');
        $this->SetFontSize(11);
        $this->SetFont('', 'B');
        $this->MultiCell(125, $height, "\n" .'CUADRO COMPARATIVO DE OFERTA PARA ADQUISICIÓN '."\n".'DE BIENES Y SERVICIOS EN EL EXTRANJERO', 0, 'C', 0, '', '');
        $this->MultiCell(0, $height, '', 0, 'C', 0, '', '');
        $this->ln(15);
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, 5, '(Decreto Supremo N° 26688) Versión I', 0, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png', 17, 15, 36);
    }
    function Footer() {
        $this->Ln();
        // $this->setY(-15);
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        //set style for cell border
        $line_width = 0.85 / $this->getScaleFactor();
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
        $this->Ln(2);
        $cur_y = $this->GetY();
        //$this->Cell($ancho, 0, 'Generado por XPHS', 'T', 0, 'L');
        $this->Cell($ancho, 0, '', '', 0, 'L');
        $pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
        $this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
        //$this->Cell($ancho, 0, $_SESSION['_REP_NOMBRE_SISTEMA'], '', 0, 'R');
        $this->Ln();
        foreach ($this->datos as $Key) {
            if($Key['adjudicado'] == 'si') {
                $this->MultiCell(0,7, ''.$Key['pie_pag']."\n" , 0, 'J', 0, '', '');
               // $this->writeHTML('<p align="justify">Enviado a: '.$Key['pie_pag'].'</p>', true, false, false, false, '');

            }
        }
    }
    function reporteCuadroComparativo(){
        $this->SetFont('times', 'B', 10);
        //var_dump($this->datos4[0]['lista_proverod']);exit;
        $this->writeHTML('<p align="justify">Enviado a: '.$this->datos4[0]['lista_proverod'].'</p> <br>', true, false, false, false, '');

        //$this->Ln();
        foreach ($this->datos as  $val)
        {
            if (  !array_key_exists($val['desc_proveedor'], $this->proveedor)
                ||!array_key_exists($val['parte'], $this->proveedor[$val['desc_proveedor']])
                ||!array_key_exists($val['descripcion'],$this->proveedor[$val['desc_proveedor']][$val['parte']])
                ||!array_key_exists($val['cantidad'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']])
                ||!array_key_exists($val['cd'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']][$val['cantidad']])
                ||!array_key_exists($val['precio_unitario'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']][$val['cantidad']][$val['cd']])
                ||!array_key_exists($val['precio_unitario_mb'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']][$val['cantidad']][$val['cd']][$val['precio_unitario']])
                ||!array_key_exists($val['codigo_tipo'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']][$val['descripcion']][$val['cantidad']][$val['cd']][$val['precio_unitario']][$val['precio_unitario_mb']])
                ||!array_key_exists($val['monto_total'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']][$val['cantidad']][$val['cd']][$val['precio_unitario']][$val['precio_unitario_mb']][$val['codigo_tipo']])
                ||!array_key_exists($val['recomendacion'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']][$val['cantidad']][$val['cd']][$val['precio_unitario']][$val['precio_unitario_mb']][$val['codigo_tipo']][$val['monto_total']])

            ) {

                $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']][$val['cantidad']][$val['cd']][$val['precio_unitario']][$val['precio_unitario_mb']][$val['codigo_tipo']][$val['monto_total']][$val['recomendacion']]= 1;
            } else {

                $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['descripcion']][$val['cantidad']][$val['cd']][$val['precio_unitario']][$val['precio_unitario_mb']][$val['codigo_tipo']][$val['monto_total']][$val['recomendacion']]++;
            }

        }


        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $cont = 1;
        foreach ($this->proveedor as $proveedor => $value){

            $tbl2 = '
        <table border="1">
        <tr>
        <td colspan="7"  align="center" style="width:100%;" ><b>' . $proveedor . '</b></td>
        </tr>
        <tr>
          <td align="center" style="width:6%;"><b>N°</b></td> 
          <td align="center" style="width:20%;"><b>Part Number</b></td>
          <td align="center" style="width:22%;"><b>Descripcion</b></td>
          <td align="center" style="width:8%;"><b>QTY</b></td>
          <td align="center" style="width:8%;"><b>CD</b></td>
          <td align="center" style="width:12%;"><b>Precio ($us)</b></td>
          <td align="center" style="width:12%;"><b>Monto Total($us)</b></td>
          <td align="center" style="width:12%;"><b>Tiempo Entrega</b></td>
          
           
        </tr>
        ';
            foreach ($value as $parte => $value2) {

                $tbl2 .='<tr>';
                $tbl2 .= '<td rowspan="1"  align="center" style="width:6%;">' .$cont. '</td> ';
                $tbl2 .= ' <td rowspan="1"  align="center" style="width:20%;">' . $parte . '</td> ';
                foreach ($value2 as $descripcion => $value3) {
                    $tbl2 .= ' <td rowspan="1"  align="center" style="width:22%;">' . $descripcion . '</td> ';
                    foreach ($value3 as $QTY=> $value4) {
                        $tbl2 .= '<td align="center"style="width:8%;">' . $QTY . '</td>';
                        foreach ($value4 as $cd => $value5) {
                           $tbl2 .= '<td align="center"style="width:8%;">' . $cd . '</td>';
                            foreach ($value5 as $Precio => $value6) {
                               $tbl2 .= '<td align="right" style="width:12%;">' . $Precio . '</td>';
                                foreach ($value6 as $Monto => $value7) {
                                   $tbl2 .= '<td align="right" style="width:12%;">' . number_format($Monto,2,",",".") . '</td>';
                                    foreach ($value7 as $dia => $value8) {
                                        $tbl2 .= '<td align="center" style="width:12%;">' . $dia . '</td>';
                                        foreach ($value8 as $total =>$value9){
                                            $tbl2 .='</tr>';
                                            foreach ($value9 as $recomendacion =>$value10){
                                                $rec=$recomendacion;
                                            }
                                        }
                                    }

                                }
                            }
                        }
                    }
                }

                $cont++;
            }


            $cont = $cont - count($this->datos3);
            $tbl2 .= '<tr>
                           <td align="center" style="width:26%;"><b>TOTALES</b></td>
                            <td align="center"style="width:50%;" ></td>
                            <td align="right" style="width:12%; color: red" ><b>'.number_format($total,2,",",".") .'</b></td>
                             
                        </tr>
                        <tr>
                        <td align="justify" style="width:100%;"><b> RECOMENDACION:</b> '.$rec.'</td>
                        </tr>';

            $tbl2 .= '</table>';
            $this->SetFont('times', '', 10);
            $this->writeHTML($tbl2);
        }

        $this->writeHTML('<p align="justify"> OBSERVACIONES:  '.$this->datos4[0]['obs'].'</p> <br>', true, false, false, false, '');
        if ( $this->datos[0]['estado'] != 'cotizacion') {
            $elaborado = $this->datos2[0]['visto_ag'];
        }else{
            $elaborado = ' ';
        }
        if ($this->datos[0]['estado'] != 'comite_unidad_abastecimientos') {
            $revision = $this->datos2[0]['visto_rev'];
        }else{
            $revision= ' ';
        }
        if ($this->datos[0]['estado'] != 'comite_aeronavegabilidad') {
            $aero = $this->datos2[0]['aero'];
        }else{
            $aero = ' ';
        }
        if ($this->datos[0]['estado'] != 'comite_dpto_abastecimientos') {
            $abastecimiento = $this->datos2[0]['visto_abas'];
        }else{
            $abastecimiento = ' ';
        }

        $fun_sol = explode('|',$elaborado);
        $fun_rev = explode('|',$revision);
        $fun_aero = explode('|',$aero);
        $fun_abas= explode('|',$abastecimiento);
        $cuartaFirma=$this->codigoQr($abastecimiento,'cuarta');
        $segundaFirma=$this->codigoQr($revision,'segundo');
        $terceraFirma=$this->codigoQr($aero,'tercer');
        $primeraFirma=$this->codigoQr($elaborado,'primer');


        $tbl =' <table border="2">
         <tbody>
        <tr>    
                <td style="font-family: Calibri;font-size: 11px"align="center"><b>Elaborado por</b><br>'.$fun_sol[0].'</td>
                <td style="font-family: Calibri;font-size: 11px"align="center"><b>Jefe Abastecimientos y suministros</b><br>'.$fun_rev[0].'</td>
        </tr>
        <tr>';
        if ( $this->datos[0]['estado'] != 'cotizacion') {
            $tbl .= '
                <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $primeraFirma . '" alt="Logo"><br></td>';
        }
        if ($this->datos[0]['estado'] != 'comite_unidad_abastecimientos') {
            $tbl .= '<td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $segundaFirma . '" alt="Logo"><br> </td>';
        }
        $tbl .= ' </tr>
         </tbody>
        </table>';
        $tbl1 = ' <table border="2">
         <tbody>
        <tr>    
                <td style="font-family: Calibri;font-size: 11px"align="center"><b>Jefe Aeronavegabilidad Continua</b><br>'.$fun_aero[0].'</td>
                <td style="font-family: Calibri;font-size: 11px"align="center"><b>Jefe Dpto Abastecimiento y Logistica</b><br>'.$fun_abas[0].'</td>
        </tr>
        <tr>';
        if ($this->datos[0]['estado'] != 'comite_aeronavegabilidad') {
            $tbl1 .= '<td align="center"><br><br>';
            $tbl1 .= ' <img  style="width: 95px; height: 95px;" src="' . $terceraFirma . '" alt="Logo">';
            $tbl1 .= '<br></td>';
        }
        if ($this->datos[0]['estado'] != 'comite_dpto_abastecimientos') {
            $tbl1 .= '<td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $cuartaFirma . '" alt="Logo"><br></td>';
        }
        $tbl1 .= '</tr>
         </tbody>
        </table>';

        $this->writeHTML($tbl);
        $this->writeHTML($tbl1);
        $this->ln();
        foreach ($this->datos as $Key) {
            if($Key['adjudicado'] == 'si') {
                if ($Key['fecha_po'] != null) {
                    $this->MultiCell(0, 5, 'Cochabamba ' . $this->fechaLiteral($Key['fecha_po']) . "\n", 0, 'C', 0, '', '');
                }
            }
        }



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
    function fechaLiteral($va){
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $fecha = strftime("%d de %B de %Y", strtotime($va));
        return $fecha;
    }

    function setDatos($datos,$datos2,$datos3,$datos4) {
        $this->datos = $datos;
        $this->datos2 = $datos2;
        $this->datos3 = $datos3;
        $this->datos4 = $datos4;
       // var_dump($this->datos4);exit;

    }
    function generarReporte() {
        $this->SetMargins(15,35,15);
        $this->setFontSubsetting(false);
        $this->AddPage();
        $this->SetMargins(15,35,15);
        $this->reporteCuadroComparativo();

    }

}






?>