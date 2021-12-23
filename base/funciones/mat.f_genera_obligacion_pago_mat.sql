CREATE OR REPLACE FUNCTION mat.f_genera_obligacion_pago_mat (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_solicitud integer,
  p_id_proceso_wf integer,
  p_id_estado_wf integer,
  p_codigo_ewf varchar,
  p_tipo_preingreso varchar,
  p_id_depto_wf_pro integer
)
RETURNS boolean AS
$body$
/*
Autor: Maylee Perez Pastor
Fecha: 4/02/2020
Descripción: Generar Obligaciones de pago desde Gestion de Materiales



*/

DECLARE

	v_nombre_funcion  				varchar;
    --v_registros_cotizacion			record;
    v_registros 					record;
    v_id_subsistema					integer;
    v_id_obligacion_pago 			integer;
    v_id_obligacion_det 			integer;
    v_resp							varchar;
    v_id_contrato					integer;
    v_num_contrato					varchar;
    v_adq_comprometer_presupuesto	varchar;


    v_registros_solicitud_mat 		record;
    v_tipo_estado_id				integer;
    va_id_tipo_estado 				integer[];
    va_codigo_estado 				varchar[];
    v_id_estado_actual				integer;
    v_total_detalle					numeric;
	v_compra						record;
	v_id_funcionario_adquisiciones	integer;

