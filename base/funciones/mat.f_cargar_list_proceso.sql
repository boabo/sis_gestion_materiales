CREATE OR REPLACE FUNCTION mat.f_cargar_list_proceso (
  p_id_solicitud_p integer,
  p_id_solicitud_h integer
)
RETURNS boolean AS
$body$
DECLARE
   v_nombre_funcion   	text;
   v_resp    			varchar;
   v_mensaje 			varchar;
   v_id_procesos		integer[];
   v_id_proceso			integer;
   v_record				record;


BEGIN
	v_nombre_funcion = 'mat.f_cargar_list_proceso';

    select ts.list_proceso
    into v_record
    from mat.tsolicitud  ts
    where ts.id_solicitud = p_id_solicitud_p;
    --array de los ids de los procesos original, clonado
    if(v_record.list_proceso is null)then
    	v_id_procesos = array[p_id_solicitud_p, p_id_solicitud_h];
    else
    	v_id_procesos = array_append(v_record.list_proceso, p_id_solicitud_h);
    end if;
    --raise exception 'v_id_procesos %', v_id_procesos;
    foreach v_id_proceso in array v_id_procesos loop
      select ts.list_proceso
      into v_record
      from mat.tsolicitud ts
      where ts.id_solicitud = v_id_proceso;
      if(v_record.list_proceso is null or v_record.list_proceso is not null)then
      	update mat.tsolicitud set
        	list_proceso = v_id_procesos
      	where id_solicitud = v_id_proceso;
      end if;
    end loop;
    return true;

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