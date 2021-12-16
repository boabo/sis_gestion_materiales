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


/***********************************I-SCP-IRVA-MAT-0-11/12/2021****************************************/
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
    mensaje_correo,
    mensaje_tiempo_entrega,
    leyenda)
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
            WHEN substr(sol.nro_tramite::text, 1, 2) = 'GR'::text THEN
                'GR - '::text || ltrim(substr(sol.nro_tramite::text, 7, 6), '0'::text)
            ELSE 'SIN GERENCIA'::text
        END AS nro_cotizacion,
    mat.f_recuperar_correos((
    SELECT pxp.aggarray(n.id_proveedor) AS aggarray
    FROM mat.tgestion_proveedores_new n
    WHERE n.id_solicitud = sol.id_solicitud
    GROUP BY n.id_solicitud
    )) AS f_recuperar_correos,
    tgp.cotizacion_solicitadas,
    sol.mensaje_correo,
        CASE
            WHEN COALESCE(sol.tiempo_entrega, 0::numeric) > 0::numeric THEN
                ('"Plazo de entrega de propuesta hasta '::text || sol.tiempo_entrega) || ' día(s) después de la invitación."'::text
            ELSE ''::text
        END AS mensaje_tiempo_entrega,
    (('Método de adjudicación: '::text || sol."metodo_de_adjudicación"::text)
        || ' Tipo de Adjudicación: '::text) || sol.tipo_de_adjudicacion::text AS leyenda
FROM mat.tsolicitud sol
     LEFT JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo = sol.id_matricula
     JOIN orga.vfuncionario f ON f.id_funcionario = sol.id_funcionario_sol
     JOIN wf.testado_wf wof ON wof.id_estado_wf = sol.id_estado_wf
     JOIN wf.ttipo_estado ti ON ti.id_tipo_estado = wof.id_tipo_estado
     LEFT JOIN mat.tgestion_proveedores tgp ON tgp.id_solicitud = sol.id_solicitud;

ALTER VIEW mat.vsolicitud
  OWNER TO postgres;
/***********************************F-SCP-IRVA-MAT-0-11/12/2021****************************************/

/***********************************I-SCP-IRVA-MAT-1-11/12/2021****************************************/
CREATE TYPE mat.detalle_solicitud_mantenimiento AS (
  id_solicitud INTEGER,
  descripcion VARCHAR(1000),
  id_unidad_medida INTEGER,
  nro_parte VARCHAR(100),
  referencia VARCHAR(100),
  nro_parte_alterno VARCHAR(250),
  cantidad_sol NUMERIC(19,0),
  tipo VARCHAR(100),
  explicacion_detallada_part VARCHAR(500)
);

ALTER TYPE mat.detalle_solicitud_mantenimiento
  OWNER TO postgres;
/***********************************F-SCP-IRVA-MAT-1-11/12/2021****************************************/
/***********************************I-SCP-IRVA-MAT-2-11/12/2021****************************************/
ALTER TABLE mat.tcotizacion_detalle
  ADD COLUMN referencial VARCHAR(5);

ALTER TABLE mat.tcotizacion_detalle
  ALTER COLUMN referencial SET DEFAULT 'No';

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN condicion_det VARCHAR(100);

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN id_centro_costo INTEGER;

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN id_concepto_ingas INTEGER;

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN id_orden_trabajo INTEGER;

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN id_partida INTEGER;

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN id_auxiliar INTEGER;

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN id_cuenta INTEGER;

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN precio_total NUMERIC(18,2);

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN id_partida_ejecucion INTEGER;

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN precio_unitario NUMERIC(18,2);

ALTER TABLE mat.tdetalle_sol
  ADD COLUMN id_producto_alkym INTEGER;

COMMENT ON COLUMN mat.tdetalle_sol.id_producto_alkym
  IS 'campo donde se alamacena el id_producto_alkym';



ALTER TABLE mat.tsolicitud
  ADD COLUMN id_depto INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN codigo_poa VARCHAR(24);

ALTER TABLE mat.tsolicitud
  ADD COLUMN tipo_cambio NUMERIC(7,2);

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_moneda INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_funcionario_solicitante INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN presu_comprometido VARCHAR(5);

ALTER TABLE mat.tsolicitud
  ADD COLUMN presupuesto_aprobado VARCHAR(20);

ALTER TABLE mat.tsolicitud
  ADD COLUMN dias_estado INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN revisado_presupuesto VARCHAR(2);

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_po_alkym INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN nro_lote INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_condicion_entrega_alkym INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_forma_pago_alkym INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_modo_envio_alkym INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_puntos_entrega_alkym INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_tipo_transaccion_alkym INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN id_orden_destino_alkym INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN codigo_condicion_entrega_alkym VARCHAR(200);

