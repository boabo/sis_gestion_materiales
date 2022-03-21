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

		$this->capturaCount('venta_total','numeric');
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
		/*Cambiando el campo precio por precio unitario*/
		//$this->captura('precio','numeric');
		$this->captura('precio_unitario','numeric');
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
        $this->captura('revisado','varchar');
        $this->captura('tipo','varchar');
        $this->captura('estado','varchar');
        $this->captura('explicacion_detallada_part','varchar');
		/*Aumentando los siguientes campos (Ismael Valdivia 31/01/2020)*/
		$this->captura('id_centro_costo','int4');
		$this->captura('id_concepto_ingas','int4');
		$this->captura('id_orden_trabajo','int4');

		//$this->captura('tipo_concepto','varchar');
		$this->captura('desc_centro_costo','text');
		$this->captura('desc_concepto_ingas','varchar');
		$this->captura('desc_orden_trabajo','varchar');
		// $this->captura('id_partida','int4');
		// $this->captura('desc_partida','varchar');
		// $this->captura('id_cuenta','int4');
		// $this->captura('desc_cuenta','varchar');
		// $this->captura('id_auxiliar','int4');
		// $this->captura('desc_auxiliar','varchar');
		$this->captura('precio_total','numeric');
		$this->captura('condicion_det','varchar');
		/***************************************************************/

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//var_dump($this->respuesta); exit;
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarDetalleSolSolictudCompra(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='mat.ft_detalle_sol_sel';
		$this->transaccion='MAT_DETCS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setParametro('id_solicitud','id_solicitud','int4');

		$this->capturaCount('venta_total','numeric');
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
		/*Cambiando el campo precio por precio unitario*/
		//$this->captura('precio','numeric');
		$this->captura('precio_unitario','numeric');
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
        $this->captura('revisado','varchar');
        $this->captura('tipo','varchar');
        $this->captura('estado','varchar');
        $this->captura('explicacion_detallada_part','varchar');
		/*Aumentando los siguientes campos (Ismael Valdivia 31/01/2020)*/
		$this->captura('id_centro_costo','int4');
		$this->captura('id_concepto_ingas','int4');
		$this->captura('id_orden_trabajo','int4');


		$this->captura('desc_centro_costo','text');
		$this->captura('desc_concepto_ingas','varchar');
		$this->captura('desc_orden_trabajo','varchar');
		$this->captura('precio_total','numeric');
		$this->captura('condicion_det','varchar');
		$this->captura('codigo_categoria','varchar');
		$this->captura('codigo_partida','varchar');
		$this->captura('nombre_partida','varchar');
		$this->captura('id_presupuesto','integer');
		$this->captura('id_partida','integer');
		$this->captura('total_hazmat','numeric');
		/***************************************************************/

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
		$this->setParametro('id_gestion','id_gestion','int4');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('nro_parte','nro_parte','varchar');
		$this->setParametro('referencia','referencia','varchar');
		$this->setParametro('nro_parte_alterno','nro_parte_alterno','varchar');
		$this->setParametro('cantidad_sol','cantidad_sol','numeric');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('explicacion_detallada_part','explicacion_detallada_part','varchar');

		/*Aumentando para insertar los nuevos campos (Ismael Valdivia 31/01/2020)*/
		//$this->setParametro('tipo_concepto','tipo_concepto','varchar');
		$this->setParametro('id_centro_costo','id_centro_costo','int4');
		$this->setParametro('id_concepto_ingas','id_concepto_ingas','int4');
		$this->setParametro('id_orden_trabajo','id_orden_trabajo','int4');
		// $this->setParametro('id_partida','id_partida','int4');
		// $this->setParametro('id_cuenta','id_cuenta','int4');
		// $this->setParametro('id_auxiliar','id_auxiliar','int4');
		$this->setParametro('precio_total','precio_total','numeric');
		$this->setParametro('precio_unitario','precio_unitario','numeric');
		$this->setParametro('condicion_det','condicion_det','varchar');
		$this->setParametro('id_producto_alkym','id_producto_alkym','integer');
		/*************************************************************************/



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
		$this->setParametro('id_gestion','id_gestion','int4');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_unidad_medida','id_unidad_medida','int4');
		$this->setParametro('nro_parte','nro_parte','varchar');
		$this->setParametro('referencia','referencia','varchar');
		$this->setParametro('nro_parte_alterno','nro_parte_alterno','varchar');
		$this->setParametro('cantidad_sol','cantidad_sol','numeric');
        $this->setParametro('revisado','revisado','varchar');
        $this->setParametro('tipo','tipo','varchar');
        $this->setParametro('explicacion_detallada_part','explicacion_detallada_part','varchar');

		/*Aumentando para insertar los nuevos campos (Ismael Valdivia 31/01/2020)*/
		//$this->setParametro('tipo_concepto','tipo_concepto','varchar');
		$this->setParametro('id_centro_costo','id_centro_costo','int4');
		$this->setParametro('id_concepto_ingas','id_concepto_ingas','int4');
		$this->setParametro('id_orden_trabajo','id_orden_trabajo','int4');
		// $this->setParametro('id_partida','id_partida','int4');
		// $this->setParametro('id_cuenta','id_cuenta','int4');
		// $this->setParametro('id_auxiliar','id_auxiliar','int4');
		$this->setParametro('precio_total','precio_total','numeric');
		$this->setParametro('precio_unitario','precio_unitario','numeric');

		$this->setParametro('control_edicion', 'control_edicion', 'varchar');
		$this->setParametro('condicion_det', 'condicion_det', 'varchar');
		$this->setParametro('id_producto_alkym','id_producto_alkym','integer');
		/*************************************************************************/
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
    function cambiarRevision(){

        $this->procedimiento='mat.ft_control_de_partes';
        $this->transaccion='MAT_CONT_PAR';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_detalle','id_detalle','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function controlPartes(){
        //var_dump('hent');exit;
        $this->procedimiento ='mat.ft_detalle_sol_ime';
        $this->transaccion='MAT_GET_PAR';
        $this->tipo_procedimiento='IME';
        $this->setParametro('nro_parte','nro_parte','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

		function getCentroCosto()
  	{
  		$this->procedimiento = 'mat.ft_detalle_sol_ime';
  		$this->transaccion = 'MAT_GET_CC';
  		$this->tipo_procedimiento = 'IME';

			$this->setParametro('id_gestion', 'id_gestion', 'integer');
  		$this->setParametro('id_solicitud', 'id_solicitud', 'integer');
  		//Ejecuta la instruccion
  		$this->armarConsulta();
  		$this->ejecutarConsulta();

  		//Devuelve la respuesta
  		return $this->respuesta;
  	}

		function RelacionarHazmat(){
			//Definicion de variables para ejecucion del procedimiento
			$this->procedimiento='mat.ft_detalle_sol_ime';
			$this->transaccion='MAT_REL_HAZMAT';
			$this->tipo_procedimiento='IME';

			//Define los parametros para la funcion
			$this->setParametro('id_cotizacion_det','id_cotizacion_det','int4');
			$this->setParametro('id_hazmat','id_hazmat','int4');

			//Ejecuta la instruccion
			$this->armarConsulta();
			$this->ejecutarConsulta();

			//Devuelve la respuesta
			return $this->respuesta;
		}

		function excluirItem()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_exlusion_item_ime';
        $this->transaccion = 'MAT_UPT_ITEM_IME';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_detalle', 'id_detalle', 'int4');
        $this->setParametro('observacion_exclusion', 'observacion_exclusion', 'varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function incluirItem()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_exlusion_item_ime';
        $this->transaccion = 'MAT_UPT_ADD_ITEM_IME';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_solicitud', 'id_solicitud', 'int4');
        $this->setParametro('id_detalle', 'id_detalle', 'int4');
        $this->setParametro('observacion_exclusion', 'observacion_exclusion', 'varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }


		function consultaDetalleExcluidos()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_exlusion_item_sel';
        $this->transaccion = 'MAT_LIST_ITEM_IME';
        $this->tipo_procedimiento = 'SEL';
				$this->setCount(false);
        //Define los parametros para la funcion
        $this->setParametro('id_detalle', 'id_detalle', 'int4');

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
