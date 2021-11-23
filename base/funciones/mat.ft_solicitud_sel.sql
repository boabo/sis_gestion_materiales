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
    v_firmas			record;
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

    --reporte
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
    --agregado (breydi.vasquez) 28/11/2019
    v_vbgerencia						 record;
    v_vbrpc								 record;
    v_revision							 record;
	v_fecha_ini							 date;
BEGIN

	v_rango_fecha = '01/11/2018';

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
                    WHERE tu.id_usuario = p_id_usuario and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion);
--raise exception 'v_record: %', v_record;
         	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN
             v_historico =  v_parametros.historico;
            ELSE
            v_historico = 'no';
            END IF;




--raise exception 'cargo: %, interfaz: % ', v_record.nombre_cargo, v_parametros.tipo_interfaz;

            IF 	p_administrador THEN
                    v_filtro = ' 0=0 AND ';
                ELSIF (v_parametros.tipo_interfaz = 'VistoBueno') THEN

                    v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND ewb.estado_reg = ''activo'' AND ';


                    ELSIF (v_parametros.tipo_interfaz =  'PedidoOperacion' or v_parametros.tipo_interfaz = 'PedidoMantenimiento' or v_parametros.tipo_interfaz ='PerdidoAlmacen' or v_parametros.tipo_interfaz ='PedidoDgac')THEN
                            /*IF (v_parametros.pes_estado = 'pedido_ma_compra' or v_parametros.pes_estado = 'pedido_ma_concluido') then
                                v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                            ELSIF v_parametros.pes_estado = 'pedido_re_comite' THEN
                                v_filtro = '';
                            ELSE*/
                            if (v_historico = 'si') then
                            	v_filtro = '';
                            else
                            	v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304,307) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                            end if;


                           -- END IF;
                   ELSIF (v_parametros.tipo_interfaz = 'SolicitudvoboComite') THEN

                  v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND tew.estado_reg = ''activo'' AND ';
                        ELSIF  (v_parametros.tipo_interfaz = 'ProcesoCompra')THEN
                                v_filtro = '';
                        ELSIF  (v_parametros.tipo_interfaz = 'Almacen')THEN
                            v_filtro = '';
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
                        v_filtro = 'tew.id_funcionario ='||p_id_usuario||' OR ewb.id_funcionario ='||p_id_usuario||' and ';
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
                                pa.observaciones as obs_pac

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
                                where '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
           -- raise exception 't';
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
           /* SELECT		tf.id_funcionario,
 					fun.desc_funcionario1,
                    fun.nombre_cargo
                    INTO
                    v_record
                    FROM segu.tusuario tu
                    INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                    INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = tf.id_funcionario
                    WHERE tu.id_usuario = p_id_usuario and fun.fecha_finalizacion is null;*/

        	SELECT		tf.id_funcionario,
 					fun.desc_funcionario1,
                    fun.nombre_cargo
                    INTO
                    v_record
                    FROM segu.tusuario tu
                    INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                    INNER JOIN orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = tf.id_funcionario
                    WHERE tu.id_usuario = p_id_usuario and (fun.fecha_finalizacion is null or current_date <= fun.fecha_finalizacion);


         	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN
             v_historico =  v_parametros.historico;
            ELSE
            v_historico = 'no';
            END IF;






        /*IF 	p_administrador THEN
				v_filtro = ' 0=0 AND ';
        ELSIF (v_parametros.tipo_interfaz = 'VistoBueno') THEN

               IF(v_record.nombre_cargo ='Gerente Mantenimiento') THEN


                    select  fun.id_funcionario,
              count(fun.id_funcionario)::varchar as cant_reg
                            into
                            v_id_usuario_rev
                            from wf.testado_wf es
                            inner join orga.vfuncionario_cargo fun on fun.id_funcionario = es.id_funcionario
                            inner join wf.ttipo_estado te on te.id_tipo_estado = es.id_tipo_estado
                            where te.codigo = 'vobo_area' and fun.nombre_cargo ='Gerente Mantenimiento'
                            group by fun.id_funcionario;


                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND  ';
                ELSE
                v_filtro = 'ewb.id_funcionario = '||v_id_usuario_rev.id_funcionario|| 'AND';
              END IF;

                ELSIF(v_record.nombre_cargo ='Especialista Planificación Servicios') THEN

                select  fun.id_funcionario,
            count(fun.id_funcionario)::varchar as cant_reg
                        into
                      v_id_usuario_rev
                        from wf.testado_wf es
                        inner join orga.vfuncionario_cargo fun on fun.id_funcionario = es.id_funcionario
                        inner join wf.ttipo_estado te on te.id_tipo_estado = es.id_tipo_estado
                        where te.codigo = 'vobo_area' and fun.nombre_cargo ='Especialista Planificación Servicios'
                        group by fun.id_funcionario;


                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND  ';
                ELSE
                v_filtro = 'ewb.id_funcionario = '||v_id_usuario_rev.id_funcionario|| 'AND';
                END IF;
                ELSIF(v_record.nombre_cargo ='Jefe Departamento Gestion Aeronavegabilidad Continua' OR  v_record.nombre_cargo ='Jefe Ingenieria Avionica / Sistemas') THEN
                  select  fun.id_funcionario,
              count(fun.id_funcionario)::varchar as cant_reg
                             into
                        v_id_usuario_rev
                            from wf.testado_wf es
                            inner join orga.vfuncionario_cargo fun on fun.id_funcionario = es.id_funcionario
                            inner join wf.ttipo_estado te on te.id_tipo_estado = es.id_tipo_estado
                            where te.codigo = 'vobo_aeronavegabilidad' and (fun.nombre_cargo ='Jefe Departamento Gestion Aeronavegabilidad Continua' OR  fun.nombre_cargo ='Jefe Ingenieria Avionica / Sistemas')
                            group by fun.id_funcionario;

                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND  ';
                ELSE
                v_filtro = 'ewb.id_funcionario = '||v_id_usuario_rev.id_funcionario|| 'AND';
                END IF;
                ELSIF(v_record.nombre_cargo ='Jefe Departamento Abastecimientos y Logistica') THEN
                select  fu.id_funcionario,
            count(fu.id_funcionario)::varchar as cant_reg
                into
                      v_id_usuario_rev
                    from wf.testado_wf es
                    inner join orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                    inner join segu.tusuario u on u.id_persona = fu.id_persona
                    inner join mat.tsolicitud  so ON so.id_estado_wf_firma = es.id_estado_wf
                    left join wf.testado_wf te ON te.id_estado_anterior = es.id_estado_wf
                    WHERE   so.estado_firma = 'vobo_dpto_abastecimientos'
                    GROUP BY fu.id_funcionario;
                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND  ';
                ELSE
                v_filtro = 'ewb.id_funcionario = '||v_id_usuario_rev.id_funcionario|| 'AND';
              END IF;
              END IF;
                ELSIF (v_parametros.tipo_interfaz =  'PedidoOperacion' or v_parametros.tipo_interfaz = 'PedidoMantenimiento' or v_parametros.tipo_interfaz ='PerdidoAlmacen' or v_parametros.tipo_interfaz ='PedidoDgac')THEN
						  IF(v_record.nombre_cargo = 'Técnico Revision Procesos' ) THEN
                         select u.id_usuario,
                        count(u.id_usuario)::varchar as cant_reg
                    into
                                v_id_usuario_rev
                                from wf.testado_wf es
                                inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                                inner join segu.tusuario u on u.id_persona = fu.id_persona
                                inner join orga.vfuncionario_cargo fc on fc.id_funcionario =es.id_funcionario and fc.fecha_finalizacion is null
                                inner JOIN mat.tsolicitud  so ON so.id_estado_wf = es.id_estado_wf
                                WHERE so.estado in('cotizacion','cotizacion_solicitada','cotizacion_sin_respuesta','compra') and fc.nombre_cargo = 'Técnico Revision Procesos'
                                GROUP BY u.id_usuario;
                                  IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                        v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
                      ELSE
                        v_filtro = '(tew.id_funcionario in (1951, 1950,69,302,373,303, 304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                      END IF;
                        END IF;
                        IF(v_record.nombre_cargo = 'Auxiliar Suministros' or  v_record.nombre_cargo = 'Técnico Control Gestión y Desarrollo Organizacional') THEN
                         select u.id_usuario,
                        count(u.id_usuario)::varchar as cant_reg
                    into
                                v_id_usuario_rev
                                from wf.testado_wf es
                                inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                                inner join segu.tusuario u on u.id_persona = fu.id_persona
                                inner join orga.vfuncionario_cargo fc on fc.id_funcionario =es.id_funcionario and fc.fecha_finalizacion is null
                                inner JOIN mat.tsolicitud  so ON so.id_estado_wf = es.id_estado_wf
                                WHERE so.estado in('cotizacion','cotizacion_solicitada','cotizacion_sin_respuesta','compra') and fc.nombre_cargo = 'Auxiliar Suministros'
                                GROUP BY u.id_usuario;
                                  IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                        v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
                      ELSE
                        v_filtro = '(tew.id_funcionario in (1951, 1950,69,302,373,303, 304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                      END IF;
                        END IF;
                        IF(v_record.nombre_cargo = 'Analista II Presupuestos' or v_record.nombre_cargo = 'Profesional Abastecimientos') THEN
                         select u.id_usuario,
                        count(u.id_usuario)::varchar as cant_reg
                    into
                                v_id_usuario_rev
                                from wf.testado_wf es
                                inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                                inner join segu.tusuario u on u.id_persona = fu.id_persona
                                inner join orga.vfuncionario_cargo fc on fc.id_funcionario =es.id_funcionario and fc.fecha_finalizacion is null
                                inner JOIN mat.tsolicitud  so ON so.id_estado_wf = es.id_estado_wf
                                WHERE so.estado in('cotizacion','cotizacion_solicitada','cotizacion_sin_respuesta','compra') and fc.nombre_cargo in ('Analista II Presupuestos','Profesional Abastecimientos')
                                GROUP BY u.id_usuario;
                                  IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                        v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
                      ELSE
                        v_filtro = '(tew.id_funcionario in (1951, 1950,69,302,373,303, 304) OR tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                      END IF;
                        END IF;
                        IF(v_record.nombre_cargo = 'Técnico Adquisiciones' ) THEN
                         select u.id_usuario,
                        count(u.id_usuario)::varchar as cant_reg
                    into
                                v_id_usuario_rev
                                from wf.testado_wf es
                                inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                                inner join segu.tusuario u on u.id_persona = fu.id_persona
                                inner join orga.vfuncionario_cargo fc on fc.id_funcionario =es.id_funcionario and fc.fecha_finalizacion is null
                                inner JOIN mat.tsolicitud  so ON so.id_estado_wf = es.id_estado_wf
                                WHERE so.estado in('cotizacion','cotizacion_solicitada','cotizacion_sin_respuesta','compra') and fc.nombre_cargo = 'Técnico Adquisiciones'
                                GROUP BY u.id_usuario;
                                  IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                        v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
                      ELSE
                        v_filtro = '(tew.id_funcionario in (1951, 1950,69,302,373,303, 304)  OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                      END IF;
                        END IF;
               ------
               ELSIF (v_parametros.tipo_interfaz = 'SolicitudvoboComite') THEN

              IF(v_record.nombre_cargo ='Jefe Abastecimientos y Suministros') THEN
                  select  fun.id_funcionario,
                    count(fun.id_funcionario)::varchar as cant_reg
                          into
                        v_id_usuario_rev
                        from wf.testado_wf es
                        inner join orga.vfuncionario_cargo fun on fun.id_funcionario = es.id_funcionario
                        inner join wf.ttipo_estado te on te.id_tipo_estado = es.id_tipo_estado
                        where te.codigo = 'comite_unidad_abastecimientos' and fun.nombre_cargo ='Jefe Abastecimientos y Suministros'
                        group by fun.id_funcionario;

                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                        v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
                    ELSE
                       v_filtro = 'tew.id_funcionario = '||v_id_usuario_rev.id_funcionario|| 'AND';
                    END IF;
               END IF;

               IF(v_record.nombre_cargo ='Jefe Departamento Gestion Aeronavegabilidad Continua' OR  v_record.nombre_cargo ='Jefe Ingenieria Avionica / Sistemas') THEN

                    select  fun.id_funcionario,
                    count(fun.id_funcionario)::varchar as cant_reg
                          into
                          v_id_usuario_rev
                          from wf.testado_wf es
                          inner join orga.vfuncionario_cargo fun on fun.id_funcionario = es.id_funcionario
                          inner join wf.ttipo_estado te on te.id_tipo_estado = es.id_tipo_estado
                          where te.codigo = 'comite_aeronavegabilidad' and (fun.nombre_cargo ='Jefe Departamento Gestion Aeronavegabilidad Continua' or fun.nombre_cargo='Jefe Ingenieria Avionica / Sistemas')
                          group by fun.id_funcionario;
                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                        v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
                    ELSE
                        v_filtro = 'tew.id_funcionario = '||v_id_usuario_rev.id_funcionario|| 'AND';
                    END IF;
               END IF;
              IF(v_record.nombre_cargo ='Jefe Departamento Abastecimientos y Logistica') THEN

                 select   fun.id_funcionario,
                    count(fun.id_funcionario)::varchar as cant_reg
                          into
                        v_id_usuario_rev
                        from wf.testado_wf es
                        inner join orga.vfuncionario_cargo fun on fun.id_funcionario = es.id_funcionario
                        inner join wf.ttipo_estado te on te.id_tipo_estado = es.id_tipo_estado
                        where te.codigo = 'comite_dpto_abastecimientos' and fun.nombre_cargo ='Jefe Departamento Abastecimientos y Logistica'
                        group by fun.id_funcionario;
                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                        v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
                      ELSE
                       v_filtro = 'tew.id_funcionario = '||v_id_usuario_rev.id_funcionario|| 'AND';
                      END IF;


                 END IF;
                -------

                IF(v_record.nombre_cargo ='Jefe Departamento Centro Entrenamiento Aeronautico Civil') THEN

                 select   fun.id_funcionario,
                    count(fun.id_funcionario)::varchar as cant_reg
                          into
                        v_id_usuario_rev
                        from wf.testado_wf es
                        inner join orga.vfuncionario_cargo fun on fun.id_funcionario = es.id_funcionario
                        inner join wf.ttipo_estado te on te.id_tipo_estado = es.id_tipo_estado
                        where te.codigo = 'departamento_ceac' and fun.nombre_cargo ='Jefe Departamento Centro Entrenamiento Aeronautico Civil'
                        group by fun.id_funcionario;
                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                        v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
                      ELSE
                       v_filtro = 'tew.id_funcionario = '||v_id_usuario_rev.id_funcionario|| 'AND';
                      END IF;
					   END IF;
    				ELSIF  (v_parametros.tipo_interfaz = 'ProcesoCompra')THEN
          					v_filtro = '';
                    ELSIF  (v_parametros.tipo_interfaz = 'Almacen')THEN
                        v_filtro = '';
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
            END IF;*/

            IF 	p_administrador THEN
                    v_filtro = ' 0=0 AND ';
                ELSIF (v_parametros.tipo_interfaz = 'VistoBueno') THEN

                    v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND ewb.estado_reg = ''activo'' AND ';


                    ELSIF (v_parametros.tipo_interfaz =  'PedidoOperacion' or v_parametros.tipo_interfaz = 'PedidoMantenimiento' or v_parametros.tipo_interfaz ='PerdidoAlmacen' or v_parametros.tipo_interfaz ='PedidoDgac')THEN
                            /*IF (v_parametros.pes_estado = 'pedido_ma_compra' or v_parametros.pes_estado = 'pedido_ma_concluido') then
                                v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                            ELSIF v_parametros.pes_estado = 'pedido_re_comite' THEN
                                v_filtro = '';
                            ELSE*/
                                --v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                            --END IF;
                            if (v_historico = 'si') then
                            	v_filtro = '';
                            else
                            	v_filtro = '(tew.id_funcionario in (1951,1950,69,302,373,303,304,307) OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
                            end if;
                   ELSIF (v_parametros.tipo_interfaz = 'SolicitudvoboComite') THEN

                  v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND tew.estado_reg = ''activo'' AND ';
                        ELSIF  (v_parametros.tipo_interfaz = 'ProcesoCompra')THEN
                                v_filtro = '';
                        ELSIF  (v_parametros.tipo_interfaz = 'Almacen')THEN
                            v_filtro = '';
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
                        v_filtro = 'tew.id_funcionario ='||p_id_usuario||' OR ewb.id_funcionario ='||p_id_usuario||' and ';
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
                                where '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			--Devuelve la respuesta
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
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select ord.id_orden_trabajo,
                          		split_part(ord.desc_orden ,'' '',2) ||'' ''||  split_part(ord.desc_orden :: text,'' '',3):: text as matricula,
       					 		ord.desc_orden
                                from conta.torden_trabajo ord
								inner join conta.tgrupo_ot_det gr on gr.id_orden_trabajo = ord.id_orden_trabajo and gr.id_grupo_ot IN( 1,4)
							    where ';

            --Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
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
    			--Sentencia de la consulta de conteo de registros
			v_consulta:='select  	f.id_funcionario,
        							p.nombre_completo1,
									uo.nombre_cargo
 									from orga.tfuncionario f
                                    inner join segu.vpersona p on p.id_persona= f.id_persona
                                    inner JOIN orga.tuo_funcionario uof on uof.id_funcionario = f.id_funcionario
                                    inner JOIN orga.tuo uo on  uo.id_uo = uof.id_uo and uo.estado_reg = ''activo''
                                    inner  JOIN orga.tcargo car on car.id_cargo = uof.id_cargo
                                    where ';

            --Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
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
			v_consulta:='select
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
                                sol.fecha_solicitud as fecha_soli

          						from mat.tsolicitud sol
                                inner join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud and de.estado_reg = ''activo''
                                left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                                inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                                inner join wf.testado_wf wof on wof.id_estado_wf = sol.id_estado_wf
                                inner join wf.ttipo_estado ti on ti.id_tipo_estado = wof.id_tipo_estado
                                inner join mat.tunidad_medida un on un.id_unidad_medida = de.id_unidad_medida
                                where sol.id_proceso_wf='||v_parametros.id_proceso_wf;
			--Devuelve la respuesta
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
          INNER JOIN orga.vfuncionario vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND te.codigo = 'vobo_area' GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg;

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
          INNER JOIN orga.vfuncionario vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_id_proceso_wf_firma AND te.codigo = 'vobo_aeronavegabilidad' GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg;

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
          INNER JOIN orga.vfuncionario vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'revision' GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg;

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
		SELECT
          		  vf.desc_funcionario1
                  into v_nombre_funcionario_ag_qr
          FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_tipo_estado = 992
          GROUP BY twf.id_funcionario, vf.desc_funcionario1;
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
            --Devuelve la respuesta

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

			--Devuelve la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||'ORDER BY nro_tramite, s.nro_tramite';
            --v_consulta:=v_consulta||' order by ';

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

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
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

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
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

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
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
        --Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
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
        select to_char(sou.fecha_po,'DD/MM/YYYY')as fechapo, to_char(sou.fecha_solicitud,'DD/MM/YYYY')as fechasol
        into
        v_fecha_po,
        v_fecha_solicitud
        from mat.tsolicitud sou
        where sou.id_proceso_wf = v_parametros.id_proceso_wf;

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
          		INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
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
                and (vf.fecha_finalizacion >= v_fecha_solicitud::date OR vf.fecha_finalizacion is null)
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
          	WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_dpto_abastecimientos' and vf.fecha_finalizacion is null
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
                    and (vf.fecha_finalizacion >= v_fecha_solicitud::date OR vf.fecha_finalizacion is null)
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
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_unidad_abastecimientos'and vf.fecha_finalizacion is null
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
        		WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbrpc' and ( vf.fecha_finalizacion is null or vf.fecha_finalizacion >= now())
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
        		WHERE twf.id_proceso_wf = v_id_proceso_wf_adq AND te.codigo = 'vbrpc'and ( vf.fecha_finalizacion is null or vf.fecha_finalizacion >= now())
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
                  AND( vf.fecha_finalizacion is null or vf.fecha_finalizacion >= now())
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
                 AND( vf.fecha_finalizacion is null or vf.fecha_finalizacion >= now())
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
initcap(pxp.f_convertir_num_a_letra( mat.f_id_detalle_cotizacion(c.id_cotizacion)))::varchar as item_selecionados,                                s.nro_tramite,
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
                                where ng.id_solicitud = s.id_solicitud) ::integer as cotizacion_solicitadas,
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
                                s.estado as estado_materiales,
                               d.nro_parte_cot::varchar,
                               d.descripcion_cot::varchar,
                               d.cantidad_det,
                               d.cd,
							   da.codigo_tipo,
                               '''||COALESCE (initcap(v_nombre_funcionario_resp_qr),' ')||'''::varchar as funcionario_resp,
                               '''||COALESCE (v_fecha_firma_resp_qr,' ')||'''::text as fecha_resp,
                               s.fecha_solicitud::date

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
                          det.precio,
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
                          s.condicion,
                          s.lugar_entrega
                          from mat.tdetalle_sol det
                          inner join segu.tusuario usu1 on usu1.id_usuario = det.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = det.id_usuario_mod
                          inner join mat.tunidad_medida un on un.id_unidad_medida = det.id_unidad_medida
                          inner join mat.tsolicitud s on s.id_solicitud = det.id_solicitud and det.estado_reg = ''activo''
                          where s.id_proceso_wf = '||v_parametros.id_proceso_wf;
        	--Devuelve la respuesta
            raise notice 'v_consulta %',v_consulta;
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
--raise exception 'Los datos enviados proceso, fecha, id funcionarioson:';
        SELECT ts.nro_cite_cobs, ts.nro_tramite, to_char(ts.fecha_solicitud, 'DD/MM/YYYY')as fechasol
        INTO v_nro_cite_dce, v_num_tramite, v_fecha_solicitud
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
          END IF;

          UPDATE mat.tsolicitud SET
          nro_cite_cobs = v_nro_cite_dce
          WHERE id_proceso_wf = v_parametros.id_proceso_wf;
        END IF;

    -- modificado, funcionario cargo respecto al visto bueno en el workflow (breydi.vasquez) 28/11/2019
      SELECT  twf.id_funcionario,
              twf.fecha_reg
            into  v_revision
      FROM wf.testado_wf twf
      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
      WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
      AND te.codigo = 'revision'
	  GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;

	-- fin modif 28/11/2019


    if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
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
          and v_revision.fecha_reg between vf.fecha_asignacion and  coalesce(now(), vf.fecha_finalizacion)
           GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, fecha_firma;

  		remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_oficial,v_fecha_solicitud);
    else
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
          --and vf.fecha_finalizacion is null
          --21-04-2021 (may) modificacion coalesce al reves coalesce(vf.fecha_finalizacion,now())
          and v_revision.fecha_reg between vf.fecha_asignacion and  coalesce(now(), vf.fecha_finalizacion)
           GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo, fecha_firma;


  		remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_oficial);
    end if;

      if(remplaso is null)THEN

              v_funcionario_sol = v_funcionario_sol_oficial;
              v_funcionario     = v_funcionario_oficial;
      else
              v_funcionario_sol = remplaso.desc_funcionario1;
              v_funcionario	    = remplaso.funcion;

      end if;

          WITH RECURSIVE gerencia(id_uo, id_nivel_organizacional, nombre_unidad, nombre_cargo) AS (
              SELECT tu.id_uo, tu.id_nivel_organizacional, tu.nombre_unidad, tu.nombre_cargo
              FROM orga.tuo  tu
              INNER JOIN orga.tuo_funcionario tf ON tf.id_uo = tu.id_uo
              WHERE tf.id_funcionario = v_id_funcionario_oficial
              /*Se adiciono la fecha para tomar en cuenta en caso que el funcionario este inactivo*/
              AND v_fecha_solicitud::date between tf.fecha_asignacion and tf.fecha_finalizacion
              --and tu.estado_reg = 'activo'

              UNION ALL

              SELECT teu.id_uo_padre, tu1.id_nivel_organizacional, tu1.nombre_unidad, tu1.nombre_cargo
              FROM orga.testructura_uo teu
              INNER JOIN gerencia g ON g.id_uo = teu.id_uo_hijo
              INNER JOIN orga.tuo tu1 ON tu1.id_uo = teu.id_uo_padre
              WHERE g.id_nivel_organizacional <> 3
          	)
            SELECT  pxp.aggarray( nombre_unidad )
            INTO v_nom_unidad
            FROM gerencia;

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


    -- modificado, funcionario cargo respecto al visto bueno en el workflow (breydi.vasquez) 28/11/2019
      SELECT  twf.id_funcionario,
              twf.fecha_reg
            into  v_vbgerencia
      FROM wf.testado_wf twf
      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
      WHERE twf.id_proceso_wf = v_id_proceso_wf_adq
      AND te.codigo = 'vbgerencia'
	  GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;

	-- fin modif 28/11/2019

  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
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
              and  v_vbgerencia.fecha_reg between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg;


  	remplaso = mat.f_firma_modif(v_id_proceso_wf_adq,v_id_funcionario_af_qr_oficial,v_fecha_solicitud);
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
        and  v_vbgerencia.fecha_reg between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,twf.fecha_reg;

  	remplaso = mat.f_firma_original(v_id_proceso_wf_adq, v_id_funcionario_af_qr_oficial);
  end if;

      if(remplaso is null)THEN

              v_nombre_funcionario_af_qr = v_nombre_funcionario_af_qr_ocifial;

      else
              v_nombre_funcionario_af_qr = remplaso.desc_funcionario1;

      end if;

    -- modificado, funcionario cargo respecto al visto bueno en el workflow (breydi.vasquez) 28/11/2019
      SELECT  twf.id_funcionario,
              twf.fecha_reg
            into  v_vbrpc
      FROM wf.testado_wf twf
      INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
      INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
      WHERE twf.id_proceso_wf = v_id_proceso_wf_adq
      AND te.codigo = 'vbrpc'
	  GROUP BY twf.id_funcionario ,pro.nro_tramite,twf.fecha_reg;

	-- fin modif 28/11/2019


  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
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
            and v_vbrpc.fecha_reg between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        	GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,pro.nro_tramite,twf.fecha_reg;

  	remplaso = mat.f_firma_modif(v_id_proceso_wf_adq,v_id_funcionario_presu_qr_oficial,v_fecha_solicitud);
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
            and v_vbrpc.fecha_reg between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
        	GROUP BY twf.id_funcionario, vf.desc_funcionario1,te.codigo,vf.nombre_cargo,twf.fecha_reg;

  	remplaso = mat.f_firma_original(v_id_proceso_wf_adq, v_id_funcionario_presu_qr_oficial);
  end if;

      if(remplaso is null)THEN

              v_nombre_funcionario_presu_qr = v_nombre_funcionario_presu_qr_oficial;

      else
              v_nombre_funcionario_presu_qr = remplaso.desc_funcionario1;

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

          v_consulta='select
           				  '''||COALESCE(v_nom_unidad[1],'')||'''::varchar AS unidad_sol,
                          '''||COALESCE (v_nom_unidad[3],v_nom_unidad[2])||'''::varchar AS gerencia,
                          '''||COALESCE (v_funcionario_sol,'')||'''::varchar AS funcionario_sol,
                          '''||COALESCE(v_nombre_funcionario_af_qr,' ')||'''::varchar AS funcionario_adm,
                          '''||COALESCE(v_nombre_funcionario_presu_qr,' ')||'''::varchar AS funcionario_pres,
                          '''||COALESCE(v_codigo_pre,' ')||'''::varchar AS codigo_pres,
                          COALESCE(array_length(pxp.aggarray(det.nro_parte),1),0)::integer AS nro_items,
                          COALESCE(tgp.adjudicado,''POR COTIZAR'')::varchar AS adjudicado,
                          s.motivo_solicitud::varchar,
                           --array_to_string(pxp.aggarray(det.nro_parte),'','')::varchar as nro_partes,
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
                           substring(s.nro_tramite from 1 for 2)::varchar as tipo_proceso
                          from mat.tsolicitud s
                          inner join mat.tdetalle_sol det on det.id_solicitud = s.id_solicitud and det.estado_reg = ''activo''
						  left join mat.tgestion_proveedores tgp ON tgp.id_solicitud = s.id_solicitud
                          left join mat.tcotizacion tc ON tc.id_solicitud = s.id_solicitud AND tc.adjudicado = ''si''
                          left join mat.tsolicitud_pac sp on sp.id_proceso_wf = s.id_proceso_wf
                          where s.id_proceso_wf = '||v_parametros.id_proceso_wf;


          v_consulta=v_consulta||' GROUP BY tgp.adjudicado,s.motivo_solicitud,s.fecha_solicitud, monto_ref,sp.observaciones,s.nro_tramite';
        	--Devuelve la respuesta
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
		           v_consulta:='select
                   				MAX((select pxp.list(po.email::text)
                                from param.vproveedor po
                                join mat.tgestion_proveedores_new pr on pr.id_proveedor = po.id_proveedor
                                where pr.id_solicitud = sol.id_solicitud))::varchar as lista_correos,

                                MAX(sol.mensaje_correo)::varchar,
                                MAX(ala.fecha_reg)::timestamp,
                                MAX(array_to_string(pcorreo.correos, '',''))::varchar as correos,
                                MAX(ala.titulo_correo)::varchar

                                from  mat.tsolicitud sol
                                left join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
                                left join segu.vusuario u on u.id_usuario = sol.id_usuario_mod
                                inner join param.talarma ala on ala.id_proceso_wf = sol.id_proceso_wf
                                inner join wf.tplantilla_correo pcorreo on pcorreo.id_plantilla_correo = ala.id_plantilla_correo

                                where sol.id_proceso_wf = '||v_parametros.id_proceso_wf;
			--Devuelve la respuesta

            raise notice 'consulta %',v_consulta;


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
