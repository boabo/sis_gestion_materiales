<?php
/**
*@package pXP
*@file gen-ACTCotizacionDetalle.php
*@author  (miguel.mamani)
*@date 04-07-2017 14:51:54
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTCotizacionDetalle extends ACTbase{    
			
	function listarCotizacionDetalle(){
		$this->objParam->defecto('ordenacion','id_cotizacion_det');
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCotizacionDetalle','listarCotizacionDetalle');
		} else{
			$this->objFunc=$this->create('MODCotizacionDetalle');
			
			$this->res=$this->objFunc->listarCotizacionDetalle($this->objParam);
		}
        $temp = Array();
        $temp['nro_parte_alterno_cot'] = 'TOTAL';
        $temp['precio_unitario'] = $this->res->extraData['total_precio_unitario'];
        $temp['precio_unitario_mb'] = $this->res->extraData['total_precio'];
        $temp['tipo_reg'] = 'summary';
        $temp['id_cotizacion_det'] = 0;

        $this->res->total++;

        $this->res->addLastRecDatos($temp);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCotizacionDetalle(){
		$this->objFunc=$this->create('MODCotizacionDetalle');	
		if($this->objParam->insertar('id_cotizacion_det')){
			$this->res=$this->objFunc->insertarCotizacionDetalle($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCotizacionDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCotizacionDetalle(){
        $this->objFunc=$this->create('MODCotizacionDetalle');
		$this->res=$this->objFunc->eliminarCotizacionDetalle($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    function listarDay_week(){
        $this->objFunc=$this->create('MODCotizacionDetalle');
        $this->res=$this->objFunc->listarDay_week($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
			
}

?>