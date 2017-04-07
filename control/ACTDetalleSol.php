<?php
/**
*@package pXP
*@file gen-ACTDetalleSol.php
*@author  (admin)
*@date 23-12-2016 13:13:01
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTDetalleSol extends ACTbase{    
			
	function listarDetalleSol(){
		$this->objParam->defecto('ordenacion','id_detalle');
        $this->objParam->defecto('dir_ordenacion','asc');
       if($this->objParam->getParametro('id_solicitud') != '') {
            $this->objParam->addFiltro(" det.id_solicitud = " . $this->objParam->getParametro('id_solicitud'));
        }

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODDetalleSol','listarDetalleSol');
		} else{
			$this->objFunc=$this->create('MODDetalleSol');
			
			$this->res=$this->objFunc->listarDetalleSol($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

    function insertarDetalleSol(){
		$this->objFunc=$this->create('MODDetalleSol');	
		if($this->objParam->insertar('id_detalle')){
			$this->res=$this->objFunc->insertarDetalleSol($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarDetalleSol($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarDetalleSol(){
			$this->objFunc=$this->create('MODDetalleSol');	
		$this->res=$this->objFunc->eliminarDetalleSol($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function unidadMedia(){
        $this->objParam->defecto('ordenacion','id_unidad_medida');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_unidad_medida') != '') {
            $this->objParam->addFiltro(" un.id_unidad_medida = " . $this->objParam->getParametro('id_unidad_medida'));
        }

        $this->objFunc=$this->create('MODDetalleSol');
        $this->res=$this->objFunc->listarUnidadMedida($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function cambiarRevision(){
        $this->objFunc=$this->create('MODDetalleSol');
        $this->res=$this->objFunc->cambiarRevision($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());

    }
    function controlPartes(){

        $this->objFunc=$this->create('MODDetalleSol');
        $this->res=$this->objFunc->controlPartes($this->objParam);
        //var_dump($this->res); exit;
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
}

?>