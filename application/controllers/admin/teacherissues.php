<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* LimeSurvey
* Copyright (C) 2007-2011 The LimeSurvey Project Team / Carsten Schmitz
* All rights reserved.
* License: GNU/GPL License v2 or later, see LICENSE.php
* LimeSurvey is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*
*/

/**
* Teacher Issues (Asuntos docentes)
*
* Este controlador manejará todas las solicitudes que tengan que ver con el módulo de asuntos docentes
*
* @package		LimeSurvey
* @subpackage	Backend
*/
class TeacherIssues extends Survey_Common_Action {

    const USUARIO  = 'DEW6';
    const PASSWORD = 'H#h1/d3w&';
    const CADENA_CONEXION = '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=172.16.1.36)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=SIAFINE)))';
    const CODIFICACION_CARACTERES = 'AL32UTF8';

    private $dbOracleConn;

	/**
    * Inicializa el controlador llamando al construct del padre.
    *
    * @access public
    * @param CController $controller
    * @param string $id
    * @return void
    */
    public function __construct($controller, $id){
        parent::__construct($controller, $id);
        $this->dbOracleConn = oci_connect(self::USUARIO, self::PASSWORD, self::CADENA_CONEXION,self::CODIFICACION_CARACTERES);
        if(!@($this->dbOracleConn)){
            echo "Falló la conexión a la base de datos.";
            $this->dbOracleConn = null;
        }
    }

    /**
    * Función index del controlador TeacherIssues, esta se encarga de redireccionar a la lista de configuraciones de plantillas.
    *
    * @access public
    * @return void
    */
    public function index(){
        $this->getController()->redirect(array('admin/teacherissues/sa/templateconfiguration'));
    }

    /**
     * Función que permite configurar la plantilla para evaluación de desempeño
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 04/05/2016
     * @return void Muestra la página para la configuración de la plantilla, a su vez, muestra todas las plantillas que se han creado.
     */
    public function templateconfiguration(){
        $aData = array();
        if(isset($_POST['nombre_plantilla'], $_POST['tipo_labor']) && trim($_POST['nombre_plantilla']) != "" && trim($_POST['tipo_labor']) != "" ){
            $plantillaEvaluacion = new PlantillaEvaluacion;
            $plantillaEvaluacion->plev_nombre = $_POST['nombre_plantilla'];
            $plantillaEvaluacion->plev_tipolabor = $_POST['tipo_labor'];
            $plantillaEvaluacion->save();
            Yii::app()->setFlashMessage(gT("La plantilla: ".$_POST['nombre_plantilla']." ha sido guardada exitosamente."));
        }
        $aData['model'] = $model = new PlantillaEvaluacion('search');
        if (isset($_GET['PlantillaEvaluacion']['searched_value']))
        {
            $model->searched_value = $_GET['PlantillaEvaluacion']['searched_value'];
        }
            // Set number of page
        if (isset($_GET['pageSize']))
        {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
        }
        
        $this->_renderWrappedTemplate('teacherissues', 'templateConfiguration_view', $aData);
    }

    /**
     * Función que permite configurar una fuente de información, la función almacena la fuente de información con todas sus informaciones adicionales, además si la petición es de eliminación de la fuente de información, verifica que exista en la base de datos y la elimina.
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 25/05/2016
     * @param  int $templateid Identificador único de la plantilla a la cual pertenece la fuente de información
     * @return void            No realiza un retorno, envia un JSON a la pantalla que accede a la función con el estado de la petición, success si se realizó correctamente, error si hubo algún error.
     */
    public function configurationinformationsource($templateid){
        // Cargo la plantilla de la base de datos
        $plantillaEvaluacion = PlantillaEvaluacion::model()->findByPk($templateid);
        // Verifico que la plantilla exista en bd
        if(!is_null($plantillaEvaluacion)){
            // Verifico que el arreglo $_POST contenga información
            // Si no contiene información se listarán todas las fuentes de información asociadas a la plantilla y se mostrarán en pantalla.
            if(!empty($_POST)){
                // Si posee información $_POST modifico la cabecera para responder en formato JSON
                header('Content-Type: application/json');
                // Si la acción es: actiondel eliminó la fuente de información de la base de datos.
                if(isset($_POST['actiondel'], $_POST['informationsourceid'])){
                    $fuenteInformacion = FuenteInformacion::model()->findByPk($_POST['informationsourceid']);
                    if(!is_null($fuenteInformacion)){
                        $fuenteInformacion->delete();
                        echo json_encode(array("state" => "success"));
                        die();
                    }
                    else{
                        echo json_encode(array("state" => "error"));
                        die();
                    }
                }
                // Si no viene la acción del, verifico que $_POST contenga un objeto JSON llamado plantilla
                elseif(isset($_POST['plantilla'])){
                    $plantilla = $_POST['plantilla'];
                    $plantilla = json_decode($plantilla);
                    $id_plantilla = $plantilla->id_plantilla;
                    if(isset($templateid, $id_plantilla) && $templateid == $id_plantilla){
                        // Extraigo la fuente de información de la plantilla, se setea el 0 debido a que siempre viene 1 sola fuente de información.
                        $fi = $plantilla->fi[0];
                        $id_encuesta = $fi->encuesta_fi;
                        $survey = Survey::model()->findByPk($id_encuesta);
                        $nombre_fuente_informacion = $fi->nombre_fi;
                        $peso_fuente_informacion = $fi->peso_fi;
                        if(is_null($survey)){
                            echo json_encode(array("state" => "error", "message" => "La encuesta seleccionada no existe."));
                            return; 
                        }else if(trim($nombre_fuente_informacion) == ""){
                            echo json_encode(array("state" => "error", "message" => "El campo Nombre es obligatorio."));
                            return; 
                        }else if(trim($peso_fuente_informacion) == "" || $peso_fuente_informacion < 1 ){
                            echo json_encode(array("state" => "error", "message" => "El peso de la fuente de información no debe ser menor de 1."));
                            return;
                        }else if(!isset($fi->grupos_fi)){
                            echo json_encode(array("state" => "error", "message" => "Debe seleccionar si desea o no permitir grupos de encuestados para la fuente de información."));
                            return;
                        }elseif (trim($fi->grupos_fi) == "") {
                            echo json_encode(array("state" => "error", "message" => "Debe seleccionar si desea o no permitir grupos de encuestados para la fuente de información."));
                            return;
                        }    
                        if($fi->grupos_fi == "true")
                            $permite_grupos = true;
                        else if($fi->grupos_fi == "false")
                            $permite_grupos = false;
                        else{
                            echo json_encode(array("state" => "error", "message" => "Debe seleccionar si desea o no permitir grupos de encuestados para la fuente de información."));
                            return;
                        }
                            
                        $idfi = $fi->id_fi;
                        $fuenteInformacion = null;
                        // Si no viene el id de la fuente de información es porque está creando uno nuevo, si viene es porque está editando uno existente.
                        $existente = false;
                        if($idfi == ""){
                            $fuenteInformacion = new FuenteInformacion;
                        } else{
                            $fuenteInformacion = FuenteInformacion::model()->findByPK($idfi);
                            InformacionAdicional::model()->deleteAll("fuin_inad_fk = :id_fuin", array(":id_fuin" => $fuenteInformacion->fuin_pk));
                            $existente = true;
                        }
                        // Inicio la transacción, si ocurre algún error en la inserción de la información se deberá devolver el estado de la bd a su estado original antes de realizar inserciones.
                        $pesoTotal = ($existente) ? $plantillaEvaluacion->getPesoTotal() + $peso_fuente_informacion - $fuenteInformacion->fuin_peso: $plantillaEvaluacion->getPesoTotal() + $peso_fuente_informacion;
                        if($pesoTotal > 100){  
                            echo json_encode(array("state" => "error", "message" => "La suma total de los pesos de las fuentes de información no debe ser superior al 100%. Suma total: ".$pesoTotal."%"));
                            return; 
                        }       
                        $transaction=$fuenteInformacion->dbConnection->beginTransaction();
                        $fuenteInformacion->fuin_nombre = $nombre_fuente_informacion;
                        $fuenteInformacion->fuin_peso = $peso_fuente_informacion;
                        $fuenteInformacion->fuin_permitegrupos = $permite_grupos;
                        $fuenteInformacion->surv_fuin_fk = $survey->sid;
                        $fuenteInformacion->plev_fuin_fk = $templateid;
                        // Guardo la fuente de información
                        if($fuenteInformacion->save(true)){
                            // Obtego la llave primaria con la que fue insertada, debido a que esta se autogenera.
                            $idFuenteInformacion = $fuenteInformacion->getPrimaryKey();
                            // Recorro todas las informaciones adicionales de la fuente de información
                            foreach ($fi->ia as $ia) {
                                $informacionAdicional = new InformacionAdicional;
                                $informacionAdicional->inad_nombre = $ia->nombre;;
                                $informacionAdicional->fuin_inad_fk = $idFuenteInformacion;
                                // Guardo la información adicional de la fuente de información
                                if($informacionAdicional->save(true)){
                                    $idInformacionAdicional = $informacionAdicional->getPrimaryKey();
                                    foreach ($ia->preguntas as $preguntas => $value) {
                                        $informacionAdicionalPregunta = new InformacionAdicionalPregunta;
                                        $informacionAdicionalPregunta->inad_inap_fk = $idInformacionAdicional;
                                        $informacionAdicionalPregunta->ques_inap_fk = $value;
                                        // Guardo la relación entre la información adicional y el id de la pregunta
                                        if($informacionAdicionalPregunta->save(true)){
                                        }
                                        else{
                                            $transaction->rollBack();
                                            echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
                                            return; 
                                        }
                                    }
                                }else{
                                   $transaction->rollBack();
                                   echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
                                   return; 
                                }
                            }
                            // Si no ocurren errores se hace el commit a la bd
                            $transaction->commit();
                            echo json_encode(array("state" => "success", "fuin_pk" => $idFuenteInformacion, "Tr" => "Commit hecho."));
                            return;
                        }else{
                            $transaction->rollBack();
                            echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
                            return; 
                        }
                    }
                }
                  
            }else{
                $aData = array();
                $aData['nombre_plantilla'] = $plantillaEvaluacion->plev_nombre;
                $aData['id_plantilla'] = $plantillaEvaluacion->plev_pk;
                $criteria = new CDbCriteria();
                $criteria->addCondition("plev_fuin_fk = :plev_pk");
                $criteria->params = array(':plev_pk' => $plantillaEvaluacion->plev_pk);
                // Consulto todas las fuentes de información asociadas a la plantilla del parametro de la función
                $consulta_fi = FuenteInformacion::model()->findAll($criteria);
                $arrayDataProvider = new CArrayDataProvider($consulta_fi, array(
                    'keyField'=> 'fuin_pk',
                    'sort' => array(
                        'attributes'=>array(
                            'fuin_pk',
                        ), 
                        'defaultOrder' => array(
                            'fuin_pk'=>CSort::SORT_ASC,
                        ),   
                    ),
                    'pagination'=>array(
                         'pageSize'=>5,
                     ),
                    ));
                $aData['fuentesinformacion'] = $arrayDataProvider;
                $this->_renderWrappedTemplate('teacherissues', 'configurationInformationSource_view', $aData);
            }
        }
        else{
            Yii::app()->setFlashMessage(gT("Identificador de plantilla no válido."), "error");
            $this->getController()->redirect(array("admin/teacherissues/sa/templateconfiguration"));
            die();
        }
    }

