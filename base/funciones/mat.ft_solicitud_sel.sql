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
    v_id_usuario_rev	integer;
    v_origen 			varchar;

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
        if p_administrador 	THEN

          v_filtro = ' 0=0 AND ';
        ELSIF v_parametros.pes_estado = 'borrador' THEN

            v_filtro = ' sol.id_usuario_reg = '||p_id_usuario||
                ' AND ';

       ELSIF  (v_parametros.pes_estado = 'visto_bueno' )THEN

         select u.id_persona
         into v_id_usuario_rev
                    from wf.testado_wf es
                    inner JOIN orga.tfuncionario fu on fu.id_funcionario = es.id_funcionario
                    inner join segu.tusuario u on u.id_persona = fu.id_persona
                   	LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = es.id_estado_wf
                    LEFT JOIN mat.tsolicitud  so ON so.id_estado_wf = es.id_estado_wf
                    WHERE so.estado = 'vobo_area' and so.origen_pedido = 'Gerencia de Mantenimiento' OR so.estado = 'vobo_area' and so.origen_pedido = 'Gerencia de Operaciones' OR so.estado = 'vobo_aeronavegabilidad' and so.origen_pedido = 'Gerencia de Mantenimiento';

         v_filtro = '(sol.id_usuario_mod = '||v_id_usuario_rev||' OR  tew.id_funcionario = '||v_record.id_funcionario||') AND';


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
                     	pxp.list (de.nro_parte) as nro_partes,
                        sol.nro_justificacion
                        from mat.tsolicitud sol
						inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                        inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                        left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
						left join param.vproveedor pro on pro.id_proveedor =sol.id_proveedor
                        inner join mat.tdetalle_sol de on de.id_solicitud = sol.id_solicitud
                        left join wf.testado_wf tew on tew.id_estado_wf = sol.id_estado_wf
                        LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                        LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario
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
                        sol.nro_justificacion order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

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
			v_consulta:='select count(id_solicitud)
					  	from mat.tsolicitud sol
						inner join segu.tusuario usu1 on usu1.id_usuario = sol.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sol.id_usuario_mod
                        inner join orga.vfuncionario f on f.id_funcionario = sol.id_funcionario_sol
                        left join conta.torden_trabajo ot on ot.id_orden_trabajo = sol.id_matricula
                         left join wf.testado_wf tew on tew.id_estado_wf = sol.id_estado_wf
                        LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                        LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario

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
								inner join conta.tgrupo_ot_det gr on gr.id_orden_trabajo = ord.id_orden_trabajo and gr.id_grupo_ot = 1
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
                                    inner join segu.vpersona p on p.id_persona= f.id_persona and f.id_funcionario IN(308,307,309,1483,304,306,1022,766,1482)
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
                                sol.nro_tramite,
                                de.nro_parte::text,
                                de.referencia::text,
                                de.descripcion,
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
                                de.nro_parte_alterno
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

   create temp table firma_funcionarios(
            nombre_estado varchar,
            desc_funcionario2 text,
            fecha_ini text,
            nro_tramite varchar,
            tipo_solicitud varchar,
            fecha_solicitud text
           )on commit drop;
  			FOR v_firmas IN (   select s.id_estado_wf
								from mat.tsolicitud s
                                where s.id_proceso_wf= v_parametros.id_proceso_wf)
                                LOOP
                                raise  notice 'estasd %', v_firmas.id_estado_wf;
                                INSERT INTO firma_funcionarios(
                                WITH RECURSIVE estado( id_proceso_wf,id_estado_wf,id_funcionario,id_estado_anterior,id_tipo_estado)as(
								select et.id_proceso_wf, et.id_estado_wf, et.id_funcionario,et.id_estado_anterior, et.id_tipo_estado
								from wf.testado_wf et
								where et.id_proceso_wf= v_parametros.id_proceso_wf
								UNION
								select et.id_proceso_wf, et.id_estado_wf, et.id_funcionario,et.id_estado_anterior, et.id_tipo_estado
                                from wf.testado_wf et, estado
                                WHERE et.id_estado_wf =estado.id_estado_anterior
								)select  te.nombre_estado,
                                         initcap(fu.desc_funcionario1) as funcionario_bv ,
                                         to_char( pwf.fecha_ini,'DD/MM/YYYY')as fecha_ini,
                                         sol.nro_tramite,
                                         sol.tipo_solicitud,
                                         to_char(  sol.fecha_solicitud,'DD/MM/YYYY')as fecha_solicitud
                                         from estado es
                                         INNER JOIN wf.ttipo_estado te on te.id_tipo_estado= es.id_tipo_estado
                                         INNER JOIN wf.tproceso_wf pwf on pwf.id_proceso_wf=es.id_proceso_wf
                                         INNER JOIN wf.ttipo_proceso tp on tp.id_tipo_proceso=pwf.id_tipo_proceso
                                         INNER JOIN mat.tsolicitud sol on sol.id_proceso_wf=pwf.id_proceso_wf
                                         INNER JOIN orga.vfuncionario fu on fu.id_funcionario = es.id_funcionario
     									 ORDER BY es.id_estado_wf ASC);
                                         END LOOP;

            v_consulta:='select *
            			from firma_funcionarios';

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