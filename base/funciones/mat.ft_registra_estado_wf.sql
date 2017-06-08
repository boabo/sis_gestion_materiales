CREATE OR REPLACE FUNCTION mat.ft_registra_estado_wf (
  p_id_tipo_estado_siguiente integer,
  p_id_funcionario integer,
  p_id_estado_wf_anterior integer,
  p_id_proceso_wf integer,
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_depto integer = NULL::integer,
  p_obs text = ''::text,
  p_acceso_directo varchar = ''::character varying,
  p_clase varchar = NULL::character varying,
  p_parametros varchar = '{}'::character varying,
  p_tipo varchar = 'notificacion'::character varying,
  p_titulo varchar = 'Visto Bueno'::character varying
)
RETURNS integer AS
$body$
/**************************************************************************
 FUNCION: 		mat.f_registra_estado_wf
 DESCRIPCION: 	Devuelve: estado anterios
 AUTOR: 		MMV
 FECHA:			07/06/2017
 COMENTARIOS:
***************************************************************************/
DECLARE

    v_nombre_funcion varchar;
    v_resp varchar;

    g_registros			record;
    v_consulta			varchar;
    v_id_estado_actual	integer;
    v_registros record;
    v_id_tipo_estado integer;
    v_desc_alarma varchar;
    v_id_alarma integer;
    v_alarmas_con integer[];
    v_cont_alarma  integer;

    v_registros_ant record;
    v_registros_depto record;
    v_resp_doc boolean;

    v_plantilla_correo  varchar;
    v_plantilla_asunto  varchar;

    va_id_tipo_estado integer[];
    va_codigo_estado varchar[];
    v_resgistros_prod_dis   record;
    v_sw_error  boolean;
    v_mensaje_error    varchar;
    v_alarma		integer;
    v_documentos	text;
    v_res_validacion	text;

    va_id_alarmas  INTEGER[];
    va_verifica_documento 	varchar;

    v_i integer;
    v_registros_correo	record;


BEGIN

     v_nombre_funcion ='f_registra_estado_wf';


    select
    ew.estado_reg,
    ew.id_funcionario,
    ew.id_depto,
    tew.alerta,
    ew.id_depto,
    tew.id_tipo_estado,
    tew.nombre_estado,
    tew.disparador,
    ew.id_estado_wf
    into
    v_registros_ant
    from wf.testado_wf ew
    inner join wf.ttipo_estado tew on tew.id_tipo_estado = ew.id_tipo_estado
    where ew.id_estado_wf = p_id_estado_wf_anterior;


    --revisar que el estado se encuentre activo, en caso contrario puede
    --se una orden desde una pantalla desactualizada
    --raise exception '%,%',v_registros_ant.nombre_estado,v_registros_ant.id_estado_wf;
    IF (v_registros_ant.estado_reg !='activo') THEN
       raise exception 'El estado se encuentra inactivo, actualice sus datos' ;
    END IF;



    v_id_estado_actual = -1;

    if( p_id_tipo_estado_siguiente is null
        OR p_id_estado_wf_anterior is null
        OR p_id_proceso_wf is null
        )then
    	raise exception 'Faltan parametros, existen parametros nulos o en blanco, para registrar el estado en el WF.';

    end if;

 INSERT INTO wf.testado_wf(
     id_estado_anterior,
     id_tipo_estado,
     id_proceso_wf,
     id_funcionario,
     fecha_reg,
     estado_reg,
     id_usuario_reg,
     id_depto,
     obs,
     id_alarma,
     id_usuario_ai,
     usuario_ai)
    values(
       p_id_estado_wf_anterior,
       p_id_tipo_estado_siguiente,
       p_id_proceso_wf,
       p_id_funcionario,
       now(),
       'activo',
       p_id_usuario,
       p_id_depto,
       p_obs,
       v_alarmas_con,
       p_id_usuario_ai,
       p_usuario_ai)
    RETURNING id_estado_wf INTO v_id_estado_actual;

    --inserta log de estado en el proceso_wf
    update wf.tproceso_wf SET
    id_tipo_estado_wfs =  array_append(id_tipo_estado_wfs, p_id_tipo_estado_siguiente)
    where id_proceso_wf = p_id_proceso_wf;

    --recuperar  alarmas del estado anterior
    select
     ew.id_alarma
    into
     va_id_alarmas
    from wf.testado_wf ew
    where ew.id_estado_wf = p_id_estado_wf_anterior;
    --eliminar alarmas del estado anterior
    IF va_id_alarmas is not null THEN
      FOR v_i IN 1 .. array_upper(va_id_alarmas, 1)
      LOOP
           delete  from param.talarma  a where a.id_alarma = va_id_alarmas[v_i];
      END LOOP;
    END IF;


    UPDATE wf.testado_wf
    SET estado_reg = 'inactivo'
    WHERE id_estado_wf = p_id_estado_wf_anterior;

    select
     ew.verifica_documento
    into
     va_verifica_documento
    from wf.testado_wf ew
    where ew.id_estado_wf = p_id_estado_wf_anterior;

    return v_id_estado_actual;


 EXCEPTION

	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;