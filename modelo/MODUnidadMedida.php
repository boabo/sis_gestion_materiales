<?php
/**
*@package pXP
*@file gen-MODUnidadMedida.php
*@author  (admin)
*@date 14-03-2017 16:18:47
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODUnidadMedida extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarUnidadMedida(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_unidad_medida_sel';
		$this->transaccion='MAT_UDM_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_unidad_medida','int4');
		$this->captura('codigo','varchar');
		$this->captura('descripcion','varchar');
		$this->captura('tipo_unidad_medida','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarUnidadMedida(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_unidad_medida_ime';
		$this->transaccion='MAT_UDM_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('tipo_unidad_medida','tipo_unidad_medida','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarUnidadMedida(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_unidad_medida_ime';
		$this->transaccion='MAT_UDM_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('tipo_unidad_medida','tipo_unidad_medida','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarUnidadMedida(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_unidad_medida_ime';
		$this->transaccion='MAT_UDM_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>