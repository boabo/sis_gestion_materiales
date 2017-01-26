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
        $this->ln(15);
        $this->customy = $this->getY();
        $this->reporteRequerimiento();
    }
    function reporteRequerimiento()
    {
        $this->ln(5);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' Restocking Request', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(45, 0, 'Documento:', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0,  $this->datos[0]['nro_tramite'], 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, 'Estado:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0,  $this->datos[0]['estado'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(45, 0, 'Fecha:', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0,  $this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, 'Fecha Requerida:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0,  $this->datos[0]['fecha_requerida'], 1, 1, 'C', 0, '', 0);
        $this->ln(2);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 7, ' General', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(45, 0, 'Tipo:', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0,  $this->datos[0]['tipo_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, 'Solicitante:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0,  $this->datos[0]['desc_funcionario1'], 1, 1, 'C', 0, '', 0);
        $this->ln(2);
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, ' Motivo: '. $this->datos[0]['motivo_solicitud']."\n", 1, 'J', 0, '', '');
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, ' Observaciones: '. $this->datos[0]['observaciones_sol']."\n", 1, 'J', 0, '', '');
        $this->ln(13);
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 6, ' Detalle', 1, 1, 'L', 0, '', 0);
        $this->ln(0.10);
        $this->SetFont('','B',10);

        $conf_det_tablewidths=array(45,45,50,55,25);
        $conf_det_tablealigns=array('C','C','C','C','C','C');

        $this->tablewidths=$conf_det_tablewidths;
        $this->tablealigns=$conf_det_tablealigns;


        $RowArray = array(

            'Número de Parte',
            'Número Parte Alterno',
            'Referencia',
            'Descripcion',
            'Cantidad',
            'Unidad Medida'
        );
        $this-> MultiRow($RowArray,false,1);
        $this->SetFont('','',10);
        $conf_det_tablewidths=array(45,45,50,55,25);
        $conf_det_tablealigns=array('C','C','C','C','C','C');
        $this->tablewidths=$conf_det_tablewidths;
        $this->tablealigns=$conf_det_tablealigns;

        foreach ($this->datos as $Row) {

            $RowArray = array(
                'Número de Parte' => $Row['nro_parte'],
                'Número Parte Alterno'=> $Row['nro_parte_alterno'],
                'Referencia' => $Row['referencia'],
                'Descripcion' => $Row['descripcion'],
                'Cantidad' => $Row['cantidad_sol'],
                'Unidad Medida' => $Row['unidad_medida']
            );
            $this-> MultiRow($RowArray);
        }
        foreach ( $this->datos2 as $Row){
            $usr = $Row['funcionario_bv'];
            $fec = $Row['fecha_ini'];
            $tra = $Row['nro_tramite'];
            $tip= $Row['tipo_solicitud'];
            $esta3 = $Row['nombre_estado'];
            $fecha3 =$Row['fecha_solicitud'];
        }
        $this->SetFont('times', '', 9);
        $this->ln(48);
        $this->Cell(70, 30, ' ', 0, 0, 'C', 0, '', 0);
        $this->Cell(50, 10, ' Solicitado Por: ' . $this->datos[0]['desc_funcionario1'], 0, 0, 'L', 0, '', 0);
        $this->Cell(50, 10, 'Revisado Por: '.$usr, 0, 0, 'C', 0, '', 0);
        $this->Cell(0, 30,  ' ', 0, 0, 'C', 0, '', 0);


        //$this->Cell(0, 7, ' V.B. Encargado Mantenimiento: '.$this->datos2[0]['funcionario_bv'], 1, 0, 'C', 0, '', 0);

        $this->ln(1);
        $this->Cell(70, 30, ' ', 0, 0, 'C', 0, '', 0);
        $this->Cell(55, 40, ' ', 1, 0, 'C', 0, '', 0);
        $this->Cell(55, 40, ' ', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 30,  ' ', 0, 0, 'C', 0, '', 0);
        $this->ln();

        $fun = $this->datos[0]['desc_funcionario1'];
        $num = $this->datos[0]['nro_tramite'];
        $tipo= $this->datos[0]['tipo_solicitud'];
        $esta = $this->datos[0]['estado'];
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

       if($this->datos[0]['estado'] == 'revision') {

            $this->write2DBarcode($html, 'QRCODE,L', 100, 165, 25, 25, $style, 'N');
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