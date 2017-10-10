CREATE OR REPLACE FUNCTION mat.ft_unidad_medida_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_unidad_medida_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tunidad_medida'
 AUTOR: 		 (admin)
 FECHA:	        14-03-2017 16:18:47
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

	v_nombre_funcion = 'mat.ft_unidad_medida_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_UDM_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		14-03-2017 16:18:47
	***********************************/

	if(p_transaccion='MAT_UDM_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						um.id_unidad_medida,
						um.codigo,
						um.descripcion,
						um.tipo_unidad_medida,
						um.estado_reg,
						um.id_usuario_ai,
						um.usuario_ai,
						um.fecha_reg,
						um.id_usuario_reg,
						um.fecha_mod,
						um.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod
						from mat.tunidad_medida um
						inner join segu.tusuario usu1 on usu1.id_usuario = um.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = um.id_usuario_mod
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_UDM_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		14-03-2017 16:18:47
	***********************************/

	elsif(p_transaccion='MAT_UDM_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_unidad_medida)
					    from mat.tunidad_medida um
					    inner join segu.tusuario usu1 on usu1.id_usuario = um.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = um.id_usuario_mod
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