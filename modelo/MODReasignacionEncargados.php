<?php
/**
 *@package pXP
 *@file gen-MODReasignacionEncargados.php
 *@author  (admin)
 *@date 14-03-2017 16:18:47
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */
class MODReasignacionEncargados extends MODbase{

    function __construct(CTParametro $pParam){
        parent::__construct($pParam);
    }

    function listarDatos(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_listado_reasignacion';
        $this->transaccion='MAT_REASIGN_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('nro_tramite','varchar');
        $this->captura('estado','varchar');
        $this->captura('id_proceso_wf','int4');
        $this->captura('fecha_solicitud','varchar');
        $this->captura('id_gestion','int4');
        $this->captura('id_estado_wf','int4');
        $this->captura('funcionario_asignado','varchar');
        $this->captura('id_solicitud','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarProcesoMacro(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_listado_reasignacion';
        $this->transaccion='MAT_MACR_LIST_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('id_estado_wf','integer');
        $this->captura('nombre','varchar');
        $this->captura('id_proceso_macro','int4');
        $this->captura('id_subsistema','int4');
        $this->captura('codigo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarEncargados(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_listado_reasignacion';
        $this->transaccion='MAT_ENCAR_LIST_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('id_funcionario','integer');
        $this->captura('desc_funcionario','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function actualizarEncargado(){
      //Definicion de variables para ejecucion del procedimiento
      $this->procedimiento='mat.ft_reasignacion_encargado_ime';
      $this->transaccion='MAT_UDT_ENCAR_INS';
      $this->tipo_procedimiento='IME';

      //Define los parametros para la funcion
      $this->setParametro('id_estado_wf','id_estado_wf','integer');
      $this->setParametro('id_funcionario','id_funcionario','integer');
      $this->setParametro('observacion','observacion','varchar');
      //Ejecuta la instruccion
      $this->armarConsulta();
      $this->ejecutarConsulta();
      //Devuelve la respuesta
      return $this->respuesta;
    }


    function listarLogReasignacion()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_listado_reasignacion';
        $this->transaccion = 'MAT_LOG_REASIG_IME';
        $this->tipo_procedimiento = 'SEL';
				$this->setCount(false);
        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');

				$this->captura('jsonData','text');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
				//var_dump("aqui llega respuesta",$this->respuesta);
        //Devuelve la respuesta
        return $this->respuesta;
    }

}
?>
