CREATE OR REPLACE FUNCTION "mat"."ft_conuslta_pac_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_conuslta_pac_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tconuslta_pac'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        03-07-2018 16:19:47
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				03-07-2018 16:19:47								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tconuslta_pac'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'mat.ft_conuslta_pac_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'MAT_CPA_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		miguel.mamani	
 	#FECHA:		03-07-2018 16:19:47
	***********************************/

	if(p_transaccion='MAT_CPA_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						cpa.id_proceso_wf,
						cpa.desc_funcionario1,
						cpa.nro_tramite,
						cpa.codigo_internacional,
						cpa.tipo_solicitud,
						cpa.estado,
						cpa.monto,
						cpa.origen_pedido,
						cpa.observaciones_sol,
						cpa.fecha_requerida,
						cpa.id_solicitud,
						cpa.fecha_solicitud,
						cpa.motivo_solicitud,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from mat.tconuslta_pac cpa
						inner join segu.tusuario usu1 on usu1.id_usuario = cpa.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cpa.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'MAT_CPA_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani	
 	#FECHA:		03-07-2018 16:19:47
	***********************************/

	elsif(p_transaccion='MAT_CPA_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_proceso_wf)
					    from mat.tconuslta_pac cpa
					    inner join segu.tusuario usu1 on usu1.id_usuario = cpa.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cpa.id_usuario_mod
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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "mat"."ft_conuslta_pac_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
