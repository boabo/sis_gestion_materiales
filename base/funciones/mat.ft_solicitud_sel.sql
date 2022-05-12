CREATE OR REPLACE FUNCTION mat.ft_solicitud_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestión de Materiales
 FUNCION: 		mat.ft_solicitud_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tsolicitud'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;

    v_campos 			record;
    v_firmas			VARCHAR[];
    v_id_solicitud		INTEGER;
    p_id_proceso_wf 	integer;
    v_id_proceso_wf_prev integer;
    v_orden				varchar;
	v_filtro			varchar;
    v_funcionario_wf    record;
    v_record    		record;
    v_id_usuario_rev	record;
    v_origen 			varchar;
    v_filtro_repo       VARCHAR;
    v_origen_pedido     VARCHAR;
    v_id_proceso_wf_firma 	integer;
    v_usuario				integer;
    v_nro_cite_dce		varchar;
    v_num_tramite		varchar;
    nro_part			varchar;
    parte				varchar;

    v_id_funcionario	integer;
    v_nom_unidad		VARCHAR[];
    v_funcionario_sol	varchar;
    v_monto_ref		    record;
    --
    v_id_funcionario_qr integer;
    v_id_funcionario_qr_oficial integer;
    v_id_funcionario_qr_rempla integer;
    v_nombre_funcionario_qr varchar;
    v_nombre_funcionario_qr_oficial varchar;
    v_nombre_funcionario_qr_rempla varchar;
    v_fecha_firma_qr 		text;
    v_fecha_firma_qr_oficial 		text;
    v_fecha_firma_qr_rempla 		text;

    v_id_funcionario_dc_qr 		integer;
    v_id_funcionario_dc_qr_oficial 		integer;
    v_id_funcionario_dc_qr_rempla 		integer;
    v_nombre_funcionario_dc_qr 	varchar;
    v_nombre_funcionario_dc_qr_oficial 	varchar;
    v_nombre_funcionario_dc_qr_rempla 	varchar;
    v_fecha_firma_dc_qr 		text;
    v_fecha_firma_dc_qr_oficial 		text;
    v_fecha_firma_dc_qr_rempla 		text;

    v_id_funcionario_ag_qr 		integer;
    v_id_funcionario_ag_qr_oficial 		integer;
    v_id_funcionario_ag_qr_rempla 		integer;
    v_nombre_funcionario_ag_qr 	varchar;
    v_nombre_funcionario_ag_qr_oficial 	varchar;
    v_nombre_funcionario_ag_qr_rempla 	varchar;
    v_fecha_firma_ag_qr 		text;
    v_fecha_firma_ag_qr_oficial 		text;
    v_fecha_firma_ag_qr_rempla 		text;

    v_id_funcionario_rev_qr_oficial integer;
   	v_nombre_funcionario_rev_qr 	varchar;
   	v_nombre_funcionario_rev_qr_oficial 	varchar;
   	v_nombre_funcionario_rev_qr_rempla 	varchar;
    v_fecha_firma_rev_qr 		text;
    v_fecha_firma_rev_qr_oficial 		text;
    v_fecha_firma_rev_qr_rempla 		text;

    v_id_funcionario_abas_qr_oficial integer;
    v_nombre_funcionario_abas_qr 	varchar;
    v_nombre_funcionario_abas_qr_oficial 	varchar;
    v_nombre_funcionario_af_qr_ocifial  varchar;
    v_nombre_funcionario_abas_qr_rempla 	varchar;
    v_fecha_firma_abas_qr 		text;
    v_fecha_firma_abas_qr_oficial 		text;
    v_fecha_firma_abas_qr_rempla 		text;

	v_id_proceso_wf_firma_cotizacion integer;

    v_id_despacho				integer;
    v_id_proceso_wf_adq			integer;

    v_nombre_funcionario_af_qr 	varchar;
    v_fecha_firma_af_qr			text;

    v_nombre_funcionario_presu_qr 	varchar;
    v_fecha_firma_presu_qr				text;
    v_codigo_pre						varchar;
    v_funcionario						varchar;
     v_historico 						varchar;
     v_id_funcionario_ai				integer;
     registro							record;
     v_pase						integer;
	v_id_funcionario_dc_rempla	integer;
	v_id_funcionario_rev_rempla    integer;
	v_id_funcionario_abas_rempla	integer;

    v_id_funcionario_presu_qr_oficial integer;
    v_nombre_funcionario_presu_qr_oficial varchar;
    v_fecha_firma_presu_qr_oficial		text;
    v_nombre_funcionario_presu_qr_rempla varchar;
    v_fecha_firma_presu_qr_rempla	     text;
    v_id_funcionario_presu_rempla integer;
	v_usuario_dc_ai			integer;
	v_id_usuario_abas_ai	integer;
	v_id_usuario_rev_ai		integer;
	v_id_usuario_presu_ai   integer;
    remplaso				record;
   v_id_funcionario_oficial	integer;
    v_id_funcionario_af_qr_oficial   integer;
    v_id_usuario_af_ai				record;
    v_id_usuario_sol				record;
    v_funcionario_sol_oficial   varchar;
    v_funcionario_oficial	varchar;
    v_fecha_firma_pru			text;
    v_rango_fecha			text;
    v_fecha_po				text;
    v_fecha_solicitud		text;

	v_codigo_rpc			varchar='';
    v_cod_tramite			varchar;

    v_id_funcionario_resp_qr_oficial 	 integer;
    v_nombre_funcionario_resp_qr_oficial varchar;
    v_fecha_firma_resp_qr				 text;
    v_nombre_funcionario_resp_qr		 varchar;

    v_vbgerencia						 record;
    v_vbrpc								 record;
    v_revision							 record;
	v_fecha_ini							 date;

    v_record_sol						 record;
    v_index								integer;
    v_record_funcionario				record;
    v_firma_fun 						varchar;
    v_nombre_entidad					varchar;
    v_direccion_admin				    varchar;
    v_unidad_ejecutora					varchar;

    /*Agregando variables Ismael Valdivia (06/02/2020)*/
    v_vb_dpto_abastecimientos			record;
    v_estado_firma						varchar;
    v_poa_aprobado						integer;
    v_poa_elaborado						integer;
    v_vobo_poa							integer;
    v_id_fun_gerencia_mantenimiento		integer;


    v_email_funcionario					varchar;
    v_telefono_funcionario				varchar;
    v_direccion_funcio					varchar;
    v_id_funcionario_solicitante		integer;
    v_numero_interno					varchar;
    v_num_tramite_rep					varchar;
    v_tipo_evaluacion					varchar;
    v_fecha_order						varchar;
    v_prioridad							varchar;
    v_nom_provee						varchar;
    v_direccion_provee					varchar;
    v_email_provee						varchar;
    v_telf_provee						varchar;
    v_estado_provee						varchar;
    v_country_provee					varchar;
    v_id_solicitud_rec					integer;
    v_num_part							varchar;
    v_num_part_alt						varchar;
    v_cantidad							varchar;
    v_descripcion						varchar;
    v_serial							varchar;
    v_cd								varchar;
    v_precio_unitario					varchar;
    v_precio_total						varchar;
    v_suma_totales						numeric;
    v_observaciones_sol 				varchar;
    v_fecha_cotizacion					varchar;
    v_total_literal						varchar;

    v_contador							integer;
    v_id_condicion_entrega				integer;
    v_name_condicion_entrega			varchar;
    v_rep								varchar;
    v_contacto_proveedor				varchar;
    v_payment_terms						varchar;
    v_incoterms							varchar;
    v_ship_to							varchar;
    v_fecha_entrega						varchar;

    v_id_funcionario_rpcd_oficial		integer;
    v_funcionario_sol_rpcd_oficial		varchar;
    v_funcionario_rpcd_oficial			varchar;
    v_fecha_firma_rpcd_pru				varchar;
    v_rpcd								record;
    v_nit_proveedor						varchar;
    v_cabecera							record;
    v_id_fun_pre						integer;
    v_funcionario_pre					varchar;
    v_datos								record;
    v_fax_provee						varchar;
    v_dire_condicion_entrega			varchar;
    v_direccion							varchar;
    v_nro_parte_sol						varchar;
    v_descripcion_sol					varchar;
    v_serial_sol						varchar;
    v_condicion_sol						varchar;
    v_nro_tramite						varchar;
    v_fecha_sol_rep						date;
    v_id_estado_wf						integer;
    v_fecha_sol							date;
    v_proces_wf							integer;
    v_id_gerente_rep					integer;
    v_gerente							varchar;
    v_firma_gerente						varchar;
    v_desc_uo							varchar;
    v_desc_cargo_gerente				varchar;
    v_gestion							varchar;
    v_evaluacion						varchar;
    v_tipo_taller						varchar;
    v_nro_parte_det						varchar;
    v_nro_parte_alterna_det				varchar;
    v_descripcion_det					varchar;
    v_serial_det						varchar;
    v_cotizaciones_recibidas			varchar;
    v_literal							varchar;
    v_taller_asignado					varchar;
    v_estado_firma_paralelo				varchar;
    v_estado_actual						varchar;
    v_nro_lote							varchar;
    v_fecha_comite						varchar;
    v_fill 						 		varchar;
    v_tiempo_entrega					 numeric;
    v_gerencia							varchar;
    v_IdProducto				 integer;
    v_IdProductoPN			  integer;

    v_PN 						varchar;
    v_DescripcionPN       varchar;
    v_TipoProductoPN     varchar;
    v_rotulo_proveedor      varchar;
    v_informe_rep           varchar;
    v_nro_rep               varchar;
    v_fecha_entrega_rep         varchar;
    v_id_solicitud_rep      integer;
    v_total_venta_rep       numeric;

    v_gestion_rep           numeric;

    v_lote_rep              varchar;

     v_Codigo_UM_PN varchar;
     v_Id_UM_PN integer;
     v_Id_TipoProducto_PN integer;
     v_Reparable		varchar;
     v_fecha_literal	varchar;

     v_metodo_adju		varchar;
     v_tipo_adju		varchar;
     v_fecha_salida_gm  date;
     v_fecha_solicitud_recu	date;
     v_cantidad_items	integer;
     v_nombre_macro		varchar;
     v_cantidad_sumados_adjudicado	integer;
     v_dias_sumados		integer;
     v_cargo_solicitante	varchar;
     v_fecha_po_rep		varchar;
     v_fecha_cotizacion_rep	varchar;
     v_cotizacion_fecha	varchar;
     v_fecha_cotizacion_oficial	varchar;
     v_fecha_firma_envio	varchar;
     v_fecha_nuevo_flujo	varchar;
     v_es_mayor				varchar;
     v_id_solicitud_reporte	integer;
     v_existe_bear			integer;
     v_tiene_bear			varchar;
     v_id_uo_padre			integer;
     v_nivel_organizacional	integer;
     v_id_funcionario_tecnico_qr integer;
     v_nombre_funcionario_tecnico_qr_oficial varchar;
     v_fecha_firma_tecnico_qr	varchar;
    /**************************************************/
    /*Aumentando la firma del tecnico de abastecimeintos*/
    v_id_funcionario_tecnico_abas	integer;
    v_nombre_funcionario_tecnico_abas	varchar;
    v_fecha_firma_tecnico_abas		varchar;
    v_nombre_funcionario_tecnico_abastecimiento	varchar;
    v_aplica_nuevo_flujo	varchar;

    v_id_funcionario_aux_abas		integer;
    v_funcionario_sol_aux_abas		varchar;
    v_funcionario_oficial_aux_abas	varchar;
    v_fecha_firma_aux_abas			varchar;
    v_funcionario_auxiliar_abastecimiento	varchar;
    v_funcionario_abas				varchar;
    v_id_funcionario_oficial_revision	integer;
    v_funcionario_oficial_revision		varchar;
    v_serial_original				varchar;
    v_id_detalle					varchar;
    v_aplica_cambio_etiqueta		varchar;
    v_etiqueta						varchar;
    v_corregir_reporte				varchar;
    v_id_estado_aprobado 			integer;
    v_fecha_aprobacion				date;

BEGIN

	v_rango_fecha = '01/11/2018';

    /*Aumentando para poner la condicion en los reportes y no variar*/
    v_fecha_salida_gm = pxp.f_get_variable_global('fecha_salida_gm')::date;
    /****************************************************************/

    /*Aumentando para que los reportes cambien con el lo que es el Iterinato*/
    v_fecha_nuevo_flujo = pxp.f_get_variable_global('fecha_nuevo_flujo_gm')::date;
    /************************************************************************/

	v_nombre_funcion = 'mat.ft_solicitud_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_SOL_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/

	if(p_transaccion='MAT_SOL_SEL')then

    	begin

        SELECT		tf.id_funcionario,
 					fun.desc_funcionario1,
                    fun.nombre_cargo
                    INTO
                    v_record
                    FROM segu.tusuario tu
                    INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                    INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = tf.id_funcionario
                    WHERE tu.id_usuario = p_id_usuario and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion)
                    limit 1;

         	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN
             v_historico =  v_parametros.historico;
            ELSE
            v_historico = 'no';
            END IF;


        	IF 	p_administrador THEN
				v_filtro = ' 0=0 AND ';

            ELSIF (v_historico = 'si') THEN

            	v_filtro = ' 0=0 AND ';

			/*aumentnado para la interfaz de adquisiciones*/
            ELSIF (v_parametros.tipo_interfaz = 'PedidosAdquisiciones') then
            	v_filtro = '(tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
            /****************************************************/

            /*Aumentando condicion para presupuestos luego cambiar*/
            ELSIF (v_parametros.tipo_interfaz = 'Presupuesto_Mantenimiento') THEN
            		v_filtro = ' 0=0 AND ';

            ELSIF (v_parametros.tipo_interfaz = 'VistoBueno') THEN

            	 --v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND ';
                  v_filtro = 'sol.id_funcionario_solicitante = '||v_record.id_funcionario||' AND ';


                ELSIF (v_parametros.tipo_interfaz = 'PedidoRepuesto' or v_parametros.tipo_interfaz =  'PedidoOperacion' or v_parametros.tipo_interfaz = 'PedidoMantenimiento' or v_parametros.tipo_interfaz ='PerdidoAlmacen' or v_parametros.tipo_interfaz ='PedidoDgac')THEN

                        IF (v_parametros.pes_estado = 'pedido_ma_compra' OR v_parametros.pes_estado = 'pedido_op_compra' OR v_parametros.pes_estado = 'pedido_re_compra' OR v_parametros.pes_estado = 'pedido_dgac_compra' OR v_parametros.pes_estado = 'pedido_al_compra') then
                        	v_filtro = '(tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                        ELSIF (v_parametros.pes_estado = 'pedido_ma_concluido') THEN
                        	v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                        ELSIF v_parametros.pes_estado = 'pedido_re_comite' THEN
                    		v_filtro = '';
                	    ELSE
                        	v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                        END IF;

               ELSIF (v_parametros.tipo_interfaz = 'SolicitudvoboComite') THEN
               		v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND tew.estado_reg = ''activo'' AND ';


               ELSIF (v_parametros.tipo_interfaz = 'SolicitudvoboComiteAeronavegabilidad') THEN
                      v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND ewb.estado_reg = ''activo'' AND ';

    				ELSIF  (v_parametros.tipo_interfaz = 'ProcesoCompra')THEN
          					v_filtro = '';
                    ELSIF  (v_parametros.tipo_interfaz = 'Almacen')THEN
                    		v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND tew.estado_reg = ''activo'' AND ';
                    ELSIF  (v_parametros.tipo_interfaz = 'SolArchivado')THEN
                        v_filtro = '';
                    ELSIF  (v_parametros.tipo_interfaz = 'SolicitudFec')THEN
                        v_filtro = '';
                    ELSIF  (v_parametros.tipo_interfaz = 'ConsultaRequerimientos')THEN
                        v_filtro = '';

                    ELSIF v_parametros.pes_estado = 'borrador_reg' THEN
                    v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||'and ';

                    ELSIF v_parametros.pes_estado = 'vobo_area_reg'   THEN
                     v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||'AND';

                    ELSIF v_parametros.pes_estado = 'revision_reg' THEN
                    v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||'AND';

                    ELSIF v_parametros.pes_estado = 'finalizado_reg' THEN
                    v_filtro = 'sol.id_usuario_reg ='||p_id_usuario||
                            ' AND';
                    ELSE
                    v_filtro = 'tew.id_funcionario ='||p_id_usuario||'OR ewb.id_funcionario ='||p_id_usuario||'and';
            END IF;

