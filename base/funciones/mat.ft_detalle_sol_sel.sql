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
    		--Sentencia de la consulta
			v_consulta:='select
						det.id_detalle,
						det.id_solicitud,
						det.descripcion,
						det.estado_reg,
						det.unidad_medida,
						det.nro_parte,
						det.referencia,
						det.nro_parte_alterno,
						det.id_moneda,
						det.precio,
						det.cantidad_sol,
						det.id_usuario_reg,
						det.usuario_ai,
						det.fecha_reg,
						det.id_usuario_ai,
						det.id_usuario_mod,
						det.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod
						from mat.tdetalle_sol det
						inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

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
			v_consulta:='select count(id_detalle)
					    from mat.tdetalle_sol det
					    inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
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