CREATE OR REPLACE FUNCTION mat.ft_detalle_sol_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestión de Materiales
 FUNCION: 		mat.ft_detalle_sol_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tdetalle_sol'
 AUTOR: 		 (admin)
 FECHA:	        23-12-2016 13:13:01
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
	v_id_detalle	integer;

BEGIN

    v_nombre_funcion = 'mat.ft_detalle_sol_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_DET_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	if(p_transaccion='MAT_DET_INS')then

        begin
        --RAISE EXCEPTION 'parametros %',v_parametros;
        	--Sentencia de la insercion
        	insert into mat.tdetalle_sol(
			id_solicitud,
			descripcion,
			estado_reg,
			id_unidad_medida,
			nro_parte,
			referencia,
			nro_parte_alterno,
			--id_moneda,
			--precio,
			cantidad_sol,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.id_solicitud,
			v_parametros.descripcion,
			'activo',
			v_parametros.id_unidad_medida,
			v_parametros.nro_parte,
			v_parametros.referencia,
			v_parametros.nro_parte_alterno,
			--v_parametros.id_moneda,
			--v_parametros.precio,
			v_parametros.cantidad_sol,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null)RETURNING id_detalle into v_id_detalle;
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle_sol almacenado(a) con exito (id_detalle'||v_id_detalle||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_id_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_DET_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_DET_MOD')then

		begin
			--Sentencia de la modificacion
			update mat.tdetalle_sol set
			id_solicitud = v_parametros.id_solicitud,
			descripcion = v_parametros.descripcion,
			id_unidad_medida = v_parametros.id_unidad_medida,
			nro_parte = v_parametros.nro_parte,
			referencia = v_parametros.referencia,
			nro_parte_alterno = v_parametros.nro_parte_alterno,
			--id_moneda = v_parametros.id_moneda,
			--precio = v_parametros.precio,
			cantidad_sol = v_parametros.cantidad_sol,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_detalle=v_parametros.id_detalle;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle_sol modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_parametros.id_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_DET_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_DET_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from mat.tdetalle_sol
            where id_detalle=v_parametros.id_detalle;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle_sol eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_parametros.id_detalle::varchar);

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