    /**
     * Función que permite ver y buscar las evaluaciones de desempeño creadas. 
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 12/05/2016
     * @return void Muestra la página donde se listan las evaluaciones de desempeño.
     */
    public function performanceevaluation(){
        $aData = array();
        $aData['model'] = $model = new EvaluacionDesempeno('search');
       
        if (isset($_GET['EvaluacionDesempeno']['searched_value']))
        {
            $model->searched_value = $_GET['EvaluacionDesempeno']['searched_value'];
        }
            // Set number of page
        if (isset($_GET['pageSize']))
        {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
        }
        
        $this->_renderWrappedTemplate('teacherissues', 'performanceEvaluation_view', $aData);
    }

    /**
     * Función que permite agregar una evaluación de desemepeño.
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 01/06/2016
     * @return void Muestra la página para agregar una nueva evaluación de desempeño, además si vienen datos por post sobre una evaluación de desempeño se encarga de adicionarla en la base de datos realizando las respectivas clonaciones de encuestas.
     */
    public function addperformanceevaluation(){
        if(isset($_POST, $_POST['evaluacion']) ){
            header('Content-Type: application/json');
            $evaluacion = $_POST['evaluacion'];
            $evaluacion = json_decode($evaluacion);
            $evaluacion = $evaluacion->evaluacion;
            $plantilla_evaluado = PlantillaEvaluacion::model()->findByPk($evaluacion->idplantilla);
            if(is_null($plantilla_evaluado)){
                // Error en la seleccion de la plantilla
                echo json_encode(array('state' => 'error', 'message' => 'Debe seleccionar una plantilla válida.'));
            }else{
                $facultad_evaluado = $evaluacion->idfacultad;
                $programa_evaluado = $evaluacion->idprograma;
                $tipo_identificacion = $evaluacion->tipo_identificacion;
                $identificacion_evaluado = $evaluacion->identificacion;
                $nombre_evaluado = $evaluacion->nombre;
                $dependencia_evaluado = null;
                // Si la plantilla es de docente o director de programa debe existir la selección de la facultad y el programa
                if( ($plantilla_evaluado->plev_tipolabor == 1 || $plantilla_evaluado->plev_tipolabor == 3 ) && !is_null($facultad_evaluado) && !is_null($programa_evaluado)){
                    $dependencia_evaluado = $programa_evaluado;
                }
                // Si la plantilla es de decano debe existir la selección de la facultad
                elseif ($plantilla_evaluado->plev_tipolabor == 4 && !is_null($facultad_evaluado)) {
                    $dependencia_evaluado = $facultad_evaluado;
                }
                // Si la plantilla es de vicerrector académico no necesitamos facultad ni nada
                elseif ($plantilla_evaluado->plev_tipolabor == 5) {
                    $dependencia_evaluado = 246; // Vicerrectoria académica
                }
                if(!is_null($dependencia_evaluado)){
                    if(!is_null($tipo_identificacion) && !is_null($identificacion_evaluado) && !is_null($nombre_evaluado) && trim($tipo_identificacion) != "" && trim($identificacion_evaluado) != "" && trim($nombre_evaluado) != "" ){
                        $fuentesinformacion = $evaluacion->fuentesinformacion;
                        if(sizeof($fuentesinformacion) > 0){
                            $suma = 0;
                            $error_suma = false;
                            foreach ($fuentesinformacion as $fi) {
                                if($fi->pesofi < 0 ){
                                    echo json_encode(array('state' => 'error', 'message' => 'Los pesos de las fuentes de información no deben ser inferiores a 0'));
                                    die();
                                }
                                $suma+= $fi->pesofi;
                                if($suma > 100){
                                    // Error, suma de los pesos mayor que el 100%
                                    $error_suma = true;
                                    break;
                                }
                                $aGrupos = $fi->gruposfi;
                                if(sizeof($aGrupos) > 0){
                                    // Verifico si todos los grupos están inhabilitados
                                    $resultado_verificacion = $this->_verificar_todos_grupos_inhabilitados($aGrupos);
                                    if($resultado_verificacion && $fi->pesofi != 0 ){
                                        $error_suma = true;
                                        break;
                                    }
                                }
                            }
                            if(!$error_suma && $suma == 100){
                                $error_en_bd = false;
                                $evaluacionDesempeno = new EvaluacionDesempeno;
                                $evaluacionDesempeno->evde_fechaevaluacion = date('Y-m-d');
                                $evaluacionDesempeno->evde_identificacionevaluado = $identificacion_evaluado;
                                $evaluacionDesempeno->evde_dependenciaevaluado = $dependencia_evaluado;
                                $evaluacionDesempenoId = null;
                                $idEncuestas = array();
                                $idGrupos = array();
                                $idEvaluacionesFuentes = array();
                                if($evaluacionDesempeno->save(true)){
                                    $evaluacionDesempenoId = $evaluacionDesempeno->getPrimaryKey();
                                    foreach ($fuentesinformacion as $fi) {
                                        if(!is_null($fi->idfi) ){
                                            $fuenteInfo = FuenteInformacion::model()->findByPk($fi->idfi);
                                            $aGrupos = $fi->gruposfi;
                                            if(sizeof($aGrupos) > 0){
                                                foreach ($aGrupos as $key => $grupofi) {
                                                    $aData = $this->copy_survey($fuenteInfo->surv_fuin_fk, "EVALUACIÓN DE DESEMPEÑO - FUENTE: ".$fuenteInfo->fuin_nombre." - EVALUADO: ".$nombre_evaluado." - MATERIA: ".$grupofi->mate_nombre." - GRUPO: ".$grupofi->grup_nombre);

                                                    if(isset($aData['bFailed']) && $aData['bFailed'] == true){
                                                        $error_en_bd = true;
                                                        break;
                                                    }else{
                                                        $aImportResults = $aData['aImportResults'];
                                                        $surveyid = $aImportResults['newsid'];
                                                        $idEncuestas[] = $surveyid;
                                                        $grupo = new Grupo;
                                                        $grupo->grup_nombre = $grupofi->mate_nombre." - ".$grupofi->grup_nombre;
                                                        $grupo->grup_grupoid = $grupofi->grup_id;
                                                        $grupo->surv_grup_fk = $surveyid;
                                                        $grupo->grup_estado = ($grupofi->grup_estado === "true") ? true: false;
                                                        if($fi->pesofi == 0)
                                                            $grupo->grup_estado = false;
                                                        if($grupo->save(true)){
                                                            $grupoid = $grupo->getPrimaryKey();
                                                            $idGrupos[] = $grupoid;
                                                            $evaldesefueninfo = new EvaluacionDesempenoFuenteInformacion;
                                                            $evaldesefueninfo->evde_edfi_fk = $evaluacionDesempenoId;
                                                            $evaldesefueninfo->fuin_edfi_fk = $fuenteInfo->fuin_pk;
                                                            $evaldesefueninfo->grup_edfi_fk = $grupoid;
                                                            $evaldesefueninfo->edfi_peso = $fi->pesofi;
                                                            if($evaldesefueninfo->save(true)){
                                                                $idEvaluacionesFuentes[] = $evaldesefueninfo->getPrimaryKey();
                                                            }else{
                                                                $error_en_bd = true;
                                                            }
                                                        }else{
                                                            $error_en_bd = true;
                                                        }
                                                    }
                                                } // Cierre foreach de gruposfi
                                            }else{
                                                // No vienen grupos de materias
                                                $aData = $this->copy_survey($fuenteInfo->surv_fuin_fk, "EVALUACIÓN DE DESEMPEÑO - FUENTE: ".$fuenteInfo->fuin_nombre." - EVALUADO: ".$nombre_evaluado);
                                                
                                                if(isset($aData['bFailed']) && $aData['bFailed'] == true){
                                                    $error_en_bd = true;
                                                    break;
                                                }else{
                                                    $aImportResults = $aData['aImportResults'];
                                                    $surveyid = $aImportResults['newsid'];
                                                    $idEncuestas[] = $surveyid;
                                                    $grupo = new Grupo;
                                                    $nombre_grupo = $fuenteInfo->fuin_nombre." - ".$identificacion_evaluado;
                                                    $grupo->grup_nombre = $nombre_grupo;
                                                    $grupo->surv_grup_fk = $surveyid;
                                                    $grupo->grup_estado = true;
                                                    if($fi->pesofi == 0)
                                                        $grupo->grup_estado = false;
                                                    if($grupo->save(true)){
                                                        $grupoid = $grupo->getPrimaryKey();
                                                        $idGrupos[] = $grupoid;
                                                        $evaldesefueninfo = new EvaluacionDesempenoFuenteInformacion;
                                                        $evaldesefueninfo->evde_edfi_fk = $evaluacionDesempenoId;
                                                        $evaldesefueninfo->fuin_edfi_fk = $fuenteInfo->fuin_pk;
                                                        $evaldesefueninfo->grup_edfi_fk = $grupoid;
                                                        $evaldesefueninfo->edfi_peso = $fi->pesofi;
                                                        if($evaldesefueninfo->save(true)){
                                                            $idEvaluacionesFuentes[] = $evaldesefueninfo->getPrimaryKey();
                                                        }else{
                                                            $error_en_bd = true;
                                                        }
                                                    }else{
                                                        $error_en_bd = true;
                                                    }
                                                }
                                            }
                                        }
                                    } // End foreach
                                }else{
                                    // Error al insertar la EvaluacionDesempeno
                                    $error_en_bd = true;
                                }
                                if($error_en_bd){
                                    if(!is_null($evaluacionDesempenoId)){
                                        foreach ($idEvaluacionesFuentes as $key => $value) {
                                            $evdefuin = EvaluacionDesempenoFuenteInformacion::model()->findByPk($value);
                                            if(!is_null($evdefuin)){
                                                $evdefuin->delete();
                                                echo "Eliminada la evdefuin";
                                            }
                                        }
                                        foreach ($idGrupos as $key => $value) {
                                            $grupo = Grupo::model()->findByPk($value);
                                            if(!is_null($grupo)){
                                                $grupo->delete();
                                                echo "eliminado el grupo";
                                            }
                                        }
                                        foreach ($idEncuestas as $key => $value) {
                                            $encuesta = Survey::model()->findByPk($value);
                                            if(!is_null($encuesta)){
                                                rmdirr(Yii::app()->getConfig('uploaddir') . '/surveys/' .$encuesta->sid);
                                                $encuesta->delete();
                                                echo "eliminada la encuesta";
                                            }
                                        }
                                        $evde = EvaluacionDesempeno::model()->findByPk($evaluacionDesempenoId);
                                        if(!is_null($evde)){
                                            $evde->delete();
                                        }
                                    }
                                }
                                else{
                                    echo json_encode(array('state' => 'success', 'message' => 'Se ha guardado la evaluación de desempeño con éxito.', 'url' => $this->getController()->createUrl("admin/teacherissues/sa/performanceevaluation")));
                                }
                            }else{
                                echo json_encode(array('state' => 'error', 'message' => 'La suma de los pesos de las fuentes de información debe ser igual al 100%.'));
                            }
                        }else{
                            // Error en las fuentes de información, no existe al menos 1
                            echo json_encode(array('state' => 'error', 'message' => 'Debe existir al menos 1 fuente de información para guardar la evaluación de desempeño.'));
                        }
                    } else{
                        // Error en el tipo de identificación o en la identificación
                        echo json_encode(array('state' => 'error', 'message' => 'Tipo de identificación, número de identificación y nombre son requeridos.'));
                    }
                }else{
                    echo json_encode(array('state' => 'error', 'message' => 'Todos los campos que estén habilitados para selección son requeridos.'));
                }
            }
        }else{
            $aData = array();
            $command = Yii::app()->dbOracle->createCommand("call REPORTES.PR_REPORTES_TIPODOCUMENTO(P_RECORDSET)");
            $sql = 'BEGIN REPORTES.PR_REPORTES_TIPODOCUMENTO(:tipo_documentos); END;';
            $stmt = oci_parse($this->dbOracleConn, $sql);
            // Crear un nuevo cursor 
            $tipo_documentos = oci_new_cursor($this->dbOracleConn);
            // Pasar el cursor a la consulta
            oci_bind_by_name($stmt,":tipo_documentos",$tipo_documentos,-1,SQLT_RSET);
            // Ejecutar la consulta
            oci_execute($stmt);
            // Ejecutar el cursor
            oci_execute($tipo_documentos);
            $documentos = array();
            // Use oci_fetch_assoc para obtener los resultados en un arreglo asociativo.
            while ($entry = oci_fetch_assoc($tipo_documentos)) {
                $documentos[$entry['TIDG_ID']] = $entry['TIDG_DESCRIPCION'];
            }
            $facultades = array();
            $facultades[''] = "Por favor seleccione...";
            $sql = "SELECT DISTINCT UNID_ID, UNID_NOMBRE FROM REPORTES.VW_REPORTES_FACULTADPROGRAMA ORDER BY UNID_NOMBRE";
            $stmt = oci_parse($this->dbOracleConn, $sql);
            oci_execute($stmt);
            while ($entry = oci_fetch_assoc($stmt)) {
                $facultades[$entry['UNID_ID']] = $entry['UNID_NOMBRE'];
            }
            oci_close($this->dbOracleConn);
            $aData['plantillas'] = PlantillaEvaluacion::model()->findAll();
            $aData['facultades'] = $facultades;
            $aData['tipo_documentos'] = $documentos;
            $this->_renderWrappedTemplate('teacherissues', 'addPerformanceEvaluation_view', $aData);
        }
    }