v_consulta:='select		sol.id_solicitud,
                                sol.id_funcionario_sol,
                                sol.id_proveedor,
                                sol.id_proceso_wf,
                                sol.id_estado_wf,
                                sol.nro_po,
                                sol.tipo_solicitud,
                                sol.fecha_entrega_miami,
                                sol.origen_pedido,
                                sol.fecha_requerida,
                                sol.observacion_nota,
                                sol.fecha_solicitud,
                                sol.estado_reg,
                                sol.observaciones_sol,
                                sol.fecha_tentativa_llegada,
                                sol.fecha_despacho_miami,
                                sol.justificacion,
                                sol.fecha_arribado_bolivia,
                                sol.fecha_desaduanizacion,
                                sol.fecha_entrega_almacen,
                                sol.cotizacion,
                                sol.tipo_falla,
                                sol.nro_tramite,
                                sol.id_matricula,
                                sol.nro_solicitud,
                                sol.motivo_solicitud,
                                sol.fecha_en_almacen,
                                sol.estado,
                                sol.id_usuario_reg,
                                sol.usuario_ai,
                                sol.fecha_reg,
                                sol.id_usuario_ai,
                                sol.fecha_mod,
                                sol.id_usuario_mod,
                                usu1.cuenta as usr_reg,
                                usu2.cuenta as usr_mod,
                                initcap (f.desc_funcionario1) as desc_funcionario1,
                                ot.desc_orden as matricula,
                                sol.tipo_reporte,
                                sol.mel,
                                sol.nro_no_rutina,
                               (select pxp.list ( po.desc_proveedor)
                                from mat.tcotizacion c
                                inner join param.vproveedor po on po.id_proveedor = c.id_proveedor
                                where c.adjudicado = ''si'' and c.id_solicitud =  sol.id_solicitud)::varchar as desc_proveedor,
                                sol.nro_partes,
                                sol.nro_parte_alterno,
                                sol.nro_justificacion,
                                sol.fecha_cotizacion,
                                (select count(*)
                                from unnest(pwf.id_tipo_estado_wfs) elemento
                                where elemento = tew.id_tipo_estado) as contador_estados,
                                mat.control_fecha_requerida(now()::date, sol.fecha_requerida)::VARCHAR as control_fecha,
                                sol.estado_firma,
                                sol.id_proceso_wf_firma,
                                sol.id_estado_wf_firma,
                               (select count(*)
                                from unnest(pwfb.id_tipo_estado_wfs) elemento
                                where elemento = ewb.id_tipo_estado) as contador_estados_firma,
                                ti.nombre_estado,
                                tip.nombre_estado as nombre_estado_firma,
                                sol.fecha_po,
                                sol.tipo_evaluacion,
                                sol.taller_asignado,
                               (select pxp.list(pr.id_proveedor::text)
                                from mat.tgestion_proveedores_new pr
                                where pr.id_solicitud = sol.id_solicitud)::varchar as lista_correos,
                                sol.condicion,
                                sol.lugar_entrega,
                                sol.mensaje_correo,
                                sol.tipo,
                                 (select pxp.list ( c.id_cotizacion::varchar)
                                from mat.tcotizacion c
                                where c.id_solicitud = sol.id_solicitud and c.adjudicado = ''si'')::varchar as id_cotizacion,
                                COALESCE(pa.monto,0) as monto_pac,
                         		COALESCE(mo.codigo_internacional,'''') as moneda,
                                pa.tipo as tipo_mov,
                                pa.observaciones as obs_pac,
                                /*Aumentando este campo para recuperar el departamento (Ismael Valdivia 31/01/2020)*/
                                sol.id_depto,
                                sol.id_gestion,
                                sol.id_moneda,
                                initcap (funsol.desc_funcionario1) as funcionario_solicitante,
                                sol.revisado_presupuesto,
                                sol.nro_lote,
                                sol.id_condicion_entrega_alkym,
                                sol.id_forma_pago_alkym,
                                sol.codigo_condicion_entrega_alkym,
                                sol.codigo_forma_pago_alkym,
                                sol.fecha_entrega::date,
                                sol.mel_observacion,
                                sol.origen_solicitud::varchar,
                                /***********************************************************************************/
                                /*Aumentando para el reporte de invitacion (Ismael Valdivia 09/11/2020)*/
                                sol.tiempo_entrega::numeric,
                                /***********************************************************************/
                                /*Aumentando los dos campos para recuperar en la interfaz (Ismael Valdivia 13/10/2021)*/
                                sol.metodo_de_adjudicación,
                                sol.tipo_de_adjudicacion,
                                sol.remark::varchar,
                                /**************************************************************************************/

                                pag.id_obligacion_pago,

                                (CASE
                                     WHEN sol.fecha_solicitud::date >= '''||v_fecha_nuevo_flujo||'''::date  THEN
                                     ''si''
                                     ELSE
                                     ''no''
                                END)::varchar as nuevo_flujo

                                from mat.tsolicitud sol
                                inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
                                inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                                inner join wf.testado_wf tew on tew.id_estado_wf = sol.id_estado_wf
                                inner join wf.ttipo_estado ti on ti.id_tipo_estado = tew.id_tipo_estado
                                inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf
                                left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                                left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                                left join param.vproveedor pro on pro.id_proveedor =sol.id_proveedor
                                left join wf.testado_wf ewb on ewb.id_estado_wf = sol.id_estado_wf_firma
                                left join wf.tproceso_wf pwfb on pwfb.id_proceso_wf = sol.id_proceso_wf_firma
                                left join wf.ttipo_estado tip on tip.id_tipo_estado = ewb.id_tipo_estado
                                left join mat.tsolicitud_pac pa on pa.id_proceso_wf = sol.id_proceso_wf
                           		left join param.tmoneda mo on mo.id_moneda = pa.id_moneda
                                left join orga.vfuncionario funsol on funsol.id_funcionario = sol.id_funcionario_solicitante

                               	left join tes.tobligacion_pago pag on pag.numero = sol.nro_tramite and pag.estado_reg = ''activo''

                                where '||v_filtro;

			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;


			RAISE NOTICE 'v_consulta %',v_consulta;
			return v_consulta;
		end;
	/*********************************
 	#TRANSACCION:  'MAT_SOL_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/

	elsif(p_transaccion='MAT_SOL_CONT')then

		begin
            SELECT		tf.id_funcionario,
 					fun.desc_funcionario1,
                    fun.nombre_cargo
                    INTO
                    v_record
                    FROM segu.tusuario tu
                    INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                    INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = tf.id_funcionario
                    WHERE tu.id_usuario = p_id_usuario and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion)
                    limit 1;

         	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN
             v_historico =  v_parametros.historico;
            ELSE
            v_historico = 'no';
            END IF;






        IF 	p_administrador THEN
				v_filtro = ' 0=0 AND ';

                 /*aumentnado para la interfaz de adquisiciones*/
            ELSIF (v_parametros.tipo_interfaz = 'PedidosAdquisiciones') then
            	v_filtro = '(tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
            /****************************************************/

            ELSIF (v_historico = 'si') THEN

            	v_filtro = ' 0=0 AND ';

            ELSIF (v_parametros.tipo_interfaz = 'VistoBueno') THEN

            	 --v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND ';
                  v_filtro = 'sol.id_funcionario_solicitante = '||v_record.id_funcionario||' AND ';


            ELSIF (v_parametros.tipo_interfaz = 'PedidoRepuesto' or v_parametros.tipo_interfaz =  'PedidoOperacion' or v_parametros.tipo_interfaz = 'PedidoMantenimiento' or v_parametros.tipo_interfaz ='PerdidoAlmacen' or v_parametros.tipo_interfaz ='PedidoDgac')THEN


                        IF (v_parametros.pes_estado = 'pedido_ma_compra' OR v_parametros.pes_estado = 'pedido_op_compra' OR v_parametros.pes_estado = 'pedido_re_compra' OR v_parametros.pes_estado = 'pedido_dgac_compra' OR v_parametros.pes_estado = 'pedido_al_compra') then
                        	v_filtro = '(tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                        ELSIF (v_parametros.pes_estado = 'pedido_ma_concluido') THEN
                        	v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                        ELSIF v_parametros.pes_estado = 'pedido_re_comite' THEN
                    		v_filtro = '';
                	    ELSE
                        	v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                        END IF;

               ELSIF (v_parametros.tipo_interfaz = 'SolicitudvoboComite') THEN
               		v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND tew.estado_reg = ''activo'' AND ';

                 ELSIF (v_parametros.tipo_interfaz = 'SolicitudvoboComiteAeronavegabilidad') THEN

                      v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND ewb.estado_reg = ''activo'' AND ';

    				ELSIF  (v_parametros.tipo_interfaz = 'ProcesoCompra')THEN
          					v_filtro = '';
                    ELSIF  (v_parametros.tipo_interfaz = 'Almacen')THEN
                    		v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND tew.estado_reg = ''activo'' AND ';
                    ELSIF  (v_parametros.tipo_interfaz = 'SolArchivado')THEN
                        v_filtro = '';
                    ELSIF  (v_parametros.tipo_interfaz = 'SolicitudFec')THEN
                        v_filtro = '';
                    ELSIF  (v_parametros.tipo_interfaz = 'ConsultaRequerimientos')THEN
                        v_filtro = '';

                    ELSIF v_parametros.pes_estado = 'borrador_reg' THEN
                    v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||'and ';

                    ELSIF v_parametros.pes_estado = 'vobo_area_reg'   THEN
                     v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||'AND';

                    ELSIF v_parametros.pes_estado = 'revision_reg' THEN
                    v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||'AND';

                    ELSIF v_parametros.pes_estado = 'finalizado_reg' THEN
                    v_filtro = 'sol.id_usuario_reg ='||p_id_usuario||
                            ' AND';
                    ELSE
                    v_filtro = 'tew.id_funcionario ='||p_id_usuario||'OR ewb.id_funcionario ='||p_id_usuario||'and';
            END IF;

		v_consulta:='select count(sol.id_solicitud)
                                from mat.tsolicitud sol
                                inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
                                inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                                inner join wf.testado_wf tew on tew.id_estado_wf = sol.id_estado_wf
                                inner join wf.ttipo_estado ti on ti.id_tipo_estado = tew.id_tipo_estado
                                inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf
                                left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                                left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                                left join param.vproveedor pro on pro.id_proveedor =sol.id_proveedor
                                left join wf.testado_wf ewb on ewb.id_estado_wf = sol.id_estado_wf_firma
                                left join wf.tproceso_wf pwfb on pwfb.id_proceso_wf = sol.id_proceso_wf_firma
                                left join wf.ttipo_estado tip on tip.id_tipo_estado = ewb.id_tipo_estado
                                left join mat.tsolicitud_pac pa on pa.id_proceso_wf = sol.id_proceso_wf
                           		left join param.tmoneda mo on mo.id_moneda = pa.id_moneda
                                left join orga.vfuncionario funsol on funsol.id_funcionario = sol.id_funcionario_solicitante

                                left join tes.tobligacion_pago pag on pag.numero = sol.nro_tramite and pag.estado_reg = ''activo''


                                where '||v_filtro;

			v_consulta:=v_consulta||v_parametros.filtro;

			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_MATR_SEL'
 	#DESCRIPCION:	Matricul
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_MATR_SEL')then

		begin

			v_consulta:='select ord.id_orden_trabajo,
                          		split_part(ord.desc_orden ,'' '',2) ||'' ''||  split_part(ord.desc_orden :: text,'' '',3):: text as matricula,
       					 		ord.desc_orden
                                from conta.torden_trabajo ord
								inner join conta.tgrupo_ot_det gr on gr.id_orden_trabajo = ord.id_orden_trabajo and gr.id_grupo_ot IN( 1,4)
							    where ';

			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

           return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_FUN_SEL'
 	#DESCRIPCION:	Lista de funcionarios para registro
 	#AUTOR:		MMV
 	#FECHA:		10-01-2017 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_FUN_SEL')then

		begin

			v_consulta:='select  	f.id_funcionario,
        							p.nombre_completo1,
									uo.nombre_cargo
 									from orga.tfuncionario f
                                    inner join segu.vpersona p on p.id_persona= f.id_persona
                                    inner JOIN orga.tuo_funcionario uof on uof.id_funcionario = f.id_funcionario
                                    inner JOIN orga.tuo uo on  uo.id_uo = uof.id_uo and uo.estado_reg = ''activo''
                                    inner  JOIN orga.tcargo car on car.id_cargo = uof.id_cargo
                                    where ';

			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

           return v_consulta;

		end;


     /*********************************
 	#TRANSACCION:  'MAT_REING_SEL'
 	#DESCRIPCION:	Reporte requerimiento de materiales ingeniaeria
 	#AUTOR:		MAM
 	#FECHA:		23-12-2016 13:13:01
	***********************************/
    elsif(p_transaccion='MAT_REING_SEL')then

		begin
			v_consulta:='WITH part_number_cotizaciones as (select
                                                            replace (list(distinct(CASE
                                                                 --WHEN trim(de.nro_parte_alterno) != '''''' and trim(de.nro_parte_alterno) != ''-''
                                                                 WHEN ((trim(de.nro_parte_alterno) != '''' and trim(de.nro_parte_alterno) != ''-'' and trim(de.nro_parte_alterno) != ''N/A'') and (trim(detcot.explicacion_detallada_part_cot) != trim(de.nro_parte_alterno)) and (trim(detcot.explicacion_detallada_part_cot) != trim(de.nro_parte)))


                                                                 THEN trim(de.nro_parte_alterno)||'',''||detcot.explicacion_detallada_part_cot

                                                                 WHEN ((trim(de.nro_parte_alterno) = '''' or trim(de.nro_parte_alterno) = ''-'' or trim(de.nro_parte_alterno) = ''N/A'') and (trim(detcot.explicacion_detallada_part_cot) != trim(de.nro_parte_alterno)) and (trim(detcot.explicacion_detallada_part_cot) != trim(de.nro_parte)))


                                                                  THEN detcot.explicacion_detallada_part_cot

                                                                  WHEN (
                                                                      (trim(de.nro_parte_alterno) != '''' and trim(de.nro_parte_alterno) != ''-'' and trim(de.nro_parte_alterno) != ''N/A'')

                                                                      and (trim(detcot.explicacion_detallada_part_cot) = trim(de.nro_parte_alterno))

                                                                      and (trim(de.nro_parte_alterno) = trim(de.nro_parte))

                                                                      )

                                                                  THEN ''''::varchar

                                                                 --ELSE  detcot.explicacion_detallada_part_cot
                                                                 ELSE  de.nro_parte_alterno
                                                            END))::varchar, '','', '' / '') as part_number_alternos,
                                                            de.id_detalle
                                                            /*****************************************/
                                                            from mat.tsolicitud sol
                                                            inner join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud and de.estado_reg = ''activo''  and de.estado_excluido = ''no''
                                                            left join mat.tcotizacion_detalle detcot on detcot.id_detalle = de.id_detalle
                                                            where sol.id_proceso_wf = '||v_parametros.id_proceso_wf||'
                                                            group by de.id_detalle)
                          select
                                sol.id_solicitud,
                                to_char( sol.fecha_solicitud,''DD/MM/YYYY'') as fecha_solicitud,
                                ot.motivo_orden,
                                left(ot.desc_orden,20) as matricula,
                                RIGHT (ot.desc_orden,18) as matri,
                                split_part(ot.desc_orden,'' '',1) as flota,
                                sol.nro_tramite,
                                de.nro_parte::text,
                                de.referencia::text,
                                initcap (de.descripcion) as descripcion,
                                de.cantidad_sol,
                                sol.justificacion,
                                sol.tipo_solicitud,
                                to_char( sol.fecha_requerida,''DD/MM/YYYY'') as fecha_requerida,
                                sol.motivo_solicitud::text as motivo_solicitud,
                                REGEXP_REPLACE(sol.observaciones_sol::text, E''[[:space:]]'', '' '', ''g'') as observaciones_sol,
        						initcap( f.desc_funcionario1)as desc_funcionario1,
                                sol.tipo_falla,
        						sol.tipo_reporte,
        						sol.mel,
                                de.id_unidad_medida,
                                ti.codigo as estado,
                                un.codigo as unidad_medida,
                                sol.nro_justificacion,
                                de.nro_parte_alterno,
                                de.tipo,
                                sol.nro_no_rutina,
                                sol.condicion,
                                sol.fecha_solicitud as fecha_soli,
                                /*Aumentando para desglosar en el reporte Ismael Valdivia (26/10/2021)*/
                                sol.tipo_de_adjudicacion,
                                sol.metodo_de_adjudicación,
                                '''||v_fecha_salida_gm||'''::date as fecha_salida,
                                coti.part_number_alternos::varchar
                                /*****************************************/
          						from mat.tsolicitud sol
                                inner join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud and de.estado_reg = ''activo'' and de.estado_excluido = ''no''

                                inner join part_number_cotizaciones coti on coti.id_detalle = de.id_detalle


                                left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                                inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                                inner join wf.testado_wf wof on wof.id_estado_wf = sol.id_estado_wf
                                inner join wf.ttipo_estado ti on ti.id_tipo_estado = wof.id_tipo_estado
                                inner join mat.tunidad_medida un on un.id_unidad_medida = de.id_unidad_medida
                                where sol.id_proceso_wf='||v_parametros.id_proceso_wf||'
                                ORDER BY de.id_detalle desc';

			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_FRI_SEL'
 	#DESCRIPCION:	Control de firmas  qr
 	#AUTOR:	 Ale MV
 	#FECHA:		23-12-2016 13:13:01
	***********************************/
    elsif(p_transaccion='MAT_FRI_SEL')then

		begin

        select  sou.fecha_solicitud,
        	    sou.id_solicitud
            	into
        		v_fecha_solicitud_recu,
                v_id_solicitud_reporte
        from mat.tsolicitud sou
        where sou.id_proceso_wf = v_parametros.id_proceso_wf;
		if (v_fecha_solicitud_recu >= v_fecha_salida_gm or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) then
        v_id_fun_gerencia_mantenimiento = pxp.f_get_variable_global('gm_funcionario_gerencia_mantenimiento')::integer;

                    select
                          fun.id_funcionario,
                          fun.desc_funcionario1,
                          ''::text as fecha
                    into v_id_funcionario_qr_oficial,
                    	 v_nombre_funcionario_qr_oficial,
                         v_fecha_firma_qr
                    from orga.vfuncionario_cargo fun
                    where fun.nombre_cargo = 'Gerencia de Mantenimiento'
                    and v_fecha_solicitud_recu::date between fun.fecha_asignacion
                    and COALESCE(fun.fecha_finalizacion,now()::date);



        if (v_fecha_solicitud_recu >= v_fecha_nuevo_flujo::date) then
            remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_qr_oficial,v_fecha_solicitud_recu::varchar);

            if(remplaso is null)THEN

            else
                    v_nombre_funcionario_qr = remplaso.funcion;
            end if;
        else
        	v_nombre_funcionario_qr = v_nombre_funcionario_qr_oficial;
        end if;


        select sou.id_proceso_wf_firma, to_char(sou.fecha_solicitud, 'DD/MM/YYYY')as fechasol
            	into
                v_id_proceso_wf_firma, v_fecha_solicitud
        from mat.tsolicitud sou
        where sou.id_proceso_wf = v_parametros.id_proceso_wf;


        SELECT    twf.id_funcionario,
              vf.desc_funcionario1,
                to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                    into
              v_id_funcionario_dc_qr_oficial,
                  v_nombre_funcionario_dc_qr_oficial,
                  v_fecha_firma_dc_qr
          FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND te.codigo = 'comite_aeronavegabilidad'

          and v_fecha_solicitud_recu::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
          GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg
          ORDER BY  twf.fecha_reg DESC
          limit 1;




	if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
		remplaso = mat.f_firma_modif(v_id_proceso_wf_firma,v_id_funcionario_dc_qr_oficial,v_fecha_solicitud);
        if(remplaso is null)THEN

                v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

        else
                v_nombre_funcionario_dc_qr = remplaso.funcion;

        end if;
	else
		remplaso = mat.f_firma_original(v_id_proceso_wf_firma, v_id_funcionario_dc_qr_oficial);

        if(remplaso is null)THEN

              v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

        else
                v_nombre_funcionario_dc_qr = remplaso.funcion;

        end if;
	end if;



             SELECT  twf.id_funcionario,
              vf.desc_funcionario1,
                to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                    into
              v_id_funcionario_ag_qr_oficial,
              v_nombre_funcionario_ag_qr_oficial,
            v_fecha_firma_ag_qr
          FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
          AND te.codigo = 'revision'
          and v_fecha_solicitud_recu::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
          GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg
          ORDER BY  twf.fecha_reg DESC
          limit 1;

          if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
              remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_ag_qr_oficial,v_fecha_solicitud);
              if(remplaso is null)THEN

                      v_nombre_funcionario_ag_qr = v_nombre_funcionario_ag_qr_oficial;

              else
                      v_nombre_funcionario_ag_qr = remplaso.funcion;

              end if;
          else
              remplaso = mat.f_firma_original(v_parametros.id_proceso_wf, v_id_funcionario_ag_qr_oficial);

              if(remplaso is null)THEN

                      v_nombre_funcionario_ag_qr = v_nombre_funcionario_ag_qr_oficial;

              else
                      v_nombre_funcionario_ag_qr = remplaso.funcion;

              end if;
          end if;


      SELECT
      substr (s.nro_tramite,1,2)
      into v_cod_tramite
      FROM mat.tsolicitud s
      WHERE s.id_proceso_wf = v_parametros.id_proceso_wf;

          IF(v_cod_tramite = 'GC') then

           if (v_fecha_solicitud_recu >= v_fecha_nuevo_flujo::date) then

                SELECT
                           vf.desc_funcionario1
                            into v_nombre_funcionario_ag_qr

                    FROM wf.ttipo_estado te
                    inner join wf.tfuncionario_tipo_estado estado on estado.id_tipo_estado = te.id_tipo_estado
                    INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = estado.id_funcionario
                    WHERE te.codigo = 'departamento_ceac'
                    and v_fecha_solicitud_recu::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                    and te.estado_reg = 'activo' and estado.estado_reg = 'activo';

           else


            SELECT
                      vf.desc_funcionario1
                      into v_nombre_funcionario_ag_qr
              FROM wf.testado_wf twf
              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
              INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
              WHERE twf.id_tipo_estado = 992
              and v_fecha_solicitud_recu::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
              GROUP BY twf.id_funcionario, vf.desc_funcionario1, twf.fecha_reg
              ORDER BY  twf.fecha_reg DESC
              limit 1;

            end if;







           end if;




            v_consulta:='select 	s.origen_pedido,
                                    '''||COALESCE (initcap(v_nombre_funcionario_qr),' ') ||'''::varchar as visto_bueno,
                                    '''||COALESCE (v_fecha_solicitud,' ')||'''::text as fecha_visto_bueno,
                                    '''||COALESCE (initcap(v_nombre_funcionario_dc_qr),' ')||'''::varchar as aero,
                                    '''||COALESCE (v_fecha_firma_dc_qr,' ')||'''::text as fecha_aero,
                                    '''||COALESCE (initcap(v_nombre_funcionario_ag_qr),' ')||'''::varchar as visto_ag,
                                    '''||COALESCE (v_fecha_firma_ag_qr,' ')||'''::text as fecha_ag,
                                    s.nro_tramite
                                    from mat.tsolicitud s
                                    where s.id_proceso_wf = '||v_parametros.id_proceso_wf;
		ELSE
        select sou.id_proceso_wf_firma, to_char(sou.fecha_solicitud, 'DD/MM/YYYY')as fechasol
            into
                v_id_proceso_wf_firma, v_fecha_solicitud
        from mat.tsolicitud sou
        where sou.id_proceso_wf = v_parametros.id_proceso_wf;

        SELECT  twf.id_funcionario,
            vf.desc_funcionario1,
            to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                into
            v_id_funcionario_qr_oficial,
                v_nombre_funcionario_qr_oficial,
                v_fecha_firma_qr
          FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND te.codigo = 'vobo_area'
          GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg;


          if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
              remplaso = mat.f_firma_modif(v_id_proceso_wf_firma,v_id_funcionario_qr_oficial,v_fecha_solicitud);
          else
              remplaso = mat.f_firma_original(v_id_proceso_wf_firma, v_id_funcionario_qr_oficial);
          end if;

            if(remplaso is null)THEN

                    v_nombre_funcionario_qr = v_nombre_funcionario_qr_oficial;

            else
                    v_nombre_funcionario_qr = remplaso.funcion;

            end if;

              SELECT    twf.id_funcionario,
                    vf.desc_funcionario1,
                      to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                          into
                    v_id_funcionario_dc_qr_oficial,
                        v_nombre_funcionario_dc_qr_oficial,
                        v_fecha_firma_dc_qr
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_id_proceso_wf_firma
                AND te.codigo = 'vobo_aeronavegabilidad'
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg;

          if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
              remplaso = mat.f_firma_modif(v_id_proceso_wf_firma,v_id_funcionario_dc_qr_oficial,v_fecha_solicitud);
          else
              remplaso = mat.f_firma_original(v_id_proceso_wf_firma, v_id_funcionario_dc_qr_oficial);
          end if;
            if(remplaso is null)THEN

                    v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

            else
                    v_nombre_funcionario_dc_qr = remplaso.funcion;

            end if;

                   SELECT  twf.id_funcionario,
                    vf.desc_funcionario1,
                      to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                          into
                    v_id_funcionario_ag_qr_oficial,
                    v_nombre_funcionario_ag_qr_oficial,
                  v_fecha_firma_ag_qr
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                AND te.codigo = 'revision'
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg;

          if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
              remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_ag_qr_oficial,v_fecha_solicitud);
          else
              remplaso = mat.f_firma_original(v_parametros.id_proceso_wf, v_id_funcionario_ag_qr_oficial);
          end if;
            if(remplaso is null)THEN

                    v_nombre_funcionario_ag_qr = v_nombre_funcionario_ag_qr_oficial;

            else
                    v_nombre_funcionario_ag_qr = remplaso.funcion;

            end if;

            SELECT
            substr (s.nro_tramite,1,2)
            into v_cod_tramite
            FROM mat.tsolicitud s
            WHERE s.id_proceso_wf = v_parametros.id_proceso_wf;

            IF(v_cod_tramite = 'GC') then
              /*SELECT
                        vf.desc_funcionario1
                        into v_nombre_funcionario_ag_qr
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN orga.vfuncionario vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_tipo_estado = 992
                GROUP BY twf.id_funcionario, vf.desc_funcionario1;*/

              SELECT
                      vf.desc_funcionario1
                      into v_nombre_funcionario_ag_qr
              FROM wf.testado_wf twf
              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
              INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
              WHERE twf.id_tipo_estado = 992
              and v_fecha_solicitud_recu between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
              GROUP BY twf.id_funcionario, vf.desc_funcionario1, twf.fecha_reg
              ORDER BY  twf.fecha_reg DESC
              limit 1;



             end if;




                  v_consulta:='select 	s.origen_pedido,
                                          '''||COALESCE (initcap(v_nombre_funcionario_qr),' ') ||'''::varchar as visto_bueno,
                                          '''||COALESCE (v_fecha_firma_qr,' ')||'''::text as fecha_visto_bueno,
                                          '''||COALESCE (initcap(v_nombre_funcionario_dc_qr),' ')||'''::varchar as aero,
                                          '''||COALESCE (v_fecha_firma_dc_qr,' ')||'''::text as fecha_aero,
                                          '''||COALESCE (initcap(v_nombre_funcionario_ag_qr),' ')||'''::varchar as visto_ag,
                                          '''||COALESCE (v_fecha_firma_ag_qr,' ')||'''::text as fecha_ag,
                                          s.nro_tramite
                                          from mat.tsolicitud s
                                          where s.id_proceso_wf = '||v_parametros.id_proceso_wf;
        end if;

            v_consulta=v_consulta||' GROUP BY s.origen_pedido,s.nro_tramite';
            return v_consulta;
   end;

    /*********************************
 	#TRANSACCION:  'MAT_CON_AL_SEL'
 	#DESCRIPCION:	Reporte para control de numoer de partes alamcen
 	#AUTOR:	 MMV
 	#FECHA:		10-02-2017 13:13:01
	***********************************/
     elsif(p_transaccion='MAT_CON_AL_SEL')then

		begin
        IF(v_parametros.origen_pedido  = 'Gerencia de Mantenimiento')THEN
        IF (v_parametros.estado > 1::VARCHAR )THEN
        v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and t.id_tipo_estado in('||v_parametros.estado||') and ';
    	ELSE
         v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and ';
        END IF;

      ELSIF(v_parametros.origen_pedido  = 'Gerencia de Operaciones')THEN

          IF (v_parametros.estado_op > 1::VARCHAR )THEN

        v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and t.id_tipo_estado::integer in ('||v_parametros.estado_op||') and ';
    	ELSE
         v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and ';
        END IF;
        ELSIF(v_parametros.origen_pedido  = 'Almacenes Consumibles o Rotables')THEN

          IF (v_parametros.estado_ro > 1::VARCHAR )THEN

        v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and t.id_tipo_estado::integer in ('||v_parametros.estado_ro||') and ';
    	ELSE
         v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and ';
        END IF;

        ELSIF(v_parametros.origen_pedido  = 'Centro de Entrenamiento Aeronautico Civil')THEN

          IF (v_parametros.estado_ro > 1::VARCHAR )THEN

        v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and t.id_tipo_estado::integer in ('||v_parametros.estado_ro||') and ';
    	ELSE
         v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and ';
        END IF;


        ELSIF(v_parametros.origen_pedido  = 'Todos')THEN
         IF (v_parametros.estado > 1::VARCHAR )THEN
        v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin|| ''' and t.id_tipo_estado in('||v_parametros.estado||') and ';
    	ELSE
         v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''  and ';
        END IF;
        END IF;

            v_consulta:='select s.nro_tramite,
                                s.origen_pedido,
                                t.nombre_estado as estado,
                                f.desc_funcionario1,
                                to_char(s.fecha_solicitud,''DD/MM/YYYY'')as fecha_solicitud,
                                d.nro_parte,
                                d.nro_parte_alterno,
                                d.descripcion,
                                d.cantidad_sol,
                                t.id_tipo_estado,
                                d.id_solicitud as id,
                                to_char(s.fecha_requerida,''DD/MM/YYYY'')as fecha_requerida,
                                COALESCE (ot.desc_orden,'' '')::varchar as matricula,
                                initcap (s.motivo_solicitud)::varchar as motivo_solicitud,
                                initcap(s.observaciones_sol)::varchar as observaciones_sol,
                                s.justificacion,
                                s.nro_justificacion,
                                s.tipo_solicitud,
                                s.tipo_falla,
                                s.tipo_reporte,
                                s.mel,
                                s.nro_no_rutina
                                from mat.tsolicitud s
                                inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                                inner join mat.tdetalle_sol d on d.id_solicitud = s.id_solicitud
                                inner join wf.testado_wf e on e.id_estado_wf = s.id_estado_wf
                                inner join wf.ttipo_estado t on t.id_tipo_estado = e.id_tipo_estado
                                left join conta.torden_trabajo ot on ot.id_orden_trabajo = s.id_matricula
                                where '||v_filtro_repo;

            v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||'ORDER BY nro_tramite, s.nro_tramite';


			return v_consulta;
		end;
    /*********************************
 	#TRANSACCION:  'MAT_ESTADO_SEL'
 	#DESCRIPCION:	Listar estadi
 	#AUTOR:	 MMV
 	#FECHA:		10-02-2017 13:13:01
	***********************************/
     elsif(p_transaccion='MAT_ESTADO_SEL')then

		begin
			v_consulta:='select
            					t.id_tipo_estado,
								t.nombre_estado as codigo
								from wf.ttipo_estado t
								inner join wf.ttipo_proceso pr on pr.id_tipo_proceso = t.id_tipo_proceso and pr.nombre = ''Requerimiento Gerencia de Mantenimiento'' and t.estado_reg = ''activo''
                                where';

			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

           return v_consulta;

		end;
          elsif(p_transaccion='MAT_ES_OP_SEL')then

		begin
			v_consulta:='select
            					t.id_tipo_estado,
								t.nombre_estado as codigo
								from wf.ttipo_estado t
								inner join wf.ttipo_proceso pr on pr.id_tipo_proceso = t.id_tipo_proceso and pr.nombre = ''Requerimiento Gerencia de Operaciones'' and t.estado_reg = ''activo''
                                where';

			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

           return v_consulta;

		end;
         elsif(p_transaccion='MAT_ES_RO_SEL')then

		begin
			v_consulta:='select
            					t.id_tipo_estado,
								t.nombre_estado as codigo
								from wf.ttipo_estado t
								inner join wf.ttipo_proceso pr on pr.id_tipo_proceso = t.id_tipo_proceso and pr.nombre = ''Requerimiento de Abastecimiento'' and t.estado_reg = ''activo''
                                where';

			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

           return v_consulta;

		end;
          elsif(p_transaccion='MAT_ES_SAC_SEL')then

		begin
			v_consulta:='select
            					t.id_tipo_estado,
								t.nombre_estado as codigo
								from wf.ttipo_estado t
								inner join wf.ttipo_proceso pr on pr.id_tipo_proceso = t.id_tipo_proceso and pr.nombre = ''Requerimiento Gerencia de Operaciones CRAC'' and t.estado_reg = ''activo''
                                where';

			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

           return v_consulta;

		end;

   /*********************************
 	#TRANSACCION:  'MAT_REPOR_SEL'
 	#DESCRIPCION:	Reporte comite evaluacion de compra y selecion de proveedor
 	#AUTOR:	 MMV
 	#FECHA:		28-06-2017
	***********************************/
    elsif(p_transaccion='MAT_REPOR_SEL')then

		begin

        select to_char(sou.fecha_po,'DD/MM/YYYY')as fechapo,
        to_char(sou.fecha_solicitud,'DD/MM/YYYY')as fechasol,
        sou.id_solicitud
        into
        v_fecha_po,
        v_fecha_solicitud,
        v_id_solicitud_reporte
        from mat.tsolicitud sou
        where sou.id_proceso_wf = v_parametros.id_proceso_wf;


        /*Aumentando condicion para saber si aplica o no cambio de etiqueta*/
        v_aplica_cambio_etiqueta = 'no';
        if (v_fecha_po::date >= '01/03/2022'::date) then
            v_aplica_cambio_etiqueta = 'si';
        else
        	v_aplica_cambio_etiqueta = 'no';
        end if;
        /*******************************************************************/



        if (v_fecha_solicitud::date >= v_fecha_salida_gm or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) then

        /*Recuperamos el id_proceso_wf_firma para recuperar en el reporte (Ismael Valdivia 09/03/2020)*/
        select sol.id_proceso_wf_firma into v_id_proceso_wf_firma
        from mat.tsolicitud sol
        where sol.id_proceso_wf = v_parametros.id_proceso_wf;
        /**********************************************************************************************/


        /*Recuperando la firma del nuevo estado (Ismael Valdivia 16/02/2022)*/
        if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then

        	SELECT		twf.id_funcionario,
                        vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                        to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            into
                        v_id_funcionario_tecnico_qr,
                        v_nombre_funcionario_tecnico_qr_oficial,
                        v_fecha_firma_tecnico_qr
            FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                AND  te.codigo = 'revision_tecnico_abastecimientos'
                and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1, twf.fecha_reg, vf.nombre_cargo, pro.nro_tramite
                ORDER BY  twf.fecha_reg DESC
                limit 1;

        else
        	v_nombre_funcionario_tecnico_qr_oficial = '';
        end if;

        if (v_nombre_funcionario_tecnico_qr_oficial is null) then
        	v_nombre_funcionario_tecnico_qr_oficial = '';
        end if;

        /********************************************************************/

            if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

                SELECT            	twf.id_funcionario,
                                    vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                    to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma,
                                    twf.id_estado_wf
                INTO
                                v_id_funcionario_rev_qr_oficial,
                                v_nombre_funcionario_rev_qr_oficial,
                                v_fecha_firma_rev_qr,
                                v_id_estado_aprobado
                    FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                          AND te.codigo = 'comite_unidad_abastecimientos'
                          and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                          GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite, twf.id_estado_wf--Aumentando el id estado (Ismael Valdivia 12/05/2022)
                          ORDER BY  twf.id_estado_wf DESC
                          limit 1;

                /*Aumentando para condicionar el reporte del comite del 01/04/2022 al 30/04/2022*/
                select es.fecha_reg::date into v_fecha_aprobacion
                from wf.testado_wf es
                where es.id_estado_anterior = v_id_estado_aprobado;


                if ((v_fecha_aprobacion between '01/04/2022' and '30/04/2022') OR (v_fecha_aprobacion >= '01/06/2022') ) then
                	v_fecha_po = v_fecha_aprobacion;
                end if;


                /********************************************************************************/


                remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_rev_qr_oficial,v_fecha_po);
                  if(remplaso is null)THEN

                          v_nombre_funcionario_rev_qr = v_nombre_funcionario_rev_qr_oficial;
                  else
                          v_nombre_funcionario_rev_qr = remplaso.desc_funcionario1;

                  end if;
            else

                SELECT            	twf.id_funcionario,
                                    vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                    to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                INTO
                                v_id_funcionario_rev_qr_oficial,
                                v_nombre_funcionario_rev_qr_oficial,
                                v_fecha_firma_rev_qr
                    FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          INNER JOIN orga.vfuncionario_ultimo_cargo vf ON vf.id_funcionario = twf.id_funcionario
                          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_unidad_abastecimientos'
                          and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                          GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo
                          ORDER BY  twf.fecha_reg DESC
                          limit 1;

                remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_rev_qr_oficial);

                if(remplaso is null)THEN

                          v_nombre_funcionario_rev_qr = v_nombre_funcionario_rev_qr_oficial;
                  else
                          v_nombre_funcionario_rev_qr = remplaso.desc_funcionario1;

                  end if;
            end if;


             if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

                  SELECT		twf.id_funcionario,
                              vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                              to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                  into
                              v_id_funcionario_dc_qr_oficial,
                              v_nombre_funcionario_dc_qr_oficial,
                              v_fecha_firma_dc_qr
                  FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                      INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      WHERE twf.id_proceso_wf = v_id_proceso_wf_firma
                      AND  te.codigo = 'comite_aeronavegabilidad'
                      and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                      GROUP BY twf.id_funcionario, vf.desc_funcionario1, twf.fecha_reg, vf.nombre_cargo, pro.nro_tramite
                      ORDER BY  twf.fecha_reg DESC
                      limit 1;

          remplaso = mat.f_firma_modif(v_id_proceso_wf_firma,v_id_funcionario_dc_qr_oficial,v_fecha_po);

          if(remplaso is null)THEN

                    v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

            else
                    v_nombre_funcionario_dc_qr = remplaso.desc_funcionario1;

            end if;
        else

                  SELECT		twf.id_funcionario,
                              vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                              to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                  into
                              v_id_funcionario_dc_qr_oficial,
                              v_nombre_funcionario_dc_qr_oficial,
                              v_fecha_firma_dc_qr
                  FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      WHERE twf.id_proceso_wf = v_id_proceso_wf_firma
                      AND  te.codigo = 'comite_aeronavegabilidad'
                      and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                      GROUP BY twf.id_funcionario, vf.desc_funcionario1, twf.fecha_reg, vf.nombre_cargo
                      ORDER BY  twf.fecha_reg DESC
                      limit 1;

          remplaso = mat.f_firma_original(v_id_proceso_wf_firma,v_id_funcionario_dc_qr_oficial);

          if(remplaso is null)THEN

                    v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

            else
                    v_nombre_funcionario_dc_qr = remplaso.desc_funcionario1;

            end if;
        end if;



            if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

                  SELECT        	twf.id_funcionario,
                                  vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                  to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                  into
                              v_id_funcionario_abas_qr_oficial,
                              v_nombre_funcionario_abas_qr_oficial,
                              v_fecha_firma_abas_qr
                    FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                      INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      /*Comentando esta parte para Incluir a marco Mendoza (Ismael Valdivia 06/02/2020)*/
                      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                      AND te.codigo = 'vb_dpto_abastecimientos'
                      and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                      GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                      ORDER BY  twf.fecha_reg DESC
                      limit 1;

                  if (v_fecha_solicitud_recu >= v_fecha_nuevo_flujo::date) then
                      remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_abas_qr_oficial,v_fecha_po);
                      if (remplaso is null)THEN
                              v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;
                      else
                              v_nombre_funcionario_abas_qr = remplaso.desc_funcionario1;
                      end if;
                  else
                  		v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;
                  end if;

            else

                  SELECT        	twf.id_funcionario,
                                  vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                  to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                  into
                              v_id_funcionario_abas_qr_oficial,
                              v_nombre_funcionario_abas_qr_oficial,
                              v_fecha_firma_abas_qr
                    FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      /*Comentando esta parte para Incluir a marco Mendoza (Ismael Valdivia 06/02/2020)*/
                      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                      AND te.codigo = 'vb_dpto_abastecimientos'
                      and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                      GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo
                      ORDER BY  twf.fecha_reg DESC
                      limit 1;

              remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_abas_qr_oficial);

              if (remplaso is null)THEN
                      v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;
              else
                      v_nombre_funcionario_abas_qr = remplaso.desc_funcionario1;
              end if;
            end if;




              WITH RECURSIVE firmas(id_estado_fw, id_estado_anterior,fecha_reg, codigo, id_funcionario) AS (
                                        SELECT tew.id_estado_wf, tew.id_estado_anterior , tew.fecha_reg, te.codigo, tew.id_funcionario
                                        FROM wf.testado_wf tew
                                        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = tew.id_tipo_estado
                                        WHERE tew.id_proceso_wf = v_parametros.id_proceso_wf

                                        UNION ALL

                                        SELECT ter.id_estado_wf, ter.id_estado_anterior, ter.fecha_reg, te.codigo, ter.id_funcionario
                                        FROM wf.testado_wf ter
                                        INNER JOIN firmas f ON f.id_estado_anterior = ter.id_estado_wf
                                        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = ter.id_tipo_estado
                                        WHERE f.id_estado_anterior IS NOT NULL
              )
                        SELECT id_estado_fw
                        into
                        v_id_despacho
                        FROM firmas
                        WHERE codigo = 'despachado' and fecha_reg::date = ( SELECT    max (tew.fecha_reg::date)
                                                  FROM wf.testado_wf tew
                                                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = tew.id_tipo_estado
                                                  WHERE tew.id_proceso_wf = v_parametros.id_proceso_wf and te.codigo = 'despachado');


                  select pwf.id_proceso_wf
                  INTO
                  v_id_proceso_wf_adq
                  from wf.tproceso_wf pwf
                  where  pwf.id_estado_wf_prev = v_id_despacho;

            if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

                    SELECT 	twf.id_funcionario,
                            vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                            to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma,
                            te.codigo

                    INTO
                            v_id_funcionario_presu_qr_oficial,
                            v_nombre_funcionario_presu_qr_oficial,
                            v_fecha_firma_presu_qr,
                            v_codigo_rpc
                    FROM wf.testado_wf twf
                        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                        /*Comentamos esta parte para incluir a Karina Barrancos*/

                        WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                        AND te.codigo = 'vb_rpcd'
                        and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg
                        ORDER BY  twf.fecha_reg DESC
                          limit 1;
                        /*IF(v_codigo_rpc = 'vbrpc') then
                            RAISE EXCEPTION 'a: %, b: %, c: %, d: %',v_id_funcionario_presu_qr_oficial,
                            v_nombre_funcionario_presu_qr_oficial,
                            v_fecha_firma_presu_qr,
                            v_fecha_po;
                        end if;*/

            else

                    SELECT 	twf.id_funcionario,
                            vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                            to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma

                    INTO
                            v_id_funcionario_presu_qr_oficial,
                            v_nombre_funcionario_presu_qr_oficial,
                            v_fecha_firma_presu_qr
                    FROM wf.testado_wf twf
                        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                        /*Comentamos esta parte para incluir a Karina Barrancos*/

                        WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                        AND te.codigo = 'vb_rpcd'
                        and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,twf.fecha_reg
                        ORDER BY  twf.fecha_reg DESC
                        limit 1;

            end if;


                    v_nombre_funcionario_presu_qr = v_nombre_funcionario_presu_qr_oficial;






    if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

  			SELECT		twf.id_funcionario,
        				vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          				to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            into
        				v_id_funcionario_resp_qr_oficial,
                		v_nombre_funcionario_resp_qr_oficial,
                		v_fecha_firma_resp_qr
          	FROM wf.testado_wf twf
          		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
          		INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          	WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            	  AND  te.codigo = 'cotizacion'
                   and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
           	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
            ORDER BY  twf.fecha_reg DESC
			LIMIT 1;

  	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_resp_qr_oficial,v_fecha_po);
  else

  			SELECT		twf.id_funcionario,
        				vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          				to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            into
        				v_id_funcionario_resp_qr_oficial,
                		v_nombre_funcionario_resp_qr_oficial,
                		v_fecha_firma_resp_qr
          	FROM wf.testado_wf twf
          		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          		INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            	 AND  te.codigo ='cotizacion'
                  and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
           	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo
            ORDER BY  twf.fecha_reg DESC
			LIMIT 1;

  	remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_resp_qr_oficial);
  end if;

      if(remplaso is null)THEN

              v_nombre_funcionario_resp_qr = v_nombre_funcionario_resp_qr_oficial;

      else
              v_nombre_funcionario_resp_qr = remplaso.desc_funcionario1;

      end if;

        select s.estado
        into
        v_codigo_pre
        from adq.tsolicitud s
        where s.id_proceso_wf = v_id_proceso_wf_adq;



        with datos as (select ce.nro_parte_cot
                       from mat.tcotizacion_detalle ce
                       where ce.id_cotizacion = ( select c.id_cotizacion
                                                  from mat.tsolicitud s
                                                  inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
                                                  where s.id_proceso_wf = v_parametros.id_proceso_wf) and ce.revisado = 'si'
                       group by ce.nro_parte_cot)
        select count(nro_parte_cot) into v_cantidad_items
        from datos;
        v_fecha_po = to_char(v_fecha_po::date,'DD/MM/YYYY');
			v_consulta:='select s.id_solicitud::integer,
								initcap(pxp.f_convertir_num_a_letra( mat.f_id_detalle_cotizacion(c.id_cotizacion)))::varchar as item_selecionados,
                                initcap(pxp.f_convertir_num_a_letra('||v_cantidad_items||'))::varchar as items_diferentes,
                                s.nro_tramite::varchar,
                                s.origen_pedido::varchar,
                                --to_char(s.fecha_po,''DD/MM/YYYY'')::text as fecha_po,
                                '''||COALESCE(v_fecha_po,'')::text||'''::text as fecha_po,
                                pxp.aggarray( d.nro_parte_cot)::text as nro_parte,
                                s.tipo_evaluacion::varchar,
                                (case
                                when s.tipo_evaluacion =''Reparacion''then
                                s.taller_asignado
                                else
                                ''N/A''
                                end::varchar )::varchar as taller_asignado,
                                COALESCE(s.observacion_nota,''N/A'')::varchar as observacion_nota,
                                (select count(ng.id_solicitud)
                                from mat.tgestion_proveedores_new ng
                                where ng.id_solicitud = s.id_solicitud)::integer as cotizacion_solicitadas,
                                c.nro_cotizacion::varchar,
                                c.monto_total::numeric,
                                (select count(z.id_proveedor)
                                from mat.tcotizacion z
                                where z.id_solicitud = s.id_solicitud
                                )::integer as proveedores_resp,
                                initcap (p.desc_proveedor)::varchar as desc_proveedor,
                                '''||COALESCE (initcap(v_nombre_funcionario_dc_qr),' ')||'''::varchar as aero,
                                    '''||COALESCE (v_fecha_firma_dc_qr,' ')||'''::text as fecha_aero,
                                    '''||COALESCE (initcap(v_nombre_funcionario_rev_qr),' ')||'''::varchar as visto_rev,
                                    '''||COALESCE (v_fecha_firma_rev_qr,' ')||'''::text as fecha_rev,
                                    '''||COALESCE (initcap(v_nombre_funcionario_abas_qr),' ')||'''::varchar as visto_abas,
                                    '''||COALESCE (v_fecha_firma_abas_qr,' ')||'''::text as fecha_abas,
                                c.obs::varchar,
                                c.recomendacion::varchar,
                                mo.codigo::varchar,
                                '''||COALESCE(initcap(v_nombre_funcionario_presu_qr),' ')||'''::varchar AS funcionario_pres,
                                '''||COALESCE(v_codigo_pre,' ')||'''::varchar AS codigo_pres,
                                '''||COALESCE (v_fecha_firma_presu_qr,' ')||'''::text as fecha_pres,
                                s.estado::varchar as estado_materiales,
                               d.nro_parte_cot::varchar,
                               d.descripcion_cot::varchar,
                               d.cantidad_det::integer,
                               d.cd::varchar,
                               /*Aumentando este campo para mostrar el PN cotizacion (Ismael Valdivia 06/10/2020)*/
                               d.explicacion_detallada_part_cot::varchar,
                               /**********************************************************************************/
							   da.codigo_tipo::varchar,
                               '''||COALESCE (initcap(v_nombre_funcionario_resp_qr),' ')||'''::varchar as funcionario_resp,
                               '''||COALESCE (v_fecha_firma_resp_qr,' ')||'''::text as fecha_resp,
                               s.fecha_solicitud::date,
								s.estado_firma::varchar,
                                '''||v_fecha_salida_gm||'''::date as fecha_salida,
                                '''||initcap(v_nombre_funcionario_tecnico_qr_oficial)||'''::varchar as firma_tecnico_abastecimiento,
                                '''||v_aplica_cambio_etiqueta||'''::varchar as cambio_etiqueta
                                from mat.tsolicitud s
                                inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = ''si''
                                inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion  and d.tipo_cot <> ''Otros Cargos'' and  d.tipo_cot <>''NA'' and d.revisado = ''si'' and d.tipo_cot <> ''Fletes - Otros''
                                inner join param.vproveedor p on p.id_proveedor = c.id_proveedor
                                inner join param.tmoneda mo on mo.id_moneda = c.id_moneda
                                left join mat.tgestion_proveedores ge on ge.id_solicitud = s.id_solicitud
                                left join mat.tday_week da on da.id_day_week =d.id_day_week
                                left join wf.testado_wf es on es.id_estado_wf = s.id_estado_wf and es.id_tipo_estado=787
                                left join orga.vfuncionario_persona vfp on vfp.id_funcionario = es.id_funcionario
								where  s.id_proceso_wf ='||v_parametros.id_proceso_wf;

            v_consulta:=v_consulta||'GROUP BY s.id_solicitud,ge.cotizacion_solicitadas,c.nro_cotizacion,c.monto_total,p.desc_proveedor,c.obs,c.recomendacion,mo.codigo,  d.nro_parte_cot,
                               d.descripcion_cot,
                               d.cantidad_det,
                               d.cd,
							   da.codigo_tipo,
                           	   c.id_cotizacion,
                               s.fecha_solicitud,
                               d.explicacion_detallada_part_cot,
                               d.id_detalle

                               ORDER BY d.id_detalle desc';
	   else
       		if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

  			SELECT		twf.id_funcionario,
        				vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          				to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            into
        				v_id_funcionario_dc_qr_oficial,
                		v_nombre_funcionario_dc_qr_oficial,
                		v_fecha_firma_dc_qr
          	FROM wf.testado_wf twf
          		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
          		INNER JOIN orga.vfuncionario_ultimo_cargo vf ON vf.id_funcionario = twf.id_funcionario
          		WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND  te.codigo =
          		(case
          			when (select s.origen_pedido
          				   from mat.tsolicitud s
                		   where s.id_proceso_wf = v_parametros.id_proceso_wf) = 'Centro de Entrenamiento Aeronautico Civil' then
          					'departamento_ceac'
           				   else
          					'comite_aeronavegabilidad'
           					end) and vf.id_uo_funcionario=mat.f_position_end(twf.id_funcionario)
           	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite;

  	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_dc_qr_oficial,v_fecha_po);
  else

  			SELECT		twf.id_funcionario,
        				vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          				to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            into
        				v_id_funcionario_dc_qr_oficial,
                		v_nombre_funcionario_dc_qr_oficial,
                		v_fecha_firma_dc_qr
          	FROM wf.testado_wf twf
          		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          		INNER JOIN orga.vfuncionario_ultimo_cargo vf ON vf.id_funcionario = twf.id_funcionario
          		WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND  te.codigo =
          		(case
          			when (select s.origen_pedido
          				   from mat.tsolicitud s
                		   where s.id_proceso_wf = v_parametros.id_proceso_wf) = 'Centro de Entrenamiento Aeronautico Civil' then
          					'departamento_ceac'
           				   else
          					'comite_aeronavegabilidad'
           					end) and vf.id_uo_funcionario=mat.f_position_end(twf.id_funcionario)
           	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;

  	remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_dc_qr_oficial);
  end if;

      if(remplaso is null)THEN

              v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

      else
              v_nombre_funcionario_dc_qr = remplaso.desc_funcionario1;

      end if;

  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

  		SELECT        	twf.id_funcionario,
        				vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          				to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        into
                    v_id_funcionario_abas_qr_oficial,
                	v_nombre_funcionario_abas_qr_oficial,
                	v_fecha_firma_abas_qr
          FROM wf.testado_wf twf
          	INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
          	INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          	WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            	AND te.codigo = 'comite_dpto_abastecimientos'
                --27-04-2021 (may) la condicion fecha finalizacion no es solo que este null ya tiene valor
                --and vf.fecha_finalizacion is null
                and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
          	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite;

  	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_abas_qr_oficial,v_fecha_po);
  else

  		SELECT        	twf.id_funcionario,
        				vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          				to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        into
                    v_id_funcionario_abas_qr_oficial,
                	v_nombre_funcionario_abas_qr_oficial,
                	v_fecha_firma_abas_qr
          FROM wf.testado_wf twf
          	INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          	INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          	WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_dpto_abastecimientos'
            and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
          	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;

  	remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_abas_qr_oficial);
  end if;

    if (remplaso is null)THEN

            v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;

    else
            v_nombre_funcionario_abas_qr = remplaso.desc_funcionario1;

    end if;

    if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

      	SELECT            	twf.id_funcionario,
        					vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          					to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        INTO
                    	v_id_funcionario_rev_qr_oficial,
                    	v_nombre_funcionario_rev_qr_oficial,
    					v_fecha_firma_rev_qr
        	FROM wf.testado_wf twf
                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                  INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                  INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                  	AND te.codigo = 'comite_unidad_abastecimientos'
                    --27-04-2021 (may) la condicion fecha finalizacion no es solo que este null ya tiene valor
                    --and vf.fecha_finalizacion is null
                    and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
                  GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite;

    	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_rev_qr_oficial,v_fecha_po);
    else

    	SELECT            	twf.id_funcionario,
        					vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          					to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        INTO
                    	v_id_funcionario_rev_qr_oficial,
                    	v_nombre_funcionario_rev_qr_oficial,
    					v_fecha_firma_rev_qr
        	FROM wf.testado_wf twf
                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                  INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_unidad_abastecimientos'
                  and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
                  GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;

    	remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_rev_qr_oficial);
    end if;


    if(remplaso is null)THEN

            v_nombre_funcionario_rev_qr = v_nombre_funcionario_rev_qr_oficial;
    else
            v_nombre_funcionario_rev_qr = remplaso.desc_funcionario1;

    end if;

