<?php
/**
*@package pXP
*@file gen-ACTSolicitudMantenimiento.php
*@author  (Ismael Valdivia)
*@date 16-01-2020 18:35:00
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'../../../pxp/pxpReport/DataSource.php');
require_once(dirname(__FILE__).'../../../pxp/sis_workflow/reportes/DiagramadorGanttWF.php');

class ACTSolicitudMantenimiento extends ACTbase{

	function insertarSolicitudMantenimiento(){

		/*Aumentando Sercicio para Recuperar los Cp de Alkym (Ismael Valdivia 28/06/2021)*/
		$actualizacion_Cp_alkym = $this->actualizarCpAlkym();
		/*********************************************************************************/




		if ($this->objParam->getParametro('url') == NULL) {
			$this->objParam->addParametro('archivo','NULL');
			$this->objParam->addParametro('extension','NULL');
			$this->objParam->addParametro('ruta','NULL');
			$this->objFunc=$this->create('MODSolicitudMantenimiento');
			$this->res=$this->objFunc->insertarSolicitudMantenimiento($this->objParam);
			$this->res->imprimirRespuesta($this->res->generarJson());
		} else {
			$ext = pathinfo($this->objParam->getParametro('url'));
			$file_name = $ext['filename'];
			$extension = $ext['extension'];
			$ruta_descom = explode("/", $this->objParam->getParametro('ruta'));

			$gestion = $ruta_descom[0];
			$carpeta = $ruta_descom[1];
			$codigo = $ruta_descom[2];
			$nombre_archivo = $ruta_descom[3];

			//$file_name = md5($nombre_file . $_SESSION["_SEMILLA"]);
			//$archivo_completo = $file_name.'.'.$extension;
			$ruta_archivo = "./../../../uploaded_files/sis_workflow/DocumentoWf/".$gestion."/".$carpeta."/".$codigo."/".$nombre_archivo;
			//$archivo = $nombre_file.'.'.$extension;
			//if (file_put_contents("./../../../uploaded_files/sis_workflow/DocumentoWf/".$archivo_completo,file_get_contents($this->objParam->getParametro('url')))) {
			/*Aqui mandamos los parametos*/
			$this->objParam->addParametro('archivo',$file_name);
			$this->objParam->addParametro('extension',$extension);
			$this->objParam->addParametro('ruta',$ruta_archivo);
			$this->objFunc=$this->create('MODSolicitudMantenimiento');
			$this->res=$this->objFunc->insertarSolicitudMantenimiento($this->objParam);
			$this->res->imprimirRespuesta($this->res->generarJson());
			//$this->subirArchivo();
		}
		//else {
		//			echo "No se cargo el archivo";
		// 		}
		//}


}


