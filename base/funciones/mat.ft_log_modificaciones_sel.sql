CREATE OR REPLACE FUNCTION mat.ft_log_modificaciones_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_log_modificaciones_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tlog_modificaciones'
 AUTOR: 		 (ismael.valdivia)
 FECHA:	        22-08-2022 12:38:25
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-08-2022 12:38:25								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tlog_modificaciones'
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;

BEGIN

	v_nombre_funcion = 'mat.ft_log_modificaciones_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_LOG_TRAM_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		ismael.valdivia
 	#FECHA:		22-08-2022 12:38:25
	***********************************/

	if(p_transaccion='MAT_LOG_TRAM_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						log_tram.id_log,
						log_tram.estado_reg,
						log_tram.id_funcionario_solicitante,
						log_tram.motivo_modificacion,
						log_tram.nro_po_anterior,
						log_tram.nro_po_nuevo,
						log_tram.fecha_cotizacion_antigua,
						log_tram.fecha_cotizacion_nueva,
						log_tram.nro_cotizacion_anterior,
						log_tram.nro_cotizacion_nueva,
						log_tram.id_cotizacion,
						log_tram.id_solicitud,
						log_tram.fecha_modificacion,
						log_tram.id_usuario_reg,
						log_tram.fecha_reg,
						log_tram.id_usuario_ai,
						log_tram.usuario_ai,
						log_tram.id_usuario_mod,
						log_tram.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,

                        fun.desc_funcionario1::varchar as desc_funcionario_solicitante,
                        sol.nro_tramite

						from mat.tlog_modificaciones log_tram
						inner join segu.tusuario usu1 on usu1.id_usuario = log_tram.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = log_tram.id_usuario_mod
                        inner join orga.vfuncionario fun on fun.id_funcionario = log_tram.id_funcionario_solicitante
				        inner join mat.tsolicitud sol on sol.id_solicitud = log_tram.id_solicitud
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_LOG_TRAM_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		ismael.valdivia
 	#FECHA:		22-08-2022 12:38:25
	***********************************/

	elsif(p_transaccion='MAT_LOG_TRAM_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_log)
					    from mat.tlog_modificaciones log_tram
						inner join segu.tusuario usu1 on usu1.id_usuario = log_tram.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = log_tram.id_usuario_mod
                        inner join orga.vfuncionario fun on fun.id_funcionario = log_tram.id_funcionario_solicitante
				        inner join mat.tsolicitud sol on sol.id_solicitud = log_tram.id_solicitud
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

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

ALTER FUNCTION mat.ft_log_modificaciones_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
