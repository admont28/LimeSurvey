<?php 

?>

<h3 class="pagetitle">Configuración global de la plantilla de evaluación de desempeño</h3>
<!-- Form submited by save buton menu bar -->
<?php echo CHtml::form(array('admin/survey/sa/templateconfiguration'), 'post', array('id'=>'addnewsurvey', 'name'=>'addnewsurvey', 'class'=>'form-horizontal')); ?>
    <div class='col-sm-12 col-md-12 col-xs-12'>

        <!-- Text elements -->
        <div class="row">

            <!-- Tipo de evaluación -->
            <div class="form-group">
            	<label for="tipo_evaluacion" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Tipo evaluación</label>
            	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            		<select id='tipo_evaluacion' name='tipo_evaluacion'  class="form-control">
						<option value="docente_catedratico">Docente catedrático</option>
						<option value="docente_planta">Docente planta</option>
						<option value="docente_ocasional">Docente ocasional</option>
						<option value="director_programa">Director programa</option>
						<option value="decano">Decano</option>
						<option value="vicerrector">Vicerrector</option>
                	</select>	
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-md-4 col-md-offset-5 col-sm-6 col-sm-offset-4">
					<button type="button" class="btn btn-primary col-xs-10 col-xs-offset-1" id="adicionar_fi">Adicionar fuente de información</button>
				</div>
            </div>
			
			<div id="main-container">
				
			</div>

            <div class="form-group">
			    <div class="col-sm-2 col-sm-offset-6">
			      <button type="submit" name="save"  class="btn btn-success col-xs-10 col-xs-offset-1" value='save'><?php eT("Save"); ?></button>
			    </div>
			 </div>
        </div>
    </div>
</form>


<!-- Modal -->
<div id="modal_fuente_informacion" class="modal fade" role="dialog">
  	<div class="modal-dialog">
	    <!-- Modal content -->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Agregar Información Adicional</h4>
		      </div>
		      <div class="modal-body">
						<div class="form-group">
						  	<label for="nombre_fuente_informacion">Nombre</label>
						  	<div>
				              <p class="form-control-static">
				                <span class='annotation text-warning'>Obligatorio</span>
				              </p>
				            </div>
						  	<div class="input-group">
						    	<div class="input-group-addon">
						      		<i class="glyphicon glyphicon-bookmark"></i>
						    		</div>
					    		<?php echo CHtml::textField("nombre_fuente_informacion","",array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"nombre_fuente_informacion", "placeholder" => "Ingrese el nombre de la información adicional")); ?>
						  	</div>
						</div>
		      </div>
		      <div class="modal-footer">
		      	<button type="button" name="save" id="guardar-fuente-informacion" class="btn btn-success" value='save'>Adicionar</button>
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		      </div>
		    </div> <!-- /Modal content -->
		 </form>
	</div>
</div><!-- /Modal -->

