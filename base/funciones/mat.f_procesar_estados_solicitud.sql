CREATE OR REPLACE FUNCTION mat.f_procesar_estados_solicitud (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_estado_wf integer,
  p_id_proceso_wf integer,
  p_codigo_estado varchar
)
RETURNS boolean AS
$body$
DECLARE
  v_nombre_funcion   	 	text;
    v_resp    			 	varchar;
    v_mensaje 			 	varchar;
    v_solicitud		 	    record;
    v_detalle				record;
    v_valor 				varchar = '';
    v_cont 					integer = 0;
    v_record 				record;
    v_cont_del			    integer;

    v_registros_mat			record;
    v_revisado				varchar;
BEGIN
  v_nombre_funcion = 'mat.f_procesar_estados_solicitud';

  select* into v_solicitud
  FROM mat.tsolicitud
  where id_proceso_wf = p_id_proceso_wf;

  SELECT* into v_detalle
  FROM mat.tdetalle_sol
  where id_solicitud = v_solicitud.id_solicitud;

  select count(*) into v_cont_del
  from mat.tdetalle_sol
  where id_solicitud = v_solicitud.id_solicitud;

   if(p_codigo_estado = 'vobo_area') then
    	begin
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    			where id_proceso_wf = p_id_proceso_wf;
            end;
   elsif(p_codigo_estado in ('vobo_aeronavegabilidad')) then
    	begin

    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
   elsif(p_codigo_estado in ('revision')) then
    	begin

    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;

   elsif(p_codigo_estado in ('cotizacion')) then
    	begin
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;

   elsif(p_codigo_estado in ('compra')) then
    	begin
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;

     elsif(p_codigo_estado in ('despachado')) then
    	begin
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
     elsif(p_codigo_estado in ('arribo')) then
    	begin
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
     elsif(p_codigo_estado in ('desaduanizado')) then
    	begin
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
     elsif(p_codigo_estado in ('almacen')) then
    	begin
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
        elsif(p_codigo_estado in ('finalizado')) then
    	begin
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
     end if;
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