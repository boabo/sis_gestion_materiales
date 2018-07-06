CREATE OR REPLACE FUNCTION mat.estados (
)
RETURNS void AS
$body$
DECLARE
   v_nombre_funcion   	text;
   v_resp    			varchar;
   v_mensaje 			varchar;
   v_id_gestion			integer;
   v_record				record;
 
BEGIN
	v_nombre_funcion = 'mat.estados';
   
 /*   for v_record in (select  s.id_solicitud,
					extract(year from s.fecha_solicitud)  as gestion
					from mat.tsolicitud s)loop
                    select g.id_gestion
                    into v_id_gestion
                    from param.tgestion g
                    where g.gestion = v_record.gestion;
		
    	update mat.tsolicitud set
        id_gestion = v_id_gestion
        where id_solicitud = v_record.id_solicitud;
    	
    end loop;*/
    
  /* for v_record in (select de.id_solicitud,
                        		list(de.nro_parte) as parte
                        from mat.tdetalle_sol de
                        group by de.id_solicitud)loop
        update mat.tsolicitud set
        nro_partes = v_record.parte
        where id_solicitud = v_record.id_solicitud;
    end loop;*/
    
    for v_record in (select de.id_solicitud,
                        		list(de.nro_parte_alterno) as alterna
                        from mat.tdetalle_sol de
                        group by de.id_solicitud)loop
        update mat.tsolicitud set
        nro_parte_alterno = v_record.alterna
        where id_solicitud = v_record.id_solicitud;
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