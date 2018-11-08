/***********************************I-DEP-MAM-MAT-0-07/01/2017****************************************/
----------------------------------
--COPY LINES TO dependencies.sql FILE
---------------------------------

select pxp.f_insert_testructura_gui ('MAT', 'SISTEMA');
select pxp.f_insert_testructura_gui ('REMA', 'MAT');
select pxp.f_delete_testructura_gui ('DET', 'MAT');
select pxp.f_insert_testructura_gui ('VBS', 'MAT');
select pxp.f_insert_testructura_gui ('CR', 'MAT');
select pxp.f_insert_testructura_gui ('ABS', 'MAT');
select pxp.f_insert_testructura_gui ('REMA.1', 'REMA');
select pxp.f_insert_testructura_gui ('REMA.2', 'REMA');
select pxp.f_insert_testructura_gui ('REMA.3', 'REMA');
select pxp.f_insert_testructura_gui ('REMA.4', 'REMA');
select pxp.f_insert_testructura_gui ('REMA.5', 'REMA');
select pxp.f_insert_testructura_gui ('REMA.3.1', 'REMA.3');
select pxp.f_insert_testructura_gui ('REMA.3.2', 'REMA.3');
select pxp.f_insert_testructura_gui ('REMA.3.3', 'REMA.3');
select pxp.f_insert_testructura_gui ('REMA.3.4', 'REMA.3');
select pxp.f_insert_testructura_gui ('REMA.3.5', 'REMA.3');
select pxp.f_insert_testructura_gui ('REMA.3.5.1', 'REMA.3.5');
select pxp.f_insert_testructura_gui ('REMA.3.5.1.1', 'REMA.3.5.1');
select pxp.f_insert_testructura_gui ('REMA.4.1', 'REMA.4');
select pxp.f_insert_testructura_gui ('VBS.1', 'VBS');
select pxp.f_insert_testructura_gui ('VBS.2', 'VBS');
select pxp.f_insert_testructura_gui ('VBS.3', 'VBS');
select pxp.f_insert_testructura_gui ('VBS.4', 'VBS');
select pxp.f_insert_testructura_gui ('VBS.5', 'VBS');
select pxp.f_insert_testructura_gui ('VBS.3.1', 'VBS.3');
select pxp.f_insert_testructura_gui ('VBS.3.2', 'VBS.3');
select pxp.f_insert_testructura_gui ('VBS.3.3', 'VBS.3');
select pxp.f_insert_testructura_gui ('VBS.3.4', 'VBS.3');
select pxp.f_insert_testructura_gui ('VBS.3.5', 'VBS.3');
select pxp.f_insert_testructura_gui ('VBS.3.5.1', 'VBS.3.5');
select pxp.f_insert_testructura_gui ('VBS.3.5.1.1', 'VBS.3.5.1');
select pxp.f_insert_testructura_gui ('VBS.4.1', 'VBS.4');
select pxp.f_insert_testructura_gui ('CR.1', 'CR');
select pxp.f_insert_testructura_gui ('CR.2', 'CR');
select pxp.f_insert_testructura_gui ('CR.3', 'CR');
select pxp.f_insert_testructura_gui ('CR.4', 'CR');
select pxp.f_insert_testructura_gui ('CR.5', 'CR');
select pxp.f_insert_testructura_gui ('CR.3.1', 'CR.3');
select pxp.f_insert_testructura_gui ('CR.3.2', 'CR.3');
select pxp.f_insert_testructura_gui ('CR.3.3', 'CR.3');
select pxp.f_insert_testructura_gui ('CR.3.4', 'CR.3');
select pxp.f_insert_testructura_gui ('CR.3.5', 'CR.3');
select pxp.f_insert_testructura_gui ('CR.3.5.1', 'CR.3.5');
select pxp.f_insert_testructura_gui ('CR.3.5.1.1', 'CR.3.5.1');
select pxp.f_insert_testructura_gui ('CR.4.1', 'CR.4');
select pxp.f_insert_testructura_gui ('ABS.1', 'ABS');
select pxp.f_insert_testructura_gui ('ABS.2', 'ABS');
select pxp.f_insert_testructura_gui ('ABS.3', 'ABS');
select pxp.f_insert_testructura_gui ('ABS.4', 'ABS');
select pxp.f_insert_testructura_gui ('ABS.5', 'ABS');
select pxp.f_insert_testructura_gui ('ABS.3.1', 'ABS.3');
select pxp.f_insert_testructura_gui ('ABS.3.2', 'ABS.3');
select pxp.f_insert_testructura_gui ('ABS.3.3', 'ABS.3');
select pxp.f_insert_testructura_gui ('ABS.3.4', 'ABS.3');
select pxp.f_insert_testructura_gui ('ABS.3.5', 'ABS.3');
select pxp.f_insert_testructura_gui ('ABS.3.5.1', 'ABS.3.5');
select pxp.f_insert_testructura_gui ('ABS.3.5.1.1', 'ABS.3.5.1');
select pxp.f_insert_testructura_gui ('ABS.4.1', 'ABS.4');
select pxp.f_insert_tprocedimiento_gui ('WF_GATNREP_SEL', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('RH_FUNCIOCAR_SEL', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_MATR_SEL', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_INS', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_MOD', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_ELI', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_SEL', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SIG_IME', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_ANT_INS', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_INS', 'REMA.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'REMA.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_GETGES_GET', 'REMA.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_SEL', 'REMA.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('RH_FUNCIOCAR_SEL', 'REMA.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_MATR_SEL', 'REMA.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'REMA.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_MOD', 'REMA.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_ELI', 'REMA.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_SEL', 'REMA.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'REMA.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'REMA.3.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'REMA.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DHW_SEL', 'REMA.3.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPPROC_SEL', 'REMA.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPES_SEL', 'REMA.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_INS', 'REMA.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_MOD', 'REMA.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_ELI', 'REMA.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_SEL', 'REMA.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_GETFILOP_IME', 'REMA.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_PROVEEV_SEL', 'REMA.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('CONTA_ODT_SEL', 'REMA.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIG_SEL', 'REMA.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPARGES_SEL', 'REMA.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPAR_SEL', 'REMA.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPP_SEL', 'REMA.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPRE_SEL', 'REMA.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_REPPAGOS_SEL', 'REMA.3.5.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'REMA.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'REMA.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERSIGPRO_IME', 'REMA.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CHKSTA_IME', 'REMA.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPES_SEL', 'REMA.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DEPTIPES_SEL', 'REMA.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_FUNTIPES_SEL', 'REMA.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'REMA.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'REMA.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_GATNREP_SEL', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('RH_FUNCIOCAR_SEL', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_MATR_SEL', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_INS', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_MOD', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_ELI', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_SEL', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SIG_IME', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_ANT_INS', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'VBS.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_MOD', 'VBS.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_ELI', 'VBS.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_SEL', 'VBS.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_INS', 'VBS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'VBS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_GETGES_GET', 'VBS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_SEL', 'VBS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('RH_FUNCIOCAR_SEL', 'VBS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_MATR_SEL', 'VBS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'VBS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'VBS.3.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'VBS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DHW_SEL', 'VBS.3.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPPROC_SEL', 'VBS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPES_SEL', 'VBS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_INS', 'VBS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_MOD', 'VBS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_ELI', 'VBS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_SEL', 'VBS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_GETFILOP_IME', 'VBS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_PROVEEV_SEL', 'VBS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('CONTA_ODT_SEL', 'VBS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIG_SEL', 'VBS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPARGES_SEL', 'VBS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPAR_SEL', 'VBS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPP_SEL', 'VBS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPRE_SEL', 'VBS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_REPPAGOS_SEL', 'VBS.3.5.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'VBS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'VBS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERSIGPRO_IME', 'VBS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CHKSTA_IME', 'VBS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPES_SEL', 'VBS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DEPTIPES_SEL', 'VBS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_FUNTIPES_SEL', 'VBS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'VBS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'VBS.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_GATNREP_SEL', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('RH_FUNCIOCAR_SEL', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_MATR_SEL', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_INS', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_MOD', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_ELI', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_SEL', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SIG_IME', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_ANT_INS', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'CR.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_MOD', 'CR.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_ELI', 'CR.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_SEL', 'CR.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_INS', 'CR.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'CR.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_GETGES_GET', 'CR.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_SEL', 'CR.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('RH_FUNCIOCAR_SEL', 'CR.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_MATR_SEL', 'CR.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'CR.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'CR.3.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'CR.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DHW_SEL', 'CR.3.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPPROC_SEL', 'CR.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPES_SEL', 'CR.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_INS', 'CR.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_MOD', 'CR.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_ELI', 'CR.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_SEL', 'CR.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_GETFILOP_IME', 'CR.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_PROVEEV_SEL', 'CR.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('CONTA_ODT_SEL', 'CR.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIG_SEL', 'CR.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPARGES_SEL', 'CR.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPAR_SEL', 'CR.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPP_SEL', 'CR.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPRE_SEL', 'CR.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_REPPAGOS_SEL', 'CR.3.5.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'CR.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'CR.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERSIGPRO_IME', 'CR.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CHKSTA_IME', 'CR.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPES_SEL', 'CR.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DEPTIPES_SEL', 'CR.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_FUNTIPES_SEL', 'CR.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'CR.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'CR.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_GATNREP_SEL', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('RH_FUNCIOCAR_SEL', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_MATR_SEL', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_INS', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_MOD', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_ELI', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_SEL', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SIG_IME', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_ANT_INS', 'ABS', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'ABS.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_MOD', 'ABS.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_ELI', 'ABS.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_SEL', 'ABS.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_SOL_INS', 'ABS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_INS', 'ABS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_GETGES_GET', 'ABS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_DET_SEL', 'ABS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('RH_FUNCIOCAR_SEL', 'ABS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('MAT_MATR_SEL', 'ABS.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'ABS.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'ABS.3.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'ABS.3.2', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DHW_SEL', 'ABS.3.3', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPPROC_SEL', 'ABS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPES_SEL', 'ABS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_INS', 'ABS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_MOD', 'ABS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_ELI', 'ABS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DES_SEL', 'ABS.3.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_GETFILOP_IME', 'ABS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_PROVEEV_SEL', 'ABS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('CONTA_ODT_SEL', 'ABS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIG_SEL', 'ABS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPARGES_SEL', 'ABS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPAR_SEL', 'ABS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPP_SEL', 'ABS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIGPRE_SEL', 'ABS.3.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_REPPAGOS_SEL', 'ABS.3.5.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'ABS.3.5.1.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'ABS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERSIGPRO_IME', 'ABS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CHKSTA_IME', 'ABS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPES_SEL', 'ABS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DEPTIPES_SEL', 'ABS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_FUNTIPES_SEL', 'ABS.4', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VERDOC_IME', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPDW_SEL', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIDOCPLAN_SEL', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_VISTA_SEL', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_INS', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_MOD', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_ELI', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DWF_SEL', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_CABMOM_IME', 'ABS.4.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_DOCWFAR_MOD', 'ABS.5', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPCOLFOR_SEL', 'REMA', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPCOLFOR_SEL', 'VBS', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPCOLFOR_SEL', 'CR', 'no');
select pxp.f_insert_tprocedimiento_gui ('WF_TIPCOLFOR_SEL', 'ABS', 'no');
select pxp.f_delete_tgui_rol ('REMA', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('MAT', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('SISTEMA', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.5', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.4', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.4.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.3', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.3.5', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.3.5.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.3.5.1.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.3.4', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.3.3', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.3.2', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.3.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.2', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('CR.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.5', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.4', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.4.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.5', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.5.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.5.1.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.4', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.3', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.2', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.2', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('MAT', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('SISTEMA', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.5', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.4', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.4.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.5', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.5.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.5.1.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.4', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.3', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.2', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.3.1', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.2', 'GM - Solicitante');
select pxp.f_delete_tgui_rol ('REMA.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.5', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.4', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.4.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.3', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.3.5', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.3.5.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.3.5.1.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.3.4', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.3.3', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.3.2', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.3.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.2', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('CR.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.5', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.4', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.4.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.3', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.3.5', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.3.5.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.3.5.1.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.3.4', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.3.3', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.3.2', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.3.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.2', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('REMA.1', 'GM - Solicitante');
select pxp.f_insert_tgui_rol ('ABS', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('MAT', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('SISTEMA', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.5', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.4', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.4.1', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.3', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.3.5', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.3.5.1', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.3.5.1.1', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.3.4', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.3.3', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.3.2', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.3.1', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.2', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('ABS.1', 'GM - Auxiliar Abastecimientos');
select pxp.f_insert_tgui_rol ('VBS', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('MAT', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('SISTEMA', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.5', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.4', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.4.1', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.3', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.3.5', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.3.5.1', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.3.5.1.1', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.3.4', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.3.3', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.3.2', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.3.1', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.2', 'GM - Visto Bueno Solicitud');
select pxp.f_insert_tgui_rol ('VBS.1', 'GM - Visto Bueno Solicitud');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_GATNREP_SEL', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_MOD', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_ELI', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_ANT_INS', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SIG_IME', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_SEL', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'CR');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'CR.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_FUNTIPES_SEL', 'CR.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'CR.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'CR.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DEPTIPES_SEL', 'CR.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERSIGPRO_IME', 'CR.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CHKSTA_IME', 'CR.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'CR.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'CR.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_PROVEEV_SEL', 'CR.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIG_SEL', 'CR.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'CONTA_ODT_SEL', 'CR.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPP_SEL', 'CR.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPAR_SEL', 'CR.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'TES_GETFILOP_IME', 'CR.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPRE_SEL', 'CR.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPARGES_SEL', 'CR.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'TES_REPPAGOS_SEL', 'CR.3.5.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'CR.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'CR.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPPROC_SEL', 'CR.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_INS', 'CR.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_MOD', 'CR.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_ELI', 'CR.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_SEL', 'CR.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DHW_SEL', 'CR.3.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'CR.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'CR.3.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'CR.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_GETGES_GET', 'CR.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'CR.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'CR.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'CR.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'CR.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'CR.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_MOD', 'CR.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_ELI', 'CR.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'CR.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_GATNREP_SEL', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_MOD', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_ELI', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_ANT_INS', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SIG_IME', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_SEL', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_FUNTIPES_SEL', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DEPTIPES_SEL', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERSIGPRO_IME', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CHKSTA_IME', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_PROVEEV_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIG_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'CONTA_ODT_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPP_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPAR_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'TES_GETFILOP_IME', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPRE_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPARGES_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'TES_REPPAGOS_SEL', 'REMA.3.5.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPPROC_SEL', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_INS', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_MOD', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_ELI', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_SEL', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DHW_SEL', 'REMA.3.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.3.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_MOD', 'REMA.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_ELI', 'REMA.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'REMA.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_GETGES_GET', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_GATNREP_SEL', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_MOD', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_ELI', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_ANT_INS', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SIG_IME', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_SEL', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'REMA');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_FUNTIPES_SEL', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DEPTIPES_SEL', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERSIGPRO_IME', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CHKSTA_IME', 'REMA.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.4.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_PROVEEV_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIG_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'CONTA_ODT_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPP_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPAR_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'TES_GETFILOP_IME', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPRE_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPARGES_SEL', 'REMA.3.5');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'TES_REPPAGOS_SEL', 'REMA.3.5.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3.5.1.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPPROC_SEL', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_INS', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_MOD', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_ELI', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_SEL', 'REMA.3.4');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DHW_SEL', 'REMA.3.3');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.3.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_MOD', 'REMA.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_ELI', 'REMA.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'REMA.2');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'PM_GETGES_GET', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'REMA.1');
select pxp.f_delete_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'REMA.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_GATNREP_SEL', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_MOD', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_ELI', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_ANT_INS', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SIG_IME', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_SEL', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'CR');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'CR.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_FUNTIPES_SEL', 'CR.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'CR.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'CR.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DEPTIPES_SEL', 'CR.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERSIGPRO_IME', 'CR.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CHKSTA_IME', 'CR.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'CR.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'CR.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_PROVEEV_SEL', 'CR.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIG_SEL', 'CR.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'CONTA_ODT_SEL', 'CR.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPP_SEL', 'CR.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPAR_SEL', 'CR.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'TES_GETFILOP_IME', 'CR.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPRE_SEL', 'CR.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPARGES_SEL', 'CR.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'TES_REPPAGOS_SEL', 'CR.3.5.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'CR.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'CR.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPPROC_SEL', 'CR.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_INS', 'CR.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_MOD', 'CR.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_ELI', 'CR.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_SEL', 'CR.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DHW_SEL', 'CR.3.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'CR.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'CR.3.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'CR.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_GETGES_GET', 'CR.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'CR.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'CR.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'CR.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'CR.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'CR.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_MOD', 'CR.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_ELI', 'CR.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'CR.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_GATNREP_SEL', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_MOD', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_ELI', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_ANT_INS', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SIG_IME', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_SEL', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'REMA');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_FUNTIPES_SEL', 'REMA.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'REMA.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DEPTIPES_SEL', 'REMA.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERSIGPRO_IME', 'REMA.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CHKSTA_IME', 'REMA.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_PROVEEV_SEL', 'REMA.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIG_SEL', 'REMA.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'CONTA_ODT_SEL', 'REMA.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPP_SEL', 'REMA.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPAR_SEL', 'REMA.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'TES_GETFILOP_IME', 'REMA.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPRE_SEL', 'REMA.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_CONIGPARGES_SEL', 'REMA.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'TES_REPPAGOS_SEL', 'REMA.3.5.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPES_SEL', 'REMA.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPPROC_SEL', 'REMA.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_INS', 'REMA.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_MOD', 'REMA.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_ELI', 'REMA.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DES_SEL', 'REMA.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DHW_SEL', 'REMA.3.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIPDW_SEL', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_MOD', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_ELI', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_SEL', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_CABMOM_IME', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DWF_INS', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VERDOC_IME', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_TIDOCPLAN_SEL', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_VISTA_SEL', 'REMA.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'WF_DOCWFAR_MOD', 'REMA.3.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_MOD', 'REMA.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_ELI', 'REMA.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'REMA.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'RH_FUNCIOCAR_SEL', 'REMA.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'PM_GETGES_GET', 'REMA.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_INS', 'REMA.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_DET_SEL', 'REMA.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_SOL_INS', 'REMA.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Solicitante', 'MAT_MATR_SEL', 'REMA.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'RH_FUNCIOCAR_SEL', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_GATNREP_SEL', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_DET_INS', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_SOL_INS', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_SOL_MOD', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_SOL_ELI', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_ANT_INS', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_SIG_IME', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_SOL_SEL', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_MATR_SEL', 'ABS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DOCWFAR_MOD', 'ABS.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_FUNTIPES_SEL', 'ABS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIPES_SEL', 'ABS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DOCWFAR_MOD', 'ABS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DEPTIPES_SEL', 'ABS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VERSIGPRO_IME', 'ABS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_CHKSTA_IME', 'ABS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIPDW_SEL', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_MOD', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_ELI', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_SEL', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_CABMOM_IME', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_INS', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VERDOC_IME', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIDOCPLAN_SEL', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VISTA_SEL', 'ABS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIPDW_SEL', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_MOD', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_ELI', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_SEL', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_CABMOM_IME', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_INS', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VERDOC_IME', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIDOCPLAN_SEL', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VISTA_SEL', 'ABS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'PM_PROVEEV_SEL', 'ABS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'PM_CONIG_SEL', 'ABS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'CONTA_ODT_SEL', 'ABS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'PM_CONIGPP_SEL', 'ABS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'PM_CONIGPAR_SEL', 'ABS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'TES_GETFILOP_IME', 'ABS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'PM_CONIGPRE_SEL', 'ABS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'PM_CONIGPARGES_SEL', 'ABS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'TES_REPPAGOS_SEL', 'ABS.3.5.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIPDW_SEL', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_MOD', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_ELI', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_SEL', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_CABMOM_IME', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_INS', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VERDOC_IME', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIDOCPLAN_SEL', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VISTA_SEL', 'ABS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIPES_SEL', 'ABS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIPPROC_SEL', 'ABS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DES_INS', 'ABS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DES_MOD', 'ABS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DES_ELI', 'ABS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DES_SEL', 'ABS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DHW_SEL', 'ABS.3.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIPDW_SEL', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_MOD', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_ELI', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_SEL', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_CABMOM_IME', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DWF_INS', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VERDOC_IME', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_TIDOCPLAN_SEL', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_VISTA_SEL', 'ABS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'WF_DOCWFAR_MOD', 'ABS.3.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'RH_FUNCIOCAR_SEL', 'ABS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'PM_GETGES_GET', 'ABS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_DET_INS', 'ABS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_DET_SEL', 'ABS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_SOL_INS', 'ABS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_MATR_SEL', 'ABS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_DET_INS', 'ABS.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_DET_MOD', 'ABS.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_DET_ELI', 'ABS.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Auxiliar Abastecimientos', 'MAT_DET_SEL', 'ABS.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'RH_FUNCIOCAR_SEL', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_GATNREP_SEL', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_DET_INS', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_SOL_INS', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_SOL_MOD', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_SOL_ELI', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_ANT_INS', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_SIG_IME', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_SOL_SEL', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_MATR_SEL', 'VBS');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DOCWFAR_MOD', 'VBS.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_FUNTIPES_SEL', 'VBS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIPES_SEL', 'VBS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DOCWFAR_MOD', 'VBS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DEPTIPES_SEL', 'VBS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VERSIGPRO_IME', 'VBS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_CHKSTA_IME', 'VBS.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIPDW_SEL', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_MOD', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_ELI', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_SEL', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_CABMOM_IME', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_INS', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VERDOC_IME', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIDOCPLAN_SEL', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VISTA_SEL', 'VBS.4.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIPDW_SEL', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_MOD', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_ELI', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_SEL', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_CABMOM_IME', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_INS', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VERDOC_IME', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIDOCPLAN_SEL', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VISTA_SEL', 'VBS.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'PM_PROVEEV_SEL', 'VBS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'PM_CONIG_SEL', 'VBS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'CONTA_ODT_SEL', 'VBS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'PM_CONIGPP_SEL', 'VBS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'PM_CONIGPAR_SEL', 'VBS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'TES_GETFILOP_IME', 'VBS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'PM_CONIGPRE_SEL', 'VBS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'PM_CONIGPARGES_SEL', 'VBS.3.5');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'TES_REPPAGOS_SEL', 'VBS.3.5.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIPDW_SEL', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_MOD', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_ELI', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_SEL', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_CABMOM_IME', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_INS', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VERDOC_IME', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIDOCPLAN_SEL', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VISTA_SEL', 'VBS.3.5.1.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIPES_SEL', 'VBS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIPPROC_SEL', 'VBS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DES_INS', 'VBS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DES_MOD', 'VBS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DES_ELI', 'VBS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DES_SEL', 'VBS.3.4');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DHW_SEL', 'VBS.3.3');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIPDW_SEL', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_MOD', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_ELI', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_SEL', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_CABMOM_IME', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DWF_INS', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VERDOC_IME', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_TIDOCPLAN_SEL', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_VISTA_SEL', 'VBS.3.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'WF_DOCWFAR_MOD', 'VBS.3.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'RH_FUNCIOCAR_SEL', 'VBS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'PM_GETGES_GET', 'VBS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_DET_INS', 'VBS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_DET_SEL', 'VBS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_SOL_INS', 'VBS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_MATR_SEL', 'VBS.2');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_DET_INS', 'VBS.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_DET_MOD', 'VBS.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_DET_ELI', 'VBS.1');
select pxp.f_insert_trol_procedimiento_gui ('GM - Visto Bueno Solicitud', 'MAT_DET_SEL', 'VBS.1');

/***********************************F-DEP-MAM-MAT-0-07/01/2017****************************************/

/***********************************I-DEP-FEA-MAT-0-7/11/2018****************************************/
CREATE VIEW mat.vaper (
    desc_persona,
    to_char,
    pais,
    estacion,
    punto_venta,
    obs_cierre,
    arqueo_moneda_local,
    arqueo_moneda_extranjera,
    monto_inicial,
    monto_inicial_moneda_extranjera,
    tipo_cambio,
    tiene_dos_monedas,
    moneda_local,
    moneda_extranjera,
    cod_moneda_local,
    cod_moneda_extranjera,
    efectivo_boletos_ml,
    efectivo_boletos_me,
    tarjeta_boletos_ml,
    tarjeta_boletos_me,
    cuenta_corriente_boletos_ml,
    cuenta_corriente_boletos_me,
    mco_boletos_ml,
    mco_boletos_me,
    otro_boletos_ml,
    otro_boletos_me,
    efectivo_ventas_ml,
    efectivo_ventas_me,
    tarjeta_ventas_ml,
    tarjeta_vetas_me,
    cuenta_corriente_ventas_ml,
    cuenta_corriente_ventas_me,
    mco_ventas_ml,
    mco_ventas_me,
    otro_ventas_ml,
    otro_ventas_me,
    comisiones_ml,
    comisiones_me,
    monto_ca_recibo_ml,
    monto_cc_recibo_ml)
AS
 WITH total_ventas AS (
        ( WITH forma_pago AS (
SELECT fp_1.id_forma_pago,
                    fp_1.id_moneda,
                        CASE
                            WHEN fp_1.codigo::text ~~ 'CA%'::text THEN 'CASH'::text
                            WHEN fp_1.codigo::text ~~ 'CC%'::text THEN 'CC'::text
                            WHEN fp_1.codigo::text ~~ 'CT%'::text THEN 'CT'::text
                            WHEN fp_1.codigo::text ~~ 'MCO%'::text THEN 'MCO'::text
                            ELSE 'OTRO'::text
                        END::character varying AS codigo
FROM obingresos.tforma_pago fp_1
                )
    SELECT u.desc_persona::character varying AS desc_persona,
            to_char(acc.fecha_apertura_cierre::timestamp with time zone,
                'DD/MM/YYYY'::text)::character varying AS to_char,
            COALESCE(ppv.codigo, ps.codigo) AS pais,
            COALESCE(lpv.codigo, ls.codigo) AS estacion,
            COALESCE(pv.codigo, s.codigo) AS punto_venta,
            acc.obs_cierre::character varying AS obs_cierre,
            acc.arqueo_moneda_local,
            acc.arqueo_moneda_extranjera,
            acc.monto_inicial,
            acc.monto_inicial_moneda_extranjera,
            6.960000 AS tipo_cambio,
            'si'::character varying AS tiene_dos_monedas,
            'Bolivianos (BOB)'::character varying AS moneda_local,
            'Dolares Americanos (USD)'::character varying AS moneda_extranjera,
            'BOB'::character varying AS cod_moneda_local,
            'USD'::character varying AS cod_moneda_extranjera,
            sum(
                CASE
                    WHEN fp.codigo::text = 'CASH'::text AND fp.id_moneda = 1
                        THEN bfp.importe
                    ELSE 0::numeric
                END) AS efectivo_boletos_ml,
            sum(
                CASE
                    WHEN fp.codigo::text = 'CASH'::text AND fp.id_moneda = 2
                        THEN bfp.importe
                    ELSE 0::numeric
                END) AS efectivo_boletos_me,
            sum(
                CASE
                    WHEN fp.codigo::text = 'CC'::text AND fp.id_moneda = 1 THEN
                        bfp.importe
                    ELSE 0::numeric
                END) AS tarjeta_boletos_ml,
            sum(
                CASE
                    WHEN fp.codigo::text = 'CC'::text AND fp.id_moneda = 2 THEN
                        bfp.importe
                    ELSE 0::numeric
                END) AS tarjeta_boletos_me,
            sum(
                CASE
                    WHEN fp.codigo::text = 'CT'::text AND fp.id_moneda = 1 THEN
                        bfp.importe
                    ELSE 0::numeric
                END) AS cuenta_corriente_boletos_ml,
            sum(
                CASE
                    WHEN fp.codigo::text = 'CT'::text AND fp.id_moneda = 2 THEN
                        bfp.importe
                    ELSE 0::numeric
                END) AS cuenta_corriente_boletos_me,
            sum(
                CASE
                    WHEN fp.codigo::text = 'MCO'::text AND fp.id_moneda = 1
                        THEN bfp.importe
                    ELSE 0::numeric
                END) AS mco_boletos_ml,
            sum(
                CASE
                    WHEN fp.codigo::text = 'MCO'::text AND fp.id_moneda = 2
                        THEN bfp.importe
                    ELSE 0::numeric
                END) AS mco_boletos_me,
            sum(
                CASE
                    WHEN fp.codigo::text = 'OTRO'::text AND fp.id_moneda = 1
                        THEN bfp.importe
                    ELSE 0::numeric
                END) AS otro_boletos_ml,
            sum(
                CASE
                    WHEN fp.codigo::text ~~ 'OTRO'::text AND fp.id_moneda = 2
                        THEN bfp.importe
                    ELSE 0::numeric
                END) AS otro_boletos_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CASH'::text AND fp2.id_moneda = 1
                        THEN vfp.monto_mb_efectivo
                    ELSE 0::numeric
                END) AS efectivo_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CASH'::text AND fp2.id_moneda = 2
                        THEN vfp.monto_mb_efectivo / 6.960000
                    ELSE 0::numeric
                END) AS efectivo_ventas_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CC'::text AND fp2.id_moneda = 1
                        THEN vfp.monto_mb_efectivo
                    ELSE 0::numeric
                END) AS tarjeta_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CC'::text AND fp2.id_moneda = 2
                        THEN vfp.monto_mb_efectivo / 6.960000
                    ELSE 0::numeric
                END) AS tarjeta_vetas_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CT'::text AND fp2.id_moneda = 1
                        THEN vfp.monto_mb_efectivo
                    ELSE 0::numeric
                END) AS cuenta_corriente_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CT'::text AND fp2.id_moneda = 2
                        THEN vfp.monto_mb_efectivo / 6.960000
                    ELSE 0::numeric
                END) AS cuenta_corriente_ventas_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'MCO'::text AND fp2.id_moneda = 1
                        THEN vfp.monto_mb_efectivo
                    ELSE 0::numeric
                END) AS mco_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'MCO'::text AND fp2.id_moneda = 2
                        THEN vfp.monto_mb_efectivo / 6.960000
                    ELSE 0::numeric
                END) AS mco_ventas_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'OTRO'::text AND fp2.id_moneda = 1
                        THEN vfp.monto_mb_efectivo
                    ELSE 0::numeric
                END) AS otro_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text ~~ 'OTRO'::text AND fp2.id_moneda = 2
                        THEN vfp.monto_mb_efectivo / 6.960000
                    ELSE 0::numeric
                END) AS otro_ventas_me,
            COALESCE((
        SELECT sum(ven.comision) AS sum
        FROM vef.tventa ven
        WHERE COALESCE(ven.comision, 0::numeric) > 0::numeric AND ven.id_moneda
            = 1 AND ven.fecha = acc.fecha_apertura_cierre AND ven.id_punto_venta = acc.id_punto_venta AND ven.id_usuario_cajero = acc.id_usuario_cajero AND ven.estado::text = 'finalizado'::text
        ), 0::numeric) + COALESCE((
        SELECT sum(bol.comision) AS sum
        FROM obingresos.tboleto_amadeus bol
        WHERE COALESCE(bol.comision, 0::numeric) > 0::numeric AND
            bol.id_moneda_boleto = 1 AND bol.fecha_emision = acc.fecha_apertura_cierre AND bol.id_punto_venta = acc.id_punto_venta AND bol.id_usuario_cajero = acc.id_usuario_cajero AND bol.estado::text = 'revisado'::text
        ), 0::numeric) AS comisiones_ml,
            COALESCE((
        SELECT sum(ven.comision) AS sum
        FROM vef.tventa ven
        WHERE COALESCE(ven.comision, 0::numeric) > 0::numeric AND ven.id_moneda
            = 2 AND ven.fecha = acc.fecha_apertura_cierre AND ven.id_punto_venta = acc.id_punto_venta AND ven.id_usuario_cajero = acc.id_usuario_cajero AND ven.estado::text = 'finalizado'::text
        ), 0::numeric) + COALESCE((
        SELECT sum(bol.comision) AS sum
        FROM obingresos.tboleto_amadeus bol
        WHERE COALESCE(bol.comision, 0::numeric) > 0::numeric AND
            bol.id_moneda_boleto = 2 AND bol.fecha_emision = acc.fecha_apertura_cierre AND bol.id_punto_venta = acc.id_punto_venta AND bol.id_usuario_cajero = acc.id_usuario_cajero AND bol.estado::text = 'revisado'::text
        ), 0::numeric) AS comisiones_me,
            acc.monto_ca_recibo_ml,
            acc.monto_cc_recibo_ml
    FROM vef.tapertura_cierre_caja acc
             JOIN segu.vusuario u ON u.id_usuario = acc.id_usuario_cajero
             LEFT JOIN vef.tsucursal s ON acc.id_sucursal = s.id_sucursal
             LEFT JOIN vef.tpunto_venta pv ON pv.id_punto_venta = acc.id_punto_venta
             LEFT JOIN vef.tsucursal spv ON spv.id_sucursal = pv.id_sucursal
             LEFT JOIN param.tlugar lpv ON lpv.id_lugar = spv.id_lugar
             LEFT JOIN param.tlugar ls ON ls.id_lugar = s.id_lugar
             LEFT JOIN param.tlugar ppv ON ppv.id_lugar =
                 param.f_get_id_lugar_pais(lpv.id_lugar)
             LEFT JOIN param.tlugar ps ON ps.id_lugar =
                 param.f_get_id_lugar_pais(ls.id_lugar)
             LEFT JOIN obingresos.tboleto_amadeus b ON b.id_usuario_cajero =
                 u.id_usuario AND b.fecha_emision = acc.fecha_apertura_cierre AND b.id_punto_venta = acc.id_punto_venta AND b.estado::text = 'revisado'::text AND b.voided::text = 'no'::text
             LEFT JOIN obingresos.tboleto_amadeus_forma_pago bfp ON
                 bfp.id_boleto_amadeus = b.id_boleto_amadeus
             LEFT JOIN forma_pago fp ON fp.id_forma_pago = bfp.id_forma_pago
             LEFT JOIN vef.tventa v ON v.id_usuario_cajero = u.id_usuario AND
                 v.fecha = acc.fecha_apertura_cierre AND v.id_punto_venta = acc.id_punto_venta AND v.estado::text = 'finalizado'::text
             LEFT JOIN vef.tventa_forma_pago vfp ON vfp.id_venta = v.id_venta
             LEFT JOIN forma_pago fp2 ON fp2.id_forma_pago = vfp.id_forma_pago
    WHERE acc.id_apertura_cierre_caja = ANY (ARRAY[3623])
    GROUP BY u.desc_persona, acc.fecha_apertura_cierre, ppv.codigo, ps.codigo,
        lpv.codigo, ls.codigo, pv.codigo, pv.nombre, s.codigo, s.nombre, acc.id_punto_venta, acc.id_usuario_cajero, acc.obs_cierre, acc.arqueo_moneda_local, acc.arqueo_moneda_extranjera, acc.monto_inicial, acc.monto_inicial_moneda_extranjera, acc.monto_ca_recibo_ml, acc.monto_cc_recibo_ml
    )
