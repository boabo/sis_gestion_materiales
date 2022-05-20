<?php
/**
 * @package pXP
 * @file gen-MODSolicitud.php
 * @author  (admin)
 * @date 23-12-2016 13:12:58
 * @description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODSolicitud extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function listarSolicitud()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_SOL_SEL';
        $this->tipo_procedimiento = 'SEL';//tipo de transaccion
        $this->setParametro('pes_estado', 'pes_estado', 'varchar');
        $this->setParametro('tipo_interfaz', 'tipo_interfaz', 'varchar');
        $this->setParametro('id_usuario', 'id_usuario', 'int4');
        $this->setParametro('fill', 'fill', 'varchar');
        $this->setParametro('historico', 'historico', 'varchar');

        //Definicion de la lista del resultado del query
        $this->captura('id_solicitud', 'int4');
        $this->captura('id_funcionario_sol', 'int4');
        $this->captura('id_proveedor', 'int4');
        $this->captura('id_proceso_wf', 'int4');
        $this->captura('id_estado_wf', 'int4');
        $this->captura('nro_po', 'varchar');
        $this->captura('tipo_solicitud', 'varchar');
        $this->captura('fecha_entrega_miami', 'date');
        $this->captura('origen_pedido', 'varchar');
        $this->captura('fecha_requerida', 'date');
        $this->captura('observacion_nota', 'text');
        $this->captura('fecha_solicitud', 'date');
        $this->captura('estado_reg', 'varchar');
        $this->captura('observaciones_sol', 'varchar');
        $this->captura('fecha_tentativa_llegada', 'date');
        $this->captura('fecha_despacho_miami', 'date');
        $this->captura('justificacion', 'varchar');
        $this->captura('fecha_arribado_bolivia', 'date');
        $this->captura('fecha_desaduanizacion', 'date');
        $this->captura('fecha_entrega_almacen', 'date');
        $this->captura('cotizacion', 'numeric');
        $this->captura('tipo_falla', 'varchar');
        $this->captura('nro_tramite', 'varchar');
        $this->captura('id_matricula', 'int4');
        $this->captura('nro_solicitud', 'varchar');
        $this->captura('motivo_solicitud', 'varchar');
        $this->captura('fecha_en_almacen', 'date');
        $this->captura('estado', 'varchar');
        $this->captura('id_usuario_reg', 'int4');
        $this->captura('usuario_ai', 'varchar');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('id_usuario_ai', 'int4');
        $this->captura('fecha_mod', 'timestamp');
        $this->captura('id_usuario_mod', 'int4');
        $this->captura('usr_reg', 'varchar');
        $this->captura('usr_mod', 'varchar');
        $this->captura('desc_funcionario1', 'text');
        $this->captura('matricula', 'varchar');
        $this->captura('tipo_reporte', 'varchar');
        $this->captura('mel', 'varchar');
        $this->captura('nro_no_rutina', 'varchar');
        $this->captura('desc_proveedor', 'varchar');
        $this->captura('nro_partes', 'text');
        $this->captura('nro_parte_alterno', 'text');
        $this->captura('nro_justificacion', 'varchar');
        $this->captura('fecha_cotizacion', 'date');
        $this->captura('contador_estados', 'bigint');
        $this->captura('control_fecha', 'varchar');
        $this->captura('estado_firma', 'varchar');
        $this->captura('id_proceso_wf_firma', 'int4');
        $this->captura('id_estado_wf_firma', 'int4');
        $this->captura('contador_estados_firma', 'bigint');
        $this->captura('nombre_estado', 'varchar');
        $this->captura('nombre_estado_firma', 'varchar');
        $this->captura('fecha_po', 'date');
        $this->captura('tipo_evaluacion', 'varchar');
        $this->captura('taller_asignado', 'varchar');
        $this->captura('lista_correos', 'varchar');
        $this->captura('condicion', 'varchar');
        $this->captura('lugar_entrega', 'varchar');
        $this->captura('mensaje_correo', 'varchar');
        $this->captura('tipo', 'varchar');
        $this->captura('id_cotizacion', 'varchar');
        $this->captura('monto_pac', 'numeric');
        $this->captura('moneda', 'varchar');
        $this->captura('tipo_mov', 'varchar');

        $this->captura('obs_pac', 'varchar');
        /*Aumentando este campo para obtener el departamento (Ismael Valdivia 31/01/2020)*/
        $this->captura('id_depto', 'int4');
        $this->captura('id_gestion', 'int4');
        $this->captura('id_moneda', 'int4');

        $this->captura('funcionario_solicitante', 'text');
        $this->captura('revisado_presupuesto', 'varchar');
        $this->captura('nro_lote', 'int4');
        $this->captura('id_condicion_entrega', 'int4');
        $this->captura('id_forma_pago', 'int4');
        $this->captura('codigo_condicion_entrega', 'varchar');
        $this->captura('codigo_forma_pago', 'varchar');
        $this->captura('fecha_entrega', 'date');
        $this->captura('mel_observacion', 'text');
        $this->captura('origen_solicitud', 'varchar');
        $this->captura('tiempo_entrega', 'numeric');
        $this->captura('metodo_de_adjudicación', 'varchar');
        $this->captura('tipo_de_adjudicacion', 'varchar');
        $this->captura('remark', 'varchar');
        $this->captura('id_obligacion_pago', 'int4');
        $this->captura('nuevo_flujo', 'varchar');
        /********************************************************************************/
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function insertarSolicitud()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_SOL_INS';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_funcionario_sol', 'id_funcionario_sol', 'int4');
        $this->setParametro('id_proveedor', 'id_proveedor', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('nro_po', 'nro_po', 'varchar');
        $this->setParametro('tipo_solicitud', 'tipo_solicitud', 'varchar');
        $this->setParametro('fecha_entrega_miami', 'fecha_entrega_miami', 'date');
        $this->setParametro('origen_pedido', 'origen_pedido', 'varchar');
        $this->setParametro('fecha_requerida', 'fecha_requerida', 'date');
        $this->setParametro('observacion_nota', 'observacion_nota', 'text');
        $this->setParametro('fecha_solicitud', 'fecha_solicitud', 'date');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('observaciones_sol', 'observaciones_sol', 'varchar');
        $this->setParametro('fecha_tentativa_llegada', 'fecha_tentativa_llegada', 'date');
        $this->setParametro('fecha_despacho_miami', 'fecha_despacho_miami', 'date');
        $this->setParametro('justificacion', 'justificacion', 'varchar');
        $this->setParametro('fecha_arribado_bolivia', 'fecha_arribado_bolivia', 'date');
        $this->setParametro('fecha_desaduanizacion', 'fecha_desaduanizacion', 'date');
        $this->setParametro('fecha_entrega_almacen', 'fecha_entrega_almacen', 'date');
        $this->setParametro('cotizacion', 'cotizacion', 'numeric');
        $this->setParametro('tipo_falla', 'tipo_falla', 'varchar');
        $this->setParametro('nro_tramite', 'nro_tramite', 'varchar');
        $this->setParametro('id_matricula', 'id_matricula', 'int4');
        $this->setParametro('nro_solicitud', 'nro_solicitud', 'varchar');
        $this->setParametro('motivo_solicitud', 'motivo_solicitud', 'varchar');
        $this->setParametro('fecha_en_almacen', 'fecha_en_almacen', 'date');
        $this->setParametro('estado', 'estado', 'varchar');

        $this->setParametro('tipo_reporte', 'tipo_reporte', 'varchar');
        $this->setParametro('mel', 'mel', 'varchar');
        $this->setParametro('nro_no_rutina', 'nro_no_rutina', 'varchar');
        $this->setParametro('nro_justificacion', 'nro_justificacion', 'varchar');
		    /*Aumentando este campo (Ismael Valdivia 31/01/2020)*/
        $this->setParametro('id_depto', 'id_depto', 'int4');
        /***************************************************/
        /*Aumentando este campo (Ismael Valdivia 10/02/2020)*/
        $this->setParametro('id_moneda', 'id_moneda', 'int4');
        /****************************************************/

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function modificarSolicitud()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_SOL_MOD';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_funcionario_sol', 'id_funcionario_sol', 'int4');
        $this->setParametro('id_proveedor', 'id_proveedor', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('nro_po', 'nro_po', 'varchar');
        $this->setParametro('tipo_solicitud', 'tipo_solicitud', 'varchar');
        $this->setParametro('fecha_entrega_miami', 'fecha_entrega_miami', 'date');
        $this->setParametro('origen_pedido', 'origen_pedido', 'varchar');
        $this->setParametro('fecha_requerida', 'fecha_requerida', 'date');
        $this->setParametro('observacion_nota', 'observacion_nota', 'text');
        $this->setParametro('fecha_solicitud', 'fecha_solicitud', 'date');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('observaciones_sol', 'observaciones_sol', 'varchar');
        $this->setParametro('fecha_tentativa_llegada', 'fecha_tentativa_llegada', 'date');
        //$this->setParametro('fecha_despacho_miami','fecha_despacho_miami','date');
        $this->setParametro('fecha_cotizacion', 'fecha_cotizacion', 'date');
        $this->setParametro('justificacion', 'justificacion', 'varchar');
        $this->setParametro('fecha_arribado_bolivia', 'fecha_arribado_bolivia', 'date');
        $this->setParametro('fecha_desaduanizacion', 'fecha_desaduanizacion', 'date');
        $this->setParametro('fecha_entrega_almacen', 'fecha_entrega_almacen', 'date');
        $this->setParametro('cotizacion', 'cotizacion', 'numeric');
        $this->setParametro('tipo_falla', 'tipo_falla', 'varchar');
        $this->setParametro('nro_tramite', 'nro_tramite', 'varchar');
        $this->setParametro('id_matricula', 'id_matricula', 'int4');
        $this->setParametro('nro_solicitud', 'nro_solicitud', 'varchar');
        $this->setParametro('motivo_solicitud', 'motivo_solicitud', 'varchar');
        $this->setParametro('fecha_en_almacen', 'fecha_en_almacen', 'date');
        $this->setParametro('estado', 'estado', 'varchar');

        $this->setParametro('tipo_reporte', 'tipo_reporte', 'varchar');
        $this->setParametro('mel', 'mel', 'varchar');
        $this->setParametro('nro_no_rutina', 'nro_no_rutina', 'varchar');
        $this->setParametro('fecha_po', 'fecha_po', 'date');
        $this->setParametro('tipo_evaluacion', 'tipo_evaluacion', 'varchar');
        $this->setParametro('taller_asignado', 'taller_asignado', 'varchar');

        $this->setParametro('condicion', 'condicion', 'varchar');
        $this->setParametro('lugar_entrega', 'lugar_entrega', 'varchar');
        $this->setParametro('mensaje_correo', 'mensaje_correo', 'varchar');

        $this->setParametro('monto_pac', 'monto_pac', 'numeric');
        $this->setParametro('obs_pac', 'obs_pac', 'varchar');

		    /*Aumentando el campo id_moneda (Ismael Valdivia 10/02/2020)*/
        $this->setParametro('id_moneda', 'id_moneda', 'int4');
        $this->setParametro('lista_correos', 'lista_correos', 'varchar');
        $this->setParametro('nro_lote', 'nro_lote', 'int4');

        $this->setParametro('id_forma_pago', 'id_forma_pago', 'int4');
        $this->setParametro('id_condicion_entrega', 'id_condicion_entrega', 'int4');
        $this->setParametro('codigo_forma_pago', 'codigo_forma_pago', 'varchar');
        $this->setParametro('codigo_condicion_entrega', 'codigo_condicion_entrega', 'varchar');
        $this->setParametro('fecha_entrega', 'fecha_entrega', 'date');
        $this->setParametro('mel_observacion', 'mel_observacion', 'varchar');
        $this->setParametro('tiempo_entrega', 'tiempo_entrega', 'numeric');
        /************************************************************/
        /*Nuevos Campos para la leyenda que se mandara por el correo*/
        $this->setParametro('metodo_de_adjudicación', 'metodo_de_adjudicación', 'varchar');
        $this->setParametro('tipo_de_adjudicacion', 'tipo_de_adjudicacion', 'varchar');
        $this->setParametro('remark', 'remark', 'text');
        /************************************************************/
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function eliminarSolicitud()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_SOL_ELI';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function insertarSolicitudCompleta()
    {

        //Abre conexion con PDO
        $cone = new conexion();
        $link = $cone->conectarpdo();
        $copiado = false;
        try {
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $link->beginTransaction();
            //  inserta cabecera
            //Definicion de variables para ejecucion del procedimiento
            $this->procedimiento = 'mat.ft_solicitud_ime';
            $this->transaccion = 'MAT_SOL_INS';
            $this->tipo_procedimiento = 'IME';

            //Define los parametros para la funcion
            $this->setParametro('id_funcionario_sol', 'id_funcionario_sol', 'int4');
            $this->setParametro('id_proveedor', 'id_proveedor', 'int4');
            $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
            $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
            //$this->setParametro('nro_po', 'nro_po', 'varchar');
            $this->setParametro('tipo_solicitud', 'tipo_solicitud', 'varchar');
            $this->setParametro('fecha_entrega_miami', 'fecha_entrega_miami', 'date');
            $this->setParametro('origen_pedido', 'origen_pedido', 'varchar');
            $this->setParametro('fecha_requerida', 'fecha_requerida', 'date');
            $this->setParametro('observacion_nota', 'observacion_nota', 'text');
            $this->setParametro('fecha_solicitud', 'fecha_solicitud', 'date');
            $this->setParametro('estado_reg', 'estado_reg', 'varchar');
            $this->setParametro('observaciones_sol', 'observaciones_sol', 'varchar');
            $this->setParametro('fecha_tentativa_llegada', 'fecha_tentativa_llegada', 'date');
            $this->setParametro('fecha_despacho_miami', 'fecha_despacho_miami', 'date');
            $this->setParametro('justificacion', 'justificacion', 'varchar');
            $this->setParametro('fecha_arribado_bolivia', 'fecha_arribado_bolivia', 'date');
            $this->setParametro('fecha_desaduanizacion', 'fecha_desaduanizacion', 'date');
            $this->setParametro('fecha_entrega_almacen', 'fecha_entrega_almacen', 'date');
            $this->setParametro('cotizacion', 'cotizacion', 'numeric');
            $this->setParametro('tipo_falla', 'tipo_falla', 'varchar');
            $this->setParametro('nro_tramite', 'nro_tramite', 'varchar');
            $this->setParametro('id_matricula', 'id_matricula', 'int4');
            $this->setParametro('nro_solicitud', 'nro_solicitud', 'varchar');
            $this->setParametro('motivo_solicitud', 'motivo_solicitud', 'varchar');
            $this->setParametro('fecha_en_almacen', 'fecha_en_almacen', 'date');
            $this->setParametro('estado', 'estado', 'varchar');
            $this->setParametro('tipo_reporte', 'tipo_reporte', 'varchar');
            $this->setParametro('mel', 'mel', 'varchar');
            $this->setParametro('nro_no_rutina', 'nro_no_rutina', 'varchar');
            $this->setParametro('nro_justificacion', 'nro_justificacion', 'varchar');

		         /*Aumentando este campo (Ismael Valdivia 31/01/2020)*/
            $this->setParametro('id_depto', 'id_depto', 'int4');
            /****************************************************/

            /*Aumentando este campo (Ismael Valdivia 10/02/2020)*/
            $this->setParametro('id_moneda', 'id_moneda', 'int4');
            /****************************************************/

            /*Aumentando para Los BOA REP (IRVA 11/05/2020)*/
            $this->setParametro('nro_po', 'nro_po', 'varchar');
            //$this->setParametro('fecha_po', 'fecha_po', 'date');
            $this->setParametro('nro_lote', 'nro_lote', 'int4');

            $this->setParametro('id_condicion_entrega','id_condicion_entrega','int4');
  					$this->setParametro('id_forma_pago','id_forma_pago','int4');
            $this->setParametro('codigo_condicion_entrega','codigo_condicion_entrega','varchar');
            $this->setParametro('codigo_forma_pago','codigo_forma_pago','varchar');
            $this->setParametro('id_funcionario_solicitante','id_funcionario_solicitante','int4');
            $this->setParametro('mel_observacion','mel_observacion','text');
  					$this->setParametro('dias_entrega_estimado','dias_entrega_estimado','integer');
            /***********************************************/

            //Ejecuta la instruccion
            $this->armarConsulta();
            $stmt = $link->prepare($this->consulta);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            //recupera parametros devuelto depues de insertar ... (id_solicitud)
            $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
            if ($resp_procedimiento['tipo_respuesta'] == 'ERROR') {
                throw new Exception("Error al ejecutar en la bd", 3);
            }
            $respuesta = $resp_procedimiento['datos'];
            $id_solicitud = $respuesta['id_solicitud'];
            $origen_pedido = $this->aParam->arreglo_parametros['origen_pedido'];
            //inserta detalle

            //decodifica JSON  de detalles
            $json_detalle = $this->aParam->_json_decode($this->aParam->getParametro('json_new_records'));
            //$this->captura('nro_partes', 'text');

            //var_dump($json_detalle);exit;

            foreach ($json_detalle as $f) {

                $this->resetParametros();
                //Definicion de variables para ejecucion del procedimiento
                $this->procedimiento = 'mat.ft_detalle_sol_ime';
                $this->transaccion = 'MAT_DET_INS';
                $this->tipo_procedimiento = 'IME';

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
                /*Aumentando para los BOA REP*/
                if ($origen_pedido == 'Reparación de Repuestos') {
                  $this->arreglo['condicion_det'] = 'FOR '.$f['condicion_det'];
                } else {
                  $this->arreglo['condicion_det'] = $f['condicion_det'];
                }

                $this->arreglo['id_centro_costo'] = $f['id_centro_costo'];
                $this->arreglo['id_concepto_ingas'] = $f['id_concepto_ingas'];
                $this->arreglo['id_orden_trabajo'] = $f['id_orden_trabajo'];
                $this->arreglo['precio_unitario'] = $f['precio_unitario'];
                $this->arreglo['precio_total'] = $f['precio_total'];
                $this->arreglo['id_producto_alkym'] = $f['id_producto_alkym'];

                //Define los parametros para la funcion
                $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
                $this->setParametro('descripcion', 'descripcion', 'varchar');
                $this->setParametro('estado_reg', 'estado_reg', 'varchar');
                $this->setParametro('id_unidad_medida', 'id_unidad_medida', 'int4');
                $this->setParametro('nro_parte', 'nro_parte', 'varchar');
                $this->setParametro('referencia', 'referencia', 'varchar');
                $this->setParametro('nro_parte_alterno', 'nro_parte_alterno', 'varchar');
                $this->setParametro('cantidad_sol', 'cantidad_sol', 'numeric');
                $this->setParametro('tipo', 'tipo', 'varchar');
                $this->setParametro('explicacion_detallada_part', 'explicacion_detallada_part', 'varchar');
                /*Aumentando para los BOA REP*/
                $this->setParametro('condicion_det', 'condicion_det', 'varchar');
                $this->setParametro('id_centro_costo', 'id_centro_costo', 'int4');
                $this->setParametro('id_concepto_ingas', 'id_concepto_ingas', 'int4');
                $this->setParametro('id_orden_trabajo', 'id_orden_trabajo', 'int4');
                $this->setParametro('precio_unitario', 'precio_unitario', 'numeric');
                $this->setParametro('precio_total', 'precio_total', 'numeric');
                $this->setParametro('id_producto_alkym', 'id_producto_alkym', 'numeric');

                //Ejecuta la instruccion
                $this->armarConsulta();
                $stmt = $link->prepare($this->consulta);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                //recupera parametros devuelto depues de insertar ... (id_solicitud)
                $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
                if ($resp_procedimiento['tipo_respuesta'] == 'ERROR') {
                    throw new Exception("Error al insertar detalle  en la bd", 3);
                }
            }
            //si todo va bien confirmamos y regresamos el resultado
            $link->commit();
            $this->respuesta = new Mensaje();
            $this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'], $this->nombre_archivo, $resp_procedimiento['mensaje'], $resp_procedimiento['mensaje_tec'], 'base', $this->procedimiento, $this->transaccion, $this->tipo_procedimiento, $this->consulta);
            $this->respuesta->setDatos($respuesta);
        } catch (Exception $e) {
            $link->rollBack();
            $this->respuesta = new Mensaje();
            if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
                $this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'], $this->nombre_archivo, $resp_procedimiento['mensaje'], $resp_procedimiento['mensaje_tec'], 'base', $this->procedimiento, $this->transaccion, $this->tipo_procedimiento, $this->consulta);
            } else if ($e->getCode() == 2) {//es un error en bd de una consulta
                $this->respuesta->setMensaje('ERROR', $this->nombre_archivo, $e->getMessage(), $e->getMessage(), 'modelo', '', '', '', '');
            } else {//es un error lanzado con throw exception
                throw new Exception($e->getMessage(), 2);
            }
        }
        //var_dump($this->respuesta); exit;
        return $this->respuesta;
    }

    function listarMatricula()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_MATR_SEL';
        $this->tipo_procedimiento = 'SEL';//tipo de transaccion
        $this->setCount(false);
        //Definicion de la lista del resultado del query
        $this->captura('id_orden_trabajo', 'int4');
        $this->captura('matricula', 'text');
        $this->captura('desc_orden', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        // var_dump($this->respuesta); exit;

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function siguienteEstadoSolicitud()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_SIG_IME';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf_act', 'id_proceso_wf_act', 'int4');
        $this->setParametro('id_estado_wf_act', 'id_estado_wf_act', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_funcionario_wf', 'id_funcionario_wf', 'int4');
        $this->setParametro('id_depto_wf', 'id_depto_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');
        $this->setParametro('json_procesos', 'json_procesos', 'text');
        /*Aumentando para realizar control (Ismael Valdivia 19/02/2020)*/
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('idPoAlkym', 'idPoAlkym', 'int4');
        $this->setParametro('nro_po', 'nro_po', 'varchar');
        /**************************************************************/

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;
    }


    function siguienteEstadoSolicitudBorrador()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_SIG_BORR_IME';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('estado', 'estado', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function actualizarPO()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_PO_ALKYM_IME';
        $this->tipo_procedimiento = 'IME';

        /*Aumentando para realizar control (Ismael Valdivia 19/02/2020)*/
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('idPoAlkym', 'idPoAlkym', 'int4');
        $this->setParametro('nro_po', 'nro_po', 'varchar');
        /**************************************************************/

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump("llega modelo",$this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function anteriorEstadoSolicitud()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_ANT_INS';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('operacion', 'operacion', 'varchar');

        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function devolverTramiteServicio()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_BORR_EST';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('operacion', 'operacion', 'varchar');

        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function inicioEstadoSolicitud()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_INI_INS';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('operacion', 'operacion', 'varchar');
        $this->setParametro('estado_destino', 'estado_destino', 'varchar');

        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarRequerimiento()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_REING_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('id_solicitud', 'int4');
        $this->captura('fecha_solicitud', 'text');
        $this->captura('motivo_orden', 'varchar');
        $this->captura('matricula', 'text');
        $this->captura('matri', 'text');
        $this->captura('flota', 'text');
        $this->captura('nro_tramite', 'varchar');
        $this->captura('nro_parte', 'text');
        $this->captura('referencia', 'text');
        $this->captura('descripcion', 'text');
        $this->captura('cantidad_sol', 'numeric');
        $this->captura('justificacion', 'varchar');
        $this->captura('tipo_solicitud', 'varchar');
        $this->captura('fecha_requerida', 'text');
        $this->captura('motivo_solicitud', 'text');
        $this->captura('observaciones_sol', 'text');
        $this->captura('desc_funcionario1', 'text');
        $this->captura('tipo_falla', 'varchar');
        $this->captura('tipo_reporte', 'varchar');
        $this->captura('mel', 'varchar');
        $this->captura('id_unidad_medida', 'int4');
        $this->captura('estado', 'varchar');
        $this->captura('unidad_medida', 'varchar');
        $this->captura('nro_justificacion', 'varchar');
        $this->captura('nro_parte_alterno', 'varchar');
        $this->captura('tipo', 'varchar');
        $this->captura('nro_no_rutina', 'varchar');
        $this->captura('condicion', 'varchar');
        $this->captura('fecha_soli', 'date');
        $this->captura('tipo_de_adjudicacion', 'varchar');
        $this->captura('metodo_de_adjudicación', 'varchar');
        $this->captura('fecha_salida', 'date');
        $this->captura('pn_cotizacion', 'varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listasFrimas()
    {
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_FRI_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('origen_pedido', 'varchar');
        $this->captura('visto_bueno', 'varchar');
        $this->captura('fecha_visto_bueno', 'text');
        $this->captura('aero', 'varchar');
        $this->captura('fecha_aero', 'text');
        $this->captura('visto_ag', 'varchar');
        $this->captura('fecha_ag', 'text');
        $this->captura('nro_tramite', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listasFrimas2()
    {
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_MAF_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->captura('nombre_estado', 'varchar');
        $this->captura('funcionario_bv', 'text');
        $this->captura('fecha_ini', 'text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarFuncionarios()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_FUN_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->captura('id_funcionario', 'int4');
        $this->captura('nombre_completo1', 'text');
        $this->captura('nombre_cargo', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listaGetDatos()
    {

        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_FUN_GET';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('p_id_usuario', 'p_id_usuario', 'int4');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listaGetDatosTecnico()
    {

        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_FUN_GET_TEC';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('p_id_usuario', 'p_id_usuario', 'int4');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function getCentroCostoDefecto()
    {

        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_GET_CC_DEF';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_gestion', 'id_gestion', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        return $this->respuesta;
    }




    function listaNroJustificacion()
    {
        //var_dump('hent');exit;
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_GET_JUS';
        $this->tipo_procedimiento = 'IME';
        $this->setParametro('justificacion', 'justificacion', 'varchar');
        $this->setParametro('nro_parte', 'nro_parte', 'varchar');
        $this->setParametro('id_matricula', 'id_matricula', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function ControlPartesAlmacen()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_CON_AL_SEL';
        $this->tipo_procedimiento = 'SEL';

        $this->setParametro('origen_pedido', 'origen_pedido', 'varchar');
        $this->setParametro('estado', 'estado', 'varchar');
        $this->setParametro('estado_op', 'estado_op', 'varchar');
        $this->setParametro('estado_ro', 'estado_ro', 'varchar');
        $this->setParametro('fecha_ini', 'fecha_ini', 'date');
        $this->setParametro('fecha_fin', 'fecha_fin', 'date');
        $this->setCount(false);
        // $this->captura('id_solicitud','int4');
        $this->captura('nro_tramite', 'varchar');
        $this->captura('origen_pedido', 'varchar');
        $this->captura('estado', 'varchar');
        $this->captura('desc_funcionario1', 'text');
        $this->captura('fecha_solicitud', 'text');
        $this->captura('nro_parte', 'varchar');
        $this->captura('nro_parte_alterno', 'varchar');
        $this->captura('descripcion', 'varchar');
        $this->captura('cantidad_sol', 'numeric');
        $this->captura('id_tipo_estado', 'int4');
        $this->captura('id', 'int4');
        $this->captura('fecha_requerida', 'text');
        $this->captura('matricula', 'varchar');
        $this->captura('motivo_solicitud', 'varchar');
        $this->captura('observaciones_sol', 'varchar');
        $this->captura('justificacion', 'varchar');
        $this->captura('nro_justificacion', 'varchar');
        $this->captura('tipo_solicitud', 'varchar');
        $this->captura('tipo_falla', 'varchar');
        $this->captura('tipo_reporte', 'varchar');
        $this->captura('mel', 'varchar');
        $this->captura('nro_no_rutina', 'varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump( $this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarEstado()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_ESTADO_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->captura('id_tipo_estado', 'int4');
        $this->captura('codigo', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarEstadoOp()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_ES_OP_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->captura('id_tipo_estado', 'int4');
        $this->captura('codigo', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarEstadoRo()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_ES_RO_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->captura('id_tipo_estado', 'int4');
        $this->captura('codigo', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarEstadoSAC()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_ES_SAC_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->captura('id_tipo_estado', 'int4');
        $this->captura('codigo', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function iniciarDisparo()
    {
        $this->procedimiento = 'mat.f_iniciar_disparo_ime';
        $this->transaccion = 'MAT_SOL_DIS';
        $this->tipo_procedimiento = 'IME';
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function siguienteDisparo()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.f_iniciar_disparo_ime';
        $this->transaccion = 'MAT_SIG_DIS';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf_act', 'id_proceso_wf_act', 'int4');
        $this->setParametro('id_estado_wf_act', 'id_estado_wf_act', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_funcionario_wf', 'id_funcionario_wf', 'int4');
        $this->setParametro('id_depto_wf', 'id_depto_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');
        $this->setParametro('json_procesos', 'json_procesos', 'text');
        /*Aumentando para realizar control (Ismael Valdivia 19/02/2020)*/
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        /**************************************************************/


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function anteriorEstadoDisparo()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.f_iniciar_disparo_ime';
        $this->transaccion = 'MAT_ANT_DIS';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_proceso_wf_firma', 'id_proceso_wf_firma', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('operacion', 'operacion', 'varchar');
        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_estado_wf_firma', 'id_estado_wf_firma', 'int4');
        $this->setParametro('obs', 'obs', 'text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function inicioEstadoSolicitudDisparo()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.f_iniciar_disparo_ime';
        $this->transaccion = 'MAT_INI_DIS';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('operacion', 'operacion', 'varchar');
        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarComiteEvaluacion()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_REPOR_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->captura('id_solicitud', 'int4');
        $this->captura('item_selecionados', 'varchar');
        $this->captura('items_diferentes', 'varchar');
        $this->captura('nro_tramite', 'varchar');
        $this->captura('origen_pedido', 'varchar');
        $this->captura('fecha_po', 'text');
        $this->captura('nro_parte', 'text');
        $this->captura('tipo_evaluacion', 'varchar');
        $this->captura('taller_asignado', 'varchar');
        $this->captura('observacion_nota', 'varchar');
        $this->captura('cotizacion_solicitadas', 'int4');
        $this->captura('nro_cotizacion', 'varchar');
        $this->captura('monto_total', 'numeric');
        $this->captura('proveedores_resp', 'int4');
        $this->captura('desc_proveedor', 'varchar');

        $this->captura('aero', 'varchar');
        $this->captura('fecha_aero', 'text');
        $this->captura('visto_rev', 'varchar');
        $this->captura('fecha_rev', 'text');
        $this->captura('visto_abas', 'varchar');
        $this->captura('fecha_abas', 'text');
        $this->captura('obs', 'varchar');
        $this->captura('recomendacion', 'varchar');
        $this->captura('codigo', 'varchar');
        $this->captura('funcionario_pres', 'varchar');
        $this->captura('codigo_pres', 'varchar');
        $this->captura('fecha_pres', 'text');
        $this->captura('estado_materiales', 'varchar');

        $this->captura('parte', 'varchar');
        $this->captura('descripcion_cot', 'varchar');
        $this->captura('cantidad_det', 'int4');
        $this->captura('cd', 'varchar');

        /*Aumentando esta parte para el mostrar el pn cotizacion*/
        $this->captura('explicacion_detallada_part_cot', 'varchar');
        /********************************************************/

        $this->captura('codigo_tipo', 'varchar');

        $this->captura('funcionario_resp', 'varchar');
        $this->captura('fecha_resp', 'text');
        $this->captura('fecha_solicitud', 'date');
        $this->captura('estado_firma', 'varchar');
        $this->captura('fecha_salida', 'date');
        $this->captura('firma_tecnico_abastecimiento', 'varchar');
        $this->captura('cambio_etiqueta', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarComiteEvaluacionGR()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_REPORGR_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('evaluacion', 'varchar');
        $this->captura('parte_solicitada', 'varchar');
        $this->captura('proveedor', 'varchar');
        $this->captura('cotizaciones_recibidas', 'varchar');
        $this->captura('literal', 'varchar');
        $this->captura('taller_asignado', 'varchar');
        $this->captura('fecha_cotizacion', 'varchar');
        $this->captura('observaciones', 'varchar');
        $this->captura('total', 'varchar');
        $this->captura('firma_aeronavegabilidad', 'varchar');
        $this->captura('firma_abastecimiento', 'varchar');
        $this->captura('firma_rpce', 'varchar');
        $this->captura('firma_auxiliar', 'varchar');
        $this->captura('firma_jefe_departamento', 'varchar');
        $this->captura('firma_tecnico_abastecimiento', 'varchar');
        $this->captura('nro_tramite', 'varchar');
        $this->captura('cambiar_etiqueta', 'varchar');
        $this->captura('ocultar_administrativo', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteOrdenReparacion()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_REPORDER_REP_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('num_tramite', 'varchar');
        $this->captura('email_fun', 'varchar');
        $this->captura('po_type', 'varchar');
        $this->captura('rep', 'varchar');
        $this->captura('fecha_order', 'varchar');
        $this->captura('priority', 'varchar');
        $this->captura('nom_provee', 'varchar');
        $this->captura('dni', 'varchar');
        $this->captura('contacto_proveedor', 'varchar');
        $this->captura('direcc_provee', 'varchar');
        $this->captura('email_provee', 'varchar');
        $this->captura('telf_provee', 'varchar');
        $this->captura('fax_provee', 'varchar');
        $this->captura('estado_provee', 'varchar');
        $this->captura('country_provee', 'varchar');

        $this->captura('num_part', 'varchar');
        $this->captura('num_part_alt', 'varchar');
        $this->captura('cantidad', 'varchar');
        $this->captura('descripcion', 'varchar');
        $this->captura('serial', 'varchar');
        $this->captura('cd', 'varchar');
        $this->captura('precio_unitario', 'varchar');
        $this->captura('precio_total', 'varchar');
        $this->captura('suma_total', 'numeric');
        $this->captura('payment_terms', 'varchar');
        $this->captura('incoterms', 'varchar');
        //$this->captura('ship_to', 'varchar');
        $this->captura('delivery_date', 'varchar');
        $this->captura('observaciones_sol', 'varchar');
        //$this->captura('direccion_entrega', 'varchar');
        $this->captura('firma_rpc', 'varchar');
        $this->captura('serial_original', 'varchar');
        $this->captura('id_detalle', 'varchar');
        $this->captura('corregir_reporte', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function informeDeJustificacion()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_REP_JUSTREP_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('nro_rep', 'varchar');
        $this->captura('num_tramite', 'varchar');
        $this->captura('fecha_solicitud', 'varchar');
        $this->captura('fecha_order', 'varchar');
        $this->captura('fecha_aprobacion', 'varchar');

        $this->captura('num_part', 'varchar');
        $this->captura('num_part_alt', 'varchar');
        $this->captura('cantidad', 'varchar');
        $this->captura('descripcion', 'varchar');
        $this->captura('serial', 'varchar');
        $this->captura('cd', 'varchar');
        $this->captura('precio_unitario', 'varchar');
        $this->captura('precio_total', 'varchar');
        $this->captura('suma_total', 'numeric');
        $this->captura('nom_provee', 'varchar');
        $this->captura('suma_literal', 'varchar');
        $this->captura('firma_unidad', 'varchar');
        $this->captura('firma_jefe_departamento', 'varchar');
        $this->captura('condicion_detalle', 'varchar');
        $this->captura('gestion', 'varchar');
        $this->captura('evaluacion', 'varchar');
        $this->captura('tipo_taller', 'varchar');
        $this->captura('parte_det', 'varchar');
        $this->captura('parte_alter_det', 'varchar');
        $this->captura('desc_det', 'varchar');
        $this->captura('serial_det', 'varchar');
        $this->captura('nro_lote', 'varchar');
        $this->captura('fecha_cotizacion', 'varchar');
        $this->captura('fecha_envio', 'varchar');
        $this->captura('mayor', 'varchar');
        $this->captura('editar_etiqueta', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function technicalSpecifications()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_REP_TECSPE_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('num_tramite', 'varchar');
        $this->captura('po_type', 'varchar');
        $this->captura('rep', 'varchar');
        $this->captura('fecha_order', 'varchar');
        $this->captura('priority', 'varchar');

        $this->captura('num_part', 'varchar');
        $this->captura('num_part_alt', 'varchar');
        $this->captura('cantidad', 'varchar');
        $this->captura('descripcion', 'varchar');
        $this->captura('serial', 'varchar');
        $this->captura('cd', 'varchar');
        $this->captura('observaciones_sol', 'varchar');
        $this->captura('payment_terms', 'varchar');
        $this->captura('incoterms', 'varchar');
        $this->captura('ship_to', 'varchar');
        $this->captura('aprobado_por', 'varchar');
        $this->captura('preparado_por', 'varchar');
        $this->captura('tiempo_entrega', 'integer');
        $this->captura('tipo_evaluacion', 'varchar');
        //$this->captura('direccion_entrega', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteDocContratacionExt()
    {
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_RDOC_CON_EXT_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        //Definicion de la lista del resultado del query
        $this->captura('descripcion', 'varchar');
        $this->captura('estado_reg', 'varchar');
        $this->captura('nro_parte', 'varchar');
        $this->captura('referencia', 'varchar');
        $this->captura('nro_parte_alterno', 'varchar');
        $this->captura('precio', 'numeric');
        $this->captura('cantidad_sol', 'numeric');
        $this->captura('id_usuario_reg', 'int4');
        $this->captura('usuario_ai', 'varchar');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('id_usuario_ai', 'int4');
        $this->captura('id_usuario_mod', 'int4');
        $this->captura('fecha_mod', 'timestamp');
        $this->captura('usr_reg', 'varchar');
        $this->captura('usr_mod', 'varchar');
        $this->captura('codigo', 'varchar');
        $this->captura('desc_descripcion', 'varchar');
        $this->captura('tipo', 'varchar');
        $this->captura('estado', 'varchar');
        $this->captura('nro_cite_dce', 'varchar');
        $this->captura('fecha_solicitud', 'date');
        $this->captura('condicion', 'varchar');
        $this->captura('lugar_entrega', 'varchar');
        $this->captura('tiempo_entrega', 'numeric');
        $this->captura('fecha_salida', 'date');
        $this->captura('pn_cotizacion', 'varchar');
        $this->captura('cambiar_leyenda', 'varchar');
        //$this->captura('tipo_solicitud', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        //var_dump($this->consulta); exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;

    }

    function listarProveedor()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'param.f_tproveedor_sel';
        $this->transaccion = 'PM_PROVEEV_SEL';
        $this->tipo_procedimiento = 'SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query

        $this->captura('id_proveedor', 'INTEGER');
        $this->captura('id_persona', 'INTEGER');
        $this->captura('codigo', 'VARCHAR');
        $this->captura('numero_sigma', 'VARCHAR');
        $this->captura('tipo', 'VARCHAR');
        $this->captura('id_institucion', 'INTEGER');
        $this->captura('desc_proveedor', 'VARCHAR');
        $this->captura('nit', 'VARCHAR');
        $this->captura('id_lugar', 'int4');
        $this->captura('lugar', 'varchar');
        $this->captura('pais', 'varchar');
        $this->captura('rotulo_comercial', 'varchar');
        $this->captura('email', 'varchar');
        $this->captura('num_proveedor', 'varchar');
        $this->captura('condicion', 'varchar');



        //Ejecuta la instruccion
        $this->armarConsulta();
        //var_dump($this->consulta); exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function setCorreosCotizacion()
    {
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_EMAIL_COT_IME';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('lista_correos', 'lista_correos', 'varchar');
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');

        $this->armarConsulta();
        //var_dump ('consulta'.$this->consulta);exit;
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteComparacionByS()
    {
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_REP_COMP_BYS_SEL';
        $this->tipo_procedimiento = 'SEL';

        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        //Definicion de la lista del resultado del query

        $this->captura('estado_actual', 'varchar');
        $this->captura('unidad_sol', 'varchar');
        $this->captura('gerencia', 'varchar');
        $this->captura('funcionario_sol', 'varchar');
        $this->captura('funcionario_adm', 'varchar');
        $this->captura('funcionario_pres', 'varchar');
        $this->captura('codigo_pres', 'varchar');
        $this->captura('nro_items', 'integer');
        $this->captura('adjudicado', 'varchar');
        $this->captura('motivo_solicitud', 'varchar');
        $this->captura('nro_partes', 'varchar');
        $this->captura('nro_partes_alternos', 'varchar');
        $this->captura('nro_cobs', 'varchar');
        $this->captura('fecha_solicitud', 'date');
        $this->captura('monto_ref', 'numeric');
        $this->captura('funcionario', 'varchar');

        $this->captura('observaciones', 'varchar');
        $this->captura('tipo_proceso', 'varchar');
        $this->captura('fecha_salida','date');
        $this->captura('pn_cotizacion','varchar');
        $this->captura('mayor','varchar');
        $this->captura('nuevo_flujo','varchar');
        $this->captura('funcionario_auxiliar_abas','varchar');


        $this->armarConsulta();
        //echo($this->consulta);exit;
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function verificarCorreosProveedor()
    {
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_EMAIL_PROV_VAL';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function clonarSolicitud()
    {

        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_CLONAR_IME';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function generarPAC()
    {
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_PAC_IME';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('importe', 'importe', 'int4');
        $this->setParametro('moneda', 'moneda', 'int4');
        $this->setParametro('tipo', 'tipo', 'varchar');
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function ReporteConstanciaEnvioInvitacion()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_CONENV_REP';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        //$this->setParametro('historico','historico','varchar');

        $this->captura('lista_correos', 'varchar');
        $this->captura('mensaje_correo', 'varchar');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('correos', 'varchar');
        $this->captura('titulo_correo', 'varchar');
        $this->captura('tiempo_entrega', 'numeric');
        $this->captura('metodo_de_adjudicación', 'varchar');
        $this->captura('tipo_de_adjudicacion', 'varchar');
        $this->captura('fecha_solicitud', 'date');
        $this->captura('fecha_salida', 'date');


        //Ejecuta la instruccion
        $this->armarConsulta();//echo ($this->consulta); exit;
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function ReporteConstanciaEnvioInvitacionRep()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_CONENVREP_REP';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        //$this->setParametro('historico','historico','varchar');

        $this->captura('lista_correos', 'varchar');
        $this->captura('mensaje_correo', 'varchar');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('correos', 'varchar');
        $this->captura('titulo_correo', 'varchar');
        $this->captura('detalle', 'varchar');
        $this->captura('proveedores', 'varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();//echo ($this->consulta); exit;
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    /*Aumentando por Breydi (11/02/2020)*/
    function aprobarPresupuestoSolicitud()
     {
         //Definicion de variables para ejecucion del procedimiento
         $this->procedimiento = 'mat.ft_solicitud_ime';
         $this->transaccion = 'MAT_VALPRESU_IME';
         $this->tipo_procedimiento = 'IME';

         //Define los parametros para la funcion
         $this->setParametro('id_proceso_wf_act', 'id_proceso_wf_act', 'int4');
         $this->setParametro('aprobar', 'aprobar', 'varchar');

         //Ejecuta la instruccion
         $this->armarConsulta();
         $this->ejecutarConsulta();

         //Devuelve la respuesta
         return $this->respuesta;
       }
       /***********************************/

    //{'desarrollador':'franklin.espinoza', 'fecha':'04/02/2020'}
    function reporteCertificacionP(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_sel';
        $this->transaccion='MAT_REPCERPRE_SEL';
        $this->tipo_procedimiento='SEL';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');


        $this->captura('id_cp', 'int4');
        $this->captura('centro_costo', 'varchar');
        $this->captura('codigo_programa', 'varchar');
        $this->captura('codigo_proyecto', 'varchar');
        $this->captura('codigo_actividad', 'varchar');
        $this->captura('codigo_fuente_fin', 'varchar');
        $this->captura('codigo_origen_fin', 'varchar');

        $this->captura('codigo_partida', 'varchar');
        $this->captura('nombre_partidad', 'varchar');
        $this->captura('codigo_cg', 'varchar');
        $this->captura('nombre_cg', 'varchar');
        $this->captura('precio_total', 'numeric');
        //$this->captura('codigo_moneda', 'varchar');
        $this->captura('num_tramite', 'varchar');
        $this->captura('nombre_entidad', 'varchar');
        $this->captura('direccion_admin', 'varchar');
        $this->captura('unidad_ejecutora', 'varchar');
        $this->captura('codigo_ue', 'varchar');
        $this->captura('firmas', 'varchar');
        $this->captura('justificacion', 'varchar');
        $this->captura('codigo_transf', 'varchar');
        $this->captura('unidad_solicitante', 'varchar');
        $this->captura('funcionario_solicitante', 'varchar');
        $this->captura('fecha_soli', 'date');
        $this->captura('gestion', 'integer');
        $this->captura('codigo_poa', 'varchar');
        $this->captura('codigo_descripcion', 'varchar');
        $this->captura('tipo', 'varchar');
        $this->captura('fecha_cotizacion', 'varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        //echo($this->consulta);exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function getDatosNecesarios()
  	{
  		$this->procedimiento = 'mat.ft_solicitud_ime';
  		$this->transaccion = 'MAT_DATOSNEC';
  		$this->tipo_procedimiento = 'IME';

  		$this->setParametro('id_usuario', 'id_usuario', 'integer');
  		//Ejecuta la instruccion
  		$this->armarConsulta();
  		$this->ejecutarConsulta();

  		//Devuelve la respuesta
  		return $this->respuesta;
  	}

    function getVerificarDocumentos()
  	{
  		$this->procedimiento = 'mat.ft_solicitud_ime';
  		$this->transaccion = 'MAT_DOCU_VERIFI';
  		$this->tipo_procedimiento = 'IME';

  		$this->setParametro('id_proceso_wf', 'id_proceso_wf', 'integer');
      $this->setParametro('estado_sig', 'estado_sig', 'varchar');

      //Ejecuta la instruccion
  		$this->armarConsulta();
  		$this->ejecutarConsulta();

  		//Devuelve la respuesta
  		return $this->respuesta;
  	}

    function obtenerCombosAlkym()
  	{
  		$this->procedimiento = 'mat.ft_solicitud_sel';
  		$this->transaccion = 'MAT_COMBOS_ALKYM_SEL';
  		$this->tipo_procedimiento = 'SEL';

      $this->setParametro('json_obtenido', 'json_obtenido', 'json');
  		$this->setParametro('cantidad_json', 'cantidad_json', 'int4');
      $this->setParametro('tipo_combo', 'tipo_combo', 'varchar');

      $this->captura('id', 'integer');
      $this->captura('nombre', 'varchar');
      $this->captura('direccion', 'varchar');

  		//Ejecuta la instruccion
  		$this->armarConsulta();
  		$this->ejecutarConsulta();
      //var_dump($this->respuesta); exit;
  		//Devuelve la respuesta
  		return $this->respuesta;
  	}


    function obtenerCombosPartNumber()
  	{
  		$this->procedimiento = 'mat.ft_solicitud_sel';
  		$this->transaccion = 'MAT_COMB_PARNUM_SEL';
  		$this->tipo_procedimiento = 'SEL';

      //$this->setCount(false);

      $this->setParametro('json_obtenido', 'json_obtenido', 'json');
  		$this->setParametro('cantidad_json', 'cantidad_json', 'int4');
      $this->setParametro('tipo_combo', 'tipo_combo', 'varchar');

      $this->captura('idproducto', 'integer');
      $this->captura('idproductopn', 'integer');
      $this->captura('pn', 'varchar');
      $this->captura('descripcion', 'varchar');
      $this->captura('tipoproducto', 'varchar');
      $this->captura('codigo_unidad_medida', 'varchar');
      $this->captura('idunidadmedida', 'integer');
      $this->captura('idtipoproducto', 'integer');
      $this->captura('reparable', 'varchar');

  		//Ejecuta la instruccion
  		$this->armarConsulta();
  		$this->ejecutarConsulta();
      //var_dump($this->respuesta); exit;
  		//Devuelve la respuesta
  		return $this->respuesta;
  	}


    function actualizarCpAlkym()
  	{
  		$this->procedimiento = 'mat.ft_solicitud_ime';
  		$this->transaccion = 'MAT_UPD_CP_IME';
  		$this->tipo_procedimiento = 'IME';

      $this->setParametro('json_obtenido', 'json_obtenido', 'json');
  		$this->setParametro('cantidad_json', 'cantidad_json', 'int4');

  		//Ejecuta la instruccion
  		$this->armarConsulta();
  		$this->ejecutarConsulta();
      //var_dump($this->respuesta); exit;
  		//Devuelve la respuesta
  		return $this->respuesta;
  	}

    function controlPresupuesto(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_CTRL_PRESU';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud','id_solicitud','int4');
        $this->setParametro('revisado_presupuesto','revisado_presupuesto','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function ModificarTipoSolicitud(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_UDT_TIP_IME';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud','id_solicitud','int4');
        $this->setParametro('tipo_solicitud','tipo_solicitud','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function obtenerDetalleSolicitudServicio()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_DETSERV_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);



        $this->setParametro('id_solicitud', 'id_solicitud', 'varchar');

        //Definicion de la lista del resultado del query
        $this->captura('partnumber', 'varchar');
        $this->captura('cantidad', 'integer');
        $this->captura('precio_unitario', 'numeric');
        $this->captura('moneda', 'varchar');
        $this->captura('condicion', 'varchar');
        $this->captura('fechaentrega', 'varchar');
        $this->captura('IdPlanCuentaComp', 'integer');
        $this->captura('descripcion', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function obtenerDetalleSolicitudServicioHazmat()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_DET_HAZMAT_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);



        $this->setParametro('id_solicitud', 'id_solicitud', 'varchar');

        //Definicion de la lista del resultado del query
        $this->captura('partnumber', 'varchar');
        $this->captura('cantidad', 'integer');
        $this->captura('precio_unitario', 'numeric');
        $this->captura('moneda', 'varchar');
        $this->captura('condicion', 'varchar');
        $this->captura('fechaentrega', 'varchar');
        $this->captura('IdPlanCuentaComp', 'integer');
        $this->captura('descripcion', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }




    function obtenerDetalleCabecera()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_DETCABE_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_solicitud', 'id_solicitud', 'varchar');
        //Definicion de la lista del resultado del query
        $this->captura('id_proveedor', 'integer');
        $this->captura('id_criticidad', 'integer');
        $this->captura('id_condicion_entrega_alkym', 'integer');
        $this->captura('id_forma_pago_alkym', 'integer');
        $this->captura('id_modo_envio_alkym', 'integer');
        $this->captura('id_puntos_entrega_alkym', 'integer');
        $this->captura('id_tipo_transaccion_alkym', 'integer');
        $this->captura('monto_total', 'numeric');
        $this->captura('matricula', 'varchar');
        $this->captura('id_proveedor_contacto', 'integer');
        $this->captura('id_orden_destino_alkym', 'integer');
        $this->captura('observaciones_sol', 'varchar');
        $this->captura('nro_documento', 'varchar');
        $this->captura('fecha_po', 'date');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteSolicitudCompraBoARep()
    {
      //Definicion de variables para ejecucion del procedimientp
      $this->procedimiento = 'mat.ft_solicitud_sel';
      $this->transaccion = 'MAT_REP_SOLCOMP_SEL';
      $this->tipo_procedimiento = 'SEL';//tipo de transaccion
      $this->setCount(false);

      $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

      //Definicion de la lista del resultado del query
      $this->captura('id_solicitud', 'int4');
      $this->captura('estado_reg', 'varchar');
      $this->captura('estado', 'varchar');
      $this->captura('id_moneda', 'int4');
      $this->captura('id_gestion', 'int4');
      $this->captura('tipo', 'varchar');
      $this->captura('num_tramite', 'varchar');
      $this->captura('justificacion', 'varchar');
      $this->captura('id_depto', 'int4');
      $this->captura('id_proceso_wf', 'int4');
      $this->captura('id_funcionario_solicitante', 'int4');
      $this->captura('id_estado_wf', 'int4');
      $this->captura('fecha_solicitud', 'date');
      $this->captura('fecha_reg', 'timestamp');
      $this->captura('id_usuario_reg', 'int4');
      $this->captura('fecha_mod', 'timestamp');
      $this->captura('id_usuario_mod', 'int4');
      $this->captura('nombre_usuario_ai', 'varchar');
      $this->captura('usr_reg', 'varchar');
      $this->captura('usr_mod', 'varchar');
      $this->captura('desc_funcionario', 'text');
      $this->captura('desc_gestion', 'integer');
      $this->captura('desc_moneda', 'varchar');
      $this->captura('desc_depto', 'varchar');
      $this->captura('dep_prioridad', 'int4');
      $this->captura('fecha_soli_gant', 'date');
      $this->captura('fecha_soli_material','date');
      $this->captura('funcionario_rpc','varchar');
      $this->captura('gerente','varchar');
      $this->captura('firma_gerente','varchar');
      $this->captura('desc_uo','varchar');
      $this->captura('cargo_desc_funcionario','varchar');
      $this->captura('desc_cargo_gerente','varchar');
      $this->captura('nombre_macro','varchar');
      $this->captura('cotizacion_fecha','varchar');
      $this->captura('funcionario_jefe','varchar');
      $this->captura('cargo_jefe','varchar');

      $this->armarConsulta();
      //var_dump("aqui llegada",$this->respuesta);
      //echo($this->consulta);exit;
      $this->ejecutarConsulta();
      //var_dump("aqui llegada",$this->respuesta);
      return $this->respuesta;
    }
    function conexionAlkym()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'param.ft_get_variables_globales_sel';
        $this->transaccion = 'PARAM_GET_VG_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);


        $this->setParametro('variable_global', 'variable_global', 'varchar');
        //Definicion de la lista del resultado del query
        $this->captura('variable_obtenida', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function getDatosAlkym(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_solicitud_ime';
        $this->transaccion='MAT_GET_ALK_IME';
        $this->tipo_procedimiento='IME';//tipo de transaccion

        $this->setParametro('id_solicitud','id_solicitud','integer');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    //{developer:franklin.espinoza date: 04/06/2020}
    function upload_file_mantenimiento_erp(){

        $file_name = $this->objParam->getParametro('file_name');
        $file_bytes = $this->objParam->getParametro('file_bytes');
        $file_bytes = base64_decode($file_bytes);

        $path = $this->objParam->getParametro('path');

        if('/' != $path[0]){
            $path = '/'.$path;
        }
        if('/' != $path[strlen($path)-1]){
            $path = $path.'/';
        }

        $path_relative = "./../../../uploaded_files/sis_workflow/DocumentoWf".$path;

        if(!file_exists($path_relative)){
            mkdir($path_relative, 0744, true);
        }
        $path_absolute = $path_relative.$file_name;

        if(!file_exists($path_absolute)){
            $status = file_put_contents($path_absolute,$file_bytes);
            $exists = false;
        }else{
            $status = file_put_contents($path_absolute,$file_bytes);
            $exists = true;
        }

        if($status && !$exists){
            $message = 'Guardado Exitoso';
            $description = 'Se guardo el archivo '.$file_name.' con exito';
        }else if($exists){
            $message = 'Reemplazo Exitoso';
            $description = 'Se ha cambiado el archivo '.$file_name.' con exito';
        }else{
            $message = 'Falla al Guardar';
            $description = 'Se tubo problemas al crear el directorio para el archivo '.$file_name;
        }

        if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
            $path_download = 'erp.obairlines.bo/uploaded_files/sis_workflow/DocumentoWf'.$path.$file_name;
        } else {
            $path_download = '10.150.0.91/kerp_ismael/uploaded_files/sis_workflow/DocumentoWf'.$path.$file_name;
        }

        $this->respuesta = new Mensaje();
        $this->respuesta->setMensaje($status?'EXITO':'ERROR',"",$message,$description,'modelo',"","","","");
        $this->respuesta->setDatos(array('path_erp'=>$path_absolute, 'path_download'=>$path_download));
        //Devuelve la respuesta
        return $this->respuesta;
    }

    /*Aumentando para el reporte del RPC (Ismael Valdivia 07/10/2020)*/
    function ControlRpc(){

        $this->procedimiento ='mat.ft_solicitud_sel';
        $this->transaccion='MAT_CTRRPCE_REP';
        $this->tipo_procedimiento='SEL';

        $this->setParametro('origen_pedido','origen_pedido','varchar');
        $this->setParametro('fecha_ini','fecha_ini','date');
        $this->setParametro('fecha_fin','fecha_fin','date');
        $this->setCount(false);

        $this->captura('origen_pedido','varchar');
        $this->captura('nro_tramite','varchar');
        $this->captura('estado','varchar');
        $this->captura('funciaonario','text');
        $this->captura('matricula','varchar');
        $this->captura('fecha_solicitud','text');
        $this->captura('fecha_requerida','text');
        $this->captura('motivo_solicitud','varchar');
        $this->captura('observaciones_sol','varchar');
        $this->captura('justificacion','varchar');
        $this->captura('nro_justificacion','varchar');
        $this->captura('tipo_solicitud','varchar');
        $this->captura('tipo_falla','varchar');
        $this->captura('tipo_reporte','varchar');
        $this->captura('mel','varchar');
        $this->captura('nro_no_rutina','varchar');
        $this->captura('nro_cotizacion','varchar');
        $this->captura('proveedor','text');
        $this->captura('nro_parte_cot','varchar');
        $this->captura('nro_parte_alterno_cot','varchar');
        $this->captura('descripcion_cot','varchar');
        $this->captura('explicacion_detallada_part_cot','varchar');
        $this->captura('cantidad_det','int4');
        $this->captura('precio_unitario','numeric');
        $this->captura('precio_unitario_mb','numeric');
				$this->captura('nro_po','varchar');
				$this->captura('aux_abas','varchar');
				$this->captura('centro_costo','varchar');
        $this->captura('partida','varchar');
        $this->captura('fecha_autorizacion_rpc','varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump( $this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function ControlGridRpc(){

        $this->procedimiento ='mat.ft_listado_control_rpc ';
        $this->transaccion='MAT_RPCE_REP_SEL';
        $this->tipo_procedimiento='SEL';

        $this->setParametro('origen_pedido','origen_pedido','varchar');
        $this->setParametro('fecha_ini','fecha_ini','date');
        $this->setParametro('fecha_fin','fecha_fin','date');
        //$this->setCount(false);

        $this->captura('origen_pedido','varchar');
        $this->captura('nro_tramite','varchar');
        $this->captura('estado','varchar');
         $this->captura('funciaonario','varchar');
        $this->captura('fecha_solicitud','varchar');
        $this->captura('motivo_solicitud','varchar');
        $this->captura('observaciones_sol','varchar');
        $this->captura('remark','varchar');
        $this->captura('justificacion','varchar');
        $this->captura('nro_justificacion','varchar');
         $this->captura('tipo_solicitud','varchar');
        $this->captura('tipo_falla','varchar');
        $this->captura('tipo_reporte','varchar');
        $this->captura('mel','varchar');
        $this->captura('nro_no_rutina','varchar');
        $this->captura('nro_cotizacion','varchar');
         $this->captura('proveedor','text');
				$this->captura('nro_po','varchar');
				 $this->captura('aux_abas','varchar');
        $this->captura('fecha_autorizacion_rpc','varchar');
        $this->captura('encargado_rpc','varchar');
         $this->captura('precio_unitario_mb','numeric');
         $this->captura('id_proceso_wf','numeric');
         $this->captura('moneda','varchar');
         $this->captura('funcionario_solicitante','varchar');
         $this->captura('id_solicitud','numeric');
         $this->captura('id_estado_wf','numeric');
         $this->captura('existe_usuario','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump( $this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    /*****************************************************************/


    function NotaAdjudicacionBoaRep()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_REP_NOT_ADJU_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('proveedor', 'varchar');
        $this->captura('informe_rep', 'varchar');
        $this->captura('nro_rep', 'varchar');
        $this->captura('gestion_rep', 'numeric');
        $this->captura('lote_rep', 'varchar');
        $this->captura('fecha_entrega', 'varchar');
        $this->captura('id_solicitud_rep', 'numeric');
        $this->captura('total_venta_rep', 'numeric');
        $this->captura('firma_rpc', 'varchar');
        $this->captura('nro_tramite', 'varchar');
        $this->captura('fecha_firma', 'varchar');
        $this->captura('fecha_literal', 'varchar');
        $this->captura('fecha_cotizacion', 'varchar');
        $this->captura('evaluacion', 'varchar');
        $this->captura('tiene_bear', 'varchar');
        $this->captura('fecha_po', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }


    function reporteActaConformidadFinal()
    {

        $this->procedimiento = 'mat.ft_acta_conformidad_final_sel';
        $this->transaccion = 'MAT_REP_ACTA_CONFOR';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('nro_tramite', 'varchar');
        $this->captura('proveedor', 'varchar');
        $this->captura('nro_po', 'varchar');
        $this->captura('fecha_po', 'varchar');
        $this->captura('fecha_conformidad', 'varchar');
        $this->captura('conformidad_final', 'varchar');
        $this->captura('fecha_inicio', 'varchar');
        $this->captura('fecha_final', 'varchar');
        $this->captura('observaciones', 'varchar');
        $this->captura('desc_funcionario1', 'varchar');
        $this->captura('nombre_cargo', 'varchar');
        $this->captura('oficina_nombre', 'varchar');

        $this->captura('jefe_abastecimiento', 'varchar');
        $this->captura('cargo_jefe_abastecimiento', 'varchar');
        $this->captura('oficina_abastecimiento', 'varchar');

        $this->captura('encargado_almacen', 'varchar');
        $this->captura('cargo_encargado_almacen', 'varchar');
        $this->captura('oficina_encargado_almacen', 'varchar');
        $this->captura('aplica_nuevo_flujo', 'varchar');
        $this->captura('revisado', 'varchar');
        $this->captura('aumentar_condicion', 'varchar');
        $this->captura('tipo_pedido', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteActaConformidadFinalDetalle()
    {

        $this->procedimiento = 'mat.ft_acta_conformidad_final_sel';
        $this->transaccion = 'MAT_REP_ACTA_DETA';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('concepto', 'varchar');
        $this->captura('descripcion', 'varchar');
        $this->captura('cantidad_sol', 'integer');
        $this->captura('condicion', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarActaFinal(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_acta_conformidad_final_sel';
        $this->transaccion='MAT_LIST_ACT_FIN_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->captura('nro_tramite', 'varchar');
        $this->captura('estado', 'varchar');
        $this->captura('fecha_sol', 'varchar');
        $this->captura('proveedor', 'varchar');
        $this->captura('total_a_pagar', 'numeric');
        $this->captura('moneda', 'varchar');
        $this->captura('fecha_conformidad', 'varchar');

        $this->captura('desc_funcionario1', 'varchar');
        $this->captura('id_usuario', 'integer');
        $this->captura('id_solicitud', 'integer');
        $this->captura('observaciones', 'varchar');
        $this->captura('conformidad_final', 'varchar');
        $this->captura('fecha_inicio', 'varchar');
        $this->captura('fecha_final', 'varchar');
        $this->captura('id_proceso_wf', 'integer');
        $this->captura('nro_po', 'varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        //echo($this->consulta);exit;
        $this->ejecutarConsulta();
        //var_dump("aqui llega data",$this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }



    function actualizarActaConformidad(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_acta_conformidad_final_ime';
        $this->transaccion='MAT_UPDATE_ACTA';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_conformidad','id_conformidad','int4');
        $this->setParametro('id_solicitud','id_solicitud','int4');
        $this->setParametro('fecha_inicio','fecha_inicio','date');
        $this->setParametro('fecha_fin','fecha_fin','date');
        $this->setParametro('conformidad_final','conformidad_final','text');
        $this->setParametro('fecha_conformidad_final','fecha_conformidad_final','date');
        $this->setParametro('observaciones','observaciones','varchar');



          //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
 //console.log($this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }


    function controlPresupuestos()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_CTRL_PRESUPUESTO';
        $this->tipo_procedimiento = 'IME';

        /*Aumentando para realizar control (Ismael Valdivia 19/11/2021)*/
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        /**************************************************************/

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;
    }


    function controlReimpresion(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_control_revision_reportes';
        $this->transaccion='MAT_REV_REPOR_SEL';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('po_inicio','po_inicio','numeric');
        $this->setParametro('po_final','po_final','numeric');

          //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function consultaDetalleSolicitud(){
  		//Definicion de variables para ejecucion del procedimientp
  		$this->procedimiento='mat.ft_listado_control_rpc';
  		$this->transaccion='MAT_DET_SOL_RPC_SEL';
  		$this->tipo_procedimiento='SEL';//tipo de transaccion
  		$this->setCount(false);

  		$this->setParametro('id_solicitud', 'id_solicitud', 'int4');
  		$this->captura('jsonData','text');
  		//Ejecuta la instruccion
  		$this->armarConsulta();
  		// echo $this->consulta;exit;
  		$this->ejecutarConsulta();
  		//Devuelve la respuesta
      //var_dump("aqui llega la respuesta",$this->respuesta);exit;
  		return $this->respuesta;
  	}


    function insertarCuce()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_ime';
        $this->transaccion = 'MAT_INS_CUCE_IME';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('cuce', 'cuce', 'varchar');
        $this->setParametro('fecha_publicacion_cuce', 'fecha_publicacion_cuce', 'date');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }


    function formulario3008()
    {

        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_FOR_3008_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('nombre_empresa', 'varchar');
        $this->captura('cod_institucional', 'varchar');
        $this->captura('nro_cuce', 'varchar');
        $this->captura('nro_tramite', 'varchar');
        $this->captura('fecha_po', 'varchar');
        $this->captura('fecha_entrega', 'varchar');
        $this->captura('plazo', 'varchar');
        $this->captura('fecha_contratacion', 'varchar');
        $this->captura('fecha_cuce', 'varchar');
        $this->captura('monto_total', 'numeric');
        $this->captura('proveedor', 'varchar');
        $this->captura('nro_cite', 'varchar');
        $this->captura('fecha_solicitud', 'varchar');
        $this->captura('objeto_contratacion', 'varchar');
        $this->captura('nro_pac', 'varchar');
        $this->captura('fecha_pac', 'varchar');
        $this->captura('fecha_precio_referencial', 'varchar');
        $this->captura('fecha_esp_tecnica', 'varchar');
        $this->captura('fecha_certificacion_pre', 'varchar');
        $this->captura('fecha_correo', 'varchar');
        $this->captura('nro_cotizacion', 'varchar');
        $this->captura('fecha_cotizacion_adju', 'varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta);

        //Devuelve la respuesta
        return $this->respuesta;
    }

}

?>
