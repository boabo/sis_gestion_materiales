<?php

class RControlParteCotizacion
{
    private $docexcel;
    private $objWriter;
    private $numero;
    private $equivalencias=array();
    private $objParam;
    private $NroTra= array();
    private $Estado= array();
    public  $url_archivo;
    function __construct(CTParametro $objParam)
    {
        $this->objParam = $objParam;
        $this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
        //ini_set('memory_limit','512M');
        set_time_limit(400);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize'  => '10MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->docexcel = new PHPExcel();
        $this->docexcel->getProperties()->setCreator("PXP")
            ->setLastModifiedBy("PXP")
            ->setTitle($this->objParam->getParametro('titulo_archivo'))
            ->setSubject($this->objParam->getParametro('titulo_archivo'))
            ->setDescription('Reporte "'.$this->objParam->getParametro('titulo_archivo').'", generado por el framework PXP')
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Report File");

        $this->equivalencias=array( 0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
            9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',
            18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',
            26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',
            34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',
            42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',
            50=>'AY',51=>'AZ',
            52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',
            60=>'BI',61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',
            68=>'BQ',69=>'BR',70=>'BS',71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',
            76=>'BY',77=>'BZ');

    }
    function imprimeCabecera() {
        $this->docexcel->createSheet();
        $this->docexcel->getActiveSheet()->setTitle('Part Number');
        $this->docexcel->setActiveSheetIndex(0);

        $styleTitulos1 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 12,
                'name'  => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );


