CREATE OR REPLACE FUNCTION mat.f_disparo_firma (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_solicitud integer,
  p_id_estado_actual integer,
  p_id_funcionario_wf_pro integer,
  "p_obs_pro " varchar,
  p_id_depto_wf_pro integer
)
RETURNS boolean AS
$body$
/**************************************************************************
 FUNCION: 		mat.f_disparo_firma
 DESCRIPCION: 	Disparo del flujo Gestion de Materiales a Firmas.
 AUTOR: 		MMV
 FECHA:			14/07/2017
 COMENTARIOS:
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:

***************************************************************************/

DECLARE
   	v_nombre_funcion 			varchar;
  	v_resp						varchar;
    v_id_proceso_wf				integer;
    v_id_estado_wf				integer;
    v_codigo_estado				varchar;
    v_registro_solicitud		record;
    v_id_tipo_estado			integer;
    v_pedir_obs		    		varchar;
    v_id_estado_wf_firma		integer;
    v_codigo_estado_siguiente	varchar;
    v_acceso_directo  			varchar;
    v_clase   					varchar;
    v_parametros_ad   			varchar;
    v_tipo_noti  				varchar;
    v_titulo   					varchar;
    v_id_estado_actual			integer;


BEGIN
  v_nombre_funcion = 'mat.f_disparo_firma';

  SELECT 	id_proceso_wf,
  			nro_tramite,
            estado_firma,
            id_estado_wf_firma,
            id_proceso_wf_firma
		  	into
            v_registro_solicitud
            from mat.tsolicitud
            where id_solicitud = p_id_solicitud;



            IF  v_registro_solicitud.estado_firma = 'rechazado' THEN

            select
            ew.id_tipo_estado,
            te.pedir_obs,
            ew.id_estado_wf
           	into
            v_id_tipo_estado,
            v_pedir_obs,
            v_id_estado_wf
          	from wf.testado_wf ew
          	inner join wf.ttipo_estado te on te.id_tipo_estado = ew.id_tipo_estado
          	where ew.id_estado_wf =  v_registro_solicitud.id_estado_wf_firma;
           -- raise exception 'llego';
            select te.id_tipo_estado
        	into
        	v_id_estado_wf_firma
            from wf.tproceso_wf pw
            inner join wf.ttipo_proceso tp on pw.id_tipo_proceso = tp.id_tipo_proceso
            inner join wf.ttipo_estado te on te.id_tipo_proceso = tp.id_tipo_proceso and (te.codigo = 'vobo_area' or te.codigo = 'vobo_aeronavegabilidad' or te.codigo = 'comite_aeronavegabilidad')
            where pw.id_proceso_wf = v_registro_solicitud.id_proceso_wf_firma;


           -- obtener datos tipo estado siguiente //codigo=borrador
           select te.codigo into
             v_codigo_estado_siguiente
           from wf.ttipo_estado te
           where te.id_tipo_estado = v_id_estado_wf_firma;

             --configurar acceso directo para la alarma
             v_acceso_directo = '';
             v_clase = '';
             v_parametros_ad = '';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Visto Boa';


             IF   v_codigo_estado_siguiente not in('vobo_area','revision','cotizacion')   THEN

                  v_acceso_directo = '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php';
                  v_clase = 'Solicitud';
                  v_parametros_ad = '{filtro_directo:{campo:"mat.id_proceso_wf",valor:"'||
                  v_registro_solicitud.id_proceso_wf_firma::varchar||'"}}';
                  v_tipo_noti = 'notificacion';
                  v_titulo  = 'Notificacion';
             END IF;

             	v_id_estado_actual =  wf.f_registra_estado_wf(v_id_estado_wf_firma,
                                                             p_id_funcionario_wf_pro,
                                                             v_registro_solicitud.id_estado_wf_firma,
                                                             v_registro_solicitud.id_proceso_wf_firma,
                                                             p_id_usuario,
                                                             p_id_usuario_ai,
                                                             p_usuario_ai,
                                                             p_id_depto_wf_pro,
                                                             'Firma ['||v_registro_solicitud.nro_tramite||']',
                                                             v_acceso_directo ,
                                                             v_clase,
                                                             v_parametros_ad,
                                                             v_tipo_noti,
                                                             v_titulo);


         		IF mat.f_procesar_estados_firmas(p_id_usuario,
           											p_id_usuario_ai,
                                            	    p_usuario_ai,
                                            		v_id_estado_actual,
                                            		v_registro_solicitud.id_proceso_wf_firma,
                                            		v_codigo_estado_siguiente) THEN

         			RAISE NOTICE 'PASANDO DE ESTADO';

          		END IF;
             ELSE
              SELECT	 ps_id_proceso_wf,
                             ps_id_estado_wf,
                             ps_codigo_estado
                             into
                             v_id_proceso_wf,
                             v_id_estado_wf,
                             v_codigo_estado
                    FROM wf.f_registra_proceso_disparado_wf(
                             p_id_usuario,
                             p_id_usuario_ai,
                             p_usuario_ai,
                             p_id_estado_actual::integer,
                             p_id_funcionario_wf_pro,
                             p_id_depto_wf_pro,
                             'Firma ['||v_registro_solicitud.nro_tramite||']',
                             'FRD',
                             v_registro_solicitud.nro_tramite);

  			update mat.tsolicitud set
			estado_firma = v_codigo_estado,
            id_proceso_wf_firma = v_id_proceso_wf,
            id_estado_wf_firma = v_id_estado_wf
            where id_proceso_wf = v_registro_solicitud.id_proceso_wf;



            END IF;


  return TRUE;
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

ALTER FUNCTION mat.f_disparo_firma (p_id_usuario integer, p_id_usuario_ai integer, p_usuario_ai varchar, p_id_solicitud integer, p_id_estado_actual integer, p_id_funcionario_wf_pro integer, "p_obs_pro " varchar, p_id_depto_wf_pro integer)
  OWNER TO postgres;
