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

    function generarDatos()
    {
      $tipo_reporte = $datos = $this->objParam->getParametro('tipo_reporte');

      if ($tipo_reporte != 'resumido') {
        $this->docexcel->createSheet();
        $this->docexcel->getActiveSheet()->setTitle('Detalle Adjudicados');
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
        $styleTitulos5 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial'

            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'F4D03F'
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
        $this->docexcel->getActiveSheet()->getStyle('A2:AA2')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A2:AA2');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,'Origen Pedido: '.$this->objParam->getParametro('origen_pedido'));
        $this->docexcel->getActiveSheet()->getStyle('A3:AA3')->applyFromArray($styleTitulos3);
        $this->docexcel->getActiveSheet()->mergeCells('A3:AA3');


        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,4,'Del: '.  $this->objParam->getParametro('fecha_ini').'   Al: '.  $this->objParam->getParametro('fecha_fin') );
        $this->docexcel->getActiveSheet()->getStyle('A4:AA4')->applyFromArray($styleTitulos3);
        $this->docexcel->getActiveSheet()->mergeCells('A4:AA4');

        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);

        $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
        $this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);

        $this->docexcel->getActiveSheet()->getStyle('A5:AA5')->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->getStyle('A5:I5')->applyFromArray($styleTitulos2);
        $this->docexcel->getActiveSheet()->getStyle('J5:AA5')->applyFromArray($styleTitulos5);
        $this->docexcel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);



        //*************************************Cabecera*****************************************
        $this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
        $this->docexcel->getActiveSheet()->setCellValue('B5','NRO. TRAMITE');
        $this->docexcel->getActiveSheet()->setCellValue('C5','FECHA SOLICITUD');
        $this->docexcel->getActiveSheet()->setCellValue('D5','FUNCIONARIO SOLICITANTE');
        $this->docexcel->getActiveSheet()->setCellValue('E5','PROVEEDOR');
        $this->docexcel->getActiveSheet()->setCellValue('F5','P.O.');
        $this->docexcel->getActiveSheet()->setCellValue('G5','MONTO PAGADO ($us)');
        $this->docexcel->getActiveSheet()->setCellValue('H5','FECHA REQUERIDA');
        $this->docexcel->getActiveSheet()->setCellValue('I5','AUXILIAR ABASTECIMIENTO');
        $this->docexcel->getActiveSheet()->setCellValue('J5','NRO. PART NUMBER');
        $this->docexcel->getActiveSheet()->setCellValue('K5','NRO. PART NUMBER ALTERNO');
        $this->docexcel->getActiveSheet()->setCellValue('L5','DESCRIPCION');
        $this->docexcel->getActiveSheet()->setCellValue('M5','CANTIDAD');
        $this->docexcel->getActiveSheet()->setCellValue('N5','PRECIO UNITARIO');

        $this->docexcel->getActiveSheet()->setCellValue('O5','PRECIO TOTAL');

        $this->docexcel->getActiveSheet()->setCellValue('P5','CENTRO COSTO');
        $this->docexcel->getActiveSheet()->setCellValue('Q5','PARTIDA');
        $this->docexcel->getActiveSheet()->setCellValue('R5','MATRICULA');
        $this->docexcel->getActiveSheet()->setCellValue('S5','MOTIVO SOLICITUD');
        $this->docexcel->getActiveSheet()->setCellValue('T5','OBSERVACIONES');
        $this->docexcel->getActiveSheet()->setCellValue('U5','JUSTIFICACION');
        $this->docexcel->getActiveSheet()->setCellValue('V5','N° JUSTIFICACION');
        $this->docexcel->getActiveSheet()->setCellValue('W5','TIPO SOLICITUD');
        $this->docexcel->getActiveSheet()->setCellValue('X5','TIPO FALLA');
        $this->docexcel->getActiveSheet()->setCellValue('Y5','TIPO REPORTE');
        $this->docexcel->getActiveSheet()->setCellValue('Z5','MEL');
        $this->docexcel->getActiveSheet()->setCellValue('AA5','N° NO RUTINA');




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
              ),
              'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              ),
              'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
          );
          $styleTitulos4 = array(
              'font'  => array(
                  'bold'  => true,
                  'size'  => 12,
                  'name'  => 'Arial'
              ),
              'fill' => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array(
                      'rgb' => 'D5F5E3'
                  )
              ));

          $this->numero = 1;
          $fila = 6;
          $datos = $this->objParam->getParametro('datos');
          $ger = '';
          $fila_ini = $fila;
          $fila_fin = $fila;
          $tmp_ini = $datos[0];
          $tmp_rec = $datos[0];
          $sumatoria_monto = 0;
          $last_key = end(array_keys($datos));
          $getTitle=false;
          foreach ($datos as $key => $value) {
              if ($value['origen_pedido'] != $ger) {
                  $this->docexcel->getActiveSheet()->getStyle("A$fila:A$fila")->applyFromArray($styleTitulos4);
                  $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $value['origen_pedido']);
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("A$fila:AA$fila");
                  $ger = $value['origen_pedido'];
                  $getTitle=true;
                  $fila++;
              }else{
                  $getTitle=false;
              }

              if($tmp_rec['nro_tramite'] != $value['nro_tramite']){
                  if($fila_ini == 6){
                      $fila_ini++;
                  }
                  if($getTitle and $key != 0){
                      $fila_fin = $fila-2;
                  }else{
                      $fila_fin = $fila-1;
                  }

                  $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila_ini,$sumatoria_monto);

                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("A".($fila_ini).":A".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("B".($fila_ini).":B".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("C".($fila_ini).":C".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("D".($fila_ini).":D".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("E".($fila_ini).":E".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("F".($fila_ini).":F".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("G".($fila_ini).":G".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("H".($fila_ini).":H".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("I".($fila_ini).":I".($fila_fin));

                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("R".($fila_ini).":R".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("S".($fila_ini).":S".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("T".($fila_ini).":T".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("U".($fila_ini).":U".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("V".($fila_ini).":V".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("W".($fila_ini).":W".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("X".($fila_ini).":X".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("Y".($fila_ini).":Y".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("Z".($fila_ini).":Z".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("AA".($fila_ini).":AA".($fila_fin));


                  $sumatoria_monto = 0;
                  $fila_ini = $fila;
              }
              if($key == $last_key){
                  $fila_fin = $fila;
                  $sumatoria_monto = $sumatoria_monto + $value['precio_unitario_mb'];

                  $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila_ini, $sumatoria_monto);

                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("A".($fila_ini).":A".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("B".($fila_ini).":B".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("C".($fila_ini).":C".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("D".($fila_ini).":D".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("E".($fila_ini).":E".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("F".($fila_ini).":F".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("G".($fila_ini).":G".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("H".($fila_ini).":H".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("I".($fila_ini).":I".($fila_fin));

                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("R".($fila_ini).":R".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("S".($fila_ini).":S".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("T".($fila_ini).":T".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("U".($fila_ini).":U".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("V".($fila_ini).":V".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("W".($fila_ini).":W".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("X".($fila_ini).":X".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("Y".($fila_ini).":Y".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("Z".($fila_ini).":Z".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("AA".($fila_ini).":AA".($fila_fin));


                  $sumatoria_monto = 0;
              }

              if (($tmp_rec['nro_tramite'] != $value['nro_tramite']) or ($tmp_ini['nro_tramite'] == $value['nro_tramite'])) {
                  $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
              }
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['nro_tramite']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['fecha_solicitud']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['funciaonario']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['proveedor']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nro_po']);

              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['fecha_requerida']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['aux_abas']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['nro_parte_cot']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['nro_parte_alterno_cot']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['descripcion_cot']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['cantidad_det']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['precio_unitario']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['precio_unitario_mb']);

              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['centro_costo']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['partida']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $value['matricula']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['motivo_solicitud']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['observaciones_sol']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['justificacion']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['nro_justificacion']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $value['tipo_solicitud']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, $value['tipo_falla']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, $value['tipo_reporte']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['mel']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['nro_no_rutina']);


              $this->docexcel->getActiveSheet()->getStyle("A$fila:F$fila")->applyFromArray($styleTitulos3);
              $this->docexcel->getActiveSheet()->getStyle("H$fila:I$fila")->applyFromArray($styleTitulos3);
              $this->docexcel->getActiveSheet()->getStyle("R$fila:AA$fila")->applyFromArray($styleTitulos3);

              //$this->docexcel->getActiveSheet()->getStyle("E$fila:E$fila")->applyFromArray($styleTitulos);
              $this->docexcel->getActiveSheet()->getStyle("A$fila:AA$fila")->applyFromArray($styleArray);
              $this->docexcel->getActiveSheet()->getStyle("M$fila:O$fila")->applyFromArray($styleTitulos);
              $this->docexcel->getActiveSheet()->getStyle("G$fila:G$fila")->applyFromArray($styleTitulos);
              //$this->docexcel->getActiveSheet()->getStyle("M$fila:O$fila")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
              $this->docexcel->getActiveSheet()->getStyle("G$fila:G$fila")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat :: FORMAT_NUMBER_COMMA_SEPARATED1);
              $this->docexcel->getActiveSheet()->getStyle("M$fila:O$fila")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat :: FORMAT_NUMBER_COMMA_SEPARATED1);

              if (($tmp_rec['nro_tramite'] != $value['nro_tramite']) or ($tmp_ini['nro_tramite'] == $value['nro_tramite'])){
                  $this->numero++;
              }

              $sumatoria_monto = $sumatoria_monto + $value['precio_unitario_mb'];

              $fila++;
              $num++;
              $tmp_rec = $value;
          }
      } else {
        $this->docexcel->createSheet();
        $this->docexcel->getActiveSheet()->setTitle('Resumen Adjudicados');
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
        $styleTitulos5 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial'

            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'F4D03F'
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
        $this->docexcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A2:I2');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,'Origen Pedido: '.$this->objParam->getParametro('origen_pedido'));
        $this->docexcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($styleTitulos3);
        $this->docexcel->getActiveSheet()->mergeCells('A3:I3');


        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,4,'Del: '.  $this->objParam->getParametro('fecha_ini').'   Al: '.  $this->objParam->getParametro('fecha_fin') );
        $this->docexcel->getActiveSheet()->getStyle('A4:I4')->applyFromArray($styleTitulos3);
        $this->docexcel->getActiveSheet()->mergeCells('A4:I4');

        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);

        $this->docexcel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray($styleTitulos2);
        $this->docexcel->getActiveSheet()->getStyle('E5:I5')->applyFromArray($styleTitulos5);
        $this->docexcel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);



        //*************************************Cabecera*****************************************
        $this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
        $this->docexcel->getActiveSheet()->setCellValue('B5','NRO. TRAMITE');
        $this->docexcel->getActiveSheet()->setCellValue('C5','FECHA SOLICITUD');
        $this->docexcel->getActiveSheet()->setCellValue('D5','PROVEEDOR');
        $this->docexcel->getActiveSheet()->setCellValue('E5','NRO. PART NUMBER');
        $this->docexcel->getActiveSheet()->setCellValue('F5','DESCRIPCION');
        $this->docexcel->getActiveSheet()->setCellValue('G5','CANTIDAD');
        $this->docexcel->getActiveSheet()->setCellValue('H5','PRECIO UNITARIO');
        $this->docexcel->getActiveSheet()->setCellValue('I5','PRECIO TOTAL');


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
              ),
              'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
              ),
              'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
          );
          $styleTitulos4 = array(
              'font'  => array(
                  'bold'  => true,
                  'size'  => 12,
                  'name'  => 'Arial'
              ),
              'fill' => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array(
                      'rgb' => 'D5F5E3'
                  )
              ));

          $this->numero = 1;
          $fila = 6;
          $datos = $this->objParam->getParametro('datos');
          $ger = '';
          $fila_ini = $fila;
          $fila_fin = $fila;
          $tmp_ini = $datos[0];
          $tmp_rec = $datos[0];
          $sumatoria_monto = 0;
          $last_key = end(array_keys($datos));
          $getTitle=false;
          foreach ($datos as $key => $value) {
              if ($value['origen_pedido'] != $ger) {
                  $this->docexcel->getActiveSheet()->getStyle("A$fila:A$fila")->applyFromArray($styleTitulos4);
                  $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $value['origen_pedido']);
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("A$fila:I$fila");
                  $ger = $value['origen_pedido'];
                  $getTitle=true;
                  $fila++;
              }else{
                  $getTitle=false;
              }

              if($tmp_rec['nro_tramite'] != $value['nro_tramite']){
                  if($fila_ini == 6){
                      $fila_ini++;
                  }
                  if($getTitle and $key != 0){
                      $fila_fin = $fila-2;
                  }else{
                      $fila_fin = $fila-1;
                  }

                  //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila_ini,$sumatoria_monto);

                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("A".($fila_ini).":A".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("B".($fila_ini).":B".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("C".($fila_ini).":C".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("D".($fila_ini).":D".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("E".($fila_ini).":E".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("F".($fila_ini).":F".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("G".($fila_ini).":G".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("H".($fila_ini).":H".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("I".($fila_ini).":I".($fila_fin));


                  $sumatoria_monto = 0;
                  $fila_ini = $fila;
              }
              if($key == $last_key){
                  $fila_fin = $fila;
                  $sumatoria_monto = $sumatoria_monto + $value['precio_unitario_mb'];

                //  $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila_ini, $sumatoria_monto);

                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("A".($fila_ini).":A".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("B".($fila_ini).":B".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("C".($fila_ini).":C".($fila_fin));
                  $this->docexcel->setActiveSheetIndex(0)->mergeCells("D".($fila_ini).":D".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("E".($fila_ini).":E".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("F".($fila_ini).":F".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("G".($fila_ini).":G".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("H".($fila_ini).":H".($fila_fin));
                  // $this->docexcel->setActiveSheetIndex(0)->mergeCells("I".($fila_ini).":I".($fila_fin));


                  $sumatoria_monto = 0;
              }

              if (($tmp_rec['nro_tramite'] != $value['nro_tramite']) or ($tmp_ini['nro_tramite'] == $value['nro_tramite'])) {
                  $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
              }
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['nro_tramite']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['fecha_solicitud']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['proveedor']);

              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['nro_parte_cot']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['descripcion_cot']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['cantidad_det']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['precio_unitario']);
              $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['precio_unitario_mb']);


              $this->docexcel->getActiveSheet()->getStyle("A$fila:D$fila")->applyFromArray($styleTitulos3);

              //$this->docexcel->getActiveSheet()->getStyle("E$fila:E$fila")->applyFromArray($styleTitulos);
              $this->docexcel->getActiveSheet()->getStyle("A$fila:I$fila")->applyFromArray($styleArray);
              $this->docexcel->getActiveSheet()->getStyle("G$fila:I$fila")->applyFromArray($styleTitulos);
              //$this->docexcel->getActiveSheet()->getStyle("G$fila:G$fila")->applyFromArray($styleTitulos);
              //$this->docexcel->getActiveSheet()->getStyle("M$fila:O$fila")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
              $this->docexcel->getActiveSheet()->getStyle("G$fila:I$fila")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat :: FORMAT_NUMBER_COMMA_SEPARATED1);
              //$this->docexcel->getActiveSheet()->getStyle("M$fila:O$fila")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat :: FORMAT_NUMBER_COMMA_SEPARATED1);

              if (($tmp_rec['nro_tramite'] != $value['nro_tramite']) or ($tmp_ini['nro_tramite'] == $value['nro_tramite'])){
                  $this->numero++;
              }

              $sumatoria_monto = $sumatoria_monto + $value['precio_unitario_mb'];

              $fila++;
              $num++;
              $tmp_rec = $value;
          }
      }




    }


    function imprimeAdjudicados(){

      $tipo_reporte = $datos = $this->objParam->getParametro('tipo_reporte');

      if ($tipo_reporte != 'resumido') {

        $this->docexcel->setActiveSheetIndex(1);
        $this->docexcel->getActiveSheet()->setTitle('Adjudicados');
        $datos = $this->objParam->getParametro('adjudicados');
        $fila = 2;
        $this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(35);
        $this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(35);
        $this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);

        $styleTitulos = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 8,
                'name'  => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'c5d9f1'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));
        $this->docexcel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setWrapText(true);

        $this->docexcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleTitulos);

        //*************************************Cabecera*****************************************
        $this->docexcel->getActiveSheet()->setCellValue('A1','# Tramite');
        $this->docexcel->getActiveSheet()->setCellValue('B1','Justificacion');
        $this->docexcel->getActiveSheet()->setCellValue('C1','Solicitante');
        $this->docexcel->getActiveSheet()->setCellValue('D1','Tecnico Adquisiciones');
        $this->docexcel->getActiveSheet()->setCellValue('E1','Proveedor Recomendado');
        $this->docexcel->getActiveSheet()->setCellValue('F1','Proveedor Adjudicado');
        $this->docexcel->getActiveSheet()->setCellValue('G1','Inicio Proceso');
        $this->docexcel->getActiveSheet()->setCellValue('H1','Precio en Bs');
        $this->docexcel->getActiveSheet()->setCellValue('I1','Precio del Proceso');
        $this->docexcel->getActiveSheet()->setCellValue('J1','Precio Adjudicado en Bs');
        $this->docexcel->getActiveSheet()->setCellValue('K1','Precio Adjudicado');
        $this->docexcel->getActiveSheet()->setCellValue('L1','Moneda del Proceso');
        $this->docexcel->getActiveSheet()->setCellValue('M1','Contrato');
        $this->docexcel->getActiveSheet()->setCellValue('N1','CUCE');
        $this->docexcel->getActiveSheet()->setCellValue('O1','Modalidad Contratación');
        $this->docexcel->getActiveSheet()->setCellValue('P1','Depto');
        $this->docexcel->getActiveSheet()->setCellValue('Q1','Número de Orden o Número PO');

        //*************************************Detalle*****************************************
        $columna = 0;
        foreach($datos as $value) {

            foreach ($value as $key => $val) {

                $this->docexcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($columna,$fila,$val);
                $this->docexcel->getActiveSheet()->getStyle("H$fila:K$fila")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat :: FORMAT_NUMBER_COMMA_SEPARATED1);
                $columna++;
            }
            $fila++;
            $columna = 0;
        }

      }


        //************************************************Fin Detalle***********************************************
    }


    function generarReporte(){

        $this->docexcel->setActiveSheetIndex(0);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);


    }

}
?>
