CREATE OR REPLACE FUNCTION mat.f_primer_letra_mayuscula (
  p_parametro varchar
)
RETURNS varchar AS
$body$
DECLARE

	v_nombre_funcion   	text;
	v_resp				varchar;
    v_string			varchar;
BEGIN
  	v_nombre_funcion = 'mat.f_primer_letra_mayuscula';
     select LEFT(UPPER(p_parametro),1) || RIGHT(LOWER(p_parametro),CHAR_LENGTH(p_parametro)-1)
     		into
            v_string;

            return v_string;


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