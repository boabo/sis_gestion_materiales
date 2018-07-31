CREATE OR REPLACE FUNCTION mat.f_correo_reformulacion_pac (
  p_id_proceso_wf integer,
  p_monto numeric,
  p_id_moneda integer,
  p_tipo varchar
)
RETURNS void AS
$body$
DECLARE

	v_nombre_funcion   	text;
	v_resp				varchar;
    v_registros			record;
    v_asunto					varchar;
	v_destinatorio				varchar;
	v_template					varchar;
    v_id_alarma					integer[];
	v_titulo					varchar;
	v_clase						varchar;
	v_parametros_ad				varchar;
	v_acceso_directo			varchar;
    v_pac						record;
    --fea reformulación
    v_cambio_compra				numeric;
    v_objeto_contrato			text;
    v_record					record;

BEGIN
  v_nombre_funcion = 'mat.f_correo_reformulacion_pac';

  if(select 1
  	from mat.tsolicitud_pac
  	where id_proceso_wf = p_id_proceso_wf)then

    update mat.tsolicitud_pac set
    	monto = p_monto,
      	id_moneda = p_id_moneda,
      	tipo = 	p_tipo
    where id_proceso_wf = p_id_proceso_wf;

  else
  	INSERT INTO mat.tsolicitud_pac(
      id_proceso_wf,
      monto,
      id_moneda,
      tipo
    )
	VALUES (
      p_id_proceso_wf,
      p_monto,
      p_id_moneda,
      p_tipo
	);
  end if;





  select 	so.nro_tramite,
            so.origen_pedido,
            to_char( so.fecha_solicitud,'DD/MM/YYYY') as fecha_solicitud,
            initcap (fu.desc_funcionario1) as funcionario,
            ot.desc_orden as matricula,
            initcap( so.motivo_solicitud) as motivo_solicitud,
            so.observaciones_sol,
            so.tipo_falla,
            to_char( so.fecha_requerida,'DD/MM/YYYY') as fecha_requerida ,
            so.tipo_solicitud,
            so.id_solicitud
            into v_registros
  from mat.tsolicitud so
  inner join mat.tdetalle_sol de on de.id_solicitud = so.id_solicitud
  left join conta.torden_trabajo ot on ot.id_orden_trabajo = so.id_matricula
  inner join orga.vfuncionario fu on fu.id_funcionario = so.id_funcionario_sol
  where so.id_proceso_wf = p_id_proceso_wf
  group by
  so.nro_tramite,
  so.origen_pedido,
  so.fecha_solicitud,
  fu.desc_funcionario1,
  ot.desc_orden,
  so.motivo_solicitud,
  so.observaciones_sol,
  so.tipo_falla,
  so.fecha_requerida,
  so.tipo_solicitud,
  so.id_solicitud ;

  --tipo de cambio compra
  select tc.compra
  into v_cambio_compra
  from param.ttipo_cambio tc
  where tc.fecha = current_date and tc.id_moneda = 2;

  select  pa.id_proceso_wf,
          pa.monto,
          mo.codigo_internacional,
          pa.tipo
          into
          v_pac
  from mat.tsolicitud_pac pa
  inner join param.tmoneda mo on mo.id_moneda = pa.id_moneda
  where pa.id_proceso_wf = p_id_proceso_wf;

  if v_cambio_compra * v_pac.monto >= 20000 then
    v_objeto_contrato = '<ol>';
    for v_record in (select tds.referencia, tds.nro_parte, tds.nro_parte_alterno
                    from mat.tdetalle_sol tds
                    where tds.id_solicitud = v_registros.id_solicitud) loop
      v_objeto_contrato = v_objeto_contrato||'<li>'||v_record.referencia||' P/N '||v_record.nro_parte||';'||v_record.nro_parte_alterno||'</li>';
    end loop;
    v_objeto_contrato = v_objeto_contrato||'</ol>';

    v_template = '<p><span><strong>Nro. Tramite:</strong> </span>'||v_registros.nro_tramite||'<br/>
                      <strong>Origen Pedido:</strong> '||v_registros.origen_pedido||'<br/>
                      <strong>Fechas solicitud</strong>: '||v_registros.fecha_solicitud||'
                      </p>
                      <p><span style="text-decoration: underline;"><strong>Solicitante</strong></span><br/><br/>
                      '||v_registros.funcionario||'</p>
                      <p><span style="text-decoration: underline;"><strong>Justificacion de Necesidad</strong></span></p>
                      <p><strong>Fecha Requerida:</strong> '||v_registros.fecha_requerida||' <strong>Tipo de solicitus</strong>: '||v_registros.tipo_solicitud||'</p>
                      <p><span style="text-decoration: underline;"><strong>Detalle solicitud</strong></span></p>

                      <table boder = "1">
                          <tr>
                            <th><span style="font-weight:bold">Tipo de Contratación</span></th>
                            <th><span style="font-weight:bold">Objeto de Contratación </span></th>
                            <th><span style="font-weight:bold">Mes Estimado</span></th>
                            <th><span style="font-weight:bold">Precio Referencial Bs.</span></th>
                            <th><span style="font-weight:bold">Precio Referencial $us.</span></th>
                          </tr>
                          <tr>
                            <td>'||case when v_pac.tipo = 'bien' then 'Bien' else 'Servicio' end||'</td>
                            <td>'||v_objeto_contrato||'</td>
                            <td>'||pxp.f_get_mes_of_fecha_es(v_registros.fecha_requerida::date)||'</td>
                            <td>'||v_cambio_compra*v_pac.monto||' Bs.</td>
                            <td>'||v_pac.monto||' $us.</td>
                          </tr>
                      </table>

                      <p><span style="text-decoration: underline;"><strong>Observaciones</strong></span></p>
                      <p>'||v_registros.observaciones_sol||'</p>';


          v_asunto = 'Solicitud de Reformulación PAC';
          v_titulo = 'Solicitud Modificacion PAC';
          v_acceso_directo = '';
          v_clase = '';
          v_parametros_ad = '{}';

          v_id_alarma[1]:=param.f_inserta_alarma(		null,
                                                      v_template ,    --descripcion alarmce
                                                      COALESCE(v_acceso_directo,''),--acceso directo
                                                      now()::date,
                                                      'notificacion',
                                                      '',   -->
                                                      1,
                                                      v_clase,
                                                      v_titulo,--titulo
                                                     '',
                                                      null,  --destino de la alarma
                                                      v_asunto,
                                                      'mvidaurre@boa.bo,gvelasquez@boa.bo,miguel.ale19934@gmail.com,abastecimiento@boa.bo'
                                                      );

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