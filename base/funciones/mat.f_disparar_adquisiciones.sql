CREATE OR REPLACE FUNCTION mat.f_disparar_adquisiciones (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_solicitud integer,
  p_id_estado_actual integer,
  p_id_funcionario_wf_pro integer,
  p_obs_pro varchar,
  p_tipo_preingreso varchar,
  p_id_depto_wf_pro integer
)
RETURNS boolean AS
$body$
/**************************************************************************
 FUNCION: 		mat.f_disparar_adquisiciones
 DESCRIPCION: 	Disparo del flujo Gestion de Materiales a Adquisiones.
 AUTOR: 		FEA
 FECHA:			26/06/2017
 COMENTARIOS:
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:

***************************************************************************/
DECLARE
  v_num_siguiente 			INTEGER;
  v_gestion 				varchar;
  v_id_gestion 				integer;
  v_cont_gestion 			integer;
  v_nombre_funcion 			VARCHAR;
  v_resp					varchar;
  v_registros_proc  		record;

  --sol
  v_registros_solicitud_mat record;
  v_id_subsistema			integer;
  v_id_solicitud			integer;
  v_reg_prov				record;

  v_id_periodo				integer;
  v_num_sol					varchar;
  --wf
  v_num_tramite				varchar;
  v_id_proceso_wf			integer;
  v_id_estado_wf			integer;
  v_codigo_estado 			varchar;
  --detalle solicitud
  v_id_solicitud_det		integer;
  v_record_det				record;
  v_codigo_tipo_proceso		varchar;

