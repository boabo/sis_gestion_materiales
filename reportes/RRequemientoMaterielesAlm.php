<?php
class RRequemientoMaterielesAlm extends  ReportePDF
{
    function Header()
    {
        $this->ln(15);
        $height = 25;
        //cabecera del reporte
        $this->Cell(40, $height, '', 0, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(15);
        $this->SetFont('', 'B');
        $this->MultiCell(105, $height, "\n" . 'ALMACENES CONSUMIBLES O ROTABLES', 0, 'C', 0, '', '');

        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 17, 23, 36);
        $this->ln(20);
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
        $this->Cell(45, 0, 'Trabajo:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0,  $this->datos[0]['fecha_requerida'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(45, 0, 'Categoria :', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0,  '', 1, 0, 'C', 0, '', 0);
        $this->Cell(45, 0, 'Solicitante:', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0,  $this->datos[0]['desc_funcionario1'], 1, 1, 'C', 0, '', 0);
        $this->ln(2);
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, ' Motivo: '. $this->datos[0]['motivo_solicitud']."\n", 1, 'J', 0, '', '');
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->MultiCell(0, 10, ' Observaciones: '. $this->datos[0]['observaciones_sol']."\n", 1, 'J', 0, '', '');
        $this->ln();
        $this->ln();
        $this->SetFont('times', 'B', 11);
        $this->Cell(0, 6, ' Detalle', 1, 1, 'L', 0, '', 0);
        $this->ln(2);
        $this->SetFont('','B',9);

        $conf_det_tablewidths=array(35,50,35,35,31);
        $conf_det_tablealigns=array('C','C','C','C','C');

        $this->tablewidths=$conf_det_tablewidths;
        $this->tablealigns=$conf_det_tablealigns;


        $RowArray = array(

            'P/N',
            'Descripcion',
            'Cantidad',
            'UM',
            'Estasdo'
        );
        $this-> MultiRow($RowArray,false,1);
        $this->SetFont('','',8);
        $conf_det_tablewidths=array(35,50,35,35,31);
        $conf_det_tablealigns=array('C','C','C','C','C');
        $this->tablewidths=$conf_det_tablewidths;
        $this->tablealigns=$conf_det_tablealigns;

        foreach ($this->datos as $Row) {

            $RowArray = array(
                'P/N' => $Row['nro_parte'],
                'Descripcion'=> $Row['descripcion'],
                'Cantidad' => $Row['cantidad_sol'],
                'UM' => $Row['unidad_medida'],
                'Estasdo' => $Row['estado']
            );
            $this-> MultiRow($RowArray);
        }



    }
    function setDatos($datos) {
        $this->datos = $datos;
    }
    function generarReporte() {
        $this->setFontSubsetting(false);

    }
}
?>