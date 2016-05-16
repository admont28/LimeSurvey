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
    public function __construct($controller, $id)
    {
        parent::__construct($controller, $id);
    }

    /**
    * Loads list of surveys and it's few quick properties.
    *
    * @access public
    * @return void
    */
    public function index()
    {
        $this->getController()->redirect(array('admin/teacherissues/sa/listtemplates'));
    }

    public function listtemplates(){
        echo "hola 3";
    }

    /**
     * Función que permite configurar la plantilla para evaluación de desempeño
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 04/05/2016
     * @return [type] [description]
     */
    public function templateconfiguration(){
        //Yii::trace(CVarDumper::dumpAsString(Yii::app()->request->getPost()), 'vardump');
        $aData = array();
        Yii::trace(CVarDumper::dumpAsString($_POST), 'vardump');
        $this->_renderWrappedTemplate('teacherissues', 'templateConfiguration_view', $aData);
    }

    /**
     * [performanceEvaluation description]
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 12/05/2016
     * @return [type] [description]
     */
    public function performanceEvaluation(){
    	$aData = array();
        Yii::trace(CVarDumper::dumpAsString($_POST), 'vardump');
        $this->_renderWrappedTemplate('teacherissues', 'performanceEvaluation_view', $aData);
    }

    /**
     * Función que permite verificar si los datos en el arreglo post existen y no estan vacíos.
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 04/05/2016
     * @param  array  $aData Datos a verificar, debe ser el contenido en la etiqueta name
     * @return boolean        Retorna true en caso de que existan todos los datos y no estén vacíos, de lo contrario retorna false.
     */
    private function _verify_data_post($aData = array()){
        if(isset($_POST)){
            foreach ($aData as $key) {
                if(isset($_POST[$key])){
                    if(empty($_POST[$key]) || trim($_POST[$key]) == ""){
                        return false;
                    }
                }
                else return false;
            }
            return true;
        }else return false;
    }

    /**
     * Función que permite obtener las preguntas principales de una encuesta, es decir,
     * obtiene las preguntas cuyo id del padre es 0.
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 04/05/2016
     * @param  int $surveyid Identificador único de la encuesta.
     * @return json           Retorna un mensaje de success o error y la lista de preguntas en formato json si el estado es success.
     */
    public function getajaxquestions($surveyid){
        header('Content-Type: application/json');
        if (!is_null($surveyid) && !trim($surveyid) == "") {
             $criteria = new CDbCriteria;
            $criteria->select = 'question';  
            $criteria->condition = 'sid = :surveyid';
            $criteria->addCondition('parent_qid = 0');
            $criteria->params = array(':surveyid' => $surveyid);
            $criteria->order = 'question_order,gid ASC'; 
            $post = Question::model()->findAll($criteria);
            $preguntas = array();
            foreach ($post as $question) {
                $preguntas[] = viewHelper::flatEllipsizeText($question->question,true,90,'[...]',0.5);
            }
            echo json_encode(array("state" => "success", "questions" => $preguntas));
        }
        else{
            echo json_encode(array("state" => "error"));
        }
    }
}