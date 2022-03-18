<?php

class RConstanciaEnvioInvitacionBoaRep extends ReportePDF
{
    function Header()
    {
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->Ln(10);
        $this->SetFont('', 'B');
        //$this->Cell(35, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(15, 0, 'De:', 0, 0, 'L', 0, '', 0);
        $this->SetFont('times', '', 11);
        $this->Cell(55, 0, 'Sistema ERP BOA', 0, 0, 'L', 0, '', 0);

        $this->Ln(5);
        $this->SetFont('', 'B');
        //$this->Cell(35, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(15, 0, 'Para: ', 0, 0, 'L', 0, '', 0);
        $this->SetFont('times', '', 11);
        $this->Cell(55, 0, $this->datos[0]['correos'], 0, 0, 'L', 0, '', 0);

        $this->SetFont('', 'B');
        //$this->Cell(30, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(25, 0, 'Fecha Envio:   ', 0, 0, 'L', 0, '', 0);
        $this->SetFont('times', '', 11);
        $this->Cell(0, 0, date_format(date_create($this->datos[0]["fecha_reg"]), 'd/m/Y H:i:s'), 0, 0, 'L', 0, '', 0);

        $this->Ln(8);
        $this->SetFont('', 'B');
        //$this->Cell(35, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(15, 0, 'Asunto: ', 0, 0, 'L', 0, '', 0);
        $this->SetFont('times', '', 11);
        $this->Cell(55, 0, $this->datos[0]['titulo_correo'], 0, 0, 'L', 0, '', 0);

        $this->Ln(10);
        $html = '
                <li>
                    <b>CCO:</b> ' . $this->datos[0]['lista_correos'] . '
                 </li> ';
        $this->writeHTML($html, true);






    }

    public function Footer()
    {

    }

    function setDatos($datos)
    {
        $this->datos = $datos;
    }

    function reporteGeneralPrimer()
    {

    }

    function generarReporte()
    {

        //$this->SetMargins(30, 40, 30);
        $this->setFontSubsetting(false);
        $this->SetMargins(30, 50, 30);
        $this->AddPage();

        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $this->SetFont('times', '', 11);

        $this->Cell(60, 0, 'Señores: '.$this->datos[0]['proveedores'], 0, 0, 'L', 0, '', 0);

        $this->Ln(15);
        $this->Cell(60, 0, '' . $this->datos[0]['mensaje_correo'] . '', 0, 0, 'L', 0, '', 0);
        $this->Ln(17);



        $table = ''.$this->datos[0]['detalle'].'';
        $this->writeHTML($table, true, 0, true, 0);
      //  $this->writeHTMLCell(100, 200, '', '', $table, 0, 1, 0, true, 'J', true);

        $this->Ln(3);
        $this->Cell(60, 0, '*Favor responder a la invitación en el lapso de 10 días como máximo.', 0, 0, 'L', 0, '', 0);
        $this->Ln(10);
        $this->Cell(60, 0, 'Gracias', 0, 0, 'L', 0, '', 0);
        $this->Ln(10);
        $this->Cell(60, 0, 'Saludos', 0, 0, 'L', 0, '', 0);

        $this->Ln(12);
        $html = '<p><img src="../../../sis_gestion_materiales/media/abastecimientos.png">';
        $this->writeHTML($html, true);


    }

}

?>
