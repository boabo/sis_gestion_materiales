CREATE OR REPLACE FUNCTION mat.ft_conuslta_pac_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
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
			v_consulta:='select   pa.id_solicitud_pac,
                                  so.id_proceso_wf,
                                  so.id_solicitud,
                                  so.nro_tramite,
                                  fu.desc_funcionario1,
                                  so.origen_pedido,
                                  pa.monto,
                                  mo.codigo_internacional,
                                  pa.estado,
                                  so.tipo_solicitud,
                                  so.fecha_solicitud,
                                  so.fecha_requerida,
                                  so.motivo_solicitud,
                                  so.observaciones_sol
                                  from mat.tsolicitud_pac pa
                                  inner join param.tmoneda mo on mo.id_moneda = pa.id_moneda
                                  inner join mat.tsolicitud so on so.id_proceso_wf = pa.id_proceso_wf
                                  inner join orga.vfuncionario fu on fu.id_funcionario = so.id_funcionario_sol
                                  inner join wf.tdocumento_wf doc on doc.id_proceso_wf = pa.id_proceso_wf
                                  inner join wf.ttipo_documento dot on dot.id_tipo_documento = doc.id_tipo_documento
                                  where dot.codigo = ''PAC'' and';
                        			
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
			v_consulta:='select count (pa.id_solicitud_pac)
                          from mat.tsolicitud_pac pa
                          inner join param.tmoneda mo on mo.id_moneda = pa.id_moneda
                          inner join mat.tsolicitud so on so.id_proceso_wf = pa.id_proceso_wf
                          inner join orga.vfuncionario fu on fu.id_funcionario = so.id_funcionario_sol
                          inner join wf.tdocumento_wf doc on doc.id_proceso_wf = pa.id_proceso_wf
                          inner join wf.ttipo_documento dot on dot.id_tipo_documento = doc.id_tipo_documento
                          where dot.codigo = ''PAC'' and';
			
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