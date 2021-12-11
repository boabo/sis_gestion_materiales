CREATE OR REPLACE FUNCTION mat.ft_acta_conformidad_final_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Gestion de Materiales
 FUNCION: 		mat.ft_acta_conformidad_final_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'cola.tservicio'
 AUTOR: 		 (Ismael Valdivia)
 FECHA:	        05-08-2021 10:30:11
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

    v_id_funcionario			integer;
    v_id_usuario_firma		integer;

BEGIN

    v_nombre_funcion = 'mat.ft_acta_conformidad_final_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_UPDATE_ACTA'
 	#DESCRIPCION:	Actualizacion de registro de datos
 	#AUTOR:		Ismael Valdivia
 	#FECHA:		05-08-2021 10:30:11
	***********************************/

	if(p_transaccion='MAT_UPDATE_ACTA')then

        begin

           select usu.id_persona
           into v_id_funcionario
           from segu.vusuario usu
           inner join orga.vfuncionario fun on fun.id_persona = usu.id_persona
           where usu.id_usuario = p_id_usuario;

           if(not exists (select 1
                from mat.tacta_conformidad_final acta
                inner join orga.tfuncionario fun on fun.id_funcionario = acta.id_funcionario_firma
                where acta.id_solicitud = v_parametros.id_solicitud and
                (v_id_funcionario = fun.id_funcionario or p_administrador = 1))) then
            	raise exception 'Solo el solicitante y el usuario que registro la solicitud pueden generar la conformidad';
            end if;


            select usu.id_usuario
            into v_id_usuario_firma
            from  mat.tacta_conformidad_final acta
            inner join orga.tfuncionario fun on acta.id_funcionario_firma = fun.id_funcionario
            inner join segu.tusuario usu on fun.id_persona = usu.id_persona
            where acta.id_solicitud = v_parametros.id_solicitud;

            if v_id_usuario_firma is null then
            	raise exception 'El funcionario del proceso no tiene usuario en el sistema para firmar el acta de conformidad';
            end if;


            update mat.tacta_conformidad_final
             set
             fecha_inicio = v_parametros.fecha_inicio,
             fecha_final = v_parametros.fecha_fin,
             conformidad_final = v_parametros.conformidad_final,
             fecha_conformidad = v_parametros.fecha_conformidad_final,
             observaciones = v_parametros.observaciones,
             fecha_mod = now(),
             id_usuario_mod = p_id_usuario
             where id_solicitud = v_parametros.id_solicitud;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se modificaron los datos de la conformidad exitosamente');
            v_resp = pxp.f_agrega_clave(v_resp,'id_solicitud',v_parametros.id_solicitud::varchar);
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

ALTER FUNCTION mat.ft_acta_conformidad_final_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
