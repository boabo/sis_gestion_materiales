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
	v_id_detalle				integer;
    v_campos 					record;
    v_revisado 					VARCHAR;
    v_detalle 					record;

    v_parte 					varchar;
    v_msg_control				varchar;
    v_control_duplicidad 		record;

    v_nro_parte					varchar;
    v_nro_parte_alterno			varchar;

    /*Aumentamos las siguientes variables (Ismael valdivia 14/02/2020)*/
    v_id_partida 	integer;
    v_id_cuenta		integer;
    v_id_auxiliar 	integer;
    v_registros_cig record;
    v_id_solicitud	integer;
    v_id_proceso_wf_so  	INTEGER;
    v_suma_total	numeric;
    v_existencia_pac	integer;
    v_id_gestion	integer;

    v_id_centro_costo	integer;

    v_id_cc_rec			integer;
    v_id_concepto_ingas_rec integer;
    v_id_orden_trabajo_rec integer;

    v_id_tipo_cc		integer;
	v_filtro			varchar;
    v_ordenes			varchar;
    v_orden_trabajo		record;
    v_id_orden_trabajo	integer;
    v_filtro_ingas		record;
    v_filtro_ingas_ots	varchar;
    v_filtro_fecha_ingas	varchar;
    v_condicion			varchar;
    v_gestion			integer;
    v_id_cp				integer;
    v_ot				integer;

	 v_id_orden_trabajo_recu integer;
     v_id_concepto_ingas_recu integer;
     v_id_partida_recu	integer;
     v_cuenta_recu 	integer;
     v_id_auxiliar_recu	integer;
     v_recuperar_datos_detalle	varchar;
     v_origen_pedido	varchar;
     v_total_hazmat		numeric;
     v_existe_relacion	numeric;
     v_datos_cambiar	record;
     v_suma_hazmat		numeric;

     v_cantidad			numeric;
     v_precio_unitario  numeric;
     v_precio_total		numeric;
     v_total_detalle	numeric;



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


        if ( (pxp.f_existe_parametro(p_tabla,'id_concepto_ingas')) ) then

        	 /*Recuperaremos el id_auxiliar, id_cuenta, y el id_partida (Ismael Valdivia 14/02/2020)*/
            select
                cig.desc_ingas
                into
                v_registros_cig
                from param.tconcepto_ingas cig
                where cig.id_concepto_ingas =  v_parametros.id_concepto_ingas;

            --obtener partida, cuenta auxiliar del concepto de gasto

                 v_id_partida = NULL;

                --recueprar la partida de la parametrizacion
                if ( (pxp.f_existe_parametro(p_tabla,'id_gestion')) ) then
                	v_gestion = v_parametros.id_gestion;
                else
                	select g.id_gestion
           	 		into v_gestion
           			from param.tgestion g
           			where g.gestion = EXTRACT(YEAR FROM current_date);
                end if;

            	if (v_parametros.id_concepto_ingas is not null and v_parametros.id_centro_costo is not null) then

                    SELECT
                      ps_id_partida ,
                      ps_id_cuenta,
                      ps_id_auxiliar
                    into
                      v_id_partida,
                      v_id_cuenta,
                      v_id_auxiliar
                   FROM conta.f_get_config_relacion_contable('CUECOMP', v_gestion, v_parametros.id_concepto_ingas, v_parametros.id_centro_costo,  'No se encontro relación contable para el conceto de gasto: '||v_registros_cig.desc_ingas||'. <br> Mensaje: ');
			   end if;
            /****************************************************************************************/
        	/*Aqui concatenaremos para los repuestos en la condicion detalle el FOR*/

              select s.estado, s.id_gestion, s.id_matricula, s.fecha_solicitud , de.id_centro_costo, de.id_concepto_ingas, s.origen_pedido
              into v_detalle
              from mat.tsolicitud s
              inner join mat.tdetalle_sol de on de.id_solicitud = s.id_solicitud
              WHERE s.id_solicitud = v_parametros.id_solicitud;

              v_condicion = '';
              if ( (pxp.f_existe_parametro(p_tabla,'condicion_det')) ) then
                  IF (v_parametros.condicion_det != '' and v_parametros.condicion_det is not null) then
                          v_condicion = v_parametros.condicion_det;
                  end if;
              end if;
              /***********************************************************************/



        	--RAISE EXCEPTION 'parametros %',v_condicion;
        	--Sentencia de la insercion
        	insert into mat.tdetalle_sol(
			id_solicitud,
			descripcion,
			estado_reg,
			id_unidad_medida,
			nro_parte,
			referencia,
			nro_parte_alterno,
			cantidad_sol,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod,
            tipo,
            explicacion_detallada_part,
            /*Aumentando estos campos (Ismael Valdivia 31/01/2020)*/
            id_centro_costo,
            id_concepto_ingas,
            id_orden_trabajo,

                id_partida,
                id_cuenta,
                id_auxiliar,
            /****************************************************/
            precio_total,
            precio_unitario,
            condicion_det,
            id_producto_alkym
            /******************************************************/
          	) values(
			v_parametros.id_solicitud,
			regexp_replace(v_parametros.descripcion,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
			'activo',
			v_parametros.id_unidad_medida,
			regexp_replace(v_parametros.nro_parte,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
			regexp_replace(v_parametros.referencia,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
			regexp_replace(v_parametros.nro_parte_alterno,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
			v_parametros.cantidad_sol,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null,
            v_parametros.tipo,
            regexp_replace(v_parametros.explicacion_detallada_part,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
            /*Aumentando estos campos (Ismael Valdivia 31/01/2020)*/
            v_parametros.id_centro_costo,
            v_parametros.id_concepto_ingas,
            v_parametros.id_orden_trabajo,
            /******************************************************/
                  v_id_partida,
                  v_id_cuenta,
                  v_id_auxiliar,
            /******************************************************/
            v_parametros.precio_total,
            v_parametros.precio_unitario,
            v_condicion,
            v_parametros.id_producto_alkym
            /******************************************************/
            )RETURNING id_detalle into v_id_detalle;

        else
        	/*Recuperamos el id _gestion para recuperar el centro de costo (Ismael Valdivia 17/03/2020)*/
        	select sol.id_gestion into v_id_gestion
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;
        	/*******************************************************************************************/

            /*Aqui Recuperamos el centro de costo de acuerdo a la gestion (Ismael Valdivia 17/03/2020)*/
            select cc.id_centro_costo into v_id_centro_costo
            from param.tcentro_costo cc
            inner join param.ttipo_cc tc on tc.id_tipo_cc = cc.id_tipo_cc
            where tc.codigo = '845' and cc.id_gestion = v_id_gestion;
            /******************************************************************************************/

             v_condicion = '';

              if ( (pxp.f_existe_parametro(p_tabla,'condicion_det')) ) then

                  IF (v_parametros.condicion_det != '' and v_parametros.condicion_det is not null) then
                          v_condicion = v_parametros.condicion_det;
                  end if;

              end if;


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
                and trim(det.nro_parte) = trim(regexp_replace(v_parametros.nro_parte,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'))
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
                select sol.id_matricula into v_id_orden_trabajo_rec
                from mat.tsolicitud sol
                where sol.id_solicitud = v_parametros.id_solicitud;
                /********************************************************************************************/
                ELSE
                	select
                       det.id_orden_trabajo
                  into
                         v_id_orden_trabajo_rec
                  from mat.tdetalle_sol det
                  inner join mat.tsolicitud sol on sol.id_solicitud = det.id_solicitud
                  where sol.origen_pedido = v_origen_pedido
                  and trim(det.nro_parte) = trim(regexp_replace(v_parametros.nro_parte,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'))
                  order by det.fecha_reg desc
                  limit 1;
                end if;



              else
              	v_id_concepto_ingas_recu = null;
                v_id_partida_recu = null;
                v_cuenta_recu = null;
                v_id_auxiliar_recu = null;
                v_id_orden_trabajo_rec = null;
              end if;


        	--Sentencia de la insercion
        	insert into mat.tdetalle_sol(
			id_solicitud,
			descripcion,
			estado_reg,
			id_unidad_medida,
			nro_parte,
			referencia,
			nro_parte_alterno,
			cantidad_sol,
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
            id_orden_trabajo,
            /****************************************/



            condicion_det,
            id_producto_alkym
          	) values(
			v_parametros.id_solicitud,
			regexp_replace(v_parametros.descripcion,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
			'activo',
			v_parametros.id_unidad_medida,
			regexp_replace(v_parametros.nro_parte,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
            regexp_replace(v_parametros.referencia,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
            regexp_replace(v_parametros.nro_parte_alterno,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
			v_parametros.cantidad_sol,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null,
            v_parametros.tipo,
            regexp_replace(v_parametros.explicacion_detallada_part,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'),
            v_id_centro_costo,
            /*Aumentando para insertar los datos recuperados*/
            v_id_concepto_ingas_recu,
            v_id_partida_recu,
            v_cuenta_recu,
            v_id_auxiliar_recu,
            v_id_orden_trabajo_rec,
            /************************************************/
            v_condicion,
             v_parametros.id_producto_alkym
            )RETURNING id_detalle into v_id_detalle;
            end if;

            --modificar nro_parte, nro_parte_alterno en tsolicitud
            select list(ds.nro_parte)
            into v_nro_parte
            from mat.tdetalle_sol ds
            where  ds.id_solicitud= v_parametros.id_solicitud
            GROUP by ds.id_solicitud;

            select list(ds.nro_parte_alterno)
            into v_nro_parte_alterno
            from mat.tdetalle_sol ds
            where  ds.id_solicitud= v_parametros.id_solicitud
            GROUP by ds.id_solicitud;

             -- RAISE exception '%, %',v_nro_parte, v_nro_parte_alterno;

              update mat.tsolicitud
              set
              nro_partes = v_nro_parte,
              nro_parte_alterno =  v_nro_parte_alterno
              where
              mat.tsolicitud.id_solicitud = v_parametros.id_solicitud;

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
        select s.estado, s.id_gestion, s.id_matricula, s.fecha_solicitud , de.id_centro_costo, de.id_concepto_ingas, s.origen_pedido
        into v_detalle
        from mat.tsolicitud s
        inner join mat.tdetalle_sol de on de.id_solicitud = s.id_solicitud
        WHERE s.id_solicitud = v_parametros.id_solicitud;

        /*Aqui concatenaremos para los repuestos en la condicion detalle el FOR*/
        v_condicion = '';
        IF (v_parametros.condicion_det != '' and v_parametros.condicion_det is not null) then
            if(v_detalle.origen_pedido = 'Reparación de Repuestos') then
            	IF (substr(v_parametros.condicion_det,1,3) = 'FOR') THEN
                	v_condicion = v_parametros.condicion_det;
                ELSE
                	v_condicion = 'FOR '||v_parametros.condicion_det;
                END IF;
            else
                v_condicion = v_parametros.condicion_det;
            end if;
        end if;
        /***********************************************************************/


        if (v_detalle.estado = 'desaduanizado')THEN
        v_revisado = v_parametros.revisado;
        ELSE
        v_revisado = 'no';
        end IF;

        /*Recuperaremos el id_auxiliar, id_cuenta, y el id_partida (Ismael Valdivia 14/02/2020)*/
        select
            cig.desc_ingas
            into
            v_registros_cig
            from param.tconcepto_ingas cig
            where cig.id_concepto_ingas =  v_parametros.id_concepto_ingas;

        --obtener partida, cuenta auxiliar del concepto de gasto

             v_id_partida = NULL;
			 v_id_cuenta = NULL;
             v_id_auxiliar = NULL;

             v_id_orden_trabajo = NULL;



          /*Aqui ponemos la condicion para que solo se edite las partes necesarias (Ismael Valdivia)*/
          IF (v_parametros.id_concepto_ingas is not null and v_parametros.id_centro_costo is not null ) then

              --recueprar la partida de la parametrizacion
              SELECT
                ps_id_partida ,
                ps_id_cuenta,
                ps_id_auxiliar
              into
                v_id_partida,
                v_id_cuenta,
                v_id_auxiliar
             FROM conta.f_get_config_relacion_contable('CUECOMP', v_detalle.id_gestion, v_parametros.id_concepto_ingas, v_parametros.id_centro_costo,  'No se encontro relación contable para el conceto de gasto: '||v_registros_cig.desc_ingas||'. <br> Mensaje: ');

          /*Aqui recuperamos la orden de trabajo*/

              IF pxp.f_existe_parametro(p_tabla, 'id_centro_costo') THEN

                   select
                      id_tipo_cc
                   into
                     v_id_tipo_cc
                   from param.tcentro_costo cc
                   where cc.id_centro_costo = v_parametros.id_centro_costo;

                   IF v_id_tipo_cc is null THEN
                      raise exception 'No fue parametrizaso un tipo para el centro de costos % ',v_parametros.id_centro_costo;
                   END IF;

                   SELECT
                    pxp.list(c.id_orden_trabajo::VARCHAR)
                   into
                     v_ordenes
                  FROM conta.vot_arb c
                  inner join conta.ttipo_cc_ot tco on tco.id_orden_trabajo = ANY(c.ids)
                  where c.movimiento = 'si'  and tco.id_tipo_cc = v_id_tipo_cc;


             -- raise exception 'ordenes es %',v_ordenes;
              END IF;


              v_filtro_ingas_ots = '0=0';

              /*Aqui recuperamos los parametros del concepto para filtrar la orden de trabajo*/
              select ingas.filtro_ot,
                     ingas.requiere_ot,
                     ingas.id_grupo_ots
              into v_filtro_ingas
              from param.tconcepto_ingas ingas
              where ingas.id_concepto_ingas = v_parametros.id_concepto_ingas;

              --raise exception 'Aqui obtenemos el id %  % % % %',v_ordenes,v_detalle.id_matricula,v_filtro_ingas.filtro_ot,v_filtro_ingas.id_grupo_ots,v_detalle.fecha_solicitud;
              IF (v_filtro_ingas.filtro_ot = 'listado') THEN

                    if (v_detalle.fecha_solicitud is not null) then
                      select
                              id_orden_trabajo
                              into v_orden_trabajo
                      from conta.vorden_trabajo odt
                      where      movimiento = 'si'
                            and tipo in ('estadistica','centro','edt','orden')
                            and  id_orden_trabajo in (SELECT UNNEST(REGEXP_SPLIT_TO_ARRAY(COALESCE(v_ordenes,'0'), ','))::integer)
                            and  (v_filtro_ingas.id_grupo_ots::varchar)::integer[] && odt.id_grupo_ots
                            and odt.fecha_inicio <= (v_detalle.fecha_solicitud::varchar)::date and (odt.fecha_final is null or odt.fecha_final >= (v_detalle.fecha_solicitud::varchar)::date)
                            and id_orden_trabajo = v_detalle.id_matricula;
                    else
                        select
                          id_orden_trabajo
                          into v_orden_trabajo
                        from conta.vorden_trabajo odt
                        where      movimiento = 'si'
                              and tipo in ('estadistica','centro','edt','orden')
                              and  id_orden_trabajo in (SELECT UNNEST(REGEXP_SPLIT_TO_ARRAY(COALESCE(v_ordenes,'0'), ','))::integer)
                              and  v_filtro_ingas.id_grupo_ots && odt.id_grupo_ots
                              and id_orden_trabajo = v_detalle.id_matricula;
                    end if;
              END IF;


              if(v_parametros.control_edicion = 'botonEditar') then
                   v_id_orden_trabajo = v_parametros.id_orden_trabajo;
              else
                  if (v_orden_trabajo.id_orden_trabajo is not null) then
                    	v_id_orden_trabajo = v_orden_trabajo.id_orden_trabajo::integer;
                    end if;
              end if;
          end if;
        /****************************************************************************************/

        --Sentencia de la modificacion
			update mat.tdetalle_sol set
			id_solicitud = v_parametros.id_solicitud,
			descripcion = v_parametros.descripcion,
			id_unidad_medida = v_parametros.id_unidad_medida,
			nro_parte = v_parametros.nro_parte,
			referencia = v_parametros.referencia,
			nro_parte_alterno = v_parametros.nro_parte_alterno,
			cantidad_sol = v_parametros.cantidad_sol,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            revisado = v_revisado,
            tipo = v_parametros.tipo,
            explicacion_detallada_part = v_parametros.explicacion_detallada_part,
            /*Aumentando estos campos (Ismael Valdivia 31/01/2020)*/
            id_centro_costo = v_parametros.id_centro_costo,
            id_concepto_ingas = v_parametros.id_concepto_ingas,
            id_orden_trabajo = v_id_orden_trabajo,

            id_partida = v_id_partida,
            id_cuenta = v_id_cuenta,
            id_auxiliar = v_id_auxiliar,

            precio_unitario = v_parametros.precio_unitario,
            precio_total = (v_parametros.cantidad_sol*v_parametros.precio_unitario),-- v_parametros.precio_total
            condicion_det = v_condicion,
            id_producto_alkym = v_parametros.id_producto_alkym
            /**********************************************************/
			where id_detalle=v_parametros.id_detalle;

     --modificar nro_parte, nro_parte_alterno en tsolicitud
        select list(ds.nro_parte)
        into v_nro_parte
        from mat.tdetalle_sol ds
        where  ds.id_solicitud= v_parametros.id_solicitud
        GROUP by ds.id_solicitud;

        select list(ds.nro_parte_alterno)
        into v_nro_parte_alterno
        from mat.tdetalle_sol ds
        where  ds.id_solicitud= v_parametros.id_solicitud
        GROUP by ds.id_solicitud;

       -- RAISE exception '%, %',v_nro_parte, v_nro_parte_alterno;

        update mat.tsolicitud
        set
        nro_partes = v_nro_parte,
        nro_parte_alterno =  v_nro_parte_alterno
        where
        mat.tsolicitud.id_solicitud = v_parametros.id_solicitud;


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


        /*********************************************************************************************************/

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

    /*********************************
 	#TRANSACCION:  'MAT_GET_PAR'
 	#DESCRIPCION:	control de numero de justificacion
 	#AUTOR:		MMV
 	#FECHA:		10-01-2017 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_GET_PAR')then
		begin
        FOR v_control_duplicidad in (select	d.nro_parte,
											f.desc_funcionario1,
                                            s.nro_justificacion,
                                            s.nro_no_rutina,
                                            s.justificacion,
                                            s.nro_tramite,
                                            s.fecha_solicitud
                                            from mat.tdetalle_sol d
                                            inner join mat.tsolicitud s on s.id_solicitud = d.id_solicitud
                                            inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
     	)LOOP
        	if v_parametros.nro_parte = v_control_duplicidad.nro_parte then
            	v_parte= v_control_duplicidad.nro_parte;
                v_msg_control = v_control_duplicidad.nro_parte||' fue registrado por '||v_control_duplicidad.desc_funcionario1||' en el tramite '||v_control_duplicidad.nro_tramite;
            end if;

        END LOOP;

        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
        v_resp = pxp.f_agrega_clave(v_resp,'parte', v_parte::varchar );
        v_resp = pxp.f_agrega_clave(v_resp,'mgs_control_duplicidad', v_msg_control::varchar );
        return v_resp;

		end;

    /*********************************
        #TRANSACCION:  'MAT_GET_CC'
        #DESCRIPCION:
        #AUTOR:		Ismael Valdivia
        #FECHA:		17/03/2020
        ***********************************/
	elsif(p_transaccion='MAT_GET_CC')then

		begin

			/*Aqui Recuperamos el centro de costo de acuerdo a la gestion (Ismael Valdivia 17/03/2020)*/
            select cc.id_centro_costo into v_id_centro_costo
            from param.tcentro_costo cc
            inner join param.ttipo_cc tc on tc.id_tipo_cc = cc.id_tipo_cc
            where tc.codigo = '845' and cc.id_gestion = v_parametros.id_gestion;
            /******************************************************************************************/

            /*Aqui recuperamos los datos cabecera*/
            select det.id_centro_costo,
                   det.id_concepto_ingas,
                   det.id_orden_trabajo INTO
                   v_id_cc_rec,
                   v_id_concepto_ingas_rec,
                   v_id_orden_trabajo_rec
            from mat.tdetalle_sol det
            where det.id_solicitud = v_parametros.id_solicitud
            LIMIT 1;

            /*Recuperamos el id matricula para relacionar el CP*/
            select solic.id_matricula into v_id_cp
            from mat.tsolicitud solic
            where solic.id_solicitud = v_parametros.id_solicitud;
            /***************************************************/

            /*Aumentamos para recuperar el CP de la Aeronave*/
            /*Recuperamos el tipo de centro de costos*/
            select
              id_tipo_cc
           into
             v_id_tipo_cc
           from param.tcentro_costo cc
           where cc.id_centro_costo = v_id_centro_costo;
            /************************************************/
            IF v_id_tipo_cc is null THEN
                    raise exception 'No fue parametrizaso un tipo para el centro de costos % ',v_parametros.id_centro_costo;
            END IF;
            /*Obtenemos las ordenes de trabajo relacionados al centro de costo*/
             SELECT
              pxp.list(c.id_orden_trabajo::VARCHAR)
             into
               v_ordenes
             FROM conta.vot_arb c
             inner join conta.ttipo_cc_ot tco on tco.id_orden_trabajo = ANY(c.ids)
             where c.movimiento = 'si'  and tco.id_tipo_cc = v_id_tipo_cc;
            /******************************************************************/


            select ot.id_orden_trabajo into v_ot
            from conta.vorden_trabajo ot
            where ot.movimiento = 'si'
              and ot.tipo in ('estadistica','centro','edt','orden')
              and ot.id_orden_trabajo = v_id_cp and ot.id_orden_trabajo in (SELECT UNNEST(REGEXP_SPLIT_TO_ARRAY(COALESCE(v_ordenes,'0'), ','))::integer);


            if (v_ot is null) then
            	select ot.id_orden_trabajo into v_ot
                from conta.vorden_trabajo ot
                where ot.movimiento = 'si'
              	and ot.tipo in ('estadistica','centro','edt','orden')
                and ot.codigo = 'FLOTA BOA';
            end if;



            /************************************************/

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Datos de Oficina y Funcionario que registra Reclamos');
            v_resp = pxp.f_agrega_clave(v_resp,'id_centro_costo',v_id_centro_costo::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_cc_rec',v_id_cc_rec::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_concepto_ingas_rec',v_id_concepto_ingas_rec::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_orden_trabajo_rec',v_id_orden_trabajo_rec::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_cp',v_ot::varchar);

            --Devuelve la respuesta de petición
            return v_resp;

		end;

        /*********************************
        #TRANSACCION:  'MAT_REL_HAZMAT'
        #DESCRIPCION:	Asociar Hazmat
        #AUTOR:		admin
        #FECHA:		01-02-2022 09:15:01
        ***********************************/

        elsif(p_transaccion='MAT_REL_HAZMAT')then

            begin
                --Sentencia de la eliminacion

                /*Aqui para reponer los datos si tienen hazmat relacionado y quieran cambiarlo*/
                select count(det.id_cotizacion_det) into v_existe_relacion
                from mat.tcotizacion_detalle det
                where det.id_detalle_hazmat = v_parametros.id_hazmat;

                if (v_existe_relacion > 0) then


                  /*Recuperamos el id Solicitud para actualizar*/
                  select det.id_detalle, det.cantidad_det, det.precio_unitario, det.precio_unitario_mb into v_id_detalle, v_cantidad, v_precio_unitario, v_precio_total
                  from mat.tcotizacion cot
                  inner join mat.tcotizacion_detalle det on det.id_cotizacion = cot.id_cotizacion and det.referencial = 'Si'
                  where det.id_cotizacion_det = v_parametros.id_cotizacion_det;
                  /*********************************************/

                  if (v_id_detalle is not null) then
                      update mat.tdetalle_sol set
                      cantidad_sol = v_cantidad,
                      precio_unitario = v_precio_unitario,
                      precio_total = v_precio_total
                      where id_detalle =  v_id_detalle;
                  end if;

                  update mat.tcotizacion_detalle set
                      id_detalle_hazmat = null
                  where id_detalle_hazmat = v_parametros.id_hazmat;

                end if;


                /*****************************************************************************/



                update mat.tcotizacion_detalle set
                id_detalle_hazmat = v_parametros.id_cotizacion_det
                --precio_total = precio_total + (COALESCE(v_total_hazmat,0))
                where id_cotizacion_det = v_parametros.id_hazmat;


                /*Recuperamos el id Solicitud para actualizar*/
                select cot.id_solicitud,
                det.id_detalle
                --(det.precio_unitario_mb + COALESCE(detHazmat.precio_unitario_mb,0))
                into v_id_solicitud,
                v_id_detalle
                --v_suma_hazmat
                from mat.tcotizacion cot
                inner join mat.tcotizacion_detalle det on det.id_cotizacion = cot.id_cotizacion and det.referencial = 'Si'
                left join mat.tcotizacion_detalle detHazmat on detHazmat.id_detalle_hazmat = det.id_cotizacion_det
                where det.id_cotizacion_det = v_parametros.id_cotizacion_det;
                /*********************************************/


                /*Hazmat Totalizado*/
                select
                sum(COALESCE(detHazmat.precio_unitario_mb,0))
                into
                v_suma_hazmat
                from mat.tcotizacion cot
                inner join mat.tcotizacion_detalle det on det.id_cotizacion = cot.id_cotizacion and det.referencial = 'Si'
                left join mat.tcotizacion_detalle detHazmat on detHazmat.id_detalle_hazmat = det.id_cotizacion_det
                where det.id_cotizacion_det = v_parametros.id_cotizacion_det;
                /*******************/



                select
                det.precio_unitario_mb into v_total_detalle
                from mat.tcotizacion cot
                inner join mat.tcotizacion_detalle det on det.id_cotizacion = cot.id_cotizacion and det.referencial = 'Si'
                where det.id_cotizacion_det = v_parametros.id_cotizacion_det;





                if (v_id_detalle is not null) then
                      update mat.tdetalle_sol set
                      cantidad_sol = 1,
                      precio_unitario = (v_suma_hazmat + v_total_detalle),
                      precio_total = (v_suma_hazmat + v_total_detalle)
                      where id_detalle =  v_id_detalle;
                end if;



                /*select sum(

                (case
                    when detHazmat.id_detalle_hazmat is not null then
                     COALESCE (detcot.precio_unitario_mb,0) + COALESCE(detHazmat.precio_unitario_mb,0)
                    else
                     COALESCE (detcot.precio_unitario_mb,0)
                    end )::numeric

                ) into v_suma_total
                from mat.tdetalle_sol det

                inner join mat.tcotizacion_detalle detcot on detcot.id_detalle = det.id_detalle and detcot.referencial = 'Si'
                left join mat.tcotizacion_detalle detHazmat on detHazmat.id_detalle_hazmat = detcot.id_cotizacion_det
            	where det.id_solicitud = v_id_solicitud;*/


                v_suma_total = ((v_suma_hazmat + v_total_detalle));


				--raise exception 'Aqui llega la solicitud %, total %',v_id_solicitud,v_suma_total;
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





                --Definicion de la respuesta
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Hazmat Relacionado Correctamente');
                v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_parametros.id_hazmat::varchar);

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

ALTER FUNCTION mat.ft_detalle_sol_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
