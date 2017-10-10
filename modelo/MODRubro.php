<?php
/**
 *@package pXP
 *@file gen-MODMotivoAnulado.php
 *@author  (admin)
 *@date 12-10-2016 19:36:54
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODRubro extends MODbase{

    function __construct(CTParametro $pParam){
        parent::__construct($pParam);
    }

    function listarRubro(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='prov.ft_rubro_sel';
        $this->transaccion='PROV_RUB_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('id_rubro','int4');
        $this->captura('nombre','varchar');
        $this->captura('descripcion','text');
        $this->captura('estado_reg','varchar');
        $this->captura('id_usuario_reg','int4');
        $this->captura('fecha_reg','timestamp');
        $this->captura('usuario_ai','varchar');
        $this->captura('id_usuario_ai','int4');
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

    function insertarRubro(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='prov.ft_rubro_ime';
        $this->transaccion='PROV_RUB_INS';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('nombre','nombre','varchar');
        $this->setParametro('descripcion','descripcion','text');
        $this->setParametro('estado_reg','estado_reg','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function modificarRubro(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='prov.ft_rubro_ime';
        $this->transaccion='PROV_RUB_MOD';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_rubro','id_rubro','int4');
        $this->setParametro('nombre','nombre','varchar');
        $this->setParametro('descripcion','descripcion','text');
        $this->setParametro('estado_reg','estado_reg','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function eliminarRubro(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='prov.ft_rubro_ime';
        $this->transaccion='PROV_RUB_ELI';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_rubro','id_rubro','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }


    //--------------------------------------------------------------------
    function listarProveedorRubro(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='prov.ft_proveedor_rubro_sel';
        $this->transaccion='PROV_PROVRUB_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query

        $this->setParametro('id_usuario','id_usuario','integer');
        //$this->captura('estado_reg','varchar');
        $this->captura('id_proveedor_rubro','integer');
        $this->captura('id_proveedor','integer');
        $this->captura('id_rubro','integer');

        $this->captura('estado_reg','varchar');
        $this->captura('id_usuario_reg','int4');
        $this->captura('fecha_reg','timestamp');
        $this->captura('usuario_ai','varchar');
        $this->captura('id_usuario_ai','int4');
        $this->captura('fecha_mod','timestamp');
        $this->captura('id_usuario_mod','int4');
        $this->captura('usr_reg','varchar');
        $this->captura('usr_mod','varchar');

        //Ejecuta la funcion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        return $this->respuesta;

    }

    function insertarProveedorRubro(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='segu.ft_usuario_rol_ime';
        $this->transaccion='SEG_USUROL_INS';
        $this->tipo_procedimiento='IME';

        //Define los Parametros para la funcion
        $this->setParametro('id_rol','id_rol','integer');
        $this->setParametro('id_usuario','id_usuario','integer');

        //Ejecuta la instruccion
        $this->armarConsulta();

        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function modificarProveedorRubro(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='segu.ft_usuario_rol_ime';
        $this->transaccion='SEG_USUROL_MOD';
        $this->tipo_procedimiento='IME';

        //Define los setParametros para la funcion

        $this->setParametro('id_usuario_rol','id_usuario_rol','integer');
        $this->setParametro('id_rol','id_rol','integer');
        $this->setParametro('id_usuario','id_usuario','integer');

        //Ejecuta la instruccion
        $this->armarConsulta();

        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function eliminarProveedorRubro(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='segu.ft_usuario_rol_ime';
        $this->transaccion='SEG_USUROL_ELI';
        $this->tipo_procedimiento='IME';

        //Define los setParametros para la funcion
        $this->setParametro('id_usuario_rol','id_usuario_rol','integer');
        //Ejecuta la instruccion
        $this->armarConsulta();

        $this->ejecutarConsulta();
        return $this->respuesta;
    }

}
?>