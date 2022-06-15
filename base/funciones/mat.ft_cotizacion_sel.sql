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

	v_consulta    				 varchar;
	v_parametros  				 record;
	v_nombre_funcion   			 text;
	v_resp						 varchar;
    v_filtro					 varchar;
    v_proveedor					 VARCHAR[];
    id_prov						 INTEGER[];
     v_id_funcionario_qr 		 integer;
    v_nombre_funcionario_qr 	 varchar;
    v_fecha_firma_qr 			 text;

    v_id_funcionario_dc_qr 		 integer;
    v_nombre_funcionario_dc_qr 	 varchar;
    v_fecha_firma_dc_qr 		 text;

    v_id_funcionario_ag_qr 		 integer;
    v_nombre_funcionario_ag_qr 	 varchar;
    v_fecha_firma_ag_qr 		 text;
   	v_id_proceso_wf_firma		 integer;

    v_nombre_funcionario_rev_qr  varchar;
    v_fecha_firma_rev_qr 		 text;
    v_nombre_funcionario_abas_qr varchar;
    v_fecha_firma_abas_qr 		 text;
    v_fill 						 varchar;
    v_origen 					 varchar;
    v_estado 					 varchar;

    v_id_funcionario_dc_qr_oficial  		integer;
   	v_nombre_funcionario_dc_qr_oficial 		varchar;
    v_id_funcionario_abas_qr_oficial  		integer;
    v_nombre_funcionario_abas_qr_oficial   	varchar;
    v_id_funcionario_ag_qr_oficial 			integer;
    v_nombre_funcionario_ag_qr_oficial      varchar;
    v_id_funcionario_rev_qr_oficial 		integer;
    v_nombre_funcionario_rev_qr_oficial     varchar;
    remplaso 								record;
    v_rango_fecha 							text;
    --v_nombre_funcionario_ac_qr varchar,
    v_fecha_po								text;
    v_fecha_solicitud						text;
    v_estado_firma							varchar;
	v_fecha_salida_gm  date;
    v_fecha_solicitud_recu	date;
    v_es_mayor								varchar;
    v_fecha_nuevo_flujo	varchar;
    v_id_solicitud_reporte integer;
    v_insertar_datos	varchar;
    v_id_estado_aprobado	integer;
    v_fecha_aprobacion				date;

