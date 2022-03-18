CREATE OR REPLACE FUNCTION mat.f_gestionar_presupuesto_solicitud (
  p_id_solicitud integer,
  p_id_usuario integer,
  p_operacion varchar,
  p_conexion varchar = NULL::character varying
)
RETURNS boolean AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Gestion Materiales
 DESCRIPCION:   Esta funcion a partir del id Solicitud  se encarga de gestion el presupuesto,
                compromenter
                revertir
                adcionar comprometido (revertido - negativo)
 AUTOR: 		breydi vasquez pacheco
 FECHA:	        06-01-2020
 COMENTARIOS:
***************************************************************************/

DECLARE
  v_registros 						record;
  v_reg_sol							record;
  v_nombre_funcion 					varchar;
  v_resp 							varchar;
  v_men_presu						varchar;
  v_mensage_error					varchar;
  v_resp_pre 						varchar;
  v_pre_verificar_categoria 		varchar;
  v_pre_verificar_tipo_cc 			varchar;
  v_control_partida 				varchar;
  v_consulta						varchar;
  v_llave_control_presupuesto		varchar;
  v_pre_verificar_tipo_cc_control 	varchar;
  va_columna_relacion     			varchar[];
  va_num_tramite					varchar[];
  v_i   				  			integer;
  v_cont				  			integer;
  v_ano_1 							integer;
  v_ano_2 							integer;
  v_id_centro_costo					integer;
  va_id_solicitud_det	  			integer[];
  va_id_presupuesto 				integer[];
  va_id_partida     				integer[];
  va_momento						integer[];
  va_id_moneda    					integer[];
  va_id_partida_ejecucion 			integer[];
  va_fk_llave             			integer[];
  v_id_moneda_base		  			integer;
  v_monto_a_revertir 				numeric;
  v_total_adjudicado  				numeric;
  v_aux 							numeric;
  v_comprometido  	    			numeric;
  v_comprometido_ga     			numeric;
  v_ejecutado     	    			numeric;
  v_monto_a_revertir_mb  			numeric;
  va_monto          				numeric[];
  va_resp_ges              			numeric[];
  va_fecha                			date[];
  v_sw_error						boolean;

