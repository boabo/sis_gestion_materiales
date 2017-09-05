CREATE OR REPLACE FUNCTION mat.f_lista_funcionario_solicitud (
  p_id_usuario integer,
  p_id_tipo_estado integer,
  p_fecha date = now(),
  p_id_estado_wf integer = NULL::integer,
  p_count boolean = false,
  p_limit integer = 1,
  p_start integer = 0,
  p_filtro varchar = '0=0'::character varying
)
RETURNS SETOF record AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestion de Materiales
 FUNCION: 		mat.f_lista_funcionario_solicitud
 DESCRIPCION:   Funcion que recupera el funcionario del vobo de materiales para insertarlo en el
 				sistema de Adquisiciones como funcionario que realiza la solicitud.
 AUTOR: 		 (FEA)
 FECHA:	        07-07-2017 15:15:26
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_resp		            varchar;
	v_nombre_funcion        text;
	v_id_funcionario		integer;
	v_consulta				varchar;
    g_registros  			record;
BEGIN

    v_nombre_funcion = 'mat.f_lista_funcionario_solicitud';

	WITH RECURSIVE firmas(id_estado_fw, id_estado_anterior,fecha_reg, codigo, id_funcionario) AS (
                              SELECT tew.id_estado_wf, tew.id_estado_anterior , tew.fecha_reg, te.codigo, tew.id_funcionario
                              FROM wf.testado_wf tew
                              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = tew.id_tipo_estado
                              WHERE tew.id_estado_wf = p_id_estado_wf

                              UNION ALL

                              SELECT ter.id_estado_wf, ter.id_estado_anterior, ter.fecha_reg, te.codigo, ter.id_funcionario
                              FROM wf.testado_wf ter
                              INNER JOIN firmas f ON f.id_estado_anterior = ter.id_estado_wf
                              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = ter.id_tipo_estado
                              WHERE f.id_estado_anterior IS NOT NULL
    )
    SELECT id_funcionario
    INTO v_id_funcionario
    FROM firmas
  	WHERE codigo = 'revision';

    IF not p_count then
             v_consulta='SELECT
                            fun.id_funcionario,
                            fun.desc_funcionario1 as desc_funcionario,
                            ''Gerente''::text  as desc_funcionario_cargo,
                            1 as prioridad
                         FROM orga.vfuncionario fun WHERE fun.id_funcionario = '||COALESCE(v_id_funcionario,0)::varchar||'
                         and '||p_filtro||'
                         limit '|| p_limit::varchar||' offset '||p_start::varchar;


                   FOR g_registros in execute (v_consulta)LOOP
                     RETURN NEXT g_registros;
                   END LOOP;

      ELSE
                  v_consulta='select
                                  COUNT(fun.id_funcionario) as total
                                 FROM orga.vfuncionario fun WHERE fun.id_funcionario = '||COALESCE(v_id_funcionario,0)::varchar||'
                                 and '||p_filtro;

                   FOR g_registros in execute (v_consulta)LOOP
                     RETURN NEXT g_registros;
                   END LOOP;


    END IF;

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
COST 100 ROWS 1000;