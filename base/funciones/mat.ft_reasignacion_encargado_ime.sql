CREATE OR REPLACE FUNCTION mat.ft_reasignacion_encargado_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_reasignacion_encargado_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tunidad_medida'
 AUTOR: 		 (Ismael Valdivia)
 FECHA:	        18-04-2022 14:18:47
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_unidad_medida	integer;
    v_id_reasignacion	integer;


    v_funcionario_antiguo	integer;
    v_id_solicitud		integer;

BEGIN

    v_nombre_funcion = 'mat.ft_reasignacion_encargado_ime';
    v_parametros = pxp.f_get_record(p_tabla);


	/*********************************
 	#TRANSACCION:  'MAT_UDT_ENCAR_INS'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR: 		 (Ismael Valdivia)
 	#FECHA:	        18-04-2022 14:18:47
	***********************************/

	if(p_transaccion='MAT_UDT_ENCAR_INS')then

		begin
			--Sentencia de la modificacion

            /*Recuperamos el id funcionario antiguo*/
            select est.id_funcionario into v_funcionario_antiguo
            from wf.testado_wf est
            where est.id_estado_wf = v_parametros.id_estado_wf;
            /***************************************/


            /*Recuperamos el id de la solicitud para identificar cual ha sido reasignado*/
            select sol.id_solicitud into v_id_solicitud
            from mat.tsolicitud sol
            where sol.id_estado_wf = v_parametros.id_estado_wf;
            /****************************************************************************/


			update wf.testado_wf set
			id_funcionario = v_parametros.id_funcionario,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_estado_wf = v_parametros.id_estado_wf;


            insert into mat.tlog_reasignacion_funcionario(
			estado_reg,
			id_usuario_ai,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod,
            id_funcionario_antiguo,
            id_funcionario_nuevo,
            id_solicitud,
            observacion
          	) values(
			'activo',
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null,
            v_funcionario_antiguo,
            v_parametros.id_funcionario,
            v_id_solicitud,
            v_parametros.observacion

			)RETURNING id_reasignacion into v_id_reasignacion;





			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Reasignacion Correcto');
            v_resp = pxp.f_agrega_clave(v_resp,'id_estado_wf',v_parametros.id_estado_wf::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	else

    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

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

ALTER FUNCTION mat.ft_reasignacion_encargado_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
