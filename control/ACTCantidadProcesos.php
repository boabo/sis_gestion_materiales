<?php
/**
*@package pXP
*@file gen-ACTCantidadProcesos.php
*@author  (admin)
*@date 20-09-2015 19:11:44
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTCantidadProcesos extends ACTbase{

	function listarFuncionariosAsignados(){
		$this->objParam->defecto('ordenacion','id_entidad');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCantidadProcesos','listarFuncionariosAsignados');
		} else{
			$this->objFunc=$this->create('MODCantidadProcesos');

			$this->res=$this->objFunc->listarFuncionariosAsignados($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

}

?>
