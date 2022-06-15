<?php
/**
 *@package pXP
 *@file gen-ACTSolicitud.php
 *@author  (admin)
 *@date 23-12-2016 13:12:58
 *@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */
require_once(dirname(__FILE__).'/../reportes/RRequemientoMaterielesIng.php');
require_once(dirname(__FILE__).'/../reportes/RRequemientoMaterielesMan.php');
require_once(dirname(__FILE__).'/../reportes/RRequemientoMaterielesAlm.php');
require_once(dirname(__FILE__).'/../reportes/RControlAlmacenXLS.php');
require_once(dirname(__FILE__).'/../reportes/RSalidaAlmacen.php');
require_once(dirname(__FILE__).'/../reportes/RRequerimientoMaterialesPDF.php');
require_once(dirname(__FILE__).'/../reportes/RComiteEvaluacion.php');
require_once(dirname(__FILE__).'/../reportes/RDocContratacionExtPDF.php');
require_once(dirname(__FILE__).'/../reportes/RComparacionBySPDF.php');
require_once(dirname(__FILE__).'/../reportes/RRequerimientoMaterialesCeac.php');

require_once(dirname(__FILE__).'/../reportes/RConstanciaEnvioInvitacion.php');
require_once(dirname(__FILE__).'/../reportes/RConstanciaEnvioInvitacionBoaRep.php');
require_once(dirname(__FILE__).'/../reportes/RSolicitudCompraBoARep.php');


require_once(dirname(__FILE__).'/../reportes/RCertificacionPresupuestaria.php');

/*Aumentando Reportes*/
require_once(dirname(__FILE__).'/../reportes/RComiteEvaluacionGR.php');
require_once(dirname(__FILE__).'/../reportes/ROrdenDeReparacionExterior.php');
require_once(dirname(__FILE__).'/../reportes/RInformJustificacionRep.php');
require_once(dirname(__FILE__).'/../reportes/RTechnicalSpecifications.php');
require_once(dirname(__FILE__) . '/../../pxp/pxpReport/DataSource.php');
require_once(dirname(__FILE__).'/../reportes/RControlRpc.php');
require_once(dirname(__FILE__).'/../reportes/RNotaDeAdjudicacionRep.php');
require_once(dirname(__FILE__).'/../reportes/RConformidadActaFinal.php');

require_once(dirname(__FILE__).'/../reportes/RFORMULARIO3008.php');




class ACTSolicitud extends ACTbase{

