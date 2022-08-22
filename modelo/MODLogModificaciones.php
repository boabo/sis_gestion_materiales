<?php
/**
*@package pXP
*@file gen-MODLogModificaciones.php
*@author  (ismael.valdivia)
*@date 22-08-2022 12:38:25
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODLogModificaciones extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}

	function listarLogModificaciones(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_log_modificaciones_sel';
		$this->transaccion='MAT_LOG_TRAM_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//Definicion de la lista del resultado del query
		$this->captura('id_log','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_funcionario_solicitante','int4');
		$this->captura('motivo_modificacion','text');
		$this->captura('nro_po_anterior','varchar');
		$this->captura('nro_po_nuevo','varchar');
		$this->captura('fecha_cotizacion_antigua','date');
		$this->captura('fecha_cotizacion_nueva','date');
		$this->captura('nro_cotizacion_anterior','varchar');
		$this->captura('nro_cotizacion_nueva','varchar');
		$this->captura('id_cotizacion','int4');
		$this->captura('id_solicitud','int4');
		$this->captura('fecha_modificacion','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');

		$this->captura('desc_funcionario_solicitante','varchar');
		$this->captura('nro_tramite','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function insertarLogModificaciones(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_log_modificaciones_ime';
		$this->transaccion='MAT_LOG_TRAM_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_funcionario_solicitante','id_funcionario_solicitante','int4');
		$this->setParametro('motivo_modificacion','motivo_modificacion','text');
		$this->setParametro('nro_po_anterior','nro_po_anterior','varchar');
		$this->setParametro('nro_po_nuevo','nro_po_nuevo','varchar');
		$this->setParametro('fecha_cotizacion_antigua','fecha_cotizacion_antigua','date');
		$this->setParametro('fecha_cotizacion_nueva','fecha_cotizacion_nueva','date');
		$this->setParametro('nro_cotizacion_anterior','nro_cotizacion_anterior','varchar');
		$this->setParametro('nro_cotizacion_nueva','nro_cotizacion_nueva','varchar');
		$this->setParametro('id_cotizacion','id_cotizacion','int4');
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('fecha_modificacion','fecha_modificacion','timestamp');

		$this->setParametro('interfaz_origen','interfaz_origen','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function modificarLogModificaciones(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_log_modificaciones_ime';
		$this->transaccion='MAT_LOG_TRAM_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_log','id_log','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_funcionario_solicitante','id_funcionario_solicitante','int4');
		$this->setParametro('motivo_modificacion','motivo_modificacion','text');
		$this->setParametro('nro_po_anterior','nro_po_anterior','varchar');
		$this->setParametro('nro_po_nuevo','nro_po_nuevo','varchar');
		$this->setParametro('fecha_cotizacion_antigua','fecha_cotizacion_antigua','date');
		$this->setParametro('fecha_cotizacion_nueva','fecha_cotizacion_nueva','date');
		$this->setParametro('nro_cotizacion_anterior','nro_cotizacion_anterior','varchar');
		$this->setParametro('nro_cotizacion_nueva','nro_cotizacion_nueva','varchar');
		$this->setParametro('id_cotizacion','id_cotizacion','int4');
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('fecha_modificacion','fecha_modificacion','timestamp');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function eliminarLogModificaciones(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_log_modificaciones_ime';
		$this->transaccion='MAT_LOG_TRAM_ELI';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_log','id_log','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}


	function verificarAdjudicado(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_log_modificaciones_ime';
		$this->transaccion='MAT_VERI_ADJU_IME';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_solicitud','id_solicitud','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

}
?>
