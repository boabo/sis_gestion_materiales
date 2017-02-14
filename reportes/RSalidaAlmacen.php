<?php

class RSalidaAlmacen extends  ReportePDF {
    private  $fun = array();

    function Header() {
        //$this->AddPage();
        $this->ln(5);
        $height = 20;
        //cabecera del reporte
        $this->Cell(40, $height, '', 1, 0, 'C', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(15);
        $this->SetFont('','B');
        $this->MultiCell(90, $height,"\n".'SALIDA DE ALMACÉN', 1, 'C', 0, '', '');
        $this->SetFontSize(12);
        $this->SetFont('','B');
        $this->MultiCell(0, $height,"\n".'N° '.$this->datos[0]['nro_tramite'], 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__).'/../../pxp/lib'.$_SESSION['_DIR_LOGO'], 17, 15, 36);
        $this->ln(20);
        $this->customy = $this->getY();
        $this->reporteRequerimiento();

    }

    function reporteRequerimiento()
    {
        $this->SetFont('times', 'B', 11);
        $this->Cell(40, 7, '', 1, 0, 'L', 0, '', 0);
        $this->Cell(90, 7, 'Matricula: ' . $this->datos[0]['matri'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7, ' ', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'B', 11);
        $this->Cell(40, 7, '', 1, 0, 'L', 0, '', 0);
        $this->Cell(90, 7, 'Fecha: ' . $this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7, ' ', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', 'B', 11);
        $this->Cell(20, 7, 'Cantidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(20, 7, 'Unidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(20, 7, 'Tipo', 1, 0, 'C', 0, '', 0);
        $this->Cell(50, 7, 'Nro. de Parte', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7, 'Descripcion de Material', 1, 0, 'C', 0, '', 0);
        $this->ln();
        foreach ($this->datos as $Row) {
            $this->SetFont('times', '', 9);
            $this->Cell(20, 7, $Row['cantidad_sol'], 1, 0, 'C', 0, '', 0);
            $this->Cell(20, 7, $Row['unidad_medida'], 1, 0, 'C', 0, '', 0);
            $this->Cell(20, 7, $Row['tipo'], 1, 0, 'C', 0, '', 0);
            $this->Cell(50, 7, ' '.$Row['nro_parte'], 1, 0, 'L', 0, '', 0);
            $this->Cell(0, 7, ' '.$Row['descripcion'], 1, 0, 'L', 0, '', 0);
            $this->ln();
        }
        $this->Cell(0, 20, '', 1, 0, 'L', 0, '', 0);
        $this->Ln(2);
        $this->SetFont('times', '', 10);
        $this->Cell(85, 7, ' Recibido Por: ', 0, 0, 'L', 0, '', 0);
        $this->Cell(0, 7, ' Despachado Por: ', 0, 0, 'L', 0, '', 0);
        $this->Ln();
        $this->Cell(85, 7, ' Autorizada Por: ', 0, 0, 'L', 0, '', 0);
        $this->Cell(0, 7, ' Observaciones: '.$this->datos[0]['observaciones_sol'], 0, 0, 'L', 0, '', 0);
        $this->Ln(0.5);
        // output some RTL HTML content
        $html = '<p><span style="background-color: rgb(245,245,255); color: rgb(255,3,0);">Dpto. Abastecimientos y Logística</span> <br/> <span style="background-color: rgb(245,245,255); color: rgb(255,3,0);">Almacén Consumible</span><br/> <span style="background-color: rgb(245,245,255); color: rgb(255,3,0);">Cero de Existencia</span></p>';
        $this->SetFont('dejavusans', 'B', 11);
        if($this->datos[0]['estado'] == 'vobo_area' or $this->datos[0]['estado'] == 'vobo_aeronavegabilidad' or $this->datos[0]['estado'] == 'revision' or $this->datos[0]['estado'] == 'cotizacion'or $this->datos[0]['estado'] == 'compra'or $this->datos[0]['estado'] == 'despachado'or $this->datos[0]['estado'] == 'arribo'or $this->datos[0]['estado'] == 'desaduanizado'or $this->datos[0]['estado'] == 'almacen') {
            $this->writeHTMLCell(0, 0, '', '', $html, '', 0, 0, true, 'C', true);
        }
        $this->ln();

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