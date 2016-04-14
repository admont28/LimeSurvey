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
class Index extends Survey_Common_Action
{

    public function run()
    {
        App()->loadHelper('surveytranslator');
        $aData['issuperadmin'] = false;
        if (Permission::model()->hasGlobalPermission('superadmin','read'))
        {
            $aData['issuperadmin'] = true;
        }

        // We get the last survey visited by user
        $setting_entry = 'last_survey_'.Yii::app()->user->getId();
        $lastsurvey = getGlobalSetting($setting_entry);
        $survey = Survey::model()->findByPk($lastsurvey);
        if( $lastsurvey != null && $survey)
        {
            $aData['showLastSurvey'] = true;
            $iSurveyID = $lastsurvey;
            $surveyinfo = $survey->surveyinfo;
            $aData['surveyTitle'] = $surveyinfo['surveyls_title']."(".gT("ID").":".$iSurveyID.")";
            $aData['surveyUrl'] = $this->getController()->createUrl("admin/survey/sa/view/surveyid/{$iSurveyID}");
        }
        else
        {
            $aData['showLastSurvey'] = false;
        }

        // We get the last question visited by user
        $setting_entry = 'last_question_'.Yii::app()->user->getId();
        $lastquestion = getGlobalSetting($setting_entry);

        // the question group of this question
        $setting_entry = 'last_question_gid_'.Yii::app()->user->getId();
        $lastquestiongroup = getGlobalSetting($setting_entry);

        // the sid of this question : last_question_sid_1
        $setting_entry = 'last_question_sid_'.Yii::app()->user->getId();
        $lastquestionsid = getGlobalSetting($setting_entry);
        $survey = Survey::model()->findByPk($lastquestionsid);
        if( $lastquestion && $lastquestiongroup && $survey)
        {

            $baselang = $survey->language;
            $aData['showLastQuestion'] = true;
            $qid = $lastquestion;
            $gid = $lastquestiongroup;
            $sid = $lastquestionsid;
            $qrrow = Question::model()->findByAttributes(array('qid' => $qid, 'gid' => $gid, 'sid' => $sid, 'language' => $baselang));
            if($qrrow)
            {
                $aData['last_question_name'] = $qrrow['title'];
                if($qrrow['question'])
                {
                    $aData['last_question_name'] .= ' : '.$qrrow['question'];
                }
                $aData['last_question_link'] = $this->getController()->createUrl("admin/questions/sa/view/surveyid/$sid/gid/$gid/qid/$qid");
            }
            else
            {
                $aData['showLastQuestion'] = false;
            }
        }
        else
        {
           $aData['showLastQuestion'] = false;
        }

        $aData['countSurveyList'] = count(getSurveyList(true));
        // We get the home page display setting
        $aData['bShowLogo'] = (getGlobalSetting('show_logo')=="show");
        $aData['bShowLastSurveyAndQuestion'] = (getGlobalSetting('show_last_survey_and_question')=="show");
        $aData['iBoxesByRow']=(int) getGlobalSetting('boxes_by_row');
        $aData['sBoxesOffSet']=(string) getGlobalSetting('boxes_offset');
        /*
         * ----------------------------------------------------------------------------------------------
         * ADICIÓN DE CÓDIGO REALIZADA POR: ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 10/04/2016
         * Número de líneas: 6
         * Se captura el id del usuario logueado.
         * Se crea un CDBCriteria para agregar una condición a la consulta de la base de datos, donde se trae todas las encuestas del usuario logueado.
         * Si el usuario logueado tiene al menos 1 encuesta creada entonces no se muestra el mensaje de políticas de gesen-uq, si no posee ninguna encuesta, se muestra el mensaje de políticas de gesen-uq.
         * ----------------------------------------------------------------------------------------------
         */
        $loginID = Yii::app()->session['loginID'];
        $criteria = new CDbCriteria();
        $criteria->addCondition("owner_id=:owner");
        $criteria->params = array(':owner' => $loginID);
        $surveys_user_logged = Survey::model()->findAll($criteria);
        $aData['show_politices'] = isset($surveys_user_logged) && sizeof($surveys_user_logged) == 0;
        $this->_renderWrappedTemplate('super', 'welcome', $aData);
    }

}
