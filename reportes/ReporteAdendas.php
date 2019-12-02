<?php
/*
*
*   ISSUE             FECHA:		      AUTOR           DESCRIPCION
*    0, ETR          19/10/2017			   RAC		  Para reporte de solicitud se agrega la posibilidad de verificacion por tipo de centro de costos
*
*/

require_once dirname(__FILE__) . '/../../pxp/lib/lib_reporte/ReportePDF.php';
require_once dirname(__FILE__) . '/../../pxp/pxpReport/Report.php';

Class ReporteAdendas extends ReportePDF implements Estrategia
{
    var $objParam;
    var $width;
    var $dataSource;

    function Header()
    {
        $this->Ln(3);

        $this->Image(dirname(__FILE__) . '/../../lib/imagenes/logos/logo.jpg', 16, 5, 40, 20);
        $this->ln(5);
        $this->SetFont('', 'B', 12);
        $this->Cell(0, 5, "Reporte Adenda", 0, 1, 'C');
        $this->Ln(2);

    }

    public function setDataSource(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    function generarReporte()
    {
        $html = '<hr><br>';
        $adenda = $this->dataSource->getParameter('adenda');
        $adendaDet = $this->dataSource->getParameter('adendaDet');
        $presupuestoDet = $this->dataSource->getParameter('presupuestoDet');
        $adenda = $adenda[0];

        $this->AddPage();

        $this->width = $this->getPageWidth($this->getPage());
        $this->SetFontSize(8.5);
        $this->SetFont('', 'B');
        $this->setTextColor(0, 0, 0);

        $html .= $this->agregar_datos_adenda($adenda);
        $html .= '<br>';
        $html .= $this->agregar_datos_proceso($adenda);
        $html .= '<br>';
        $html .= $this->agregar_datos_detalle($adendaDet);
        $html .= '<br>';
        $html .= $this->agregar_datos_presupuestos($presupuestoDet);

        $this->writeHTML($html, true, false, true, false, '');
    }

    function agregar_datos_adenda($adenda)
    {
        $fecha_modificacion = DateTime::createFromFormat('Y-m-d', $adenda[0]['fecha_mod']);
        $style = 'background-color: #003366;color:#FFFFFF;style="font-weight: bold;"';
        $tbl = '<table border="0" width="100%" cellpadding="2" cellspacing="0"  style="font-family: Monospaced;font-size: 9pt;">';
        $tbl .= '<thead>';
        $tbl .= '<tr>';
        $tbl .= '<th style="' . $style . '" width="25%">Tr&aacute;mite</th>';
        $tbl .= '<th style="' . $style . '" width="25%">Estado</th>';
        $tbl .= '<th style="' . $style . '" width="25%">Ultima Modificaci&oacute;n</th>';
        $tbl .= '<th style="' . $style . '" width="25%">Proceso</th>';
        $tbl .= '</tr>';
        $tbl .= '</thead>';
        $tbl .= '<tbody>';
        $tbl .= '<tr>';
        $tbl .= '<td width="25%">' . $adenda['num_tramite'] . '</td>';
        $tbl .= '<td width="25%">' . $adenda['estado'] . '</td>';
        $tbl .= '<td width="25%">' . $adenda['fecha_mod'] . '</td>';
        $tbl .= '<td width="25%">' . $adenda['descripcion'] . '</td>';
        $tbl .= '</tr>';
        $tbl .= '</tbody>';
        $tbl .= ' </table> ';
        return $tbl;
    }

    function agregar_datos_proceso($proceso)
    {
        $fecha_entrega = DateTime::createFromFormat('Y-m-d', $proceso['fecha_entrega']);
        $tbl = '<h3><u>Adenda</u></h3>';
        $tbl .= '<table border="0.5px" width="100%" cellpadding="2" cellspacing="0" style="font-family: Monospaced;font-size: 9pt">';
        $tbl .= '<tbody>';
        $tbl .= '<tr>';
        $tbl .= '<td width="15%" style="background-color: #003366;color:#FFFFFF;font-weight: bold;" >N&uacute;mero</td>';
        $tbl .= '<td width="35%" style="background-color: #E1E8F0;">' . $proceso['numero'] . '</td>';
        $tbl .= '<td width="15%" style="background-color: #003366;color:#FFFFFF;font-weight: bold;">Estdo</td>';
        $tbl .= '<td width="35%" style="background-color: #E1E8F0;" >' . $proceso['estado_reg'] . '</td>';
        $tbl .= '</tr>';
        $tbl .= '<tr>';
        $tbl .= '<td width="15%" style="background-color: #003366;color:#FFFFFF;font-weight: bold;" >N&uacute;mero Contrato</td>';
        $tbl .= '<td width="35%" style="background-color: #E1E8F0;">' . $proceso['numero_contratp'] . '</td>';
        $tbl .= '<td width="15%" style="background-color: #003366;color:#FFFFFF;font-weight: bold;">Fecha Entrega</td>';
        $tbl .= '<td width="35%" style="background-color: #E1E8F0;" >' . $proceso['fecha_entrega'] . '</td>';
        $tbl .= '</tr>';
        $tbl .= '<tr>';
        $tbl .= '<td width="15%" style="background-color: #003366;color:#FFFFFF;font-weight: bold;" >Funcionario</td>';
        $tbl .= '<td width="35%" style="background-color: #E1E8F0;">' . $proceso['desc_funcionario1'] . '</td>';
        $tbl .= '<td width="15%" style="background-color: #003366;color:#FFFFFF;font-weight: bold;">Departamento</td>';
        $tbl .= '<td width="35%" style="background-color: #E1E8F0;" >' . $proceso['nombre_depto'] . '</td>';
        $tbl .= '</tr>';
        $tbl .= '</tbody>';
        $tbl .= ' </table> ';
        return $tbl;

    }

    function agregar_datos_detalle($detalles)
    {
        $styleHeader = 'style="background-color: #003366;color:#FFFFFF;font-weight: bold;"';
        $tbl = '<h3><u>Detalle Adenda</u></h3>';
        $tbl .= '<table border="0.5pt"  width="100%"  cellpadding="2" cellspacing="0"  style="font-family: Monospaced;font-size: 9pt;">';
        $tbl .= '<thead>';
        $tbl .= '<tr><th colspan="6" align="right"><b>N:</b> Nuevo <b>C:</b> Comprometer <b>D:</b> Descomprometer</th></tr>';
        $tbl .= '<tr>';
        $tbl .= '<th ' . $styleHeader . ' width="25%">Centro de Costos</th>';
        $tbl .= '<th ' . $styleHeader . ' width="25%">Partida</th>';
        $tbl .= '<th ' . $styleHeader . ' width="12.5%">Monto Anterior</th>';
        $tbl .= '<th ' . $styleHeader . ' width="12.5%">Nuevo Monto</th>';
        $tbl .= '<th ' . $styleHeader . ' width="12.5%">Monto Operaci&oacute;n</th>';
        $tbl .= '<th ' . $styleHeader . ' width="12.5%">Estado</th>';
        $tbl .= '</tr>';
        $tbl .= '</thead>';
        $tbl .= '<tbody>';
        $i = 1;
        foreach ($detalles as $detalle) {
            $style = '';
            if ($i % 2 == 0) {
                $style = 'background-color: #E1E8F0;';
            }
            $tbl .= '<tr>';
            $tbl .= '<td width="25%" style="' . $style . '">' . $detalle['centro_costos'] . '</td>';
            $tbl .= '<td width="25%" style="' . $style . '">' . $detalle['nombre_partida'] . '</td>';
            $tbl .= '<td width="12.5%" style="' . $style . '" align="right">' . $detalle['monto_anterior'] . '</td>';
            $tbl .= '<td width="12.5%" style="' . $style . '" align="right">' . $detalle['nuevo_monto'] . '</td>';
            $tbl .= '<td width="12.5%" style="' . $style . '" align="right">' . $detalle['monto_operacion'] . '</td>';
            $tbl .= '<td width="12.5%" style="' . $style . '" align="center"><b>' . strtoupper(substr($detalle['estado'], 0, 1)) . '</b></td>';
            $tbl .= '</tr>';
            $i++;
        }
        $tbl .= '</tbody>';
        $tbl .= ' </table> ';
        return $tbl;
    }

    function agregar_datos_presupuestos($presupuestos)
    {
        $styleHeader = 'style="background-color: #003366;color:#FFFFFF;font-weight: bold;"';
        $tbl = '<h3><u>Disponibilidad Presupuestaria</u></h3>';
        $tbl .= '<table border="0.5pt"  width="100%" cellpadding="2" cellspacing="0"  style="font-family: Monospaced;font-size: 9pt;">';
        $tbl .= '<thead>';
        $tbl .= '<tr>';
        $tbl .= '<th ' . $styleHeader . ' width="50%">Centro de Costos</th>';
        $tbl .= '<th ' . $styleHeader . ' width="25%">Monto A Compromenter</th>';
        $tbl .= '<th ' . $styleHeader . ' width="25%">Disponibilidad</th>';
        $tbl .= '</tr>';
        $tbl .= '</thead>';
        $tbl .= '<tbody>';
        $i = 1;
        foreach ($presupuestos as $presupuesto) {
            $style = '';
            if ($i % 2 == 0) {
                $style = 'background-color: #E1E8F0;';
            }
            $tbl .= '<tr>';
            $tbl .= '<td width="50%" style="' . $style . '">' . $presupuesto['techo'] . '</td>';
            $tbl .= '<td width="25%" style="' . $style . '" align="right">' . $presupuesto['monto_operacion'] . '</td>';
            $disponibilidad = '';
            if ($presupuesto['disponible'] == 'true') {
                $style .= 'color:rgb(6,129,51);font-weight: bold;';
                $disponibilidad = "<b>SI</b>";
            } else {
                $disponibilidad = "<b>NO</b>";
                $style .= 'color:rgb(255,0,0);font-weight: bold;';
            }

            $tbl .= '<td width="25%" style="' . $style . '" align="center">' . $disponibilidad . '</td>';
            $tbl .= '</tr>';
            $i++;
        }
        $tbl .= '</tbody>';
        $tbl .= ' </table> ';
        return $tbl;
    }

    function transpose($arr)
    {

        $out = array();
        foreach ($arr as $key => $subarr) {
            foreach ($subarr as $subkey => $subvalue) {
                $out[$subkey][$key] = $subvalue;
            }
        }
        return $out;

    }
}

?>