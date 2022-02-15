<?php
class RNotaDeAdjudicacionRep extends  ReportePDF
{
    function Header(){
        //$this->Ln(8);
        //$this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        $this->SetFont('', 'B');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib/imagenes/logo.jpg', 80, 5, 60);
        $this->Ln(26);
        $this->SetFontSize(25);
        $this->MultiCell(130, 25, "\n" . 'NOTA DE ADJUDICACIÓN'."\n", 0, 'C', 0, 1, '47', '');
        $this->SetFontSize(15);
        $this->MultiCell(0, 0, "\n" . $this->datos[0]['nro_tramite']."\n", 0, 'C', 0, 1, '', '50');
        // $this->SetFont('times', '', 10);
        // $this->MultiCell(0, 25, "\n" . $this->datos[0]['num_tramite']."\n", 1, 'C', 0, '', '');

    }
    function ReporteOrdenRaparacion(){
        $this->AddPage();

        $this->setPrintFooter(false);

        $this->Ln(30);

        $funcionario_rpc = $this->datos[0]["firma_rpc"];
        $fun_rpc = explode('|', $funcionario_rpc);

        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($funcionario_rpc == NULL || $funcionario_rpc == '') {
          $firma_rpc_0 = ' ';
          $firma_rpc_1 = '';
          $firma_rpc_3 = '';
          $firma_rpc_4 = '';
        } else {
          $firma_rpc_0 = $fun_rpc[0];
          $firma_rpc_1 = $fun_rpc[1];
          $firma_rpc_3 = $fun_rpc[3];
          $firma_rpc_4 = $fun_rpc[4];
        }


        if ($this->datos[0]['evaluacion'] == NULL) {
          $texto = 'De esta manera, en cumplimiento a los procedimientos internos del Reglamento Específico para la Contratación de Bienes, Obras y Servicios Especializados en el Extranjero - BoA, se adjudica el servicio de reparación denominado <b>"(REP '.$this->datos[0]['nro_rep'].') CONTRATACIÓN DE SERVICIO DE REPARACIÓN DE REPUESTOS LOTE '.$this->datos[0]['lote_rep'].'"</b> por un monto de <b>$us.'.$this->datos[0]['total_venta_rep'].'</b>';
        } elseif ($this->datos[0]['evaluacion'] == 'Exchange' || $this->datos[0]['evaluacion'] == 'Flat Exchange') {
          $texto = 'De esta manera, en cumplimiento a los procedimientos internos del Reglamento Específico para la Contratación de Bienes, Obras y Servicios Especializados en el Extranjero - BoA, se adjudica el servicio de reparación denominado <b>"(REP '.$this->datos[0]['nro_rep'].') COMPRA DE REPUESTO POR INTERCAMBIO (FLAT EXCHANGE) LOTE '.$this->datos[0]['lote_rep'].'"</b> por un monto de <b>$us.'.$this->datos[0]['total_venta_rep'].'</b>';
        } elseif ($this->datos[0]['evaluacion'] == 'Reparacion') {
          $texto = 'De esta manera, en cumplimiento a los procedimientos internos del Reglamento Específico para la Contratación de Bienes, Obras y Servicios Especializados en el Extranjero - BoA, se adjudica el servicio de reparación denominado <b>"(REP '.$this->datos[0]['nro_rep'].') CONTRATACIÓN DE SERVICIO DE REPARACIÓN DE REPUESTOS LOTE '.$this->datos[0]['lote_rep'].'"</b> por un monto de <b>$us.'.$this->datos[0]['total_venta_rep'].'</b>';

        } elseif ($this->datos[0]['evaluacion'] == 'Calibracion') {
          $texto = 'De esta manera, en cumplimiento a los procedimientos internos del Reglamento Específico para la Contratación de Bienes, Obras y Servicios Especializados en el Extranjero - BoA, se adjudica el servicio de calibración denominado <b>"(REP '.$this->datos[0]['nro_rep'].') CONTRATACIÓN DE SERVICIO DE CALIBRACION DE EQUIPOS LOTE '.$this->datos[0]['lote_rep'].'"</b> por un monto de <b>$us.'.$this->datos[0]['total_venta_rep'].'</b>';

        }

        if ($this->datos[0]['tiene_bear'] == 'SI') {
          $texto = 'De esta manera, en cumplimiento a los procedimientos internos del Reglamento Específico para la Contratación de Bienes, Obras y Servicios Especializados en el Extranjero - BoA, se adjudica el servicio de reparación denominado <b>"(REP '.$this->datos[0]['nro_rep'].') COMPRA DE REPUESTO EN REEMPLAZO DE UNIDAD DECLARADA B.E.R REQUERIDO PARA FLOTA BOA LOTE '.$this->datos[0]['lote_rep'].'"</b> por un monto de <b>$us.'.$this->datos[0]['total_venta_rep'].'</b>';
        }



        //$this->MultiCell(180, 0,$texto, 0, '', 0, 1, '20', '',true,0,true);
        $this->MultiCell(180, 0,'La Empresa Pública Nacional Estratégica "Boliviana de Aviación", Comunica a la Empresa del Extranjero:', 0, '', 0, 1, '20', '',true,0,true);

        $this->Ln(8);
        $this->SetFont('', 'B');
        $this->SetFontSize(17);
        $this->MultiCell(180, 0,'<b><u>"'.$this->datos[0]['proveedor'].'"</u></b>', 0, 'C', 0, 1, '20', '',true,0,true);

        $this->Ln(8);
        $this->SetFont('', '');
        $this->SetFontSize(12);
        $this->MultiCell(180, 0,'Que la propuesta presentada por su empresa ha sido evaluada, justificada y recomendada en el informe <b>'.$this->datos[0]['informe_rep'].'</b> en fecha '.$this->datos[0]['fecha_cotizacion'].' por la unidad solicitante.', 0, '', 0, 1, '20', '',true,0,true);

        $this->Ln(8);
        $this->SetFont('', '');
        $this->SetFontSize(12);
        $this->MultiCell(180, 0,$texto, 0, '', 0, 1, '20', '',true,0,true);

        $this->Ln(8);
        $this->SetFont('', '');
        $this->SetFontSize(12);
        $this->MultiCell(180, 0,'Con este particular motivo, saludo a usted muy atentamente.', 0, '', 0, 1, '20', '',true,0,true);


        $this->Ln(15);
        $this->SetFont('', '');
        $this->SetFontSize(12);

        $tb_funcio =' <table cellspacing="0" cellpadding="5" style="font-size:11px;">
              <tr>
              <th align="center" style="font-family: Calibri; font-size: 9px;">
                  <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($firma_rpc_0, $firma_rpc_1,$firma_rpc_4,$this->datos[0]['fecha_po']).'" alt="Logo">
                  <br> <br>'.$firma_rpc_0.'
              </th>
              </tr>
        </table>';


        $this->writeHTML($tb_funcio);


        $this->Ln(8);
        $this->SetFont('', '');
        $this->SetFontSize(12);
        if ($this->datos[0]['fecha_literal'] != '') {
          $this->MultiCell(180, 0,'Cochabamba, '.$this->datos[0]['fecha_literal'].'', 0, 'R', 0, 0, '20', '');
        }

        // $this->SetFont('', 'B');
        //
        // $this->MultiCell(180, 0,'OB.DAB.REP.4007.2021 ', 0, '', 0, 1, '40', '96.5');
        //
        // $this->MultiCell(180, 0,'en fecha 17/05/2021 por la unidad ', 0, '', 0, 1, '40', '96.5');



    }
    function generarImagen($nom, $car, $ofi, $fecha){
        $cadena_qr = 'Nombre: '.$nom. "\n" . 'Cargo: '.$car."\n".'Oficina: '.$ofi."\n".'Fecha: '.$fecha ;
        $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/" . $nom . ".png");
            imagedestroy($im);

        } else {
            echo 'A ocurrido un Error.';
        }
        $url_archivo = dirname(__FILE__) . "/../../reportes_generados/" . $nom . ".png";

        return $url_archivo;
    }

    function setDatos($datos)
    {
        $this->datos = $datos;
    }
    function generarReporte() {
        $this->SetMargins(10,40,10);
        $this->setFontSubsetting(false);
        //$this->AddPage();
        $this->SetMargins(10,40,10);
        $this->ReporteOrdenRaparacion();
    }
}
?>
