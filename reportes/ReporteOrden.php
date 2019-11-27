<?php
/*
*
*   ISSUE             FECHA:		      AUTOR           DESCRIPCION
*
*/
include_once dirname(__FILE__) . "/../../lib/lib_reporte/lang.es_AR.php";
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
        $adenda = $adenda[0];
        $header = '<table width="100%" cellpadding="0" cellspacing="0" >';
        $header .= '<tr>';
        $header .= '<td width="25%" rowspan="2">';
        $header .= '<img src="' . dirname(__FILE__) . '/../../lib/imagenes/logos/logo.jpg' . '" />';
        $header .= '</td>';
        $header .= '<td width="60%" >';
        $header .= '</td>';
        $header .= '<td width="15%" align="center"  >';
        $header .= $this->datosCabecera($adenda);
        $header .= '</td>';
        $header .= '</tr>';

        $header .= '<tr>';
        $header .= '<td align="center">';
        $header .= '<h2>Orden de Compra Modificatorio N&deg; ' . $adenda['numero_modificatorio'] . '</h2>';
        $header .= '</td>';
        $header .= '<td>';
        $header .= $this->titulo($adenda);
        $header .= '</td>';
        $header .= '</tr>';
        $header .= '</table>';
        $this->writeHTML(utf8_encode($header), true, false, true, false, '');
    }

    private function datosCabecera($adenda)
    {
        $content = '<table style="font-size: 8pt;" cellpadding="0" cellspacing="0">';
        $content .= '<tr>';
        $content .= '<td align="right">';
        $content .= $adenda['num_tramite'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td align="right">';
        $content .= $adenda['numero'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    private function titulo($adenda)
    {
        $fecha_modificacion = DateTime::createFromFormat("Y-m-d", $adenda['fecha_informe']);
        $content = '<table border="1" style="font-size: 8pt;" cellpadding="0" cellspacing="0">';
        $content .= '<tr>';
        $content .= '<th align="center"><b>Dia</b></th>';
        $content .= '<th align="center"><b>Mes</b></th>';
        $content .= '<th align="center"><b>A&ntilde;o</b></th>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td align="center">';
        $content .= $fecha_modificacion->format('d');
        $content .= '</td>';
        $content .= '<td align="center">';
        $content .= $fecha_modificacion->format('m');
        $content .= '</td>';
        $content .= '<td align="center">';
        $content .= $fecha_modificacion->format('Y');
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function datosProveedor($adenda)
    {
        $content = '<table  cellpadding="0" cellspacing="3">';
        $content .= '<tr>';
        $content .= '<th width="10%"><b>Se&ntilde;or(es):</b></th>';
        $content .= '<td width="90%" style="background-color: #E1E8F0;">';
        $content .= $adenda['rotulo_comercial'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th width="10%"><b>Direcci&oacute;n:</b></th>';
        $content .= '<td  width="90%" style="background-color: #E1E8F0;">';
        $content .= $adenda['direccion'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function Footer()
    {
        $this->notaFinal();
        $this->SetFontSize(8.5);
        parent::Footer();
    }

    function notaFinal()
    {
        $this->SetFontSize(5.5);
        $this->setY(-30); //-80  30
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        //set style for cell border
        $this->SetFont('', 'B');
        $this->Cell($ancho, 0, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode('CONOCIMIENTO SOBRE LA RESPONSABILIDAD')), 0, 0, 'C');
        $this->Ln(2);
        $this->SetFont('', '');
        $this->Cell($ancho, 0, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode('Consiente de la importancia de incorporar normativas y procedimientos que mejoren el relacionamiento  con su personal y sus públicos de interés, declara que conoce, respeta y se adhiere voluntariamente a los')), '', 1, 'L');
        $this->Cell($ancho, 0, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode('principios y reglas referentes a la responsabilidad social, tales como: la declaración Universal de Derechos Humanos, el convenio de las Naciones Unidas sobre los derechos de los niños y los convenios de la')), '', 1, 'L');
        $this->Cell($ancho, 0, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode('organización internacional del trabajo (documentos que forman parte competente del pliego de especificaciones). Declara que su personal se encuentra capacitado para realizar las actividades encomendadas,')), '', 1, 'L');
        $this->Cell($ancho, 0, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode('que se presenta de manera voluntaria a su puesto de trabajo y que recibe una remuneración justa acorde al tipo de tarea que realiza y sin discriminación alguna de raza, color, sexo, religión, opinión política,')), '', 1, 'L');
        $this->Cell($ancho, 0, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode('ascendencia nacional, discapacidad u origen social. Manifiesta que garantiza la equidad de oportunidades a su personal y que éste tiene todas las garantías y libertades para asociarse y constituirse en gremio,')), '', 1, 'L');
        $this->Cell($ancho, 0, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode(', así como, para realizar negociaciones colectivas.')), '', 1, 'L');
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
        $html = '<hr>';
        $adenda = $this->dataSource->getParameter('adenda');
        $adendaDet = $this->dataSource->getParameter('adendaDet');
        $presupuestoDet = $this->dataSource->getParameter('presupuestoDet');
        $adenda = $adenda[0];

        $this->AddPage();

        $this->width = $this->getPageWidth($this->getPage());
        $this->SetFontSize(8);
        $this->setTextColor(0, 0, 0);
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 008', PDF_HEADER_STRING);

        $html .= '<br/>';
        $html .= $this->datosProveedor($adenda);
        $html .= '<br/><br/>';
        $html .= $this->agregar_datos_detalle($adendaDet);
        $html .= '<br/><br/>';
        $html .= $this->detalleEntrega($adenda);
        $html .= '<br/><br/>';
        $html .= $this->datosContacto($adenda);
        $html .= '<br/><br/>';
        $html .= $this->datosGlosa($adenda);
        $html .= '<br/><br/>';
        $html .= $this->notas();
        $html .= '<br/><br/>';
        $html .= $this->conciliacion();
        $html .= '<br/><br/>';
        $html .= $this->firmasAutorizacion();


        $this->writeHTML(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($html)), true, false, true, false, '');
    }

    function agregar_datos_detalle($detalles)
    {
        $obj = new Numbers_Words_es_AR;
        $styleHeader = 'style="background-color: #003366;color:#FFFFFF;font-weight: bold;"';
        $tbl = '<table>';
        $tbl .= '<tr>';
        $tbl .= '<td  width="100%" >';
        $tbl .= 'De acuerdo a la propuesta económica presentada por su empresa, se detalla:';
        $tbl .= '</td>';
        $tbl .= '</tr>';
        $tbl .= '</table>';
        $tbl .= '<br/><br/>';
        $tbl .= '<table border="0.5pt"  width="100%"  cellpadding="2" cellspacing="0"  style="font-family: Monospaced;font-size: 8pt;">';
        $tbl .= '<thead>';
        $tbl .= '<tr>';
        $tbl .= '<th ' . $styleHeader . ' width="10%">Cantidad</th>';
        $tbl .= '<th ' . $styleHeader . ' width="15%">Precio Unitario</th>';
        $tbl .= '<th ' . $styleHeader . ' width="55%">Partida</th>';
        $tbl .= '<th ' . $styleHeader . ' width="20%">Monto</th>';
        $tbl .= '</tr>';
        $tbl .= '</thead>';
        $tbl .= '<tbody>';
        $i = 1;
        $precioTotal = 0;
        foreach ($detalles as $detalle) {
            $style = '';
            if ($i % 2 == 0) {
                $style = 'background-color: #E1E8F0;';
            }
            $tbl .= '<tr>';
            $tbl .= '<td width="10%" style="' . $style . '" align="right">' . $detalle['cantidad_adjudicada'] . '</td>';
            $tbl .= '<td width="15%" style="' . $style . '" align="right">' . $detalle['precio_unitario'] . '</td>';
            $tbl .= '<td width="55%" style="' . $style . '">';
            $tbl .= $detalle['nombre_partida'] . '<br/>';
            $tbl .= "-&nbsp;" . $detalle['descripcion'] . '<br/>';
            $tbl .= '</td>';
            $tbl .= '<td width="20%" style="' . $style . '" align="right">' . $detalle['monto_pago_mo'] . '</td>';
            $tbl .= '</tr>';
            $i++;
            $precioTotal += $precioTotal + $detalle['monto_pago_mo'];
        }
        $numero = explode('.', number_format($precioTotal, 2));
        $precioLiteral = strtoupper(trim($obj->toWords(str_replace(',', '', $numero[0])))) . ' ' . $numero[1] . '/' . '100 ';
        $tbl .= '<tr>';
        $tbl .= '<td colspan="3" align="right">' . $precioLiteral . '</td>';
        $tbl .= '<td width="20%" style="' . $style . '" align="right">' . $precioTotal . '</td>';
        $tbl .= '</tr>';
        $tbl .= '</tbody>';
        $tbl .= ' </table> ';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($tbl));
    }

    function detalleEntrega($adenda)
    {
        $content = '<table  width="100%" cellpadding="3" cellspacing="2">';
        $content .= '<tr>';
        $content .= '<th width="20%">';
        $content .= '<b>Fecha de Entrega:</b>';
        $content .= '</th>';
        $content .= '<td width="80%" style="background-color: #E1E8F0;">';
        $content .= $adenda['fecha_entrega'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th width="20%">';
        $content .= '<b>Lugar de Entrega:</b>';
        $content .= '</th>';
        $content .= '<td width="80%" style="background-color: #E1E8F0;">';
        $content .= $adenda['lugar_entrega'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th width="20%">';
        $content .= '<b>Forma de Pago:</b>';
        $content .= '</th>';
        $content .= '<td width="80%" style="background-color: #E1E8F0;">';
        $content .= $adenda['forma_pago'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function datosContacto($adenda)
    {
        $content = '<table width="100%" cellpadding="3" cellspacing="2" border="0" style="font-size: 7pt;">';
        $content .= '<tr>';
        $content .= '<td width="10%">';
        $content .= 'Contacto:';
        $content .= '</td>';
        $content .= '<td width="90%" style="background-color: #E1E8F0;">';
        $content .= $adenda['funcionario_contacto'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td width="10%">';
        $content .= 'Correo:';
        $content .= '</td>';
        $content .= '<td width="90%" style="background-color: #E1E8F0;">';
        $content .= $adenda['correo_contacto'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function datosGlosa($adenda)
    {
        $content = '<table width="100%" cellpadding="3" cellspacing="2">';
        $content .= '<tr>';
        $content .= '<td>';
        $content .= '<pre>' . $adenda['glosa'] . '</pre>';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function notas()
    {
        $content = '<table width="100%" cellpadding="3" border="0"  style="font-size: 7pt">';
        $content .= '<tr>';
        $content .= '<td width="100%" style="text-align: justify;background-color: #E1E8F0;">';
        $content .= 'La adquisición o contratación debe ser entregada conforme lo establecido en el pliego de condiciones, oferta y orden de compra';
        $content .= '</td>';
        $content .= '</tr>';
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

        $content .= '<br/><br/>';

        $content .= '<table width="100%" cellpadding="0" border="0">';
        $content .= '<tr>';
        $content .= '<td style="text-align: justify">';
        $content .= 'Enviar las facturas en original y copia a ENDE Transmision S.A. NIT 1023097024';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function conciliacion()
    {
        $content = '<table width="100%" cellpadding="3" border="0"  style="font-size: 7pt">';
        $content .= '<tr>';
        $content .= '<td align="center" style="font-weight: bold">';
        $content .= 'CONCILIACIÓN Y ARBITRAJE';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td style="text-align: justify">';
        $content .= 'Las partes del presente contrato se comprometen a someter a la jurisdicción arbitral del Centro de Conciliación y Arbitraje Institucional de la Cámara de Comercio de Cochabamba, ante cualquier controversia
        que surja de la interpretación del presente contrato, de todos los documentos anexos que forman parte del mismo. Asimismo declaran que acataran el Acta de conciliación o el laudo arbitral, comprometiéndose
        a no presentar ninguna demanda de anulación. Cada parte elegirá un arbitro, el centro de conciliación y Arbitraje, elegirá el tercero. Las partes de común acuerdo, podrán elegir un solo arbitro.';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        $content .= '<table width="100%" cellpadding="3" border="0">';
        $content .= '<tr>';
        $content .= '<td style="text-align: justify">';
        $content .= 'EN CONFORMIDAD CON LOS TERMINOS DEL PRESENTE CONTRATO';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
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

        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

}

?>