/***********************************I-SCP-VAN-ADS-0-17/10/2019****************************************/
CREATE TABLE ads.tadendas (
  id_adenda SERIAL,
  id_obligacion_pago INTEGER NOT NULL,
  id_funcionario INTEGER,
  id_estado_wf INTEGER,
  id_proceso_wf INTEGER,
  estado VARCHAR(255),
  num_tramite VARCHAR(200),
  total_pago NUMERIC(19,2),
  nueva_fecha_fin DATE,
  observacion VARCHAR,
  numero VARCHAR,
  numero_adenda VARCHAR,
  id_contrato_adenda INTEGER,
  CONSTRAINT pk_tadenda_id_adenda PRIMARY KEY(id_adenda),
  CONSTRAINT fk_adendas__id_estado_wf FOREIGN KEY (id_estado_wf)
    REFERENCES wf.testado_wf(id_estado_wf)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_adendas__id_funcionario FOREIGN KEY (id_funcionario)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_adendas__id_obligacion_pago FOREIGN KEY (id_obligacion_pago)
    REFERENCES tes.tobligacion_pago(id_obligacion_pago)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_adendas__id_proceso_wf FOREIGN KEY (id_proceso_wf)
    REFERENCES wf.tproceso_wf(id_proceso_wf)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)
WITH (oids = false);

CREATE INDEX tadendas_idx1 ON ads.tadendas
  USING btree (id_estado_wf);

ALTER TABLE ads.tadendas
  OWNER TO postgres;
--SQL
CREATE TABLE ads.tadenda_det (
  id_adenda_det SERIAL,
  id_obligacion_det INTEGER,
  id_obligacion_pago INTEGER NOT NULL,
  id_concepto_ingas INTEGER NOT NULL,
  id_centro_costo INTEGER,
  id_partida INTEGER,
  id_partida_ejecucion_com INTEGER,
  descripcion TEXT,
  monto_pago_mo NUMERIC(19,2),
  monto_pago_mb NUMERIC(19,2),
  factor_porcentual NUMERIC,
  id_orden_trabajo INTEGER,
  monto_pago_sg_mo NUMERIC(19,2) DEFAULT 0,
  monto_pago_sg_mb NUMERIC(19,2) DEFAULT 0,
  id_adenda INTEGER,
  fecha_comp DATE,
  monto_comprometer NUMERIC DEFAULT 0,
  monto_descomprometer NUMERIC DEFAULT 0,
  id_partida_ejecucion_com_ad INTEGER,
  CONSTRAINT pk_tadenda_det__id_adenda_det PRIMARY KEY(id_adenda_det),
  CONSTRAINT fk_tadenda_det__id_centro_costo FOREIGN KEY (id_centro_costo)
    REFERENCES param.tcentro_costo(id_centro_costo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tadenda_det__id_concepto_ingas FOREIGN KEY (id_concepto_ingas)
    REFERENCES param.tconcepto_ingas(id_concepto_ingas)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tadenda_det__id_obligacion_pago FOREIGN KEY (id_obligacion_pago)
    REFERENCES tes.tobligacion_pago(id_obligacion_pago)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tadenda_det__id_orden_trabajo FOREIGN KEY (id_orden_trabajo)
    REFERENCES conta.torden_trabajo(id_orden_trabajo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tadenda_det__id_partida FOREIGN KEY (id_partida)
    REFERENCES pre.tpartida(id_partida)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fx_tadenda_det__id_adenda FOREIGN KEY (id_adenda)
    REFERENCES ads.tadendas(id_adenda)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)
WITH (oids = false);

COMMENT ON COLUMN ads.tadenda_det.id_orden_trabajo
IS 'orden de trabajo';

COMMENT ON COLUMN ads.tadenda_det.monto_pago_sg_mo
IS 'monto a pagar siguiente gestion en moneda original, este monto no comprometera presupuesto';

COMMENT ON COLUMN ads.tadenda_det.monto_pago_sg_mb
IS 'monto para la siguiente gestion en moenda base, este monto no comprometera presupuestos';

ALTER TABLE ads.tadenda_det
  OWNER TO postgres;

/***********************************F-SCP-VAN-ADS-0-17/10/2019****************************************/