UNION ALL
        ( WITH forma_pago AS (
SELECT fp.id_forma_pago,
                    fp.id_moneda,
                        CASE
                            WHEN fp.codigo::text ~~ 'CA%'::text THEN 'CASH'::text
                            WHEN fp.codigo::text ~~ 'CC%'::text THEN 'CC'::text
                            WHEN fp.codigo::text ~~ 'CT%'::text THEN 'CT'::text
                            WHEN fp.codigo::text ~~ 'MCO%'::text THEN 'MCO'::text
                            ELSE 'OTRO'::text
                        END::character varying AS codigo
FROM obingresos.tforma_pago fp
                )
    SELECT u.desc_persona::character varying AS desc_persona,
            to_char(acc.fecha_apertura_cierre::timestamp with time zone,
                'DD/MM/YYYY'::text)::character varying AS fecha_apertura_cierre,
            v.pais,
            v.estacion,
            v.agt::character varying AS punto_venta,
            acc.obs_cierre::character varying AS obs_cierre,
            acc.arqueo_moneda_local,
            acc.arqueo_moneda_extranjera,
            acc.monto_inicial,
            acc.monto_inicial_moneda_extranjera,
            6.960000 AS tipo_cambio,
            'si'::character varying AS tiene_dos_monedas,
            'Bolivianos (BOB)'::character varying AS moneda_local,
            'Dolares Americanos (USD)'::character varying AS moneda_extranjera,
            'BOB'::character varying AS cod_moneda_local,
            'USD'::character varying AS cod_moneda_extranjera,
            0 AS efectivo_boletos_ml,
            0 AS efectivo_boletos_me,
            0 AS tarjeta_boletos_ml,
            0 AS tarjeta_boletos_me,
            0 AS cuenta_corriente_boletos_ml,
            0 AS cuenta_corriente_boletos_me,
            0 AS mco_boletos_ml,
            0 AS mco_boletos_me,
            0 AS otro_boletos_ml,
            0 AS otro_boletos_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CASH'::text AND fp2.id_moneda = 1
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS efectivo_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CASH'::text AND fp2.id_moneda = 2
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS efectivo_ventas_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CC'::text AND fp2.id_moneda = 1
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS tarjeta_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CC'::text AND fp2.id_moneda = 2
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS tarjeta_vetas_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CT'::text AND fp2.id_moneda = 1
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS cuenta_corriente_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'CT'::text AND fp2.id_moneda = 2
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS cuenta_corriente_ventas_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'MCO'::text AND fp2.id_moneda = 1
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS mco_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'MCO'::text AND fp2.id_moneda = 2
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS mco_ventas_me,
            sum(
                CASE
                    WHEN fp2.codigo::text = 'OTRO'::text AND fp2.id_moneda = 1
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS otro_ventas_ml,
            sum(
                CASE
                    WHEN fp2.codigo::text ~~ 'OTRO'::text AND fp2.id_moneda = 2
                        THEN vfp.importe_pago
                    ELSE 0::numeric
                END) AS otro_ventas_me,
            0 AS comisiones_ml,
            0 AS comisiones_me,
            0 AS monto_ca_recibo_ml,
            0 AS monto_cc_recibo_ml
    FROM vef.tapertura_cierre_caja acc
             JOIN vef.tpunto_venta pv ON pv.id_punto_venta = acc.id_punto_venta
             JOIN segu.vusuario u ON u.id_usuario = acc.id_usuario_cajero
             JOIN vef.tfactucom_endesis v ON v.fecha =
                 acc.fecha_apertura_cierre AND v.estado_reg::text = 'emitida'::text AND v.usuario::text = u.cuenta::text AND v.agt::character varying::text = pv.codigo::text
             JOIN vef.tfactucompag_endesis vfp ON vfp.id_factucom = v.id_factucom
             JOIN forma_pago fp2 ON fp2.id_forma_pago = ((
        SELECT fp.id_forma_pago
        FROM obingresos.tforma_pago fp
                     JOIN param.tmoneda mon ON mon.id_moneda = fp.id_moneda
                     JOIN param.tlugar lug ON lug.id_lugar = fp.id_lugar
        WHERE fp.codigo::text = vfp.forma::text AND
            mon.codigo_internacional::text = vfp.moneda::text AND lug.codigo::text = vfp.pais::text
        ))
    WHERE (acc.id_apertura_cierre_caja = ANY (ARRAY[3623])) AND
        v.sw_excluir::text = 'no'::text
    GROUP BY u.desc_persona, acc.fecha_apertura_cierre, acc.id_punto_venta,
        acc.id_usuario_cajero, acc.obs_cierre, acc.arqueo_moneda_local, acc.arqueo_moneda_extranjera, acc.monto_inicial, acc.monto_inicial_moneda_extranjera, v.pais, v.estacion, v.agt, v.razon_sucursal
    )
        )
    SELECT total_ventas.desc_persona,
    total_ventas.to_char,
    total_ventas.pais,
    total_ventas.estacion,
    total_ventas.punto_venta,
    total_ventas.obs_cierre,
    total_ventas.arqueo_moneda_local,
    total_ventas.arqueo_moneda_extranjera,
    total_ventas.monto_inicial,
    total_ventas.monto_inicial_moneda_extranjera,
    total_ventas.tipo_cambio,
    total_ventas.tiene_dos_monedas,
    total_ventas.moneda_local,
    total_ventas.moneda_extranjera,
    total_ventas.cod_moneda_local,
    total_ventas.cod_moneda_extranjera,
    sum(total_ventas.efectivo_boletos_ml) AS efectivo_boletos_ml,
    sum(total_ventas.efectivo_boletos_me) AS efectivo_boletos_me,
    sum(total_ventas.tarjeta_boletos_ml) AS tarjeta_boletos_ml,
    sum(total_ventas.tarjeta_boletos_me) AS tarjeta_boletos_me,
    sum(total_ventas.cuenta_corriente_boletos_ml) AS cuenta_corriente_boletos_ml,
    sum(total_ventas.cuenta_corriente_boletos_me) AS cuenta_corriente_boletos_me,
    sum(total_ventas.mco_boletos_ml) AS mco_boletos_ml,
    sum(total_ventas.mco_boletos_me) AS mco_boletos_me,
    sum(total_ventas.otro_boletos_ml) AS otro_boletos_ml,
    sum(total_ventas.otro_boletos_me) AS otro_boletos_me,
    sum(total_ventas.efectivo_ventas_ml) AS efectivo_ventas_ml,
    sum(total_ventas.efectivo_ventas_me) AS efectivo_ventas_me,
    sum(total_ventas.tarjeta_ventas_ml) AS tarjeta_ventas_ml,
    sum(total_ventas.tarjeta_vetas_me) AS tarjeta_vetas_me,
    sum(total_ventas.cuenta_corriente_ventas_ml) AS cuenta_corriente_ventas_ml,
    sum(total_ventas.cuenta_corriente_ventas_me) AS cuenta_corriente_ventas_me,
    sum(total_ventas.mco_ventas_ml) AS mco_ventas_ml,
    sum(total_ventas.mco_ventas_me) AS mco_ventas_me,
    sum(total_ventas.otro_ventas_ml) AS otro_ventas_ml,
    sum(total_ventas.otro_ventas_me) AS otro_ventas_me,
    sum(total_ventas.comisiones_ml) AS comisiones_ml,
    sum(total_ventas.comisiones_me) AS comisiones_me,
    sum(total_ventas.monto_ca_recibo_ml) AS monto_ca_recibo_ml,
    sum(total_ventas.monto_cc_recibo_ml) AS monto_cc_recibo_ml
    FROM total_ventas
    GROUP BY total_ventas.desc_persona, total_ventas.to_char,
        total_ventas.pais, total_ventas.estacion, total_ventas.obs_cierre, total_ventas.arqueo_moneda_local, total_ventas.arqueo_moneda_extranjera, total_ventas.monto_inicial, total_ventas.monto_inicial_moneda_extranjera, total_ventas.punto_venta, total_ventas.tipo_cambio, total_ventas.tiene_dos_monedas, total_ventas.moneda_local, total_ventas.moneda_extranjera, total_ventas.cod_moneda_local, total_ventas.cod_moneda_extranjera;


