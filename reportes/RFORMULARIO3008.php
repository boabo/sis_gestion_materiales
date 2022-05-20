<?php
class RFORMULARIO3008 extends  ReportePDF
{
    function Header(){
    $header =   '<table style="width: 1200px; table-layout: fixed;">
                      <tbody>
                        <tr>
                          <td style="width: 300px; text-align: left;"><img src="../../../lib/imagenes/ContraloriaImg.png" width="300" height="70"></td>
                          <td style="width: 200px;">&nbsp;</td>
                          <td style="width: 200px; text-align: center;"><br/><br/>Formulario F-3008 <br/>RE/CE-086</td>
                        </tr>
                      </tbody>
                    </table>';
      $this->writeHTML($header);
    }
    function ReporteOrdenRaparacion(){
      $this->setPrintFooter(false);
      $this->SetMargins(10,22,10);
      $this->AddPage();

        $this->Ln(6);
        $cabecera = '<table style="width: 1200px; table-layout: fixed;">
                        <tbody>
                          <tr>
                            <td style="width: 700px; text-align: center; font-size:13px; font-weight: bold;" colspan="3"><br/><br/>FORMULARIO DE LA DECLARACIÓN JURADA SOBRE INFORMACIÓN <br/>DEL PROCESO DE CONTRATACION DIRECTA EN EMPRESAS PÚBLICAS <br/>DEL NIVEL CENTRAL DEL ESTADO</td>
                          </tr>
                        </tbody>
                      </table>';
          $this->writeHTML($cabecera);

        $this->SetFontSize(9);
        $this->SetFont('', 'B');
        $this->MultiCell(0, 0, "I. DATOS BÁSICOS DE LA CONTRATACION DIRECTA", 0, 'L', 0,1, '', '');
        $this->Ln(2);
        $this->SetFont('', '');

        /**************************************Texto de la justificacion********************************/
        $justificacion = 'RESABS-EPNE-EE (D.S. 26688 Y D.S. 3935) ART.30 UNIDAD SOLICITANTE';
        $justificacion2_1 = 'RESABS-EPNE-EE (D.S. 26688 Y D.S. 3935) ART.30 UNIDAD SOLICITANTE Y ART.12';
        $justificacion5_1 = 'RESABS-EPNE-EE (D.S. 26688 Y D.S. 3935) ART.30 Y ART.24 UNIDAD FINANCIERA';
        $justificacion6_1 = 'RESABS-EPNE-EE (D.S. 26688 Y D.S. 3935) ART.13 Y ART.30 UNIDAD ADMINISTRATIVA';
        $justificacion7_1 = 'RESABS-EPNE-EE (D.S. 26688 Y D.S. 3935) ART.30';
        $justificacion8_1 = 'RESABS-EPNE-EE (D.S. 26688 Y D.S. 3935) ART.30 UNIDAD ADMINISTRATIVA';//Tambien al 9.1
        $justificcacion16_1 = 'MANUAL DE OPERACIONES DEL SICOES 7.2.11 INC. A)';
        $justificacion17_1 = 'RESABS-EPNE-EE (D.S. 26688 Y D.S. 3935) ART.30 RPCE ';
        /*****************************************************************************/


        /*Texto de las Aclaraciones*/
        $aclaracion6_1='MEDIANTE CORREO ELECTRONICO EN MARCO AL ART.10 DEL REGLAMENTO';
        $aclaracion7_1='PROPUESTA RECIBIDA A TRAVES DE CORREO ELECTRONICO DE ACUERDO A ART.14. RECEPCION DE PROPUESTA "PODRAN SER RECIBIDAS EN MEDIO FISICO O DIGITAL"';
        $aclaracion8_1__12_1='NO APLICA POR SER UN SERVICIO ESPECIALIZADO EN EL EXTRANJERO';
        $aclaracion13_1__15_1='NO APLICA POR SER UN SERVICIO FORMALIZADO  CON ORDEN DE SERVICIO';
        $aclaracion17_1='INFORME DE EVALUACIÓN';
        /***************************/


        /*Aqui armaremos el detalle de los proveedores*/
        $cantidad_proveedores = explode(',',$this->datos[0]["nro_cotizacion"]);
        $total_array_proveedores = count($cantidad_proveedores);


        // for (i=0; i<=17; i++;) {
        //   $detalle_proveedores .= '<tr>
        //                             <td>'.$cantidad_proveedores[i].'</td>
        //                           </tr>';
        // }
        //
        // $detalle_proveedores1 .= '<table>
        //                             <tbody>
        //                             '.$detalle_proveedores.'
        //                             </tbody>
        //                           </table>';
        // var_dump("aqui llega los proveedores",$detalle_proveedores1);exit;

        /****************************************/


        $tablaDatosBasicos = '<table border="1" style="width: 1200px; table-layout: fixed;" nobr="true">
                                  <tbody>
                                    <tr>
                                      <td style="width: 280px; text-align: right; font-weight: bold;">Denominación de la Empresa Pública:</td>
                                      <td style="width: 390px; text-align: left;">'.$this->datos[0]['nombre_empresa'].'</td>
                                    </tr>
                                    <tr>
                                    <td style="width: 280px; text-align: right; font-weight: bold;">Código institucional:</td>
                                    <td style="width: 390px; text-align: left;">'.$this->datos[0]['cod_institucional'].'</td>
                                    </tr>
                                    <tr>
                                    <td style="width: 280px; text-align: right; font-weight: bold;">CUCE: <br/><span style="font-style: italic;">(Para Contrataciones mayores a Bs. 20000)</span></td>
                                    <td style="width: 390px; text-align: left;">'.$this->datos[0]['nro_cuce'].'</td>
                                    </tr>
                                    <tr>
                                    <td style="width: 280px; text-align: right; font-weight: bold;">Código interno:</td>
                                    <td style="width: 390px; text-align: left;">'.$this->datos[0]['nro_tramite'].'</td>
                                    </tr>
                                    <tr>
                                    <td style="width: 280px; text-align: right; font-weight: bold;">Objeto de contratación:</td>
                                    <td style="width: 390px; text-align: left;">'.$this->datos[0]['objeto_contratacion'].'</td>
                                    </tr>
                                    <tr>
                                    <td style="width: 280px; text-align: right; font-weight: bold;">Importe total contratado:</td>
                                    <td style="width: 390px; text-align: left;">'.$this->datos[0]['monto_total'].'</td>
                                    </tr>
                                    <tr>
                                    <td style="width: 280px; text-align: right; font-weight: bold;">Proveedor:</td>
                                    <td style="width: 390px; text-align: left;">'.$this->datos[0]['proveedor'].'</td>
                                    </tr>
                                    <tr>
                                    <td style="width: 280px; text-align: right; font-weight: bold;">Plazo:</td>
                                    <td style="width: 390px; text-align: left;">'.$this->datos[0]['plazo'].' DIAS</td>
                                    </tr>
                                    <tr>
                                    <td style="width: 280px; text-align: right; font-weight: bold;">Reglamento Específico utilizado y documento de aprobación:</td>
                                    <td style="width: 390px; text-align: left;">REGLAMENTO ESPECIFICO DEL SISTEMA DE ADMINISTRACIÓN DE</td>
                                    </tr>
                                  </tbody>
                              </table>';
        $this->writeHTML($tablaDatosBasicos);
        $this->Ln(1);
        $this->SetFont('', 'B');
        $this->MultiCell(0, 0, "II. ACTIVIDADES PREVIAS", 0, 'L', 0,1, '', '');
        $this->SetFont('', '');
        $this->SetFontSize(6.5);
        $tablaPrevias = '<table border="1" style="width: 1200px; table-layout: fixed;">
                            <tbody>
                              <tr>
                                <td style="background-color:#A4A4A4; width: 25px; text-align: center; font-weight: bold;" rowspan="2">N°</td>
                                <td style="background-color:#A4A4A4; width: 160px; text-align: center; font-weight: bold;" rowspan="2">DESCRIPCIÓN</td>
                                <td style="background-color:#A4A4A4; width: 25px; text-align: center; font-weight: bold;" rowspan="2">SI</td>
                                <td style="background-color:#A4A4A4; width: 25px; text-align: center; font-weight: bold;" rowspan="2">NO</td>
                                <td style="background-color:#A4A4A4; width: 145px; text-align: center; font-weight: bold;" colspan="2">DOCUMENTO</td>
                                <td style="background-color:#A4A4A4; width: 150px; text-align: center; font-weight: bold;" rowspan="2">JUSTIFICACIÓN <br/>NORMATIVA</td>
                                <td style="background-color:#A4A4A4; width: 141px; text-align: center; font-weight: bold;" rowspan="2">ACLARACIONES</td>
                              </tr>
                            <tr>
                                <td style="background-color:#A4A4A4; width: 90px; text-align: center; font-weight: bold;">N°</td>
                                <td style="background-color:#A4A4A4; width: 55px; text-align: center; font-weight: bold;">FECHA</td>
                            </tr>
                            <tr>
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">1.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">La contratación se encuentra incluida en el Plan Operativo Anual (POA).</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                            </tr>
                            <tr>
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">2.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">La Contratación de Bienes y Servicios fue solicitada en la fecha programada e inscrita en el Programa Anual de Contrataciones.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                            </tr>
                            <tr>
                              <td style="width: 25px; text-align: center;">2.1</td>
                              <td style="width: 210px; text-align: left;" colspan="3">Programa Anual de Contrataciones (PAC).</td>
                              <td style="width: 90px; text-align: left;">'.$this->datos[0]['nro_pac'].'</td>
                              <td style="width: 50px; text-align: left;">'.$this->datos[0]['fecha_pac'].'</td>
                              <td style="width: 150px; text-align: left;">'.$justificacion2_1.'</td>
                              <td style="width: 146px; text-align: left;"></td>
                            </tr>
                            <tr>
                              <td style="width: 25px; text-align: center;">2.2</td>
                              <td style="width: 210px; text-align: left;" colspan="3">Justificación para la Contratación.</td>
                              <td style="width: 90px; text-align: left;">'.$this->datos[0]['nro_cite'].'</td>
                              <td style="width: 50px; text-align: left;">'.$this->datos[0]['fecha_contratacion'].'</td>
                              <td style="width: 150px; text-align: left;">'.$justificacion.'</td>
                              <td style="width: 146px; text-align: left;"></td>
                            </tr>
                            <tr>
                              <td style="width: 25px; text-align: center;">2.3</td>
                              <td style="width: 210px; text-align: left;" colspan="3">Solicitud para la contratación.</td>
                              <td style="width: 90px; text-align: left;">'.$this->datos[0]['nro_tramite'].'</td>
                              <td style="width: 50px; text-align: left;">'.$this->datos[0]['fecha_solicitud'].'</td>
                              <td style="width: 150px; text-align: left;">'.$justificacion.'</td>
                              <td style="width: 146px; text-align: left;"></td>
                            </tr>
                            <tr>
                              <td style="background-color:#DCDCDC; width: 25px; text-align: center;">3.</td>
                              <td style="background-color:#DCDCDC; width: 160px; text-align: left;">Se estimó el precio referencial para la contratación.</td>
                              <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                              <td style="width: 25px; text-align: left;"></td>
                              <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                            </tr>
                            <tr>
                              <td style="width: 25px; text-align: center;">3.1</td>
                              <td style="width: 210px; text-align: left;" colspan="3">Respaldo de cálculo del Precio Referencial.</td>
                              <td style="width: 90px; text-align: left;">'.$this->datos[0]['nro_tramite'].'</td>
                              <td style="width: 50px; text-align: left;">'.$this->datos[0]['fecha_precio_referencial'].'</td>
                              <td style="width: 150px; text-align: left;">'.$justificacion.'</td>
                              <td style="width: 146px;; text-align: left;"></td>
                            </tr>
                            <tr>
                              <td style="background-color:#DCDCDC; width: 25px; text-align: center;">4.</td>
                              <td style="background-color:#DCDCDC; width: 160px; text-align: left;">Se elaboraron las Especificaciones Técnicas / Términos de Referencia, definiendo el método de selección de adjudicación, e incluyendo condiciones de calidad del bien o servicio, lugar, plazo de entrega, forma de pago, formalización y multas, si corresponde.</td>
                              <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                              <td style="width: 25px; text-align: left;"></td>
                              <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                            </tr>
                            <tr>
                              <td style="width: 25px; text-align: center;">4.1</td>
                              <td style="width: 210px; text-align: left;" colspan="3">Especificaciones Técnicas / Términos de Referencia.</td>
                              <td style="width: 90px; text-align: left;">'.$this->datos[0]['nro_tramite'].'</td>
                              <td style="width: 50px; text-align: left;">'.$this->datos[0]['fecha_esp_tecnica'].'</td>
                              <td style="width: 150px; text-align: left;">'.$justificacion.'</td>
                              <td style="width: 146px; text-align: left;"></td>
                            </tr>
                            <tr >
                              <td style="background-color:#DCDCDC; width: 25px; text-align: center;">5.</td>
                              <td style="background-color:#DCDCDC; width: 160px; text-align: left;">Se verificó la existencia de saldo presupuestario para la contratación de acuerdo al POA.</td>
                              <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                              <td style="width: 25px; text-align: left;"></td>
                              <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                            </tr>
                            <tr >
                              <td style="width: 25px; text-align: center;">5.1</td>
                              <td style="width: 210px; text-align: left;" colspan="3">Certificación Presupuestaria.</td>
                              <td style="width: 90px; text-align: left;">'.$this->datos[0]['nro_tramite'].'</td>
                              <td style="width: 50px; text-align: left;">'.$this->datos[0]['fecha_certificacion_pre'].'</td>
                              <td style="width: 150px; text-align: left;">'.$justificacion5_1.'</td>
                              <td style="width: 146px; text-align: left;"></td>
                            </tr>
                            </tbody>
                        </table>';
          $this->Ln(3);
          $this->writeHTML($tablaPrevias);
          $this->Ln(1);
          $this->SetFontSize(10);
          $this->SetFont('', 'B');
          $this->MultiCell(0, 0, "III. PROCESO DE CONTRATACIÓN", 0, 'L', 0,1, '', '');
          $this->SetFont('', '');
          $this->SetFontSize(6);
          $tablaContratacion = '<table border="1" style="width: 1200px; table-layout: fixed;">
                              <tbody>
                              <tr>
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">6.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">La invitación a los proponentes se realizó considerando el rubro y capacidad de cumplimiento de los TDR´s.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>
                              <tr>
                                <td style="width: 25px; text-align: center;">6.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Notas de invitación a proponentes.</td>
                                <td style="width: 90px; text-align: left;">'.$this->datos[0]['nro_tramite'].'</td>
                                <td style="width: 50px; text-align: left;">'.$this->datos[0]['fecha_correo'].'</td>
                                <td style="width: 150px; text-align: left;">'.$justificacion6_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion6_1.'</td>
                              </tr>
                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">7.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">El proponente adjudicado, presentó propuesta o cotización en cumplimiento de las Especificaciones Técnicas / Términos de Referencia.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>
                              </tbody>
                          </table>';

          $tablaSegundaHoja = '<table border="1" style="width: 1200px; table-layout: fixed;">
                              <tbody>
                                <tr>
                                  <td style="background-color:#A4A4A4; width: 25px; text-align: center; font-weight: bold;" rowspan="2">N°</td>
                                  <td style="background-color:#A4A4A4; width: 160px; text-align: center; font-weight: bold;" rowspan="2">DESCRIPCION</td>
                                  <td style="background-color:#A4A4A4; width: 25px; text-align: center; font-weight: bold;" rowspan="2">SI</td>
                                  <td style="background-color:#A4A4A4; width: 25px; text-align: center; font-weight: bold;" rowspan="2">NO</td>
                                  <td style="background-color:#A4A4A4; width: 140px; text-align: center; font-weight: bold;" colspan="2">DOCUMENTO</td>
                                  <td style="background-color:#A4A4A4; width: 150px; text-align: center; font-weight: bold;" rowspan="2">JUSTIFICACIÓN <br/>NORMATIVA</td>
                                  <td style="background-color:#A4A4A4; width: 146px; text-align: center; font-weight: bold;" rowspan="2">ACLARACIONES</td>
                                </tr>
                              <tr>
                                  <td style="background-color:#A4A4A4; width: 90px; text-align: center; font-weight: bold;">N°</td>
                                  <td style="background-color:#A4A4A4; width: 50px; text-align: center; font-weight: bold;">FECHA</td>
                              </tr>

                              <tr >
                                <td style="width: 25px; text-align: center;">7.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Propuesta / Cotización del proponente adjudicado</td>
                                <td style="width: 90px; text-align: left;">'.$detalle_proveedores.'</td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificacion7_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion7_1.'</td>
                              </tr>
                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">8.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">El proponente adjudicado se encuentra registrado en el Registro Único de Proveedores del Estado (RUPE), y no tiene impedimento para participar en procesos de contratación.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>
                              <tr >
                                <td style="width: 25px; text-align: center;">8.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Certificado RUPE.</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificacion8_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion8_1__12_1.'</td>
                              </tr>
                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">9.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">El proponente se encuentra registrado como deudor en el Sistema Integral de Pensiones y el Sistema de Contribuciones al Seguro Social Obligatorio de largo plazo.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>
                              <tr >
                                <td style="width: 25px; text-align: center;">9.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Certificado de no adeudo al Sistema Integral de Pensiones y S.S.O. a largo plazo.</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificacion8_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion8_1__12_1.'</td>
                              </tr>
                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">10.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">La Empresa proponente tiene cuentas pendientes con el Estado.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>
                              <tr >
                                <td style="width: 25px; text-align: center;">10.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Certificado de Solvencia Fiscal emitida por la Contraloría General del Estado (CGE).</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificacion8_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion8_1__12_1.'</td>
                              </tr>
                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">11.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">El proponente se encuentra registrado en el Padrón Nacional de Contribuyentes.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>
                              <tr >
                                <td style="width: 25px; text-align: center;">11.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Número de Identificación Tributaria (NIT).</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificacion8_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion8_1__12_1.'</td>
                              </tr>
                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">12.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">La Empresa se encuentra inscrita en el Registro de Comercio.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>
                              <tr >
                                <td style="width: 25px; text-align: center;">12.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Registro en FUNDEMPRESA.</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificacion8_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion8_1__12_1.'</td>
                              </tr>

                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">13.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">El contrato incluye en su contenido lo establecido en el Artículo 87 del Decreto Supremo 181.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>
                              <tr >
                                <td style="width: 25px; text-align: center;">13.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Contrato.</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificacion8_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion13_1__15_1.'</td>
                              </tr>
                              <tr >
                                <td style="width: 25px; text-align: center;">13.2</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Información citada en cláusula <span style="font-style: italic;">"Documentos Integrantes"</span>, que nofigura en el presente formulario</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;"></td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion13_1__15_1.'</td>
                              </tr>

                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">14.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">La contratación del bien/prestación de servicio, con plazo de entrega no mayor a 15 días calendario o el establecido en Reglamento Específico, que haya sido formalizada mediante Orden de Compra o Servicio.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>

                              <tr >
                                <td style="width: 25px; text-align: center;">14.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Orden de Compra / Servicio.</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;"></td>
                                <td style="width: 146px; text-align: left;"></td>
                              </tr>

                              <tr >
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">15.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">Las garantías otorgadas por el proponente son de carácter de renovable, irrevocable y de ejecución inmediata.</td>
                                <td style="width: 25px; text-align: center;"></td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>

                              <tr>
                                <td style="width: 25px; text-align: center;">15.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Póliza(s) / Boleta(s) de Garantía.</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificacion8_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion13_1__15_1.'</td>
                              </tr>

                              <tr>
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">16.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">La contratación se encuentra registrada en el Sistema de Contrataciones Estatales (SICOES), en el plazo previsto. Se deben considerar también, contrataciones mayores a Bs.20.000 que no requieran convocatoria o se efectúen por invitación directa.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>

                              <tr>
                                <td style="width: 25px; text-align: center;">16.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Registro en SICOES (Formulario N° 400).</td>
                                <td style="width: 90px; text-align: left;"></td>
                                <td style="width: 50px; text-align: left;"></td>
                                <td style="width: 150px; text-align: left;">'.$justificcacion16_1.'</td>
                                <td style="width: 146px; text-align: left;"></td>
                              </tr>

                              <tr>
                                <td style="background-color:#DCDCDC; width: 25px; text-align: center;">17.</td>
                                <td style="background-color:#DCDCDC; width: 160px; text-align: left;">Otros documentos adicionales que se consideren pertinentes.</td>
                                <td style="width: 25px; text-align: center;"><br/><br/><img src="../../../lib/imagenes/icono_awesome/awe_ok.png" width="10" height="10"></td>
                                <td style="width: 25px; text-align: left;"></td>
                                <td style="background-color:#DCDCDC; width: 436px; text-align: left;" colspan="4"></td>
                              </tr>

                              <tr>
                                <td style="width: 25px; text-align: center;">17.1</td>
                                <td style="width: 210px; text-align: left;" colspan="3">Detalle de los documentos adicionales.</td>
                                <td style="width: 90px; text-align: left;">'.$this->datos[0]['nro_tramite'].'</td>
                                <td style="width: 50px; text-align: left;">'.$this->datos[0]['fecha_contratacion'].'</td>
                                <td style="width: 150px; text-align: left;">'.$justificacion17_1.'</td>
                                <td style="width: 146px; text-align: left;">'.$aclaracion17_1.'</td>
                              </tr>
                              </tbody>
                          </table>';
            $this->Ln(3);
            $this->writeHTML($tablaContratacion);

            $this->AddPage();
            $this->Ln(8);

            $this->writeHTML($tablaSegundaHoja);

            $this->SetFontSize(9);
            $this->SetFont('', 'B');
            $this->MultiCell(15, 0, "NOTA:", 0, 'L', 0,0, '', '');
            $this->SetFont('', '');
            $this->MultiCell(182, 0, "El contenido de la información a registrar en el presente formulario, debe considerar la secuencia y cronología de las actividades realizadas.", 0, 'L', 0,0, '', '');



            $this->AddPage();
            $this->Ln(8);

            $firmas = '<table style="width: 1200px; table-layout: fixed; font-style: italic;" nobr="true">
                          <tbody>
                            <tr>
                              <td style="width: 700px;" colspan="3">Declaramos que la información registrada en el presente documento constituye Declaracion Jurada, y fue determinada en cuanto a la normativa aplicable para la contratación, siendo la misma fidedigna y que la documentación descrita se encuentra en archivos de la Empresa Pública, encontrándose a disposición para fines de control externo posterior.</td>
                            </tr>
                            <tr>
                              <td style="width: 700px; text-align: center;" colspan="3"><br/><br/><br/><br/><br/><br/><center>Cochabamba,......de......20......</center></td>
                            </tr>
                            <tr>
                              <td style="width: 300px; text-align: center;"><br/><br/><br/><br/><br/><br/><br/><br/>Responsable del Proceso de Contratación <br/>EMPRESA PÚBLICA</td>
                              <td style="width: 100px;"></td>
                              <td style="width: 300px; text-align: center;"><br/><br/><br/><br/><br/><br/><br/><br/>Titular del Área Administrativa Financiera <br/>EMPRESA PÚBLICA</td>
                            </tr>
                          </tbody>
                        </table>';

            $this->writeHTML($firmas);

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

        $this->ReporteOrdenRaparacion();
    }
}
?>
