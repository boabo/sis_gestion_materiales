<?php
/**
*@package pXP
*@file gen-MODCotizacion.php
*@author  (miguel.mamani)
*@date 04-07-2017 14:03:30
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCotizacion extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}

	function listarCotizacion(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_cotizacion_sel';
		$this->transaccion='MAT_CTS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

        $this->setParametro('id_solicitud','id_solicitud','int4');
		//Definicion de la lista del resultado del query
		$this->captura('id_cotizacion','int4');
		$this->captura('id_solicitud','int4');
		$this->captura('id_moneda','int4');
		$this->captura('nro_tramite','varchar');
		$this->captura('fecha_cotizacion','date');
		$this->captura('adjudicado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_proveedor','int4');
		$this->captura('monto_total','numeric');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_moneda','varchar');
		$this->captura('desc_proveedor','varchar');
		$this->captura('nro_cotizacion','varchar');
		$this->captura('recomendacion','varchar');
		$this->captura('obs','varchar');
		$this->captura('pie_pag','varchar');
		//
		// $this->captura('id_condicion_entrega','int4');
		// $this->captura('id_forma_pago','int4');
		// $this->captura('id_modo_envio','int4');
		// $this->captura('id_puntos_entrega','int4');
		// $this->captura('id_tipo_transaccion','int4');
		// $this->captura('id_orden_destino','int4');
		//
		// $this->captura('codigo_condicion_entrega','varchar');
		// $this->captura('codigo_forma_pago','varchar');
		// $this->captura('codigo_modo_envio','varchar');
		// $this->captura('codigo_puntos_entrega','varchar');
		// $this->captura('codigo_tipo_transaccion','varchar');
		// $this->captura('codigo_orden_destino','varchar');
		$this->captura('tipo_evaluacion','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function insertarCotizacion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_cotizacion_ime';
		$this->transaccion='MAT_CTS_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('fecha_cotizacion','fecha_cotizacion','date');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_proveedor','id_proveedor','int4');
    $this->setParametro('nro_cotizacion','nro_cotizacion','varchar');
    $this->setParametro('recomendacion','recomendacion','varchar');
    $this->setParametro('obs','obs','varchar');
    $this->setParametro('pie_pag','pie_pag','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function modificarCotizacion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_cotizacion_ime';
		$this->transaccion='MAT_CTS_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_cotizacion','id_cotizacion','int4');
		$this->setParametro('id_solicitud','id_solicitud','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('fecha_cotizacion','fecha_cotizacion','date');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_proveedor','id_proveedor','int4');
        $this->setParametro('nro_cotizacion','nro_cotizacion','varchar');
        $this->setParametro('recomendacion','recomendacion','varchar');
        $this->setParametro('obs','obs','varchar');
        $this->setParametro('pie_pag','pie_pag','varchar');

				$this->setParametro('id_condicion_entrega','id_condicion_entrega','int4');
				$this->setParametro('id_forma_pago','id_forma_pago','int4');
				$this->setParametro('id_modo_envio','id_modo_envio','int4');
				$this->setParametro('id_puntos_entrega','id_puntos_entrega','int4');
				$this->setParametro('id_tipo_transaccion','id_tipo_transaccion','int4');
				$this->setParametro('id_orden_destino','id_orden_destino','int4');

				$this->setParametro('codigo_condicion_entrega','codigo_condicion_entrega','varchar');
				$this->setParametro('codigo_forma_pago','codigo_forma_pago','varchar');
				$this->setParametro('codigo_modo_envio','codigo_modo_envio','varchar');
				$this->setParametro('codigo_puntos_entrega','codigo_puntos_entrega','varchar');
				$this->setParametro('codigo_tipo_transaccion','codigo_tipo_transaccion','varchar');
				$this->setParametro('codigo_orden_destino','codigo_orden_destino','varchar');
				$this->setParametro('direccion_punto_entrega','direccion_punto_entrega','varchar');
				$this->setParametro('tipo_evaluacion','tipo_evaluacion','varchar');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function eliminarCotizacion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='mat.ft_cotizacion_ime';
		$this->transaccion='MAT_CTS_ELI';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_cotizacion','id_cotizacion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
    function listarProveedor(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_CTS_COM_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        //$this->setCount(false);
        $this->setParametro('id_solicitud','id_solicitud','int4');

        $this->captura('id_solicitud','int4');
        $this->captura('id_gestion_proveedores','int4');
        $this->captura('id_prov','int4');
        $this->captura('desc_proveedor','varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

		function listarContactos(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_CONT_PROVE_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

				$this->captura('id_proveedor_contacto','int4');
        $this->captura('id_proveedor_contacto_alkym','int4');
        $this->captura('nombre_contacto','varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function controlAdjudicado(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='mat.ft_cotizacion_ime';
        $this->transaccion='MAT_CTS_ADJ';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_cotizacion','id_cotizacion','int4');
        $this->setParametro('adjudicado','adjudicado','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function cuadroComparativo(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_CTS_CUAR';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setCount(false);

        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        $this->captura('id_solicitud','int4');
        $this->captura('id_cotizacion','int4');
        $this->captura('parte','varchar');
        $this->captura('descripcion','text');
        $this->captura('explicacion_detallada_part_cot','varchar');
        $this->captura('cantidad','integer');
        $this->captura('tipo_cot','varchar');
        $this->captura('cd','varchar');
        $this->captura('precio_unitario','numeric');
        $this->captura('precio_unitario_mb','numeric');
        $this->captura('codigo_tipo','varchar');
        $this->captura('desc_proveedor','text');
        $this->captura('adjudicado','varchar');
        $this->captura('recomendacion','varchar');
        $this->captura('obs','varchar');
        $this->captura('fecha_cotizacion','varchar');
        $this->captura('fecha_po','varchar');
        $this->captura('monto_total','numeric');
        $this->captura('lista_proveedor','varchar');
        $this->captura('pie_pag','varchar');
        $this->captura('estado','varchar');
				$this->captura('nro_cotizacion','varchar');
				$this->captura('fecha_solicitud','date');
				$this->captura('fecha_salida','date');
        $this->captura('pn_cotizacion','varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
				var_dump("aqui llega ",$this->respuesta);exit;
        return $this->respuesta;
    }
    function listasFrimas(){
        $this->procedimiento ='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_CTS_QR';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        $this->captura('origen_pedido','varchar');
        $this->captura('aero','varchar');
        $this->captura('fecha_aero','text');
        $this->captura('visto_ag','varchar');
        $this->captura('fecha_ag','text');
        $this->captura('visto_rev','varchar');
        $this->captura('fecha_rev','text');
        $this->captura('visto_abas','varchar');
        $this->captura('fecha_abas','text');
				$this->captura('nro_tramite','varchar');
				$this->captura('estado_firma','varchar');
        $this->captura('mayor','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listaPartes(){
        $this->procedimiento ='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_CTS_PART';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        $this->captura('nro_parte','varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        // var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listaProveedor(){
        $this->procedimiento ='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_CTS_PROVEE';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        $this->captura('lista_proverod','varchar');
        $this->captura('obs','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
		/*Aumentando esta para para la fecha de la solicitud*/
		function listaSolicitudFecha(){
        $this->procedimiento ='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_SOLI_FEC';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');

        $this->captura('fecha_solicitud','date');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;
    }
		/***************************************************/
    function ControlPartesCotizacion(){

        $this->procedimiento ='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_CTS_REP';
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

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump( $this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

		function recuperarDetalleCotizacion(){
        //Definicion de variables para ejecucion del procedimiento
				$this->procedimiento='mat.ft_cotizacion_ime';
				$this->transaccion='MAT_REC_DET_SOL';
				$this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
				$this->setParametro('id_solicitud','id_solicitud','int4');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;
    }

		function insertarCotizacionCompleta(){

			//Abre conexion con PDO
			$cone = new conexion();
			$link = $cone->conectarpdo();
			$copiado = false;
			try {
					$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$link->beginTransaction();
					//Definicion de variables para ejecucion del procedimiento
					$this->procedimiento='mat.ft_cotizacion_ime';
					$this->transaccion='MAT_CTS_COMPLE_INS';
					$this->tipo_procedimiento='IME';

					//Define los parametros para la funcion
					$this->setParametro('id_solicitud','id_solicitud','int4');
					$this->setParametro('id_gestion','id_gestion','int4');
					$this->setParametro('id_proveedor','id_proveedor','int4');
					$this->setParametro('nro_cotizacion','nro_cotizacion','varchar');
					$this->setParametro('fecha_cotizacion','fecha_cotizacion','date');
					$this->setParametro('recomendacion','recomendacion','varchar');
					$this->setParametro('obs','obs','varchar');
					$this->setParametro('pie_pag','pie_pag','varchar');
					$this->setParametro('id_centro_costo','id_centro_costo','int4');
					$this->setParametro('id_concepto_ingas','id_concepto_ingas','int4');
					$this->setParametro('id_orden_trabajo','id_orden_trabajo','int4');
					$this->setParametro('adjudicado','adjudicado','varchar');
					$this->setParametro('referencial','referencial','varchar');
					/********************Aqui Aumentando para datos del Alkym (Ismael Valdivia 22/04/2020)********************/
					$this->setParametro('id_condicion_entrega','id_condicion_entrega','int4');
					$this->setParametro('id_forma_pago','id_forma_pago','int4');
					$this->setParametro('id_modo_envio','id_modo_envio','int4');
					$this->setParametro('id_puntos_entrega','id_puntos_entrega','int4');
					$this->setParametro('id_tipo_transaccion','id_tipo_transaccion','int4');
					$this->setParametro('id_orden_destino','id_orden_destino','int4');

					$this->setParametro('codigo_condicion_entrega','codigo_condicion_entrega','varchar');
					$this->setParametro('codigo_forma_pago','codigo_forma_pago','varchar');
					$this->setParametro('codigo_modo_envio','codigo_modo_envio','varchar');
					$this->setParametro('codigo_puntos_entrega','codigo_puntos_entrega','varchar');
					$this->setParametro('codigo_tipo_transaccion','codigo_tipo_transaccion','varchar');
					$this->setParametro('codigo_orden_destino','codigo_orden_destino','varchar');
					$this->setParametro('direccion_punto_entrega','direccion_punto_entrega','varchar');
					//$this->setParametro('fecha_entrega','fecha_entrega','date');
					$this->setParametro('tipo_evaluacion','tipo_evaluacion','varchar');
					$this->setParametro('id_proveedor_contacto','id_proveedor_contacto','int4');
					/****************************************************************************/

					//Ejecuta la instruccion
					$this->armarConsulta();
					$stmt = $link->prepare($this->consulta);
					$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);

					$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
					if ($resp_procedimiento['tipo_respuesta'] == 'ERROR') {
							throw new Exception("Error al ejecutar en la bd", 3);
					}
					$respuesta = $resp_procedimiento['datos'];

					$id_cotizacion = $respuesta['id_cotizacion'];
					$referencial = $respuesta['referencial'];
					$id_solicitud = $respuesta['id_solicitud'];

					//inserta detalle

					//decodifica JSON  de detalles
					$json_detalle = $this->aParam->_json_decode($this->aParam->getParametro('json_new_records'));

					foreach ($json_detalle as $f) {

							$this->resetParametros();
							//Definicion de variables para ejecucion del procedimiento
							$this->procedimiento = 'mat.ft_cotizacion_ime';
							$this->transaccion = 'MAT_COT_DETCOM_INS';
							$this->tipo_procedimiento = 'IME';

							//modifica los valores de las variables que mandaremos
							$this->arreglo['id_cotizacion'] = $id_cotizacion;
							$this->arreglo['nro_parte_cot'] = $f['nro_parte_cot'];
							$this->arreglo['nro_parte_alterno_cot'] = $f['nro_parte_alterno_cot'];
							$this->arreglo['referencia_cot'] = $f['referencia_cot'];
							$this->arreglo['descripcion_cot'] = $f['descripcion_cot'];
							$this->arreglo['explicacion_detallada_part_cot'] = $f['explicacion_detallada_part_cot'];
							$this->arreglo['tipo_cot'] = $f['tipo_cot'];
							$this->arreglo['id_unidad_medida'] = $f['id_unidad_medida'];
							$this->arreglo['cantidad_det'] = $f['cantidad_det'];
							$this->arreglo['cd'] = $f['cd'];
							$this->arreglo['precio_unitario'] = $f['precio_unitario'];
							$this->arreglo['precio_unitario_mb'] = $f['precio_unitario_mb'];
							$this->arreglo['id_day_week'] = $f['id_day_week'];
							$this->arreglo['referencial'] = $referencial;
							$this->arreglo['id_detalle'] = $f['id_detalle'];
							$this->arreglo['id_solicitud'] = $id_solicitud;

							//Define los parametros para la funcion
							$this->setParametro('id_cotizacion', 'id_cotizacion', 'int4');
							$this->setParametro('nro_parte_cot', 'nro_parte_cot', 'varchar');
							$this->setParametro('nro_parte_alterno_cot', 'nro_parte_alterno_cot', 'varchar');
							$this->setParametro('referencia_cot', 'referencia_cot', 'varchar');
							$this->setParametro('descripcion_cot', 'descripcion_cot', 'varchar');
							$this->setParametro('explicacion_detallada_part_cot', 'explicacion_detallada_part_cot', 'varchar');
							$this->setParametro('tipo_cot', 'tipo_cot', 'varchar');
							$this->setParametro('id_unidad_medida', 'id_unidad_medida', 'int4');
							$this->setParametro('cantidad_det', 'cantidad_det', 'numeric');
							$this->setParametro('cd', 'cd', 'varchar');
							$this->setParametro('precio_unitario', 'precio_unitario', 'numeric');
							$this->setParametro('precio_unitario_mb', 'precio_unitario_mb', 'numeric');
							$this->setParametro('id_day_week', 'id_day_week', 'int4');
							$this->setParametro('referencial', 'referencial', 'varchar');
							$this->setParametro('id_detalle', 'id_detalle', 'int4');
							$this->setParametro('id_solicitud', 'id_solicitud', 'int4');

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
			//Devuelve la respuesta
			return $this->respuesta;
		}


		function ListadoAdjudicados(){

        $this->procedimiento ='mat.ft_cotizacion_sel';
        $this->transaccion='MAT_LIS_ADJ_REP';
        $this->tipo_procedimiento='SEL';

        $this->setParametro('origen_pedido','origen_pedido','varchar');
        $this->setParametro('fecha_ini','fecha_ini','date');
				$this->setParametro('fecha_fin','fecha_fin','date');
        $this->setParametro('monto_mayor','monto_mayor','numeric');
        $this->setCount(false);

				$this->captura('nro_tramite','varchar');
				$this->captura('justificacion','varchar');
				$this->captura('funcionario_solicitante','varchar');
				$this->captura('tecnico_adquisiciones','varchar');
				$this->captura('proveedor_recomendado','varchar');
				$this->captura('proveedor_adjudicado','varchar');
				$this->captura('fecha_solicitud','varchar');
				$this->captura('precio_bs','numeric');
				$this->captura('precio_proceso','numeric');
				$this->captura('precio_adjudicado_bs','numeric');
				$this->captura('precio_adjudicado','numeric');
				$this->captura('moneda','varchar');
				$this->captura('contrato','varchar');
				$this->captura('cuce','varchar');
				$this->captura('modalidad_contratacion','varchar');
				$this->captura('depto','varchar');
				$this->captura('nro_po','varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump( $this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

}
?>
