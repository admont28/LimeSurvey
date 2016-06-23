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
		            	<input type='hidden' name='idevaluacion' id='idevaluacion' value='<?php echo $evaluacionDesempeno->evde_pk ?>' />
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
		            	<?php if(sizeof($value) > 1): ?>
		            		<?php $primero = $value[0]; ?>
		            			<div class='row group-container'>
				                    <div class='col-md-12 box'>
					                    <div class='header' style='text-align: left;'>
					                       	<span class='groupTitle' style='font-size: 1.5em;'>
					                        	Fuente de información - <?php echo $primero['nombre_fi']; ?>
					                       	</span>
					                    </div>
				                	</div>
				                	<div class='col-md-12 questionContainer'>
		                                <div class='form-group'>
		                                   <label for='nombre_fi_<?php echo $primero['idfi']; ?>' class='col-md-2'>Nombre: </label>
		                                   <div class='col-md-6'>
		                                      <input type='hidden' name='idfi' id='idfi_<?php echo $primero['idfi']; ?>' value='<?php echo $primero['idfi']; ?>' />
		                                      <input type='text' class='form-control nombrefi bloquear' name='nombre_fi_<?php echo $primero['idfi']; ?>' id='nombre_fi_<?php echo $primero['idfi']; ?>' required='required' disabled='disabled' maxlength='200' autofocus='autofocus' placeholder='Ingrese el nombre de la fuente de información' value='<?php echo $primero['nombre_fi']; ?>' style='color: #000;'/>
		                                   </div>
		                                   <label for='peso_fi_<?php echo $primero['idfi']; ?>' class='col-md-1'>Peso: </label>
		                                   <div class='col-md-3'>
		                                    	<div class='input-group'>
		                                        	<input min='0' max='100' step='1' placeholder='Peso de esta fuente de información' required='required' type='number' class='form-control currency pesofi bloquear peso_idfi_<?php echo $primero['idfi']; ?>' name='peso_fi_<?php echo $primero['idfi']; ?>' id='peso_fi_<?php echo $primero['idfi']; ?>' value='<?php echo $primero['peso_fi']; ?>' <?php if($primero['peso_fi'] == 0) echo "disabled='disabled'"; ?> style='color: #000;'  data-fi='<?php echo $primero['idfi']; ?>' />
		                                           <span class='input-group-addon'>%</span>
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class='form-group'>
		                                   <label for='encuesta_fi_<?php echo $primero['idfi']; ?>' class='col-md-2'>Encuesta base: </label>
		                                   <div class='col-md-10' >
		                                      <input type='text' id='encuesta_fi_<?php echo $primero['idfi']; ?>' name='encuesta_fi_<?php echo $primero['idfi']; ?>' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' data-sid='<?php echo $primero['id_encuesta']; ?>' value='<?php echo $primero['nombre_encuesta']; ?>' style='color: #000;' />
		                                   </div>
		                                </div>
							<?php foreach ($value as $llave => $valor): ?>
										<div class='form-group'>
											<label for='estado_grupo_fi' class='col-md-2 '>Estado del grupo:</label>
			            					<div class='col-md-2'>
			                                    <label class='radio-inline'>
			                                       	<input type='radio' name='estado_grupo_fi_<?php echo $valor['id_grupo']; ?>' value='true' required='required' id="<?php echo $valor['id_grupo']; ?>" <?php  if($valor['estado_grupo']) echo "checked='checked'"; ?> class='estadogrupofi bloquear' required='required' data-fi="<?php echo $valor['idfi']; ?>" data-idgrup='<?php echo $valor['id_grupo']; ?>'> Habilitado
			                                    </label>
			                                    <label class='radio-inline'>
			                                       	<input type='radio' name='estado_grupo_fi_<?php echo $valor['id_grupo']; ?>' value='false' required='required' id="<?php echo $valor['id_grupo']; ?>" <?php  if(!$valor['estado_grupo']) echo "checked='checked'"; ?> class='estadogrupofi bloquear' required='required' data-fi="<?php echo $valor['idfi']; ?>" data-idgrup='<?php echo $valor['id_grupo']; ?>'> Inhabilitado
			                                    </label>
			                                </div>
			                                <div class='col-md-8'>
			                                    <p><?php echo $valor['nombre_encuesta_clonada'] ?></p>
			                                </div>
			                            </div>
		            		<?php endforeach; // SEGUNDO FOREACH ?>
		            		 		</div><!-- END questionContainer-->
				              	</div><!-- END group-container-->
		            	<?php else: ?>
							<?php foreach ($value as $llave => $valor): ?>
		            			<div class='row group-container'>
				                    <div class='col-md-12 box'>
					                    <div class='header' style='text-align: left;'>
					                       	<span class='groupTitle' style='font-size: 1.5em;'>
					                        	Fuente de información - <?php echo $valor['nombre_fi']; ?>
					                       	</span>
					                    </div>
				                	</div>
				                	<div class='col-md-12 questionContainer'>
		                                <div class='form-group'>
		                                   <label for='nombre_fi_<?php echo $valor['idfi']; ?>' class='col-md-2'>Nombre: </label>
		                                   <div class='col-md-6'>
		                                      <input type='hidden' name='idfi' id='idfi_<?php echo $valor['idfi']; ?>' value='<?php echo $valor['idfi']; ?>' />
		                                      <input type='text' class='form-control nombrefi bloquear' name='nombre_fi_<?php echo $valor['idfi']; ?>' id='nombre_fi_<?php echo $valor['idfi']; ?>' required='required' disabled='disabled' maxlength='200' autofocus='autofocus' placeholder='Ingrese el nombre de la fuente de información' value='<?php echo $valor['nombre_fi']; ?>' style='color: #000;'/>
		                                   </div>
		                                   <label for='peso_fi_<?php echo $valor['idfi']; ?>' class='col-md-1'>Peso: </label>
		                                   <div class='col-md-3'>
		                                    	<div class='input-group'>
		                                        	<input min='0' max='100' step='1' placeholder='Peso de esta fuente de información' required='required' type='number' class='form-control currency pesofi bloquear peso_idfi_<?php echo $valor['idfi']; ?>' name='peso_fi_<?php echo $valor['idfi']; ?>' id='peso_fi_<?php echo $valor['idfi']; ?>' value='<?php echo $valor['peso_fi']; ?>' <?php if($valor['peso_fi'] == 0) echo "disabled='disabled'"; ?> style='color: #000;'  data-fi='<?php echo $valor['idfi']; ?>' />
		                                           <span class='input-group-addon'>%</span>
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class='form-group'>
		                                   <label for='encuesta_fi_<?php echo $valor['idfi']; ?>' class='col-md-2'>Encuesta base: </label>
		                                   <div class='col-md-10' >
		                                      <input type='text' id='encuesta_fi_<?php echo $valor['idfi']; ?>' name='encuesta_fi_<?php echo $valor['idfi']; ?>' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' data-sid='<?php echo $valor['id_encuesta']; ?>' value='<?php echo $valor['nombre_encuesta']; ?>' style='color: #000;' />
		                                   </div>
		                                </div>
		                                <div class='form-group'>
		                                   <label for='nombre_encuesta_clonada_fi_<?php echo $valor['idfi']; ?>' class='col-md-2'>Nombre de la encuesta clonada:</label>
		                                   <div class='col-md-10' >
		                                      <textarea  id='nombre_encuesta_clonada_fi_<?php echo $valor['idfi']; ?>' name='nombre_encuesta_clonada_fi_<?php echo $valor['idfi']; ?>' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' data-sid='<?php echo $valor['id_encuesta_clonada']; ?>' style="color: #000;"><?php echo (isset($valor['nombre_encuesta_clonada'])) ? $valor['nombre_encuesta_clonada']: '';?>
		                                      </textarea>
		                                   </div>
		                                </div>
		                                <div class='form-group'>
		                                   <label for='encuesta_clonada_fi_<?php echo $valor['idfi']; ?>' class='col-md-2'>ID de encuesta clonada: </label>
		                                   	<div class='col-md-10' >
		                                      <input type='text' id='encuesta_clonada_fi_<?php echo $valor['idfi']; ?>' name='encuesta_clonada_fi_<?php echo $valor['idfi']; ?>' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' data-sid='<?php echo $valor['id_encuesta_clonada']; ?>' value="<?php echo (isset($valor['id_encuesta_clonada'])) ? $valor['id_encuesta_clonada'] : ''; ?>" style="color: #000;" />
		                                   	</div>
		                                </div>
		                                <div class='form-group'>
											<label for='estado_grupo_fi' class='col-md-2 '>Estado de la fuente de información:</label>
											<div class='col-md-2'>
												<input type='hidden' name='idgrup' id='idgrup_<?php echo $valor['id_grupo']; ?>' value='<?php echo $valor['id_grupo']; ?>' />
												<label class='radio-inline'><input type='radio' class='estadogrupofi bloquear' name='estado_grupo_fi_<?php echo $valor['id_grupo']; ?>' value='true' required='required' <?php if($valor['estado_grupo']) echo "checked='checked'"; ?> id='<?php echo $valor['id_grupo']; ?>' data-fi="<?php echo $valor['idfi']; ?>" data-idgrup='<?php echo $valor['id_grupo']; ?>' >Habilitada</label>
												<label class='radio-inline'><input type='radio' class='estadogrupofi bloquear' name='estado_grupo_fi_<?php echo $valor['id_grupo']; ?>'  value='false' required='required' <?php if(!$valor['estado_grupo']) echo "checked='checked'"; ?> id='<?php echo $valor['id_grupo']; ?>' data-fi="<?php echo $valor['idfi']; ?>" data-idgrup='<?php echo $valor['id_grupo']; ?>' >Inhabilitada</label>
											</div>
										</div>
		                            </div>
		                        </div> 
		            		<?php endforeach; // SEGUNDO FOREACH ?> 
		            	<?php endif; ?>
		            <?php endforeach; ?>
		        </div> <!-- <- /Main-Container -->
		        <div class="form-group">
		            <div class="col-sm-2 col-sm-offset-10 text-center">
                  		<img id="cargando" src="<?php echo IMAGE_BASE_URL."cargando-gesen.gif"; ?>" alt="Gargando..." style="display: none;">
		                <button type="submit" name="save"  class="btn btn-success btn-block" value='save' id="save"><?php eT("Save"); ?></button>
		            </div>
		        </div>
		    </div>
		</div>
	<?php echo CHtml::endForm(); ?>
