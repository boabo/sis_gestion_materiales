/***********************************I-SCP-MAM-MAT-1-06/01/2017****************************************/
CREATE TABLE mat.tsolicitud (
  id_solicitud SERIAL,
  origen_pedido VARCHAR(100) NOT NULL,
  id_funcionario_sol INTEGER NOT NULL,
  nro_solicitud VARCHAR(50),
  nro_tramite VARCHAR(50),
  fecha_solicitud DATE,
  fecha_requerida DATE,
  observaciones_sol TEXT,
  tipo_solicitud VARCHAR(100),
  motivo_solicitud VARCHAR(1000),
  id_matricula INTEGER,
  justificacion VARCHAR(500),
  cotizacion NUMERIC(18,2),
  id_proveedor INTEGER,
  nro_po VARCHAR(50),
  fecha_entrega_miami DATE,
  fecha_despacho_miami DATE,
  fecha_arribado_bolivia DATE,
  fecha_desaduanizacion DATE,
  fecha_entrega_almacen DATE,
  observacion_nota TEXT,
  id_proceso_wf INTEGER NOT NULL,
  id_estado_wf INTEGER NOT NULL,
  estado VARCHAR(50),
  fecha_tentativa_llegada DATE,
  fecha_en_almacen DATE,
  tipo_falla VARCHAR(50),
  tipo_reporte VARCHAR(100),
  mel VARCHAR(50),
  nro_no_rutina VARCHAR(50),
  CONSTRAINT tsolicitud_pkey PRIMARY KEY(id_solicitud),
  CONSTRAINT tsolicitud_fk FOREIGN KEY (id_funcionario_sol)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tsolicitud_fk1 FOREIGN KEY (id_proveedor)
    REFERENCES param.tproveedor(id_proveedor)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tsolicitud_fk2 FOREIGN KEY (id_proceso_wf)
    REFERENCES wf.tproceso_wf(id_proceso_wf)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tsolicitud_fk3 FOREIGN KEY (id_estado_wf)
    REFERENCES wf.testado_wf(id_estado_wf)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tsolicitud_fk4 FOREIGN KEY (id_matricula)
    REFERENCES conta.torden_trabajo(id_orden_trabajo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE mat.tdetalle_sol (
  id_detalle SERIAL,
  id_solicitud INTEGER NOT NULL,
  nro_parte VARCHAR(100),
  nro_parte_alterno VARCHAR(100),
  referencia VARCHAR(100),
  descripcion VARCHAR(100),
  cantidad_sol NUMERIC(19,0),
  id_unidad_medida INTEGER,
  precio NUMERIC(18,2),
  id_moneda INTEGER,
  CONSTRAINT tdetalle_pkey PRIMARY KEY(id_detalle),
  CONSTRAINT tdetalle_sol_fk FOREIGN KEY (id_solicitud)
    REFERENCES mat.tsolicitud(id_solicitud)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);
/***********************************F-SCP-MAM-MAT-1-06/01/2017****************************************/

/***********************************I-SCP-MAM-MAT-3-08/01/2017****************************************/
CREATE OR REPLACE VIEW mat.vsolicitud (
    id_solicitud,
    fecha_solicitud,
    motivo_orden,
    matricula,
    nro_tramite,
    nro_parte,
    referencia,
    descripcion,
    cantidad_sol,
    unidad_medida,
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
    de.unidad_medida,
    sol.justificacion,
    sol.tipo_solicitud,
    to_char(sol.fecha_requerida::timestamp with time zone, 'DD/MM/YYYY'::text)
        AS fecha_requerida,
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
             					<td>'::text) || COALESCE(de.unidad_medida::text, '-'::text)) || '</td>'::character varying::text)::character varying)::text) || '</table>'::text AS detalle,
    sol.origen_pedido,
    sol.id_estado_wf,
    sol.id_proceso_wf
FROM mat.tsolicitud sol
   JOIN mat.tdetalle_sol de ON de.id_solicitud = sol.id_solicitud
   JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo = sol.id_matricula
   JOIN orga.vfuncionario f ON f.id_funcionario = sol.id_funcionario_sol
   JOIN wf.testado_wf wof ON wof.id_estado_wf = sol.id_estado_wf
   JOIN wf.ttipo_estado ti ON ti.id_tipo_estado = wof.id_tipo_estado
GROUP BY sol.id_solicitud, sol.fecha_solicitud, ot.motivo_orden, ot.desc_orden,
    sol.nro_tramite, de.nro_parte, de.referencia, de.descripcion, de.cantidad_sol, de.unidad_medida, sol.justificacion, sol.tipo_solicitud, sol.fecha_requerida, sol.motivo_solicitud, sol.observaciones_sol, f.desc_funcionario1, sol.tipo_falla, sol.tipo_reporte, sol.mel, ti.codigo;
/***********************************F-SCP-MAM-MAT-3-08/01/2017****************************************/

/***********************************I-SCP-MAM-MAT-0-09/01/2017****************************************/
CREATE OR REPLACE VIEW mat.vsolicitud (
    id_solicitud,
    fecha_solicitud,
    motivo_orden,
    matricula,
    nro_tramite,
    nro_parte,
    referencia,
    descripcion,
    cantidad_sol,
    unidad_medida,
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
    de.unidad_medida,
    sol.justificacion,
    sol.tipo_solicitud,
    to_char(sol.fecha_requerida::timestamp with time zone, 'DD/MM/YYYY'::text)
        AS fecha_requerida,
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
             					<td>'::text) || COALESCE(de.unidad_medida::text, '-'::text)) || '</td>'::character varying::text)::character varying)::text) || '</table>'::text AS detalle,
    sol.origen_pedido,
    sol.id_estado_wf,
    sol.id_proceso_wf
FROM mat.tsolicitud sol
   JOIN mat.tdetalle_sol de ON de.id_solicitud = sol.id_solicitud
   LEFT JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo = sol.id_matricula
   JOIN orga.vfuncionario f ON f.id_funcionario = sol.id_funcionario_sol
   JOIN wf.testado_wf wof ON wof.id_estado_wf = sol.id_estado_wf
   JOIN wf.ttipo_estado ti ON ti.id_tipo_estado = wof.id_tipo_estado
GROUP BY sol.id_solicitud, sol.fecha_solicitud, ot.motivo_orden, ot.desc_orden,
    sol.nro_tramite, de.nro_parte, de.referencia, de.descripcion, de.cantidad_sol, de.unidad_medida, sol.justificacion, sol.tipo_solicitud, sol.fecha_requerida, sol.motivo_solicitud, sol.observaciones_sol, f.desc_funcionario1, sol.tipo_falla, sol.tipo_reporte, sol.mel, ti.codigo;
/***********************************F-SCP-MAM-MAT-0-09/01/2017****************************************/

/***********************************I-SCP-MAM-MAT-1-09/01/2017****************************************/
CREATE OR REPLACE VIEW mat.vsolicitud (
    id_solicitud,
    fecha_solicitud,
    motivo_orden,
    matricula,
    nro_tramite,
    nro_parte,
    referencia,
    descripcion,
    cantidad_sol,
    unidad_medida,
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
    de.unidad_medida,
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
             					<td>'::text) || COALESCE(de.unidad_medida::text, '-'::text)) || '</td>'::character varying::text)::character varying)::text) || '</table>'::text AS detalle,
    sol.origen_pedido,
    sol.id_estado_wf,
    sol.id_proceso_wf
FROM mat.tsolicitud sol
     JOIN mat.tdetalle_sol de ON de.id_solicitud = sol.id_solicitud
     LEFT JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo = sol.id_matricula
     JOIN orga.vfuncionario f ON f.id_funcionario = sol.id_funcionario_sol
     JOIN wf.testado_wf wof ON wof.id_estado_wf = sol.id_estado_wf
     JOIN wf.ttipo_estado ti ON ti.id_tipo_estado = wof.id_tipo_estado
GROUP BY sol.id_solicitud, sol.fecha_solicitud, ot.motivo_orden, ot.desc_orden,
    sol.nro_tramite, de.nro_parte, de.referencia, de.descripcion, de.cantidad_sol, de.unidad_medida, sol.justificacion, sol.tipo_solicitud, sol.fecha_requerida, sol.motivo_solicitud, sol.observaciones_sol, f.desc_funcionario1, sol.tipo_falla, sol.tipo_reporte, sol.mel, ti.codigo;

/***********************************F-SCP-MAM-MAT-1-09/01/2017****************************************/

/***********************************I-SCP-MAM-MAT-1-12/01/2017****************************************/
CREATE TABLE mat.tunidad_medida (
  id_unidad_medida INTEGER DEFAULT nextval('mat.tunidad_medida_id_unidad_medida_seq'::regclass) NOT NULL,
  codigo VARCHAR(100),
  descripcion VARCHAR(100),
  tipo_unidad_medida VARCHAR(100),
  CONSTRAINT tunidad_medida_pkey PRIMARY KEY(id_unidad_medida)
) INHERITS (pxp.tbase)

WITH (oids = false);

/***********************************F-SCP-MAM-MAT-0-12/01/2017****************************************/

/***********************************I-SCP-FEA-MAT-1-7/11/2018****************************************/

CREATE TABLE mat.tcotizacion (
  id_cotizacion SERIAL,
  monto_total NUMERIC(20,2),
  fecha_cotizacion DATE,
  nro_tramite VARCHAR(255),
  adjudicado VARCHAR(20) DEFAULT 'no'::character varying,
  id_proveedor INTEGER,
  id_solicitud INTEGER,
  id_moneda INTEGER,
  nro_cotizacion VARCHAR(255),
  recomendacion VARCHAR(1000),
  obs VARCHAR(1000),
  pie_pag VARCHAR(500),
  CONSTRAINT tcotizacion_pkey PRIMARY KEY(id_cotizacion),
  CONSTRAINT tcotizacion_fk1 FOREIGN KEY (id_moneda)
    REFERENCES param.tmoneda(id_moneda)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE mat.tcotizacion
  ALTER COLUMN id_cotizacion SET STATISTICS 0;

ALTER TABLE mat.tcotizacion
  ALTER COLUMN monto_total SET STATISTICS 0;

ALTER TABLE mat.tcotizacion
  ALTER COLUMN id_proveedor SET STATISTICS 0;

ALTER TABLE mat.tcotizacion
  ALTER COLUMN id_solicitud SET STATISTICS 0;

ALTER TABLE mat.tcotizacion
  ALTER COLUMN id_moneda SET STATISTICS 0;


CREATE TABLE mat.tcotizacion_detalle (
  id_cotizacion_det SERIAL,
  id_cotizacion INTEGER,
  id_detalle INTEGER,
  precio_unitario NUMERIC(19,2),
  precio_unitario_mb NUMERIC(19,2),
  cantidad_det INTEGER,
  id_solicitud INTEGER,
  cd VARCHAR(200),
  id_day_week INTEGER,
  nro_parte_cot VARCHAR(1000),
  nro_parte_alterno_cot VARCHAR(1000),
  referencia_cot VARCHAR(1000),
  descripcion_cot VARCHAR(1000),
  explicacion_detallada_part_cot VARCHAR(1000),
  tipo_cot VARCHAR(100),
  id_unidad_medida_cot INTEGER,
  revisado VARCHAR(2) DEFAULT 'no'::character varying,
  CONSTRAINT tcotizacion_det_pkey PRIMARY KEY(id_cotizacion_det),
  CONSTRAINT tcotizacion_det_fk FOREIGN KEY (id_cotizacion)
    REFERENCES mat.tcotizacion(id_cotizacion)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE mat.tcotizacion_detalle
  ALTER COLUMN id_cotizacion_det SET STATISTICS 0;

ALTER TABLE mat.tcotizacion_detalle
  ALTER COLUMN id_cotizacion SET STATISTICS 0;

ALTER TABLE mat.tcotizacion_detalle
  ALTER COLUMN id_detalle SET STATISTICS 0;

ALTER TABLE mat.tcotizacion_detalle
  ALTER COLUMN precio_unitario SET STATISTICS 0;

ALTER TABLE mat.tcotizacion_detalle
  ALTER COLUMN cantidad_det SET STATISTICS 0;

CREATE TABLE mat.tday_week (
  id_day_week SERIAL,
  codigo_tipo VARCHAR(250) NOT NULL,
  CONSTRAINT tday_week_pkey PRIMARY KEY(id_day_week)
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE mat.tday_week
  ALTER COLUMN id_day_week SET STATISTICS 0;

CREATE TABLE mat.tgestion_proveedores (
  id_gestion_proveedores SERIAL,
  id_solicitud INTEGER,
  cotizacion_solicitadas INTEGER[][] [],
  cotizacion_recibidas INTEGER[][] [],
  adjudicado VARCHAR(100),
  fecha_cotizacion DATE,
  monto NUMERIC(20,2),
  CONSTRAINT tgestion_proveedores_pkey PRIMARY KEY(id_gestion_proveedores),
  CONSTRAINT tgestion_proveedores_fk FOREIGN KEY (id_solicitud)
    REFERENCES mat.tsolicitud(id_solicitud)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE mat.tgestion_proveedores
  ALTER COLUMN id_gestion_proveedores SET STATISTICS 0;

ALTER TABLE mat.tgestion_proveedores
  ALTER COLUMN cotizacion_solicitadas SET STATISTICS 0;

CREATE TABLE mat.tgestion_proveedores_new (
  id_gestion_proveedores SERIAL,
  id_solicitud INTEGER,
  id_proveedor INTEGER,
  CONSTRAINT tgestion_proveedores_new_pkey PRIMARY KEY(id_gestion_proveedores)
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE mat.tgestion_proveedores_new
  ALTER COLUMN id_gestion_proveedores SET STATISTICS 0;

CREATE TABLE mat.tsolicitud_pac (
  id_solicitud_pac SERIAL,
  id_proceso_wf INTEGER,
  monto NUMERIC(18,2),
  id_moneda INTEGER,
  estado VARCHAR(50) DEFAULT 'pendiente'::character varying,
  tipo VARCHAR(15),
  CONSTRAINT tsolicitud_pac_pkey PRIMARY KEY(id_solicitud_pac)
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE mat.tsolicitud_pac
  ALTER COLUMN id_solicitud_pac SET STATISTICS 0;

ALTER TABLE mat.tsolicitud_pac
  ALTER COLUMN id_proceso_wf SET STATISTICS 0;

ALTER TABLE mat.tsolicitud_pac
  ALTER COLUMN monto SET STATISTICS 0;

ALTER TABLE mat.tsolicitud_pac
  ALTER COLUMN id_moneda SET STATISTICS 0;

/***********************************F-SCP-FEA-MAT-0-7/11/2018****************************************/

/***********************************I-SCP-MAY-MAT-0-18/02/2019****************************************/
ALTER TABLE mat.tdetalle_sol
  ALTER COLUMN nro_parte_alterno TYPE VARCHAR(250) COLLATE pg_catalog."default";
/***********************************F-SCP-MAY-MAT-0-18/02/2019****************************************/

/***********************************I-SCP-MAY-MAT-0-24/05/2019****************************************/
ALTER TABLE mat.tsolicitud_pac
  ADD COLUMN observaciones VARCHAR(1000) DEFAULT 'El monto solicitado para el presente requerimiento, fue establecido por la unidad solicitante considerando criterios de economía y condiciones del mercado actual.'::character varying NOT NULL;
/***********************************F-SCP-MAY-MAT-0-24/05/2019****************************************/

/***********************************I-SCP-IRVA-MAT-0-27/02/2020****************************************/
ALTER TABLE mat.tunidad_medida
  ADD COLUMN id_alkym INTEGER;
/***********************************F-SCP-IRVA-MAT-0-27/02/2020****************************************/
