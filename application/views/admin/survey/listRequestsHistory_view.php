<?php 
/**
 * Vista para listar el historial de solicitudes de activación de una encuesta en particular.
 * Es llamada por SurveyAdmin::listrequestshistory
 * It's called from SurveyAdmin::listrequestshistory
 *
 */
$pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);?>
<div class="col-lg-12 list-surveys">
    <h3>Historial de solicitudes de activación (<?php echo $iSurveyID ?>)</h3>

    <!-- Search Box -->
    <div class="row">
        <div class="col-lg-12">
            <div class="form text-right">
                <!-- Begin Form -->
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl("admin/survey/sa/listrequestshistory/surveyid/".$iSurveyID.""),
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
                <a href="<?php echo Yii::app()->createUrl('admin/survey/sa/listrequestshistory/surveyid/'.$iSurveyID);?>" class="btn btn-warning"><?php eT('Reset');?></a>

                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>

<?php 
    // El nombre del dueño de la encuesta ($data->owner->full_name) es traido debido a la relación: 
    // 'owner' => array(self::BELONGS_TO, 'User', 'higosu_users_fk')
    // Definida en la función relations del modelo GovernanceSurvey.php
    // En el campo Aprobado por se hace una decisión: 
    // '(isset($data->approval_user->full_name) ? $data->approval_user->full_name: "" )'
    // Si existe el full_name del usuario lo muestra, sino muestra el espacio en blanco, esto se hace por si no existe el nombre es porque el objeto approval_user es null y no se puede acceder a las propiedades.
	$this->widget('bootstrap.widgets.TbGridView', array(
           'dataProvider' => $model->search($iSurveyID),
           'id' => 'survey-grid',
           'summaryText'=>gT('Displaying {start}-{end} of {count} result(s).').' '. sprintf(gT('%s rows per page'),
                    CHtml::dropDownList(
                        'pageSize',
                        $pageSize,
                        Yii::app()->params['pageSizeOptions'],
                        array('class'=>'changePageSize form-control', 'style'=>'display: inline; width: auto'))),
           'columns' => array(
                array(
                    'header' => "Id",
                    'name' => 'higosu_pk',
                    'value'=> '$data->higosu_pk',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Solicitado por",
                    'name' => 'higosu_users_fk',
                    'value'=> '$data->owner->full_name',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
             	array(
                    'header' => "Justificación",
                    'name' => 'higosu_requestjustification',
                    'value'=>'$data->higosu_requestjustification',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Estado",
                    'name' => 'higosu_requeststate',
                    'value'=>'$data->higosu_requeststate',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Modificada por",
                    'name' => 'higosu_user_fk',
                    'value'=> '(isset($data->approval_user->full_name) ? $data->approval_user->full_name: "---" )',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Respuesta a la solicitud",
                    'name' => 'higosu_requestresponse',
                    'value'=>'$data->higosu_requestresponse',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Fecha Solicitud",
                    'name' => 'higosu_requestdate',
                    'value'=>'$data->higosu_requestdate',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
                array(
                    'header' => "Fecha Modificación",
                    'name' => 'higosu_modificationdate',
                    'value'=>'(isset($data->higosu_modificationdate) ? $data->higosu_modificationdate: "---" )',
                    'htmlOptions' => array('class' => 'hidden-xs'),
                ),
            ),
           'htmlOptions'=>array('class'=>'hoverAction'),
           
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