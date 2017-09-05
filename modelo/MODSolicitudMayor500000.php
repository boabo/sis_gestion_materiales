<?php
/**
*@package pXP
*@file gen-MODSolicitudMayor500000.php
*@author  (admin)
*@date 05-09-2017 15:19:59
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODSolicitudMayor500000 extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarSolicitudMayor500000(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_solicitud_mayor_500000_sel';
		$this->transaccion='MAT_SMI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_solicitud','int4');
		$this->captura('id_proceso_wf','int4');
		$this->captura('nro_po','varchar');
		$this->captura('fecha_solicitud','date');
		$this->captura('funcionario','text');
		$this->captura('fecha_po','date');
		$this->captura('monto_dolares','numeric');
		$this->captura('nro_tramite','varchar');
		$this->captura('proveedor','varchar');
		$this->captura('monto_bolivianos','numeric');

		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarSolicitudMayor500000(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_solicitud_mayor_500000_ime';
		$this->transaccion='MAT_SMI_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('nro_po','nro_po','varchar');
		$this->setParametro('fecha_solicitud','fecha_solicitud','date');
		$this->setParametro('funcionario','funcionario','text');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_po','fecha_po','date');
		$this->setParametro('monto_dolares','monto_dolares','numeric');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('proveedor','proveedor','varchar');
		$this->setParametro('monto_bolivianos','monto_bolivianos','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarSolicitudMayor500000(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_solicitud_mayor_500000_ime';
		$this->transaccion='MAT_SMI_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sol','id_sol','int4');
		$this->setParametro('nro_po','nro_po','varchar');
		$this->setParametro('fecha_solicitud','fecha_solicitud','date');
		$this->setParametro('funcionario','funcionario','text');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_po','fecha_po','date');
		$this->setParametro('monto_dolares','monto_dolares','numeric');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('proveedor','proveedor','varchar');
		$this->setParametro('monto_bolivianos','monto_bolivianos','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarSolicitudMayor500000(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_solicitud_mayor_500000_ime';
		$this->transaccion='MAT_SMI_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sol','id_sol','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>