<?php endif; ?>
<script type="text/javascript">
	var peticion = null;
	$("#editperformanceevaluation").submit(function(e){
		e.preventDefault();
     	if(isConfirm === true){
     		$("#cargando").show();
	      	$("#save").prop("disabled", true);
	      	var group_container = $("#main-container").children(".group-container");
	      	var informacion     = {};
	      	informacion['idevaluacion'] = $("#idevaluacion").val();
	      	var fuentes = new Array();
	      	var suma = 0;
	      	$.each(group_container, function(k, filafi) {
	      		var fuentesinformacion ={};
	         	var pesofi = $(filafi).find(".pesofi").val();
	         	suma += parseInt(pesofi);
	         	var idfi   = $(filafi).find("input[name=idfi]").val();
	         	var gruposfi = $(filafi).find("input[type='radio']:checked");
	         	var grupos = new Array();
	         	$.each(gruposfi, function(index, value){
	         		var grupo = {};
		            grupo['grup_id'] = value.id;
		            grupo['grup_estado'] = $(value).val();
		            grupos.push(grupo);
	         	});
	         	fuentesinformacion['idfi']   = idfi;
	         	fuentesinformacion['pesofi'] = pesofi;
	         	fuentesinformacion['gruposfi'] = grupos;
	         	fuentes.push(fuentesinformacion);
	      	});
	      	if(suma > 100){
	      		swal({
	             	title: 'Oops... ¡Ha ocurrido un error!',
	             	text : 'La suma de los pesos de las fuentes de información no puede ser superior a 100%',
	             	type: 'error',
	             	confirmButtonColor: '#22722b',
	             	confirmButtonText: 'OK',
	             	buttonsStyling: true
	            }).then(function(isConfirm){
	            	$("#cargando").hide();
	                $("#save").prop("disabled", false);
	            });
	            return false;
	      	}
	      	informacion['fuentes'] = fuentes;
	      	var empaquetadojson = JSON.stringify(informacion);
	      	if(peticion != null)
	            peticion.abort();
	        peticion = $.ajax({
	            url  : "",
	            type : "POST",
	            dataType: "JSON",
	            data:{
	               'informacion' : empaquetadojson
	            },
	            success: function(data){
	               	if(data.state == "success"){
	                  	$("#cargando").hide();
	                  	$("#save").prop("disabled", false);
	                  	swal({
	                     	title: '¡Guardado con éxito!',
	                     	text : data.message,
	                     	type: 'success',
	                     	showCancelButton: false,
	                     	confirmButtonColor: '#22722b',
	                     	confirmButtonText: 'OK',
	                     	buttonsStyling: true
	                  	}).then(function(isConfirm) {
	                     	location.reload();
	                  	});
	               	}
	               	else if(data.state == "error"){
	                 	swal({
	                     	title: 'Oops... ¡Ha ocurrido un error!',
	                     	text : data.message,
	                     	type: 'error',
	                     	confirmButtonColor: '#22722b',
	                     	confirmButtonText: 'OK',
	                     	buttonsStyling: true
	                  	});
	                  	$("#cargando").hide();
	                  	$("#save").prop("disabled", false);
	               }
	            },
	            error: function(xhr, status){
	               console.log(status);
	            }
	        });
     	}
	});

	$("#main-container").delegate('.estadogrupofi' , 'click' , function(e){
		var question_container = $(this).parent().parent().parent().parent();
      	var grupos_fi = $(question_container).find("input[type=radio]:checked");
      	var cantidad_total_radio = grupos_fi.length;
      	var cantidad_falsos = 0;
      	$.each(grupos_fi, function(index, value){
         	if($(value).val() == "false"){
            	cantidad_falsos ++;
         	}
      	});      	
      	var fi = $(this).data("fi");
      	if(cantidad_falsos == cantidad_total_radio){
         	$("#peso_fi_"+fi).val("0");
         	$("#peso_fi_"+fi).prop("disabled", true);
      	}else{
         	$("#peso_fi_"+fi).prop("disabled", false);
         	$("#peso_fi_"+fi).focus();
      	}
   });
</script>