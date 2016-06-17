<?php if (!defined('BASEPATH')) die('No direct script access allowed'); ?>
<?php if(isset($evaluacionDesempeno, $fi, $plantilla, $dependencia)): ?>
	<?php echo CHtml::form(array('admin/teacherissues/sa/editperformanceevaluation'), 'post', array('id'=>'editperformanceevaluation', 'name'=>'editperformanceevaluation', 'class'=>'form-horizontal')); ?>
		<div class='col-sm-12 col-md-12 col-xs-12'>
		    <!-- Text elements -->
		    <div class="row">
		        <!-- Plantilla de evaluación de desempeño -->
		        <div class="form-group">
		            <label for="plantilla_evaluado" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Plantilla de evaluación: </label>
		            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
		               <?php echo CHtml::textField("plantilla_evaluado",$plantilla->plev_nombre,array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"plantilla_evaluado", "disabled" => "disabled", "style" => "color: black;", "title" => "Plantilla de evaluación del evaluado.")); ?>
		            </div>
		        </div>
		        <!-- Facultades -->
		        <div class="form-group">
		            <label for="dependencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Dependencia: </label>
		            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
		               <?php echo CHtml::textField("dependencia",$dependencia,array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"dependencia", "disabled" => "disabled", "style" => "color: black;", "title" => "Dependencia del evaluado.")); ?>
		         	</div>
		        </div>
		        <!-- Datos del evaluado -->
		        <div class="form-group">
		            <label for="fecha_evaluacion" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Fecha de evaluación: </label>
		            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
		               <?php echo CHtml::textField("fecha_evaluacion",$evaluacionDesempeno->evde_fechaevaluacion,array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"fecha_evaluacion", "disabled" => "disabled", "style" => "color: black;", "title" => "Fecha de creación de la evaluación de desempeño.")); ?>
		            </div>
		            <label for="identificacion" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Identificación: </label>
		            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		               <?php echo CHtml::textField("identificacion",$evaluacionDesempeno->evde_identificacionevaluado,array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"identificacion", "disabled" => "disabled", "style" => "color: black;", "title" => "Fecha de creación de la evaluación de desempeño.")); ?>
		            </div>
		        </div>
		        <h3 class="pagetitle">Fuentes de información</h3>
		        <div id="main-container">
		            <?php foreach ($fi as $key => $value): ?>
		            	<div class='row group-container'>
		                    <div class='col-md-12 box'>
			                    <div class='header' style='text-align: left;'>
			                       	<span class='groupTitle' style='font-size: 1.5em;'>
			                        	Fuente de información - <?php echo $value['nombre_fi']; ?>
			                       	</span>
			                       	<?php if($value['peso_fi'] == 0 ): ?>
										<span id='state_fi_<?php echo $value['idfi']; ?>' style='margin-left: 10px; font-size: 1.2em;' class='label label-warning'>No se aplicará esta fuente de información.
			                       		</span>
									<?php else: ?>
										<span id='state_fi_<?php echo $value['idfi']; ?>' style='margin-left: 10px; font-size: 1.2em;' class='label label-success'>Se aplicará esta fuente de información.
			                       		</span>
			                       	<?php endif; ?>
			                       	
			                       	<button type='button' class='btn btn-default noaplicarfi' style='float: right; margin-right: 15px;' title='Si desea puede no aplicar la fuente de información, su peso se establecerá en 0 y deberá modificar los pesos de las demás fuentes de información.' id='no_aplicar_fi_<?php echo $value['idfi']; ?>' data-fi='<?php echo $value['idfi']; ?>' >
			                          <span class='glyphicon glyphicon-off' aria-hidden='false'></span> No aplicar esta fuente de información
			                       	</button>
			                    </div>
		                	</div>
		                	<div class='col-md-12 questionContainer'>
                                <div class='form-group'>
                                   <label for='nombre_fi_<?php echo $value['idfi']; ?>' class='col-md-2'>Nombre: </label>
                                   <div class='col-md-6'>
                                      <input type='hidden' name='idfi' id='idfi_<?php echo $value['idfi']; ?>' value='<?php echo $value['idfi']; ?>' />
                                      <input type='text' class='form-control nombrefi bloquear' name='nombre_fi_<?php echo $value['idfi']; ?>' id='nombre_fi_<?php echo $value['idfi']; ?>' required='required' disabled='disabled' maxlength='200' autofocus='autofocus' placeholder='Ingrese el nombre de la fuente de información' value='<?php echo $value['nombre_fi']; ?>' style='color: #000;'/>
                                   </div>
                                   <label for='peso_fi_<?php echo $value['idfi']; ?>' class='col-md-1'>Peso: </label>
                                   <div class='col-md-3'>
                                    	<div class='input-group'>
                                        	<input min='0' max='100' step='1' placeholder='Peso de esta fuente de información' required='required' disabled='disabled' type='number' class='form-control currency pesofi bloquear' name='peso_fi_<?php echo $value['idfi']; ?>' id='peso_fi_<?php echo $value['idfi']; ?>' value='<?php echo $value['peso_fi']; ?>' style='color: #000;' data-state='off' />
                                           <span class='input-group-addon'>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class='form-group'>
                                   <label for='encuesta_fi_<?php echo $value['idfi']; ?>' class='col-md-2'>Encuesta: </label>
                                   <div class='col-md-10' >
                                      <input type='text' id='encuesta_fi_<?php echo $value['idfi']; ?>' name='encuesta_fi_<?php echo $value['idfi']; ?>' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' data-sid='<?php echo $value['id_encuesta']; ?>' value='<?php echo $value['nombre_encuesta']; ?>' style='color: #000;' />
                                   </div>
                                </div>
                                <div class='form-group'>
                                   <label for='nombre_encuesta_clonada_fi_<?php echo $value['idfi']; ?>' class='col-md-2'>Nombre de la encuesta clonada:</label>
                                   <div class='col-md-10' >
                                      <textarea  id='nombre_encuesta_clonada_fi_<?php echo $value['idfi']; ?>' name='nombre_encuesta_clonada_fi_<?php echo $value['idfi']; ?>' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' data-sid='<?php echo $value['id_encuesta_clonada']; ?>' style="color: #000;"><?php echo (isset($value['nombre_encuesta_clonada'])) ? $value['nombre_encuesta_clonada']: '';?>
                                      </textarea>
                                   </div>
                                </div>
                                <div class='form-group'>
                                   <label for='encuesta_clonada_fi_<?php echo $value['idfi']; ?>' class='col-md-2'>ID de encuesta clonada: </label>
                                   <div class='col-md-10' >
                                      <input type='text' id='encuesta_clonada_fi_<?php echo $value['idfi']; ?>' name='encuesta_clonada_fi_<?php echo $value['idfi']; ?>' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' data-sid='<?php echo $value['id_encuesta_clonada']; ?>' value="<?php echo (isset($value['id_encuesta_clonada'])) ? $value['id_encuesta_clonada'] : ''; ?>" style="color: #000;" />
                                   </div>
                                </div>
                            </div>
                        </div>    
		            <?php endforeach; ?>
		        </div> <!-- <- /Main-Container -->
		        <div class="form-group">
		            <div class="col-sm-2 col-sm-offset-10">
		               <button type="submit" name="save"  class="btn btn-success col-xs-12" value='save'><?php eT("Save"); ?></button>
		            </div>
		        </div>
		    </div>
		</div>
	<?php echo CHtml::endForm(); ?>
<?php endif; ?>