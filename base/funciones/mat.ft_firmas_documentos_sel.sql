CREATE OR REPLACE FUNCTION mat.ft_firmas_documentos_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_firmas_documentos_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tfirmas_documentos'
 AUTOR: 		 (ismael.valdivia)
 FECHA:	        16-08-2022 13:50:13
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				16-08-2022 13:50:13								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tfirmas_documentos'
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;

BEGIN

	v_nombre_funcion = 'mat.ft_firmas_documentos_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_FIRM_DOC_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		ismael.valdivia
 	#FECHA:		16-08-2022 13:50:13
	***********************************/

	if(p_transaccion='MAT_FIRM_DOC_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						firm_doc.id_firma_documento,
						firm_doc.estado_reg,
						firm_doc.fecha_inicio,
						firm_doc.fecha_fin,
						firm_doc.id_funcionario,
						firm_doc.tipo_firma,
						firm_doc.id_usuario_reg,
						firm_doc.fecha_reg,
						firm_doc.id_usuario_ai,
						firm_doc.usuario_ai,
						firm_doc.id_usuario_mod,
						firm_doc.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        firm_doc.motivo_asignacion::varchar,
                        firm_doc.tipo_documento::varchar,
                        fun.desc_funcionario1::varchar
						from mat.tfirmas_documentos firm_doc
						inner join segu.tusuario usu1 on usu1.id_usuario = firm_doc.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = firm_doc.id_usuario_mod
                        inner join orga.vfuncionario fun on fun.id_funcionario = firm_doc.id_funcionario
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_FIRM_DOC_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		ismael.valdivia
 	#FECHA:		16-08-2022 13:50:13
	***********************************/

	elsif(p_transaccion='MAT_FIRM_DOC_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_firma_documento)
					    from mat.tfirmas_documentos firm_doc
					    inner join segu.tusuario usu1 on usu1.id_usuario = firm_doc.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = firm_doc.id_usuario_mod
                        inner join orga.vfuncionario fun on fun.id_funcionario = firm_doc.id_funcionario
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

ALTER FUNCTION mat.ft_firmas_documentos_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
