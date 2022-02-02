CREATE OR REPLACE FUNCTION mat.ft_cotizacion_detalle_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_cotizacion_detalle_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tcotizacion_detalle'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        04-07-2017 14:51:54
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
    v_id_detalle		integer;
    v_precio			numeric;


BEGIN

	v_nombre_funcion = 'mat.ft_cotizacion_detalle_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_CDE_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:51:54
	***********************************/

	if(p_transaccion='MAT_CDE_SEL')then

    	begin


    		--Sentencia de la consulta
			v_consulta:='select
            			cde.id_day_week,
						cde.id_cotizacion_det,
						cde.id_cotizacion,
						cde.id_detalle,
                        cde.id_solicitud,
                        cde.nro_parte_cot,
                        cde.nro_parte_alterno_cot,
                        cde.referencia_cot,
                        cde.descripcion_cot,
                        cde.explicacion_detallada_part_cot,
                        cde.tipo_cot,
                        cde.cantidad_det,
                        COALESCE(cde.precio_unitario,0)::numeric as precio_unitario,
                        COALESCE(cde.precio_unitario_mb,0)::numeric as precio_unitario_mb,
						cde.estado_reg,
						cde.id_usuario_ai,
						cde.id_usuario_reg,
						cde.usuario_ai,
						cde.fecha_reg,
						cde.fecha_mod,
						cde.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        cde.cd,
                        u.codigo,
                        cde.revisado,
                        da.codigo_tipo as desc_codigo_tipo,
                        cde.referencial,
                        cde.id_unidad_medida_cot,
                        cde.id_detalle_hazmat
						from mat.tcotizacion_detalle cde
						inner join segu.tusuario usu1 on usu1.id_usuario = cde.id_usuario_reg
            			left join mat.tunidad_medida u on u.id_unidad_medida = cde.id_unidad_medida_cot
                        left join mat.tday_week da on da.id_day_week = cde.id_day_week
                        left join segu.tusuario usu2 on usu2.id_usuario = cde.id_usuario_mod
            			where cde.id_cotizacion ='||v_parametros.id_cotizacion||'and';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_CDE_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:51:54
	***********************************/

	elsif(p_transaccion='MAT_CDE_CONT')then

		begin

			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cotizacion_det),
            					COALESCE(sum(cde.precio_unitario),0)::numeric as total_precio_unitario,
								COALESCE(sum(cde.precio_unitario_mb),0)::numeric as total_precio
            			from mat.tcotizacion_detalle cde
						inner join segu.tusuario usu1 on usu1.id_usuario = cde.id_usuario_reg
            			left join mat.tunidad_medida u on u.id_unidad_medida = cde.id_unidad_medida_cot
                        left join mat.tday_week da on da.id_day_week = cde.id_day_week
                        left join segu.tusuario usu2 on usu2.id_usuario = cde.id_usuario_mod
            			where cde.id_cotizacion ='||v_parametros.id_cotizacion||'and';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_CDE_DAY'
 	#DESCRIPCION:	combo day week
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/
    elsif(p_transaccion='MAT_CDE_DAY')then

		begin

        v_consulta:='select  da.id_day_week as id_day,
                              da.codigo_tipo
							  from mat.tday_week da
        					  where ';
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

ALTER FUNCTION mat.ft_cotizacion_detalle_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
