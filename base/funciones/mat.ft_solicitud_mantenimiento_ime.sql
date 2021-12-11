CREATE OR REPLACE FUNCTION mat.ft_solicitud_mantenimiento_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestión de Materiales
 FUNCION: 		mat.ft_solicitud_mantenimiento_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tsolicitud'
 AUTOR: 		 (Ismael Valdivia)
 FECHA:	        17-01-2020 08:50:00
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
    v_parte 					varchar;
    v_matricula					varchar;

    --correos proveedores
    v_ids_prov					integer[];
	v_tam						integer;
	v_cont						integer;
    v_ids_prov_act				integer[];
    v_record					record;
    v_sin_correo				varchar[];
    v_bandera					boolean;
    v_i							integer;
    v_id_proveedores			varchar;

    vv_id_proveedor_md			integer[];
    vv_lista					integer;
   	vv_id 						integer;
    vv_ids						integer;

      v_record_clon				record;
    v_detalle_clon				record;
	v_id_proceso_wf_clo			integer;
	v_estado_recrdo				record;
    v_id_estado_clon			integer;
    v_funcionario_wf			integer;
    v_estado					record;
    v_id_usuario 				integer;
    v_registros					record;

    v_datos_solicitud			record;
    v_id_tipo_est			integer;
    v_mod					record;
    v_id_tip_pro			integer;
    v_documento_wf			record;
    v_id_estado_firma		integer;
    v_mod_f					record;
    v_id					integer;
  	v_id_2					integer;
    v_url					varchar;

    v_id_estado_tipo		integer;
    vv_id_funcionario		integer;
    v_id_tipo_docuemnteo	integer;
    v_cotizacon	record;
    vv_id_cot	integer;
    v_cotizacion_det record;
     vv_id_det	integer;
     v_documento record;
      v_nro_cite_dce		varchar;
      v_id_gestion  integer;
    v_cargar_list			boolean;

    v_id_proceso_wf_so  	INTEGER;

    v_id_detalle				integer;
    v_nro_parte					varchar;
    v_nro_parte_alterno			varchar;

    v_datos_matricula		record;
    v_codigo_subsitema		varchar;
    v_codadd  varchar;
    v_filadd varchar;
    v_inner  varchar;
    v_datos_recuperados	record;

    v_contador		integer;
    v_id_funcionario_solicitante integer;

    v_id_tipo_proceso_wf integer;
    v_id_tipo_estado_siguiente integer;
    v_codigo_estado_siguiente_rpcd varchar;
    v_acceso_directo_automatico varchar;
   	v_clase_automatico varchar;
   	v_parametros_ad_automatico varchar;
   	v_tipo_noti_automatico varchar;
   	v_titulo_automatico  varchar;
   	v_obs_automatico varchar;
    v_funcionario_encargado integer;
    v_id_unidad_medida	integer;

    v_codigo_poa		varchar;
    v_id_centro_costo	integer;
    v_gestion_actual	integer;
    v_gestion_solicitud	integer;
    v_id_tipo_documento	integer;
    v_id_documento_wf	integer;
    v_max_version       integer;
    v_registros_his	    record;
    v_new_url				varchar;
    v_id_documento_historico_wf integer;
    v_tipo_falla	varchar;

    v_registros_cig	record;
    v_id_concepto_ingas_recu	integer;
    v_id_partida_recu	integer;
    v_cuenta_recu		integer;
    v_id_auxiliar_recu	integer;
    v_id_orden_trabajo_rec	integer;
    v_recuperar_datos_detalle	varchar;
    v_origen_pedido		varchar;
