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
class ACTSolicitud extends ACTbase{

    function listarSolicitud(){
        $this->objParam->defecto('ordenacion','id_solicitud');

        $this->objParam->defecto('dir_ordenacion','asc');

        if ($this->objParam->getParametro('pes_estado') == 'borrador') {
            $this->objParam->addFiltro("sol.estado  in (''borrador'')");
        }
        else if ($this->objParam->getParametro('pes_estado') == 'vobo_area') {
            $this->objParam->addFiltro("sol.estado  in (''vobo_area'')");
        }
       else if ($this->objParam->getParametro('pes_estado') == 'revision') {
            $this->objParam->addFiltro("sol.estado  in (''vobo_aeronavegabilidad'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'')");
        }
      else  if ($this->objParam->getParametro('pes_estado') == 'finalizado') {
            $this->objParam->addFiltro("sol.estado  in (''finalizado'',''anulado'')");
        }
        else if ($this->objParam->getParametro('pes_estado') == 'consulta') {
            $this->objParam->addFiltro("sol.estado  in (''borrador'',''vobo_area'',''vobo_aeronavegabilidad'',''revision'',''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'',''finalizado'',''anulado'')");
        }
       else  if ($this->objParam->getParametro('pes_estado') == 'visto_bueno') {
            $this->objParam->addFiltro("sol.estado  in (''vobo_area'',''vobo_aeronavegabilidad'',''revision'')");
        }else  if ($this->objParam->getParametro('pes_estado') == 'abastecimiento') {
           $this->objParam->addFiltro("sol.estado  in (''cotizacion'',''compra'',''despachado'',''arribo'',''desaduanizado'',''almacen'')");
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
    function reporteRequerimientoIng (){
        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listarRequerimiento($this->objParam);
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
        $this->objReporteFormato->setDatos($this->res->datos);
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
        $this->objReporteFormato->setDatos($this->res->datos);
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
        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RRequemientoMaterielesAlm($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }

    function compararNroOrigen(){

        $this->objFunc=$this->create('MODSolicitud');
        $this->res=$this->objFunc->listaNroOrigen($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

}

?>