BEGIN
    v_nombre_funcion = 'mat.f_disparar_adquisiciones';

    select g.id_gestion
    into v_id_gestion
    from param.tgestion g
    where g.gestion = EXTRACT(YEAR FROM current_date);

    ----------------------------------------------------------------------------------

            -------------------------------------
            --recuperar datos de la solicitud material e inserta en adquisiociones
            ------------------------------------------------

            select
              tc.id_proveedor,
              vp.email,
              tc.fecha_cotizacion,
              ts.nro_po,
              ts.fecha_po,
              ts.motivo_solicitud,
              ts.lugar_entrega,
              ts.nro_tramite,
              tc.monto_total,
              ts.id_estado_wf
            into
             v_registros_solicitud_mat
            from mat.tsolicitud ts
            left join mat.tcotizacion tc on tc.id_solicitud = ts.id_solicitud AND tc.adjudicado = 'si'
            left join param.vproveedor vp ON vp.id_proveedor = tc.id_proveedor
            WHERE ts.id_solicitud = p_id_solicitud;


            --si existe el parametro del correo proveedor  actulizamos la tabla
            select
              p.id_persona,
              p.id_institucion
            into
              v_reg_prov
            from param.tproveedor p
            where  p.id_proveedor = v_registros_solicitud_mat.id_proveedor;

            IF  v_reg_prov.id_persona is not NULL   THEN

                 update segu.tpersona set correo = v_registros_solicitud_mat.email where id_persona = v_reg_prov.id_persona;

            ELSE
                 update param.tinstitucion set email1 = v_registros_solicitud_mat.email where id_institucion = v_reg_prov.id_institucion;

            END IF;

            SELECT id_periodo
            INTO v_id_periodo
            FROM param.tperiodo per
            WHERE per.fecha_ini <= now()::date and per.fecha_fin >=  now()::date
            LIMIT 1 OFFSET 0;

            v_num_sol =   param.f_obtener_correlativo(
                                                      'SOLC',
                                                       v_id_periodo,-- par_id,
                                                       NULL, --id_uo
                                                       2,    -- id_depto ver como obtener el depto estoy insertando por defecto
                                                       p_id_usuario,
                                                       'ADQ',
                                                       NULL);

           /*IF  v_registros_cotizacion.estado  in('adjudicado','contrato_elaborado') THEN
              raise exception 'Solo pueden habilitarce pago para cotizaciones adjudicadas';
            END IF;*/

           /*SELECT
                 ps_num_tramite ,
                 ps_id_proceso_wf ,
                 ps_id_estado_wf ,
                 ps_codigo_estado
              into
                 v_num_tramite,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 p_id_usuario_ai,
                 p_usuario_ai,
                 v_id_gestion,
                 'SOLCINPD',
                 370,--FUNCIONARIO SOLICITANTE
                 NULL,
                 'Solicitud de Compra '||v_num_sol,
                 v_num_sol);*/


            -- Iniciar el tramite en el sistema de WF
            /*SELECT
                         ps_id_proceso_wf,
                         ps_id_estado_wf,
                         ps_codigo_estado,
                         ps_nro_tramite
            INTO
                         v_id_proceso_wf,
                         v_id_estado_wf,
                         v_codigo_estado,
                         v_num_tramite
            FROM wf.f_registra_proceso_disparado_wf(
                        p_id_usuario,
                        p_id_usuario_ai::integer,
                        p_usuario_ai::varchar,
                        v_registros_solicitud_mat.id_estado_wf,
                        370,  --id_funcionario wf
                        null,
                        v_codigo_tipo_proceso||'-['||v_registros_solicitud_mat.nro_tramite||']',
                        'SOLCINPD',
                        v_codigo_tipo_proceso||'-['||v_registros_solicitud_mat.nro_tramite||']');*/
            --raise exception 'v_num_tramite %, %',v_num_tramite,v_codigo_estado ;
             -- disparar creacion de procesos seleccionados
                        SELECT
                                 ps_id_proceso_wf,
                                 ps_id_estado_wf,
                                 ps_codigo_estado
                           into
                                 v_id_proceso_wf,
                                 v_id_estado_wf,
                                 v_codigo_estado
                        FROM wf.f_registra_proceso_disparado_wf(
                                 p_id_usuario,
                                 p_id_usuario_ai,
                                 p_usuario_ai,
                                 p_id_estado_actual::integer,
                                 p_id_funcionario_wf_pro,
                                 p_id_depto_wf_pro,
                                 'Solicitud de Compra ['||v_num_sol||']',
                                 'CINTPD',
                                 v_num_sol);

            v_num_tramite = wf.f_get_numero_tramite('CINTPD', v_id_gestion, p_id_usuario);
            INSERT INTO adq.tsolicitud
            (
              id_usuario_reg,
              fecha_reg,
              estado_reg,
              id_funcionario,
              id_uo,
              id_categoria_compra,
              id_moneda,
              id_proceso_macro,
              id_gestion,
              id_funcionario_aprobador,
              id_depto,
              numero,
              extendida,
              tipo,
              fecha_soli,
              id_proveedor,
              tipo_concepto,
              lugar_entrega,
              justificacion,

              num_tramite,

              nro_po,
              fecha_po,
              proveedor_unico,
              id_proceso_wf,
              id_estado_wf,
              estado
            )
            VALUES (
              p_id_usuario,
              now(),
              'activo',

              370,
              9420,

              4,
              2,
              21,
              v_id_gestion,
              160,
              2,
              v_num_sol,
              'no',
              'Boa',
              now()::date,
              v_registros_solicitud_mat.id_proveedor,
              'boa_po',
              v_registros_solicitud_mat.lugar_entrega,
              v_registros_solicitud_mat.motivo_solicitud,

              v_num_tramite::varchar,

              v_registros_solicitud_mat.nro_po,
              v_registros_solicitud_mat.fecha_po,
              true,
              v_id_proceso_wf,
              v_id_estado_wf,
              v_codigo_estado

            ) RETURNING id_solicitud into v_id_solicitud;

            --Obtenemos detalle de solicitud
            /*SELECT tds.*
            INTO v_record_det
            FROM mat.tdetalle_sol tds
            WHERE tds.id_solicitud = p_id_solicitud;*/
            --raise exception 'v_record_det %',p_id_solicitud;
            FOR v_record_det IN  (
            					  SELECT DISTINCT ON (tds.nro_parte)tds.nro_parte,tcd.cantidad_det, tcd.precio_unitario, tcd.precio_unitario_mb,tds.descripcion,tds.explicacion_detallada_part,tds.tipo
                                  FROM mat.tcotizacion_detalle tcd
                                  --INNER JOIN mat.tsolicitud ts ON ts.id_solicitud = tcd.id_solicitud
                                  INNER JOIN mat.tdetalle_sol tds ON tds.id_solicitud = tcd.id_solicitud
                                  where tcd.id_solicitud = p_id_solicitud )LOOP
            --raise exception 'v_record_det %',v_record_det;
            	INSERT INTO adq.tsolicitud_det
                (
                  id_usuario_reg,
                  fecha_reg,
                  estado_reg,
                  id_solicitud,


                  descripcion,
                  cantidad,
                  precio_unitario,
                  precio_total,
                  precio_ga,

                  precio_sg,
                  precio_sg_mb

                )
                VALUES (
                  p_id_usuario,
                  now(),
                  'activo',
                  v_id_solicitud,



                  '('||v_record_det.nro_parte||') '||v_record_det.descripcion||' ('||v_record_det.explicacion_detallada_part||') '||v_record_det.tipo,
                  COALESCE(v_record_det.cantidad_det,0),
                  COALESCE(v_record_det.precio_unitario,0),
                  COALESCE(v_record_det.precio_unitario_mb,0),
                  COALESCE(v_record_det.precio_unitario_mb,0),

                  0,
                  0


                );
            END LOOP;

            /*INSERT INTO adq.tsolicitud_det
            (
              id_usuario_reg,
              fecha_reg,
              estado_reg,
              id_solicitud,

              id_centro_costo,
              id_partida,
              id_cuenta,
              id_auxiliar,
              id_concepto_ingas,
              id_partida_ejecucion,
              id_orden_trabajo,
              descripcion,
              precio_unitario,
              cantidad,

              precio_total,
              precio_ga

            )
            VALUES (
              p_id_usuario,
              now(),
              'activo',
              v_id_solicitud,

              845,
              9739,
              25148,
              1856,
              1697,
              null,
              43,
              v_record_det.descripcion,
              v_registros_solicitud_mat.monto_total,
              v_record_det.cantidad_sol,

              v_registros_solicitud_mat.monto_total,
              v_registros_solicitud_mat.monto_total

            )RETURNING id_solicitud_det into v_id_solicitud_det;*/

            -----------------------------------------------------------------------------
            --recupera datos del detalle de cotizacion e inserta en detalle de obligacion
            -----------------------------------------------------------------------------

            /*FOR v_registros in (
              select
                cd.id_cotizacion_det,
                sd.id_concepto_ingas,
                sd.id_cuenta,
                sd.id_auxiliar,
                sd.id_partida,
                sd.id_partida_ejecucion,
                cd.cantidad_adju,
                cd.precio_unitario,
                cd.precio_unitario_mb,
                sd.id_centro_costo,
                sd.descripcion,
                sd.id_orden_trabajo
              from adq.tcotizacion_det cd
              inner join adq.tsolicitud_det sd on sd.id_solicitud_det = cd.id_solicitud_det
              where cd.id_cotizacion = p_id_cotizacion
                    and cd.estado_reg='activo'

            )LOOP


              --TO DO,  para el pago de dos gestion  gestion hay que
              --        mandar solamente el total comprometido  de la gestion actual menos el revrtido
              --         o el monto total adjudicado, el que sea menor.

               -- inserta detalle obligacion
                IF((v_registros.cantidad_adju * v_registros.precio_unitario) > 0)THEN

                       INSERT INTO
                        tes.tobligacion_det
                      (
                        id_usuario_reg,
                        fecha_reg,
                        estado_reg,
                        id_obligacion_pago,
                        id_concepto_ingas,
                        id_centro_costo,
                        id_partida,
                        id_cuenta,
                        id_auxiliar,
                        id_partida_ejecucion_com,
                        monto_pago_mo,
                        monto_pago_mb,
                        descripcion,
                        id_orden_trabajo)
                      VALUES (
                        p_id_usuario,
                        now(),
                        'activo',
                        v_id_obligacion_pago,
                        v_registros.id_concepto_ingas,
                        v_registros.id_centro_costo,
                        v_registros.id_partida,
                        v_registros.id_cuenta,
                        v_registros.id_auxiliar,
                        v_registros.id_partida_ejecucion,
                        (v_registros.cantidad_adju *v_registros.precio_unitario),
                        (v_registros.cantidad_adju *v_registros.precio_unitario_mb),
                        v_registros.descripcion,
                        v_registros.id_orden_trabajo
                      )RETURNING id_obligacion_det into v_id_obligacion_det;

                       -- actulizar detalle de cotizacion

                       update adq.tcotizacion_det set
                       id_obligacion_det = v_id_obligacion_det
                       where id_cotizacion_det=v_registros.id_cotizacion_det;

               END IF;

            END LOOP;


              -- actualiza estado en la solicitud
               update adq.tcotizacion  c set
               id_obligacion_pago = v_id_obligacion_pago
              where c.id_cotizacion  = p_id_cotizacion;

              IF  p_id_depto_wf_pro::integer  is NULL  THEN

                 raise exception 'Para obligaciones de pago el depto es indispensable';

              END IF;

               update tes.tobligacion_pago  o set
                   id_estado_wf =  p_id_estado_wf,
                   id_proceso_wf = p_id_proceso_wf,
                   id_depto =   p_id_depto_wf_pro::integer,
                   estado = p_codigo_ewf,
                   id_usuario_mod=p_id_usuario,
                   fecha_mod=now(),
                   id_usuario_ai = p_id_usuario_ai,
                   usuario_ai = p_usuario_ai
                   where o.id_obligacion_pago  = v_id_obligacion_pago;

            --Llamada a obliagcion de pago para que pase de borrador a en_pago
            v_hstore_registros =   hstore(
            						ARRAY[
                                    'id_obligacion_pago', v_id_obligacion_pago::varchar,
                                    'id_estado_wf_act', p_id_estado_wf::varchar,
                                    'id_tipo_estado', 33::varchar,
                                    'id_funcionario_wf', v_registros_cotizacion.id_funcionario::varchar,
                                    'id_depto_wf',p_id_depto_wf_pro::varchar,
                                    'obs', ''::varchar,
                                    '_id_usuario_ai', p_id_usuario_ai::varchar,
                                    '_nombre_usuario_ai', p_usuario_ai::varchar,
                                    'json_procesos',arr::varchar
                                    ]);
            --
            IF( tes.f_sig_stado_ob(1,p_id_usuario, v_hstore_registros) )THEN
            	RAISE NOTICE 'EXITO';
            END IF;*/

    return TRUE;
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
SECURITY DEFINER
COST 100;