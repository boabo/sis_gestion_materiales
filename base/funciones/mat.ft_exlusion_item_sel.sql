CREATE OR REPLACE FUNCTION mat.ft_exlusion_item_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_exlusion_item_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tunidad_medida'
 AUTOR: 		 (Ismael Valdivia)
 FECHA:	        18-03-2022 15:00:00
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

	v_nombre_funcion = 'mat.ft_exlusion_item_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_LIST_ITEM_IME'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		18-03-2022 15:00:00
	***********************************/

	if(p_transaccion='MAT_LIST_ITEM_IME')then

    	begin

    		--Sentencia de la consulta
			v_consulta:='
                          SELECT	TO_JSON(ROW_TO_JSON(jsonD) :: TEXT) #>> ''{}'' as jsonData
                                                    FROM (
						  select
                          ( SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(detalle_historico))) as boletos_no_revisados
                            FROM(
                                select TO_CHAR(
                                            his.fecha_reg,
                                            ''DD/MM/YYYY HH12:MI:SS''
                                        )  as fecha,
                                       his.estado_excluido as estado_registrado,
                                       his.observacion,
                                       usu.desc_persona
                                from mat.tdetalle_excluido_historico his
                                inner join segu.vusuario usu on usu.id_usuario = his.id_usuario_reg
                                where his.id_detalle_solicitud = '||v_parametros.id_detalle||'
                                order by his.fecha_reg asc )detalle_historico ) as detalle_historico




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

ALTER FUNCTION mat.ft_exlusion_item_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
