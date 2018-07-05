CREATE OR REPLACE FUNCTION mat.f_proveedor_array_columna (
)
RETURNS void AS
$body$
DECLARE
   	v_nombre_funcion   	text;
    v_resp    			varchar;
    v_mensaje 			varchar;
    v_registro			record;
    v_i					integer;
    v_tam				integer;
    v_array				INTEGER[];
    
    
    
BEGIN
  v_nombre_funcion = 'mat.f_proveedor_array_columna';
  for v_registro in (select 	g.id_solicitud,
  								g.id_usuario_reg,
								g.cotizacion_solicitadas
								from mat.tgestion_proveedores g
                                )loop
  	v_array = v_registro.cotizacion_solicitadas;
	v_tam = array_length(v_array,1);
	v_i = 1;   
           	while v_i <= v_tam loop
                    INSERT INTO mat.tgestion_proveedores_new(
                      id_usuario_reg,
                      id_usuario_mod,
                      fecha_reg,
                      fecha_mod,
                      estado_reg,
                      id_usuario_ai,
                      usuario_ai,
  					  id_solicitud,
  					  id_proveedor
                      
                    )VALUES(
                      v_registro.id_usuario_reg,
                      null,
                      now(),
                      null,
                      'activo',
                      null,
                      null,
                      v_registro.id_solicitud,
                   	  v_array[v_i]::integer);
                v_i = v_i + 1;
               
            end loop;




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