<?php
class RInformJustificacionRep extends  ReportePDF
{
    function Header(){
        $this->Ln(8);
        $titulo = '<table cellspacing="0" cellpadding="1">
              <tr>
                <th align="center"><img src="../../../pxp/lib/images/Logo-BoA.png"></th>
              </tr>
              <tr>
                <th align="center" style="font-size:15px"><b>INFORME DE JUSTIFICACIÓN Y RECOMENDACIÓN</b></th>
              </tr>
              </table>';
        $this->writeHTML($titulo);
        // $this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        // $this->SetFontSize(12);
        // $this->SetFont('', 'B');
        // $this->MultiCell(105, 25, "\n" . 'INFORME DE JUSTIFICACIÓN' . "\n" . 'Y RECOMENDACIÓN'."\n".$this->datos[0]['num_tramite']. "\n", 1, 'C', 0, '', '');
        // $this->SetFont('times', '', 10);
        // $this->MultiCell(0, 25, "\n" . 'FORM.GMM-M-4.05'."\n", 1, 'C', 0, '', '');
        // $this->Image(dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png', 12, 15, 36);
    }
    function ReporteComiteEvaluacion(){
        $this->AddPage();


        $nro_partes = explode(',',$this->datos[0]["num_part"]);
        $nro_partes_alternas = explode(',',$this->datos[0]["num_part_alt"]);
        $cantidad = explode(',',$this->datos[0]["cantidad"]);
        $descripcion = explode('|',$this->datos[0]["descripcion"]);
        $serial = explode(',',$this->datos[0]["serial"]);
        $cd = explode(',',$this->datos[0]["cd"]);
        $cd_det = explode(',',$this->datos[0]["condicion_detalle"]);
        $precio_unitario = explode(',',$this->datos[0]["precio_unitario"]);
        $precio_total = explode(',',$this->datos[0]["precio_total"]);

        $parte_det = explode(',',$this->datos[0]["parte_det"]);
        $parte_alter_det = explode(',',$this->datos[0]["parte_alter_det"]);
        $desc_det = explode('|',$this->datos[0]["desc_det"]);
        $serial_det = explode(',',$this->datos[0]["serial_det"]);
        $nro_lote = $this->datos[0]["nro_lote"];


        //var_dump("lelga aqui",$this->datos[0]['tipo_taller']);
        if ($this->datos[0]['tipo_taller'] == NULL) {
          $inciso = 'b)/d)';
        } elseif ($this->datos[0]['tipo_taller'] == 'taller_repues_abas' || $this->datos[0]['tipo_taller'] == 'taller-abas') {
          $inciso = 'd, efectua el presente proceso de contratación directa. para la contratación de servicio denominado';
        } elseif ($this->datos[0]['tipo_taller'] == 'broker_repues_abas' ) {
          $inciso = 'b, efectua el presente proceso de contratación directa. para la contratación de servicio denominado';
        }

        if ($this->datos[0]['evaluacion'] == 'Calibracion') {
          $inciso = 'b, efectua el presente proceso de contratación directa. para la contratación de servicio denominado';
        }




        /*Aqui controlamos los textos de acuerdo a la evaluacion*/
        if ($this->datos[0]['evaluacion'] == NULL) {
          $texto = 'COMPLETAR INFORMACION ADJUDICADA';
        } elseif ($this->datos[0]['evaluacion'] == 'Exchange' || $this->datos[0]['evaluacion'] == 'Flat Exchange') {

          if ($this->datos[0]['aplica_mayo'] == 'si') {
            $texto = '(REP '.$this->datos[0]['nro_rep'].') COMPRA DE REPUESTOS POR INTERCAMBIO (FLAT EXCHANGE) LOTE '.$nro_lote.'';
          } else {
            $texto = '(REP '.$this->datos[0]['nro_rep'].') COMPRA DE REPUESTO POR INTERCAMBIO (FLAT EXCHANGE) LOTE '.$nro_lote.'';
          }
          $texto_ref = 'en '.$this->datos[0]['evaluacion'];
          $texto_detalle = 'de acuerdo a partes removidas entregadas por parte de la
          Gerencia de Mantenimiento, al Almacén de la unidad de Abastecimientos, con su respectiva Tarjeta verde "Parte Reparable" MOM-005 del manual de Organización
          de Mantenimiento "MOM", en la que indica el motivo de la falla y las instrucciones para su reparación, es que la Unidad de Abastecimientos requiere enviar
          dichos componentes a talleres o empresas establecidos en los Estados Unidos de Norte América. Que procedan con la gestión  de reparación. De acuerdo a las normas
          y procedimientos establecidos por la Federal Aviation Administration "FFAA". Y su posterior regreso a Bolivia, con la documentación de respaldo correspondiente.';
        } elseif ($this->datos[0]['evaluacion'] == 'Reparacion') {
          $texto = '(REP '.$this->datos[0]['nro_rep'].') CONTRATACIÓN DE SERVICIO DE REPARACIÓN DE REPUESTOS LOTE '.$nro_lote.'';
          $texto_ref = 'las reparaciones';
          $texto_detalle = 'de acuerdo a partes removidas entregadas por parte de la
          Gerencia de Mantenimiento, al Almacén de la unidad de Abastecimientos, con su respectiva Tarjeta verde "Parte Reparable" MOM-005 del manual de Organización
          de Mantenimiento "MOM", en la que indica el motivo de la falla y las instrucciones para su reparación, es que la Unidad de Abastecimientos requiere enviar
          dichos componentes a talleres o empresas establecidos en los Estados Unidos de Norte América. Que procedan con la gestión  de reparación. De acuerdo a las normas
          y procedimientos establecidos por la Federal Aviation Administration "FFAA". Y su posterior regreso a Bolivia, con la documentación de respaldo correspondiente.';


        } elseif ($this->datos[0]['evaluacion'] == 'Calibracion') {
          $texto = '(REP '.$this->datos[0]['nro_rep'].') CONTRATACIÓN DE SERVICIO DE CALIBRACION DE EQUIPOS LOTE '.$nro_lote.'';
          $texto_ref = 'las calibraciones';
          $texto_detalle = 'de acuerdo a equipos y/o herramientas entregadas por parte de la Gerencia de Mantenimiento, al Almacén de la Unidad de Abastecimientos,
          en donde solicitan que las mismas sean enviadas para su respectiva calibración, es que la Unidad de Abastecimientos requiere enviar dichas unidades a talleres o
          empresas establecidos en los Estados Unidos de Norte América, que procedan con la gestión de la calibración, de acuerdo a normas y procedimientos establecidos por la Federal Aviation Administration "FAA",
          y su posterior regreso a Bolivia, con la documentación de respaldo correspondiente.';



        }

        $incluyeBerTexto = 'NO';
        foreach ($nro_partes as $indice=>$partes){
          if($cd[$indice] == 'B.E.R.'){
            $incluyeBerTexto = 'SI';
          }
         }

         if ($incluyeBerTexto == 'SI' && $this->datos[0]['evaluacion'] != 'Reparacion' ) {
           $texto = '(REP '.$this->datos[0]['nro_rep'].') COMPRA DE REPUESTO EN REEMPLAZO DE UNIDAD DECLARADA B.E.R REQUERIDO PARA FLOTA BOA LOTE '.$nro_lote.'';
         }



        /********************************************************/

        $tb='<table cellspacing="0" cellpadding="1" style="font-size:14px;">
              <tr>
                <th align="center" style="border:1px solid #000000;" width="60"><b>Fecha:</b></th>
                <th align="center" style="border:1px solid #000000;">'.$this->datos[0]["fecha_aprobacion"].'</th>
                <th align="center" width="211"> </th>
                <th align="center" width="250" style="border:1px solid #000000;"><b>OB.DAB.REP. '.$this->datos[0]['nro_rep'].' .'.$this->datos[0]['gestion'].'</b></th>
              </tr>
              </table>
            <table cellspacing="0" cellpadding="1" style="font-size:14px; border:1px solid #000000;">';

            if ($this->datos[0]["quitar_etiqueta"] == 'no') {
              $tb.='
                <tr>
                  <th align="center" style="font-size:14px; border-right:1px solid #000000;"><b>Unidad Solicitante:</b></th>
                  <th align="center" ><b>Gerencia de Área:</b></th>
                </tr>';
            }            


            $tb.='
              <tr>
                <th align="center" style="font-size:14px; border-right:1px solid #000000;"><b>Unidad Abastecimientos</b></th>
                <th align="center" ><b>Gerencia Administrativa y Financiera</b></th>
              </tr>
             </table>
             <table cellspacing="0" cellpadding="1" style="font-size:14px; border:1px solid #000000;">
               <tr>
                 <th align="center" style="font-size:14px; border-right:1px solid #000000;"><b>ANTECEDENTES-JUSTIFICACIÓN</b></th>
               </tr>
            </table>
            <table cellspacing="0" cellpadding="1" style="font-size:14px; border:1px solid #000000;">
              <tr>
                <th align="left" style="font-size:14px; border-right:1px solid #000000;">En cumplimiento a Circular Instructiva <b>'.$this->datos[0]['instructiva'].'</b> de <b>05/01/2021</b> que establece el procedimiento interno
                  para la Contratación de Bienes. Obras y Servicios Especializados en el Extranjero, <b>RESABS-EE</b> BoA. La Unidad de Abastecimientos.
                  acorde al numeral IV "Causales para la Contratación Directa", inciso '.$inciso.':
                  <b>'.$texto.'</b> '.$texto_detalle.'
                </th>
              </tr>
           </table>
           <table cellspacing="0" cellpadding="1" style="font-size:14px; border:1px solid #000000;">
             <tr>
               <th align="left" style="font-size:14px; border-right:1px solid #000000;"><b>Los componentes que deben ser reparados son los siguientes: </b></th>
             </tr>
          </table>
          <table cellspacing="0" cellpadding="1" border="1" style="font-size:12px;">
                <tr>
                  <th></th>
                </tr>
                <tr>
                  <th width="50" align="center"><b>ITEM</b></th>
                  <th width="150" align="center"><b>PART NUMBER</b></th>
                  <th width="170" align="center"><b>DESCRIPCIÓN</b></th>
                  <th width="170" align="center"><b>SERIAL</b></th>
                  <th width="154" align="center"><b>REPAIR INSTRUCTION</b></th>
                </tr>
         </table>';

         $numero = 1;
         foreach ($parte_det as $indice=>$partes){
         $tb.='<table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;">
                   <tr>
                       <td width="50" align="center"><li>'.$numero.'</li></td>
                       <td width="150" align="center"><li>'.$partes.'</li></td>
                       <td width="170" align="center"><li>'.$desc_det[$indice].'</li></td>
                       <td width="170" align="center"><li>'.$serial_det[$indice].'</li></td>
                       <td width="154" align="center"><li>'.$cd_det[$indice].'</li></td>
                   </tr>
               </table>';
             $numero++;
          }

          $incluyeBer = 'NO';

          foreach ($nro_partes as $indice=>$partes){
            if($cd[$indice] == 'B.E.R.'){
              $incluyeBer = 'SI';
            }
           }

           if ($incluyeBer == 'SI' && $this->datos[0]['evaluacion'] != 'Reparacion') {
             $tb.='<table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                     <tr>
                       <th></th>
                     </tr>
                     <tr>
                       <th align="center"><b>PRECIO REFERENCIAL</b></th>
                     </tr>
                  </table>
                  <table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                          <tr>
                            <th align="left">En fecha <b>'.$this->datos[0]["fecha_envio"].'</b> se procedió a invitar via correo electrónico a la Empresa <b>'.$this->datos[0]["nom_provee"].'</b>
                             obteniendo respuesta via correo electrónico el día <b>'.$this->datos[0]["fecha_cotizacion"].'</b> en la que el taller nos informa que la o las unidades fueron declaradas B.E.R (BEYOND ECONOMICAL REPAIR) y nos ofrece reemplazar las mismas con otras unidades, de acuerdo al siguiente cuadro:</th>
                          </tr>
                  </table>
                  <table cellspacing="0" cellpadding="1" border="1" style="font-size:12px;">
                        <tr>
                          <th width="50" align="center"><b>ITEM</b></th>
                          <th width="150" align="center"><b>PART NUMBER</b></th>
                          <th width="150" align="center"><b>DESCRIPCIÓN</b></th>
                          <th width="140" align="center"><b>SERIAL</b></th>
                          <th width="102" align="center"><b>MONTO EN $US.</b></th>
                          <th width="102" align="center"><b>CONDICIÓN</b></th>
                        </tr>
                 </table>';
                 $numero = 1;
                 foreach ($nro_partes as $indice=>$partes){
                 $tb.='<table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;">
                           <tr>
                               <td width="50" align="center"><li>'.$numero.'</li></td>
                               <td width="150" align="center"><li>'.$partes.'</li></td>
                               <td width="150" align="center"><li>'.$descripcion[$indice].'</li></td>
                               <td width="140" align="center"><li>'.$serial[$indice].'</li></td>
                               <td width="102" align="right"><li>'.$precio_total[$indice].'</li></td>
                               <td width="102" align="center"><li>'.$cd[$indice].'</li></td>
                           </tr>
                       </table>';
                     $numero++;
                  }
           } else {
             $tb.='<table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                     <tr>
                       <th></th>
                     </tr>
                     <tr>
                       <th align="center"><b>PRECIO REFERENCIAL</b></th>
                     </tr>
                  </table>
                  <table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                          <tr>
                            <th align="left">En fecha <b>'.$this->datos[0]["fecha_envio"].'</b> se procedió a invitar via correo electrónico a la Empresa <b>'.$this->datos[0]["nom_provee"].'</b>
                             obteniendo respuesta via correo electrónico el día <b>'.$this->datos[0]["fecha_cotizacion"].'</b> en la que cotiza '.$texto_ref.' de acuerdo al siguiente cuadro:</th>
                          </tr>
                  </table>
                  <table cellspacing="0" cellpadding="1" border="1" style="font-size:12px;">
                        <tr>
                          <th width="50" align="center"><b>ITEM</b></th>
                          <th width="150" align="center"><b>PART NUMBER</b></th>
                          <th width="150" align="center"><b>DESCRIPCIÓN</b></th>
                          <th width="140" align="center"><b>SERIAL</b></th>
                          <th width="102" align="center"><b>MONTO EN $US.</b></th>
                          <th width="102" align="center"><b>CONDICIÓN</b></th>
                        </tr>
                 </table>';
                 $numero = 1;
                 foreach ($nro_partes as $indice=>$partes){
                 $tb.='<table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;">
                           <tr>
                               <td width="50" align="center"><li>'.$numero.'</li></td>
                               <td width="150" align="center"><li>'.$partes.'</li></td>
                               <td width="150" align="center"><li>'.$descripcion[$indice].'</li></td>
                               <td width="140" align="center"><li>'.$serial[$indice].'</li></td>
                               <td width="102" align="right"><li>'.$precio_total[$indice].'</li></td>
                               <td width="102" align="center"><li>'.$cd[$indice].'</li></td>
                           </tr>
                       </table>';
                     $numero++;
                  }
           }



               $tb.='<table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;">
                       <tr>
                         <td style="width: 490px; text-align: right;" colspan="4"><b>TOTAL $US: </b></td>
                         <td style="width: 102px; text-align: right;">'.number_format($this->datos[0]['suma_total'], 2, ',', '.').'</td>
                         <td style="width: 102px;"></td>
                       </tr>
                     </table>
                    <table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                       <tr>
                         <th align="center"><b>CONCLUSIONES Y RECOMENDACIONES</b></th>
                       </tr>
                    </table>
                    <table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                       <tr>
                         <th align="left">De acuerdo a cotización de la empresa: <b>'.$this->datos[0]['nom_provee'].'</b> y la evaluación del Comité de Evaluación de Compra
                         y Selección de Proveedor, es que los integrantes del Comité aceptan y recomiendan se proceda con los trabajos detallados en cuadro anterior, en concordancia a las Especifcaciones Técnicas del presente proceso.
                         Por un monto de $us. <b>'.number_format($this->datos[0]['suma_total'], 2, ',', '.').' ('.$this->datos[0]['suma_literal'].' Dólares Americanos)</b></th>
                       </tr>
                    </table>';


        $this->writeHTML($tb);
        $this->writeHTML($firmas);
        $this->writeHTML($firmas2);
        $this->writeHTML($pie);

    }
    function ReporteComiteEvaluacion2(){


        $this->setPrintHeader(false);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        if ($this->getNumPages() == 1) {
          $this->AddPage();
        }

        $funcionario_sol = $this->datos[0]["firma_unidad"];
        $jefeDepartamento = $this->datos[0]["firma_jefe_departamento"];
        $nro_lote = $this->datos[0]["nro_lote"];

        $nro_partes = explode(',',$this->datos[0]["num_part"]);
        $cd = explode(',',$this->datos[0]["cd"]);

        if ($this->datos[0]['evaluacion'] == NULL) {
          $texto = 'COMPLETAR INFORMACION ADJUDICADA';
        } elseif ($this->datos[0]['evaluacion'] == 'Exchange' || $this->datos[0]['evaluacion'] == 'Flat Exchange') {

          if ($this->datos[0]['aplica_mayo'] == 'si') {
              $texto = '(REP '.$this->datos[0]['nro_rep'].') COMPRA DE REPUESTOS POR INTERCAMBIO (FLAT EXCHANGE) LOTE '.$nro_lote.'';
          }else{
              $texto = '(REP '.$this->datos[0]['nro_rep'].') COMPRA DE REPUESTO POR INTERCAMBIO (FLAT EXCHANGE) LOTE '.$nro_lote.'';
          }

        } elseif ($this->datos[0]['evaluacion'] == 'Reparacion') {
          $texto = '(REP '.$this->datos[0]['nro_rep'].') CONTRATACIÓN DE SERVICIO DE REPARACIÓN DE REPUESTOS LOTE '.$nro_lote.'';
        } elseif ($this->datos[0]['evaluacion'] == 'Calibracion') {
          $texto = '(REP '.$this->datos[0]['nro_rep'].') CONTRATACIÓN DE SERVICIO DE CALIBRACION DE EQUIPOS LOTE '.$nro_lote.'';
        }



        $incluyeBerTexto = 'NO';
        foreach ($nro_partes as $indice=>$partes){
          if($cd[$indice] == 'B.E.R.'){
            $incluyeBerTexto = 'SI';
          }
         }

         if ($incluyeBerTexto == 'SI' && $this->datos[0]['evaluacion'] != 'Reparacion') {
           $texto = '(REP '.$this->datos[0]['nro_rep'].') COMPRA DE REPUESTO EN REEMPLAZO DE UNIDAD DECLARADA B.E.R REQUERIDO PARA FLOTA BOA LOTE '.$nro_lote.'';
         }


        $fun_sol = explode('|', $funcionario_sol);

        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($funcionario_sol == NULL) {
          $fun_sol_0 = ' ';
          $fun_sol_1 = '';
          $fun_sol_3 = '';
          $fun_sol_4 = '';
        } else {
          $fun_sol_0 = $fun_sol[0];
          $fun_sol_1 = $fun_sol[1];
          $fun_sol_3 = $fun_sol[3];
          $fun_sol_4 = $fun_sol[4];
        }
        /****************************************************************************/

        $jefeDepto = explode('|', $jefeDepartamento);

        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($jefeDepartamento == NULL) {
          $jefeDepto_0 = ' ';
          $jefeDepto_1 = '';
          $jefeDepto_3 = '';
          $jefeDepto_4 = '';
        } else {
          $jefeDepto_0 = $jefeDepto[0];
          $jefeDepto_1 = $jefeDepto[1];
          $jefeDepto_3 = $jefeDepto[3];
          $jefeDepto_4 = $jefeDepto[4];
        }
        /*************************************************************************/


              $tb='
              <table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                 <tr>
                   <th align="center"><b>SOLICITUD DE AUTORIZACIÓN Y RECOMENDACIÓN</b></th>
                 </tr>
              </table>
              <table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                 <tr>
                   <th align="left">Por lo expuesto en el presente informe, sustentados en el análisis y la evaluación realizada por el <b>Comité de Evaluación de Compra y Selección de Proveedores,</b> se solicita al Responsable de Proceso de Contratación  en el Exterior
                   (RPCE), que en cumplimineto a los procedimientos administrativos internos autorice el proceso de contratación directa en el exterior denominado:
                   <b>'.$texto.'</b> y se recomienda la adjudicación a la empresa: <b>'.$this->datos[0]['nom_provee'].'.</b>
                   por un importe total de $us: <b>'.number_format($this->datos[0]['suma_total'], 2, ',', '.').' ('.$this->datos[0]['suma_literal'].' Dólares Americanos)</b> en razón
                   de que su propuesta CUMPLE CON TODOS los requerimientos establecidos en las Especificaciones Técnicas.
                   </th>
                </tr>
              </table>';

              if ($this->datos[0]["mayor"] == 'menor') {
              $tb .=   '
              <table table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                  <tr>
                      <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Jefe de Unidad de Abastecimientos:</b> </td>
                      <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Jefe Departamento Abastecimiento y Logistica:</b><br></td>
                  </tr>
                  <tr>
                      <td align="center" style="font-family: Calibri; font-size: 9px;">
                          <br><br>
                          <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_sol_0, $fun_sol_1,$fun_sol_4,$fun_sol_3).'" alt="Logo">
                          <br>'.$fun_sol_0.'
                      </td>
                      <td align="center" style="font-family: Calibri; font-size: 9px;" colspan="2">
                      <br><br>
                      <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($jefeDepto_0, $jefeDepto_1,$jefeDepto_4,$jefeDepto_3) . '" alt="Logo">
                       <br>'.$jefeDepto_0.'
                      </td>
                   </tr>
              </table>
              ';
              } else {

                if ($this->datos[0]['editar_etiqueta'] == 'si') {
                  $tb .= '<table table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                      <tr>
                        <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Elaborado Por:</b> </td>
                        <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> '.$fun_sol_1.':</b><br></td>
                      </tr>';
                } else {
                  $tb .= '<table table cellspacing="0" cellpadding="1" border="1" style="font-size:14px;">
                      <tr>
                        <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Elaborado Por:</b> </td>
                        <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Jefe Departamento Abastecimiento y Logistica:</b><br></td>
                      </tr>';
                }


                $tb .=   '

                    <tr>
                        <td align="center" style="font-family: Calibri; font-size: 9px;">
                            <br><br>
                            <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($jefeDepto_0, $jefeDepto_1,$jefeDepto_4,$jefeDepto_3) . '" alt="Logo">
                             <br>'.$jefeDepto_0.'
                        </td>
                        <td align="center" style="font-family: Calibri; font-size: 9px;">
                            <br><br>
                            <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_sol_0, $fun_sol_1,$fun_sol_4,$fun_sol_3).'" alt="Logo">
                            <br>'.$fun_sol_0.'
                        </td>
                     </tr>
                </table>
                ';
              }






        $this->writeHTML($tb);
        $this->writeHTML($firmas);
        $this->writeHTML($firmas2);
        $this->writeHTML($pie);

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
        $this->ReporteComiteEvaluacion();
        $this->ReporteComiteEvaluacion2();
        //$this->revisarfinPagina();
    }
}
?>
