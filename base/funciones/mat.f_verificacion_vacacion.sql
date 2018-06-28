CREATE OR REPLACE FUNCTION mat.f_verificacion_vacacion (
  p_id_funcionario integer,
  p_fecha text
)
RETURNS integer AS
$body$
 declare
  reg record;
   id_inte integer;
   id_usuario_rempla integer;
   id_fun_remplasante integer;
   v_r integer=0;
id_person integer;   
 begin 
 
	select
						int.id_interinato,
                        fs.id_funcionario                         
                        into id_inte,
                             id_fun_remplasante               	
						from orga.tinterinato int
                        inner join orga.tcargo ct on ct.id_cargo = int.id_cargo_titular
                        inner join orga.tuo_funcionario uoft on uoft.id_cargo = ct.id_cargo 
                        inner join orga.vfuncionario ft on ft.id_funcionario = uoft.id_funcionario                         
                        inner join orga.tcargo cs on cs.id_cargo = int.id_cargo_suplente 
                        inner join orga.tuo_funcionario uofs on uofs.id_cargo = cs.id_cargo and uofs.tipo = 'oficial'
                        inner join orga.vfuncionario fs on fs.id_funcionario = uofs.id_funcionario                         
						inner join segu.tusuario usu1 on usu1.id_usuario = int.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = int.id_usuario_mod
                        where ft.id_funcionario=p_id_funcionario and p_fecha::date between int.fecha_ini and int.fecha_fin;

select u.id_usuario
into
id_usuario_rempla
from orga.tfuncionario fun 
inner join segu.tusuario u on u.id_persona=fun.id_persona
where fun.id_funcionario = COALESCE(id_fun_remplasante,0);

                        
  if(id_usuario_rempla is not null)then
  return id_usuario_rempla;
  end if;
  return v_r;
end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;