----firma adq
	WITH RECURSIVE firmas(id_estado_fw, id_estado_anterior,fecha_reg, codigo, id_funcionario) AS (
                              SELECT tew.id_estado_wf, tew.id_estado_anterior , tew.fecha_reg, te.codigo, tew.id_funcionario
                              FROM wf.testado_wf tew
                              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = tew.id_tipo_estado
                              WHERE tew.id_proceso_wf = v_parametros.id_proceso_wf

                              UNION ALL

                              SELECT ter.id_estado_wf, ter.id_estado_anterior, ter.fecha_reg, te.codigo, ter.id_funcionario
                              FROM wf.testado_wf ter
                              INNER JOIN firmas f ON f.id_estado_anterior = ter.id_estado_wf
                              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = ter.id_tipo_estado
                              WHERE f.id_estado_anterior IS NOT NULL
    )
    SELECT id_estado_fw
    into
    v_id_despacho
    FROM firmas
  	WHERE codigo = 'despachado' and fecha_reg::date = ( SELECT    max (tew.fecha_reg::date)
                              FROM wf.testado_wf tew
                              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = tew.id_tipo_estado
                              WHERE tew.id_proceso_wf = v_parametros.id_proceso_wf and te.codigo = 'despachado');


    select pwf.id_proceso_wf
	INTO
    v_id_proceso_wf_adq
    from wf.tproceso_wf pwf
	where  pwf.id_estado_wf_prev = v_id_despacho;

    if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

    		SELECT 	twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
					to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma,
                    te.codigo

        	INTO
        			v_id_funcionario_presu_qr_oficial,
        			v_nombre_funcionario_presu_qr_oficial,
        			v_fecha_firma_presu_qr,
                    v_codigo_rpc
        	FROM wf.testado_wf twf
        		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
        		INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        		WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbrpc'
                and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
        		GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg;
                /*IF(v_codigo_rpc = 'vbrpc') then
					RAISE EXCEPTION 'a: %, b: %, c: %, d: %',v_id_funcionario_presu_qr_oficial,
        			v_nombre_funcionario_presu_qr_oficial,
        			v_fecha_firma_presu_qr,
                    v_fecha_po;
                end if;*/
    	remplaso = mat.f_firma_modif(v_id_proceso_wf_adq,v_id_funcionario_presu_qr_oficial,v_fecha_po);
    else

    		SELECT 	twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
					to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma

        	INTO
        			v_id_funcionario_presu_qr_oficial,
        			v_nombre_funcionario_presu_qr_oficial,
        			v_fecha_firma_presu_qr
        	FROM wf.testado_wf twf
        		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        		INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        		WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbrpc'
                and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
        		GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,twf.fecha_reg;

    	remplaso = mat.f_firma_original(v_id_proceso_wf_adq,v_id_funcionario_presu_qr_oficial);
    end if;

      if(remplaso is null)THEN

              v_nombre_funcionario_presu_qr = v_nombre_funcionario_presu_qr_oficial;
      else
              v_nombre_funcionario_presu_qr = remplaso.desc_funcionario1;

      end if;

    --para nueva firma del funcionario responsable del tramite
    --inicia desde el 01-06-2019

    if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

            --(may) 02-02-2021 se aumento el limit para que agarre el ultimo registro de quien aprobo la cotizacion

  			SELECT		twf.id_funcionario,
        				vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          				to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            into
        				v_id_funcionario_resp_qr_oficial,
                		v_nombre_funcionario_resp_qr_oficial,
                		v_fecha_firma_resp_qr
          	FROM wf.testado_wf twf
          		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
          		INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          	WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            	  AND  te.codigo = 'cotizacion'
                  and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
           	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
            ORDER BY  twf.fecha_reg DESC
			LIMIT 1;

  	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_resp_qr_oficial,v_fecha_po);
  else
            --(may) 02-02-2021 se aumento el limit para que agarre el ultimo registro de quien aprobo la cotizacion

  			SELECT		twf.id_funcionario,
        				vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Pública Nacional Estratégica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          				to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            into
        				v_id_funcionario_resp_qr_oficial,
                		v_nombre_funcionario_resp_qr_oficial,
                		v_fecha_firma_resp_qr
          	FROM wf.testado_wf twf
          		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          		INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            	 AND  te.codigo ='cotizacion'
                 and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
           	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo
            ORDER BY  twf.fecha_reg DESC
			LIMIT 1;

  	remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_resp_qr_oficial);
  end if;

      if(remplaso is null)THEN

              v_nombre_funcionario_resp_qr = v_nombre_funcionario_resp_qr_oficial;

      else
              v_nombre_funcionario_resp_qr = remplaso.desc_funcionario1;

      end if;

        select s.estado
        into
        v_codigo_pre
        from adq.tsolicitud s
        where s.id_proceso_wf = v_id_proceso_wf_adq;

		--24-08-2021 (may) modificacion d.cantidad_de para que cuente el totalde los registros de cotizacion_det


			v_consulta:='select s.id_solicitud,
