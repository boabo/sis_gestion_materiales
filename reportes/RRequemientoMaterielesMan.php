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
        $this->MultiCell(105, $height,"\n".'COMITÉ DE EVALUACIÓN TÉCNICA'."\n".'REPUESTOS AERONÁUTICOS' , 0, 'C', 0, '', '');
        $y = $this->getY();
        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 10, $y, 36);
        $this->ln(25);
        $this->customy = $this->getY();
        $this->reporteRequerimiento();
    }
    function reporteRequerimiento(){
        $this->SetFont('times', 'B', 11);
        $this->Cell(75, 7, ' Datos Generales', 1, 0, 'L', 0, '', 0);
        $this->Cell(60, 7, ' Número Tramite', 1, 0, 'L', 0, '', 0);
        $this->Cell(0, 7, $this->datos[0]['nro_tramite'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(15, 7, ' Fecha', 1, 0, 'C', 0, '', 0);
        $this->Cell(60, 7,  $this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        if($this->datos[0]['flota'] == 'FLOTA') {
            $this->Cell(60, 7,  '  '.$this->datos[0]['matricula'], 1, 0, 'L', 0, '', 0);
        }else{
            $this->Cell(60, 7, ' Matrícula: ' . $this->datos[0]['matri'], 1, 0, 'L', 0, '', 0);
        }
        if($this->datos[0]['flota'] == 'FLOTA') {
            $this->Cell(0, 7, '', 1, 0, 'C', 0, '', 0);
        }else{
            $this->Cell(0, 7, $this->datos[0]['matricula'], 1, 0, 'C', 0, '', 0);
        }
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
            $this->Cell(0, 7, ' ' .$Row['nro_parte'], 1, 0, 'L', 0, '', 0);
        }
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(36, 7, ' Cantidad', 1, 0, 'L', 0, '', 0);
        foreach ($this->datos as $Row) {
            $this->Cell(35, 7, ' ' .$Row['cantidad_sol'], 0, 0, 'L', 0, '', 0);
        }
        $this->ln(0.10);
        $this->Cell(36, 7, '', 0, 0, 'L', 0, '', 0);

        $this->Cell(0, 7, '', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Motivo de la Solicitud', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, $this->datos[0]['motivo_solicitud']."\n", 1, 'J', 0, '', '');
        $this->ln(12);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Justificación de Necesidad', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(40, 7, 'Tipo de Falla', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 7,  'Tipo de Reporte', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 7,  'Tipo de Solicitud', 1, 0, 'C', 0, '', 0);
        $this->Cell(25, 7,  'MEL', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7,  'Due Date ', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(40, 15,$this->datos[0]['tipo_falla'] , 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 15, $this->datos[0]['tipo_reporte'] , 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 15, $this->datos[0]['tipo_solicitud'] , 1, 0, 'C', 0, '', 0);
        $this->Cell(25, 15, $this->datos[0]['mel'] , 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 15, $this->datos[0]['fecha_requerida'] , 1, 0, 'C', 0, '', 0);

        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, ' Observaciones: '. $this->datos[0]['observaciones_sol']."\n", 1, 'J', 0, '', '');
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(0, 7, ' Evaluado y Analizado Por: ', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'B', 11);

        $this->Cell(25, 40, '', 1, 0, 'C', 0, '', 0);
        if($this->datos[0]['estado'] == 'vobo_area' or $this->datos[0]['estado'] == 'vobo_aeronavegabilidad'  or $this->datos[0]['estado'] == 'revision'or $this->datos[0]['estado'] == 'cotizacion' or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {

                $this->MultiCell(65, 0, 'Unidad C & S/Control Producción' . "\n" . $this->datos[0]['desc_funcionario1'], 0, 'C', 0, '', '');
        }else{
            $this->MultiCell(65, 0, 'Unidad C & S/Control Producción', 0, 'C', 0, '', '');
        }
        if($this->datos[0]['estado'] == 'vobo_aeronavegabilidad' or $this->datos[0]['estado'] == 'revision'or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            if($this->datos[0]['fecha_solicitud'] < '13/02/2017' ) {
                $this->MultiCell(65, 0, 'Gerencia de Mantenimiento' . "\n" . $this->datos2[0]['funcionario_bv'], 0, 'C', 0, '', '');
            }else{
                $this->MultiCell(65, 0, 'Gerencia de Mantenimiento' . "\n" . $this->datos2[1]['funcionario_bv'], 0, 'C', 0, '', '');

            }
        }else{
            $this->MultiCell(65, 0, 'Gerencia de Mantenimiento',  0, 'C', 0, '', '');
        }

        $this->Cell(0, 0,  '', 0, 0, 'C', 0, '', 0);
        $this->ln(0.10);
        $this->Cell(25, 40, '', 1, 0, 'C', 0, '', 0);
        $this->Cell(65, 40, '', 1, 0, 'C', 0, '', 0);
        $this->Cell(65, 40,  '', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 40,  '', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(0, 7, ' Evaluado y Analizado por OAC - 121', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'B', 11);
        if($this->datos[0]['estado'] == 'revision'or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            if($this->datos[0]['fecha_solicitud'] < '13/02/2017' ) {
                $this->MultiCell(90, 0, 'V.B. DAC' . "\n" . $this->datos2[1]['funcionario_bv'], 0, 'C', 0, '', '');
            }   elseif ($this->datos[0]['fecha_solicitud'] < '23/02/2017'){
                $this->MultiCell(90, 0, 'V.B. DAC' . "\n" . $this->datos2[0]['funcionario_bv'], 0, 'C', 0, '', '');
             }
            else{
                $this->MultiCell(90, 0, 'V.B. DAC' . "\n" . $this->datos2[2]['funcionario_bv'], 0, 'C', 0, '', '');
            }
        }else{
            $this->MultiCell(90, 0, 'V.B. DAC', 0, 'C', 0, '', '');
       }
        if($this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            if($this->datos[0]['fecha_solicitud'] < '13/02/2017' ) {
                $this->MultiCell(0, 0, 'Recibido Abastecimiento' . "\n" . $this->datos2[2]['funcionario_bv'], 0, 'C', 0, '', '');
            } elseif ($this->datos[0]['fecha_solicitud'] < '23/02/2017'){
                $this->MultiCell(0, 0, 'Recibido Abastecimiento' . "\n" . $this->datos2[1]['funcionario_bv'], 0, 'C', 0, '', '');
            }
            else{
                $this->MultiCell(0, 0, 'Recibido Abastecimiento' . "\n" . $this->datos2[3]['funcionario_bv'], 0, 'C', 0, '', '');
            }
        }else{
            $this->MultiCell(0, 0, 'Recibido Abastecimiento', 0, 'C', 0, '', '');
        }
        $this->ln(0.10);
        $this->Cell(90, 40, '', 1, 0, 'L', 0, '', 0);
        $this->Cell(0, 40,  '', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
       /* if($this->datos[0]['estado'] == 'revision'or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(90, 7, ' Fecha: ' . $this->datos2[1]['fecha_ini'], 1, 0, 'L', 0, '', 0);
        }*///else{
            $this->Cell(90, 7, ' Fecha: ', 1, 0, 'L', 0, '', 0);
       // }
        if($this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(0, 7, ' Fecha:' . $this->datos2[2]['fecha_ini'], 1, 0, 'L', 0, '', 0);
        }else{
            $this->Cell(0, 7, ' Fecha:', 1, 0, 'L', 0, '', 0);
        }
        $this->ln();

        $fun = $this->datos[0]['desc_funcionario1'];
        $num = $this->datos[0]['nro_tramite'];
        $tipo= $this->datos[0]['tipo_solicitud'];
        $esta = 'borrador';//$this->datos[0]['estado'];
        $fecha =$this->datos[0]['fecha_solicitud'];

        $html = 'Funcionario Solicitante: '.$fun."\n".'Nro. Pedido: '.$num."\n".'Tipo Solicitud: '.$tipo."\n".'Estado: '.$esta."\n".'Fecha de de la Solicitud: '.$fecha."\n";
        // set style for barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );




        if($this->datos[0]['estado'] == 'vobo_area' or $this->datos[0]['estado'] == 'vobo_aeronavegabilidad'   or $this->datos[0]['estado'] == 'revision'or $this->datos[0]['estado'] == 'cotizacion' or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado' ) {

            $this->write2DBarcode($html, 'QRCODE,L', 60, 155, 25, 25, $style, 'N');
        }
        $html3 = 'Encargado: '.$this->datos2[0]['funcionario_bv']."\n".'Nro. Pedido: '.$num."\n".'Tipo Solicitud: '.$tipo."\n".'Estado: '.$this->datos2[0]['estado']."\n".'Fecha de de la Solicitud: '.$fecha."\n";


        if($this->datos[0]['estado'] == 'vobo_aeronavegabilidad'  or  $this->datos[0]['estado'] == 'revision'or $this->datos[0]['estado'] == 'cotizacion' or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {

            $this->write2DBarcode($html3, 'QRCODE,L', 125, 155, 25, 25, $style, 'N');
        }

        $html4 = 'Encargado: '.$this->datos2[2]['funcionario_bv']."\n".'Nro. Pedido: '.$num."\n".'Tipo Solicitud: '.$tipo."\n".'Estado: '.$this->datos2[2]['estado']."\n".'Fecha de de la Solicitud: '.$fecha."\n";
        if($this->datos[0]['estado'] == 'revision'or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->write2DBarcode($html4, 'QRCODE,L', 50, 200, 25, 25, $style, 'N');
        }
        $html5 = 'Encargado: '.$this->datos2[1]['funcionario_bv']."\n".'Nro. Pedido: '.$num."\n".'Tipo Solicitud: '.$tipo."\n".'Estado: '.$this->datos2[1]['estado']."\n".'Fecha de de la Solicitud: '.$fecha."\n";
        if($this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->write2DBarcode($html5, 'QRCODE,L', 140, 200, 25, 25, $style, 'N');
        }


    }

    function setDatos($datos,$datos2) {
        $this->datos = $datos;
        $this->datos2 = $datos2;
    }
    function generarReporte() {
        $this->setFontSubsetting(false);

    }

}

?>