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
class ACTSolicitud extends ACTbase{

    function listarSolicitud(){
        $this->objParam->defecto('ordenacion','id_solicitud');

        $this->objParam->defecto('dir_ordenacion','asc');

        if ($this->objParam->getParametro('pes_estado') == 'borrador') {
            $this->objParam->addFiltro("sol.estado  in (''borrador'')");
        }
         if ($this->objParam->getParametro('pes_estado') == 'vobo_area') {
            $this->objParam->addFiltro("sol.estado_firma  in (''vobo_area'',''vobo_aeronavegabilidad'')or sol.estado  in (''revision'')");
        }
        if ($this->objParam->getParametro('pes_estado') == 'revision') {
            $this->objParam->addFiltro("sol.estado  in (''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'') or sol.estado in (''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''cotizacion_solicitada'',''cotizacion_sin_respuesta'') ");
        }
       if ($this->objParam->getParametro('pes_estado') == 'finalizado') {
            $this->objParam->addFiltro("sol.estado  in (''finalizado'',''anulado'')");
        }
         if ($this->objParam->getParametro('pes_estado') == 'consulta_op') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''borrador'',''vobo_area'',''vobo_aeronavegabilidad'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'',''vobo_almacen'')");
        }if ($this->objParam->getParametro('pes_estado') == 'consulta_mal') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''borrador'',''vobo_area'',''vobo_aeronavegabilidad'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'',''vobo_almacen'')");
        }if ($this->objParam->getParametro('pes_estado') == 'consulta_ab') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''borrador'',''vobo_area'',''vobo_aeronavegabilidad'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'',''vobo_almacen'')");
        }

         if ($this->objParam->getParametro('pes_estado') == 'visto_bueno') {
             $this->objParam->addFiltro("sol.estado_firma  in (''vobo_area'',''vobo_aeronavegabilidad'')");
         }

        if ($this->objParam->getParametro('pes_estado') == 'ab_origen_ing') {
           $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''despachado'',''arribo'',''desaduanizado'')");
        }if ($this->objParam->getParametro('pes_estado') == 'ab_origen_man') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''despachado'',''arribo'',''desaduanizado'')");
        }if ($this->objParam->getParametro('pes_estado') == 'ab_origen_alm') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''despachado'',''arribo'',''desaduanizado'')");
        }
        if ($this->objParam->getParametro('pes_estado') == 'pedido_op_pendiente') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''cotizacion'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_op_solicitada') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''cotizacion_solicitada'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_op_sin_resp') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''cotizacion_sin_respuesta'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_op_compra') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''compra'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_op_concluido') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and sol.estado  in (''finalizado'')");
        }
        if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_pendiente') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''cotizacion'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_solicitada') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''cotizacion_solicitada'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_sin_resp') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''cotizacion_sin_respuesta'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_compra') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''compra'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_ma_concluido') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and sol.estado  in (''finalizado'')");
        }
        if ($this->objParam->getParametro('pes_estado') == 'pedido_al_pendiente') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''cotizacion'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_al_solicitada') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''cotizacion_solicitada'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_al_sin_resp') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''cotizacion_sin_respuesta'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_al_compra') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''compra'')");
        }if ($this->objParam->getParametro('pes_estado') == 'pedido_al_concluido') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and sol.estado  in (''finalizado'')");
        }

        if ($this->objParam->getParametro('pes_estado') == 'almacen') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and  sol.estado  in (''almacen'')");
        }if ($this->objParam->getParametro('pes_estado') == 'origen_al_man') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and  sol.estado in (''almacen'')");
        }if ($this->objParam->getParametro('pes_estado') == 'origen_al_ab') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and  sol.estado  in (''almacen'')");
        }
        if ($this->objParam->getParametro('pes_estado') == 'archivado_ing') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and  sol.estado  in (''finalizado'')");
        }if ($this->objParam->getParametro('pes_estado') == 'archivado_man') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and  sol.estado in (''finalizado'')");
        }if ($this->objParam->getParametro('pes_estado') == 'archivado_alm') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and  sol.estado  in (''finalizado'')");
        }
       
        if ($this->objParam->getParametro('pes_estado') == 'origen_ing') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Operaciones'') and  sol.estado  in (''revision'')");
        }if ($this->objParam->getParametro('pes_estado') == 'origen_man') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Gerencia de Mantenimiento'') and  sol.estado in (''revision'')");
        }if ($this->objParam->getParametro('pes_estado') == 'origen_alm') {
            $this->objParam->addFiltro("sol.origen_pedido  in (''Almacenes Consumibles o Rotables'') and  sol.estado  in (''revision'')");
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
            var_dump($this->res); exit;
        } else{
            //$this->res=$this->objFunc->modificarSolicitud($this->objParam);
            //trabajar en la modificacion compelta de solicitud ....
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->eliminarSolicitud($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function listarMatricula(){
        $this->objParam->defecto('ordenacion','id_orden_trabajo');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_orden_trabajo') != '') {
            $this->objParam->addFiltro(" ord.id_orden_trabajo = " . $this->objParam->getParametro('id_orden_trabajo'));
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

    function siguienteEstadoSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');

        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);

        $this->res=$this->objFunc->siguienteEstadoSolicitud($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function anteriorEstadoSolicitud(){
        $this->objFunc=$this->create('MODSolicitud');
        $this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
        $this->res=$this->objFunc->anteriorEstadoSolicitud($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
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
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos );
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
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos );
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
        $this->objReporteFormato->setDatos($this->res->datos, $this->res2->datos );
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

}

?>