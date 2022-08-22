<?php
/**
*@package pXP
*@file gen-ACTLogModificaciones.php
*@author  (ismael.valdivia)
*@date 22-08-2022 12:38:25
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTLogModificaciones extends ACTbase{

	function listarLogModificaciones(){
		$this->objParam->defecto('ordenacion','id_log');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		if($this->objParam->getParametro('id_solicitud') != '' ) {
				$this->objParam->addFiltro("log_tram.id_solicitud = " . $this->objParam->getParametro('id_solicitud'));
		}




		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLogModificaciones','listarLogModificaciones');
		} else{
			$this->objFunc=$this->create('MODLogModificaciones');

			$this->res=$this->objFunc->listarLogModificaciones($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function insertarLogModificaciones(){
		$this->objFunc=$this->create('MODLogModificaciones');
		if($this->objParam->insertar('id_log')){
			$this->res=$this->objFunc->insertarLogModificaciones($this->objParam);
		} else{
			$this->res=$this->objFunc->modificarLogModificaciones($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function eliminarLogModificaciones(){
			$this->objFunc=$this->create('MODLogModificaciones');
		$this->res=$this->objFunc->eliminarLogModificaciones($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function verificarAdjudicado(){
			$this->objFunc=$this->create('MODLogModificaciones');
		$this->res=$this->objFunc->verificarAdjudicado($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

}

?>