function actualizarCpAlkym(){

	$concatenar_variable = array("matricula"=>"");
	$envio_dato = json_encode($concatenar_variable);
	if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
		$request =  'http://sms.obairlines.bo/ServMantenimiento/servSiscomm.svc/MostrarAvion';
	} else {
		$request =  'http://sms.obairlines.bo/ServSisComm/servSiscomm.svc/MostrarAvion';
	}
	$session = curl_init($request);
	curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($session, CURLOPT_POSTFIELDS, $envio_dato);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($session, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($envio_dato))
	);
	$result = curl_exec($session);
	curl_close($session);
	$respuesta = json_decode($result);
	$respuesta_deco = json_decode($respuesta->MostrarAvionResult);
	$respuesta_final = ($respuesta_deco->objeto);

	/*Aqui recuperamos la informacion para enviar*/
	$json_obtenido = json_encode($respuesta_final);
	$cantidad_json = count($respuesta_final);
	/*********************************************/


	if ($respuesta_final == '') {
		throw new Exception('No se puede conectar con el servicio de Mantenimiento. Porfavor consulte con el Área de Sistemas');
	} else {

		//$this->objParam->addParametro('json_obtenido',$_SESSION["_LOGIN"]);
		$this->objParam->addParametro('json_obtenido',$json_obtenido);
		$this->objParam->addParametro('cantidad_json',$cantidad_json);
		$this->objFunc=$this->create('MODSolicitud');
		$cbteHeader=$this->objFunc->actualizarCpAlkym($this->objParam);
		if ($cbteHeader->getTipo() == 'EXITO') {
				return $cbteHeader;
		} else {
				$cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
				exit;
		}

	}
}

	// function subirArchivo(){
	// 	 $ext = pathinfo($this->objParam->getParametro('url'));
	// 	 $nombre_file = $ext['filename'];
	//    $extension = $ext['extension'];
	// 	 $file_name = md5($nombre_file . $_SESSION["_SEMILLA"]);
	// 	 $archivo_completo = $file_name.'.'.$extension;
	// 	 //$archivo = $nombre_file.'.'.$extension;
	// 	 if (file_put_contents("./../../../uploaded_files/sis_workflow/DocumentoWf/".$archivo_completo,file_get_contents($this->objParam->getParametro('url')))) {
	// 		/*Aqui mandamos los parametos*/
	// 		$this->objParam->addParametro('variable_global',$file_name);
	//
	// 		$this->objFunc=$this->create('MODSolicitudMantenimiento');
	// 		$this->res=$this->objFunc->insertarDocumentoWF($this->objParam);
	// 		$this->res->imprimirRespuesta($this->res->generarJson());
	// 	} else {
	// 		echo "Archivo no registrado";
	// 	}
	// }

	function ObtenerGantt(){

			$recup_id_proceso_wf = $this->ObtenerIdProcesoWf();

			$id_proceso_wf = $recup_id_proceso_wf->datos[0]["id_proceso_wf"];

			$dataSource = new DataSource();
				//$idSolicitud = $this->objParam->getParametro('nro_tramite');
				//$this->objParam->addParametroConsulta('id_plan_mant',$idPlanMant);
				$this->objParam->addParametroConsulta('ordenacion','nro_tramite');
				$this->objParam->addParametroConsulta('dir_ordenacion','ASC');
				$this->objParam->addParametroConsulta('cantidad',1000);
				$this->objParam->addParametroConsulta('puntero',0);
				$this->objParam->addParametro('id_proceso_wf',$id_proceso_wf);


				$this->objFunc = $this->create('MODSolicitudMantenimiento');

				$resultSolicitud = $this->objFunc->ObtenerGantt();

				if($resultSolicitud->getTipo()=='EXITO'){

								$datosSolicitud = $resultSolicitud->getDatos();
								$dataSource->setDataset($datosSolicitud);

								$nombreArchivo='diagramaGanttTramite.png';

								$diagramador = new DiagramadorGanttWF();
								$diagramador->setDataSource($dataSource);
					//var_dump($dataSource); exit;
							$diagramador->graficar($nombreArchivo);

								$mensajeExito = new Mensaje();

								$mensajeExito->setMensaje('EXITO','DiagramaGanttTramite.php','Diagrama Gantt de tramite generado',
																								'Se generó con éxito el diagrama para: '.$nombreArchivo,'control');
								/*Comentar para produccion*/
								if ($_SESSION["_ESTADO_SISTEMA"] == 'produccion') {
									$mensajeExito->setArchivoGenerado('https://erp.obairlines.bo/lib/lib_control/Intermediario.php?r='.$nombreArchivo);
								}else{
									$mensajeExito->setArchivoGenerado('http://10.150.0.91/kerp_ismael/lib/lib_control/Intermediario.php?r='.$nombreArchivo);
								}

								/*Descomentar para produccion*/
								//$mensajeExito->setArchivoGenerado('https://erp.obairlines.bo/lib/lib_control/Intermediario.php?r='.$nombreArchivo);
								$this->res = $mensajeExito;
								$this->res->imprimirRespuesta($this->res->generarJson());

							}
							else{

									 $resultSolicitud->imprimirRespuesta($resultSolicitud->generarJson());

			}
	}


	function ObtenerIdProcesoWf(){

		$this->objParam->addParametro('nro_tramite',$this->objParam->getParametro('nro_tramite'));

		$this->objFunc=$this->create('MODSolicitudMantenimiento');
		$cbteHeader=$this->objFunc->ObtenerIdProcesoWf($this->objParam);
		if ($cbteHeader->getTipo() == 'EXITO') {
				return $cbteHeader;
		} else {
				$cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
				exit;
		}
	}




}

?>