    /**
     * Función que permite editar una evaluación de desempeño.
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 14/06/2016
     * @return void Muestra la página de edición de evaluación de desempeño.
     */
    public function editperformanceevaluation($performanceevaluationid){
        if(isset($_POST) && sizeof($_POST) > 0 && isset($_POST['informacion'])){
            header("Content-Type: application/json");
            $informacion = json_decode($_POST['informacion']);
            $idevaluacion = $informacion->idevaluacion;
            $evaluacionDesempeno = EvaluacionDesempeno::model()->findByPk($idevaluacion);
            if(is_null($evaluacionDesempeno)){
                echo json_encode(array("state" => "error", "message" => "Identificador de la evaluación de desempeño no válido."));
                die();
            }
            $fuentes = $informacion->fuentes;
            $peso_total = 0;
            foreach ($fuentes as $fi) {
                $peso_total += $fi->pesofi;
            }
            if($peso_total != 100){
                echo json_encode(array("state" => "error", "message" => "La suma de los pesos de la fuentes de información debe ser igual a 100%"));
                die();
            }
            $transaction = Yii::app()->db->beginTransaction();
            foreach ($fuentes as $fi) {
                $idfi = $fi->idfi;
                $fuenteInformacion = FuenteInformacion::model()->findByPk($idfi);
                if(is_null($fuenteInformacion)){
                    echo json_encode(array("state" => "error", "message" => "Identificador de la fuente de información no válido."));
                    $transaction->rollback();
                    die();
                }
                $pesofi = $fi->pesofi;
                $gruposfi = $fi->gruposfi;
                // Con este if verifico que si todos los grupos que vengan están en falso en su estado, establezco el peso en 0 para la fuente de información, si existe al menos 1 con estado en true, se conserva el peso que venga desde la interfaz.
                $resultado_comprobacion = $this->_verificar_todos_grupos_inhabilitados($gruposfi);
                if($resultado_comprobacion){
                    $pesofi = 0;
                }
                else if($pesofi < 1){
                    echo json_encode(array("state" => "error", "message" => "Debe establecer un peso para los grupos habilitados."));
                    $transaction->rollback();
                    die();
                }
                foreach ($gruposfi as $gfi) {
                    $grup_estado = ($gfi->grup_estado == "true") ? true : false;
                    $grup_id = $gfi->grup_id;
                    $grupo = Grupo::model()->findByPk($grup_id);
                    if(is_null($grupo)){
                        echo json_encode(array("state" => "error", "message" => "Identificador del grupo no válido."));
                        $transaction->rollback();
                        die();
                    }
                    $grupo->grup_estado = $grup_estado;
                    if(!$grupo->save(true)){
                        echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
                        $transaction->rollback();
                        die();
                    }
                }
                $criteria = new CDbCriteria();
                $criteria->addCondition("evde_edfi_fk = :evde_pk AND fuin_edfi_fk = :fuin_pk");
                $criteria->params = array(":evde_pk" => $idevaluacion, ":fuin_pk" => $idfi);
                $filas_fectadas = EvaluacionDesempenoFuenteInformacion::model()->updateAll(array("edfi_peso" => $pesofi),$criteria);
                if($filas_fectadas < 1 ){
                    echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
                    $transaction->rollback();
                    die();
                }
            }
            $criteria = new CDbCriteria();
            $criteria->select = "edfi_peso, evde_edfi_fk, fuin_edfi_fk";
            $criteria->distinct = true;
            $criteria->addCondition("evde_edfi_fk = :evde_pk ");
            $criteria->params = array(":evde_pk" => $idevaluacion);
            $edfi = EvaluacionDesempenoFuenteInformacion::model()->findAll($criteria);
            $suma_total = 0;
            foreach ($edfi as $fila) {
                $suma_total += (int)$fila->edfi_peso;
            }
            if($suma_total != 100){
                echo json_encode(array("state" => "error", "message" => "La suma de los pesos de la fuentes de información debe ser igual a 100%"));
                $transaction->rollback();
                die();
            }
            // Si todo sale bien, hago el commit. Si algo falla en el codigo de arriba, siempre se hace un die() que finaliza el escript, antecedido del rollback de la transacción.
            $transaction->commit();
            echo json_encode(array("state" => "success", "message" => "La evaluación de desempeño se ha editado con éxito.")); 
        }else{
            $evaluacionDesempeno = EvaluacionDesempeno::model()->findByPk($performanceevaluationid);
            if(!is_null($evaluacionDesempeno)){
                $aData = array();
                $aData['evaluacionDesempeno'] = $evaluacionDesempeno;
                $criteria = new CDbCriteria;
                $criteria->addCondition("evde_edfi_fk = :evde_pk");
                $criteria->select = 'fuin_edfi_fk';
                $criteria->distinct = true;
                $criteria->params = array(':evde_pk' => $evaluacionDesempeno->evde_pk);
                $criteria->order = 'fuin_edfi_fk ASC';
                $edfi = EvaluacionDesempenoFuenteInformacion::model()->findAll($criteria);
                foreach ($edfi as $row) {
                    $criteria = new CDbCriteria;
                    $criteria->addCondition("fuin_edfi_fk = :fuin_pk AND evde_edfi_fk = :evde_pk");
                    $criteria->params = array(':fuin_pk' => $row->fuin_edfi_fk, ":evde_pk" => $evaluacionDesempeno->evde_pk);
                    $criteria->order = 'fuin_edfi_fk ASC';
                    $edfi2 = EvaluacionDesempenoFuenteInformacion::model()->findAll($criteria);
                    $fuenteInformacion = array();
                    $i = 0;
                    foreach ($edfi2 as $row2) {
                        $fi = FuenteInformacion::model()->findByPk($row2->fuin_edfi_fk);
                        if(!is_null($fi)){
                            if(!isset($aData['plantilla'])){
                                $plantilla = PlantillaEvaluacion::model()->findByPk($fi->plev_fuin_fk);
                                $aData['plantilla'] = $plantilla;
                                $sql = "";
                                $nombre = "";
                                if($plantilla->plev_tipolabor == 1 ){
                                    $sql = "SELECT PROGRAMA FROM REPORTES.VW_REPORTES_FACULTADPROGRAMA WHERE PROG_ID = :ID_DEPENDENCIA";
                                    $nombre = "PROGRAMA";
                                }
                                else if($plantilla->plev_tipolabor == 3 ){
                                    $sql = "SELECT PROGRAMA FROM REPORTES.VW_REPORTES_FACULTADPROGRAMA WHERE UNID_DIRPROGRAMA = :ID_DEPENDENCIA";
                                    $nombre = "PROGRAMA";
                                    
                                }else if($plantilla->plev_tipolabor == 4){
                                    $sql = "SELECT UNID_NOMBRE FROM REPORTES.VW_REPORTES_FACULTADPROGRAMA WHERE UNID_ID = :ID_DEPENDENCIA";
                                    $nombre = "UNID_NOMBRE";
                                   
                                }
                                else if($plantilla->plev_tipolabor == 5){
                                    $sql = "SELECT UNID_NOMBRE FROM ACADEMICO.UNIDAD WHERE UNID_ID = :ID_DEPENDENCIA";
                                    $nombre = "UNID_NOMBRE";
                                }
                                if($sql != "" && $nombre != ""){
                                    $dependencia_evaluado = $evaluacionDesempeno->evde_dependenciaevaluado;
                                    $stmt = oci_parse($this->dbOracleConn, $sql);
                                    oci_bind_by_name($stmt, ":ID_DEPENDENCIA", $dependencia_evaluado);
                                    oci_execute($stmt);
                                    while ($entry = oci_fetch_assoc($stmt)) {
                                        $aData['dependencia'] = $entry[$nombre];
                                    }
                                }
                            }
                            $survey_model = Survey::model()->findByPk($fi->surv_fuin_fk);
                            $surveyinfo = $survey_model->getSurveyinfo();
                            $grupo_fi = Grupo::model()->findByPk($row2->grup_edfi_fk);
                            $fuenteInformacion[$i]['idfi'] = $fi->fuin_pk;
                            $fuenteInformacion[$i]['nombre_fi'] = $fi->fuin_nombre;
                            $fuenteInformacion[$i]['id_encuesta'] = $fi->surv_fuin_fk;
                            $fuenteInformacion[$i]['nombre_encuesta'] = $surveyinfo['surveyls_title'];
                            $fuenteInformacion[$i]['peso_fi'] = $row2->edfi_peso;
                            if(!is_null($grupo_fi) && $grupo_fi->surv_grup_fk != 0){
                                $survey_model = Survey::model()->findByPk($grupo_fi->surv_grup_fk);
                                $surveyinfo = $survey_model->getSurveyinfo();
                                $fuenteInformacion[$i]['id_grupo'] = $grupo_fi->grup_pk;
                                $fuenteInformacion[$i]['estado_grupo'] = $grupo_fi->grup_estado;
                                $fuenteInformacion[$i]['nombre_encuesta_clonada'] = $surveyinfo['surveyls_title'];
                                $fuenteInformacion[$i]['id_encuesta_clonada'] = $grupo_fi->surv_grup_fk;
                            }else{
                                $fuenteInformacion[$i]['id_grupo'] = 0;
                                $fuenteInformacion[$i]['estado_grupo'] = false;
                                $fuenteInformacion[$i]['id_encuesta_clonada'] = "SIN ENCUESTA CLONADA";
                            }
                            
                        }
                        $i++;
                    }
                    $aData['fi'][] = $fuenteInformacion;   
                }
                $this->_renderWrappedTemplate('teacherissues', 'viewPerformanceEvaluation_view', $aData);
            }else{
                Yii::app()->setFlashMessage(gT("Identificador de evaluación de desempeño no válido."), "error");
                $this->getController()->redirect(array("admin/teacherissues/sa/performanceevaluation"));
                die();
            }
        }
    }

