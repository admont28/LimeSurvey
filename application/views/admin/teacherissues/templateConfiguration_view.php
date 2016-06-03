<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');
?>

<h2 class="pagetitle">Configuración global de la plantilla de evaluación de desempeño</h2>

<h3 class="pagetitle">Lista de plantillas</h3>
	<?php $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);?>
	<!-- Search Box -->
    <div class="row">
        <div class="col-lg-12">
            <div class="form text-right">
                <!-- Begin Form -->
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl('admin/teacherissues/sa/templateconfiguration/'),
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
                <a href="<?php echo Yii::app()->createUrl('admin/teacherissues/sa/templateconfiguration');?>" class="btn btn-warning"><?php eT('Reset');?></a>

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
                'id' => 'template-grid',
                'emptyText'=>gT('No se han encontrado plantillas.'),
                'summaryText'=>gT('Displaying {start}-{end} of {count} result(s).').' '. sprintf(gT('%s rows per page'),
                    CHtml::dropDownList(
                        'pageSize',
                        $pageSize,
                        Yii::app()->params['pageSizeOptions'],
                        array('class'=>'changePageSize form-control', 'style'=>'display: inline; width: auto'))),

                'columns' => array(
                    array(
                        'header' => gT('ID de la plantilla'),
                        'name' => 'template_id',
                        'value'=>'$data->plev_pk',
                        'headerHtmlOptions'=>array('class' => 'hidden-xs'),
                        'htmlOptions' => array('class' => 'hidden-xs')
                    ),

                    array(
                        'header' => gT('Nombre de la plantilla'),
                        'name' => 'template_name',
                        'value'=>'$data->plev_nombre',
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
                'selectionChanged'=>"function(id){window.location='" . Yii::app()->urlManager->createUrl('admin/teacherissues/sa/configurationinformationsource/templateid' ) . '/' . "' + $.fn.yiiGridView.getSelection(id.split(',', 1));}",
                'ajaxUpdate' => true,
                'afterAjaxUpdate' => 'doToolTip'
            ));
            ?>
        </div>
    </div>
<h3 class="pagetitle">Adicionar una nueva plantilla</h3>

<?php echo CHtml::form(array('admin/teacherissues/sa/templateconfiguration'), 'post', array('id'=>'addnewtemplate', 'name'=>'addnewtemplate', 'class'=>'form-horizontal')); ?>
    <div class='col-sm-12 col-md-12 col-xs-12'>

        <!-- Text elements -->
        <div class="row">
			<div class="form-group">
            	<label for="nombre_plantilla" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Nombre</label>
            	<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12">
            		<?php echo CHtml::textField("nombre_plantilla","",array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"nombre_plantilla", "placeholder" => "Ingrese el nombre de la nueva plantilla")); ?>
            	</div>
            </div>
            <div class="form-group">
            	<div class="col-md-4 col-md-offset-4 col-sm-5 col-sm-offset-4">
					<button type="submit" class="btn btn-primary col-xs-10 col-xs-offset-1" id="adicionar_fi">Guardar plantilla</button>
				</div>
            </div>
        </div>
    </div>
<?php echo CHtml::endForm(); ?>
<!-- To update rows per page via ajax -->
<script type="text/javascript">
    jQuery(function($) {
            $('.eliminar').click(function(event){
            event.preventDefault();
            event.stopPropagation();
            var link = $(this).attr('href');
            swal({
              title: '¿Eliminar?',
              text: "¿Realmente está seguro de eliminar la plantilla de evaluación de desempeño? Esta acción no se podrá deshacer.",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#22722b',
              cancelButtonColor: '#a0352f',
              confirmButtonText: 'Si, eliminar',
              cancelButtonText: 'No, cancelar',
              buttonsStyling: true
            }).then(function(isConfirm) {
                if (isConfirm === true) {
                    window.location = link;
                    return true;
                }
            });
        });
        jQuery(document).on("change", '#pageSize', function(){
            $.fn.yiiGridView.update('template-grid',{ data:{ pageSize: $(this).val() }});
        });
    });
    
</script>