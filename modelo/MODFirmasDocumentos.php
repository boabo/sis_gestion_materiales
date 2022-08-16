<?php
/**
*@package pXP
*@file gen-MODFirmasDocumentos.php
*@author  (ismael.valdivia)
*@date 16-08-2022 13:50:13
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFirmasDocumentos extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}

	function listarFirmasDocumentos(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_firmas_documentos_sel';
		$this->transaccion='MAT_FIRM_DOC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//Definicion de la lista del resultado del query
		$this->captura('id_firma_documento','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_inicio','date');
		$this->captura('fecha_fin','date');
		$this->captura('id_funcionario','int4');
		$this->captura('tipo_firma','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('motivo_asignacion','varchar');
		$this->captura('tipo_documento','varchar');
		$this->captura('desc_funcionario1','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function insertarFirmasDocumentos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_firmas_documentos_ime';
		$this->transaccion='MAT_FIRM_DOC_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_inicio','fecha_inicio','date');
		$this->setParametro('fecha_fin','fecha_fin','date');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('tipo_firma','tipo_firma','varchar');
		$this->setParametro('motivo_asignacion','motivo_asignacion','varchar');
		$this->setParametro('tipo_documento','tipo_documento','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function modificarFirmasDocumentos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_firmas_documentos_ime';
		$this->transaccion='MAT_FIRM_DOC_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_firma_documento','id_firma_documento','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_inicio','fecha_inicio','date');
		$this->setParametro('fecha_fin','fecha_fin','date');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('tipo_firma','tipo_firma','varchar');
		$this->setParametro('motivo_asignacion','motivo_asignacion','varchar');
		$this->setParametro('tipo_documento','tipo_documento','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function eliminarFirmasDocumentos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_firmas_documentos_ime';
		$this->transaccion='MAT_FIRM_DOC_ELI';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_firma_documento','id_firma_documento','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

}
?>