    /**
     * Función que permite verificar si el estado de todos los grupos es falso, con 1 solo que sea verdadero devolverá false.
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 22/06/2016
     * @param  array  $gruposfi Grupos de las fuentes de información
     * @return boolean          Retorna true si todos los grupos están inhabilitados (estado false), de lo contrario retorna false.
     */
    private function _verificar_todos_grupos_inhabilitados($gruposfi = array()){
        foreach ($gruposfi as $gfi) {
            //echo "- ID ".$gfi->grup_id." Estado: ".$gfi->grup_estado;
            if(isset($gfi->grup_estado) && $gfi->grup_estado == "true"){
                return false;
            }
        }
        return true;
    }

    /**
     * Función que permite obtener los programas dad auna facultad y una plantilla, esta función es usada en la adición de nuevas evaluaciones de desempeño.
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 07/06/2016
     * @return void Imprime un objeto JSON con los programas de la facultad.
     */
    public function getprogramas(){
        if(isset($_POST, $_POST['idfacultad'], $_POST['idplantilla']) && trim($_POST['idfacultad']) != "" && trim($_POST['idplantilla']) != ""){
            $idfacultad = $_POST['idfacultad'];
            $idplantilla = $_POST['idplantilla'];
            $plantilla = PlantillaEvaluacion::model()->findByPk($idplantilla);
            if(!is_null($plantilla)){
                $sql = "SELECT DISTINCT PROGRAMA, UNID_DIRPROGRAMA, PROG_ID FROM REPORTES.VW_REPORTES_FACULTADPROGRAMA WHERE UNID_ID = :UNID_ID ORDER BY PROGRAMA";
                $stmt = oci_parse($this->dbOracleConn, $sql);
                oci_bind_by_name($stmt, ":UNID_ID", $idfacultad);
                oci_execute($stmt);
                $programas = "<option value='' >Por favor seleccione...</option>";
                // Si la plantilla es de Docente necesito el PROG_ID Y EL PROGRAMA  
                if($plantilla->plev_tipolabor == 1){
                    while ($entry = oci_fetch_assoc($stmt)) {
                        $programas.="<option value='".$entry['PROG_ID']."' >".$entry['PROGRAMA']."</option>";
                    }
                }
                // Si no es de Docente necesito el UNID_DIRPROGRAMA Y EL PROGRAMA
                else{
                    while ($entry = oci_fetch_assoc($stmt)) {
                        $programas.="<option value='".$entry['UNID_DIRPROGRAMA']."' >".$entry['PROGRAMA']."</option>";
                    }
                }
                echo json_encode(array("state" => "success", "html" => $programas));
                die();
            }
        }
        echo json_encode(array("state" => "error", "message" => "Facultad o plantilla seleccionada no válida."));
    }

