<?php
/**
*@package pXP
*@file gen-MODDetalleSol.php
*@author  (admin)
*@date 23-12-2016 13:13:01
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODDetalleSol extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarDetalleSol(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_detalle_sol_sel';
		$this->transaccion='MAT_DET_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_detalle','int4');
		$this->captura('id_solicitud','int4');
		$this->captura('descripcion','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_unidad_medida','int4');
		$this->captura('nro_parte','varchar');
		$this->captura('referencia','varchar');
		$this->captura('nro_parte_alterno','varchar');
		$this->captura('id_moneda','int4');
		$this->captura('precio','numeric');
		$this->captura('cantidad_sol','numeric');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('codigo','varchar');
        $this->captura('desc_descripcion','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//var_dump($this->respuesta); exit;
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarDetalleSol(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_detalle_sol_ime';
		$this->transaccion='MAT_DET_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('nro_parte','nro_parte','varchar');
		$this->setParametro('referencia','referencia','varchar');
		$this->setParametro('nro_parte_alterno','nro_parte_alterno','varchar');
		//$this->setParametro('id_moneda','id_moneda','int4');
		//$this->setParametro('precio','precio','numeric');
		$this->setParametro('cantidad_sol','cantidad_sol','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarDetalleSol(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_detalle_sol_ime';
		$this->transaccion='MAT_DET_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_detalle','id_detalle','int4');
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('nro_parte','nro_parte','varchar');
		$this->setParametro('referencia','referencia','varchar');
		$this->setParametro('nro_parte_alterno','nro_parte_alterno','varchar');
		//$this->setParametro('id_moneda','id_moneda','int4');
		//$this->setParametro('precio','precio','numeric');
		$this->setParametro('cantidad_sol','cantidad_sol','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarDetalleSol(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_detalle_sol_ime';
		$this->transaccion='MAT_DET_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_detalle','id_detalle','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function listarUnidadMedida(){

        $this->procedimiento ='mat.ft_detalle_sol_sel';
        $this->transaccion='MAT_UM_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->captura('id_unidad_medida','int4');
        $this->captura('codigo','varchar');
        $this->captura('descripcion','varchar');
        $this->captura('tipo_unidad_medida','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;

    }

}
?>