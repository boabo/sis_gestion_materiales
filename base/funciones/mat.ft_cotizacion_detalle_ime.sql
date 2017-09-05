CREATE OR REPLACE FUNCTION mat.ft_cotizacion_detalle_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_cotizacion_detalle_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tcotizacion_detalle'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        04-07-2017 14:51:54
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
	v_id_cotizacion_det		integer;
    v_total					numeric;
    v_monto_total			numeric;
    v_total_ca					numeric;
    v_total_cotizacion					numeric;
    v_id_cotizacion 		integer;

    --v_id_detalle		integer;
    --v_precio			numeric;

BEGIN

    v_nombre_funcion = 'mat.ft_cotizacion_detalle_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_CDE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:51:54
	***********************************/

	if(p_transaccion='MAT_CDE_INS')then
        begin



        	--Sentencia de la insercion
        	insert into mat.tcotizacion_detalle(
			id_cotizacion,
			id_detalle,
			id_solicitud,
			cantidad_det,
			precio_unitario,
			estado_reg,
			precio_unitario_mb,
			id_usuario_ai,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			fecha_mod,
			id_usuario_mod,
            cd,
            id_day_week,
            nro_parte_cot,
            nro_parte_alterno_cot,
            referencia_cot,
            descripcion_cot,
            explicacion_detallada_part_cot,
            tipo_cot,
            revisado
          	) values(
			v_parametros.id_cotizacion,
			v_parametros.id_detalle,
			v_parametros.id_solicitud,
			v_parametros.cantidad_det,
			v_parametros.precio_unitario,
			'activo',
			v_parametros.cantidad_det * v_parametros.precio_unitario,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			null,
			null,
            v_parametros.cd,
            v_parametros.id_day_week,
            v_parametros.nro_parte_cot,
            v_parametros.nro_parte_alterno_cot,
            v_parametros.referencia_cot,
            v_parametros.descripcion_cot,
            v_parametros.explicacion_detallada_part_cot,
            v_parametros.tipo_cot,
            case
            when v_parametros.precio_unitario > 0 then
            'si'
        	else
            'no'
            end::varchar
			)RETURNING id_cotizacion_det into v_id_cotizacion_det;

        select sum (d.precio_unitario_mb)
        into
        v_monto_total
        from mat.tcotizacion_detalle d
        where d.id_cotizacion = v_parametros.id_cotizacion;

        update mat.tcotizacion set
        monto_total =  v_monto_total
        where id_cotizacion = v_parametros.id_cotizacion;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización Detalle almacenado(a) con exito (id_cotizacion_det'||v_id_cotizacion_det||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion_det',v_id_cotizacion_det::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_CDE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:51:54
	***********************************/

	elsif(p_transaccion='MAT_CDE_MOD')then

		begin

       if v_parametros.precio_unitario > 0 then
        v_total = v_parametros.cantidad_det * v_parametros.precio_unitario;
    	update mat.tcotizacion_detalle  set
    	revisado = 'si'
        where id_cotizacion_det = v_parametros.id_cotizacion_det;
        else
        v_total = 0;
        update mat.tcotizacion_detalle  set
    	revisado = 'no'
        where id_cotizacion_det = v_parametros.id_cotizacion_det;
        end if;


			--Sentencia de la modificacion
			update mat.tcotizacion_detalle set
			id_cotizacion = v_parametros.id_cotizacion,
			id_detalle = v_parametros.id_detalle,
			id_solicitud = v_parametros.id_solicitud,
			cantidad_det = v_parametros.cantidad_det,
			precio_unitario = v_parametros.precio_unitario,
			precio_unitario_mb = v_total,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            cd = v_parametros.cd,
            id_day_week = v_parametros.id_day_week,
            nro_parte_cot = v_parametros.nro_parte_cot,
            nro_parte_alterno_cot = v_parametros.nro_parte_alterno_cot,
            referencia_cot = v_parametros.referencia_cot,
            descripcion_cot = v_parametros.descripcion_cot,
            explicacion_detallada_part_cot = v_parametros.explicacion_detallada_part_cot,
            tipo_cot = v_parametros.tipo_cot

			where id_cotizacion_det=v_parametros.id_cotizacion_det;

       select sum (d.precio_unitario_mb)
        into
        v_monto_total
        from mat.tcotizacion_detalle d
        where d.id_cotizacion = v_parametros.id_cotizacion;

        update mat.tcotizacion set
        monto_total =  v_monto_total
        where id_cotizacion = v_parametros.id_cotizacion;


			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización Detalle modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion_det',v_parametros.id_cotizacion_det::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_CDE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:51:54
	***********************************/

	elsif(p_transaccion='MAT_CDE_ELI')then

		begin

        select precio_unitario_mb,
        id_cotizacion
        into
        v_total_cotizacion,
        v_id_cotizacion
        from mat.tcotizacion_detalle d
        where id_cotizacion_det=v_parametros.id_cotizacion_det;

        select monto_total
        into
        v_total
        from mat.tcotizacion
        where id_cotizacion = v_id_cotizacion;

        update mat.tcotizacion  set
        monto_total = v_total - v_total_cotizacion
        where id_cotizacion = v_id_cotizacion;


			--Sentencia de la eliminacion
			delete from mat.tcotizacion_detalle
            where id_cotizacion_det=v_parametros.id_cotizacion_det;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización Detalle eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion_det',v_parametros.id_cotizacion_det::varchar);

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