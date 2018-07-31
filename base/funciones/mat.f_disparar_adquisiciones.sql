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
              ts.id_estado_wf,
              ts.mel,
              ts.list_proceso
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
              estado,
              prioridad,
              list_proceso
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
              'bien',
              v_registros_solicitud_mat.lugar_entrega,
              v_registros_solicitud_mat.motivo_solicitud,

              v_registros_solicitud_mat.nro_tramite,--v_num_tramite::varchar,

              v_registros_solicitud_mat.nro_po,
              v_registros_solicitud_mat.fecha_po,
              true,
              v_id_proceso_wf,
              v_id_estado_wf,
              v_codigo_estado,
              case when v_registros_solicitud_mat.mel = 'AOG' then 383
              	   when v_registros_solicitud_mat.mel = 'A'	then 384
               	   when v_registros_solicitud_mat.mel = 'B' then 385
                   when v_registros_solicitud_mat.mel = 'C' then 386
                   when v_registros_solicitud_mat.mel = 'No Aplica' then 387 end,
              v_registros_solicitud_mat.list_proceso
            ) RETURNING id_solicitud into v_id_solicitud;

            --Obtenemos detalle de solicitud
            /*SELECT tds.*
            INTO v_record_det
            FROM mat.tdetalle_sol tds
            WHERE tds.id_solicitud = p_id_solicitud;*/
            --raise exception 'v_record_det %',p_id_solicitud;
            FOR v_record_det IN  (select 	d.nro_parte_cot,
                                  			d.cantidad_det,
                                            d.precio_unitario,
                                            d.precio_unitario_mb,
                                            d.descripcion_cot,
                                            d.explicacion_detallada_part_cot,
                                            d.tipo_cot

                                  from mat.tcotizacion c
                                  inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion
                                  where c.id_solicitud =  p_id_solicitud and c.adjudicado = 'si'  )LOOP
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
                  '('||v_record_det.nro_parte_cot||') '||v_record_det.descripcion_cot||' ('||v_record_det.explicacion_detallada_part_cot||') '||v_record_det.tipo_cot,
                  COALESCE(v_record_det.cantidad_det,0),
                  COALESCE(v_record_det.precio_unitario,0),
                  COALESCE(v_record_det.precio_unitario_mb,0),
                  COALESCE(v_record_det.precio_unitario_mb,0),

                  0,
                  0


                );
            END LOOP;


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