BEGIN

	v_nombre_funcion='mat.f_genera_obligacion_pago_mat';


             select
           	 s.id_subsistema
            into
            	v_id_subsistema
            from segu.tsubsistema s
            where s.codigo = 'MAT';

            -------------------------------------------------------------------------------
            --recuperar datos de la solicitud material e inserta en oblligacion de pago
            -------------------------------------------------------------------------------

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
              ts.list_proceso,

              tc.id_moneda,
              ts.id_gestion,
              ts.id_funcionario_solicitante,
			  est.id_funcionario,
              est.tipo_cambio,
              ts.codigo_poa,
              ts.id_proceso_wf,
              ts.origen_pedido

            into
             v_registros_solicitud_mat
            from mat.tsolicitud ts
            left join mat.tcotizacion tc on tc.id_solicitud = ts.id_solicitud AND tc.adjudicado = 'si'
            left join param.vproveedor vp ON vp.id_proveedor = tc.id_proveedor
            left join wf.testado_wf est on est.id_estado_wf = ts.id_estado_wf
            WHERE ts.id_solicitud = p_id_solicitud;

    		if (v_registros_solicitud_mat.origen_pedido = 'Reparación de Repuestos') then


    		/*Aumetnando condicion para recuperar al encargado asignado de adquisiciones*/
            SELECT  twf.id_funcionario,
                    twf.fecha_reg
                  into  v_compra
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
            WHERE twf.id_proceso_wf = v_registros_solicitud_mat.id_proceso_wf
            AND te.codigo = 'cotizacion'
            GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;

            SELECT  twf.id_funcionario
          	INTO
                  v_id_funcionario_adquisiciones
            FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_ultimo_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_registros_solicitud_mat.id_proceso_wf AND te.codigo = 'cotizacion'
                and v_compra.fecha_reg between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg;
			/****************************************************************************/


            --  RAC  02/08/2017
            --  marca la obligacion como comproemtido en funcion a variable global de adquisiciones
            v_adq_comprometer_presupuesto = pxp.f_get_variable_global('adq_comprometer_presupuesto');


            INSERT INTO
              tes.tobligacion_pago
            (
              id_usuario_reg,
              fecha_reg,
              estado_reg,
              id_proveedor,
              id_subsistema,
              id_moneda,
             -- id_depto,
              tipo_obligacion,
              fecha,
              numero,
              tipo_cambio_conv,
              num_tramite,
              id_gestion,
              comprometido,
              id_categoria_compra,
              tipo_solicitud,
              tipo_concepto_solicitud,
              id_funcionario,
              id_contrato,
              obs,
              id_funcionario_gerente,
              codigo_poa,
              presupuesto_aprobado,
              pago_variable
            )
            VALUES (
              p_id_usuario,
              now(),
              'activo',
              v_registros_solicitud_mat.id_proveedor,
              v_id_subsistema,
              v_registros_solicitud_mat.id_moneda,
              'gestion_mat', --'adquisiciones',
              now(),
              null, --pxp.f_iif(v_num_contrato is NULL, v_registros_cotizacion.numero_oc, v_num_contrato),
              1,--v_registros_solicitud_mat.tipo_cambio, -- 2,  --v_registros_cotizacion.tipo_cambio_conv,??
              v_registros_solicitud_mat.nro_tramite,
              v_registros_solicitud_mat.id_gestion,
              v_adq_comprometer_presupuesto,
              null, -- 4,     --v_registros_cotizacion.id_categoria_compra,
              'Boa',  --v_registros_cotizacion.tipo,
              'bien', --v_registros_cotizacion.tipo_concepto,
              v_id_funcionario_adquisiciones, -- 370 => PASTOR JAIME LAZARTE VILLAGRA
              null, --v_id_contrato,
              v_registros_solicitud_mat.motivo_solicitud, --v_registros_cotizacion.justificacion,
              v_registros_solicitud_mat.id_funcionario, --v_registros_cotizacion.id_funcionario_aprobador, 2711 = JORGE EDUARDO DELGADILLO POEPSEL 160= GONZALO ARIEL MAYORGA LAZCANO
              v_registros_solicitud_mat.codigo_poa,
              null,
              'si'

            ) RETURNING id_obligacion_pago into v_id_obligacion_pago;


			else

            --  RAC  02/08/2017
            --  marca la obligacion como comproemtido en funcion a variable global de adquisiciones
            v_adq_comprometer_presupuesto = pxp.f_get_variable_global('adq_comprometer_presupuesto');


            INSERT INTO
              tes.tobligacion_pago
            (
              id_usuario_reg,
              fecha_reg,
              estado_reg,
              id_proveedor,
              id_subsistema,
              id_moneda,
             -- id_depto,
              tipo_obligacion,
              fecha,
              numero,
              tipo_cambio_conv,
              num_tramite,
              id_gestion,
              comprometido,
              id_categoria_compra,
              tipo_solicitud,
              tipo_concepto_solicitud,
              id_funcionario,
              id_contrato,
              obs,
              id_funcionario_gerente,
              codigo_poa,
              presupuesto_aprobado,
              pago_variable
            )
            VALUES (
              p_id_usuario,
              now(),
              'activo',
              v_registros_solicitud_mat.id_proveedor,
              v_id_subsistema,
              v_registros_solicitud_mat.id_moneda,
              'gestion_mat', --'adquisiciones',
              now(),
              null, --pxp.f_iif(v_num_contrato is NULL, v_registros_cotizacion.numero_oc, v_num_contrato),
              1,--v_registros_solicitud_mat.tipo_cambio, -- 2,  --v_registros_cotizacion.tipo_cambio_conv,??
              v_registros_solicitud_mat.nro_tramite,
              v_registros_solicitud_mat.id_gestion,
              v_adq_comprometer_presupuesto,
              null, -- 4,     --v_registros_cotizacion.id_categoria_compra,
              'Boa',  --v_registros_cotizacion.tipo,
              'bien', --v_registros_cotizacion.tipo_concepto,
              v_registros_solicitud_mat.id_funcionario_solicitante, -- 370 => PASTOR JAIME LAZARTE VILLAGRA
              null, --v_id_contrato,
              v_registros_solicitud_mat.motivo_solicitud, --v_registros_cotizacion.justificacion,
              v_registros_solicitud_mat.id_funcionario, --v_registros_cotizacion.id_funcionario_aprobador, 2711 = JORGE EDUARDO DELGADILLO POEPSEL 160= GONZALO ARIEL MAYORGA LAZCANO
              v_registros_solicitud_mat.codigo_poa,
              null,
              'si'

            ) RETURNING id_obligacion_pago into v_id_obligacion_pago;

            end if;

            -----------------------------------------------------------------------------
            --recupera datos del detalle e inserta en detalle de obligacion
            -----------------------------------------------------------------------------

            FOR v_registros in (
                                  /*select 	ds.nro_parte,
                                            ds.cantidad_sol,
                                            ds.precio_unitario,
                                            ds.precio_unitario as precio_unitario_mb,

                                            ds.id_concepto_ingas,
                                            ds.id_centro_costo,
                                            ds.id_partida,
                                            ds.id_cuenta,
                                            ds.id_auxiliar,
                                            ds.id_partida_ejecucion,
                                            (' P/N: ' ||ds.nro_parte||' '|| ds.descripcion ||' '||' S/N: ' || ds.referencia) as descripcion,
                                            ds.id_orden_trabajo

                                  from mat.tdetalle_sol ds
                                  where ds.id_solicitud =  p_id_solicitud*/

                                  /*Tomar en cuenta de la cotizacion adjudicada*/
                                  select 	det.nro_parte_cot,
                                            det.cantidad_det,
                                            det.precio_unitario,
                                            det.precio_unitario_mb as precio_unitario_mb,

                                            ds.id_concepto_ingas,
                                            ds.id_centro_costo,
                                            ds.id_partida,
                                            ds.id_cuenta,
                                            ds.id_auxiliar,
                                            ds.id_partida_ejecucion,
                                            (' P/N: ' ||det.nro_parte_cot||' '|| det.descripcion_cot ||' '||' S/N: ' || det.referencia_cot) as descripcion,
                                            ds.id_orden_trabajo

                                    from mat.tdetalle_sol ds
                                    inner join mat.tcotizacion_detalle det on det.id_detalle = ds.id_detalle
                                    inner join mat.tcotizacion cot on cot.id_cotizacion = det.id_cotizacion and cot.adjudicado = 'si'
                                    where ds.id_solicitud = p_id_solicitud

            )LOOP


               -- inserta detalle obligacion


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

                        (v_registros.cantidad_sol *v_registros.precio_unitario),
                        (v_registros.cantidad_sol *v_registros.precio_unitario_mb),
                        v_registros.descripcion,
                        v_registros.id_orden_trabajo
                      )RETURNING id_obligacion_det into v_id_obligacion_det;


            END LOOP;



			  --UPDATE DATOS WF de la OP
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


               --TOTAL OBLIGACION DE PAGO
              --validamos que el detalle tenga por lo menos un item con valor
               select sum(od.monto_pago_mo)
               into v_total_detalle
               from tes.tobligacion_det od
               where od.id_obligacion_pago = v_id_obligacion_pago and od.estado_reg ='activo';

               IF v_total_detalle = 0 or v_total_detalle is null THEN
                  raise exception 'No existe el detalle de obligacion...';
               END IF;

              --calcula el factor de prorrateo de la obligacion  detalle

               IF (tes.f_calcular_factor_obligacion_det(v_id_obligacion_pago) != 'exito')  THEN
                  raise exception 'error al calcular factores';
               END IF;


               --WF VA DIRECTO A ESTADO REGISTRADO
                SELECT es.id_tipo_estado
                INTO v_tipo_estado_id
                FROM wf.testado_wf es
                WHERE es.id_estado_wf = p_id_estado_wf;

            	 --   para un estado siguiente
                       SELECT  ps_id_tipo_estado,
                               ps_codigo_estado

                           into
                              va_id_tipo_estado,
                              va_codigo_estado

                          FROM wf.f_obtener_estado_wf(
                           p_id_proceso_wf,
                           NULL,
                           v_tipo_estado_id,
                           'siguiente',
                           p_id_usuario);

                       v_id_estado_actual =  wf.f_registra_estado_wf(  va_id_tipo_estado[1],
                                                                       null,
                                                                       p_id_estado_wf,
                                                                       p_id_proceso_wf,
                                                                       p_id_usuario,
                                                                       null,
                                                                       null,
                                                                       null,
                                                                       '');


                   update tes.tobligacion_pago  o set
                   id_estado_wf =  v_id_estado_actual,
                   estado = va_codigo_estado[1],
                   total_pago = v_total_detalle
                   where o.id_obligacion_pago  = v_id_obligacion_pago;


 --raise exception 'llega ';
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
SECURITY INVOKER
COST 100;

ALTER FUNCTION mat.f_genera_obligacion_pago_mat (p_id_usuario integer, p_id_usuario_ai integer, p_usuario_ai varchar, p_id_solicitud integer, p_id_proceso_wf integer, p_id_estado_wf integer, p_codigo_ewf varchar, p_tipo_preingreso varchar, p_id_depto_wf_pro integer)
  OWNER TO postgres;
