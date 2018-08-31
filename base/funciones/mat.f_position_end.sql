CREATE OR REPLACE FUNCTION mat.f_position_end (
  id_fun integer
)
RETURNS integer AS
$body$
DECLARE
  resp			integer;

BEGIN

				select ou.id_uo_funcionario
                into resp
                from orga.tuo_funcionario ou
                where ou.id_funcionario =id_fun
                order by ou.fecha_asignacion desc;

return resp;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;