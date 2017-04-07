CREATE OR REPLACE FUNCTION mat.ft_solicitud_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestión de Materiales
 FUNCION: 		mat.ft_solicitud_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tsolicitud'
 AUTOR: 		 (admin)
 FECHA:	        23-12-2016 13:12:58
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_solicitud	integer;
     v_codigo_tipo_proceso 	varchar;
    v_id_proceso_macro    	integer;
    v_gestion 				integer;
     v_nro_tramite			varchar;
    v_id_proceso_wf			integer;
    v_id_estado_wf			integer;
    v_codigo_estado 		varchar;

    v_registros_mat		record;
      v_cont_del			integer;
      v_operacion			varchar;

    v_id_funcionario    integer;
    v_id_usuario_reg	integer;
    v_id_estado_wf_ant  integer;
    v_id_estado_actual	integer;
    v_id_depto				integer;
    v_id_tipo_estado		integer;

     v_acceso_directo  	varchar;
    v_clase   			varchar;
    v_parametros_ad   	varchar;
    v_tipo_noti  		varchar;
    v_titulo   			varchar;
     v_codigo_estado_siguiente varchar;

     v_solicitud				record;
     v_pedir_obs		    	varchar;
     v_registros_proc	record;
    v_codigo_tipo_pro	varchar;
    v_codigo_llave		varchar;
     v_obs					text;

     v_codigo				varchar;
     v_count_sol		INTEGER;
     anho		INTEGER;
     v_campos record;


     v_control_duplicidad record;
     v_justificacion varchar;

     v_est record;
     v_rev varchar;
     v_codigo_abastecimientos	varchar;


 	v_codigo_tipo_proceso_ab 	varchar;
    v_id_proceso_macro_ab    	integer;
    v_estado_wf_sol				INTEGER;

	v_id_proceso_wf_ab			integer;
    v_id_estado_wf_ab			integer;


    va_id_tipo_estado_pro 			integer[];
    va_codigo_estado_pro 			varchar[];
    va_disparador_pro 				varchar[];
    va_regla_pro 					varchar[];
    va_prioridad_pro 				integer[];

    v_msg_control				varchar;




BEGIN

    v_nombre_funcion = 'mat.ft_solicitud_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_SOL_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/

	if(p_transaccion='MAT_SOL_INS')then

        begin


    		--Obtenemos la gestion
           select g.id_gestion, g.gestion
           into v_gestion, anho
           from param.tgestion g
           where g.gestion = EXTRACT(YEAR FROM current_date);
           --conteo de solicitud
           select count(*)
           into v_count_sol
			from mat.tsolicitud;

           --generar numero de tramite
           IF v_parametros.origen_pedido ='Gerencia de Operaciones' THEN

     	   select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GO-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

            elsif v_parametros.origen_pedido ='Gerencia de Mantenimiento'then

           select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GM-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

            elsif v_parametros.origen_pedido ='Almacenes Consumibles o Rotables'then
           	select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GA-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;
