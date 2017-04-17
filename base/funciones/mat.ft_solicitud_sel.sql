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
    p_id_proceso_wf integer;
    v_id_proceso_wf_prev integer;
    v_orden				varchar;
	v_filtro			varchar;
    v_funcionario_wf    record;
    v_record    		record;
    v_id_usuario_rev	record;
    v_origen 			varchar;
    v_filtro_repo       VARCHAR;
    v_origen_pedido     VARCHAR;
    v_id_proceso_wf_firma integer;
    v_usuario				integer;
BEGIN

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
    		--Sentencia de la consulta
            SELECT vfcl.id_oficina, vfcl.nombre_cargo,  vfcl.oficina_nombre,
            tf.id_funcionario, vfcl.desc_funcionario1 INTO v_record
            FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            INNER JOIN orga.vfuncionario_cargo_lugar vfcl on vfcl.id_funcionario = tf.id_funcionario
            WHERE tu.id_usuario = p_id_usuario ;
        IF p_administrador 	THEN
				v_filtro = ' 0=0 AND ';

      	ELSIF v_parametros.pes_estado = 'borrador_reg' THEN

            	v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||
                'OR tew.id_funcionario ='||p_id_usuario||
                'OR ewb.id_funcionario ='||p_id_usuario||'and ';

       ELSIF v_parametros.pes_estado = 'vobo_area_reg'   THEN
         v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||'AND';

    	ELSIF v_parametros.pes_estado = 'revision_reg' THEN
        v_filtro = 'sol.id_usuario_reg = '||p_id_usuario||'AND';

        ELSIF v_parametros.pes_estado = 'finalizado_reg' THEN
        v_filtro = 'sol.id_usuario_reg ='||p_id_usuario||
                ' AND';

       ELSIF  (v_parametros.tipo_interfaz = 'VistoBueno'and v_record.nombre_cargo ='Gerente de Mantenimiento' )THEN
      	 			select fu.id_funcionario,
                	count(fu.id_funcionario)::varchar as cant_reg
         			into v_id_usuario_rev
                    from wf.testado_wf es
                    inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                    inner join segu.tusuario u on u.id_persona = fu.id_persona
                   	LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = es.id_estado_wf
                    LEFT JOIN mat.tsolicitud  so ON so.id_estado_wf_firma = es.id_estado_wf
                    WHERE   so.estado_firma = 'vobo_area' and so.origen_pedido = 'Gerencia de Mantenimiento'
                    GROUP BY fu.id_funcionario;
            IF(v_id_usuario_rev.cant_reg IS NULL)THEN
         	v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND  ';
         	ELSE
         	v_filtro = '(ewb.id_funcionario = '||v_id_usuario_rev.id_funcionario||' OR  tew.id_funcionario = '||v_record.id_funcionario||') AND';
         	END IF;
       ELSIF  (v_parametros.tipo_interfaz = 'VistoBueno' and v_record.nombre_cargo = 'Técnico Planificación de Servicios' )THEN
       				select fu.id_funcionario,
                	count(fu.id_funcionario)::varchar as cant_reg
         			into v_id_usuario_rev
                    from wf.testado_wf es
                    inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                    inner join segu.tusuario u on u.id_persona = fu.id_persona
                   	LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = es.id_estado_wf
                    LEFT JOIN mat.tsolicitud  so ON so.id_estado_wf_firma = es.id_estado_wf
                    WHERE   so.estado_firma = 'vobo_area' and so.origen_pedido = 'Gerencia de Operaciones'
                    GROUP BY fu.id_funcionario;

        	IF(v_id_usuario_rev.cant_reg IS NULL)THEN
         	v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND  ';
         	ELSE
         	v_filtro = '(ewb.id_funcionario = '||v_id_usuario_rev.id_funcionario||' OR  tew.id_funcionario = '||v_record.id_funcionario||') AND';
         	END IF;
       ELSIF  (v_parametros.tipo_interfaz =  'VistoBueno' and v_record.nombre_cargo ='Jefe Departamento Ingenieria - Planeamiento' )THEN
        			select fu.id_funcionario,
                	count(fu.id_funcionario)::varchar as cant_reg
         			into v_id_usuario_rev
                    from wf.testado_wf es
                    inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                    inner join segu.tusuario u on u.id_persona = fu.id_persona
                   	LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = es.id_estado_wf
                    LEFT JOIN mat.tsolicitud  so ON so.id_estado_wf_firma = es.id_estado_wf
                    WHERE  so.estado_firma = 'vobo_aeronavegabilidad'
                    GROUP BY fu.id_funcionario;
        	IF(v_id_usuario_rev.cant_reg IS NULL)THEN
         	v_filtro = 'ewb.id_funcionario = '||v_record.id_funcionario||' AND  ';
         	ELSE
         	v_filtro = '(ewb.id_funcionario = '||v_id_usuario_rev.id_funcionario||' OR  tew.id_funcionario = '||v_record.id_funcionario||') AND';
         	END IF;
       ELSIF (v_parametros.tipo_interfaz =  'PedidoOperacion' and v_record.nombre_cargo = 'Auxiliar Apoyo Control Viáticos' or
          		v_parametros.tipo_interfaz = 'PedidoMantenimiento' and v_record.nombre_cargo = 'Auxiliar Apoyo Control Viáticos' or
           		v_parametros.tipo_interfaz ='PerdidoAlmacen'and v_record.nombre_cargo = 'Auxiliar Apoyo Control Viáticos')THEN
					select u.id_usuario,
                	count(u.id_usuario)::varchar as cant_reg
         			into v_id_usuario_rev
                    from wf.testado_wf es
                    inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                    inner join segu.tusuario u on u.id_persona = fu.id_persona
                    inner join orga.vfuncionario_cargo_lugar fc on fc.id_funcionario =es.id_funcionario
                   	LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = es.id_estado_wf
                    LEFT JOIN mat.tsolicitud  so ON so.id_estado_wf = es.id_estado_wf
                    WHERE so.estado in('cotizacion','cotizacion_solicitada','cotizacion_sin_respuesta','compra') and fc.nombre_cargo ='Auxiliar Apoyo Control Viáticos'
               		GROUP BY u.id_usuario;
			IF(v_id_usuario_rev.cant_reg IS NULL)THEN
         		v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
         	ELSE
         		v_filtro = '(sol.id_usuario_mod = '||v_id_usuario_rev.id_usuario||' OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
         	END IF;
       ELSIF (v_parametros.tipo_interfaz =  'PedidoOperacion' and v_record.nombre_cargo = 'Auxiliar Suministros' or v_parametros.tipo_interfaz = 'PedidoMantenimiento'
        		 and v_record.nombre_cargo = 'Auxiliar Suministros' or v_parametros.tipo_interfaz ='PerdidoAlmacen' and v_record.nombre_cargo = 'Auxiliar Suministros' )THEN
					select u.id_usuario,
                	count(u.id_usuario)::varchar as cant_reg
         			into v_id_usuario_rev
                    from wf.testado_wf es
                    inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                    inner join segu.tusuario u on u.id_persona = fu.id_persona
                    inner join orga.vfuncionario_cargo_lugar fc on fc.id_funcionario =es.id_funcionario
                   	LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = es.id_estado_wf
                    LEFT JOIN mat.tsolicitud  so ON so.id_estado_wf = es.id_estado_wf
                    WHERE so.estado in('cotizacion','cotizacion_solicitada','cotizacion_sin_respuesta','compra') and  fc.nombre_cargo ='Auxiliar Suministros'
               		GROUP BY u.id_usuario;

      		IF(v_id_usuario_rev.cant_reg IS NULL)THEN
         	v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND  ';
        	ELSE
         	v_filtro = '(sol.id_usuario_mod = '||v_id_usuario_rev.id_usuario||' OR  tew.id_funcionario = '||v_record.id_funcionario||' ) AND';
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
      END IF;
			v_consulta:='select
						sol.id_solicitud,
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
                        f.desc_funcionario1,
                        ot.desc_orden as matricula,
                        sol.tipo_reporte,
                        sol.mel,
                        sol.nro_no_rutina,
                        pro.desc_proveedor,
                        pxp.list (de.nro_parte) as nro_parte,
                        sol.nro_justificacion,
                        sol.fecha_cotizacion,
                         (select count(*)
                             from unnest(pwf.id_tipo_estado_wfs) elemento
                             where elemento = ew.id_tipo_estado) as contador_estados,

                             mat.control_fecha_requerida(now()::date, sol.fecha_requerida, ''CONTROL_FECHA'')::VARCHAR as control_fecha,
                             sol.estado_firma,
                             sol.id_proceso_wf_firma,
                             sol.id_estado_wf_firma,
                             (select count(*)
                             from unnest(pwfb.id_tipo_estado_wfs) elemento
                             where elemento = ewb.id_tipo_estado) as contador_estados_firma,
                             ti.nombre_estado,
                             tip.nombre_estado as nombre_estado_firma

                        from mat.tsolicitud sol
						inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                        inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                        left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
						left join param.vproveedor pro on pro.id_proveedor =sol.id_proveedor
                        left join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud
                        left join wf.testado_wf tew on tew.id_estado_wf = sol.id_estado_wf
                        LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                        LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario
                        inner join wf.testado_wf ew on ew.id_estado_wf = sol.id_estado_wf
                        inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf
						LEFT join wf.testado_wf ewb on ewb.id_estado_wf = sol.id_estado_wf_firma
                        LEFT join wf.tproceso_wf pwfb on pwfb.id_proceso_wf = sol.id_proceso_wf_firma
                        inner join wf.ttipo_estado ti on ti.id_tipo_estado = ew.id_tipo_estado
                        LEFT join wf.ttipo_estado tip on tip.id_tipo_estado = ewb.id_tipo_estado
                        where  '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||'GROUP BY sol.id_solicitud,
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
						usu1.cuenta,
						usu2.cuenta,
                        f.desc_funcionario1,
                        ot.desc_orden,
                        sol.tipo_reporte,
                        sol.mel,
                        sol.nro_no_rutina,
                        pro.desc_proveedor,
                        sol.nro_justificacion,
                        sol.fecha_cotizacion,
                        contador_estados,
         		     	control_fecha,
                        sol.estado_firma,
                        contador_estados_firma,
                        ti.nombre_estado,
                        nombre_estado_firma order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta

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
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(sol.id_solicitud)
						from mat.tsolicitud sol
						inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                        inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                        left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
						left join param.vproveedor pro on pro.id_proveedor =sol.id_proveedor
                        left join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud
                        left join wf.testado_wf tew on tew.id_estado_wf = sol.id_estado_wf
                        LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                        LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario
                        inner join wf.testado_wf ew on ew.id_estado_wf = sol.id_estado_wf
                        inner join wf.tproceso_wf pwf on pwf.id_proceso_wf = sol.id_proceso_wf
                        LEFT join wf.testado_wf ewb on ewb.id_estado_wf = sol.id_estado_wf_firma
                        LEFT join wf.tproceso_wf pwfb on pwfb.id_proceso_wf = sol.id_proceso_wf_firma
                        inner join wf.ttipo_estado ti on ti.id_tipo_estado = ew.id_tipo_estado
                        LEFT join wf.ttipo_estado tip on tip.id_tipo_estado = ewb.id_tipo_estado
                        where ';

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
                                sol.motivo_solicitud,
                                sol.observaciones_sol,
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
                                sol.estado_firma,
                                to_char( sol.fecha_mod,''DD/MM/YYYY'') as fecha_fir

          						from mat.tsolicitud sol
                                inner join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud
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
 	#DESCRIPCION:	Control de firmas qr
 	#AUTOR:	 Ale MV
 	#FECHA:		23-12-2016 13:13:01
	***********************************/
    elsif(p_transaccion='MAT_FRI_SEL')then

		begin
        select sou.id_proceso_wf_firma
        		into
                v_id_proceso_wf_firma
        from mat.tsolicitud sou
        where sou.id_proceso_wf= v_parametros.id_proceso_wf;

   create temp table firma_funcionarios(
            nombre_estado varchar,
            desc_funcionario2 text,
            fecha_ini text

           )on commit drop;
  			FOR v_firmas IN (   select s.id_estado_wf_firma
								from mat.tsolicitud s
                                where s.id_proceso_wf_firma= v_id_proceso_wf_firma)
                                LOOP
                                raise  notice 'estasd %', v_firmas.id_estado_wf_firma;
                                INSERT INTO firma_funcionarios(
                                WITH RECURSIVE estado( id_proceso_wf,id_estado_wf,id_funcionario,id_estado_anterior,id_tipo_estado)as(
								select et.id_proceso_wf, et.id_estado_wf, et.id_funcionario,et.id_estado_anterior, et.id_tipo_estado
								from wf.testado_wf et
								where et.id_proceso_wf= v_id_proceso_wf_firma
								UNION
								select et.id_proceso_wf, et.id_estado_wf, et.id_funcionario,et.id_estado_anterior, et.id_tipo_estado
                                from wf.testado_wf et, estado
                                WHERE et.id_estado_wf =estado.id_estado_anterior
								)select  te.nombre_estado,
                                         initcap(fu.desc_funcionario1) as funcionario_bv ,
                                         to_char( pwf.fecha_reg,'DD/MM/YYYY')as fecha_ini
                                         from estado es
                                         INNER JOIN wf.ttipo_estado te on te.id_tipo_estado= es.id_tipo_estado
                                         INNER JOIN wf.tproceso_wf pwf on pwf.id_proceso_wf=es.id_proceso_wf
                                         INNER JOIN wf.ttipo_proceso tp on tp.id_tipo_proceso=pwf.id_tipo_proceso
                                         INNER JOIN mat.tsolicitud sol on sol.id_proceso_wf_firma=pwf.id_proceso_wf
                                         INNER JOIN orga.vfuncionario fu on fu.id_funcionario = es.id_funcionario
     									 ORDER BY es.id_estado_wf ASC);
                                         END LOOP;

            v_consulta:='select *
            			from firma_funcionarios';

            --Devuelve la respuesta
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
        v_filtro_repo = ' s.fecha_solicitud >='''||v_parametros.fecha_ini||''' and s.fecha_solicitud <= '''||v_parametros.fecha_fin||'''and s.origen_pedido='''||v_parametros.origen_pedido||''' and t.id_tipo_estado::integer in ('||v_parametros.estado||') and ';
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
        END IF;

            v_consulta:=' select
            					s.id_solicitud,
                                s.nro_tramite,
                                s.origen_pedido,
                                s.estado,
                                f.desc_funcionario1,
                                to_char(s.fecha_solicitud,''DD/MM/YYYY'')as fecha_solicitud,
                                d.nro_parte,
  								d.nro_parte_alterno,
                                d.descripcion,
                                d.cantidad_sol,
                                t.id_tipo_estado,
                                d.id_solicitud as id
                                from mat.tsolicitud s
                                inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
                                inner join mat.tdetalle_sol d on d.id_solicitud = s.id_solicitud
                                inner join wf.testado_wf e on e.id_estado_wf = s.id_estado_wf
                                inner join wf.ttipo_estado t on t.id_tipo_estado = e.id_tipo_estado
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
								t.codigo
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
								t.codigo
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
								t.codigo
								from wf.ttipo_estado t
								inner join wf.ttipo_proceso pr on pr.id_tipo_proceso = t.id_tipo_proceso and pr.nombre = ''Requerimiento Almacenes Consumibles o Rotables'' and t.estado_reg = ''activo''
                                where';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
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