ALTER TABLE mat.tsolicitud
  ADD COLUMN codigo_forma_pago_alkym VARCHAR(200);

ALTER TABLE mat.tsolicitud
  ADD COLUMN codigo_modo_envio_alkym VARCHAR(200);

ALTER TABLE mat.tsolicitud
  ADD COLUMN codigo_puntos_entrega_alkym VARCHAR(200);

ALTER TABLE mat.tsolicitud
  ADD COLUMN codigo_tipo_transaccion_alkym VARCHAR(200);

ALTER TABLE mat.tsolicitud
  ADD COLUMN codigo_orden_destino_alkym VARCHAR(200);

ALTER TABLE mat.tsolicitud
  ADD COLUMN direccion_punto_entrega_alkym VARCHAR(200);

ALTER TABLE mat.tsolicitud
  ADD COLUMN fecha_entrega DATE;

ALTER TABLE mat.tsolicitud
  ADD COLUMN mel_observacion TEXT;

ALTER TABLE mat.tsolicitud
  ADD COLUMN origen_solicitud TEXT;

ALTER TABLE mat.tsolicitud
  ADD COLUMN tiempo_entrega INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN tiempo_entrega_estimado INTEGER;

ALTER TABLE mat.tsolicitud
  ADD COLUMN metodo_de_adjudicación VARCHAR(100);

COMMENT ON COLUMN mat.tsolicitud.metodo_de_adjudicación
IS 'Campo para almacenar el metodo de adjudicacion y mostrar en la leyenda del correo';


ALTER TABLE mat.tsolicitud
  ADD COLUMN tipo_de_adjudicacion VARCHAR(100);

COMMENT ON COLUMN mat.tsolicitud.tipo_de_adjudicacion
IS 'Campo para almacenar el tipo de adjudicacion para mostrar en la leyenda';

ALTER TABLE mat.tcotizacion
  ADD COLUMN id_proveedor_contacto INTEGER;

/***********************************F-SCP-IRVA-MAT-2-11/12/2021****************************************/
/***********************************I-SCP-IRVA-MAT-3-11/12/2021****************************************/
CREATE TABLE mat.tacta_conformidad_final (
  fecha_conformidad DATE,
  conformidad_final TEXT,
  fecha_inicio DATE,
  fecha_final DATE,
  observaciones TEXT,
  id_solicitud INTEGER,
  id_funcionario_firma INTEGER,
  CONSTRAINT tacta_conformidad_final_id_funcionario_fk FOREIGN KEY (id_funcionario_firma)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tacta_conformidad_final_id_solicitud_fk FOREIGN KEY (id_solicitud)
    REFERENCES mat.tsolicitud(id_solicitud)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)
WITH (oids = false);

COMMENT ON COLUMN mat.tacta_conformidad_final.fecha_conformidad
IS 'Fecha de la conformidad';

COMMENT ON COLUMN mat.tacta_conformidad_final.conformidad_final
IS 'Descripcion donde se detallara la conformidad';

COMMENT ON COLUMN mat.tacta_conformidad_final.fecha_inicio
IS 'fecha inical si requiere';

COMMENT ON COLUMN mat.tacta_conformidad_final.fecha_final
IS 'fecha final si requiere';

COMMENT ON COLUMN mat.tacta_conformidad_final.observaciones
IS 'Observaciones si requiere detallar';

COMMENT ON COLUMN mat.tacta_conformidad_final.id_solicitud
IS 'Hace la relacion con el id_solicitud de la tabla mat.tsolicitud';

ALTER TABLE mat.tacta_conformidad_final
  OWNER TO postgres;
/***********************************F-SCP-IRVA-MAT-3-11/12/2021****************************************/

/***********************************I-SCP-IRVA-MAT-4-11/12/2021****************************************/
CREATE TABLE mat.tasginacion_automatica_abastecimiento (
  id_asignacion SERIAL,
  id_solicitud INTEGER,
  nro_tramite VARCHAR(500),
  id_funcionario_asignado INTEGER,
  id_tipo_estado INTEGER,
  id_funcionario_rpc INTEGER,
  ultima_asignacion VARCHAR(10),
  CONSTRAINT tasginacion_automatica_abastecimiento_pkey PRIMARY KEY(id_asignacion)
) INHERITS (pxp.tbase)
WITH (oids = false);

COMMENT ON TABLE mat.tasginacion_automatica_abastecimiento
IS 'Tabla donde Almacenara los funcionarios que han sido asignados automaticamente para repartir los tramites.';

COMMENT ON COLUMN mat.tasginacion_automatica_abastecimiento.id_solicitud
IS 'Hace la relacion con la solicitud de la tabla mat.tsolicitud';

