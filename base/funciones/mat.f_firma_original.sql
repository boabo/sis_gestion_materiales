CREATE OR REPLACE FUNCTION mat.f_firma_original (
  p_id_proceso_wf integer,
  p_id_funcionario integer
)
RETURNS record AS
$body$
 declare
  reg 						   record;
  v_id_funcionario_rempla   integer;
  v_id_funcionario			integer;
  es_id_usuario 				   integer;   
  v_usuario_dc_ai			   integer;
  v_nombre_funcionario_dc_qr_rempla integer;
  v_fecha_firma_dc_qr_rempla		text;
  resul								record;
  resul1							record;
  es_id_funcionario					record;
 begin 
 
    
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
                      
                                          
IF(es_id_usuario is not null)then               

	 select f.id_funcionario
     into v_id_funcionario_rempla
          from segu.vusuario us
          inner join orga.tfuncionario f on f.id_persona=us.id_persona
          where us.id_usuario=es_id_usuario;
                
              
        select 
            f.desc_funcionario1||' | '||f.nombre_cargo||' | Empresa Publica Nacional Estrategica Boliviana de Aviación - BoA'::varchar as desc_funcionario1,
            f.desc_funcionario1 as funcion
            into  resul            	
        from orga.vfuncionario_cargo f
        inner join orga.tfuncionario fu on fu.id_funcionario=f.id_funcionario
        inner join segu.vusuario su on su.id_persona=fu.id_persona      
        where f.id_funcionario=v_id_funcionario_rempla 
        and f.fecha_finalizacion is null;
        
	return   resul;          
else
	 select f.id_funcionario
     into resul
          from segu.vusuario us
          inner join orga.tfuncionario f on f.id_persona=us.id_persona
          where us.id_usuario=es_id_usuario;          
	return resul;
end if;        
             
 
end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;