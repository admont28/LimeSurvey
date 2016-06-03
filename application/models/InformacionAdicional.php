<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');

/**
 * Modelo InformacionAdicional encargado de manejar la tabla InformacionAdicional en la base de datos,
 * En ella se almacenarán los nombres de las plantillas de evaluación que se han configurado en el sistema.
 */
class InformacionAdicional extends LSActiveRecord{

    /**
     * Atributo que almacenará el valor buscado cuando se realiza una búsqueda.
     * @var String 
     */
    public $searched_value;

    /**
    * Retorna el modelo estático de la tabla InformacionAdicional
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
        return '{{informacionadicional}}';
    }

    /**
    * Retorna la llave primaria de la tabla
    *
    * @access public
    * @return string
    */
    public function primaryKey()
    {
        return 'inad_pk';
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


} // Close InformacionAdicional Class
?>