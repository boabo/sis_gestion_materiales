CREATE OR REPLACE FUNCTION mat.ft_detalle_sol_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Gestión de Materiales
 FUNCION: 		mat.ft_detalle_sol_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tdetalle_sol'
 AUTOR: 		 (admin)
 FECHA:	        23-12-2016 13:13:01
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
	v_id_detalle				integer;
    v_campos 					record;
    v_revisado 					VARCHAR;
    v_detalle 					record;

    v_parte 					varchar;
    v_msg_control				varchar;
    v_control_duplicidad 		record;

    v_nro_parte					varchar;
    v_nro_parte_alterno			varchar;



BEGIN

    v_nombre_funcion = 'mat.ft_detalle_sol_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MAT_DET_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	if(p_transaccion='MAT_DET_INS')then

        begin
        --RAISE EXCEPTION 'parametros %',v_parametros;
        	--Sentencia de la insercion
        	insert into mat.tdetalle_sol(
			id_solicitud,
			descripcion,
			estado_reg,
			id_unidad_medida,
			nro_parte,
			referencia,
			nro_parte_alterno,
			cantidad_sol,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod,
            tipo,
            explicacion_detallada_part
          	) values(
			v_parametros.id_solicitud,
			regexp_replace(v_parametros.descripcion,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g'),
			'activo',
			v_parametros.id_unidad_medida,
            regexp_replace(v_parametros.nro_parte,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g'),
            regexp_replace(v_parametros.referencia,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g'),
            regexp_replace(v_parametros.nro_parte_alterno,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g'),
			v_parametros.cantidad_sol,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null,
            v_parametros.tipo,
            regexp_replace(v_parametros.explicacion_detallada_part,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g')
            )RETURNING id_detalle into v_id_detalle;

            --modificar nro_parte, nro_parte_alterno en tsolicitud
            select list(ds.nro_parte)
            into v_nro_parte
            from mat.tdetalle_sol ds
            where  ds.id_solicitud= v_parametros.id_solicitud
            GROUP by ds.id_solicitud;

            select list(ds.nro_parte_alterno)
            into v_nro_parte_alterno
            from mat.tdetalle_sol ds
            where  ds.id_solicitud= v_parametros.id_solicitud
            GROUP by ds.id_solicitud;

             -- RAISE exception '%, %',v_nro_parte, v_nro_parte_alterno;

              update mat.tsolicitud
              set
              nro_partes = v_nro_parte,
              nro_parte_alterno =  v_nro_parte_alterno
              where
              mat.tsolicitud.id_solicitud = v_parametros.id_solicitud;


			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle_sol almacenado(a) con exito (id_detalle'||v_id_detalle||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_id_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_DET_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_DET_MOD')then

		begin
        select s.estado
        into v_detalle
        from mat.tsolicitud s
        inner join mat.tdetalle_sol de on de.id_solicitud = s.id_solicitud
        WHERE s.id_solicitud = v_parametros.id_solicitud;

        if (v_detalle.estado = 'desaduanizado')THEN
        v_revisado = v_parametros.revisado;
        ELSE
        v_revisado = 'no';
        end IF;

			--Sentencia de la modificacion
			update mat.tdetalle_sol set
			id_solicitud = v_parametros.id_solicitud,
			descripcion = v_parametros.descripcion,
			id_unidad_medida = v_parametros.id_unidad_medida,
			nro_parte = regexp_replace(v_parametros.nro_parte,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g'),
			referencia = regexp_replace(v_parametros.referencia,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g'),
			nro_parte_alterno = regexp_replace(v_parametros.nro_parte_alterno,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g'),
			cantidad_sol = v_parametros.cantidad_sol,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            revisado = v_revisado,
            tipo = v_parametros.tipo,
            explicacion_detallada_part = regexp_replace(v_parametros.explicacion_detallada_part,'[^a-zA-Z0-9.,#()"°/+*:|!$?¡¿´¨~{}^`&= ]+', '-','g')
			where id_detalle=v_parametros.id_detalle;

     --modificar nro_parte, nro_parte_alterno en tsolicitud
        select list(ds.nro_parte)
        into v_nro_parte
        from mat.tdetalle_sol ds
        where  ds.id_solicitud= v_parametros.id_solicitud
        GROUP by ds.id_solicitud;

        select list(ds.nro_parte_alterno)
        into v_nro_parte_alterno
        from mat.tdetalle_sol ds
        where  ds.id_solicitud= v_parametros.id_solicitud
        GROUP by ds.id_solicitud;

       -- RAISE exception '%, %',v_nro_parte, v_nro_parte_alterno;

        update mat.tsolicitud
        set
        nro_partes = v_nro_parte,
        nro_parte_alterno =  v_nro_parte_alterno
        where
        mat.tsolicitud.id_solicitud = v_parametros.id_solicitud;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle_sol modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_parametros.id_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MAT_DET_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin
 	#FECHA:		23-12-2016 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_DET_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from mat.tdetalle_sol
            where id_detalle=v_parametros.id_detalle;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle_sol eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle',v_parametros.id_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'MAT_GET_PAR'
 	#DESCRIPCION:	control de numero de justificacion
 	#AUTOR:		MMV
 	#FECHA:		10-01-2017 13:13:01
	***********************************/

	elsif(p_transaccion='MAT_GET_PAR')then
		begin
        FOR v_control_duplicidad in (select	d.nro_parte,
											f.desc_funcionario1,
                                            s.nro_justificacion,
                                            s.nro_no_rutina,
                                            s.justificacion,
                                            s.nro_tramite,
                                            s.fecha_solicitud
                                            from mat.tdetalle_sol d
                                            inner join mat.tsolicitud s on s.id_solicitud = d.id_solicitud
                                            inner join orga.vfuncionario f on f.id_funcionario = s.id_funcionario_sol
     	)LOOP
        	if v_parametros.nro_parte = v_control_duplicidad.nro_parte then
            	v_parte= v_control_duplicidad.nro_parte;
                v_msg_control = v_control_duplicidad.nro_parte||' fue registrado por '||v_control_duplicidad.desc_funcionario1||' en el tramite '||v_control_duplicidad.nro_tramite;
            end if;

        END LOOP;

        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
        v_resp = pxp.f_agrega_clave(v_resp,'parte', v_parte::varchar );
        v_resp = pxp.f_agrega_clave(v_resp,'mgs_control_duplicidad', v_msg_control::varchar );
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

ALTER FUNCTION mat.ft_detalle_sol_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;
