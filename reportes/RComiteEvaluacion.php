<?php
class RComiteEvaluacion extends  ReportePDF
{
    function Header(){
        $this->Ln(8);
        $this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        $this->SetFontSize(12);
        $this->SetFont('', 'B');
        $this->MultiCell(105, 25, "\n" . 'COMITÉ DE EVALUACIÓN DE' . "\n" . 'COMPRA Y SELECCIÓN DE'."\n".'PROVEEDOR', 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, 25, "\n" . 'Form MA-01'."\n" .' '."\n" .'Rev 0 20/06/2016'."\n" .$this->datos[0]['nro_tramite'], 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png', 12, 15, 36);
    }
    function ReporteComiteEvaluacion(){
        $this->AddPage();
        $fechaPo= $this->datos[0]['fecha_po'];
        $fcotizaciones = $this->datos[0]['cotizacion_solicitadas'];
        $cotizacionReci = $this->datos[0]['proveedores_resp'];
        $proveedor = $this->datos[0]['desc_proveedor'];
        $neo_cotizacion =$this->datos[0]['nro_cotizacion'];
        if ($this->datos[0]['fecha_solicitud'] >= $this->datos[0]['fecha_salida']) {
        $itemSeleccionados = preg_replace('/[0-9]+/', '', $this->datos[0]['items_diferentes']);
        $itemSeleccionados =str_replace(',/','',$itemSeleccionados);
        $itemSeleccionados =str_replace('/','',$itemSeleccionados);
        $titulo_items = 'Cant. de Items Diferentes';
        } else {
        $itemSeleccionados = preg_replace('/[0-9]+/', '', $this->datos[0]['item_selecionados']);
        $itemSeleccionados =str_replace(',/','',$itemSeleccionados);
        $itemSeleccionados =str_replace('/','',$itemSeleccionados);
        $titulo_items = 'Items Seleccionados';
        }
        $tipo = $this->datos[0]['tipo_evaluacion'];
        $nroParte = explode(',',$this->datos[0]['nro_parte']);
        $a ='{';
        $b ='}';
        $remplace = '';
        $nroParte = str_replace($a,$remplace,$nroParte);
        $nroParte = str_replace($b,$remplace,$nroParte);
        $nomto = $this->datos[0]['monto_total'];
        $observaciones = $this->datos[0]['observacion_nota'];
        $talleAsignado = $this->datos[0]['taller_asignado'];
        $obs = $this->datos[0]['obs'];
        $simbolo = $this->datos[0]['codigo'];
        $mont = number_format($nomto, 2);
        if ($this->datos[0]['fecha_solicitud'] >= $this->datos[0]['fecha_salida']) {
          $tb ='<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                  <th scope="col" width="60" align="center" ><b>Fecha:</b></th>
                  <th scope="col" width="95" align="center" >'.$fechaPo.'</th>
                  <th scope="col" width="195" > <b>Cotizaciones solicitadas:</b></th>
                  <th scope="col" width="82"  align="center">'.$fcotizaciones.'</th>
                  <th scope="col" width="193"> <b>Cotizaciones calificadas:</b></th>
                  <th scope="col" width="70"  align="center">'.$cotizacionReci.'</th>
                </tr>';
        } else {
          $tb ='<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                  <th scope="col" width="60" align="center" ><b>Fecha:</b></th>
                  <th scope="col" width="95" align="center" >'.$fechaPo.'</th>
                  <th scope="col" width="195" > <b>Cotizaciones solicitadas:</b></th>
                  <th scope="col" width="82"  align="center">'.$fcotizaciones.'</th>
                  <th scope="col" width="193"> <b>Cotizaciones recibidas:</b></th>
                  <th scope="col" width="70"  align="center">'.$cotizacionReci.'</th>
                </tr>';
        }

        $tb .= '<tr>
                    <th scope="col" width="200" > <b>Cotizacion Seleccionada:</b></th>
                    <th scope="col" width="98"   align="center" >' . $neo_cotizacion . '</th>
                    <th scope="col" width="196"  align="center"><b>Tipo de Evaluacion:</b></th>
                    <th scope="col" width="200"   align="center" >' . $tipo . '</th>
                  </tr>';
        /*  if ($tipo == 'Compra') {
             $tb .= '<tr>
                     <th scope="col" width="200" > <b>Cotizacion Seleccionada:</b></th>
                     <th scope="col" width="98"   align="center" >' . $neo_cotizacion . '</th>
                     <th scope="col" width="196"  align="center"><b>Tipo de Evaluacion:</b></th>
                     <th scope="col" width="200"   align="center" >' . $tipo . '</th>
                     <th scope="col" width="100"  align="center" ><b>Reparación:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                     <th scope="col" width="100"  align="center" ><b>Exchange:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                   </tr>';
        }elseif ($tipo == 'Reparacion'){
             $tb .= '<tr>
                     <th scope="col" width="200" > <b>Cotizacion Seleccionada:</b></th>
                     <th scope="col" width="98"   align="center" >' . $neo_cotizacion . '</th>
                     <th scope="col" width="100"  align="center"><b>Compra:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                     <th scope="col" width="100"  align="center" ><b>Reparación:</b></th>
                     <th scope="col" width="32"   align="center" >SI</th>
                     <th scope="col" width="100"  align="center" ><b>Exchange:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                   </tr>';
         }elseif ($tipo == 'Exchage'){
             $tb .= '<tr>
                     <th scope="col" width="200" > <b>Cotizacion Seleccionada:</b></th>
                     <th scope="col" width="98"   align="center" >' . $neo_cotizacion . '</th>
                     <th scope="col" width="100"  align="center"><b>Compra:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                     <th scope="col" width="100"  align="center" ><b>Reparación:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                     <th scope="col" width="100"  align="center" ><b>Exchange:</b></th>
                     <th scope="col" width="32"   align="center" >SI</th>
                   </tr>';
         }*/
        $tb.=' <tr>
                        <th scope="col"  height="10"> <b>Empresa:</b></th>
                        <th scope="col" width="495"  align="justify"> '.$proveedor.'</th>
                   </tr>
                   <tr>
                        <th scope="col"  height="10" > <b>'.$titulo_items.':</b></th>
                        <th scope="col" width="495"  align="justify">  '.$itemSeleccionados.'</th>
                   </tr> </table>
      <style> .conborde {
                            border: 1px solid #000;
                            border-collapse:collapse;
                        }
                            table.conborde th,
                            table.conborde td { border = 0;
                        }
      </style>
    <table class="conborde">
    <tr>
      <td width="150"><b>Comentario:</b></td>
      <td align="center" width="545" > El Comité de evaluación de acuerdo al cuadro comparativo adjunto y siendo la opción mas conveniente, recomienda la adjudicación al proveedor
           <br> <b>'.$proveedor.'</b> por un importe de <b>'.$simbolo.' '.$mont.'.</b></td>
    </tr>
    <br>';

if($this->datos[0]['fecha_solicitud'] >= $this->datos[0]['fecha_salida']){
    $numero = 1;
    $tb.='
     <table border="1">
        <tr>
    <td style="width: 32px;" align="center" ><b>Nro.</b></td>
    <td style="width: 120px;" align="center" ><b>Part Number Solicitado</b></td>
    <td style="width: 120px;" align="center" ><b>Part Number Cotización Alterno</b></td>
    <td align="center" ><b>Descripción</b></td>
    <td style="width: 70px;" align="center" ><b>Cantidad </b></td>
    <td align="center" ><b>Condición</b></td>
    <td style="width: 140px;" align="center" ><b>Tiempo Entrega</b></td>
    </tr>
    ';
    foreach ($this->datos as $value){

        $tb .= '<tr><td style="width: 32px;" align="center" >'.$numero.'</td>';
        $tb .= '<td align="center" >'.$value['parte'].'</td>';
        $tb .= '<td align="center" >'.$value['explicacion_detallada_part_cot'].'</td>';
        $tb .= '<td align="center" >'.$value['descripcion_cot'].'</td>';
        $tb .= '<td align="center" >'.$value['cantidad_det'].'</td>';
        $tb .= '<td align="center" >'.$value['cd'].'</td>';
        $tb .= '<td align="center" >'.$value['codigo_tipo'].'</td>
     </tr>';

     $numero ++;
    }
} else {
  $tb.='
   <table border="1">
      <tr>
  <td align="center" ><b>Part Number Solicitado</b></td>
  <td align="center" ><b>Descripción</b></td>
  <td align="center" ><b>Cantidad </b></td>
  <td align="center" ><b>Condición</b></td>
  <td align="center" ><b>Tiempo Entrega</b></td>
  </tr>
  ';
  foreach ($this->datos as $value){

      $tb .= '<tr><td align="center" >'.$value['parte'].'</td>';
      $tb .= '<td align="center" >'.$value['descripcion_cot'].'</td>';
      $tb .= '<td align="center" >'.$value['cantidad_det'].'</td>';
      $tb .= '<td align="center" >'.$value['cd'].'</td>';
      $tb .= '<td align="center" >'.$value['codigo_tipo'].'</td>
   </tr>';
  }
}
        $tb .= '
</table>
    <tr>
      <td width="150"><b>Observaciones:</b></td>
      <td align="justify" width="545" >'.$obs.'<br></td>
    </tr>
    </table>
    <br>';

$tb .= '<table border="1">
          <br>
          <tr>
            <td width="695"> <b>Para componentes reparados</b>
            <br> Las unidad(es) reparadas fueron enviadas a los talleres asignados? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$talleAsignado.'
            <br> <b>Observaciones:</b> '.$observaciones.'</td>
          </tr>
        <br>
          <tr>
            <td width="695"> <b>Observaciones para componentes en exchange:</b> '.$observaciones.'</td>
          </tr>
        </table>';
        //$tb.='';
        if($this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos') {
            $revision = $this->datos[0]['visto_rev'];
            $fun_rev = explode('|', $revision);

            $nombre_rev_fun = $fun_rev[0];
            $cargo_rev = $fun_rev[1];
            $tramite_rev = strtoupper($fun_rev[2]);
            $institucion_rev = $fun_rev[3];
            $fecha_rev = $this->datos[0]['fecha_rev'];

            $primeraFirma = $this->codigoQr('Funcionario: '.$nombre_rev_fun."\n".'Cargo: '.$cargo_rev."\n".'Nro Trámite: '.$tramite_rev."\n".'Institución: '.$institucion_rev."\n".'Fecha: '.$fecha_rev, 'prime');
        }
        if(  $this->datos[0]["estado_materiales"] != 'comite_aeronavegabilidad' and $this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos') {
            $abastecimiento = $this->datos[0]['visto_abas'];
            $fun_abas = explode('|', $abastecimiento);

            $nombre_abas_fun = $fun_abas[0];
            $cargo_abas = $fun_abas[1];
            $tramite_abas = strtoupper($fun_abas[2]);
            $institucion_abas = $fun_abas[3];
            $fecha_abas = $this->datos[0]['fecha_abas'];

            $segundaFirma = $this->codigoQr('Funcionario: '.$nombre_abas_fun."\n".'Cargo: '.$cargo_abas."\n".'Nro Trámite: '.$tramite_abas."\n".'Institución: '.$institucion_abas."\n".'Fecha: '.$fecha_abas, 'segundo');
        }
        if( $this->datos[0]["estado_firma"] != 'comite_aeronavegabilidad') {
            $aero = $this->datos[0]['aero'];
            $fun_aero = explode('|', $aero);

            $nombre_aero_fun = $fun_aero[0];
            $cargo_aero = $fun_aero[1];
            $tramite_aero = strtoupper($fun_aero[2]);
            $institucion_aero = $fun_aero[3];
            $fecha_aero = $this->datos[0]['fecha_aero'];

            $terceraFirma = $this->codigoQr('Funcionario: '.$nombre_aero_fun."\n".'Cargo: '.$cargo_aero."\n".'Nro Trámite: '.$tramite_aero."\n".'Institución: '.$institucion_aero."\n".'Fecha: '.$fecha_aero, 'tercera');
        }
        if($this->datos[0]["codigo_pres"] != 'vb_rpcd' and $this->datos[0]["estado_materiales"] != 'comite_dpto_abastecimientos' and $this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos' and $this->datos[0]["estado_materiales"] != 'comite_aeronavegabilidad') {
            $rpce = $this->datos[0]['funcionario_pres'];
            $fun_rpcs = explode('|', $rpce);

            $nombre_rpcs_fun = $fun_rpcs[0];
            $cargo_rpcs = $fun_rpcs[1];
            $tramite_rpcs = strtoupper($fun_rpcs[2]);
            $institucion_rpcs = $fun_rpcs[3];
            $fecha_rpcs = $this->datos[0]['fecha_pres'];

            $CuartaFirma = $this->codigoQr('Funcionario: '.$nombre_rpcs_fun."\n".'Cargo: '.$cargo_rpcs."\n".'Nro Trámite: '.$tramite_rpcs."\n".'Institución: '.$institucion_rpcs."\n".'Fecha: '.$fecha_rpcs, 'cuarto');
        }

        /*Aumentando la firma del tecnico de abastecimiento (Ismael Valdivia 16/02/2022)*/
        if($this->datos[0]['firma_tecnico_abastecimiento'] != '') {
            $firma_tecnico_abas = $this->datos[0]['firma_tecnico_abastecimiento'];
            $tecnico_abastecimiento = explode('|', $firma_tecnico_abas);
            $nombre_tecnico_abastecimiento = $tecnico_abastecimiento[0];
            $cargo_tecnico_abastecimiento = $tecnico_abastecimiento[1];
            $tramite_tecnico_abastecimiento = strtoupper($tecnico_abastecimiento[2]);
            $institucion_abastecimiento = $tecnico_abastecimiento[3];
            $fecha_tecnico_abastacieminto = $this->datos[0]['fecha_pres'];

            $firma_qr_tecnico_abastecimiento = $this->codigoQr('Funcionario: '.$nombre_tecnico_abastecimiento."\n".'Cargo: '.$cargo_tecnico_abastecimiento."\n".'Nro Trámite: '.$tramite_tecnico_abastecimiento."\n".'Institución: '.$institucion_abastecimiento."\n".'Fecha: '.$fecha_tecnico_abastacieminto, 'quinta');
        }
        /********************************************************************************/


        //if(  $this->datos[0]["estado_materiales"] != 'comite_aeronavegabilidad' and $this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos') {
//      var_dump('llega' , $this->datos[0]['fecha_solicitud']);exit;
//      inicia desde el 01-06-2019 para nueva firma del funcionario responsable del tramite
         if($this->datos[0]['fecha_solicitud'] >= '2019-06-01'){
             $resp = $this->datos[0]['funcionario_resp'];
             $fun_resp = explode('|', $resp);

             $nombre_resp_fun = $fun_resp[0];
             $cargo_resp = $fun_resp[1];
             $tramite_resp = strtoupper($fun_resp[2]);
             $institucion_resp = $fun_resp[3];
             $fecha_resp = $this->datos[0]['fecha_resp'];

             $quintaFirma = $this->codigoQr('Funcionario: '.$nombre_resp_fun."\n".'Cargo: '.$cargo_resp."\n".'Nro Trámite: '.$tramite_resp."\n".'Institución: '.$institucion_resp."\n".'Fecha: '.$fecha_resp, 'quinto');

             $v_tit ='<td style="font-family: Calibri;font-size: 11px"align="center"><b>Técnico Adquisiciones</b><br>'.$fun_resp[0].'</td>';
             $v_firma = '<td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $quintaFirma . '" alt="Logo"><br></td>';

         }else{
             $v_tit ='';
             $v_firma ='';
         }
//
        //}
        //if ($this->datos[0]["codigo_pres"] != 'vbrpc' or $this->datos[0]["codigo_pres"] != 'suppresu'or $this->datos[0]["codigo_pres"] != 'vbgaf' ){
        $var = 'X';
        //}

        if (trim($nombre_abas_fun) != '') {

          if (trim($nombre_rpcs_fun) == trim($nombre_abas_fun)) {
            $firmas ='
           <table border="2">
             <tbody>
            <tr>
                    <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_rev[1].'</b><br>'.$fun_rev[0].'</td>
                    <!-- <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_abas[1].'</b><br>'.$fun_abas[0].'</td> -->
            </tr>
            <tr>
                    <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $primeraFirma . '" alt="Logo"><br></td>
                  <!--  <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $segundaFirma . '" alt="Logo"><br></td> -->
             </tr>
             </tbody>
            </table>
            ';
          } else {
            $firmas ='
           <table border="2">
             <tbody>
            <tr>
                    <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_abas[1].'</b><br>'.$fun_abas[0].'</td>
                    <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_rev[1].'</b><br>'.$fun_rev[0].'</td>
            </tr>
            <tr>
                    <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $segundaFirma . '" alt="Logo"><br></td>
                    <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $primeraFirma . '" alt="Logo"><br></td>
             </tr>
             </tbody>
            </table>
            ';
          }

        } else {

          if ($this->datos[0]['firma_tecnico_abastecimiento'] != '') {
            $firmas ='
           <table border="2">
             <tbody>
            <tr>

                    <td style="font-family: Calibri;font-size: 11px"align="center"><b>Técnico Abastecimientos</b><br>'.$tecnico_abastecimiento[0].'</td>
                    '.$v_tit.'



            </tr>
            <tr>

                    <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $firma_qr_tecnico_abastecimiento . '" alt="Logo"><br></td>
                    '.$v_firma.'
             </tr>
             </tbody>
            </table>
            ';
          } else {
            $firmas ='
           <table border="2">
             <tbody>
            <tr>
                    <!-- <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_abas[1].'</b><br>'.$fun_abas[0].'</td> -->
                    <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_rev[1].'</b><br>'.$fun_rev[0].'</td>
            </tr>
            <tr>
                    <!-- <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $segundaFirma . '" alt="Logo"><br></td> -->
                    <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $primeraFirma . '" alt="Logo"><br></td>
             </tr>
             </tbody>
            </table>
            ';
          }


        }

        $fun_presu = explode('|', $aero);
        if ($this->datos[0]['firma_tecnico_abastecimiento'] != '') {
          $firmas2 ='
         <table border="2">
           <tbody>
          <tr>
                  <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_rev[1].'</b><br>'.$fun_rev[0].'</td>
                  <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_aero[1].'</b><br>'.$fun_aero[0].'</td>

          </tr>
          <tr>
                  <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $primeraFirma . '" alt="Logo"><br></td>
                  <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $terceraFirma . '" alt="Logo"></td>

           </tr>
           </tbody>
          </table>
          ';
        } else {
          $firmas2 ='
         <table border="2">
           <tbody>
          <tr>
                  <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_aero[1].'</b><br>'.$fun_aero[0].'</td>
                  '.$v_tit.'
          </tr>
          <tr>
                  <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $terceraFirma . '" alt="Logo"></td>
                  '.$v_firma.'
           </tr>
           </tbody>
          </table>
          ';
        }

        // $pie='<table border="1">
        //     <tr>';
        // if ($this->datos[0]["codigo_pres"] == 'vbrpc' or $this->datos[0]["codigo_pres"] == 'suppresu'or $this->datos[0]["codigo_pres"] == 'vbgaf' or $this->datos[0]["codigo_pres"] == 'vbpresupuestos') {
        //     $pie .= '  <td align="center" >ACEPTACION [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RECHAZA [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td><br>
        //     ';
        // }else{
        //     $pie .= ' <td align="center" >ACEPTACION [&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RECHAZA [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td><br>
        //     ';
        // }
        // $pie.=' </tr>
        //     <tr>
        //       <td align="justify"><b>Instruciones RPCE:</b> Adjudiquese el proceso de contratación de '.strtolower($itemSeleccionados).' items  a la empresa '.$proveedor.', de acuerdo con la recomendación del comite de evaluacion de compra y selección de proveedor. Debiendo notificarse via Purchase Order a la empresa adjudicada.</td>
        //     </tr>
        //     <tr>
        //     <td style="font-family: Calibri;font-size: 11px"align="center"><b>RPCE</b><br>'.$fun_rpcs[0].'</td>
        //     </tr>
        //     <tr>
        //     <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $CuartaFirma . '" alt="Logo"><br></td>
        //     </tr>
        //     </table>';

        $this->writeHTML($tb);
        $pagina = $this->getPage();
        $y1 = $this->GetY();

        if ($y1 <= 165) {
          $this->writeHTML($firmas);
          $this->AddPage();
          $this->writeHTML($firmas2);
        } else if ($y1 > 165) {
          $this->AddPage();
          $this->writeHTML($firmas);
          $this->writeHTML($firmas2);
        }


    }
    function ReporteComiteEvaluacion2(){

        //  $this->AddPage();
        if($this->datos[0]["codigo_pres"] != 'vb_rpcd' and $this->datos[0]["estado_materiales"] != 'vb_rpcd' and $this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos' and $this->datos[0]["estado_materiales"] != 'comite_aeronavegabilidad') {
            $rpce = $this->datos[0]['funcionario_pres'];
            $fun_rpcs = explode('|', $rpce);

            $nombre_rpcs_fun = $fun_rpcs[0];
            $cargo_rpcs = $fun_rpcs[1];
            $tramite_rpcs = strtoupper($fun_rpcs[2]);
            $institucion_rpcs = $fun_rpcs[3];
            $fecha_rpcs = $this->datos[0]['fecha_pres'];


            $CuartaFirma = $this->codigoQr('Funcionario: '.$nombre_rpcs_fun."\n".'Cargo: '.$cargo_rpcs."\n".'Nro Trámite: '.$tramite_rpcs."\n".'Institución: '.$institucion_rpcs."\n".'Fecha: '.$fecha_rpcs, 'cuarto');
        }
        //if ($this->datos[0]["codigo_pres"] != 'vbrpc' or $this->datos[0]["codigo_pres"] != 'suppresu'or $this->datos[0]["codigo_pres"] != 'vbgaf' ){
        $var = 'X';
        //}

        $pie='<table border="1">
            <tr>';
        if ($this->datos[0]["codigo_pres"] == 'vbrpc' or $this->datos[0]["codigo_pres"] == 'suppresu'or $this->datos[0]["codigo_pres"] == 'vbgaf' or $this->datos[0]["codigo_pres"] == 'vbpresupuestos') {
            $pie .= '  <td align="center" >ACEPTACION [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RECHAZA [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td><br>
            ';
        }else{
            $pie .= ' <td align="center" >ACEPTACION [&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RECHAZA [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td><br>
            ';
        }
        $pie.=' </tr>
            <tr>
              <td align="justify"><b>Instruciones RPCE:</b> Adjudiquese el proceso de contratación de '.strtolower($itemSeleccionados).' items  a la empresa '.$proveedor.', de acuerdo con la recomendación del comite de evaluacion de compra y selección de proveedor. Debiendo notificarse via Purchase Order a la empresa adjudicada.</td>
            </tr>
            <tr>
            <td style="font-family: Calibri;font-size: 11px"align="center"><b>RPCE</b><br>'.$fun_rpcs[0].'</td>
            </tr>
            <tr>
            <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $CuartaFirma . '" alt="Logo"><br></td>
            </tr>
            </table>';

        $this->writeHTML($pie);

    }
    function  codigoQr ($cadena,$ruta){
        $barcodeobj = new TCPDF2DBarcode($cadena, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/".$ruta.".png");
            imagedestroy($im);
        } else {
            echo 'An error occurred.';
        }
        $url =  dirname(__FILE__) . "/../../reportes_generados/".$ruta.".png";
        return $url;
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
