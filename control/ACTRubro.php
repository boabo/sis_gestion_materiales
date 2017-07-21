<?php
/**
 *@package pXP
 *@file gen-ACTMotivoAnulado.php
 *@author  (admin)
 *@date 12-10-2016 19:36:54
 *@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

class ACTRubro extends ACTbase{

    function listarRubro(){
        
        $this->objParam->defecto('ordenacion','id_rubro');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODRubro','listarRubro');
        } else{
            $this->objFunc=$this->create('MODRubro');

            $this->res=$this->objFunc->listarRubro($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarRubro(){
        $this->objFunc=$this->create('MODRubro');
        if($this->objParam->insertar('id_rubro')){
            $this->res=$this->objFunc->insertarRubro($this->objParam);
        } else{
            $this->res=$this->objFunc->modificarRubro($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarRubro(){
        $this->objFunc=$this->create('MODRubro');
        $this->res=$this->objFunc->eliminarRubro($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    //------------------------------------------------------------------------------------------------------------------
    
    function listarProveedorRubro(){

        /*$this->objParam->defecto('ordenacion','id_informe');
        $this->objParam->defecto('dir_ordenacion','asc');*/

        /*if($this->objParam->getParametro('id_reclamo') != '') {
            if ($this->objParam->getParametro('id_informe') != null || $this->objParam->getParametro('id_informe') != ''){
                $this->objParam->addFiltro("(infor.id_reclamo = ".$this->objParam->getParametro('id_reclamo')." or infor.id_informe = ".$this->objParam->getParametro('id_informe').")");
            }else{
                $this->objParam->addFiltro("infor.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
            }

        }*/

        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODRubro','listarProveedorRubro');
        }else{
            $this->objFunc=$this->create('MODRubro');

            $this->res=$this->objFunc->listarProveedorRubro($this->objParam);
        }

        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarProveedorRubro(){
        $this->objFunc=$this->create('MODRubro');
        if($this->objParam->insertar('id_proveedor_rubro')){
            $this->res=$this->objFunc->insertarProveedorRubro($this->objParam);
        } else{
            $this->res=$this->objFunc->modificarProveedorRubro($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarProveedorRubro(){
        $this->objFunc=$this->create('MODRubro');
        $this->res=$this->objFunc->eliminarProveedorRubro($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

}

?>