    /**
     * Función que permite obtener toda la información relacionada con una plantilla de evaluación de desempeño.
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 07/06/2016
     * @return void Imprime un objeto JSON con las fuentes de información y toda la información asociada a la plantilla.
     */
    public function gettemplateinformation(){
        if(isset($_POST, $_POST['idplantilla']) && trim($_POST['idplantilla']) != ""){
            $idplantilla = $_POST['idplantilla'];
            $plantilla = PlantillaEvaluacion::model()->findByPk($idplantilla);
            if(!is_null($plantilla)){
                header('Content-Type: application/json');
                $criteria = new CDbCriteria();
                $criteria->addCondition("plev_fuin_fk = :plev_pk");
                $criteria->order = "fuin_pk ASC";
                $criteria->params = array(':plev_pk' => $plantilla->plev_pk);
                // Consulto las fuentes de información de la plantilla
                $fuentesinformacion = FuenteInformacion::model()->findAll($criteria);
                $respuesta = array();
                $i = 0;
                $fuente_json = array();
                foreach ($fuentesinformacion as $fi) {
                    $fuente['fuin_pk'] = $fi->fuin_pk;
                    $fuente['plev_fuin_fk'] = $fi->plev_fuin_fk;
                    $survey_model = Survey::model()->findByPk($fi->surv_fuin_fk);
                    $surveyinfo = $survey_model->getSurveyinfo();
                    $survey = array();
                    $survey['nombre'] = $surveyinfo['surveyls_title'];
                    $survey['surv_fuin_fk'] = $surveyinfo['sid'];
                    $fuente['survey'] = $survey;
                    $fuente['fuin_nombre'] = $fi->fuin_nombre;
                    $fuente['fuin_peso'] = $fi->fuin_peso;
                    $fuente['fuin_permitegrupos'] = $fi->fuin_permitegrupos;
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("fuin_inad_fk = :fuin_pk");
                    $criteria->order = "inad_pk ASC";
                    $criteria->params = array(':fuin_pk' => $fi->fuin_pk);
                    $consulta_ia = InformacionAdicional::model()->findAll($criteria);
                    $criteria = new CDbCriteria();
                    $criteria->select = 'question, qid';  
                    $criteria->condition = 'sid = :surveyid';
                    $criteria->addCondition('parent_qid = 0');
                    $criteria->params = array(':surveyid' => $fi->surv_fuin_fk);
                    $criteria->order = 'gid, question_order ASC'; 
                    $consulta_question = Question::model()->findAll($criteria);
                    $p = array();
                    foreach ($consulta_question as $q) {
                        $p[$q->qid] = viewHelper::flatEllipsizeText($q->question,true,90,'[...]',0.5);
                    }
                    $fuente['preguntas'] = $p;

                    foreach ($consulta_ia as $ia ) {
                        $ia_json['inad_pk'] = $ia->inad_pk;
                        $ia_json['fuin_inad_fk'] = $ia->fuin_inad_fk;
                        $ia_json['inad_nombre'] = $ia->inad_nombre;
                        $criteria = new CDbCriteria();
                        $criteria->addCondition("inad_inap_fk = :inad_pk");
                        $criteria->params = array(':inad_pk' => $ia->inad_pk);
                        $consulta_iap = InformacionAdicionalPregunta::model()->findAll($criteria);
                        $pregunta = array();
                        foreach ($consulta_question as $q) {
                            $pia = array();
                            $pia['id'] = $q->qid;
                            $pia['pregunta'] = viewHelper::flatEllipsizeText($q->question,true,90,'[...]',0.5);
                            $encontrada = false;
                            foreach ($consulta_iap as $iap) {
                                if($q->qid == $iap->ques_inap_fk){
                                    $pia['checked'] = 1;
                                    $encontrada = true;
                                    break;
                                }
                            }
                            if(!$encontrada){
                                $pia['checked'] = 0;
                            }
                            $pregunta[] = $pia;
                        }
                        $ia_json['preguntas'] = $pregunta;
                        $fuente['ia'][] = $ia_json;
                        $ia_json = array();
                    }
                    $fuente_json[$i] = $fuente;
                    $fuente = array();
                    $i++;
                }
                echo json_encode(array("state" => "success", "tipolabor" => $plantilla->plev_tipolabor, "fuentesinformacion" => $fuente_json));
                die();
            }    
        }
        echo json_encode(array("state" => "error", "message" => "Identificador de plantilla no válido"));  
    }

