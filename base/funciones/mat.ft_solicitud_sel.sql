CREATE OR REPLACE FUNCTION mat.ft_solicitud_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestión de Materiales
 FUNCION: 		mat.ft_solicitud_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tsolicitud'
 AUTOR: 		 (admin)
 FECHA:	        23-12-2016 13:12:58
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

	v_nombre_funcion = 'mat.ft_solicitud_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_SOL_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/

	if(p_transaccion='MAT_SOL_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						sol.id_solicitud,
						sol.id_funcionario_sol,
						sol.id_proveedor,
						sol.id_proceso_wf,
						sol.id_estado_wf,
						sol.nro_po,
						sol.tipo_solicitud,
						sol.fecha_entrega_miami,
						sol.origen_pedido,
						sol.fecha_requerida,
						sol.observacion_nota,
						sol.fecha_solicitud,
						sol.estado_reg,
						sol.observaciones_sol,
						sol.fecha_tentativa_llegada,
						sol.fecha_despacho_miami,
						sol.justificacion,
						sol.fecha_arribado_bolivia,
						sol.fecha_desaduanizacion,
						sol.fecha_entrega_almacen,
						sol.cotizacion,
						sol.tipo_falla,
						sol.nro_tramite,
						sol.id_matricula,
						sol.nro_solicitud,
						sol.motivo_solicitud,
						sol.fecha_en_almacen,
						sol.estado,
						sol.id_usuario_reg,
						sol.usuario_ai,
						sol.fecha_reg,
						sol.id_usuario_ai,
						sol.fecha_mod,
						sol.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        f.desc_funcionario1,
                        ot.desc_orden as matricula,
                        sol.tipo_reporte,
                        sol.mel,
                        sol.nro_no_rutina,
                        pro.desc_proveedor,
                     	pxp.list (de.nro_parte) as nro_partes
                        from mat.tsolicitud sol
						inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                        inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                        left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
						left join param.vproveedor pro on pro.id_proveedor =sol.id_proveedor
                        inner join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud
                        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||'GROUP BY sol.id_solicitud,
						sol.id_funcionario_sol,
						sol.id_proveedor,
						sol.id_proceso_wf,
						sol.id_estado_wf,
						sol.nro_po,
						sol.tipo_solicitud,
						sol.fecha_entrega_miami,
						sol.origen_pedido,
						sol.fecha_requerida,
						sol.observacion_nota,
						sol.fecha_solicitud,
						sol.estado_reg,
						sol.observaciones_sol,
						sol.fecha_tentativa_llegada,
						sol.fecha_despacho_miami,
						sol.justificacion,
						sol.fecha_arribado_bolivia,
						sol.fecha_desaduanizacion,
						sol.fecha_entrega_almacen,
						sol.cotizacion,
						sol.tipo_falla,
						sol.nro_tramite,
						sol.id_matricula,
						sol.nro_solicitud,
						sol.motivo_solicitud,
						sol.fecha_en_almacen,
						sol.estado,
						sol.id_usuario_reg,
						sol.usuario_ai,
						sol.fecha_reg,
						sol.id_usuario_ai,
						sol.fecha_mod,
						sol.id_usuario_mod,
						usu1.cuenta,
						usu2.cuenta,
                        f.desc_funcionario1,
                        ot.desc_orden,
                        sol.tipo_reporte,
                        sol.mel,
                        sol.nro_no_rutina,
                        pro.desc_proveedor order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_SOL_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/

	elsif(p_transaccion='MAT_SOL_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_solicitud)
					  	from mat.tsolicitud sol
						inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                        inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                        left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                         where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_MATR_SEL'
 	#DESCRIPCION:	Matricul
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_MATR_SEL')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select ord.id_orden_trabajo,
                          		split_part(ord.desc_orden ,'' '',2) ||'' ''||  split_part(ord.desc_orden :: text,'' '',3):: text as matricula,
       					 		ord.desc_orden
        						from conta.torden_trabajo ord
								inner join conta.tgrupo_ot_det gr on gr.id_orden_trabajo = ord.id_orden_trabajo and gr.id_grupo_ot = 1
							    where ';

            --Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
           return v_consulta;

		end;
     /*********************************
 	#TRANSACCION:  'MAT_REING_SEL'
 	#DESCRIPCION:	Reporte requerimiento de materiales ingeniaeria
 	#AUTOR:		MAM
 	#FECHA:		23-12-2016 13:13:01
	***********************************/
    elsif(p_transaccion='MAT_REING_SEL')then

		begin
			v_consulta:='select
                                sol.id_solicitud,
                                to_char( sol.fecha_solicitud,''DD/MM/YYYY'') as fecha_solicitud,
                                ot.motivo_orden,
                                left(ot.desc_orden,20) as matricula ,
                                sol.nro_tramite,
                                de.nro_parte,
                                de.referencia,
                                de.descripcion,
                                de.cantidad_sol,
                                sol.justificacion,
                                sol.tipo_solicitud,
                                to_char( sol.fecha_requerida,''DD/MM/YYYY'') as fecha_requerida,
                                sol.motivo_solicitud,
                                sol.observaciones_sol,
        						initcap( f.desc_funcionario1)as desc_funcionario1,
                                sol.tipo_falla,
        						sol.tipo_reporte,
        						sol.mel,
                                de.unidad_medida,
                                ti.codigo as estado
          						from mat.tsolicitud sol
                                inner join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud
                                inner join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                                inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                                inner join wf.testado_wf wof on wof.id_estado_wf = sol.id_estado_wf
                                inner join wf.ttipo_estado ti on ti.id_tipo_estado = wof.id_tipo_estado
                    			where sol.id_proceso_wf='||v_parametros.id_proceso_wf;
			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_OPT_SEL'
 	#DESCRIPCION:	Optener el funcionario
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/




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