CREATE OR REPLACE FUNCTION mat.f_iniciar_disparo_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
DECLARE

	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_solicitud			integer;
    v_id_proceso_ab_wf		integer;
    v_id_estado_ab_wf		integer;
    v_registros				record;
    v_codigo_estado_ab		varchar;
    v_solicitud				record;
    v_pedir_obs		    	varchar;
    v_id_tipo_estado		integer;
    v_id_estado_wf			integer;
    v_codigo_estado_siguiente varchar;
    v_obs					text;
    v_id_estado_actual		integer;
    v_id_depto				integer;
    v_acceso_directo  		varchar;
    v_clase   				varchar;
    v_parametros_ad   		varchar;
    v_tipo_noti  			varchar;
    v_titulo   				varchar;

    v_registros_proc		record;
    v_codigo_tipo_pro		varchar;
    v_codigo_llave			varchar;

    v_registros_mat 		record;
    v_id_funcionario    integer;
     v_id_usuario_reg	integer;
    v_id_estado_wf_ant  integer;
    v_id_proceso_wf  	integer;
    v_operacion			varchar;

    v_codigo_tipo_proceso 	varchar;
    v_id_proceso_macro    	integer;
    v_funcionario_wf		integer;

    v_id_estado_wf_dis		integer;

	v_codigo_estado 		varchar;

    v_id_estado_wf_firma	integer;

    /*Aumentando Variables Ismael Valdivia (02/03/2020)*/
    v_estado_actual	varchar;
    v_datos_solicitud	record;
    v_id_tipo_proceso_wf integer;
    v_id_tipo_estado_siguiente integer;
    v_codigo_estado_siguiente_auto varchar;
    v_acceso_directo_automatico varchar;
   	v_clase_automatico varchar;
   	v_parametros_ad_automatico varchar;
   	v_tipo_noti_automatico varchar;
   	v_titulo_automatico  varchar;
   	v_obs_automatico varchar;
	v_funcionario_encargado integer;