BEGIN

  v_nombre_funcion = 'mat.f_gestionar_presupuesto_solicitud';

  v_id_moneda_base =  param.f_get_moneda_base();

  select
    s.*
  into
   v_reg_sol
  from mat.tsolicitud s
  where s.id_solicitud = p_id_solicitud;



      IF p_operacion = 'comprometer' THEN

          --compromete al aprobar la solicitud
           v_i = 0;

           -- verifica que solicitud

          FOR v_registros in (
                            select
                                ds.id_detalle,
                                ds.id_centro_costo,
                                ds.id_partida,
                                ds.precio_total,
                                s.id_gestion,
                                s.id_solicitud,
                                p.id_presupuesto,
                                s.presu_comprometido,
                                s.id_moneda,
                                s.fecha_solicitud,
                                s.nro_tramite
                            from mat.tsolicitud s
                            inner join mat.tdetalle_sol ds on ds.id_solicitud = s.id_solicitud
                            inner join pre.tpresupuesto p on p.id_centro_costo = ds.id_centro_costo
                            where s.id_solicitud = p_id_solicitud
                                and ds.estado_reg = 'activo'
                                and ds.cantidad_sol > 0 ) LOOP


                     IF(v_registros.presu_comprometido='si') THEN
                        raise exception 'El presupuesto ya se encuentra comprometido';
                     END IF;



                     v_i = v_i +1;

                   --armamos los array para enviar a presupuestos

                    va_id_presupuesto[v_i] = v_registros.id_presupuesto;
                    va_id_partida[v_i]= v_registros.id_partida;
                    va_momento[v_i]	= 1; --el momento 1 es el comprometido
                    va_monto[v_i]  = v_registros.precio_total; -- Cambio por moneda de la solicitud
                    va_id_moneda[v_i]  = v_registros.id_moneda; --RAC Cambio por moneda de la solicitud

                    va_columna_relacion[v_i]= 'id_solicitud_mat';
                    va_fk_llave[v_i] = v_registros.id_solicitud;
                    va_id_solicitud_det[v_i]= v_registros.id_detalle;
                    va_num_tramite[v_i] = v_reg_sol.nro_tramite;



                    -- la fecha de solictud es la fecha de compromiso
                    IF  now()  < v_registros.fecha_solicitud THEN
                      va_fecha[v_i] = v_registros.fecha_solicitud::date;
                    ELSE
                       -- la fecha de reversion como maximo puede ser el 31 de diciembre
                       va_fecha[v_i] = now()::date;
                       v_ano_1 =  EXTRACT(YEAR FROM  now()::date);
                       v_ano_2 =  EXTRACT(YEAR FROM  v_registros.fecha_solicitud::date);

                       IF  v_ano_1  >  v_ano_2 THEN
                         va_fecha[v_i] = ('31-12-'|| v_ano_2::varchar)::date;
                       END IF;
                    END IF;


             END LOOP;

              IF v_i > 0 THEN

                    --llamada a la funcion de compromiso
                    va_resp_ges =  pre.f_gestionar_presupuesto(p_id_usuario,
                    										   NULL, --tipo cambio
                                                               va_id_presupuesto,
                                                               va_id_partida,
                                                               va_id_moneda,
                                                               va_monto,
                                                               va_fecha, --p_fecha
                                                               va_momento,
                                                               NULL,--  p_id_partida_ejecucion
                                                               va_columna_relacion,
                                                               va_fk_llave,
                                                               va_num_tramite,--nro_tramite
                                                               NULL,
                                                               p_conexion);



                 --actualizacion de los id_partida_ejecucion en el detalle de solicitud

                   FOR v_cont IN 1..v_i LOOP


                      update mat.tdetalle_sol  s set
                         id_partida_ejecucion = va_resp_ges[v_cont],
                         fecha_mod = now(),
                         id_usuario_mod = p_id_usuario,
                         revertido_mb = 0,     -- inicializa el monto de reversion
                         revertido_mo = 0     -- inicializa el monto de reversion
                      where s.id_detalle =  va_id_solicitud_det[v_cont];


                   END LOOP;
             END IF;



        ELSEIF p_operacion = 'revertir' THEN

       --revierte al revveertir la probacion de la solicitud

           v_i = 0;
           v_men_presu = '';
           FOR v_registros in (
							select
                                ds.id_detalle,
                                ds.id_centro_costo,
                                ds.id_partida,
                                ds.precio_total,
                                ds.id_partida_ejecucion,
                                ds.revertido_mb,
                                ds.revertido_mo,
                                s.id_gestion,
                                s.id_solicitud,
                                p.id_presupuesto,
                                s.presu_comprometido,
                                s.id_moneda,
                                s.fecha_solicitud,
                                s.nro_tramite
                            from mat.tsolicitud s
                            inner join mat.tdetalle_sol ds on ds.id_solicitud = s.id_solicitud
                            inner join pre.tpresupuesto p on p.id_centro_costo = ds.id_centro_costo
                            where s.id_solicitud = p_id_solicitud
                                and ds.estado_reg = 'activo'
                                and ds.cantidad_sol > 0) LOOP

                     IF(v_registros.id_partida_ejecucion is not  NULL) THEN


                           v_comprometido=0;
                           v_ejecutado=0;



                           SELECT
                                 COALESCE(ps_comprometido,0),
                                 COALESCE(ps_ejecutado,0)
                             into
                                 v_comprometido,
                                 v_ejecutado
                           FROM pre.f_verificar_com_eje_pag(v_registros.id_partida_ejecucion,v_registros.id_moneda);   --v_id_moneda_base);


                           v_monto_a_revertir = COALESCE(v_comprometido,0) - COALESCE(v_ejecutado,0);


                          --armamos los array para enviar a presupuestos
                          IF v_monto_a_revertir != 0 THEN

                              v_i = v_i +1;

                              va_id_presupuesto[v_i] = v_registros.id_presupuesto;
                              va_id_partida[v_i]= v_registros.id_partida;
                              va_momento[v_i]	= 2; --el momento 2 con signo positivo es revertir
                              va_monto[v_i]  = (v_monto_a_revertir)*-1;  -- considera la posibilidad de que a este item se le aya revertido algun monto
                              va_id_moneda[v_i]  = v_registros.id_moneda; -- v_id_moneda_base;
                              va_id_partida_ejecucion[v_i]= v_registros.id_partida_ejecucion;
                              va_columna_relacion[v_i]= 'id_solicitud_mat';
                              va_fk_llave[v_i] = v_registros.id_solicitud;
                              va_id_solicitud_det[v_i]= v_registros.id_detalle;
                              va_num_tramite[v_i] = v_reg_sol.nro_tramite;


                               -- la fecha de solictud es la fecha de compromiso
                              IF  now()  < v_registros.fecha_solicitud THEN
                                va_fecha[v_i] = v_registros.fecha_solicitud::date;
                              ELSE
                                 -- la fecha de reversion como maximo puede ser el 31 de diciembre
                                 va_fecha[v_i] = now()::date;
                                 v_ano_1 =  EXTRACT(YEAR FROM  now()::date);
                                 v_ano_2 =  EXTRACT(YEAR FROM  v_registros.fecha_solicitud::date);

                                 IF  v_ano_1  >  v_ano_2 THEN
                                   va_fecha[v_i] = ('31-12-'|| v_ano_2::varchar)::date;
                                 END IF;
                              END IF;


                          END IF;


                          v_men_presu = ' comprometido: '||COALESCE(v_comprometido,0)::varchar||'  ejecutado: '||COALESCE(v_ejecutado,0)::varchar||' \n'||v_men_presu;


                   ELSE
                        raise notice 'El presupuesto del detalle con el identificador (%)  no se encuentra comprometido',v_registros.id_solicitud_det;
                   END IF;


             END LOOP;


             --llamada a la funcion de para reversion
               IF v_i > 0 THEN
                  va_resp_ges =  pre.f_gestionar_presupuesto(p_id_usuario,
                    										 NULL, --tipo cambio
                  											 va_id_presupuesto,
                                                             va_id_partida,
                                                             va_id_moneda,
                                                             va_monto,
                                                             va_fecha, --p_fecha
                                                             va_momento,
                                                             va_id_partida_ejecucion,--  p_id_partida_ejecucion
                                                             va_columna_relacion,
                                                             va_fk_llave,
                                                             va_num_tramite,--nro_tramite
                                                             NULL,
                                                             p_conexion);
               END IF;


       ELSIF p_operacion = 'verificar' THEN

           --verifica si tenemos suficiente presupeusto para comprometer
          v_i = 0;
          v_mensage_error = '';
          v_sw_error = false;


          v_pre_verificar_categoria = pxp.f_get_variable_global('pre_verificar_categoria');
          v_pre_verificar_tipo_cc = pxp.f_get_variable_global('pre_verificar_tipo_cc');
          v_pre_verificar_tipo_cc_control = pxp.f_get_variable_global('pre_verificar_tipo_cc_control');
          v_control_partida = 'si'; --por defeto controlamos los monstos por partidas

          IF   v_pre_verificar_categoria = 'si' THEN

            		-- verifica  por categoria programatica
                      FOR v_registros in (
                                        select
                                            p.id_categoria_prog,
                                            p.codigo_cc,
                                            s.id_gestion,
                                            s.id_solicitud,
                                            s.id_moneda,
                                            ds.id_partida,
                                            sum(ds.precio_total) as precio_ga,
                                            par.codigo,
                                            par.nombre_partida,
                                            pxp.aggarray(p.id_centro_costo) as id_centro_costos
                                        from mat.tsolicitud s
                                        inner join mat.tdetalle_sol ds on ds.id_solicitud = s.id_solicitud
                                        inner join pre.tpartida par on par.id_partida = ds.id_partida
                                        inner join pre.vpresupuesto_cc p on p.id_centro_costo = ds.id_centro_costo
                                        where s.id_solicitud = p_id_solicitud
                                            and ds.estado_reg = 'activo'
                                            and ds.cantidad_sol > 0

                                          group by

                                          p.id_categoria_prog,
                                          p.codigo_cc,
                                          s.id_gestion,
                                          s.id_solicitud,
                                          ds.id_partida,
                                          s.id_moneda,
                                          par.codigo,
                                          par.nombre_partida)

                                      LOOP

                                          select
                                                sd.id_centro_costo
                                            INTO
                                                v_id_centro_costo
                                          from  mat.tdetalle_sol sd
                                          where  sd.id_solicitud = p_id_solicitud
                                          limit 1 offset 0;

                                          v_resp_pre = pre.f_verificar_presupuesto_partida ( v_registros.id_centro_costos[1],
                                                                    v_registros.id_partida,
                                                                    v_registros.id_moneda,
                                                                    v_registros.precio_ga);


                                          IF   v_resp_pre = 'false' THEN
                                               v_mensage_error = v_mensage_error||format('Presupuesto CP:  %s, partida (%s) %s <BR/>', v_registros.codigo_cc, v_registros.codigo,v_registros.nombre_partida);
                                               v_sw_error = true;

                                          END IF;



                         END LOOP;





          ELSE

              IF   v_pre_verificar_tipo_cc = 'si' THEN

                   --la verificacion sea por tipo de centro de costo del tipo techo, ademas se verifica si es necesario validar por partida

                   FOR v_registros in (select
                                          tcc.id_tipo_cc_techo,
                                          s.id_gestion,
                                          s.id_solicitud,
                                          s.id_moneda,
                                          sum(ds.precio_total) as precio_ga,
                                          tcc.codigo_techo,
                                          case when tcc.control_partida::text = 'no' then
                                              0
                                          else
                                              ds.id_partida
                                          end as id_par,
                                          case when tcc.control_partida::text = 'no' then
                                              'No se considera partida'::varchar
                                          else
                                              par.nombre_partida
                                          end as nombre_partida_desc,
                                          pxp.aggarray(p.id_centro_costo) AS id_centro_costos
                                      from mat.tsolicitud s
                                      inner join mat.tdetalle_sol ds on ds.id_solicitud = s.id_solicitud
                                      inner join pre.tpartida par on par.id_partida = ds.id_partida
                                      join param.tcentro_costo p on p.id_centro_costo = ds.id_centro_costo
                                      join param.vtipo_cc_techo tcc on tcc.id_tipo_cc = p.id_tipo_cc
                                      where s.id_solicitud = p_id_solicitud
                                          and ds.estado_reg = 'activo'
                                          and ds.cantidad_sol > 0

                                        group by

                                       tcc.id_tipo_cc_techo,
                                       tcc.control_partida,
                                       s.id_gestion,
                                       s.id_solicitud,
                                       id_par,
                                       s.id_moneda,
                                       nombre_partida_desc,
                                       tcc.codigo_techo)
                            LOOP


                                          v_resp_pre = pre.f_verificar_presupuesto_partida ( v_registros.id_centro_costos[1],
                                                                    v_registros.id_par,
                                                                    v_registros.id_moneda,
                                                                    v_registros.precio_ga);


                                          IF   v_resp_pre = 'false' THEN
                                               v_mensage_error = v_mensage_error||format('Presupuesto:  %s,  (%s)  <BR/>', v_registros.codigo_techo, v_registros.nombre_partida_desc);
                                               v_sw_error = true;

                                          END IF;



                         END LOOP;




              ELSE

                  -- La verificacion es sencilla por presupeusto y por partida
                   FOR v_registros in (select
                                        ds.id_centro_costo,
                                        ds.id_partida,
                                        sum(ds.precio_total) as precio_ga,
                                        s.id_gestion,
                                        s.id_solicitud,
                                        s.id_moneda,
                                        p.id_presupuesto,
                                        p.codigo_cc,
                                        par.codigo,
                                        par.nombre_partida
                                    from mat.tsolicitud s
                                    inner join mat.tdetalle_sol ds on ds.id_solicitud = s.id_solicitud
                                    inner join pre.tpartida par on par.id_partida = ds.id_partida
                                    inner join pre.vpresupuesto_cc p on p.id_centro_costo = ds.id_centro_costo
                                    /*Comentando para tomar solo del detalle solicitud (Ismael Valdivia 18/03/2022)*/
                                    --inner join mat.tcotizacion_detalle cot on cot.id_solicitud = s.id_solicitud
                                    where s.id_solicitud = p_id_solicitud
                                        and ds.estado_reg = 'activo'
                                        and ds.cantidad_sol > 0

                                          group by

                                          ds.id_centro_costo,
                                          s.id_gestion,
                                          s.id_solicitud,
                                          ds.id_partida,
                                          p.id_presupuesto,
                                          s.id_moneda,
                                          par.codigo,
                                          par.nombre_partida,
                                          p.codigo_cc)
                            LOOP

                                          select
                                                ds.id_centro_costo
                                            INTO
                                                v_id_centro_costo
                                          from  mat.tdetalle_sol ds
                                          where  ds.id_solicitud = p_id_solicitud;

                                          v_resp_pre = pre.f_verificar_presupuesto_partida ( v_id_centro_costo,
                                                                    v_registros.id_partida,
                                                                    v_registros.id_moneda,
                                                                    v_registros.precio_ga);


                                          IF   v_resp_pre = 'false' THEN
                                               v_mensage_error = v_mensage_error||format('Presupuesto CC:  %s, partida (%s) %s <BR/>', v_registros.codigo_cc, v_registros.codigo,v_registros.nombre_partida);
                                               v_sw_error = true;

                                          END IF;



                         END LOOP;



              END IF;
          END IF;

		if v_pre_verificar_tipo_cc_control = 'si' then

        	-- verificar si se aprobo el presupuesto a nivel centro de costo por presupuestos
             select sol.presupuesto_aprobado
               into v_llave_control_presupuesto
             from mat.tsolicitud sol
             where sol.id_solicitud = p_id_solicitud;


             if (v_llave_control_presupuesto in ('verificar', 'sin_presupuesto_cc') and v_sw_error = false )then
                   FOR v_registros in (select
                                              ds.id_centro_costo,
                                              ds.id_partida,
                                              sum(ds.precio_total) as precio_ga,
                                              s.id_gestion,
                                              s.id_solicitud,
                                              s.id_moneda,
                                              p.id_presupuesto,
                                              p.codigo_cc,
                                              par.codigo,
                                              par.nombre_partida
                                          from mat.tsolicitud s
                                          inner join mat.tdetalle_sol ds on ds.id_solicitud = s.id_solicitud
                                          inner join pre.tpartida par on par.id_partida = ds.id_partida
                                          inner join pre.vpresupuesto_cc p on p.id_centro_costo = ds.id_centro_costo
                                          where s.id_solicitud = p_id_solicitud
                                              and ds.estado_reg = 'activo'
                                              and ds.cantidad_sol > 0

                                          group by

                                          ds.id_centro_costo,
                                          s.id_gestion,
                                          s.id_solicitud,
                                          ds.id_partida,
                                          p.id_presupuesto,
                                          s.id_moneda,
                                          par.codigo,
                                          par.nombre_partida,
                                          p.codigo_cc)
                            LOOP

                                          select
                                                sd.id_centro_costo
                                            INTO
                                                v_id_centro_costo
                                          from  mat.tdetalle_sol sd
                                          where  sd.id_solicitud = p_id_solicitud;

                                          v_resp_pre = pre.f_verificar_presupuesto_partida_centro_costo(v_id_centro_costo,
                                                                    v_registros.id_partida,
                                                                    v_registros.id_moneda,
                                                                    v_registros.precio_ga);


                                          IF   v_resp_pre = 'false' THEN
                                               v_mensage_error = v_mensage_error||format('Presupuesto :  %s, partida (%s) %s <BR/>', v_registros.codigo_cc, v_registros.codigo,v_registros.nombre_partida);
                                               v_sw_error = true;

                                          END IF;



                         END LOOP;
                         v_mensage_error = 'EL centro de costo -> '||v_mensage_error;
             end if;
        end if;




             IF v_sw_error THEN
                 raise exception 'No se tiene suficiente presupuesto para; <BR/>%', v_mensage_error;
             END IF;


             return TRUE;

       ELSE

          raise exception 'Oepracion no implementada';

       END IF;



  return  TRUE;


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

ALTER FUNCTION mat.f_gestionar_presupuesto_solicitud (p_id_solicitud integer, p_id_usuario integer, p_operacion varchar, p_conexion varchar)
  OWNER TO postgres;
