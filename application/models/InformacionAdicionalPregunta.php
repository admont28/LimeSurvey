<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');

/**
 * Modelo InformacionAdicionalPregunta encargado de manejar la tabla InformacionAdicionalPregunta en la base de datos,
 * En ella se almacenarán los nombres de las plantillas de evaluación que se han configurado en el sistema.
 */
class InformacionAdicionalPregunta extends LSActiveRecord{

    /**
     * Atributo que almacenará el valor buscado cuando se realiza una búsqueda.
     * @var String 
     */
    public $searched_value;

    /**
    * Retorna el modelo estático de la tabla InformacionAdicionalPregunta
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
        return '{{informacionadicionalpregunta}}';
    }

    /**
    * Retorna la llave primaria de la tabla
    *
    * @access public
    * @return string
    */
    public function primaryKey()
    {
        return array('inad_inap_fk', 'ques_inap_fk');
    }

    /**
    * Returna las reglas de validación de este modelo
    * @access public
    */
    public function rules()
    {
        return array(
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
        );
    }


} // Close InformacionAdicionalPregunta Class
?>