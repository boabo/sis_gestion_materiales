<?php

class RConstanciaEnvioInvitacion extends ReportePDF
{
    function Header()
    {
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


        $this->Ln(15);
        //$this->Cell(60, 0, ' ' . $this->datos[0]['mensaje_correo'] . '', 0, 0, 'L', 0, '', 0);
        $this->MultiCell(150, 20, ' ' . $this->datos[0]['mensaje_correo'] . '', 0,'L', false ,0);




        //if ($this->datos[0]['tiempo_entrega'] > 0) {
          //$this->Ln(16);
          //$tiempo_entrega = '"Plazo de entrega de propuesta hasta '. $this->datos[0]['tiempo_entrega'].' día(s) después de la invitación."<br><br>';
          //$this->writeHTML($tiempo_entrega, true);

          //$this->Cell(20, 0, '"Plazo de entrega de propuesta hasta '.$this->datos[0]['tiempo_entrega'].' día(s) después de la invitación."', 0, 0, 'L', 0, '', 0);
          if ($this->datos[0]['fecha_solicitud'] >= $this->datos[0]['fecha_salida']) {
            $this->Ln(20);
            $this->MultiCell(150, 20, 'En caso de ofertar números de partes alternos favor adjuntar Documento 8130 o certificación de alternabilidad aprobado por la FAA.', 0,'L', false ,0);
            $this->SetFont('', 'B');
            $this->Ln(18);
            $this->MultiCell(150, 20, 'IMPORTANTE: Una vez confirmada la adjudicación todo componente o material aeronáutico a ser entregado al Forwarder en Miami debe contar con el embalaje adecuado, documentación y certificaciones, para ser transportado vía aérea hasta Bolivia.', 0,'L', false ,0);
            /******************************************************************************************/

            $this->Ln(30);
          } else {
            $this->Ln(20);
          }

        //} else {
          //$this->Ln(20);
        //}
        // $this->SetFont('', '');
        // $this->SetTextColor(0, 100, 100, 0);
        // $this->MultiCell(170, 20, 'AQUI LA LEYENDA PENDIENTE METODO ADJU: '.$this->datos[0]['metodo_de_adjudicación'].' TIPO DE ADJUDI: '.$this->datos[0]['tipo_de_adjudicacion'], 0,'L', false ,0);
        // $this->Ln(20);

        $this->SetTextColor();
        $html = '<p><img src="../../../sis_gestion_materiales/media/abastecimientos.png">';
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

        $this->SetMargins(30, 40, 30);
        $this->setFontSubsetting(false);
        $this->SetMargins(30, 100, 30);
        $this->AddPage();
        //$this->reporteGeneralPrimer();


    }

}

?>
