<?php
/**
*@package pXP
*@file gen-ACTSolicitudMayor500000.php
*@author  (admin)
*@date 05-09-2017 15:19:59
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTSolicitudMayor500000 extends ACTbase{    
			
	function listarSolicitudMayor500000(){
		$this->objParam->defecto('ordenacion','id_sol');

        if($this->objParam->getParametro('desde')!='' && $this->objParam->getParametro('hasta')!=''){
            $this->objParam->addFiltro("(smi.fecha_po::date  BETWEEN ''%".$this->objParam->getParametro('desde')."%''::date  and ''%".$this->objParam->getParametro('hasta')."%''::date)");
        }

        if($this->objParam->getParametro('desde')!='' && $this->objParam->getParametro('hasta')==''){
            $this->objParam->addFiltro("(smi.fecha_po::date  >= ''%".$this->objParam->getParametro('desde')."%''::date)");
        }

        if($this->objParam->getParametro('desde')=='' && $this->objParam->getParametro('hasta')!=''){
            $this->objParam->addFiltro("(smi.fecha_po::date  <= ''%".$this->objParam->getParametro('hasta')."%''::date)");
        }


        $this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSolicitudMayor500000','listarSolicitudMayor500000');
		} else{
			$this->objFunc=$this->create('MODSolicitudMayor500000');
			
			$this->res=$this->objFunc->listarSolicitudMayor500000($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarSolicitudMayor500000(){
		$this->objFunc=$this->create('MODSolicitudMayor500000');	
		if($this->objParam->insertar('id_sol')){
			$this->res=$this->objFunc->insertarSolicitudMayor500000($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarSolicitudMayor500000($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarSolicitudMayor500000(){
			$this->objFunc=$this->create('MODSolicitudMayor500000');	
		$this->res=$this->objFunc->eliminarSolicitudMayor500000($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>