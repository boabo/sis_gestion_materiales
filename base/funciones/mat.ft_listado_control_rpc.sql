CREATE OR REPLACE FUNCTION mat.ft_listado_control_rpc (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion Materiales
 FUNCION: 		mat.ft_listado_control_rpc
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'cola.tsucursal_video'
 AUTOR: 		 (Ismael Valdivia)
 FECHA:	        26-01-2022 10:15:34
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

    v_fill				varchar;
    v_fil_func			varchar;
    v_id_funcionario_recu	integer;

BEGIN

	v_nombre_funcion = 'mat.ft_listado_control_rpc';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_RPCE_REP_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Ismael.Valdivia
 	#FECHA:		08-08-2017 21:54:34
	***********************************/

	if(p_transaccion='MAT_RPCE_REP_SEL')then

    	begin
    		--Sentencia de la consulta
            /*Aumentando para listar solo del RPC Encargado*/


            if (p_administrador != 1) then
            	select fun.id_funcionario into v_id_funcionario_recu
                from segu.vusuario us
                inner join orga.vfuncionario fun on fun.id_persona = us.id_persona
                where us.id_usuario = p_id_usuario;

                v_fil_func = 'e.id_funcionario = '||v_id_funcionario_recu;

            else
            	v_fil_func = '0=0';
            end if;


            /**********************************************/


            IF  pxp.f_existe_parametro(p_tabla,'origen_pedido') THEN
              if (v_parametros.origen_pedido != 'Todos')then
                    v_fill = ' e.fecha_reg::date >='''||v_parametros.fecha_ini||''' and e.fecha_reg::date <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and c.adjudicado = ''si''';

            else
                    v_fill = ' e.fecha_reg::date >='''||v_parametros.fecha_ini||''' and e.fecha_reg::date <= '''||v_parametros.fecha_fin||'''and c.adjudicado = ''si''';
            end if;
            ELSE

              v_fill = ' e.fecha_reg::date >='''||now()::date||''' and e.fecha_reg::date <= '''||now()::date||''' and c.adjudicado = ''si''';


            END IF;






           v_consulta:='	select	 s.origen_pedido,
                                     s.nro_tramite,
                                     s.estado as estado,
                                     (upper(f.desc_funcionario1))::varchar as funciaonario,
                                     to_char(s.fecha_solicitud,''DD/MM/YYYY'')::varchar as fecha_solicitud,
                                     initcap(s.motivo_solicitud)::varchar as motivo_solicitud,
                                     initcap(s.observaciones_sol)::varchar as observaciones_sol,
                                     s.remark::varchar,
                                     s.justificacion,
                                     s.nro_justificacion,
                                     s.tipo_solicitud,
                                     s.tipo_falla,
                                     s.tipo_reporte,
                                     s.mel,
                                     s.nro_no_rutina,
                                     c.nro_cotizacion,
                                     initcap(v.desc_proveedor) as proveedor,
                                     s.nro_po,
                                     vu.desc_persona::varchar as aux_abas,
                                     to_char(MAX(e.fecha_reg::date), ''DD/MM/YYYY'')::varchar as fecha_autorizacion_rpc,
                                     fun.desc_funcionario1::varchar as encargado_rpc,
                                     sum(d.precio_unitario_mb)::numeric,
                                     s.id_proceso_wf::numeric,
                                     mon.codigo_internacional::varchar,
         							 func.desc_funcionario1::varchar as funcionario_solicitante,
                                     s.id_solicitud::numeric
                           from  mat.tsolicitud s
                           inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                           inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud
                           inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion and d.revisado = ''si''
                           inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                           inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf
                           inner join wf.ttipo_estado t on t.id_tipo_estado = e.id_tipo_estado and t.codigo = ''vb_rpcd''
                           inner join orga.vfuncionario fun on fun.id_funcionario = e.id_funcionario
                           left join segu.vusuario vu on c.id_usuario_reg = vu.id_usuario
                           inner join param.tmoneda mon on mon.id_moneda = s.id_moneda
                           inner join orga.vfuncionario func on func.id_funcionario = s.id_funcionario_solicitante
                           where '||v_fil_func||' and '||v_fill||' and '||v_parametros.filtro||'
                           group by s.origen_pedido,
                                  s.nro_tramite,
                                  s.estado,
                                  f.desc_funcionario1,
                                  s.fecha_solicitud,
                                  s.fecha_requerida,
                                  s.motivo_solicitud,
                                  s.observaciones_sol,
                                  s.justificacion,
                                  s.nro_justificacion,
                                  s.tipo_solicitud,
                                  s.tipo_falla,
                                  s.tipo_reporte,
                                  s.mel,
                                  s.nro_no_rutina,
                                  c.nro_cotizacion,
                                  v.desc_proveedor,
                                  vu.desc_persona,
                                  s.nro_po,
                                  s.remark,
                                  fun.desc_funcionario1,
                                  --d.precio_unitario_mb,
                                  s.id_proceso_wf,
                                  mon.codigo_internacional,
                                  func.desc_funcionario1,
                                  e.fecha_reg ,
                                  s.id_solicitud
                          	order by e.fecha_reg desc ';

			return v_consulta;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_RPCE_REP_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael.Valdivia
 	#FECHA:		08-08-2017 21:54:34
	***********************************/

	elsif(p_transaccion='MAT_RPCE_REP_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
            /*Aumentando para listar solo del RPC Encargado*/


            if (p_administrador != 1) then
            	select fun.id_funcionario into v_id_funcionario_recu
                from segu.vusuario us
                inner join orga.vfuncionario fun on fun.id_persona = us.id_persona
                where us.id_usuario = p_id_usuario;

                v_fil_func = 'e.id_funcionario = '||v_id_funcionario_recu;

            else
            	v_fil_func = '0=0';
            end if;


            /**********************************************/

             IF  pxp.f_existe_parametro(p_tabla,'origen_pedido') THEN
              if (v_parametros.origen_pedido != 'Todos')then
                    v_fill = ' e.fecha_reg::date >='''||v_parametros.fecha_ini||''' and e.fecha_reg::date <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and c.adjudicado = ''si''';

            else
                    v_fill = ' e.fecha_reg::date >='''||v_parametros.fecha_ini||''' and e.fecha_reg::date <= '''||v_parametros.fecha_fin||'''and c.adjudicado = ''si''';
            end if;
            ELSE

              v_fill = ' e.fecha_reg::date >='''||now()::date||''' and e.fecha_reg::date <= '''||now()::date||''' and c.adjudicado = ''si''';


            END IF;

           v_consulta:='	select	 count(s.nro_tramite)
                           from  mat.tsolicitud s
                           inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                           inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud
                           inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion and d.revisado = ''si''
                           inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                           inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf
                           inner join wf.ttipo_estado t on t.id_tipo_estado = e.id_tipo_estado and t.codigo = ''vb_rpcd''
                           inner join orga.vfuncionario fun on fun.id_funcionario = e.id_funcionario
                           left join segu.vusuario vu on c.id_usuario_reg = vu.id_usuario
                           inner join param.tmoneda mon on mon.id_moneda = s.id_moneda
                           inner join orga.vfuncionario func on func.id_funcionario = s.id_funcionario_solicitante
                           where '||v_fil_func||' and '||v_fill||' and '||v_parametros.filtro||'';

			return v_consulta;

		end;


    /*********************************
 	#TRANSACCION:  'MAT_DET_SOL_RPC_SEL'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael.Valdivia
 	#FECHA:		08-08-2017 21:54:34
	***********************************/

	elsif(p_transaccion='MAT_DET_SOL_RPC_SEL')then

		begin


           v_consulta:='
                          SELECT	TO_JSON(ROW_TO_JSON(jsonD) :: TEXT) #>> ''{}'' as jsonData
                                                    FROM (

                          SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(detalle))) as detalle_solicitud
                          FROM(


                          select det.nro_parte_cot,
                                 det.explicacion_detallada_part_cot,
                                 det.cantidad_det,
                                 det.precio_unitario,
                                 day.codigo_tipo as cantidad_dias,
                                 (cc.ep || '' - '' || cc.nombre_uo)::varchar as centro_costo,
                                 COALESCE (ot.desc_orden,'' '')::varchar as matricula,
                                 (pp.codigo || '' - '' || pp.nombre_partida)::varchar as partida
                          from mat.tcotizacion cot
                          inner join mat.tcotizacion_detalle det on det.id_solicitud = cot.id_solicitud
                          inner join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
                          left join mat.tday_week day on day.id_day_week = det.id_day_week
                          inner join mat.tsolicitud sol on sol.id_solicitud = cot.id_solicitud
                          left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                          left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
                          left join pre.tpartida pp on deta.id_partida = pp.id_partida
                          where cot.id_solicitud = '||v_parametros.id_solicitud||'
                          and cot.adjudicado = ''si''

                          ) detalle
                          ) jsonD';

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

ALTER FUNCTION mat.ft_listado_control_rpc (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
