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
    v_adjudicacion			varchar;
    v_count					integer;
    v_record				record;

BEGIN
  v_nombre_funcion = 'mat.f_procesar_estados_solicitud';

  select* into v_solicitud
  FROM mat.tsolicitud
  where id_proceso_wf = p_id_proceso_wf;


select count(c.id_cotizacion),
		c.adjudicado
into
v_count,
v_adjudicacion
from mat.tcotizacion  c
where c.id_solicitud  = v_solicitud.id_solicitud
group by c.adjudicado;

   if(p_codigo_estado in ('revision')) then
    	begin
         select de.id_solicitud,
        list(de.nro_parte) as parte
        into
        v_record
        from mat.tdetalle_sol de
        group by de.id_solicitud;
        
        update mat.tsolicitud set
        nro_partes = v_record.parte
        where id_solicitud = v_record.id_solicitud;
        
        select de.id_solicitud,
        list(de.nro_parte_alterno) as alterna
        into
        v_record
        from mat.tdetalle_sol de
        group by de.id_solicitud;
        
        update mat.tsolicitud set
        nro_parte_alterno = v_record.alterna
        where id_solicitud = v_record.id_solicitud;

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
    		update mat.tsolicitud s set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
        ---
        elsif(p_codigo_estado in ('comite_unidad_abastecimientos')) then
    	begin
   			if ((select COALESCE(p.monto,0)
                from mat.tsolicitud s
                left join mat.tsolicitud_pac p on p.id_proceso_wf = s.id_proceso_wf
                where s.id_proceso_wf = p_id_proceso_wf) = 0)then
                raise exception 'Registre un importe en PAC para enviar el correo.';
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
        elsif(p_codigo_estado in ('comite_aeronavegabilidad')) then
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
        elsif(p_codigo_estado in ('departamento_ceac')) then
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
        elsif(p_codigo_estado in ('comite_dpto_abastecimientos')) then
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
        ---
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

            --SELECT mat.f_disparar_adquisiciones(p_id_usuario, p_id_estado_wf, p_id_proceso_wf, p_codigo_estado);
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

        if v_solicitud.fecha_en_almacen is null and v_solicitud.estado <> 'cotizacion' then
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