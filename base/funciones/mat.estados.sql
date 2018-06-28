CREATE OR REPLACE FUNCTION mat.estados (
)
RETURNS void AS
$body$
DECLARE
   v_nombre_funcion   	text;
    v_resp    			varchar;
    v_mensaje 			varchar;
    v_estado			record;

    v_id_estado_actual	integer;
    v_id_tipo_estado	integer;
    
    v_id_usuario		integer;
    v_acceso_directo	varchar;
    v_clase 			varchar;
    v_parametros_ad 	varchar;
    v_tipo_noti			varchar;
    v_titulo			varchar;
	v_id_funcionario	integer;

    v_codigo_estado  	integer;
    v_id_proceso_ab_wf	integer;
    v_id_estado_ab_wf	integer;
    v_codigo_estado_ab	varchar;
    v_funcionario_wf	integer;
    v_registros			integer;
    
   
    
   
BEGIN
	v_nombre_funcion = 'mat.estados';
    v_acceso_directo = '';
    v_clase = '';
    v_parametros_ad = '';
    v_tipo_noti = 'notificacion';
    v_titulo  = 'Archivo Concluido';

FOR v_estado in(select *
				from mat.tsolicitud ) LOOP --control estado
                
              /*	SELECT f.id_funcionario
        		INTO
        		v_funcionario_wf
				FROM orga.vfuncionario f 
				where f.desc_funcionario1 = 'ELIZABETH TRIVEÑO PEDRO';

               
                
               	IF v_estado.origen_pedido = 'Gerencia de Operaciones' THEN
                SELECT te.id_tipo_estado
                INTO v_codigo_estado
                FROM wf.ttipo_estado te
                WHERE te.codigo = 'finalizado' and te.id_tipo_proceso = 117;
  				ELSIF v_estado.origen_pedido = 'Gerencia de Mantenimiento' THEN
                SELECT te.id_tipo_estado
                INTO v_codigo_estado
                FROM wf.ttipo_estado te
                WHERE te.codigo = 'finalizado' and te.id_tipo_proceso = 119;
                ELSIF v_estado.origen_pedido = 'Almacenes Consumibles o Rotables' THEN
                SELECT te.id_tipo_estado
                INTO v_codigo_estado
                FROM wf.ttipo_estado te
                WHERE te.codigo = 'finalizado' and te.id_tipo_proceso = 120;
                
				END IF;
  				

                SELECT tu.id_usuario INTO v_id_usuario
                FROM wf.testado_wf te
                inner join orga.tfuncionario tf on tf.id_funcionario = te.id_funcionario
                inner join segu.tpersona tp on tp.id_persona = tf.id_persona
                inner join segu.tusuario tu on tu.id_persona = tp.id_persona
                WHERE te.id_estado_wf=v_estado.id_estado_wf;
              	v_id_estado_actual =  wf.f_registra_estado_wf(
                						v_codigo_estado,
                                        v_funcionario_wf,
                                        v_estado.id_estado_wf,
                                        v_estado.id_proceso_wf,
                                        v_id_usuario,
                                        v_estado.id_usuario_ai,
                                        v_estado.usuario_ai,
                                        NULL,
                                        COALESCE(v_estado.nro_tramite,'--')||' Obs:'||'ARCHIVADO',
                                        v_acceso_directo,
                                        v_clase,
                                        v_parametros_ad,
                                        v_tipo_noti,
                                        v_titulo);
                                      
                 update mat.tsolicitud  ma set
                 id_estado_wf =  v_id_estado_actual,
                 estado = 'finalizado',
                 id_usuario_mod=v_id_usuario,
                 fecha_mod=now(),
                 id_usuario_ai = v_estado.id_usuario_ai,
                 usuario_ai = v_estado.usuario_ai
              	 where ma.id_proceso_wf  = v_estado.id_proceso_wf;
                 
                 
  IF v_estado.origen_pedido in ('Gerencia de Operaciones','Gerencia de Mantenimiento') THEN            
        
        IF v_estado.origen_pedido ='Gerencia de Operaciones' THEN
        SELECT f.id_funcionario
        INTO
        	v_funcionario_wf
		FROM orga.vfuncionario f 
		where f.desc_funcionario1 = 'JORGE OMAR GUZMAN FERNANDEZ';
       
       elsif v_estado.origen_pedido ='Gerencia de Mantenimiento'then
        
        SELECT f.id_funcionario
        INTO
        	v_funcionario_wf
		FROM orga.vfuncionario f 
		where f.desc_funcionario1 = 'ROGER WILMER BALDERRAMA ANGULO';
        END IF;


 SELECT
            ps_id_proceso_wf,
            ps_id_estado_wf,
            ps_codigo_estado
            into
            v_id_proceso_ab_wf,
            v_id_estado_ab_wf,
            v_codigo_estado_ab
            FROM wf.f_registra_proceso_disparado_wf(
                               v_id_usuario,
                               null,
                               null,
                               v_estado.id_estado_wf, 
                               v_funcionario_wf, 
                               NULL,
                               'Flujo de Firmas',       
                               'GRM',    
                               'Flujo de Firmas');
        
             --Sentencia de la insercion
           
             
            update mat.tsolicitud set 
			estado_firma = v_codigo_estado_ab,
            id_proceso_wf_firma = v_id_proceso_ab_wf,
            id_estado_wf_firma = v_id_estado_ab_wf
            where id_solicitud=v_estado.id_solicitud;
         END IF;  */
        
     -----      
   /*IF v_estado.estado = 'finalizado' and v_estado.estado_firma = 'vobo_area' and v_estado.origen_pedido in ('Gerencia de Operaciones','Gerencia de Mantenimiento') THEN 	 
                SELECT f.id_funcionario
        		INTO
        		v_funcionario_wf
				FROM orga.vfuncionario f 
				where f.desc_funcionario1 = 'PEDRO WILFREDO TRIVEÑO HERRERA';
                
               	IF v_estado.origen_pedido = 'Gerencia de Operaciones' THEN
                SELECT te.id_tipo_estado
                INTO v_codigo_estado
                FROM wf.ttipo_estado te
                WHERE te.codigo = 'vobo_aeronavegabilidad' and te.id_tipo_proceso = 118;
  				ELSIF v_estado.origen_pedido = 'Gerencia de Mantenimiento' THEN
                SELECT te.id_tipo_estado
                INTO v_codigo_estado
                FROM wf.ttipo_estado te
                WHERE te.codigo = 'vobo_aeronavegabilidad' and te.id_tipo_proceso = 125;
				END IF;
  				

                SELECT tu.id_usuario INTO v_id_usuario
                FROM wf.testado_wf te
                inner join orga.tfuncionario tf on tf.id_funcionario = te.id_funcionario
                inner join segu.tpersona tp on tp.id_persona = tf.id_persona
                inner join segu.tusuario tu on tu.id_persona = tp.id_persona
                WHERE te.id_estado_wf=v_estado.id_estado_wf;
              	v_id_estado_actual =  wf.f_registra_estado_wf(
                						v_codigo_estado,
                                        v_funcionario_wf,
                                        v_estado.id_estado_wf_firma,
                                        v_estado.id_proceso_wf_firma,
                                        v_id_usuario,
                                        v_estado.id_usuario_ai,
                                        v_estado.usuario_ai,
                                        NULL,
                                        COALESCE(v_estado.nro_tramite,'--')||' Obs:'||'ARCHIVADO',
                                        v_acceso_directo,
                                        v_clase,
                                        v_parametros_ad,
                                        v_tipo_noti,
                                        v_titulo);
                                      
                 update mat.tsolicitud  set
                 id_estado_wf_firma =  v_id_estado_actual,
                 estado_firma = 'vobo_aeronavegabilidad'
              	 where id_proceso_wf_firma  = v_estado.id_proceso_wf_firma;
         END IF;  */
            
            ---------------------------
            
        IF v_estado.estado = 'finalizado' and v_estado.estado_firma = 'vobo_aeronavegabilidad' and v_estado.origen_pedido in ('Gerencia de Operaciones','Gerencia de Mantenimiento') THEN     
             	
                SELECT f.id_funcionario
        		INTO
        		v_funcionario_wf
				FROM orga.vfuncionario f 
				where f.desc_funcionario1 = 'PEDRO WILFREDO TRIVEÑO HERRERA';
                
               	IF v_estado.origen_pedido = 'Gerencia de Operaciones' THEN
                SELECT te.id_tipo_estado
                INTO v_codigo_estado
                FROM wf.ttipo_estado te
                WHERE te.codigo = 'aprobado' and te.id_tipo_proceso = 118;
  				ELSIF v_estado.origen_pedido = 'Gerencia de Mantenimiento' THEN
                SELECT te.id_tipo_estado
                INTO v_codigo_estado
                FROM wf.ttipo_estado te
                WHERE te.codigo = 'aprobado' and te.id_tipo_proceso = 125;
				END IF;
  				

                SELECT tu.id_usuario INTO v_id_usuario
                FROM wf.testado_wf te
                inner join orga.tfuncionario tf on tf.id_funcionario = te.id_funcionario
                inner join segu.tpersona tp on tp.id_persona = tf.id_persona
                inner join segu.tusuario tu on tu.id_persona = tp.id_persona
                WHERE te.id_estado_wf=v_estado.id_estado_wf;
              	v_id_estado_actual =  wf.f_registra_estado_wf(
                						v_codigo_estado,
                                        v_funcionario_wf,
                                        v_estado.id_estado_wf_firma,
                                        v_estado.id_proceso_wf_firma,
                                        v_id_usuario,
                                        v_estado.id_usuario_ai,
                                        v_estado.usuario_ai,
                                        NULL,
                                        COALESCE(v_estado.nro_tramite,'--')||' Obs:'||'ARCHIVADO',
                                        v_acceso_directo,
                                        v_clase,
                                        v_parametros_ad,
                                        v_tipo_noti,
                                        v_titulo);
                                      
                 update mat.tsolicitud  set
                 id_estado_wf_firma =  v_id_estado_actual,
                 estado_firma = 'aprobado'
              	 where id_proceso_wf_firma  = v_estado.id_proceso_wf_firma;
            
        END IF;

                

END LOOP;
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