    /**
     * Función que permite eliminar una configuración de la plantilla pasada por parametro, la función verifica que la plantilla no haya sido usada en una evaluación de desempeño creada para poderla eliminar.
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 25/05/2016 
     * @param  int $templateid Identificador único de la plantilla de evaluación
     * @return void            Realiza la eliminación de la base de datos de la plantilla, redirige hacia la vista de las plantillas y envia un mensaje.
     */
    public function deleteconfigurationtemplate($templateid){
        $criteria = new CDbCriteria;
        $criteria->addCondition("plev_fuin_fk = :plev_pk");
        $criteria->params = array(":plev_pk" => $templateid);
        $fuentes = FuenteInformacion::model()->findAll($criteria);
        $existente = false;
        foreach ($fuentes as $fi) {
            $criteria = new CDbCriteria;
            $criteria->addCondition("fuin_edfi_fk = :fuin_pk");
            $criteria->params = array(":fuin_pk" => $fi->fuin_pk);
            $edfi = EvaluacionDesempenoFuenteInformacion::model()->findAll($criteria);
            if(sizeof($edfi) > 0){
                $existente = true;
                break;
            }
        }
        if(!$existente){
            PlantillaEvaluacion::model()->deleteByPk($templateid);
            Yii::app()->setFlashMessage(gT("La plantilla con id: ".$templateid." ha sido eliminada exitosamente."));
        }else{
            Yii::app()->setFlashMessage(gT("La plantilla con id: ".$templateid." no se ha podido eliminar porque se encuentra en uso en al menos 1 evaluación de desempeño."), "error");
        }
        $this->getController()->redirect(array('admin/teacherissues/sa/templateconfiguration'));
    }

    /**
     * Función que permite eliminar una evaluación de desempeño, la función verifica que la evaluación de desempeño no tenga encuestas activas con tabla de respuestas para poderla eliminar.
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 17/06/2016
     * @param  integer $performanceevaluationid Identificador único de la evaluación de desempeño.
     * @return void                             Redirecciona a una vista y muestra un mensaje (error o success).
     */
    public function deleteperformanceevaluation($performanceevaluationid){
        $evaluacionDesempeno = EvaluacionDesempeno::model()->findByPk($performanceevaluationid);
        if(!is_null($evaluacionDesempeno)){
            $criteria = new CDbCriteria;
            $criteria->addCondition("evde_edfi_fk = :evde_pk");
            $criteria->params = array(':evde_pk' => $evaluacionDesempeno->evde_pk);
            $edfi = EvaluacionDesempenoFuenteInformacion::model()->findAll($criteria);
            $encuesta_activa = false;
            foreach ($edfi as $row) {
                $grupo = Grupo::model()->findByPk($row->grup_edfi_fk);
                if(!is_null($grupo)){
                    $encuesta = $grupo->surv_grup_fk;
                    if(tableExists('survey_'.$encuesta)){
                        $encuesta_activa = true;
                        break;
                    }
                }
            }
            if(!$encuesta_activa){
                $transaction = Yii::app()->db->beginTransaction();
                try
                {   
                    EvaluacionDesempenoFuenteInformacion::model()->deleteAll($criteria);
                    foreach ($edfi as $row) {
                        $grupo = Grupo::model()->findByPk($row->grup_edfi_fk);
                        if($grupo->grup_pk == 0)
                            continue;
                        $grupo->delete();
                        Survey::model()->deleteSurvey($grupo->surv_grup_fk);
                    }
                    $evaluacionDesempeno->delete();
                    $transaction->commit();
                    Yii::app()->setFlashMessage(gT("Se ha eliminado exitosamente la evaluación de desempeño."), "success");
                    $this->getController()->redirect(array("admin/teacherissues/sa/performanceevaluation"));
                }
                catch(Exception $e){
                    $transaction->rollback();
                    Yii::app()->setFlashMessage(gT("Se ha producido un error inesperado al momento de eliminar la evaluación de desempeño, por favor inténtalo más tarde.".$e->getMessage()), "error");
                    $this->getController()->redirect(array("admin/teacherissues/sa/performanceevaluation"));
                }
            }else{
                Yii::app()->setFlashMessage(gT("No se puede eliminar la evaluación de desempeño porque existe al menos 1 encuesta activada."), "error");
                $this->getController()->redirect(array("admin/teacherissues/sa/performanceevaluation"));
                die();
            }
        }
    }

    /**
     * Función que permite ver la configuración de una fuente de información pasada por parametro
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 31/05/2016
     * @param  int $informationsourceid Identificador único de la fuente de información
     * @return void                     Redirige a la vista de la fuente de información
     */
    public function viewconfigurationinformationsources($informationsourceid){
        // Consulto la fuente de información
        $fi = FuenteInformacion::model()->findByPk($informationsourceid);
        // Valido que la fuente de información exista en bd
        if(!is_null($fi)){
            $aData = array();
            $criteria = new CDbCriteria();
            $criteria->addCondition("fuin_inad_fk = :fuin_pk");
            $criteria->params = array(':fuin_pk' => $fi->fuin_pk);
            // Consulto las informaciones adicionales de la fuente de información
            $consulta_ia = InformacionAdicional::model()->findAll($criteria);
            $criteria = new CDbCriteria();
            $criteria->select = 'question, qid';  
            $criteria->condition = 'sid = :surveyid';
            $criteria->addCondition('parent_qid = 0');
            $criteria->params = array(':surveyid' => $fi->surv_fuin_fk);
            $criteria->order = 'gid, question_order  ASC'; 
            // Consulto la encuesta asociada a la fuente de información
            $consulta_survey = Question::model()->findAll($criteria);
            $preguntas = array();
            // En los siguientes ciclos construyo la información de las preguntas (id, nombre), además se provee información para saber cuál pregunta está checkeada por las informaciones adicionales.
            foreach ($consulta_survey as $question) {
                $pregunta = array();
                $pregunta['pregunta'] = viewHelper::flatEllipsizeText($question->question,true,90,'[...]',0.5);
                $pregunta['id'] = $question->qid;
                foreach ($consulta_ia as $ia) {
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("inad_inap_fk = :inad_pk");
                    $criteria->params = array(':inad_pk' => $ia->inad_pk);
                    $consulta_iap = InformacionAdicionalPregunta::model()->findAll($criteria);
                    foreach ($consulta_iap as $iap) {
                        if($iap->ques_inap_fk == $question->qid){
                            $pregunta['seleccionado'.$ia->inad_pk] = true;
                            break;
                        }else{
                            $pregunta['seleccionado'.$ia->inad_pk] = false;
                        }
                    }
                }
                $preguntas[] = $pregunta;
            }
            $aData['fuenteinformacion'] = $fi;
            $aData['informacionadicional'] = $consulta_ia;
            $aData['preguntas'] = $preguntas;
            $this->_renderWrappedTemplate('teacherissues', 'viewInformationSource_view', $aData);
        }else{
            Yii::app()->setFlashMessage('El id de la fuente de información no es válido','error');
            $this->getController()->redirect(array('admin/teacherissues'));
        }
    }

    /**
     * Función que permite obtener las preguntas principales de una encuesta, es decir,
     * obtiene las preguntas cuyo id del padre es 0.
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 04/05/2016
     * @param  int $surveyid Identificador único de la encuesta.
     * @return JSON           Retorna un mensaje de success o error y la lista de preguntas en formato json si el estado es success.
     */
    public function getajaxquestions($surveyid){
        header('Content-Type: application/json');
        if (!is_null($surveyid) && !trim($surveyid) == "") {
            $criteria = new CDbCriteria;
            $criteria->select = 'question, qid';  
            $criteria->condition = 'sid = :surveyid';
            $criteria->addCondition('parent_qid = 0');
            $criteria->params = array(':surveyid' => $surveyid);
            $criteria->order = 'gid, question_order ASC'; 
            $post = Question::model()->findAll($criteria);
            foreach ($post as $question) {
                $pregunta['pregunta'] = viewHelper::flatEllipsizeText($question->question,true,90,'[...]',0.5);
                $pregunta['id'] = $question->qid;
                $preguntas[] = $pregunta;
            }
            echo json_encode(array("state" => "success", "questions" => $preguntas));
        }
        else{
            echo json_encode(array("state" => "error"));
        }
    }

