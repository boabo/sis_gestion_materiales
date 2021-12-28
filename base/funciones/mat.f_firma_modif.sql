CREATE OR REPLACE FUNCTION mat.f_firma_modif (
  p_id_proceso_wf integer,
  p_id_funcionario integer,
  p_fecha text
)
RETURNS record AS
$body$
 /**************************************************************************
 SISTEMA:		Gestion de Materiales
 FUNCION: 		mat.f_firma_modif
 DESCRIPCION:   Funcion que realiza la accion de cambiar firmas de acuerdo a un interinato registrado en base a su fecha y añade el numero de tramite
 AUTOR: 		 RZABALA
 FECHA:	        01-11-2018 17:09:45
 COMENTARIOS:
 ***************************************************************************/
 declare
  reg 						   		record;
  v_id_funcionario_rempla   		integer;
  v_id_funcionario					    integer;
  es_id_cargo 				   	      integer;
  es_id_cargo_suplente 				  integer;
  es_id_usuario   					    integer;
  v_usuario_dc_ai			   		      integer;
  v_nombre_funcionario_dc_qr_rempla integer;
  v_fecha_firma_qr_reemp			text;
  es_fecha_po						text;
  es_tramite						text;
  resul								record;
  resul1							record;
  es_id_funcionario					record;
 begin



 --raise exception 'Los datos enviados proceso, fecha, id funcionarioson: %', p_id_funcionario ;
 		select es.id_usuario_ai,es.id_estado_wf,es.id_estado_anterior
        into es_id_funcionario
				from wf.testado_wf es
				where es.id_proceso_wf = p_id_proceso_wf
				and es.id_funcionario=p_id_funcionario
				order by es.fecha_reg;




        select es.id_usuario_ai
        into es_id_usuario
        		from wf.testado_wf es
        		where es.id_estado_anterior=es_id_funcionario.id_estado_wf
        		and es.id_proceso_wf=p_id_proceso_wf;


        select pwf.nro_tramite
        into es_tramite
        		from wf.tproceso_wf pwf
        		where pwf.id_proceso_wf=p_id_proceso_wf;
--raise exception '%',p_id_funcionario;
        select fu.id_cargo
        into es_id_cargo
				from orga.vfuncionario_cargo fu
				where fu.id_funcionario=p_id_funcionario --370
                AND p_fecha::date between fu.fecha_asignacion and coalesce(fu.fecha_finalizacion,now())
				order by fu.fecha_finalizacion desc
				limit 1;




        select inte.id_cargo_suplente
        into es_id_cargo_suplente
				from orga.tinterinato inte
				where inte.id_cargo_titular=es_id_cargo--15778
				and p_fecha::date between inte.fecha_ini and inte.fecha_fin;




IF(es_id_cargo_suplente is not null)then


     select
    		f.desc_funcionario1||' | '||f.nombre_cargo||' | '||es_tramite||'| Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
    		f.desc_funcionario1 as funcion

            into resul
				from orga.vfuncionario_cargo f
				inner join orga.tfuncionario fu on fu.id_funcionario=f.id_funcionario
				inner join segu.vusuario su on su.id_persona=fu.id_persona
				where f.id_cargo=es_id_cargo_suplente--15781
                and p_fecha::date between f.fecha_asignacion and coalesce(f.fecha_finalizacion,now());

	return   resul;
else
	 select f.id_funcionario,
     f1.desc_funcionario1||' | '||f1.nombre_cargo||' | '||es_tramite||' | Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
     f1.desc_funcionario1 as funcion
     into resul
          from segu.vusuario us
          inner join orga.tfuncionario f on f.id_persona=us.id_persona
          inner join orga.vfuncionario_cargo f1 on f1.id_funcionario=f.id_funcionario
          --where us.id_usuario=es_id_usuario;
          where f.id_funcionario=p_id_funcionario
          and p_fecha::date between f1.fecha_asignacion and coalesce(f1.fecha_finalizacion,now());
	return resul;
end if;


end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION mat.f_firma_modif (p_id_proceso_wf integer, p_id_funcionario integer, p_fecha text)
  OWNER TO postgres;
