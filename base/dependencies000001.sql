/********************************************I-DEP-VAN-ADENDAS-1-27/02/2019*************************************/
CREATE TRIGGER trig_adenda_det_total_pago
  AFTER UPDATE
  ON ads.tadenda_det sFOR EACH ROW
EXECUTE PROCEDURE ads.f_update_adenda_total_pago();
/********************************************F-DEP-VAN-ADENDAS-1-27/02/2019*************************************/