    /**
     * Función que permite obtener la información de un evaluado desde academusoft, si la plantilla es para profesor se obtienen los grupos activos.
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 12/05/2016
     * @return void                     Imprime un objeto JSON con la información del evaluado.
     */
    public function getajaxinformationevaluated(){
        header('Content-Type: application/json');
        // Valido que como minimo exista: plantilla_evaluado, tipo_identificacion e identificacion
        if(isset($_POST, $_POST['plantilla_evaluado'], $_POST['tipo_identificacion'],$_POST['identificacion']) && trim($_POST['plantilla_evaluado']) != "" && trim($_POST['tipo_identificacion']) != "" && trim($_POST['identificacion']) != ""){
            $idplantilla = $_POST['plantilla_evaluado'];
            $tipo_identificacion = $_POST['tipo_identificacion'];
            $identificacion = $_POST['identificacion'];
            $plantilla = PlantillaEvaluacion::model()->findByPk($idplantilla);
            if(!is_null($plantilla)){
                $tipolabor = $plantilla->plev_tipolabor;
                $prog_id = null;
                $unid_id = null;
                $stmt = null;
                $consultaok = false;
                // DIRECTOR DE PROGRAMA - DECANO - VICERRECTOR
                if($plantilla->plev_tipolabor == 3 || $plantilla->plev_tipolabor == 4 || $plantilla->plev_tipolabor == 5){
                    // Si la plantilla es de director de programa debe existir la selección de la facultad y el programa
                    if($plantilla->plev_tipolabor == 3 && isset($_POST['facultad'], $_POST['programa']) && trim($_POST['facultad']) != "" && trim($_POST['programa']) != ""){
                        $unid_id = $_POST['programa'];
                    }
                    // Si la plantilla es de decano debe existir la selección de la facultad
                    if($plantilla->plev_tipolabor == 4 && isset($_POST['facultad']) && trim($_POST['facultad']) != "" ){
                        $unid_id = $_POST['facultad'];
                    }
                    // Si la plantilla es de vicerrector no es necesaria la facultad ni el programa
                    if ($plantilla->plev_tipolabor == 5) {
                        $unid_id = 0;
                    }
                    if(!is_null($unid_id)){
                        $sql = 'BEGIN REPORTES.PR_REPORTES_CONSULTADPDEVA(:TIDG_ID, :PEGE_DOCUMENTOIDENTIDAD, :LABO_ID, :UNID_ID, :CURSOR); END;';
                        $stmt = oci_parse($this->dbOracleConn, $sql);
                        oci_bind_by_name($stmt, ":LABO_ID", $tipolabor);
                        oci_bind_by_name($stmt, ":UNID_ID", $unid_id);
                        $consultaok = true;
                    }
                }
                // DOCENTE
                else if( $plantilla->plev_tipolabor == 1){
                    // Si la plantilla es de docente debe existir la selección de la facultad y el programa
                    if(isset($_POST['facultad'], $_POST['programa']) && trim($_POST['facultad']) != "" && trim($_POST['programa']) != "" ){
                        $prog_id = $_POST['programa'];
                        $sql = 'BEGIN REPORTES.PR_REPORTES_CONSDOCEGRUP(:TIDG_ID, :PEGE_DOCUMENTOIDENTIDAD, :PROG_ID, :CURSOR); END;';
                        $stmt = oci_parse($this->dbOracleConn, $sql);
                        oci_bind_by_name($stmt, ":PROG_ID", $prog_id);
                        $consultaok = true;
                    }
                }
                if($consultaok){
                    $cursor = oci_new_cursor($this->dbOracleConn);
                    oci_bind_by_name($stmt, ":TIDG_ID", $tipo_identificacion);
                    oci_bind_by_name($stmt, ":PEGE_DOCUMENTOIDENTIDAD", $identificacion);
                    oci_bind_by_name($stmt, ":CURSOR", $cursor,-1,SQLT_RSET);
                    oci_execute($stmt);
                    oci_execute($cursor);
                    $existente = false;
                    $resultado = array();
                    while ($fila = oci_fetch_assoc($cursor)) {
                        $existente = true;
                        $grupo = array();
                        //echo json_encode(array("fila" => $fila));
                        if (!array_key_exists("nombre", $resultado)) {
                            $resultado['nombre'] = $fila['NOMBRE'];
                        }
                        if($plantilla->plev_tipolabor == 1){
                            $grupo['grup_id'] = $fila['GRUP_ID'];
                            $grupo['mate_codigomateria'] = $fila['MATE_CODIGOMATERIA'];
                            $grupo['mate_nombre'] = $fila['MATE_NOMBRE'];
                            $grupo['grup_nombre'] = $fila['GRUP_NOMBRE'];
                            $resultado['grupos'][] = $grupo;
                        }
                    }
                    if($existente){
                        echo json_encode(array("state" => "success", "message" => "Documento encontrado" ,"datos" => $resultado));
                    }
                    else{
                        echo json_encode(array("state" => "error", "message" => "Documento no encontrado"));
                    }
                }else{
                    echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error, compruebe todos los datos."));
                }
            }    
        }
    }

    /**
     * Función que permite clonar una encuesta dada por parametro.
     * @access private
     * @param  integer $iSurveyID Identificador de la encuesta a clonar.
     * @param  string $titulo    Titulo que tendrá la nueva encuesta.
     * @return array             Retorna un arreglo con la información de la nueva encuesta.
     */
    private function copy_survey($iSurveyID, $titulo = "Titulo por defecto"){
        $importsurvey = "";
        $action = "copysurvey";
        $iSurveyID = //sanitize_int(Yii::app()->request->getParam('sid'));
        $iSurveyID = sanitize_int($iSurveyID);

        if ($action == "importsurvey" || $action == "copysurvey")
        {
            // Start traitment and messagebox
            $aData['bFailed'] = false; // Put a var for continue
            if ($action == 'copysurvey')
            {
                //$iSurveyID = sanitize_int(Yii::app()->request->getParam('copysurveylist'));
                $iSurveyID = $iSurveyID;
                $aExcludes = array();

                //$sNewSurveyName = Yii::app()->request->getPost('copysurveyname');titulo
                $sNewSurveyName = $titulo;
                //$aExcludes['quotas'] = true;
                //$aExcludes['permissions'] = true;
                //$aExcludes['answers'] = true;
                //$aExcludes['conditions'] = true;
                $aExcludes['dates'] = true;
                /*if (Yii::app()->request->getPost('copysurveyexcludequotas') == "on")
                {
                    $aExcludes['quotas'] = true;
                }
                if (Yii::app()->request->getPost('copysurveyexcludepermissions') == "on")
                {
                    $aExcludes['permissions'] = true;
                }
                if (Yii::app()->request->getPost('copysurveyexcludeanswers') == "on")
                {
                    $aExcludes['answers'] = true;
                }
                if (Yii::app()->request->getPost('copysurveyresetconditions') == "on")
                {
                    $aExcludes['conditions'] = true;
                }
                if (Yii::app()->request->getPost('copysurveyresetstartenddate') == "on")
                {
                    $aExcludes['dates'] = true;
                }*/
                if (!$iSurveyID)
                {
                    $aData['sErrorMessage'] = gT("No survey ID has been provided. Cannot copy survey");
                    $aData['bFailed'] = true;
                }
                elseif(!Survey::model()->findByPk($iSurveyID))
                {
                    $aData['sErrorMessage'] = gT("Invalid survey ID");
                    $aData['bFailed'] = true;
                }
                /*elseif (!Permission::model()->hasSurveyPermission($iSurveyID, 'surveycontent', 'export') && !Permission::model()->hasSurveyPermission($iSurveyID, 'surveycontent', 'export'))
                {
                    $aData['sErrorMessage'] = gT("We are sorry but you don't have permissions to do this.");
                    $aData['bFailed'] = true;
                }*/
                else
                {
                    Yii::app()->loadHelper('export');
                    $copysurveydata = surveyGetXMLData($iSurveyID, $aExcludes);
                }
            }

            // Now, we have the survey : start importing
            Yii::app()->loadHelper('admin/import');

            if ($action == 'copysurvey' && !$aData['bFailed'])
            {
                $aImportResults = XMLImportSurvey('', $copysurveydata, $sNewSurveyName, null);
                if (!isset($aExcludes['permissions']))
                {
                    Permission::model()->copySurveyPermissions($iSurveyID,$aImportResults['newsid']);
                }
            }
            else
            {
                $aData['bFailed'] = true;
            }

            if (!$aData['bFailed'])
            {
                $aData['action'] = $action;
                $aData['sLink'] = $this->getController()->createUrl('admin/survey/sa/view/surveyid/' . $aImportResults['newsid']);
                $aData['aImportResults'] = $aImportResults;
            }
        }
        //Yii::trace(CVarDumper::dumpAsString($aData), 'vardump');
        return $aData;
        //$this->_renderWrappedTemplate('survey', 'importSurvey_view', $aData);
    }

