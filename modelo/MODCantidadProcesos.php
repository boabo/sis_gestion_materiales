<?php
/**
*@package pXP
*@file gen-MODEntidad.php
*@author  (admin)
*@date 20-09-2015 19:11:44
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCantidadProcesos extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}

	function listarFuncionariosAsignados(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_procesos_asignados_sel';
		$this->transaccion='MAT_PROSC_ASIG_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//Definicion de la lista del resultado del query
		$this->captura('id_funcionario','int4');
		$this->captura('desc_funcionario1','varchar');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

}
?>
