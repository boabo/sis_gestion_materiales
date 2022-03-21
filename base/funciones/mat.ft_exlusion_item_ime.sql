CREATE OR REPLACE FUNCTION mat.ft_exlusion_item_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_exlusion_item_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tunidad_medida'
 AUTOR: 		 (admin)
 FECHA:	        14-03-2017 16:18:47
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

	v_id_historico	integer;
	v_estado_actual varchar;
BEGIN

    v_nombre_funcion = 'mat.ft_exlusion_item_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_UPT_ITEM_IME'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		17-03-2022 09:32:00
	***********************************/

	if(p_transaccion='MAT_UPT_ITEM_IME')then

        begin

        	select sol.estado into v_estado_actual
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;

        	if (v_estado_actual not in ('revision_tecnico_abastecimientos','cotizacion','cotizacion_solicitada')) then
            	raise exception 'Solo se puede excluir items en el estado Revisión Tecnico Abastecimiento, Cotización y Cotización Solicitada';
            else
            	update mat.tdetalle_sol  set
                estado_excluido = 'si',
                observacion_excluido = v_parametros.observacion_exclusion
                where id_detalle = v_parametros.id_detalle
                and id_solicitud = v_parametros.id_solicitud;

                --Insertamos en la tabla Historico para tener log de las modificaciones que se haran

                insert into mat.tdetalle_excluido_historico(
                estado_reg,
                id_usuario_ai,
                usuario_ai,
                fecha_reg,
                id_usuario_reg,
                fecha_mod,
                id_usuario_mod,
                id_detalle_solicitud,
                id_solicitud,
                observacion,
                estado_excluido
                ) values(
                'activo',
                v_parametros._id_usuario_ai,
                v_parametros._nombre_usuario_ai,
                now(),
                p_id_usuario,
                null,
                null,
                v_parametros.id_detalle,
                v_parametros.id_solicitud,
                v_parametros.observacion_exclusion,
                'si'

                )RETURNING id_historico into v_id_historico;
            end if;




			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Actualización y registro de datos correctamente');
            v_resp = pxp.f_agrega_clave(v_resp,'id_historico',v_id_historico::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_UPT_ADD_ITEM_IME'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		17-03-2022 09:32:00
	***********************************/

	elsif(p_transaccion='MAT_UPT_ADD_ITEM_IME')then

        begin

        	select sol.estado into v_estado_actual
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;

        	if (v_estado_actual not in ('revision_tecnico_abastecimientos','cotizacion','cotizacion_solicitada')) then
            	raise exception 'Solo se puede excluir items en el estado Revisión Tecnico Abastecimiento, Cotización y Cotización Solicitada';
            else

        	update mat.tdetalle_sol  set
            estado_excluido = 'no',
            observacion_excluido = v_parametros.observacion_exclusion
            where id_detalle = v_parametros.id_detalle
            and id_solicitud = v_parametros.id_solicitud;

            --Insertamos en la tabla Historico para tener log de las modificaciones que se haran

            insert into mat.tdetalle_excluido_historico(
			estado_reg,
			id_usuario_ai,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod,
            id_detalle_solicitud,
            id_solicitud,
            observacion,
            estado_excluido
          	) values(
			'activo',
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null,
            v_parametros.id_detalle,
            v_parametros.id_solicitud,
            v_parametros.observacion_exclusion,
            'no'

			)RETURNING id_historico into v_id_historico;

            end if;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Actualización y registro de datos correctamente');
            v_resp = pxp.f_agrega_clave(v_resp,'id_historico',v_id_historico::varchar);

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

ALTER FUNCTION mat.ft_exlusion_item_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