    /**
     * Función que permite exportar las estadísticas de la evaluación de desempeño
     * @param  int $performanceevaluationid Identificador único de la encuesta.
     * @return void           Muestra el pdf con las estadísticas de la evaluación docente.
     */
    public function exportperformanceevaluation($performanceevaluationid){
        //Yii::trace(CVarDumper::dumpAsString($variable), 'vardump');
        $evaluacionDesempeno = EvaluacionDesempeno::model()->findByPk($performanceevaluationid);
        if(!is_null($evaluacionDesempeno)){
            $identificacion_evaluado = $evaluacionDesempeno->evde_identificacionevaluado;
            // CONSULTO EL PEGE_ID Y TIDG_ID PARA PODER CONSULTAR LUEGO EL NOMBRE DE LA PERSONA
            $sql = "SELECT P.PEGE_ID, P.TIDG_ID FROM GENERAL.PERSONAGENERAL P WHERE P.PEGE_DOCUMENTOIDENTIDAD = :DOCUMENTOEVALUADO";
            $stmt = oci_parse($this->dbOracleConn, $sql);
            oci_bind_by_name($stmt, ":DOCUMENTOEVALUADO", $identificacion_evaluado);
            oci_execute($stmt);
            $evaluado = new stdClass();
            $evaluado->identificacionevaluado = $identificacion_evaluado;
            while ($fila = oci_fetch_assoc($stmt)) {
                $evaluado->pege_id = $fila['PEGE_ID'];
                $evaluado->tidg_id = $fila['TIDG_ID'];
            }
            // CONSULTO EL NOMBRE COMPLETO DE LA PERSONA DADO EL PEGE_ID DE LA CONSULTA ANTERIOR
            $sql = "SELECT P.PENG_PRIMERNOMBRE, P.PENG_SEGUNDONOMBRE, P.PENG_PRIMERAPELLIDO, P.PENG_SEGUNDOAPELLIDO FROM GENERAL.PERSONANATURALGENERAL P WHERE P.PEGE_ID = :PEGE_ID";
            $pege_id = $evaluado->pege_id;
            $stmt = oci_parse($this->dbOracleConn, $sql);
            oci_bind_by_name($stmt, ":PEGE_ID", $pege_id);
            oci_execute($stmt);
            while ($fila = oci_fetch_assoc($stmt)) {
                $evaluado->nombrecompleto = $fila['PENG_PRIMERNOMBRE']." ".$fila['PENG_SEGUNDONOMBRE']." ".$fila['PENG_PRIMERAPELLIDO']." ".$fila['PENG_SEGUNDOAPELLIDO'];
            }
            // CONSULTO EL TIPO DE PLANTILLA APLICADO AL EVALUADO 1 PARA DOCENTE 3 PARA DIRECTOR DE PROGRAMA, 4 PARA DECANO, 5 PARA VICERRECTOR
            $command = Yii::app()->db->createCommand("SELECT p.plev_tipolabor FROM plantillaevaluacion p, fuenteinformacion f, evaluaciondesempenofuenteinformacion edfi, evaluaciondesempeno WHERE p.plev_pk = f.plev_fuin_fk AND f.fuin_pk = edfi.fuin_edfi_fk AND edfi.evde_edfi_fk = :evde_pk");
            $command->bindParam(':evde_pk', $performanceevaluationid);
            $resultado = $command->queryRow();
            $existe_nombre_facultad = false;
            // VERIFICO EL TIPO DE LABOR PARA CREAR LA CONSULTA SQL Y PODER TRAER EL NOMBRE DE LA DEPENDENCIA
            if($resultado['plev_tipolabor'] == 1 ){
                $sql = "SELECT PROGRAMA, UNID_NOMBRE FROM REPORTES.VW_REPORTES_FACULTADPROGRAMA WHERE PROG_ID = :ID_DEPENDENCIA";
                $nombre = "PROGRAMA";
                $existe_nombre_facultad = true;
            }
            else if($resultado['plev_tipolabor'] == 3 ){
                $sql = "SELECT PROGRAMA, UNID_NOMBRE FROM REPORTES.VW_REPORTES_FACULTADPROGRAMA WHERE UNID_DIRPROGRAMA = :ID_DEPENDENCIA";
                $nombre = "PROGRAMA";
                $existe_nombre_facultad = true;
                
            }else if($resultado['plev_tipolabor'] == 4){
                $sql = "SELECT UNID_NOMBRE FROM REPORTES.VW_REPORTES_FACULTADPROGRAMA WHERE UNID_ID = :ID_DEPENDENCIA";
                $nombre = "UNID_NOMBRE";
               
            }
            else if($resultado['plev_tipolabor'] == 5){
                $sql = "SELECT UNID_NOMBRE FROM ACADEMICO.UNIDAD WHERE UNID_ID = :ID_DEPENDENCIA";
                $nombre = "UNID_NOMBRE";
            }
            if($sql != "" && $nombre != ""){
                $dependencia_evaluado = $evaluacionDesempeno->evde_dependenciaevaluado;
                $stmt = oci_parse($this->dbOracleConn, $sql);
                oci_bind_by_name($stmt, ":ID_DEPENDENCIA", $dependencia_evaluado);
                oci_execute($stmt);
                while ($fila = oci_fetch_assoc($stmt)) {
                    $evaluado->nombredependencia = $fila[$nombre];
                    if($existe_nombre_facultad){
                        $evaluado->nombrefacultad = $fila['UNID_NOMBRE'];
                    }
                    
                }
            }
            $sql = "SELECT PEUN_ANO, PEUN_PERIODO FROM ACADEMICO.PERIODOUNIVERSIDAD WHERE PEUN_FECHAINICIO <= :FECHA and :FECHA <= PEUN_FECHAFIN";
            $fechaevaluacion = $evaluacionDesempeno->evde_fechaevaluacion;
            $fechaevaluacion = strtotime(str_replace('-', '/', $fechaevaluacion));
            $fechaevaluacion = date("d/m/y", $fechaevaluacion);
            Yii::trace(CVarDumper::dumpAsString($fechaevaluacion), 'vardump');
            //die();
            $stmt = oci_parse($this->dbOracleConn, $sql);
            oci_bind_by_name($stmt, ":FECHA", $fechaevaluacion);
            oci_execute($stmt);
            while ($fila = oci_fetch_assoc($stmt)) {
                $evaluado->periodoevaluacion = $fila['PEUN_ANO']." - ".$fila['PEUN_PERIODO'];
            }
            Yii::app()->loadHelper('admin/statistics');
            $helper = new statistics_helper();
            $helper->generate_results_performance_evaluation($performanceevaluationid, $evaluado);
            exit;
        }
        //$surveyid = sanitize_int($surveyid);
        //no survey ID? -> come and get one
        //if (!isset($surveyid)) {
            //$surveyid=returnGlobal('sid');
        //}
        //$aData['surveyid'] = $surveyid;
        // Set language for questions and answers to base language of this survey
        //$language = Survey::model()->findByPk($surveyid)->language;
        //$aData['language'] = $language;
        //Select public language file
        //$row  = Survey::model()->find('sid = :sid', array(':sid' => $surveyid));
        //Yii::trace(CVarDumper::dumpAsString($row), 'vardump');
         //store all the data in $rows
        //$rows = Question::model()->getQuestionList($surveyid, $language);
         //SORT IN NATURAL ORDER!
        //usort($rows, 'groupOrderThenQuestionOrder');
        //Yii::trace(CVarDumper::dumpAsString($rows), 'vardump');
    }
}