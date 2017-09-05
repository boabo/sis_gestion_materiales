CREATE OR REPLACE FUNCTION mat.f_listar_funcionario (
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
DECLARE
  	g_registros  		record;
    v_consulta 			varchar;
    v_nombre_funcion 	varchar;
    v_resp 				varchar;
    v_id_funcionario   integer;
BEGIN
v_nombre_funcion ='mat.f_listar_funcionario';


/*select  ewf.id_funcionario
		into
        v_id_funcionario
from wf.testado_wf e
inner join wf.testado_wf es on es.id_estado_wf = e.id_estado_anterior
inner join wf.testado_wf eo on eo.id_estado_wf = es.id_estado_anterior
inner join wf.testado_wf ewf on ewf.id_estado_wf = eo.id_estado_anterior
inner join orga.vfuncionario f on f.id_funcionario = ewf.id_funcionario
where e.id_estado_wf = p_id_estado_wf;*/

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
  	WHERE codigo = 'cotizacion';

 IF not p_count then

             v_consulta:='SELECT
                            fun.id_funcionario,
                            fun.desc_funcionario1 as desc_funcionario,
                            ''''::text  as desc_funcionario_cargo,
                            1 as prioridad
                         FROM orga.vfuncionario fun WHERE fun.id_funcionario ='||v_id_funcionario||'
                          and '||p_filtro||'
                         limit '|| p_limit::varchar||' offset '||p_start::varchar;
                 raise notice 'id_funcionario %', v_consulta;
             FOR g_registros in execute (v_consulta)LOOP
                     RETURN NEXT g_registros;
             END LOOP;

      ELSE
                  v_consulta='select
                                  COUNT(fun.id_funcionario) as total
                                 FROM orga.vfuncionario fun WHERE fun.id_funcionario ='||v_id_funcionario||'
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