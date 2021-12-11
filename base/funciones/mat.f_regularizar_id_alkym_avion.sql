CREATE OR REPLACE FUNCTION mat.f_regularizar_id_alkym_avion (
)
RETURNS varchar AS
$body$
DECLARE
	v_consulta    		varchar;
	v_registros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_datos					record;

    v_cadena_cnx		varchar;
    v_conexion			varchar;
    v_id_factura		integer;
    v_tipo_pv			varchar;
	v_cajero			varchar;
    v_res_cone			varchar;
    v_consulta_2		varchar;


BEGIN
	  v_nombre_funcion = 'mat.f_regularizar_id_alkym_avion';

      	for v_registros in (		 select ot.codigo,
                                                      ot.id_orden_trabajo,
                                                      alk.idavion,
                                                      alk.matricula
                                            from conta.torden_trabajo ot
                                            inner join MatriculaAlkym alk on alk.matricula = ot.codigo
                                            order by ot.id_orden_trabajo ASC ) loop

                                    update conta.torden_trabajo set
                                    id_avion_alkym = v_registros.idavion
                                    where id_orden_trabajo = v_registros.id_orden_trabajo;



        end loop;

    return 'exito';

EXCEPTION
	WHEN OTHERS THEN
			--update a la tabla informix.migracion

            return SQLERRM;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION mat.f_regularizar_id_alkym_avion ()
  OWNER TO postgres;