        $styleTitulos2 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => 'FFFFFF'
                )

            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '0066CC'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));
        $styleTitulos3 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 11,
                'name'  => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),

        );

        //modificacionw

        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,'PART NUMBER ADJUDICADAS' );
        $this->docexcel->getActiveSheet()->getStyle('A2:V2')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A2:V2');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,'Origen Pedido: '.$this->objParam->getParametro('origen_pedido'));
        $this->docexcel->getActiveSheet()->getStyle('A3:V3')->applyFromArray($styleTitulos3);
        $this->docexcel->getActiveSheet()->mergeCells('A3:V3');


        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,4,'Del: '.  $this->objParam->getParametro('fecha_ini').'   Al: '.  $this->objParam->getParametro('fecha_fin') );
        $this->docexcel->getActiveSheet()->getStyle('A4:V4')->applyFromArray($styleTitulos3);
        $this->docexcel->getActiveSheet()->mergeCells('A4:V4');

        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);

        $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
        $this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);

        $this->docexcel->getActiveSheet()->getStyle('A5:V5')->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->getStyle('A5:V5')->applyFromArray($styleTitulos2);



        //*************************************Cabecera*****************************************
        $this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
        $this->docexcel->getActiveSheet()->setCellValue('B5','NRO. TRAMITE');
        $this->docexcel->getActiveSheet()->setCellValue('C5','FUNCIONARIO SOLICITANTE');
        $this->docexcel->getActiveSheet()->setCellValue('D5','PROVEEDOR');
        $this->docexcel->getActiveSheet()->setCellValue('E5','FECHA SOLICITUD');
        $this->docexcel->getActiveSheet()->setCellValue('F5','FECHA REQUERIDA');
        $this->docexcel->getActiveSheet()->setCellValue('G5','NRO. PART NUMBER');
        $this->docexcel->getActiveSheet()->setCellValue('H5','NRO. PART NUMBER ALTERNO');
        $this->docexcel->getActiveSheet()->setCellValue('I5','DESCRIPCION');
        $this->docexcel->getActiveSheet()->setCellValue('J5','CANTIDAD');
        $this->docexcel->getActiveSheet()->setCellValue('K5','PRECIO UNITARIO');

        $this->docexcel->getActiveSheet()->setCellValue('L5','PRECIO TOTAL');
        $this->docexcel->getActiveSheet()->setCellValue('M5','MATRICULA');
        $this->docexcel->getActiveSheet()->setCellValue('N5','MOTIVO SOLICITUD');
        $this->docexcel->getActiveSheet()->setCellValue('O5','OBSERVACIONES');
        $this->docexcel->getActiveSheet()->setCellValue('P5','JUSTIFICACION');
        $this->docexcel->getActiveSheet()->setCellValue('R5','N° JUSTIFICACION');
        $this->docexcel->getActiveSheet()->setCellValue('Q5','TIPO SOLICITUD');
        $this->docexcel->getActiveSheet()->setCellValue('S5','TIPO FALLA');
        $this->docexcel->getActiveSheet()->setCellValue('T5','TIPO REPORTE');
        $this->docexcel->getActiveSheet()->setCellValue('U5','MEL');
        $this->docexcel->getActiveSheet()->setCellValue('V5','N° NO RUTINA');

    }
    function generarDatos()
    {
        $styleTitulos3 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $styleTitulos = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 11,
                'name'  => 'Calibri'
            ));
        $this->numero = 1;
        $fila = 6;
        $datos = $this->objParam->getParametro('datos');
        $this->imprimeCabecera(0);
        $ger = '';
        foreach ($datos as $value) {
            if ($value['origen_pedido'] != $ger) {
                $this->imprimeSubtitulo($fila,$value['origen_pedido']);
                $ger = $value['origen_pedido'];
                $fila++;
            }


            if (!array_key_exists($value['nro_tramite'], $this->NroTra)) {
                $this->NroTra[$value['nro_tramite']] = 1;
               // $value['origen_pedido'];
                $value['proveedor'];
                $value['nro_tramite'];
                $value['funciaonario'];
                $value['fecha_solicitud'];
                $value['fecha_requerida'];
                $value['matricula'];
                $value['motivo_solicitud'];
                $value['observaciones_sol'];
                $value['justificacion'];
                $value['nro_justificacion'];
                $value['tipo_solicitud'];
                $value['tipo_falla'];
                $value['tipo_reporte'];
                $value['mel'];
                $value['nro_no_rutina'];
            } else {
                $this->NroTra[$value['nro_tramite']]++;
              //  $value['estado'] = '';
               $value['proveedor'] = '';
                $value['nro_tramite'] = '';
                $value['funciaonario'] = '';
                $value['fecha_solicitud'] = '';
                $value['fecha_requerida'] = '';
                $value['matricula'] = '';
                $value['motivo_solicitud'] = '';
                $value['observaciones_sol'] = '';
                $value['justificacion'] = '';
                $value['nro_justificacion'] = '';
                $value['tipo_solicitud'] = '';
                $value['tipo_falla'] = '';
                $value['tipo_reporte'] = '';
                $value['mel'] = '';
                $value['nro_no_rutina'] = '';

            }
            if ( $value['nro_tramite'] != "") {
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
            }
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['nro_tramite']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['funciaonario']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['proveedor']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['fecha_solicitud']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['fecha_requerida']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['nro_parte_cot']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['nro_parte_alterno_cot']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['descripcion_cot']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['cantidad_det']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['precio_unitario']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['precio_unitario_mb']);

            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['matricula']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['motivo_solicitud']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['observaciones_sol']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['justificacion']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['nro_justificacion']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $value['tipo_solicitud']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['tipo_falla']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['tipo_reporte']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['mel']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['nro_no_rutina']);


            $this->docexcel->getActiveSheet()->getStyle("E$fila:E$fila")->applyFromArray($styleTitulos3);
            $this->docexcel->getActiveSheet()->getStyle("F$fila:F$fila")->applyFromArray($styleTitulos3);
            $this->docexcel->getActiveSheet()->getStyle("D$fila:D$fila")->applyFromArray($styleTitulos);
            $this->docexcel->getActiveSheet()->getStyle("Q$fila:Q$fila")->applyFromArray($styleTitulos3);
            $this->docexcel->getActiveSheet()->getStyle("A$fila:V$fila")->applyFromArray($styleArray);
            $this->docexcel->getActiveSheet()->getStyle("J$fila:L$fila")->applyFromArray($styleTitulos);
            if ( $value['nro_tramite'] != ""){
                $this->numero++;
            }
            $fila++;
        }

    }
    function imprimeSubtitulo($fila, $valor) {
        $styleTitulos = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 12,
                'name'  => 'Arial'
            ));

        $this->docexcel->getActiveSheet()->getStyle("A$fila:A$fila")->applyFromArray($styleTitulos);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $valor);

    }


    function generarReporte(){

        //$this->docexcel->setActiveSheetIndex(0);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
        $this->imprimeCabecera(0);

    }

}
?>