BEGIN

    v_nombre_funcion = 'mat.ft_solicitud_mantenimiento_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_SOLMANT_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		17-01-2020 08:50:00
	***********************************/

	if(p_transaccion='MAT_SOLMANT_INS')then

        begin

    		/*Aumentando para recuperar al funcionario solicitante
            El funcionario solicitante esta creado en una variable global
            por el momento el funcionario solicitante es JAIME LAZARTE
            el id funcionario es (370).
            Si el funcionario solicitante es otro cambiar en la variable global*/
            v_id_funcionario_solicitante = pxp.f_get_variable_global('funcionario_solicitante_gm');        	/**********************************************************************/

            /*En base al funcionario solicitante debemos recuperar el Departamento y no del
            Técnico de Mantenimiento (Ismael Valdivia 04/03/2020)*/
            /*Aumentando condicion para recuperar el departamento (28/02/2020)*/
            SELECT fun.id_funcionario, fun.desc_funcionario1, fun.nombre_cargo,
            /*Aumentando para recuperar el departamento*/
            fun.id_lugar::varchar
            /*******************************************/
            INTO v_campos
			FROM orga.vfuncionario_ultimo_cargo fun
            WHERE fun.id_funcionario =  v_id_funcionario_solicitante;

          SELECT
              COUNT (DEPPTO.id_depto)
              into v_contador
              FROM param.tdepto DEPPTO
              INNER JOIN segu.tsubsistema SUBSIS on SUBSIS.id_subsistema=DEPPTO.id_subsistema
              INNER JOIN segu.tusuario USUREG on USUREG.id_usuario=DEPPTO.id_usuario_reg
              INNER JOIN segu.vpersona PERREG on PERREG.id_persona=USUREG.id_persona
              LEFT JOIN segu.tusuario USUMOD on USUMOD.id_usuario=DEPPTO.id_usuario_mod
              LEFT JOIN segu.vpersona PERMOD on PERMOD.id_persona=USUMOD.id_persona
            WHERE   DEPPTO.estado_reg = 'activo'  and (SUBSIS.codigo = 'ADQ') AND (v_campos.id_lugar::integer =ANY(DEPPTO.id_lugares) or prioridad = 1);

            if (v_contador > 1) THEN
                raise exception 'Tiene mas de dos departamentos para realizar la solicitud';
            end if;

            SELECT
              DISTINCT
              DEPPTO.id_depto
              into v_datos_recuperados
              FROM param.tdepto DEPPTO
              INNER JOIN segu.tsubsistema SUBSIS on SUBSIS.id_subsistema=DEPPTO.id_subsistema
              INNER JOIN segu.tusuario USUREG on USUREG.id_usuario=DEPPTO.id_usuario_reg
              INNER JOIN segu.vpersona PERREG on PERREG.id_persona=USUREG.id_persona
              LEFT JOIN segu.tusuario USUMOD on USUMOD.id_usuario=DEPPTO.id_usuario_mod
              LEFT JOIN segu.vpersona PERMOD on PERMOD.id_persona=USUMOD.id_persona
            WHERE   DEPPTO.estado_reg = 'activo'  and (SUBSIS.codigo = 'ADQ') AND (v_campos.id_lugar::integer =ANY(DEPPTO.id_lugares) or prioridad = 1);

            /******************************************************************/


        	/*Aumentamos para recuperar el id_matricula*/
            	select ord.id_orden_trabajo,
                split_part(ord.desc_orden ,' ',2) ||' '||  split_part(ord.desc_orden :: text,' ',3):: text as matricula,
                ord.desc_orden,
                ord.codigo into v_datos_matricula
                from conta.torden_trabajo ord
                inner join conta.tgrupo_ot_det gr on gr.id_orden_trabajo = ord.id_orden_trabajo and gr.id_grupo_ot IN( 1,4)
                where ord.codigo = v_parametros.matricula;
            /*******************************************/


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

               v_justificacion = '"0" Existencia en Almacén';


                elsif v_parametros.origen_pedido ='Gerencia de Mantenimiento' then

               select    tp.codigo, pm.id_proceso_macro
               into v_codigo_tipo_proceso, v_id_proceso_macro
               from  wf.tproceso_macro pm
               inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
               where pm.codigo='GM-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

               v_justificacion = '"0" Existencia en Almacén';


                elsif v_parametros.origen_pedido ='Almacenes Consumibles o Rotables'then
                select    tp.codigo, pm.id_proceso_macro
               into v_codigo_tipo_proceso, v_id_proceso_macro
               from  wf.tproceso_macro pm
               inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
               where pm.codigo='GA-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

               v_justificacion = '';



                elsif v_parametros.origen_pedido ='Centro de Entrenamiento Aeronautico Civil'then
                select    tp.codigo, pm.id_proceso_macro
               into v_codigo_tipo_proceso, v_id_proceso_macro
               from  wf.tproceso_macro pm
               inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
               where pm.codigo='GC-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

               v_justificacion = '"0" Existencia en Almacén';

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
                 'Gestión de Materiales',
                 v_codigo_tipo_proceso);

			UPDATE wf.tproceso_wf SET
            	descripcion = 'Gestión de Materiales ['||v_nro_tramite||']',
          		codigo_proceso = v_nro_tramite
            WHERE id_proceso_wf = v_id_proceso_wf;
            --iniciar el disparador

        /*Aqui recuperamos el codigo_poa (Ismael Valdivia 10/03/2020)*/
        v_codigo_poa = pxp.f_get_variable_global('codigo_poa');
        /*************************************************************/

        select g.id_gestion
           	 		into v_id_gestion
           			from param.tgestion g
           			where g.gestion = EXTRACT(YEAR FROM current_date);


         /*Aqui Recuperamos el centro de costo de acuerdo a la gestion (Ismael Valdivia 17/03/2020)*/
            select cc.id_centro_costo into v_id_centro_costo
            from param.tcentro_costo cc
            inner join param.ttipo_cc tc on tc.id_tipo_cc = cc.id_tipo_cc
            where tc.codigo = '845' and cc.id_gestion = v_id_gestion;
         /******************************************************************************************/







       		/*Aqui recuperamos la gestion actual para que no permita registrar de gestiones anteriores*/
            select EXTRACT(YEAR FROM current_date) into v_gestion_actual;

            select EXTRACT(YEAR FROM v_parametros.fecha_solicitud::date) into v_gestion_solicitud;


            if (v_gestion_actual != v_gestion_solicitud) then
            	raise exception 'La fecha no pertenece a la gestión actual verifique';
            end if;

            /******************************************************************************************/


		--Recuperara estado Abastecimientos

            --Sentencia de la insercion
        	insert into mat.tsolicitud(
			id_funcionario_sol,
			id_proceso_wf,
			id_estado_wf,
			nro_po,
			tipo_solicitud,
			origen_pedido,
			fecha_requerida,
			fecha_solicitud,
			estado_reg,
			observaciones_sol,
			justificacion,
			tipo_falla,
			nro_tramite,
			id_matricula,
			motivo_solicitud,
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
			id_usuario_mod,
            id_depto,
            /*Aumentando el funcionario solicitante*/
            id_funcionario_solicitante,
            codigo_poa,
            id_moneda,
            presu_comprometido,
            presupuesto_aprobado,
            id_gestion,
            mel_observacion,
            origen_solicitud
          	) values(
			v_parametros.id_funcionario_sol,
			v_id_proceso_wf,
			v_id_estado_wf,
			null,
			v_parametros.tipo_solicitud,
			v_parametros.origen_pedido,
			v_parametros.fecha_requerida,
			v_parametros.fecha_solicitud,
			'activo',
			v_parametros.observaciones_sol,
			v_justificacion,--v_parametros.justificacion,
			v_parametros.tipo_falla,
			v_nro_tramite,
            v_datos_matricula.id_orden_trabajo,	--v_parametros.id_matricula,
			v_parametros.motivo_solicitud,
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
			null,
            v_datos_recuperados.id_depto,
            /*Aumentando el funcionario solicitante*/
            v_id_funcionario_solicitante,
            v_codigo_poa,
            2,
            'no',
            'verificar',
            v_id_gestion,
            v_parametros.mel_observacion,
            'control_mantenimiento'
            )RETURNING id_solicitud into v_id_solicitud;

            /*Insertamos el detalle de datos*/
            for v_registros in (select *
            					from json_populate_recordset(null::mat.detalle_solicitud_mantenimiento,v_parametros.json_solicitud_detalle::json))loop


            /*Aqui recuperamos el id_unidad_medida del erp porque nos mandaran el id_alkym de la unidad de medida (Ismael Valdivia 04/03/2020)*/
            select un.id_unidad_medida into v_id_unidad_medida
            from mat.tunidad_medida un
            where un.id_alkym = v_registros.id_unidad_medida;

            /*Aqui aumentando para recuperar la partida y el centro de costo del PartNumber Seleccionado Ismael Valdivia (22/11/2021)*/
            v_recuperar_datos_detalle =  pxp.f_get_variable_global('mat_recuperar_datos_detalle');
            if (v_recuperar_datos_detalle = 'si') then

            	select soli.origen_pedido into v_origen_pedido
                from mat.tsolicitud soli
                where soli.id_solicitud = v_id_solicitud;


                select
                       det.id_concepto_ingas
                into
                       v_id_concepto_ingas_recu
                from mat.tdetalle_sol det
                inner join mat.tsolicitud sol on sol.id_solicitud = det.id_solicitud
                where sol.origen_pedido = v_origen_pedido
                and trim(det.nro_parte) = trim(regexp_replace(v_registros.nro_parte,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'))
                order by det.fecha_reg desc
                limit 1;

                if (v_id_concepto_ingas_recu is not null) then
                    select
                    cig.desc_ingas
                    into
                    v_registros_cig
                    from param.tconcepto_ingas cig
                    where cig.id_concepto_ingas =  v_id_concepto_ingas_recu;

                     SELECT
                        ps_id_partida ,
                        ps_id_cuenta,
                        ps_id_auxiliar
                      into
                        v_id_partida_recu,
                        v_cuenta_recu,
                        v_id_auxiliar_recu
                     FROM conta.f_get_config_relacion_contable('CUECOMP', v_gestion, v_id_concepto_ingas_recu, v_id_centro_costo,  'No se encontro relación contable para el conceto de gasto: '||v_registros_cig.desc_ingas||'. <br> Mensaje: ');
                 end if;
                /********************************************************************************************/
                if (v_origen_pedido not in ('Almacenes Consumibles o Rotables','Centro de Entrenamiento Aeronautico Civil')) then
                /*Aqui para recueprar la OT*/
                select sol.id_matricula into v_id_orden_trabajo_rec
                from mat.tsolicitud sol
                where sol.id_solicitud = v_id_solicitud;
                /********************************************************************************************/
                ELSE
                	select
                       det.id_orden_trabajo
                  into
                         v_id_orden_trabajo_rec
                  from mat.tdetalle_sol det
                  inner join mat.tsolicitud sol on sol.id_solicitud = det.id_solicitud
                  where sol.origen_pedido = v_origen_pedido
                  and trim(det.nro_parte) = trim(regexp_replace(v_registros.nro_parte,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&=_ ]+', '-','g'))
                  order by det.fecha_reg desc
                  limit 1;
                end if;
            else
            	v_id_concepto_ingas_recu = null;
                v_id_partida_recu = null;
                v_cuenta_recu = null;
                v_id_auxiliar_recu = null;
                v_id_orden_trabajo_rec = null;
            end if;



            /**********************************************************************************/

            insert into mat.tdetalle_sol(
			id_solicitud,
			descripcion,
			estado_reg,
			id_unidad_medida,
			nro_parte,
			referencia,
			nro_parte_alterno,
			cantidad_sol,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod,
            tipo,
            explicacion_detallada_part,
            id_centro_costo,

            /*Aumentando para insertar lo recuperado*/
            id_concepto_ingas,
            id_partida,
            id_cuenta,
            id_auxiliar,
            id_orden_trabajo
            /****************************************/


          	) values(
			v_id_solicitud,
			v_registros.descripcion,
			'activo',
			v_id_unidad_medida,
			v_registros.nro_parte,
			v_registros.referencia,
			v_registros.nro_parte_alterno,
			v_registros.cantidad_sol,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null,
            v_registros.tipo,
            v_registros.explicacion_detallada_part,
            v_id_centro_costo,
            /*Aumentando para insertar los datos recuperados*/
            v_id_concepto_ingas_recu,
            v_id_partida_recu,
            v_cuenta_recu,
            v_id_auxiliar_recu,
            v_id_orden_trabajo_rec
            /************************************************/
            )RETURNING id_detalle into v_id_detalle;

            end loop;
            /********************************/

             --modificar nro_parte, nro_parte_alterno en tsolicitud
            select list(ds.nro_parte)
            into v_nro_parte
            from mat.tdetalle_sol ds
            where  ds.id_solicitud= v_id_solicitud
            GROUP by ds.id_solicitud;

            select list(ds.nro_parte_alterno)
            into v_nro_parte_alterno
            from mat.tdetalle_sol ds
            where  ds.id_solicitud= v_id_solicitud
            GROUP by ds.id_solicitud;

             -- RAISE exception '%, %',v_nro_parte, v_nro_parte_alterno;

              update mat.tsolicitud
              set
              nro_partes = v_nro_parte,
              nro_parte_alterno =  v_nro_parte_alterno
              where
              mat.tsolicitud.id_solicitud = v_id_solicitud;

            /*Aumentando para que el registro pase al estado de revision (Ismael Valdivia 28/02/2020)*/
             --Recuperamos el id_tipo_proceso_wf para obtener el siguiente estado
                    select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                    from wf.tproceso_wf pr
                    where pr.id_proceso_wf = v_id_proceso_wf;

                    select es.id_tipo_estado,
                    	   es.codigo,
                           fun.id_funcionario
                    into v_id_tipo_estado_siguiente,
                         v_codigo_estado_siguiente_rpcd,
                         v_funcionario_encargado
                    from wf.ttipo_estado es
                    inner join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                    where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'revision';
                    ---------------------------------------------------------------------------

                     v_acceso_directo_automatico = '';
                     v_clase_automatico = '';
                     v_parametros_ad_automatico = '';
                     v_tipo_noti_automatico = 'notificacion';
                     v_titulo_automatico  = 'Visto Boa';
                     v_obs_automatico ='---';

                     ------------------------------------------pasamos el estado a revision
                     v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                             v_id_funcionario_solicitante,--id del funcionario Solicitante Jaime Lazarte (Definir de donde recuperaremos)
                                                             v_id_estado_wf,
                                                             v_id_proceso_wf,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             COALESCE(v_nro_tramite,'--')||' Obs:'||v_obs_automatico,
                                                             v_acceso_directo_automatico,
                                                             v_clase_automatico,
                                                             v_parametros_ad_automatico,
                                                             v_tipo_noti_automatico,
                                                             v_titulo_automatico);

                     IF mat.f_procesar_estados_solicitud(p_id_usuario,
           											v_parametros._id_usuario_ai,
                                            		v_parametros._nombre_usuario_ai,
                                            		v_id_estado_actual,
                                            		v_id_proceso_wf,
                                            		v_codigo_estado_siguiente_rpcd) THEN

         			RAISE NOTICE 'PASANDO DE ESTADO';
                    -------------------------------------------------------------------------------------------------------------------------------------

          	END IF;

            /*Aqui aumentamos esta condicion para recuperar el docuemtno y cargarlo al ERP*/
            IF(v_parametros.archivo != 'NULL' || v_parametros.archivo is not NULL)then

            	select  doc.id_tipo_documento into v_id_tipo_documento
                from wf.tproceso_macro mac
                inner join wf.ttipo_documento doc on doc.id_proceso_macro = mac.id_proceso_macro
                where mac.id_proceso_macro = v_id_proceso_macro and doc.nombre = 'IPC';

                select docwf.id_documento_wf into v_id_documento_wf
                from wf.tdocumento_wf docwf
                where docwf.id_proceso_wf = v_id_proceso_wf and docwf.id_tipo_documento = v_id_tipo_documento;

                /*Aqui insertamos*/
                select
                  max(dh.version)
                into
                  v_max_version
                from wf.tdocumento_historico_wf dh
                where dh.id_documento = v_id_documento_wf;

                -- optine la version maxima del historico
                select
                  dh.url,
                  dh.url_old,
                  dh.version,
                  dh.id_documento_historico_wf
                into
                  v_registros_his
                from wf.tdocumento_historico_wf dh
                where dh.id_documento = v_id_documento_wf and version = v_max_version;

                -- cambiamos el estado de las versiones anterior

                UPDATE wf.tdocumento_historico_wf  SET vigente = 'no'
                WHERE  id_documento = v_id_documento_wf;

                -- inserta registro en el historico con el numero de version  actual
                v_new_url = './../../../uploaded_files/sis_workflow/DocumentoWf/historico/'||v_parametros.archivo||'_v'||(COALESCE(v_max_version,0) + 1)::VARCHAR||'.'||v_parametros.extension;
        		--v_new_url = './../../../uploaded_files/sis_workflow/DocumentoWf/9628774d710a6b57af100cd432cb0744.pdf';
                INSERT INTO
                  wf.tdocumento_historico_wf
                  (
                    id_usuario_reg,
                    fecha_reg,
                    estado_reg,
                    id_usuario_ai,
                    usuario_ai,
                    id_documento,
                    url_old,
                    url,
                    extension,
                    version,
                    vigente
                  )
                VALUES (
                  p_id_usuario,
                  now(),
                  'activo',
                  v_parametros._id_usuario_ai,
                  v_parametros._nombre_usuario_ai,
                  v_id_documento_wf,
                  v_parametros.ruta,
                  v_new_url,
                  v_parametros.extension,
                  --'pdf',
                  COALESCE(v_max_version,0) +1,
                  'si')RETURNING id_documento_historico_wf into v_id_documento_historico_wf;

                -- raise exception '--- %',COALESCE(v_max_version,0);
                 --actualiza el archivo
                update wf.tdocumento_wf set
                 -- archivo=v_parametros.archivo,
                  extension=v_parametros.extension,
                  chequeado = 'si',
                  url = v_parametros.ruta,
                  fecha_mod = now(),
                  id_usuario_mod = p_id_usuario,
                  fecha_upload = now(),
                  id_usuario_upload = p_id_usuario
                where id_documento_wf = v_id_documento_wf;
                /*****************/
           end if;

            /******************************************************************************/



            /*****************************************************************************************/



			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud almacenado(a) con exito (id_solicitud'||v_id_solicitud||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_id_solicitud::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'nro_tramite',v_nro_tramite::varchar);


            --Devuelve la respuesta
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

COMMENT ON FUNCTION mat.ft_solicitud_mantenimiento_ime(p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
IS 'Funcion para insertar solicitudes desde el servicio Mantenimiento ';

ALTER FUNCTION mat.ft_solicitud_mantenimiento_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
