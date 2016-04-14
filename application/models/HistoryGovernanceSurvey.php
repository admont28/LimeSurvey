<?php
if (!defined('BASEPATH'))
    die('No direct script access allowed');

/**
 * Modelo HistoryGovernanceSurvey que gestionará la tabla historygovernancesurvey en la base de datos,
 * En ella se almacenara el historial de cada una de las solicitudes de activación de encuestas.
 */
class HistoryGovernanceSurvey extends LSActiveRecord{

    /**
     * Atributo que almacenará el valor buscado cuando se realiza una búsqueda.
     * @var String 
     */
    public $searched_value;

    /**
    * Retorna el modelo estático de la tabla HistoryGovernanceSurvey
    *
    * @static
    * @access public
    * @param string $class
    * @return CActiveRecord
    */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }

	/**
    * Returna el nombre de la tabla
    *
    * @access public
    * @return string
    */
    public function tableName()
    {
        return '{{historygovernancesurvey}}';
    }

	/**
    * Retorna la llave primaria de la tabla
    *
    * @access public
    * @return string
    */
    public function primaryKey()
    {
        // governance history id
        return 'higosu_pk';
    }

	/**
    * Returna las reglas de validación de este modelo
    * @access public
    */
    public function rules()
    {
        return array(
            array('higosu_requestdate', 'default','value'=>date("Y-m-d")),
            array('higosu_modificationdate', 'default','value'=>date("Y-m-d")),
            array('higosu_requestresponse', 'default','value'=>"No se proporcionó una respuesta"),
			array('higosu_user_fk', 'default','value'=>NULL),
            array('higosu_requestjustification', 'default', 'value' => "No se propocionó una justificación"),
			array('higosu_requeststate', 'in','range'=>array('pendiente','aprobada','rechazada','requiere ajustes'), 'allowEmpty'=>false),
        );
    }

    /**
    * Retorna las relaciones de este modelo
    *
    * @access public
    * @return array
    */
    public function relations()
    {
        $alias = $this->getTableAlias();
        return array(
            'owner' => array(self::BELONGS_TO, 'User', 'higosu_users_fk'),
            'approval_user' => array(self::BELONGS_TO, 'User', 'higosu_user_fk'),
            'last_update' => array(self::BELONGS_TO, 'Governance', 'higosu_surv_fk'),
            // ????????
            // 'owner' => array(self::BELONGS_TO, 'User', '', 'on' => "$alias.owner_id = owner.uid"),

        );
    }

    /**
     * Función que permite realizar búsquedas en la tabla de governance history, este metodo es usado cuando se listan solicitudes de activación y se ingresa texto en el campo de búsqueda, se realizan búsquedas por los siguientes campos:
     * higosu_surv_fk
     * higosu_Requestjustification
     * higosu_Requestdate
     * higosu_Modificationdate
     * higosu_Requeststate
     * @return CActiveDataProvider Retorna un CActiveDataProvider con el resultado de la búsqueda
     */
    public function search($iSurveyID){
        $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);

        $criteria = new CDbCriteria;
        // Las variables ..._reference son usadas para evitar errores en la consulta con postgresql
        // El valor que venga por parametro get será convertido a varchar dentro de la consulta sql
        $higosu_requestdate_reference = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.higosu_requestdate::varchar' : 't.higosu_requestdate');
        $higosu_modificationdate_reference = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.higosu_modificationdate::varchar' : 't.higosu_modificationdate');
        // Se compara con cada uno de los distintas columnas de la bd
        $criteria->compare('higosu_requestjustification', $this->searched_value, true, 'OR');
        $criteria->compare('higosu_requeststate', $this->searched_value, true, 'OR');
        $criteria->compare('higosu_requestresponse', $this->searched_value, true, 'OR');
        $criteria->compare($higosu_requestdate_reference, $this->searched_value, true, 'OR');
        $criteria->compare($higosu_modificationdate_reference, $this->searched_value, true, 'OR');
        $criteria->addCondition('higosu_surv_fk = '.$iSurveyID);

        // Se crea un nuevo CDbcriteria para relacionar el higosu_users_fk y higosu_user_fk con el uid
        // de la tabla users, esto se hace para luego en la vista mostrar el nombre de la persona y no el id
        // higosu_user_fk es el que modifica el estado, un super admin
        // higosu_users_fk es el dueño de la encuesta
        $criteria2 = new CDbCriteria;
        $criteria2->select = array('*');
        $criteria2->join .= 'LEFT JOIN {{users}} AS users ON ( users.uid = t.higosu_users_fk AND users.uid = t.higosu_user_fk)';
        $criteria->mergeWith($criteria2, 'AND');
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder'=>'higosu_pk ASC'
                ),
            'pagination'=>array(
                'pageSize'=>$pageSize,
            ),
        ));
    }

} // Close HistoryGovernanceSurvey Class
?>