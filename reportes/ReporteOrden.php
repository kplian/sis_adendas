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
    var $BG_COLOR='#C0C0C0';
    function Header()
    {
        $adenda = $this->getDataSource()->getParameter('adenda');
        $adenda = $adenda[0];
        $header = '<table width="100%" border="0" cellpadding="0" cellspacing="5" >';
        $header .= '<tr>';
        $header .= '<td width="24%" rowspan="3">';
        $header .= '<img src="' . dirname(__FILE__) . '/../../lib/imagenes/logos/logo.jpg' . '" />';
        $header .= '</td>';
        $header .= '<td width="64%" >';
        $header .= '</td>';
        $header .= '<td width="16%" align="center"  >';
        $header .= $this->datosCabecera($adenda);
        $header .= '</td>';
        $header .= '</tr>';
        $header .= '<tr>';
        $header .= '<td align="center">';
        $header .= '</td>';
        $header .= '<td>';
        $header .= '</td>';
        $header .= '</tr>';
        $header .= '<tr>';
        $header .= '<td align="center">';
        $header .= '<h1>Orden de Compra Modificatorio N&deg; ' . $adenda['numero_modificatorio'] . '</h1>';
        $header .= '</td>';
        $header .= '<td>';
        $header .= $this->fecha($adenda);
        $header .= '</td>';
        $header .= '</tr>';
        $header .= '</table>';
        $this->writeHTML(utf8_encode($header), true, false, true, false, '');
    }

    private function datosCabecera($adenda)
    {
        $content = '<table style="font-size: 6pt;font-stretch:condensed;white-space:nowrap;font-weight: bold " cellpadding="0" cellspacing="0">';
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

    private function fecha($adenda)
    {
        $fecha_modificacion = DateTime::createFromFormat("d/m/Y", date('d/m/Y', strtotime($adenda['fecha_informe'])));
        $content = '<table border="1" width="100%" style="font-size: 8pt;" cellpadding="1" cellspacing="0">';
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
        $content = '<table width="70%" cellpadding="2" style="font-size: 7pt">';
        $content .= '<tr>';
        $content .= '<th width="15%"><b>Se&ntilde;or(es):</b></th>';
        $content .= '<td width="90%" style="background-color: '.$this->BG_COLOR.';">';
        $content .= $adenda['rotulo_comercial'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th width="15%"><b>Direcci&oacute;n:</b></th>';
        $content .= '<td  width="90%" style="background-color: '.$this->BG_COLOR.';">';
        $content .= $adenda['direccion'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function Footer()
    {
        $this->setY(-24); //-80  30
        $this->notaFinal();
        $this->SetFontSize(8.5);
    }

    function notaFinal()
    {
        $this->SetFontSize(5.5);
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
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
        $html = '';
        $adenda = $this->dataSource->getParameter('adenda');
        $adendaDet = $this->dataSource->getParameter('adendaDet');
        $presupuestoDet = $this->dataSource->getParameter('presupuestoDet');
        $adenda = $adenda[0];
        $this->width = $this->getPageWidth($this->getPage());

        $this->AddPage();

        $this->setFont('','');
        $this->setTextColor(0, 0, 0);
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 008', PDF_HEADER_STRING);

        // set document information
        $this->SetCreator(PDF_CREATOR);
        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        //set margins
        $this->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(10);
        //set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        //set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $html .= '<br/><br/><br/>';
        $html .= $this->datosProveedor($adenda);
        $html .= '<br/><br/>';
        $html .= $this->agregar_datos_detalle($adendaDet,$adenda);
        $html .= '<br/>';
        $html .= $this->detalleEntrega($adenda);
        $html .= '<br/><br/><br/><br/>';
        $html .= $this->datosContacto($adenda);
        $html .= '<br/><br/>';
        $html .= $this->datosGlosa($adenda);
        $html .= '<br/><br/>';
        $html .= $this->notas();
        $html .= '<br/><br/><br/><br/><br/><br/><br/>';
        $html .= $this->conciliacion();
        $html .= '<br/><br/>';
        $html .= $this->firmasAutorizacion();


        $this->writeHTML(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($html)), true, false, true, false, '');
    }

    function agregar_datos_detalle($detalles,$adenda)
    {
        $obj = new Numbers_Words_es_AR;
        $tbl = '<table>';
        $tbl .= '<tr>';
        $tbl .= '<td  width="100%" style="font-size: 10pt">';
        $tbl .= 'De acuerdo a la propuesta económica presentada por su empresa, se detalla:';
        $tbl .= '</td>';
        $tbl .= '</tr>';
        $tbl .= '</table>';
        $tbl .= '<br/><br/>';
        $tbl .= '<table border="1"  width="100%"  cellpadding="2" cellspacing="0"  style="font-size: 7pt;">';
        $tbl .= '<tr>';
        $tbl .= '<th width="10%" align="center">Cantidad</th>';
        $tbl .= '<th width="15%" align="center">Precio Unitario</th>';
        $tbl .= '<th width="55%">Ítem</th>';
        $tbl .= '<th width="20%" align="right">Total</th>';
        $tbl .= '</tr>';
        $i = 1;
        $precioTotal = 0;
        foreach ($detalles as $detalle) {
            $tbl .= '<tr>';
            $tbl .= '<td width="10%" align="center">&nbsp;' . $detalle['cantidad_adjudicada'] . '</td>';
            $tbl .= '<td width="15%" align="left">&nbsp;' . number_format($detalle['precio_unitario'], 2) . '</td>';
            $tbl .= '<td width="55%" >&nbsp;';
            $tbl .= $detalle['nombre_partida'] . '<br/>';
            $tbl .= "&nbsp;-&nbsp;" . $detalle['descripcion'] . '<br/>';
            $tbl .= '</td>';
            $tbl .= '<td width="20%" align="right">' . number_format($detalle['monto_pago_mo'], 2) . '</td>';
            $tbl .= '</tr>';
            $i++;
            $precioTotal = $precioTotal + $detalle['monto_pago_mo'];
        }
        $numero = explode('.', number_format($precioTotal, 2));
        $precioLiteral = strtoupper(trim($obj->toWords(str_replace(',', '', $numero[0])))) . ' ' . $numero[1] . '/' . '100 ';
        $moneda = strtoupper($adenda['moneda']);
        $tbl .= '<tr>';
        $tbl .= '<td colspan="3" align="left">SON: ' . $precioLiteral . '&nbsp;' . $moneda . '</td>';
        $tbl .= '<td width="20%" align="right">' . number_format($precioTotal, 2) . '</td>';
        $tbl .= '</tr>';
        $tbl .= ' </table> ';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($tbl));
    }

    function detalleEntrega($adenda)
    {
        $fecha_entrega = DateTime::createFromFormat("d/m/Y", date('d/m/Y', strtotime($adenda['fecha_entrega'])));
        $content = '<table  width="100%" style="font-size: 8pt;" cellpadding="2" cellspacing="0">';
        $content .= '<tr>';
        $content .= '<th width="20%">';
        $content .= '<b>Fecha de Entrega:</b>';
        $content .= '</th>';
        $content .= '<td width="80%" style="background-color: '.$this->BG_COLOR.';">';
        $content .= $fecha_entrega->format('Y-m-d');
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th width="20%">';
        $content .= '</th>';
        $content .= '<td width="80%">';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th width="20%">';
        $content .= '<b>Lugar de Entrega:</b>';
        $content .= '</th>';
        $content .= '<td width="80%" style="background-color: '.$this->BG_COLOR.';">';
        $content .= $adenda['lugar_entrega'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<th width="20%">';
        $content .= '<b>Forma de Pago:</b>';
        $content .= '</th>';
        $content .= '<td width="80%" style="background-color: '.$this->BG_COLOR.';">';
        $content .= $adenda['forma_pago'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function datosContacto($adenda)
    {
        $content = '<table width="60%" cellpadding="2" cellspacing="0" border="0" style="font-size: 7pt;">';
        $content .= '<tr>';
        $content .= '<td width="15%">';
        $content .= 'Contacto:';
        $content .= '</td>';
        $content .= '<td width="70%" style="background-color: '.$this->BG_COLOR.';">';
        $content .= $adenda['funcionario_contacto'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td width="15%">';
        $content .= 'Correo:';
        $content .= '</td>';
        $content .= '<td width="70%" style="background-color: '.$this->BG_COLOR.';">';
        $content .= $adenda['correo_contacto'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function datosGlosa($adenda)
    {
        $content = '<table width="100%" style="font-size: 8pt" cellpadding="3" cellspacing="2">';
        $content .= '<tr>';
        $content .= '<td>';
        $content .= $adenda['glosa'];
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function notas()
    {
        $content = '<table width="100%" cellpadding="0" border="0"  style="font-size: 6pt">';
        $content .= '<tr>';
        $content .= '<td width="100%" style="text-align: justify;background-color: '.$this->BG_COLOR.';">';
        $content .= 'La adquisición o contratación debe ser entregada conforme lo establecido en el pliego de condiciones, oferta y orden de compra<br/>';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td width="100%">';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        $content .= '<table width="50%" cellpadding="0" border="0"  style="font-size: 6pt">';
        $content .= '<tr>';
        $content .= '<td width="100%" style="text-align: left;background-color: '.$this->BG_COLOR.';">';
        $content .= '<ol type="1"  style="text-align: left"> <li>Esta orden debe ser confirmada con la Copia adjunta</li>
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
        $content .= '<table width="55%" cellpadding="0" border="0"  style="font-size: 7.5pt">';
        $content .= '<tr>';
        $content .= '<td width="100%" style="text-align: left;">';
        $content .= 'Enviar las facturas en original y copia a ENDE Transmision S.A. NIT 1023097024';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function conciliacion()
    {
        $content = '<table width="100%" cellpadding="3" border="0"  style="font-size: 6pt">';
        $content .= '<tr>';
        $content .= '<td align="left">';
        $content .= 'CONCILIACIÓN Y ARBITRAJE';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td style="text-align: left">';
        $content .= 'Las partes del presente contrato se comprometen a someter a la jurisdicción arbitral del Centro de Conciliación y Arbitraje Institucional de la Cámara de Comercio de Cochabamba, ante cualquier controversia
        que surja de la interpretación del presente contrato, de todos los documentos anexos que forman parte del mismo. Asimismo declaran que acataran el Acta de conciliación o el laudo arbitral, comprometiéndose
        a no presentar ninguna demanda de anulación. Cada parte elegirá un arbitro, el centro de conciliación y Arbitraje, elegirá el tercero. Las partes de común acuerdo, podrán elegir un solo arbitro.';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td>';
        $content .= 'EN CONFORMIDAD CON LOS TERMINOS DEL PRESENTE CONTRATO';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';

        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', utf8_encode($content));
    }

    function firmasAutorizacion()
    {
        $content = '<table width="100%" cellpadding="3" border="0" style="font-size: 6pt;">';
        $content .= '<tr>';
        $content .= '<td>';
        $content .= '<br/><br/>';
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