CREATE OR REPLACE FUNCTION mat.f_obtener_proveedor (
  p_id_proveedor integer
)
RETURNS varchar AS
$body$
DECLARE
  	v_proveedor    		varchar;
	v_id_funcionario	integer;
	v_nombre_funcion   	text;
	v_resp				varchar;
BEGIN
  	v_nombre_funcion = ' mat.f_obtener_proveedor';
    
         select e.desc_proveedor
         into
         v_proveedor
         from param.vproveedor e
       	 where e.id_proveedor =p_id_proveedor;
         
         return v_proveedor;

					
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