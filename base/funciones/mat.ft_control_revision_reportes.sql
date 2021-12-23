CREATE OR REPLACE FUNCTION mat.ft_control_revision_reportes (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion Materiales
 FUNCION: 		mat.ft_control_revision_reportes
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'cola.tprioridad'
 AUTOR: 		Ismael Valdivia
 FECHA:	        23-12-2021 10:35:33
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
    v_json	varchar;

BEGIN

    v_nombre_funcion = 'mat.ft_control_revision_reportes';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_REV_REPOR_SEL'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR: 		Ismael Valdivia
 	#FECHA:	        23-12-2021 10:35:33
	***********************************/

	if(p_transaccion='MAT_REV_REPOR_SEL')then

        begin
        	with t_tramites_po as (select sol.id_proceso_wf,
                                   regexp_replace(sol.nro_po, '[^0-9]+', '', 'g')::numeric as po
                            from mat.tsolicitud sol
                            where
                            regexp_replace(sol.nro_po, '[^0-9]+', '', 'g') != ''
                            and sol.fecha_po between '01/01/2021' and '23/12/2021')

          SELECT TO_JSON(ROW_TO_JSON(jsonData) :: TEXT) #>> '{}' as json
          into v_json
          from (select (
                 select ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(t_tramites_po)))
                 FROM (
                          select *
                          from t_tramites_po tra
                          where tra.po between v_parametros.po_inicio and v_parametros.po_final
                      ) t_tramites_po
                ) AS t_tramites_po
          ) jsonData;



			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'json',v_json);
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje',v_json);

            --Devuelve la respuesta
            return v_resp;

		end;

	else

    	raise exception 'Transaccion inexistente: %',p_transaccion;

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

ALTER FUNCTION mat.ft_control_revision_reportes (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
