CREATE OR REPLACE FUNCTION mat.ft_firmas_documentos_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_firmas_documentos_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tfirmas_documentos'
 AUTOR: 		 (ismael.valdivia)
 FECHA:	        16-08-2022 13:50:13
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				16-08-2022 13:50:13								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tfirmas_documentos'
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_firma_documento	integer;
    v_existe_documento		integer;

BEGIN

    v_nombre_funcion = 'mat.ft_firmas_documentos_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_FIRM_DOC_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		ismael.valdivia
 	#FECHA:		16-08-2022 13:50:13
	***********************************/

	if(p_transaccion='MAT_FIRM_DOC_INS')then

        begin

        	/*Control para no sobre poner fechas*/
            select count (docu.id_firma_documento) into v_existe_documento
            from mat.tfirmas_documentos docu
            where docu.tipo_firma = v_parametros.tipo_firma
            and docu.tipo_documento = v_parametros.tipo_documento
            and v_parametros.fecha_inicio between docu.fecha_inicio and docu.fecha_fin;

        	if (v_existe_documento > 0) then
            	raise exception 'Ya existe un registro para el tipo de documento <b>%</b>, y tipo de firma <b>%</b>, con fecha de finalizacion mayor o igual a <b>%</b>',v_parametros.tipo_documento, v_parametros.tipo_firma, to_char(v_parametros.fecha_inicio,'DD/MM/YYYY');
            end if;



        	--Sentencia de la insercion
        	insert into mat.tfirmas_documentos(
			estado_reg,
			fecha_inicio,
			fecha_fin,
			id_funcionario,
			tipo_firma,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
            motivo_asignacion,
            tipo_documento
          	) values(
			'activo',
			v_parametros.fecha_inicio,
			v_parametros.fecha_fin,
			v_parametros.id_funcionario,
			v_parametros.tipo_firma,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.motivo_asignacion,
            v_parametros.tipo_documento



			)RETURNING id_firma_documento into v_id_firma_documento;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Firmas Documentos almacenado(a) con exito (id_firma_documento'||v_id_firma_documento||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_firma_documento',v_id_firma_documento::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_FIRM_DOC_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		ismael.valdivia
 	#FECHA:		16-08-2022 13:50:13
	***********************************/

	elsif(p_transaccion='MAT_FIRM_DOC_MOD')then

		begin
			--Sentencia de la modificacion
			update mat.tfirmas_documentos set
			fecha_inicio = v_parametros.fecha_inicio,
			fecha_fin = v_parametros.fecha_fin,
			id_funcionario = v_parametros.id_funcionario,
			tipo_firma = v_parametros.tipo_firma,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            motivo_asignacion = v_parametros.motivo_asignacion,
            tipo_documento = v_parametros.tipo_documento
			where id_firma_documento=v_parametros.id_firma_documento;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Firmas Documentos modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_firma_documento',v_parametros.id_firma_documento::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_FIRM_DOC_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		ismael.valdivia
 	#FECHA:		16-08-2022 13:50:13
	***********************************/

	elsif(p_transaccion='MAT_FIRM_DOC_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from mat.tfirmas_documentos
            where id_firma_documento=v_parametros.id_firma_documento;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Firmas Documentos eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_firma_documento',v_parametros.id_firma_documento::varchar);

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

ALTER FUNCTION mat.ft_firmas_documentos_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
