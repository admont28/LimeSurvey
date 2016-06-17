<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');

/**
 * Modelo Grupo encargado de manejar la tabla Grupo en la base de datos,
 * En ella se almacenarán los grupos de las evaluaciones de desempeño realizadas.
 * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 13/06/2016
 */
class Grupo extends LSActiveRecord{

    /**
     * Atributo que almacenará el valor buscado cuando se realiza una búsqueda.
     * @var String 
     */
    public $searched_value;

    /**
    * Retorna el modelo estático de la tabla Grupo
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
        return '{{grupo}}';
    }

    /**
    * Retorna la llave primaria de la tabla
    *
    * @access public
    * @return string
    */
    public function primaryKey()
    {
        return 'grup_pk';
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
    
} // Close Grupo Class
?>