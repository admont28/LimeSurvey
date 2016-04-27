<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');

/**
 * Modelo DeletedSurvey encargado de manejar la tabla DeletedSurvey en la base de datos,
 * En ella se almacenarán las encuestas eliminadas.
 */
class DeletedSurvey extends LSActiveRecord{

    /**
     * Atributo que almacenará el valor buscado cuando se realiza una búsqueda.
     * @var String 
     */
    public $searched_value;

    /**
    * Retorna el modelo estático de la tabla DeletedSurvey
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
    * Retorna el nombre de la tabla
    *
    * @access public
    * @return string
    */
    public function tableName()
    {
        return '{{deletedsurvey}}';
    }

	/**
    * Retorna la llave primaria de la tabla
    *
    * @access public
    * @return string
    */
    public function primaryKey()
    {
        return 'desu_pk';
    }

	/**
    * Returna las reglas de validación de este modelo
    * @access public
    */
    public function rules()
    {
        return array(
            array('desu_deleteddate', 'default','value'=>date("Y-m-d")),
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
            'owner_survey' => array(self::BELONGS_TO, 'User', 'delsur_user_fk'),
            'eliminator_survey' => array(self::BELONGS_TO, 'User', 'desu_user_fk'),
        );
    }

} // Close DeletedSurvey Class
?>