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