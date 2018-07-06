CREATE OR REPLACE FUNCTION mat.control_fecha_requerida (
  p_fecha_actual date,
  p_fecha_requerida date
)
RETURNS integer AS
$body$
DECLARE
  v_nombre_funcion   	text;
    v_resp    			varchar;
    v_mensaje 			varchar;
	v_diferencia		integer;

    v_contador			integer=0;
    v_fecha_actual		date;
    v_fecha_requerida   date;
BEGIN
	
     v_fecha_actual = p_fecha_actual;
      WHILE  v_fecha_actual < p_fecha_requerida LOOP
          IF (date_part('dow',v_fecha_actual) in (1,2,3,4,5,6,7))THEN
              v_contador = v_contador + 1;    
          END IF; 
          v_fecha_actual = v_fecha_actual + 1;
      END LOOP;
      
      IF(v_fecha_actual > p_fecha_requerida) THEN
          v_contador=-1;
      END IF;
     RETURN v_contador;
   
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;