CREATE VIEW mat.vsolicitud (
    id_solicitud,
    fecha_solicitud,
    motivo_orden,
    matricula,
    nro_tramite,
    justificacion,
    tipo_solicitud,
    fecha_requerida,
    motivo_solicitud,
    observaciones_sol,
    desc_funcionario1,
    tipo_falla,
    tipo_reporte,
    mel,
    estado,
    detalle,
    origen_pedido,
    id_estado_wf,
    id_proceso_wf,
    nro_cotizacion,
    f_recuperar_correos,
    cotizacion_solicitadas,
    mensaje_correo)
AS
SELECT sol.id_solicitud,
    to_char(sol.fecha_solicitud::timestamp with time zone, 'DD/MM/YYYY'::text)
        AS fecha_solicitud,
    ot.motivo_orden,
    "left"(ot.desc_orden::text, 20) AS matricula,
    sol.nro_tramite,
    sol.justificacion,
    sol.tipo_solicitud,
    COALESCE(to_char(sol.fecha_requerida::timestamp with time zone,
        'DD/MM/YYYY'::text), ''::text) AS fecha_requerida,
    sol.motivo_solicitud,
    sol.observaciones_sol,
    initcap(f.desc_funcionario1) AS desc_funcionario1,
    sol.tipo_falla,
    sol.tipo_reporte,
    sol.mel,
    ti.codigo AS estado,
    mat.f_get_detalle_html(sol.id_solicitud)::text AS detalle,
    sol.origen_pedido,
    sol.id_estado_wf,
    sol.id_proceso_wf,
        CASE
            WHEN substr(sol.nro_tramite::text, 1, 2) = 'GM'::text THEN
                'GM - '::text || ltrim(substr(sol.nro_tramite::text, 7, 6), '0'::text)
            WHEN substr(sol.nro_tramite::text, 1, 2) = 'GA'::text THEN
                'GA - '::text || ltrim(substr(sol.nro_tramite::text, 7, 6), '0'::text)
            WHEN substr(sol.nro_tramite::text, 1, 2) = 'GO'::text THEN
                'GO - '::text || ltrim(substr(sol.nro_tramite::text, 7, 6), '0'::text)
            WHEN substr(sol.nro_tramite::text, 1, 2) = 'GC'::text THEN
                'GC - '::text || ltrim(substr(sol.nro_tramite::text, 7, 6), '0'::text)
            ELSE 'SIN GERENCIA'::text
        END AS nro_cotizacion,
    mat.f_recuperar_correos((
    SELECT pxp.aggarray(n.id_proveedor) AS aggarray
    FROM mat.tgestion_proveedores_new n
    WHERE n.id_solicitud = sol.id_solicitud
    GROUP BY n.id_solicitud
    )) AS f_recuperar_correos,
    tgp.cotizacion_solicitadas,
    sol.mensaje_correo
