CREATE OR REPLACE FUNCTION mat.ft_control_de_partes (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestion de Materiales
 FUNCION: 		mat.ft_control_de_partes
 DESCRIPCION:   control
 AUTOR: 		MMV
 FECHA:	        03-02-2017
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/


DECLARE
  v_aux					varchar;
    v_record				record;

    v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_auxiliar	integer;
BEGIN
  v_nombre_funcion = 'mat.ft_control_de_partes';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_CONT_PAR'
 	#DESCRIPCION:	Control de Partes
 	#AUTOR:		MMV
 	#FECHA:		03-02-2017
	***********************************/
if(p_transaccion='MAT_CONT_PAR')then

        begin

            SELECT s.estado,
            	   de.revisado into v_record from mat.tdetalle_sol de
            inner join mat.tsolicitud s on s.id_solicitud = de.id_solicitud
            where de.id_detalle = v_parametros.id_detalle;


             if v_record.estado = 'cotizacion' then
            raise exception '%', 'El control de piezaas se realizar en el estado de Desaduanizacion' ;
            end if;
               if v_record.estado = 'compra' then
            raise exception '%', 'El control de piezaas se realizar en el estado de Desaduanizacion' ;
            end if;
             if v_record.estado = 'despachado' then
            raise exception '%', 'El control de piezaas se realizar en el estado de Desaduanizacion' ;
            end if;


            if v_record.revisado = 'si' then
            update mat.tdetalle_sol
            set revisado = 'no'
            where id_detalle = v_parametros.id_detalle;
            end if;

            if v_record.revisado = 'no' then
            update mat.tdetalle_sol
            set revisado = 'si'
            where id_detalle = v_parametros.id_detalle;
            end if;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Revision con exito (id_detalle'||v_parametros.id_detalle||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_parametros.id_detalle::varchar);

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