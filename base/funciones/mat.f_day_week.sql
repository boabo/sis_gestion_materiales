CREATE OR REPLACE FUNCTION mat.f_day_week (
)
RETURNS void AS
$body$
DECLARE
    v_numero    		varchar;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_i					integer;
BEGIN
  v_nombre_funcion = 'mat.f_day_week';

  for v_i in 1..20 loop
  insert into mat.tday_week(
  							codigo_tipo,
                            id_usuario_reg
  							)values(
                            v_i||' '||'semanas',
                            1
  							);
  end loop;

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