FROM mat.tsolicitud sol
     LEFT JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo = sol.id_matricula
     JOIN orga.vfuncionario f ON f.id_funcionario = sol.id_funcionario_sol
     JOIN wf.testado_wf wof ON wof.id_estado_wf = sol.id_estado_wf
     JOIN wf.ttipo_estado ti ON ti.id_tipo_estado = wof.id_tipo_estado
     LEFT JOIN mat.tgestion_proveedores tgp ON tgp.id_solicitud = sol.id_solicitud;
CREATE VIEW mat.vsolicitud_firma (
    id_solicitud,
    fecha_solicitud,
    motivo_orden,
    matricula,
    nro_tramite,
    nro_parte,
    referencia,
    descripcion,
    cantidad_sol,
    id_unidad_medida,
    justificacion,
    tipo_solicitud,
    fecha_requerida,
    motivo_solicitud,
    observaciones_sol,
    desc_funcionario1,
    tipo_falla,
    tipo_reporte,
    mel,
    estado,
    detalle,
    origen_pedido,
    id_estado_wf,
    id_proceso_wf)
AS
SELECT sol.id_solicitud,
    to_char(sol.fecha_solicitud::timestamp with time zone, 'DD/MM/YYYY'::text)
        AS fecha_solicitud,
    ot.motivo_orden,
    "left"(ot.desc_orden::text, 20) AS matricula,
    sol.nro_tramite,
    de.nro_parte,
    de.referencia,
    de.descripcion,
    de.cantidad_sol,
    de.id_unidad_medida,
    sol.justificacion,
    sol.tipo_solicitud,
    COALESCE(to_char(sol.fecha_requerida::timestamp with time zone,
        'DD/MM/YYYY'::text), ''::text) AS fecha_requerida,
    sol.motivo_solicitud,
    sol.observaciones_sol,
    initcap(f.desc_funcionario1) AS desc_funcionario1,
    sol.tipo_falla,
    sol.tipo_reporte,
    sol.mel,
    ti.codigo AS estado,
    ('<table border="1"><TR>
   								<TH>Nro. Parte</TH>
   								<TH>Referencia</TH>
   								<TH>Descripción</TH>
   								<TH>Cantidad</TH>
   								<TH>Unidad de
       Medida</TH>'::text || pxp.html_rows((((((((((('<td>'::text || COALESCE(de.nro_parte::text, '-'::text)) || '</td>
       							<td>'::text) || COALESCE(de.referencia::text, '-'::text)) || '</td>
           						<td>'::text) || COALESCE(de.descripcion::text, '-'::text)) || '</td>
             					<td>'::text) || COALESCE(de.cantidad_sol::text, '-'::text)) || '</td>
             					<td>'::text) || COALESCE(de.id_unidad_medida::text, '-'::text)) || '</td>'::character varying::text)::character varying)::text) || '</table>'::text AS detalle,
    sol.origen_pedido,
    sol.id_estado_wf,
    sol.id_proceso_wf
FROM mat.tsolicitud sol
     JOIN mat.tdetalle_sol de ON de.id_solicitud = sol.id_solicitud
     LEFT JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo = sol.id_matricula
     JOIN orga.vfuncionario f ON f.id_funcionario = sol.id_funcionario_sol
     LEFT JOIN wf.testado_wf wof ON wof.id_estado_wf = sol.id_estado_wf_firma
     JOIN wf.ttipo_estado ti ON ti.id_tipo_estado = wof.id_tipo_estado
GROUP BY sol.id_solicitud, sol.fecha_solicitud, ot.motivo_orden, ot.desc_orden,
    sol.nro_tramite, de.nro_parte, de.referencia, de.descripcion, de.cantidad_sol, de.id_unidad_medida, sol.justificacion, sol.tipo_solicitud, sol.fecha_requerida, sol.motivo_solicitud, sol.observaciones_sol, f.desc_funcionario1, sol.tipo_falla, sol.tipo_reporte, sol.mel, ti.codigo;

CREATE VIEW mat.vsolicitud_mayor_500000 (
    id_solicitud,
    id_proceso_wf,
    nro_tramite,
    fecha_solicitud,
    funcionario,
    nro_po,
    fecha_po,
    proveedor,
    monto_dolares,
    monto_bolivianos)
AS
SELECT so.id_solicitud,
    so.id_proceso_wf,
    so.nro_tramite,
    so.fecha_solicitud,
    f.desc_funcionario1 AS funcionario,
    so.nro_po,
    so.fecha_po,
    po.desc_proveedor AS proveedor,
    co.monto_total AS monto_dolares,
    co.monto_total * 6.96 AS monto_bolivianos
FROM mat.tsolicitud so
     JOIN orga.vfuncionario f ON f.id_funcionario = so.id_funcionario_sol
     JOIN mat.tcotizacion co ON co.id_solicitud = so.id_solicitud AND
         co.adjudicado::text = 'si'::text
     JOIN param.vproveedor po ON po.id_proveedor = co.id_proveedor
WHERE (co.monto_total * 6.96) > 50000::numeric
ORDER BY so.nro_tramite;
/***********************************F-DEP-FEA-MAT-0-7/11/2018****************************************/