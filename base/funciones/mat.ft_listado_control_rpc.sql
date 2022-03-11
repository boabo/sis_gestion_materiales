CREATE OR REPLACE FUNCTION mat.ft_listado_control_rpc (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion Materiales
 FUNCION: 		mat.ft_listado_control_rpc
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'cola.tsucursal_video'
 AUTOR: 		 (Ismael Valdivia)
 FECHA:	        26-01-2022 10:15:34
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

    v_fill				varchar;
    v_fil_func			varchar;
    v_id_funcionario_recu	integer;
    v_existe_hazmat		numeric;
    v_registros			record;
	v_origen_pedido		varchar;
    v_existe_vacios		numeric;
    v_nom_cargo			varchar;
    v_fil_estado		varchar;
    v_join				varchar;
    v_ex_estado			varchar;
    v_join_2			varchar;
    v_existe_user		integer;
BEGIN

	v_nombre_funcion = 'mat.ft_listado_control_rpc';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_RPCE_REP_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Ismael.Valdivia
 	#FECHA:		08-08-2017 21:54:34
	***********************************/

	if(p_transaccion='MAT_RPCE_REP_SEL')then

    	begin
    		--Sentencia de la consulta

            /*Aumentando para listar solo del RPC Encargado*/
            if (p_administrador != 1) then
            	select fun.id_funcionario into v_id_funcionario_recu
                from segu.vusuario us
                inner join orga.vfuncionario fun on fun.id_persona = us.id_persona
                where us.id_usuario = p_id_usuario;

                /*Aumentando para verificar si el usuario ingresado es encargado RPC,Comite Aeronavegabilidad o Comite Abastecimientos*/
                select 1 into v_existe_user
                where v_id_funcionario_recu in (select
                                                   fe.id_funcionario
                                            from wf.ttipo_estado te
                                            inner join wf.tfuncionario_tipo_estado fe on fe.id_tipo_estado = te.id_tipo_estado
                                            inner join orga.vfuncionario fun on fun.id_funcionario = fe.id_funcionario
                                            where te.id_tipo_proceso in (119,125,120,127,117,118,146,182,183,184)
                                            and te.codigo in ('comite_unidad_abastecimientos','vb_rpcd','comite_aeronavegabilidad')
                                            group by fe.id_funcionario);

                if (v_existe_user is null) then
                	v_existe_user = 0;
                end if;
                /*********************************************************************************************************************/

                if (v_existe_user = 1) then

                      /*Aqui para saber el cargo si es jefe de abastecimientos solo mostrar comite*/
                      select car.nombre_cargo into v_nom_cargo
                      from orga.vfuncionario_ultimo_cargo car
                      where car.id_funcionario = v_id_funcionario_recu;
                      /****************************************************************************/

                      if (trim(v_nom_cargo) = 'Jefe Abastecimientos y Logistica')then
                          v_fil_estado = 'autorizado';
                          v_join = 'inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf';
                          v_ex_estado = 's.estado NOT IN (''borrador'',''revision'',''revision_tecnico_abastecimientos'',''cotizacion'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'')';
                          v_join_2 =  'inner join wf.testado_wf e1 on e1.id_proceso_wf = s.id_proceso_wf
                                       inner join wf.ttipo_estado t1 on t1.id_tipo_estado = e1.id_tipo_estado and t1.codigo = ''comite_unidad_abastecimientos''
                                       ';
                         v_fil_func = 'e1.id_funcionario = '||v_id_funcionario_recu;
                      elsif (trim(v_nom_cargo) = 'Director de Aeronavegabilidad Continua') then
                          v_fil_estado = 'autorizado';
                          v_join = 'inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf_firma';
                          v_ex_estado = 's.estado_firma NOT IN (''comite_aeronavegabilidad'')';
                          v_join_2 =  'inner join wf.testado_wf e1 on e1.id_proceso_wf = s.id_proceso_wf_firma
                                       inner join wf.ttipo_estado t1 on t1.id_tipo_estado = e1.id_tipo_estado and t1.codigo = ''comite_aeronavegabilidad''
                                       ';
                          v_fil_func = 'e1.id_funcionario = '||v_id_funcionario_recu;

                      else
                          v_fil_estado = 'vb_rpcd';
                          v_join = 'inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf';
                          v_ex_estado = 's.estado NOT IN (''borrador'',''revision'',''revision_tecnico_abastecimientos'',''cotizacion'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'')';
                          v_fil_func = 'e.id_funcionario = '||v_id_funcionario_recu;
                          v_join_2 = '';
                      end if;


                end if;
            else
            	v_fil_func = '0=0';
                v_fil_estado = 'vb_rpcd';
                v_join = 'inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf';
                v_ex_estado = 's.estado NOT IN (''borrador'',''revision'',''revision_tecnico_abastecimientos'',''cotizacion'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'')';
                v_join_2 = '';
                v_existe_user = 1;
            end if;
            /**********************************************/


            IF  pxp.f_existe_parametro(p_tabla,'origen_pedido') THEN
            	if (v_existe_user = 1) then
                	if (v_parametros.origen_pedido != 'Todos')then
                          v_fill = ' e.fecha_reg::date >='''||v_parametros.fecha_ini||''' and e.fecha_reg::date <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and c.adjudicado = ''si''';
                    else
                          v_fill = ' e.fecha_reg::date >='''||v_parametros.fecha_ini||''' and e.fecha_reg::date <= '''||v_parametros.fecha_fin||'''and c.adjudicado = ''si''';
                    end if;
                else
                		v_fill = ' s.fecha_solicitud::date >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud::date <= '''||v_parametros.fecha_fin||'''';
                end if;



            ELSE
              		v_fill = ' e.fecha_reg::date >='''||now()::date||''' and e.fecha_reg::date <= '''||now()::date||''' and c.adjudicado = ''si''';
            END IF;

		   if (v_existe_user = 1) then
           v_consulta:='	select	 s.origen_pedido,
                                     s.nro_tramite,
                                     s.estado as estado,
                                     (upper(f.desc_funcionario1))::varchar as funciaonario,
                                     to_char(s.fecha_solicitud,''DD/MM/YYYY'')::varchar as fecha_solicitud,
                                     initcap(s.motivo_solicitud)::varchar as motivo_solicitud,
                                     initcap(s.observaciones_sol)::varchar as observaciones_sol,
                                     s.remark::varchar,
                                     s.justificacion,
                                     s.nro_justificacion,
                                     s.tipo_solicitud,
                                     s.tipo_falla,
                                     s.tipo_reporte,
                                     s.mel,
                                     s.nro_no_rutina,
                                     c.nro_cotizacion,
                                     initcap(v.desc_proveedor) as proveedor,
                                     s.nro_po,
                                     vu.desc_persona::varchar as aux_abas,
                                     to_char(MAX(e.fecha_reg::date), ''DD/MM/YYYY'')::varchar as fecha_autorizacion_rpc,
                                     fun.desc_funcionario1::varchar as encargado_rpc,
                                     sum(d.precio_unitario_mb)::numeric,
                                     s.id_proceso_wf::numeric,
                                     mon.codigo_internacional::varchar,
         							 func.desc_funcionario1::varchar as funcionario_solicitante,
                                     s.id_solicitud::numeric,
                                     s.id_estado_wf::numeric,
                                     '||v_existe_user||'::varchar as existe_usuario
                           from  mat.tsolicitud s
                           inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                           inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = ''si''
                           inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion --and d.revisado = ''si''
                           inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                           --inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf
                           '||v_join||'
                           inner join wf.ttipo_estado t on t.id_tipo_estado = e.id_tipo_estado and t.codigo = '''||v_fil_estado||'''
                           '||v_join_2||'
                           left join orga.vfuncionario fun on fun.id_funcionario = e.id_funcionario
                           left join segu.vusuario vu on c.id_usuario_reg = vu.id_usuario
                           inner join param.tmoneda mon on mon.id_moneda = s.id_moneda
                           inner join orga.vfuncionario func on func.id_funcionario = s.id_funcionario_solicitante
                           where '||v_fil_func||' and '||v_fill||' and '||v_parametros.filtro||'
                           and '||v_ex_estado||'
                           group by s.origen_pedido,
                                  s.nro_tramite,
                                  s.estado,
                                  f.desc_funcionario1,
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
                                  vu.desc_persona,
                                  s.nro_po,
                                  s.remark,
                                  fun.desc_funcionario1,
                                  --d.precio_unitario_mb,
                                  s.id_proceso_wf,
                                  mon.codigo_internacional,
                                  func.desc_funcionario1,
                                  e.fecha_reg ,
                                  s.id_solicitud,
                                  s.id_estado_wf
                          	order by e.fecha_reg desc
                            ' || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
           else

           		v_consulta:='	select	 s.origen_pedido,
                                     s.nro_tramite,
                                     s.estado as estado,
                                     (upper(f.desc_funcionario1))::varchar as funciaonario,
                                     to_char(s.fecha_solicitud,''DD/MM/YYYY'')::varchar as fecha_solicitud,
                                     initcap(s.motivo_solicitud)::varchar as motivo_solicitud,
                                     initcap(s.observaciones_sol)::varchar as observaciones_sol,
                                     s.remark::varchar,
                                     s.justificacion,
                                     s.nro_justificacion,
                                     s.tipo_solicitud,
                                     s.tipo_falla,
                                     s.tipo_reporte,
                                     s.mel,
                                     s.nro_no_rutina,
                                     c.nro_cotizacion,
                                     initcap(v.desc_proveedor) as proveedor,
                                     s.nro_po,
                                     vu.desc_persona::varchar as aux_abas,
                                     to_char(s.fecha_solicitud::date, ''DD/MM/YYYY'')::varchar as fecha_autorizacion_rpc,
                                     ''''::varchar as encargado_rpc,
                                     sum(d.precio_unitario_mb)::numeric,
                                     s.id_proceso_wf::numeric,
                                     mon.codigo_internacional::varchar,
         							 func.desc_funcionario1::varchar as funcionario_solicitante,
                                     s.id_solicitud::numeric,
                                     s.id_estado_wf::numeric,
                                     '||v_existe_user||'::varchar as existe_usuario
                           from  mat.tsolicitud s
                           inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                           inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = ''si''
                           inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion
                           inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                           --left join orga.vfuncionario fun on fun.id_funcionario = e.id_funcionario
                           left join segu.vusuario vu on c.id_usuario_reg = vu.id_usuario
                           inner join param.tmoneda mon on mon.id_moneda = s.id_moneda
                           inner join orga.vfuncionario func on func.id_funcionario = s.id_funcionario_solicitante
                           where '||v_fill||' and '||v_parametros.filtro||'
                           and s.estado not in (''borrador'',''revision'',''cotizacion'',''cotizacion_solicitada'',''cotizacion_sin_respuesta'',''finalizado'',''anulado'')
                           group by s.origen_pedido,
                                  s.nro_tramite,
                                  s.estado,
                                  f.desc_funcionario1,
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
                                  vu.desc_persona,
                                  s.nro_po,
                                  s.remark,
                                  s.id_proceso_wf,
                                  mon.codigo_internacional,
                                  func.desc_funcionario1,
                                  s.id_solicitud,
                                  s.id_estado_wf
                          	order by s.fecha_solicitud desc
                            ' || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
           end if;

			return v_consulta;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_RPCE_REP_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael.Valdivia
 	#FECHA:		08-08-2017 21:54:34
	***********************************/

	elsif(p_transaccion='MAT_RPCE_REP_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
            /*Aumentando para listar solo del RPC Encargado*/


            if (p_administrador != 1) then
            	select fun.id_funcionario into v_id_funcionario_recu
                from segu.vusuario us
                inner join orga.vfuncionario fun on fun.id_persona = us.id_persona
                where us.id_usuario = p_id_usuario;

                /*Aumentando para verificar si el usuario ingresado es encargado RPC,Comite Aeronavegabilidad o Comite Abastecimientos*/
                select 1 into v_existe_user
                where v_id_funcionario_recu in (select
                                                   fe.id_funcionario
                                            from wf.ttipo_estado te
                                            inner join wf.tfuncionario_tipo_estado fe on fe.id_tipo_estado = te.id_tipo_estado
                                            inner join orga.vfuncionario fun on fun.id_funcionario = fe.id_funcionario
                                            where te.id_tipo_proceso in (119,125,120,127,117,118,146,182,183,184)
                                            and te.codigo in ('comite_unidad_abastecimientos','vb_rpcd','comite_aeronavegabilidad')
                                            group by fe.id_funcionario);
                /*********************************************************************************************************************/

                if (v_existe_user = 1) then

                      /*Aqui para saber el cargo si es jefe de abastecimientos solo mostrar comite*/
                      select car.nombre_cargo into v_nom_cargo
                      from orga.vfuncionario_ultimo_cargo car
                      where car.id_funcionario = v_id_funcionario_recu;
                      /****************************************************************************/

                      if (trim(v_nom_cargo) = 'Jefe Abastecimientos y Logistica')then
                          v_fil_estado = 'autorizado';
                          v_join = 'inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf';
                          v_ex_estado = 's.estado NOT IN (''borrador'',''revision'',''revision_tecnico_abastecimientos'',''cotizacion'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'')';
                          v_join_2 =  'inner join wf.testado_wf e1 on e1.id_proceso_wf = s.id_proceso_wf
                                       inner join wf.ttipo_estado t1 on t1.id_tipo_estado = e1.id_tipo_estado and t1.codigo = ''comite_unidad_abastecimientos''
                                       ';
                         v_fil_func = 'e1.id_funcionario = '||v_id_funcionario_recu;
                      elsif (trim(v_nom_cargo) = 'Director de Aeronavegabilidad Continua') then
                          v_fil_estado = 'autorizado';
                          v_join = 'inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf_firma';
                          v_ex_estado = 's.estado_firma NOT IN (''comite_aeronavegabilidad'')';
                          v_join_2 =  'inner join wf.testado_wf e1 on e1.id_proceso_wf = s.id_proceso_wf_firma
                                       inner join wf.ttipo_estado t1 on t1.id_tipo_estado = e1.id_tipo_estado and t1.codigo = ''comite_aeronavegabilidad''
                                       ';
                          v_fil_func = 'e1.id_funcionario = '||v_id_funcionario_recu;

                      else
                          v_fil_estado = 'vb_rpcd';
                          v_join = 'inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf';
                          v_ex_estado = 's.estado NOT IN (''borrador'',''revision'',''revision_tecnico_abastecimientos'',''cotizacion'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'')';
                          v_fil_func = 'e.id_funcionario = '||v_id_funcionario_recu;
                          v_join_2 = '';
                      end if;


                end if;
            else
            	v_fil_func = '0=0';
                v_fil_estado = 'vb_rpcd';
                v_join = 'inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf';
                v_ex_estado = 's.estado NOT IN (''borrador'',''revision'',''revision_tecnico_abastecimientos'',''cotizacion'',''cotizacion_solicitada'',''comite_unidad_abastecimientos'')';
                v_join_2 = '';
                v_existe_user = 1;
            end if;
            /**********************************************/


            IF  pxp.f_existe_parametro(p_tabla,'origen_pedido') THEN
            	if (v_existe_user = 1) then
                	if (v_parametros.origen_pedido != 'Todos')then
                          v_fill = ' e.fecha_reg::date >='''||v_parametros.fecha_ini||''' and e.fecha_reg::date <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and c.adjudicado = ''si''';
                    else
                          v_fill = ' e.fecha_reg::date >='''||v_parametros.fecha_ini||''' and e.fecha_reg::date <= '''||v_parametros.fecha_fin||'''and c.adjudicado = ''si''';
                    end if;
                else
                		v_fill = ' s.fecha_solicitud::date >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud::date <= '''||v_parametros.fecha_fin||'''';
                end if;



            ELSE
              		v_fill = ' e.fecha_reg::date >='''||now()::date||''' and e.fecha_reg::date <= '''||now()::date||''' and c.adjudicado = ''si''';
            END IF;

			if (v_existe_user = 1) then
           v_consulta:='	with datos as (

							select	 s.origen_pedido,
                                     s.nro_tramite,
                                     s.estado as estado,
                                     (upper(f.desc_funcionario1))::varchar as funciaonario,
                                     to_char(s.fecha_solicitud,''DD/MM/YYYY'')::varchar as fecha_solicitud,
                                     initcap(s.motivo_solicitud)::varchar as motivo_solicitud,
                                     initcap(s.observaciones_sol)::varchar as observaciones_sol,
                                     s.remark::varchar,
                                     s.justificacion,
                                     s.nro_justificacion,
                                     s.tipo_solicitud,
                                     s.tipo_falla,
                                     s.tipo_reporte,
                                     s.mel,
                                     s.nro_no_rutina,
                                     c.nro_cotizacion,
                                     initcap(v.desc_proveedor) as proveedor,
                                     s.nro_po,
                                     vu.desc_persona::varchar as aux_abas,
                                     to_char(MAX(e.fecha_reg::date), ''DD/MM/YYYY'')::varchar as fecha_autorizacion_rpc,
                                     fun.desc_funcionario1::varchar as encargado_rpc,
                                     sum(d.precio_unitario_mb)::numeric,
                                     s.id_proceso_wf::numeric,
                                     mon.codigo_internacional::varchar,
         							 func.desc_funcionario1::varchar as funcionario_solicitante,
                                     s.id_solicitud::numeric,
                                     s.id_estado_wf::numeric
                           from  mat.tsolicitud s
                           inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                           inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = ''si''
                           inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion --and d.revisado = ''si''
                           inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                           --inner join wf.testado_wf e on e.id_proceso_wf = s.id_proceso_wf
                           '||v_join||'
                           inner join wf.ttipo_estado t on t.id_tipo_estado = e.id_tipo_estado and t.codigo = '''||v_fil_estado||'''
                           '||v_join_2||'
                           left join orga.vfuncionario fun on fun.id_funcionario = e.id_funcionario
                           left join segu.vusuario vu on c.id_usuario_reg = vu.id_usuario
                           inner join param.tmoneda mon on mon.id_moneda = s.id_moneda
                           inner join orga.vfuncionario func on func.id_funcionario = s.id_funcionario_solicitante
                           where '||v_fil_func||' and '||v_fill||' and '||v_parametros.filtro||'
                           and '||v_ex_estado||'
                           group by s.origen_pedido,
                                  s.nro_tramite,
                                  s.estado,
                                  f.desc_funcionario1,
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
                                  vu.desc_persona,
                                  s.nro_po,
                                  s.remark,
                                  fun.desc_funcionario1,
                                  --d.precio_unitario_mb,
                                  s.id_proceso_wf,
                                  mon.codigo_internacional,
                                  func.desc_funcionario1,
                                  e.fecha_reg ,
                                  s.id_solicitud,
                                  s.id_estado_wf
                          	order by e.fecha_reg desc
                        )
                        select count(nro_tramite)
                        from datos';
