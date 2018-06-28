CREATE OR REPLACE FUNCTION mat.f_id_detalle_cotizacion (
  p_id_cotizacion integer
)
RETURNS integer AS
$body$
DECLARE
  	v_numero    		varchar;
	
	v_nombre_funcion   	text;
	v_resp				varchar;
BEGIN
  	v_nombre_funcion = 'mat.f_id_detalle_cotizacion';
  
select count( ce.id_cotizacion_det)
into 
v_numero
from mat.tcotizacion_detalle ce
where ce.id_cotizacion = p_id_cotizacion and ce.revisado = 'si';
            return v_numero;


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