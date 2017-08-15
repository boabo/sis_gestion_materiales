CREATE OR REPLACE FUNCTION mat.ft_cotizacion_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_cotizacion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tcotizacion'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        04-07-2017 14:03:30
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
	v_id_cotizacion			integer;
    v_nro_tramite			varchar;
    v_id_solicitud			integer;
    v_registros 			record;
    v_valor					varchar;
    v_proveedor				record;
    v_id_pro				integer;
    v_nom_proveedor			varchar;


BEGIN

    v_nombre_funcion = 'mat.ft_cotizacion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_CTS_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/

	if(p_transaccion='MAT_CTS_INS')then

        begin
        select 	s.nro_tramite,
        		s.id_solicitud
        into
        v_nro_tramite,
        v_id_solicitud
        from mat.tsolicitud s
       	where s.id_solicitud = v_parametros.id_solicitud;


        select 	c.id_proveedor,
        		p.desc_proveedor
        into
        v_id_pro,
        v_nom_proveedor
        from mat.tcotizacion c
        inner join param.vproveedor p on p.id_proveedor = c.id_proveedor
        where c.id_solicitud = v_parametros.id_solicitud;

        if v_id_pro = v_parametros.id_proveedor then
        raise exception 'El proveedor % ya fue registrato',v_nom_proveedor;
        end if;

        	--Sentencia de la insercion
        	insert into mat.tcotizacion(
			id_solicitud,
			id_moneda,
			nro_tramite,
			fecha_cotizacion,
			estado_reg,
			id_proveedor,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
            nro_cotizacion,
            recomendacion,
            obs,
            pie_pag
          	) values(
			v_parametros.id_solicitud,
			v_parametros.id_moneda,
			v_nro_tramite,
			v_parametros.fecha_cotizacion,
			'activo',
			v_parametros.id_proveedor,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.nro_cotizacion,
            v_parametros.recomendacion,
            v_parametros.obs,
            v_parametros.pie_pag
			)RETURNING id_cotizacion into v_id_cotizacion;
          --  RAISE EXCEPTION 'llega';
            FOR v_registros in( select 	d.id_detalle,
                                        d.id_solicitud,
                                        d.nro_parte,
                                        d.nro_parte_alterno,
                                        d.referencia,
                                        d.descripcion,
                                        d.explicacion_detallada_part,
                                        d.tipo,
                                        d.cantidad_sol,
                                        d.id_unidad_medida
                                        from mat.tdetalle_sol d
                                        where d.id_solicitud = v_id_solicitud and d.estado_reg = 'activo')loop

                                        insert into mat.tcotizacion_detalle(
                                                    id_cotizacion,
                                                    id_detalle,
                                                    id_solicitud,
                                                    precio_unitario,
                                                    estado_reg,
                                                    precio_unitario_mb,
                                                    id_usuario_ai,
                                                    id_usuario_reg,
                                                    usuario_ai,
                                                    fecha_reg,
                                                    fecha_mod,
                                                    id_usuario_mod,
                                                    nro_parte_cot,
                                                    nro_parte_alterno_cot,
                                                    referencia_cot,
                                                    descripcion_cot,
                                                    explicacion_detallada_part_cot,
                                                    tipo_cot,
                                                    cantidad_det,
                                                    id_unidad_medida_cot
                                                    ) values(
                                                    v_id_cotizacion,
                                                    v_registros.id_detalle,
                                                    v_registros.id_solicitud,
                                                    0,
                                                    'activo',
                                                    0,
                                                    v_parametros._id_usuario_ai,
                                                    p_id_usuario,
                                                    v_parametros._nombre_usuario_ai,
                                                    now(),
                                                    null,
                                                    null,
                                                    v_registros.nro_parte,
                                                    v_registros.nro_parte_alterno,
                                                    v_registros.referencia,
                                                    v_registros.descripcion,
                                                    v_registros.explicacion_detallada_part,
                                                    v_registros.tipo,
                                                    v_registros.cantidad_sol,
                                                    v_registros.id_unidad_medida
                                                    );

            end loop;
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización  almacenado(a) con exito (id_cotizacion'||v_id_cotizacion||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion',v_id_cotizacion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_CTS_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/

	elsif(p_transaccion='MAT_CTS_MOD')then

		begin
			--Sentencia de la modificacion
			update mat.tcotizacion set
			id_solicitud = v_parametros.id_solicitud,
			id_moneda = v_parametros.id_moneda,
			fecha_cotizacion = v_parametros.fecha_cotizacion,
			id_proveedor = v_parametros.id_proveedor,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            nro_cotizacion = v_parametros.nro_cotizacion,
            recomendacion = v_parametros.recomendacion,
            obs = v_parametros.obs,
            pie_pag = v_parametros.pie_pag

			where id_cotizacion=v_parametros.id_cotizacion;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización  modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion',v_parametros.id_cotizacion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_CTS_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/

	elsif(p_transaccion='MAT_CTS_ELI')then

		begin
			--Sentencia de la eliminacion
            delete from mat.tcotizacion_detalle
            where id_cotizacion=v_parametros.id_cotizacion;

            delete from mat.tcotizacion
            where id_cotizacion=v_parametros.id_cotizacion;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización  eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion',v_parametros.id_cotizacion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_CTS_ADJ'
 	#DESCRIPCION:	control adjudicacion
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/

	elsif(p_transaccion='MAT_CTS_ADJ')then

		begin
        select	c.id_proveedor,
       			c.fecha_cotizacion,
                c.id_solicitud
                into
                v_proveedor
        from mat.tcotizacion c
        where c.id_cotizacion = v_parametros.id_cotizacion;
      -- raise exception 'll %',v_proveedor.id_solicitud;
			select adjudicado
            		into
                    v_valor
            from mat.tcotizacion
            where id_cotizacion = v_parametros.id_cotizacion;

            if v_valor = 'si' then
            update mat.tcotizacion  set
            fecha_mod = now(),
            adjudicado = 'no'
            where id_cotizacion = v_parametros.id_cotizacion;

            update mat.tsolicitud set
            id_proveedor = null,
            fecha_cotizacion = null
            where id_solicitud = v_proveedor.id_solicitud;

            end if;
            if v_valor = 'no' then
            update mat.tcotizacion  set
            fecha_mod = now(),
            adjudicado = 'si'
            where id_cotizacion = v_parametros.id_cotizacion;

            update mat.tsolicitud set
            id_proveedor = v_proveedor.id_proveedor,
            fecha_cotizacion = v_proveedor.fecha_cotizacion
            where id_solicitud = v_proveedor.id_solicitud;
            end if;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion',v_parametros.id_cotizacion::varchar);

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