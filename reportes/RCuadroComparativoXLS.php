<?php

class RCuadroComparativoXLS
{
    private $docexcel;
    private $objWriter;
    private $numero;
    private $equivalencias=array();
    private $objParam;
    public  $url_archivo;
    private  $cabecera = array();
    private $proveedor = array();
    private $parte = array();
    private $cotizacion = array();
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
        $this->docexcel->getActiveSheet()->setTitle('Comparacion');
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
                'size'  => 10,
                'name'  => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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

        //titulos

        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,'CUADRO COMPARATIVO DE OFERTA PARA ADQUISICIÓN ' );
        $this->docexcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A2:M2');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,'DE BIENES Y SERVICIOS EN EL EXTRANJERO');
        $this->docexcel->getActiveSheet()->getStyle('A3:M3')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A3:M3');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,4,'(Decreto Supremo N° 26688) Versión I');
        $this->docexcel->getActiveSheet()->getStyle('A4:M4')->applyFromArray($styleTitulos2);
        $this->docexcel->getActiveSheet()->mergeCells('A4:M4');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,5,'Enviado a');
        $this->docexcel->getActiveSheet()->getStyle('A5:A5')->applyFromArray($styleTitulos2);

        $this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);

        $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
        $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
        $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);

        $this->docexcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setWrapText(true);
        //$this->docexcel->getActiveSheet()->getStyle('C9:L9')->getAlignment()->setWrapText(true);


    }
    function generarDatos()
    {
        $styleArrayCell = array(
            'font' => array(
                'name' => 'Arial',
                'size' => '8',
            ),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),
        );
        //ESTILO PARA EL PRECIO TOTAL
        $styleArrayPriceTotal = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => 'FF0000'),
                'size'  => 10,
                'name'  => 'Verdana'
            ),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )

        );
        $styleArrayCantAdjudicada = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '32CD32'),
                'size'  => 10,
                'name'  => 'Verdana'
            ),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )

        );


        $datos = $this->objParam->getParametro('datos');
        $this->imprimeCabecera(0);


        foreach ($datos as  $val)
        {
            if (!array_key_exists($val['desc_proveedor'], $this->proveedor)||!array_key_exists($val['parte'], $this->proveedor[$val['desc_proveedor']])||
                !array_key_exists($val['cantidad'], $this->proveedor[$val['desc_proveedor']][$val['parte']])
                ||!array_key_exists($val['cd'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['cantidad']])
                ||!array_key_exists($val['precio_unitario'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['cantidad']][$val['cd']])
                ||!array_key_exists($val['precio_unitario_mb'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['cantidad']][$val['cd']][$val['precio_unitario']])
                ||!array_key_exists($val['codigo_tipo'], $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['cantidad']][$val['cd']][$val['precio_unitario']][$val['precio_unitario_mb']])) {

                $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['cantidad']][$val['cd']][$val['precio_unitario']][$val['precio_unitario_mb']][$val['codigo_tipo']]= 1;
            } else {

                $this->proveedor[$val['desc_proveedor']][$val['parte']][$val['cantidad']][$val['cd']][$val['precio_unitario']][$val['precio_unitario_mb']][$val['codigo_tipo']]++;
            }
            if (!array_key_exists($val['parte'], $this->parte)) {

                $this->parte[$val['parte']]= 1;
            } else {

                $this->parte[$val['parte']]++;
            }

        }

        $column = 2;
        $file = 8 ;
        //count($this->proveedor)
        foreach ($this->proveedor as  $Rt =>$value1){
            $this->iniciarEvento(0);

            $this->docexcel->getActiveSheet()->mergeCellsByColumnAndRow($column,$file,$column+4,$file);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column, $file, $Rt);
           // $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column+1, $file, 'HOLA');
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column,$file)->applyFromArray($styleArrayCell);
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+1,$file)->applyFromArray($styleArrayCell);
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+2,$file)->applyFromArray($styleArrayCell);
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+3,$file)->applyFromArray($styleArrayCell);
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+4,$file)->applyFromArray($styleArrayCell);
            //
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column, $file+1, 'QTY');
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column,$file+1)->applyFromArray($styleArrayCell);

            //
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column+1, $file+1, 'CD');
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+1,$file+1)->applyFromArray($styleArrayCell);

            //
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column+2, $file+1, 'Precio ($us)');
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+2,$file+1)->applyFromArray($styleArrayCell);

            //
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column+3, $file+1, 'Monto Total($us)');
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+3,$file+1)->applyFromArray($styleArrayCell);

            //
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column+4, $file+1, 'Tiempo Entrega');
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+4,$file+1)->applyFromArray($styleArrayCell);

           /*  $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column+5, $file+1, 'RECOMENDACIÓN');
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column+5,$file+1)->applyFromArray($styleArrayCell);*/



            $column=$column+5;
            $fileItemCotizado=$file+2;
            foreach ($value1 as $it => $m) {


                    foreach ($m as $P => $G) {
                        /*if (count($this->proveedor) > 2){
                            $this->iniciarEvento($column);
                        }*/

                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column - 5 , $fileItemCotizado, $P);
                        $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column - 5, $fileItemCotizado)->applyFromArray($styleArrayCell);
                        foreach ($G as $d =>$h){
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column - 4, $fileItemCotizado, $d);
                            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column -  4, $fileItemCotizado)->applyFromArray($styleArrayCell);
                        }
                        foreach ($h as $pc =>$cc){
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column - 3, $fileItemCotizado, $pc);
                            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column - 3 , $fileItemCotizado)->applyFromArray($styleArrayCell);
                        }
                        foreach ($cc as $pp =>$tt){
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column - 2, $fileItemCotizado, $pp);
                            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column - 2, $fileItemCotizado)->applyFromArray($styleArrayCell);
                        }
                        foreach ($tt as $kk =>$ll){
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column - 1, $fileItemCotizado, $kk);
                            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($column - 1, $fileItemCotizado)->applyFromArray($styleArrayCell);
                            //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($column +1, $fileItemCotizado, 'hola');
                        }
                        $fileItemCotizado = $fileItemCotizado + 1;

                    }

            }
            //var_dump($value1);exit;


        }


    }
    function iniciarEvento($columna){
        $styleArrayCell = array(
            'font' => array(
                'name' => 'Arial',
                'size' => '8',
            ),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),
        );
        $fill = 10;
        $numer = 1;

        foreach ($this->parte as $uin => $WE){
            $this->docexcel->getActiveSheet()->mergeCells($this->equivalencias[$columna]."8".":".$this->equivalencias[$columna]."9");
            $this->docexcel->getActiveSheet()->mergeCells($this->equivalencias[$columna+1]."8".":".$this->equivalencias[$columna+1]."9");
            $this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$columna]."8",'Nro')->getStyle($this->equivalencias[$columna]."8".":".$this->equivalencias[$columna]."9")->applyFromArray($styleArrayCell);
            $this->docexcel->getActiveSheet()->setCellValue($this->equivalencias[$columna+1]."8",'Part  Number')->getStyle($this->equivalencias[$columna+1]."8".":".$this->equivalencias[$columna+1]."9")->applyFromArray($styleArrayCell);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fill, $numer);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna+1, $fill, $uin);
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($columna,$fill)->applyFromArray($styleArrayCell);
            $this->docexcel->getActiveSheet()->getStyleByColumnAndRow($columna+1,$fill)->applyFromArray($styleArrayCell);
            $numer++;
            $fill++;
        }



    }
    function generarReporte(){
        $this->docexcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->docexcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
        $this->docexcel->getActiveSheet()->getPageMargins()->setTop(1.4);
        $this->docexcel->getActiveSheet()->getPageMargins()->setLeft(0.9);
        $this->docexcel->getActiveSheet()->getPageMargins()->setRight(1);
        $this->docexcel->getActiveSheet()->getPageMargins()->setBottom(1.9);

        $this->docexcel->getActiveSheet()->getPageMargins()->setHeader(0.8);
        $this->docexcel->getActiveSheet()->getPageMargins()->setFooter(0.8);

        //$this->docexcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&C&HPlease treat this document as confidential!');
        $this->docexcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' .$this->docexcel->getProperties()->getTitle() . '&CPagina &P de &N');
       // $this->docexcel->getActiveSheet()->setShowGridlines(true);
        //$this->docexcel->setActiveSheetIndex(0);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
        $this->imprimeCabecera(0);

    }

}
?>