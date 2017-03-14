/***********************************I-DAT-MAM-MAT-0-07/01/2017****************************************/
----------------------------------
--COPY LINES TO data.sql FILE
---------------------------------

select pxp.f_insert_tgui ('GESTION DE MATERIALES', '', 'MAT', 'si', 1, '', 1, '', '', 'MAT');
select pxp.f_insert_tgui ('Requerimiento de Materiales', 'Requerimiento de materiales', 'REMA', 'si', 1, 'sis_gestion_materiales/vista/solicitud/RegistroSolicitud.php', 2, '', 'RegistroSolicitud', 'MAT');
select pxp.f_delete_tgui ('DET');
select pxp.f_insert_tgui ('Visto Bueno Solicitud', 'Visto Bueno Solicitud', 'VBS', 'si', 2, 'sis_gestion_materiales/vista/solicitud/SolicitudVistoBueno.php', 2, '', 'SolicitudVistoBueno', 'MAT');
select pxp.f_insert_tgui ('Consulta Requerimientos', 'Consulta Requerimientos', 'CR', 'si', 4, 'sis_gestion_materiales/vista/solicitud/ConsultaRequerimientos.php', 2, '', 'ConsultaRequerimientos', 'MAT');
select pxp.f_insert_tgui ('Abastecimiento', 'Abastecimiento', 'ABS', 'si', 3, 'sis_gestion_materiales/vista/solicitud/AbastecimientoSolicitud.php', 2, '', 'AbastecimientoSolicitud', 'MAT');
select pxp.f_insert_tgui ('Formulario Requerimiento de Materiales', 'Formulario Requerimiento de Materiales', 'REMA.1', 'no', 0, 'sis_gestion_materiales/vista/solicitud/FromFormula.php', 3, '', '65%', 'MAT');
select pxp.f_insert_tgui ('Detalle', 'Detalle', 'REMA.2', 'no', 0, 'sis_gestion_materiales/vista/detalle_sol/DetalleSol.php', 3, '', '50%', 'MAT');
select pxp.f_insert_tgui ('Chequear documento del WF', 'Chequear documento del WF', 'REMA.3', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 3, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Estado de Wf', 'Estado de Wf', 'REMA.4', 'no', 0, 'sis_workflow/vista/estado_wf/FormEstadoWf.php', 3, '', 'FormEstadoWf', 'MAT');
select pxp.f_insert_tgui ('Estado de Wf', 'Estado de Wf', 'REMA.5', 'no', 0, 'sis_workflow/vista/estado_wf/AntFormEstadoWf.php', 3, '', 'AntFormEstadoWf', 'MAT');
select pxp.f_insert_tgui ('Subir ', 'Subir ', 'REMA.3.1', 'no', 0, 'sis_workflow/vista/documento_wf/SubirArchivoWf.php', 4, '', 'SubirArchivoWf', 'MAT');
select pxp.f_insert_tgui ('Documentos de Origen', 'Documentos de Origen', 'REMA.3.2', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Histórico', 'Histórico', 'REMA.3.3', 'no', 0, 'sis_workflow/vista/documento_historico_wf/DocumentoHistoricoWf.php', 4, '', '30%', 'MAT');
select pxp.f_insert_tgui ('Estados por momento', 'Estados por momento', 'REMA.3.4', 'no', 0, 'sis_workflow/vista/tipo_documento_estado/TipoDocumentoEstadoWF.php', 4, '', '40%', 'MAT');
select pxp.f_insert_tgui ('Pagos similares', 'Pagos similares', 'REMA.3.5', 'no', 0, 'sis_tesoreria/vista/plan_pago/RepFilPlanPago.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('73%', '73%', 'REMA.3.5.1', 'no', 0, 'sis_tesoreria/vista/plan_pago/RepPlanPago.php', 5, '', 'RepPlanPago', 'MAT');
select pxp.f_insert_tgui ('Chequear documento del WF', 'Chequear documento del WF', 'REMA.3.5.1.1', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 6, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Documentos del Proceso', 'Documentos del Proceso', 'REMA.4.1', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Detalle', 'Detalle', 'VBS.1', 'no', 0, 'sis_gestion_materiales/vista/detalle_sol/DetalleSol.php', 3, '', '50%', 'MAT');
select pxp.f_insert_tgui ('Formulario Requerimiento de Materiales', 'Formulario Requerimiento de Materiales', 'VBS.2', 'no', 0, 'sis_gestion_materiales/vista/solicitud/FromFormula.php', 3, '', '65%', 'MAT');
select pxp.f_insert_tgui ('Chequear documento del WF', 'Chequear documento del WF', 'VBS.3', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 3, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Estado de Wf', 'Estado de Wf', 'VBS.4', 'no', 0, 'sis_workflow/vista/estado_wf/FormEstadoWf.php', 3, '', 'FormEstadoWf', 'MAT');
select pxp.f_insert_tgui ('Estado de Wf', 'Estado de Wf', 'VBS.5', 'no', 0, 'sis_workflow/vista/estado_wf/AntFormEstadoWf.php', 3, '', 'AntFormEstadoWf', 'MAT');
select pxp.f_insert_tgui ('Subir ', 'Subir ', 'VBS.3.1', 'no', 0, 'sis_workflow/vista/documento_wf/SubirArchivoWf.php', 4, '', 'SubirArchivoWf', 'MAT');
select pxp.f_insert_tgui ('Documentos de Origen', 'Documentos de Origen', 'VBS.3.2', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Histórico', 'Histórico', 'VBS.3.3', 'no', 0, 'sis_workflow/vista/documento_historico_wf/DocumentoHistoricoWf.php', 4, '', '30%', 'MAT');
select pxp.f_insert_tgui ('Estados por momento', 'Estados por momento', 'VBS.3.4', 'no', 0, 'sis_workflow/vista/tipo_documento_estado/TipoDocumentoEstadoWF.php', 4, '', '40%', 'MAT');
select pxp.f_insert_tgui ('Pagos similares', 'Pagos similares', 'VBS.3.5', 'no', 0, 'sis_tesoreria/vista/plan_pago/RepFilPlanPago.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('73%', '73%', 'VBS.3.5.1', 'no', 0, 'sis_tesoreria/vista/plan_pago/RepPlanPago.php', 5, '', 'RepPlanPago', 'MAT');
select pxp.f_insert_tgui ('Chequear documento del WF', 'Chequear documento del WF', 'VBS.3.5.1.1', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 6, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Documentos del Proceso', 'Documentos del Proceso', 'VBS.4.1', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Detalle', 'Detalle', 'CR.1', 'no', 0, 'sis_gestion_materiales/vista/detalle_sol/DetalleSol.php', 3, '', '50%', 'MAT');
select pxp.f_insert_tgui ('Formulario Requerimiento de Materiales', 'Formulario Requerimiento de Materiales', 'CR.2', 'no', 0, 'sis_gestion_materiales/vista/solicitud/FromFormula.php', 3, '', '65%', 'MAT');
select pxp.f_insert_tgui ('Chequear documento del WF', 'Chequear documento del WF', 'CR.3', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 3, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Estado de Wf', 'Estado de Wf', 'CR.4', 'no', 0, 'sis_workflow/vista/estado_wf/FormEstadoWf.php', 3, '', 'FormEstadoWf', 'MAT');
select pxp.f_insert_tgui ('Estado de Wf', 'Estado de Wf', 'CR.5', 'no', 0, 'sis_workflow/vista/estado_wf/AntFormEstadoWf.php', 3, '', 'AntFormEstadoWf', 'MAT');
select pxp.f_insert_tgui ('Subir ', 'Subir ', 'CR.3.1', 'no', 0, 'sis_workflow/vista/documento_wf/SubirArchivoWf.php', 4, '', 'SubirArchivoWf', 'MAT');
select pxp.f_insert_tgui ('Documentos de Origen', 'Documentos de Origen', 'CR.3.2', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Histórico', 'Histórico', 'CR.3.3', 'no', 0, 'sis_workflow/vista/documento_historico_wf/DocumentoHistoricoWf.php', 4, '', '30%', 'MAT');
select pxp.f_insert_tgui ('Estados por momento', 'Estados por momento', 'CR.3.4', 'no', 0, 'sis_workflow/vista/tipo_documento_estado/TipoDocumentoEstadoWF.php', 4, '', '40%', 'MAT');
select pxp.f_insert_tgui ('Pagos similares', 'Pagos similares', 'CR.3.5', 'no', 0, 'sis_tesoreria/vista/plan_pago/RepFilPlanPago.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('73%', '73%', 'CR.3.5.1', 'no', 0, 'sis_tesoreria/vista/plan_pago/RepPlanPago.php', 5, '', 'RepPlanPago', 'MAT');
select pxp.f_insert_tgui ('Chequear documento del WF', 'Chequear documento del WF', 'CR.3.5.1.1', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 6, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Documentos del Proceso', 'Documentos del Proceso', 'CR.4.1', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Detalle', 'Detalle', 'ABS.1', 'no', 0, 'sis_gestion_materiales/vista/detalle_sol/DetalleSol.php', 3, '', '50%', 'MAT');
select pxp.f_insert_tgui ('Formulario Requerimiento de Materiales', 'Formulario Requerimiento de Materiales', 'ABS.2', 'no', 0, 'sis_gestion_materiales/vista/solicitud/FromFormula.php', 3, '', '65%', 'MAT');
select pxp.f_insert_tgui ('Chequear documento del WF', 'Chequear documento del WF', 'ABS.3', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 3, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Estado de Wf', 'Estado de Wf', 'ABS.4', 'no', 0, 'sis_workflow/vista/estado_wf/FormEstadoWf.php', 3, '', 'FormEstadoWf', 'MAT');
select pxp.f_insert_tgui ('Estado de Wf', 'Estado de Wf', 'ABS.5', 'no', 0, 'sis_workflow/vista/estado_wf/AntFormEstadoWf.php', 3, '', 'AntFormEstadoWf', 'MAT');
select pxp.f_insert_tgui ('Subir ', 'Subir ', 'ABS.3.1', 'no', 0, 'sis_workflow/vista/documento_wf/SubirArchivoWf.php', 4, '', 'SubirArchivoWf', 'MAT');
select pxp.f_insert_tgui ('Documentos de Origen', 'Documentos de Origen', 'ABS.3.2', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Histórico', 'Histórico', 'ABS.3.3', 'no', 0, 'sis_workflow/vista/documento_historico_wf/DocumentoHistoricoWf.php', 4, '', '30%', 'MAT');
select pxp.f_insert_tgui ('Estados por momento', 'Estados por momento', 'ABS.3.4', 'no', 0, 'sis_workflow/vista/tipo_documento_estado/TipoDocumentoEstadoWF.php', 4, '', '40%', 'MAT');
select pxp.f_insert_tgui ('Pagos similares', 'Pagos similares', 'ABS.3.5', 'no', 0, 'sis_tesoreria/vista/plan_pago/RepFilPlanPago.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tgui ('73%', '73%', 'ABS.3.5.1', 'no', 0, 'sis_tesoreria/vista/plan_pago/RepPlanPago.php', 5, '', 'RepPlanPago', 'MAT');
select pxp.f_insert_tgui ('Chequear documento del WF', 'Chequear documento del WF', 'ABS.3.5.1.1', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 6, '', '90%', 'MAT');
select pxp.f_insert_tgui ('Documentos del Proceso', 'Documentos del Proceso', 'ABS.4.1', 'no', 0, 'sis_workflow/vista/documento_wf/DocumentoWf.php', 4, '', '90%', 'MAT');
select pxp.f_insert_tfuncion ('mat.f_ant_estado_solicitud_wf', 'Funcion para tabla     ', 'MAT');
select pxp.f_insert_tfuncion ('mat.f_procesar_estados_solicitud', 'Funcion para tabla     ', 'MAT');
select pxp.f_insert_tfuncion ('mat.ft_detalle_sol_ime', 'Funcion para tabla     ', 'MAT');
select pxp.f_insert_tfuncion ('mat.ft_detalle_sol_sel', 'Funcion para tabla     ', 'MAT');
select pxp.f_insert_tfuncion ('mat.ft_solicitud_ime', 'Funcion para tabla     ', 'MAT');
select pxp.f_insert_tfuncion ('mat.ft_solicitud_sel', 'Funcion para tabla     ', 'MAT');
select pxp.f_insert_tprocedimiento ('MAT_DET_INS', 'Insercion de registros', 'si', '', '', 'mat.ft_detalle_sol_ime');
select pxp.f_insert_tprocedimiento ('MAT_DET_MOD', 'Modificacion de registros', 'si', '', '', 'mat.ft_detalle_sol_ime');
select pxp.f_insert_tprocedimiento ('MAT_DET_ELI', 'Eliminacion de registros', 'si', '', '', 'mat.ft_detalle_sol_ime');
select pxp.f_insert_tprocedimiento ('MAT_DET_SEL', 'Consulta de datos', 'si', '', '', 'mat.ft_detalle_sol_sel');
select pxp.f_insert_tprocedimiento ('MAT_DET_CONT', 'Conteo de registros', 'si', '', '', 'mat.ft_detalle_sol_sel');
select pxp.f_insert_tprocedimiento ('MAT_SOL_INS', 'Insercion de registros', 'si', '', '', 'mat.ft_solicitud_ime');
select pxp.f_insert_tprocedimiento ('MAT_SOL_MOD', 'Modificacion de registros', 'si', '', '', 'mat.ft_solicitud_ime');
select pxp.f_insert_tprocedimiento ('MAT_SOL_ELI', 'Eliminacion de registros', 'si', '', '', 'mat.ft_solicitud_ime');
select pxp.f_insert_tprocedimiento ('MAT_ANT_INS', 'Estado Anterior', 'si', '', '', 'mat.ft_solicitud_ime');
select pxp.f_insert_tprocedimiento ('MAT_SIG_IME', 'Siguiente', 'si', '', '', 'mat.ft_solicitud_ime');
select pxp.f_insert_tprocedimiento ('MAT_SOL_SEL', 'Consulta de datos', 'si', '', '', 'mat.ft_solicitud_sel');
select pxp.f_insert_tprocedimiento ('MAT_SOL_CONT', 'Conteo de registros', 'si', '', '', 'mat.ft_solicitud_sel');
select pxp.f_insert_tprocedimiento ('MAT_MATR_SEL', 'Matricul', 'si', '', '', 'mat.ft_solicitud_sel');
select pxp.f_insert_tprocedimiento ('MAT_REING_SEL', 'Reporte requerimiento de materiales ingeniaeria', 'si', '', '', 'mat.ft_solicitud_sel');
select pxp.f_insert_trol ('GM - Solicitante', 'GM - Solicitante', 'MAT');
select pxp.f_insert_trol ('GM - Visto Bueno Solicitud', 'GM - Visto Bueno Solicitud', 'MAT');
select pxp.f_insert_trol ('GM - Abastecimientos', 'GM - Abastecimientos', 'MAT');
select pxp.f_insert_trol ('GM - Auxiliar Abastecimientos', 'GM - Auxiliar Abastecimientos', 'MAT');
/***********************************F-DAT-MAM-MAT-0-07/01/2017****************************************/

/***********************************I-DAT-MAM-MAT-0-12/01/2017****************************************/
/* Data for the 'mat.tunidad_medida' table  (Records 1 - 25) */

INSERT INTO mat.tunidad_medida ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_unidad_medida", "codigo", "descripcion", "tipo_unidad_medida")
VALUES
  (NULL, NULL, E'2017-01-12 11:56:11.160', E'2017-01-12 11:56:11.160', E'activo', NULL, NULL, 1, E'EA', E'Each', E'Cantidad'),
  (NULL, NULL, E'2017-01-12 12:17:51.192', E'2017-01-12 12:17:51.192', E'activo', NULL, NULL, 2, E'MT', E'Meter', E'Longitud'),
  (NULL, NULL, E'2017-01-12 12:18:48.363', E'2017-01-12 12:18:48.363', E'activo', NULL, NULL, 3, E'Kg', E'Kilogram', E'Weight'),
  (NULL, NULL, E'2017-01-12 12:19:00.963', E'2017-01-12 12:19:00.963', E'activo', NULL, NULL, 4, E'Lb', E'Pound', E'Weight'),
  (NULL, NULL, E'2017-01-12 12:19:12.523', E'2017-01-12 12:19:12.523', E'activo', NULL, NULL, 5, E'Lt', E'Liter', E'Volume'),
  (NULL, NULL, E'2017-01-12 12:19:48.808', E'2017-01-12 12:19:48.808', E'activo', NULL, NULL, 6, E'Kg/Lt', E'Kg/L', E'Density'),
  (NULL, NULL, E'2017-01-12 12:20:00.935', E'2017-01-12 12:20:00.935', E'activo', NULL, NULL, 7, E'Lb/UsG', E'Lb/UsG', E'Density'),
  (NULL, NULL, E'2017-01-12 12:20:18.903', E'2017-01-12 12:20:18.903', E'activo', NULL, NULL, 8, E'ºC', E'Degree Celsius', E'Temperature'),
  (NULL, NULL, E'2017-01-12 12:20:30.979', E'2017-01-12 12:20:30.979', E'activo', NULL, NULL, 9, E'ºF', E'Degree Fahrenheit', E'Temperature'),
  (NULL, NULL, E'2017-01-12 12:21:00.969', E'2017-01-12 12:21:00.969', E'activo', NULL, NULL, 10, E'USG', E'US Gallon', E'Volume'),
  (NULL, NULL, E'2017-01-12 12:23:30.135', E'2017-01-12 12:23:30.135', E'activo', NULL, NULL, 11, E'UKG', E'UK Gallon', E'Weight'),
  (NULL, NULL, E'2017-01-12 12:23:40.100', E'2017-01-12 12:23:40.100', E'activo', NULL, NULL, 12, E'FT', E'Feets', E'Longitud'),
  (NULL, NULL, E'2017-01-12 12:23:46.708', E'2017-01-12 12:23:46.708', E'activo', NULL, NULL, 13, E'IN', E'Inches', E'Longitud'),
  (NULL, NULL, E'2017-01-12 12:23:53.725', E'2017-01-12 12:23:53.725', E'activo', NULL, NULL, 14, E'DR', E'DR', E'Longitud'),
  (NULL, NULL, E'2017-01-12 12:23:59.965', E'2017-01-12 12:23:59.965', E'activo', NULL, NULL, 15, E'QT', E'Quarter', E'Cantidad'),
  (NULL, NULL, E'2017-01-12 12:24:06.252', E'2017-01-12 12:24:06.252', E'activo', NULL, NULL, 16, E'KT', E'KIT', E'Cantidad'),
  (NULL, NULL, E'2017-01-12 12:24:13.828', E'2017-01-12 12:24:13.828', E'activo', NULL, NULL, 17, E'TB', E'Tube', E'Cantidad'),
  (NULL, NULL, E'2017-01-12 12:24:19.972', E'2017-01-12 12:24:19.972', E'activo', NULL, NULL, 18, E'YD', E'Yards', E'Longitud'),
  (NULL, NULL, E'2017-01-12 12:24:25.642', E'2017-01-12 12:24:25.642', E'activo', NULL, NULL, 19, E'BL', E'Block', E'Cantidad'),
  (NULL, NULL, E'2017-01-12 12:24:31.418', E'2017-01-12 12:24:31.418', E'activo', NULL, NULL, 20, E'PL', E'Pail', E'Cantidad'),
  (NULL, NULL, E'2017-01-12 12:24:37.418', E'2017-01-12 12:24:37.418', E'activo', NULL, NULL, 21, E'GL', E'Gallon', E'Cantidad'),
  (NULL, NULL, E'2017-01-12 12:24:43.410', E'2017-01-12 12:24:43.410', E'activo', NULL, NULL, 22, E'RL', E'Roll', E'Cantidad'),
  (NULL, NULL, E'2017-01-12 12:24:49.185', E'2017-01-12 12:24:49.185', E'activo', NULL, NULL, 23, E'YDS', E'Square', E'Yards\tLongitud'),
  (NULL, NULL, E'2017-01-12 12:24:56.977', E'2017-01-12 12:24:56.977', E'activo', NULL, NULL, 24, E'SI', E'Square', E'Longitud'),
  (NULL, NULL, E'2017-01-12 12:25:00.713', E'2017-01-12 12:25:00.713', E'activo', NULL, NULL, 25, E'CJ', E'Cajas', E'Cantidad');

/***********************************F-DAT-MAM-MAT-0-12/01/2017****************************************/