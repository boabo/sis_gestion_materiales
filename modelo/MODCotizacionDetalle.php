<?php
/**
*@package pXP
*@file gen-MODCotizacionDetalle.php
*@author  (miguel.mamani)
*@date 04-07-2017 14:51:54
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCotizacionDetalle extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCotizacionDetalle(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_cotizacion_detalle_sel';
		$this->transaccion='MAT_CDE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setParametro('id_cotizacion','id_cotizacion','int4');
      //  $this->setParametro('id_cotizacion_det','id_cotizacion_det','int4');
		//Definicion de la lista del resultado del query
        $this->capturaCount('total_precio_unitario','numeric');
		$this->capturaCount('total_precio','numeric');
		$this->captura('id_cotizacion_det','int4');
		$this->captura('id_cotizacion','int4');
		$this->captura('id_detalle','int4');
		$this->captura('id_solicitud','int4');
        $this->captura('nro_parte_cot','varchar');
        $this->captura('nro_parte_alterno_cot','varchar');
        $this->captura('referencia_cot','varchar');
        $this->captura('descripcion_cot','varchar');
        $this->captura('explicacion_detallada_part_cot','varchar');
        $this->captura('tipo_cot','varchar');
		$this->captura('cantidad_det','int4');
		$this->captura('precio_unitario','numeric');
        $this->captura('precio_unitario_mb','numeric');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('cd','varchar');
		$this->captura('codigo','varchar');
		$this->captura('codigo_tipo','varchar');
        $this->captura('revisado','varchar');




		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarCotizacionDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_cotizacion_detalle_ime';
		$this->transaccion='MAT_CDE_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cotizacion','id_cotizacion','int4');
		$this->setParametro('id_detalle','id_detalle','int4');
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('cantidad_det','cantidad_det','int4');
		$this->setParametro('precio_unitario','precio_unitario','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');
		//$this->setParametro('precio_unitario_mb','precio_unitario_mb','numeric');
        $this->setParametro('cd','cd','varchar');
        $this->setParametro('id_day_week','id_day_week','int4');
        $this->setParametro('nro_parte_cot','nro_parte_cot','varchar');
        $this->setParametro('nro_parte_alterno_cot','nro_parte_alterno_cot','varchar');
        $this->setParametro('referencia_cot','referencia_cot','varchar');
        $this->setParametro('descripcion_cot','descripcion_cot','varchar');
        $this->setParametro('explicacion_detallada_part_cot','explicacion_detallada_part_cot','varchar');
        $this->setParametro('tipo_cot','tipo_cot','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCotizacionDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_cotizacion_detalle_ime';
		$this->transaccion='MAT_CDE_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cotizacion_det','id_cotizacion_det','int4');
		$this->setParametro('id_cotizacion','id_cotizacion','int4');
		$this->setParametro('id_detalle','id_detalle','int4');
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('cantidad_det','cantidad_det','int4');
		$this->setParametro('precio_unitario','precio_unitario','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('precio_unitario_mb','precio_unitario_mb','numeric');
		$this->setParametro('cd','cd','varchar');
		$this->setParametro('id_day_week','id_day_week','int4');
        $this->setParametro('nro_parte_cot','nro_parte_cot','varchar');
        $this->setParametro('nro_parte_alterno_cot','nro_parte_alterno_cot','varchar');
        $this->setParametro('referencia_cot','referencia_cot','varchar');
        $this->setParametro('descripcion_cot','descripcion_cot','varchar');
        $this->setParametro('explicacion_detallada_part_cot','explicacion_detallada_part_cot','varchar');
        $this->setParametro('tipo_cot','tipo_cot','varchar');



		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCotizacionDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_cotizacion_detalle_ime';
		$this->transaccion='MAT_CDE_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cotizacion_det','id_cotizacion_det','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
    function listarDay_week(){
        $this->procedimiento='mat.ft_cotizacion_detalle_sel';
        $this->transaccion='MAT_CDE_DAY';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->captura('id_day','int4');
        $this->captura('codigo_tipo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;

    }
			
}
?>