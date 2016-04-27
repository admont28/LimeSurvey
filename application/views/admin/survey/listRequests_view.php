<?php 
/**
 * Vista para listar las solicitudes de activación.
 * Es llamada por SurveyAdmin::listrequests
 * It's called from SurveyAdmin::listrequests
 *
 */
$pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);?>
<div class="col-lg-12 list-surveys">
    <h3>Lista de solicitudes de activación</h3>

    <!-- Search Box -->
    <div class="row">
        <div class="col-lg-12">
            <div class="form text-right">
                <!-- Begin Form -->
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl('admin/survey/sa/listrequests/'),
                    'method' => 'get',
                    'htmlOptions'=>array(
                        'class'=>'form-inline',
                    ),
                )); ?>

                <!-- search input -->
                <div class="form-group">
                    <?php echo $form->label($model, 'search', array('label'=>gT('Search:'),'class'=>'control-label')); ?>
                    <?php echo $form->textField($model, 'searched_value', array('class'=>'form-control')); ?>
                </div>

                <?php echo CHtml::submitButton(gT('Search','unescaped'), array('class'=>'btn btn-success')); ?>
                <a href="<?php echo Yii::app()->createUrl('admin/survey/sa/listrequests');?>" class="btn btn-warning"><?php eT('Reset');?></a>

                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>

<?php 
    // El nombre del dueño de la encuesta ($data->owner->full_name) es traido debido a la relación: 
    // 'owner' => array(self::BELONGS_TO, 'User', 'request_owner_id')
    // Definida en la función relations del modelo Governance.php
    // En el campo Aprobado por se hace una decisión: 
    // '(isset($data->approval_user->full_name) ? $data->approval_user->full_name: "" )'
    // Si existe el full_name del usuario lo muestra, sino muestra el espacio en blanco, esto se hace por si no existe el nombre es porque el objeto approval_user es null y no se puede acceder a las propiedades.
	$this->widget('bootstrap.widgets.TbGridView', array(
            'dataProvider' => $model->search(),
            'id' => 'survey-grid',
            'summaryText'=>gT('Displaying {start}-{end} of {count} result(s).').' '. sprintf(gT('%s rows per page'),
                    CHtml::dropDownList(
                        'pageSize',
                        $pageSize,
                        Yii::app()->params['pageSizeOptions'],
                        array('class'=>'changePageSize form-control', 'style'=>'display: inline; width: auto'))),
            'columns' => array(
             	array(
                    'header' => "Id encuesta",
                    'name' => 'gosu_pk',
                    'value'=>'$data->gosu_pk',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Solicitado por",
                    'name' => 'govsur_user_fk',
                    'value'=> '$data->owner->full_name',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
             	array(
                    'header' => "Justificación",
                    'name' => 'gosu_requestjustification',
                    'value'=>'$data->gosu_requestjustification',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Estado",
                    'name' => 'gosu_requeststate',
                    'value'=>'$data->gosu_requeststate',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Modificada por",
                    'name' => 'gosu_user_fk',
                    'value'=> '(isset($data->approval_user->full_name) ? $data->approval_user->full_name: "---" )',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Respuesta a la solicitud",
                    'name' => 'gosu_requestresponse',
                    'value'=>'$data->gosu_requestresponse',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Fecha Solicitud",
                    'name' => 'gosu_requestdate',
                    'value'=>'$data->gosu_requestdate',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Fecha Modificación",
                    'name' => 'gosu_modificationdate',
                    'value'=>'(isset($data->gosu_modificationdate) ? $data->gosu_modificationdate: "---" )',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
            ),
            'htmlOptions'=>array('style'=>'cursor: pointer;', 'class'=>'hoverAction'),
            'selectionChanged'=>"function(id){window.location='" . Yii::app()->urlManager->createUrl('admin/survey/sa/view/surveyid' ) . '/' . "' + $.fn.yiiGridView.getSelection(id.split(',', 1));}",
                'ajaxUpdate' => true,
        ));
?>
</div>
<!-- To update rows per page via ajax -->
<script type="text/javascript">
    jQuery(function($) {
        jQuery(document).on("change", '#pageSize', function(){
            $.fn.yiiGridView.update('survey-grid',{ data:{ pageSize: $(this).val() }});
        });
    });
</script>