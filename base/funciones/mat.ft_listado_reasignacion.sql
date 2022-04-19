CREATE OR REPLACE FUNCTION mat.ft_listado_reasignacion (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_listado_reasignacion
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tunidad_medida'
 AUTOR: 		 (Ismael Valdivia)
 FECHA:	        18-04-2022 11:31:00
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

BEGIN

	v_nombre_funcion = 'mat.ft_listado_reasignacion';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_REASIGN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		18-04-2022 11:31:00
	***********************************/

	if(p_transaccion='MAT_REASIGN_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select sol.nro_tramite::varchar,
                                sol.estado::varchar,
                                sol.id_proceso_wf::integer,
                                to_char(sol.fecha_solicitud,''DD/MM/YYYY'')::varchar,
                                sol.id_gestion::integer,
                                sol.id_estado_wf::integer,
                                (select vf.desc_funcionario1
                                from wf.testado_wf esta
                                inner join wf.ttipo_estado te on te.id_tipo_estado = esta.id_tipo_estado
                                inner join orga.vfuncionario_cargo vf on vf.id_funcionario = esta.id_funcionario
                                where esta.id_proceso_wf = sol.id_proceso_wf
                                AND te.codigo = ''cotizacion''
                                order by esta.fecha_reg DESC
                                limit 1)::varchar,
                                sol.id_solicitud
                        from mat.tsolicitud sol
                        where sol.estado = ''cotizacion''
                        and sol.origen_pedido not in (''Reparación de Repuestos'')
                        and ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_REASIGN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		18-04-2022 11:31:00
	***********************************/

	elsif(p_transaccion='MAT_REASIGN_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(sol.id_solicitud)
                        from mat.tsolicitud sol
                        where sol.estado = ''cotizacion''
                        and sol.origen_pedido not in (''Reparación de Repuestos'')
                        and ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;


    /*********************************
 	#TRANSACCION:  'MAT_MACR_LIST_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		18-04-2022 11:31:00
	***********************************/

	elsif(p_transaccion='MAT_MACR_LIST_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select est.id_estado_wf,
                                macro.nombre,
                                macro.id_proceso_macro,
                                macro.id_subsistema,
                                te.codigo
                        from wf.testado_wf est
                        inner join wf.ttipo_estado te on te.id_tipo_estado = est.id_tipo_estado
                        inner join wf.ttipo_proceso tp on tp.id_tipo_proceso = te.id_tipo_proceso
                        inner join wf.tproceso_macro macro on macro.id_proceso_macro = tp.id_proceso_macro
                        where te.codigo = ''cotizacion'' and ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_MACR_LIST_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		18-04-2022 11:31:00
	***********************************/

	elsif(p_transaccion='MAT_MACR_LIST_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(est.id_estado_wf)
                        from wf.testado_wf est
                        inner join wf.ttipo_estado te on te.id_tipo_estado = est.id_tipo_estado
                        inner join wf.ttipo_proceso tp on tp.id_tipo_proceso = te.id_tipo_proceso
                        inner join wf.tproceso_macro macro on macro.id_proceso_macro = tp.id_proceso_macro
                        where te.codigo = ''cotizacion'' and ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_ENCAR_LIST_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		18-04-2022 11:31:00
	***********************************/

	elsif(p_transaccion='MAT_ENCAR_LIST_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='
                          select carg.id_funcionario::INTEGER,
                                 carg.desc_funcionario1::VARCHAR
                          from wf.tproceso_macro pm
                          inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
                          inner join wf.ttipo_estado te on te.id_tipo_proceso = tp.id_tipo_proceso and tp.id_tipo_estado is null and te.codigo = ''cotizacion'' and te.estado_reg = ''activo''
                          inner join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = te.id_tipo_estado
                          inner join orga.vfuncionario_ultimo_cargo carg on carg.id_funcionario = fun.id_funcionario
                          where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_ENCAR_LIST_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		18-04-2022 11:31:00
	***********************************/

	elsif(p_transaccion='MAT_ENCAR_LIST_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(carg.id_funcionario)
                       	 from wf.tproceso_macro pm
                          inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
                          inner join wf.ttipo_estado te on te.id_tipo_proceso = tp.id_tipo_proceso and tp.id_tipo_estado is null and te.codigo = ''cotizacion'' and te.estado_reg = ''activo''
                          inner join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = te.id_tipo_estado
                          inner join orga.vfuncionario_ultimo_cargo carg on carg.id_funcionario = fun.id_funcionario
                          where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_LOG_REASIG_IME'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		18-04-2022 11:31:00
	***********************************/

	elsif(p_transaccion='MAT_LOG_REASIG_IME')then

		begin
			v_consulta:='
                          SELECT	TO_JSON(ROW_TO_JSON(jsonD) :: TEXT) #>> ''{}'' as jsonData
                                                    FROM (
						  select
                          ( SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(detalle_historico))) as boletos_no_revisados
                            FROM(
                                select to_char(rea.fecha_reg,''DD/MM/YYYY'') as fecha_reasignacion,
                                       usu.desc_persona as reasignado_por,
                                       fun.desc_funcionario1 as asignado_inicial,
                                       fun2.desc_funcionario1 as asignado_actual,
                                       rea.observacion as motivo_reasignacion
                                from mat.tlog_reasignacion_funcionario rea
                                inner join segu.vusuario usu on usu.id_usuario = rea.id_usuario_reg
                                inner join orga.vfuncionario fun on fun.id_funcionario = rea.id_funcionario_antiguo
                                inner join orga.vfuncionario fun2 on fun2.id_funcionario = rea.id_funcionario_nuevo
                                where rea.id_solicitud = '||v_parametros.id_solicitud||' )detalle_historico ) as detalle_historico




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

ALTER FUNCTION mat.ft_listado_reasignacion (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
