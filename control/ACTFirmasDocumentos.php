<?php
/**
*@package pXP
*@file gen-ACTFirmasDocumentos.php
*@author  (ismael.valdivia)
*@date 16-08-2022 13:50:13
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTFirmasDocumentos extends ACTbase{    
			
	function listarFirmasDocumentos(){
		$this->objParam->defecto('ordenacion','id_firma_documento');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODFirmasDocumentos','listarFirmasDocumentos');
		} else{
			$this->objFunc=$this->create('MODFirmasDocumentos');
			
			$this->res=$this->objFunc->listarFirmasDocumentos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarFirmasDocumentos(){
		$this->objFunc=$this->create('MODFirmasDocumentos');	
		if($this->objParam->insertar('id_firma_documento')){
			$this->res=$this->objFunc->insertarFirmasDocumentos($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarFirmasDocumentos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarFirmasDocumentos(){
			$this->objFunc=$this->create('MODFirmasDocumentos');	
		$this->res=$this->objFunc->eliminarFirmasDocumentos($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>