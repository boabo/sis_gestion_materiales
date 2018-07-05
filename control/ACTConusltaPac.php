<?php
/**
*@package pXP
*@file gen-ACTConusltaPac.php
*@author  (miguel.mamani)
*@date 03-07-2018 16:19:47
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTConusltaPac extends ACTbase{    
			
	function listarConusltaPac(){
		$this->objParam->defecto('ordenacion','id_proceso_wf');
		$this->objParam->defecto('dir_ordenacion','asc');
        if ($this->objParam->getParametro('pes_estado') == 'pendientes') {
            $this->objParam->addFiltro("doc.url is null");
        }
        if ($this->objParam->getParametro('pes_estado') == 'revisados') {
            $this->objParam->addFiltro("doc.url is not null");
        }
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODConusltaPac','listarConusltaPac');
		} else{
			$this->objFunc=$this->create('MODConusltaPac');
			
			$this->res=$this->objFunc->listarConusltaPac($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarConusltaPac(){
		$this->objFunc=$this->create('MODConusltaPac');	
		if($this->objParam->insertar('id_proceso_wf')){
			$this->res=$this->objFunc->insertarConusltaPac($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarConusltaPac($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarConusltaPac(){
			$this->objFunc=$this->create('MODConusltaPac');	
		$this->res=$this->objFunc->eliminarConusltaPac($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>