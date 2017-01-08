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
  unidad_medida VARCHAR(10),
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

/***********************************I-SCP-MAM-MAT-1-08/01/2017****************************************/
CREATE VIEW mat.vsolicitud (
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
    origen_pedido)
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
    sol.origen_pedido
FROM mat.tsolicitud sol
   JOIN mat.tdetalle_sol de ON de.id_solicitud = sol.id_solicitud
   JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo = sol.id_matricula
   JOIN orga.vfuncionario f ON f.id_funcionario = sol.id_funcionario_sol
   JOIN wf.testado_wf wof ON wof.id_estado_wf = sol.id_estado_wf
   JOIN wf.ttipo_estado ti ON ti.id_tipo_estado = wof.id_tipo_estado
GROUP BY sol.id_solicitud, sol.fecha_solicitud, ot.motivo_orden, ot.desc_orden,
    sol.nro_tramite, de.nro_parte, de.referencia, de.descripcion, de.cantidad_sol, de.unidad_medida, sol.justificacion, sol.tipo_solicitud, sol.fecha_requerida, sol.motivo_solicitud, sol.observaciones_sol, f.desc_funcionario1, sol.tipo_falla, sol.tipo_reporte, sol.mel, ti.codigo;
/***********************************F-SCP-MAM-MAT-1-08/01/2017****************************************/