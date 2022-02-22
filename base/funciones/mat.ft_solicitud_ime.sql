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

	v_mat_comprometer_presupuesto varchar;
    v_llave_aprobado	record;

    /*Aumentando variables (Ismael Valdivia 19/02/2020)*/
    v_existe_proveedor_adjudicado integer;
    v_estado_firma_actual	varchar;
    v_datos_incompletos	integer;
    v_monto_adjudicado	numeric;
    v_monto_referencial	numeric;


    v_estado_actual	varchar;
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

    v_codigo_poa		varchar;
    v_id_funcionario_solicitante integer;
    v_filadd varchar;
    v_inner  varchar;
    v_contador		integer;
    v_datos_recuperados	record;

    v_estado_wf			varchar;
    v_record_gestion	record;
    v_frd varchar;

    v_id_proceso_wf_normal	integer;
    v_id_proceso_wf_firma	integer;
    v_valor_revision		varchar;
    v_id_po_alkym			integer;

    v_id_tipo_proceso_docu  integer;
    v_id_tipo_estado_documento  integer;
    v_datos_documento       record;
    v_chekeado              varchar;
    v_nombre_documento		varchar;
    v_nro_po				varchar;
    v_estado_tramite		varchar;
    v_id_centro_costo		integer;


    v_IdAvion  					integer;
    v_IdTipoAvion  			  integer;
    v_MatriculaPn  			  varchar;
    v_Modelo 					varchar;
    v_NroVariable 			   varchar;
    v_IdComponent  		    integer;

    v_NombreAvion			varchar;
    v_TipoAvion					varchar;
    v_CodigoTipoAvion		varchar;

    v_id_orden_trabajo		integer;
    v_registros_update		record;
    v_origen_pedido			varchar;
    v_solicitud_actual		record;
    v_id_funcionario_tecnico_auxiliar	integer;
    /*Variables para asignar al funcionario de Manera Automatica*/
    v_ultimo_funcionario_asignado	integer;
    v_id_tipo_estado_asignacion		integer;
    v_id_funcionario_asignacion		integer;
    v_id_funcionario_tipo_estado_asignacion integer;
    v_funcionario_encargado_rpcd	integer;
    v_id_funcionario_tipo_estado	integer;
    v_id_asignacion					integer;
    v_existe_todos					integer;
    v_condicion						varchar;
    /***************************************************/

    /*Agregando para el jefe de departamento*/
    v_id_tipo_estado_siguiente_jefe	integer;
    v_codigo_estado_siguiente_auto_jefe varchar;
    v_funcionario_encargado_jefe	integer;
    v_id_matricula					integer;
    v_nro_po_alkym					varchar;
    v_existe_acta					integer;
    v_id_funcionario_actual_estado	integer;
    v_existe_referencia				numeric;
    v_verificar_relacion			record;
    v_proveedor_hazmat				varchar;
    v_presu_comprometido			varchar;
    /****************************************/

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


            elsif v_parametros.origen_pedido ='Centro de Entrenamiento Aeronautico Civil'then
           	select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GC-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;


           elsif v_parametros.origen_pedido ='Reparación de Repuestos'then
           	select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GR-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;
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

		--raise exception 'llega %',v_parametros.id_matricula;
		--Recuperara estado Abastecimientos

        /*Aumentando para recuperar al funcionario solicitante
            El funcionario solicitante esta creado en una variable global
            por el momento el funcionario solicitante es JAIME LAZARTE
            el id funcionario es (370).
            Si el funcionario solicitante es otro cambiar en la variable global*/
            --v_id_funcionario_solicitante = pxp.f_get_variable_global('funcionario_solicitante_gm');        	/**********************************************************************/



		/*Aqui recuperamos el codigo_poa (Ismael Valdivia 10/03/2020)*/
        v_codigo_poa = pxp.f_get_variable_global('codigo_poa');
        /*************************************************************/
        --raise exception 'llega datos aqui %',v_parametros.id_depto;

            --Sentencia de la insercion
        	insert into mat.tsolicitud(
			id_funcionario_sol,
			id_proceso_wf,
			id_estado_wf,
			nro_po,
            fecha_po,
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
            /*Aumentando este campo (Ismael Valdivia 31/01/2020)*/
            id_depto,
            id_moneda,
            presu_comprometido,
            presupuesto_aprobado,
            --id_funcionario_solicitante,
            codigo_poa,
            nro_lote,
            id_forma_pago_alkym,
            id_condicion_entrega_alkym,
            codigo_forma_pago_alkym,
            codigo_condicion_entrega_alkym,
            origen_solicitud,
            mel_observacion,
            tiempo_entrega_estimado
            /***********************/
          	) values(
			v_parametros.id_funcionario_sol,
			v_id_proceso_wf,
			v_id_estado_wf,
			v_parametros.nro_po,
            null,
			v_parametros.tipo_solicitud,
			v_parametros.origen_pedido,
			v_parametros.fecha_requerida,
			v_parametros.fecha_solicitud,
			'activo',
			v_parametros.observaciones_sol,
			v_parametros.justificacion,
			v_parametros.tipo_falla,
			v_nro_tramite,
			v_parametros.id_matricula,
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
            /*Aumentando este campo (Ismael Valdivia 31/01/2020)*/
            v_parametros.id_depto,
            2,
            'no',
            'verificar',
            --v_parametros.id_funcionario_solicitante,--v_id_funcionario_solicitante,
            v_codigo_poa,
            v_parametros.nro_lote,
            v_parametros.id_forma_pago,
            v_parametros.id_condicion_entrega,
            v_parametros.codigo_forma_pago,
            v_parametros.codigo_condicion_entrega,
            'ERP',
            v_parametros.mel_observacion,
            v_parametros.dias_entrega_estimado
            /****************************************************/
            )RETURNING id_solicitud into v_id_solicitud;

            select g.id_gestion
           	 		into v_id_gestion
           			from param.tgestion g
           			where g.gestion = EXTRACT(YEAR FROM current_date);

            update mat.tsolicitud set
         	id_gestion = v_id_gestion
        	where id_solicitud = v_id_solicitud;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud almacenado(a) con exito (id_solicitud'||v_id_solicitud||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_id_solicitud::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_proceso_wf',v_id_proceso_wf::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_estado_wf',v_id_estado_wf::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'estado',v_codigo_estado::varchar);
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

        /*Aqui si contiene el tipo todos*/
        WITH condiciones_proveedor as (SELECT UNNEST(REGEXP_SPLIT_TO_ARRAY(v_parametros.condicion, ',','')) as nombre)
        select Count (nombre) into v_existe_todos
        from condiciones_proveedor
        where nombre = 'TODOS';

        if (v_existe_todos > 0) then
        	select
            	list(cat.descripcion)||',OTROS' into v_condicion
            from param.tcatalogo cat
            inner join param.tcatalogo_tipo cattip on cattip.id_catalogo_tipo = cat.id_catalogo_tipo
            inner join segu.tsubsistema subsis on subsis.id_subsistema = cattip.id_subsistema
            where subsis.codigo = 'MAT' and cattip.nombre = 'tsolicitud'
            and cat.codigo not in ('B.E.R.','AS-IS');
        else
        	v_condicion = v_parametros.condicion;
        end if;
        /********************************/



        select s.estado
        into v_est
        from mat.tsolicitud s
        WHERE s.id_solicitud = v_parametros.id_solicitud;

        if ( (pxp.f_existe_parametro(p_tabla,'id_depto')) ) then
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
			usuario_ai = v_parametros._nombre_usuario_ai,
			fecha_po = v_parametros.fecha_po,
            condicion = v_condicion,
            lugar_entrega = v_parametros.lugar_entrega,
            tipo_evaluacion = v_parametros.tipo_evaluacion,
        	taller_asignado = v_parametros.taller_asignado,
            mensaje_correo = v_parametros.mensaje_correo,
            /*Aumentando este campo (Ismael Valdivia 31/01/2020)*/
            id_depto = v_parametros.id_depto,
            nro_lote = v_parametros.nro_lote,
            id_forma_pago_alkym = v_parametros.id_forma_pago,
            id_condicion_entrega_alkym = v_parametros.id_condicion_entrega,
            codigo_forma_pago_alkym = v_parametros.codigo_forma_pago,
            codigo_condicion_entrega_alkym = v_parametros.codigo_condicion_entrega,
            fecha_entrega = v_parametros.fecha_entrega,
            mel_observacion = v_parametros.mel_observacion,
            tiempo_entrega = v_parametros.tiempo_entrega,
            /*****************************************************/
            /*Aumentando campos (Ismael Valdivia 06/10/2021)*/
            metodo_de_adjudicación = v_parametros.metodo_de_adjudicación,
            tipo_de_adjudicacion = v_parametros.tipo_de_adjudicacion,
            remark = v_parametros.remark
            /************************************************/
        	where id_solicitud=v_parametros.id_solicitud;
        ELSE
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
			usuario_ai = v_parametros._nombre_usuario_ai,
			fecha_po = v_parametros.fecha_po,
            condicion = v_condicion,
            lugar_entrega = v_parametros.lugar_entrega,
            tipo_evaluacion = v_parametros.tipo_evaluacion,
        	taller_asignado = v_parametros.taller_asignado,
            mensaje_correo = v_parametros.mensaje_correo,
            id_moneda = 2,
            nro_lote = v_parametros.nro_lote,
            id_forma_pago_alkym = v_parametros.id_forma_pago,
            id_condicion_entrega_alkym = v_parametros.id_condicion_entrega,
            codigo_forma_pago_alkym = v_parametros.codigo_forma_pago,
            codigo_condicion_entrega_alkym = v_parametros.codigo_condicion_entrega,
            fecha_entrega = v_parametros.fecha_entrega,
            mel_observacion = v_parametros.mel_observacion,
            tiempo_entrega = v_parametros.tiempo_entrega,
            /*Aumentando campos (Ismael Valdivia 06/10/2021)*/
            metodo_de_adjudicación = v_parametros.metodo_de_adjudicación,
            tipo_de_adjudicacion = v_parametros.tipo_de_adjudicacion,
            remark = v_parametros.remark
            /************************************************/
        	where id_solicitud=v_parametros.id_solicitud;
        end if;



         --para insertar monto_pac en tsolicitud_pac
         select so.id_proceso_wf
         into v_id_proceso_wf_so
         from mat.tsolicitud so
         where so.id_solicitud = v_parametros.id_solicitud;


   --RAISE exception '%',v_id_proceso_wf_so;
           if(select 1
              from mat.tsolicitud_pac
              where id_proceso_wf = v_id_proceso_wf_so)then

              update mat.tsolicitud_pac set
                  monto = v_parametros.monto_pac,
                  observaciones = v_parametros.obs_pac
              where id_proceso_wf = v_id_proceso_wf_so;

           ELSE
              INSERT INTO mat.tsolicitud_pac(
                id_proceso_wf,
                monto,
                observaciones
              )
              VALUES (
                v_id_proceso_wf_so,
                v_parametros.monto_pac,
                v_parametros.obs_pac
              );
            END IF;
     -----
     		/*Aumentando para registrar los proveedores directamente de la interfaz*/
            if (v_parametros.lista_correos = '0') then
                select pxp.list (p.id_proveedor::text)
                into
                v_id_proveedores
                from param.vproveedor p
                where p.tipo = 'abastecimiento' and  p.email <> '';

                v_ids_prov = string_to_array(v_id_proveedores,',');
                v_tam = array_length(v_ids_prov,1);

            else

                v_ids_prov = string_to_array(v_parametros.lista_correos,',');
                v_tam = array_length(v_ids_prov,1);

            end if;

            SELECT pxp.aggarray(tgp.id_proveedor)
            INTO v_ids_prov_act
            FROM mat.tgestion_proveedores_new tgp
            WHERE tgp.id_solicitud = v_parametros.id_solicitud;


            IF (v_tam IS NOT NULL AND v_ids_prov_act IS NULL)THEN
			v_i = 1;
           	while v_i <= v_tam loop
                    INSERT INTO mat.tgestion_proveedores_new(
                      id_usuario_reg,
                      id_usuario_mod,
                      fecha_reg,
                      fecha_mod,
                      estado_reg,
                      id_usuario_ai,
                      usuario_ai,
  					  id_solicitud,
  					  id_proveedor

                    )VALUES(
                      p_id_usuario,
                      null,
                      now(),
                      null,
                      'activo',
                      v_parametros._id_usuario_ai,
                      v_parametros._nombre_usuario_ai,
                      v_parametros.id_solicitud,
                   	  v_ids_prov[v_i]::integer);
            	--Definicion de la respuesta
                v_i = v_i + 1;
            	v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Lista de correos fue definido Exitosamente');

            end loop;

            ELSIF(v_ids_prov_act <> v_ids_prov)THEN

            delete from mat.tgestion_proveedores_new
            where id_solicitud=v_parametros.id_solicitud;

            v_i = 1;
           	while v_i <= v_tam loop
                    INSERT INTO mat.tgestion_proveedores_new(
                      id_usuario_reg,
                      id_usuario_mod,
                      fecha_reg,
                      fecha_mod,
                      estado_reg,
                      id_usuario_ai,
                      usuario_ai,
  					  id_solicitud,
  					  id_proveedor

                    )VALUES(
                      p_id_usuario,
                      null,
                      now(),
                      null,
                      'activo',
                      v_parametros._id_usuario_ai,
                      v_parametros._nombre_usuario_ai,
                      v_parametros.id_solicitud,
                   	  v_ids_prov[v_i]::integer);
            	--Definicion de la respuesta
            v_i = v_i + 1;
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Lista de correos fue modificado Exitosamente');
            end loop;
            END IF;
            /***********************************************************************/


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
                ma.nro_tramite,
                ma.presu_comprometido
                into
                v_id_proceso_wf,
                v_id_estado_wf,
        		v_codigo_estado,
                v_id_solicitud,
                v_codigo,
                v_presu_comprometido
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

        /*Revertimos presupuestos*/
         IF v_presu_comprometido = 'si' THEN
         	-- Comprometer Presupuesto
                IF not mat.f_gestionar_presupuesto_solicitud(v_parametros.id_solicitud, p_id_usuario, 'revertir')  THEN
                     raise exception 'Error al comprometer el presupuesto';
                END IF;

                --modifca bandera de comprometido
                 update mat.tsolicitud  set
                      presu_comprometido =  'no'
                 where id_solicitud = v_parametros.id_solicitud;
         END IF;
        /*************************/

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


           /*Aumentando para retroceder cuando este en estado Compra*/
           if (v_registros_mat.estado = 'compra') then

           		select sol.*
                      into v_solicitud
                from mat.tsolicitud sol
                where id_proceso_wf = v_parametros.id_proceso_wf;

           	/*Retrocedemos al comite de Abastecimiento el estado del tramite*/
               select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                        from wf.tproceso_wf pr
                        where pr.id_proceso_wf = v_solicitud.id_proceso_wf;

                select es.id_tipo_estado,
                       es.codigo,
                       fun.id_funcionario
                into v_id_tipo_estado_siguiente,
                     v_codigo_estado_siguiente_auto,
                     v_funcionario_encargado
                from wf.ttipo_estado es
                LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'comite_unidad_abastecimientos';

               v_acceso_directo_automatico = '';
               v_clase_automatico = '';
               v_parametros_ad_automatico = '';
               v_tipo_noti_automatico = 'notificacion';
               v_titulo_automatico  = 'Visto Boa';
               v_obs_automatico ='Desde el estado Compra';
               ------------------------------------------pasamos el estado a vb_dpto_administrativo


               v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                       v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
                                                       v_solicitud.id_estado_wf,
                                                       v_solicitud.id_proceso_wf,
                                                       p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_depto,
                                                       '[RETROCESO] '||v_parametros.obs,
                                                       v_acceso_directo_automatico,
                                                       v_clase_automatico,
                                                       v_parametros_ad_automatico,
                                                       v_tipo_noti_automatico,
                                                       v_titulo_automatico);

                IF  not mat.f_ant_estado_solicitud_wf(p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_solicitud.id_proceso_wf,
                                                       v_codigo_estado_siguiente_auto) THEN

                raise exception 'Error al retroceder estado';

                END IF;



                /*Aqui retrocedemos lo que es la firma de Aeronavegabilidad*/
                select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                        from wf.tproceso_wf pr
                        where pr.id_proceso_wf = v_solicitud.id_proceso_wf_firma;

                select es.id_tipo_estado,
                       es.codigo,
                       fun.id_funcionario
                into v_id_tipo_estado_siguiente,
                     v_codigo_estado_siguiente_auto,
                     v_funcionario_encargado
                from wf.ttipo_estado es
                LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'comite_aeronavegabilidad';

               v_acceso_directo_automatico = '';
               v_clase_automatico = '';
               v_parametros_ad_automatico = '';
               v_tipo_noti_automatico = 'notificacion';
               v_titulo_automatico  = 'Visto Boa';
               v_obs_automatico ='Desde el estado Compra';
               ------------------------------------------pasamos el estado a vb_dpto_administrativo

               v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                       v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
                                                       v_solicitud.id_estado_wf_firma,
                                                       v_solicitud.id_proceso_wf_firma,
                                                       p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_depto,
                                                       '[RETROCESO] '||v_parametros.obs,
                                                       v_acceso_directo_automatico,
                                                       v_clase_automatico,
                                                       v_parametros_ad_automatico,
                                                       v_tipo_noti_automatico,
                                                       v_titulo_automatico);

                update mat.tsolicitud  set
                       id_estado_wf_firma =  v_id_estado_actual,
                       estado_firma = v_codigo_estado_siguiente_auto,
                       id_usuario_mod = p_id_usuario,
                       id_usuario_ai = v_parametros._id_usuario_ai,
                       usuario_ai = v_parametros._nombre_usuario_ai,
                       fecha_mod = now()
                where id_proceso_wf_firma = v_solicitud.id_proceso_wf_firma;
                /***********************************************************/

            elsif (v_registros_mat.estado = 'comite_unidad_abastecimientos') then

           		select sol.*
                      into v_solicitud
                from mat.tsolicitud sol
                where id_proceso_wf = v_parametros.id_proceso_wf;

           	/*Retrocedemos al comite de Abastecimiento el estado del tramite*/
               select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                        from wf.tproceso_wf pr
                        where pr.id_proceso_wf = v_solicitud.id_proceso_wf;

                select es.id_tipo_estado,
                       es.codigo,
                       fun.id_funcionario
                into v_id_tipo_estado_siguiente,
                     v_codigo_estado_siguiente_auto,
                     v_funcionario_encargado
                from wf.ttipo_estado es
                LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'cotizacion_solicitada';

               v_acceso_directo_automatico = '';
               v_clase_automatico = '';
               v_parametros_ad_automatico = '';
               v_tipo_noti_automatico = 'notificacion';
               v_titulo_automatico  = 'Visto Boa';
               v_obs_automatico ='Desde el estado Compra';
               ------------------------------------------pasamos el estado a vb_dpto_administrativo


               v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                       v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
                                                       v_solicitud.id_estado_wf,
                                                       v_solicitud.id_proceso_wf,
                                                       p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_depto,
                                                       '[RETROCESO] '||v_parametros.obs,
                                                       v_acceso_directo_automatico,
                                                       v_clase_automatico,
                                                       v_parametros_ad_automatico,
                                                       v_tipo_noti_automatico,
                                                       v_titulo_automatico);

                IF  not mat.f_ant_estado_solicitud_wf(p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_solicitud.id_proceso_wf,
                                                       v_codigo_estado_siguiente_auto) THEN

                raise exception 'Error al retroceder estado';

                END IF;


                /*Aqui retrocedemos lo que es la firma de Aeronavegabilidad*/
                select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                        from wf.tproceso_wf pr
                        where pr.id_proceso_wf = v_solicitud.id_proceso_wf_firma;

                select es.id_tipo_estado,
                       es.codigo,
                       fun.id_funcionario
                into v_id_tipo_estado_siguiente,
                     v_codigo_estado_siguiente_auto,
                     v_funcionario_encargado
                from wf.ttipo_estado es
                LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'rechazado';

               v_acceso_directo_automatico = '';
               v_clase_automatico = '';
               v_parametros_ad_automatico = '';
               v_tipo_noti_automatico = 'notificacion';
               v_titulo_automatico  = 'Visto Boa';
               v_obs_automatico ='Desde el estado Compra';
               ------------------------------------------pasamos el estado a vb_dpto_administrativo

               v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                       v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
                                                       v_solicitud.id_estado_wf_firma,
                                                       v_solicitud.id_proceso_wf_firma,
                                                       p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_depto,
                                                       '[RETROCESO] '||v_parametros.obs,
                                                       v_acceso_directo_automatico,
                                                       v_clase_automatico,
                                                       v_parametros_ad_automatico,
                                                       v_tipo_noti_automatico,
                                                       v_titulo_automatico);

                update mat.tsolicitud  set
                       id_estado_wf_firma =  v_id_estado_actual,
                       estado_firma = v_codigo_estado_siguiente_auto,
                       id_usuario_mod = p_id_usuario,
                       id_usuario_ai = v_parametros._id_usuario_ai,
                       usuario_ai = v_parametros._nombre_usuario_ai,
                       fecha_mod = now()
                where id_proceso_wf_firma = v_solicitud.id_proceso_wf_firma;
                /***********************************************************/

           else

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

                 if (v_codigo_estado = 'revision') then
                   select te.id_funcionario into v_id_funcionario_actual_estado
                   from wf.tfuncionario_tipo_estado te
                   where te.id_tipo_estado = v_id_tipo_estado;
                 else
                 	v_id_funcionario_actual_estado = v_id_funcionario;
				 end if;



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
                  v_id_funcionario_actual_estado,                --  funcionario del estado anterior
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


           end if;
           /*********************************************************/

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
                    sol.id_funcionario_sol,
        			pwf.id_tipo_proceso
                     into v_registros_mat
 					from mat.tsolicitud sol
 					inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf
 					where sol.id_proceso_wf =  v_parametros.id_proceso_wf;
                    v_id_proceso_wf = v_registros_mat.id_proceso_wf;

           /*Aqui para retroceder el estado de la firma en paralelo*/
            /*Aumentando para retroceder cuando este en estado Compra*/
           if (v_registros_mat.estado not in ('revision','cotizacion','cotizacion_solicitada')) then

           		select sol.*
                      into v_solicitud
                from mat.tsolicitud sol
                where id_proceso_wf = v_parametros.id_proceso_wf;

           	/*Retrocedemos al comite de Abastecimiento el estado del tramite*/

               if (v_solicitud.id_proceso_wf_firma is not null and v_solicitud.estado_firma != 'comite_aeronavegabilidad') then

                  /*Aqui retrocedemos lo que es la firma de Aeronavegabilidad*/
                  select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                          from wf.tproceso_wf pr
                          where pr.id_proceso_wf = v_solicitud.id_proceso_wf_firma;

                  select es.id_tipo_estado,
                         es.codigo,
                         fun.id_funcionario
                  into v_id_tipo_estado_siguiente,
                       v_codigo_estado_siguiente_auto,
                       v_funcionario_encargado
                  from wf.ttipo_estado es
                  LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                  where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'comite_aeronavegabilidad';

                 v_acceso_directo_automatico = '';
                 v_clase_automatico = '';
                 v_parametros_ad_automatico = '';
                 v_tipo_noti_automatico = 'notificacion';
                 v_titulo_automatico  = 'Visto Boa';
                 v_obs_automatico ='Desde el estado Compra';
                 ------------------------------------------pasamos el estado a vb_dpto_administrativo

                 v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                         v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
                                                         v_solicitud.id_estado_wf_firma,
                                                         v_solicitud.id_proceso_wf_firma,
                                                         p_id_usuario,
                                                         v_parametros._id_usuario_ai,
                                                         v_parametros._nombre_usuario_ai,
                                                         v_id_depto,
                                                         '[RETROCESO] '||v_parametros.obs,
                                                         v_acceso_directo_automatico,
                                                         v_clase_automatico,
                                                         v_parametros_ad_automatico,
                                                         v_tipo_noti_automatico,
                                                         v_titulo_automatico);

                  update mat.tsolicitud  set
                         id_estado_wf_firma =  v_id_estado_actual,
                         estado_firma = v_codigo_estado_siguiente_auto,
                         id_usuario_mod = p_id_usuario,
                         id_usuario_ai = v_parametros._id_usuario_ai,
                         usuario_ai = v_parametros._nombre_usuario_ai,
                         fecha_mod = now()
                  where id_proceso_wf_firma = v_solicitud.id_proceso_wf_firma;
                  /***********************************************************/
                end if;
            end if;
           /********************************************************/




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
                  v_registros_mat.id_funcionario_sol,                --  funcionario del estado anterior
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

        /*Aqui armaremos una condicion para que cuando este en el comite directamente pase el estado automaticamente*/
        select sol.*
              into v_solicitud
        from mat.tsolicitud sol
        where id_proceso_wf = v_parametros.id_proceso_wf_act;


        if (v_solicitud.estado = 'comite_unidad_abastecimientos') then
        		/*Aumentando para que el registro pase los estados de aeronavegabilidad y rpc automatico (Ismael Valdivia 20/09/2020)*/
             	--Recuperamos el id_tipo_proceso_wf para obtener el siguiente estado
                    select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                    from wf.tproceso_wf pr
                    where pr.id_proceso_wf = v_solicitud.id_proceso_wf;

                    select es.id_tipo_estado,
                    	   es.codigo,
                           fun.id_funcionario
                    into v_id_tipo_estado_siguiente,
                         v_codigo_estado_siguiente_auto,
                         v_funcionario_encargado
                    from wf.ttipo_estado es
                    LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                    where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'autorizado';
                    ---------------------------------------------------------------------------

                      ---------------------------------------------------------------------------

                     v_acceso_directo_automatico = '';
                     v_clase_automatico = '';
                     v_parametros_ad_automatico = '';
                     v_tipo_noti_automatico = 'notificacion';
                     v_titulo_automatico  = 'Visto Boa';
                     v_obs_automatico ='---';
                     ------------------------------------------pasamos el estado a vb_dpto_administrativo


                     v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                             v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
                                                             v_solicitud.id_estado_wf,
                                                             v_solicitud.id_proceso_wf,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             COALESCE(v_solicitud.nro_tramite,'--')||' Obs:'||v_obs_automatico,
                                                             v_acceso_directo_automatico,
                                                             v_clase_automatico,
                                                             v_parametros_ad_automatico,
                                                             v_tipo_noti_automatico,
                                                             v_titulo_automatico);

                     IF mat.f_procesar_estados_solicitud(p_id_usuario,
           											v_parametros._id_usuario_ai,
                                            		v_parametros._nombre_usuario_ai,
                                            		v_id_estado_actual,
                                            		v_solicitud.id_proceso_wf,
                                            		v_codigo_estado_siguiente_auto) THEN

                    RAISE NOTICE 'PASANDO DE ESTADO';
                    end if;
                    -------------------------------------------------------------------------------------------------------------------------------------

                    /*Pasamos el estado de la Firma Luis Melean*/
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
                        end if;


                    /*******************************************/
                    /*Pasa al estado RPCD*/
                    --Recuperamos los datos de la solicitud
                	select sol.* into v_datos_solicitud
                    from mat.tsolicitud sol
                    where id_solicitud = v_parametros.id_solicitud;
                    ------------------------------------------------

                    --Recuperamos el id_tipo_proceso_wf para obtener el siguiente estado
                    select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                    from wf.tproceso_wf pr
                    where pr.id_proceso_wf = v_datos_solicitud.id_proceso_wf;

                    /*Aqui para la condicion de recuperar automaticamente al jefe de Departamento (Ismael Valdivia 29/10/2021)*/
                    select es.id_tipo_estado,
                    	   es.codigo,
                           fun.id_funcionario
                    into v_id_tipo_estado_siguiente_jefe,
                         v_codigo_estado_siguiente_auto_jefe,
                         v_funcionario_encargado_jefe
                    from wf.ttipo_estado es
                    inner join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                    where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'vb_dpto_abastecimientos';
                    /**********************************************************************************************************/


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

					if (v_datos_solicitud.fecha_solicitud < '01/01/2022') then
                      if (v_funcionario_encargado_jefe != v_funcionario_encargado) then
                          ------------------------------------Pasa el estado del Jefe de departamento-----------------
                          v_acceso_directo_automatico = '';
                           v_clase_automatico = '';
                           v_parametros_ad_automatico = '';
                           v_tipo_noti_automatico = 'notificacion';
                           v_titulo_automatico  = 'Visto Boa';
                           v_obs_automatico ='---';

                           v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente_jefe,--id del estado siguiente revision
                                                                   v_funcionario_encargado_jefe,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
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
                                                          v_codigo_estado_siguiente_auto_jefe) THEN

                                      RAISE NOTICE 'PASANDO DE ESTADO';
                                      -------------------------------------------------------------------------------------------------------------------------------------

                           END IF;



                           ----------------------------------Autoriza el Jefe de Departamento-----------------------------------------------------------------


                          --Recuperamos los datos de la solicitud
                          select sol.* into v_datos_solicitud
                          from mat.tsolicitud sol
                          where id_solicitud = v_parametros.id_solicitud;
                          ------------------------------------------------

                          select es.id_tipo_estado,
                                 es.codigo,
                                 fun.id_funcionario
                          into v_id_tipo_estado_siguiente,
                               v_codigo_estado_siguiente_auto,
                               v_funcionario_encargado
                          from wf.ttipo_estado es
                          LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                          where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'autorizado_jefe_depto';
                          ---------------------------------------------------------------------------


                            ---------------------------------------------------------------------------
                          --raise exception 'Aqui llega %',v_codigo_estado_siguiente_auto;
                           v_acceso_directo_automatico = '';
                           v_clase_automatico = '';
                           v_parametros_ad_automatico = '';
                           v_tipo_noti_automatico = 'notificacion';
                           v_titulo_automatico  = 'Visto Boa';
                           v_obs_automatico ='---';
                           ------------------------------------------pasamos el estado a vb_dpto_administrativo


                           v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                                   v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
                                                                   v_datos_solicitud.id_estado_wf,
                                                                   v_datos_solicitud.id_proceso_wf,
                                                                   p_id_usuario,
                                                                   v_parametros._id_usuario_ai,
                                                                   v_parametros._nombre_usuario_ai,
                                                                   v_id_depto,
                                                                   COALESCE(v_solicitud.nro_tramite,'--')||' Obs:'||v_obs_automatico,
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
                      end if;
                    end if;
                    -------------------------------------------------------------------------------------------------------------------------------------

                    ------------------------------------Pasa el estado del RPC-----------------------------------
                    select sol.* into v_datos_solicitud
                    from mat.tsolicitud sol
                    where id_solicitud = v_parametros.id_solicitud;

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


                     v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                             v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
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



                    else

                    	v_acceso_directo_automatico = '';
                     v_clase_automatico = '';
                     v_parametros_ad_automatico = '';
                     v_tipo_noti_automatico = 'notificacion';
                     v_titulo_automatico  = 'Visto Boa';
                     v_obs_automatico ='---';
                     ------------------------------------------pasamos el estado a vb_dpto_administrativo


                     v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                             v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
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

                    end if;









                    /*Pasamos al Estado de compra automaticamente*/
                    select sol.* into v_datos_solicitud
                    from mat.tsolicitud sol
                    where id_solicitud = v_parametros.id_solicitud;

                    select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                    from wf.tproceso_wf pr
                    where pr.id_proceso_wf = v_datos_solicitud.id_proceso_wf;

                    select sol.id_proceso_wf into v_id_proceso_wf
                    from mat.tsolicitud sol
                    where sol.id_solicitud = v_datos_solicitud.id_solicitud;


                  /*Aumentando la condicion para que en las compras de repuestos se le asigne al funcionario de cotizacion*/
                  if (v_datos_solicitud.origen_pedido != 'Reparación de Repuestos') then
                  		SELECT		twf.id_funcionario

                                    into
                                                v_funcionario_encargado

                        FROM wf.testado_wf twf
                            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                        WHERE twf.id_proceso_wf = v_datos_solicitud.id_proceso_wf
                              AND  te.codigo = 'cotizacion'
                              AND twf.fecha_reg between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion, now())
                        GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                        order by twf.fecha_reg desc
                        limit 1;


                        select es.id_tipo_estado,
                               es.codigo
                        into v_id_tipo_estado_siguiente,
                             v_codigo_estado_siguiente_auto
                        from wf.ttipo_estado es
                        LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                        where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'compra';
				  else
                  		select es.id_tipo_estado,
                                   es.codigo
                            into v_id_tipo_estado_siguiente,
                                 v_codigo_estado_siguiente_auto
                            from wf.ttipo_estado es
                            LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                            where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'compra';

                            /*Aqui para asignar el usuario*/
                            select auto.id_funcionario_asignado,
                                   auto.id_asignacion
                                   into
                                   v_ultimo_funcionario_asignado,
                                   v_id_asignacion
                            from mat.tasginacion_automatica_abastecimiento auto
                            where auto.ultima_asignacion = 'si'
                            and auto.id_tipo_estado = v_id_tipo_estado_siguiente;


                            if (v_ultimo_funcionario_asignado is null) then

                            select te.id_tipo_estado,
                                   te.id_funcionario,
                                   te.id_funcionario_tipo_estado
                                   into
                                   v_id_tipo_estado_asignacion,
                                   v_funcionario_encargado,
                                   v_id_funcionario_tipo_estado_asignacion
                            from wf.tfuncionario_tipo_estado te
                            INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = te.id_funcionario
                            where te.id_tipo_estado = v_id_tipo_estado_siguiente
                            and te.estado_reg = 'activo'
                            and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion)
                            order by te.id_funcionario_tipo_estado ASC
                            limit 1;



                            /*Insertamos en la tabla de asignacion*/
                            insert into mat.tasginacion_automatica_abastecimiento (id_usuario_reg,
                                                                                   fecha_reg,
                                                                                   id_solicitud,
                                                                                   nro_tramite,
                                                                                   id_funcionario_asignado,
                                                                                   id_tipo_estado,
                                                                                   ultima_asignacion
                                                                                  )
                                                                           values(p_id_usuario,
                                                                                  now(),
                                                                                  v_datos_solicitud.id_solicitud,
                                                                                  v_datos_solicitud.nro_tramite,
                                                                                  v_funcionario_encargado,
                                                                                  v_id_tipo_estado_asignacion,
                                                                                  'si'
                                                                           );


                            else

                            select te.id_funcionario_tipo_estado into v_id_funcionario_tipo_estado
                            from wf.tfuncionario_tipo_estado te
                            where te.id_funcionario = v_ultimo_funcionario_asignado
                            and te.estado_reg = 'activo'
                            and te.id_tipo_estado = v_id_tipo_estado_siguiente;


                            select te.id_tipo_estado,
                                   te.id_funcionario,
                                   te.id_funcionario_tipo_estado
                                   into
                                   v_id_tipo_estado_asignacion,
                                   v_id_funcionario_asignacion,
                                   v_id_funcionario_tipo_estado_asignacion
                            from wf.tfuncionario_tipo_estado te
                            INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = te.id_funcionario
                            where te.id_tipo_estado = v_id_tipo_estado_siguiente
                            and te.estado_reg = 'activo'
                            and te.id_funcionario not in (v_ultimo_funcionario_asignado)
                            and te.id_funcionario_tipo_estado >= v_id_funcionario_tipo_estado
                            and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion)
                            order by te.id_funcionario_tipo_estado ASC
                            limit 1;

                              if (v_id_funcionario_tipo_estado_asignacion is not null) then
                                    v_funcionario_encargado = v_id_funcionario_asignacion;
                                    insert into mat.tasginacion_automatica_abastecimiento (id_usuario_reg,
                                                                           fecha_reg,
                                                                           id_solicitud,
                                                                           nro_tramite,
                                                                           id_funcionario_asignado,
                                                                           id_tipo_estado,
                                                                           ultima_asignacion
                                                                          )
                                                                   values(p_id_usuario,
                                                                          now(),
                                                                          v_datos_solicitud.id_solicitud,
                                                                          v_datos_solicitud.nro_tramite,
                                                                          v_id_funcionario_asignacion,
                                                                          v_id_tipo_estado_asignacion,
                                                                          'si'
                                                                   );
                               else

                                  select te.id_tipo_estado,
                                       te.id_funcionario,
                                       te.id_funcionario_tipo_estado
                                       into
                                       v_id_tipo_estado_asignacion,
                                       v_funcionario_encargado,
                                       v_id_funcionario_tipo_estado_asignacion
                                from wf.tfuncionario_tipo_estado te
                                INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = te.id_funcionario
                                where te.id_tipo_estado = v_id_tipo_estado_siguiente
                                and te.estado_reg = 'activo'
                                and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion)
                                order by te.id_funcionario_tipo_estado ASC
                                limit 1;

                                insert into mat.tasginacion_automatica_abastecimiento (id_usuario_reg,
                                                                       fecha_reg,
                                                                       id_solicitud,
                                                                       nro_tramite,
                                                                       id_funcionario_asignado,
                                                                       id_tipo_estado,
                                                                       ultima_asignacion
                                                                       )
                                                                   values(p_id_usuario,
                                                                          now(),
                                                                          v_datos_solicitud.id_solicitud,
                                                                          v_datos_solicitud.nro_tramite,
                                                                          v_funcionario_encargado,
                                                                          v_id_tipo_estado_asignacion,
                                                                          'si'
                                                                   );
                              end if;
                            end if;

                            update mat.tasginacion_automatica_abastecimiento set
                            ultima_asignacion = 'no'
                            where id_asignacion = v_id_asignacion;

                        /******************************/

                  end if;
                  /********************************************************************************************************/


                      ---------------------------------------------------------------------------

                     v_acceso_directo_automatico = '';
                     v_clase_automatico = '';
                     v_parametros_ad_automatico = '';
                     v_tipo_noti_automatico = 'notificacion';
                     v_titulo_automatico  = 'Visto Boa';
                     v_obs_automatico ='---';
                     ------------------------------------------pasamos el estado a vb_dpto_administrativo


                     v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                             v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
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
                    end if;
                    -------------------------------------------------------------------------------------------------------------------------------------

        		/*Aumentando para insertar en la tabla intermedia del estado para el documento de conformidad*/
                --Recuperamos los datos actualizados del tramite
                select sol.*
                      into v_solicitud_actual
                from mat.tsolicitud sol
                where id_proceso_wf = v_parametros.id_proceso_wf_act;


                /*Aqui Para Insertar y asignar automaticamente el funcionario de abastecimiento Ismael Valdivia (29/09/2021)*/

                  select auto.id_funcionario_asignado,
                         auto.id_asignacion
                         into
                         v_ultimo_funcionario_asignado,
                         v_id_asignacion
                  from mat.tasginacion_automatica_abastecimiento auto
                  where auto.ultima_asignacion = 'si'
                  and auto.id_tipo_estado = v_id_tipo_estado_siguiente;

                  select
                      esta.id_funcionario into v_funcionario_encargado_rpcd
                  from wf.testado_wf esta
                  inner join wf.ttipo_estado es on es.id_tipo_estado = esta.id_tipo_estado
                  where esta.id_proceso_wf = v_id_proceso_wf and es.codigo = 'vb_rpcd';

                  /*Si es Nulo entonces registramos el dato con el funcionario Asignado*/
                  update mat.tasginacion_automatica_abastecimiento set
                  id_funcionario_rpc = v_funcionario_encargado_rpcd
                  where id_solicitud = v_solicitud_actual.id_solicitud;
                  /************************************************************************************************************/




                SELECT		twf.id_funcionario

                into
                            v_id_funcionario_tecnico_auxiliar

                FROM wf.testado_wf twf
                    INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                    INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                    INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_solicitud_actual.id_proceso_wf
                      AND  te.codigo = 'revision_tecnico_abastecimientos'
                      AND twf.fecha_reg between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion, now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                order by twf.fecha_reg desc
                limit 1;

				/*Verificamos si existe el acta de conformidad para no crear otra*/

                select count(act.id_solicitud) into v_existe_acta
                from mat.tacta_conformidad_final act
                where act.id_solicitud = v_solicitud_actual.id_solicitud;

                if (v_existe_acta = 0) then

                insert into mat.tacta_conformidad_final (fecha_conformidad,
                                                         conformidad_final,
                                                         fecha_inicio,
                                                         fecha_final,
                                                         observaciones,
                                                         id_solicitud,
                                                         id_funcionario_firma,
                                                         id_usuario_reg,
                                                         fecha_reg,
                                                         revisado
                                                        )
                                                   values(now()::date,
                                                          '',
                                                          null,
                                                          null,
                                                          '',
                                                          v_solicitud_actual.id_solicitud,
                                                          v_id_funcionario_tecnico_auxiliar,
                                                          p_id_usuario,
                                                          now(),
                                                          'no'
                                                   );
                else
                	update mat.tacta_conformidad_final set
                    id_funcionario_firma = v_id_funcionario_tecnico_auxiliar,
                    revisado = 'no'
                    where id_solicitud = v_solicitud_actual.id_solicitud;
                end if;



                /*********************************************************************************************/

        	 /*Control para el estado rpce*/

             /*select sol.estado into v_estado_actual
              from mat.tsolicitud sol
              where sol.id_solicitud = v_parametros.id_solicitud;


             IF (v_estado_actual = 'autorizado') THEN


             	select sol.estado_firma into v_estado_firma_actual
                from mat.tsolicitud sol
                where sol.id_solicitud = v_parametros.id_solicitud;


                IF (v_estado_firma_actual = 'autorizado') THEN
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
                                                             v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
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

             END IF;*/
             /*****************************/


        else

			/*Cambiando para que la asignacion automatica sea en el estado revision tecnico de abastecimientos*/
        --Ismael Valdivia
        --16/02/2022
        if (v_solicitud.estado = 'revision_tecnico_abastecimientos') then


        	select sol.* into v_datos_solicitud
            from mat.tsolicitud sol
            where id_solicitud = v_parametros.id_solicitud;

            select pr.id_tipo_proceso into v_id_tipo_proceso_wf
            from wf.tproceso_wf pr
            where pr.id_proceso_wf = v_datos_solicitud.id_proceso_wf;

            select sol.id_proceso_wf into v_id_proceso_wf
            from mat.tsolicitud sol
            where sol.id_solicitud = v_datos_solicitud.id_solicitud;


            /*Control para completar los datos del detalle (Ismael Valdivia 28/02/2020)*/
            select count (*) into v_datos_incompletos
             from mat.tdetalle_sol det
             where det.nro_parte != 'HAZMAT' and
             det.id_solicitud = v_parametros.id_solicitud and (det.id_auxiliar is null or det.id_centro_costo is null or det.id_orden_trabajo is null or det.id_partida is null or det.id_concepto_ingas is null );


            IF (v_datos_incompletos > 0) THEN
                RAISE EXCEPTION 'No se puede cambiar de estado porque debe completar datos del detalle de la solicitud';
            END IF;
            /*****************************************************************************/

            /*Aumentando para poner condicion de reparaciones y compra de repuestos*/
            --Ismael Valdivia 21/02/2022

            if (v_datos_solicitud.origen_pedido = 'Reparación de Repuestos') then
            	 SELECT  twf.id_funcionario
                INTO
                      v_funcionario_encargado
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_datos_solicitud.id_proceso_wf AND te.codigo = 'revision_tecnico_abastecimientos'
                and v_datos_solicitud.fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg
                ORDER BY  twf.fecha_reg DESC
                LIMIT 1;

                select es.id_tipo_estado,
                       es.codigo
                into v_id_tipo_estado_siguiente,
                     v_codigo_estado_siguiente_auto
                from wf.ttipo_estado es
                LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'cotizacion'
                limit 1;

            else
            	select es.id_tipo_estado,
                       es.codigo
                into v_id_tipo_estado_siguiente,
                     v_codigo_estado_siguiente_auto
                from wf.ttipo_estado es
                LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'cotizacion'
                limit 1;

                select auto.id_funcionario_asignado,
                       auto.id_asignacion
                       into
                       v_ultimo_funcionario_asignado,
                       v_id_asignacion
                from mat.tasginacion_automatica_abastecimiento auto
                where auto.ultima_asignacion = 'si'
                and auto.id_tipo_estado = v_id_tipo_estado_siguiente;


                if (v_ultimo_funcionario_asignado is null) then

                select te.id_tipo_estado,
                       te.id_funcionario,
                       te.id_funcionario_tipo_estado
                       into
                       v_id_tipo_estado_asignacion,
                       v_funcionario_encargado,
                       v_id_funcionario_tipo_estado_asignacion
                from wf.tfuncionario_tipo_estado te
                INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = te.id_funcionario
                where te.id_tipo_estado = v_id_tipo_estado_siguiente
                and te.estado_reg = 'activo'
                and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion)
                order by te.id_funcionario_tipo_estado ASC
                limit 1;

                /*Insertamos en la tabla de asignacion*/
                insert into mat.tasginacion_automatica_abastecimiento (id_usuario_reg,
                                                                       fecha_reg,
                                                                       id_solicitud,
                                                                       nro_tramite,
                                                                       id_funcionario_asignado,
                                                                       id_tipo_estado,
                                                                       ultima_asignacion
                                                                      )
                                                               values(p_id_usuario,
                                                                      now(),
                                                                      v_datos_solicitud.id_solicitud,
                                                                      v_datos_solicitud.nro_tramite,
                                                                      v_funcionario_encargado,
                                                                      v_id_tipo_estado_asignacion,
                                                                      'si'
                                                               );


                else

                select te.id_funcionario_tipo_estado into v_id_funcionario_tipo_estado
                from wf.tfuncionario_tipo_estado te
                where te.id_funcionario = v_ultimo_funcionario_asignado
                and te.estado_reg = 'activo'
                and te.id_tipo_estado = v_id_tipo_estado_siguiente;


                select te.id_tipo_estado,
                       te.id_funcionario,
                       te.id_funcionario_tipo_estado
                       into
                       v_id_tipo_estado_asignacion,
                       v_id_funcionario_asignacion,
                       v_id_funcionario_tipo_estado_asignacion
                from wf.tfuncionario_tipo_estado te
                INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = te.id_funcionario
                where te.id_tipo_estado = v_id_tipo_estado_siguiente
                and te.estado_reg = 'activo'
                and te.id_funcionario not in (v_ultimo_funcionario_asignado)
                and te.id_funcionario_tipo_estado >= v_id_funcionario_tipo_estado
                and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion)
                order by te.id_funcionario_tipo_estado ASC
                limit 1;

                  if (v_id_funcionario_tipo_estado_asignacion is not null) then
                        v_funcionario_encargado = v_id_funcionario_asignacion;

                        insert into mat.tasginacion_automatica_abastecimiento (id_usuario_reg,
                                                                               fecha_reg,
                                                                               id_solicitud,
                                                                               nro_tramite,
                                                                               id_funcionario_asignado,
                                                                               id_tipo_estado,
                                                                               ultima_asignacion
                                                                              )
                                                                       values(p_id_usuario,
                                                                              now(),
                                                                              v_datos_solicitud.id_solicitud,
                                                                              v_datos_solicitud.nro_tramite,
                                                                              v_id_funcionario_asignacion,
                                                                              v_id_tipo_estado_asignacion,
                                                                              'si'
                                                                       );


                   else

                      select te.id_tipo_estado,
                           te.id_funcionario,
                           te.id_funcionario_tipo_estado
                           into
                           v_id_tipo_estado_asignacion,
                           v_funcionario_encargado,
                           v_id_funcionario_tipo_estado_asignacion
                    from wf.tfuncionario_tipo_estado te
                    INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = te.id_funcionario
                    where te.id_tipo_estado = v_id_tipo_estado_siguiente
                    and te.estado_reg = 'activo'
                    and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion)
                    order by te.id_funcionario_tipo_estado ASC
                    limit 1;

                    insert into mat.tasginacion_automatica_abastecimiento (id_usuario_reg,
                                                                           fecha_reg,
                                                                           id_solicitud,
                                                                           nro_tramite,
                                                                           id_funcionario_asignado,
                                                                           id_tipo_estado,
                                                                           ultima_asignacion
                                                                           )
                                                                       values(p_id_usuario,
                                                                              now(),
                                                                              v_datos_solicitud.id_solicitud,
                                                                              v_datos_solicitud.nro_tramite,
                                                                              v_funcionario_encargado,
                                                                              v_id_tipo_estado_asignacion,
                                                                              'si'
                                                                       );



                  end if;
                end if;
            end if;






             v_acceso_directo_automatico = '';
             v_clase_automatico = '';
             v_parametros_ad_automatico = '';
             v_tipo_noti_automatico = 'notificacion';
             v_titulo_automatico  = 'Visto Boa';
             v_obs_automatico ='Asignacion Automatica';
             ------------------------------------------pasamos el estado a vb_dpto_administrativo


             v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                     v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
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
            end if;

            if (v_id_asignacion is not null) then
              update mat.tasginacion_automatica_abastecimiento set
              ultima_asignacion = 'no'
              where id_asignacion = v_id_asignacion;
            end if;

		ELSE

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


				 /*Poniendo Control para que pase al estado vb_dpto_administrativo con la cotizacion adjudicada (Ismael Valdivia 19/02/2020)*/

				 IF (v_codigo_estado_siguiente = 'comite_unidad_abastecimientos') THEN
					select count(cot.id_cotizacion) into v_existe_proveedor_adjudicado
					from mat.tcotizacion cot
					where cot.id_solicitud = v_parametros.id_solicitud and cot.adjudicado = 'si';


					select count(cot.id_cotizacion) into v_existe_referencia
					from mat.tcotizacion cot
					inner join mat.tcotizacion_detalle det on det.id_cotizacion = cot.id_cotizacion
					where cot.id_solicitud = v_parametros.id_solicitud and det.referencial = 'Si';

					if (v_existe_referencia = 0) then
						--raise exception 'No se encontraron precios referenciales para la solicitud, favor verificar la información.';
					end if;



					/*Control para completar los datos del detalle (Ismael Valdivia 28/02/2020)*/
					select count (*) into v_datos_incompletos
					 from mat.tdetalle_sol det
					 where det.nro_parte != 'HAZMAT' and
					 det.id_solicitud = v_parametros.id_solicitud and (det.id_auxiliar is null or det.id_centro_costo is null or det.id_orden_trabajo is null or det.id_partida is null or det.id_concepto_ingas is null );


					IF (v_datos_incompletos > 0) THEN
						RAISE EXCEPTION 'No se puede cambiar de estado porque debe completar datos del detalle de la solicitud';
					END IF;
					/*****************************************************************************/



					/*Verificamos si existen adjudicados para dejar pasar al siguiente estado si no saltar alerta*/
					IF (v_existe_proveedor_adjudicado = 0) THEN
						RAISE EXCEPTION 'No se encontraron proveedores adjudicados en la cotización, debe adjudicar proveedores';

					ELSE
						select sum (cot.monto_total) into v_monto_adjudicado
						from mat.tcotizacion cot
						where cot.id_solicitud = v_parametros.id_solicitud and cot.adjudicado = 'si';

						select sum (det.precio_total) into v_monto_referencial
						from mat.tdetalle_sol det
						where det.id_solicitud = v_parametros.id_solicitud;

						/*Aqui ponemos la condicion para controlar que el monto adjudicado no sea mayor al referencial*/
						if (v_monto_adjudicado > v_monto_referencial) then
							Raise exception 'El monto Adjudicado no puede ser mayor al monto referencial. el monto adjudicado es: % y el monto referencial es: %',v_monto_adjudicado,v_monto_referencial;
						end if;


					end if;
					/*********************************************************************************************/

					/*Verificamos si el HAZMAT esta relacionado*/
					for v_verificar_relacion in (select cot.id_cotizacion
												from mat.tcotizacion cot
												inner join mat.tcotizacion_detalle det on det.id_cotizacion = cot.id_cotizacion
												where cot.id_solicitud = v_parametros.id_solicitud and det.id_detalle_hazmat is null
												and det.nro_parte_cot = 'HAZMAT') loop

						if (v_parametros.id_solicitud is not null) then

							  select pro.rotulo_comercial into v_proveedor_hazmat
							  from param.vproveedor pro
							  where pro.id_proveedor = (select cot.id_proveedor
														from mat.tcotizacion cot
														where cot.id_cotizacion = v_verificar_relacion.id_cotizacion);


							   raise exception 'El Hazmat de la cotización para el proveedor %, no esta relacionado a ningun part number.',v_proveedor_hazmat;


						end if;










					end loop;
					/*******************************************/





				 END IF;


				 /***************************************************************************************************************************/

				 /*Aqui actualizamos el campo de funcionario solicitante Ismael Valdivia (17/02/2022)*/
                 if(v_codigo_estado_siguiente = 'revision_tecnico_abastecimientos') then
                    update mat.tsolicitud  set
                           id_funcionario_solicitante =  v_parametros.id_funcionario_wf
                    where id_solicitud = v_solicitud.id_solicitud;
                 end if;
                 /*************************************************************************************/


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


				  -- ini bvp 06/02/2020 aumento para verificacion presupuestaria

				   v_mat_comprometer_presupuesto =  pxp.f_get_variable_global('mat_comprometer_presupuesto');

				   IF v_codigo_estado_siguiente in('comite_unidad_abastecimientos') THEN

						IF not mat.f_gestionar_presupuesto_solicitud(v_solicitud.id_solicitud, p_id_usuario, 'verificar')  THEN
							   raise exception 'Error al verificar  el presupeusto';
						END IF;

						 -- comprometer

						 IF v_solicitud.presu_comprometido = 'no' and v_mat_comprometer_presupuesto = 'si' THEN

								-- Comprometer Presupuesto
								IF not mat.f_gestionar_presupuesto_solicitud(v_solicitud.id_solicitud, p_id_usuario, 'comprometer')  THEN
									 raise exception 'Error al comprometer el presupuesto';
								END IF;

								--modifca bandera de comprometido
								 update mat.tsolicitud  set
									  presu_comprometido =  'si'
								 where id_solicitud = v_solicitud.id_solicitud;
						 END IF;

				   END IF;


					IF mat.f_procesar_estados_solicitud(p_id_usuario,
														v_parametros._id_usuario_ai,
														v_parametros._nombre_usuario_ai,
														v_id_estado_actual,
														v_parametros.id_proceso_wf_act,
														v_codigo_estado_siguiente) THEN

						RAISE NOTICE 'PASANDO DE ESTADO';

					END IF;
				   -- end bvp


				   /*Cambiando la condicion para que las firmas se disparen en el estado vb_dpto_administrativo y no revision (Ismael Valdivia 18/02/2020)*/
				   IF (v_codigo_estado_siguiente='comite_unidad_abastecimientos')THEN
					FOR v_registros_proc in ( select * from json_populate_recordset(null::wf.proceso_disparado_wf, v_parametros.json_procesos::json)) LOOP

					select 	tp.codigo,
							tp.codigo_llave
							into
							v_codigo_tipo_pro,
							v_codigo_llave
							from wf.ttipo_proceso tp
							where  tp.id_tipo_proceso =  v_registros_proc.id_tipo_proceso_pro;


					  IF NOT mat.f_disparo_firma(	p_id_usuario,
												  v_parametros._id_usuario_ai,
												  v_parametros._nombre_usuario_ai,
												  v_solicitud.id_solicitud,
												  v_id_estado_actual::integer,
												  v_registros_proc.id_funcionario_wf_pro::integer,
												  v_registros_proc.obs_pro,
												  v_registros_proc.id_depto_wf_pro)then

					  raise exception 'Error al generar disparo';
					  END IF;
					END LOOP;
				END IF;
				/************************************************************************************************/

			  IF (v_codigo_estado_siguiente='despachado')THEN

				if ((v_solicitud.fecha_po is null) ) then
					raise exception 'La fecha del (PO/REP) no puede ser vacia, favor verificar';
				end if;


				if (pxp.f_get_variable_global('interviene_presupuesto') = 'si') then
				  /*Aqui ponemos un control para que presupuesto (Ismael valdivia 27/04/2020)*/
				   if (v_solicitud.revisado_presupuesto = 'no') then
					  raise exception 'La solicitud actual aún se encuentra en revisión por parte de presupuestos.';
				   end if;
				  /***************************************************************************/
				end if;
				--RAISE EXCEPTION 'ENTRA';
				  FOR v_registros_proc in ( select * from json_populate_recordset(null::wf.proceso_disparado_wf, v_parametros.json_procesos::json)) LOOP

							 -- Obtenemos el codigo de tipo proceso
							 select
								tp.codigo,
								tp.codigo_llave
							 into
								v_codigo_tipo_pro,
								v_codigo_llave
							 from wf.ttipo_proceso tp
							  where  tp.id_tipo_proceso =  v_registros_proc.id_tipo_proceso_pro;


							--MAY descomentar disparo
							 -- disparar creacion de procesos seleccionados
							SELECT
									 ps_id_proceso_wf,
									 ps_id_estado_wf,
									 ps_codigo_estado,
									 ps_nro_tramite
							   into
									 v_id_proceso_wf,
									 v_id_estado_wf,
									 v_codigo_estado,
									 v_nro_tramite
							FROM wf.f_registra_proceso_disparado_wf(
									 p_id_usuario,
									 v_parametros._id_usuario_ai,
									 v_parametros._nombre_usuario_ai,
									 v_id_estado_actual::integer,
									 v_registros_proc.id_funcionario_wf_pro::integer,
									 v_registros_proc.id_depto_wf_pro::integer,
									 v_nro_tramite||v_registros_proc.obs_pro,
									 v_codigo_tipo_pro,
									 v_codigo_tipo_pro);


						--(MAY) 03-02-2020 el codigo de llave obligacion_pago es para que su flujo cambie aobligaciones de pago ya no adqui
						 --IF v_codigo_llave = 'SOLICITUD' THEN
						 IF v_codigo_llave = 'obligacion_pago' THEN
								/*raise exception 'v_id_proceso_wf: %, v_id_estado_wf %, v_codigo_estado %, v_nro_tramite %, v_codigo_tipo_pro: %',
								v_id_proceso_wf, v_id_estado_wf, v_codigo_estado, v_nro_tramite, v_codigo_tipo_pro;*/
								--
								/*IF NOT mat.f_disparar_adquisiciones(
															  p_id_usuario,
															  v_parametros._id_usuario_ai,
															  v_parametros._nombre_usuario_ai,
															  v_solicitud.id_solicitud,
															  v_id_estado_actual::integer,
															  v_registros_proc.id_funcionario_wf_pro::integer,
															  v_registros_proc.obs_pro,
															  v_codigo_llave,
															  v_registros_proc.id_depto_wf_pro

															 ) THEN

								  raise exception 'Error al generar obligacion de pago';

								END IF;*/

								IF NOT mat.f_genera_obligacion_pago_mat(
															p_id_usuario,
															v_parametros._id_usuario_ai,
															v_parametros._nombre_usuario_ai,
															v_solicitud.id_solicitud,
															v_id_proceso_wf,
															v_id_estado_wf,
															v_codigo_estado,
															v_codigo_llave,
															v_registros_proc.id_depto_wf_pro) THEN

										raise exception 'Error al generar obligacion de pago';

								END IF;


						 ELSE

							raise exception 'Codigo llave no reconocido  verifique el WF (%)', v_codigo_llave;


						 END IF;


				  END LOOP;

			  END IF;



			end if;
        /**************************************************************************************************/

        end if;

        --raise exception 'llega aqui v_solictidu %',v_solicitud.estado;
        /*Aqui Recuperamos el Id_PO_Alkym obtenido del servicio 24/04/2020*/
              SELECT sol.nro_po,
              sol.nro_tramite,
              sol.origen_pedido
              into
              v_nro_po,
              v_nro_tramite,
              v_origen_pedido
              from mat.tsolicitud sol
              where sol.id_solicitud = v_parametros.id_solicitud;
        /*******************************************************************/
        /************************************************************************************************************/


          -- si hay mas de un estado disponible  preguntamos al usuario
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado de Solicitud)');
          v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
          v_resp = pxp.f_agrega_clave(v_resp,'v_codigo_estado_siguiente',v_codigo_estado_siguiente);
		  v_resp = pxp.f_agrega_clave(v_resp,'v_nro_po',v_nro_po);
          v_resp = pxp.f_agrega_clave(v_resp,'v_nro_tramite',v_nro_tramite);
          v_resp = pxp.f_agrega_clave(v_resp,'v_origen_pedido',v_origen_pedido);

          -- Devuelve la respuesta
          return v_resp;
        end;


        /*********************************
 	#TRANSACCION:  'MAT_SIG_BORR_IME'
 	#DESCRIPCION:	Siguiente
 	#AUTOR:		admin
 	#FECHA:		21-09-2016 11:32:59
	***********************************/

    elseif(p_transaccion='MAT_SIG_BORR_IME') then
    	begin

        /*Aqui armaremos una condicion para que cuando este en el comite directamente pase el estado automaticamente*/
        select sol.*
              into v_solicitud
        from mat.tsolicitud sol
        where id_proceso_wf = v_parametros.id_proceso_wf;

        		/*Aumentando para que el registro pase los estados de aeronavegabilidad y rpc automatico (Ismael Valdivia 20/09/2020)*/
             	--Recuperamos el id_tipo_proceso_wf para obtener el siguiente estado
                    select pr.id_tipo_proceso into v_id_tipo_proceso_wf
                    from wf.tproceso_wf pr
                    where pr.id_proceso_wf = v_solicitud.id_proceso_wf;

                    select es.id_tipo_estado,
                    	   es.codigo,
                           fun.id_funcionario
                    into v_id_tipo_estado_siguiente,
                         v_codigo_estado_siguiente_auto,
                         v_funcionario_encargado
                    from wf.ttipo_estado es
                    LEFT join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                    where es.id_tipo_proceso = v_id_tipo_proceso_wf and es.codigo = 'revision';
                    ---------------------------------------------------------------------------

                      ---------------------------------------------------------------------------
                    --raise exception 'Aqui llega %',v_codigo_estado_siguiente_auto;
                     v_acceso_directo_automatico = '';
                     v_clase_automatico = '';
                     v_parametros_ad_automatico = '';
                     v_tipo_noti_automatico = 'notificacion';
                     v_titulo_automatico  = 'Visto Boa';
                     v_obs_automatico ='---';
                     ------------------------------------------pasamos el estado a vb_dpto_administrativo


                     v_id_estado_actual =  wf.f_registra_estado_wf(	 v_id_tipo_estado_siguiente,--id del estado siguiente revision
                                                             v_funcionario_encargado,--id del funcionario Solicitante Mavy trigo (Definir de donde recuperaremos)
                                                             v_solicitud.id_estado_wf,
                                                             v_solicitud.id_proceso_wf,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             COALESCE(v_solicitud.nro_tramite,'--')||' Obs:'||v_obs_automatico,
                                                             v_acceso_directo_automatico,
                                                             v_clase_automatico,
                                                             v_parametros_ad_automatico,
                                                             v_tipo_noti_automatico,
                                                             v_titulo_automatico);

                     IF mat.f_procesar_estados_solicitud(p_id_usuario,
           											v_parametros._id_usuario_ai,
                                            		v_parametros._nombre_usuario_ai,
                                            		v_id_estado_actual,
                                            		v_solicitud.id_proceso_wf,
                                            		v_codigo_estado_siguiente_auto) THEN

                    RAISE NOTICE 'PASANDO DE ESTADO';
                    end if;
                    -------------------------------------------------------------------------------------------------------------------------------------

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

        	/*Aumentando para recuperar al funcionario solicitante
            El funcionario solicitante esta creado en una variable global
            por el momento el funcionario solicitante es JAIME LAZARTE
            el id funcionario es (370).
            Si el funcionario solicitante es otro cambiar en la variable global*/
            v_id_funcionario_solicitante = pxp.f_get_variable_global('funcionario_solicitante_gm');        	/**********************************************************************/

            select usu.id_usuario into v_id_usuario
            from orga.vfuncionario_persona per
            inner join segu.tusuario usu on usu.id_persona = per.id_persona
            where per.id_funcionario = v_id_funcionario_solicitante;



			--Sentencia de la consulta de conteo de registros
			SELECT tf.id_funcionario, vfcl.desc_funcionario1, vfcl.nombre_cargo,
            /*Aumentando para recuperar el departamento*/
            vfcl.id_lugar::varchar
            /*******************************************/
            INTO v_campos
			FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            INNER JOIN orga.vfuncionario_cargo_lugar vfcl on vfcl.id_funcionario = tf.id_funcionario
            WHERE tu.id_usuario = v_id_usuario;

            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
			v_resp = pxp.f_agrega_clave(v_resp,'id_funcionario',v_campos.id_funcionario::varchar);
			v_resp = pxp.f_agrega_clave(v_resp,'nombre_completo1',v_campos.desc_funcionario1::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'nombre_cargo',v_campos.nombre_cargo);
            v_resp = pxp.f_agrega_clave(v_resp,'id_lugar',v_campos.id_lugar);

            return v_resp;

		end;
        /*********************************
        #TRANSACCION:  'MAT_FUN_GET_TEC'
        #DESCRIPCION:	Lista de funcionarios para registro
        #AUTOR:		Ismael Valdivia
        #FECHA:		3/06/2020
        ***********************************/

        elsif(p_transaccion='MAT_FUN_GET_TEC')then

            begin
            	 --Sentencia de la consulta de conteo de registros
                SELECT tf.id_funcionario, vfcl.desc_funcionario1, vfcl.nombre_cargo,
                /*Aumentando para recuperar el departamento*/
                vfcl.id_lugar::varchar
                /*******************************************/
                INTO v_campos
                FROM segu.tusuario tu
                INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                INNER JOIN orga.vfuncionario_cargo_lugar vfcl on vfcl.id_funcionario = tf.id_funcionario
                WHERE tu.id_usuario = p_id_usuario;

                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
                v_resp = pxp.f_agrega_clave(v_resp,'id_funcionario',v_campos.id_funcionario::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'nombre_completo1',v_campos.desc_funcionario1::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'nombre_cargo',v_campos.nombre_cargo);
                v_resp = pxp.f_agrega_clave(v_resp,'id_lugar',v_campos.id_lugar);

                return v_resp;

            end;

         /*********************************
        #TRANSACCION:  'MAT_GET_CC_DEF'
        #DESCRIPCION:	Lista de Centro de Costo por defecto
        #AUTOR:		Ismael Valdivia
        #FECHA:		23/06/2021
        ***********************************/

        elsif(p_transaccion='MAT_GET_CC_DEF')then

            begin
            	 --Sentencia de la consulta de conteo de registros
                   select cc.id_centro_costo
                   			into
                            v_id_centro_costo
                  from param.tcentro_costo cc
                  inner join param.ttipo_cc tc on tc.id_tipo_cc = cc.id_tipo_cc
                  where tc.codigo = '845' and cc.id_gestion = v_parametros.id_gestion;
                /*******************************************/

                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
                v_resp = pxp.f_agrega_clave(v_resp,'id_centro_costo',v_id_centro_costo::varchar);

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
                                            s.fecha_solicitud,
                                            s.estado,
                                            s.id_matricula,
                                            ot.desc_orden
                                            from mat.tdetalle_sol d
                                            inner join mat.tsolicitud s on s.id_solicitud = d.id_solicitud
                                            inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                                            left join conta.torden_trabajo ot on ot.id_orden_trabajo = s.id_matricula
                                            where s.estado != 'finalizado'

     	)LOOP
        if v_control_duplicidad.nro_justificacion !=''then
        	if v_parametros.justificacion = v_control_duplicidad.nro_justificacion then
            	v_justificacion= v_control_duplicidad.nro_justificacion;
                v_msg_control = ' El numero justificacion '||v_control_duplicidad.nro_justificacion||' de '||v_control_duplicidad.justificacion||' ya fue registrado por '||v_control_duplicidad.desc_funcionario1||' en el tramite '||v_control_duplicidad.nro_tramite|| ' con fecha ' ||v_control_duplicidad.fecha_solicitud;
            end if;
        end if;
        if v_parametros.nro_parte = v_control_duplicidad.nro_parte and v_parametros.id_matricula = v_control_duplicidad.id_matricula then
            	v_parte= v_control_duplicidad.nro_parte;
                v_matricula= v_control_duplicidad.id_matricula;
                v_msg_control = ' El number parte: '||v_control_duplicidad.nro_parte||', para la matricula: '||v_control_duplicidad.desc_orden||'; ya fue registrado por '||v_control_duplicidad.desc_funcionario1||' en el tramite '||v_control_duplicidad.nro_tramite|| ' con fecha ' ||v_control_duplicidad.fecha_solicitud;
            end if;
        END LOOP;

        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
        v_resp = pxp.f_agrega_clave(v_resp,'justificacion', v_justificacion::varchar );
        v_resp = pxp.f_agrega_clave(v_resp,'parte', v_parte::varchar );
        v_resp = pxp.f_agrega_clave(v_resp,'matricula', v_matricula::varchar );
        v_resp = pxp.f_agrega_clave(v_resp,'mgs_control_duplicidad', v_msg_control::varchar );
        return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_EMAIL_COT_IME'
 	#DESCRIPCION:	Establecemos los correos de los proveedores a los que se enviara detalle de cotización.
 	#AUTOR:		Franklin Espinoza
 	#FECHA:		29-06-2017 16:50:07
	***********************************/

	elsif(p_transaccion='MAT_EMAIL_COT_IME')then

		begin
           /* v_ids_prov = string_to_array(v_parametros.lista_correos,',');
            v_tam = array_length(v_ids_prov,1);

            SELECT tgp.cotizacion_solicitadas
            INTO v_ids_prov_act
            FROM mat.tgestion_proveedores tgp
            WHERE tgp.id_solicitud = v_parametros.id_solicitud;

            IF (v_tam IS NOT NULL AND v_ids_prov_act IS NULL)THEN
                    INSERT INTO mat.tgestion_proveedores(
                      id_usuario_reg,
                      id_usuario_mod,
                      fecha_reg,
                      fecha_mod,
                      estado_reg,
                      id_usuario_ai,
                      usuario_ai,
                      id_solicitud,
                      cotizacion_solicitadas
                    )VALUES(
                      p_id_usuario,
                      null,
                      now(),
                      null,
                      'activo',
                      v_parametros._id_usuario_ai,
                      v_parametros._nombre_usuario_ai,
                      v_parametros.id_solicitud,
                      v_ids_prov
                    );
            	--Definicion de la respuesta
            	v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Lista de correos fue definido Exitosamente');
            ELSIF(v_ids_prov_act <> v_ids_prov)THEN
            	UPDATE mat.tgestion_proveedores SET
                cotizacion_solicitadas = v_ids_prov
                WHERE id_solicitud = v_parametros.id_solicitud;
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Lista de correos fue modificado Exitosamente');

            END IF;

            */
         	--v_ids_prov = string_to_array(v_parametros.lista_correos,',');
            --v_tam = array_length(v_ids_prov,1);

            if (v_parametros.lista_correos = '0') then
            select pxp.list (p.id_proveedor::text)
            into
            v_id_proveedores
            from param.vproveedor p
            where p.tipo = 'abastecimiento' and  p.email <> '';

            v_ids_prov = string_to_array(v_id_proveedores,',');
            v_tam = array_length(v_ids_prov,1);

            else

            v_ids_prov = string_to_array(v_parametros.lista_correos,',');
            v_tam = array_length(v_ids_prov,1);
            end if;

            SELECT pxp.aggarray(tgp.id_proveedor)
            INTO v_ids_prov_act
            FROM mat.tgestion_proveedores_new tgp
            WHERE tgp.id_solicitud = v_parametros.id_solicitud;


            IF (v_tam IS NOT NULL AND v_ids_prov_act IS NULL)THEN
			v_i = 1;
           	while v_i <= v_tam loop
                    INSERT INTO mat.tgestion_proveedores_new(
                      id_usuario_reg,
                      id_usuario_mod,
                      fecha_reg,
                      fecha_mod,
                      estado_reg,
                      id_usuario_ai,
                      usuario_ai,
  					  id_solicitud,
  					  id_proveedor

                    )VALUES(
                      p_id_usuario,
                      null,
                      now(),
                      null,
                      'activo',
                      v_parametros._id_usuario_ai,
                      v_parametros._nombre_usuario_ai,
                      v_parametros.id_solicitud,
                   	  v_ids_prov[v_i]::integer);
            	--Definicion de la respuesta
                v_i = v_i + 1;
            	v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Lista de correos fue definido Exitosamente');

            end loop;

            ELSIF(v_ids_prov_act <> v_ids_prov)THEN

            delete from mat.tgestion_proveedores_new
            where id_solicitud=v_parametros.id_solicitud;

            v_i = 1;
           	while v_i <= v_tam loop
                    INSERT INTO mat.tgestion_proveedores_new(
                      id_usuario_reg,
                      id_usuario_mod,
                      fecha_reg,
                      fecha_mod,
                      estado_reg,
                      id_usuario_ai,
                      usuario_ai,
  					  id_solicitud,
  					  id_proveedor

                    )VALUES(
                      p_id_usuario,
                      null,
                      now(),
                      null,
                      'activo',
                      v_parametros._id_usuario_ai,
                      v_parametros._nombre_usuario_ai,
                      v_parametros.id_solicitud,
                   	  v_ids_prov[v_i]::integer);
            	--Definicion de la respuesta
            v_i = v_i + 1;
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Lista de correos fue modificado Exitosamente');
            end loop;
            END IF;


            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
    #TRANSACCION:  'MAT_EMAIL_PROV_VAL'
    #DESCRIPCION:	VERIFICA SI TODOS LOS PROVEEDORES TIENEN UN CORREO DE CONTACTO PARA ENVIAR CORREO DE COTIZACION
    #AUTOR:		Franklin Espinoza
    #FECHA:		06-07-2017 14:58:16
    ***********************************/
    elsif(p_transaccion='MAT_EMAIL_PROV_VAL')then

          BEGIN
          SELECT cotizacion_solicitadas
          INTO v_ids_prov
          FROM mat.tgestion_proveedores
          WHERE  id_solicitud = v_parametros.id_solicitud;


          IF(v_ids_prov IS NOT NULL)THEN
		  	v_tam = array_length(v_ids_prov,1);

            FOR v_cont IN 1..v_tam LOOP

              SELECT vp.email,vp.rotulo_comercial
              INTO v_record
              FROM param.vproveedor vp
              WHERE vp.id_proveedor = v_ids_prov[v_cont];

              IF(v_record.email = '')THEN
              	v_sin_correo[v_cont] = v_record.rotulo_comercial;
                v_bandera = true;
              END IF;

            END LOOP;


          END IF;
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Validación de correos Exitoso');
            v_resp = pxp.f_agrega_clave(v_resp,'v_sin_correo',array_to_string(v_sin_correo,'#')::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_bandera',v_bandera::varchar);

            --Devuelve la respuesta
            return v_resp;
         END;
      /*********************************
 	#TRANSACCION:  'MAT_CLONAR_IME'
 	#DESCRIPCION:	Clonar solicitud.
 	#AUTOR:		MMV
 	#FECHA:		17-08-2017
	***********************************/

	elsif(p_transaccion='MAT_CLONAR_IME')then

		begin

        select *
        into
        v_registros
        from mat.tsolicitud
        where id_proceso_wf = v_parametros.id_proceso_wf;

     /*   if v_registros.estado != 'cotizacion_solicitada'then
        raise exception 'Solo puede ejecutar la clonacion de solicitud en estado Cotizacion Solicitada';
        end if;
*/


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

            elsif v_registros.origen_pedido ='Almacenes Consumibles o Rotables'then
           	select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GA-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

           elsif v_registros.origen_pedido ='Centro de Entrenamiento Aeronautico Civil'then
           	select    tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='GC-RM' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;
			END IF;



          select g.id_gestion, g.gestion
           into v_gestion, anho
           from param.tgestion g
           where g.gestion = EXTRACT(YEAR FROM current_date);

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
                 null,
                 null,
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



        	FOR v_datos_solicitud in (  select *
									from wf.testado_wf
									where id_proceso_wf = v_parametros.id_proceso_wf and id_estado_anterior is not null
                                    order by id_estado_wf ASC )LOOP

        INSERT INTO wf.testado_wf(
        id_usuario_reg,
        fecha_reg,
        estado_reg,
        id_estado_anterior,
        id_tipo_estado,
        id_proceso_wf,
        id_funcionario,
        id_depto,
        id_usuario_ai,
        usuario_ai,
        obs
      	)
      	VALUES (
        v_datos_solicitud.id_usuario_reg,
        v_datos_solicitud.fecha_reg,
        v_datos_solicitud.estado_reg,
        v_datos_solicitud.id_estado_anterior,---
        v_datos_solicitud.id_tipo_estado,
        v_id_proceso_wf,
        v_datos_solicitud.id_funcionario,
        NULL,
        NULL,
        NULL,
        v_datos_solicitud.obs
        )RETURNING id_estado_wf into v_id_estado_clon;

        END LOOP;

        if v_registros.origen_pedido = 'Gerencia de Operaciones'
        or v_registros.origen_pedido = 'Gerencia de Mantenimiento'then

        if v_registros.origen_pedido ='Gerencia de Operaciones' THEN
        select ts.id_tipo_estado
        into
        v_id_estado_tipo
        from wf.ttipo_estado ts
        inner join wf.ttipo_proceso tp on tp.id_tipo_proceso = ts.id_tipo_proceso
        where ts.codigo = 'revision'and tp.nombre = 'Requerimiento Gerencia de Operaciones';

         select fu.id_funcionario
        into
        vv_id_funcionario
        from orga.vfuncionario_cargo fu
        where fu.fecha_finalizacion is null and fu.nombre_cargo = 'Especialista Planificación Servicios';


        elsif v_registros.origen_pedido ='Gerencia de Mantenimiento'then

        select ts.id_tipo_estado
        into
        v_id_estado_tipo
        from wf.ttipo_estado ts
        inner join wf.ttipo_proceso tp on tp.id_tipo_proceso = ts.id_tipo_proceso
        where ts.codigo = 'revision'and tp.nombre = 'Requerimiento Gerencia de Mantenimiento';

        select fu.id_funcionario
        into
        vv_id_funcionario
        from orga.vfuncionario_cargo fu
        where fu.fecha_finalizacion is null and fu.nombre_cargo = 'Gerente Mantenimiento';
        end if;



       select e.id_estado_wf
       into
       v_id
       from wf.testado_wf e
       where e.id_proceso_wf = v_id_proceso_wf and e.id_tipo_estado = v_id_estado_tipo;



       SELECT	 			 ps_id_proceso_wf,
                             ps_id_estado_wf,
                             ps_codigo_estado
                             into
                             v_id_proceso_wf_clo,
                             v_id_2,
                             v_codigo_estado
                    FROM wf.f_registra_proceso_disparado_wf(
                             v_registros.id_usuario_reg,
                             null,
                             null,
                             v_id,
                             vv_id_funcionario,
                             null,
                             'Firma ['||v_registros.nro_tramite||']',
                             'FRD',
                             v_registros.nro_tramite);



       FOR v_datos_solicitud in (  select *
									from wf.testado_wf
									where id_proceso_wf = v_registros.id_proceso_wf_firma and id_estado_anterior is not null
                                    order by id_estado_wf ASC )LOOP

        INSERT INTO wf.testado_wf(
        id_usuario_reg,
        fecha_reg,
        estado_reg,
        id_estado_anterior,
        id_tipo_estado,
        id_proceso_wf,
        id_funcionario,
        id_depto,
        id_usuario_ai,
        usuario_ai,
        obs
      	)
      	VALUES (
        v_datos_solicitud.id_usuario_reg,
        v_datos_solicitud.fecha_reg,
        v_datos_solicitud.estado_reg,
        v_datos_solicitud.id_estado_anterior,---
        v_datos_solicitud.id_tipo_estado,
        v_id_proceso_wf_clo,
        v_datos_solicitud.id_funcionario,
        NULL,
        NULL,
        NULL,
        v_datos_solicitud.obs
        )RETURNING id_estado_wf into v_id_estado_firma;

        END LOOP;

    end if;

          IF (substr(v_nro_tramite,1,2)='GM')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GM.'||ltrim(substr(v_nro_tramite,7,6),'0')||'.'||substr(v_nro_tramite,14,17);
          ELSIF (substr(v_nro_tramite,1,2)='GA')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GA.'||ltrim(substr(v_nro_tramite,7,6),'0')||'.'||substr(v_nro_tramite,14,17);
          ELSIF (substr(v_nro_tramite,1,2)='GO')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GO.'||ltrim(substr(v_nro_tramite,7,6),'0')||'.'||substr(v_nro_tramite,14,17);
             ELSIF (substr(v_nro_tramite,1,2)='GC')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GC.'||ltrim(substr(v_nro_tramite,7,6),'0')||'.'||substr(v_nro_tramite,14,17);
          END IF;

    FOR v_record_clon in ( 	select *
        						from mat.tsolicitud s
 								where s.id_proceso_wf = v_parametros.id_proceso_wf)
        loop
    INSERT INTO  mat.tsolicitud(	id_usuario_reg,
                                id_usuario_mod,
                                fecha_reg,
                                fecha_mod,
                                estado_reg,
                                id_usuario_ai,
                                usuario_ai,

                                origen_pedido,
                                id_funcionario_sol,
                                nro_solicitud,
                                nro_tramite,
                                fecha_solicitud,
                                fecha_requerida,
                                observaciones_sol,
                                tipo_solicitud,
                                motivo_solicitud,
                                id_matricula,
                                justificacion,
                                cotizacion,
                                id_proveedor,
                                nro_po,
                                fecha_entrega_miami,
                                fecha_despacho_miami,
                                fecha_arribado_bolivia,
                                fecha_desaduanizacion,
                                fecha_entrega_almacen,
                                observacion_nota,
                                id_proceso_wf,
                                id_estado_wf,
                                estado,
                                fecha_tentativa_llegada,
                                fecha_en_almacen,
                                tipo_falla,
                                tipo_reporte,
                                mel,
                                nro_no_rutina,
                                nro_justificacion,
                                fecha_cotizacion,
                                estado_firma,
                                id_proceso_wf_firma,
                                id_estado_wf_firma,
                                fecha_po,
                                nro_cite_dce,
                                lugar_entrega,
                                condicion,
                                tipo_evaluacion,
                                taller_asignado,
                                nro_cite_cobs,
                                mensaje_correo,
                                tipo,
                                id_solicitud_padre)
								VALUES (
                                v_record_clon.id_usuario_reg,
                                v_record_clon.id_usuario_mod,
                                v_record_clon.fecha_reg,
                                v_record_clon.fecha_mod,
                                v_record_clon.estado_reg,
                                v_record_clon.id_usuario_ai,
                                v_record_clon.usuario_ai,

                                v_record_clon.origen_pedido,
                                v_record_clon.id_funcionario_sol,
                                v_record_clon.nro_solicitud,
                                v_nro_tramite,
                                v_record_clon.fecha_solicitud,
                                v_record_clon.fecha_requerida,
                                v_record_clon.observaciones_sol,
                                v_record_clon.tipo_solicitud,
                                v_record_clon.motivo_solicitud,
                                v_record_clon.id_matricula,
                                v_record_clon.justificacion,
                                v_record_clon.cotizacion,
                                v_record_clon.id_proveedor,
                                v_record_clon.nro_po,
                                v_record_clon.fecha_entrega_miami,
                                v_record_clon.fecha_despacho_miami,
                                v_record_clon.fecha_arribado_bolivia,
                                v_record_clon.fecha_desaduanizacion,
                                v_record_clon.fecha_entrega_almacen,
                                v_record_clon.observacion_nota,
                                 v_id_proceso_wf,
                                v_id_estado_clon,
                                v_record_clon.estado,
                                v_record_clon.fecha_tentativa_llegada,
                                v_record_clon.fecha_en_almacen,
                                v_record_clon.tipo_falla,
                                v_record_clon.tipo_reporte,
                                v_record_clon.mel,
                                v_record_clon.nro_no_rutina,
                                v_record_clon.nro_justificacion,
                                v_record_clon.fecha_cotizacion,
                                v_record_clon.estado_firma,
                                v_id_proceso_wf_clo,
                        		v_id_estado_firma,
                                v_record_clon.fecha_po,
                                v_nro_cite_dce,
                                v_record_clon.lugar_entrega,
                                v_record_clon.condicion,
                                v_record_clon.tipo_evaluacion,
                                v_record_clon.taller_asignado,
                                v_nro_cite_dce,
                                v_record_clon.mensaje_correo,
                                'clon',
                                v_record_clon.id_solicitud
								)RETURNING id_solicitud into v_id_solicitud ;
                                  select g.id_gestion
           	 		into v_id_gestion
           			from param.tgestion g
           			where g.gestion = EXTRACT(YEAR FROM current_date);

            update mat.tsolicitud set
         	id_gestion = v_id_gestion
        	where id_solicitud = v_id_solicitud;

        v_cargar_list = mat.f_cargar_list_proceso(v_record_clon.id_solicitud, v_id_solicitud);

    	for v_documento in (select *
        from wf.tdocumento_wf d
				inner join wf.ttipo_documento t on t.id_tipo_documento = d.id_tipo_documento
        where d.id_proceso_wf = v_parametros.id_proceso_wf and t.nombre != 'Requerimiento de Materiales ERP')
        loop
        INSERT INTO  wf.tdocumento_wf (id_usuario_reg,
                    id_usuario_mod,
                    fecha_reg,
                    fecha_mod,
                    estado_reg,
                    id_usuario_ai,
                    usuario_ai,

                    id_tipo_documento,
                    id_proceso_wf,
                    num_tramite,
                    momento,
                    nombre_tipo_doc,
                    nombre_doc,
                    chequeado,
                    url,
                    extension,
                    obs,
                    id_estado_ini,
                    chequeado_fisico,
                    momento_fisico,
                    id_usuario_upload,
                    fecha_upload,
                    id_proceso_wf_ori,
                    nro_tramite_ori,
                    id_documento_wf_ori,
                    accion_pendiente,
                    fecha_firma,
                    usuario_firma,
                    datos_firma,
                    hash_firma,
                    demanda
                  )
                  VALUES (
                    v_documento.id_usuario_reg,
                    v_documento.id_usuario_mod,
                    v_documento.fecha_reg,
                    v_documento.fecha_mod,
                    v_documento.estado_reg,
                    v_documento.id_usuario_ai,
                    v_documento.usuario_ai,

                    v_documento.id_tipo_documento,
                    v_id_proceso_wf,
                    v_documento.num_tramite,
                    v_documento.momento,
                    v_documento.nombre_tipo_doc,
                    v_documento.nombre_doc,
                    v_documento.chequeado,
                    v_documento.url,
                    v_documento.extension,
                    v_documento.obs,
                    v_documento.id_estado_ini,
                    v_documento.chequeado_fisico,
                    v_documento.momento_fisico,
                    v_documento.id_usuario_upload,
                    v_documento.fecha_upload,
                    v_documento.id_proceso_wf_ori,
                    v_documento.nro_tramite_ori,
                    v_documento.id_documento_wf_ori,
                    v_documento.accion_pendiente,
                    v_documento.fecha_firma,
                    v_documento.usuario_firma,
                    v_documento.datos_firma,
                    v_documento.hash_firma,
                    v_documento.demanda
                  );
        end loop;


        		for v_detalle_clon in (	select *
                						from mat.tdetalle_sol d
                                        where d.id_solicitud =v_record_clon.id_solicitud )
                loop
                insert into mat.tdetalle_sol( id_solicitud,
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
                                              explicacion_detallada_part
                                              ) values(
                                              v_id_solicitud,
                                              v_detalle_clon.descripcion,
                                              'activo',
                                              v_detalle_clon.id_unidad_medida,
                                              v_detalle_clon.nro_parte,
                                              v_detalle_clon.referencia,
                                              v_detalle_clon.nro_parte_alterno,
                                              v_detalle_clon.cantidad_sol,
                                              v_detalle_clon.id_usuario_reg,
                                              null,
                                              now(),
                                              null,
                                              null,
                                              null,
                                              v_detalle_clon.tipo,
                                              v_detalle_clon.explicacion_detallada_part);

                end loop;
        for v_cotizacon in (select *
                            from mat.tcotizacion c
                            where c.id_solicitud = v_record_clon.id_solicitud )loop

        INSERT INTO mat.tcotizacion ( id_usuario_reg,
                                      id_usuario_mod,
                                      fecha_reg,
                                      fecha_mod,
                                      estado_reg,
                                      id_usuario_ai,
                                      usuario_ai,

                                      monto_total,
                                      fecha_cotizacion,
                                      nro_tramite,
                                      adjudicado,
                                      id_proveedor,
                                      id_solicitud,
                                      id_moneda,
                                      nro_cotizacion,
                                      recomendacion,
                                      obs,
                                      pie_pag)
                                      VALUES (
                                      v_cotizacon.id_usuario_reg,
                                      v_cotizacon.id_usuario_mod,
                                      v_cotizacon.fecha_reg,
                                      v_cotizacon.fecha_mod,
                                      v_cotizacon.estado_reg,
                                      v_cotizacon.id_usuario_ai,
                                      v_cotizacon.usuario_ai,

                                      v_cotizacon.monto_total,
                                      v_cotizacon.fecha_cotizacion,
                                      v_cotizacon.nro_tramite,
                                      v_cotizacon.adjudicado,
                                      v_cotizacon.id_proveedor,
                                      v_id_solicitud,
                                      v_cotizacon.id_moneda,
                                      v_cotizacon.nro_cotizacion,
                                      v_cotizacon.recomendacion,
                                      v_cotizacon.obs,
                                      v_cotizacon.pie_pag

                                      ) RETURNING id_cotizacion into vv_id_cot ;
                                      for v_cotizacion_det in (
        select *
        from mat.tcotizacion_detalle d
        where d.id_cotizacion = v_cotizacon.id_cotizacion
        ) loop

    INSERT INTO  mat.tcotizacion_detalle (	id_usuario_reg,
                                            id_usuario_mod,
                                            fecha_reg,
                                            fecha_mod,
                                            estado_reg,
                                            id_usuario_ai,
                                            usuario_ai,

                                            id_cotizacion,
                                            id_detalle,
                                            precio_unitario,
                                            precio_unitario_mb,
                                            cantidad_det,
                                            id_solicitud,
                                            cd,
                                            id_day_week,
                                            nro_parte_cot,
                                            nro_parte_alterno_cot,
                                            referencia_cot,
                                            descripcion_cot,
                                            explicacion_detallada_part_cot,
                                            tipo_cot,
                                            id_unidad_medida_cot,
                                            revisado
                                          )
                                      VALUES (
                                        v_cotizacion_det.id_usuario_reg,
                                        v_cotizacion_det.id_usuario_mod,
                                        v_cotizacion_det.fecha_reg,
                                        v_cotizacion_det.fecha_mod,
                                        v_cotizacion_det.estado_reg,
                                        v_cotizacion_det.id_usuario_ai,
                                        v_cotizacion_det.usuario_ai,

                                        vv_id_cot,
                                        v_cotizacion_det.id_detalle,
                                        v_cotizacion_det.precio_unitario,
                                        v_cotizacion_det.precio_unitario_mb,
                                        v_cotizacion_det.cantidad_det,
                                        v_cotizacion_det.id_solicitud,
                                        v_cotizacion_det.cd,
                                        v_cotizacion_det.id_day_week,
                                        v_cotizacion_det.nro_parte_cot,
                                        v_cotizacion_det.nro_parte_alterno_cot,
                                        v_cotizacion_det.referencia_cot,
                                        v_cotizacion_det.descripcion_cot,
                                        v_cotizacion_det.explicacion_detallada_part_cot,
                                        v_cotizacion_det.tipo_cot,
                                        v_cotizacion_det.id_unidad_medida_cot,
                                        v_cotizacion_det.revisado
                                      );
         			end loop;
        	end loop;



        end loop;



        FOR v_mod in (	select *
		 				from wf.testado_wf
						where id_proceso_wf = v_id_proceso_wf) loop


                        update wf.testado_wf  set
                        id_estado_anterior = v_mod.id_estado_wf - 1
                        where id_estado_anterior = v_mod.id_estado_anterior and id_estado_anterior is not null ;
        end loop;
        if v_registros.origen_pedido !='Almacenes Consumibles o Rotables'then
         FOR v_mod_f in (	select *
		 				from wf.testado_wf
						where id_proceso_wf = v_id_proceso_wf_clo) loop


                        update wf.testado_wf  set
                        id_estado_anterior = v_mod_f.id_estado_wf - 1
                        where id_estado_anterior = v_mod_f.id_estado_anterior and id_estado_anterior is not null ;
        end loop;
        end if;

/*       select d.url
       into v_url
       from wf.tdocumento_wf d
       where d.id_proceso_wf = v_parametros.id_proceso_wf and d.chequeado = 'si';

       if v_registros.origen_pedido ='Gerencia de Operaciones' THEN

       select  td.id_tipo_documento
       into
       v_id_tipo_docuemnteo
       from wf.ttipo_documento td
       inner join wf.tproceso_macro pm on pm.id_proceso_macro = td.id_proceso_macro
       where pm.codigo ='GO-RM' and td.nombre = 'Documentacion de Respaldo' and td.estado_reg = 'activo';

        elsif v_registros.origen_pedido ='Gerencia de Mantenimiento'then

        select  td.id_tipo_documento
        into
        v_id_tipo_docuemnteo
       from wf.ttipo_documento td
       inner join wf.tproceso_macro pm on pm.id_proceso_macro = td.id_proceso_macro
       where pm.codigo ='GM-RM' and td.nombre = 'Documentacion de Respaldo' and td.estado_reg = 'activo';
       else

       select  td.id_tipo_documento
        into
       v_id_tipo_docuemnteo
       from wf.ttipo_documento td
       inner join wf.tproceso_macro pm on pm.id_proceso_macro = td.id_proceso_macro
       where pm.codigo ='GA-RM' and td.nombre = 'Documentos de Respaldo' and td.estado_reg = 'activo';
       end if;

       update wf.tdocumento_wf  set
       url = v_url,
       chequeado = 'si',
       extension = 'pdf'
       where id_proceso_wf =  v_id_proceso_wf and id_tipo_documento = v_id_tipo_docuemnteo;
*/
         v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado');
         v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

            --Devuelve la respuesta
            return v_resp;
         END;

	/*********************************
 	#TRANSACCION:  'MAT_PAC_IME'
 	#DESCRIPCION:	generar pac
 	#AUTOR:		MMV
 	#FECHA:		10-01-2018 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_PAC_IME')then

		begin
			 --PERFORM mat.f_correo_solicitud(v_parametros.id_proceso_wf,v_parametros.importe,v_parametros.moneda);
			 PERFORM mat.f_correo_reformulacion_pac(v_parametros.id_proceso_wf,v_parametros.importe,v_parametros.moneda, v_parametros.tipo);

             v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado');
         	 v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_AUTSIES_IME'
 	#DESCRIPCION:	Pasar siguiente estado flujo automatico. gestion de materiales
 	#AUTOR:		breydi.vasquez
 	#FECHA:
	***********************************/

	elsif(p_transaccion='MAT_AUTSIES_IME')then

		begin

			 PERFORM mat.f_generar_flujo_wf_gestion_materiales(p_id_usuario, 537558, 1705563, 812, 370);

             v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado');
         	 v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

            --Devuelve la respuesta
            return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_VALPRESU_IME'
 	#DESCRIPCION:
 	#AUTOR:		breydi.vasquez
 	#FECHA:
	***********************************/
	elsif(p_transaccion='MAT_VALPRESU_IME')then

		begin

			select sol.presupuesto_aprobado,sol.id_solicitud
            	into v_llave_aprobado
            from mat.tsolicitud sol
            where sol.id_proceso_wf = v_parametros.id_proceso_wf_act;

			if v_parametros.aprobar = 'si' and v_llave_aprobado.presupuesto_aprobado <> 'aprobado' then

                  update mat.tsolicitud  set
                  presupuesto_aprobado = 'aprobado'
                  where id_solicitud = v_llave_aprobado.id_solicitud;

            else
            	if v_llave_aprobado.presupuesto_aprobado <> 'aprobado' then
                  update mat.tsolicitud  set
                  presupuesto_aprobado = 'sin_presupuesto_cc'
                  where id_solicitud = v_llave_aprobado.id_solicitud;
                end if;
            end if;


          --Definicion de la respuesta
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se Actualizo con exito');
          v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_llave_aprobado.id_solicitud::varchar);

          --Devuelve la respuesta
          return v_resp;

		end;

        /*********************************
        #TRANSACCION:  'MAT_DATOSNEC'
        #DESCRIPCION:
        #AUTOR:		Ismael Valdivia
        #FECHA:		17/03/2020
        ***********************************/
	elsif(p_transaccion='MAT_DATOSNEC')then

		begin

			SELECT vfcl.id_oficina, vfcl.oficina_nombre, tf.id_funcionario, vfcl.desc_funcionario1, vfcl.nombre_cargo
            INTO v_record
			FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            INNER JOIN orga.vfuncionario_cargo_lugar vfcl on vfcl.id_funcionario = tf.id_funcionario
            WHERE tu.id_usuario = p_id_usuario;

            SELECT tr.estado INTO v_estado_wf
            FROM rec.treclamo tr
            WHERE tr.id_estado_wf = v_parametros.id_usuario;

           	SELECT g.id_gestion, g.gestion
           	INTO v_record_gestion
           	FROM param.tgestion g
           	WHERE g.gestion = EXTRACT(YEAR FROM current_date);

			SELECT  tnf.numero
            INTO v_frd
            FROM rec.tnumero_frd tnf
            WHERE tnf.id_oficina = v_record.id_oficina;


            IF(v_frd = '' OR v_frd IS NULL)THEN
               v_frd = 1;
            END IF;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Datos de Oficina y Funcionario que registra Reclamos');
            v_resp = pxp.f_agrega_clave(v_resp,'id_oficina',v_record.id_oficina::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'oficina_nombre',v_record.oficina_nombre);
            v_resp = pxp.f_agrega_clave(v_resp,'id_funcionario',v_record.id_funcionario::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'desc_funcionario1',v_record.desc_funcionario1);
            v_resp = pxp.f_agrega_clave(v_resp,'nombre_cargo',v_record.nombre_cargo);
            v_resp = pxp.f_agrega_clave(v_resp,'estado',v_estado_wf);
            v_resp = pxp.f_agrega_clave(v_resp,'id_gestion',v_record_gestion.id_gestion::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'gestion',v_record_gestion.gestion::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_frd',v_frd::varchar);
            --Devuelve la respuesta de petición
            return v_resp;

		end;

        /*********************************
        #TRANSACCION:  'MAT_BORR_EST'
        #DESCRIPCION:
        #AUTOR:		Ismael Valdivia
        #FECHA:		02/04/2020
        ***********************************/
	elsif(p_transaccion='MAT_BORR_EST')then

		begin

		    /*Recuperamos para eliminar toda la informacion (Ismael Valdivia 02/04/2020)*/
            select sol.id_proceso_wf,
                   sol.id_proceso_wf_firma
                   into
                   v_id_proceso_wf_normal,
                   v_id_proceso_wf_firma
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;
            /*****************************************************************************/

            /*Aqui eliminamos todos los registros*/
            delete from mat.tdetalle_sol
            where id_solicitud = v_parametros.id_solicitud;

            delete from mat.tsolicitud
            where id_solicitud = v_parametros.id_solicitud;

            delete from wf.tdocumento_wf
            where id_proceso_wf = v_id_proceso_wf_normal;

            delete from wf.testado_wf
            where id_proceso_wf = v_id_proceso_wf_normal;

            delete from mat.tsolicitud_pac
            where id_proceso_wf = v_id_proceso_wf_normal;

			/*Aqui eliminamos los reistros de las firmas*/
            IF (v_id_proceso_wf_firma != null) then
                delete from wf.tdocumento_wf
                where id_proceso_wf = v_id_proceso_wf_firma;

                delete from wf.testado_wf
                where id_proceso_wf = v_id_proceso_wf_firma;

                delete from mat.tsolicitud_pac
                where id_proceso_wf = v_id_proceso_wf_firma;
            end if;
			/********************************************/
            delete from mat.tcotizacion_detalle
            where id_solicitud = v_parametros.id_solicitud;

            delete from mat.tcotizacion
            where id_solicitud = v_parametros.id_solicitud;
            /************************************************/

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se elimino el tramite correctamente');
            --Devuelve la respuesta de petición
            return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_CTRL_PRESU'
 	#DESCRIPCION:	control revision presupuestos
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		16-04-2020 10:44:30
	***********************************/

	elsif(p_transaccion='MAT_CTRL_PRESU')then

		begin

			select revisado_presupuesto
            		into
                    v_valor_revision
            from mat.tsolicitud
            where id_solicitud = v_parametros.id_solicitud;

            if v_valor_revision = 'si' then
              update mat.tsolicitud  set
              fecha_mod = now(),
              revisado_presupuesto = 'no'
              where id_solicitud = v_parametros.id_solicitud;
            end if;

            if v_valor_revision = 'no' then
              update mat.tsolicitud  set
              fecha_mod = now(),
              revisado_presupuesto = 'si'
              where id_solicitud = v_parametros.id_solicitud;
            end if;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud');
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_parametros.id_solicitud::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;


        /*********************************
        #TRANSACCION:  'MAT_UDT_TIP_IME'
        #DESCRIPCION:	control revision presupuestos
        #AUTOR:		Ismael Valdivia
        #FECHA:		16-04-2020 10:44:30
        ***********************************/

        elsif(p_transaccion='MAT_UDT_TIP_IME')then

            begin

                update mat.tsolicitud set
                tipo_solicitud = v_parametros.tipo_solicitud
                where id_solicitud = v_parametros.id_solicitud;

                --Definicion de la respuesta
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud');
                v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_parametros.id_solicitud::varchar);

                --Devuelve la respuesta
                return v_resp;

            end;
        /*********************************
        #TRANSACCION:  'MAT_PO_ALKYM_IME'
        #DESCRIPCION:	control revision presupuestos
        #AUTOR:		Ismael Valdivia
        #FECHA:		16-04-2020 10:44:30
        ***********************************/

        elsif(p_transaccion='MAT_PO_ALKYM_IME')then

            begin
            		select mate.id_po_alkym,
                    	   mate.nro_po
                           into
                           v_id_po_alkym,
                           v_nro_po_alkym
                    from mat.tsolicitud mate
                    where mate.id_solicitud = v_parametros.id_solicitud;

                  /*Aqui Actualizamos el Id_PO_Alkym obtenido del servicio 24/04/2020*/
                      if (v_id_po_alkym is null or trim(v_nro_po_alkym) = '') then
                              update mat.tsolicitud set
                              id_po_alkym = v_parametros.idPoAlkym,
                              nro_po = upper(v_parametros.nro_po)
                              where id_solicitud = v_parametros.id_solicitud;
                      end if;
                  /*******************************************************************/

                  select sol.estado, sol.origen_pedido,
                  sol.nro_tramite
                  into v_codigo_estado_siguiente,
                  v_origen_pedido,
                  v_nro_tramite
                  from mat.tsolicitud sol
                  where sol.id_solicitud = v_parametros.id_solicitud;

                --Definicion de la respuesta
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud');
                v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_parametros.id_solicitud::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'v_nro_po',upper(v_parametros.nro_po)::varchar);
                v_resp = pxp.f_agrega_clave(v_resp,'v_codigo_estado_siguiente',v_codigo_estado_siguiente);
                v_resp = pxp.f_agrega_clave(v_resp,'v_origen_pedido',v_origen_pedido);
                v_resp = pxp.f_agrega_clave(v_resp,'v_nro_tramite',v_nro_tramite);

                --Devuelve la respuesta
                return v_resp;

            end;

            /*********************************
            #TRANSACCION:  'MAT_GET_ALK_IME'
            #DESCRIPCION:	control revision presupuestos
            #AUTOR:		Ismael Valdivia
            #FECHA:		16-04-2020 10:44:30
            ***********************************/

        	elsif(p_transaccion='MAT_GET_ALK_IME')then

                begin
                       select sol.id_condicion_entrega_alkym,
                               sol.id_forma_pago_alkym,
                               sol.id_modo_envio_alkym,
                               sol.id_puntos_entrega_alkym,
                               sol.id_tipo_transaccion_alkym,
                               sol.id_orden_destino_alkym,
                               sol.codigo_condicion_entrega_alkym,
                               sol.codigo_forma_pago_alkym,
                               sol.codigo_modo_envio_alkym,
                               sol.codigo_puntos_entrega_alkym,
                               sol.codigo_tipo_transaccion_alkym,
                               sol.codigo_orden_destino_alkym
                        into
                        	   v_datos_recuperados
                        from mat.tsolicitud sol
                        where sol.id_solicitud = v_parametros.id_solicitud;
                  /*******************************************************************/

                    --Definicion de la respuesta
                    v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud');
                    v_resp = pxp.f_agrega_clave(v_resp,'id_condicion_entrega_alkym',v_datos_recuperados.id_condicion_entrega_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'id_forma_pago_alkym',v_datos_recuperados.id_forma_pago_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'id_modo_envio_alkym',v_datos_recuperados.id_modo_envio_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'id_puntos_entrega_alkym',v_datos_recuperados.id_puntos_entrega_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_transaccion_alkym',v_datos_recuperados.id_tipo_transaccion_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'id_orden_destino_alkym',v_datos_recuperados.id_orden_destino_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'codigo_condicion_entrega_alkym',v_datos_recuperados.codigo_condicion_entrega_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'codigo_forma_pago_alkym',v_datos_recuperados.codigo_forma_pago_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'codigo_modo_envio_alkym',v_datos_recuperados.codigo_modo_envio_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'codigo_puntos_entrega_alkym',v_datos_recuperados.codigo_puntos_entrega_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'codigo_tipo_transaccion_alkym',v_datos_recuperados.codigo_tipo_transaccion_alkym::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'codigo_orden_destino_alkym',v_datos_recuperados.codigo_orden_destino_alkym::varchar);

                    --Devuelve la respuesta
                    return v_resp;

           		 end;

            /*********************************
            #TRANSACCION:  'MAT_DOCU_VERIFI'
            #DESCRIPCION:	control verificar documentos
            #AUTOR:		Ismael Valdivia
            #FECHA:		16-04-2020 10:44:30
            ***********************************/

        	elsif(p_transaccion='MAT_DOCU_VERIFI')then

                begin

                	if (v_parametros.estado_sig = 'despachado') then

                    	select
                              dwf.chequeado
                              into
                              v_chekeado
                        from wf.tdocumento_wf dwf
                        INNER JOIN  wf.ttipo_documento  td
                        on td.id_tipo_documento  = dwf.id_tipo_documento
                        inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = dwf.id_proceso_wf
                        where  dwf.id_proceso_wf = v_parametros.id_proceso_wf and td.nombre = 'FORM-400';

                        v_nombre_documento = 'FORM-400';


                    else

                    	select pr.id_tipo_proceso into v_id_tipo_proceso_docu
                      from wf.tproceso_wf pr
                      where pr.id_proceso_wf = v_parametros.id_proceso_wf;
                  /*******************************************************************/

                      select es.id_tipo_estado into v_id_tipo_estado_documento
                      from wf.ttipo_estado es
                      --inner join wf.tfuncionario_tipo_estado fun on fun.id_tipo_estado = es.id_tipo_estado
                      where es.id_tipo_proceso = v_id_tipo_proceso_docu and es.codigo = v_parametros.estado_sig;

                      SELECT
                            td.id_tipo_documento,
                            td.nombre,
                            td.tipo
                            into
                            v_datos_documento
                      FROM  wf.ttipo_documento_estado tde
                      INNER JOIN  wf.ttipo_documento  td
                      on td.id_tipo_documento  = tde.id_tipo_documento
                      and td.estado_reg = 'activo' and tde.estado_reg = 'activo'
                      and (tde.momento = 'exigir' or
                      tde.momento = 'exigir_fisico')
                      and tde.id_tipo_estado = v_id_tipo_estado_documento;

                      v_nombre_documento = v_datos_documento.nombre;

                      select
                            dwf.chequeado
                           -- dwf.chequeado_fisico
                             into
                             v_chekeado
                            from wf.tdocumento_wf dwf
                            inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = dwf.id_proceso_wf
                            where  dwf.id_proceso_wf = v_parametros.id_proceso_wf
                            and  dwf.id_tipo_documento = v_datos_documento.id_tipo_documento;

                    end if;



                    --Definicion de la respuesta
                    v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud');
                    v_resp = pxp.f_agrega_clave(v_resp,'v_chekeado',v_chekeado::varchar);
                    v_resp = pxp.f_agrega_clave(v_resp,'nombre_documento',v_nombre_documento::varchar);
                    --Devuelve la respuesta
                    return v_resp;

           		 end;

         /*********************************
        #TRANSACCION:  'MAT_UPD_CP_IME'
        #DESCRIPCION:	Actualizamos el Charly Papa con los datos que esta en Alkym
        #AUTOR:	 Ismael Valdivia
        #FECHA:		25/06/2021
        ***********************************/
        elsif(p_transaccion='MAT_UPD_CP_IME')then
            begin

			 /*Creamos para Ir almacenando el Part Number desde el Servicio de Alkym*/
              CREATE TEMP  TABLE MatriculaAlkym (  IdAvion  int4,
                                                                                 IdTipoAvion  int4,
                                                                                 Matricula varchar,
                                                                                 Modelo varchar,
                                                                                 NroVariable varchar,
                                                                                 IdComponente int4,
                                                                                 NombreAvion varchar,
                                                                                 TipoAvion	varchar,
                                                                                 CodigoTipoAvion varchar
                                                                            )ON COMMIT DROP;


                    if (v_parametros.cantidad_json is not null) then
                          v_contador = v_parametros.cantidad_json;
                    end if;

                    for i in 0..(v_contador-1) loop

                     v_IdAvion = v_parametros.json_obtenido->i->> 'IdAvion';
                     v_IdTipoAvion = v_parametros.json_obtenido->i->> 'IdTipoAvion';
                     v_MatriculaPn = v_parametros.json_obtenido->i->> 'Matricula';
                     v_Modelo = v_parametros.json_obtenido->i->>  'Modelo';
                     v_NroVariable = v_parametros.json_obtenido->i->> 'NroVariable';
                     v_IdComponent = v_parametros.json_obtenido->i->> 'IdComponent';
                     v_NombreAvion = v_parametros.json_obtenido->i->> 'Nombre';
                     v_TipoAvion = v_parametros.json_obtenido->i->> 'TipoAvion';
                     v_CodigoTipoAvion = v_parametros.json_obtenido->i->> 'CodigoTipoAvion';


                      insert into MatriculaAlkym (IdAvion ,
                                                                   IdTipoAvion ,
                                                                   Matricula,
                                                                   Modelo,
                                                                   NroVariable,
                                                                   IdComponente,
                                                                   NombreAvion,
                                                                   TipoAvion,
                                                                   CodigoTipoAvion
                                                					)
                                        VALUES(v_IdAvion::integer,
                                                      v_IdTipoAvion::integer,
                                                      v_MatriculaPn::varchar,
                                                      v_Modelo::varchar,
                                                      v_NroVariable::varchar,
                                                      v_IdComponent::integer,
                                                      v_NombreAvion::varchar,
                                                      v_TipoAvion::varchar,
                                                      v_CodigoTipoAvion::varchar
                                               );

                    end loop;
				  /*************************************************************************************************/

                  /*Creamos los Charly Papa Faltantes */
                  for v_registros in ( select pnalk.matricula,
                                                        pnalk.idavion,
                                                        pnalk.tipoavion
                                              from MatriculaAlkym pnalk
                                              where pnalk.idavion not in (   select ot.id_avion_alkym
                                                                                          FROM conta.torden_trabajo ot
                                                                                          where ot.id_avion_alkym is not null
                                                                                          )
                  ) loop

                   	insert into conta.torden_trabajo(
                                                                    estado_reg,
                                                                    fecha_final,
                                                                    fecha_inicio,
                                                                    desc_orden,
                                                                    motivo_orden,
                                                                    fecha_reg,
                                                                    id_usuario_reg,
                                                                    id_usuario_mod,
                                                                    fecha_mod,
                                                                    codigo,
                                                                    tipo,
                                                                    movimiento,
                                                                    id_orden_trabajo_fk,
                                                                    id_avion_alkym
                                                                ) values(
                                                                    'activo',
                                                                	null,
                                                                    now()::date,
                                                                    v_registros.tipoavion ||' - '||  v_registros.matricula,
                                                                    '',
                                                                    now(),
                                                                    p_id_usuario,
                                                                    null,
                                                                    null,
                                                                    upper(v_registros.matricula),
                                                                    'estadistica',
                                                                    'si',
                                                                    null,
                                                                    v_registros.idavion

                                                                )RETURNING id_orden_trabajo into v_id_orden_trabajo;

                    /*Insertamos la relacion de la Ot con el Grupo*/
                    insert into conta.tgrupo_ot_det(
                                                                    estado_reg,
                                                                    id_orden_trabajo,
                                                                    id_grupo_ot,
                                                                    fecha_reg,
                                                                    usuario_ai,
                                                                    id_usuario_reg,
                                                                    id_usuario_ai,
                                                                    id_usuario_mod,
                                                                    fecha_mod
                                                                    ) values(
                                                                    'activo',
                                                                    v_id_orden_trabajo,
                                                                    1,
                                                                    now(),
                                                                    v_parametros._nombre_usuario_ai,
                                                                    p_id_usuario,
                                                                    v_parametros._id_usuario_ai,
                                                                    null,
                                                                    null
                    );

                    /*insert into conta.tgrupo_ot_det(
                                                                    estado_reg,
                                                                    id_orden_trabajo,
                                                                    id_grupo_ot,
                                                                    fecha_reg,
                                                                    usuario_ai,
                                                                    id_usuario_reg,
                                                                    id_usuario_ai,
                                                                    id_usuario_mod,
                                                                    fecha_mod
                                                                    ) values(
                                                                    'activo',
                                                                    v_id_orden_trabajo,
                                                                    4,
                                                                    now(),
                                                                    v_parametros._nombre_usuario_ai,
                                                                    p_id_usuario,
                                                                    v_parametros._id_usuario_ai,
                                                                    null,
                                                                    null
                    );*/
                    /*****************************************************/
                  end loop;
                  /********************************************/


                  /*Verificamos si hay actualizaciones de los Charly Papa*/
                   for v_registros_update in (  select ot.codigo,
                                                          alk.matricula,
                                                          ot.id_avion_alkym
                                                from conta.torden_trabajo ot
                                                inner join MatriculaAlkym alk on alk.idavion = ot.id_avion_alkym
                                                where ot.codigo != alk.matricula
                  ) loop

                              update conta.torden_trabajo set
                                  codigo =  upper(v_registros_update.matricula)
                               where id_avion_alkym = v_registros_update.id_avion_alkym;

                  end loop;
                  /*****************************************************************/





                  v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Solicitud');
                  v_resp = pxp.f_agrega_clave(v_resp,'Mensaje CP','Charly Papa Actualizados y Registrados Correctamente');
                  --Devuelve la respuesta
                  return v_resp;
        end;


         /*********************************
        #TRANSACCION:  'MAT_CTRL_PRESUPUESTO'
        #DESCRIPCION:	Control de presupuesto
        #AUTOR:	 Ismael Valdivia
        #FECHA:		19/11/2021
        ***********************************/
        elsif(p_transaccion='MAT_CTRL_PRESUPUESTO')then
            begin

            select sol.* into
            v_solicitud
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;

			if (pxp.f_get_variable_global('interviene_presupuesto') = 'si') then
              /*Aqui ponemos un control para que presupuesto (Ismael valdivia 27/04/2020)*/
               if (v_solicitud.revisado_presupuesto = 'no') then
                  raise exception 'La solicitud actual aún se encuentra en revisión por parte de presupuestos.';
               end if;
              /***************************************************************************/
            end if;

            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Exito');
            v_resp = pxp.f_agrega_clave(v_resp,'Mensaje','Exito');
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

ALTER FUNCTION mat.ft_solicitud_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