<?php $surveys = preg_replace("[\n|\r|\n\r]", "", getSurveyList(false)); ?>
<script type="text/javascript">
	$(document).ready(function() {
			var i = 1;
			var select = "<?php echo $surveys; ?>";
		$('#adicionar_fi').click(function () {
			var html = "<div class='row group-container'>"+
							"<div class='col-sm-12 box'>"+
								"<div class='header' style='text-align: left;'>"+
									"<span class='groupTitle' style='font-size: 1.5em;'>"+
										"Fuente de información - "+i+
									"</span>"+
								"</div>"+
							"</div>"+
							"<div class='col-sm-12 questionContainer'>"+
								"<div class='form-group col-sm-12'>"+
		           					"<label for='nombre_fi_"+i+"' class='col-sm-3'>Nombre de la fuente de información: </label>"+
		           					"<div class='input-group'>"+
						    			"<div class='input-group-addon'>"+
						      				"<i class='glyphicon glyphicon-bookmark'></i>"+
						    			"</div>"+
				    					"<input type='text' class='form-control col-sm-9' name='nombre_fi_"+i+"' required='required' maxlength='200' autofocus='autofocus' placeholder='Ingrese el nombre de la fuente de información'/>"+
				    				"</div>"+
								"</div>"+
								"<div class='form-group col-sm-12'>"+
		           					"<label for='encuesta_fi_"+i+"' class='col-sm-3'>Encuesta asociada: </label>"+
		           					"<div class='col-sm-9 selects'>"+
		           						"<select id='encuesta_fi_"+i+"' data-id='"+i+"' name='encuesta_fi_"+i+"' required='required' class='form-control survey'>"+select+"</select>"+
		           					"</div>"+
		           				"</div>"+
								"<div class='form-group col-sm-12'>"+
		           					"<label for='porcentaje_fi_"+i+"' class='col-sm-3'>Porcentaje de la fuente de información: </label>"+
		           					"<div class='input-group'>"+
								        "<input min='0' max='100' step='1' placeholder='Porcentaje de la fuente de información' required='required' type='number' class='form-control currency col-sm-9' name='porcentaje_fi_"+i+"' />"+
								        "<span class='input-group-addon'>%</span>"+
								    "</div>"+
		           				"</div>"+
		           				"<div class='form-group'>"+
            						"<div class='col-md-4 col-md-offset-5 col-sm-6 col-sm-offset-4'>"+
										"<button type='button' disabled='disabled' data-toggle='modal' data-target='#modal_fuente_informacion' class='btn btn-primary-bootstrap col-xs-10 col-xs-offset-1 agregar_ia' id='"+i+"'>Agregar información adicional</button>"+
									"</div>"+
            					"</div>"+
            					"<div class=' table-responsive'>"+
            						"<table class='table table-hover'>"+
            							"<thead>"+
            								"<tr>"+
            									"<th>#</th>"+
            									"<th>Preguntas de la encuesta seleccionada</th>"+
            									//"<th>Información Adicional</th>"+
            								"</tr>"+
            							"</thead>"+
            							"<tbody id='tbody_fi_"+i+"'>"+
            							"</tbody>"+
									"</table>"+
            					"</div>"+
							"</div>"+
						"</div>";
			$('#main-container').append(html);
			i++;
		});

		$('#main-container').delegate( '.survey', 'change', function(e){
		   	var id = $(this).attr("id");
		   	var data_id = $('#'+id).data('id');
	        var surveyid = $('#'+id).val();
	        console.log(surveyid);
	        if(surveyid != ""){
	        	$("button[id='"+data_id+"']").attr("disabled", false);
	        }
	        else{
	        	$("button[id='"+data_id+"']").attr("disabled", true);
	        }
	        $.ajax({
			    url: 'getajaxquestions/surveyid/'+surveyid, 
			    data: { 
			    	surveyid : surveyid 
			    },
			    type: 'POST',
			    dataType: 'json',
			    success: function(data) {
			    	$('#tbody_fi_'+data_id).empty();
			    	if(data.state == "success"){
			    		var html ="";
			    		var preguntas = data.questions;
			    		var i = 1;
				        $.each(preguntas, function(k, v) {
				        	html += "<tr>";
				        	html += "<td>"+i+"</td>";
				        	html += "<td>"+preguntas[k]+"</td>";
				        	//html += "<td><input type='checkbox' required='required' name='vehicle' value='Bike'><br></td>";
				        	html += "</tr>";
				        	i++;
						});
						$('#tbody_fi_'+data_id).append(html);
			    	}
			    },
			    // código a ejecutar si la petición falla;
			    // son pasados como argumentos a la función
			    // el objeto de la petición en crudo y código de estatus de la petición
			    error: function(xhr, status) {
			    	$('#tbody_fi_'+data_id).empty();
			    },
			    // código a ejecutar sin importar si la petición falló o no
			    /*complete : function(xhr, status) {
			        alert('Petición realizada');
			    }*/
	        });
		});
		
	    
	});
</script>