<?php
/**
*@package pXP
*@file gen-MODConusltaPac.php
*@author  (miguel.mamani)
*@date 03-07-2018 16:19:47
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODConusltaPac extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarConusltaPac(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_conuslta_pac_sel';
		$this->transaccion='MAT_CPA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_solicitud_pac','int4');
		$this->captura('id_proceso_wf','int4');
        $this->captura('id_solicitud','int4');
        $this->captura('nro_tramite','varchar');
		$this->captura('desc_funcionario1','text');
        $this->captura('origen_pedido','varchar');
        $this->captura('monto','numeric');
		$this->captura('codigo_internacional','varchar');
        $this->captura('estado','varchar');
		$this->captura('tipo_solicitud','varchar');
        $this->captura('fecha_solicitud','date');
        $this->captura('fecha_requerida','date');
        $this->captura('motivo_solicitud','varchar');
		$this->captura('observaciones_sol','varchar');
        
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarConusltaPac(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_conuslta_pac_ime';
		$this->transaccion='MAT_CPA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('desc_funcionario1','desc_funcionario1','text');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('codigo_internacional','codigo_internacional','varchar');
		$this->setParametro('tipo_solicitud','tipo_solicitud','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('monto','monto','numeric');
		$this->setParametro('origen_pedido','origen_pedido','varchar');
		$this->setParametro('observaciones_sol','observaciones_sol','varchar');
		$this->setParametro('fecha_requerida','fecha_requerida','date');
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('fecha_solicitud','fecha_solicitud','date');
		$this->setParametro('motivo_solicitud','motivo_solicitud','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarConusltaPac(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_conuslta_pac_ime';
		$this->transaccion='MAT_CPA_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_proceso_wf','id_proceso_wf','int4');
		$this->setParametro('desc_funcionario1','desc_funcionario1','text');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('codigo_internacional','codigo_internacional','varchar');
		$this->setParametro('tipo_solicitud','tipo_solicitud','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('monto','monto','numeric');
		$this->setParametro('origen_pedido','origen_pedido','varchar');
		$this->setParametro('observaciones_sol','observaciones_sol','varchar');
		$this->setParametro('fecha_requerida','fecha_requerida','date');
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('fecha_solicitud','fecha_solicitud','date');
		$this->setParametro('motivo_solicitud','motivo_solicitud','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarConusltaPac(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_conuslta_pac_ime';
		$this->transaccion='MAT_CPA_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_proceso_wf','id_proceso_wf','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>