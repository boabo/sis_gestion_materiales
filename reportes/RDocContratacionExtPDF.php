<?php
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDF.php';
//require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RDocContratacionExtPDF extends  ReportePDF{


    function Header()
    {
        $this->Ln(15);
        $url_imagen = dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png';


        $f_actual = date_format(date_create($this->datos[0]["fecha_solicitud"]), 'd/m/Y');
        $nro_cite_dce = $this->datos[0]["nro_cite_dce"];

        $html = <<<EOF
		<style>
		table, th, td {
   			border: 1px solid black;
   			border-collapse: collapse;
   			font-family: "Calibri";
   			font-size: 10pt;
		}
		</style>
		<body>
		<table border="1" cellpadding="1">
        	<tr>
            	<th style="width: 20%" align="center" rowspan="3"><img src="$url_imagen" ></th>
            	<th style="width: 50%" align="center" rowspan="3"><br><h3>DOCUMENTO DE CONTRATACIÓN DEL EXTERIOR</h3></th>
            	<th style="width: 30%" align="center" colspan="2">R-GG-2017</th>
        	</tr>
        	<tr>
        	    <td colspan="2" align="center">
        	        $nro_cite_dce
        	        <br>
        	    </td>
        	</tr>
        	<tr>
        	    <td>
        	        FECHA:
        	    </td>
        	    <td>
        	        $f_actual
        	    </td>
        	</tr>
        </table>
EOF;

        $this->writeHTML($html);

    }
    function setDatos($datos) {

        $this->datos = $datos;
    }

    function  generarReporte()
    {

        $this->AddPage();
        $this->SetMargins(17, 40, 15);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $tbl = '<table border="1">
                <tr style="font-size: 8pt; text-align: center;">
                    <td style="width:3%;"><b>N°</b></td>
                    <td style="width:12%;"><b>PART NUMBER</b></td>
                    <td style="width:17%;"><b>PART NUMBER ALTERNO</b></td>
                    <td style="width:24%;"><b>DESCRIPCION</b></td>
                    <td style="width:7%;"><b>CANT.</b></td>
                    <td style="width:8%;"><b>UNIDAD</b></td>
                    <td style="width:17%;"><b>LUGAR DE ENTREGA</b></td>
                    <td style="width:12%;"><b>CONDICION</b></td>
                </tr>
                ';
        //var_dump($this->datos);exit;
        $cont = 1;
        foreach( $this->datos as $record){
            $tbl .='<tr style="font-size: 8pt;"><td style="width:3%; text-align: center;">'.$cont.'</td><td>&nbsp;'. $record["nro_parte"].'</td><td>&nbsp;'. $record["nro_parte_alterno"].'</td><td>'. $record["descripcion"].'</td><td style="text-align: center;">'. $record["cantidad_sol"].'</td><td style="text-align: center;">'. $record["codigo"].'</td><td style="text-align: center;">'. $record["lugar_entrega"].'</td><td style="text-align: center;">'.$record["condicion"].'</td></tr>';
            $cont++;
        }
        $tbl.='</table>';

        $this->Ln(19);
        $this->writeHTML($tbl);

        $this->SetFont('', 'B',9);
        $this->MultiCell(200, 5, "\n" . 'FAVOR ENVIAR SU COTIZACIÓN AL CORREO abastecimiento@boa.bo', 0, 'L', 0, '', '');


    }

    function generarImagen($nom, $nac){
        $cadena_qr = 'Nombre: '.$nom. "\n" . 'Cargo: '.$nac ;
        $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/" . $nac . ".png");
            imagedestroy($im);

        } else {
            echo 'A ocurrido un Error.';
        }
        $url_archivo = dirname(__FILE__) . "/../../reportes_generados/" . $nac . ".png"; //$this->objParam->getParametro('nombre_archivo')

        return $url_archivo;
    }

}
?>