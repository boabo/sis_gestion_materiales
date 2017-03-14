CREATE OR REPLACE FUNCTION "mat"."ft_unidad_medida_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Gestión de Materiales
 FUNCION: 		mat.ft_unidad_medida_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'mat.tunidad_medida'
 AUTOR: 		 (admin)
 FECHA:	        14-03-2017 16:18:47
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
	v_id_unidad_medida	integer;
			    
BEGIN

    v_nombre_funcion = 'mat.ft_unidad_medida_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'MAT_U/M_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		14-03-2017 16:18:47
	***********************************/

	if(p_transaccion='MAT_U/M_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into mat.tunidad_medida(
			codigo,
			descripcion,
			tipo_unidad_medida,
			estado_reg,
			id_usuario_ai,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.codigo,
			v_parametros.descripcion,
			v_parametros.tipo_unidad_medida,
			'activo',
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_unidad_medida into v_id_unidad_medida;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Unidad Medida almacenado(a) con exito (id_unidad_medida'||v_id_unidad_medida||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_unidad_medida',v_id_unidad_medida::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'MAT_U/M_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		14-03-2017 16:18:47
	***********************************/

	elsif(p_transaccion='MAT_U/M_MOD')then

		begin
			--Sentencia de la modificacion
			update mat.tunidad_medida set
			codigo = v_parametros.codigo,
			descripcion = v_parametros.descripcion,
			tipo_unidad_medida = v_parametros.tipo_unidad_medida,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_unidad_medida=v_parametros.id_unidad_medida;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Unidad Medida modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_unidad_medida',v_parametros.id_unidad_medida::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'MAT_U/M_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		14-03-2017 16:18:47
	***********************************/

	elsif(p_transaccion='MAT_U/M_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from mat.tunidad_medida
            where id_unidad_medida=v_parametros.id_unidad_medida;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Unidad Medida eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_unidad_medida',v_parametros.id_unidad_medida::varchar);
              
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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "mat"."ft_unidad_medida_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
