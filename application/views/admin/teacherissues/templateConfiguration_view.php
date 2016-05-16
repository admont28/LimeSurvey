<?php 

?>

<h2 class="pagetitle">Configuración global de la plantilla de evaluación de desempeño</h2>
<!-- Form submited by save buton menu bar -->
<?php echo CHtml::form(array('admin/teacherissues/sa/templateconfiguration'), 'post', array('id'=>'addnewtemplate', 'name'=>'addnewtemplate', 'class'=>'form-horizontal')); ?>
    <div class='col-sm-12 col-md-12 col-xs-12'>

        <!-- Text elements -->
        <div class="row">

            <!-- Tipo de evaluación -->
            <div class="form-group">
            	<label for="tipo_evaluacion" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Nombre</label>
            	<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12">
            		<?php echo CHtml::textField("nombre_plantilla","",array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"nombre_plantilla", "placeholder" => "Ingrese el nombre de la plantilla")); ?>
            	</div>
            </div>
			<h3 class="pagetitle">Fuentes de información</h3>
            <div class="form-group">
            	<div class="col-md-4 col-md-offset-4 col-sm-5 col-sm-offset-4">
					<button type="button" class="btn btn-primary col-xs-10 col-xs-offset-1" id="adicionar_fi">Adicionar fuente de información</button>
				</div>
            </div>
			
			<div id="main-container" style="margin-bottom: 15px;">
				
			</div>

            <div class="form-group">
			    <div class="col-sm-2 col-sm-offset-10">
			      <button type="submit" name="save"  class="btn btn-success col-xs-12" value='save'><?php eT("Save"); ?></button>
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

<?php 
// Eliminar los saltos de línea para poder ser concatenados al String que contiene las etiquetas html
$surveys = preg_replace("[\n|\r|\n\r]", "", getSurveyList(false)); ?>
<script type="text/javascript">
	$(document).ready(function() {
			var i = 1;
			var select = "<?php echo $surveys; ?>";

		$('#adicionar_fi').click(function () {
			var html = "<div class='row group-container' id='row_"+i+"'>"+
							"<div class='col-md-12 box'>"+
								"<div class='header' style='text-align: left;'>"+
									"<span class='groupTitle' style='font-size: 1.5em;'>"+
										"Fuente de información - "+i+
									"</span>"+
									"<span class='glyphicon glyphicon-trash eliminar' style='float: right; cursor: pointer;' id='eliminar_"+i+"'' data-eliminar='row_"+i+"'></span>"+
									"<span class='glyphicon glyphicon-pencil editar' style='float: right; cursor: pointer; margin-right: 10px;' id='editar_"+i+"'' data-editar='row_"+i+"'></span>"+
								"</div>"+
							"</div>"+
							"<div class='col-md-12 questionContainer'>"+
								"<div class='form-group'>"+
									"<label for='nombre_fi_"+i+"' class='col-md-1'>Nombre: </label>"+
					    			"<div class='col-md-7'>"+
					    				"<input type='text' class='form-control' name='nombre_fi_"+i+"' required='required' maxlength='200' autofocus='autofocus' placeholder='Ingrese el nombre de la fuente de información'/>"+
					    			"</div>"+
					    			
					    			"<label for='peso_fi_"+i+"' class='col-md-1'>Peso: </label>"+
					    			"<div class='col-md-3'>"+
		           						"<div class='input-group'>"+
								        	"<input min='0' max='100' step='1' placeholder='Peso de esta fuente de información' required='required' type='number' class='form-control currency' name='peso_fi_"+i+"' />"+
								        	"<span class='input-group-addon'>%</span>"+
								   		"</div>"+
								   	"</div>"+
								"</div>"+
								"<div class='form-group'>"+
			           				"<label for='encuesta_fi_"+i+"' class='col-md-1'>Encuesta: </label>"+
			           				"<div class='col-md-11 selects'>"+
			           						"<select id='encuesta_fi_"+i+"' data-id='"+i+"' name='encuesta_fi_"+i+"' required='required' class='form-control survey'>"+select+"</select>"+
		           					"</div>"+
		           				"</div>"+
		           				"<div class='form-group'>"+
		           					"<label for='grupos_fi_"+i+"' class='col-md-6 col-md-offset-1'>¿Desea permitir la creación de grupos de encuestados para esta fuente de información?</label>"+
			           				"<div class='col-md-2'>"+
			           					"<label class='radio-inline'><input type='radio' name='grupos_fi_"+i+"' value='si' required>Si</label>"+
										"<label class='radio-inline'><input type='radio' name='grupos_fi_"+i+"' value='no' >No</label>"+
		           					"</div>"+
		           				"</div>"+
            					"<div class='form-group'>"+
            						"<label class='col-md-2'>Información Adicional: </label>"+
            						"<span class='col-md-2 col-md-offset-8'>"+
            							"<button type='button' disabled='disabled' style='width: 100%;' data-toggle='modal' data-target='#modal_fuente_informacion' class='btn btn-primary agregar_ia' id='adicionar_fi_"+i+"'>Adicionar columna</button>"+
            						"</span>"+
									"<div class='col-md-12'>"+
										"<div class='table-responsive'>"+
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
		            				"<span class='col-md-2 col-md-offset-10'>"+
            							"<button type='button' style='width: 100%;' class='btn btn-primary aceptar_fi_"+i+"' id='aceptar_fi_"+i+"'>Aceptar</button>"+
            						"</span>"+
            					"</div>"+
							"</div>"+
						"</div>";
			$('#main-container').append(html);
			i++;
		});

		$('#main-container').delegate('.survey', 'change', function(e){
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
		
		$('#main-container').delegate('.eliminar', 'click', function(e){
			var id = $(this).attr("id");
		   	var data_eliminar = $('#'+id).data('eliminar');
		   	$('#'+data_eliminar).remove();
		});
	});
</script>