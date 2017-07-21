CREATE OR REPLACE FUNCTION mat.f_eliminar_estado (
  p_id_proceso_wf integer,
  p_id_proceso_wf_firma integer
)
RETURNS boolean AS
$body$
  DECLARE



    v_nombre_funcion   	text;
    v_resp				varchar;
    v_sw_force			boolean;



  BEGIN

    v_nombre_funcion = 'pre.f_rep_evaluacion_recursivo';

    v_sw_force = false;  --para criterio de parada

     update mat.tsolicitud  set
                 id_estado_wf_firma = NULL,
            	 id_proceso_wf_firma = NULL,
            	 estado_firma = NULL
               	 where id_proceso_wf = p_id_proceso_wf;



    delete from wf.testado_wf
    where id_proceso_wf =p_id_proceso_wf_firma;


    RETURN TRUE;

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
RETURNS NULL ON NULL INPUT
SECURITY INVOKER
COST 100;