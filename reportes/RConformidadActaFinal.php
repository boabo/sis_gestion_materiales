<?php
class RConformidadActaFinal extends  ReportePDF
{
    function Header(){
      $this->ln(15);

      $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 20, 10, 45);


      $height = 20;
      //cabecera del reporte
      $this->Cell(50, $height, '', 0, 0, 'C', false, '', 0, false, 'T', 'C');
      $this->SetFontSize(16);
      $this->SetFont('', 'B');
      $this->Cell(100, $height, 'ACTA DE CONFORMIDAD FINAL', 0, 0, 'C', false, '', 0, false, 'T', 'C');

      $this->SetMargins(10, 50, 10);
      $this->ln(20);
      $this->customy = $this->getY();
    }

    public function Footer()
    {
        $this->SetFontSize(7);
        $this->setY(-10);
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        //set style for cell border
        $line_width = 0.85 / $this->getScaleFactor();
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
        $this->Ln(2);
        $cur_y = $this->GetY();
        //$this->Cell($ancho, 0, 'Generado por XPHS', 'T', 0, 'L');
        $this->Cell($ancho, 0, 'Usuario: ' . $_SESSION['_LOGIN'], '', 1, 'L');
        $pagenumtxt = 'Página' . ' ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages();

        //$this->Cell($ancho, 0, '', '', 0, 'C');
        $fecha_rep = date("d-m-Y H:i:s");
        $this->Cell($ancho, 0, "Fecha impresión: " . $fecha_rep, '', 0, 'L');
        $this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
        $this->Ln($line_width);
    }


    function ReporteActaFinal(){

        $nombre_solicitante = $this->datos[0]['desc_funcionario1'];
        $fecha_conformidad_final = $this->datos[0]['fecha_conformidad'];
        $num_tramite = $this->datos[0]['nro_tramite'];
        $nro_cuota_vigente = '1';
        $proveedor = $this->datos[0]['proveedor'];
        $conformidad_final = $this->datos[0]['conformidad_final'];
        $columasconcepto = '';

        $observaciones = $this->datos[0]['observaciones'];


        if ($this->datos[0]['revisado'] == 'no') {
          $nombre_usuario_firma = 'PENDIENTE DE REVISIÓN';
          $cargo = '';
          $oficina = '';

          $nombre_jefe_abastecimiento = '';
          $cargo_jefe_abastecimiento = '';
          $oficina_jefe_abastecimiento = '';

          $encargado_almacen = 'PENDIENTE DE REVISIÓN';
          $cargo_encargado_almacen = '';
          $oficina_encargado_almacen = '';

        } else {
          $nombre_usuario_firma = $nombre_solicitante;
          $cargo = $this->datos[0]['nombre_cargo'];
          $oficina = $this->datos[0]['oficina_nombre'];


          /*Aqui para la firma del jefe de abastecimiento*/
          $nombre_jefe_abastecimiento = $this->datos[0]['jefe_abastecimiento'];
          $cargo_jefe_abastecimiento = $this->datos[0]['cargo_jefe_abastecimiento'];
          $oficina_jefe_abastecimiento = $this->datos[0]['oficina_abastecimiento'];


          $encargado_almacen = $this->datos[0]['encargado_almacen'];
          $cargo_encargado_almacen = $this->datos[0]['cargo_encargado_almacen'];
          $oficina_encargado_almacen = $this->datos[0]['oficina_encargado_almacen'];

        }



        $maestro = $this->detalle->datos;
        $numeracion_item = 1;


        if ($this->datos[0]['tipo_pedido'] == 'Reparación de Repuestos' && $this->datos[0]['aumentar_condicion'] == 'si') {
          foreach ($maestro as $datomaestro) {
              $columasconcepto = $columasconcepto . '<tr>
                       <td width="5%" align="center">' . $numeracion_item. '</td>
                       <td width="38%" align="left">' . $datomaestro['concepto'] . '</td>
                       <td width="32%" align="left">' . $datomaestro['descripcion'] . '</td>
                       <td width="11%" align="right">' . $datomaestro['cantidad_sol'] . '</td>
                       <td width="11%" align="right">' . $datomaestro['condicion'] . '</td>
          		 </tr>';

               $numeracion_item ++;
          }
        }else{
          foreach ($maestro as $datomaestro) {
              $columasconcepto = $columasconcepto . '<tr>
                       <td width="5%" align="center">' . $numeracion_item. '</td>
                       <td width="38%" align="left">' . $datomaestro['concepto'] . '</td>
                       <td width="38%" align="left">' . $datomaestro['descripcion'] . '</td>
                       <td width="18%" align="right">' . $datomaestro['cantidad_sol'] . '</td>
          		 </tr>';

               $numeracion_item ++;
          }
        }





        $this->AddPage();
        $this->SetMargins(10, 50, 10);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        if ($this->datos[0]['revisado'] == 'no') {
          $url_firma = '';
          $url_firma_jefe_abastecimiento = '';
          $url_firma_encargado_almacen = '';

        }else{
          $url_firma = $this->generarImagen($nombre_solicitante, $cargo,$oficina,$fecha_conformidad_final);
          $url_firma_jefe_abastecimiento = $this->generarImagen($nombre_jefe_abastecimiento, $cargo_jefe_abastecimiento,$oficina_jefe_abastecimiento,$fecha_conformidad_final);
          $url_firma_encargado_almacen = $this->generarImagen($encargado_almacen, $cargo_encargado_almacen,$oficina,$fecha_conformidad_final);

        }


        // $url_firma = $this->generarImagen($nombre_solicitante, $cargo,$oficina,$fecha_conformidad_final);
        // $url_firma_jefe_abastecimiento = $this->generarImagen($nombre_jefe_abastecimiento, $cargo_jefe_abastecimiento,$oficina_jefe_abastecimiento,$fecha_conformidad_final);
        // $url_firma_encargado_almacen = $this->generarImagen($encargado_almacen, $cargo_encargado_almacen,$oficina_jefe_abastecimiento,$fecha_conformidad_final);
        // //para el nro de PO se oculte si no hay dato
        if (empty($this->datos['nro_po']) and empty($this->datos[0]['nro_po'])) {
            $columanPo = '';
        } else {

          if ($this->datos[0]['firma_almacen'] == 'si') {

            if ($this->datos[0]['tipo_pedido'] == 'Reparación de Repuestos') {
              $columanPo = '  <tr>
                                <td width="50%"> <b>Nro REP:  </b>' . $this->datos[0]['nro_po'] . '<br></td>
                                <td width="50%"> <b>Fecha REP:  </b>' . $this->datos[0]['fecha_po'] . '<br></td>
                              </tr>';
            } else {
              if ($this->datos[0]['tipo_evaluacion'] == 'Flat Exchange' || $this->datos[0]['tipo_evaluacion'] == 'Exchange') {
                $columanPo = '  <tr>
                                  <td width="50%"> <b>Nro EO:  </b>' . $this->datos[0]['nro_po'] . '<br></td>
                                  <td width="50%"> <b>Fecha EO:  </b>' . $this->datos[0]['fecha_po'] . '<br></td>
                                </tr>';
              } else {
                $columanPo = '  <tr>
                	<td width="50%"> <b>Nro PO:  </b>' . $this->datos[0]['nro_po'] . '<br></td>
                	<td width="50%"> <b>Fecha PO:  </b>' . $this->datos[0]['fecha_po'] . '<br></td>
                </tr>';
              }

            }
          }else{
            $columanPo = '  <tr>
            	<td width="50%"> <b>Nro PO:  </b>' . $this->datos[0]['nro_po'] . '<br></td>
            	<td width="50%"> <b>Fecha PO:  </b>' . $this->datos[0]['fecha_po'] . '<br></td>
            </tr>';
          }



        }


        if (empty($this->datos['fecha_inicio']) and empty($this->datos[0]['fecha_inicio'])) {
            $columanFecha = '';
        } else {
            $columanFecha = '  <tr>
            	<td width="50%"> <b>Fecha Inicio:  </b>'.$this->datos[0]['fecha_inicio'].'<br></td>
            	<td width="50%"> <b>Fecha Fin:  </b>'.$this->datos[0]['fecha_inicio'].'<br></td>
            </tr>';
        }
        //var_dump("aqui llega para dibujar",$this->datos[0]);exit;
        if ($this->datos[0]['aplica_nuevo_flujo'] == 'si') {
          $html .= '<style>
                        table, th, td {
                            border: 1px solid black;
                            border-collapse: collapse;
                            font-family: "Times New Roman";
                            font-size: 11pt;
                        }
                        </style>
                        <body>
                        <table border="1">
                              <tr>
                                  <td width="65%"><b>Verificado Por:  </b>'.$nombre_solicitante.'<br></td>
                                  <td width="35%"> <b>Fecha de Conformidad:  </b>'.$fecha_conformidad_final.'<br></td>
                                </tr>
                              <tr>
                                  <td width="100%"> <b>Número de Trámite:  </b>'.$num_tramite.'<br></td>

                                </tr>
                              <tr>
                                  <td width="100%"> <b>Proveedor:  </b>'.$proveedor.'<br></td>
                                </tr>

                                '.$columanPo.'

                                '.$columanFecha.'
                                <tr>
                                  <td width="100%"> <b>Conformidad:  </b>'.$conformidad_final.'<br></td>
                                </tr>
                                <tr>
                                  <td width="100%" align="justify"  colspan="2">
                                  En cumplimiento al Reglamento Específico de las Normas Básicas del Sistema de Administración de Bienes y Servicios de la Empresa,  doy conformidad a lo solicitado.
                                  <br><br>';
                              //Aumentando para aumentar la columna de la condicion
                              //Ismael Valdivia (16/05/2022)

                              if ($this->datos[0]['tipo_pedido'] == 'Reparación de Repuestos' && $this->datos[0]['aumentar_condicion'] == 'si') {

                                $html.='<table border="0">
                                        <tr>
                                               <td width="5%" align="center"><b>Nro</b></td>
                                               <td width="38%" align="center"><b>Concepto</b></td>
                                               <td width="32%" align="center"><b>Descripción</b></td>
                                               <td width="11%" align="center"><b>Cantidad Adj.</b></td>
                                               <td width="11%" align="center"><b>Condición</b></td>

                                       </tr>

                                        '.$columasconcepto.'
                                         </table>';
                              } else {
                                $html.='<table border="0">
                                        <tr>
                                               <td width="5%" align="center"><b>Nro</b></td>
                                               <td width="38%" align="center"><b>Concepto</b></td>
                                               <td width="38%" align="center"><b>Descripción</b></td>
                                               <td width="18%" align="center"><b>Cantidad Adj.</b></td>

                                       </tr>

                                        '.$columasconcepto.'
                                         </table>';
                              }



                                  $html.= '<br><br>
                                  El mismo cumple con las características y condiciones requeridas, en calidad y cantidad. La cuál fue adquirida considerando criterios de economía para la obtención de los mejores precios del mercado.
                                  <br><br>
                                  En conformidad de lo anteriormente mencionado firmo a continuación:
                                  </td>
                                </tr>';
                          if($this->datos[0]['firma_almacen'] == 'si'){
                            $html.= '  <tr>
                                          <td width="50%" align="center"  colspan="2">
                                              <b>Técnico Abastecimiento:</b>
                                          </td>

                                          <td width="50%" align="center"  colspan="2">
                                            <b>Encargado Almacen:</b>
                                          </td>


                                      </tr>

                                      <tr>

                                          <td width="50%" align="center"  colspan="2">   <br><br>
                                              <img  style="width: 80px;" src="'.$url_firma.'" alt="Logo">
                                              <br>
                                              <br>
                                              '.$nombre_usuario_firma.'
                                              <br>
                                          </td>

                                          <td width="50%" align="center"  colspan="2">   <br><br>
                                              <img  style="width: 80px;" src="'.$url_firma_encargado_almacen.'" alt="Logo">
                                              <br>
                                              <br>
                                                '.$encargado_almacen.'
                                              <br>
                                          </td>




                                      </tr>';
                          }else{
                            $html.= '  <tr>
                                  <td width="100%" align="center"  colspan="2">
                                    <b>Técnico Abastecimiento:</b>
                                  </td>
                              </tr>

                            <tr>
                                <td width="100%" align="center"  colspan="2">   <br><br>
                                    <img  style="width: 80px;" src="'.$url_firma.'" alt="Logo">
                                    <br>
                                    <br>
                                      '.$nombre_usuario_firma.'
                                    <br>
                                </td>
                            </tr>';
                          }


                            $html.= '  <tr>
                                  <td width="100%"> <b>Observaciones:  </b>'.$observaciones.'<br></td>
                                </tr>
                          </table>
                          </body>';
        } else {
          $html .= '<style>
                        table, th, td {
                            border: 1px solid black;
                            border-collapse: collapse;
                            font-family: "Times New Roman";
                            font-size: 11pt;
                        }
                        </style>
                        <body>
                        <table border="1">
                              <tr>
                                  <td width="65%"><b>Verificado Por:  </b>'.$nombre_solicitante.'<br></td>
                                  <td width="35%"> <b>Fecha de Conformidad:  </b>'.$fecha_conformidad_final.'<br></td>
                                </tr>
                              <tr>
                                  <td width="100%"> <b>Número de Trámite:  </b>'.$num_tramite.'<br></td>

                                </tr>
                              <tr>
                                  <td width="100%"> <b>Proveedor:  </b>'.$proveedor.'<br></td>
                                </tr>

                                '.$columanPo.'

                                '.$columanFecha.'
                                <tr>
                                  <td width="100%"> <b>Conformidad:  </b>'.$conformidad_final.'<br></td>
                                </tr>
                                <tr>
                                  <td width="100%" align="justify"  colspan="2">
                                  En cumplimiento al Reglamento Específico de las Normas Básicas del Sistema de Administración de Bienes y Servicios de la Empresa,  doy conformidad a lo solicitado.
                                  <br><br>
                                  <table border="0">
                                  <tr>
                                         <td width="5%" align="center"><b>Nro</b></td>
                                         <td width="38%" align="center"><b>Concepto</b></td>
                                         <td width="38%" align="center"><b>Descripción</b></td>
                                         <td width="18%" align="center"><b>Cantidad Adj.</b></td>

                                 </tr>

                                  '.$columasconcepto.'
                                   </table>

                                  <br><br>
                                  El mismo cumple con las características y condiciones requeridas, en calidad y cantidad. La cuál fue adquirida considerando criterios de economía para la obtención de los mejores precios del mercado.
                                  <br><br>
                                  En conformidad de lo anteriormente mencionado firmo a continuación:
                                  </td>
                                </tr>

                                <tr>
                                    <td width="50%" align="center"  colspan="2">
                                        <b>Jefe de Unidad de Abastecimiento:</b>
                                    </td>

                                    <td width="50%" align="center"  colspan="2">
                                      <b>Técnico:</b>
                                    </td>
                                </tr>

                              <tr>
                                  <td width="50%" align="center"  colspan="2">   <br><br>
                                      <img  style="width: 80px;" src="'.$url_firma_jefe_abastecimiento.'" alt="Logo">
                                      <br>
                                      <br>
                                      '.$nombre_jefe_abastecimiento.'
                                      <br>
                                  </td>

                                  <td width="50%" align="center"  colspan="2">   <br><br>
                                      <img  style="width: 80px;" src="'.$url_firma.'" alt="Logo">
                                      <br>
                                      <br>
                                        '.$nombre_usuario_firma.'
                                      <br>
                                  </td>
                              </tr>

                              <tr>
                                  <td width="100%" align="center"  colspan="2">
                                    <b>Encargado Almacen:</b>
                                  </td>
                              </tr>

                              <tr>
                                  <td width="100%" align="center"  colspan="2">   <br><br>
                                      <img  style="width: 80px;" src="'.$url_firma_encargado_almacen.'" alt="Logo">
                                      <br><br>
                                        '.$encargado_almacen.'
                                      <br>
                                  </td>
                              </tr>

                              <tr>
                                  <td width="100%"> <b>Observaciones:  </b>'.$observaciones.'<br></td>
                                </tr>
                          </table>
                          </body>';
        }

                          $this->setY($this->customy);
                          $this->writeHTML($html);

        //var_dump("aqui llega datos de la cabecera",$this->datos);exit;

    }

    function setDatos($datos, $detalle)
    {
        $this->datos = $datos;
        $this->detalle = $detalle;
    }

    function generarImagen($nom, $car, $ofi, $fecha){
        $cadena_qr = 'Nombre: '.$nom. "\n" . 'Cargo: '.$car."\n".'Oficina: '.$ofi."\n".'Fecha: '.$fecha ;
        $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/" . $nom . $num_tramite. ".png");
            imagedestroy($im);

        } else {
            echo 'A ocurrido un Error.';
        }
        $url_archivo = dirname(__FILE__) . "/../../reportes_generados/" . $nom . $num_tramite. ".png";

        return $url_archivo;
    }

    function generarReporte() {
        $this->SetMargins(10,40,10);
        $this->setFontSubsetting(false);
        //$this->AddPage();
        $this->SetMargins(10,40,10);
        $this->ReporteActaFinal();
    }
}
?>
