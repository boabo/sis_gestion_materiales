<?php
/**
*@package pXP
*@file gen-ACTCotizacion.php
*@author  (miguel.mamani)
*@date 04-07-2017 14:03:30
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../reportes/RCuandroComparativoPDF.php');
class ACTCotizacion extends ACTbase{    
			
	function listarCotizacion(){
		$this->objParam->defecto('ordenacion','id_cotizacion');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCotizacion','listarCotizacion');
		} else{
			$this->objFunc=$this->create('MODCotizacion');
			
			$this->res=$this->objFunc->listarCotizacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCotizacion(){
		$this->objFunc=$this->create('MODCotizacion');	
		if($this->objParam->insertar('id_cotizacion')){
			$this->res=$this->objFunc->insertarCotizacion($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCotizacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCotizacion(){
        $this->objFunc=$this->create('MODCotizacion');
		$this->res=$this->objFunc->eliminarCotizacion($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

    function listarProvedor(){
        $this->objParam->defecto('ordenacion','id_proveedor');
        $this->objParam->defecto('dir_ordenacion','asc');
        /*if ($this->objParam->getParametro('desc_proveedor') != '') {
            $this->objParam->addFiltro("prov.desc_proveedor = ''". $this->objParam->getParametro('desc_proveedor')."''");
        }*/
        $this->objFunc=$this->create('MODCotizacion');
        $this->res=$this->objFunc->listarProveedor($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function controlAdjudicado(){
        $this->objFunc=$this->create('MODCotizacion');
        $this->res=$this->objFunc->controlAdjudicado($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function cuadroComparativo(){
        
        $this->objFunc=$this->create('MODCotizacion');
        $this->res=$this->objFunc->cuadroComparativo($this->objParam);
        $this->objFunc=$this->create('MODCotizacion');
        $this->res2=$this->objFunc->listasFrimas($this->objParam);
        $this->objFunc=$this->create('MODCotizacion');
        $this->res3=$this->objFunc->listaPartes($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Requerimiento de Materiales';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RCuandroComparativoPDF($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos,$this->res2->datos,$this->res3->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }
			
}

?>