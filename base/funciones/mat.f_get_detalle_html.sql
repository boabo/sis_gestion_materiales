CREATE OR REPLACE FUNCTION mat.f_get_detalle_html (
  p_id_solicitud integer
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestion de Materiales
 FUNCION: 		mat.f_get_detalle_html
 DESCRIPCION:   Funcion que recupera el detalle de unas solicitud para enviar al siguiente estado.
 AUTOR: 		 (FEA)
 FECHA:	        30-06-2017 15:15:26
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_resp		            varchar;
	v_nombre_funcion        text;
	v_record 				record;
	--v_detalle				varchar = '';
    v_tipo_tramite			varchar;
    v_descripcion			varchar;
    v_condicion				varchar;
BEGIN

    v_nombre_funcion = 'mat.f_get_detalle_html';

    /*Aqui aumentamos la condicion para que se recupere el detalle que mandara los BoA Rep (Ismael Valdivia 05/05/2020)*/
        select
        	substr(sol.nro_tramite::text, 1, 2),
            sol.condicion,
            (select list (cat.codigo)
            from param.tcatalogo cat
            inner join param.tcatalogo_tipo tip on tip.id_catalogo_tipo = cat.id_catalogo_tipo
            where tip.nombre = 'tsolicitud' and cat.descripcion in (SELECT UNNEST(REGEXP_SPLIT_TO_ARRAY(sol.condicion, ',')))
            )
            into
            v_tipo_tramite,
            v_descripcion,
            v_condicion
        from mat.tsolicitud sol
        where sol.id_solicitud = p_id_solicitud;
    /*******************************************************************************************************************/

    IF(v_tipo_tramite = 'GR')THEN
        v_resp = '<table border="1">
                      <tbody>
                            <tr>
                            <th style="text-align: center; vertical-align: middle;"><b>PART NUMBER</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>QTY</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>DESCRIPTION</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>SERIAL</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>CD</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>REPAIR INSTRUCTION</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>MONTO $US</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>SERVICE TO BE PERFORMED</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>DELIVERY TIME</b></th>
                            <th style="text-align: center; vertical-align: middle;"><b>WARRANTY</b></th>
                            </tr>';
        FOR v_record IN (SELECT tds.nro_parte, tds.referencia, tds.descripcion, tds.condicion_det, tds.cantidad_sol, tum.codigo
                        FROM  mat.tdetalle_sol tds
                        INNER JOIN mat.tunidad_medida tum ON tum.id_unidad_medida = tds.id_unidad_medida
                        WHERE tds.id_solicitud = p_id_solicitud)
    	LOOP
            v_resp = v_resp ||'<tr>
                                  <td style="text-align: center; vertical-align: middle;">'||v_record.nro_parte||'</td>
                                  <td style="text-align: center; vertical-align: middle;">'||v_record.cantidad_sol||'</td>
                                  <td style="text-align: center; vertical-align: middle;">'||v_record.descripcion||'</td>
                                  <td style="text-align: center; vertical-align: middle;">'||v_record.referencia||'</td>
                                  <td style="text-align: center; vertical-align: middle;">AR</td>
                                  <td style="text-align: center; vertical-align: middle;">'||v_record.condicion_det||'</td>
                                  <td style="text-align: center; vertical-align: middle;"> </td>
                                  <td style="text-align: center; vertical-align: middle;"> </td>
                                  <td style="text-align: center; vertical-align: middle;"> </td>
                                  <td style="text-align: center; vertical-align: middle;"> </td>
                              </tr>
                            </tbody>';
        END LOOP;
    ELSE
    	v_resp = '<table border="1"><tr>
                                  <th>Nro. Parte</th>
                                  <th>Referencia</th>
                                  <th>Descripción</th>
                                  <th>Cantidad</th>
                                  <th>Unidad de Medida</th>
    							</tr>';
          FOR v_record IN (SELECT tds.nro_parte, tds.referencia, tds.descripcion, tds.cantidad_sol, tum.codigo
                          FROM  mat.tdetalle_sol tds
                          INNER JOIN mat.tunidad_medida tum ON tum.id_unidad_medida = tds.id_unidad_medida
                          WHERE tds.id_solicitud = p_id_solicitud)LOOP
              v_resp = v_resp ||'<tr>
                                    <td>'||v_record.nro_parte||'</td>
                                    <td>'||v_record.referencia||'</td>
                                    <td>'||v_record.descripcion||'</td>
                                    <td>'||v_record.cantidad_sol||'</td>
                                    <td>'||v_record.codigo||'</td>
                                 </tr>';
          END LOOP;

    END IF;
    v_resp = v_resp||'
    		</table>';
    return v_resp;

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

ALTER FUNCTION mat.f_get_detalle_html (p_id_solicitud integer)
  OWNER TO postgres;
