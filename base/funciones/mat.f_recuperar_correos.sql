CREATE OR REPLACE FUNCTION mat.f_recuperar_correos (
  p_ids_prov integer []
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestion de Materiales
 FUNCION: 		mat.f_recuperar_correos
 DESCRIPCION:   Funcion que recupera los correos de los proveedores para enviar cotizacion de compra.
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
	v_index					integer;
    v_tam					integer;
    v_correos_prov			VARCHAR[];
	v_valor					varchar;
BEGIN

    v_nombre_funcion = 'mat.f_recuperar_correos';
	IF(p_ids_prov IS NOT NULL)THEN
		v_tam = array_length(p_ids_prov,1);
	--IF v_tam IS NOT NULL  THEN
        FOR v_index IN 1..v_tam LOOP
          SELECT tp.email
          INTO v_valor
          FROM param.vproveedor tp
          WHERE tp.id_proveedor = p_ids_prov[v_index];
          IF v_valor <> '' THEN
	          v_correos_prov[v_index] = v_valor;
          END IF;
        END LOOP;
        v_resp = array_to_string(v_correos_prov,';');
    ELSE
    	v_resp = '';
    END IF;


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