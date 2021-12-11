<?php
/**
 * @package pXP
 * @file gen-MODSolicitudMantenimiento.php
 * @author  (Ismael Valdivia)
 * @date 16-01-2020 18:30:00
 * @description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODSolicitudMantenimiento extends MODbase
{
    function insertarSolicitudMantenimiento()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_mantenimiento_ime';
        $this->transaccion = 'MAT_SOLMANT_INS';
        $this->tipo_procedimiento = 'IME';

          $this->setParametro('archivo', 'archivo', 'varchar');
          $this->setParametro('extension', 'extension', 'varchar');
          $this->setParametro('ruta', 'ruta', 'varchar');
        //Define los parametros para la funcion
        $this->setParametro('origen_pedido', 'origen_pedido', 'varchar');
        $this->setParametro('id_funcionario_sol', 'id_funcionario_sol', 'int4');
        $this->setParametro('fecha_solicitud', 'fecha_solicitud', 'date');
        $this->setParametro('matricula', 'matricula', 'varchar');
        $this->setParametro('motivo_solicitud', 'motivo_solicitud', 'varchar');
        $this->setParametro('observaciones_sol', 'observaciones_sol', 'varchar');
        $this->setParametro('justificacion', 'justificacion', 'varchar');
        $this->setParametro('nro_justificacion', 'nro_justificacion', 'varchar');
        $this->setParametro('tipo_solicitud', 'tipo_solicitud', 'varchar');
        $this->setParametro('tipo_falla', 'tipo_falla', 'varchar');
        $this->setParametro('tipo_reporte', 'tipo_reporte', 'varchar');
        $this->setParametro('mel', 'mel', 'varchar');
        $this->setParametro('fecha_requerida', 'fecha_requerida', 'date');
        $this->setParametro('nro_no_rutina', 'nro_no_rutina', 'varchar');
        $this->setParametro('json_solicitud_detalle','json_solicitud_detalle','text');
        $this->setParametro('mel_observacion','mel_observacion','text');

        /*Parametros que no se estan usando*/
        /*$this->setParametro('id_proveedor', 'id_proveedor', 'int4');
        $this->setParametro('nro_po', 'nro_po', 'varchar');
        $this->setParametro('fecha_entrega_miami', 'fecha_entrega_miami', 'date');
        $this->setParametro('observacion_nota', 'observacion_nota', 'text');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('fecha_tentativa_llegada', 'fecha_tentativa_llegada', 'date');
        $this->setParametro('fecha_despacho_miami', 'fecha_despacho_miami', 'date');
        $this->setParametro('fecha_arribado_bolivia', 'fecha_arribado_bolivia', 'date');
        $this->setParametro('fecha_desaduanizacion', 'fecha_desaduanizacion', 'date');
        $this->setParametro('fecha_entrega_almacen', 'fecha_entrega_almacen', 'date');
        $this->setParametro('cotizacion', 'cotizacion', 'numeric');
        $this->setParametro('nro_tramite', 'nro_tramite', 'varchar');
        $this->setParametro('nro_solicitud', 'nro_solicitud', 'varchar');
        $this->setParametro('fecha_en_almacen', 'fecha_en_almacen', 'date');
        $this->setParametro('estado', 'estado', 'varchar');*/






        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }


    function ObtenerIdProcesoWf()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'mat.ft_solicitud_sel';
        $this->transaccion = 'MAT_GET_ID_PWF';
        $this->tipo_procedimiento = 'SEL';

        $this->setCount(false);
        //Define los parametros para la funcion
        $this->setParametro('nro_tramite', 'nro_tramite', 'varchar');

        $this->captura('id_proceso_wf','integer');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //var_dump("aqui llega la respuesta ",$this->respuesta);
        //Devuelve la respuesta
        return $this->respuesta;
    }


    function ObtenerGantt()
    {
      //Definicion de variables para ejecucion del procedimiento
      $this->procedimiento='wf.f_gant_wf';// nombre procedimiento almacenado
      $this->transaccion='WF_GATNREP_SEL';//nombre de la transaccion
      $this->tipo_procedimiento='SEL';//tipo de transaccion
      $this->setCount(false);

      $this->setTipoRetorno('record');

      $this->setParametro('id_proceso_wf','id_proceso_wf','integer');
      $this->setParametro('orden','orden','varchar');


     //Definicion de la lista del resultado del query
      $this->captura('id','integer');
      $this->captura('id_proceso_wf','integer');
      $this->captura('id_estado_wf','integer');
      $this->captura('tipo','varchar');
      $this->captura('nombre','varchar');
      $this->captura('fecha_ini','TIMESTAMP');

      $this->captura('fecha_fin','TIMESTAMP');
      $this->captura('descripcion','varchar');
      $this->captura('id_siguiente','integer');
      $this->captura('tramite','varchar');
      $this->captura('codigo','varchar');

      $this->captura('id_funcionario','integer');
      $this->captura('funcionario','text');
      $this->captura('id_usuario','integer');
      $this->captura('cuenta','varchar');
      $this->captura('id_depto','integer');
      $this->captura('depto','varchar');
      $this->captura('nombre_usuario_ai','varchar');
      $this->captura('arbol','varchar');
      $this->captura('id_padre','integer');
      $this->captura('id_obs','integer');
      $this->captura('id_anterior','integer');
      $this->captura('etapa','varchar');
      $this->captura('estado_reg','varchar');
      $this->captura('disparador','varchar');
      $this->captura('nombre_proceso','varchar');
      $this->captura('etapa_consulta','varchar');
      $this->captura('duracion_consulta','varchar');
      $this->captura('desc_usuario','varchar');




      //$this->captura('id_estructura_uo','integer');
      //Ejecuta la funcion
      $this->armarConsulta();

      //echo $this->getConsulta();
      $this->ejecutarConsulta();

      return $this->respuesta;
    }


}

?>