BEGIN
	v_rango_fecha = '01/11/2018';
    v_fecha_salida_gm = pxp.f_get_variable_global('fecha_salida_gm')::date;
	v_nombre_funcion = 'mat.ft_cotizacion_sel';
    v_parametros = pxp.f_get_record(p_tabla);

     /*Aumentando para que los reportes cambien con el lo que es el Iterinato*/
    v_fecha_nuevo_flujo = pxp.f_get_variable_global('fecha_nuevo_flujo_gm')::date;
    /************************************************************************/

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
                        cts.pie_pag,
                        /*Aumentando para mostrar datos de alkym Ismael Valdivia 24/04/2020*/
                        /*(CASE WHEN cts.adjudicado = ''si'' THEN
                        	(select soli.tipo_evaluacion
                            from mat.tsolicitud soli
                            where soli.id_solicitud = '||v_parametros.id_solicitud||')
                          ELSE
                          	''''
                          END
                        )::varchar as tipo_evaluacion*/
                        soli.tipo_evaluacion::varchar as tipo_evaluacion
                        /*******************************************************************/
						from mat.tcotizacion cts
                        left join mat.tsolicitud soli on soli.id_solicitud = cts.id_solicitud
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
 	#TRANSACCION:  'MAT_CTS_COM_SEL'
 	#DESCRIPCION:	combo proveedores
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/
    elsif(p_transaccion='MAT_CTS_COM_SEL')then

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
 	#TRANSACCION:  'MAT_CTS_COM_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		04-07-2017 14:03:30
	***********************************/

	elsif(p_transaccion='MAT_CTS_COM_CONT')then
		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_gestion_proveedores)
					     from mat.tgestion_proveedores_new ne
                          inner join param.vproveedor prov on prov.id_proveedor = ne.id_proveedor
                          where ne.id_solicitud = '||v_parametros.id_solicitud||' and ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

        /*********************************
        #TRANSACCION:  'MAT_CONT_PROVE_SEL'
        #DESCRIPCION:	combo contacto proveedores
        #AUTOR:		Ismael Valdivia
        #FECHA:		08-12-2021 14:03:30
        ***********************************/
        elsif(p_transaccion='MAT_CONT_PROVE_SEL')then

            begin
            v_consulta:='select  cont.id_proveedor_contacto,
                                 cont.id_proveedor_contacto_alkym,
                                 cont.nombre_contacto
                          from param.tproveedor_contacto cont
                          where ';
            --Definicion de la respuesta

                v_consulta:=v_consulta||v_parametros.filtro;
                v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
                --Devuelve la respuesta
                return v_consulta;

            end;
        /*********************************
        #TRANSACCION:  'MAT_CONT_PROVE_CONT'
        #DESCRIPCION:	Conteo de registros
        #AUTOR:		Ismael Valdivia
        #FECHA:		08-12-2021 14:03:30
        ***********************************/

        elsif(p_transaccion='MAT_CONT_PROVE_CONT')then
            begin
                --Sentencia de la consulta de conteo de registros
                v_consulta:='select count (cont.id_proveedor_contacto)
                          from param.tproveedor_contacto cont
                          where ';

                --Definicion de la respuesta
                v_consulta:=v_consulta||v_parametros.filtro;

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

                       select
                              s.fecha_po,
                              s.fecha_solicitud
                              into
                              v_fecha_po,
                              v_fecha_solicitud
                      from mat.tsolicitud s
                      where s.id_proceso_wf =v_parametros.id_proceso_wf;




        	/*Aumentando para recuperar la fecha de aprobacion del comite*/
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
        	/*************************************************************/

           if (v_fecha_po is null) then
           	v_fecha_po = '';
           end if;

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
                              --s.fecha_po,
                              '''||v_fecha_po::varchar||'''::varchar as fecha_po,
                              c.monto_total,
                              (select pxp.list(initcap(p.desc_proveedor))
                              from mat.tgestion_proveedores_new ne
                              inner join param.vproveedor p on p.id_proveedor = ne.id_proveedor
                              where ne.id_solicitud = s.id_solicitud
                              )::varchar as lista_proveedor,
                              c.pie_pag,
                              s.estado,
                               c.nro_cotizacion,
                               s.fecha_solicitud,
                               '''||v_fecha_salida_gm||'''::date as fecha_salida,
                               d.explicacion_detallada_part_cot::varchar
							  from mat.tsolicitud s
                              inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud
                              inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion
                              inner join param.vproveedor pr on pr.id_proveedor = c.id_proveedor
                              left join mat.tday_week dy on dy.id_day_week = d.id_day_week
                              where s.id_proceso_wf ='||v_parametros.id_proceso_wf||' and ';
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||'ORDER BY c.adjudicado asc, d.id_cotizacion_det DESC, d.precio_unitario DESC';
			--Devuelve la respuesta

			raise notice 'Aqui llega data %',v_consulta;
			return v_consulta;
		end;

    /*********************************
 	#TRANSACCION:  'MAT_SOLI_FEC'
 	#DESCRIPCION:	Fecha de la solicitud para nuevo formato
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		07-08-2020
	***********************************/

	elsif(p_transaccion='MAT_SOLI_FEC')then
    	begin

			 v_consulta:='select s.fecha_solicitud
							  from mat.tsolicitud s
                              where s.id_proceso_wf ='||v_parametros.id_proceso_wf||'';


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

     select to_char(sou.fecha_po,'DD/MM/YYYY')as fechapo, to_char(sou.fecha_solicitud,'DD/MM/YYYY')as fechasol, sou.id_solicitud
     into
        v_fecha_po,
        v_fecha_solicitud,
        v_id_solicitud_reporte
        from mat.tsolicitud sou
        where sou.id_proceso_wf = v_parametros.id_proceso_wf;

	if (v_fecha_solicitud::date >= v_fecha_salida_gm or v_id_solicitud_reporte in (7285,7187,7286,7268,7301,6507,7292,7283,7298,7284)) then

        /*Recuperamos el id_proceso_wf_firma para recuperar en el reporte (Ismael Valdivia 09/03/2020)*/
        select sol.id_proceso_wf_firma into v_id_proceso_wf_firma
        from mat.tsolicitud sol
        where sol.id_proceso_wf = v_parametros.id_proceso_wf;
        /**********************************************************************************************/


  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
          SELECT		twf.id_funcionario,
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
                 and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
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
          SELECT		twf.id_funcionario,
                        vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                        to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
          INTO
                        v_id_funcionario_rev_qr_oficial,
                        v_nombre_funcionario_rev_qr_oficial,
                        v_fecha_firma_rev_qr
           FROM wf.testado_wf twf
                INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
                AND te.codigo = 'comite_unidad_abastecimientos'
                and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
                GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, vf.fecha_asignacion
                ORDER BY vf.fecha_asignacion desc
                limit 1;

        remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_rev_qr_oficial);

        if(remplaso is null)THEN

              v_nombre_funcionario_rev_qr = v_nombre_funcionario_rev_qr_oficial;

      	else
              v_nombre_funcionario_rev_qr = remplaso.desc_funcionario1;

      	end if;
      end if;





  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN


  	   if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
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
              AND te.codigo = 'comite_aeronavegabilidad'
              AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion, now())
              GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg, vf.fecha_asignacion
              ORDER BY  vf.fecha_asignacion DESC
              limit 1;
       else
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
              AND te.codigo = 'comite_aeronavegabilidad'
              AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion, now())
              GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg, vf.fecha_asignacion
              ORDER BY  vf.fecha_asignacion DESC
              limit 1;
       end if;

  	remplaso = mat.f_firma_modif(v_id_proceso_wf_firma,v_id_funcionario_dc_qr_oficial,v_fecha_po);

      if(remplaso is null)THEN

              v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

      else
              v_nombre_funcionario_dc_qr = remplaso.desc_funcionario1;

      end if;

  else
  		if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then
        	SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
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
              AND te.codigo = 'comite_aeronavegabilidad'
              AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion, now())
              GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg, vf.fecha_asignacion
              ORDER BY  vf.fecha_asignacion DESC
              limit 1;
        else
            SELECT		twf.id_funcionario,
                        vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
                        to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
            into
                        v_id_funcionario_dc_qr_oficial,
                        v_nombre_funcionario_dc_qr_oficial,
                        v_fecha_firma_dc_qr
            FROM wf.testado_wf twf
              INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
              INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
              WHERE twf.id_proceso_wf = v_id_proceso_wf_firma  AND te.codigo = 'comite_aeronavegabilidad'
              AND v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion, now())
              GROUP BY twf.id_funcionario, vf.desc_funcionario1,vf.nombre_cargo,pro.nro_tramite, twf.fecha_reg, vf.fecha_asignacion
              ORDER BY  vf.fecha_asignacion DESC
              limit 1;
        end if;
  	remplaso = mat.f_firma_original(v_id_proceso_wf_firma,v_id_funcionario_dc_qr_oficial);

      if(remplaso is null)THEN

              v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

      else
              v_nombre_funcionario_dc_qr = remplaso.desc_funcionario1;

      end if;

  end if;



  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
  		if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then

                SELECT		twf.id_funcionario,
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
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'vb_dpto_abastecimientos'
                  and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
                  GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, pro.nro_tramite, vf.fecha_asignacion
                  order by vf.fecha_asignacion desc
                  limit 1;

        else
                SELECT		twf.id_funcionario,
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
                  WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'vb_dpto_abastecimientos'
                  and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
                  GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, pro.nro_tramite, vf.fecha_asignacion
                  order by vf.fecha_asignacion desc
                  limit 1;
        end if;

         if (v_fecha_solicitud::date >= v_fecha_nuevo_flujo::date) then

  			remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_abas_qr_oficial,v_fecha_po);

             if(remplaso is null)THEN

              		v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;

            else
                    v_nombre_funcionario_abas_qr = remplaso.desc_funcionario1;

            end if;
        else
        		v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;
        end if;

  else
  		 SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
         into
                    v_id_funcionario_abas_qr_oficial,
                	v_nombre_funcionario_abas_qr_oficial,
                	v_fecha_firma_abas_qr
         FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          /*Comentando esta parte para que marco mendoza aparesca en las Firmas Ismael Valdivia (06/02/2020)*/
