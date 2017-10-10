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
BEGIN

    v_nombre_funcion = 'mat.f_get_detalle_html';
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