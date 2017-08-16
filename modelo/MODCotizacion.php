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
        $this->transaccion='MAT_CTS_COM';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setCount(false);
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
        $this->captura('descripcion','varchar');
        $this->captura('explicacion_detallada_part_cot','varchar');
        $this->captura('cantidad','integer');
        $this->captura('cd','varchar');
        $this->captura('precio_unitario','numeric');
        $this->captura('precio_unitario_mb','numeric');
        $this->captura('codigo_tipo','varchar');
        $this->captura('desc_proveedor','text');
        $this->captura('adjudicado','varchar');
        $this->captura('recomendacion','varchar');
        $this->captura('obs','varchar');
        $this->captura('fecha_cotizacion','varchar');
        $this->captura('fecha_po','date');
        $this->captura('monto_total','numeric');
        $this->captura('lista_proveedor','varchar');
        $this->captura('pie_pag','varchar');
        $this->captura('estado','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
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

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        // var_dump($this->respuesta); exit;
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
			
}
?>