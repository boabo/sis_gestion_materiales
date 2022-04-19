<?php
/**
*@package pXP
*@file gen-ACTReasignacionEncargados.php
*@author  (admin)
*@date 14-03-2017 16:18:47
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTReasignacionEncargados extends ACTbase{

	function listarDatos(){
		$this->objParam->defecto('ordenacion','id_solicitud');

		if($this->objParam->getParametro('id_gestion') != '' ) {
				$this->objParam->addFiltro("sol.id_gestion = " . $this->objParam->getParametro('id_gestion'));
		}

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODReasignacionEncargados','listarDatos');
		} else{
			$this->objFunc=$this->create('MODReasignacionEncargados');

			$this->res=$this->objFunc->listarDatos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarProcesoMacro(){

		if($this->objParam->getParametro('id_proceso_wf') != '' ) {
				$this->objParam->addFiltro("est.id_proceso_wf = " . $this->objParam->getParametro('id_proceso_wf'));
		}



		$this->objFunc=$this->create('MODReasignacionEncargados');
		$this->res=$this->objFunc->listarProcesoMacro($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarEncargados(){

		if($this->objParam->getParametro('id_proceso_macro') != '' ) {
				$this->objParam->addFiltro("pm.id_proceso_macro = " . $this->objParam->getParametro('id_proceso_macro'));
		}


		$this->objFunc=$this->create('MODReasignacionEncargados');
		$this->res=$this->objFunc->listarEncargados($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function actualizarEncargado(){
		$this->objFunc=$this->create('MODReasignacionEncargados');
		$this->res=$this->objFunc->actualizarEncargado($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarLogReasignacion(){
		$this->objFunc=$this->create('MODReasignacionEncargados');
		$this->res=$this->objFunc->listarLogReasignacion($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}



}

?>
