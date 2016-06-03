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
        //Yii::trace(CVarDumper::dumpAsString(Yii::app()->request->getPost()), 'vardump');
        $aData = array();
        if(isset($_POST['nombre_plantilla']) && trim($_POST['nombre_plantilla']) != ""){
            $plantillaEvaluacion = new PlantillaEvaluacion;
            $plantillaEvaluacion->plev_nombre = $_POST['nombre_plantilla'];
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
                            echo json_encode(array("state" => "success", "fuin_pk" => $idFuenteInformacion));
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
     * NO SE ENCUENTRA EN FUNCIONAMIENTO, AÚN NO HA SIDO PROBADA
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 12/05/2016
     * @return [type] [description]
     */
    public function performanceEvaluation(){
    	$aData = array();
        Yii::trace(CVarDumper::dumpAsString($_POST), 'vardump');
        // Creo la conexión con oracle
        $oci = Yii::app()->dbOracle;
        // Creo el comando con la consulta para obtener el PEGE_ID Y EL TIDG_ID
        $row = Yii::app()->dbOracle->createCommand()
            ->select('TIDG_ID, TIDG_DESCRIPCION')
            ->from('GENERAL.TIPODOCUMENTOGENERAL T')
            ->queryAll();
        $tipo_documentos = array();
        foreach ($row as $llave => $valor) {
            $tipo_documentos[$valor['TIDG_ID']] = $valor['TIDG_DESCRIPCION'];
        }
        $aData['tipo_documentos'] = $tipo_documentos;
        $this->_renderWrappedTemplate('teacherissues', 'performanceEvaluation_view', $aData);
    }

    /**
     * Función que permite eliminar una configuración de la plantilla pasada por parametro.
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 25/05/2016 
     * @param  int $templateid Identificador único de la plantilla de evaluación
     * @return void            Realiza la eliminación de la base de datos de la plantilla, redirige hacia la vista de las plantillas y envia un mensaje.
     */
    public function deleteconfigurationtemplate($templateid){
        // TODO: verificar que la plantilla no haya sido usada en una aplicación de evaluación de desempeño, si ya ha sido usada no se podrá eliminar de la base de datos.
        PlantillaEvaluacion::model()->deleteByPk($templateid);
        Yii::app()->setFlashMessage(gT("La plantilla con id: ".$templateid." ha sido eliminada exitosamente."));
        $this->getController()->redirect(array('admin/teacherissues/sa/templateconfiguration'));
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
            $criteria->order = 'question_order,gid ASC'; 
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
            $criteria->order = 'question_order,gid ASC'; 
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
     * NO SE ENCUENTRA EN FUNCIONAMIENTO, AÚN NO HA SIDO PROBADA
     * @access public
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 12/05/2016
     * @param  [type] $tipoidentificacion [description]
     * @param  [type] $identificacion     [description]
     * @return [type]                     [description]
     */
    public function getajaxinformationevaluated($tipoidentificacion, $identificacion){
        header('Content-Type: application/json');
        // Número de identifiación nulo
        if(is_null($identificacion))
            echo json_encode(array("state" => "error", "message" => "Número de identificación no válido"));
        // Número de identifiación vacío
        elseif (trim($identificacion) == "")
            echo json_encode(array("state" => "error", "message" => "Número de identificación no válido"));
        // Número de identifiación no vacío y no nulo, procedo a consultar
        else{
            // Creo la conexión con oracle 
            $oci = Yii::app()->dbOracle;
            // Creo el comando con la consulta para obtener el PEGE_ID Y EL TIDG_ID
            $row = Yii::app()->dbOracle->createCommand()
            ->select('PEGE_ID, TIDG_ID')
            ->from('GENERAL.PERSONAGENERAL P')
            ->where('P.PEGE_DOCUMENTOIDENTIDAD = :DOCUMENTOIDENTIDAD AND P.TIDG_ID = :TIDG_ID', array(':DOCUMENTOIDENTIDAD'=>$identificacion, ':TIDG_ID' => $tipoidentificacion))
            ->queryAll();
            // No existe ningún resultado
            if(!$this->_verify_array($row)){
                echo json_encode(array("state" => "error", "message" => "Número de documento no encontrado"));
                return;
            }else{
              
                $pege_id = $row[0]['PEGE_ID'];
                $tigd_id = $row[0]['TIDG_ID'];
                // Creo el comando con la consulta para obtener el nombre y el apellido
                $row = Yii::app()->dbOracle->createCommand()
                ->select('PENG_PRIMERNOMBRE, PENG_SEGUNDONOMBRE, PENG_PRIMERAPELLIDO, PENG_SEGUNDOAPELLIDO')
                ->from('GENERAL.PERSONANATURALGENERAL P')
                ->where('P.PEGE_ID = :PEGE_ID', array(':PEGE_ID'=>$pege_id))
                ->queryAll();
                if(!$this->_verify_array($row)){
                    echo json_encode(array("state" => "error4"));
                    return;
                }else{
                    $peng_primernombre      = $row[0]['PENG_PRIMERNOMBRE'];
                    $peng_segundonombre     = $row[0]['PENG_SEGUNDONOMBRE'];
                    $peng_primerapellido    = $row[0]['PENG_PRIMERAPELLIDO'];
                    $peng_segundoapellido   = $row[0]['PENG_SEGUNDOAPELLIDO'];
                    // Creo el comando con la consulta para obtener el tipo de identificación
                    $row = Yii::app()->dbOracle->createCommand()
                    ->select('TIDG_DESCRIPCION')
                    ->from('GENERAL.TIPODOCUMENTOGENERAL T')
                    ->where('T.TIDG_ID = :TIDG_ID', array(':TIDG_ID'=>$tigd_id))
                    ->queryAll();
                    if(!$this->_verify_array($row)){
                        echo json_encode(array("state" => "error5"));
                        return;
                    }else{
                        $data = array();
                        $data['primernombre'] = $peng_primernombre;
                        $data['segundonombre'] = $peng_segundonombre;
                        $data['primerapellido'] = $peng_primerapellido;
                        $data['segundoapellido'] = $peng_segundoapellido;
                        $data['tipoidentificacion'] = $row[0]['TIDG_DESCRIPCION'];
                        echo json_encode(array("state" => "success", "message" => "Documento encontrado", "person" => $data));
                    }
                }
            }
        }
        /*
        $rowCount=$command->execute();   // ejecuta una sentencia SQL sin resultados
        $dataReader=$command->query();   // ejecuta una consulta SQL
        $rows=$command->queryAll();      // consulta y devuelve todas las filas de resultado
        $row=$command->queryRow();       // consulta y devuelve la primera fila de resultado
        $column=$command->queryColumn(); // consulta y devuelve la primera columna de resultado
        $value=$command->queryScalar(); 
        $sql = "SELECT * FROM GENERAL.PERSONAGENERAL P WHERE P.PEGE_DOCUMENTOIDENTIDAD = '41947221'";
        $command = $oci->createCommand($sql);       
        $dataReader = $command->query();
        foreach($dataReader as $row) {
            Yii::trace(CVarDumper::dumpAsString($row), 'vardump');
        }*/
    }

    private function _verify_array($arreglo = array()){
        if(empty($arreglo) || sizeof($arreglo) > 1){
            return false;
        }
        else if(sizeof($arreglo) == 1)
            return true;
    }

    /**
     * FUNCIÓN DE PRUEBA, SE ELIMINARÁ LUEGO
     * @param  [type] $identificacion [description]
     * @return [type]                 [description]
     */
    public function probar($identificacion){
        // Número de identifiación nulo
        if(is_null($identificacion))
            echo json_encode(array("state" => "error1"));
        // Número de identifiación vacío
        elseif (trim($identificacion) == "")
            echo json_encode(array("state" => "error2"));
        // Número de identifiación no vacío y no nulo, procedo a consultar
        else{
            // Creo la conexión con oracle
            $oci = Yii::app()->dbOracle;
            // Creo el comando con la consulta para obtener el PEGE_ID Y EL TIDG_ID
            $row = Yii::app()->dbOracle->createCommand()
            ->select('PEGE_ID, TIDG_ID')
            ->from('GENERAL.PERSONAGENERAL P')
            ->where('P.PEGE_DOCUMENTOIDENTIDAD = :DOCUMENTOIDENTIDAD', array(':DOCUMENTOIDENTIDAD'=>$identificacion));
            
            // No existe ningún resultado
            Yii::trace(CVarDumper::dumpAsString($row), 'vardump');
            $row = $row->queryAll();
            if(!$this->_verify_array($row)){
                echo json_encode(array("state" => "error3"));
                Yii::trace(CVarDumper::dumpAsString($row), 'vardump');
                return;
            }else{
                Yii::trace(CVarDumper::dumpAsString($row), 'vardump');
                $pege_id = $row[0]['PEGE_ID'];
                $tigd_id = $row[0]['TIDG_ID'];
                // Creo el comando con la consulta para obtener el nombre y el apellido
                $row = Yii::app()->dbOracle->createCommand()
                ->select('PENG_PRIMERNOMBRE, PENG_SEGUNDONOMBRE, PENG_PRIMERAPELLIDO, PENG_SEGUNDOAPELLIDO')
                ->from('GENERAL.PERSONANATURALGENERAL P')
                ->where('P.PEGE_ID = :PEGE_ID', array(':PEGE_ID'=>$pege_id))
                ->queryAll();
                if(!$this->_verify_array($row)){
                    echo json_encode(array("state" => "error4"));
                    return;
                }else{
                    $peng_primernombre      = $row[0]['PENG_PRIMERNOMBRE'];
                    $peng_segundonombre     = $row[0]['PENG_SEGUNDONOMBRE'];
                    $peng_primerapellido    = $row[0]['PENG_PRIMERAPELLIDO'];
                    $peng_segundoapellido   = $row[0]['PENG_SEGUNDOAPELLIDO'];
                    // Creo el comando con la consulta para obtener el tipo de identificación
                    $row = Yii::app()->dbOracle->createCommand()
                    ->select('TIDG_DESCRIPCION')
                    ->from('GENERAL.TIPODOCUMENTOGENERAL T')
                    ->where('T.TIDG_ID = :TIDG_ID', array(':TIDG_ID'=>$tigd_id))
                    ->queryAll();
                    if(!$this->_verify_array($row)){
                        echo json_encode(array("state" => "error5"));
                        return;
                    }else{
                        echo json_encode(array("state" => "success"));
                    }
                }
            }
        }
    }
}