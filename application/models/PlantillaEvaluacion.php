<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');

/**
 * Modelo PlantillaEvaluacion encargado de manejar la tabla PlantillaEvaluacion en la base de datos,
 * En ella se almacenarán los nombres de las plantillas de evaluación que se han configurado en el sistema.
 */
class PlantillaEvaluacion extends LSActiveRecord{

    /**
     * Atributo que almacenará el valor buscado cuando se realiza una búsqueda.
     * @var String 
     */
    public $searched_value;

    /**
    * Retorna el modelo estático de la tabla PlantillaEvaluacion
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
        return '{{plantillaevaluacion}}';
    }

    /**
    * Retorna la llave primaria de la tabla
    *
    * @access public
    * @return string
    */
    public function primaryKey()
    {
        return 'plev_pk';
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
     * Función que permite obtener los botones con los enlaces para una plantilla de evaluación
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 31/05/2016
     * @return String Retorna un string con los botones construidos en etiquetas html
     */
    public function getbuttons(){
        $adicionarFI  = App()->createUrl("/admin/teacherissues/sa/configurationinformationsource/templateid/".$this->plev_pk);
        $eliminarPlantilla = App()->createUrl("/admin/teacherissues/sa/deleteconfigurationtemplate/templateid/".$this->plev_pk);

        $button = '<a class="btn btn-default" href="'.$adicionarFI.'" role="button" data-toggle="tooltip" title="'.gT('Adicionar fuentes de información').'"><span class="icon-add text-success" ></span></a>';
        $button .= '<a class="btn btn-default eliminar" href="'.$eliminarPlantilla.'" role="button" data-toggle="tooltip" title="'.gT('Eliminar plantilla y todas sus fuentes de información').'"><span class="text-danger glyphicon glyphicon-trash" ></span></a>';

        return $button;
    }

    /**
     * Función que permite obtener el peso total de la plantilla de evaluación, esto se da por la suma de todas las fuentes de información asociadas a la plantilla.
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 31/05/2016
     * @return int Retorna el peso total de la plantilla, si no tiene fuentes de información, retornará 0.
     */
    public function getPesoTotal(){
        $criteria = new CDbCriteria();
        $criteria->addCondition("plev_fuin_fk = :plev_pk");
        $criteria->params = array(':plev_pk' => $this->plev_pk);
        $fuentesinformacion = FuenteInformacion::model()->findAll($criteria);
        $acumulado = 0;
        foreach ($fuentesinformacion as $fi) {
            $acumulado += $fi->fuin_peso;
        }
        return $acumulado;
    }

    /**
     * Función que permite realizar búsquedas de plantillas de evaluación.
     * @access public 
     * @author ANDRÉS DAVID MONTOYA AGUIRRE - CSNT - 31/05/2016
     * @return CActiveDataProvider Retorna un CActiveDataProvider con el resultado final de la búsqueda ordenado por el campo plev_pk ascendentemente.
     */
    public function search(){
        $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);
        $sort = new CSort();
        $sort->attributes = array(
          'plev_pk'=>array(
            'asc'=>'plev_pk',
            'desc'=>'plev_pk desc',
          ),
          'plev_pk'=>array(
            'asc'=>'plev_pk',
            'desc'=>'plev_pk desc',
          ),
        );

        $criteria = new CDbCriteria;
        // Las variables ..._reference son usadas para evitar errores en la consulta con postgresql
        // El valor que venga por parametro get será convertido a varchar dentro de la consulta sql
        
        $plev_pk_reference = (Yii::app()->db->getDriverName() == 'pgsql' ?' t.plev_pk::varchar' : 't.plev_pk');
        // Se compara con cada uno de los distintas columnas de la bd
        $criteria->compare($plev_pk_reference, $this->searched_value, true, 'OR');
        $criteria->compare('plev_nombre', $this->searched_value, true, 'OR');
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder'=>'plev_pk ASC'
                ),
            'pagination'=>array(
                'pageSize'=>$pageSize,
            ),
        ));
    }


} // Close PlantillaEvaluacion Class
?>