else
				v_consulta:='	with datos as (

							select	 s.origen_pedido,
                                     s.nro_tramite,
                                     s.estado as estado,
                                     (upper(f.desc_funcionario1))::varchar as funciaonario,
                                     to_char(s.fecha_solicitud,''DD/MM/YYYY'')::varchar as fecha_solicitud,
                                     initcap(s.motivo_solicitud)::varchar as motivo_solicitud,
                                     initcap(s.observaciones_sol)::varchar as observaciones_sol,
                                     s.remark::varchar,
                                     s.justificacion,
                                     s.nro_justificacion,
                                     s.tipo_solicitud,
                                     s.tipo_falla,
                                     s.tipo_reporte,
                                     s.mel,
                                     s.nro_no_rutina,
                                     c.nro_cotizacion,
                                     initcap(v.desc_proveedor) as proveedor,
                                     s.nro_po,
                                     vu.desc_persona::varchar as aux_abas,
                                     to_char(s.fecha_solicitud::date, ''DD/MM/YYYY'')::varchar as fecha_autorizacion_rpc,
                                     ''''::varchar as encargado_rpc,
                                     sum(d.precio_unitario_mb)::numeric,
                                     s.id_proceso_wf::numeric,
                                     mon.codigo_internacional::varchar,
         							 func.desc_funcionario1::varchar as funcionario_solicitante,
                                     s.id_solicitud::numeric,
                                     s.id_estado_wf::numeric
                           from  mat.tsolicitud s
                           inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                           inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = ''si''
                           inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion
                           inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                           --left join orga.vfuncionario fun on fun.id_funcionario = e.id_funcionario
                           left join segu.vusuario vu on c.id_usuario_reg = vu.id_usuario
                           inner join param.tmoneda mon on mon.id_moneda = s.id_moneda
                           inner join orga.vfuncionario func on func.id_funcionario = s.id_funcionario_solicitante
                           where '||v_fill||' and '||v_parametros.filtro||'
                           and s.estado not in (''borrador'',''revision'',''cotizacion'',''cotizacion_solicitada'',''cotizacion_sin_respuesta'',''finalizado'')
                           group by s.origen_pedido,
                                  s.nro_tramite,
                                  s.estado,
                                  f.desc_funcionario1,
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
                                  vu.desc_persona,
                                  s.nro_po,
                                  s.remark,
                                  s.id_proceso_wf,
                                  mon.codigo_internacional,
                                  func.desc_funcionario1,
                                  s.id_solicitud,
                                  s.id_estado_wf
                          	order by s.fecha_solicitud desc
                        )
                        select count(nro_tramite)
                        from datos';
end if;

			return v_consulta;

		end;


    /*********************************
 	#TRANSACCION:  'MAT_DET_SOL_RPC_SEL'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Ismael.Valdivia
 	#FECHA:		08-08-2017 21:54:34
	***********************************/

	elsif(p_transaccion='MAT_DET_SOL_RPC_SEL')then

		begin

        	select sol.origen_pedido into v_origen_pedido
            from mat.tsolicitud sol
            where sol.id_solicitud = v_parametros.id_solicitud;


        	/*Aqui iremos totalizando los datos en los casos del Hazmat(Compras) y B.E.A.R(Reparaciones)*/

            if (v_origen_pedido != 'Reparación de Repuestos') then

            /*Verificamos si tiene Hazmat*/
            select	count(det.nro_parte_cot) into v_existe_hazmat
            from  mat.tsolicitud s
            inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
            inner join mat.tcotizacion_detalle det on det.id_cotizacion = c.id_cotizacion
            left join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
            left join mat.tday_week day on day.id_day_week = det.id_day_week
            left join conta.torden_trabajo ot on ot.id_orden_trabajo = deta.id_orden_trabajo
            left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
            left join pre.tpartida pp on deta.id_partida = pp.id_partida
            where s.id_solicitud = v_parametros.id_solicitud and det.nro_parte_cot = 'HAZMAT';
            /*****************************/

            if (v_existe_hazmat > 0) THEN

            create temp table datos_acumulados (
        										  nro_parte_cot varchar,
                                                  explicacion_detallada_part_cot varchar,
                                                  cantidad_det integer,
                                                  precio_unitario numeric,
                                                  cantidad_dias varchar,
                                                  centro_costo varchar,
                                                  matricula varchar,
                                                  partida varchar,
                                                  id_cotizacion_det integer
                                                )on commit drop;

            insert into datos_acumulados (nro_parte_cot,
                                              explicacion_detallada_part_cot,
                                              cantidad_det,
                                              precio_unitario,
                                              cantidad_dias,
                                              centro_costo,
                                              matricula,
                                              partida,
                                              id_cotizacion_det)

              select	det.nro_parte_cot,
                      det.explicacion_detallada_part_cot,
                      det.cantidad_det,
                      det.precio_unitario,
                      day.codigo_tipo as cantidad_dias,
                      (cc.ep || ' - ' || cc.nombre_uo)::varchar as centro_costo,
                      COALESCE (ot.desc_orden,' ')::varchar as matricula,
                      (pp.codigo || ' - ' || pp.nombre_partida)::varchar as partida,
                      det.id_cotizacion_det
              from  mat.tsolicitud s
              inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
              inner join mat.tcotizacion_detalle det on det.id_cotizacion = c.id_cotizacion
              left join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
              left join mat.tday_week day on day.id_day_week = det.id_day_week
              left join conta.torden_trabajo ot on ot.id_orden_trabajo = deta.id_orden_trabajo
              left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
              left join pre.tpartida pp on deta.id_partida = pp.id_partida
              where s.id_solicitud = v_parametros.id_solicitud and det.nro_parte_cot != 'HAZMAT';


            for v_registros in (select	det.nro_parte_cot,
                                        det.explicacion_detallada_part_cot,
                                        1::integer as cantidad,
                                        sum(det.cantidad_det * det.precio_unitario) as total_hazmat,
                                        ''::varchar as cantidad_dias,
                                        ''::varchar as centro_costo,
                                        ''::varchar as matricula,
                                        ''::varchar as partida,
                                        det.id_detalle_hazmat
                                from  mat.tsolicitud s
                                inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
                                inner join mat.tcotizacion_detalle det on det.id_cotizacion = c.id_cotizacion
                                left join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
                                left join mat.tday_week day on day.id_day_week = det.id_day_week
                                left join conta.torden_trabajo ot on ot.id_orden_trabajo = deta.id_orden_trabajo
                                left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
                                left join pre.tpartida pp on deta.id_partida = pp.id_partida
                                where s.id_solicitud = v_parametros.id_solicitud and det.nro_parte_cot = 'HAZMAT'
                                group by det.nro_parte_cot,det.explicacion_detallada_part_cot, det.id_cotizacion_det)

            loop
            			/*Iremos Actualizando para totalizar los que tengan relacion Hazmat*/
                        update datos_acumulados set
                        	precio_unitario = ((cantidad_det*precio_unitario) + v_registros.total_hazmat),
                        	cantidad_det = 1
                        where id_cotizacion_det = v_registros.id_detalle_hazmat;
            			/*******************************************************************/



            end loop;


            else
        		create temp table datos_acumulados (
        										  nro_parte_cot varchar,
                                                  explicacion_detallada_part_cot varchar,
                                                  cantidad_det integer,
                                                  precio_unitario numeric,
                                                  cantidad_dias varchar,
                                                  centro_costo varchar,
                                                  matricula varchar,
                                                  partida varchar,
                                                  id_cotizacion_det integer
                                                )on commit drop;

                insert into datos_acumulados (nro_parte_cot,
                                                  explicacion_detallada_part_cot,
                                                  cantidad_det,
                                                  precio_unitario,
                                                  cantidad_dias,
                                                  centro_costo,
                                                  matricula,
                                                  partida,
                                                  id_cotizacion_det)

                  select	det.nro_parte_cot,
                          det.explicacion_detallada_part_cot,
                          det.cantidad_det,
                          det.precio_unitario,
                          day.codigo_tipo as cantidad_dias,
                          (cc.ep || ' - ' || cc.nombre_uo)::varchar as centro_costo,
                          COALESCE (ot.desc_orden,' ')::varchar as matricula,
                          (pp.codigo || ' - ' || pp.nombre_partida)::varchar as partida,
                          det.id_cotizacion_det
                  from  mat.tsolicitud s
                  inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
                  inner join mat.tcotizacion_detalle det on det.id_cotizacion = c.id_cotizacion
                  left join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
                  left join mat.tday_week day on day.id_day_week = det.id_day_week
                  left join conta.torden_trabajo ot on ot.id_orden_trabajo = deta.id_orden_trabajo
                  left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
                  left join pre.tpartida pp on deta.id_partida = pp.id_partida
                  where s.id_solicitud = v_parametros.id_solicitud;

            end if;

            else

                  select	count(det.nro_parte_cot) into v_existe_vacios
                  from  mat.tsolicitud s
                  inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
                  inner join mat.tcotizacion_detalle det on det.id_cotizacion = c.id_cotizacion
                  left join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
                  left join mat.tday_week day on day.id_day_week = det.id_day_week
                  left join conta.torden_trabajo ot on ot.id_orden_trabajo = deta.id_orden_trabajo
                  left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
                  left join pre.tpartida pp on deta.id_partida = pp.id_partida
                  where s.id_solicitud = v_parametros.id_solicitud and pp.id_partida is null
                  group by det.nro_parte_cot,det.explicacion_detallada_part_cot;

                  if (v_existe_vacios > 0) then


                  create temp table datos_acumulados (
        										  nro_parte_cot varchar,
                                                  explicacion_detallada_part_cot varchar,
                                                  cantidad_det integer,
                                                  precio_unitario numeric,
                                                  cantidad_dias varchar,
                                                  centro_costo varchar,
                                                  matricula varchar,
                                                  partida varchar,
                                                  id_relacion_ber integer
                                                )on commit drop;

                insert into datos_acumulados (nro_parte_cot,
                                                  explicacion_detallada_part_cot,
                                                  cantidad_det,
                                                  precio_unitario,
                                                  cantidad_dias,
                                                  centro_costo,
                                                  matricula,
                                                  partida,
                                                  id_relacion_ber)

            	select	det.nro_parte_cot,
                        det.explicacion_detallada_part_cot,
                        det.cantidad_det,
                        det.precio_unitario,
                        day.codigo_tipo as cantidad_dias,
                        (cc.ep || ' - ' || cc.nombre_uo)::varchar as centro_costo,
                        COALESCE (ot.desc_orden,' ')::varchar as matricula,
                        (pp.codigo || ' - ' || pp.nombre_partida)::varchar as partida,
                        det.id_detalle_hazmat
                  from  mat.tsolicitud s
                  inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
                  inner join mat.tcotizacion_detalle det on det.id_cotizacion = c.id_cotizacion
                  left join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
                  left join mat.tday_week day on day.id_day_week = det.id_day_week
                  left join conta.torden_trabajo ot on ot.id_orden_trabajo = deta.id_orden_trabajo
                  left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
                  left join pre.tpartida pp on deta.id_partida = pp.id_partida
                  where s.id_solicitud = v_parametros.id_solicitud and pp.id_partida is not null;


                  /*Aqui totalizamos los datos*/
                  for v_registros in (select	det.nro_parte_cot,
                                                det.explicacion_detallada_part_cot,
                                                1::integer as cantidad,
                                                sum(det.cantidad_det * det.precio_unitario) as total_reparaciones,
                                                ''::varchar as cantidad_dias,
                                                ''::varchar as centro_costo,
                                                ''::varchar as matricula,
                                                ''::varchar as partida,
                                                det.id_cotizacion_det   ---Aqui continuar
                                        from  mat.tsolicitud s
                                        inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
                                        inner join mat.tcotizacion_detalle det on det.id_cotizacion = c.id_cotizacion
                                        left join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
                                        left join mat.tday_week day on day.id_day_week = det.id_day_week
                                        left join conta.torden_trabajo ot on ot.id_orden_trabajo = deta.id_orden_trabajo
                                        left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
                                        left join pre.tpartida pp on deta.id_partida = pp.id_partida
                                        where s.id_solicitud = v_parametros.id_solicitud and pp.id_partida is null
                                        group by det.nro_parte_cot,det.explicacion_detallada_part_cot,det.id_cotizacion_det)
                  loop
                  		/*Iremos Actualizando para totalizar los que tengan relacion Hazmat*/
                        update datos_acumulados set
                        	precio_unitario = ((cantidad_det*precio_unitario) + v_registros.total_reparaciones),
                        	cantidad_det = 1
                        where id_relacion_ber = v_registros.id_cotizacion_det;
            			/*******************************************************************/
                  end loop;
                  /****************************/

             else

                  create temp table datos_acumulados (
        										  nro_parte_cot varchar,
                                                  explicacion_detallada_part_cot varchar,
                                                  cantidad_det integer,
                                                  precio_unitario numeric,
                                                  cantidad_dias varchar,
                                                  centro_costo varchar,
                                                  matricula varchar,
                                                  partida varchar,
                                                  condicion varchar
                                                )on commit drop;

                insert into datos_acumulados (nro_parte_cot,
                                                  explicacion_detallada_part_cot,
                                                  cantidad_det,
                                                  precio_unitario,
                                                  cantidad_dias,
                                                  centro_costo,
                                                  matricula,
                                                  partida,
                                                  condicion)

            	select	det.nro_parte_cot,
                        det.explicacion_detallada_part_cot,
                        det.cantidad_det,
                        det.precio_unitario,
                        day.codigo_tipo as cantidad_dias,
                        (cc.ep || ' - ' || cc.nombre_uo)::varchar as centro_costo,
                        COALESCE (ot.desc_orden,' ')::varchar as matricula,
                        (pp.codigo || ' - ' || pp.nombre_partida)::varchar as partida,
                        det.cd
                  from  mat.tsolicitud s
                  inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = 'si'
                  inner join mat.tcotizacion_detalle det on det.id_cotizacion = c.id_cotizacion
                  left join mat.tdetalle_sol deta on deta.id_detalle = det.id_detalle
                  left join mat.tday_week day on day.id_day_week = det.id_day_week
                  left join conta.torden_trabajo ot on ot.id_orden_trabajo = deta.id_orden_trabajo
                  left join param.vcentro_costo cc on  deta.id_centro_costo = cc.id_centro_costo
                  left join pre.tpartida pp on deta.id_partida = pp.id_partida
                  where s.id_solicitud = v_parametros.id_solicitud;
                end if;
            end if;










        	/********************************************************************************************/






           v_consulta:='
                          SELECT	TO_JSON(ROW_TO_JSON(jsonD) :: TEXT) #>> ''{}'' as jsonData
                                                    FROM (
						  select
                          (
                          SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(detalle))) as detalle_solicitud
                          FROM(
                          select *
                          from datos_acumulados) detalle) as detalle_solicitud,

                          ( SELECT TO_JSON(cabecera) as cabecera_solicitud
                            FROM(
                                select sol.nro_tramite
                                from mat.tsolicitud sol
                                where sol.id_solicitud = '||v_parametros.id_solicitud||'
                           )cabecera ) as cabecera,

                           ( SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(detalleSol))) as detalle_solicitado
                            FROM(
                            select det.nro_parte,
                                   det.nro_parte_alterno,
                                   det.cantidad_sol,
                                   det.precio_unitario,
                                   (cc.ep || '' - '' || cc.nombre_uo)::varchar as centro_costo,
                                   COALESCE (ot.desc_orden,'' '')::varchar as matricula,
                                   (pp.codigo || '' - '' || pp.nombre_partida)::varchar as partida
                            from mat.tdetalle_sol det
                            left join param.vcentro_costo cc on  det.id_centro_costo = cc.id_centro_costo
                            left join conta.torden_trabajo ot on ot.id_orden_trabajo = det.id_orden_trabajo
                            left join pre.tpartida pp on det.id_partida = pp.id_partida
                            where det.id_solicitud = '||v_parametros.id_solicitud||'
                            )detalleSol ) as  detalle_sol
                          ) jsonD';

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

ALTER FUNCTION mat.ft_listado_control_rpc (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