COMMENT ON COLUMN mat.tasginacion_automatica_abastecimiento.id_tipo_estado
IS 'Hace referencia a la tabla wf.ttipo_estado';

COMMENT ON COLUMN mat.tasginacion_automatica_abastecimiento.id_funcionario_rpc
IS 'Para saber que es lo que el encargado de Rpc ha autorizado y sacar en los reportes';

COMMENT ON COLUMN mat.tasginacion_automatica_abastecimiento.ultima_asignacion
IS 'La bandera para tener el ultimo funcionario Asignado y derivar al siguiente';

ALTER TABLE mat.tasginacion_automatica_abastecimiento
  OWNER TO postgres;
/***********************************F-SCP-IRVA-MAT-4-11/12/2021****************************************/

/***********************************I-SCP-IRVA-MAT-5-11/12/2021****************************************/
ALTER TABLE mat.tsolicitud
  ALTER COLUMN condicion TYPE VARCHAR(600) COLLATE pg_catalog."default";
/***********************************F-SCP-IRVA-MAT-5-11/12/2021****************************************/

/***********************************I-SCP-IRVA-MAT-0-12/12/2021****************************************/
ALTER TABLE mat.tsolicitud
  ALTER COLUMN revisado_presupuesto SET DEFAULT 'no';
/***********************************F-SCP-IRVA-MAT-0-12/12/2021****************************************/

/***********************************I-SCP-IRVA-MAT-1-12/12/2021****************************************/
  ALTER TABLE mat.tdetalle_sol
  ADD COLUMN revertido_mb NUMERIC(18,2);

  ALTER TABLE mat.tdetalle_sol
  ADD COLUMN revertido_mo NUMERIC(18,2);
/***********************************F-SCP-IRVA-MAT-1-12/12/2021****************************************/

/***********************************I-SCP-IRVA-MAT-0-13/12/2021****************************************/
CREATE OR REPLACE VIEW mat.vsolicitud (
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
  mensaje_correo,
  mensaje_tiempo_entrega,
  nombre_proveedores)
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
          WHEN substr(sol.nro_tramite::text, 1, 2) = 'GR'::text THEN
              'GR - '::text || ltrim(substr(sol.nro_tramite::text, 7, 6), '0'::text)
          ELSE 'SIN GERENCIA'::text
      END AS nro_cotizacion,
  mat.f_recuperar_correos((
  SELECT pxp.aggarray(n.id_proveedor) AS aggarray
  FROM mat.tgestion_proveedores_new n
  WHERE n.id_solicitud = sol.id_solicitud
  GROUP BY n.id_solicitud
  )) AS f_recuperar_correos,
  tgp.cotizacion_solicitadas,
  sol.mensaje_correo,
      CASE
          WHEN COALESCE(sol.tiempo_entrega, 0::numeric) > 0::numeric THEN
              ('"Plazo de entrega de propuesta hasta '::text || sol.tiempo_entrega) || ' día(s) después de la invitación."'::text
          ELSE ''::text
      END AS mensaje_tiempo_entrega,
  ((
  SELECT list(pro.rotulo_comercial::text) AS list
  FROM mat.tgestion_proveedores_new ge
           JOIN param.vproveedor pro ON pro.id_proveedor = ge.id_proveedor
  WHERE ge.id_solicitud = sol.id_solicitud
  ))::character varying AS nombre_proveedores
FROM mat.tsolicitud sol
   LEFT JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo = sol.id_matricula
   JOIN orga.vfuncionario f ON f.id_funcionario = sol.id_funcionario_sol
   JOIN wf.testado_wf wof ON wof.id_estado_wf = sol.id_estado_wf
   JOIN wf.ttipo_estado ti ON ti.id_tipo_estado = wof.id_tipo_estado
   LEFT JOIN mat.tgestion_proveedores tgp ON tgp.id_solicitud = sol.id_solicitud;

ALTER VIEW mat.vsolicitud
OWNER TO postgres;
/***********************************F-SCP-IRVA-MAT-0-13/12/2021****************************************/

/***********************************I-SCP-IRVA-MAT-0-15/12/2021****************************************/
ALTER TABLE mat.tday_week
  ADD COLUMN cantidad_dias INTEGER;
/***********************************F-SCP-IRVA-MAT-0-15/12/2021****************************************/

/***********************************I-SCP-IRVA-MAT-0-16/12/2021****************************************/
ALTER TABLE mat.tsolicitud
  ADD COLUMN remark TEXT;

COMMENT ON COLUMN mat.tsolicitud.remark
IS 'Campo para editar por patricia';
/***********************************F-SCP-IRVA-MAT-0-16/12/2021****************************************/
