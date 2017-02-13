<?php

class RRequemientoMaterielesIng extends  ReportePDF {
    private  $fun = array();

    function Header() {
        //$this->AddPage();
        $this->ln(5);
        $height = 25;
        //cabecera del reporte
        $this->Cell(40, $height, '', 1, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(15);
        $this->SetFont('','B');
        $this->MultiCell(105, $height,"\n".'REQUERIMIENTO DE MATERIALES'."\n".'INGENIERÍA' , 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, $height,"\n".'R-OA-01'."\n".'Rev. Original'."\n". '03/11/16', 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__).'/../../pxp/lib'.$_SESSION['_DIR_LOGO'], 17, 23, 36);
        $this->ln(20);
        $this->customy = $this->getY();
        $this->reporteRequerimiento();

    }

    function reporteRequerimiento()
    {
        $this->ln(5);
        $this->SetFont('times', 'B', 11);
        $this->Cell(60, 7, ' Datos Generales', 1, 0, 'L', 0, '', 0);
        $this->SetFont('times', '', 11);
        $this->Cell(85, 7, ' Nro: ' . $this->datos[0]['justificacion'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7, $this->datos[0]['nro_justificacion'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(15, 0, 'Fecha:', 1, 0, 'C', 0, '', 0);
        $this->Cell(25, 0, $this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(25, 0, 'Matrícula:', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, $this->datos[0]['matri'], 1, 0, 'C', 0, '', 0);
        $this->Cell(25, 0, 'Pedido N°', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, $this->datos[0]['nro_tramite'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Material a Solicitar', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 9);
        $this->Cell(15, 0, 'N°', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Número de Parte', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Referencia', 1, 0, 'C', 0, '', 0);
        $this->Cell(55, 0, 'Descripcion', 1, 0, 'C', 0, '', 0);
        $this->Cell(20, 0, 'Cantidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Unidad de Medida', 1, 0, 'C', 0, '', 0);
        $this->ln();

        $numero = 1;
        $y = 130;
        $y2 = 182;
        foreach ($this->datos as $Row) {
            $this->SetFont('times', '', 9);
            $this->Cell(15, 0, $numero, 1, 0, 'C', 0, '', 0);
            $this->Cell(35, 0, $Row['nro_parte'], 1, 0, 'C', 0, '', 0);
            $this->Cell(35, 0, $Row['referencia'], 1, 0, 'C', 0, '', 0);
            $this->Cell(55, 0, $Row['descripcion'], 1, 0, 'C', 0, '', 0);
            $this->Cell(20, 0, $Row['cantidad_sol'], 1, 0, 'C', 0, '', 0);
            $this->Cell(0, 0, $Row['unidad_medida'], 1, 0, 'C', 0, '', 0);
            $this->ln();
            $numero++;
            if ($Row > 1) {
                $y = $y + 4;
            }
            if ($Row > 1) {
                $y2 = $y2 + 4.2;
            }
        }
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Motivo de la Solicitud', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(0, 7, ' ' . $this->datos[0]['motivo_solicitud'], 1, 1, 'L', 0, '', 0);
        $this->ln(1.5);
        $this->SetFont('times', '', 11);
        $this->Cell(65, 0, 'Justificación de Necesidad:', 1, 0, 'C', 0, '', 0);
        $this->Cell(65, 0, 'Tipo de Solicitud:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Fecha Requerida de Llegada:', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(65, 20, "\n" . $this->datos[0]['justificacion'], 1, 'C', 0, '', '');
        $this->Cell(65, 20, $this->datos[0]['tipo_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 20, $this->datos[0]['fecha_requerida'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 18, 'Observaciones: ' . $this->datos[0]['observaciones_sol'] . "\n", 1, 'J', 0, '', '');
        $this->ln();
        $this->ln(3);
        $this->SetFont('times', '', 9);
        if($this->datos[0]['estado'] == 'vobo_area' or $this->datos[0]['estado'] == 'vobo_aeronavegabilidad' or $this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(90, 7, ' Solicitado Por: ' . $this->datos[0]['desc_funcionario1'], 1, 0, 'C', 0, '', 0);
        }else{
            $this->Cell(90, 7, ' Solicitado Por: ', 1, 0, 'L', 0, '', 0);
        }
        if($this->datos[0]['estado'] == 'vobo_aeronavegabilidad'or $this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion' or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
        $this->Cell(0, 7, ' V.B. Encargado Mantenimiento: '.$this->datos2[0]['funcionario_bv'], 1, 0, 'C', 0, '', 0);
        }else{
            $this->Cell(0, 7, ' V.B. Encargado Mantenimiento: ', 1, 0, 'L', 0, '', 0);
        }
        $this->ln();
        $this->Cell(90, 35, ' ', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 35,  ' ', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        if($this->datos[0]['estado'] == 'vobo_area' or $this->datos[0]['estado'] == 'vobo_aeronavegabilidad' or $this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(90, 7, ' Fecha: ' . $this->datos[0]['fecha_solicitud'], 1, 0, 'L', 0, '', 0);
        }else{
            $this->Cell(90, 7, ' Fecha: ', 1, 0, 'L', 0, '', 0);
        }
        if($this->datos[0]['estado'] == 'vobo_aeronavegabilidad'or $this->datos[0]['estado'] == 'revision'or  $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(0, 7, ' Fecha: ' . $this->datos2[0]['fecha_ini'], 1, 1, 'L', 0, '', 0);
        }else{
            $this->Cell(0, 7, ' Fecha: ', 1, 1, 'L', 0, '', 0);
        }
        $this->ln(2);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Evaluado y Analizado por AOC - 121', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 9);

        if($this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra' or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(95, 0, ' V.B. DAC: '.$this->datos2[1]['funcionario_bv'], 0, 0, 'L', 0, '', 0); ///nombre
        }else{
            $this->Cell(95, 0, 'V.B. DAC', 0, 0, 'L', 0, '', 0);
        }

        if($this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(0, 0, ' Recibido Abastecimiento: ' . $this->datos2[2]['funcionario_bv'], 0, 0, 'L', 0, '', 0);
        }else{
            $this->Cell(0, 0, ' Recibido Abastecimiento:' , 0, 0, 'L', 0, '', 0);
        }
            $this->ln(0.10);
        $this->Cell(90, 35, ' ', 1, 0, 'L', 0, '', 0);

        $this->Cell(0, 35,  ' ', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        if($this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(90, 7, ' Fecha: ' . $this->datos2[1]['fecha_ini'], 1, 0, 'L', 0, '', 0);
        }else{
            $this->Cell(90, 7, ' Fecha: ', 1, 0, 'L', 0, '', 0);
        }
        if($this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {
            $this->Cell(0, 7, ' Fecha: ' . $this->datos2[2]['fecha_ini'], 1, 1, 'L', 0, '', 0);
        }else{
            $this->Cell(00, 7, ' Fecha: ', 1, 1, 'L', 0, '', 0);
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
        $fun2 = $this->datos2[0]['funcionario_bv'];
        $esta2 = $this->datos2[0]['nombre_estado'];



        if($this->datos[0]['estado'] == 'vobo_area' or $this->datos[0]['estado'] == 'vobo_aeronavegabilidad' or $this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {

            $this->write2DBarcode($html, 'QRCODE,L', 45, $y, 30, 30, $style, 'N');
        }


        $html2 = 'Encargado: '.$fun2."\n".'Nro. Pedido: '.$num."\n".'Tipo Solicitud: '.$tipo."\n".'Estado: '.$esta2."\n".'Fecha de de la Solicitud: '.$fecha."\n";

        if($this->datos[0]['estado'] == 'vobo_aeronavegabilidad' or $this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion' or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado'){

           $this->write2DBarcode($html2, 'QRCODE,L',135, $y, 30, 30, $style, 'N');
        }
        $html3 = 'Encargado: '.$this->datos2[1]['funcionario_bv']."\n".'Nro. Pedido: '.$num."\n".'Tipo Solicitud: '.$tipo."\n".'Estado: '.$this->datos2[1]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$fecha."\n";

        if($this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion' or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado'){

            $this->write2DBarcode($html3, 'QRCODE,L',35,$y2, 30, 30, $style, 'N');
        }

        $html4 = 'Encargado: '.$this->datos2[2]['funcionario_bv']."\n".'Nro. Pedido: '.$num."\n".'Tipo Solicitud: '.$tipo."\n".'Estado: '.$this->datos2[2]['nombre_estado']."\n".'Fecha de de la Solicitud: '.$fecha."\n";
        if($this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen'or $this->datos[0]['estado'] == 'finalizado') {

            $this->write2DBarcode($html4, 'QRCODE,L', 135, $y2, 30, 30, $style, 'N');
        }
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