    function listarSolicitud()
    {
        $this->objParam->defecto('ordenacion', 'id_solicitud');
        $this->objParam->defecto('dir_ordenacion', 'asc');
        if($this->objParam->getParametro('id_gestion') != '' ) {
            $this->objParam->addFiltro("sol.id_gestion = " . $this->objParam->getParametro('id_gestion'));
        }

        /*Aumentando para la interfaz de presupuesto (Ismael Valdivia 16/04/2020)*/
        if ($this->objParam->getParametro('tipo_interfaz') == 'Presupuesto_Mantenimiento' ) {
            if ($this->objParam->getParametro('pes_estado') == 'no_revisado') {
                $this->objParam->addFiltro("sol.revisado_presupuesto = (''no'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'revisado') {
                $this->objParam->addFiltro("sol.revisado_presupuesto = (''si'')");
            }

              $this->objParam->addFiltro("sol.estado  not in (''borrador'',''revision'',''revision_tecnico_abastecimientos'',''cotizacion'',''cotizacion_solicitada'',''cotizacion_sin_respuesta'',''finalizado'',''anulado'')");

        }
        /*************************************************************************/

        if ($this->objParam->getParametro('tipo_interfaz') == 'PedidosAdquisiciones' ) {
          $this->objParam->addFiltro("sol.estado  in (''compra'')");
        }



        if ($this->objParam->getParametro('tipo_interfaz') == 'RegistroSolicitud' ) {

            if ($this->objParam->getParametro('pes_estado') == 'borrador_reg') {
                $this->objParam->addFiltro("sol.estado  in (''borrador'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'vobo_area_reg') {
                $this->objParam->addFiltro("sol.estado_firma  in (''vobo_area'',''vobo_aeronavegabilidad'',''vobo_dpto_abastecimientos'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'revision_reg') {
                $this->objParam->addFiltro("sol.estado in (''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''cotizacion_solicitada'',''cotizacion_sin_respuesta'',''revision'') ");
            }
            if ($this->objParam->getParametro('pes_estado') == 'finalizado_reg') {
                $this->objParam->addFiltro("sol.estado  in (''finalizado'',''anulado'')");
            }
        }
        if ($this->objParam->getParametro('tipo_interfaz') == 'ConsultaRequerimientos' ) {

            if ($this->objParam->getParametro('pes_estado') == 'consulta_op') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''borrador'',''revision_tecnico_abastecimientos'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'',''cotizacion_sin_respuesta'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'consulta_mal') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''borrador'',''revision_tecnico_abastecimientos'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'',''cotizacion_sin_respuesta'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'consulta_ab') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''borrador'',''revision_tecnico_abastecimientos'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'',''cotizacion_sin_respuesta'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'consulta_ceac') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''borrador'',''revision_tecnico_abastecimientos'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'',''cotizacion_sin_respuesta'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'',''departamento_ceac'',''comite_dpto_abastecimientos'')");
            }
            /*Aumentando para listar las solicitudes de reparaciones*/
            if ($this->objParam->getParametro('pes_estado') == 'consulta_repu') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''borrador'',''revision_tecnico_abastecimientos'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'',''cotizacion_sin_respuesta'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'',''departamento_ceac'',''comite_dpto_abastecimientos'')");
            }
            /********************************************************/
        }

        if ($this->objParam->getParametro('tipo_interfaz') == 'VistoBueno' ) {
              /*Aumentando esta parte del codigo para filtrar en la interfaz los vb_dpto_abastecimientos y vb_rpcd Ismael Valdivia (03/02/2020)*/
           if($this->objParam->getParametro('pes_estado') == 'pedido_revision'){
             $this->objParam->addFiltro("(sol.estado  in (''revision_tecnico_abastecimientos''))");
           }

           if($this->objParam->getParametro('pes_estado') == 'pedido_iniciado'){
             $this->objParam->addFiltro("(sol.estado in (''cotizacion'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'') and trim(sol.nro_po) = '''') or (sol.estado in (''cotizacion'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'') and (sol.origen_pedido = ''Reparación de Repuestos'' and trim(sol.nro_po) != '''')) ");
           }

           if($this->objParam->getParametro('pes_estado') == 'pedido_tiene_po'){
             $this->objParam->addFiltro("trim(sol.nro_po) != ''''");
           }

           if($this->objParam->getParametro('pes_estado') == 'pedido_compra'){
             $this->objParam->addFiltro("sol.estado in (''compra'') ");

           }
        }
        if ($this->objParam->getParametro('tipo_interfaz') == 'SolicitudvoboComite') {
            /*Aumentando la condicion para que muestre directamente en la interfaz voboComite (Ismael Valdivia 19/02/2020)*/
            $this->objParam->addFiltro("sol.estado in (''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'')");
            //$this->objParam->addFiltro("sol.estado  in (''vb_rpcd'',''vb_dpto_administrativo'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'',''departamento_ceac'')");
        }

        /*Aumentando la condicion para la nueva interfaz*/
        if ($this->objParam->getParametro('tipo_interfaz') == 'SolicitudvoboComiteAeronavegabilidad') {
            /*Aumentando la condicion para que muestre directamente en la interfaz voboComite (Ismael Valdivia 19/02/2020)*/
            $this->objParam->addFiltro("(sol.estado_firma in (''comite_aeronavegabilidad''))");
            //$this->objParam->addFiltro("sol.estado  in (''vb_rpcd'',''vb_dpto_administrativo'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'',''departamento_ceac'')");
        }
        /************************************************/

        if ($this->objParam->getParametro('tipo_interfaz') == 'SolicitudFec' ) {
            if ($this->objParam->getParametro('historico') == 'no' or  $this->objParam->getParametro('historico') == null) {

                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_ing_n') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado in (''despachado'',''arribo'',''desaduanizado'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_man_n') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''despachado'',''arribo'',''desaduanizado'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_alm_n' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''despachado'',''arribo'',''desaduanizado'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_alm_ceac' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''despachado'',''arribo'',''desaduanizado'')");
                }
                /*Aumentando para repuestos (Ismael Valdivia 16/03/2020)*/
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_rep_n' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''despachado'',''arribo'',''desaduanizado'')");
                }
                /********************************************************/

            }else{
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_ing_n') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'',''arribo'',''desaduanizado'',''almacen'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_man_n') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'',''arribo'',''desaduanizado'',''almacen'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_alm_n' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'',''arribo'',''desaduanizado'',''almacen'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_alm_ceac' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'',''arribo'',''desaduanizado'',''almacen'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'ab_origen_rep_n' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'',''arribo'',''desaduanizado'',''almacen'')");
                }
            }
        }
        //operaciones
        //var_dump($_SESSION["ss_id_funcionario"]);exit;
        if ($this->objParam->getParametro('tipo_interfaz') == 'PedidoOperacion' ) {
            if ($this->objParam->getParametro('historico') == 'no' or  $this->objParam->getParametro('historico') == null) {
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_pendiente') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''cotizacion'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_solicitada') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''cotizacion_solicitada'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_sin_resp') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''cotizacion_sin_respuesta'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_compra') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_comite') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_concluido') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''finalizado'')");
                }
            }else{
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_pendiente') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_solicitada') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_op_compra') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                }
            }
        }
        ///matenimiento
        if ($this->objParam->getParametro('tipo_interfaz') == 'PedidoMantenimiento' ) {
            if ($this->objParam->getParametro('historico') == 'no' or  $this->objParam->getParametro('historico') == null) {

                if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_pendiente') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''cotizacion'')");

                }if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_solicitada') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''cotizacion_solicitada'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_sin_resp') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''cotizacion_sin_respuesta'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_comite' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and (sol.estado  in (''comite_unidad_abastecimientos'',''comite_dpto_abastecimientos'') or sol.estado_firma in (''comite_aeronavegabilidad''))");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_compra' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''compra'')");
                }if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_concluido') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''finalizado'')");
                }
            }else{
                if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_pendiente') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                }
                 if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_solicitada') {
                   $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                 }
                 if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_compra') {
                   $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");

                 }
            }
        }
        /*Aumentando para vista de repuestos (Ismael Valdivia 16/03/2020)*/
        if ($this->objParam->getParametro('tipo_interfaz') == 'PedidoRepuesto' ) {
            if ($this->objParam->getParametro('historico') == 'no' or  $this->objParam->getParametro('historico') == null) {

                if ($this->objParam->getParametro('pes_estado') == 'pedido_re_pendiente') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''cotizacion'')");

                }if ($this->objParam->getParametro('pes_estado') == 'pedido_re_solicitada') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''cotizacion_solicitada'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_re_sin_resp') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''cotizacion_sin_respuesta'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_re_comite' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and (sol.estado  in (''comite_unidad_abastecimientos'',''comite_dpto_abastecimientos'') or sol.estado_firma in (''comite_aeronavegabilidad''))");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_re_compra' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''compra'')");
                }if ($this->objParam->getParametro('pes_estado') == 'pedido_re_concluido') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''finalizado'')");
                }
            }else{
                if ($this->objParam->getParametro('pes_estado') == 'pedido_re_pendiente') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                }
                 if ($this->objParam->getParametro('pes_estado') == 'pedido_re_solicitada') {
                   $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");

                 }
                 if ($this->objParam->getParametro('pes_estado') == 'pedido_re_compra') {
                   $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");

                 }
            }
        }
        /*****************************************************************/
        //almacenes
        if ($this->objParam->getParametro('tipo_interfaz') == 'PerdidoAlmacen' ) {
            if ($this->objParam->getParametro('historico') == 'no' or  $this->objParam->getParametro('historico') == null) {
                if ($this->objParam->getParametro('pes_estado') == 'pedido_al_pendiente' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''cotizacion'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_al_solicitada' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''cotizacion_solicitada'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_al_sin_resp') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''cotizacion_sin_respuesta'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_al_comite') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_al_compra') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''compra'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_al_concluido') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''finalizado'')");
                }

            }else{
                if ($this->objParam->getParametro('pes_estado') == 'pedido_al_pendiente') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                 }
                 if ($this->objParam->getParametro('pes_estado') == 'pedido_al_solicitada') {
                     $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                 }
                 if ($this->objParam->getParametro('pes_estado') == 'pedido_al_compra') {
                     $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                 }
            }
        }
        //dgac
        if ($this->objParam->getParametro('tipo_interfaz') == 'PedidoDgac' ) {
            if ($this->objParam->getParametro('historico') == 'no' or  $this->objParam->getParametro('historico') == null) {
                if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_pendiente' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''cotizacion'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_solicitada' ) {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''cotizacion_solicitada'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_sin_resp') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''cotizacion_sin_respuesta'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_comite') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''comite_unidad_abastecimientos'',''departamento_ceac'',''comite_dpto_abastecimientos'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_compra') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''compra'')");
                }
                if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_concluido') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''finalizado'')");
                }

            }else{
                if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_pendiente') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                 }
                 if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_solicitada') {
                     $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                 }
                 if ($this->objParam->getParametro('pes_estado') == 'pedido_dgac_compra') {
                    $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and sol.estado  in (''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''comite_unidad_abastecimientos'',''comite_aeronavegabilidad'',''comite_dpto_abastecimientos'')");
                 }
            }
        }

        if ($this->objParam->getParametro('tipo_interfaz') == 'Almacen' ) {
            if ($this->objParam->getParametro('pes_estado') == 'almacen') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and  sol.estado  in (''almacen'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'origen_al_man') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and  sol.estado in (''almacen'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'origen_al_ab') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and  sol.estado  in (''almacen'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'origen_al_ceac') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and  sol.estado  in (''almacen'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'origen_al_repu') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and  sol.estado  in (''almacen'')");
            }
        }
        if ($this->objParam->getParametro('tipo_interfaz') == 'SolArchivado' ) {
            if ($this->objParam->getParametro('pes_estado') == 'archivado_ing') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and  sol.estado  in (''finalizado'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'archivado_man') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and  sol.estado in (''finalizado'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'archivado_alm') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and  sol.estado  in (''finalizado'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'archivado_ceac') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and  sol.estado  in (''finalizado'')");
            }
        }
        if ($this->objParam->getParametro('tipo_interfaz') == 'ProcesoCompra' ) {
            if ($this->objParam->getParametro('pes_estado') == 'origen_ing') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and  sol.estado  in (''revision'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'origen_man') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and  sol.estado in (''revision'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'origen_alm') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and  sol.estado  in (''revision'')");
            }
            if ($this->objParam->getParametro('pes_estado') == 'origen_dgac') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Centro de Entrenamiento Aeronautico Civil'') and  sol.estado  in (''revision'')");
            }
            /*Aumentando para repuestos Ismael Valdivia (16/03/2020)*/
            if ($this->objParam->getParametro('pes_estado') == 'origen_repu') {
                $this->objParam->addFiltro("sol.origen_pedido  in (''Reparación de Repuestos'') and  sol.estado  in (''revision'')");
            }
            /********************************************************/
        }
        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODSolicitud','listarSolicitud');
        } else{
            $this->objFunc=$this->create('MODSolicitud');

            $this->res=$this->objFunc->listarSolicitud($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');
        if($this->objParam->insertar('id_solicitud')){
            $this->res=$this->objFunc->insertarSolicitud($this->objParam);
        } else{
            $this->res=$this->objFunc->modificarSolicitud($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function insertarSolicitudCompleta(){
        $this->objFunc=$this->create('MODSolicitud');
        if($this->objParam->insertar('id_solicitud')){
            $this->res=$this->objFunc->insertarSolicitudCompleta($this->objParam);
            //var_dump($this->res); exit;
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->eliminarSolicitud($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function actualizarCpAlkym(){
      $concatenar_variable = array("matricula"=>"");
      $envio_dato = json_encode($concatenar_variable);
      if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
        $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/MostrarAvion';
      } else {
        $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/MostrarAvion';
      }
      $session = curl_init($request);
      curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
      curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($session, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'Content-Length: ' . strlen($envio_dato))
      );
      $result = curl_exec($session);
      curl_close($session);
      $respuesta = json_decode($result);
      $respuesta_deco = json_decode($respuesta->MostrarAvionResult);
      $respuesta_final = ($respuesta_deco->objeto);

      /*Aqui recuperamos la informacion para enviar*/
      $json_obtenido = json_encode($respuesta_final);
      $cantidad_json = count($respuesta_final);
      /*********************************************/


      if ($respuesta_final == '') {
        throw new Exception('No se puede conectar con el servicio de Mantenimiento. Porfavor consulte con el Área de Sistemas');
      } else {

        //$this->objParam->addParametro('json_obtenido',$_SESSION["_LOGIN"]);
        $this->objParam->addParametro('json_obtenido',$json_obtenido);
        $this->objParam->addParametro('cantidad_json',$cantidad_json);
        $this->objFunc=$this->create('MODSolicitud');
        $cbteHeader=$this->objFunc->actualizarCpAlkym($this->objParam);
        if ($cbteHeader->getTipo() == 'EXITO') {
            return $cbteHeader;
        } else {
            $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
            exit;
        }

      }
    }

    function listarMatricula(){


      $actualizacion_Cp_alkym = $this->actualizarCpAlkym();

    //  var_dump("aqui la respuesta de la tabla",$actualizacion_Cp_alkym);exit;

        $this->objParam->defecto('ordenacion','id_orden_trabajo');
        $this->objParam->defecto('dir_ordenacion','asc');

        if($this->objParam->getParametro('id_orden_trabajo') != '') {
            $this->objParam->addFiltro(" ord.id_orden_trabajo = " . $this->objParam->getParametro('id_orden_trabajo'));
        }

        if($this->objParam->getParametro('soloAlkym') != '') {
          if($this->objParam->getParametro('flota') == 'si') {
              $this->objParam->addFiltro("(ord.id_avion_alkym is not null or ord.codigo = ''FLOTA BOA'')");
          } else {
            $this->objParam->addFiltro("(ord.id_avion_alkym is not null)");
          }
        }



        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarMatricula($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarFuncionarioRegistro(){
        $this->objParam->defecto('ordenacion','id_funcionario');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_funcionario') != '') {
            $this->objParam->addFiltro(" f.id_funcionario = " . $this->objParam->getParametro('id_orden_trabajo'));
        }

        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarFuncionarios($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function getDatos(){
        $this->objParam->defecto('ordenacion','id_funcionario');
        $this->objParam->defecto('dir_ordenacion','asc');


        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listaGetDatos($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function getDatosTecnico(){
        $this->objParam->defecto('ordenacion','id_funcionario');
        $this->objParam->defecto('dir_ordenacion','asc');


        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listaGetDatosTecnico($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function getCentroCostoDefecto(){
        $this->objParam->defecto('ordenacion','id_funcionario');
        $this->objParam->defecto('dir_ordenacion','asc');


        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->getCentroCostoDefecto($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }



    function siguienteEstadoSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');

        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);

        $this->res=$this->objFunc->siguienteEstadoSolicitud($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function siguienteEstadoSolicitudBorrador(){
        $this->objFunc=$this->create('MODSolicitud');

        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);

        $this->res=$this->objFunc->siguienteEstadoSolicitudBorrador($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }



    function anteriorEstadoSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
        $this->res=$this->objFunc->anteriorEstadoSolicitud($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function conexionSistemaControlMantenimiento(){
      $this->objParam->addParametro('variable_global','servicio_control_mantenimiento');
      $this->objFunc = $this->create('MODSolicitud');
      $cbteHeader = $this->objFunc->conexionAlkym($this->objParam);
      if ($cbteHeader->getTipo() == 'EXITO') {
          return $cbteHeader;
      } else {
          $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
          exit;
      }
    }

    /*Aumentando para enviar por el servicio a devolucion y aprobacion (Ismael Valdivia 02/04/2020)*/

    function siguienteEstadoSolicitudServicio(){
      $variable_global = $this->conexionSistemaControlMantenimiento();
      $conexionAlkym = $variable_global->getDatos();
      $respuesta = $conexionAlkym[0]["variable_obtenida"];

      if ($respuesta == 'si') {
        $nro_tramite = $this->objParam->getParametro('nro_tramite');
        $data = array("usuario"=>$_SESSION["_LOGIN"], "estado"=>"aprobado", "nroTramite"=>$nro_tramite,"observacion"=>"El tramite ".$nro_tramite." ha sido aprobado para compra.");
        $datosUpdate = json_encode($data);
        $concatenar_variable = array("datosUpdate"=>$datosUpdate);
        $envio_dato = json_encode($concatenar_variable);

        if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
          $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/UpdateEstadoCompras';
        } else {
          $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/UpdateEstadoCompras';
        }

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($envio_dato))
        );

        $result = curl_exec($session);
        curl_close($session);

        $respuesta = json_decode($result);
        $respuesta_final = json_decode($respuesta->UpdateEstadoComprasResult);

        if (($respuesta_final->codigo != 0) && ($respuesta_final->codigo != 1)) {
          throw new Exception('No se puede conectar con el servicio de Mantenimiento. Porfavor consulte con el Área de Sistemas');
        }
        /*Cambiar estado para servicio ERIO para pruebas 1*/
        if ($respuesta_final->codigo == 0) {
          throw new Exception($respuesta_final->mensaje.' en el sistema de Mantenimiento por lo tanto no se puede pasar al estado compra favor verifique');
        }
        else {
          $this->objFunc = $this->create('MODSolicitud');
          $this->objParam->addParametro('variable_global','servicio_control_mantenimiento');
          // $this->objFunc=$this->create('MODSolicitud');
          // $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);
          $this->res=$this->objFunc->conexionAlkym($this->objParam);
          $this->res->imprimirRespuesta($this->res->generarJson());
        }
      }
      else {
        $this->objFunc = $this->create('MODSolicitud');
        $this->objParam->addParametro('variable_global','servicio_control_mantenimiento');
        // $this->objFunc=$this->create('MODSolicitud');
        // $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);
        $this->res=$this->objFunc->conexionAlkym($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
      }

    }

    function obtenerDetalleSolicitudServicio(){
      $this->objFunc = $this->create('MODSolicitud');
      $cbteHeader = $this->objFunc->obtenerDetalleSolicitudServicio($this->objParam);
      if ($cbteHeader->getTipo() == 'EXITO') {
          return $cbteHeader;
      } else {
          $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
          exit;
      }
    }

    function obtenerDetalleSolicitudServicioHazmat(){
      $this->objFunc = $this->create('MODSolicitud');
      $cbteHeader = $this->objFunc->obtenerDetalleSolicitudServicioHazmat($this->objParam);
      if ($cbteHeader->getTipo() == 'EXITO') {
          return $cbteHeader;
      } else {
          $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
          exit;
      }
    }



    function obtenerDetalleCabecera(){
      $this->objFunc = $this->create('MODSolicitud');
      $cbteHeader = $this->objFunc->obtenerDetalleCabecera($this->objParam);
      if ($cbteHeader->getTipo() == 'EXITO') {
          return $cbteHeader;
      } else {
          $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
          exit;
      }
    }

    function conexionAlkym(){
      $this->objParam->addParametro('variable_global','servicio_alkym_orden_compra');
      $this->objFunc = $this->create('MODSolicitud');
      $cbteHeader = $this->objFunc->conexionAlkym($this->objParam);
       if ($cbteHeader->getTipo() == 'EXITO') {
          return $cbteHeader;
       } else {
          $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
          exit;
      }
    }


    function verificarEstado($id_estado){
      $cone = new conexion();
  		$link = $cone->conectarpdo();
  		$copiado = false;

  		$consulta ="select codigo
                  From wf.ttipo_estado
                  where id_tipo_estado = ".$id_estado." and estado_reg = 'activo'";

  		$res = $link->prepare($consulta);
  		$res->execute();
  		$result = $res->fetchAll(PDO::FETCH_ASSOC);
  		return $result[0]['codigo'];
    }

    function verificarEvaluacion($id_solicitud){
      $cone = new conexion();
  		$link = $cone->conectarpdo();
  		$copiado = false;

  		$consulta ="select
                        (CASE
                          WHEN tipo_evaluacion = 'Compra'  THEN
                            1
                          WHEN (tipo_evaluacion = 'Exchange' or tipo_evaluacion = 'Flat Exchange')  THEN
                            2
                          else
                            3
                        END) as tipo_evaluacion
                  From mat.tsolicitud
                  where id_solicitud = ".$id_solicitud;

  		$res = $link->prepare($consulta);
  		$res->execute();
  		$result = $res->fetchAll(PDO::FETCH_ASSOC);
  		return $result[0]['tipo_evaluacion'];
    }

    function insertarOrdenCompraAlkym(){
      /*Aqui recuperaremos la variable Global para que se decida si se ejecuta los serviciosAlkym o no (Ismael Valdivia 30/04/2020)*/
      $variable_global = $this->conexionAlkym();
      $conexionAlkym = $variable_global->getDatos();
      $respuesta = $conexionAlkym[0]["variable_obtenida"];

      $verificarEstado = $this->verificarEstado($this->objParam->getParametro('id_tipo_estado'));

      /*Aumentando para recuperar el tipo de evalucion Compra. Exchange, Reparaciones
      Ismael Valdivia (16/05/2022)*/
      $tipoEvaluacion = $this->verificarEvaluacion($this->objParam->getParametro('id_solicitud'));
      /*******************************************************************************/



      if ($respuesta == 'si' && $verificarEstado == 'despachado') {

        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);
        $this->objFunc=$this->create('MODSolicitud');
        $this->resControlPresupuestos=$this->objFunc->controlPresupuestos($this->objParam);

        if ($this->resControlPresupuestos->getTipo() != 'ERROR') {


          /*Armamos el Json del detalle para enviar al servicio (Ismael Valdivia 23/04/2020)*/
         $DetalleSolicitud = $this->obtenerDetalleSolicitudServicio();
         $datosDetalle = $DetalleSolicitud->getDatos();
         $totalarreglo = count($datosDetalle);
         $datos_arreglo = array();

         $arreglo_detalle = array();
         $item = 1;

         for ($i=0; $i<$totalarreglo; $i++) {

           /*Validaciones de Datos*/
           if ($datosDetalle[$i]["partnumber"] == '') {
             throw new Exception("El PartNumber del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
           }

           if ($datosDetalle[$i]["cantidad"] == '') {
             throw new Exception("La cantidad del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
           }

           if ($datosDetalle[$i]["precio_unitario"] == '') {
             throw new Exception("El precio unitario del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
           }

           if ($datosDetalle[$i]["moneda"] == '') {
             throw new Exception("La moneda del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
           }

           if ($datosDetalle[$i]["condicion"] == '') {
             throw new Exception("La Condicion Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
           }

           if ($datosDetalle[$i]["fechaentrega"] == '') {
             throw new Exception("La fecha de entrega del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
           }

           if ($datosDetalle[$i]["idplancuentacomp"] == '') {
             throw new Exception("El ID Plan de cuenta Compra del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
           }
           /**********************/

           $DetalleOrdenCompra = array(
                                        "NroItem"=>$item,
                                        "TipoDetalleOrden"=>"1",
                                        "PartNumber"=>$datosDetalle[$i]["partnumber"],
                                        "Cantidad"=>$datosDetalle[$i]["cantidad"],
                                        "PrecioUnitario"=>$datosDetalle[$i]["precio_unitario"],
                                        "Moneda" => $datosDetalle[$i]["moneda"],
                                        "Condicion"=>$datosDetalle[$i]["condicion"],
                                        "FechaEntrega"=>$datosDetalle[$i]["fechaentrega"],
                                        "IdPlanCuentaCompra"=>$datosDetalle[$i]["idplancuentacomp"],
                                        "Observacion"=>$datosDetalle[$i]["descripcion"]);

            $DetalleCompraJson = ($DetalleOrdenCompra);
            array_push($datos_arreglo,$DetalleCompraJson);
            $item++;
         }
         $datos_arreglo_json = ($datos_arreglo);
         $DetalleSolicitudHazmat = $this->obtenerDetalleSolicitudServicioHazmat();
         $datosDetalleHazmat = $DetalleSolicitudHazmat->getDatos();
         $totalarregloHazmat = count($datosDetalleHazmat);
         $datos_arregloHazmat = array();
         $item_Hazmat = 1;

         if ($totalarregloHazmat > 0){
           for ($i=0; $i<$totalarregloHazmat; $i++) {

             /*Validaciones de Datos*/
             if ($datosDetalleHazmat[$i]["partnumber"] == '') {
               throw new Exception("El PartNumber del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
             }

             if ($datosDetalleHazmat[$i]["cantidad"] == '') {
               throw new Exception("La cantidad del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
             }

             if ($datosDetalleHazmat[$i]["precio_unitario"] == '') {
               throw new Exception("El precio unitario del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
             }

             if ($datosDetalleHazmat[$i]["moneda"] == '') {
               throw new Exception("La moneda del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
             }

             if ($datosDetalleHazmat[$i]["condicion"] == '') {
               //throw new Exception("La Condicion Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
             }

             if ($datosDetalleHazmat[$i]["fechaentrega"] == '') {
               throw new Exception("La fecha de entrega del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
             }

             if ($datosDetalleHazmat[$i]["idplancuentacomp"] == '') {
               throw new Exception("El ID Plan de cuenta Compra del Item: ".$item." no puede ser vacio favor verificar el detalle de la cotización adjudicada");
             }
             /**********************/

             $DetalleHazmat = array(
                                          "NroItem"=>$item_Hazmat,
                                          "TipoDetalleOrden"=>"2",
                                          "PartNumber"=>$datosDetalleHazmat[$i]["partnumber"],
                                          "Cantidad"=>$datosDetalleHazmat[$i]["cantidad"],
                                          "PrecioUnitario"=>$datosDetalleHazmat[$i]["precio_unitario"],
                                          "Moneda" => $datosDetalleHazmat[$i]["moneda"],
                                          "Condicion"=>$datosDetalleHazmat[$i]["condicion"],
                                          "FechaEntrega"=>$datosDetalleHazmat[$i]["fechaentrega"],
                                          "IdPlanCuentaCompra"=>$datosDetalleHazmat[$i]["idplancuentacomp"],
                                          "Observacion"=>$datosDetalleHazmat[$i]["descripcion"]);

              $DetalleHazmatJson = ($DetalleHazmat);
              array_push($datos_arreglo_json,$DetalleHazmatJson);
              $item_Hazmat++;
           }
         }


      //  var_dump("aqui llega el arreglo completo",$datos_arreglo_json);exit;

         /*Aqui unimos las dos partes cabecera y detalle*/

         /*Armamos el Json de la cabezera para enviar al servicio (Ismael Valdivia 23/04/2020)*/
         $fecha = date('Y-m-d');
         $DetalleCabecera = $this->obtenerDetalleCabecera();
         $datosCabecera = $DetalleCabecera->getDatos();
         $totalarregloCabecera = count($datosCabecera);
         for ($i=0; $i<$totalarregloCabecera; $i++) {
           /*Validaciones*/
           if ($datosCabecera[$i]["fecha_po"] == '') {
             throw new Exception("La fecha del PO no puede ser vacia");
           }

           if ($datosCabecera[$i]["id_proveedor"] == '') {
             throw new Exception("No se encontró el ID proveedor Alkym");
           }

           if ($datosCabecera[$i]["id_criticidad"] == '') {
             throw new Exception("No se encontró el ID Criticidad");
           }

           if ($datosCabecera[$i]["id_condicion_entrega_alkym"] == '') {
             throw new Exception("No se encontró la Condicion Entrega Alkym en la cabecera de la cotizacion del proveedor adjudicado");
           }

           if ($datosCabecera[$i]["id_forma_pago_alkym"] == '') {
             throw new Exception("No se encontró la Forma de Pago en la cabecera de la cotizacion del proveedor adjudicado");
           }

           if ($datosCabecera[$i]["id_modo_envio_alkym"] == '') {
             throw new Exception("No se encontró el Modo de Envio en la cabecera de la cotizacion del proveedor adjudicado");
           }

           if ($datosCabecera[$i]["id_puntos_entrega_alkym"] == '') {
             throw new Exception("No se encontró el Punto de Entrega en la cabecera de la cotizacion del proveedor adjudicado");
           }

           if ($datosCabecera[$i]["id_tipo_transaccion_alkym"] == '') {
             throw new Exception("No se encontró el Tipo de Transacción en la cabecera de la cotizacion del proveedor adjudicado");
           }

           if ($datosCabecera[$i]["id_proveedor_contacto"] == '') {
             throw new Exception("No se encontró el Contacto del proveedor en la cabecera de la cotizacion del proveedor adjudicado");
           }

           if ($datosCabecera[$i]["id_orden_destino_alkym"] == '') {
             throw new Exception("No se encontró la Orden de Destino en la cabecera de la cotizacion del proveedor adjudicado");
           }
           /***************/
           //var_dump("aqui llega data",$tipoEvaluacion);exit;
                 $CabeceraSolicitud = array(
                                             "Fecha"=>$datosCabecera[$i]["fecha_po"],
                                             "TipoOrden"=>$tipoEvaluacion,//1 para orden de compra 2 para orden de reparacion
                                             "IdProveedor"=>$datosCabecera[$i]["id_proveedor"],
                                             "IdTipoCriticidad"=>$datosCabecera[$i]["id_criticidad"],
                                             "IdCondicionEntrega"=>$datosCabecera[$i]["id_condicion_entrega_alkym"],
                                             "IdFormaPago"=>$datosCabecera[$i]["id_forma_pago_alkym"],
                                             "IdModoEnvio"=>$datosCabecera[$i]["id_modo_envio_alkym"],
                                             "IdPuntoEntrega"=>$datosCabecera[$i]["id_puntos_entrega_alkym"],
                                             "IdTipoTransaccion"=>$datosCabecera[$i]["id_tipo_transaccion_alkym"],
                                             "Total"=>$datosCabecera[$i]["monto_total"],
                                             "MatriculaAvion"=>$datosCabecera[$i]["matricula"],
                                             "IdProveedorContacto"=>$datosCabecera[$i]["id_proveedor_contacto"],
                                             "IdDestinoOrden"=>$datosCabecera[$i]["id_orden_destino_alkym"],
                                             "Observacion"=>$datosCabecera[$i]["observaciones_sol"],
                                             "Descripcion"=>$datosCabecera[$i]["observaciones_sol"],
                                             "NroDocumento"=>$datosCabecera[$i]["nro_documento"],
                                             "DetalleOrdenCompra"=>$datos_arreglo_json
                                             );

          }

         /*************************************************************************************/
           $datosArmados = array("credenciales"=>"",
                                 "dato"=>json_encode($CabeceraSolicitud));
           $enviarDatos = json_encode($datosArmados);
           //var_dump("mandar",$_SESSION["_ESTADO_SISTEMA"]);
           //var_dump("mandar",$enviarDatos);exit;
         /***********************************************/


             if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
               $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/InsertarOrdenCompra';
             } else {
               $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/InsertarOrdenCompra';
             }



          $session = curl_init($request);
          curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($session, CURLOPT_POSTFIELDS, $enviarDatos);
          curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($session, CURLOPT_HTTPHEADER, array(
                  'Content-Type: application/json',
                  'Content-Length: ' . strlen($enviarDatos))
          );

          $result = curl_exec($session);
          curl_close($session);

          $respuesta = json_decode($result);
          $respuesta_final = json_decode($respuesta->InsertarOrdenCompraResult);
          //var_dump("llega aqui el dato",$respuesta_final);
          $idPoAlkym = $respuesta_final->objeto->IdPO_alkym;
          $nroPOAlkym = $respuesta_final->objeto->nroPOAlkym;

          if (($respuesta_final->codigo != 0) && ($respuesta_final->codigo != 1)) {
            throw new Exception("Ocurrio un problema al registrar el PO en el sistema Alkym, favor contactarse con el Area de Sistemas y reportarlo, el mensaje de Respuesta es el siguiente:".$respuesta_final->mensaje."los datos de envio son:".$enviarDatos);
          }

          if ($respuesta_final->codigo == 0) {
            throw new Exception($respuesta_final->mensaje.' en el sistema de Mantenimiento favor verifique');
          }
          else {
            $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);
            $this->objParam->addParametro('idPoAlkym',$idPoAlkym);
            $this->objParam->addParametro('nro_po',$nroPOAlkym);
            $this->objFunc=$this->create('MODSolicitud');
            $this->res=$this->objFunc->actualizarPO($this->objParam);


            $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);
            $this->objFunc=$this->create('MODSolicitud');
            $this->resPasaEstado=$this->objFunc->siguienteEstadoSolicitud($this->objParam);
            $this->resPasaEstado->imprimirRespuesta($this->resPasaEstado->generarJson());

          }
        } else {
          $this->resControlPresupuestos->imprimirRespuesta($this->resControlPresupuestos->generarJson());
        }

     } else {
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->siguienteEstadoSolicitud($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
     }
      /*****************************************************************************************************************************/


    }

    function devolverTramiteServicio(){

      $variable_global = $this->conexionSistemaControlMantenimiento();
      $conexionAlkym = $variable_global->getDatos();
      $respuesta = $conexionAlkym[0]["variable_obtenida"];

      if ($respuesta == 'si') {
        $observacion = $this->objParam->getParametro('obs');
        $nro_tramite = $this->objParam->getParametro('nro_tramite');
        $data = array("usuario"=>$_SESSION["_LOGIN"], "estado"=>"rechazado", "nroTramite"=>$nro_tramite,"observacion"=>$observacion);
        $datosUpdate = json_encode($data);
        $concatenar_variable = array("datosUpdate"=>$datosUpdate);
        $envio_dato = json_encode($concatenar_variable);
        if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
          $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/UpdateEstadoCompras';
        } else {
          $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/UpdateEstadoCompras';
        }

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($envio_dato))
        );

        $result = curl_exec($session);
        curl_close($session);

        $respuesta = json_decode($result);
        $respuesta_final = json_decode($respuesta->UpdateEstadoComprasResult);


        if (($respuesta_final->codigo != 0) && ($respuesta_final->codigo != 1)) {
          throw new Exception('No se puede conectar con el servicio de Mantenimiento. Porfavor consulte con el Área de Sistemas');
        }

        if ($respuesta_final->codigo == 0) {
          throw new Exception($respuesta_final->mensaje.' en el sistema de Mantenimiento favor verifique');
        } else {
          $this->objFunc=$this->create('MODSolicitud');
          $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
          $this->res=$this->objFunc->devolverTramiteServicio($this->objParam);

          $this->res->imprimirRespuesta($this->res->generarJson());
        }
      } else {
        $this->objFunc=$this->create('MODSolicitud');
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
        $this->res=$this->objFunc->anteriorEstadoSolicitud($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
      }
    }
    /**********************************************************************************/
    function inicioEstadoSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
        $this->res=$this->objFunc->inicioEstadoSolicitud($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function reporteRequerimientoIng (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarRequerimiento($this->objParam);

        $this->objFunc=$this->create('MODSolicitud');
        $this->res2=$this->objFunc->listasFrimas($this->objParam);
        $this->objFunc=$this->create('MODSolicitud');
        //$this->res3=$this->objFunc->listasFrimas2($this->objParam);

        //var_dump($this->res2);exit;
        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RRequemientoMaterielesIng($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }

    function reporteRequerimientoMan (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarRequerimiento($this->objParam);
        $this->objFunc=$this->create('MODSolicitud');
        $this->res2=$this->objFunc->listasFrimas($this->objParam);
        $this->objFunc=$this->create('MODSolicitud');
        //$this->res3=$this->objFunc->listasFrimas2($this->objParam);
        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RRequemientoMaterielesMan($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function reporteRequerimientoCeac (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarRequerimiento($this->objParam);
        $this->objFunc=$this->create('MODSolicitud');
        $this->res2=$this->objFunc->listasFrimas($this->objParam);
        $this->objFunc=$this->create('MODSolicitud');
        //$this->res3=$this->objFunc->listasFrimas2($this->objParam);
        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RRequerimientoMaterialesCeac($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }

    function reporteRequerimientoAlm (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarRequerimiento($this->objParam);
        $this->objFunc=$this->create('MODSolicitud');
        $this->res2=$this->objFunc->listasFrimas($this->objParam);


        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','L');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RRequemientoMaterielesAlm($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function compararNroJustificacion(){

        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listaNroJustificacion($this->objParam);
        //var_dump($this->res); exit;
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function ControlPartesAlmacen (){

        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->ControlPartesAlmacen ($this->objParam);
        //obtener titulo de reporte
        $titulo ='Control Almacen';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);

        $nombreArchivo.='.xls';
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        $this->objParam->addParametro('datos',$this->res->datos);
        //Instancia la clase de excel
        $this->objReporteFormato=new RControlAlmacenXLS($this->objParam);
        $this->objReporteFormato->generarDatos();
        $this->objReporteFormato->generarReporte();

        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }
    /* function cambiarRevision(){
         $this->objFunc=$this->create('MODSolicitud');
         $this->res=$this->objFunc->listarRevision($this->objParam);
         $this->res->imprimirRespuesta($this->res->generarJson());
     }*/
    function reporteSalidaAlmacen (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarRequerimiento($this->objParam);
        $this->objFunc=$this->create('MODSolicitud');
        $this->res2=$this->objFunc->listasFrimas($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','L');
        $this->objParam->addParametro('tamano','A4');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RSalidaAlmacen($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos );
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function listarEstado(){
        $this->objParam->defecto('ordenacion','id_tipo_estado');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_tipo_estado') != '') {
            $this->objParam->addFiltro(" t.id_tipo_estado = " . $this->objParam->getParametro('id_tipo_estado'));
        }
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarEstado($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function listarEstadoOp(){
        $this->objParam->defecto('ordenacion','id_tipo_estado');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_tipo_estado') != '') {
            $this->objParam->addFiltro(" t.id_tipo_estado = " . $this->objParam->getParametro('id_tipo_estado'));
        }
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarEstadoOp($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function listarEstadoRo(){
        $this->objParam->defecto('ordenacion','id_tipo_estado');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_tipo_estado') != '') {
            $this->objParam->addFiltro(" t.id_tipo_estado = " . $this->objParam->getParametro('id_tipo_estado'));
        }
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarEstadoRo($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarEstadoSAC(){
        $this->objParam->defecto('ordenacion','id_tipo_estado');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_tipo_estado') != '') {
            $this->objParam->addFiltro(" t.id_tipo_estado = " . $this->objParam->getParametro('id_tipo_estado'));
        }
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarEstadoSAC($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function reporteRequerimientoMateriales (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarRequerimiento($this->objParam);
        $this->objFunc=$this->create('MODSolicitud');
        $this->res2=$this->objFunc->listasFrimas($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RRequerimientoMaterialesPDF($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos );
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function iniciarDisparo(){

        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->iniciarDisparo($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());

    }
    function siguienteDisparo(){
        $this->objFunc=$this->create('MODSolicitud');

        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);

        $this->res=$this->objFunc->siguienteDisparo($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function anteriorDisparo(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
        $this->res=$this->objFunc->anteriorEstadoDisparo($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function inicioEstadoSolicitudDisparo(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
        $this->res=$this->objFunc->inicioEstadoSolicitudDisparo($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function reporteDocContratacionExt(){
        $this->objFunc=$this->create('MODSolicitud');
        $dataSource=$this->objFunc->reporteDocContratacionExt();
        $this->dataSource=$dataSource->getDatos();

        $nombreArchivo = uniqid(md5(session_id()).'[Reporte-DocContratacionExt]').'.pdf';
        $this->objParam->addParametro('orientacion','L');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        $this->objReporte = new RDocContratacionExtPDF($this->objParam);
        $this->objReporte->setDatos($this->dataSource);
        $this->objReporte->generarReporte();
        $this->objReporte->output($this->objReporte->url_archivo,'F');

        $this->mensajeExito = new Mensaje();
        $this->mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado', 'Se generó con éxito el reporte: ' . $nombreArchivo, 'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());


    }
    /*function reporteDocContratacionExt( $create_file = false){
        $dataSource = new DataSource();
        $this->objFunc=$this->create('MODSolicitud');
        $resultadoSolicitud = $this->objFunc->reporteDocContratacionExt();
        $datoSolicitud = $resultadoSolicitud->getDatos();
        //var_dump($datoSolicitud);exit;
        $dataSource->putParameter('solicitud', $datoSolicitud);
        $nombreArchivo = uniqid(md5(session_id()).'[Reporte-DocContratacionExt]') . '.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('titulo_archivo','SOLICITUD DE COTIZACION');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //build the report
        $reporte = new RDocContratacionExtPDF($this->objParam);
        $reporte->setDataSource($dataSource);
        $datos= $reporte->generarReporte();
        if(!$create_file){
            $mensajeExito = new Mensaje();
            $mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
            $mensajeExito->setArchivoGenerado($nombreArchivo);
            $mensajeExito->setDatos($datos);
            $this->res = $mensajeExito;
            $this->res->imprimirRespuesta($this->res->generarJson());
        }else{
            return dirname(__FILE__).'/../../reportes_generados/'.$nombreArchivo;
        }
    }*/

    function listarComiteEvaluacion (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarComiteEvaluacion($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RComiteEvaluacion($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    /*Aumentando para el reporte del comite Evaluacion para GR (Ismael Valdivia 25/03/2020)*/
    function listarComiteEvaluacionGR (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarComiteEvaluacionGR($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RComiteEvaluacionGR($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function reporteOrdenReparacion (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->reporteOrdenReparacion($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Orden de reparacion exterior';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new ROrdenDeReparacionExterior($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function informeDeJustificacion (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->informeDeJustificacion($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Informe de Justificación y Recomendación';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RInformJustificacionRep($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function technicalSpecifications (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->technicalSpecifications($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Technical Specifications';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RTechnicalSpecifications($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    /************************************************************************************/

    /*Aumentando para el reporte de nota de Adjudicacion*/
    function NotaAdjudicacionBoaRep (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->NotaAdjudicacionBoaRep($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Nota de Adjudicación';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RNotaDeAdjudicacionRep($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    /****************************************************/


    function listarProveedor(){

        $this->objParam->defecto('ordenacion','id_proveedor');
        $this->objParam->defecto('dir_ordenacion','asc');

        if ($this->objParam->getParametro('tipo') != '') {
          if ($this->objParam->getParametro('tipo') != 'talleres') {
              $this->objParam->addFiltro("provee.tipo  in (''". $this->objParam->getParametro('tipo')."'',''taller-abas'')");
          } else {
            $this->objParam->addFiltro("provee.tipo  in (''taller_repues_abas'',''broker_repues_abas'', ''taller-abas'')");
          }
        }
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarProveedor();

        if($this->objParam->getParametro('_adicionar')!=''){

            $respuesta = $this->res->getDatos();
            array_unshift ( $respuesta, array('rotulo_comercial'=>'Todos','desc_proveedor'=>'Todos','id_proveedor'=>'0','email'=>'todo@gmail.com'));
            $this->res->setDatos($respuesta);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    //fea 04/07/2017
    function setCorreosCotizacion(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->setCorreosCotizacion();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    //fea 05/07/2017
    function reporteComparacionByS(){
        $this->objFunc=$this->create('MODSolicitud');
        $dataSource=$this->objFunc->reporteComparacionByS();
        $this->dataSource=$dataSource->getDatos();

        $nombreArchivo = uniqid(md5(session_id()).'[Reporte-ComparacionBienesyServicios]').'.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        $this->objReporte = new RComparacionBySPDF($this->objParam);
        $this->objReporte->setDatos($this->dataSource);
        $this->objReporte->generarReporte();
        $this->objReporte->output($this->objReporte->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado', 'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    //fea 06/07/2017
    function verificarCorreosProveedor(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->verificarCorreosProveedor();

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function clonarSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->clonarSolicitud();

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function generarPAC(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->generarPAC($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function ReporteConstanciaEnvioInvitacion(){

        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->ReporteConstanciaEnvioInvitacion($this->objParam);
//        $dataSource=$this->objFunc->ReporteConstanciaEnvioInvitacion();
//        $this->dataSource=$dataSource->getDatos();

        //obtener titulo del reporte
        $titulo = 'Correo';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        $this->objReporteFormato=new RConstanciaEnvioInvitacion($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');
        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
//var_dump($this->res);
    }

    function ReporteConstanciaEnvioInvitacionRep(){

        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->ReporteConstanciaEnvioInvitacionRep($this->objParam);
//        $dataSource=$this->objFunc->ReporteConstanciaEnvioInvitacion();
//        $this->dataSource=$dataSource->getDatos();

        //obtener titulo del reporte
        $titulo = 'Correo';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','L');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        $this->objReporteFormato=new RConstanciaEnvioInvitacionBoaRep($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');
        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }

    function obtenerCategoriaProg()
    {
        //crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
        $this->objFunSeguridad = $this->create('sis_seguridad/MODSubsistema');
        $objParam = new CTParametro($aPostData['p'], null, $aPostFiles);
        $objParam->addParametro('codigo', 'pre_verificar_categoria');
        $objFunc = new MODSubsistema($objParam);
        $this->res = $objFunc->obtenerVariableGlobal($this->objParam);

        return $this->res->getDatos();
    }

    function reporteCertificacionP()
    {
        $this->objFunc = $this->create('MODSolicitud');
        $this->res = $this->objFunc->reporteCertificacionP($this->objParam);
        //var_dump($this->res);exit;
        //obtener titulo del reporte
        $titulo = 'Informe de Reclamo';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo = uniqid(md5(session_id()) . $titulo);
        $nombreArchivo .= '.pdf';

        $this->objParam->addParametro('orientacion', 'P');
        $this->objParam->addParametro('tamano', 'LETTER');
        $this->objParam->addParametro('nombre_archivo', $nombreArchivo);


        $this->objReporteFormato = new RCertificacionPresupuestaria($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo, 'F');


        $this->mensajeExito = new Mensaje();
        $this->mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado',
            'Se generó con éxito el reporte: ' . $nombreArchivo, 'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }


    function reporteSolicitudCompraBoARep($create_file = false, $onlyData = false){

      $dataSource = new DataSource();
      $sw_cat = $this->obtenerCategoriaProg();

      $firmar = 'no';
      $fecha_firma = '';
      $usuario_firma = '';

      $idSolicitud = $this->objParam->getParametro('id_solicitud');
      $id_proceso_wf = $this->objParam->getParametro('id_proceso_wf');
      $estado = $this->objParam->getParametro('estado');

      $this->objParam->addParametroConsulta('ordenacion', 'id_solicitud');
      $this->objParam->addParametroConsulta('dir_ordenacion', 'ASC');
      $this->objParam->addParametroConsulta('cantidad', 1000);
      $this->objParam->addParametroConsulta('puntero', 0);

      $this->objFunc = $this->create('MODSolicitud');
      $resultSolicitud = $this->objFunc->reporteSolicitudCompraBoARep();

      $datosSolicitud = $resultSolicitud->getDatos();

      //armamos el array parametros y metemos ahi los data sets de las otras tablas
      $dataSource->putParameter('estado', $datosSolicitud[0]['estado']);
      $dataSource->putParameter('id_solicitud', $datosSolicitud[0]['id_solicitud']);
      $dataSource->putParameter('num_tramite', $datosSolicitud[0]['num_tramite']);
      $dataSource->putParameter('desc_moneda', $datosSolicitud[0]['desc_moneda']);
      $dataSource->putParameter('tipo', $datosSolicitud[0]['tipo']);
      $dataSource->putParameter('desc_gestion', $datosSolicitud[0]['desc_gestion']);
      $dataSource->putParameter('fecha_solicitud', $datosSolicitud[0]['fecha_solicitud']);
      $dataSource->putParameter('fecha_soli_material', $datosSolicitud[0]['fecha_soli_material']);
      $dataSource->putParameter('fecha_soli_gant', $datosSolicitud[0]['fecha_soli_gant']);
      $dataSource->putParameter('desc_funcionario', $datosSolicitud[0]['desc_funcionario']);
      $dataSource->putParameter('desc_depto', $datosSolicitud[0]['desc_depto']);

      $dataSource->putParameter('justificacion', $datosSolicitud[0]['justificacion']);
      $dataSource->putParameter('nombre_usuario_ai', $datosSolicitud[0]['nombre_usuario_ai']);
      $dataSource->putParameter('fecha_reg', $datosSolicitud[0]['fecha_reg']);
      $dataSource->putParameter('dep_prioridad', $datosSolicitud[0]['dep_prioridad']);
      $dataSource->putParameter('funcionario_rpc', $datosSolicitud[0]['funcionario_rpc']);
      $dataSource->putParameter('gerente', $datosSolicitud[0]['gerente']);
      $dataSource->putParameter('desc_uo', $datosSolicitud[0]['desc_uo']);
      $dataSource->putParameter('cargo_desc_funcionario', $datosSolicitud[0]['cargo_desc_funcionario']);
      $dataSource->putParameter('desc_cargo_gerente', $datosSolicitud[0]['desc_cargo_gerente']);
      $dataSource->putParameter('nombre_macro', $datosSolicitud[0]['nombre_macro']);
      $dataSource->putParameter('cotizacion_fecha', $datosSolicitud[0]['cotizacion_fecha']);
      $dataSource->putParameter('funcionario_jefe', $datosSolicitud[0]['funcionario_jefe']);
      $dataSource->putParameter('cargo_jefe', $datosSolicitud[0]['cargo_jefe']);

      //get detalle
      //Reset all extra params:
      $this->objParam->defecto('ordenacion', 'id_detalle');
      $this->objParam->defecto('cantidad', 1000);
      $this->objParam->defecto('puntero', 0);

      $this->objParam->addParametro('id_solicitud', $datosSolicitud[0]['id_solicitud']);
      $modSolicitudDet = $this->create('MODDetalleSol');
      //lista el detalle de la solicitud
      $resultSolicitudDet = $modSolicitudDet->listarDetalleSolSolictudCompra();
      //var_dump("llega el detalle",$resultSolicitudDet);
      //agrupa el detalle de la solcitud por centros de costos y partidas

      // if( $datosSolicitud[0]['fecha_reg'] >= '2020-01-01') {
      //var_dump("llega el detalle",$resultSolicitudDet->getDatos());
      $solicitudDetAgrupado = $this->groupArray($resultSolicitudDet->getDatos(), 'codigo_partida', 'desc_centro_costo', $datosSolicitud[0]['id_moneda'], $datosSolicitud[0]['estado'], $onlyData);
      // }

      $solicitudDetDataSource = new DataSource();
      $solicitudDetDataSource->setDataSet($solicitudDetAgrupado);
      //$solicitudDetDataSource->setDataSet($resultSolicitudDet->getDatos());
      // var_dump("no pasa");exit;
      //inserta el detalle de la colistud como origen de datos
      $dataSource->putParameter('detalleDataSource', $solicitudDetDataSource);
      $dataSource->putParameter('sw_cat', $sw_cat["valor"]);

      if ($onlyData) {
          return $dataSource;
      }
      $nombreArchivo = uniqid(md5(session_id()) . 'SolicitudCompra') . '.pdf';
      $this->objParam->addParametro('orientacion', 'P');
      $this->objParam->addParametro('tamano', 'LETTER');
      $this->objParam->addParametro('titulo_archivo', 'SOLICITUD DE COMPRA');
      $this->objParam->addParametro('nombre_archivo', $nombreArchivo);
      $this->objParam->addParametro('firmar', $firmar);
      $this->objParam->addParametro('fecha_firma', $fecha_firma);
      $this->objParam->addParametro('usuario_firma', $usuario_firma);
      //build the report
      $reporte = new RSolicitudCompraBoARep($this->objParam);

      $reporte->setDataSource($dataSource);
      $datos_firma = $reporte->write();

      if (!$create_file) {
          $mensajeExito = new Mensaje();
          $mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado', 'Se generó con éxito el reporte: ' . $nombreArchivo, 'control');
          $mensajeExito->setArchivoGenerado($nombreArchivo);
          //anade los datos de firma a la respuesta
          if ($firmar == 'si') {
              $mensajeExito->setDatos($datos_firma);
          }
          $this->res = $mensajeExito;
          $this->res->imprimirRespuesta($this->res->generarJson());
      } else {
          return dirname(__FILE__) . '/../../reportes_generados/' . $nombreArchivo;
      }
    }

    function groupArray($array, $groupkey, $groupkeyTwo, $id_moneda, $estado_sol, $onlyData)
    {

        if (count($array) > 0) {
            //recupera las llaves del array
            $keys = array_keys($array[0]);
            $removekey = array_search($groupkey, $keys);
            $removekeyTwo = array_search($groupkeyTwo, $keys);

            if ($removekey === false)
                return array("Clave \"$groupkey\" no existe");
            if ($removekeyTwo === false)
                return array("Clave \"$groupkeyTwo\" no existe");


            //crea los array para agrupar y para busquedas
            $groupcriteria = array();
            $arrayResp = array();

            //recorre el resultado de la consulta de oslicitud detalle
            foreach ($array as $value) {
                //por cada registro almacena el valor correspondiente en $item
                $item = null;
                foreach ($keys as $key) {
                    $item[$key] = $value[$key];
                }

                //buscar si el grupo ya se incerto
                $busca = array_search($value[$groupkey] . $value[$groupkeyTwo], $groupcriteria);

                if ($busca === false) {
                    //si el grupo no existe lo crea
                    //en la siguiente posicicion de crupcriteria agrega el identificador del grupo
                    $groupcriteria[] = $value[$groupkey] . $value[$groupkeyTwo];

                    //en la siguiente posivion cre ArrayResp cre un btupo con el identificaor nuevo
                    //y un bubgrupo para acumular los detalle de semejaste caracteristicas

                    $arrayResp[] = array($groupkey . $groupkeyTwo => $value[$groupkey] . $value[$groupkeyTwo], 'groupeddata' => array(), 'presu_verificado' => "false");
                    $arrayPresuVer[] =
                        //coloca el indice en la ultima posicion insertada
                    $busca = count($arrayResp) - 1;

                }

                //inserta el registro en el subgrupo correspondiente
                $arrayResp[$busca]['groupeddata'][] = $item;

            }


            $cont_grup = 0;
            foreach ($arrayResp as $value2) {
                $grup_desc_centro_costo = "";
                $cc_array = array();
                foreach ($value2['groupeddata'] as $value_det) {


                    if (!in_array($value_det["desc_centro_costo"], $cc_array)) {
                        $grup_desc_centro_costo = $grup_desc_centro_costo . "\n" . $value_det["desc_centro_costo"];
                        $cc_array[] = $value_det["desc_centro_costo"];
                    }
                    //sumamos el monto a comprometer

                }
                $arrayResp[$cont_grup]["grup_desc_centro_costo"] = trim($grup_desc_centro_costo);
                $cont_grup++;
            }
            //solo verificar si el estado es borrador o pendiente
            //suma y verifica el presupuesto

            $estado_sin_presupuesto = array("borrador", "pendiente", "vbgerencia", "vbpresupuestos"); /*array("comite_unidad_abastecimientos", "autorizado", "compra", "despachado", "arribo", "desaduanizado","almacen","finalizado");*/

            if (in_array($estado_sol, $estado_sin_presupuesto) || $onlyData) {
                $cont_grup = 0;

                foreach ($arrayResp as $value2) {
                    $cc_array = array();
                    $total_pre = 0;
                    $grup_desc_centro_costo = "";

                    $busca = array_search($value2[$groupkey] . $value2[$groupkeyTwo], $groupcriteria);

                    foreach ($value2['groupeddata'] as $value_det) {
                        //sumamos el monto a comprometer
                        $total_pre = $total_pre + $value_det["precio_ga"];
                        if (!in_array($value_det["desc_centro_costo"], $cc_array)) {
                            $grup_desc_centro_costo = $grup_desc_centro_costo . "\n" . $value_det["desc_centro_costo"];
                            $cc_array[] = $value_det["desc_centro_costo"];
                        }
                    }

                    $value_det = $value2['groupeddata'][0];
                      //var_dump("aqui llega el dato",$value_det["id_presupuesto"]);
                    $this->objParam = new CTParametro(null, null, null);
                    $this->objParam->addParametro('id_presupuesto', $value_det["id_presupuesto"]);
                    $this->objParam->addParametro('id_partida', $value_det["id_partida"]);
                    $this->objParam->addParametro('id_moneda', $id_moneda);
                    $this->objParam->addParametro('monto_total', $total_pre);
                    $this->objParam->addParametro('id_solicitud', $value_det['id_solicitud']);
                    $this->objParam->addParametro('sis_origen', 'ADQ');
                    $this->objFunc = $this->create('sis_presupuestos/MODPresupuesto');
                    $resultSolicitud = $this->objFunc->verificarPresupuesto();

                    $arrayResp[$cont_grup]["presu_verificado"] = $resultSolicitud->datos["presu_verificado"];
                    $arrayResp[$cont_grup]["total_presu_verificado"] = $total_pre;
                    $arrayResp[$cont_grup]["grup_desc_centro_costo"] = $grup_desc_centro_costo;

                    $this->objParam1 = new CTParametro(null, null, null);
                    $this->objParam1->addParametro('id_presupuesto', $value_det["id_presupuesto"]);
                    $this->objParam1->addParametro('id_partida', $value_det["id_partida"]);
                    $this->objFunc1 = $this->create('sis_presupuestos/MODPresupuesto');
                    $resultSolicitud1 = $this->objFunc1->capturaPresupuesto();

                    $arrayResp[$cont_grup]["captura_presupuesto"] = $resultSolicitud1->datos["captura_presupuesto"];
                    $cont_grup++;


                    if ($resultSolicitud->getTipo() == 'ERROR') {

                        $resultSolicitud->imprimirRespuesta($resultSolicitud->generarMensajeJson());
                        exit;
                    }


                }

            }
            return $arrayResp;

        } else
            return array();
    }

    function getDatosNecesarios(){
      $this->objFunc=$this->create('MODSolicitud');
      $this->res=$this->objFunc->getDatosNecesarios($this->objParam);
      $this->res->imprimirRespuesta($this->res->generarJson());
    }

    /*Aumentando para controlar los documentos*/
    function getVerificarDocumentos(){
      $this->objFunc=$this->create('MODSolicitud');
      $this->res=$this->objFunc->getVerificarDocumentos($this->objParam);
      $this->res->imprimirRespuesta($this->res->generarJson());
    }
    /******************************************/

    function obtenerCombosAlkym(){
        if ($this->objParam->getParametro('tipo_combo') == 'condicion_entrega') {
              $data = '';
              $credenciales = json_encode($data);
              $concatenar_variable = array("Credenciales"=>$data);
              $envio_dato = json_encode($concatenar_variable);
              if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
                $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/ListaCondicionesEntrega';
              } else {
                $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/ListaCondicionesEntrega';
              }

              $session = curl_init($request);
              curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
              curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
              curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($session, CURLOPT_HTTPHEADER, array(
                      'Content-Type: application/json',
                      'Content-Length: ' . strlen($envio_dato))
              );

              $result = curl_exec($session);
              curl_close($session);
              $respuesta = json_decode($result);
              $respuesta_deco = json_decode($respuesta->ListaCondicionesEntregaResult);
              $respuesta_final = json_decode($respuesta_deco);

              /*Aqui recuperamos la informacion para enviar*/
              $json_obtenido = json_encode($respuesta_final->objeto);
              $cantidad_json = count($respuesta_final->objeto);
              $tipo_combo = 'condicion_entrega';
              /*********************************************/
        } else if ($this->objParam->getParametro('tipo_combo') == 'formas_pago') {
              $data = '';
              $credenciales = json_encode($data);
              $concatenar_variable = array("Credenciales"=>$data);
              $envio_dato = json_encode($concatenar_variable);
              if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
                $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/ListaFormasPago';
              } else {
                $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/ListaFormasPago';
              }

              $session = curl_init($request);

              curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
              curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
              curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($session, CURLOPT_HTTPHEADER, array(
                      'Content-Type: application/json',
                      'Content-Length: ' . strlen($envio_dato))
              );

              $result = curl_exec($session);
              curl_close($session);

              $respuesta = json_decode($result);
              $respuesta_deco = json_decode($respuesta->ListaFormasPagoResult);
              $respuesta_final = json_decode($respuesta_deco);
              /*Aqui recuperamos la informacion para enviar*/
              $json_obtenido = json_encode($respuesta_final->objeto);


              $cantidad_json = count($respuesta_final->objeto);
              $tipo_combo = 'formas_pago';

            //  var_dump("aqui llega el dato del pago",$json_obtenido);exit;
              /*********************************************/
        }   else if ($this->objParam->getParametro('tipo_combo') == 'modos_envio') {
              $data = '';
              $credenciales = json_encode($data);
              $concatenar_variable = array("Credenciales"=>$data);
              $envio_dato = json_encode($concatenar_variable);
              if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
                $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/ListaModosEnvio';
              } else {
                $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/ListaModosEnvio';
              }

              $session = curl_init($request);
              curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
              curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
              curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($session, CURLOPT_HTTPHEADER, array(
                      'Content-Type: application/json',
                      'Content-Length: ' . strlen($envio_dato))
              );

              $result = curl_exec($session);
              curl_close($session);

              $respuesta = json_decode($result);
              $respuesta_deco = json_decode($respuesta->ListaModosEnvioResult );
              $respuesta_final = json_decode($respuesta_deco);

              /*Aqui recuperamos la informacion para enviar*/
              $json_obtenido = json_encode($respuesta_final->objeto);
              $cantidad_json = count($respuesta_final->objeto);
              $tipo_combo = 'modos_envio';
              /*********************************************/
        } else if ($this->objParam->getParametro('tipo_combo') == 'puntos_entrega') {
              $data = '';
              $credenciales = json_encode($data);
              $concatenar_variable = array("Credenciales"=>$data);
              $envio_dato = json_encode($concatenar_variable);
              if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
                $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/ListaPuntosEntrega';
              } else {
                $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/ListaPuntosEntrega';
              }

              $session = curl_init($request);
              curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
              curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
              curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($session, CURLOPT_HTTPHEADER, array(
                      'Content-Type: application/json',
                      'Content-Length: ' . strlen($envio_dato))
              );

              $result = curl_exec($session);
              curl_close($session);

              $respuesta = json_decode($result);
              $respuesta_deco = json_decode($respuesta->ListaPuntosEntregaResult);
              $respuesta_final = json_decode($respuesta_deco);

              /*Aqui recuperamos la informacion para enviar*/
              $json_obtenido = json_encode($respuesta_final->objeto);
              $cantidad_json = count($respuesta_final->objeto);
              $tipo_combo = 'puntos_entrega';
              //var_dump("el combo es",$json_obtenido);
              /*********************************************/
        } else if ($this->objParam->getParametro('tipo_combo') == 'tipo_transaccion') {
              $data = '';
              $credenciales = json_encode($data);
              $concatenar_variable = array("Credenciales"=>$data);
              $envio_dato = json_encode($concatenar_variable);
              if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
                $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/ListaTipoTransacciones';
              } else {
                $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/ListaTipoTransacciones';
              }

              $session = curl_init($request);
              curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
              curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
              curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($session, CURLOPT_HTTPHEADER, array(
                      'Content-Type: application/json',
                      'Content-Length: ' . strlen($envio_dato))
              );

              $result = curl_exec($session);
              curl_close($session);

              $respuesta = json_decode($result);
              $respuesta_deco = json_decode($respuesta->ListaTipoTransaccionesResult);
              $respuesta_final = json_decode($respuesta_deco);

              /*Aqui recuperamos la informacion para enviar*/
              $json_obtenido = json_encode($respuesta_final->objeto);
              $cantidad_json = count($respuesta_final->objeto);
              $tipo_combo = 'tipo_transaccion';
              /*********************************************/
        } else if ($this->objParam->getParametro('tipo_combo') == 'orden_destino') {
              $data = '';
              $credenciales = json_encode($data);
              $concatenar_variable = array("Credenciales"=>$data);
              $envio_dato = json_encode($concatenar_variable);
              if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
                $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/ListaDestinosOrdenes';
              } else {
                $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/ListaDestinosOrdenes';
              }

              $session = curl_init($request);
              curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
              curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
              curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($session, CURLOPT_HTTPHEADER, array(
                      'Content-Type: application/json',
                      'Content-Length: ' . strlen($envio_dato))
              );

              $result = curl_exec($session);
              curl_close($session);

              $respuesta = json_decode($result);
              $respuesta_deco = json_decode($respuesta->ListaDestinosOrdenesResult);
              $respuesta_final = json_decode($respuesta_deco);

              /*Aqui recuperamos la informacion para enviar*/
              $json_obtenido = json_encode($respuesta_final->objeto);
              $cantidad_json = count($respuesta_final->objeto);
              $tipo_combo = 'orden_destino';
              /*********************************************/
        }

            //var_dump("aqui recuperar la direccion",$json_obtenido);
              if ($respuesta_final == '') {
                throw new Exception('No se puede conectar con el servicio de Mantenimiento. Porfavor consulte con el Área de Sistemas');
              }

              if ($respuesta_final->codigo == 0) {
                throw new Exception('No se pudo obtener información favor comunicarse con el Area de Sistemas');
              } else {

                //$this->objParam->addParametro('json_obtenido',$_SESSION["_LOGIN"]);
                $this->objParam->addParametro('json_obtenido',$json_obtenido);
                $this->objParam->addParametro('cantidad_json',$cantidad_json);
                $this->objParam->addParametro('tipo_combo',$tipo_combo);
                $this->objFunc=$this->create('MODSolicitud');
                $this->res=$this->objFunc->obtenerCombosAlkym($this->objParam);
                $this->res->imprimirRespuesta($this->res->generarJson());

              }
    }

    /*Aumentando para los combos del Charly Papa y de los PartNumber*/
    function obtenerCombosPartNumber(){


      if ($this->objParam->getParametro('auto_complete') == 'si') {
          $data = $this->objParam->getParametro('query');
      }

      //  $credenciales = json_encode($data);

        $concatenar_variable = array("pn"=>$data,
                                      "nomProducto"=>"");
        $envio_dato = json_encode($concatenar_variable);


        // $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/ListaCondicionesEntrega';

        if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
          $request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/BuscarProducto';
        } else {
          $request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/BuscarProducto';
        }


        //$request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/MostrarAvion';
        $session = curl_init($request);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($envio_dato))
        );

        $result = curl_exec($session);
        curl_close($session);
        $respuesta = json_decode($result);



        $respuesta_deco = json_decode($respuesta->BuscarProductoResult);
        $respuesta_final = ($respuesta_deco->objeto);



        /*Aqui recuperamos la informacion para enviar*/
        $json_obtenido = json_encode($respuesta_final);
        $cantidad_json = count($respuesta_final);
        //var_dump("aqui respuesta del servicio",$json_obtenido); exit;
        /*********************************************/
        // var_dump("aqui respuesta del servicio",$respuesta_final);
        // var_dump("aqui respuesta del servicio",$respuesta_final->codigo); exit;

            //var_dump("aqui recuperar la direccion",$json_obtenido);
              if ($respuesta_final == '') {
                throw new Exception('No se puede conectar con el servicio de Mantenimiento. Porfavor consulte con el Área de Sistemas');
              } else {

                //$this->objParam->addParametro('json_obtenido',$_SESSION["_LOGIN"]);
                $this->objParam->addParametro('json_obtenido',$json_obtenido);
                $this->objParam->addParametro('cantidad_json',$cantidad_json);
                $this->objFunc=$this->create('MODSolicitud');
                $this->res=$this->objFunc->obtenerCombosPartNumber($this->objParam);
                $this->res->imprimirRespuesta($this->res->generarJson());

              }
    }
    /****************************************************************/




    function controlPresupuesto(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->controlPresupuesto($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function ModificarTipoSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->ModificarTipoSolicitud($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function getDatosAlkym(){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->getDatosAlkym($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }

   //{developer:franklin.espinoza date: 04/06/2020}
    function upload_file_mantenimiento_erp(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->upload_file_mantenimiento_erp($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

   /*Aumentando esta parte para el reporte de RPC (Ismael Valdivia 07/10/2020)*/
   function ControlRpc (){

       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->ControlRpc ($this->objParam);
       //obtener titulo de reporte
       $titulo ='Control Partes';
       //Genera el nombre del archivo (aleatorio + titulo)
       $nombreArchivo=uniqid(md5(session_id()).$titulo);

       $nombreArchivo.='.xls';
       $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
       $this->objParam->addParametro('datos',$this->res->datos);
       //Instancia la clase de excel
       $this->objReporteFormato=new RControlRpc($this->objParam);
       $this->objReporteFormato->generarDatos();
       $this->objReporteFormato->generarReporte();

       $this->mensajeExito=new Mensaje();
       $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
           'Se generó con éxito el reporte: '.$nombreArchivo,'control');
       $this->mensajeExito->setArchivoGenerado($nombreArchivo);
       $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

   }

   function ControlGridRpc (){

       if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
           $this->objReporte = new Reporte($this->objParam,$this);
           $this->res = $this->objReporte->generarReporteListado('MODSolicitud','ControlGridRpc');
       } else{
           $this->objFunc=$this->create('MODSolicitud');

           $this->res=$this->objFunc->ControlGridRpc($this->objParam);
       }

       // $this->objFunc=$this->create('MODSolicitud');
       // $this->res=$this->objFunc->ControlGridRpc($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }
   /***************************************************************************/

   /*Aumentando el reporte del Acta de conformidad final*/
   function reporteActaConformidadFinal()
   {
       $this->objFunc = $this->create('MODSolicitud');
       $this->detalle = $this->objFunc->reporteActaConformidadFinalDetalle($this->objParam);

       $this->objFunc = $this->create('MODSolicitud');
       $this->res = $this->objFunc->reporteActaConformidadFinal($this->objParam);

       //obtener titulo del reporte
       $titulo = 'Conformidad';
       //Genera el nombre del archivo (aleatorio + titulo)
       $nombreArchivo = uniqid(md5(session_id()) . $titulo);
       $nombreArchivo .= '.pdf';
       $this->objParam->addParametro('orientacion', 'P');
       $this->objParam->addParametro('nombre_archivo', $nombreArchivo);


       $this->objReporteFormato = new RConformidadActaFinal($this->objParam);
       $this->objReporteFormato->setDatos($this->res->datos,$this->detalle);
       $this->objReporteFormato->generarReporte();
       $this->objReporteFormato->output($this->objReporteFormato->url_archivo, 'F');

       $this->mensajeExito = new Mensaje();
       $this->mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado',
           'Se generó con éxito el reporte: ' . $nombreArchivo, 'control');
       $this->mensajeExito->setArchivoGenerado($nombreArchivo);
       $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

   }
   /*****************************************************/

   function listarActaFinal(){
       $this->objParam->defecto('ordenacion', 'id_solicitud');
       $this->objParam->defecto('dir_ordenacion', 'asc');

       if ($this->objParam->getParametro('pes_estado') != '') {
         	if ($this->objParam->getParametro('pes_estado') == 'sin_firma') {
            	$this->objParam->addFiltro(" (acta.revisado = ''no'') ");
          } elseif ($this->objParam->getParametro('pes_estado') == 'firmados') {
            $this->objParam->addFiltro(" (acta.revisado = ''si'') ");
          }
       }


       if ($this->objParam->getParametro('id_gestion') != '') {
         $this->objParam->addFiltro("sol.id_gestion = ".$this->objParam->getParametro('id_gestion'));
       }

       if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
           $this->objReporte = new Reporte($this->objParam,$this);
           $this->res = $this->objReporte->generarReporteListado('MODSolicitud','listarActaFinal');
       } else{
           $this->objFunc=$this->create('MODSolicitud');

           $this->res=$this->objFunc->listarActaFinal($this->objParam);
       }

       //$this->objFunc=$this->create('MODSolicitud');
       //$this->res=$this->objFunc->listarActaFinal($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }

   function actualizarActaConformidad(){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->actualizarActaConformidad($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }

   function controlReimpresion(){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->controlReimpresion($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }


   function consultaDetalleSolicitud(){
     $this->objFunc=$this->create('MODSolicitud');
     $this->res=$this->objFunc->consultaDetalleSolicitud($this->objParam);
     $this->res->imprimirRespuesta($this->res->generarJson());
   }

   function insertarCuce(){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->insertarCuce($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }


   function insertarPac(){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->insertarPac($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }

   /*Aumetando el Formulario 3008 para que se genere en el ERP (Ismael Valdivia 17/05/200)*/
   function formulario3008 (){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->formulario3008($this->objParam);

       //obtener titulo del reporte
       $titulo = 'FORM-3008';
       //Genera el nombre del archivo (aleatorio + titulo)
       $nombreArchivo=uniqid(md5(session_id()).$titulo);
       $nombreArchivo.='.pdf';
       $this->objParam->addParametro('orientacion','P');
       $this->objParam->addParametro('tamano','LETTER');
       $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
       //Instancia la clase de pdf

       $this->objReporteFormato=new RFORMULARIO3008($this->objParam);
       $this->objReporteFormato->setDatos($this->res->datos);
       $this->objReporteFormato->generarReporte();
       $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


       $this->mensajeExito=new Mensaje();
       $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
           'Se generó con éxito el reporte: '.$nombreArchivo,'control');
       $this->mensajeExito->setArchivoGenerado($nombreArchivo);
       $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
   }

   function insertarFecha3008(){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->insertarFecha3008($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }
   /***************************************************************************************/

   /*Aumentando para controlar los documentos*/
   function getVerificarMontoAdjudicado(){
     $this->objFunc=$this->create('MODSolicitud');
     $this->res=$this->objFunc->getVerificarMontoAdjudicado($this->objParam);
     $this->res->imprimirRespuesta($this->res->generarJson());
   }
   /******************************************/

   function listarForm400(){

       if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
           $this->objReporte = new Reporte($this->objParam,$this);
           $this->res = $this->objReporte->generarReporteListado('MODSolicitud','listarForm400');
       }else {

           $this->objFunc = $this->create('MODSolicitud');
           $this->res = $this->objFunc->listarForm400($this->objParam);
       }

       $this->res->imprimirRespuesta($this->res->generarJson());
   }



   function listarFuncionariosEncargados(){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->listarFuncionariosEncargados($this->objParam);

       $respuesta = $this->res->getDatos();

       array_unshift ( $respuesta, array(
 				'id_funcionario'=>'0',
 				'desc_funcionario'=>'TODOS') );

        $this->res->setDatos($respuesta);



       $this->res->imprimirRespuesta($this->res->generarJson());
   }



   function verificarUsuario(){
       $this->objParam->defecto('ordenacion','id_funcionario');
       $this->objParam->defecto('dir_ordenacion','asc');


       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->verificarUsuario($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }


   function listarTotalTramites(){
       $this->objFunc=$this->create('MODSolicitud');
       $this->res=$this->objFunc->listarTotalTramites($this->objParam);
       $this->res->imprimirRespuesta($this->res->generarJson());
   }

}

?>
