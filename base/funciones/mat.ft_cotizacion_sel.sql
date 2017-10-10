CREATE OR REPLACE FUNCTION mat.ft_cotizacion_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_cotizacion_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'mat.tcotizacion'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        04-07-2017 14:03:30
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_consulta    			varchar;
	v_parametros  			record;
	v_nombre_funcion   		text;
	v_resp					varchar;
    v_filtro				varchar;
    v_proveedor				VARCHAR[];
    id_prov					INTEGER[];
     v_id_funcionario_qr 	integer;
    v_nombre_funcionario_qr varchar;
    v_fecha_firma_qr 		text;

    v_id_funcionario_dc_qr 		integer;
    v_nombre_funcionario_dc_qr 	varchar;
    v_fecha_firma_dc_qr 		text;

    v_id_funcionario_ag_qr 		integer;
    v_nombre_funcionario_ag_qr 	varchar;
    v_fecha_firma_ag_qr 		text;
   	v_id_proceso_wf_firma		integer;

    v_nombre_funcionario_rev_qr 	varchar;
    v_fecha_firma_rev_qr 		text;
    v_nombre_funcionario_abas_qr 	varchar;
    v_fecha_firma_abas_qr 		text;

BEGIN

	v_nombre_funcion = 'mat.ft_cotizacion_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_CTS_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/

	if(p_transaccion='MAT_CTS_SEL')then

    	begin
         IF  pxp.f_existe_parametro(p_tabla, 'id_solicitud') THEN
            	v_filtro = 'cts.id_solicitud='||v_parametros.id_solicitud||' and ';
            ELSE
                v_filtro = '';
            END IF;
    		--Sentencia de la consulta
			v_consulta:='select
						cts.id_cotizacion,
						cts.id_solicitud,
						cts.id_moneda,
						cts.nro_tramite,
						cts.fecha_cotizacion,
						cts.adjudicado,
						cts.estado_reg,
						cts.id_proveedor,
						COALESCE(cts.monto_total,0)::numeric,
						cts.id_usuario_ai,
						cts.id_usuario_reg,
						cts.fecha_reg,
						cts.usuario_ai,
						cts.id_usuario_mod,
						cts.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        mo.moneda as desc_moneda,
                        p.desc_proveedor,
                        cts.nro_cotizacion,
                        cts.recomendacion,
                        cts.obs,
                        cts.pie_pag
						from mat.tcotizacion cts
						inner join segu.tusuario usu1 on usu1.id_usuario = cts.id_usuario_reg
						inner join param.tmoneda mo on mo.id_moneda = cts.id_moneda
                        inner join param.vproveedor p on p.id_proveedor = cts.id_proveedor
                        left join segu.tusuario usu2 on usu2.id_usuario = cts.id_usuario_mod
						where  '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_CTS_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/

	elsif(p_transaccion='MAT_CTS_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cotizacion)
					    from mat.tcotizacion cts
					    inner join segu.tusuario usu1 on usu1.id_usuario = cts.id_usuario_reg
						inner join param.tmoneda mo on mo.id_moneda = cts.id_moneda
                        inner join param.vproveedor p on p.id_proveedor = cts.id_proveedor
                        left join segu.tusuario usu2 on usu2.id_usuario = cts.id_usuario_mod
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'MAT_CTS_COM'
 	#DESCRIPCION:	combo proveedores
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/
    elsif(p_transaccion='MAT_CTS_COM')then

		begin
        v_consulta:='select 	ne.id_solicitud,
								ne.id_gestion_proveedores,
                                ne.id_proveedor as id_prov,
                                prov.desc_proveedor
                                from mat.tgestion_proveedores_new ne
                                inner join param.vproveedor prov on prov.id_proveedor = ne.id_proveedor
                                where ne.id_solicitud = '||v_parametros.id_solicitud||' and ';
        --Definicion de la respuesta

			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_CTS_CUAR'
 	#DESCRIPCION:	Cuandro Comparativo
 	#AUTOR:		Miguel Alejandro Mamani Villegas
 	#FECHA:		02-06-2017
	***********************************/
elsif(p_transaccion='MAT_CTS_CUAR')then
    	begin
			 v_consulta:='select s.id_solicitud,
							  d.id_cotizacion,
                              d.nro_parte_cot::varchar as parte,
                              d.descripcion_cot::text as  descripcion_cot,
                              d.explicacion_detallada_part_cot,
                              d.cantidad_det::integer as cantidad,
                              d.tipo_cot,
                              d.cd,
                              d.precio_unitario,
                              d.precio_unitario_mb,
                              dy.codigo_tipo,
                              pr.desc_proveedor ||''Adjudicado ''||c.adjudicado as desc_proveedor,
                              c.adjudicado,
                              c.recomendacion,
                              c.obs,
                              to_char(c.fecha_cotizacion,''DD/MM/YYYY'')::varchar as fecha_cotizacion,
                              s.fecha_po,
                              c.monto_total,
                              (select pxp.list(initcap(p.desc_proveedor))
                              from mat.tgestion_proveedores_new ne
                              inner join param.vproveedor p on p.id_proveedor = ne.id_proveedor
                              where ne.id_solicitud = s.id_solicitud
                              )::varchar as lista_proveedor,
                              c.pie_pag,
                              s.estado
							  from mat.tsolicitud s
                              inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud
                              inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion
                              inner join param.vproveedor pr on pr.id_proveedor = c.id_proveedor
                              left join mat.tday_week dy on dy.id_day_week = d.id_day_week
                              where s.id_proceso_wf ='||v_parametros.id_proceso_wf||'and d.revisado = ''si'' and ';
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||'ORDER BY parte, c.adjudicado DESC';
			--Devuelve la respuesta
			return v_consulta;
		end;

        /*********************************
 	#TRANSACCION:  'MAT_CTS_QR'
 	#DESCRIPCION:	Control de firmas  qr
 	#AUTOR:	 Ale MV
 	#FECHA:		23-12-2016 13:13:01
	***********************************/
    elsif(p_transaccion='MAT_CTS_QR')then

		begin
         select sou.id_proceso_wf_firma
        		into
                v_id_proceso_wf_firma
        from mat.tsolicitud sou
        where sou.id_proceso_wf = v_parametros.id_proceso_wf;


	SELECT			twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                    into
        			v_id_funcionario_dc_qr,
                	v_nombre_funcionario_dc_qr,
                	v_fecha_firma_dc_qr
          FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo = 'comite_aeronavegabilidad' and vf.fecha_finalizacion is null GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;

     SELECT
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                    into
                	v_nombre_funcionario_abas_qr,
                	v_fecha_firma_abas_qr
          FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_dpto_abastecimientos' and vf.fecha_finalizacion is null GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;
     SELECT
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                     into
        			v_nombre_funcionario_ag_qr,
    				v_fecha_firma_ag_qr
                    FROM wf.testado_wf twf
                    INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                    INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                    WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'compra' and  vf.fecha_finalizacion is null GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;
    SELECT
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
                    INTO
                    v_nombre_funcionario_rev_qr,
    				v_fecha_firma_rev_qr
                    FROM wf.testado_wf twf
                    INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                    INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                    WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_unidad_abastecimientos'and vf.fecha_finalizacion is null GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;





            v_consulta:='select 	s.origen_pedido,
                                    '''||COALESCE (initcap(v_nombre_funcionario_dc_qr),' ')||'''::varchar as aero,
                                    '''||COALESCE (v_fecha_firma_dc_qr,' ')||'''::text as fecha_aero,
                                    '''||COALESCE (initcap(v_nombre_funcionario_ag_qr),' ')||'''::varchar as visto_ag,
                                    '''||COALESCE (v_fecha_firma_ag_qr,' ')||'''::text as fecha_ag,
                                    '''||COALESCE (initcap(v_nombre_funcionario_rev_qr),' ')||'''::varchar as visto_rev,
                                    '''||COALESCE (v_fecha_firma_rev_qr,' ')||'''::text as fecha_rev,
                                    '''||COALESCE (initcap(v_nombre_funcionario_abas_qr),' ')||'''::varchar as visto_abas,
                                    '''||COALESCE (v_fecha_firma_abas_qr,' ')||'''::text as fecha_abas,
                                    s.nro_tramite
                                    from mat.tsolicitud s
                                    where s.id_proceso_wf = '||v_parametros.id_proceso_wf;
            --Devuelve la respuesta

            v_consulta=v_consulta||' GROUP BY s.origen_pedido,s.nro_tramite';
            return v_consulta;
   end;
   /*********************************
 	#TRANSACCION:  'MAT_CTS_PART'
 	#DESCRIPCION:	Cuandro Comparativo
 	#AUTOR:		Miguel Alejandro Mamani Villegas
 	#FECHA:		02-06-2017
	***********************************/

	elsif(p_transaccion='MAT_CTS_PART')then
    	begin
          v_consulta:='SELECT 	d.nro_parte
								FROM mat.tdetalle_sol d
                                inner join mat.tsolicitud s on s.id_solicitud = d.id_solicitud
                                where s.id_proceso_wf  ='||v_parametros.id_proceso_wf||'and';
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			--Devuelve la respuesta
			return v_consulta;
		end;
         /*********************************
 	#TRANSACCION:  'MAT_CTS_PROVEE'
 	#DESCRIPCION:	lista de proveedor
 	#AUTOR:		Miguel Alejandro Mamani Villegas
 	#FECHA:		02-06-2017
	***********************************/

	elsif(p_transaccion='MAT_CTS_PROVEE')then
    	begin
          v_consulta:='select pxp.list(initcap(p.desc_proveedor))::varchar as lista_proverod,
		c.obs
		from mat.tgestion_proveedores_new ne
        inner join param.vproveedor p on p.id_proveedor = ne.id_proveedor
        inner join mat.tsolicitud s on s.id_solicitud = ne.id_solicitud
        inner join mat.tcotizacion  c on c.id_solicitud = ne.id_solicitud and c.adjudicado = ''si''
        where s.id_proceso_wf  ='||v_parametros.id_proceso_wf||'and';
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||'group by c.obs';
			--Devuelve la respuesta
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