<?php
/*
*
*   ISSUE             FECHA:		      AUTOR           DESCRIPCION
*
*/

require_once dirname(__FILE__) . '/../../pxp/lib/lib_reporte/ReportePDF.php';
require_once dirname(__FILE__) . '/../../pxp/pxpReport/Report.php';

Class ReporteOrden extends ReportePDF implements Estrategia
{
    var $objParam;
    var $width;
    var $dataSource;

    function Header()
    {
        $adenda = $this->getDataSource()->getParameter('adenda');
        $header = '<table width="100%" cellpadding="3">';
        $header .= '<tr>';
        $header .= '<td width="25%" >';
        $header .= '<img src="' . dirname(__FILE__) . '/../../lib/imagenes/logos/logo.jpg' . '" />';
        $header .= '</td>';
        $header .= '<td width="40%" align="center">';
        $header .= '<h2>Reporte Orden </h2>';
        $header .= '</td>';
        $header .= '<td width="35%">';
        $header .= $this->datosCabecera($adenda);
        $header .= '</td>';
        $header .= '</tr>';
        $header .= '</table>';
        $this->writeHTML($header, true, false, true, false, '');
    }

    private function datosCabecera($adenda)
    {
        $fecha_modificacion = DateTime::createFromFormat("Y-m-d", $adenda[0]['fecha_mod']);

        $content = '<table>';
        $content .= '<tr>';
        $content .= '<td align="right">';
        $content .= $adenda[0]['num_tramite'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td align="right">';
        $content .= $adenda[0]['numero'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        $content .= '<table border="1">';
        $content .= '<tr>';
        $content .= '<th align="center"><b>Dia</b></th>';
        $content .= '<th align="center"><b>Mes</b></th>';
        $content .= '<th align="center"><b>A&ntilde;o</b></th>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td align="center">';
//        $content .= $fecha_modificacion->format('d');
        $content .= '</td>';
        $content .= '<td align="center">';
//        $content .= $fecha_modificacion->format('m');
        $content .= '</td>';
        $content .= '<td align="center">';
//        $content .= $fecha_modificacion->format('Y');
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return $content;
    }

    function Footer()
    {
        $this->setY(-45);
        parent::Footer();
    }

    function notaFinal()
    {
        $content = '<table width="100%" cellpadding="3" border="0" style="font-size: 7pt">';
        $content .= '<tr>';
        $content .= '<td align="center">';
        $content .= 'CONOCIMIENTO SOBRE LA RESPONSABILIDAD';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td style="text-align: justify">';
        $content .= 'Consiente de la importancia de incorporar normativas y procedimientos que mejoren el relacionamiento  con su personal y sus públicos de interés, declara que conoce, respeta y se adhiere voluntariamente a los';
        $content .= 'organización internacional del trabajo (documentos que forman parte competente del pliego de especificaciones). Declara que su personal se encuentra capacitado para realizar las actividades encomendadas,';
        $content .= 'que se presenta de manera voluntaria a su puesto de trabajo y que recibe una remuneración justa acorde al tipo de tarea que realiza y sin discriminación alguna de raza, color, sexo, religión, opinión política,';
        $content .= 'ascendencia nacional, discapacidad u origen social. Manifiesta que garantiza la equidad de oportunidades a su personal y que éste tiene todas las garantías y libertades para asociarse y constituirse en gremio';
        $content .= ', así como, para realizar negociaciones colectivas.';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return utf8_encode($content);
    }

    public function setDataSource(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function getDataSource()
    {
        return $this->dataSource;
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
        $html .= '<br>';
        $html .= $this->agregar_datos_detalle($adendaDet);
        $html .= '<br>';
        $html .= '<br>';
        $html .= $this->detalleEntrega();
        $html .= '<br>';
        $html .= $this->datosContacto();
        $html .= '<br>';
        $html .= '<br>';
        $html .= $this->notas();
        $html .= '<br>';
        $html .= '<br>';
        $html .= $this->conciliacion();
        $html .= '<br>';
        $html .= '<br>';
        $html .= $this->firmasAutorizacion();
        $html .= '<br>';
        $html .= '<br>';
        $html .= $this->notaFinal();


        $this->writeHTML($html, true, false, true, false, '');
    }

    function agregar_datos_detalle($detalles)
    {
        $styleHeader = 'style="background-color: #003366;color:#FFFFFF;font-weight: bold;"';
        $tbl = '<table border="0.5pt"  width="100%"  cellpadding="2" cellspacing="0"  style="font-family: Monospaced;font-size: 9pt;">';
        $tbl .= '<thead>';
        $tbl .= '<tr>';
        $tbl .= '<th ' . $styleHeader . ' width="80%">Partida</th>';
        $tbl .= '<th ' . $styleHeader . ' width="20%">Monto</th>';
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
            $tbl .= '<td width="80%" style="' . $style . '">';
            $tbl .= $detalle['nombre_partida'] . '<br>';
            $tbl .= "-&nbsp;" . $detalle['descripcion'] . '<br>';
            $tbl .= '</td>';
            $tbl .= '<td width="20%" style="' . $style . '" align="right">' . $detalle['nuevo_monto'] . '</td>';
            $tbl .= '</tr>';
            $i++;
        }
        $tbl .= '</tbody>';
        $tbl .= ' </table> ';
        return utf8_encode($tbl);
    }

    function detalleEntrega()
    {
        $content = '<table  width="100%" cellpadding="3" border="1">';
        $content .= '<tr>';
        $content .= '<td width="20%">';
        $content .= 'Tiempo de Entrega';
        $content .= '</td>';
        $content .= '<td width="80%" style="background-color: #E1E8F0;">';
        $content .= ' ';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td width="20%">';
        $content .= 'Lugar de Entrega';
        $content .= '</td>';
        $content .= '<td width="80%" style="background-color: #E1E8F0;">';
        $content .= ' ';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td width="20%">';
        $content .= 'Forma de Pago';
        $content .= '</td>';
        $content .= '<td width="80%" style="background-color: #E1E8F0;">';
        $content .= ' ';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return utf8_encode($content);
    }

    function datosContacto()
    {
        $content = '<br><br>';
        $content .= '<table width="100%" cellpadding="3" border="1">';
        $content .= '<tr>';
        $content .= '<td width="20%">';
        $content .= 'Contacto';
        $content .= '</td>';
        $content .= '<td width="80%" style="background-color: #E1E8F0;">';
        $content .= ' ';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td width="20%">';
        $content .= 'Correo';
        $content .= '</td>';
        $content .= '<td width="80%" style="background-color: #E1E8F0;">';
        $content .= ' ';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        $content .= '<br><br>';

        $content .= '<table>';
        $content .= '<tr>';
        $content .= '<td colspan="2" style="text-align: justify;">';
        $content .= 'Orden de Servicio emitido de acuerdo a la solicitud del Departamento de Control Protección y Telecomunicaciones y la cotización de oferta de servicios de fecha 22/11/2018 solicitada por Julio Pelaez, correspondiente al Servicio de Transporte de bobinas, Cajas pernos. Desde La Maica hacia Carreras – Tarija.';
        $content .= '<br>TOTAL: Bs 7.000,00.-, Facturado.';
        $content .= '<br>FORMA DE PAGO: 100% A la entrega del Servicio en conformidad de ENDE Transmisión S.A.';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        $content .= '<br><br>';

        $content .= '<table width="100%" cellpadding="3" border="0" style="font-size: 8pt">';
        $content .= '<tr>';
        $content .= '<td colspan="2" style="text-align: justify;background-color: #E1E8F0;">';
        $content .= 'El servicio deberá ser entregado conforme a lo solicitado, estipuladas en la cotización y la presente Orden.';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return utf8_encode($content);
    }

    function notas()
    {
        $content = '<table width="100%" cellpadding="3" border="0"  style="font-size: 7pt">';
        $content .= '<tr>';
        $content .= '<td width="100%" style="text-align: justify;background-color: #E1E8F0;">';
        $content .= '<ol type="1"  style="text-align: justify"> <li>Esta orden debe ser confirmada con la Copia adjunta</li>
        <li>No debe haber ninguna variacion en los Terminos, condiciones, embarque, precios, calidad, cantidad y especificaciones
        indicadas en este periodo.</li>
        <li>El numero de pedido debe aparecer en toda factura, lista de empaque y correspondencia relativa a esta adquisición.</li>
        <li>El embalaje debera ser en cajones adecuados para exportacion y reforzados con cintas de seguridad. Las marcas de
        embarque, Numero de bultos, dimensiones, pesos Neto y Bruto en Libras y kilos, deberan pintarse en caractéres visibles en
        cada bulto usando tinta indeleble.</li>
        <li>La lista de embarque debe mostrar claramente las marcas de embarque, numero de bultos, clases de bultos, contenido,
        dimensiones, peso neto y bruto de los bultos.</li>
        <li>El comprador se reserva el derecha de cancelar el pedido si la mercaderia no es embarcada dentro del periodo indicado. </li></ol>';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        $content .= '<br><br>';

        $content .= '<table width="100%" cellpadding="0" border="0">';
        $content .= '<tr>';
        $content .= '<td style="text-align: justify">';
        $content .= 'Enviar las facturas en original y copia a ENDE Transmision S.A. NIT 1023097024';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        return utf8_encode($content);
    }

    function conciliacion()
    {
        $content = '<table width="100%" cellpadding="3" border="0"  style="font-size: 8pt">';
        $content .= '<tr>';
        $content .= '<td>';
        $content .= 'CONCILIACIÓN Y ARBITRAJE';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td style="text-align: justify">';
        $content .= 'Las partes del presente contrato se comprometen a someter a la jurisdicción arbitral del Centro de Conciliación y Arbitraje Institucional de la Cámara de Comercio de Cochabamba, ante cualquier controversia
        que surja de la interpretación del presente contrato, de todos los documentos anexos que forman parte del mismo. Asimismo declaran que acataran el Acta de conciliación o el laudo arbitral, comprometiéndose
        a no presentar ninguna demanda de anulación. Cada parte elegirá un arbitro, el centro de conciliación y Arbitraje, elegirá el tercero. Las partes de común acuerdo, podrán elegir un solo arbitro.
        ';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        $content .= '<br><br>';

        $content .= '<table width="100%" cellpadding="3" border="0">';
        $content .= '<tr>';
        $content .= '<td style="text-align: justify">';
        $content .= 'EN CONFORMIDAD CON LOS TERMINOS DEL PRESENTE CONTRATO';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        return utf8_encode($content);
    }

    function firmasAutorizacion()
    {
        $content = '<table width="100%" cellpadding="3" border="0">';
        $content .= '<tr>';
        $content .= '<td>';
        $content .= '';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td>';
        $content .= 'FIRMA AUTORIZADA Y SELLO DEL PROVEEDOR';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        return utf8_encode($content);
    }

}

?>