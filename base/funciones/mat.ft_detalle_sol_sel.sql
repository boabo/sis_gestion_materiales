CREATE OR REPLACE FUNCTION mat.ft_detalle_sol_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestión de Materiales
 FUNCION: 		mat.ft_detalle_sol_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tdetalle_sol'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_inner varchar;
    v_revisado varchar;
    v_origen_pedido		varchar;

BEGIN

	v_nombre_funcion = 'mat.ft_detalle_sol_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_DET_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/




    if(p_transaccion='MAT_DET_SEL')then

    	begin
  --  raise exception 'llega %',v_parametros.parte;
    		--Sentencia de la consulta
			v_consulta:='select
						det.id_detalle,
						det.id_solicitud,
						det.descripcion,
						det.estado_reg,
						det.id_unidad_medida,
						det.nro_parte,
						det.referencia,
						det.nro_parte_alterno,
						det.id_moneda,
						det.precio_unitario,
						det.cantidad_sol,
						det.id_usuario_reg,
						det.usuario_ai,
						det.fecha_reg,
						det.id_usuario_ai,
						det.id_usuario_mod,
						det.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        un.codigo,
                        un.descripcion as desc_descripcion,
                        det.revisado,
                        det.tipo,
                        s.estado,
                        det.explicacion_detallada_part,
                        /*Aumentando los campos (Ismael Valdivia 31/01/2020)*/
                        det.id_centro_costo,
                        det.id_concepto_ingas,
                        det.id_orden_trabajo,
                        cc.codigo_cc as desc_centro_costo,
                        ingas.desc_ingas as desc_concepto_ingas,
                        orden.desc_orden as desc_orden_trabajo,
                        det.precio_total,
                        det.condicion_det
                        /****************************************************/
						from mat.tdetalle_sol det
						inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
                        left join mat.tunidad_medida un on un.id_unidad_medida = det.id_unidad_medida
                        inner join mat.tsolicitud s on s.id_solicitud = det.id_solicitud

                        /*Aumentando para recuperar detalles (Ismael Valdivia 31/01/2020)*/
                        left join param.vcentro_costo cc on cc.id_centro_costo = det.id_centro_costo
                        left join param.tconcepto_ingas ingas on ingas.id_concepto_ingas = det.id_concepto_ingas
                        left join conta.torden_trabajo orden on orden.id_orden_trabajo = det.id_orden_trabajo
                        /******************************************************************/
                        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_DET_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_DET_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_detalle),
            			/*Aumentando para obtener suma (Ismael valdivia 17/02/2020)*/
                        sum (det.precio_total)
                        /*****************************/
					    from mat.tdetalle_sol det
					    inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
					    left join mat.tunidad_medida un on un.id_unidad_medida = det.id_unidad_medida
                        inner join mat.tsolicitud s on s.id_solicitud = det.id_solicitud

                        /*Aumentando para recuperar detalles (Ismael Valdivia 31/01/2020)*/
                        left join param.vcentro_costo cc on cc.id_centro_costo = det.id_centro_costo
                        left join param.tconcepto_ingas ingas on ingas.id_concepto_ingas = det.id_concepto_ingas
                        left join conta.torden_trabajo orden on orden.id_orden_trabajo = det.id_orden_trabajo

                        /*Comentando esta parte para que no muestre (Ismael Valdivia 14/02/2020)*/
                         /*
                          left join pre.tpartida par on par.id_partida = det.id_partida
                          left join conta.tcuenta cue on cue.id_cuenta = det.id_cuenta
                          left join conta.tauxiliar aux on aux.id_auxiliar = det.id_auxiliar
                         */
                        /******************************************************************/

                        where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

  	/*********************************
 	#TRANSACCION:  'MAT_DETCS_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		08-05-2020 16:00:01
	***********************************/
    elsif(p_transaccion='MAT_DETCS_SEL')then

    	begin
          	-- raise exception 'llega %',v_parametros.id_solicitud;
            select sol.origen_pedido into v_origen_pedido
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;

            if (v_origen_pedido = 'Reparación de Repuestos') then


    		--Sentencia de la consulta
			v_consulta:='select
						det.id_detalle,
						det.id_solicitud,
						det.descripcion,
						det.estado_reg,
						det.id_unidad_medida,
						det.nro_parte,
						cotdet.referencia_cot,
						det.nro_parte_alterno,
						det.id_moneda,
						det.precio_unitario,
						det.cantidad_sol,
						det.id_usuario_reg,
						det.usuario_ai,
						det.fecha_reg,
						det.id_usuario_ai,
						det.id_usuario_mod,
						det.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        un.codigo,
                        un.descripcion as desc_descripcion,
                        det.revisado,
                        det.tipo,
                        s.estado,
                        det.explicacion_detallada_part,
                        /*Aumentando los campos (Ismael Valdivia 31/01/2020)*/
                        det.id_centro_costo,
                        det.id_concepto_ingas,
                        det.id_orden_trabajo,
                        cc.codigo_cc as desc_centro_costo,
                        ingas.desc_ingas as desc_concepto_ingas,
                        orden.desc_orden as desc_orden_trabajo,
                        det.precio_total,
                        det.condicion_det,
                        c.codigo_categoria,
                        par.codigo as codigo_partida,
                        par.nombre_partida,
                        pre.id_presupuesto,
                        par.id_partida

                        /****************************************************/
						from mat.tdetalle_sol det
						inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
                        inner join mat.tunidad_medida un on un.id_unidad_medida = det.id_unidad_medida
                        inner join mat.tsolicitud s on s.id_solicitud = det.id_solicitud
                        left join mat.tcotizacion cot on cot.id_solicitud = det.id_solicitud and cot.adjudicado = ''si''
                        left join mat.tcotizacion_detalle cotdet on cotdet.id_cotizacion = cot.id_cotizacion and det.id_detalle = cotdet.id_detalle

                        /*Aumentando para recuperar detalles (Ismael Valdivia 31/01/2020)*/
                        left join param.vcentro_costo cc on cc.id_centro_costo = det.id_centro_costo
                        left join param.tconcepto_ingas ingas on ingas.id_concepto_ingas = det.id_concepto_ingas
                        left join conta.torden_trabajo orden on orden.id_orden_trabajo = det.id_orden_trabajo
                        left join pre.tpartida par on par.id_partida = det.id_partida
                        left join conta.tcuenta cta on cta.id_cuenta = det.id_cuenta
                        left join conta.tauxiliar aux on aux.id_auxiliar = det.id_auxiliar
                        left join pre.tpresupuesto pre on pre.id_centro_costo = cc.id_centro_costo
                        left join pre.vcategoria_programatica c on c.id_categoria_programatica = pre.id_categoria_prog
                        where  det.id_solicitud = '||v_parametros.id_solicitud||' and ';
            else
            	v_consulta:='select
						det.id_detalle,
						det.id_solicitud,
						det.descripcion,
						det.estado_reg,
						det.id_unidad_medida,
						det.nro_parte,
						det.referencia,
						det.nro_parte_alterno,
						det.id_moneda,
						det.precio_unitario,
						det.cantidad_sol,
						det.id_usuario_reg,
						det.usuario_ai,
						det.fecha_reg,
						det.id_usuario_ai,
						det.id_usuario_mod,
						det.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        un.codigo,
                        un.descripcion as desc_descripcion,
                        det.revisado,
                        det.tipo,
                        s.estado,
                        det.explicacion_detallada_part,
                        /*Aumentando los campos (Ismael Valdivia 31/01/2020)*/
                        det.id_centro_costo,
                        det.id_concepto_ingas,
                        det.id_orden_trabajo,
                        cc.codigo_cc as desc_centro_costo,
                        ingas.desc_ingas as desc_concepto_ingas,
                        orden.desc_orden as desc_orden_trabajo,
                        det.precio_total,
                        det.condicion_det,
                        c.codigo_categoria,
                        par.codigo as codigo_partida,
                        par.nombre_partida,
                        pre.id_presupuesto,
                        par.id_partida

                        /****************************************************/
						from mat.tdetalle_sol det
						inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
                        inner join mat.tunidad_medida un on un.id_unidad_medida = det.id_unidad_medida
                        inner join mat.tsolicitud s on s.id_solicitud = det.id_solicitud

                        /*Aumentando para recuperar detalles (Ismael Valdivia 31/01/2020)*/
                        left join param.vcentro_costo cc on cc.id_centro_costo = det.id_centro_costo
                        left join param.tconcepto_ingas ingas on ingas.id_concepto_ingas = det.id_concepto_ingas
                        left join conta.torden_trabajo orden on orden.id_orden_trabajo = det.id_orden_trabajo
                        left join pre.tpartida par on par.id_partida = det.id_partida
                        left join conta.tcuenta cta on cta.id_cuenta = det.id_cuenta
                        left join conta.tauxiliar aux on aux.id_auxiliar = det.id_auxiliar
                        left join pre.tpresupuesto pre on pre.id_centro_costo = cc.id_centro_costo
                        left join pre.vcategoria_programatica c on c.id_categoria_programatica = pre.id_categoria_prog
                        where  det.id_solicitud = '||v_parametros.id_solicitud||' and ';
            end if;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

        /*********************************
 	#TRANSACCION:  'MAT_DETSC_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		08-05-2020 16:00:01
	***********************************/

	elsif(p_transaccion='MAT_DETSC_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros

            select sol.origen_pedido into v_origen_pedido
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;

            if (v_origen_pedido = 'Reparación de Repuestos') then
            	v_consulta:='select count(id_detalle),
            			/*Aumentando para obtener suma (Ismael valdivia 17/02/2020)*/
                        sum (det.precio_total)
                        /*****************************/
					    from mat.tdetalle_sol det
					    inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
					    inner join mat.tunidad_medida un on un.id_unidad_medida = det.id_unidad_medida
                        inner join mat.tsolicitud s on s.id_solicitud = det.id_solicitud
                        left join mat.tcotizacion cot on cot.id_solicitud = det.id_solicitud and cot.adjudicado = ''si''
                        left join mat.tcotizacion_detalle cotdet on cotdet.id_cotizacion = cot.id_cotizacion and det.id_detalle = cotdet.id_detalle

                        /*Aumentando para recuperar detalles (Ismael Valdivia 31/01/2020)*/
                        left join param.vcentro_costo cc on cc.id_centro_costo = det.id_centro_costo
                        left join param.tconcepto_ingas ingas on ingas.id_concepto_ingas = det.id_concepto_ingas
                        left join conta.torden_trabajo orden on orden.id_orden_trabajo = det.id_orden_trabajo

                        /*Comentando esta parte para que no muestre (Ismael Valdivia 14/02/2020)*/
                         /*
                          left join pre.tpartida par on par.id_partida = det.id_partida
                          left join conta.tcuenta cue on cue.id_cuenta = det.id_cuenta
                          left join conta.tauxiliar aux on aux.id_auxiliar = det.id_auxiliar
                         */
                        /******************************************************************/

                        where det.id_solicitud = '||v_parametros.id_solicitud||' and ';
            else


			v_consulta:='select count(id_detalle),
            			/*Aumentando para obtener suma (Ismael valdivia 17/02/2020)*/
                        sum (det.precio_total)
                        /*****************************/
					    from mat.tdetalle_sol det
					    inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
					    inner join mat.tunidad_medida un on un.id_unidad_medida = det.id_unidad_medida
                        inner join mat.tsolicitud s on s.id_solicitud = det.id_solicitud

                        /*Aumentando para recuperar detalles (Ismael Valdivia 31/01/2020)*/
                        left join param.vcentro_costo cc on cc.id_centro_costo = det.id_centro_costo
                        left join param.tconcepto_ingas ingas on ingas.id_concepto_ingas = det.id_concepto_ingas
                        left join conta.torden_trabajo orden on orden.id_orden_trabajo = det.id_orden_trabajo

                        /*Comentando esta parte para que no muestre (Ismael Valdivia 14/02/2020)*/
                         /*
                          left join pre.tpartida par on par.id_partida = det.id_partida
                          left join conta.tcuenta cue on cue.id_cuenta = det.id_cuenta
                          left join conta.tauxiliar aux on aux.id_auxiliar = det.id_auxiliar
                         */
                        /******************************************************************/

                        where det.id_solicitud = '||v_parametros.id_solicitud||' and ';
            end if;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_UM_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_UM_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
                                un.id_unidad_medida,
                                un.codigo,
                                un.descripcion,
                                un.tipo_unidad_medida
                                from mat.tunidad_medida un
                                where  ';
            --Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

  	else
		raise exception 'Transaccion inexistente';
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

ALTER FUNCTION mat.ft_detalle_sol_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