--          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_dpto_abastecimientos' and vf.fecha_finalizacion is null

          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'vb_dpto_administrativo'
          and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())

          GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, vf.fecha_asignacion
          order by vf.fecha_asignacion desc
          limit 1;

  		remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_abas_qr_oficial);

        if(remplaso is null)THEN

                v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;

        else
                v_nombre_funcionario_abas_qr = remplaso.desc_funcionario1;

        end if;
  end if;


  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
       -- 08-07-2021 (may) modificacion de codigo te.codigo = 'compra' a cotizacion_solicitada
  		SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        into
                    v_id_funcionario_ag_qr_oficial,
        			v_nombre_funcionario_ag_qr_oficial,
    				v_fecha_firma_ag_qr
        FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            AND te.codigo = 'cotizacion_solicitada'
            and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
           GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, pro.nro_tramite, vf.fecha_asignacion
           ORDER BY twf.fecha_reg  desc
           limit 1;

  	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_ag_qr_oficial,v_fecha_po);

      if(remplaso is null)THEN

              v_nombre_funcionario_ag_qr = v_nombre_funcionario_ag_qr_oficial;

      else
              v_nombre_funcionario_ag_qr = remplaso.desc_funcionario1;

      end if;

  else
   		SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        into
                    v_id_funcionario_ag_qr_oficial,
        			v_nombre_funcionario_ag_qr_oficial,
    				v_fecha_firma_ag_qr
        FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            AND te.codigo = 'compra'
            and v_fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion,now())
           GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, vf.fecha_asignacion
           ORDER BY vf.fecha_asignacion desc
           limit 1;

  	remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_ag_qr_oficial);

      if(remplaso is null)THEN

              v_nombre_funcionario_ag_qr = v_nombre_funcionario_ag_qr_oficial;

      else
              v_nombre_funcionario_ag_qr = remplaso.desc_funcionario1;

      end if;

  end if;


	 /*Fecha para verificar si es menor o mayor*/
     select (case when
		   v_fecha_solicitud::date < '01/01/2022'::date then
           'menor'
	    else
           'mayor'
	    end ) into v_es_mayor;
     /******************************************/



            v_consulta:='select 	s.origen_pedido,
                                    '''||COALESCE (initcap(v_nombre_funcionario_dc_qr),' ')||'''::varchar as aero,
                                    '''||COALESCE (v_fecha_firma_dc_qr,' ')||'''::text as fecha_aero,
                                    '''||COALESCE (initcap(v_nombre_funcionario_ag_qr),' ')||'''::varchar as visto_ag,
                                    '''||COALESCE (v_fecha_firma_ag_qr,' ')||'''::text as fecha_ag,
                                    '''||COALESCE (initcap(v_nombre_funcionario_rev_qr),' ')||'''::varchar as visto_rev,
                                    '''||COALESCE (v_fecha_firma_rev_qr,' ')||'''::text as fecha_rev,
                                    '''||COALESCE (initcap(v_nombre_funcionario_abas_qr),' ')||'''::varchar as visto_abas,
                                    '''||COALESCE (v_fecha_firma_abas_qr,' ')||'''::text as fecha_abas,
                                    s.nro_tramite,
                                    s.estado_firma,
                                    '''||v_es_mayor||'''::varchar as mayor
                                    from mat.tsolicitud s
                                    where s.id_proceso_wf = '||v_parametros.id_proceso_wf;
            --Devuelve la respuesta

            v_consulta=v_consulta||' GROUP BY s.origen_pedido,s.nro_tramite,s.estado_firma';

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
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo =
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

      if(remplaso is null)THEN

                v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

        else
                v_nombre_funcionario_dc_qr = remplaso.desc_funcionario1;

        end if;
  else
  		SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        into
        			v_id_funcionario_dc_qr_oficial,
                	v_nombre_funcionario_dc_qr_oficial,
                	v_fecha_firma_dc_qr
        FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf  AND te.codigo =
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

    if(remplaso is null)THEN

            v_nombre_funcionario_dc_qr = v_nombre_funcionario_dc_qr_oficial;

    else
            v_nombre_funcionario_dc_qr = remplaso.desc_funcionario1;

    end if;
  end if;



  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
  		SELECT		twf.id_funcionario,
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
          and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
          GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, pro.nro_tramite;

  remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_abas_qr_oficial,v_fecha_po);

   if(remplaso is null)THEN

              v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;

      else
              v_nombre_funcionario_abas_qr = remplaso.desc_funcionario1;

      end if;
  else
  		 SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
         into
                    v_id_funcionario_abas_qr_oficial,
                	v_nombre_funcionario_abas_qr_oficial,
                	v_fecha_firma_abas_qr
         FROM wf.testado_wf twf
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
          INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
          WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
          AND te.codigo = 'comite_dpto_abastecimientos'
          and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
          GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;

  remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_abas_qr_oficial);

   if(remplaso is null)THEN

              v_nombre_funcionario_abas_qr = v_nombre_funcionario_abas_qr_oficial;

      else
              v_nombre_funcionario_abas_qr = remplaso.desc_funcionario1;

      end if;
  end if;



  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
  		-- 08-07-2021 (may) modificacion de codigo te.codigo = 'compra' a cotizacion_solicitada
  		SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | '||pro.nro_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        into
                    v_id_funcionario_ag_qr_oficial,
        			v_nombre_funcionario_ag_qr_oficial,
    				v_fecha_firma_ag_qr
        FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN wf.tproceso_wf pro ON twf.id_proceso_wf = pro.id_proceso_wf
            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf
            	AND te.codigo = 'cotizacion_solicitada'
                and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
           GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, pro.nro_tramite;

  	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_ag_qr_oficial,v_fecha_po);

     if(remplaso is null)THEN

              v_nombre_funcionario_ag_qr = v_nombre_funcionario_ag_qr_oficial;

      else
              v_nombre_funcionario_ag_qr = remplaso.desc_funcionario1;

      end if;
  else
   		SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
          			to_char(twf.fecha_reg,'DD/MM/YYYY')as fecha_firma
        into
                    v_id_funcionario_ag_qr_oficial,
        			v_nombre_funcionario_ag_qr_oficial,
    				v_fecha_firma_ag_qr
        FROM wf.testado_wf twf
            INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
            INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
            WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'compra'
            and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
           GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo;

  	remplaso = mat.f_firma_original(v_parametros.id_proceso_wf,v_id_funcionario_ag_qr_oficial);

     if(remplaso is null)THEN

              v_nombre_funcionario_ag_qr = v_nombre_funcionario_ag_qr_oficial;

      else
              v_nombre_funcionario_ag_qr = remplaso.desc_funcionario1;

      end if;
  end if;


  if(v_fecha_solicitud ::date >= v_rango_fecha::date)THEN
      SELECT		twf.id_funcionario,
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
             WHERE twf.id_proceso_wf = v_parametros.id_proceso_wf AND te.codigo = 'comite_unidad_abastecimientos'
             and v_fecha_solicitud::date between vf.fecha_asignacion and COALESCE(vf.fecha_finalizacion,now()::date)
             GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg,vf.nombre_cargo, pro.nro_tramite;

  	remplaso = mat.f_firma_modif(v_parametros.id_proceso_wf,v_id_funcionario_rev_qr_oficial,v_fecha_po);

      if(remplaso is null)THEN

                v_nombre_funcionario_rev_qr = v_nombre_funcionario_rev_qr_oficial;

        else
                v_nombre_funcionario_rev_qr = remplaso.desc_funcionario1;

        end if;
  else
      SELECT		twf.id_funcionario,
        			vf.desc_funcionario1||' | '||vf.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
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

    if(remplaso is null)THEN

              v_nombre_funcionario_rev_qr = v_nombre_funcionario_rev_qr_oficial;

      else
              v_nombre_funcionario_rev_qr = remplaso.desc_funcionario1;

      end if;
  end if;



        /*Fecha para verificar si es menor o mayor*/
          select (case when
             v_fecha_solicitud::date < '01/01/2022'::date then
             'menor'
          else
             'mayor'
          end ) into v_es_mayor;
         /******************************************/

            v_consulta:='select 	s.origen_pedido,
                                    '''||COALESCE (initcap(v_nombre_funcionario_dc_qr),' ')||'''::varchar as aero,
                                    '''||COALESCE (v_fecha_firma_dc_qr,' ')||'''::text as fecha_aero,
                                    '''||COALESCE (initcap(v_nombre_funcionario_ag_qr),' ')||'''::varchar as visto_ag,
                                    '''||COALESCE (v_fecha_firma_ag_qr,' ')||'''::text as fecha_ag,
                                    '''||COALESCE (initcap(v_nombre_funcionario_rev_qr),' ')||'''::varchar as visto_rev,
                                    '''||COALESCE (v_fecha_firma_rev_qr,' ')||'''::text as fecha_rev,
                                    '''||COALESCE (initcap(v_nombre_funcionario_abas_qr),' ')||'''::varchar as visto_abas,
                                    '''||COALESCE (v_fecha_firma_abas_qr,' ')||'''::text as fecha_abas,
                                    s.nro_tramite,
                                    ''''::varchar as estado_firma,
                                    '''||v_es_mayor||'''::varchar as mayor
                                    from mat.tsolicitud s
                                    where s.id_proceso_wf = '||v_parametros.id_proceso_wf;
            --Devuelve la respuesta

            v_consulta=v_consulta||' GROUP BY s.origen_pedido,s.nro_tramite';
    end if;
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
            raise notice 'v_consulta: %', v_consulta;
			--Devuelve la respuesta
			return v_consulta;
		end;
    /*********************************
 	#TRANSACCION:  'MAT_CTS_REP'
 	#DESCRIPCION:	Reporte detalle cotizacion
 	#AUTOR:		Miguel Alejandro Mamani Villegas
 	#FECHA:		02-06-2017
	***********************************/

	elsif(p_transaccion='MAT_CTS_REP')then
    	begin

        if (v_parametros.origen_pedido != 'Todos')then
                v_fill = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and c.adjudicado = ''si''';

        else
                v_fill = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and c.adjudicado = ''si''';
		end if;



       v_consulta:='select	 s.origen_pedido,
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
                                 (pp.codigo || '' - '' || pp.nombre_partida)::varchar as partida
                                 from mat.tsolicitud s
                                 inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                                 inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud
                                 inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                                 inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion and d.revisado = ''si''
                                 inner join wf.testado_wf e on e.id_estado_wf = s.id_estado_wf
                                 inner join wf.ttipo_estado t on t.id_tipo_estado = e.id_tipo_estado
          						 left join conta.torden_trabajo ot on ot.id_orden_trabajo = s.id_matricula
								 left join segu.vusuario vu on c.id_usuario_reg = vu.id_usuario
                                 left join adq.tsolicitud_det sd on d.id_cotizacion_det = sd.id_cotizacion_det
                                 left join param.vcentro_costo cc on  sd.id_centro_costo = cc.id_centro_costo
                                 left join pre.tpartida pp on sd.id_partida = pp.id_partida
                                 where '||v_fill||'
                                 order by origen_pedido, s.nro_tramite ';

			return v_consulta;
		end;



        /*********************************
        #TRANSACCION:  'MAT_LIS_ADJ_REP'
        #DESCRIPCION:	Reporte detalle Adjudicados
        #AUTOR:		Ismael Valdivia
        #FECHA:		16-03-2022
        ***********************************/

        elsif(p_transaccion='MAT_LIS_ADJ_REP')then
            begin

            if (v_parametros.origen_pedido != 'Todos')then
                    v_fill = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and c.adjudicado = ''si''';

            else
                    v_fill = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and c.adjudicado = ''si''';
            end if;


            /*Creamos la tabla para ordenar los datos del WF (Ismael Valdivia 16/03/2022)*/
            create temp table reporte_adjudicados_gm (
                                                                nro_tramite varchar,
                                                                justificacion varchar,
                                                                funcionario_solicitante varchar,
                                                                proveedor_recomendado varchar,
                                                                fecha_solicitud varchar,
                                                                precio_bs numeric,
                                                                precio_proceso numeric,
                                                                precio_adjudicado_bs numeric,
                                                                precio_adjudicado numeric,
                                                                moneda varchar,
                                                                contrato varchar,
                                                                cuce varchar,
                                                                modalidad_contratacion varchar,
                                                                departamento varchar,
                                                                nro_po varchar,
                                                                id_proceso_wf integer
                                                              )on commit drop;
                CREATE INDEX treporte_adjudicados_gm_nro_tramite ON reporte_adjudicados_gm
                USING btree (nro_tramite);

                CREATE INDEX treporte_adjudicados_gm_id_proceso_wf ON reporte_adjudicados_gm
                USING btree (id_proceso_wf);

                CREATE INDEX treporte_adjudicados_gm_precio_adjudicado_bs ON reporte_adjudicados_gm
                USING btree (precio_adjudicado_bs);
            /*****************************************************************************/

            v_insertar_datos = 'insert into reporte_adjudicados_gm (
                                                  nro_tramite,
                                                  justificacion,
                                                  funcionario_solicitante,
                                                  proveedor_recomendado,
                                                  fecha_solicitud,
                                                  precio_bs,
                                                  precio_proceso,
                                                  precio_adjudicado_bs,
                                                  precio_adjudicado,
                                                  moneda,
                                                  contrato,
                                                  cuce,
                                                  modalidad_contratacion,
                                                  departamento,
                                                  nro_po,
                                                  id_proceso_wf
                                                )

            	(select
                             s.nro_tramite,
                             (CASE
                                  WHEN s.origen_pedido = ''Reparación de Repuestos''  THEN
                                     s.motivo_solicitud
                                  ELSE
                                    s.remark
                             END)::varchar as justificacion,
                             f.desc_funcionario1 as funcionario_solicitante,
                             initcap(v.desc_proveedor) as proveedor_recomendado,
                             to_char(s.fecha_solicitud,''DD/MM/YYYY'')as fecha_solicitud,
                             param.f_convertir_moneda(s.id_moneda,1,sum(d.cantidad_det * d.precio_unitario),s.fecha_solicitud,''O'',2,NULL,''si'')::numeric as precio_bs,
                             sum(d.cantidad_det * d.precio_unitario) as precio_proceso,
                             param.f_convertir_moneda(s.id_moneda,1,sum(d.cantidad_det * d.precio_unitario),s.fecha_solicitud,''O'',2,NULL,''si'')::numeric as precio_adjudicado_bs,
                             sum(d.cantidad_det * d.precio_unitario) as precio_adjudicado,
                             mon.codigo,
                             ''Orden de Servicio''::varchar as contrato,
                             s.cuce,
                             (CASE
                                  WHEN s.origen_pedido = ''Reparación de Repuestos''  THEN
                                     ''Contratación Directa Giro Empresarial''
                                  ELSE
                                    ''Contratación Mediante Comparación De Ofertas De Bienes, Obras Y Servicios Especializados En El Extranjero''
                             END)::varchar as modalidad_contratacion,
                             depto.nombre,
                             s.nro_po,
                             s.id_proceso_wf
                         from mat.tsolicitud s
                         inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_solicitante
                         inner join mat.tcotizacion c on c.id_solicitud = s.id_solicitud and c.adjudicado = ''si''
                         inner join param.vproveedor v on v.id_proveedor = c.id_proveedor
                         inner join mat.tcotizacion_detalle d on d.id_cotizacion = c.id_cotizacion and d.revisado = ''si''
                         inner join tes.tobligacion_pago op on op.num_tramite = s.nro_tramite
                         inner join param.tdepto depto on depto.id_depto = op.id_depto
                         inner join param.tmoneda mon on mon.id_moneda = s.id_moneda
                         where '||v_fill||'
                         group by s.nro_tramite,
                                  s.remark,
                                  f.desc_funcionario1,
                                  v.desc_proveedor,
                                  s.fecha_solicitud,
                                  s.nro_po,
                                  mon.codigo,
                                  s.origen_pedido,
                                  s.motivo_solicitud,
                                  s.id_proceso_wf,
                                  s.id_moneda,
                                  depto.nombre,
                                  s.cuce);';
            execute v_insertar_datos;

           v_consulta:='
                        select   gm.nro_tramite,
                                 gm.justificacion,
                                 gm.funcionario_solicitante,
                                 (SELECT
                                      vf.desc_funcionario1
                                  FROM wf.testado_wf twf
                                  INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = twf.id_tipo_estado
                                  INNER JOIN orga.vfuncionario_cargo vf ON vf.id_funcionario = twf.id_funcionario
                                  WHERE twf.id_proceso_wf = gm.id_proceso_wf
                                  AND te.codigo = ''cotizacion''
                                  and gm.fecha_solicitud::date between vf.fecha_asignacion and coalesce(vf.fecha_finalizacion, now())
                                  GROUP BY twf.id_funcionario, vf.desc_funcionario1,twf.fecha_reg
                                  ORDER BY  twf.fecha_reg DESC
                                  limit 1)::varchar as tecnico_adquisiciones,
                                 gm.proveedor_recomendado,
                                 gm.proveedor_recomendado as proveedor_adjudicado,
                                 gm.fecha_solicitud,
                                 gm.precio_bs,
                                 gm.precio_proceso,
                                 gm.precio_adjudicado_bs,
                                 gm.precio_adjudicado,
                                 gm.moneda,
                                 gm.contrato,
                                 gm.cuce,
                                 gm.modalidad_contratacion,
                                 gm.departamento,
                                 gm.nro_po
                        from reporte_adjudicados_gm gm
                        where gm.precio_adjudicado_bs >= '||v_parametros.monto_mayor||'
                        order by gm.fecha_solicitud::date, gm.nro_tramite';

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

ALTER FUNCTION mat.ft_cotizacion_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
