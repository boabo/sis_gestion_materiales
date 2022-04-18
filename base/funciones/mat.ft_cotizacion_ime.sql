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
    v_datos					varchar;


    /*Aumentando variables para recuperar el detalle*/
    v_nro_parte				varchar;
    v_nro_parte_alterno		varchar;
    v_referencia			varchar;
    v_descripcion			varchar;
    v_explicacion_detallada_part varchar;
    v_tipo					varchar;
    v_cantidad_sol			varchar;
    v_id_unidad_medida		varchar;
	v_codigo_medida			varchar;

    v_id_partida			integer;
    v_id_cuenta				integer;
    v_id_auxiliar			integer;
    v_registros_cig record;
    v_id_detalle_recu		varchar;
    v_id_cotizacion_det		integer;
    v_monto_total			numeric;

    v_suma_total			numeric;
    v_id_proceso_wf_so		integer;
    v_existencia_pac		integer;

    v_id_day_week			integer;
    v_precio_unitario		numeric;
    v_precio_unitario_mb	numeric;
    v_cd					varchar;
    v_id_detalle			integer;
    v_id_gestion			integer;
    v_id_centro_costo		integer;



	 v_id_orden_trabajo_recu integer;
     v_id_concepto_ingas_recu integer;
     v_id_partida_recu	integer;
     v_cuenta_recu 	integer;
     v_id_auxiliar_recu	integer;
     v_recuperar_datos_detalle varchar;
     v_origen_pedido	varchar;
     v_tiene_po			varchar;
     v_observacion		varchar;
     v_recomendacion	varchar;
     v_datos_solicitud	record;
     v_datos_cotizacion	record;

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

        	 /*Aqui verificamos si ya se tiene un proveedor adjudicado y si el proceso ya tiene un PO por tanto no se deberia
              modificar el adjudicado*/
              select sol.nro_po, sol.origen_pedido into v_tiene_po, v_origen_pedido
              from mat.tsolicitud sol
              where sol.id_solicitud = v_parametros.id_solicitud;
              /***************************************************************************************************************/

              if (v_origen_pedido != 'Reparación de Repuestos') then
              	if (v_tiene_po != '') then

                	/*Aumentando condicion para la modificar ciertos campos*/

                    select sol.* into
                    	   v_datos_solicitud
                    from mat.tsolicitud sol
                    where sol.id_solicitud = v_parametros.id_solicitud;

                    -- if (v_datos_solicitud.tipo_evaluacion != v_parametros.tipo_evaluacion) then
                    --	raise exception 'No se puede modificar el tipo de Evaluación de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                   -- end if;

                    if (v_datos_solicitud.id_condicion_entrega_alkym != v_parametros.id_condicion_entrega) then
                    	raise exception 'No se puede modificar la condicion de entrega de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.id_forma_pago_alkym != v_parametros.id_forma_pago) then
                    	raise exception 'No se puede modificar la forma de pago de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.id_modo_envio_alkym != v_parametros.id_modo_envio) then
                    	raise exception 'No se puede modificar el modo de envio de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.id_puntos_entrega_alkym != v_parametros.id_puntos_entrega) then
                    	raise exception 'No se puede modificar los puntos de entrega de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                     if (v_datos_solicitud.id_tipo_transaccion_alkym != v_parametros.id_tipo_transaccion) then
                    	raise exception 'No se puede modificar el tipo de transaccion de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.id_orden_destino_alkym != v_parametros.id_orden_destino) then
                    	raise exception 'No se puede modificar la orden de destino de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.codigo_condicion_entrega_alkym != v_parametros.codigo_condicion_entrega) then
                    	raise exception 'No se puede modificar condicion de entrega de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.codigo_forma_pago_alkym != v_parametros.codigo_forma_pago) then
                    	raise exception 'No se puede modificar la forma de pago de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.codigo_modo_envio_alkym != v_parametros.codigo_modo_envio) then
                    	raise exception 'No se puede modificar el modo de envio de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.codigo_puntos_entrega_alkym != v_parametros.codigo_puntos_entrega) then
                    	raise exception 'No se puede modificar los puntos de entrega de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.codigo_tipo_transaccion_alkym != v_parametros.codigo_tipo_transaccion) then
                    	raise exception 'No se puede modificar el tipo de transaccion de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_solicitud.codigo_orden_destino_alkym != v_parametros.codigo_orden_destino) then
                    	raise exception 'No se puede modificar la orden destino de la solicitud ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    /*Control para la modicacion de la cotizacion*/
                    select cot.* into
                    	   v_datos_cotizacion
                    from mat.tcotizacion cot
                    where cot.id_cotizacion = v_parametros.id_cotizacion;
                    /***********************************************************/

                    if (v_datos_cotizacion.id_solicitud != v_parametros.id_solicitud) then
                    	raise exception 'No se puede modificar la relacion de la cotización ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_cotizacion.id_moneda != v_parametros.id_moneda) then
                    	raise exception 'No se puede modificar la moneda de la cotización ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_cotizacion.fecha_cotizacion != v_parametros.fecha_cotizacion) then
                    	raise exception 'No se puede modificar la fecha de la cotización ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_cotizacion.id_proveedor != v_parametros.id_proveedor) then
                    	raise exception 'No se puede modificar al proveedor de la cotización ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                    if (v_datos_cotizacion.nro_cotizacion != v_parametros.nro_cotizacion) then
                    	raise exception 'No se puede modificar el nro de cotización de la cotización ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;

                     if (v_datos_cotizacion.pie_pag != v_parametros.pie_pag) then
                    	raise exception 'No se puede modificar el pie de página de la cotización ya que el tramite tiene una obligacion de pago y un PO generado en alkym que podrián ser afectados';
                    end if;



                    if (v_datos_cotizacion.recomendacion != v_parametros.recomendacion) then
                    	--raise exception 'No se puede realizar modificaciones ya que se Generó un PO, y la obligacion de pago.';
                    end if;

                    if (v_datos_cotizacion.obs != v_parametros.obs) then
                    	--raise exception 'No se puede realizar modificaciones ya que se Generó un PO, y la obligacion de pago.';
                    end if;

                    /*******************************************************/








                end if;
              end if;


        	/*Aqui actualizamos el tipo de evaluacion*/
        	update mat.tsolicitud set
            tipo_evaluacion = v_parametros.tipo_evaluacion,
            /*Aumentando para los datos de alkym Ismael Valdivia 22/04/2020*/
            id_condicion_entrega_alkym = v_parametros.id_condicion_entrega,
            id_forma_pago_alkym = v_parametros.id_forma_pago,
            id_modo_envio_alkym = v_parametros.id_modo_envio,
            id_puntos_entrega_alkym = v_parametros.id_puntos_entrega,
            id_tipo_transaccion_alkym =  v_parametros.id_tipo_transaccion,
            id_orden_destino_alkym = v_parametros.id_orden_destino,

            codigo_condicion_entrega_alkym = v_parametros.codigo_condicion_entrega,
            codigo_forma_pago_alkym = v_parametros.codigo_forma_pago,
            codigo_modo_envio_alkym = v_parametros.codigo_modo_envio,
            codigo_puntos_entrega_alkym = v_parametros.codigo_puntos_entrega,
            codigo_tipo_transaccion_alkym = v_parametros.codigo_tipo_transaccion,
            codigo_orden_destino_alkym = v_parametros.codigo_orden_destino
            /***************************************************************/
            where id_solicitud = v_parametros.id_solicitud;
        	/**********************************************/

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


        /*Aqui verificamos si ya se tiene un proveedor adjudicado y si el proceso ya tiene un PO por tanto no se deberia
        modificar el adjudicado*/
        select sol.nro_po into v_tiene_po
        from mat.tsolicitud sol
        where sol.id_solicitud = v_proveedor.id_solicitud;
        /***************************************************************************************************************/


        if (v_tiene_po != '') then
        	raise exception 'No se puede modificar el proveedor adjudicado ya que se Generó un PO, y la obligacion de pago.';
        end if;




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

        /*********************************
        #TRANSACCION:  'MAT_REC_DET_SOL'
        #DESCRIPCION:	Recuperacion de datos para el detalle solicitud
        #AUTOR:		Ismael Valdivia
        #FECHA:		20-03-2020 08:10:00
        ***********************************/

        elsif(p_transaccion='MAT_REC_DET_SOL')then

            begin

                select
                      string_agg (d.nro_parte::VARCHAR,'||'),
                      string_agg (d.nro_parte_alterno,'||'),
                      string_agg (d.referencia,'||'),
                      string_agg (d.descripcion,'||'),
                      string_agg (d.explicacion_detallada_part::VARCHAR,'||'),
                      string_agg (d.tipo::VARCHAR,'||'),
                      string_agg (d.cantidad_sol::VARCHAR,'||'),
                      string_agg (d.id_unidad_medida::VARCHAR,'||'),
                      string_agg (med.codigo::VARCHAR,'||'),
                      string_agg (d.id_detalle::VARCHAR,'||')
                      into
                      v_nro_parte,
                      v_nro_parte_alterno,
                      v_referencia,
                      v_descripcion,
                      v_explicacion_detallada_part,
                      v_tipo,
                      v_cantidad_sol,
                      v_id_unidad_medida,
                      v_codigo_medida,
                      v_id_detalle_recu
                from mat.tdetalle_sol d
                inner JOIN mat.tunidad_medida med on med.id_unidad_medida = d.id_unidad_medida
                where d.id_solicitud = v_parametros.id_solicitud::integer and d.estado_reg = 'activo'
                and d.estado_excluido = 'no';


                --Definicion de la respuesta
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización  modificado(a)');
                v_resp = pxp.f_agrega_clave(v_resp,'nro_parte',v_nro_parte::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'nro_parte_alterno',v_nro_parte_alterno::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'referencia',v_referencia::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'descripcion',v_descripcion::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'explicacion_detallada_part',v_explicacion_detallada_part::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'tipo',v_tipo::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'cantidad_sol',v_cantidad_sol::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'id_unidad_medida',v_id_unidad_medida::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'cod_medida',v_codigo_medida::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_id_detalle_recu::varchar);

                --Devuelve la respuesta
                return v_resp;

            end;

    /*********************************
 	#TRANSACCION:  'MAT_CTS_COMPLE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		23/03/2020
	***********************************/

	elsif(p_transaccion='MAT_CTS_COMPLE_INS')then

        begin
        select 	s.nro_tramite,
        		s.id_solicitud,
                s.origen_pedido
        into
        v_nro_tramite,
        v_id_solicitud,
        v_origen_pedido
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

         /*Recuperaremos el id_auxiliar, id_cuenta, y el id_partida para la actualizacion del detalle solicitud (Ismael Valdivia 23/03/2020)*/

           --obtener partida, cuenta auxiliar del concepto de gasto
                 v_id_partida = NULL;
                --recueprar la partida de la parametrizacion
              /*IF (v_parametros.id_concepto_ingas is not null) then

              	select
                cig.desc_ingas
                into
                v_registros_cig
                from param.tconcepto_ingas cig
                where cig.id_concepto_ingas =  v_parametros.id_concepto_ingas;

                  SELECT
                    ps_id_partida ,
                    ps_id_cuenta,
                    ps_id_auxiliar
                  into
                    v_id_partida,
                    v_id_cuenta,
                    v_id_auxiliar
                 FROM conta.f_get_config_relacion_contable('CUECOMP', v_parametros.id_gestion, v_parametros.id_concepto_ingas, v_parametros.id_centro_costo,  'No se encontro relación contable para el conceto de gasto: '||v_registros_cig.desc_ingas||'. <br> Mensaje: ');
              end if;*/

            /****************************************************************************************/

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
            pie_pag,
            adjudicado,
            id_proveedor_contacto
          	) values(
			v_parametros.id_solicitud,
			2,
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
            v_parametros.pie_pag,
            v_parametros.adjudicado,
            v_parametros.id_proveedor_contacto
			)RETURNING id_cotizacion into v_id_cotizacion;

            /*Aqui actualizamos los datos al detalle de la solicitud (Ismael Valdivia 23/03/2020)*/
             /*IF (v_parametros.id_concepto_ingas is not null) then
                UPDATE mat.tdetalle_sol SET
                id_centro_costo = v_parametros.id_centro_costo,
                id_concepto_ingas = v_parametros.id_concepto_ingas,
                id_orden_trabajo = v_parametros.id_orden_trabajo,
                id_partida = v_id_partida,
                id_cuenta = v_id_cuenta,
                id_auxiliar = v_id_auxiliar
                WHERE id_solicitud = v_parametros.id_solicitud;
             end if;    */
            /*************************************************************************************/
           IF (v_parametros.adjudicado = 'si') then
           	  /*Validamos la informacion para ver si falta datos que se envia al Alkym*/
              	if ( v_origen_pedido != 'Reparación de Repuestos') then
                  if (v_parametros.codigo_condicion_entrega is null or v_parametros.codigo_condicion_entrega = '') then
                      raise exception 'El campo <b>condicion de entrega</b> no puede ser Vacio favor verifique';
                  end if;

                  if (v_parametros.codigo_forma_pago is null or v_parametros.codigo_forma_pago = '') then
                      raise exception 'El campo <b>forma de pago</b> no puede ser Vacio favor verifique';
                  end if;

                  if (v_parametros.codigo_modo_envio is null or v_parametros.codigo_modo_envio = '') then
                      raise exception 'El campo <b>modo de envio</b> no puede ser Vacio favor verifique';
                  end if;

                  if (v_parametros.codigo_puntos_entrega is null or v_parametros.codigo_puntos_entrega = '') then
                      raise exception 'El campo <b>puntos de entrega</b> no puede ser Vacio favor verifique';
                  end if;

                  if (v_parametros.codigo_tipo_transaccion is null or v_parametros.codigo_tipo_transaccion = '') then
                      raise exception 'El campo <b>tipo transacciones</b> no puede ser Vacio favor verifique';
                  end if;

                  if (v_parametros.codigo_tipo_transaccion is null or v_parametros.codigo_tipo_transaccion = '') then
                      raise exception 'El campo <b>tipo transacciones</b> no puede ser Vacio favor verifique';
                  end if;

                  if (v_parametros.codigo_orden_destino is null or v_parametros.codigo_orden_destino = '') then
                      raise exception 'El campo <b>orden de destino</b> no puede ser Vacio favor verifique';
                  end if;
                end if;
           	  /************************************************************************/





            	update mat.tsolicitud set
                id_proveedor = v_parametros.id_proveedor,
                fecha_cotizacion = v_parametros.fecha_cotizacion,
                tipo_evaluacion = v_parametros.tipo_evaluacion,

                /*Aumentando para los datos de alkym Ismael Valdivia 22/04/2020*/
                id_condicion_entrega_alkym = v_parametros.id_condicion_entrega,
                id_forma_pago_alkym = v_parametros.id_forma_pago,
                id_modo_envio_alkym = v_parametros.id_modo_envio,
                id_puntos_entrega_alkym = v_parametros.id_puntos_entrega,
                id_tipo_transaccion_alkym = v_parametros.id_tipo_transaccion,
                id_orden_destino_alkym = v_parametros.id_orden_destino,

                codigo_condicion_entrega_alkym = v_parametros.codigo_condicion_entrega,
                codigo_forma_pago_alkym = v_parametros.codigo_forma_pago,
                codigo_modo_envio_alkym = v_parametros.codigo_modo_envio,
                codigo_puntos_entrega_alkym = v_parametros.codigo_puntos_entrega,
                codigo_tipo_transaccion_alkym = v_parametros.codigo_tipo_transaccion,
                codigo_orden_destino_alkym = v_parametros.codigo_orden_destino,
                direccion_punto_entrega_alkym = v_parametros.direccion_punto_entrega
                --fecha_entrega = v_parametros.fecha_entrega
                /***************************************************************/
                where id_solicitud = v_parametros.id_solicitud;
            end if;



			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización  almacenado(a) con exito (id_cotizacion'||v_id_cotizacion||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion',v_id_cotizacion::varchar);
            --v_resp = pxp.f_agrega_clave(v_resp,'referencial',v_parametros.referencial::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_parametros.id_solicitud::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

        /*********************************
 	#TRANSACCION:  'MAT_COT_DETCOM_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		23/03/2020
	***********************************/

	elsif(p_transaccion='MAT_COT_DETCOM_INS')then

        begin

            if (pxp.f_existe_parametro(p_tabla,'id_detalle')) then
                v_id_detalle = v_parametros.id_detalle;
            else
            	v_id_detalle = null;
            end if;

			if (pxp.f_existe_parametro(p_tabla,'precio_unitario')) then
                v_precio_unitario = v_parametros.precio_unitario;
            else
            	v_precio_unitario = null;
            end if;

            if (pxp.f_existe_parametro(p_tabla,'precio_unitario_mb')) then
                v_precio_unitario_mb = v_parametros.precio_unitario_mb;
            else
            	v_precio_unitario_mb = null;
            end if;

            if (pxp.f_existe_parametro(p_tabla,'cd')) then
                v_cd = v_parametros.cd;
            else
            	v_cd = '';
            end if;

            if (pxp.f_existe_parametro(p_tabla,'id_day_week')) then
                v_id_day_week = v_parametros.id_day_week;
            else
            	v_id_day_week = null;
            end if;

            /*****************Aqui condicion para obviar el HAZMAT (Ismael Valdivia 17/11/2021)*******************************/
            if(v_parametros.nro_parte_cot != 'HAZMAT') then
                  if (v_precio_unitario is null or v_precio_unitario_mb is null or v_cd = '' /*or v_id_day_week is null*/) then
                      raise exception 'Debe completar la información del detalle';
                  end if;
            end if;
            /*****************************************************************************************************************/


            /*Aqui aumentamos la condicion para registrar nuevos detalles de la cotizacion (Ismael Valdivia 29/04/2020)*/
			/*if (v_id_detalle is null) then
            	select nextval('mat.tdetalle_sol_id_detalle_seq') into v_id_detalle;

                /*Aqui realizamos la inserccion*/
                /*Recuperamos el id _gestion para recuperar el centro de costo (Ismael Valdivia 29/04/2020)*/
        	select sol.id_gestion into v_id_gestion
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;
        	/*******************************************************************************************/

            /*Aqui Recuperamos el centro de costo de acuerdo a la gestion (Ismael Valdivia 29/04/2020)*/
            select cc.id_centro_costo into v_id_centro_costo
            from param.tcentro_costo cc
            inner join param.ttipo_cc tc on tc.id_tipo_cc = cc.id_tipo_cc
            where tc.codigo = '845' and cc.id_gestion = v_id_gestion;
            /******************************************************************************************/


             /*Aqui aumentando para recuperar la partida y el centro de costo del PartNumber Seleccionado Ismael Valdivia (22/11/2021)*/
             v_recuperar_datos_detalle =  pxp.f_get_variable_global('mat_recuperar_datos_detalle');

            if (v_recuperar_datos_detalle = 'si') then

            	  select soli.origen_pedido into v_origen_pedido
                  from mat.tsolicitud soli
                  where soli.id_solicitud = v_parametros.id_solicitud;


                  select
                         det.id_concepto_ingas
                  into
                         v_id_concepto_ingas_recu
                  from mat.tdetalle_sol det
                  inner join mat.tsolicitud sol on sol.id_solicitud = det.id_solicitud
                  where sol.origen_pedido = v_origen_pedido
                  and trim(det.nro_parte) = trim(regexp_replace(v_parametros.nro_parte_cot,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'))
                  order by det.fecha_reg desc
                  limit 1;

            	 if (v_id_concepto_ingas_recu is not null) then
                  select
                  cig.desc_ingas
                  into
                  v_registros_cig
                  from param.tconcepto_ingas cig
                  where cig.id_concepto_ingas =  v_id_concepto_ingas_recu;

                  --recueprar la partida de la parametrizacion
                   SELECT
                      ps_id_partida ,
                      ps_id_cuenta,
                      ps_id_auxiliar
                    into
                      v_id_partida_recu,
                      v_cuenta_recu,
                      v_id_auxiliar_recu
                   FROM conta.f_get_config_relacion_contable('CUECOMP', v_id_gestion, v_id_concepto_ingas_recu, v_id_centro_costo,  'No se encontro relación contable para el conceto de gasto: '||v_registros_cig.desc_ingas||'. <br> Mensaje: ');
                 end if;
                  /********************************************************************************************/
                  if (v_origen_pedido not in ('Almacenes Consumibles o Rotables','Centro de Entrenamiento Aeronautico Civil')) then
                  /*Aqui para recueprar la OT*/
                  select sol.id_matricula into v_id_orden_trabajo_recu
                  from mat.tsolicitud sol
                  where sol.id_solicitud = v_parametros.id_solicitud;
                  else
                  	select
                           det.id_orden_trabajo
                      into
                             v_id_orden_trabajo_recu
                      from mat.tdetalle_sol det
                      inner join mat.tsolicitud sol on sol.id_solicitud = det.id_solicitud
                      where sol.origen_pedido = v_origen_pedido
                      and trim(det.nro_parte) = trim(regexp_replace(v_parametros.nro_parte_cot,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'))
                      order by det.fecha_reg desc
                      limit 1;
                  end if;
            else
            	v_id_concepto_ingas_recu = null;
                v_id_partida_recu = null;
                v_cuenta_recu = null;
                v_id_auxiliar_recu = null;
                v_id_orden_trabajo_recu = null;
        	end if;
            /********************************************************************************************/


        	--RAISE EXCEPTION 'parametros %',v_parametros;
                  --Sentencia de la insercion
                  insert into mat.tdetalle_sol(
                  id_detalle,
                  id_solicitud,
                  descripcion,
                  estado_reg,
                  id_unidad_medida,
                  nro_parte,
                  referencia,
                  nro_parte_alterno,
                  cantidad_sol,
                  precio_unitario,
                  id_usuario_reg,
                  usuario_ai,
                  fecha_reg,
                  id_usuario_ai,
                  id_usuario_mod,
                  fecha_mod,
                  tipo,
                  explicacion_detallada_part,
                  id_centro_costo,
                  /*Aumentando para insertar lo recuperado*/
                  id_concepto_ingas,
                  id_partida,
                  id_cuenta,
                  id_auxiliar,
                  id_orden_trabajo
                  /****************************************/
                  ) values(
                  v_id_detalle,
                  v_parametros.id_solicitud,
                  v_parametros.descripcion_cot,
                  'activo',
                  v_parametros.id_unidad_medida,
                  v_parametros.nro_parte_cot,
                  v_parametros.referencia_cot,
                  v_parametros.nro_parte_alterno_cot,
                  v_parametros.cantidad_det,
                  v_parametros.precio_unitario,
                  p_id_usuario,
                  v_parametros._nombre_usuario_ai,
                  now(),
                  v_parametros._id_usuario_ai,
                  null,
                  null,
                  v_parametros.tipo_cot,
                  v_parametros.explicacion_detallada_part_cot,
                  v_id_centro_costo,
                  /*Aumentando para insertar los datos recuperados*/
                  v_id_concepto_ingas_recu,
                  v_id_partida_recu,
                  v_cuenta_recu,
                  v_id_auxiliar_recu,
                  v_id_orden_trabajo_recu
                  /************************************************/
                  );
                /*******************************/

                 /*Aumentamos la condicion para que se actualize el monto PAC en la solicitud (Ismael Valdivia 19/02/2020)*/

                  select sum(COALESCE (det.precio_total,0)) into v_suma_total
                  from mat.tdetalle_sol det
                  where det.id_solicitud = v_parametros.id_solicitud;


                  if (v_suma_total > 0) then


                      --para insertar monto_pac en tsolicitud_pac
                       select so.id_proceso_wf
                       into v_id_proceso_wf_so
                       from mat.tsolicitud so
                       where so.id_solicitud = v_parametros.id_solicitud;


                       select count(*) into v_existencia_pac
                       from mat.tsolicitud_pac
                       where id_proceso_wf = v_id_proceso_wf_so;

                               if(v_existencia_pac > 0)then

                                  update mat.tsolicitud_pac set
                                      monto = v_suma_total,
                                      observaciones = ''
                                  where id_proceso_wf = v_id_proceso_wf_so;

                               ELSE
                                  INSERT INTO mat.tsolicitud_pac(
                                    id_proceso_wf,
                                    monto,
                                    observaciones
                                  )
                                  VALUES (
                                    v_id_proceso_wf_so,
                                    v_suma_total,
                                    ''
                                  );
                                END IF;
                         -----


                  end if;
                  /****************************************************************************************************************************/




            end if;*/
			/***********************************************************************************************************/

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
                          id_unidad_medida_cot,
                          revisado,
                          referencial,
                          cd,
                          id_day_week
                          ) values(
                          v_parametros.id_cotizacion,
                          v_id_detalle,
                          v_parametros.id_solicitud,
                          v_parametros.precio_unitario,
                          'activo',
                          v_parametros.precio_unitario_mb,
                          v_parametros._id_usuario_ai,
                          p_id_usuario,
                          v_parametros._nombre_usuario_ai,
                          now(),
                          null,
                          null,
                          v_parametros.nro_parte_cot,
                          v_parametros.nro_parte_alterno_cot,
                          v_parametros.referencia_cot,
                          v_parametros.descripcion_cot,
                          v_parametros.explicacion_detallada_part_cot,
                          v_parametros.tipo_cot,
                          v_parametros.cantidad_det,
                          v_parametros.id_unidad_medida,
                          'si',
                          'No',
                          v_parametros.cd,
                          v_id_day_week--v_parametros.id_day_week
                          )RETURNING id_cotizacion_det into v_id_cotizacion_det;


              /*Aqui Actualizamos el detalle de la solicitud Ismael Valdivia (23/03/2020)*/
             /* if (v_parametros.referencial = 'Si') then
                  UPDATE mat.tdetalle_sol SET
                          cantidad_sol = v_parametros.cantidad_det,
                          precio_unitario = v_parametros.precio_unitario,
                          precio_total = v_parametros.precio_unitario_mb
                  WHERE id_detalle = v_id_detalle;

        	  /*Aumentamos la condicion para que se actualize el monto PAC en la solicitud (Ismael Valdivia 19/02/2020)*/

                select sum(COALESCE (det.precio_total,0)) into v_suma_total
                from mat.tdetalle_sol det
                where det.id_solicitud = v_parametros.id_solicitud;

                if (v_suma_total > 0) then
                    --para insertar monto_pac en tsolicitud_pac
                     select so.id_proceso_wf
                     into v_id_proceso_wf_so
                     from mat.tsolicitud so
                     where so.id_solicitud = v_parametros.id_solicitud;

                     select count(*) into v_existencia_pac
                     from mat.tsolicitud_pac
                     where id_proceso_wf = v_id_proceso_wf_so;

                             if(v_existencia_pac > 0)then
                                update mat.tsolicitud_pac set
                                    monto = v_suma_total
                                where id_proceso_wf = v_id_proceso_wf_so;
                             ELSE
                                INSERT INTO mat.tsolicitud_pac(
                                  id_proceso_wf,
                                  monto,
                                  observaciones
                                )
                                VALUES (
                                  v_id_proceso_wf_so,
                                  v_suma_total,
                                  ''
                                );
                              END IF;

                end if;
        	  /*********************************************************************************************************/




              end if;*/
              /***************************************************************************/

              /*Aqui actualizamos el monto total en la cabezera*/
              select sum(det.precio_unitario_mb) into v_monto_total
              from mat.tcotizacion_detalle det
              where det.id_cotizacion = v_parametros.id_cotizacion;

              UPDATE mat.tcotizacion SET
                      monto_total = v_monto_total
              WHERE id_cotizacion = v_parametros.id_cotizacion;
              /*************************************************/


			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización  almacenado(a) con exito (id_cotizacion_det'||v_id_cotizacion_det||')');

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

ALTER FUNCTION mat.ft_cotizacion_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
