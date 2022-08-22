CREATE OR REPLACE FUNCTION mat.ft_log_modificaciones_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_log_modificaciones_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tlog_modificaciones'
 AUTOR: 		 (ismael.valdivia)
 FECHA:	        22-08-2022 12:38:25
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-08-2022 12:38:25								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tlog_modificaciones'
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_log	integer;
    v_nro_po_antiguo		varchar;
    v_nro_po_nuevo			varchar;
    v_fecha_cotizacion_antigua	date;
    v_fecha_cotizacion_nueva	date;
    v_nro_cotizacion_anterior	varchar;
    v_nro_cotizacion_nueva		varchar;
    v_id_cotizacion			integer;
    v_nro_cotizacion		varchar;
    v_fecha_cotizacion		varchar;
    v_id_cotizacion_adjudicada	integer;
    v_existe_cotizacion		integer;
    v_exsite_cotizacion		varchar;

BEGIN

    v_nombre_funcion = 'mat.ft_log_modificaciones_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_LOG_TRAM_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		ismael.valdivia
 	#FECHA:		22-08-2022 12:38:25
	***********************************/

	if(p_transaccion='MAT_LOG_TRAM_INS')then

        begin



        	/*Aqui condiciones para ver si se modifica el PO*/
           	if (v_parametros.interfaz_origen = 'modificar_po') then
            	select sol.nro_po into v_nro_po_antiguo
                from mat.tsolicitud sol
                where sol.id_solicitud = v_parametros.id_solicitud;

                v_nro_po_nuevo = v_parametros.nro_po_nuevo;

                v_fecha_cotizacion_antigua = null;
                v_fecha_cotizacion_nueva = null;
                v_nro_cotizacion_anterior = null;
                v_nro_cotizacion_nueva	= null;
                v_id_cotizacion	= null;

            else
            	v_nro_po_antiguo = null;
                v_nro_po_nuevo	= null;


                v_fecha_cotizacion_nueva = v_parametros.fecha_cotizacion_nueva;
                v_nro_cotizacion_nueva = v_parametros.nro_cotizacion_nueva;


                select cot.nro_cotizacion,
                	   cot.fecha_cotizacion
                into
                		v_nro_cotizacion_anterior,
                        v_fecha_cotizacion_antigua
                from mat.tcotizacion cot
                where cot.id_cotizacion = v_parametros.id_cotizacion;



                v_id_cotizacion = v_parametros.id_cotizacion;

            end if;

            /************************************************/


        	--Sentencia de la insercion
        	insert into mat.tlog_modificaciones(
			estado_reg,
			id_funcionario_solicitante,
			motivo_modificacion,
			nro_po_anterior,
			nro_po_nuevo,
			fecha_cotizacion_antigua,
			fecha_cotizacion_nueva,
			nro_cotizacion_anterior,
			nro_cotizacion_nueva,
			id_cotizacion,
			id_solicitud,
			fecha_modificacion,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.id_funcionario_solicitante,
			v_parametros.motivo_modificacion,
			v_nro_po_antiguo,--v_parametros.nro_po_anterior,
			v_nro_po_nuevo,--v_parametros.nro_po_nuevo,
			v_fecha_cotizacion_antigua,--v_parametros.fecha_cotizacion_antigua,
			v_fecha_cotizacion_nueva,--v_parametros.fecha_cotizacion_nueva,
			v_nro_cotizacion_anterior,--v_parametros.nro_cotizacion_anterior,
			v_nro_cotizacion_nueva,--v_parametros.nro_cotizacion_nueva,
			v_id_cotizacion,--v_parametros.id_cotizacion,
			v_parametros.id_solicitud,
			now(),--v_parametros.fecha_modificacion,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null
			)RETURNING id_log into v_id_log;

            /*Aqui realizamos la actualizacion en la tabla de la solicitud*/
            if (v_parametros.interfaz_origen = 'modificar_po') then
            	update mat.tsolicitud set
                nro_po = v_nro_po_nuevo
                where id_solicitud = v_parametros.id_solicitud;
            else
            	update mat.tcotizacion set
                	nro_cotizacion = v_nro_cotizacion_nueva,
                	fecha_cotizacion = v_fecha_cotizacion_nueva
                where id_cotizacion = v_parametros.id_cotizacion;
            end if;
            /*******************************************************************/




			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Modificaciones almacenado(a) con exito (id_log'||v_id_log||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_log',v_id_log::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_LOG_TRAM_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		ismael.valdivia
 	#FECHA:		22-08-2022 12:38:25
	***********************************/

	elsif(p_transaccion='MAT_LOG_TRAM_MOD')then

		begin
			--Sentencia de la modificacion
			update mat.tlog_modificaciones set
			id_funcionario_solicitante = v_parametros.id_funcionario_solicitante,
			motivo_modificacion = v_parametros.motivo_modificacion,
			nro_po_anterior = v_parametros.nro_po_anterior,
			nro_po_nuevo = v_parametros.nro_po_nuevo,
			fecha_cotizacion_antigua = v_parametros.fecha_cotizacion_antigua,
			fecha_cotizacion_nueva = v_parametros.fecha_cotizacion_nueva,
			nro_cotizacion_anterior = v_parametros.nro_cotizacion_anterior,
			nro_cotizacion_nueva = v_parametros.nro_cotizacion_nueva,
			id_cotizacion = v_parametros.id_cotizacion,
			id_solicitud = v_parametros.id_solicitud,
			fecha_modificacion = v_parametros.fecha_modificacion,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_log=v_parametros.id_log;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Modificaciones modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_log',v_parametros.id_log::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_LOG_TRAM_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		ismael.valdivia
 	#FECHA:		22-08-2022 12:38:25
	***********************************/

	elsif(p_transaccion='MAT_LOG_TRAM_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from mat.tlog_modificaciones
            where id_log=v_parametros.id_log;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Modificaciones eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_log',v_parametros.id_log::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;


    /*********************************
 	#TRANSACCION:  'MAT_VERI_ADJU_IME'
 	#DESCRIPCION:	Verificar si existe el proveedor Adjudicado
 	#AUTOR:		ismael.valdivia
 	#FECHA:		22-08-2022 12:38:25
	***********************************/

	elsif(p_transaccion='MAT_VERI_ADJU_IME')then

		begin
			--Sentencia de la recuperacion


			select cot.nro_cotizacion,
            	   cot.fecha_cotizacion,
                   cot.id_cotizacion
            into
            	   v_nro_cotizacion,
                   v_fecha_cotizacion,
                   v_id_cotizacion_adjudicada
            from mat.tcotizacion cot
            where cot.id_solicitud = v_parametros.id_solicitud
            and cot.adjudicado = 'si';



            select count(cot.id_cotizacion)
            into
            	   v_existe_cotizacion
            from mat.tcotizacion cot
            where cot.id_solicitud = v_parametros.id_solicitud
            and cot.adjudicado = 'si';

            if (v_existe_cotizacion > 0) then
            	v_exsite_cotizacion = 'si';
            else
            	v_exsite_cotizacion = 'no';
            end if;



            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Modificaciones eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'nro_cotizacion',v_nro_cotizacion::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'fecha_cotizacion',v_fecha_cotizacion::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion_adjudicada',v_id_cotizacion_adjudicada::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'existe_cotizacion',v_exsite_cotizacion::varchar);


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

ALTER FUNCTION mat.ft_log_modificaciones_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
