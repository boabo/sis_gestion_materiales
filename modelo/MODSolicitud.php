<?php
/**
 *@package pXP
 *@file gen-MODSolicitud.php
 *@author  (admin)
 *@date 23-12-2016 13:12:58
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODSolicitud extends MODbase{

    function __construct(CTParametro $pParam){
        parent::__construct($pParam);
    }

    function listarSolicitud(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_solicitud_sel';
        $this->transaccion='MAT_SOL_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setParametro('pes_estado','pes_estado','varchar');
        $this->setParametro('tipo_interfaz','tipo_interfaz','varchar');
        $this->setParametro('id_usuario','id_usuario','int4');
        $this->setParametro('fill','fill','varchar');
        //Definicion de la lista del resultado del query
        $this->captura('id_solicitud','int4');
        $this->captura('id_funcionario_sol','int4');
        $this->captura('id_proveedor','int4');
        $this->captura('id_proceso_wf','int4');
        $this->captura('id_estado_wf','int4');
        $this->captura('nro_po','varchar');
        $this->captura('tipo_solicitud','varchar');
        $this->captura('fecha_entrega_miami','date');
        $this->captura('origen_pedido','varchar');
        $this->captura('fecha_requerida','date');
        $this->captura('observacion_nota','text');
        $this->captura('fecha_solicitud','date');
        $this->captura('estado_reg','varchar');
        $this->captura('observaciones_sol','varchar');
        $this->captura('fecha_tentativa_llegada','date');
        $this->captura('fecha_despacho_miami','date');
        $this->captura('justificacion','varchar');
        $this->captura('fecha_arribado_bolivia','date');
        $this->captura('fecha_desaduanizacion','date');
        $this->captura('fecha_entrega_almacen','date');
        $this->captura('cotizacion','numeric');
        $this->captura('tipo_falla','varchar');
        $this->captura('nro_tramite','varchar');
        $this->captura('id_matricula','int4');
        $this->captura('nro_solicitud','varchar');
        $this->captura('motivo_solicitud','varchar');
        $this->captura('fecha_en_almacen','date');
        $this->captura('estado','varchar');
        $this->captura('id_usuario_reg','int4');
        $this->captura('usuario_ai','varchar');
        $this->captura('fecha_reg','timestamp');
        $this->captura('id_usuario_ai','int4');
        $this->captura('fecha_mod','timestamp');
        $this->captura('id_usuario_mod','int4');
        $this->captura('usr_reg','varchar');
        $this->captura('usr_mod','varchar');
        $this->captura('desc_funcionario1','text');
        $this->captura('matricula','varchar');


        $this->captura('tipo_reporte','varchar');
        $this->captura('mel','varchar');
        $this->captura('nro_no_rutina','varchar');
        $this->captura('desc_proveedor','varchar');
        $this->captura('nro_parte','varchar');
        $this->captura('nro_justificacion','varchar');
        $this->captura('fecha_cotizacion','date');
        $this->captura('contador_estados','bigint');

        $this->captura('control_fecha','varchar');
        $this->captura('estado_firma','varchar');
        $this->captura('id_proceso_wf_firma','int4');
        $this->captura('id_estado_wf_firma','int4');
        $this->captura('contador_estados_firma','bigint');
        $this->captura('nombre_estado','varchar');
        $this->captura('nombre_estado_firma','varchar');
        $this->captura('fecha_po','date');
        $this->captura('tipo_evaluacion','varchar');
        $this->captura('taller_asignado','varchar');
        $this->captura('lista_correos','varchar');
        $this->captura('condicion','varchar');
        $this->captura('lugar_entrega','varchar');
        $this->captura('mensaje_correo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        //var_dump($this->consulta); exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function insertarSolicitud(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_SOL_INS';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_funcionario_sol','id_funcionario_sol','int4');
        $this->setParametro('id_proveedor','id_proveedor','int4');
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('nro_po','nro_po','varchar');
        $this->setParametro('tipo_solicitud','tipo_solicitud','varchar');
        $this->setParametro('fecha_entrega_miami','fecha_entrega_miami','date');
        $this->setParametro('origen_pedido','origen_pedido','varchar');
        $this->setParametro('fecha_requerida','fecha_requerida','date');
        $this->setParametro('observacion_nota','observacion_nota','text');
        $this->setParametro('fecha_solicitud','fecha_solicitud','date');
        $this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('observaciones_sol','observaciones_sol','varchar');
        $this->setParametro('fecha_tentativa_llegada','fecha_tentativa_llegada','date');
        $this->setParametro('fecha_despacho_miami','fecha_despacho_miami','date');
        $this->setParametro('justificacion','justificacion','varchar');
        $this->setParametro('fecha_arribado_bolivia','fecha_arribado_bolivia','date');
        $this->setParametro('fecha_desaduanizacion','fecha_desaduanizacion','date');
        $this->setParametro('fecha_entrega_almacen','fecha_entrega_almacen','date');
        $this->setParametro('cotizacion','cotizacion','numeric');
        $this->setParametro('tipo_falla','tipo_falla','varchar');
        $this->setParametro('nro_tramite','nro_tramite','varchar');
        $this->setParametro('id_matricula','id_matricula','int4');
        $this->setParametro('nro_solicitud','nro_solicitud','varchar');
        $this->setParametro('motivo_solicitud','motivo_solicitud','varchar');
        $this->setParametro('fecha_en_almacen','fecha_en_almacen','date');
        $this->setParametro('estado','estado','varchar');

        $this->setParametro('tipo_reporte','tipo_reporte','varchar');
        $this->setParametro('mel','mel','varchar');
        $this->setParametro('nro_no_rutina','nro_no_rutina','varchar');
        $this->setParametro('nro_justificacion','nro_justificacion','varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function modificarSolicitud(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_SOL_MOD';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud','id_solicitud','int4');
        $this->setParametro('id_funcionario_sol','id_funcionario_sol','int4');
        $this->setParametro('id_proveedor','id_proveedor','int4');
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('nro_po','nro_po','varchar');
        $this->setParametro('tipo_solicitud','tipo_solicitud','varchar');
        $this->setParametro('fecha_entrega_miami','fecha_entrega_miami','date');
        $this->setParametro('origen_pedido','origen_pedido','varchar');
        $this->setParametro('fecha_requerida','fecha_requerida','date');
        $this->setParametro('observacion_nota','observacion_nota','text');
        $this->setParametro('fecha_solicitud','fecha_solicitud','date');
        $this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('observaciones_sol','observaciones_sol','varchar');
        $this->setParametro('fecha_tentativa_llegada','fecha_tentativa_llegada','date');
        //$this->setParametro('fecha_despacho_miami','fecha_despacho_miami','date');
        $this->setParametro('fecha_cotizacion','fecha_cotizacion','date');
        $this->setParametro('justificacion','justificacion','varchar');
        $this->setParametro('fecha_arribado_bolivia','fecha_arribado_bolivia','date');
        $this->setParametro('fecha_desaduanizacion','fecha_desaduanizacion','date');
        $this->setParametro('fecha_entrega_almacen','fecha_entrega_almacen','date');
        $this->setParametro('cotizacion','cotizacion','numeric');
        $this->setParametro('tipo_falla','tipo_falla','varchar');
        $this->setParametro('nro_tramite','nro_tramite','varchar');
        $this->setParametro('id_matricula','id_matricula','int4');
        $this->setParametro('nro_solicitud','nro_solicitud','varchar');
        $this->setParametro('motivo_solicitud','motivo_solicitud','varchar');
        $this->setParametro('fecha_en_almacen','fecha_en_almacen','date');
        $this->setParametro('estado','estado','varchar');

        $this->setParametro('tipo_reporte','tipo_reporte','varchar');
        $this->setParametro('mel','mel','varchar');
        $this->setParametro('nro_no_rutina','nro_no_rutina','varchar');
        $this->setParametro('fecha_po','fecha_po','date');
        $this->setParametro('tipo_evaluacion','tipo_evaluacion','varchar');
        $this->setParametro('taller_asignado','taller_asignado','varchar');

        $this->setParametro('condicion','condicion','varchar');
        $this->setParametro('lugar_entrega','lugar_entrega','varchar');
        $this->setParametro('mensaje_correo','mensaje_correo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function eliminarSolicitud(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_SOL_ELI';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud','id_solicitud','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function insertarSolicitudCompleta(){

        //Abre conexion con PDO
        $cone = new conexion();
        $link = $cone->conectarpdo();
        $copiado = false;
        try {
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $link->beginTransaction();
            //  inserta cabecera
            //Definicion de variables para ejecucion del procedimiento
            $this->procedimiento='mat.ft_solicitud_ime';
            $this->transaccion='MAT_SOL_INS';
            $this->tipo_procedimiento='IME';

            //Define los parametros para la funcion
            $this->setParametro('id_funcionario_sol','id_funcionario_sol','int4');
            $this->setParametro('id_proveedor','id_proveedor','int4');
            $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
            $this->setParametro('id_estado_wf','id_estado_wf','int4');
            $this->setParametro('nro_po','nro_po','varchar');
            $this->setParametro('tipo_solicitud','tipo_solicitud','varchar');
            $this->setParametro('fecha_entrega_miami','fecha_entrega_miami','date');
            $this->setParametro('origen_pedido','origen_pedido','varchar');
            $this->setParametro('fecha_requerida','fecha_requerida','date');
            $this->setParametro('observacion_nota','observacion_nota','text');
            $this->setParametro('fecha_solicitud','fecha_solicitud','date');
            $this->setParametro('estado_reg','estado_reg','varchar');
            $this->setParametro('observaciones_sol','observaciones_sol','varchar');
            $this->setParametro('fecha_tentativa_llegada','fecha_tentativa_llegada','date');
            $this->setParametro('fecha_despacho_miami','fecha_despacho_miami','date');
            $this->setParametro('justificacion','justificacion','varchar');
            $this->setParametro('fecha_arribado_bolivia','fecha_arribado_bolivia','date');
            $this->setParametro('fecha_desaduanizacion','fecha_desaduanizacion','date');
            $this->setParametro('fecha_entrega_almacen','fecha_entrega_almacen','date');
            $this->setParametro('cotizacion','cotizacion','numeric');
            $this->setParametro('tipo_falla','tipo_falla','varchar');
            $this->setParametro('nro_tramite','nro_tramite','varchar');
            $this->setParametro('id_matricula','id_matricula','int4');
            $this->setParametro('nro_solicitud','nro_solicitud','varchar');
            $this->setParametro('motivo_solicitud','motivo_solicitud','varchar');
            $this->setParametro('fecha_en_almacen','fecha_en_almacen','date');
            $this->setParametro('estado','estado','varchar');
            $this->setParametro('tipo_reporte','tipo_reporte','varchar');
            $this->setParametro('mel','mel','varchar');
            $this->setParametro('nro_no_rutina','nro_no_rutina','varchar');
            $this->setParametro('nro_justificacion','nro_justificacion','varchar');




            //Ejecuta la instruccion
            $this->armarConsulta();
            $stmt = $link->prepare($this->consulta);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            //recupera parametros devuelto depues de insertar ... (id_solicitud)
            $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
            if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
                throw new Exception("Error al ejecutar en la bd", 3);
            }
            $respuesta = $resp_procedimiento['datos'];
            $id_solicitud = $respuesta['id_solicitud'];

            //inserta detalle

            //decodifica JSON  de detalles
            $json_detalle = $this->aParam->_json_decode($this->aParam->getParametro('json_new_records'));

            //var_dump($json_detalle);

            foreach($json_detalle as $f){

                $this->resetParametros();
                //Definicion de variables para ejecucion del procedimiento
                $this->procedimiento='mat.ft_detalle_sol_ime';
                $this->transaccion='MAT_DET_INS';
                $this->tipo_procedimiento='IME';

                //modifica los valores de las variables que mandaremos
                $this->arreglo['id_solicitud'] = $id_solicitud;
                $this->arreglo['descripcion'] = $f['descripcion'];
                $this->arreglo['estado_reg'] = $f['estado_reg'];
                $this->arreglo['id_unidad_medida'] = $f['id_unidad_medida'];
                $this->arreglo['nro_parte'] = $f['nro_parte'];
                $this->arreglo['referencia'] = $f['referencia'];
                $this->arreglo['nro_parte_alterno'] = $f['nro_parte_alterno'];
                $this->arreglo['cantidad_sol'] = $f['cantidad_sol'];
                $this->arreglo['tipo'] = $f['tipo'];
                $this->arreglo['explicacion_detallada_part'] = $f['explicacion_detallada_part'];

                //Define los parametros para la funcion
                $this->setParametro('id_solicitud','id_solicitud','int4');
                $this->setParametro('descripcion','descripcion','varchar');
                $this->setParametro('estado_reg','estado_reg','varchar');
                $this->setParametro('id_unidad_medida','id_unidad_medida','int4');
                $this->setParametro('nro_parte','nro_parte','varchar');
                $this->setParametro('referencia','referencia','varchar');
                $this->setParametro('nro_parte_alterno','nro_parte_alterno','varchar');
                $this->setParametro('cantidad_sol','cantidad_sol','numeric');
                $this->setParametro('tipo','tipo','varchar');
                $this->setParametro('explicacion_detallada_part','explicacion_detallada_part','varchar');

                //Ejecuta la instruccion
                $this->armarConsulta();
                $stmt = $link->prepare($this->consulta);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                //recupera parametros devuelto depues de insertar ... (id_solicitud)
                $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
                if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
                    throw new Exception("Error al insertar detalle  en la bd", 3);
                }
            }
            //si todo va bien confirmamos y regresamos el resultado
            $link->commit();
            $this->respuesta=new Mensaje();
            $this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
            $this->respuesta->setDatos($respuesta);
        }
        catch (Exception $e) {
            $link->rollBack();
            $this->respuesta=new Mensaje();
            if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
                $this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
            } else if ($e->getCode() == 2) {//es un error en bd de una consulta
                $this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
            } else {//es un error lanzado con throw exception
                throw new Exception($e->getMessage(), 2);
            }
        }
        //var_dump($this->respuesta); exit;
        return $this->respuesta;
    }
    function listarMatricula(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_solicitud_sel';
        $this->transaccion='MAT_MATR_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setCount(false);
        //Definicion de la lista del resultado del query
        $this->captura('id_orden_trabajo','int4');
        $this->captura('matricula','text');
        $this->captura('desc_orden','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        // var_dump($this->respuesta); exit;

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function siguienteEstadoSolicitud(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_SIG_IME';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf_act','id_proceso_wf_act','int4');
        $this->setParametro('id_estado_wf_act','id_estado_wf_act','int4');
        $this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_funcionario_wf','id_funcionario_wf','int4');
        $this->setParametro('id_depto_wf','id_depto_wf','int4');
        $this->setParametro('obs','obs','text');
        $this->setParametro('json_procesos','json_procesos','text');
        
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function anteriorEstadoSolicitud(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_ANT_INS';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud','id_solicitud','int4');
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
        $this->setParametro('operacion','operacion','varchar');

        $this->setParametro('id_funcionario','id_funcionario','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('obs','obs','text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function inicioEstadoSolicitud(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_INI_INS';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud','id_solicitud','int4');
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
        $this->setParametro('operacion','operacion','varchar');
        $this->setParametro('estado_destino','estado_destino','varchar');

        $this->setParametro('id_funcionario','id_funcionario','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('obs','obs','text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarRequerimiento(){

        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_REING_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        $this->captura('id_solicitud','int4');
        $this->captura('fecha_solicitud','text');
        $this->captura('motivo_orden','varchar');
        $this->captura('matricula','text');
        $this->captura('matri','text');
        $this->captura('flota','text');
        $this->captura('nro_tramite','varchar');
        $this->captura('nro_parte','text');
        $this->captura('referencia','text');
        $this->captura('descripcion','text');
        $this->captura('cantidad_sol','numeric');
        $this->captura('justificacion','varchar');
        $this->captura('tipo_solicitud','varchar');
        $this->captura('fecha_requerida','text');
        $this->captura('motivo_solicitud','text');
        $this->captura('observaciones_sol','text');
        $this->captura('desc_funcionario1','text');
        $this->captura('tipo_falla','varchar');
        $this->captura('tipo_reporte','varchar');
        $this->captura('mel','varchar');
        $this->captura('id_unidad_medida','int4');
        $this->captura('estado','varchar');
        $this->captura('unidad_medida','varchar');
        $this->captura('nro_justificacion','varchar');
        $this->captura('nro_parte_alterno','varchar');
        $this->captura('tipo','varchar');
        $this->captura('estado_firma','varchar');
        $this->captura('fecha_fir','text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listasFrimas(){
        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_FRI_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        $this->captura('origen_pedido','varchar');
        $this->captura('visto_bueno','varchar');
        $this->captura('fecha_visto_bueno','text');
        $this->captura('aero','varchar');
        $this->captura('fecha_aero','text');
        $this->captura('visto_ag','varchar');
        $this->captura('fecha_ag','text');
        $this->captura('nro_tramite','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
      //  var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listasFrimas2(){
        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_MAF_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->captura('nombre_estado','varchar');
        $this->captura('funcionario_bv','text');
        $this->captura('fecha_ini','text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarFuncionarios(){

        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_FUN_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->captura('id_funcionario','int4');
        $this->captura('nombre_completo1','text');
        $this->captura('nombre_cargo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listaGetDatos(){

        $this->procedimiento ='mat.ft_solicitud_ime';
        $this->transaccion='MAT_FUN_GET';
        $this->tipo_procedimiento='IME';

        $this->setParametro('p_id_usuario','p_id_usuario','int4');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listaNroJustificacion(){
        //var_dump('hent');exit;
        $this->procedimiento ='mat.ft_solicitud_ime';
        $this->transaccion='MAT_GET_JUS';
        $this->tipo_procedimiento='IME';
        $this->setParametro('justificacion','justificacion','varchar');
        $this->setParametro('nro_parte','nro_parte','varchar');
        $this->setParametro('id_matricula','id_matricula','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function ControlPartesAlmacen(){

        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_CON_AL_SEL';
        $this->tipo_procedimiento='SEL';

        $this->setParametro('origen_pedido','origen_pedido','varchar');
        $this->setParametro('estado','estado','varchar');
        $this->setParametro('estado_op','estado_op','varchar');
        $this->setParametro('estado_ro','estado_ro','varchar');
        $this->setParametro('fecha_ini','fecha_ini','date');
        $this->setParametro('fecha_fin','fecha_fin','date');
        $this->setCount(false);
        $this->captura('id_solicitud','int4');
        $this->captura('nro_tramite','varchar');
        $this->captura('origen_pedido','varchar');
        $this->captura('estado','varchar');
        $this->captura('desc_funcionario1','text');
        $this->captura('fecha_solicitud','text');
        $this->captura('nro_parte','varchar');
        $this->captura('nro_parte_alterno','varchar');
        $this->captura('descripcion','varchar');
        $this->captura('cantidad_sol','numeric');
        $this->captura('id_tipo_estado','int4');
        $this->captura('id','int4');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarEstado(){

        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_ESTADO_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->captura('id_tipo_estado','int4');
        $this->captura('codigo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listarEstadoOp(){

        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_ES_OP_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->captura('id_tipo_estado','int4');
        $this->captura('codigo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listarEstadoRo(){

        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_ES_RO_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->captura('id_tipo_estado','int4');
        $this->captura('codigo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function  iniciarDisparo(){
        $this->procedimiento='mat.f_iniciar_disparo_ime';
        $this->transaccion='MAT_SOL_DIS';
        $this->tipo_procedimiento='IME';
        $this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->armarConsulta();
        $this->ejecutarConsulta();
        
        return $this->respuesta;
    }
    function siguienteDisparo(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.f_iniciar_disparo_ime';
        $this->transaccion='MAT_SIG_DIS';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf_act','id_proceso_wf_act','int4');
        $this->setParametro('id_estado_wf_act','id_estado_wf_act','int4');
        $this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_funcionario_wf','id_funcionario_wf','int4');
        $this->setParametro('id_depto_wf','id_depto_wf','int4');
        $this->setParametro('obs','obs','text');
        $this->setParametro('json_procesos','json_procesos','text');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function anteriorEstadoDisparo(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.f_iniciar_disparo_ime';
        $this->transaccion='MAT_ANT_DIS';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud','id_solicitud','int4');
        $this->setParametro('id_proceso_wf_firma','id_proceso_wf_firma','int4');
        $this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
        $this->setParametro('operacion','operacion','varchar');
        $this->setParametro('id_funcionario','id_funcionario','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_estado_wf_firma','id_estado_wf_firma','int4');
        $this->setParametro('obs','obs','text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function inicioEstadoSolicitudDisparo(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.f_iniciar_disparo_ime';
        $this->transaccion='MAT_INI_DIS';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud','id_solicitud','int4');
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
        $this->setParametro('operacion','operacion','varchar');
        $this->setParametro('id_funcionario','id_funcionario','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('obs','obs','text');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listarComiteEvaluacion(){

        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_REPOR_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->captura('id_solicitud','int4');
        $this->captura('item_selecionados','varchar');
        $this->captura('nro_tramite','varchar');
        $this->captura('origen_pedido','varchar');
        $this->captura('fecha_po','text');
        $this->captura('nro_parte','text');
        $this->captura('tipo_evaluacion','varchar');
        $this->captura('taller_asignado','varchar');
        $this->captura('observacion_nota','varchar');
        $this->captura('cotizacion_solicitadas','int4');
        $this->captura('nro_cotizacion','varchar');
        $this->captura('monto_total','numeric');
        $this->captura('proveedores_resp','int4');
        $this->captura('desc_proveedor','varchar');

        $this->captura('aero','varchar');
        $this->captura('fecha_aero','text');
        $this->captura('visto_rev','varchar');
        $this->captura('fecha_rev','text');
        $this->captura('visto_abas','varchar');
        $this->captura('fecha_abas','text');
        $this->captura('obs','varchar');
        $this->captura('recomendacion','varchar');
        $this->captura ('codigo','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
       //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteDocContratacionExt(){
        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_RDOC_CON_EXT_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        //Definicion de la lista del resultado del query
        $this->captura('descripcion','varchar');
        $this->captura('estado_reg','varchar');
        $this->captura('nro_parte','varchar');
        $this->captura('referencia','varchar');
        $this->captura('nro_parte_alterno','varchar');
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
        $this->captura('tipo','varchar');
        $this->captura('estado','varchar');
        $this->captura('nro_cite_dce','varchar');
        $this->captura('fecha_solicitud','date');
        $this->captura('condicion','varchar');
        $this->captura('lugar_entrega','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        //var_dump($this->consulta); exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;

    }

    function listarProveedor(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='param.f_tproveedor_sel';
        $this->transaccion='PM_PROVEEV_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query

        $this->captura('id_proveedor','INTEGER');
        $this->captura('id_persona','INTEGER');
        $this->captura('codigo','VARCHAR');
        $this->captura('numero_sigma','VARCHAR');
        $this->captura('tipo','VARCHAR');
        $this->captura('id_institucion','INTEGER');
        $this->captura('desc_proveedor','VARCHAR');
        $this->captura('nit','VARCHAR');
        $this->captura('id_lugar','int4');
        $this->captura('lugar','varchar');
        $this->captura('pais','varchar');
        $this->captura('rotulo_comercial','varchar');
        $this->captura('email','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        //var_dump($this->consulta); exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function setCorreosCotizacion(){
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_EMAIL_COT_IME';
        $this->tipo_procedimiento='IME';

        $this->setParametro('lista_correos','lista_correos','varchar');
        $this->setParametro('id_solicitud','id_solicitud','int4');

        $this->armarConsulta();
        //var_dump ('consulta'.$this->consulta);exit;
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteComparacionByS(){
        $this->procedimiento='mat.ft_solicitud_sel';
        $this->transaccion='MAT_REP_COMP_BYS_SEL';
        $this->tipo_procedimiento='SEL';

        $this->setCount(false);

        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        //Definicion de la lista del resultado del query

        $this->captura('unidad_sol','varchar');
        $this->captura('gerencia','varchar');
        $this->captura('funcionario_sol','varchar');
        $this->captura('nro_items','integer');
        $this->captura('adjudicado','varchar');
        $this->captura('motivo_solicitud','varchar');
        $this->captura('nro_partes','varchar');
        $this->captura('nro_cobs','varchar');
        $this->captura('fecha_solicitud','date');
        $this->captura('monto_ref','numeric');
        //$this->captura('codigo','varchar');


        $this->armarConsulta();
        //var_dump ($this->consulta);exit;
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function verificarCorreosProveedor(){
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_EMAIL_PROV_VAL';
        $this->tipo_procedimiento='IME';

        $this->setParametro('id_solicitud','id_solicitud','int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }



}
?>