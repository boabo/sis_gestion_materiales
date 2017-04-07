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

BEGIN
  v_nombre_funcion = 'mat.f_procesar_estados_solicitud';

  select* into v_solicitud
  FROM mat.tsolicitud
  where id_proceso_wf = p_id_proceso_wf;


   if(p_codigo_estado in ('revision')) then
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
    elsif(p_codigo_estado in ('cotizacion_solicitada')) then
    	begin
        IF v_solicitud.estado_firma in ('vobo_area','vobo_aeronavegabilidad')THEN
        RAISE EXCEPTION'Aun no fue aprobado por %', v_solicitud.estado_firma;
        END IF;
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('cotizacion_sin_respuesta')) then
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
          IF v_solicitud.estado_firma in ('vobo_area','vobo_aeronavegabilidad')THEN
        RAISE EXCEPTION'Aun no fue aprobado por %', v_solicitud.estado_firma;
        END IF;
          if v_solicitud.fecha_cotizacion is null then
            raise exception 'Tiene que registar la fecha de contizacion';
		  end if;
          if v_solicitud.id_proveedor is null then
            raise exception 'Tiene que registar el Proveedor';
		  end if;
           if v_solicitud.nro_po = '' then
            raise exception 'Tiene que registar Nro. P.O.';
		  end if;
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
         if v_solicitud.fecha_arribado_bolivia is null then
            raise exception 'Tiene que registar la fecha de Arribado Bolivia';
		  end if;
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
        if v_solicitud.fecha_desaduanizacion is null then
            raise exception 'Tiene que registar la fecha de Desaduanizacion';
		  end if;
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
          IF v_solicitud.estado_firma in ('vobo_area','vobo_aeronavegabilidad')THEN
        RAISE EXCEPTION'Aun no fue aprobado por %', v_solicitud.estado_firma;
        END IF;
        if v_solicitud.fecha_en_almacen is null then
            raise exception 'Tiene que registar la fecha de Almacen';
		  end if;
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
        IF v_solicitud.estado_firma in ('vobo_area','vobo_aeronavegabilidad')THEN
        RAISE EXCEPTION'Aun no fue aprobado por %', v_solicitud.estado_firma;
        END IF;
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