END IF;



            -- inciar el tramite en el sistema de WF
           SELECT
                 ps_num_tramite ,
                 ps_id_proceso_wf ,
                 ps_id_estado_wf ,
                 ps_codigo_estado
              into
                 v_nro_tramite,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 v_parametros._id_usuario_ai,
                 v_parametros._nombre_usuario_ai,
                 v_gestion,
                 v_codigo_tipo_proceso,
                 NULL,
                 NULL,
                 'Solicitud',
                 'SOL');

            --iniciar el disparador


		--Recuperara estado Abastecimientos

            --Sentencia de la insercion
        	insert into mat.tsolicitud(
			id_funcionario_sol,
			id_proceso_wf,
			id_estado_wf,
			nro_po,
			tipo_solicitud,
			--fecha_entrega_miami,
			origen_pedido,
			fecha_requerida,
			--observacion_nota,
			fecha_solicitud,
			estado_reg,
			observaciones_sol,
			--fecha_tentativa_llegada,
			--fecha_despacho_miami,
			justificacion,
			--fecha_arribado_bolivia,
			--fecha_desaduanizacion,
			--fecha_entrega_almacen,
			--cotizacion,
			tipo_falla,
			nro_tramite,
			id_matricula,
			--nro_solicitud,
			motivo_solicitud,
			--fecha_en_almacen,
            tipo_reporte,
            mel,
            nro_no_rutina,
            nro_justificacion,
			estado,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
           -- estado_ab
          	) values(
			v_parametros.id_funcionario_sol,
			v_id_proceso_wf,
			v_id_estado_wf,
			null,
			v_parametros.tipo_solicitud,
			--v_parametros.fecha_entrega_miami,
			v_parametros.origen_pedido,
			v_parametros.fecha_requerida,
			--v_parametros.observacion_nota,
			v_parametros.fecha_solicitud,
			'activo',
			v_parametros.observaciones_sol,
			--v_parametros.fecha_tentativa_llegada,
			--v_parametros.fecha_despacho_miami,
			v_parametros.justificacion,
			--v_parametros.fecha_arribado_bolivia,
			--v_parametros.fecha_desaduanizacion,
			--v_parametros.fecha_entrega_almacen,
			--v_parametros.cotizacion,
			v_parametros.tipo_falla,
			v_nro_tramite,
			v_parametros.id_matricula,
			--v_parametros.nro_solicitud,
			v_parametros.motivo_solicitud,
			--v_parametros.fecha_en_almacen,
            v_parametros.tipo_reporte,
            v_parametros.mel,
            v_parametros.nro_no_rutina,
            v_parametros.nro_justificacion,
			v_codigo_estado,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null
          --  null
            )RETURNING id_solicitud into v_id_solicitud;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud almacenado(a) con exito (id_solicitud'||v_id_solicitud||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_id_solicitud::varchar);
            ---v_resp = mat.f_iniciar_disparo(p_administrador, p_id_usuario,hstore(v_parametros));


            --Devuelve la respuesta
            return v_resp;


		end;

	/*********************************
 	#TRANSACCION:  'MAT_SOL_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/

	elsif(p_transaccion='MAT_SOL_MOD')then

		begin
        select s.estado
        into v_est
        from mat.tsolicitud s
        WHERE s.id_solicitud = v_parametros.id_solicitud;

			--Sentencia de la modificacion
			update mat.tsolicitud set
			id_funcionario_sol = v_parametros.id_funcionario_sol,
            tipo_reporte = v_parametros.tipo_reporte,
            mel = v_parametros.mel,
            nro_no_rutina = v_parametros.nro_no_rutina,
            id_proveedor = v_parametros.id_proveedor,
			nro_po = v_parametros.nro_po,
			tipo_solicitud = v_parametros.tipo_solicitud,
			origen_pedido = v_parametros.origen_pedido,
			fecha_requerida = v_parametros.fecha_requerida,
			fecha_solicitud = v_parametros.fecha_solicitud,
			observaciones_sol = v_parametros.observaciones_sol,
            fecha_cotizacion = v_parametros.fecha_cotizacion,
			justificacion = v_parametros.justificacion,
			fecha_arribado_bolivia = v_parametros.fecha_arribado_bolivia,
			fecha_desaduanizacion = v_parametros.fecha_desaduanizacion,
			tipo_falla = v_parametros.tipo_falla,
			id_matricula = v_parametros.id_matricula,
			motivo_solicitud = v_parametros.motivo_solicitud,
			fecha_en_almacen = v_parametros.fecha_en_almacen,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai

        	 where id_solicitud=v_parametros.id_solicitud;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_parametros.id_solicitud::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_SOL_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/

	elsif(p_transaccion='MAT_SOL_ELI')then

		begin

        select 	ma.id_proceso_wf,
        		ma.id_estado_wf,
                ma.estado,
                ma.id_solicitud,
                ma.nro_tramite
                into
                v_id_proceso_wf,
                v_id_estado_wf,
        		v_codigo_estado,
                v_id_solicitud,
                v_codigo
        from mat.tsolicitud ma
        where ma.id_solicitud = v_parametros.id_solicitud;

        select
        	te.id_tipo_estado
        into
        	v_id_tipo_estado
        from wf.tproceso_wf pw
        inner join wf.ttipo_proceso tp on pw.id_tipo_proceso = tp.id_tipo_proceso
        inner join wf.ttipo_estado te on te.id_tipo_proceso = tp.id_tipo_proceso and te.codigo = 'anulado'
        where pw.id_proceso_wf = v_id_proceso_wf;


        if v_id_tipo_estado is null  then
        	raise exception 'No se parametrizo el estado "anulado" ';
        end if;
          -- pasamos la solicitud  al siguiente anulado
           v_id_estado_actual =  wf.f_registra_estado_wf(	v_id_tipo_estado,
           													v_id_funcionario,
                                                          	v_id_estado_wf,
            												v_id_proceso_wf,
                                                           	p_id_usuario,
                                                           	v_parametros._id_usuario_ai,
            											   	v_parametros._nombre_usuario_ai,
                                                           	v_id_depto,
                                                           	'Solicitud Anulada');


        -- actualiza estado en la solicitud
        update mat.tsolicitud  ma set
                 id_estado_wf =  v_id_estado_actual,
                 estado = 'anulado',
                 id_usuario_mod=p_id_usuario,
                 fecha_mod=now(),
                 estado_reg='inactivo',
                 id_usuario_ai = v_parametros._id_usuario_ai,
                 usuario_ai = v_parametros._nombre_usuario_ai
               where ma.id_solicitud  = v_parametros.id_solicitud;

        --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_parametros.id_solicitud::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
     /*********************************
 	#TRANSACCION:  'MAT_ANT_INS'
 	#DESCRIPCION:	Estado Anterior
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/
    elsif(p_transaccion='MAT_ANT_INS')then

		begin

			v_operacion = 'anterior';

            IF  pxp.f_existe_parametro(p_tabla , 'estado_destino')  THEN
               v_operacion = v_parametros.estado_destino;
            END IF;

          --obtenermos datos basicos
           select 	sol.id_solicitud,
 					sol.id_proceso_wf,
        			sol.estado,
        			pwf.id_tipo_proceso
                     into v_registros_mat
 					from mat.tsolicitud sol
 					inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf
 					where sol.id_proceso_wf =  v_parametros.id_proceso_wf;
                    v_id_proceso_wf = v_registros_mat.id_proceso_wf;

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
                     v_codigo_estado,
                     v_id_estado_wf_ant
                  FROM wf.f_obtener_estado_ant_log_wf(v_parametros.id_estado_wf);

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
                  v_id_tipo_estado,                --  id_tipo_estado al que retrocede
                  v_id_funcionario,                --  funcionario del estado anterior
                  v_parametros.id_estado_wf,       --  estado actual ...
                  v_id_proceso_wf,                 --  id del proceso actual
                  p_id_usuario,                    -- usuario que registra
                  v_parametros._id_usuario_ai,
                  v_parametros._nombre_usuario_ai,
                  v_id_depto,                       --depto del estado anterior
                  '[RETROCESO] '|| v_parametros.obs,
                  v_acceso_directo,
                  v_clase,
                  v_parametros_ad,
                  v_tipo_noti,
                  v_titulo);

                IF  not mat.f_ant_estado_solicitud_wf(p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_parametros.id_proceso_wf,
                                                       v_codigo_estado) THEN

                raise exception 'Error al retroceder estado';

                END IF;


             -- si hay mas de un estado disponible  preguntamos al usuario
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado)');
                v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

              --Devuelve la respuesta
                return v_resp;

        END;

    /*********************************
 	#TRANSACCION:  'MAT_INI_INS'
 	#DESCRIPCION:	Estado inicio
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/
    elsif(p_transaccion='MAT_INI_INS')then

		begin

			v_operacion = 'inicio';

            IF  pxp.f_existe_parametro(p_tabla , 'estado_destino')  THEN
               v_operacion = v_parametros.estado_destino;
            END IF;

          --obtenermos datos basicos
           select 	sol.id_solicitud,
 					sol.id_proceso_wf,
        			sol.estado,
        			pwf.id_tipo_proceso
                     into v_registros_mat
 					from mat.tsolicitud sol
 					inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf
 					where sol.id_proceso_wf =  v_parametros.id_proceso_wf;
                    v_id_proceso_wf = v_registros_mat.id_proceso_wf;

          	IF  v_operacion = 'inicio' THEN

            SELECT
               ps_id_tipo_estado,
               ps_codigo_estado
             into
               v_id_tipo_estado,
               v_codigo_estado
             FROM wf.f_obtener_tipo_estado_inicial_del_tipo_proceso(v_registros_mat.id_tipo_proceso);
             --busca en log e estado de wf que identificamos como el inicial
             SELECT
               ps_id_funcionario,
              ps_id_depto
             into
             v_id_funcionario,
             v_id_depto
         FROM wf.f_obtener_estado_segun_log_wf(v_id_estado_wf, v_id_tipo_estado);

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
                  v_id_tipo_estado,                --  id_tipo_estado al que retrocede
                  v_id_funcionario,                --  funcionario del estado anterior
                  v_parametros.id_estado_wf,       --  estado actual ...
                  v_id_proceso_wf,                 --  id del proceso actual
                  p_id_usuario,                    -- usuario que registra
                  v_parametros._id_usuario_ai,
                  v_parametros._nombre_usuario_ai,
                  v_id_depto,                       --depto del estado anterior
                  '[RETROCESO] '|| v_parametros.obs,
                  v_acceso_directo,
                  v_clase,
                  v_parametros_ad,
                  v_tipo_noti,
                  v_titulo);

                IF  not mat.f_ant_estado_solicitud_wf(p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_parametros.id_proceso_wf,
                                                       v_codigo_estado) THEN

                raise exception 'Error al retroceder estado';

                END IF;


             -- si hay mas de un estado disponible  preguntamos al usuario
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado)');
                v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

              --Devuelve la respuesta
                return v_resp;

        END;



	/*********************************
 	#TRANSACCION:  'MAT_SIG_IME'
 	#DESCRIPCION:	Siguiente
 	#AUTOR:		admin
 	#FECHA:		21-09-2016 11:32:59
	***********************************/

    elseif(p_transaccion='MAT_SIG_IME') then
    	begin
        --recupera toda la tabla solicitud
          select sol.*
          into v_solicitud
          from mat.tsolicitud sol
          where id_proceso_wf = v_parametros.id_proceso_wf_act;

          select
            ew.id_tipo_estado ,
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


         		IF mat.f_procesar_estados_solicitud(p_id_usuario,
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
 	#TRANSACCION:  'MAT_FUN_GET'
 	#DESCRIPCION:	Lista de funcionarios para registro
 	#AUTOR:		MMV
 	#FECHA:		10-01-2017 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_FUN_GET')then

		begin
			--Sentencia de la consulta de conteo de registros
			SELECT tf.id_funcionario, vfcl.desc_funcionario1, vfcl.nombre_cargo
            INTO v_campos
			FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            INNER JOIN orga.vfuncionario_cargo_lugar vfcl on vfcl.id_funcionario = tf.id_funcionario
            WHERE tu.id_usuario = p_id_usuario;

            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
			v_resp = pxp.f_agrega_clave(v_resp,'id_funcionario',v_campos.id_funcionario::varchar);
			v_resp = pxp.f_agrega_clave(v_resp,'nombre_completo1',v_campos.desc_funcionario1::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'nombre_cargo',v_campos.nombre_cargo);

            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_GET_JUS'
 	#DESCRIPCION:	control de numero de justificacion
 	#AUTOR:		MMV
 	#FECHA:		10-01-2017 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_GET_JUS')then
		begin
        FOR v_control_duplicidad in (select	d.nro_parte,
											f.desc_funcionario1,
                                            s.nro_justificacion,
                                            s.nro_no_rutina,
                                            s.justificacion,
                                            s.nro_tramite,
                                            s.fecha_solicitud
                                            from mat.tdetalle_sol d
                                            inner join mat.tsolicitud s on s.id_solicitud = d.id_solicitud
                                            inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol

     	)LOOP
        if v_control_duplicidad.nro_justificacion !=''then
        	if v_parametros.justificacion = v_control_duplicidad.nro_justificacion then
            	v_justificacion= v_control_duplicidad.nro_justificacion;
                v_msg_control = v_control_duplicidad.nro_justificacion||' de '||v_control_duplicidad.justificacion||' fue registrado por '||v_control_duplicidad.desc_funcionario1||' en el tramite '||v_control_duplicidad.nro_tramite;
            end if;
        end if;
        END LOOP;

        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
        v_resp = pxp.f_agrega_clave(v_resp,'justificacion', v_justificacion::varchar );
        v_resp = pxp.f_agrega_clave(v_resp,'mgs_control_duplicidad', v_msg_control::varchar );
        return v_resp;

		end;



else

    	raise exception 'Transaccion inexistente: %',p_transaccion;

end if;

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