CREATE OR REPLACE FUNCTION mat.ft_acta_conformidad_final_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_acta_conformidad_final_sel
 DESCRIPCION:   Funcion que devuelve datos necesarios para el Acta de Conformidad
 AUTOR: 		 (Ismael Valdivia)
 FECHA:	        04-08-2021 11:18:47
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
    v_filtro_user		varchar;
    v_id_funcionario	integer;


    v_nombre_jefe_abastecimiento  varchar;
    v_cargo_jefe_abastecimiento   varchar;
    v_oficina_jefe_abastecimiento varchar;

    v_tipo_proceso		varchar;
    v_funcionario_almacen	integer;

    v_funcionario_encargado_almacen	varchar;
    v_cargo_encargado_almacen		varchar;
    v_oficina_encargado_almacen		varchar;


BEGIN

	v_nombre_funcion = 'mat.ft_acta_conformidad_final_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_REP_ACTA_CONFOR'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR: 		 (Ismael Valdivia)
    #FECHA:	        04-08-2021
	***********************************/

	if(p_transaccion='MAT_REP_ACTA_CONFOR')then

    	begin
        	/*Incluimos las otras firmas para el reporte de acta de conformidad (Ismael Valdivia 27/10/2021)*/
            --Firma de Jefe de Abastecimiento
            SELECT
                    vf.desc_funcionario1::varchar,
                    vf.nombre_cargo::varchar,
                    vf.oficina_nombre::varchar
            INTO
            		v_nombre_jefe_abastecimiento,
                    v_cargo_jefe_abastecimiento,
                    v_oficina_jefe_abastecimiento
            FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN orga.vfuncionario_ultimo_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'revision'
            GROUP BY vf.desc_funcionario1,
                    vf.nombre_cargo,
                    vf.oficina_nombre;


            --Firma del encargado de almacen
            select det.tipo into v_tipo_proceso
            from mat.tsolicitud sol
            inner join mat.tdetalle_sol det on det.id_solicitud = sol.id_solicitud
            where sol.id_proceso_wf = v_parametros.id_proceso_wf
            group by det.tipo;

            if (v_tipo_proceso = 'Consumibles') then
            	v_funcionario_almacen = pxp.f_get_variable_global('gm_encargado_almacen_consumible');
            elsif (v_tipo_proceso = 'Rotables') then
                v_funcionario_almacen = pxp.f_get_variable_global('gm_encargado_almacen_rotable');
            end if;


            select car.desc_funcionario1,
            	   car.nombre_cargo,
                   car.oficina_nombre
            into
            	   v_funcionario_encargado_almacen,
                   v_cargo_encargado_almacen,
                   v_oficina_encargado_almacen
            from orga.vfuncionario_ultimo_cargo car
            where car.id_funcionario = v_funcionario_almacen;


            /************************************************************************************************/


    		--Sentencia de la consulta
			v_consulta:='select
                                sol.nro_tramite::varchar,
                                pro.rotulo_comercial::varchar as proveedor,
                                sol.nro_po::varchar,
                                to_char (sol.fecha_po, ''DD/MM/YYYY'')::varchar,
                                to_char (acta.fecha_conformidad, ''DD/MM/YYYY'')::varchar,
                                acta.conformidad_final::varchar,
                                to_char (acta.fecha_inicio,''DD/MM/YYYY'')::varchar,
                                to_char (acta.fecha_final,''DD/MM/YYYY'')::varchar,
                                acta.observaciones::varchar,
                                fun.desc_funcionario1::varchar,
                                fun.nombre_cargo::varchar,
                                fun.oficina_nombre::varchar,
                                /*Firma de jefe abastecimiento*/
                                '''||v_nombre_jefe_abastecimiento||'''::varchar as jefe_abastecimiento,
                                '''||v_cargo_jefe_abastecimiento||'''::varchar as cargo_jefe_abastecimiento,
                                '''||v_oficina_jefe_abastecimiento||'''::varchar as oficina_abastecimiento,
                                /******************************/

                                 /*Firma encargado almacen*/
                                '''||v_funcionario_encargado_almacen||'''::varchar as encargado_almacen,
                                '''||v_cargo_encargado_almacen||'''::varchar as cargo_encargado_almacen,
                                '''||v_oficina_encargado_almacen||'''::varchar as oficina_encargado_almacen
                                /******************************/



                          from mat.tsolicitud sol
                          left join param.vproveedor2 pro on pro.id_proveedor = sol.id_proveedor
                          left join mat.tcotizacion cot on cot.id_solicitud = sol.id_solicitud and cot.adjudicado = ''si''
                          left join mat.tacta_conformidad_final acta on acta.id_solicitud = sol.id_solicitud
                          left join orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = acta.id_funcionario_firma
                          where sol.id_proceso_wf = '||v_parametros.id_proceso_wf||'';

			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_REP_ACTA_DETA'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR: 		 (Ismael Valdivia)
    #FECHA:	        04-08-2021
	***********************************/

	elsif(p_transaccion='MAT_REP_ACTA_DETA')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
                               ing.desc_ingas::varchar,
                               (CASE
                                      WHEN sol.origen_pedido = ''Reparación de Repuestos''  THEN
                                          (''P/N: ''||det.nro_parte||''  ''||det.descripcion||'' S/N: ''||det.referencia)
                                      ELSE
                                          (''P/N: ''||det.nro_parte||''  ''||det.descripcion||'' ''||

                                              (case when det.tipo = ''Rotables'' THEN
                                                  '' Rotable''
                                               when det.tipo = ''Consumibles'' then
                                                  '' Consumible''
                                               else
                                                  ''''
                                              end ))
                                END)::varchar as descripcion,
                               det.cantidad_sol::integer
                      from mat.tsolicitud sol
                      inner join mat.tdetalle_sol det on det.id_solicitud = sol.id_solicitud
                      inner join param.tconcepto_ingas ing on ing.id_concepto_ingas = det.id_concepto_ingas
                      where sol.id_proceso_wf = '||v_parametros.id_proceso_wf||'';
			--raise notice '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_LIST_ACT_FIN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR: 		 (Ismael Valdivia)
    #FECHA:	        04-08-2021
	***********************************/

	elsif(p_transaccion='MAT_LIST_ACT_FIN_SEL')then

    	begin
        	--raise exception 'Aqui llega administrador %',p_administrador;
        	if (p_administrador = 1) then
            	v_filtro_user = '0=0';
            else
            	select fun.id_funcionario into v_id_funcionario
                from segu.vusuario usu
                inner join orga.vfuncionario fun on fun.id_persona = usu.id_persona
                where usu.id_usuario = p_id_usuario;
            	v_filtro_user = 'acta.id_funcionario_firma = '||v_id_funcionario||'';
            end if;

    		--Sentencia de la consulta
			v_consulta:=' select sol.nro_tramite::varchar,
                                 sol.estado::varchar,
                                 to_char(sol.fecha_solicitud,''DD/MM/YYYY'')::varchar as fecha_sol,
                                 pro.rotulo_comercial::varchar as proveedor,
                                 round(sum(cotdet.cantidad_det * cotdet.precio_unitario),2)::numeric as total_a_pagar,
                                 mon.moneda::varchar,
                                 to_char(acta.fecha_conformidad,''DD/MM/YYYY'')::varchar as fecha_conformidad,
                                 fun.desc_funcionario1::varchar,
                                 usu.id_usuario,
                                 sol.id_solicitud,
                                 acta.observaciones::varchar,
                                 acta.conformidad_final::varchar,
                                 to_char(acta.fecha_inicio,''DD/MM/YYYY'')::varchar,
                                 to_char(acta.fecha_final,''DD/MM/YYYY'')::varchar,
                                 sol.id_proceso_wf::integer
                          from mat.tsolicitud sol
                          inner join param.vproveedor2 pro on pro.id_proveedor = sol.id_proveedor
                          inner join mat.tcotizacion cot on cot.id_solicitud = sol.id_solicitud and cot.adjudicado = ''si''
                          inner join mat.tcotizacion_detalle cotdet on cotdet.id_cotizacion = cot.id_cotizacion
                          inner join param.tmoneda mon on mon.id_moneda = sol.id_moneda
                          inner join mat.tacta_conformidad_final acta on acta.id_solicitud = sol.id_solicitud
                          inner join orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = acta.id_funcionario_firma
                          inner join orga.vfuncionario funcio on funcio.id_funcionario = acta.id_funcionario_firma
                          inner join segu.vusuario usu on usu.id_persona = funcio.id_persona
                          where '||v_filtro_user||' and ';

			--Devuelve la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' group by sol.nro_tramite,
                                               sol.estado,
                                               sol.fecha_solicitud,
                                               pro.rotulo_comercial,
                                               mon.moneda,
                                               acta.fecha_conformidad,
                                               fun.desc_funcionario1,
                                               usu.id_usuario,
                                               sol.id_solicitud,
                                               acta.observaciones,
                                               acta.conformidad_final,
                                               acta.fecha_inicio,
                                               acta.fecha_final';
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            raise notice '%',v_consulta;
			return v_consulta;
		end;


    /*********************************
 	#TRANSACCION:  'MAT_LIST_ACT_FIN_CONT'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR: 		 (Ismael Valdivia)
    #FECHA:	        04-08-2021
	***********************************/

	elsif(p_transaccion='MAT_LIST_ACT_FIN_CONT')then

    	begin
        	--raise exception 'Aqui llega administrador %',p_administrador;
        	if (p_administrador = 1) then
            	v_filtro_user = '0=0';
            else
            	select fun.id_funcionario into v_id_funcionario
                from segu.vusuario usu
                inner join orga.vfuncionario fun on fun.id_persona = usu.id_persona
                where usu.id_usuario = p_id_usuario;
            	v_filtro_user = 'acta.id_funcionario_firma = '||v_id_funcionario||'';
            end if;

    		--Sentencia de la consulta
			v_consulta:=' select count (distinct sol.id_solicitud)
                          from mat.tsolicitud sol
                          inner join param.vproveedor2 pro on pro.id_proveedor = sol.id_proveedor
                          inner join mat.tcotizacion cot on cot.id_solicitud = sol.id_solicitud and cot.adjudicado = ''si''
                          inner join mat.tcotizacion_detalle cotdet on cotdet.id_cotizacion = cot.id_cotizacion
                          inner join param.tmoneda mon on mon.id_moneda = sol.id_moneda
                          inner join mat.tacta_conformidad_final acta on acta.id_solicitud = sol.id_solicitud
                          inner join orga.vfuncionario_ultimo_cargo fun on fun.id_funcionario = acta.id_funcionario_firma
                          inner join orga.vfuncionario funcio on funcio.id_funcionario = acta.id_funcionario_firma
                          inner join segu.vusuario usu on usu.id_persona = funcio.id_persona
                          where '||v_filtro_user||' and ';

			--Devuelve la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;

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

ALTER FUNCTION mat.ft_acta_conformidad_final_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;