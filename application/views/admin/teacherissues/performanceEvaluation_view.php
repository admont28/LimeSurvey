<?php

?>
<h2 class="pagetitle">Configuración de evaluación de desempeño</h2>
   <h3 class="pagetitle">Lista de evaluaciones de desempeño existentes</h3>
   <?php $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);?>
   <!-- Search Box -->
    <div class="row">
        <div class="col-lg-12">
            <div class="form text-right">
                <!-- Begin Form -->
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl('admin/teacherissues/sa/performanceevaluation/'),
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
                <a href="<?php echo Yii::app()->createUrl('admin/teacherissues/sa/performanceevaluation');?>" class="btn btn-warning"><?php eT('Reset');?></a>

                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
   <!-- Grid -->
    <div class="row">
        <div class="col-lg-12 content-right">
            <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'dataProvider' => $model->search(),

                // Number of row per page selection
                'id' => 'evaluation-grid',
                'emptyText'=>gT('No se han encontrado evaluaciones de desempeño'),
                'summaryText'=>gT('Displaying {start}-{end} of {count} result(s).').' '. sprintf(gT('%s rows per page'),
                    CHtml::dropDownList(
                        'pageSize',
                        $pageSize,
                        Yii::app()->params['pageSizeOptions'],
                        array('class'=>'changePageSize form-control', 'style'=>'display: inline; width: auto'))),

                'columns' => array(
                    array(
                        'header' => gT('ID de la Evaluación'),
                        'name' => 'evaluation_id',
                        'value'=>'$data->evde_pk',
                        'headerHtmlOptions'=>array('class' => 'hidden-xs'),
                        'htmlOptions' => array('class' => 'hidden-xs')
                    ),

                    array(
                        'header' => gT('Fecha de creación'),
                        'name' => 'evaluation_date',
                        'value'=>'$data->evde_fechaevaluacion',
                        'headerHtmlOptions'=>array('class' => 'hidden-xs'),
                        'htmlOptions' => array('class' => 'hidden-xs'),
                    ),
                    array(
                        'header' => gT('Identificación del evaluado'),
                        'name' => 'id_evaluado',
                        'value'=>'$data->evde_identificacionevaluado',
                        'headerHtmlOptions'=>array('class' => 'hidden-xs'),
                        'htmlOptions' => array('class' => 'hidden-xs'),
                    ),
                    array(
                        'header' => 'Acciones',
                        'name' => 'actions',
                        'value'=>'$data->buttons',
                        'type'=>'raw',
                        'htmlOptions' => array('class' => ''),
                    ),

                ),

                'htmlOptions'=>array('style'=>'cursor: pointer;', 'class'=>'hoverAction'),
                'selectionChanged'=>"function(id){window.location='" . Yii::app()->urlManager->createUrl('admin/teacherissues/sa/editperformanceevaluation/performanceevaluationid' ) . '/' . "' + $.fn.yiiGridView.getSelection(id.split(',', 1));}",
                'ajaxUpdate' => true,
                'afterAjaxUpdate' => 'doToolTip'
            ));
            ?>
        </div>
    </div>
<h3 class="pagetitle">Crear una nueva evaluación de desempeño</h3>
<div class="row">
   <div class="alert alert-warning" id="mensaje_general" style="display: none"></div>
      <div class="form-group">
         <div class="col-md-4 col-md-offset-4 col-sm-5 col-sm-offset-4">
            <a href="<?php echo Yii::app()->urlManager->createUrl('admin/teacherissues/sa/addperformanceevaluation'); ?>" class="btn btn-primary col-xs-10 col-xs-offset-1">Crear una nueva evaluación de desemepeño</a>
         </div>
      </div>
</div>