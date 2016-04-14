<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');

/**
 * Modelo Governance encargado de manejar la tabla governancesurvey en la base de datos,
 * En ella se almacenarán las solicitudes de activación de encuestas.
 */
class GovernanceSurvey extends LSActiveRecord{

    /**
     * Atributo que almacenará el valor buscado cuando se realiza una búsqueda.
     * @var String 
     */
    public $searched_value;

    /**
    * Retorna el modelo estático de la tabla Governance
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
        return '{{governancesurvey}}';
    }

	/**
    * Retorna la llave primaria de la tabla
    *
    * @access public
    * @return string
    */
    public function primaryKey()
    {
        return 'gosu_pk';
    }

	/**
    * Returna las reglas de validación de este modelo
    * @access public
    */
    public function rules()
    {
        return array(
            array('gosu_requestdate', 'default','value'=>date("Y-m-d")),
            array('gosu_modificationdate', 'default','value'=>NULL),
			array('gosu_requestresponse', 'default','value'=>NULL),
			array('gosu_user_fk', 'default','value'=>NULL),
            array('gosu_requestjustification', 'default', 'value' => "No se propocionó una justificación"),
			array('gosu_requeststate', 'in','range'=>array('pendiente','aprobada','rechazada','requiere ajustes'), 'allowEmpty'=>false),
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
            'owner' => array(self::BELONGS_TO, 'User', 'govsur_user_fk'),
            'approval_user' => array(self::BELONGS_TO, 'User', 'gosu_user_fk'),
        );
    }

    /**
     * Función que permite realizar búsquedas en la tabla de governance, este metodo es usado cuando se listan solicitudes de activación y se ingresa texto en el campo de búsqueda, se realizan búsquedas por los siguientes campos:
     * gosu_pk
     * gosu_Requestjustification
     * gosu_Requestdate
     * gosu_Modificationdate
     * gosu_Requeststate
     * @return CActiveDataProvider Retorna un CActiveDataProvider con el resultado de la búsqueda
     */
    public function search(){
        $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);
        $criteria = new CDbCriteria;
        // Las variables ..._reference son usadas para evitar errores en la consulta con postgresql
        // El valor que venga por parametro get será convertido a varchar dentro de la consulta sql
        $gosu_pk_reference = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.gosu_pk::varchar' : 't.gosu_pk');
        $gosu_requestdate_reference = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.gosu_requestdate::varchar' : 't.gosu_requestdate');
        $gosu_modificationdate_reference = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.gosu_modificationdate::varchar' : 't.gosu_modificationdate');
        // Se compara con cada uno de los distintas columnas de la bd
        $criteria->compare($gosu_pk_reference, $this->searched_value, true, 'OR');
        $criteria->compare('gosu_requestjustification', $this->searched_value, true, 'OR');
        $criteria->compare('gosu_requestresponse', $this->searched_value, true, 'OR');
        $criteria->compare($gosu_requestdate_reference, $this->searched_value, true, 'OR');
        $criteria->compare($gosu_modificationdate_reference, $this->searched_value, true, 'OR');
        $criteria->compare('gosu_requeststate', $this->searched_value, true, 'OR');

        $loginID = Yii::app()->session['loginID'];

        // Se crea un nuevo CDbcriteria para relacionar el govsur_user_fk y govsur_user_fk con el uid
        // de la tabla users, esto se hace para luego en la vista mostrar el nombre de la persona y no el id
        $criteria2 = new CDbCriteria;
        $criteria2->select = array('*');
        $criteria2->join .= 'LEFT JOIN {{users}} AS users ON ( users.uid = t.govsur_user_fk AND users.uid = t.govsur_user_fk)';
        
        // Si no es super administrador se agrega la condición a la consulta para mostrar solo las 
        // solicitudes hechas por el usuario actual.
        if(!Permission::model()->hasGlobalPermission('superadmin','read',$loginID)){
            $criteria->addCondition('govsur_user_fk = '.$loginID);
        }
        $criteria->mergeWith($criteria2, 'AND');
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder'=>'gosu_requeststate DESC'
                ),
            'pagination'=>array(
                'pageSize'=>$pageSize,
            ),
        ));
    }
} // Close GovernanceSurvey Class
?>