initcap(pxp.f_convertir_num_a_letra( mat.f_id_detalle_cotizacion(c.id_cotizacion)))::varchar as item_selecionados,
								''''::varchar,
                               s.nro_tramite,
                                s.origen_pedido,
                                to_char(s.fecha_po,''DD/MM/YYYY'') as fecha_po,
                                pxp.aggarray( d.nro_parte_cot)::text as nro_parte,
                                s.tipo_evaluacion,
                                (case
                                when s.tipo_evaluacion =''Reparacion''then
                                s.taller_asignado
                                else
                                ''N/A''
                                end::varchar )as taller_asignado,
                                COALESCE(s.observacion_nota,''N/A'')::varchar as observacion_nota,
                                (select count(ng.id_solicitud)
                                from mat.tgestion_proveedores_new ng
                                where ng.id_solicitud = s.id_solicitud)::integer as cotizacion_solicitadas,
                                c.nro_cotizacion,
                                c.monto_total,
                                (select count(z.id_proveedor)
                                from mat.tcotizacion z
                                where z.id_solicitud = s.id_solicitud
                                )::integer as proveedores_resp,
                                initcap (p.desc_proveedor)::varchar as desc_proveedor,
                                '''||COALESCE (initcap(v_nombre_funcionario_dc_qr),' ')||'''::varchar as aero,
                                    '''||COALESCE (v_fecha_firma_dc_qr,' ')||'''::text as fecha_aero,
                                    '''||COALESCE (initcap(v_nombre_funcionario_rev_qr),' ')||'''::varchar as visto_rev,
                                    '''||COALESCE (v_fecha_firma_rev_qr,' ')||'''::text as fecha_rev,
                                    '''||COALESCE (initcap(v_nombre_funcionario_abas_qr),' ')||'''::varchar as visto_abas,
                                    '''||COALESCE (v_fecha_firma_abas_qr,' ')||'''::text as fecha_abas,
                                c.obs,
                                c.recomendacion,
                                mo.codigo,
                                '''||COALESCE(initcap(v_nombre_funcionario_presu_qr),' ')||'''::varchar AS funcionario_pres,
                                '''||COALESCE(v_codigo_pre,' ')||'''::varchar AS codigo_pres,
                                ''''::text as fecha_pres,
                                s.estado as estado_materiales,
                               d.nro_parte_cot::varchar,
                               d.descripcion_cot::varchar,
                               d.cantidad_det,
                               d.cd,
                               /*Aumentando este campo para mostrar el PN cotizacion (Ismael Valdivia 06/10/2020)*/
                               ''''::varchar as explicacion_detallada_part_cot,
                               /**********************************************************************************/
							   da.codigo_tipo,
                               '''||COALESCE (initcap(v_nombre_funcionario_resp_qr),' ')||'''::varchar as funcionario_resp,
                               '''||COALESCE (v_fecha_firma_resp_qr,' ')||'''::text as fecha_resp,
                               s.fecha_solicitud::date,
							   s.estado_firma,
                               '''||v_fecha_salida_gm||'''::date as fecha_salida,
                               ''''::varchar as firma_tecnico_abastecimiento,
                               '''||v_aplica_cambio_etiqueta||'''::varchar as cambio_etiqueta
                                from mat.tsolicitud s
                                inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = ''si''
                                inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion  and d.tipo_cot <> ''Otros Cargos'' and  d.tipo_cot <>''NA'' and d.revisado = ''si''
                                inner join param.vproveedor p on p.id_proveedor = c.id_proveedor
                                inner join param.tmoneda mo on mo.id_moneda = c.id_moneda
                                left join mat.tgestion_proveedores ge on ge.id_solicitud = s.id_solicitud
                                left join mat.tday_week da on da.id_day_week =d.id_day_week

                                left join wf.testado_wf es on es.id_estado_wf = s.id_estado_wf and es.id_tipo_estado=787
                                left join orga.vfuncionario_persona vfp on vfp.id_funcionario = es.id_funcionario

								where  s.id_proceso_wf ='||v_parametros.id_proceso_wf;
			--Devuelve la respuesta
            v_consulta:=v_consulta||'GROUP BY s.id_solicitud,ge.cotizacion_solicitadas,c.nro_cotizacion,c.monto_total,p.desc_proveedor,c.obs,c.recomendacion,mo.codigo,  d.nro_parte_cot,
                               d.descripcion_cot,
                               d.cantidad_det,
                               d.cd,
							   da.codigo_tipo,
                           	   c.id_cotizacion,
                               s.fecha_solicitud,
                               d.id_detalle';
       end if;
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_RDOC_CON_EXT_SEL'
 	#DESCRIPCION:	Reporte Documento de Contratacion del Exterior
 	#AUTOR:	 Franklin espinoza
 	#FECHA:		28-06-2017
	***********************************/
    elsif(p_transaccion='MAT_RDOC_CON_EXT_SEL')then
		begin

        SELECT ts.nro_cite_dce, ts.nro_tramite, ts.fecha_solicitud
        INTO v_nro_cite_dce, v_num_tramite, v_fecha_ini
        FROM mat.tsolicitud ts
        WHERE ts.id_proceso_wf = v_parametros.id_proceso_wf;

        IF(v_nro_cite_dce is null)THEN

          IF (substr(v_num_tramite,1,2)='GM')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GM.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          ELSIF (substr(v_num_tramite,1,2)='GA')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GA.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          ELSIF (substr(v_num_tramite,1,2)='GO')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GO.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          ELSIF (substr(v_num_tramite,1,2)='GC')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GC.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          /*Incluimos a los BoA REP (Ismael Valdivia 05/05/2020)*/
          ELSIF (substr(v_num_tramite,1,2)='GR')THEN
          	v_nro_cite_dce = 'OB.DAB.DCE.GR.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          /******************************************************/
          END IF;


          UPDATE mat.tsolicitud SET
          nro_cite_dce = v_nro_cite_dce
          WHERE id_proceso_wf = v_parametros.id_proceso_wf;
         END IF;

		IF v_fecha_ini > to_date('20190901','YYYYMMDD') THEN
          	v_nro_cite_dce = REPLACE(v_nro_cite_dce,'.DCE.','.');
        END IF;
          v_consulta:='select
                          det.descripcion,
                          det.estado_reg,
                          det.nro_parte,
                          det.referencia,
                          det.nro_parte_alterno,
                          det.precio_unitario,
                          det.cantidad_sol,
                          det.id_usuario_reg,
                          det.usuario_ai,
                          det.fecha_reg,
                          det.id_usuario_ai,
                          det.id_usuario_mod,
                          det.fecha_mod,
                          usu1.cuenta as usr_reg,
                          usu2.cuenta as usr_mod,
                          un.codigo,
                          un.descripcion as desc_descripcion,
                          det.tipo,
                          s.estado,
                          '''||v_nro_cite_dce||'''::varchar as nro_cite_dce,
                          s.fecha_solicitud::date,
                          s.condicion::varchar,
                          s.lugar_entrega::varchar,
                          s.tiempo_entrega::numeric,
                          '''||v_fecha_salida_gm||'''::date as fecha_salida,

                          (CASE
                                     --WHEN trim(det.nro_parte_alterno) != '''' and trim(det.nro_parte_alterno) != ''-''
                                     WHEN ((trim(det.nro_parte_alterno) != '''' and trim(det.nro_parte_alterno) != ''-'' and trim(det.nro_parte_alterno) != ''N/A'') and (trim(detcot.explicacion_detallada_part_cot) != trim(det.nro_parte_alterno)) and (trim(detcot.explicacion_detallada_part_cot) != trim(det.nro_parte)))


                                     THEN trim(det.nro_parte_alterno)||'',''||detcot.explicacion_detallada_part_cot

                                     WHEN ((trim(det.nro_parte_alterno) = '''' or trim(det.nro_parte_alterno) = ''-'' or trim(det.nro_parte_alterno) = ''N/A'') and (trim(detcot.explicacion_detallada_part_cot) != trim(det.nro_parte_alterno)) and (trim(detcot.explicacion_detallada_part_cot) != trim(det.nro_parte)))

									  THEN detcot.explicacion_detallada_part_cot

                                     WHEN (
                                          (trim(det.nro_parte_alterno) != '''' and trim(det.nro_parte_alterno) != ''-'' and trim(det.nro_parte_alterno) != ''N/A'')

                                          and (trim(detcot.explicacion_detallada_part_cot) = trim(det.nro_parte_alterno))

                                          and (trim(det.nro_parte_alterno) = trim(det.nro_parte))

                                          )

                                      THEN ''''::varchar

                                     --ELSE  detcot.explicacion_detallada_part_cot
                                     ELSE  det.nro_parte_alterno
                                END)::varchar

                          from mat.tdetalle_sol det
                          inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod

                          left join mat.tcotizacion cot on cot.id_solicitud = det.id_solicitud and cot.adjudicado = ''si''
                          left join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion and detcot.id_detalle = det.id_detalle and det.estado_excluido = ''no''



                          inner join mat.tunidad_medida un on un.id_unidad_medida = det.id_unidad_medida
                          inner join mat.tsolicitud s on s.id_solicitud = det.id_solicitud and det.estado_reg = ''activo''
                          where det.estado_excluido = ''no'' and s.id_proceso_wf = '||v_parametros.id_proceso_wf||'
                          ORDER BY det.id_detalle desc';

            raise notice 'v_consulta %',v_consulta;
			return v_consulta;
	end;
	/*********************************
 	#TRANSACCION:  'MAT_REPORGR_SEL'
 	#DESCRIPCION:	Reporte comite evaluacion de compra y selecion de proveedor
 	#AUTOR:	 Ismael Valdivia
 	#FECHA:		08-05-2020
	***********************************/
    elsif(p_transaccion='MAT_REPORGR_SEL')then

		begin

        select
        			to_char(sou.fecha_po,'DD/MM/YYYY')as fechapo,
                    to_char(sou.fecha_solicitud,'DD/MM/YYYY')as fechasol,
                    sou.id_proceso_wf_firma,
                    sou.estado_firma,
                    sou.estado,
                    sou.nro_tramite
                    into
                    v_fecha_po,
                    v_fecha_solicitud,
                    v_id_proceso_wf_firma,
                    v_estado_firma_paralelo,
                    v_estado_actual,
                    v_nro_tramite
                    from mat.tsolicitud sou
                    where sou.id_proceso_wf = v_parametros.id_proceso_wf;

                    if (v_fecha_po is null)then
                    	v_fecha_po = '';
                    end if;

        if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

         		 /*Aqui recuperamos la Firma del encargado del comite de aeronavegabilidad*/
                 if (v_estado_firma_paralelo = 'autorizado') then

                          SELECT to_char(twf.fecha_reg, 'DD/MM/YYYY') as fecha_firma into v_fecha_firma_dc_qr
                          FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND
                                te.codigo = 'autorizado'; --comite aeronavegabilidad


                  SELECT		twf.id_funcionario,
                              vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1

                  into
                              v_id_funcionario_dc_qr_oficial,
                              v_nombre_funcionario_dc_qr_oficial

                  FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                      INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      WHERE twf.id_proceso_wf = v_id_proceso_wf_firma
                      AND  te.codigo = 'comite_aeronavegabilidad'
                      AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now())
                      GROUP by twf.id_funcionario, vf.desc_funcionario1, vf.nombre_cargo, pro.nro_tramite, twf.fecha_reg
                      ORDER BY twf.fecha_reg DESC
                      limit 1;

                      /*Aumentando del Iterinado*/
                      if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                          remplaso = mat.f_firma_modif(v_id_proceso_wf_firma,v_id_funcionario_dc_qr_oficial,v_fecha_solicitud::varchar);

                          if(remplaso is null)THEN
                             	v_nombre_funcionario_dc_qr_oficial = v_nombre_funcionario_dc_qr_oficial;
                          else
                                v_nombre_funcionario_dc_qr_oficial = remplaso.desc_funcionario1;
                          end if;
                      else
                          v_nombre_funcionario_dc_qr_oficial = v_nombre_funcionario_dc_qr_oficial;
                      end if;
                      /*******************************/

                  /*************************************************************************/
                 end if;

                  /*Aqui recuperamos la firma del encargado del dpto de abastecimiento*/
 				  SELECT      twf.id_funcionario,
                              vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                              to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
              	  into
                          v_id_funcionario_abas_qr_oficial,
                          v_nombre_funcionario_abas_qr_oficial,
                          v_fecha_firma_abas_qr
                  FROM wf.testado_wf twf
                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                  INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                  INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                  AND te.codigo = 'vb_dpto_abastecimientos'
                  and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now())
                  GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                  ORDER BY twf.fecha_reg DESC
                  limit 1;

                  /*Aumentando del Iterinado*/
                  if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                      remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf ,v_id_funcionario_abas_qr_oficial,v_fecha_solicitud::varchar);

                      if(remplaso is null)THEN
                            v_nombre_funcionario_abas_qr_oficial = v_nombre_funcionario_abas_qr_oficial;
                      else
                            v_nombre_funcionario_abas_qr_oficial = remplaso.desc_funcionario1;
                      end if;
                  else
                      v_nombre_funcionario_abas_qr_oficial = v_nombre_funcionario_abas_qr_oficial;
                  end if;
                  /*******************************/
                  /**************************************************************************/

                  /*Aqui recuperamos la Firma del encargado del comite de Unidad de abastecimiento*/
                  if (v_estado_actual != 'comite_unidad_abastecimientos') then

                          SELECT to_char(twf.fecha_reg, 'DD/MM/YYYY') as fecha_firma into v_fecha_firma_rev_qr
                          FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND
                                te.codigo = 'autorizado'; --comite abastecimiento


                  SELECT          twf.id_funcionario,
                                  vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1

                  INTO
                              v_id_funcionario_rev_qr_oficial,
                              v_nombre_funcionario_rev_qr_oficial

                  FROM wf.testado_wf twf
                        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                        WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                        AND te.codigo = 'comite_unidad_abastecimientos'
                        and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now())
                        GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                        ORDER by twf.fecha_reg DESC
                        limit 1;

                        /*Aumentando del Iterinado*/
                        if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                            remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf ,v_id_funcionario_rev_qr_oficial,v_fecha_solicitud::varchar);

                            if(remplaso is null)THEN
                                  v_nombre_funcionario_rev_qr_oficial = v_nombre_funcionario_rev_qr_oficial;
                            else
                                  v_nombre_funcionario_rev_qr_oficial = remplaso.desc_funcionario1;
                            end if;
                        else
                            v_nombre_funcionario_rev_qr_oficial = v_nombre_funcionario_rev_qr_oficial;
                        end if;
                        /*******************************/

                  end if;
                  /*********************************************************************************************************************************/

                  /**************************************Aqui recuperamos la firma del encargado RPCE********************************************/
        			if (v_estado_actual != 'vb_rpcd') then
                    SELECT 	twf.id_funcionario,
                            vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                            to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma,
                            te.codigo

                    INTO
                            v_id_funcionario_presu_qr_oficial,
                            v_nombre_funcionario_presu_qr_oficial,
                            v_fecha_firma_presu_qr,
                            v_codigo_rpc
                    FROM wf.testado_wf twf
                        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                        WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                        AND te.codigo = 'vb_rpcd'
                        and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now())
                        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg
                        ORDER BY twf.fecha_reg DESC
                        limit 1;


                     /*Aumentando del Iterinado*/
                      if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                          remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf ,v_id_funcionario_presu_qr_oficial,v_fecha_solicitud::varchar);

                          if(remplaso is null)THEN
                                v_nombre_funcionario_presu_qr_oficial = v_nombre_funcionario_presu_qr_oficial;
                          else
                                v_nombre_funcionario_presu_qr_oficial = remplaso.desc_funcionario1;
                          end if;
                      else
                          v_nombre_funcionario_presu_qr_oficial = v_nombre_funcionario_presu_qr_oficial;
                      end if;
                      /*******************************/



                    end if;
                  /********************************************************************************************************************************/

                  if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                      SELECT		twf.id_funcionario,
                                  vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                  to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                      into
                                  v_id_funcionario_resp_qr_oficial,
                                  v_nombre_funcionario_resp_qr_oficial,
                                  v_fecha_firma_resp_qr
                      FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                            AND  te.codigo = 'compra'
                            AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now())
                      GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                      ORDER BY twf.fecha_reg DESC
                      limit 1;

                    else
                    SELECT		twf.id_funcionario,
                                vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                    into
                                v_id_funcionario_resp_qr_oficial,
                                v_nombre_funcionario_resp_qr_oficial,
                                v_fecha_firma_resp_qr
                    FROM wf.testado_wf twf
                        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                    WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                          AND  te.codigo = 'cotizacion'
                          AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now())
                    GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                    ORDER BY twf.fecha_reg DESC
                    limit 1;
                    end if;



                   /*Aumentando del Iterinado*/
                    if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                        remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf ,v_id_funcionario_resp_qr_oficial,v_fecha_solicitud::varchar);

                        if(remplaso is null)THEN
                              v_nombre_funcionario_resp_qr_oficial = v_nombre_funcionario_resp_qr_oficial;
                        else
                              v_nombre_funcionario_resp_qr_oficial = remplaso.desc_funcionario1;
                        end if;
                    else
                        v_nombre_funcionario_resp_qr_oficial = v_nombre_funcionario_resp_qr_oficial;
                    end if;
                    /*******************************/


                    /*Aumentando para recueperar la firma del tecnico auxiliar de abastecimientos*/
                    if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                    SELECT		twf.id_funcionario,
                                vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                    into
                                v_id_funcionario_tecnico_abas,
                                v_nombre_funcionario_tecnico_abas,
                                v_fecha_firma_tecnico_abas
                    FROM wf.testado_wf twf
                        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                    WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                          AND  te.codigo = 'revision_tecnico_abastecimientos'
                          AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now())
                    GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                    ORDER BY twf.fecha_reg DESC
                    limit 1;



                   /*Aumentando del Iterinado*/

                        remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf ,v_id_funcionario_resp_qr_oficial,v_fecha_solicitud::varchar);

                        if(remplaso is null)THEN
                              v_nombre_funcionario_tecnico_abastecimiento = v_nombre_funcionario_tecnico_abas;
                        else
                              v_nombre_funcionario_tecnico_abastecimiento = remplaso.desc_funcionario1;
                        end if;

                        v_nombre_funcionario_tecnico_abastecimiento = v_nombre_funcionario_tecnico_abas;
                   	else
                    	v_nombre_funcionario_tecnico_abastecimiento = '';
                    end if;
                    /*****************************************************************************/






        end if;

        if(v_nombre_funcionario_dc_qr_oficial is null )THEN
                v_nombre_funcionario_dc_qr_oficial = '';
        end if;

        if (v_nombre_funcionario_abas_qr_oficial is null)THEN
                v_nombre_funcionario_abas_qr_oficial = '';
        end if;

        if(v_nombre_funcionario_rev_qr_oficial is null)THEN
                v_nombre_funcionario_rev_qr_oficial = '';
        end if;

        if( v_nombre_funcionario_presu_qr_oficial is null)THEN
                v_nombre_funcionario_presu_qr_oficial = '';
        end if;


        if(v_nombre_funcionario_resp_qr_oficial is null)THEN
                v_nombre_funcionario_resp_qr_oficial = '';
        end if;

        if(v_nombre_funcionario_abas_qr_oficial IS NULL)THEN
        	v_nombre_funcionario_abas_qr_oficial = '';
        end if;

        if (v_nombre_funcionario_tecnico_abastecimiento is null) then
        	v_nombre_funcionario_tecnico_abastecimiento = '';
        end if;

       	v_fecha_comite = ' ';
        if (v_fecha_firma_dc_qr is not null and v_fecha_firma_rev_qr is not null) THEN
        	if (v_fecha_firma_dc_qr::date > v_fecha_firma_rev_qr::date) then
            	v_fecha_comite = v_fecha_firma_dc_qr;
            elsif (v_fecha_firma_rev_qr::date > v_fecha_firma_dc_qr::date) then
            	v_fecha_comite = v_fecha_firma_abas_qr;
            elsif (v_fecha_firma_rev_qr::date = v_fecha_firma_dc_qr::date) then
            	v_fecha_comite = v_fecha_firma_rev_qr;
            end if;
        end if;

        	select
                                sol.tipo_evaluacion::varchar,
                                list(det.nro_parte)::varchar as detalle,
                                pro.rotulo_comercial::varchar as proveedor,
                                count(det.nro_parte)::varchar as cotizaciones_recibidas,
                                rtrim (replace (pxp.f_convertir_num_a_letra(COALESCE (count(det.nro_parte),0)),' 00/100',''),' ')::varchar as literal,
                                sol.taller_asignado::varchar,
                                to_char(cot.fecha_cotizacion,'DD/MM/YYYY')::varchar,
                                cot.obs::varchar,
                                sol.id_solicitud
                                INTO
                                v_evaluacion,
                                v_nro_parte_sol,
                                v_nom_provee,
                                v_cotizaciones_recibidas,
                                v_literal,
                                v_taller_asignado,
                                v_fecha_cotizacion,
                                v_observaciones_sol,
                                v_id_solicitud
                        from mat.tsolicitud sol
                        inner join mat.tdetalle_sol det on det.id_solicitud = sol.id_solicitud and det.estado_excluido = 'no'
                        left join param.vproveedor2 pro on pro.id_proveedor = sol.id_proveedor
                        left join mat.tcotizacion cot on cot.id_solicitud = sol.id_solicitud and cot.adjudicado = 'si'
                        where sol.id_proceso_wf =v_parametros.id_proceso_wf
                        group by sol.tipo_evaluacion,
                                     pro.rotulo_comercial,
                                     sol.taller_asignado,
                                     cot.fecha_cotizacion,
                                     cot.obs,
                                     sol.id_solicitud;

                        if (v_evaluacion is null) then
                        	v_evaluacion = '';
                        end if;
                        if (v_nro_parte_sol is null) then
                        	v_nro_parte_sol = '';
                        end if;
                        if (v_nom_provee is null) then
                        	v_nom_provee = '';
                        end if;
                        if (v_cotizaciones_recibidas is null) then
                        	v_cotizaciones_recibidas = '';
                        end if;
                        if (v_literal is null) then
                        	v_literal = '';
                        end if;
                        if (v_taller_asignado is null) then
                        	v_taller_asignado = '';
                        end if;
                        if (v_fecha_cotizacion is null) then
                        	v_fecha_cotizacion = '';
                        end if;
                        if (v_observaciones_sol is null) then
                        	v_observaciones_sol = '';
                        end if;

                        select coalesce(sum(detcot.precio_unitario_mb),0)
                        into v_suma_totales
                        from mat.tcotizacion coti
                        inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = coti.id_cotizacion
                        where coti.id_solicitud = v_id_solicitud and coti.adjudicado = 'si';

        	/*Aumentando para cambiar etiqueta*/
            if (v_fecha_comite >= '01/03/2022') then
            	v_etiqueta = 'si';
            else
            	v_etiqueta = 'no';
            end if;
			/***********************************/


          	v_consulta :='select 	('''||v_evaluacion||''')::varchar as evaluacion,
                                    ('''||v_nro_parte_sol||''')::varchar as parte_solicitada,
                                    ('''||v_nom_provee||''')::varchar as proveedor,
                                    ('''||v_cotizaciones_recibidas||''')::varchar as cotizaciones_recibidas,
                                    ('''||v_literal||''')::varchar as literal,
                                    ('''||v_taller_asignado||''')::varchar as taller_asignado,
                                    ('''||v_fecha_comite||''')::varchar as fecha_cotizacion,
                                    ('''||v_observaciones_sol||''')::varchar as observaciones,
                                    ('''||v_suma_totales||''')::varchar as total,
                                    ('''||v_nombre_funcionario_dc_qr_oficial||''')::varchar as firma_aeronavegabilidad,
                                    ('''||v_nombre_funcionario_rev_qr_oficial||''')::varchar as firma_abastecimiento,
                                    ('''||v_nombre_funcionario_presu_qr_oficial||''')::varchar as firma_rpce,
                                    ('''||v_nombre_funcionario_resp_qr_oficial||''')::varchar as firma_auxiliar,
                                    ('''||v_nombre_funcionario_abas_qr_oficial||''')::varchar as firma_jefe_departamento,
                                    ('''||v_nombre_funcionario_tecnico_abastecimiento||''')::varchar as firma_tecnico_abastecimiento,
                                    ('''||v_nro_tramite||''')::varchar as nro_tramite,
                                    ('''||v_etiqueta||''')::varchar as cambiar_etiqueta';
          	raise notice '%',v_consulta;

			return v_consulta;

		end;
	/*********************************
 	#TRANSACCION:  'MAT_REP_COMP_BYS_SEL'
 	#DESCRIPCION:	Reporte Documento proceso de contratacion mediante comparacion de oferta de bienes y servicios
 	#AUTOR:	 Franklin espinoza
 	#FECHA:		28-06-2017
	***********************************/
   elsif(p_transaccion='MAT_REP_COMP_BYS_SEL')then
		begin

        SELECT ts.nro_cite_cobs, ts.nro_tramite, to_char(ts.fecha_solicitud, 'DD/MM/YYYY')as fechasol, ts.estado, ts.id_solicitud
        INTO v_nro_cite_dce, v_num_tramite, v_fecha_solicitud, v_estado_actual, v_id_solicitud_reporte
        FROM mat.tsolicitud ts
        WHERE ts.id_proceso_wf = v_parametros.id_proceso_wf;

        IF(v_nro_cite_dce is null)THEN

          IF (substr(v_num_tramite,1,2)='GM')THEN
          	v_nro_cite_dce = 'OB.DAB.GM.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          ELSIF (substr(v_num_tramite,1,2)='GA')THEN
          	v_nro_cite_dce = 'OB.DAB.GA.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          ELSIF (substr(v_num_tramite,1,2)='GO')THEN
          	v_nro_cite_dce = 'OB.DAB.GO.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
           ELSIF (substr(v_num_tramite,1,2)='GC')THEN
          	v_nro_cite_dce = 'OB.DAB.GC.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          ELSIF (substr(v_num_tramite,1,2)='GR')THEN
          	v_nro_cite_dce = 'OB.DAB.GC.'||ltrim(substr(v_num_tramite,7,6),'0')||'.'||substr(v_num_tramite,14,17);
          END IF;

          UPDATE mat.tsolicitud SET
          nro_cite_cobs = v_nro_cite_dce
          WHERE id_proceso_wf = v_parametros.id_proceso_wf;
        END IF;

        SELECT  twf.id_funcionario,
                twf.fecha_reg
              into  v_revision
        FROM wf.testado_wf twf
        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
        WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
        AND te.codigo = 'revision'
        GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;


        if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
         /*Aumentamos para que los reportes no sean afectados*/
         if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
			if (v_fecha_solicitud ::date >= v_fecha_nuevo_flujo::date) then
            	SELECT 	twf.id_funcionario,
                      vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                      vf.desc_funcionario1,
                      to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                INTO v_id_funcionario_oficial,
                      v_funcionario_sol_aux_abas,
                      v_funcionario_oficial,
                      v_fecha_firma_aux_abas
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision_tecnico_abastecimientos'

                and v_revision.fecha_reg::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                ORDER BY  twf.fecha_reg DESC
                limit 1;

                if (v_id_funcionario_oficial is null) then
                	SELECT 	twf.id_funcionario,
                            vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                            vf.desc_funcionario1,
                            to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                      INTO v_id_funcionario_oficial,
                            v_funcionario_sol_aux_abas,
                            v_funcionario_oficial,
                            v_fecha_firma_aux_abas
                      FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                      INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'cotizacion_solicitada'

                      and v_revision.fecha_reg::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                      GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                      ORDER BY  twf.fecha_reg DESC
                      limit 1;
                end if;

                remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_aux_abas,v_fecha_solicitud);

                if(remplaso is null)THEN
                    v_funcionario_auxiliar_abastecimiento = v_funcionario_sol_aux_abas;
                    v_funcionario_abas     = v_funcionario_oficial_aux_abas;
                else
                    v_funcionario_auxiliar_abastecimiento = remplaso.desc_funcionario1;
                    v_funcionario_abas = remplaso.funcion;
                end if;


                SELECT 	twf.id_funcionario,
                      vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                      vf.desc_funcionario1,
                      to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                INTO v_id_funcionario_oficial_revision,
                      v_funcionario_sol_oficial,
                      v_funcionario_oficial_revision,
                      v_fecha_firma_pru
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision'

                and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                ORDER BY  twf.fecha_reg DESC
                limit 1;



            else
            	SELECT 	twf.id_funcionario,
                      vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                      vf.desc_funcionario1,
                      to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                INTO v_id_funcionario_oficial,
                      v_funcionario_sol_oficial,
                      v_funcionario_oficial,
                      v_fecha_firma_pru
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision'

                and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                ORDER BY  twf.fecha_reg DESC
                limit 1;
            end if;


          else
                SELECT 	twf.id_funcionario,
                      vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                      vf.desc_funcionario1,
                      to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                INTO v_id_funcionario_oficial,
                      v_funcionario_sol_oficial,
                      v_funcionario_oficial,
                      v_fecha_firma_pru
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision'
                --and vf.fecha_finalizacion is null
                --21-04-2021 (may) modificacion coalesce al reves coalesce(vf.fecha_finalizacion,now())
                and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                 GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                 ORDER BY  twf.fecha_reg DESC
                limit 1;
                 --raise exception 'Aqui lelga data %, %',v_parametros.id_proceso_wf, v_revision.fecha_reg;
          end if;
            	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud);
        else
         if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
              SELECT 	 twf.id_funcionario,
                     vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                     vf.desc_funcionario1,
                     to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
              INTO 	v_id_funcionario_oficial,
                    v_funcionario_sol_oficial,
                    v_funcionario_oficial,
                    v_fecha_firma_pru
              FROM wf.testado_wf twf
              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
              INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
              WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision'

              and v_revision.fecha_reg::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
              GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo, twf.fecha_reg
              ORDER BY  twf.fecha_reg DESC
              limit 1;
          else
            SELECT twf.id_funcionario,
                   vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                   vf.desc_funcionario1,
                   to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            INTO   v_id_funcionario_oficial,
                   v_funcionario_sol_oficial,
                   v_funcionario_oficial,
                   v_fecha_firma_pru
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision'
            --and vf.fecha_finalizacion is null
            --21-04-2021 (may) modificacion coalesce al reves coalesce(vf.fecha_finalizacion,now())
            and v_fecha_solicitud::date between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion, now())
            GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo, twf.fecha_reg
            ORDER BY  twf.fecha_reg DESC
            limit 1;
          end if;
            remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_oficial);
        end if;

          if(remplaso is null)THEN
              v_funcionario_sol = v_funcionario_sol_oficial;
              v_funcionario     = v_funcionario_oficial;
          else
              if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
              v_funcionario_sol = v_funcionario_sol_oficial;
              else
              v_funcionario_sol = remplaso.desc_funcionario1;
              end if;
              v_funcionario	    = remplaso.funcion;
          end if;

          /*Aumentando consulta para recuperar padre uo (Ismael Valdivia 11/02/2021) */
          SELECT est.id_uo_padre into v_id_uo_padre
          FROM orga.testructura_uo est
          WHERE est.id_uo_hijo = (select car.id_uo
          						  from orga.vfuncionario_cargo car
                                  where car.id_funcionario = v_id_funcionario_oficial
                                  and v_fecha_solicitud::date between car.fecha_asignacion and COALESCE(car.fecha_finalizacion,now())
                                  );

          SELECT uo.id_nivel_organizacional into v_nivel_organizacional
          FROM orga.tuo uo
          where uo.id_uo = v_id_uo_padre;
          /************************************************/


          WITH RECURSIVE gerencia(id_uo, id_nivel_organizacional, nombre_unidad, nombre_cargo) AS (
              SELECT tu.id_uo, tu.id_nivel_organizacional, tu.nombre_unidad, tu.nombre_cargo
              FROM orga.tuo  tu
              INNER JOIN orga.tuo_funcionario tf ON tf.id_uo = tu.id_uo
              WHERE tf.id_funcionario = v_id_funcionario_oficial
              /*Se adiciono la fecha para tomar en cuenta en caso que el funcionario este inactivo*/
              AND ((v_fecha_solicitud::date between tf.fecha_asignacion and tf.fecha_finalizacion)
              OR (v_fecha_solicitud::date >= tf.fecha_asignacion and tf.fecha_finalizacion is null))
              --and tu.estado_reg = 'activo'

              UNION ALL

              SELECT teu.id_uo_padre, tu1.id_nivel_organizacional, tu1.nombre_unidad, tu1.nombre_cargo
              FROM orga.testructura_uo teu
              INNER JOIN gerencia g ON g.id_uo = teu.id_uo_hijo
              INNER JOIN orga.tuo tu1 ON tu1.id_uo = teu.id_uo_padre
              WHERE g.id_nivel_organizacional <> v_nivel_organizacional
          	)
            SELECT  pxp.aggarray( nombre_unidad )
            INTO v_nom_unidad
            FROM gerencia;



		WITH RECURSIVE firmas(id_estado_fw, id_estado_anterior,fecha_reg, codigo, id_funcionario) AS (
                              SELECT tew.id_estado_wf, tew.id_estado_anterior , tew.fecha_reg, te.codigo, tew.id_funcionario
                              FROM wf.testado_wf tew
                              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = tew.id_tipo_estado
                              WHERE tew.id_proceso_wf = v_parametros.id_proceso_wf

                              UNION ALL

                              SELECT ter.id_estado_wf, ter.id_estado_anterior, ter.fecha_reg, te.codigo, ter.id_funcionario
                              FROM wf.testado_wf ter
                              INNER JOIN firmas f ON f.id_estado_anterior = ter.id_estado_wf
                              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = ter.id_tipo_estado
                              WHERE f.id_estado_anterior IS NOT NULL
    )
    SELECT id_estado_fw
    into
    v_id_despacho
    FROM firmas
  	WHERE codigo = 'despachado' and fecha_reg::date = ( SELECT    max (tew.fecha_reg::date)
                              FROM wf.testado_wf tew
                              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = tew.id_tipo_estado
                              WHERE tew.id_proceso_wf = v_parametros.id_proceso_wf and te.codigo = 'despachado');



    select pwf.id_proceso_wf
	INTO
    v_id_proceso_wf_adq
    from wf.tproceso_wf pwf
	where  pwf.id_estado_wf_prev = v_id_despacho;

    if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
      SELECT  twf.id_funcionario,
              twf.fecha_reg
            into  v_vbgerencia
      FROM wf.testado_wf twf
      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
      /*Comentando esta  parte para recuperar la Firma del Encargado(a) de vb_dpto_administrativo*/
      /*WHERE twf.id_proceso_wf = v_id_proceso_wf_adq
      AND te.codigo = 'vbgerencia'*/
      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
      AND te.codigo = 'vb_dpto_administrativo'
	  GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg
      ORDER BY  twf.fecha_reg DESC
            limit 1;
     else
       SELECT  twf.id_funcionario,
                twf.fecha_reg
              into  v_vbgerencia
        FROM wf.testado_wf twf
        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
        WHERE twf.id_proceso_wf = v_id_proceso_wf_adq
        AND te.codigo = 'vbgerencia'
        GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg
        ORDER BY  twf.fecha_reg DESC
            limit 1;
     end if;


  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
  	if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
  	 SELECT 	twf.id_funcionario,
    		vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
    INTO
    	    v_id_funcionario_af_qr_oficial,
        	v_nombre_funcionario_af_qr_ocifial,
        	v_fecha_firma_af_qr
    FROM wf.testado_wf twf
        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario

        /*Comentando esta parte recuperar al Encargado(a) de vb_dpto_administrativo (Ismael Valdivia 20/02/2020)
        WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbgerencia' */
        WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'vb_dpto_administrativo'

              and  v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg
        ORDER BY  twf.fecha_reg DESC
            limit 1;
	else
    SELECT 	twf.id_funcionario,
    		vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
    INTO
    	    v_id_funcionario_af_qr_oficial,
        	v_nombre_funcionario_af_qr_ocifial,
        	v_fecha_firma_af_qr
    FROM wf.testado_wf twf
        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbgerencia'
        --and vf.fecha_finalizacion is null
              and  v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg
        ORDER BY  twf.fecha_reg DESC
            limit 1;

    end if;

  	remplaso = mat.f_firma_modif(v_id_proceso_wf_adq,v_id_funcionario_af_qr_oficial,v_fecha_solicitud);
  else
  if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
  SELECT 	twf.id_funcionario, vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
    INTO
    	    v_id_funcionario_af_qr_oficial,
        	v_nombre_funcionario_af_qr_ocifial,
        	v_fecha_firma_af_qr
    FROM wf.testado_wf twf
        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        /*Comentando esta parte recuperar a MAVY MARCELA TRIGO QUIROGA como vb_dpto_administrativo (Ismael Valdivia 20/02/2020)*/

        WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'vb_dpto_administrativo'

        and  v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,twf.fecha_reg
        ORDER BY  twf.fecha_reg DESC
            limit 1;
	else
    SELECT 	twf.id_funcionario, vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
    INTO
    	    v_id_funcionario_af_qr_oficial,
        	v_nombre_funcionario_af_qr_ocifial,
        	v_fecha_firma_af_qr
    FROM wf.testado_wf twf
        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbgerencia'
        --and vf.fecha_finalizacion is null
        and  v_fecha_solicitud::Date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,twf.fecha_reg
        ORDER BY  twf.fecha_reg DESC
            limit 1;
    end if;
  	remplaso = mat.f_firma_original(v_id_proceso_wf_adq, v_id_funcionario_af_qr_oficial);
  end if;

      if(remplaso is null)THEN

              v_nombre_funcionario_af_qr = v_nombre_funcionario_af_qr_ocifial;

      else
      		if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
              v_nombre_funcionario_af_qr = v_nombre_funcionario_af_qr_ocifial;
            ELSE
              v_nombre_funcionario_af_qr = remplaso.desc_funcionario1;
            end if;

      end if;

	if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
      SELECT  twf.id_funcionario,
              twf.fecha_reg
            into  v_vbrpc
      FROM wf.testado_wf twf
      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
      /*Comentando esta parte para recuperar al encargado(a) de vb_rpcd (Ismael Valdivia 20/02/2020)*/
      /*WHERE twf.id_proceso_wf = v_id_proceso_wf_adq
      AND te.codigo = 'vbrpc'*/
      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
      AND te.codigo = 'vb_rpcd'
	  GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg
      ORDER BY  twf.fecha_reg DESC
            limit 1;
     else
     	SELECT  twf.id_funcionario,
                twf.fecha_reg
              into  v_vbrpc
        FROM wf.testado_wf twf
        INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
        WHERE twf.id_proceso_wf = v_id_proceso_wf_adq
        AND te.codigo = 'vbrpc'
        GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg
        ORDER BY  twf.fecha_reg DESC
            limit 1;
     end if;



  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
  if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
   SELECT  twf.id_funcionario,
    		vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
    INTO
        	v_id_funcionario_presu_qr_oficial,
        	v_nombre_funcionario_presu_qr_oficial,
        	v_fecha_firma_presu_qr

    	FROM wf.testado_wf twf
        	INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
        	INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        	/*Comentando esta parte para recuperar al encargado(a) de vb_rpcd (Ismael Valdivia 20/02/2020)*/

            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'vb_rpcd'

            and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        	GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg
            ORDER BY  twf.fecha_reg DESC
            limit 1;


	else
    SELECT  twf.id_funcionario,
    		vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
    INTO
        	v_id_funcionario_presu_qr_oficial,
        	v_nombre_funcionario_presu_qr_oficial,
        	v_fecha_firma_presu_qr

    	FROM wf.testado_wf twf
        	INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
        	INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        	WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbrpc'
            --and vf.fecha_finalizacion is null
            and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        	GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg
            ORDER BY  twf.fecha_reg DESC
            limit 1;

    end if;
  	remplaso = mat.f_firma_modif(v_id_proceso_wf_adq,v_id_funcionario_presu_qr_oficial,v_fecha_solicitud);
  else
  if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
  	SELECT  twf.id_funcionario,
    		vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
    INTO
        	v_id_funcionario_presu_qr_oficial,
        	v_nombre_funcionario_presu_qr_oficial,
        	v_fecha_firma_presu_qr

    	FROM wf.testado_wf twf
        	INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        	INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        	/*Comentando esta parte para recuperar al encargado(a) de vb_rpcd (Ismael Valdivia 20/02/2020)*/

            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'vb_rpcd'

            and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        	GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,twf.fecha_reg
            ORDER BY  twf.fecha_reg DESC
            limit 1;



	else
    SELECT  twf.id_funcionario,
    		vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
    INTO
        	v_id_funcionario_presu_qr_oficial,
        	v_nombre_funcionario_presu_qr_oficial,
        	v_fecha_firma_presu_qr

    	FROM wf.testado_wf twf
        	INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
        	INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
        	WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbrpc'
            --and vf.fecha_finalizacion is null
            and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        	GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,twf.fecha_reg
            ORDER BY  twf.fecha_reg DESC
            limit 1;

    end if;
  	remplaso = mat.f_firma_original(v_id_proceso_wf_adq, v_id_funcionario_presu_qr_oficial);
  end if;

      if(remplaso is null)THEN

              v_nombre_funcionario_presu_qr = v_nombre_funcionario_presu_qr_oficial;

      else
      	if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN
              v_nombre_funcionario_presu_qr = v_nombre_funcionario_presu_qr_oficial;
      	else
      		 v_nombre_funcionario_presu_qr = remplaso.desc_funcionario1;
      	end if;

      end if;

        select s.estado
        into
        v_codigo_pre
        from adq.tsolicitud s
        where s.id_proceso_wf = v_id_proceso_wf_adq;

	if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN

    	v_funcionario_sol = initcap(v_funcionario_sol);
    	v_nombre_funcionario_af_qr = initcap(v_nombre_funcionario_af_qr);
    	v_nombre_funcionario_presu_qr=initcap(v_nombre_funcionario_presu_qr);

    end if;


    /*Aqui poniendo condicion para la firma*/
    if (v_nom_unidad is not null) then
  		if(v_nom_unidad[3] is not null) then
    		v_gerencia = v_nom_unidad[3];
        ELSIF(v_nom_unidad[2] is not null and v_nom_unidad[3] is null) then
        	v_gerencia = v_nom_unidad[2];
        end if;
    ELSE
    	v_gerencia = '';
    end if;
    /***************************************/


    /*Fecha para verificar si es menor o mayor*/
     select (case when
		   v_fecha_solicitud::date < '01/01/2022'::date then
           'menor'
	    else
           'mayor'
	    end ) into v_es_mayor;
    /******************************************/


      if(v_fecha_solicitud ::date >= v_fecha_salida_gm::date or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) THEN

        select initcap(sol.nro_tramite) into v_nro_tramite
        from mat.tsolicitud sol
        where sol.id_proceso_wf = v_parametros.id_proceso_wf;


       /*Aqui recuperamos al Gerencia*/

      /* select initcap(fun.desc_funcionario1) || ' | '||fun.nombre_cargo||' | '||v_nro_tramite||' | '||replace(v_fecha_firma_presu_qr,'/','-')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1
              into
              v_nombre_funcionario_af_qr
       from orga.vfuncionario_ultimo_cargo fun
       where fun.nombre_cargo = 'Gerencia Administrativa Financiera';*/

      /*Aumentando para recuperar a carlos alba como director de aeronavagabilidad*/
      if(v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
        v_aplica_nuevo_flujo = 'si';
        select initcap(fun.desc_funcionario1) || ' | '||fun.nombre_cargo||' | '||v_nro_tramite||' | '||replace(v_fecha_firma_presu_qr,'/','-')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1 into v_nombre_funcionario_af_qr
               from orga.vfuncionario_cargo fun
        where fun.nombre_cargo = 'Director de Aeronavegabilidad Continua'
        AND v_fecha_solicitud::date between fun.fecha_asignacion and coalesce(fun.fecha_finalizacion,now());

      else
      	v_aplica_nuevo_flujo = 'no';
       select (CASE WHEN ((select 1
                    from orga.vfuncionario_cargo fun
                    where fun.nombre_cargo = 'Gerencia Administrativa Financiera'
                    AND v_fecha_firma_presu_qr::date between fun.fecha_asignacion and coalesce(fun.fecha_finalizacion,now())) = 1
                    ) then
                      (select initcap(fun.desc_funcionario1) || ' | '||fun.nombre_cargo||' | '||v_nro_tramite||' | '||replace(v_fecha_firma_presu_qr,'/','-')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1
                       from orga.vfuncionario_cargo fun
                       where fun.nombre_cargo = 'Gerencia Administrativa Financiera'
                       AND v_fecha_firma_presu_qr::date between fun.fecha_asignacion and coalesce(fun.fecha_finalizacion,now()))
                    else
                      (select initcap(fun.desc_funcionario1) || ' | '||fun.nombre_cargo||' | '||v_nro_tramite||' | '||replace(v_fecha_firma_presu_qr,'/','-')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1
                       from orga.vfuncionario_cargo fun
                       where fun.nombre_cargo = 'Gerente Administrativo Financiero'
                       AND v_fecha_firma_presu_qr::date between fun.fecha_asignacion and coalesce(fun.fecha_finalizacion,now()))
                    end) into v_nombre_funcionario_af_qr;
        end if;
		/************************************************************************/

          v_consulta='select
           			      '''||v_estado_actual||'''::varchar AS estado_actual,
           				  '''||COALESCE(v_nom_unidad[1],'')||'''::varchar AS unidad_sol,
                          '''||v_gerencia||'''::varchar AS gerencia,
                          '''||COALESCE (v_funcionario_sol,'')||'''::varchar AS funcionario_sol,
                          '''||COALESCE(v_nombre_funcionario_af_qr,' ')||'''::varchar AS funcionario_adm,
                          '''||COALESCE(v_nombre_funcionario_presu_qr,' ')||'''::varchar AS funcionario_pres,
                          '''||COALESCE(v_codigo_pre,' ')||'''::varchar AS codigo_pres,
                          COALESCE(array_length(pxp.aggarray(det.nro_parte),1),0)::integer AS nro_items,
                          COALESCE(tgp.adjudicado,''POR COTIZAR'')::varchar AS adjudicado,
                          s.motivo_solicitud::varchar,

                          coalesce(array_to_string(pxp.aggarray(det.nro_parte),''|'')::varchar,''''::varchar) as nro_partes,
                          coalesce(array_to_string(pxp.aggarray(det.nro_parte_alterno),''|'')::varchar,''''::varchar) as nro_partes_alternos,
                            '''||COALESCE(v_nro_cite_dce,'')||'''::varchar AS nro_cobs,
                          s.fecha_solicitud::date,
                          (case
                          		when sp.monto=0 then tc.monto_total
                                when sp.monto is Null then tc.monto_total
                                when sp.monto>0 then sp.monto
                            end)AS monto_ref,
                           '''||COALESCE(v_funcionario,'')||'''::varchar AS funcionario,
                           sp.observaciones,
                           substring(s.nro_tramite from 1 for 2)::varchar as tipo_proceso,
                           '''||v_fecha_salida_gm||'''::date as fecha_salida,
                           coalesce(array_to_string(pxp.aggarray(CASE
                                     --WHEN trim(det.nro_parte_alterno) != '''' and trim(det.nro_parte_alterno) != ''-''
                                      WHEN ((trim(det.nro_parte_alterno) != '''' and trim(det.nro_parte_alterno) != ''-'' and trim(det.nro_parte_alterno) != ''N/A'') and (trim(detcot.explicacion_detallada_part_cot) != trim(det.nro_parte_alterno)) and (trim(detcot.explicacion_detallada_part_cot) != trim(det.nro_parte)))


                                      THEN trim(det.nro_parte_alterno)||'',''||detcot.explicacion_detallada_part_cot


                                      WHEN ((trim(det.nro_parte_alterno) = '''' or trim(det.nro_parte_alterno) = ''-'' or trim(det.nro_parte_alterno) = ''N/A'') and (trim(detcot.explicacion_detallada_part_cot) != trim(det.nro_parte_alterno)) and (trim(detcot.explicacion_detallada_part_cot) != trim(det.nro_parte)))


                                      THEN detcot.explicacion_detallada_part_cot

                                      WHEN (
                                          (trim(det.nro_parte_alterno) != '''' and trim(det.nro_parte_alterno) != ''-'' and trim(det.nro_parte_alterno) != ''N/A'')

                                          and (trim(detcot.explicacion_detallada_part_cot) = trim(det.nro_parte_alterno))

                                          and (trim(det.nro_parte_alterno) = trim(det.nro_parte))

                                          )

                                      THEN ''''::varchar

                                     --ELSE  detcot.explicacion_detallada_part_cot
                                      ELSE  det.nro_parte_alterno
                                END),''|'')::varchar,''''::varchar)::varchar,
                           '''||v_es_mayor||'''::varchar as mayor,
                           '''||v_aplica_nuevo_flujo||'''::varchar as nuevo_flujo,
                           '''||initcap(COALESCE(v_funcionario_sol_aux_abas,''))||'''::varchar as funcionario_auxiliar_abas
                          from mat.tsolicitud s
                          inner join mat.tdetalle_sol det on det.id_solicitud = s.id_solicitud and det.estado_reg = ''activo'' and det.estado_excluido = ''no''

                          left join mat.tcotizacion cot on cot.id_solicitud = s.id_solicitud and cot.adjudicado = ''si''
						  left join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion and detcot.id_detalle = det.id_detalle


						  left join mat.tgestion_proveedores tgp ON tgp.id_solicitud = s.id_solicitud
                          left join mat.tcotizacion tc ON tc.id_solicitud = s.id_solicitud AND tc.adjudicado = ''si''
                          left join mat.tsolicitud_pac sp on sp.id_proceso_wf = s.id_proceso_wf
                          where s.id_proceso_wf = '||v_parametros.id_proceso_wf;

    ELSE
    					v_consulta='select
           			      '''||v_estado_actual||'''::varchar AS estado_actual,
           				  '''||COALESCE(v_nom_unidad[1],'')||'''::varchar AS unidad_sol,
                          '''||v_gerencia||'''::varchar AS gerencia,
                          '''||COALESCE (v_funcionario_sol,'')||'''::varchar AS funcionario_sol,
                          '''||COALESCE(v_nombre_funcionario_af_qr,' ')||'''::varchar AS funcionario_adm,
                          '''||COALESCE(v_nombre_funcionario_presu_qr,' ')||'''::varchar AS funcionario_pres,
                          '''||COALESCE(v_codigo_pre,' ')||'''::varchar AS codigo_pres,
                          COALESCE(array_length(pxp.aggarray(det.nro_parte),1),0)::integer AS nro_items,
                          COALESCE(tgp.adjudicado,''POR COTIZAR'')::varchar AS adjudicado,
                          s.motivo_solicitud::varchar,

                          coalesce(array_to_string(pxp.aggarray(det.nro_parte),''|'')::varchar,''''::varchar) as nro_partes,
                          coalesce(array_to_string(pxp.aggarray(det.nro_parte_alterno),''|'')::varchar,''''::varchar) as nro_partes_alternos,
                            '''||COALESCE(v_nro_cite_dce,'')||'''::varchar AS nro_cobs,
                          s.fecha_solicitud::date,
                          (case
                          		when sp.monto=0 then tc.monto_total
                                when sp.monto is Null then tc.monto_total
                                when sp.monto>0 then sp.monto
                            end)AS monto_ref,
                           '''||COALESCE(v_funcionario,'')||'''::varchar AS funcionario,
                           sp.observaciones,
                           substring(s.nro_tramite from 1 for 2)::varchar as tipo_proceso,
                           '''||v_fecha_salida_gm||'''::date as fecha_salida,
                           ''''::varchar,
                           '''||v_es_mayor||'''::varchar as mayor,
                           ''no''::varchar as nuevo_flujo,
                           ''''::varchar as funcionario_auxiliar_abas
                          from mat.tsolicitud s
                          inner join mat.tdetalle_sol det on det.id_solicitud = s.id_solicitud and det.estado_reg = ''activo''


						  left join mat.tgestion_proveedores tgp ON tgp.id_solicitud = s.id_solicitud
                          left join mat.tcotizacion tc ON tc.id_solicitud = s.id_solicitud AND tc.adjudicado = ''si''
                          left join mat.tsolicitud_pac sp on sp.id_proceso_wf = s.id_proceso_wf
                          where s.id_proceso_wf = '||v_parametros.id_proceso_wf;
	end if;


          v_consulta=v_consulta||' GROUP BY tgp.adjudicado,s.motivo_solicitud,s.fecha_solicitud, monto_ref,sp.observaciones,s.nro_tramite';

            raise notice 'v_consulta %',v_consulta;
			return v_consulta;
	end;


     /*********************************
 	#TRANSACCION:  'MAT_CONENV_REP'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		15-08-2018 20:33:35
	***********************************/

	elsif(p_transaccion='MAT_CONENV_REP')then

		begin
		           select 	soli.fecha_solicitud,
                   			soli.id_solicitud
                   			into
                            v_fecha_solicitud_recu,
                            v_id_solicitud_reporte
                     from mat.tsolicitud soli
                     where soli.id_proceso_wf = v_parametros.id_proceso_wf;

                   if (v_fecha_solicitud_recu >= v_fecha_salida_gm or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) then


                   /*Aqui aumentando para recueprar el tiempo de entrega (Ismael Valdivia 09/11/2020)*/
                     select COALESCE(soli.tiempo_entrega,0),
                     		soli.metodo_de_adjudicación,
                            soli.tipo_de_adjudicacion
                     	    into
                            v_tiempo_entrega,
                            v_metodo_adju,
                            v_tipo_adju
                     from mat.tsolicitud soli
                     where soli.id_proceso_wf = v_parametros.id_proceso_wf;
                   /**********************************************************************************/

		           v_consulta:='select
                   				MAX((select pxp.list(po.email::text)
                                from param.vproveedor po
                                join mat.tgestion_proveedores_new pr on pr.id_proveedor = po.id_proveedor
                                where pr.id_solicitud = sol.id_solicitud))::varchar as lista_correos,
                                MAX(sol.mensaje_correo)::varchar,
                                MAX(ala.fecha_reg)::timestamp,
                                MAX(array_to_string(pcorreo.correos, '',''))::varchar as correos,
                                MAX(ala.titulo_correo)::varchar,
                                /*Auementando para recuperar el tiempo de entrega en el reporte invitacion(Ismael Valdivia 9/11/2020)*/
								'||v_tiempo_entrega||'::numeric as tiempo_entrega,
                                '''||v_metodo_adju||'''::varchar as metodo_de_adjudicación,
                                '''||v_tipo_adju||'''::varchar as tipo_de_adjudicacion,
                                '''||v_fecha_solicitud_recu||'''::date as fecha_solicitud,
                                '''||v_fecha_salida_gm||'''::date as fecha_salida
                                /*****************************************************************************************************/
                                from  mat.tsolicitud sol
                                left join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
                                left join segu.vusuario u on u.id_usuario = sol.id_usuario_mod
                                inner join param.talarma ala on ala.id_proceso_wf = sol.id_proceso_wf
                                inner join wf.tplantilla_correo pcorreo on pcorreo.id_plantilla_correo = ala.id_plantilla_correo
                                where sol.id_proceso_wf = '||v_parametros.id_proceso_wf;
            else
            	v_consulta:='select
                   				MAX((select pxp.list(po.email::text)
                                from param.vproveedor po
                                join mat.tgestion_proveedores_new pr on pr.id_proveedor = po.id_proveedor
                                where pr.id_solicitud = sol.id_solicitud))::varchar as lista_correos,

                                MAX(sol.mensaje_correo)::varchar,
                                MAX(ala.fecha_reg)::timestamp,
                                MAX(array_to_string(pcorreo.correos, '',''))::varchar as correos,
                                MAX(ala.titulo_correo)::varchar,

                                ''0''::numeric as tiempo_entrega,
                                ''''::varchar as metodo_de_adjudicación,
                                ''''::varchar as tipo_de_adjudicacion,
                                '''||v_fecha_solicitud_recu||'''::date as fecha_solicitud,
                                '''||v_fecha_salida_gm||'''::date as fecha_salida

                                from  mat.tsolicitud sol
                                left join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
                                left join segu.vusuario u on u.id_usuario = sol.id_usuario_mod
                                inner join param.talarma ala on ala.id_proceso_wf = sol.id_proceso_wf
                                inner join wf.tplantilla_correo pcorreo on pcorreo.id_plantilla_correo = ala.id_plantilla_correo

                                where sol.id_proceso_wf = '||v_parametros.id_proceso_wf;
        	end if;


            raise notice 'consulta %',v_consulta;


			return v_consulta;

		end;

        /*********************************
        #TRANSACCION:  'MAT_CONENVREP_REP'
        #DESCRIPCION:	Invitacion para BoA Rep
        #AUTOR:		Ismael Valdivia
        #FECHA:		05-05-2020 16:15:00
        ***********************************/

        elsif(p_transaccion='MAT_CONENVREP_REP')then

            begin
            		 v_consulta:='select
                                    MAX((select pxp.list(po.email::text)
                                    from param.vproveedor po
                                    join mat.tgestion_proveedores_new pr on pr.id_proveedor = po.id_proveedor
                                    where pr.id_solicitud = sol.id_solicitud))::varchar as lista_correos,
                                    MAX(sol.mensaje_correo)::varchar,
                                    MAX(ala.fecha_reg)::timestamp,
                                    MAX(array_to_string(pcorreo.correos, '',''))::varchar as correos,
                                    MAX(ala.titulo_correo)::varchar,
                                    MAX(mat.f_get_detalle_html(sol.id_solicitud)::text)::varchar as detalle,
                                    (select pxp.list(po.rotulo_comercial::text)
                                    from param.vproveedor po
                                    join mat.tgestion_proveedores_new pr on pr.id_proveedor = po.id_proveedor
                                    where pr.id_solicitud = (select sol.id_solicitud
                                                            from  mat.tsolicitud sol
                                                            where sol.id_proceso_wf = '||v_parametros.id_proceso_wf||'))::varchar as proveedores


                                    from  mat.tsolicitud sol
                                    left join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
                                    left join segu.vusuario u on u.id_usuario = sol.id_usuario_mod
                                    inner join param.talarma ala on ala.id_proceso_wf = sol.id_proceso_wf
                                    inner join wf.tplantilla_correo pcorreo on pcorreo.id_plantilla_correo = ala.id_plantilla_correo
                                    where sol.id_proceso_wf = '||v_parametros.id_proceso_wf;


                raise notice 'consulta %',v_consulta;


                return v_consulta;

            end;


	/*********************************
 	#TRANSACCION:  'MAT_REPCERPRE_SEL'
 	#DESCRIPCION:	Reporte Certificación Presupuestaria
 	#AUTOR:		franklin.espinoza
 	#FECHA:		03-04-2020 11:00
	***********************************/

	elsif(p_transaccion='MAT_REPCERPRE_SEL')then

		begin

            SELECT ts.estado, ts.id_estado_wf, ts.justificacion, ts.id_gestion,ts.motivo_solicitud, ts.origen_pedido, ts.fecha_solicitud
            INTO v_record_sol
            FROM mat.tsolicitud ts
            WHERE ts.id_proceso_wf = v_parametros.id_proceso_wf;


            v_poa_aprobado = pxp.f_get_variable_global('poa_funcionario_aprobado_por')::integer;
            v_poa_elaborado = pxp.f_get_variable_global('poa_funcionario_elaborado_por')::integer;
            v_vobo_poa = pxp.f_get_variable_global('poa_vobo')::integer;
            v_index = 1;
            FOR v_record_funcionario IN ( (

                                          /*select fun.id_funcionario,
            								     fun.nombre_cargo,
                                                 fun.desc_funcionario1,
                                                 fun.oficina_nombre
                                          from orga.vfuncionario_ultimo_cargo fun
                                          where fun.id_funcionario =v_poa_aprobado*/

                                          select fun.id_funcionario,
                                                 fun.nombre_cargo,
                                                 fun.desc_funcionario1,
                                                 ofi.nombre as oficina_nombre
                                          from orga.vfuncionario_cargo fun
                                          inner join orga.tcargo car on car.id_cargo = fun.id_cargo
                                          inner join orga.toficina ofi on ofi.id_oficina = car.id_oficina
                                          where fun.id_funcionario = v_poa_aprobado
                                          and v_record_sol.fecha_solicitud between fun.fecha_asignacion and COALESCE(fun.fecha_finalizacion,now()::date)

                                          UNION

                                          /*select fun.id_funcionario,
            								     fun.nombre_cargo,
                                                 fun.desc_funcionario1,
                                                 fun.oficina_nombre
                                          from orga.vfuncionario_ultimo_cargo fun
                                          where fun.id_funcionario = v_poa_elaborado*/


                                          select fun.id_funcionario,
                                                 fun.nombre_cargo,
                                                 fun.desc_funcionario1,
                                                 ofi.nombre as oficina_nombre
                                          from orga.vfuncionario_cargo fun
                                          inner join orga.tcargo car on car.id_cargo = fun.id_cargo
                                          inner join orga.toficina ofi on ofi.id_oficina = car.id_oficina
                                          where fun.id_funcionario = v_poa_elaborado
                                          and v_record_sol.fecha_solicitud between fun.fecha_asignacion and COALESCE(fun.fecha_finalizacion,now()::date)

                                          UNION

                                          /*select fun.id_funcionario,
            								     fun.nombre_cargo,
                                                 fun.desc_funcionario1,
                                                 fun.oficina_nombre
                                          from orga.vfuncionario_ultimo_cargo fun
                                          where fun.id_funcionario = v_vobo_poa*/

                                          select fun.id_funcionario,
                                                 fun.nombre_cargo,
                                                 fun.desc_funcionario1,
                                                 ofi.nombre as oficina_nombre
                                          from orga.vfuncionario_cargo fun
                                          inner join orga.tcargo car on car.id_cargo = fun.id_cargo
                                          inner join orga.toficina ofi on ofi.id_oficina = car.id_oficina
                                          where fun.id_funcionario = v_vobo_poa
                                          and v_record_sol.fecha_solicitud between fun.fecha_asignacion and COALESCE(fun.fecha_finalizacion,now()::date)

                                          )
                                          ORDER BY id_funcionario ASC )
        	LOOP
                v_firmas[v_index] = v_record_funcionario.desc_funcionario1::VARCHAR||','||v_record_funcionario.nombre_cargo::VARCHAR||','||v_record_funcionario.oficina_nombre;
            	v_index = v_index + 1;
            END LOOP;


            v_firma_fun = array_to_string(v_firmas,';');


            SELECT (''||te.codigo||' '||te.nombre)::varchar
            INTO v_nombre_entidad
            FROM param.tempresa te;

            SELECT (''||tda.codigo||' '||tda.nombre)::varchar
            INTO v_direccion_admin
            FROM pre.tdireccion_administrativa tda;


             /*Aumentando para recuperar la fecha de la cotizacion*/
            select cot.fecha_cotizacion
            into
            v_cotizacion_fecha
            from mat.tsolicitud sol
            inner join mat.tcotizacion cot on cot.id_solicitud = sol.id_solicitud and cot.adjudicado = 'si'
            where sol.id_proceso_wf = v_parametros.id_proceso_wf;

            if (v_cotizacion_fecha is null) then
            	v_cotizacion_fecha = '';
            end if;

            /*Cambiando para recuperar la fecha del proceso wf*/

            select
        			to_char(sou.fecha_po,'DD/MM/YYYY')as fechapo,
                    to_char(sou.fecha_solicitud,'DD/MM/YYYY')as fechasol,
                    sou.id_proceso_wf_firma,
                    sou.estado_firma,
                    sou.estado,
                    sou.nro_tramite
                    into
                    v_fecha_po,
                    v_fecha_solicitud,
                    v_id_proceso_wf_firma,
                    v_estado_firma_paralelo,
                    v_estado_actual,
                    v_nro_tramite
            from mat.tsolicitud sou
            where sou.id_proceso_wf = v_parametros.id_proceso_wf;



            /*Aqui recuperamos la Firma del encargado del comite de aeronavegabilidad*/
             if (v_estado_firma_paralelo = 'autorizado') then

                      SELECT twf.fecha_reg into v_fecha_firma_dc_qr
                      FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND
                            te.codigo = 'autorizado'
                      order by twf.fecha_reg desc
                      limit 1;
              /*************************************************************************/
             end if;




            /*Aqui recuperamos la Firma del encargado del comite de Unidad de abastecimiento*/
            if (v_estado_actual != 'comite_unidad_abastecimientos') then

                  SELECT twf.fecha_reg into v_fecha_firma_rev_qr
                  FROM wf.testado_wf twf
                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND
                        te.codigo = 'autorizado'
                  order by twf.fecha_reg desc
                  limit 1; --comite abastecimiento
            end if;
            /*********************************************************************************************************************************/



            /*****************************************************/
            v_cotizacion_fecha = '';
            if (v_fecha_firma_dc_qr is not null and v_fecha_firma_rev_qr is not null) THEN
                if (v_fecha_firma_dc_qr::date > v_fecha_firma_rev_qr::date) then
                    v_cotizacion_fecha = v_fecha_firma_dc_qr::varchar;
                elsif (v_fecha_firma_rev_qr::date > v_fecha_firma_dc_qr::date) then
                    v_cotizacion_fecha = v_fecha_firma_abas_qr::varchar;
                elsif (v_fecha_firma_rev_qr::date = v_fecha_firma_dc_qr::date) then
                    v_cotizacion_fecha = v_fecha_firma_rev_qr::varchar;
                end if;
            end if;


            /*****************************************************/

            if (v_record_sol.origen_pedido = 'Reparación de Repuestos') then



			v_consulta:='
            SELECT
            vcp.id_categoria_programatica AS id_cp,
            ttc.codigo AS centro_costo,
            vcp.codigo_programa ,
            vcp.codigo_proyecto,
            vcp.codigo_actividad,
            vcp.codigo_fuente_fin,
            vcp.codigo_origen_fin,
            tpar.codigo AS codigo_partida,
            tpar.nombre_partida ,
            tcg.codigo AS codigo_cg,
            tcg.nombre AS nombre_cg,
            sum(tsd.precio_total) AS precio_total,

            ts.nro_tramite,
            COALESCE('''||v_nombre_entidad||'''::varchar, '''') AS nombre_entidad,
            COALESCE('''||v_direccion_admin||'''::varchar, '''') AS direccion_admin,
            coalesce(vcp.desc_unidad_ejecutora::varchar,''Boliviana de Aviación - BoA''::varchar) as unidad_ejecutora,
            coalesce(vcp.codigo_unidad_ejecutora::varchar,''0''::varchar) as codigo_ue,
            COALESCE('''||v_firma_fun||'''::varchar, '''') AS firmas,
            COALESCE('''||v_record_sol.motivo_solicitud||'''::varchar,'''') AS justificacion,
            COALESCE(tet.codigo::varchar,''00''::varchar) AS codigo_transf,
            (uo.codigo||''-''||uo.nombre_unidad)::varchar as unidad_solicitante,
            fun.desc_funcionario1::varchar as funcionario_solicitante,
            ts.fecha_solicitud AS fecha_soli,
            COALESCE(tg.gestion, (extract(year from current_date))::integer) AS gestion,
            coalesce(ts.codigo_poa,''''::varchar) as codigo_poa,

            ob.descripcion as codigo_descripcion,
            ''''::varchar as tipo,
            '''||v_cotizacion_fecha||'''::varchar as fecha_cotizacion
            FROM mat.tsolicitud ts
            INNER JOIN mat.tdetalle_sol tsd ON tsd.id_solicitud = ts.id_solicitud and tsd.estado_excluido = ''no''
            INNER JOIN pre.tpartida tpar ON tpar.id_partida = tsd.id_partida
            /*Aumentando para recueperar la descripcion del codigo_poa*/
            INNER JOIN pre.tobjetivo ob on ob.codigo = ts.codigo_poa
            /**********************************************************/
            inner join param.tgestion tg on tg.id_gestion = ts.id_gestion
            INNER JOIN param.tcentro_costo tcc ON tcc.id_centro_costo = tsd.id_centro_costo
            INNER JOIN param.ttipo_cc ttc ON ttc.id_tipo_cc = tcc.id_tipo_cc
            INNER JOIN pre.tpresupuesto	tp ON tp.id_presupuesto = tsd.id_centro_costo
            INNER JOIN pre.vcategoria_programatica vcp ON vcp.id_categoria_programatica = tp.id_categoria_prog
            INNER JOIN pre.tclase_gasto_partida tcgp ON tcgp.id_partida = tpar.id_partida
            INNER JOIN pre.tclase_gasto tcg ON tcg.id_clase_gasto = tcgp.id_clase_gasto

            inner join orga.vfuncionario fun on fun.id_funcionario = ts.id_funcionario_solicitante
            inner join orga.tuo uo on uo.id_uo = 9421
            left JOIN pre.tpresupuesto_partida_entidad tppe ON tppe.id_partida = tpar.id_partida AND tppe.id_presupuesto = tp.id_presupuesto
            left JOIN pre.tentidad_transferencia tet ON tet.id_entidad_transferencia = tppe.id_entidad_transferencia
            WHERE ob.id_gestion = '||v_record_sol.id_gestion||' and tsd.estado_reg = ''activo'' AND ts.id_proceso_wf = '||v_parametros.id_proceso_wf;

            else

            v_consulta:='
            SELECT
            vcp.id_categoria_programatica AS id_cp,
            ttc.codigo AS centro_costo,
            vcp.codigo_programa ,
            vcp.codigo_proyecto,
            vcp.codigo_actividad,
            vcp.codigo_fuente_fin,
            vcp.codigo_origen_fin,
            tpar.codigo AS codigo_partida,
            tpar.nombre_partida ,
            tcg.codigo AS codigo_cg,
            tcg.nombre AS nombre_cg,

            sum(tsd.precio_total) AS precio_total,

            ts.nro_tramite,
            COALESCE('''||v_nombre_entidad||'''::varchar, '''') AS nombre_entidad,
            COALESCE('''||v_direccion_admin||'''::varchar, '''') AS direccion_admin,
            coalesce(vcp.desc_unidad_ejecutora::varchar,''Boliviana de Aviación - BoA''::varchar) as unidad_ejecutora,
            coalesce(vcp.codigo_unidad_ejecutora::varchar,''0''::varchar) as codigo_ue,
            COALESCE('''||v_firma_fun||'''::varchar, '''') AS firmas,
            COALESCE('''||v_record_sol.motivo_solicitud||'''::varchar,'''') AS justificacion,
            COALESCE(tet.codigo::varchar,''00''::varchar) AS codigo_transf,
            (uo.codigo||''-''||uo.nombre_unidad)::varchar as unidad_solicitante,
            fun.desc_funcionario1::varchar as funcionario_solicitante,
            ts.fecha_solicitud AS fecha_soli,
            COALESCE(tg.gestion, (extract(year from current_date))::integer) AS gestion,
            coalesce(ts.codigo_poa,''''::varchar) as codigo_poa,

            ob.descripcion as codigo_descripcion,
            ''''::varchar as tipo,
            ts.fecha_solicitud::varchar as fecha_cotizacion
            FROM mat.tsolicitud ts
            INNER JOIN mat.tdetalle_sol tsd ON tsd.id_solicitud = ts.id_solicitud and tsd.estado_excluido = ''no''
            INNER JOIN pre.tpartida tpar ON tpar.id_partida = tsd.id_partida
            /*Aumentando para recueperar la descripcion del codigo_poa*/
            INNER JOIN pre.tobjetivo ob on ob.codigo = ts.codigo_poa
            /**********************************************************/
            inner join param.tgestion tg on tg.id_gestion = ts.id_gestion
            INNER JOIN param.tcentro_costo tcc ON tcc.id_centro_costo = tsd.id_centro_costo
            INNER JOIN param.ttipo_cc ttc ON ttc.id_tipo_cc = tcc.id_tipo_cc
            INNER JOIN pre.tpresupuesto	tp ON tp.id_presupuesto = tsd.id_centro_costo
            INNER JOIN pre.vcategoria_programatica vcp ON vcp.id_categoria_programatica = tp.id_categoria_prog
            INNER JOIN pre.tclase_gasto_partida tcgp ON tcgp.id_partida = tpar.id_partida
            INNER JOIN pre.tclase_gasto tcg ON tcg.id_clase_gasto = tcgp.id_clase_gasto

            inner join orga.vfuncionario fun on fun.id_funcionario = ts.id_funcionario_solicitante
            inner join orga.tuo uo on uo.id_uo = 9421
            left JOIN pre.tpresupuesto_partida_entidad tppe ON tppe.id_partida = tpar.id_partida AND tppe.id_presupuesto = tp.id_presupuesto
            left JOIN pre.tentidad_transferencia tet ON tet.id_entidad_transferencia = tppe.id_entidad_transferencia

            /*Aumentando para el HAZMAT*/
            --inner join mat.tcotizacion_detalle det on det.id_detalle = tsd.id_detalle and det.referencial = ''Si''
			--left join mat.tcotizacion_detalle detHazmat on detHazmat.id_detalle_hazmat = det.id_cotizacion_det

            /***************************/


            WHERE ob.id_gestion = '||v_record_sol.id_gestion||' and tsd.estado_reg = ''activo'' AND ts.id_proceso_wf = '||v_parametros.id_proceso_wf;

            end if;

			v_consulta =  v_consulta || ' GROUP BY vcp.id_categoria_programatica, tpar.codigo, ttc.codigo,vcp.codigo_programa,vcp.codigo_proyecto, vcp.codigo_actividad,
            vcp.codigo_fuente_fin, vcp.codigo_origen_fin, tpar.nombre_partida, tcg.codigo, tcg.nombre, ts.nro_tramite, tet.codigo, unidad_solicitante, funcionario_solicitante,
            ts.fecha_solicitud, tg.gestion, ts.codigo_poa, ts.tipo, ts.id_solicitud,
            vcp.desc_unidad_ejecutora, vcp.codigo_unidad_ejecutora,ob.descripcion';
			v_consulta =  v_consulta || ' ORDER BY tpar.codigo, tcg.nombre, vcp.id_categoria_programatica, ttc.codigo asc ';

            RAISE NOTICE 'v_consulta %',v_consulta;
			return v_consulta;

        end;

    /*********************************
 	#TRANSACCION:  'MAT_REPORDER_REP_SEL'
 	#DESCRIPCION:	Reporte Ordebn de Reparacion Exterior
 	#AUTOR:	 Ismael Valdivia
 	#FECHA:		25/03/2020
	***********************************/
    elsif(p_transaccion='MAT_REPORDER_REP_SEL')then
		begin

            /*Aqui recuperamos el id funcionario solicitante*/
            select sol.id_solicitud,
                   sol.id_funcionario_solicitante,
            	   sol.nro_tramite,
                   sol.condicion,--sol.tipo_evaluacion,
                   sol.nro_po,
                   COALESCE (to_char(sol.fecha_po,'DD/MM/YYYY')::Varchar,''),
                   sol.tipo_solicitud,
                   sol.observaciones_sol,
                   pro.rotulo_comercial,
                   COALESCE (inst.direccion,''),
                   (COALESCE (inst.email1,'') ||' '|| COALESCE(inst.email2,''))::varchar as email,
                   (Coalesce(inst.telefono1::varchar,'') ||' '|| Coalesce (inst.telefono2::varchar,''))::varchar as telefono,
                   (COALESCE (inst.fax,''))::varchar as fax,
                   --pro.lugar::varchar as estado,
                   --pro.pais::varchar as country,
                   state.nombre::varchar as estado,
                   country.nombre as region,

                   COALESCE(procontac.nombre_contacto,'')::varchar,
                   pro.nit,
                   sol.fecha_solicitud,
                   sol.estado,
                   (EXTRACT(DAY FROM sol.fecha_entrega)||' de '|| pxp.f_obtener_literal_periodo((EXTRACT(MONTH FROM sol.fecha_entrega))::integer,0) ||' de '||EXTRACT(YEAR FROM sol.fecha_entrega))::varchar,
                   upper(sol.codigo_forma_pago_alkym),
                   upper(sol.codigo_condicion_entrega_alkym),
                   sol.tipo_evaluacion
                   into
                   v_id_solicitud_rec,
                   v_id_funcionario_solicitante,
                   v_num_tramite_rep,
                   v_tipo_evaluacion,
                   v_rep,
                   v_fecha_order,
                   v_prioridad,
                   v_observaciones_sol,
                   v_nom_provee,
                   v_direccion_provee,
                   v_email_provee,
                   v_telf_provee,
                   v_fax_provee,
                   v_estado_provee,
                   v_country_provee,
                   v_contacto_proveedor,
                   v_nit_proveedor,
                   v_fecha_solicitud,
                   v_estado_actual,
                   v_fecha_entrega,
                   v_payment_terms,
                   v_incoterms,
                   v_tipo_evaluacion
            from mat.tsolicitud sol
            left join param.vproveedor2 pro on pro.id_proveedor = sol.id_proveedor
            left join param.tproveedor_contacto procontac on procontac.id_proveedor = pro.id_proveedor
            left join param.tinstitucion inst on inst.id_institucion = pro.id_institucion
            left join param.tproveedor proof on proof.id_proveedor = pro.id_proveedor
            left join param.tlugar country on country.id_lugar = proof.id_lugar
            left join param.tlugar state on state.id_lugar = proof.id_lugar_ciudad
            where sol.id_proceso_wf = v_parametros.id_proceso_wf;
            /************************************************/

            if (v_nom_provee is null)THEN
            	v_nom_provee = '';
            end if;

            if (v_direccion_provee is null)THEN
            	v_direccion_provee = '';
            end if;

            if (v_email_provee is null)THEN
            	v_email_provee = '';
            end if;

            if (v_nit_proveedor is null)THEN
            	v_nit_proveedor = '';
            end if;

            if (v_telf_provee is null)THEN
            	v_telf_provee = '';
            end if;

            if (v_fax_provee is null)THEN
            	v_fax_provee = '';
            end if;

            if (v_estado_provee is null)THEN
            	v_estado_provee = '';
            end if;

            if (v_country_provee is null)THEN
            	v_country_provee = '';
            end if;

            if (v_contacto_proveedor is null)THEN
            	v_contacto_proveedor = '';
            end if;

            if (v_nit_proveedor is null)THEN
            	v_nit_proveedor = '';
            end if;


            /*Aqui Recuperamos el lugar y el correo del funcionario*/
            select funci.email_empresa,
                   of.telefono,
                   of.direccion
                   into
                   v_email_funcionario,
                   v_telefono_funcionario,
                   v_direccion_funcio
            from orga.vfuncionario_cargo funci
            inner join orga.tcargo car on car.id_cargo = funci.id_cargo
            inner join orga.toficina of on of.id_oficina = car.id_oficina
            where funci.id_funcionario = v_id_funcionario_solicitante
            and v_fecha_solicitud::date between funci.fecha_asignacion and COALESCE(funci.fecha_finalizacion,now())
            order by funci.fecha_asignacion DESC
            limit 1;
            /*******************************************************/

            /*Aqui recuperamos datos del funcionario*/
            select cel.numero into v_numero_interno
            from gecom.tfuncionario_celular fun
            inner join gecom.tnumero_celular cel on cel.id_numero_celular = fun.id_numero_celular
            where fun.id_funcionario = v_id_funcionario_solicitante and fun.estado_reg = 'activo' and cel.tipo = 'interno';


            select
                  count(detcot.id_cotizacion_det)  into v_existe_bear
            from mat.tcotizacion cot
            inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion
            left join mat.tcotizacion_detalle det on det.id_detalle_hazmat = detcot.id_cotizacion_det
            where cot.id_solicitud = v_id_solicitud_rec and cot.adjudicado = 'si'
            and detcot.cd = 'B.E.R.';

            /*Recuperamos datos del detalle de solicitud Cotizacion*/

            /*Aumentando para poner condicion de flat exchange (Ismael Valdivia 08/02/2022)*/
            if ((v_tipo_evaluacion = 'Flat Exchange' OR v_tipo_evaluacion = 'Exchange') and (v_existe_bear = 0)) then
            	select list(detcot.nro_parte_cot),
                      list(detcot.nro_parte_alterno_cot),
                      list(detcot.cantidad_det::varchar),
                      array_to_string(pxp.aggarray(detcot.descripcion_cot),'|')::varchar,--list(detcot.descripcion_cot),
                      list(detcot.referencia_cot),
                      list(detcot.cd),
                      list(COALESCE (detcot.precio_unitario,0)::varchar),
                      list(COALESCE (detcot.precio_unitario_mb,0)::varchar),
                      list(COALESCE(detcot.id_detalle,0)::varchar),
                      list(COALESCE( NULLIF(detsol.referencia,'') ,'/'))
                       INTO
                       v_num_part,
                       v_num_part_alt,
                       v_cantidad,
                       v_descripcion,
                       v_serial,
                       v_cd,
                       v_precio_unitario,
                       v_precio_total,
                       v_id_detalle,
                       v_serial_original
                from mat.tcotizacion cot
                inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion
                left join mat.tdetalle_sol detsol on detsol.id_detalle = detcot.id_detalle
                where cot.id_solicitud = v_id_solicitud_rec and cot.adjudicado = 'si';
            else
            	select list(detcot.nro_parte_cot),
                      list(detcot.nro_parte_alterno_cot),
                      list(detcot.cantidad_det::varchar),
                      array_to_string(pxp.aggarray(detcot.descripcion_cot),'|')::varchar,--list(detcot.descripcion_cot),
                      list(detcot.referencia_cot),
                      list(detcot.cd),
                      list(COALESCE (detcot.precio_unitario,0)::varchar),
                      list(COALESCE (detcot.precio_unitario_mb,0)::varchar),
                      list(COALESCE(detcot.id_detalle,0)::varchar),
                      list(COALESCE( NULLIF(det.referencia_cot,'') ,'/'))
                       INTO
                       v_num_part,
                       v_num_part_alt,
                       v_cantidad,
                       v_descripcion,
                       v_serial,
                       v_cd,
                       v_precio_unitario,
                       v_precio_total,
                       v_id_detalle,
                       v_serial_original
                from mat.tcotizacion cot
                inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion
                left join mat.tcotizacion_detalle det on det.id_detalle_hazmat = detcot.id_cotizacion_det
                where cot.id_solicitud = v_id_solicitud_rec and cot.adjudicado = 'si';
            end if;
            /*******************************************************************************/




            if (v_num_part is null) then
            	v_num_part = '';
            end if;

            if (v_num_part_alt is null) then
            	v_num_part_alt = '';
            end if;

            if (v_cantidad is null) then
            	v_cantidad = '0';
            end if;

            if (v_descripcion is null) then
            	v_descripcion = '';
            end if;

            if (v_serial is null) then
            	v_serial = '';
            end if;

            if (v_cd is null) then
            	v_cd = '';
            end if;

            if (v_precio_unitario is null) then
            	v_precio_unitario = '0';
            end if;

            if (v_precio_total is null) then
            	v_precio_total = '0';
            end if;


            if (v_serial_original is null) then
            	v_serial_original = '';
            end if;


            select sum (detcot.precio_unitario_mb)


                   into
                   v_suma_totales


            from mat.tcotizacion cot
            inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion
            where cot.id_solicitud = v_id_solicitud_rec and cot.adjudicado = 'si';

            if (v_suma_totales is null) then
            	v_suma_totales = '0';
            end if;

            if (v_payment_terms is null) then
            	v_payment_terms = '';
            end if;

            if (v_incoterms is null) then
            	v_incoterms = '';
            end if;




            if (v_fecha_entrega is null) then
            	v_fecha_entrega = '';
            end if;




            if (v_estado_actual != 'vb_rpcd') then
        	SELECT  twf.id_funcionario,
                            twf.fecha_reg
                          into  v_rpcd
                    FROM wf.testado_wf twf
                    INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                    INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                    WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                    AND te.codigo = 'vb_rpcd'
                    GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;

            if (v_fecha_order != '') then

            	  if (v_rpcd.fecha_reg is not null)then
                    if(v_fecha_solicitud::date >= v_rango_fecha::date)THEN
                          SELECT  twf.id_funcionario,
                                  vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                  vf.desc_funcionario1,
                                  to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                            INTO  v_id_funcionario_rpcd_oficial,
                                  v_funcionario_sol_rpcd_oficial,
                                  v_funcionario_rpcd_oficial,
                                  v_fecha_firma_rpcd_pru
                          FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                          AND te.codigo = 'vb_rpcd'
                          and v_fecha_order::date between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
                          GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                          order by twf.fecha_reg DESC
                          limit 1;

                          /*Aumentando para el iterinato 25/01/2022*/
                          if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                              remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_rpcd_oficial,v_fecha_order::varchar);

                              if(remplaso is null)THEN
                                    v_funcionario_sol_rpcd_oficial = v_funcionario_sol_rpcd_oficial;
                              else
                              		v_funcionario_sol_rpcd_oficial = remplaso.desc_funcionario1;
                              end if;
                          else
                              v_funcionario_sol_rpcd_oficial = v_funcionario_sol_rpcd_oficial;
                          end if;
                          /*****************************************/
                     end if;
        		else
                	v_funcionario_sol_rpcd_oficial = '';
                end if;
            end if;
        end if;

            if (v_funcionario_sol_rpcd_oficial is null) then
            	v_funcionario_sol_rpcd_oficial = '';
            end if;

          v_corregir_reporte = 'no';

          if (v_fecha_order != '') then

          	if (v_fecha_order::date >= '01/04/2022'::date) then
            	v_corregir_reporte = 'si';
            else
            	v_corregir_reporte = 'no';
            end if;


          end if;


          v_consulta:='select
           			  ('''||v_num_tramite_rep||''')::varchar as num_tramite,
                      ('''||v_email_funcionario||''')::varchar as email_fun,
                      ('''||v_tipo_evaluacion||''')::varchar as po_type,
                      ('''||v_rep||''')::varchar as rep,
                      ('''||v_fecha_order||''')::varchar as fecha_order,
                      ('''||upper(v_prioridad)||''')::varchar as priority,
                      ('''||v_nom_provee||''')::varchar as nom_provee,
                      ('''||v_nit_proveedor||''')::varchar as dni,
                      ('''||v_contacto_proveedor||''')::varchar as contacto_proveedor,
                      ('''||v_direccion_provee||''')::varchar as direcc_provee,
                      ('''||v_email_provee||''')::varchar as email_provee,
                      ('''||v_telf_provee||''')::varchar as telf_provee,
                      ('''||v_fax_provee||''')::varchar as fax_provee,
                      ('''||v_estado_provee||''')::varchar as estado_provee,
                      ('''||v_country_provee||''')::varchar as country_provee,
                      ('''||v_num_part||''')::varchar as num_part,
                      ('''||v_num_part_alt||''')::varchar as num_part_alt,
                      ('''||v_cantidad||''')::varchar as cantidad,
                      ('''||v_descripcion||''')::varchar as descripcion,
                      ('''||v_serial||''')::varchar as serial,
                      ('''||v_cd||''')::varchar as cd,
                      ('''||COALESCE(v_precio_unitario,'0')||''')::varchar as precio_unitario,
                      ('''||COALESCE(v_precio_total,'0')||''')::varchar as precio_total,
                      ('||COALESCE(v_suma_totales,0)||')::numeric as suma_total,
                      ('''||v_payment_terms||''')::varchar as payment_terms,
                      ('''||v_incoterms||''')::varchar as incoterms,
                      ('''||COALESCE(v_fecha_entrega,'')||''')::varchar as delivery_date,
                      ('''||v_observaciones_sol||''')::varchar as observaciones_sol,
                      ('''||v_funcionario_sol_rpcd_oficial||''')::varchar as firma_rpc,
                      ('''||v_serial_original||''')::varchar as serial_original,
                      ('''||v_id_detalle||''')::varchar as detalle_sol,
                      ('''||v_corregir_reporte||''')::varchar as corregir_reporte';

            raise notice 'v_consulta %',v_consulta;
			return v_consulta;
	end;

    /*********************************
 	#TRANSACCION:  'MAT_REP_JUSTREP_SEL'
 	#DESCRIPCION:	Reporte Informde de Justicacion y Recomendacion
 	#AUTOR:	 Ismael Valdivia
 	#FECHA:		26/03/2020
	***********************************/
    elsif(p_transaccion='MAT_REP_JUSTREP_SEL')then
		begin

            /*Aqui recuperamos el id funcionario solicitante*/
            select sol.id_solicitud,
                   sol.id_funcionario_solicitante,
                   sol.id_funcionario_sol,
            	   sol.nro_tramite,
                   COALESCE (to_char(sol.fecha_solicitud,'DD/MM/YYYY')::Varchar,''),
                   COALESCE (to_char(sol.fecha_po,'DD/MM/YYYY')::Varchar,''),
                   pro.rotulo_comercial,
                   sol.nro_po,
                   ge.gestion,
                   sol.tipo_evaluacion,
                   pro.tipo,
                   sol.nro_lote,
                   sol.estado
                   into
                   v_id_solicitud_rec,
                   v_id_funcionario_solicitante,
                   v_id_fun_pre,
                   v_num_tramite_rep,
                   v_fecha_solicitud,
                   v_fecha_order,
                   v_nom_provee,
                   v_rep,
                   v_gestion,
                   v_evaluacion,
                   v_tipo_taller,
                   v_nro_lote,
                   v_estado_actual
            from mat.tsolicitud sol
            left join param.vproveedor2 pro on pro.id_proveedor = sol.id_proveedor
            inner join param.tgestion ge on ge.id_gestion = sol.id_gestion
            where sol.id_proceso_wf = v_parametros.id_proceso_wf;
            /************************************************/
             if (v_fecha_order is null) then
            	v_fecha_order = '';
            end if;

             if (v_nro_lote is null) then
            	v_nro_lote = '';
            end if;

            if (v_tipo_taller is null) then
            	v_tipo_taller = '';
            end if;

            if (v_evaluacion is null) then
            	v_evaluacion = '';
            end if;

             if (v_gestion is null) then
            	v_gestion = '';
            end if;

             if (v_nom_provee is null) then
            	v_nom_provee = 'Ningun Taller Adjudicado';
            end if;

             if (v_rep is null) then
            	v_rep = '';
            end if;

            /*Recuperamos datos del detalle de solicitud Cotizacion*/
            select list (detcot.nro_parte_cot),
                   list (detcot.nro_parte_alterno_cot),
                   list (detcot.cantidad_det::varchar),
                   array_to_string(pxp.aggarray(detcot.descripcion_cot),'|')::varchar,--list (detcot.descripcion_cot),
                   list (detcot.referencia_cot),
                   list (detcot.cd),
                   list (COALESCE (detcot.precio_unitario,0)::varchar),
                   list (COALESCE (detcot.precio_unitario_mb,0)::varchar),
                   to_char(cot.fecha_cotizacion,'DD/MM/YYYY')::varchar
                   INTO
                   v_num_part,
                   v_num_part_alt,
                   v_cantidad,
                   v_descripcion,
                   v_serial,
                   v_cd,
                   v_precio_unitario,
                   v_precio_total,
                   v_fecha_cotizacion_oficial
            from mat.tcotizacion cot
            inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion
            where cot.id_solicitud = v_id_solicitud_rec and cot.adjudicado = 'si'
			group by cot.fecha_cotizacion;

             if (v_num_part is null) then
            	v_num_part = '';
            end if;

            if (v_num_part_alt is null) then
            	v_num_part_alt = '';
            end if;

            if (v_cantidad is null) then
            	v_cantidad = '0';
            end if;

            if (v_descripcion is null) then
            	v_descripcion = '';
            end if;

            if (v_serial is null) then
            	v_serial = '';
            end if;

            if (v_cd is null) then
            	v_cd = '';
            end if;

            if (v_precio_unitario is null) then
            	v_precio_unitario = '0';
            end if;

            if (v_precio_total is null) then
            	v_precio_total = '0';
            end if;

            /*Cambiando para recuperar la fecha del proceso wf*/

            select
        			to_char(sou.fecha_po,'DD/MM/YYYY')as fechapo,
                    to_char(sou.fecha_solicitud,'DD/MM/YYYY')as fechasol,
                    sou.id_proceso_wf_firma,
                    sou.estado_firma,
                    sou.estado,
                    sou.nro_tramite
                    into
                    v_fecha_po,
                    v_fecha_solicitud,
                    v_id_proceso_wf_firma,
                    v_estado_firma_paralelo,
                    v_estado_actual,
                    v_nro_tramite
            from mat.tsolicitud sou
            where sou.id_proceso_wf = v_parametros.id_proceso_wf;



            /*Aqui recuperamos la Firma del encargado del comite de aeronavegabilidad*/
             if (v_estado_firma_paralelo = 'autorizado') then

                      SELECT twf.fecha_reg into v_fecha_firma_dc_qr
                      FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND
                            te.codigo = 'autorizado'
                      order by twf.fecha_reg desc
                      limit 1;
              /*************************************************************************/
             end if;




            /*Aqui recuperamos la Firma del encargado del comite de Unidad de abastecimiento*/
            if (v_estado_actual != 'comite_unidad_abastecimientos') then

                  SELECT twf.fecha_reg into v_fecha_firma_rev_qr
                  FROM wf.testado_wf twf
                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND
                        te.codigo = 'autorizado'
                  order by twf.fecha_reg desc
                  limit 1; --comite abastecimiento
            end if;
            /*********************************************************************************************************************************/


            SELECT to_char(twf.fecha_reg,'DD/MM/YYYY') into v_fecha_firma_envio
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND
                  te.codigo = 'cotizacion_solicitada'
            order by twf.fecha_reg desc
            limit 1;



            /*****************************************************/
            v_fecha_cotizacion = '';
            if (v_fecha_firma_dc_qr is not null and v_fecha_firma_rev_qr is not null) THEN
                if (v_fecha_firma_dc_qr::date > v_fecha_firma_rev_qr::date) then
                    v_fecha_cotizacion = to_char(v_fecha_firma_dc_qr::date,'DD/MM/YYYY');
                elsif (v_fecha_firma_rev_qr::date > v_fecha_firma_dc_qr::date) then
                    v_fecha_cotizacion = to_char(v_fecha_firma_abas_qr::date,'DD/MM/YYYY');
                elsif (v_fecha_firma_rev_qr::date = v_fecha_firma_dc_qr::date) then
                    v_fecha_cotizacion = to_char(v_fecha_firma_rev_qr::date,'DD/MM/YYYY');
                end if;
            end if;




            if (v_fecha_cotizacion_oficial is null) then
            	v_fecha_cotizacion_oficial = '';
            end if;


            if (v_fecha_firma_envio is null) then
            	v_fecha_firma_envio = '';
            end if;


            /*Aqui recuperamos el detalle de la solicitud*/
            select
                   list (det.condicion_det),
                   list (det.nro_parte),
                   list (det.nro_parte_alterno),
                   array_to_string(pxp.aggarray(det.descripcion),'|')::varchar, --list (det.descripcion),
                   list (det.referencia)
                   into
                   v_condicion_sol,
                   v_nro_parte_det,
                   v_nro_parte_alterna_det,
                   v_descripcion_det,
                   v_serial_det
            from mat.tsolicitud sol
            inner join mat.tdetalle_sol det on det.id_solicitud = sol.id_solicitud and det.estado_excluido = 'no'
            where sol.id_solicitud = v_id_solicitud_rec;

            if (v_condicion_sol is null) then
            	v_condicion_sol = '';
            end if;

            if (v_nro_parte_det is null) then
            	v_nro_parte_det = '';
            end if;

            if (v_nro_parte_alterna_det is null) then
            	v_nro_parte_alterna_det = '';
            end if;

            if (v_descripcion_det is null) then
            	v_descripcion_det = '';
            end if;

            if (v_serial_det is null) then
            	v_serial_det = '';
            end if;

            select sum (detcot.precio_unitario_mb)
            	   into
                   v_suma_totales
            from mat.tcotizacion cot
            inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion
            where cot.id_solicitud = v_id_solicitud_rec and cot.adjudicado = 'si';

             if (v_suma_totales is null) then
            	v_suma_totales = '0';
            end if;

            v_total_literal =  pxp.f_convertir_num_a_letra(COALESCE (v_suma_totales,0));

            /*Aqui recuperamos para las firmas Jaime Lazarte*/
            if (v_estado_actual != 'revision') then
            SELECT  twf.id_funcionario,
                    twf.fecha_reg
                  into  v_revision
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            AND te.codigo = 'revision'
            GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg
            ORDER BY twf.fecha_reg DESC
            limit 1;

              if (v_revision.fecha_reg is not null)then
                      if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
                            SELECT  twf.id_funcionario,
                                    vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                    vf.desc_funcionario1,
                                    to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                              INTO v_id_funcionario_oficial,
                                    v_funcionario_sol_oficial,
                                    v_funcionario_oficial,
                                    v_fecha_firma_pru
                            FROM wf.testado_wf twf
                            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision'
                            and v_fecha_solicitud::date between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
                            GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                            ORDER BY twf.fecha_reg DESC
                            LIMIT 1;

                            /******************Aumentando Iterinato**************************/
                            if (v_fecha_solicitud_recu >= v_fecha_nuevo_flujo::date) then
                                remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud::varchar);

                                if(remplaso is null)THEN
                                   		v_funcionario_sol_oficial = v_funcionario_sol_oficial;
                                else
                                        v_funcionario_sol_oficial = remplaso.desc_funcionario1;
                                end if;
                            else
                                v_funcionario_sol_oficial = v_funcionario_sol_oficial;
                            end if;
                            /*****************************************************************/


                       end if;
                      /****************************************************************************************/
                      else
                          v_funcionario_sol_oficial = ' ';
              end if;
            end if;

              /*if (v_estado_actual != 'borrador') then
                    /*Firma del almacenero */
                   SELECT  twf.id_funcionario,
                            twf.fecha_reg
                          into  v_rpcd
                    FROM wf.testado_wf twf
                    INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                    INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                    WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                    AND te.codigo = 'borrador'
                    GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;

            	  if (v_rpcd.fecha_reg is not null)then
                    if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
                          SELECT  twf.id_funcionario,
                                  vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                  vf.desc_funcionario1,
                                  to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                            INTO  v_id_funcionario_rpcd_oficial,
                                  v_funcionario_sol_rpcd_oficial,
                                  v_funcionario_rpcd_oficial,
                                  v_fecha_firma_rpcd_pru
                          FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                          INNER JOIN orga.vfuncionario_ultimo_cargo vf ON vf.id_funcionario = twf.id_funcionario
                          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'borrador'
                          and v_rpcd.fecha_reg between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
                          GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg;
                     end if;
        		else
                	v_funcionario_sol_rpcd_oficial = ' ';
                end if;
              end if;*/
            if (v_estado_actual != 'borrador') then


              if (v_fecha_solicitud::date >= '01/01/2022'::date) then
              SELECT		twf.id_funcionario,
                                  vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                  to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                      INTO v_id_funcionario_oficial,
                        v_funcionario_sol_rpcd_oficial,
                        v_funcionario_oficial,
                        v_fecha_firma_pru
                      FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                            AND  te.codigo = 'cotizacion'
                            AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now())
                      GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
                      ORDER BY twf.fecha_reg DESC
                      limit 1;



                     /*Aumentando del Iterinado*/
                      if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                          remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf ,v_id_funcionario_oficial,v_fecha_solicitud::varchar);

                           if(remplaso is null)THEN
                                  v_funcionario_sol_rpcd_oficial = v_funcionario_sol_rpcd_oficial;
                          else
                                  v_funcionario_sol_rpcd_oficial = remplaso.desc_funcionario1;
                          end if;
                      else
                          v_funcionario_sol_rpcd_oficial = v_funcionario_sol_rpcd_oficial;
                      end if;
                      /*******************************/


              else

                SELECT  twf.id_funcionario,
                        vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                        vf.desc_funcionario1,
                        to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                  INTO v_id_funcionario_oficial,
                        v_funcionario_sol_rpcd_oficial,
                        v_funcionario_oficial,
                        v_fecha_firma_pru
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                AND te.codigo = 'vb_dpto_abastecimientos'
                and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                ORDER BY twf.fecha_reg DESC
                limit 1;


                 /******************Aumentando Iterinato**************************/
                  if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                      remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud::varchar);

                      if(remplaso is null)THEN
                              v_funcionario_sol_rpcd_oficial = v_funcionario_sol_rpcd_oficial;
                      else
                              v_funcionario_sol_rpcd_oficial = remplaso.desc_funcionario1;
                      end if;
                  else
                      v_funcionario_sol_rpcd_oficial = v_funcionario_sol_rpcd_oficial;
                  end if;
                /*****************************************************************/

               end if;

              end if;


            if (v_funcionario_sol_rpcd_oficial is null) then
            	v_funcionario_sol_rpcd_oficial = '';
            end if;

            if (v_funcionario_sol_oficial is null) then
            	v_funcionario_sol_oficial = '';
            end if;
            /***********************/


            /*Fecha para verificar si es menor o mayor*/
             select (case when
                   v_fecha_solicitud::date < '01/01/2022'::date then
                   'menor'
                else
                   'mayor'
                end ) into v_es_mayor;
            /******************************************/

			if (v_fecha_cotizacion::date >= '01/03/2022'::date) then
            	v_etiqueta = 'si';
            else
            	v_etiqueta = 'no';
            end if;

          v_consulta:='select
          			  ('''||v_rep||''')::varchar as nro_rep,
           			  ('''||v_num_tramite_rep||''')::varchar as num_tramite,
                      ('''||v_fecha_solicitud||''')::varchar as fecha_solicitud,
                      ('''||v_fecha_order||''')::varchar as fecha_order,
                      ('''||v_fecha_cotizacion||''')::varchar as fecha_aprobacion,
                      ('''||v_num_part||''')::varchar as num_part,
                      ('''||v_num_part_alt||''')::varchar as num_part_alt,
                      ('''||v_cantidad||''')::varchar as cantidad,
                      ('''||v_descripcion||''')::varchar as descripcion,
                      ('''||v_serial||''')::varchar as serial,
                      ('''||v_cd||''')::varchar as cd,
                      ('''||v_precio_unitario||''')::varchar as precio_unitario,
                      ('''||v_precio_total||''')::varchar as precio_total,
                      ('||v_suma_totales||')::numeric as suma_total,
                      ('''||v_nom_provee||''')::varchar as nom_provee,
                      ('''||v_total_literal||''')::varchar as suma_literal,
                      ('''||v_funcionario_sol_oficial||''')::varchar as firma_unidad,
                      ('''||v_funcionario_sol_rpcd_oficial||''')::varchar as firma_jefe_departamento,
                      ('''||v_condicion_sol||''')::varchar as condicion_detalle,
                      ('''||v_gestion||''')::varchar as gestion,
                      ('''||v_evaluacion||''')::varchar as evaluacion,
                      ('''||v_tipo_taller||''')::varchar as tipo_taller,
                      ('''||v_nro_parte_det||''')::varchar as parte_det,
                      ('''||v_nro_parte_alterna_det||''')::varchar as parte_alter_det,
                      ('''||v_descripcion_det||''')::varchar as desc_det,
                      ('''||v_serial_det||''')::varchar as serial_det,
                      ('''||v_nro_lote||''')::varchar as nro_lote,
                      ('''||v_fecha_cotizacion_oficial||''')::varchar as fecha_cotizacion,
                      ('''||v_fecha_firma_envio||''')::varchar as fecha_envio,
                      ('''||v_es_mayor||''')::varchar as mayor,
                      ('''||v_etiqueta||''')::varchar as editar_etiqueta';

            raise notice 'v_consulta %',v_consulta;
			return v_consulta;
	end;

    /*********************************
 	#TRANSACCION:  'MAT_REP_TECSPE_SEL'
 	#DESCRIPCION:	Reporte Technical Specifications
 	#AUTOR:	 Ismael Valdivia
 	#FECHA:		27/03/2020
	***********************************/
    elsif(p_transaccion='MAT_REP_TECSPE_SEL')then
		begin

            /*Aqui recuperamos el id funcionario solicitante*/
            select sol.id_solicitud,
                   sol.id_funcionario_solicitante,
                   sol.id_funcionario_sol,
            	   sol.nro_tramite,
                   sol.condicion,--sol.tipo_evaluacion,
                   sol.nro_po,
                   COALESCE (to_char(sol.fecha_solicitud,'DD/MM/YYYY')::Varchar,''),
                   COALESCE (to_char(sol.fecha_po,'DD/MM/YYYY')::Varchar,''),
                   sol.tipo_solicitud,
                   sol.observaciones_sol,
                   sol.estado,
                   sol.codigo_forma_pago_alkym,
                   sol.codigo_condicion_entrega_alkym,
                   (COALESCE(sol.tiempo_entrega_estimado,0)+COALESCE(sol.tiempo_entrega,0)),
                   sol.tipo_evaluacion
                   into
                   v_id_solicitud_rec,
                   v_id_funcionario_solicitante,
                   v_id_fun_pre,
                   v_num_tramite_rep,
                   v_condicion_sol,
                   v_rep,
                   v_fecha_solicitud,
                   v_fecha_order,
                   v_prioridad,
                   v_observaciones_sol,
                   v_estado_actual,
                   v_payment_terms,
                   v_incoterms,
                   v_tiempo_entrega,
                   v_tipo_evaluacion
            from mat.tsolicitud sol
            where sol.id_proceso_wf = v_parametros.id_proceso_wf;
            /************************************************/

            /*Aqui Recuperamos el lugar y el correo del funcionario*/
            select funci.email_empresa,
                   of.telefono,
                   of.direccion
                   into
                   v_email_funcionario,
                   v_telefono_funcionario,
                   v_direccion_funcio
            from orga.vfuncionario_cargo funci
            inner join orga.tcargo car on car.id_cargo = funci.id_cargo
            inner join orga.toficina of on of.id_oficina = car.id_oficina
            where funci.id_funcionario = v_id_funcionario_solicitante
            and v_fecha_solicitud::date between funci.fecha_asignacion and COALESCE(funci.fecha_finalizacion,now())
            order by funci.fecha_asignacion DESC
            limit 1;
            /*******************************************************/



            /*Aqui recuperamos datos del funcionario*/
            select cel.numero into v_numero_interno
            from gecom.tfuncionario_celular fun
            inner join gecom.tnumero_celular cel on cel.id_numero_celular = fun.id_numero_celular
            where fun.id_funcionario = v_id_funcionario_solicitante and fun.estado_reg = 'activo' and cel.tipo = 'interno';


            /*Recuperamos datos del detalle de solicitud Cotizacion*/
            select 	list (detsol.nro_parte),
                    list (detsol.nro_parte_alterno),
                    list (detsol.cantidad_sol::varchar),
                    array_to_string(pxp.aggarray(detsol.descripcion),'|')::varchar,--list (detsol.descripcion),
                    list (detsol.referencia),
                    list (detsol.condicion_det),
                    list (detsol.precio_unitario::varchar),
                    list (detsol.precio_total::varchar)
                   INTO
                   v_num_part,
                   v_num_part_alt,
                   v_cantidad,
                   v_descripcion,
                   v_serial,
                   v_cd,
                   v_precio_unitario,
                   v_precio_total
            from mat.tsolicitud sol
            inner join mat.tdetalle_sol detsol on detsol.id_solicitud = sol.id_solicitud and detsol.estado_excluido = 'no'
            where sol.id_solicitud = v_id_solicitud_rec;

			/*Aumentnado para que se sume los disas estimados en el reporte de especificacion tecnica*/
            select sum(COALESCE(day.cantidad_dias,0)) into v_cantidad_sumados_adjudicado
            from mat.tcotizacion cot
            inner join mat.tcotizacion_detalle det on det.id_cotizacion = cot.id_cotizacion
            inner join mat.tday_week day on day.id_day_week = det.id_day_week
            where cot.id_solicitud = v_id_solicitud_rec
            and cot.adjudicado = 'si';
            /******************************************************************************************/

            v_dias_sumados = v_tiempo_entrega;


            /*Aqui recuperamos para las firmas Jaime Lazarte*/
            if (v_estado_actual != 'revision') then

            SELECT  twf.id_funcionario,
                    twf.fecha_reg
                  into  v_revision
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            AND te.codigo = 'revision'
            GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;


              if (v_revision.fecha_reg is not null)then
                if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
                      SELECT  twf.id_funcionario,
                              vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||COALESCE (to_char(twf.fecha_reg,'DD-MM-YYYY'),'')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                              vf.desc_funcionario1,
                              to_char(twf.fecha_reg,'DD-MM-YYYY')as fecha_firma
                        INTO v_id_funcionario_oficial,
                              v_funcionario_sol_oficial,
                              v_funcionario_oficial,
                              v_fecha_firma_pru
                      FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                      INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                      AND te.codigo = 'revision'
                      and v_fecha_solicitud::date between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
                      GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                      ORDER BY twf.fecha_reg DESC
                      limit 1;

                      /*Aumentando para el iterinato 25/01/2022*/
                      if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                          remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud::varchar);

                          if(remplaso is null)THEN
                                v_funcionario_sol_oficial = v_funcionario_sol_oficial;
                          else
                                  v_funcionario_sol_oficial = remplaso.desc_funcionario1;
                          end if;
                      else
                          v_funcionario_sol_oficial = v_funcionario_sol_oficial;
                      end if;
                      /*****************************************/



                 end if;

              else
                  v_funcionario_sol_oficial = '';
                  v_fecha_firma_pru = '';
              end if;
            end if;

            if (v_funcionario_sol_oficial is null) then
            	v_funcionario_sol_oficial = '';
            end if;

            if (v_fecha_firma_pru is null) then
            	v_fecha_firma_pru = '';
            end if;


            /****************************************************************************************/

            /*Recuperamos al gerente de abastecimiento esta variable se parametrizo en variable global*/

              if (v_estado_actual != 'borrador') then
              select fun.desc_funcionario1||' | '||fun.nombre_cargo||' | '||v_num_tramite_rep||' | '||v_fecha_solicitud||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1
              	     into
                     v_funcionario_pre
              from orga.vfuncionario_cargo fun
              where fun.id_funcionario = v_id_fun_pre
              and v_fecha_solicitud::date between fun.fecha_asignacion and  coalesce(fun.fecha_finalizacion,now())
              order by fun.fecha_asignacion DESC
              limit 1;


               /*Aumentando para el iterinato 25/01/2022*/
                if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                    remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_fun_pre,v_fecha_solicitud::varchar);

                    if(remplaso is null)THEN
                          v_funcionario_pre = v_funcionario_pre;
                    else
                          v_funcionario_pre = remplaso.desc_funcionario1;
                    end if;
                else
                	v_funcionario_pre = v_funcionario_pre;
                end if;
                /*****************************************/

              end if;

              if (v_funcionario_pre is NULL) then
              	v_funcionario_pre='';
              end if;
              /******************************************************************************************/

          v_consulta:='select
           			  ('''||v_num_tramite_rep||''')::varchar as num_tramite,
                      ('''||COALESCE(v_condicion_sol,'')||''')::varchar as po_type,
                      ('''||v_rep||''')::varchar as rep,
                      ('''||v_fecha_solicitud||''')::varchar as fecha_order,
                      ('''||upper(v_prioridad)||''')::varchar as priority,
                      ('''||v_num_part||''')::varchar as num_part,
                      ('''||COALESCE(v_num_part_alt,'''''')||''')::varchar as num_part_alt,
                      ('''||v_cantidad||''')::varchar as cantidad,
                      ('''||v_descripcion||''')::varchar as descripcion,
                      ('''||v_serial||''')::varchar as serial,
                      ('''||v_cd||''')::varchar as cd,
                      ('''||v_observaciones_sol||''')::varchar as observaciones_sol,
                      ('''||COALESCE(v_payment_terms,'')||''')::varchar as payment_terms,
                      ('''||COALESCE(v_incoterms,'')||''')::varchar as incoterms,
                      ('''||COALESCE(v_ship_to,'')||''')::varchar as ship_to,
                      ('''||v_funcionario_sol_oficial||''')::varchar as aprobado_por,
                      ('''||v_funcionario_pre||''')::varchar as preparado_por,
                      ('||v_dias_sumados||')::integer as tiempo_entrega,
                      ('''||v_tipo_evaluacion||''')::varchar as tipo_evaluacion';

            raise notice 'v_consulta %',v_consulta;
			return v_consulta;
	end;

    /*********************************
 	#TRANSACCION:  'MAT_COMBOS_ALKYM_SEL'
 	#DESCRIPCION:	Recuperamos los combos de Alkym
 	#AUTOR:	 Ismael Valdivia
 	#FECHA:		27/03/2020
	***********************************/
    elsif(p_transaccion='MAT_COMBOS_ALKYM_SEL')then
		begin


          if (v_parametros.tipo_combo = 'condicion_entrega' OR v_parametros.tipo_combo = 'formas_pago' OR v_parametros.tipo_combo = 'modos_envio' OR v_parametros.tipo_combo = 'tipo_transaccion' OR v_parametros.tipo_combo = 'orden_destino') then
                CREATE TEMPORARY TABLE combosAlkym (  id  int4,
                                                      nombre varchar,
                                                      direccion varchar
                                                  )ON COMMIT DROP;


                if (v_parametros.cantidad_json is not null) then
                      v_contador = v_parametros.cantidad_json;
                end if;

                for i in 0..(v_contador-1) loop
                  v_id_condicion_entrega = v_parametros.json_obtenido->i->>'id';
                  v_name_condicion_entrega = v_parametros.json_obtenido->i->>'Name';


                  insert into combosAlkym (id,
                                           nombre,
                                           direccion
                                            )
                                    VALUES(v_id_condicion_entrega::integer,
                                           v_name_condicion_entrega::varchar,
                                           NULL);

                end loop;



                v_consulta:='select alk.id,
                                    alk.nombre,
                                    alk.direccion
                             from combosAlkym alk
                             where ';
          elsif (v_parametros.tipo_combo = 'puntos_entrega') then

           		CREATE TEMPORARY TABLE combosAlkym (  id  int4,
                                                      nombre varchar,
                                                      direccion varchar
                                                  )ON COMMIT DROP;
                 if (v_parametros.cantidad_json is not null) then
                      v_contador = v_parametros.cantidad_json;
                end if;

                for i in 0..(v_contador-1) loop
                  v_id_condicion_entrega = v_parametros.json_obtenido->i->>'IdPuntoEntrega';
                  v_name_condicion_entrega = v_parametros.json_obtenido->i->>'PuntoEntrega';
                  v_dire_condicion_entrega = v_parametros.json_obtenido->i->>'Direccion';


                  insert into combosAlkym (id,
                                           nombre,
                                           direccion
                                            )
                                    VALUES(v_id_condicion_entrega::integer,
                                           v_name_condicion_entrega::varchar,
                                           v_dire_condicion_entrega::varchar);

                end loop;



                v_consulta:='select alk.id,
                                    alk.nombre,
                                    alk.direccion
                             from combosAlkym alk
                             where ';

          end if;

            v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			RAISE NOTICE 'v_consulta %',v_consulta;
			return v_consulta;
	end;

    /*********************************
 	#TRANSACCION:  'MAT_COMBOS_ALKYM_CONT'
 	#DESCRIPCION:	Recuperamos los combos de Alkym
 	#AUTOR:	 Ismael Valdivia
 	#FECHA:		27/03/2020
	***********************************/
    elsif(p_transaccion='MAT_COMBOS_ALKYM_CONT')then
		begin

          CREATE TEMPORARY TABLE combosAlkym (  id  int4,
                                                nombre varchar
                                            )ON COMMIT DROP;


          if (v_parametros.cantidad_json is not null) then
          		v_contador = v_parametros.cantidad_json;
          end if;

          for i in 0..(v_contador-1) loop
          	v_id_condicion_entrega = v_parametros.json_obtenido->i->>'id';
            v_name_condicion_entrega = v_parametros.json_obtenido->i->>'Name';


            insert into combosAlkym (id,
                                     nombre
                                      )
                              VALUES(v_id_condicion_entrega::integer,
                                     v_name_condicion_entrega::varchar);

          end loop;

          v_consulta:='select count(alk.id)
           			   from combosAlkym alk
                       where ';

            raise notice 'v_consulta %',v_consulta;
            v_consulta:=v_consulta||v_parametros.filtro;
			return v_consulta;
	end;

    /*********************************
 	#TRANSACCION:  'MAT_DETSERV_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:12:58
	***********************************/
    elsif(p_transaccion='MAT_DETSERV_SEL')then
		begin
        	v_consulta = 'select
                                detcot.nro_parte_cot::varchar as partnumber,
                                detcot.cantidad_det::integer as cantidad,
                                detcot.precio_unitario::numeric as preciounitario,
                                ''USD''::varchar as moneda,
                                detcot.cd::varchar as condicion,
                                sol.fecha_entrega::varchar as fechaentrega,
                                (select cat.codigo
                                from param.tcatalogo cat
                                inner join param.tcatalogo_tipo tip on tip.id_catalogo_tipo = cat.id_catalogo_tipo
                                where tip.nombre = ''tdetalle_sol'' and cat.descripcion = detcot.tipo_cot)::integer as IdPlanCuentaComp,
                                detcot.descripcion_cot::varchar as descripcion
                        from mat.tcotizacion cot
                        inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion
                        /*Aumentando para no mandar el Hazmat (Comentar cuando Jhon modifique el servicio) (Ismael Valdivia 17/11/2020)*/
                        and detcot.tipo_cot <> ''Otros Cargos'' and  detcot.tipo_cot <>''NA'' and detcot.revisado = ''si'' and detcot.tipo_cot <> ''Fletes - Otros''
                        /***************************************************************************************************************/
                        inner join mat.tsolicitud sol on sol.id_solicitud = cot.id_solicitud
                        where cot.id_solicitud = '||v_parametros.id_solicitud::integer||' and cot.adjudicado = ''si''';
        	raise notice '%', v_consulta;
            return v_consulta;
        end;

        /*********************************
        #TRANSACCION:  'MAT_DET_HAZMAT_SEL'
        #DESCRIPCION:	Consulta de datos
        #AUTOR:		Ismael Valdivia
        #FECHA:		17-11-2021 09:42:58
        ***********************************/
        elsif(p_transaccion='MAT_DET_HAZMAT_SEL')then
            begin
                v_consulta = 'select
                                    detcot.nro_parte_cot::varchar as partnumber,
                                    detcot.cantidad_det::integer as cantidad,
                                    detcot.precio_unitario::numeric as preciounitario,
                                    ''USD''::varchar as moneda,
                                    detcot.cd::varchar as condicion,
                                    sol.fecha_entrega::varchar as fechaentrega,
                                    (select cat.codigo
                                    from param.tcatalogo cat
                                    inner join param.tcatalogo_tipo tip on tip.id_catalogo_tipo = cat.id_catalogo_tipo
                                    where tip.nombre = ''tdetalle_sol'' and cat.descripcion = detcot.tipo_cot)::integer as IdPlanCuentaComp,
                                    detcot.descripcion_cot::varchar as descripcion
                            from mat.tcotizacion cot
                            inner join mat.tcotizacion_detalle detcot on detcot.id_cotizacion = cot.id_cotizacion
                            inner join mat.tsolicitud sol on sol.id_solicitud = cot.id_solicitud
                            where cot.id_solicitud = '||v_parametros.id_solicitud::integer||' and cot.adjudicado = ''si''
                            and detcot.nro_parte_cot = ''HAZMAT''';
                raise notice '%', v_consulta;
                return v_consulta;
            end;

        /*********************************
        #TRANSACCION:  'MAT_DETCABE_SEL'
        #DESCRIPCION:	Consulta de datos
        #AUTOR:		admin
        #FECHA:		23-12-2016 13:12:58
        ***********************************/
        elsif(p_transaccion='MAT_DETCABE_SEL')then
            begin
                v_consulta = 'select
                					 pro.id_proveedor_alkym::integer,
                                     (select cat.codigo
                                      from param.tcatalogo cat
                                      inner join param.tcatalogo_tipo tip on tip.id_catalogo_tipo = cat.id_catalogo_tipo
                                      where tip.nombre = ''tsolicitud_criticidad'' and cat.descripcion = sol.tipo_solicitud)::integer as id_criticidad,
                                     sol.id_condicion_entrega_alkym::integer,
                                     sol.id_forma_pago_alkym::integer,
                                     sol.id_modo_envio_alkym::integer,
                                     sol.id_puntos_entrega_alkym::integer,
                                     sol.id_tipo_transaccion_alkym::integer,
                                     cot.monto_total::numeric,
                                     ot.codigo::varchar as matricula,
                                     procont.id_proveedor_contacto_alkym::integer,
                                     sol.id_orden_destino_alkym::integer,
                                     sol.remark::varchar,
                                     (select fun.ci
                                      from orga.vfuncionario_ultimo_cargo fun
                                      where fun.id_funcionario = (select pxp.f_get_variable_global(''funcionario_solicitante_gm'')::integer))::varchar as nro_documento,
                                      sol.fecha_po
                              from mat.tsolicitud sol
                              inner join mat.tcotizacion cot on cot.id_solicitud = sol.id_solicitud
                              left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                              inner join param.tproveedor_contacto procont on procont.id_proveedor_contacto = cot.id_proveedor_contacto
                              inner join param.tproveedor pro on pro.id_proveedor = sol.id_proveedor
                              where cot.adjudicado = ''si'' and sol.id_solicitud = '||v_parametros.id_solicitud::integer||'';
                raise notice '%', v_consulta;
                return v_consulta;
            end;


    /*********************************
 	#TRANSACCION:  'MAT_REP_SOLCOMP_SEL'
 	#DESCRIPCION:	Reporte Solicitud Compra
 	#AUTOR:	 Ismael Valdivia
 	#FECHA:		6/05/2020
	***********************************/
    elsif(p_transaccion='MAT_REP_SOLCOMP_SEL')then
		begin


            IF pxp.f_existe_parametro(p_tabla,'id_proceso_wf') then
                  v_filtro = 'sol.id_proceso_wf='||v_parametros.id_proceso_wf||' and ';

                  v_proces_wf = v_parametros.id_proceso_wf;

               		select sol.nro_tramite,
                           sol.fecha_solicitud,
                           sol.nro_tramite
                     into v_nro_tramite,
                          v_fecha_solicitud,
                          v_nro_tramite
                    from mat.tsolicitud sol
                    where sol.id_proceso_wf = v_parametros.id_proceso_wf;


                    SELECT ma.nombre into v_nombre_macro
                    FROM wf.tproceso_wf pro
                    inner join wf.ttipo_proceso tp on tp.id_tipo_proceso = pro.id_tipo_proceso
                    inner join wf.tproceso_macro ma on ma.id_proceso_macro = tp.id_proceso_macro
                    WHERE pro.id_proceso_wf = v_proces_wf;

            END IF;

			if (substr(v_nro_tramite, 1, 2) in ('GM', 'GO', 'GA', 'GC', 'GR')) then

            	select sol.fecha_solicitud
                	into v_fecha_sol_rep
                from mat.tsolicitud sol
                where sol.nro_tramite = v_nro_tramite;

            else

                select es.id_estado_wf
                	into v_id_estado_wf
                from wf.testado_wf es
                where es.fecha_reg = (
                select
                     max(ewf.fecha_reg)
                   FROM  wf.testado_wf ewf
                   INNER JOIN  wf.ttipo_estado te on ewf.id_tipo_estado = te.id_tipo_estado
                   LEFT JOIN   segu.tusuario usu on usu.id_usuario = ewf.id_usuario_reg
                   LEFT JOIN  orga.vfuncionario fun on fun.id_funcionario = ewf.id_funcionario
                   LEFT JOIN  param.tdepto depto on depto.id_depto = ewf.id_depto
                   WHERE
                    ewf.id_proceso_wf = v_proces_wf
                    and te.codigo = 'borrador'
                    and te.etapa = 'Solicitante');

              select
                     ew.fecha_reg::date
                     into v_fecha_sol
                   FROM  wf.testado_wf ew
                   where ew.id_estado_anterior = v_id_estado_wf;

          	end if;


            /*Firma Marco Mendoza Encargado RPC*/
                 /*   select fun.desc_funcionario1 into v_funcionario_rpcd_oficial
                    from wf.tproceso_macro ma
                    inner join wf.ttipo_proceso tip on tip.id_proceso_macro = ma.id_proceso_macro
                    inner join wf.ttipo_estado es on es.id_tipo_proceso = tip.id_tipo_proceso
                    inner join wf.tfuncionario_tipo_estado tes on tes.id_tipo_estado = es.id_tipo_estado
                    inner join orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = tes.id_funcionario
                    where --ma.nombre = 'Reparación de Repuestos' and tip.codigo = 'GR-RDM' and es.codigo = 'vb_rpcd';
							ma.nombre = 'Reparacion de Repuestos' and tip.codigo = 'GR-RM' and es.codigo = 'vb_rpcd';*/


             SELECT
        				vf.desc_funcionario1
            into
        				v_funcionario_rpcd_oficial
          	FROM wf.testado_wf twf
          		INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
          		INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          	WHERE twf.id_proceso_wf = v_proces_wf
            	  AND  te.codigo = 'vb_rpcd'
                   and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
           	GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo,pro.nro_tramite
            ORDER BY  twf.fecha_reg DESC
			LIMIT 1;
            /***********************/

            v_id_gerente_rep = pxp.f_get_variable_global('gerente_boa_rep_solicitud_compra')::integer;


            if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) THEN
            select fu.desc_funcionario1,
                   fu.desc_funcionario1||' | '||fu.nombre_cargo||' | '||v_nro_tramite||' | '||v_fecha_sol_rep||' | Boliviana de Aviación - BoA'::varchar as firma_fun,
                   uo.codigo||'-'||uo.nombre_unidad as desc_uo,
                   fu.descripcion_cargo
            	   into
                   v_gerente,
                   v_firma_gerente,
                   v_desc_uo,
                   v_desc_cargo_gerente
            from orga.vfuncionario_cargo fu
            inner join orga.tuo uo on uo.id_uo = fu.id_uo
            where v_fecha_solicitud::date between
            fu.fecha_asignacion and COALESCE(fu.fecha_finalizacion,now()::date)
            and fu.nombre_cargo = 'Director de Aeronavegabilidad Continua'
            order by fu.fecha_asignacion desc
            limit 1;

            ELSE

            select fu.desc_funcionario1,
                   fu.desc_funcionario1||' | '||fu.nombre_cargo||' | '||v_nro_tramite||' | '||v_fecha_sol_rep||' | Boliviana de Aviación - BoA'::varchar as firma_fun,
                   uo.codigo||'-'||uo.nombre_unidad as desc_uo,
                   fu.descripcion_cargo
            	   into
                   v_gerente,
                   v_firma_gerente,
                   v_desc_uo,
                   v_desc_cargo_gerente
            from orga.vfuncionario_cargo fu
            inner join orga.tuo uo on uo.id_uo = fu.id_uo
            where v_fecha_solicitud::date between
            fu.fecha_asignacion and COALESCE(fu.fecha_finalizacion,now()::date)
            and fu.nombre_cargo = 'Gerencia Administrativa Financiera';

			END IF;


            --fu.id_funcionario = v_id_gerente_rep;


            /*Aumentando para recuperar la fecha de la cotizacion*/
            /*select cot.fecha_cotizacion
            into
            v_cotizacion_fecha
            from mat.tsolicitud sol
            inner join mat.tcotizacion cot on cot.id_solicitud = sol.id_solicitud and cot.adjudicado = 'si'
            where sol.id_proceso_wf = v_proces_wf;*/

            /*Cambiando para recuperar la fecha del proceso wf*/

            select
        			to_char(sou.fecha_po,'DD/MM/YYYY')as fechapo,
                    to_char(sou.fecha_solicitud,'DD/MM/YYYY')as fechasol,
                    sou.id_proceso_wf_firma,
                    sou.estado_firma,
                    sou.estado,
                    sou.nro_tramite
                    into
                    v_fecha_po,
                    v_fecha_solicitud,
                    v_id_proceso_wf_firma,
                    v_estado_firma_paralelo,
                    v_estado_actual,
                    v_nro_tramite
            from mat.tsolicitud sou
            where sou.id_proceso_wf = v_proces_wf;



            /*Aqui recuperamos la Firma del encargado del comite de aeronavegabilidad*/
             if (v_estado_firma_paralelo = 'autorizado') then

                      SELECT twf.fecha_reg into v_fecha_firma_dc_qr
                      FROM wf.testado_wf twf
                      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                      WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND
                            te.codigo = 'autorizado'
                      order by twf.fecha_reg desc
                      limit 1;
              /*************************************************************************/
             end if;




            /*Aqui recuperamos la Firma del encargado del comite de Unidad de abastecimiento*/
            if (v_estado_actual != 'comite_unidad_abastecimientos') then

                  SELECT twf.fecha_reg into v_fecha_firma_rev_qr
                  FROM wf.testado_wf twf
                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                  WHERE twf.id_proceso_wf = v_proces_wf AND
                        te.codigo = 'autorizado'
                  order by twf.fecha_reg desc
                  limit 1; --comite abastecimiento
            end if;
            /*********************************************************************************************************************************/



            /*****************************************************/
            v_cotizacion_fecha = '';
            if (v_fecha_firma_dc_qr is not null and v_fecha_firma_rev_qr is not null) THEN
                if (v_fecha_firma_dc_qr::date > v_fecha_firma_rev_qr::date) then
                    v_cotizacion_fecha = v_fecha_firma_dc_qr::varchar;
                elsif (v_fecha_firma_rev_qr::date > v_fecha_firma_dc_qr::date) then
                    v_cotizacion_fecha = v_fecha_firma_abas_qr::varchar;
                elsif (v_fecha_firma_rev_qr::date = v_fecha_firma_dc_qr::date) then
                    v_cotizacion_fecha = v_fecha_firma_rev_qr::varchar;
                end if;
            end if;



		if (v_nombre_macro = 'Reparacion de Repuestos') then

        	if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) THEN
                  SELECT  twf.id_funcionario,
                          vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||COALESCE (to_char(twf.fecha_reg,'DD-MM-YYYY'),'')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                          vf.desc_funcionario1,
                          to_char(twf.fecha_reg,'DD-MM-YYYY')as fecha_firma,
                          vf.nombre_cargo
                    INTO v_id_funcionario_oficial,
                          v_funcionario_sol_oficial,
                          v_funcionario_oficial,
                          v_fecha_firma_pru,
                          v_cargo_solicitante
                  FROM wf.testado_wf twf
                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                  INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                  INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision_tecnico_abastecimientos'
                  and v_fecha_solicitud::date between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
                  GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg;



                  if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                      remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud);
                      if(remplaso is null)THEN
                              v_funcionario_oficial = v_funcionario_oficial;
                      else
                              v_funcionario_oficial = remplaso.funcion;
                      end if;
                  end if;

            else
            	SELECT  twf.id_funcionario,
                          vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||COALESCE (to_char(twf.fecha_reg,'DD-MM-YYYY'),'')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                          vf.desc_funcionario1,
                          to_char(twf.fecha_reg,'DD-MM-YYYY')as fecha_firma,
                          vf.nombre_cargo
                    INTO v_id_funcionario_oficial,
                          v_funcionario_sol_oficial,
                          v_funcionario_oficial,
                          v_fecha_firma_pru,
                          v_cargo_solicitante
                  FROM wf.testado_wf twf
                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                  INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                  INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'cotizacion'
                  and v_fecha_solicitud::date between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
                  GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg;



                  if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                      remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud);
                      if(remplaso is null)THEN
                              v_funcionario_oficial = v_funcionario_oficial;
                      else
                              v_funcionario_oficial = remplaso.funcion;
                      end if;
                  end if;

            end if;





        	v_consulta:='select
						sol.id_solicitud,
                        sol.estado_reg,
                        sol.estado,
                        sol.id_moneda,
                        sol.id_gestion,
                        sol.tipo,
                        sol.nro_tramite,
                        sol.motivo_solicitud,
                        sol.id_depto,
                        sol.id_proceso_wf,
                        sol.id_funcionario_solicitante,
                        sol.id_estado_wf,
                        sol.fecha_solicitud,
                        sol.fecha_reg,
                        sol.id_usuario_reg,
                        sol.fecha_mod,
                        sol.id_usuario_mod,
                        COALESCE(sol.usuario_ai,'''')::varchar as nombre_usuario_ai,
                        usu1.cuenta as usr_reg,
                        usu2.cuenta as usr_mod,
                        '''||v_funcionario_oficial||'''::text as desc_funcionario,
                        ges.gestion as desc_gestion,
                        mon.codigo as desc_moneda,
                        dep.codigo as desc_depto,
                        dep.prioridad as dep_prioridad,
						'''||coalesce(v_fecha_sol,now())||'''::date as fecha_soli_gant,
                        '''||coalesce(v_fecha_sol_rep,now())||'''::date as fecha_soli_material,
                        ('''||Coalesce(v_funcionario_rpcd_oficial,'')||''')::varchar as funcionario_rpc,
                        ('''||Coalesce(v_gerente,'')||''')::varchar as gerente,
                        ('''||Coalesce(v_firma_gerente,'')||''')::varchar as firma_gerente,
                        ('''||v_desc_uo||''')::varchar as desc_uo,
                        '''||v_cargo_solicitante||'''::varchar as cargo_desc_funcionario,
                        ('''||v_desc_cargo_gerente||''')::varchar as desc_cargo_gerente,
                        '''||v_nombre_macro||'''::varchar as nombre_macro,
                        '''||v_cotizacion_fecha||'''::varchar as cotizacion_fecha
						from mat.tsolicitud sol
                        inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
                        inner join orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = sol.id_funcionario_solicitante
                        inner join param.tmoneda mon on mon.id_moneda = sol.id_moneda
                        inner join param.tgestion ges on ges.id_gestion = sol.id_gestion
                        inner join param.tdepto dep on dep.id_depto = sol.id_depto
                        left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                        inner join wf.testado_wf ew on ew.id_estado_wf = sol.id_estado_wf
                        where '||v_filtro;
        else

        if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) THEN
        SELECT  twf.id_funcionario,
                    vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||COALESCE (to_char(twf.fecha_reg,'DD-MM-YYYY'),'')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                    vf.desc_funcionario1,
                    to_char(twf.fecha_reg,'DD-MM-YYYY')as fecha_firma,
                    vf.nombre_cargo
              INTO v_id_funcionario_oficial,
                    v_funcionario_sol_oficial,
                    v_funcionario_oficial,
                    v_fecha_firma_pru,
                    v_cargo_solicitante
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'revision_tecnico_abastecimientos'
            and v_fecha_solicitud::date between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
            GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg;

            if (v_id_funcionario_oficial is null) then
                SELECT  twf.id_funcionario,
                        vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||COALESCE (to_char(twf.fecha_reg,'DD-MM-YYYY'),'')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                        vf.desc_funcionario1,
                        to_char(twf.fecha_reg,'DD-MM-YYYY')as fecha_firma,
                        vf.nombre_cargo
                  INTO v_id_funcionario_oficial,
                        v_funcionario_sol_oficial,
                        v_funcionario_oficial,
                        v_fecha_firma_pru,
                        v_cargo_solicitante
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'cotizacion'
                and v_fecha_sol_rep between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg;
            end if;


             if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
            	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud);

                if(remplaso is null)THEN
                        v_funcionario_oficial = v_funcionario_oficial;
                else
                        v_funcionario_oficial = remplaso.funcion;

                end if;
            end if;
		else
        	SELECT  twf.id_funcionario,
                    vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||COALESCE (to_char(twf.fecha_reg,'DD-MM-YYYY'),'')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                    vf.desc_funcionario1,
                    to_char(twf.fecha_reg,'DD-MM-YYYY')as fecha_firma,
                    vf.nombre_cargo
              INTO v_id_funcionario_oficial,
                    v_funcionario_sol_oficial,
                    v_funcionario_oficial,
                    v_fecha_firma_pru,
                    v_cargo_solicitante
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'cotizacion'
            and v_fecha_solicitud::date between vf.fecha_asignacion and  coalesce(vf.fecha_finalizacion,now())
            GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg;

             if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
            	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud);

                if(remplaso is null)THEN
                        v_funcionario_oficial = v_funcionario_oficial;
                else
                        v_funcionario_oficial = remplaso.funcion;

                end if;
            end if;
        end if;



        v_consulta:='select
						sol.id_solicitud,
                        sol.estado_reg,
                        sol.estado,
                        sol.id_moneda,
                        sol.id_gestion,
                        sol.tipo,
                        sol.nro_tramite,
                        sol.remark::varchar,
                        sol.id_depto,
                        sol.id_proceso_wf,
                        sol.id_funcionario_solicitante,
                        sol.id_estado_wf,
                        sol.fecha_solicitud,
                        sol.fecha_reg,
                        sol.id_usuario_reg,
                        sol.fecha_mod,
                        sol.id_usuario_mod,
                        COALESCE(sol.usuario_ai,'''')::varchar as nombre_usuario_ai,
                        usu1.cuenta as usr_reg,
                        usu2.cuenta as usr_mod,
                        '''||v_funcionario_oficial||'''::text as desc_funcionario,
                        ges.gestion as desc_gestion,
                        mon.codigo as desc_moneda,
                        dep.codigo as desc_depto,
                        dep.prioridad as dep_prioridad,
						'''||coalesce(v_fecha_sol,now())||'''::date as fecha_soli_gant,
                        '''||coalesce(v_fecha_sol_rep,now())||'''::date as fecha_soli_material,
                        ('''||Coalesce(v_funcionario_rpcd_oficial,'')||''')::varchar as funcionario_rpc,
                        ('''||Coalesce(v_gerente,'')||''')::varchar as gerente,
                        ('''||Coalesce(v_firma_gerente,'')||''')::varchar as firma_gerente,
                        ('''||v_desc_uo||''')::varchar as desc_uo,
                        '''||v_cargo_solicitante||'''::varchar as cargo_desc_funcionario,
                        ('''||v_desc_cargo_gerente||''')::varchar as desc_cargo_gerente,
                        '''||v_nombre_macro||'''::varchar as nombre_macro,
                        sol.fecha_solicitud::varchar as cotizacion_fecha
						from mat.tsolicitud sol
                        inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
                        inner join orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = sol.id_funcionario_solicitante
                        inner join param.tmoneda mon on mon.id_moneda = sol.id_moneda
                        inner join param.tgestion ges on ges.id_gestion = sol.id_gestion
                        inner join param.tdepto dep on dep.id_depto = sol.id_depto
                        left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                        inner join wf.testado_wf ew on ew.id_estado_wf = sol.id_estado_wf
                        where '||v_filtro;
        end if;


			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            raise notice '%', v_consulta;

			return v_consulta;
	end;

    /*********************************
    #TRANSACCION:  'MAT_CTRRPCE_REP'
    #DESCRIPCION:	Reporte para datos autorizados RPCE
    #AUTOR:		Ismael Valdivia
    #FECHA:		07-10-2020 13:12:58
    ***********************************/
    elsif(p_transaccion='MAT_CTRRPCE_REP')then
        begin
            if (v_parametros.origen_pedido != 'Todos')then
                v_fill = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and c.adjudicado = ''si''';

        else
                v_fill = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and c.adjudicado = ''si''';
		end if;

       v_consulta:='	select	 s.origen_pedido,
                                 s.nro_tramite,
                                 t.nombre_estado as estado,
                                 initcap (f.desc_funcionario1) as funciaonario,
                                 COALESCE (ot.desc_orden,'' '')::varchar as matricula,
                                 to_char(s.fecha_solicitud,''DD/MM/YYYY'')as fecha_solicitud,
                                 to_char(s.fecha_requerida,''DD/MM/YYYY'')as fecha_requerida,
                                 initcap(s.motivo_solicitud)::varchar as motivo_solicitud,
                                 initcap(s.observaciones_sol)::varchar as observaciones_sol,
                                 s.justificacion,
                                 s.nro_justificacion,
                                 s.tipo_solicitud,
                                 s.tipo_falla,
                                 s.tipo_reporte,
                                 s.mel,
                                 s.nro_no_rutina,
                                 c.nro_cotizacion,
                                 initcap(v.desc_proveedor) as proveedor,
                                 d.nro_parte_cot,
                                 d.nro_parte_alterno_cot,
                                 d.descripcion_cot,
                                 d.explicacion_detallada_part_cot,
                                 d.cantidad_det,
                                 d.precio_unitario,
                                 d.precio_unitario_mb,
                                 s.nro_po,
                                 vu.desc_persona::varchar as aux_abas,
                                 (cc.ep || '' - '' || cc.nombre_uo)::varchar as centro_costo,
                                 (pp.codigo || '' - '' || pp.nombre_partida)::varchar as partida,
                                 to_char(MAX(e.fecha_reg::date), ''DD/MM/YYYY'')::varchar as fecha_autorizacion_rpc
                                 from mat.tasginacion_automatica_abastecimiento asig
                                 inner join mat.tsolicitud s on s.id_solicitud = asig.id_solicitud
                                 inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                                 inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud
                                 inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                                 inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion and d.revisado = ''si''
                                 inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf
                                 inner join wf.ttipo_estado t on t.id_tipo_estado = e.id_tipo_estado and t.codigo = ''vb_rpcd''
                                 inner join wf.testado_wf es on es.id_estado_wf = s.id_estado_wf
                                 inner join wf.ttipo_estado tt on tt.id_tipo_estado = es.id_tipo_estado
          						 left join conta.torden_trabajo ot on ot.id_orden_trabajo = s.id_matricula
								 left join segu.vusuario vu on c.id_usuario_reg = vu.id_usuario
                                 left join mat.tdetalle_sol sd on d.id_detalle = sd.id_detalle
                                 left join param.vcentro_costo cc on  sd.id_centro_costo = cc.id_centro_costo
                                 left join pre.tpartida pp on sd.id_partida = pp.id_partida
                                 where '||v_fill||'
                                 --and tt.codigo not in (''borrador'',''revision'',''cotizacion'',''cotizacion_solicitada'')
                                 group by s.origen_pedido,
                                        s.nro_tramite,
                                        t.nombre_estado,
                                        f.desc_funcionario1,
                                        ot.desc_orden,
                                        s.fecha_solicitud,
                                        s.fecha_requerida,
                                        s.motivo_solicitud,
                                        s.observaciones_sol,
                                        s.justificacion,
                                        s.nro_justificacion,
                                        s.tipo_solicitud,
                                        s.tipo_falla,
                                        s.tipo_reporte,
                                        s.mel,
                                        s.nro_no_rutina,
                                        c.nro_cotizacion,
                                        v.desc_proveedor,
                                        d.nro_parte_cot,
                                        d.nro_parte_alterno_cot,
                                        d.descripcion_cot,
                                        d.explicacion_detallada_part_cot,
                                        d.cantidad_det,
                                        d.precio_unitario,
                                        d.precio_unitario_mb,
                                        s.nro_po,
                                        vu.desc_persona,
                                        cc.ep,
                                        cc.nombre_uo,
                                        pp.codigo,
                                        pp.nombre_partida,

                                        tt.codigo
                                 order by origen_pedido, s.nro_tramite ';

			return v_consulta;
        end;

       /*********************************
      #TRANSACCION:  'MAT_COMB_PARNUM_SEL'
      #DESCRIPCION:	Recuperamos los combos de Alkym
      #AUTOR:	 Ismael Valdivia
      #FECHA:		24/06/2021
      ***********************************/
      elsif(p_transaccion='MAT_COMB_PARNUM_SEL')then
          begin


            CREATE TEMP TABLE comboPartNumber (  IdProducto  int4,
                                                                                           IdProductoPN  int4,
                                                                                           PN varchar,
                                                                                           Descripcion varchar,
                                                                                           TipoProducto varchar,
                                                                                           Codigo varchar,
                                                                                           IdUnidadMedida INTEGER,
                                                                                           IdTipoProducto	INTEGER,
                                                                                           Reparable	varchar
                                                                                      )ON COMMIT DROP;


                  if (v_parametros.cantidad_json is not null) then
                        v_contador = v_parametros.cantidad_json;
                  end if;

                  for i in 0..(v_contador-1) loop

                    v_IdProducto = v_parametros.json_obtenido->i->>'IdProducto';
                    v_IdProductoPN = v_parametros.json_obtenido->i->>'IdProductoPN';
                    v_PN = v_parametros.json_obtenido->i->>'PN';
                    v_DescripcionPN= v_parametros.json_obtenido->i->>'Descripcion';
                    v_TipoProductoPN = v_parametros.json_obtenido->i->>'TipoProducto';

                     v_Codigo_UM_PN = v_parametros.json_obtenido->i->>'Codigo';
                     v_Id_UM_PN = v_parametros.json_obtenido->i->>'IdUnidadMedida';
                     v_Id_TipoProducto_PN = v_parametros.json_obtenido->i->>'IdTipoProducto';
                     v_Reparable = v_parametros.json_obtenido->i->>'Reparable';

                    insert into comboPartNumber (IdProducto ,
                                                           IdProductoPN ,
                                                           PN,
                                                           Descripcion,
                                                           TipoProducto,
                                                           Codigo,
                                                           IdUnidadMedida,
                                                           IdTipoProducto,
                                                           Reparable
                                              )
                                      VALUES(v_IdProducto::integer,
                                             		v_IdProductoPN::integer,
                                                    v_PN::varchar,
                                                    v_DescripcionPN::varchar,
                                                    v_TipoProductoPN::varchar,
                                                    v_Codigo_UM_PN::varchar,
                                                    v_Id_UM_PN::integer,
                                                    v_Id_TipoProducto_PN::integer,
                                                    v_Reparable::varchar
                                             );

                  end loop;

                  v_consulta:='select 			part.IdProducto ,
                                                         part.IdProductoPN ,
                                                         part.PN,
                                                         part.Descripcion,
                                                          (CASE
                                                                 WHEN (part.TipoProducto = ''Consumables/Expendables'')  THEN
                                                                 	 ''Consumibles''
                                                                  WHEN (part.TipoProducto = ''Rotables'')  THEN
                                                                 	''Rotables''
                                                          END)::varchar as TipoProducto,
                                                         part.Codigo as codigo_unidad_medida,
                                                         part.IdUnidadMedida,
                                                         part.IdTipoProducto,
                                                         (CASE
                                                         		WHEN part.Reparable = ''true'' THEN
                                                                	''SI''
                                                                ELSE
                                                                	''NO''
                                                         END)::varchar as Reparable
                               from comboPartNumber part';


              v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

              return v_consulta;
      end;

      /*********************************
      #TRANSACCION:  'MAT_COMB_PARNUM_CONT'
      #DESCRIPCION:	Recuperamos los combos de Alkym
      #AUTOR:	 Ismael Valdivia
      #FECHA:		24/06/2021
      ***********************************/
      elsif(p_transaccion='MAT_COMB_PARNUM_CONT')then
          begin
 CREATE TEMP TABLE comboPartNumber (  IdProducto  int4,
                                                                                           IdProductoPN  int4,
                                                                                           PN varchar,
                                                                                           Descripcion varchar,
                                                                                           TipoProducto varchar,
                                                                                           Codigo varchar,
                                                                                           IdUnidadMedida INTEGER,
                                                                                           IdTipoProducto	INTEGER,
                                                                                           Reparable	varchar
                                                                                      )ON COMMIT DROP;


                  if (v_parametros.cantidad_json is not null) then
                        v_contador = v_parametros.cantidad_json;
                  end if;

                  for i in 0..(v_contador-1) loop

                    v_IdProducto = v_parametros.json_obtenido->i->>'IdProducto';
                    v_IdProductoPN = v_parametros.json_obtenido->i->>'IdProductoPN';
                    v_PN = v_parametros.json_obtenido->i->>'PN';
                    v_DescripcionPN= v_parametros.json_obtenido->i->>'Descripcion';
                    v_TipoProductoPN = v_parametros.json_obtenido->i->>'TipoProducto';

                     v_Codigo_UM_PN = v_parametros.json_obtenido->i->>'Codigo';
                     v_Id_UM_PN = v_parametros.json_obtenido->i->>'IdUnidadMedida';
                     v_Id_TipoProducto_PN = v_parametros.json_obtenido->i->>'IdTipoProducto';
                     v_Reparable = v_parametros.json_obtenido->i->>'Reparable';

                    insert into comboPartNumber (IdProducto ,
                                                           IdProductoPN ,
                                                           PN,
                                                           Descripcion,
                                                           TipoProducto,
                                                           Codigo,
                                                           IdUnidadMedida,
                                                           IdTipoProducto,
                                                           Reparable
                                              )
                                      VALUES(v_IdProducto::integer,
                                             		v_IdProductoPN::integer,
                                                    v_PN::varchar,
                                                    v_DescripcionPN::varchar,
                                                    v_TipoProductoPN::varchar,
                                                    v_Codigo_UM_PN::varchar,
                                                    v_Id_UM_PN::integer,
                                                    v_Id_TipoProducto_PN::integer,
                                                    v_Reparable::varchar
                                             );

                  end loop;


            v_consulta:='select count(part.IdProducto)
                         from comboPartNumber part';

              raise notice 'v_consulta %',v_consulta;
              return v_consulta;
      end;

      /*********************************
 	#TRANSACCION:  'MAT_REP_NOT_ADJU_SEL'
 	#DESCRIPCION:	Reporte Nota de Adjudicacion
 	#AUTOR:	 Ismael Valdivia
 	#FECHA:		28/06/2020
	***********************************/
    elsif(p_transaccion='MAT_REP_NOT_ADJU_SEL')then
		begin

        select
                   pro.rotulo_comercial,
                   ('OB.DAB.REP.'||sol.nro_po||'.'||ge.gestion)::varchar as informe,
                   sol.nro_po,
                   ge.gestion,
                   sol.nro_lote,
                   sol.fecha_entrega,
                   sol.id_solicitud,
                   (select sum(det.cantidad_sol *  det.precio_unitario)
                  from mat.tdetalle_sol det
                  where det.id_solicitud = sol.id_solicitud) as total_venta,
                  sol.estado,
                  sol.nro_tramite,
                  sol.fecha_po

                  into

                  v_rotulo_proveedor,
                  v_informe_rep,
                  v_nro_rep,
                  v_gestion_rep,
                  v_lote_rep,
                  v_fecha_entrega_rep,
                  v_id_solicitud_rep,
                  v_total_venta_rep,
                  v_estado_actual,
                  v_nro_tramite,
                  v_fecha_po_rep


      from mat.tsolicitud sol
      left join param.vproveedor2 pro on pro.id_proveedor = sol.id_proveedor
      inner join param.tgestion ge on ge.id_gestion = sol.id_gestion
      where sol.id_proceso_wf = v_parametros.id_proceso_wf;


      select count(det.cd) into v_existe_bear
      from mat.tcotizacion cot
      inner join mat.tcotizacion_detalle det on det.id_cotizacion = cot.id_cotizacion and cot.adjudicado = 'si'
      where cot.id_solicitud = v_id_solicitud_rep
      and det.cd = 'B.E.R.';

	  if (v_existe_bear > 0) then
      	v_tiene_bear = 'SI';
      else
      	v_tiene_bear = 'NO';
      end if;
      /*SELECT sol.fecha_cotizacion
      	     into
             v_fecha_cotizacion_rep
      FROM mat.tsolicitud sol
      inner join mat.tcotizacion cot on cot.id_solicitud = sol.id_solicitud and cot.adjudicado = 'si'
      WHERE sol.id_proceso_wf = v_parametros.id_proceso_wf;*/


      /*Cambiando para recuperar la fecha del proceso wf*/

      select
              to_char(sou.fecha_po,'DD/MM/YYYY')as fechapo,
              to_char(sou.fecha_solicitud,'DD/MM/YYYY')as fechasol,
              sou.id_proceso_wf_firma,
              sou.estado_firma,
              sou.estado,
              sou.nro_tramite,
              sou.tipo_evaluacion
              into
              v_fecha_po,
              v_fecha_solicitud,
              v_id_proceso_wf_firma,
              v_estado_firma_paralelo,
              v_estado_actual,
              v_nro_tramite,
              v_tipo_evaluacion
      from mat.tsolicitud sou
      where sou.id_proceso_wf = v_parametros.id_proceso_wf;



      /*Aqui recuperamos la Firma del encargado del comite de aeronavegabilidad*/
       if (v_estado_firma_paralelo = 'autorizado') then

                SELECT twf.fecha_reg into v_fecha_firma_dc_qr
                FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND
                      te.codigo = 'autorizado'
                order by twf.fecha_reg desc
                limit 1;
        /*************************************************************************/
       end if;




      /*Aqui recuperamos la Firma del encargado del comite de Unidad de abastecimiento*/
      if (v_estado_actual != 'comite_unidad_abastecimientos') then

            SELECT twf.fecha_reg into v_fecha_firma_rev_qr
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND
                  te.codigo = 'autorizado'
            order by twf.fecha_reg desc
            limit 1; --comite abastecimiento
      end if;
      /*********************************************************************************************************************************/



      /*****************************************************/
      v_fecha_cotizacion_rep = '';
      if (v_fecha_firma_dc_qr is not null and v_fecha_firma_rev_qr is not null) THEN
          if (v_fecha_firma_dc_qr::date > v_fecha_firma_rev_qr::date) then
              v_fecha_cotizacion_rep = to_char(v_fecha_firma_dc_qr::date,'DD/MM/YYYY');
          elsif (v_fecha_firma_rev_qr::date > v_fecha_firma_dc_qr::date) then
              v_fecha_cotizacion_rep = to_char(v_fecha_firma_abas_qr::date,'DD/MM/YYYY');
          elsif (v_fecha_firma_rev_qr::date = v_fecha_firma_dc_qr::date) then
              v_fecha_cotizacion_rep = to_char(v_fecha_firma_rev_qr::date,'DD/MM/YYYY');
          end if;
      end if;


            if (v_estado_actual != 'vb_rpcd') then
        	SELECT  twf.id_funcionario,
                            twf.fecha_reg
                          into  v_rpcd
                    FROM wf.testado_wf twf
                    INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                    INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                    WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                    AND te.codigo = 'vb_rpcd'
                    GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;

            	  if (v_fecha_po_rep is not null)then

                          SELECT  twf.id_funcionario,
                                  vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | '||to_char(twf.fecha_reg,'DD-MM-YYYY')||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                                  vf.desc_funcionario1,
                                  to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                            INTO  v_id_funcionario_rpcd_oficial,
                                  v_funcionario_sol_rpcd_oficial,
                                  v_funcionario_rpcd_oficial,
                                  v_fecha_firma_rpcd_pru
                          FROM wf.testado_wf twf
                          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                          INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
                          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                          AND te.codigo = 'vb_rpcd'
                          and v_fecha_po_rep::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
                          GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg
                          ORDER BY twf.fecha_reg DESC
                          Limit 1;

                          if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
                              remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_rpcd_oficial,v_fecha_po_rep::varchar);

                              if(remplaso is null)THEN
                                  v_funcionario_sol_rpcd_oficial = v_funcionario_sol_rpcd_oficial;
                              else
                                      v_funcionario_sol_rpcd_oficial = remplaso.desc_funcionario1;
                              end if;
                          else
                              v_funcionario_sol_rpcd_oficial = v_funcionario_sol_rpcd_oficial;
                          end if;

        		else
                	v_funcionario_sol_rpcd_oficial = '';
                end if;
            end if;

            if (v_fecha_entrega_rep is null )then
                v_fecha_entrega_rep = '';
            end if;

            if (v_fecha_po_rep is null) then
            	v_fecha_po_rep = '';
            end if;

			IF (v_fecha_po_rep != '') THEN
            v_fecha_literal = (to_char(v_fecha_po_rep::date,'DD')::integer || ' de ' ||param.f_literal_periodo(to_char(v_fecha_po_rep::date,'MM')::integer + 1) || ' del ' || to_char(v_fecha_po_rep::date,'YYYY'))::varchar;
			ELSE
            v_fecha_literal = '';
            END IF;

            IF (v_fecha_cotizacion_rep is null) THEN
            	v_fecha_cotizacion_rep = '';
            else
            	v_fecha_cotizacion_rep = to_char(v_fecha_cotizacion_rep::date,'DD/MM/YYYY')::Varchar;
            END IF;

      		v_consulta:='select
          			  ('''||v_rotulo_proveedor||''')::varchar as proveedor,
           			  ('''||v_informe_rep||''')::varchar as informe_rep,
                      ('''||v_nro_rep||''')::varchar as nro_rep,
                      ('||v_gestion_rep||')::numeric as gestion_rep,
                      ('''||v_lote_rep||''')::varchar as lote_rep,
                      ('''||v_fecha_entrega_rep||''')::varchar as fecha_entrega,
                      ('||v_id_solicitud_rep||')::numeric as id_solicitud_rep,
                      ('||v_total_venta_rep||')::numeric as total_venta_rep,
                      ('''||v_funcionario_sol_rpcd_oficial||''')::varchar as firma_rpc,
                      ('''||v_nro_tramite||''')::varchar as nro_tramite,
                      ('''||v_fecha_firma_rpcd_pru||''')::varchar as fecha_firma,
                      ('''||v_fecha_literal||''')::varchar as fecha_literal,
                      ('''||v_fecha_cotizacion_rep||''')::varchar as fecha_cotizacion,
                      ('''||v_tipo_evaluacion||''')::varchar as tipo_evaluacion,
                      ('''||v_tiene_bear||''')::varchar as tiene_bear,
                      ('''||COALESCE(v_fecha_po,'')||''')::varchar as fecha_po';

            raise notice 'v_consulta %',v_consulta;
			return v_consulta;
	end;


    	/*********************************
        #TRANSACCION:  'MAT_GET_ID_PWF'
        #DESCRIPCION:	Consulta de datos
        #AUTOR:		ISMAEL VALDIVIA
        #FECHA:		02-07-2021 07:30:00
        ***********************************/
        elsif(p_transaccion='MAT_GET_ID_PWF')then
            begin

                v_consulta = '  select sol.id_proceso_wf
                                      from mat.tsolicitud sol
                                      where sol.nro_tramite = '''||v_parametros.nro_tramite::varchar||'''';
                return v_consulta;
            end;





else

		raise exception 'Transaccion inexistente';

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

ALTER FUNCTION mat.ft_solicitud_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
