<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');

/**
 * Modelo EvaluacionDesempeno encargado de manejar la tabla EvaluacionDesempeno en la base de datos,
 * En ella se almacenarán las evaluaciones de desempeño realizadas.
 * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 03/06/2016
 */
class EvaluacionDesempeno extends LSActiveRecord{

    /**
     * Atributo que almacenará el valor buscado cuando se realiza una búsqueda.
     * @var String 
     */
    public $searched_value;

    /**
    * Retorna el modelo estático de la tabla EvaluacionDesempeno
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
        return '{{evaluaciondesempeno}}';
    }

    /**
    * Retorna la llave primaria de la tabla
    *
    * @access public
    * @return string
    */
    public function primaryKey()
    {
        return 'evde_pk';
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

    /**
     * Función que permite obtener los botones con los enlaces para una de evaluación de desempeño
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 03/06/2016
     * @return String Retorna un string con los botones construidos en etiquetas html
     */
    public function getbuttons(){
        //$adicionarFI  = App()->createUrl("/admin/teacherissues/sa/configurationinformationsource/templateid/".$this->plev_pk);
        $eliminarEvaluacion = App()->createUrl("/admin/teacherissues/sa/deleteperformanceevaluation/performanceevaluationid/".$this->evde_pk);

        //$button = '<a class="btn btn-default" href="'.$adicionarFI.'" role="button" data-toggle="tooltip" title="'.gT('Adicionar fuentes de información').'"><span class="icon-add text-success" ></span></a>';
        $button = '<a class="btn btn-default eliminar" href="'.$eliminarEvaluacion.'" role="button" data-toggle="tooltip" title="'.gT('La evaluación de desempeño si no se ha aplicado aún.').'"><span class="text-danger glyphicon glyphicon-trash" ></span></a>';

        return $button;
    }

    /**
     * Función que permite realizar búsquedas de evaluaciones de desempeño.
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 03/06/2016
     * @return CActiveDataProvider Retorna un CActiveDataProvider con el resultado final de la búsqueda ordenado por el campo evde_pk ascendentemente.
     */
    public function search(){
        $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);
        $sort = new CSort();
        $sort->attributes = array(
          'evde_pk'=>array(
            'asc'=>'evde_pk',
            'desc'=>'evde_pk desc',
          ),
          'evde_pk'=>array(
            'asc'=>'evde_pk',
            'desc'=>'evde_pk desc',
          ),
        );

        $criteria = new CDbCriteria;
        // Las variables ..._reference son usadas para evitar errores en la consulta con postgresql
        // El valor que venga por parametro get será convertido a varchar dentro de la consulta sql
        
        $evde_pk_reference = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.evde_pk::varchar' : 't.evde_pk');
        $evde_fechaevaluacion_reference = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.evde_fechaevaluacion::varchar' : 't.evde_fechaevaluacion');
        $evde_identificacionevaluado = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.evde_identificacionevaluado::varchar' : 't.evde_identificacionevaluado');
        // Se compara con cada uno de los distintas columnas de la bd
        $criteria->compare($evde_pk_reference, $this->searched_value, true, 'OR');
        $criteria->compare($evde_identificacionevaluado, $this->searched_value, true, 'OR');
        $criteria->compare($evde_fechaevaluacion_reference, $this->searched_value, true, 'OR');
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder'=>'evde_pk ASC'
                ),
            'pagination'=>array(
                'pageSize'=>$pageSize,
            ),
        ));
    }

} // Close EvaluacionDesempeno Class
?>