CREATE OR REPLACE FUNCTION mat.f_agrupar_nro_parte (
  p_id_solicitud integer
)
RETURNS varchar AS
$body$
DECLARE
  	v_parte    		varchar;
	v_nombre_funcion   	text;
	v_resp				varchar;
BEGIN
  	v_nombre_funcion = 'gecom.f_numero_celular_tipo';
    
    select pxp.list (d.nro_parte)
    into
    v_parte
    from mat.tdetalle_sol d
    where d.id_solicitud =  p_id_solicitud;              
    return v_parte;

					
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