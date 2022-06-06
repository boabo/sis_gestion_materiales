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
    v_datos 				record;

    v_valor					varchar;
    v_id_detalle			integer;
    v_precio_unitario		numeric;
    v_precio_total 			numeric;
    v_cantidad 				integer;

    v_valor_ref 			varchar;
   	v_id_detalle_ref		integer;

    v_id_solicitud			integer;
    v_cont_ref				integer;
    v_id_contizacion_det	integer;
    --v_id_detalle		integer;
    --v_precio			numeric;

    v_id_proceso_wf_so  	INTEGER;
    v_suma_total			numeric;
    v_existencia_pac		integer;
    v_id_gestion			integer;
    v_id_centro_costo		integer;
	v_estado_actual			varchar;
    v_interfaz_origen	varchar;
	 v_precio_recuperado	numeric;
     v_origen_pedido		varchar;
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

        	/*Aqui aumentamos esta condicion para que se inserte nuevas partes tanto en la cotizacion como Referencial*/
            /*select nextval('mat.tdetalle_sol_id_detalle_seq') into v_id_detalle;

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
                  precio_total,
                  id_usuario_reg,
                  usuario_ai,
                  fecha_reg,
                  id_usuario_ai,
                  id_usuario_mod,
                  fecha_mod,
                  tipo,
                  explicacion_detallada_part,
                  id_centro_costo
                  ) values(
                  v_id_detalle,
                  v_parametros.id_solicitud,
                  v_parametros.descripcion_cot,
                  'activo',
                  v_parametros.id_unidad_medida_cot,
                  v_parametros.nro_parte_cot,
                  v_parametros.referencia_cot,
                  v_parametros.nro_parte_alterno_cot,
                  v_parametros.cantidad_det,
                  v_parametros.precio_unitario,
                  (v_parametros.cantidad_det*v_parametros.precio_unitario),
                  p_id_usuario,
                  v_parametros._nombre_usuario_ai,
                  now(),
                  v_parametros._id_usuario_ai,
                  null,
                  null,
                  v_parametros.tipo_cot,
                  v_parametros.explicacion_detallada_part_cot,
                  v_id_centro_costo
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


                  end if;*/
                  /****************************************************************************************************************************/

            /**********************************************************************************************************/

        	--Sentencia de la insercion
        	insert into mat.tcotizacion_detalle(
			id_cotizacion,
			--id_detalle,
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
            revisado,
            referencial,
            id_unidad_medida_cot
          	) values(
			v_parametros.id_cotizacion,
			--v_id_detalle,--v_parametros.id_detalle,
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
            end::varchar,
            'No',
            v_parametros.id_unidad_medida_cot
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


        /*Aumentando para actualizar al precio referencial si es que es referencial (Ismael Valdivia 23/03/2020)*/
        select det.referencial,
               det.id_detalle
               into
               v_valor_ref,
               v_id_detalle_ref
        from mat.tcotizacion_detalle det
        where det.id_cotizacion_det = v_parametros.id_cotizacion_det;

        IF (v_valor_ref = 'Si') then
        	UPDATE mat.tdetalle_sol set
             cantidad_sol = v_parametros.cantidad_det,
             precio_unitario = v_parametros.precio_unitario,
             precio_total = (v_parametros.cantidad_det * v_parametros.precio_unitario)
            WHERE id_detalle = v_id_detalle_ref;


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
                            --observaciones = 'hola'
                        where id_proceso_wf = v_id_proceso_wf_so;

                     ELSE
                        INSERT INTO mat.tsolicitud_pac(
                          id_proceso_wf,
                          monto
                          --observaciones
                        )
                        VALUES (
                          v_id_proceso_wf_so,
                          v_suma_total
                          --'hola'
                        );
                      END IF;
               -----


        end if;




        end if;


        /*********************************************************************************************************/



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
                    v_datos
                    from mat.tcotizacion_detalle
                    where id_cotizacion_det=v_parametros.id_cotizacion_det;

            select c.monto_total
            into
            v_monto_total
            from mat.tcotizacion c
			where c.id_cotizacion = v_datos.id_cotizacion;

            update mat.tcotizacion  set
            monto_total  = v_monto_total - v_datos.precio_unitario_mb
            where id_cotizacion = v_datos.id_cotizacion;

			--Sentencia de la eliminacion
			delete from mat.tcotizacion_detalle
            where id_cotizacion_det=v_parametros.id_cotizacion_det;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización Detalle eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cotizacion_det',v_parametros.id_cotizacion_det::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_CDE_CLO'
 	#DESCRIPCION:	Clonar numero de parte
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:51:54
	***********************************/

	elsif(p_transaccion='MAT_CDE_CLO')then

		begin
		/*Si se clonara un Item tambien se debe reflejar en el detalle referencial*/
        select nextval('mat.tdetalle_sol_id_detalle_seq') into v_id_detalle;

            /*Aqui realizamos la inserccion*/
            /*Recuperamos el id _gestion para recuperar el centro de costo (Ismael Valdivia 29/04/2020)*/
        /*	select sol.id_gestion into v_id_gestion
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;*/
        	/*******************************************************************************************/

            /*Aqui Recuperamos el centro de costo de acuerdo a la gestion (Ismael Valdivia 29/04/2020)*/
        /*    select cc.id_centro_costo into v_id_centro_costo
            from param.tcentro_costo cc
            inner join param.ttipo_cc tc on tc.id_tipo_cc = cc.id_tipo_cc
            where tc.codigo = '845' and cc.id_gestion = v_id_gestion;*/
            /******************************************************************************************/


        /**************************************************************************/
        FOR v_datos IN (select 	cds.id_solicitud,
        						cds.id_cotizacion,
                                cds.id_detalle,
                                cds.nro_parte_cot,
                                cds.nro_parte_alterno_cot,
                                cds.referencia_cot,
                                cds.descripcion_cot,
                                cds.explicacion_detallada_part_cot,
                                cds.tipo_cot,
                                cds.id_unidad_medida_cot,
                                cds.cantidad_det
                                from mat.tcotizacion_detalle cds
                                where cds.id_cotizacion_det = v_parametros.id_detalle)LOOP

                                /*Insertamos en el detalle de la solicitud*/
                                  --Sentencia de la insercion
                                 /* insert into mat.tdetalle_sol(
                                  id_detalle,
                                  id_solicitud,
                                  descripcion,
                                  estado_reg,
                                  id_unidad_medida,
                                  nro_parte,
                                  referencia,
                                  nro_parte_alterno,
                                  cantidad_sol,
                                  --precio_unitario,
                                  --precio_total,
                                  id_usuario_reg,
                                  usuario_ai,
                                  fecha_reg,
                                  id_usuario_ai,
                                  id_usuario_mod,
                                  fecha_mod,
                                  tipo,
                                  explicacion_detallada_part,
                                  id_centro_costo
                                  ) values(
                                  v_id_detalle,
                                  v_parametros.id_solicitud,
                                  v_datos.descripcion_cot,
                                  'activo',
                                  v_datos.id_unidad_medida_cot,
                                  v_datos.nro_parte_cot,
                                  v_datos.referencia_cot,
                                  v_datos.nro_parte_alterno_cot,
                                  v_datos.cantidad_det,
                                  --v_parametros.precio_unitario,
                                  --(v_parametros.cantidad_det*v_parametros.precio_unitario),
                                  p_id_usuario,
                                  v_parametros._nombre_usuario_ai,
                                  now(),
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null,
                                  v_datos.tipo_cot,
                                  v_datos.explicacion_detallada_part_cot,
                                  v_id_centro_costo
                                  );*/
                                /******************************************/

                                insert into mat.tcotizacion_detalle(
                                                  id_cotizacion,
                                                  id_detalle,
                                                  id_solicitud,
                                                  id_unidad_medida_cot,
                                                  cantidad_det,
                                                  estado_reg,
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
                                                  revisado,
                                                  referencial
                                                  ) values(
                                                  v_datos.id_cotizacion,
                                                  v_datos.id_detalle,
                                                  v_datos.id_solicitud,
                                                  v_datos.id_unidad_medida_cot,
                                                  v_datos.cantidad_det,
                                                  'activo',
                                                  v_parametros._id_usuario_ai,
                                                  p_id_usuario,
                                                  v_parametros._nombre_usuario_ai,
                                                  now(),
                                                  null,
                                                  null,
                                                  v_datos.nro_parte_cot,
                                                  v_datos.nro_parte_alterno_cot,
                                                  v_datos.referencia_cot,
                                                  v_datos.descripcion_cot,
                                                  v_datos.explicacion_detallada_part_cot,
                                                  v_datos.tipo_cot,
                                                  'no',
                                                  'No'
                                                  );
        END LOOP;

           --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización Detalle clonad(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_parametros.id_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
      /*********************************
      #TRANSACCION:  'MAT_CDE_REFE'
      #DESCRIPCION:	control referencial
      #AUTOR:		Ismael Valdivia
      #FECHA:		23-03-2020 14:38:30
      ***********************************/

      elsif(p_transaccion='MAT_CDE_REFE')then

          begin

        -- raise exception 'll %',v_proveedor.id_solicitud;
              select referencial,
                     id_detalle,
                     precio_unitario,
                     precio_unitario_mb,
                     cantidad_det,
                     id_solicitud
                      into
                      v_valor,
                      v_id_detalle,
                      v_precio_unitario,
                      v_precio_total,
                      v_cantidad,
                      v_id_solicitud
              from mat.tcotizacion_detalle
              where id_cotizacion_det = v_parametros.id_cotizacion_det;

			  select sol.estado, sol.origen_pedido into v_estado_actual, v_origen_pedido
              from mat.tsolicitud sol
              where sol.id_solicitud = v_id_solicitud;


              if (v_estado_actual not in ('borrador','revision','cotizacion','cotizacion_solicitada','revision_tecnico_abastecimientos','finalizado')) then
              	raise exception 'No se puede modificar el precio Referencial ya que se comprometió presupuesto.';
              end if;




              if v_valor = 'Si' then

                  update mat.tcotizacion_detalle  set
                  fecha_mod = now(),
                  referencial = 'No'
                  where id_cotizacion_det = v_parametros.id_cotizacion_det;

                  select deta.interfaz_origen,
                           COALESCE(deta.precio_unitario,0)
                    into v_interfaz_origen, v_precio_recuperado
                    from mat.tdetalle_sol deta
                    where deta.id_detalle = v_id_detalle;

					if (v_origen_pedido != 'Reparación de Repuestos') then
                      if (v_precio_recuperado > 0) then
                          if (v_interfaz_origen = 'Tecnico Abastecimiento' or v_interfaz_origen = 'Tecnico Administrativo') then
                              if (v_interfaz_origen != v_parametros.interfaz_origen) then
                                  raise exception 'El Item ya tiene un precio Referencial registrado por el %, y solo el puede realizar la modificación.',v_interfaz_origen;
                              end if;
                          end if;
                      end if;
                    end if;


                  /*Aqui Actualizamos los montos cuando el detalle es referencial (Ismael valdivia 23/03/2020)*/
                  update mat.tdetalle_sol set
                  	     cantidad_sol = v_cantidad,
                         precio_unitario = 0,
                         precio_total = 0,
                         fecha_mod = now()
                  where id_detalle = v_id_detalle;
                  /********************************************************************************************/


              end if;

              if v_valor = 'No' then

              	  select count (det.id_cotizacion_det),
                         det.id_cotizacion_det
                  	     into
                         v_cont_ref,
                         v_id_contizacion_det
                  from mat.tcotizacion_detalle det
                  where det.id_detalle = v_id_detalle and det.referencial = 'Si'
                  group by det.id_cotizacion_det;

                  if (v_cont_ref > 0) then
                  	update mat.tcotizacion_detalle  set
                    fecha_mod = now(),
                    referencial = 'No'
                    where id_cotizacion_det = v_id_contizacion_det;
                  end if;

                  update mat.tcotizacion_detalle  set
                  fecha_mod = now(),
                  referencial = 'Si'
                  where id_cotizacion_det = v_parametros.id_cotizacion_det;

                  /*Aqui Actualizamos los montos cuando el detalle es referencial (Ismael valdivia 23/03/2020)*/

                    select deta.interfaz_origen,
                           COALESCE(deta.precio_unitario,0)
                    into v_interfaz_origen, v_precio_recuperado
                    from mat.tdetalle_sol deta
                    where deta.id_detalle = v_id_detalle;

					if (v_origen_pedido != 'Reparación de Repuestos') then
                      if (v_precio_recuperado > 0) then
                          if (v_interfaz_origen = 'Tecnico Abastecimiento' or v_interfaz_origen = 'Tecnico Administrativo') then
                              if (v_interfaz_origen != v_parametros.interfaz_origen) then
                                  raise exception 'El Item ya tiene un precio Referencial registrado por el %, y solo el puede realizar la modificación.',v_interfaz_origen;
                              end if;
                          end if;
                      end if;
					end if;


                  update mat.tdetalle_sol set
                  	     cantidad_sol = v_cantidad,
                         precio_unitario = v_precio_unitario,
                         precio_total = v_precio_total,
                         fecha_mod = now()
                  where id_detalle = v_id_detalle;
                  /********************************************************************************************/
                        select sum(COALESCE (det.precio_total,0)) into v_suma_total
                        from mat.tdetalle_sol det
                        where det.id_solicitud = v_id_solicitud
                        and det.estado_excluido = 'no';

                       if (v_suma_total > 0) then


                          --para insertar monto_pac en tsolicitud_pac
                           select so.id_proceso_wf
                           into v_id_proceso_wf_so
                           from mat.tsolicitud so
                           where so.id_solicitud = v_id_solicitud;


                           select count(*) into v_existencia_pac
                           from mat.tsolicitud_pac
                           where id_proceso_wf = v_id_proceso_wf_so;

                                   if(v_existencia_pac > 0)then

                                      update mat.tsolicitud_pac set
                                          monto = v_suma_total
                                          --observaciones = 'hola'
                                      where id_proceso_wf = v_id_proceso_wf_so;

                                   ELSE
                                      INSERT INTO mat.tsolicitud_pac(
                                        id_proceso_wf,
                                        monto
                                        --observaciones
                                      )
                                      VALUES (
                                        v_id_proceso_wf_so,
                                        v_suma_total
                                        --'hola'
                                      );
                                    END IF;
                             -----


                      end if;





              end if;

              --Definicion de la respuesta
              v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cotización');
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

ALTER FUNCTION mat.ft_cotizacion_detalle_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
