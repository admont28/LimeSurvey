<?php

?>
<h2 class="pagetitle">Evaluación de desempeño</h2>

<?php echo CHtml::form(array('admin/teacherissues/sa/templateconfiguration'), 'post', array('id'=>'addnewtemplate', 'name'=>'addnewtemplate', 'class'=>'form-horizontal')); ?>
	<div class='col-sm-12 col-md-12 col-xs-12'>

        <!-- Text elements -->
        <div class="row">

            <!-- Datos del evaluado -->
            <div class="form-group">
            	<label for="nombres_evaluado" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Nombres: </label>
            	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            		<?php echo CHtml::textField("nombres_evaluado","",array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"nombres_evaluado", "placeholder" => "Ingrese el nombre del evaluado")); ?>
            	</div>
            	<label for="apellido_evaluado" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Apellidos: </label>
            	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            		<?php echo CHtml::textField("apellido_evaluado","",array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"apellido_evaluado", "placeholder" => "Ingrese el apellido del evaluado")); ?>
            	</div>
            </div>
            <div class="form-group">
            	<label for="identificacion_evaluado" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Identificación: </label>
            	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            		<select name="identificacion_evaluado" required="required" class="form-control">
            			<option selected="selected" value="">Por favor seleccione...</option>
            			<option value="cc">Cédula de ciudadanía</option>
            			<option value="ti">Tarjeta de identidad</option>
            		</select>
            	</div>
            	<label for="numero_evaluado" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Número: </label>
            	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            		<?php echo CHtml::numberField("numero_evaluado","",array('class'=>'form-control', 'min'=>0, 'required'=>'required','autofocus'=>'autofocus','id'=>"numero_evaluado", "placeholder" => "Ingrese el número de identificación del evaluado")); ?>
            	</div>
            </div>
            <div class="form-group">
            	<label for="dependencia_evaluado" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Dependencia: </label>
            	<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12">
            		<select name="dependencia_evaluado" required="required" class="form-control">
            			<option selected="selected" value="">Por favor seleccione...</option>
            			<option value="d1">Dependencia 1</option>
            			<option value="d2">Dependencia 2</option>
            			<option value="d3">Dependencia 3</option>
            			<option value="d4">Dependencia 4</option>
            		</select>
            	</div>
            </div>
            <div class="form-group">
            	<label for="plantilla_evaluado" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Plantilla: </label>
            	<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12">
            		<select name="plantilla_evaluado" required="required" class="form-control">
            			<option selected="selected" value="">Por favor seleccione...</option>
            			<option value="p1">Plantilla 1</option>
            			<option value="p2">Plantilla 2</option>
            			<option value="p3">Plantilla 3</option>
            			<option value="p4">Plantilla 4</option>
            		</select>
            	</div>
            </div>
            <h3 class="pagetitle">Fuentes de información</h3>
            
            <div class="form-group">
			    <div class="col-sm-2 col-sm-offset-10">
			      <button type="submit" name="save"  class="btn btn-success col-xs-12" value='save'><?php eT("Save"); ?></button>
			    </div>
			 </div>
        </div>
    </div>
</form>