BEGIN
 v_nombre_funcion = 'mat.f_iniciar_disparo_ime';
 v_parametros = pxp.f_get_record(p_tabla);

    /*********************************
 	#TRANSACCION:  'MAT_SIG_DIS'
 	#DESCRIPCION:	Siguiente
 	#AUTOR:		admin
 	#FECHA:		21-09-2016 11:32:59
	***********************************/

    if(p_transaccion='MAT_SIG_DIS') then
    	begin
        --recupera toda la tabla solicitud
          select *
          into v_solicitud
          from mat.tsolicitud
          where id_proceso_wf_firma = v_parametros.id_proceso_wf_act;

          /*Aqui pondremos las condiciones para que pase directamente de estado*/
          if (v_solicitud.estado_firma = 'comite_aeronavegabilidad') then

          		/**********************AUMENTANDO ESTA PARTE PARA QUE NO PUEDA PASAR DE ESTADO SI EL COMITE NO AUTORIZO
               ***********************iSMAEL VALDIVIA (03/03/2020)*******************************************************/

                        --Recuperamos los datos de la solicitud
                        select sol.* into v_datos_solicitud
                        from mat.tsolicitud sol
                        where id_solicitud = v_parametros.id_solicitud;
                        ------------------------------------------------

                        --Recuperamos el id_tipo_proceso_wf para obtener el siguiente estado
                        select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                        from wf.tproceso_wf pr
                        where pr.id_proceso_wf = v_datos_solicitud.id_proceso_wf_firma;

                        select es.id_tipo_estado,
                               es.codigo,
                               fun.id_funcionario
                        into v_id_tipo_estado_siguiente,
                             v_codigo_estado_siguiente_auto,
                             v_funcionario_encargado
                        from wf.ttipo_estado es
                        left join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                        where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'autorizado';
                        ---------------------------------------------------------------------------
                         v_acceso_directo_automatico = '';
                         v_clase_automatico = '';
                         v_parametros_ad_automatico = '';
                         v_tipo_noti_automatico = 'notificacion';
                         v_titulo_automatico  = 'Visto Boa';
                         v_obs_automatico ='---';
                         ------------------------------------------pasamos el estado a vb_dpto_administrativo
                         v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                                 v_funcionario_encargado,--id del funcionario Solicitante Jaime Lazarte (Definir de donde recuperaremos)
                                                                 v_datos_solicitud.id_estado_wf_firma,
                                                                 v_datos_solicitud.id_proceso_wf_firma,
                                                                 p_id_usuario,
                                                                 v_parametros._id_usuario_ai,
                                                                 v_parametros._nombre_usuario_ai,
                                                                 v_id_depto,
                                                                 COALESCE(v_datos_solicitud.nro_tramite,'--')||' Obs:'||v_obs_automatico,
                                                                 v_acceso_directo_automatico,
                                                                 v_clase_automatico,
                                                                 v_parametros_ad_automatico,
                                                                 v_tipo_noti_automatico,
                                                                 v_titulo_automatico);

                         IF mat.f_procesar_estados_firmas(p_id_usuario,
                                                        v_parametros._id_usuario_ai,
                                                        v_parametros._nombre_usuario_ai,
                                                        v_id_estado_actual,
                                                        v_datos_solicitud.id_proceso_wf_firma,
                                                        v_codigo_estado_siguiente_auto) THEN

                        RAISE NOTICE 'PASANDO DE ESTADO';
                        -------------------------------------------------------------------------------------------------------------------------------------

                		END IF;

          /**********************AUMENTANDO ESTA PARTE PARA QUE NO PUEDA PASAR DE ESTADO SI EL COMITE NO AUTORIZO
           ***********************iSMAEL VALDIVIA (03/03/2020)*******************************************************/


                select sol.estado into v_estado_actual
                from mat.tsolicitud sol
                where sol.id_solicitud = v_parametros.id_solicitud;

                IF (v_estado_actual = 'autorizado') THEN
                	--Recuperamos los datos de la solicitud
                	select sol.* into v_datos_solicitud
                    from mat.tsolicitud sol
                    where id_solicitud = v_parametros.id_solicitud;
                    ------------------------------------------------

                    --Recuperamos el id_tipo_proceso_wf para obtener el siguiente estado
                    select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                    from wf.tproceso_wf pr
                    where pr.id_proceso_wf = v_datos_solicitud.id_proceso_wf;

                    select es.id_tipo_estado,
                    	   es.codigo,
                           fun.id_funcionario
                    into v_id_tipo_estado_siguiente,
                         v_codigo_estado_siguiente_auto,
                         v_funcionario_encargado
                    from wf.ttipo_estado es
                    inner join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                    where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'vb_rpcd';
                    ---------------------------------------------------------------------------

                     v_acceso_directo_automatico = '';
                     v_clase_automatico = '';
                     v_parametros_ad_automatico = '';
                     v_tipo_noti_automatico = 'notificacion';
                     v_titulo_automatico  = 'Visto Boa';
                     v_obs_automatico ='---';
                     ------------------------------------------pasamos el estado a vb_dpto_administrativo
                     v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                             v_funcionario_encargado,--id del funcionario Solicitante Jaime Lazarte (Definir de donde recuperaremos)
                                                             v_datos_solicitud.id_estado_wf,
                                                             v_datos_solicitud.id_proceso_wf,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             COALESCE(v_datos_solicitud.nro_tramite,'--')||' Obs:'||v_obs_automatico,
                                                             v_acceso_directo_automatico,
                                                             v_clase_automatico,
                                                             v_parametros_ad_automatico,
                                                             v_tipo_noti_automatico,
                                                             v_titulo_automatico);

                     IF mat.f_procesar_estados_solicitud(p_id_usuario,
           											v_parametros._id_usuario_ai,
                                            		v_parametros._nombre_usuario_ai,
                                            		v_id_estado_actual,
                                            		v_datos_solicitud.id_proceso_wf,
                                            		v_codigo_estado_siguiente_auto) THEN

         			RAISE NOTICE 'PASANDO DE ESTADO';
                    -------------------------------------------------------------------------------------------------------------------------------------

          			END IF;





                END IF;


           /*************************************************************************************************************/


               /*************************************************************************************************************/
          else
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
          where ew.id_estado_wf =  v_parametros.id_estado_wf_act;


           -- obtener datos tipo estado siguiente //codigo=borrador
           select te.codigo into
             v_codigo_estado_siguiente
           from wf.ttipo_estado te
           where te.id_tipo_estado = v_parametros.id_tipo_estado;


           IF  pxp.f_existe_parametro(p_tabla,'id_depto_wf') THEN
           	 v_id_depto = v_parametros.id_depto_wf;
           END IF;

           IF  pxp.f_existe_parametro(p_tabla,'obs') THEN
           	 v_obs = v_parametros.obs;
           ELSE
           	 v_obs='---';
           END IF;

             --configurar acceso directo para la alarma
             v_acceso_directo = '';
             v_clase = '';
             v_parametros_ad = '';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Visto Boa';


             IF   v_codigo_estado_siguiente not in('borrador','vobo_area','revision','cotizacion')   THEN

                  v_acceso_directo = '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php';
                  v_clase = 'Solicitud';
                  v_parametros_ad = '{filtro_directo:{campo:"mat.id_proceso_wf",valor:"'||
                  v_parametros.id_proceso_wf_act::varchar||'"}}';
                  v_tipo_noti = 'notificacion';
                  v_titulo  = 'Notificacion';
             END IF;

             -- hay que recuperar el supervidor que seria el estado inmediato...
            	v_id_estado_actual =  wf.f_registra_estado_wf(v_parametros.id_tipo_estado,
                                                             v_parametros.id_funcionario_wf,
                                                             v_parametros.id_estado_wf_act,
                                                             v_parametros.id_proceso_wf_act,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             COALESCE(v_solicitud.nro_tramite,'--')||' Obs:'||v_obs,
                                                             v_acceso_directo ,
                                                             v_clase,
                                                             v_parametros_ad,
                                                             v_tipo_noti,
                                                             v_titulo);


         		IF mat.f_procesar_estados_firmas(p_id_usuario,
           											v_parametros._id_usuario_ai,
                                            		v_parametros._nombre_usuario_ai,
                                            		v_id_estado_actual,
                                            		v_parametros.id_proceso_wf_act,
                                            		v_codigo_estado_siguiente) THEN

         			RAISE NOTICE 'PASANDO DE ESTADO';

          		END IF;
          end if;
          /*********************************************************************/

          -- si hay mas de un estado disponible  preguntamos al usuario
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado de Solicitud)');
          v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
          v_resp = pxp.f_agrega_clave(v_resp,'v_codigo_estado_siguiente',v_codigo_estado_siguiente);

          -- Devuelve la respuesta
          return v_resp;
        end;
 	/*********************************
 	#TRANSACCION:  'MAT_ANT_DIS'
 	#DESCRIPCION:	Estado Anterior
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/
    elsif(p_transaccion='MAT_ANT_DIS')then

		begin

			v_operacion = 'anterior';

            IF  pxp.f_existe_parametro(p_tabla , 'estado_destino')  THEN
               v_operacion = v_parametros.estado_destino;
            END IF;

          --obtenermos datos basicos
           select 	sol.id_solicitud,
 					sol.id_proceso_wf_firma,
                    sol.id_estado_wf_firma,
        			sol.estado_firma,
        			pwf.id_tipo_proceso
                     into v_registros_mat
 					from mat.tsolicitud sol
 					inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf_firma
 					where sol.id_proceso_wf_firma =  v_parametros.id_proceso_wf_firma;

           v_id_proceso_wf = v_registros_mat.id_proceso_wf_firma;
           IF  v_operacion = 'anterior' THEN

                -------------------------------------------------
                --Retrocede al estado inmediatamente anterior
                -------------------------------------------------
               	--recuperaq estado anterior segun Log del WF
                  SELECT

                     ps_id_tipo_estado,
                     ps_id_funcionario,
                     ps_id_usuario_reg,
                     ps_id_depto,
                     ps_codigo_estado,
                     ps_id_estado_wf_ant
                  into
                     v_id_tipo_estado,
                     v_id_funcionario,
                     v_id_usuario_reg,
                     v_id_depto,
                     v_codigo_estado_ab,
                     v_id_estado_wf_ant
                  FROM wf.f_obtener_estado_ant_log_wf(v_registros_mat.id_estado_wf_firma);

                select
              	ew.id_proceso_wf
              into
                v_id_proceso_wf
          	  from wf.testado_wf ew
         	  where ew.id_estado_wf= v_id_estado_wf_ant;
          --end if;


         END IF;

             --configurar acceso directo para la alarma
                 v_acceso_directo = '';
                 v_clase = '';
                 v_parametros_ad = '';
                 v_tipo_noti = 'notificacion';
                 v_titulo  = 'Visto Bueno';


               IF   v_codigo_estado_siguiente not in('borrador','vobo_area','revision','cotizacion','compra','despachado','arribo','desaduanizado','almacen')   THEN
                     v_acceso_directo = '../../../sis_gestion_materiales/vista/solicitud/RegistroSolicitud.php';
                     v_clase = 'RegistroSolicitud';
                     v_parametros_ad = '{filtro_directo:{campo:"mat.id_proceso_wf",valor:"'||v_id_proceso_wf::varchar||'"}}';
                     v_tipo_noti = 'notificacion';
                     v_titulo  = 'Visto Bueno';

               END IF;


              -- registra nuevo estado

                	v_id_estado_actual = wf.f_registra_estado_wf(
                  v_id_tipo_estado,
                  v_id_funcionario,
                  v_registros_mat.id_estado_wf_firma,
                  v_id_proceso_wf,
                  p_id_usuario,
                  v_parametros._id_usuario_ai,
                  v_parametros._nombre_usuario_ai,
                  v_id_depto,
                  '[RETROCESO] '|| v_parametros.obs);


                IF  not mat.f_ant_estado_firmas(p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_parametros.id_proceso_wf_firma,
                                                       v_codigo_estado_ab) THEN

                raise exception 'Error al retroceder estado';

                END IF;


             -- si hay mas de un estado disponible  preguntamos al usuario
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado)');
                v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

              --Devuelve la respuesta
                return v_resp;

        END;
        /*********************************
 	#TRANSACCION:  'MAT_INI_DIS'
 	#DESCRIPCION:	Devolver al estado Borrador
 	#AUTOR:		admin
 	#FECHA:		07-06-2017
	***********************************/
    elsif(p_transaccion='MAT_INI_DIS')then
		begin


        		select 	sol.id_solicitud,
                		sol.nro_tramite,
                        sol.id_proceso_wf,
                        pwf.id_tipo_proceso,
                        es.id_estado_wf,
                        sol.estado,
                        sol.id_funcionario_sol,
                        pwf.id_tipo_proceso,
                        sol.id_estado_wf_firma,
                        sol.id_proceso_wf_firma,
                        sol.estado_firma
                     	into v_registros_mat
 					from mat.tsolicitud sol
 					inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf
                    inner join wf.testado_wf es on es.id_estado_wf = sol.id_estado_wf
 					where sol.id_proceso_wf =  v_parametros.id_proceso_wf;



        IF  v_parametros.operacion = 'inicio' THEN

        -- recuperamos el estado inicial segun tipo_proceso
             SELECT
               ps_id_tipo_estado,
               ps_codigo_estado
             into
               v_id_tipo_estado,
               v_codigo_estado
             FROM wf.f_obtener_tipo_estado_inicial_del_tipo_proceso(v_registros_mat.id_tipo_proceso);

         --registra estado borrador
          v_id_estado_actual =  mat.ft_registra_estado_wf(v_id_tipo_estado,
                                                           v_registros_mat.id_funcionario_sol,
                                                           v_registros_mat.id_estado_wf,
                                                           v_registros_mat.id_proceso_wf,
                                                           p_id_usuario,
                                                           v_parametros._id_usuario_ai,
                                                           v_parametros._nombre_usuario_ai,
                                                           v_id_depto,
                                                           'RETRO: #'|| COALESCE(v_registros_mat.nro_tramite,'S/N')||' - '||v_parametros.obs);

               update mat.tsolicitud  set
                 id_estado_wf =  v_id_estado_actual,
                 estado = v_codigo_estado,
                 id_usuario_mod=p_id_usuario,
                 fecha_mod=now()
                 where id_proceso_wf = v_parametros.id_proceso_wf;



       	select
        te.id_tipo_estado
        into
        	v_id_estado_wf_firma
        from wf.tproceso_wf pw
        inner join wf.ttipo_proceso tp on pw.id_tipo_proceso = tp.id_tipo_proceso
        inner join wf.ttipo_estado te on te.id_tipo_proceso = tp.id_tipo_proceso and te.codigo = 'rechazado'
        where pw.id_proceso_wf = v_registros_mat.id_proceso_wf_firma;


           v_id_estado_actual =  wf.f_registra_estado_wf(	v_id_estado_wf_firma,
           													v_registros_mat.id_funcionario_sol,
                                                           	v_registros_mat.id_estado_wf_firma,
                                                           	v_registros_mat.id_proceso_wf_firma,
                                                           	p_id_usuario,
                                                           	v_parametros._id_usuario_ai,
            											   	v_parametros._nombre_usuario_ai,
                                                           	v_id_depto,
                                                           	'RECHAZADO');


        -- actualiza estado en la solicitud
        update mat.tsolicitud  set
                 id_estado_wf_firma = v_id_estado_actual,
            	 estado_firma = 'rechazado'
               	 where id_proceso_wf_firma = v_registros_mat.id_proceso_wf_firma;




 END IF;




             -- si hay mas de un estado disponible  preguntamos al usuario
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado)');
                v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

              --Devuelve la respuesta
                return v_resp;


        end;
END IF;
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

ALTER FUNCTION mat.f_iniciar_disparo_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
