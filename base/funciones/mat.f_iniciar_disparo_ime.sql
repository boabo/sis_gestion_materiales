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


BEGIN
 v_nombre_funcion = 'mat.f_iniciar_disparo_ime';
 v_parametros = pxp.f_get_record(p_tabla);

    /*********************************
 	#TRANSACCION:  'MAT_SOL_DIS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/

    if(p_transaccion='MAT_SOL_DIS')then

        begin

        SELECT * into v_registros
        FROM mat.tsolicitud
        WHERE id_solicitud = v_parametros.id_solicitud;

         IF v_registros.origen_pedido ='Gerencia de Operaciones' THEN

     	   select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GO-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

            elsif v_registros.origen_pedido ='Gerencia de Mantenimiento'then

           select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GM-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;
		END IF;

        select fu.id_funcionario
        INTO
        	v_funcionario_wf
        from segu.tusuario u
        inner join orga.tfuncionario fu on fu.id_persona = u.id_persona
        where fu.id_funcionario = p_id_usuario;

     	------------------------------
            --registra procesos disparados
            ------------------------------
            SELECT
            ps_id_proceso_wf,
            ps_id_estado_wf,
            ps_codigo_estado
            into
            v_id_proceso_ab_wf,
            v_id_estado_ab_wf,
            v_codigo_estado_ab
            FROM wf.f_registra_proceso_disparado_wf(
                               p_id_usuario,
                               v_parametros._id_usuario_ai,
                               v_parametros._nombre_usuario_ai,
                               v_registros.id_estado_wf,
                               v_funcionario_wf,
                               NULL,
                               'Flujo de Firmas',
                               v_codigo_tipo_proceso,
                               'Flujo de Firmas');

             --Sentencia de la insercion

            update mat.tsolicitud set
			estado_firma = v_codigo_estado_ab,
            id_proceso_wf_firma = v_id_proceso_ab_wf,
            id_estado_wf_firma = v_id_estado_ab_wf
            where id_solicitud=v_parametros.id_solicitud;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud almacenado(a) con exito (id_solicitud'||v_id_solicitud||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_id_solicitud::varchar);

            --Devuelve la respuesta
            return v_resp;


		end;
    /*********************************
 	#TRANSACCION:  'MAT_SIG_DIS'
 	#DESCRIPCION:	Siguiente
 	#AUTOR:		admin
 	#FECHA:		21-09-2016 11:32:59
	***********************************/

    elseif(p_transaccion='MAT_SIG_DIS') then
    	begin

        --recupera toda la tabla solicitud
          select *
          into v_solicitud
          from mat.tsolicitud
          where id_proceso_wf_firma = v_parametros.id_proceso_wf_act;

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