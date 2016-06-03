<?php 
if (!defined('BASEPATH'))
    die('No direct script access allowed');
$pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);?>
<h3 class="pagetitle">Plantilla: <?php echo $nombre_plantilla ?> - Configuración de las Fuentes de información</h3>
<!-- Form submited by save buton menu bar -->
    <div class='col-sm-12 col-md-12 col-xs-12'>
	<p>Para adicionar una fuente de información presione el botón: <span class="text-warning">Adicionar fuente de información</span>, complete los campos y luego presione el botón aceptar, <span class="text-warning">debe haber guardado todos los cambios antes de agregar una nueva fuente de información.</span></p>
	<?php if(isset($fuentesinformacion) && sizeof($fuentesinformacion) > 0): ?>
			<h4 class="pagetitle">Fuentes de información existentes</h4>
			<?php $this->widget('bootstrap.widgets.TbGridView', 
				array(
				    'dataProvider' => $fuentesinformacion,
				    'id' => 'fi-grid',
				    'columns' => array(
				        array(
				        	'header' => 'Fuente de información',
				            'name' => 'fiun_nombre',
				            'type' => 'raw',
				            'value' => '$data->fuin_nombre'
				        ),
				        array(
				            'header' => 'ID encuesta',
				            'name' => 'surv_fuin_fk',
				            'type' => 'raw',
				            'value' => '$data->surv_fuin_fk'
				        ),
				        array(
				            'header' => 'Peso',
				            'name' => 'fuin_peso',
				            'type' => 'raw',
				            'value' => '$data->fuin_peso'
				        ),
				        array(
				            'header' => '¿Permite grupos?',
				            'name' => 'fuin_permitegrupos',
				            'type' => 'raw',
				            'value' => '($data->fuin_permitegrupos) ? "Si": "No"'
				        ),
				    ),
				    'htmlOptions'=>array('style'=>'cursor: pointer;', 'class'=>'hoverAction'),
	                'selectionChanged'=>"function(id){window.location='" . Yii::app()->urlManager->createUrl('admin/teacherissues/sa/viewconfigurationinformationsources/informationsourceid' ) . '/' . "' + $.fn.yiiGridView.getSelection(id.split(',', 1));}",
	                'ajaxUpdate' => true,
	                'afterAjaxUpdate' => 'doToolTip',
				)
			);?>
			
	<?php endif;?>	
	<h4 class="pagetitle">Adición de nuevas fuentes de información</h4>
        <!-- Text elements -->
        <div class="row">
			<div class="alert alert-warning" id="mensaje_general" style="display: none"></div>
            <div class="form-group">
            	<div class="col-md-4 col-md-offset-4 col-sm-5 col-sm-offset-4">
					<button type="button" class="btn btn-primary col-xs-10 col-xs-offset-1" id="adicionar_fi">Adicionar fuente de información</button>
				</div>
            </div>
			
			<div id="main-container" style="margin-bottom: 15px;">
				
			</div>
        </div>
    </div>
    <input type="hidden" readonly="readonly" id="state" name="state" value="1"></input>
	<input type="hidden" readonly="readonly" id="id_plantilla" name="id_plantilla" value="<?php echo $id_plantilla; ?>">

<?php 
// Eliminar los saltos de línea para poder ser concatenados al String que contiene las etiquetas html
$surveys = preg_replace("[\n|\r|\n\r]", "", getSurveyList(false)); ?>
<?php $form =  CHtml::form(array(''), 'post', array('id'=>'addfi', 'name'=>'addfi', 'class'=>'form-horizontal formfi')); ?>
<?php $form = str_replace("\"","'",$form);  ?>
<?php $form = preg_replace("[\n|\r|\n\r]", "", $form); ?>
<script type="text/javascript">
	$(document).ready(function() {
			var i = 1;
			var iterador_columnas = 1;
			var select = "<?php echo $surveys; ?>";

		$('#adicionar_fi').click(function () {
			var estado_formulario = $("#state").val();
			if(estado_formulario == 1){
				var html = "<?php echo $form; ?>"+
						"<div class='row group-container' id='row_"+i+"' data-fi='"+i+"'>"+
							"<div class='col-md-12 box'>"+
								"<div class='header' style='text-align: left;'>"+
									"<span class='groupTitle' id='titulo_fi_"+i+"' style='font-size: 1.5em;'>"+
										"Fuente de información - "+i+
									"</span>"+
									"<span id='state_fi_"+i+"' style='margin-left: 10px; font-size: 1.2em;' class='label label-danger'>Información sin almacenar.</span>"+
									"<span class='glyphicon glyphicon-trash eliminar' style='float: right; cursor: pointer;' id='eliminar_"+i+"' data-eliminar='row_"+i+"' data-fi='"+i+"'></span>"+
									"<span class='glyphicon glyphicon-pencil editar' style='float: right; cursor: pointer; margin-right: 10px;' id='editar_"+i+"' data-fi='"+i+"'></span>"+
								"</div>"+
							"</div>"+
							"<div class='col-md-12 questionContainer'>"+
								"<div class='form-group'>"+
									"<label for='nombre_fi_"+i+"' class='col-md-1'>Nombre: </label>"+
					    			"<div class='col-md-7'>"+
					    				"<input type='hidden' name='idfi' id='idfi_"+i+"' value='' />"+
					    				"<input type='text' class='form-control nombrefi bloquear' name='nombre_fi_"+i+"' id='nombre_fi_"+i+"' data-fi='"+i+"' required='required' maxlength='200' autofocus='autofocus' placeholder='Ingrese el nombre de la fuente de información'/>"+
					    			"</div>"+
					    			
					    			"<label for='peso_fi_"+i+"' class='col-md-1'>Peso: </label>"+
					    			"<div class='col-md-3'>"+
		           						"<div class='input-group'>"+
								        	"<input min='1' max='100' step='1' placeholder='Peso de esta fuente de información' required='required' type='number' class='form-control currency pesofi bloquear' name='peso_fi_"+i+"' />"+
								        	"<span class='input-group-addon'>%</span>"+
								   		"</div>"+
								   	"</div>"+
								"</div>"+
								"<div class='form-group'>"+
			           				"<label for='encuesta_fi_"+i+"' class='col-md-1'>Encuesta: </label>"+
			           				"<div class='col-md-11 selects'>"+
			           						"<select id='encuesta_fi_"+i+"' data-id='"+i+"' name='encuesta_fi_"+i+"' required='required' class='form-control survey encuestafi bloquear'>"+select+"</select>"+
		           					"</div>"+
		           				"</div>"+
		           				"<div class='form-group'>"+
		           					"<label for='grupos_fi_"+i+"' class='col-md-6 col-md-offset-1'>¿Desea permitir la creación de grupos de encuestados para esta fuente de información?</label>"+
			           				"<div class='col-md-2'>"+
			           					"<label class='radio-inline'><input type='radio' class='gruposfi bloquear' name='grupos_fi_"+i+"' value='true' required>Si</label>"+
										"<label class='radio-inline'><input type='radio' name='grupos_fi_"+i+"' class='gruposfi bloquear' value='false' >No</label>"+
		           					"</div>"+
		           				"</div>"+
            					"<div class='form-group'>"+
            						"<label class='col-md-2'>Información Adicional: </label>"+
            						"<span class='col-md-2 col-md-offset-8'>"+
            							"<button type='button' disabled='disabled' style='width: 100%;' class='adicionar_col btn btn-primary bloquear' data-id='"+i+"' id='adicionar_col_fi_"+i+"'>Adicionar columna</button>"+
            						"</span>"+
									"<div class='col-md-12'>"+
										"<div class='table-responsive'>"+
		            						"<table class='table table-hover'>"+
		            							"<thead id='thead_fi_"+i+"'>"+
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
            							"<button type='submit' style='width: 100%;' class='btn btn-primary aceptar_fi bloquear' id='aceptar_fi_"+i+" data-fi='"+i+"'>Aceptar</button>"+
            						"</span>"+
            					"</div>"+
							"</div>"+
						"</div>"+
						"</form>";
				$('#main-container').append(html);
				$("#state").val("0");
				$('#mensaje_general').hide();
				i++;
			}else{
				$('#mensaje_general').show();
				$('#mensaje_general').text("Debe guardar todos los cambios antes de adicionar una nueva fuente de información");
			}
		});
		var f_focus = function () {
	        // Store the current value on focus, before it changes
	        $(this).data('lastSelected', $(this).find('option:selected'));
	    };
		$('#main-container').delegate('.survey', 'focus', f_focus);

		var f_change = function (){
			var objeto = $(this);
			$('#main-container').off("focus", ".survey");
			swal({
			  title: '¿Confirmación de acción?',
			  text: "Si selecciona otra encuesta perderá la información de las columnas que haya agregado, ¿Desea continuar?",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#22722b',
			  cancelButtonColor: '#a0352f',
			  confirmButtonText: 'Si, aceptar',
			  cancelButtonText: 'No, cancelar',
			  //confirmButtonClass: 'btn btn-success btn-lg',
			  //cancelButtonClass: 'btn btn-danger btn-lg',
			  buttonsStyling: true
			}).then(function(isConfirm) {
				if (isConfirm === false) {
			  		objeto.data('lastSelected').prop('selected', true);
			  		$('#main-container').on("focus", ".survey", f_focus);
	                return false;
			  	}
			  	else if(isConfirm === true) {
				    var id = objeto.attr("id");
				   	var data_id = $('#'+id).data('id');
			        var surveyid = $('#'+id).val();
			        $('#thead_fi_'+data_id).empty();
			        $('#thead_fi_'+data_id).append("<tr><th>#</th><th>Preguntas de la encuesta seleccionada</th></tr>");
			        if(surveyid != ""){
			        	$("button[id='adicionar_col_fi_"+data_id+"']").attr("disabled", false);
			        }
			        else{
			        	$("button[id='adicionar_col_fi_"+data_id+"']").attr("disabled", true);
			        }
			        $.ajax({
					    url: '../../getajaxquestions/surveyid/'+surveyid, 
					    data: { 
					    	surveyid : surveyid 
					    },
					    type: 'POST',
					    dataType: 'json',
					    success: function(data) {
					    	$('#tbody_fi_'+data_id).empty();
					    	if(data.state == "success"){
					    		var html = "";
					    		var preguntas = data.questions;
					    		var iterador = 1;
						        $.each(preguntas, function(k, v) {
						        	html += "<tr data-fi='"+data_id+"'>";
						        	html += "<td>"+iterador+"</td>";
						        	html += "<td id='"+v.id+"' >"+v.pregunta+"</td>";
						        	html += "</tr>";
						        	iterador++;
								});
								$('#tbody_fi_'+data_id).append(html);
					    	}
							objeto.data('lastSelected', objeto.find('option:selected'));
							$('#main-container').on("focus", ".survey", f_focus);
							return true;
					    },
					    // código a ejecutar si la petición falla;
					    // son pasados como argumentos a la función
					    // el objeto de la petición en crudo y código de estatus de la petición
					    error: function(xhr, status) {
					    	$('#tbody_fi_'+data_id).empty();
					    },
		        	});
			  	} else {
				    // Esc, close button or outside click
				    // isConfirm is undefined
			   		objeto.data('lastSelected').prop('selected', true);
			  		$('#main-container').on("focus", ".survey", f_focus);
	                return false;
			  }
			});
		};
	    $('#main-container').delegate('.survey', 'change', f_change);
		
		$('#main-container').delegate('.eliminar', 'click', function(e){
			e.preventDefault();
			var id = $(this).attr("id");
		   	var data_eliminar = $('#'+id).data('eliminar');
		   	var numero_fi = $('#'+id).data('fi');
		   	var idfi = $("#idfi_"+numero_fi).val();
		   	var row = $('#'+data_eliminar);
			swal({
			  title: '¿Eliminar?',
			  text : '¿Realmente está seguro de eliminar la fuente de información? Esta acción no se podrá deshacer.',
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#22722b',
			  cancelButtonColor: '#a0352f',
			  confirmButtonText: 'Si, eliminar',
			  cancelButtonText: 'No, cancelar',
			  buttonsStyling: true
			}).then(function(isConfirm) {
				if(isConfirm === true){
				   	if(idfi == ""){
				   		swal({
						  title: '¡Eliminación con éxito!',
						  text : 'Se ha eliminado la fuente de información con éxito.',
						  type: 'success',
						  showCancelButton: false,
						  confirmButtonColor: '#22722b',
						  confirmButtonText: 'OK',
						  buttonsStyling: true
						}).then(function(isConfirm) {
							$(row).parent().remove();
	   						$("#state").val("1");
						});
				   	}
				   	if(idfi != null && idfi != ""){
				   		$.ajax({
							url: '../../configurationinformationsource/templateid/'.idfi,
						    data: { 
						    	"informationsourceid" : idfi,
						    	"actiondel" : "actiondel"
						    },
						    type: 'POST',
						    dataType: 'json',
						    success: function(data) {
						    	if(data.state=="success"){
						    		swal({
									  title: '¡Eliminación con éxito!',
									  text : 'Se ha eliminado la fuente de información con éxito.',
									  type: 'success',
									  showCancelButton: false,
									  confirmButtonColor: '#22722b',
									  confirmButtonText: 'OK',
									  buttonsStyling: true
									}).then(function(isConfirm) {
										$(row).parent().remove();
				   						$("#state").val("1");
									});
						    	}else if (data.state == "error"){
						    		removercontenedor = false;
						    		swal({
									  title: 'Oops... ¡Ha ocurrido un error!',
									  text : 'Ha sucedido un error al eliminar la fuente de informacion.',
									  type: 'error',
									  confirmButtonColor: '#22722b',
									  confirmButtonText: 'OK',
									  buttonsStyling: true
									});
						    	}
						    },
						    // código a ejecutar si la petición falla;
						    // son pasados como argumentos a la función
						    // el objeto de la petición en crudo y código de estatus de la petición
						    error: function(xhr, status) {
						    	swal({
									  title: 'Oops... ¡Ha ocurrido un error!',
									  text : 'Ha sucedido un error al eliminar la fuente de informacion.',
									  type: 'error',
									  confirmButtonColor: '#22722b',
									  confirmButtonText: 'OK',
									  buttonsStyling: true
									});
						    },
						});
				   	}
				}
			});	
		});

		$('#main-container').delegate('.editar', 'click', function(e){
			var id = $(this).attr("id");
		   	var numero_fi = $('#'+id).data('fi');
		   	var idfi = $("#idfi_"+numero_fi).val();
		   	if(idfi != ""){
		   		var container = $("#row_"+numero_fi);
			   	container.find(".bloquear").prop('disabled', false);
			   	$('#state_fi_'+numero_fi).removeClass('label-danger label-success');
	    		$('#state_fi_'+numero_fi).addClass('label-warning');
	    		$('#state_fi_'+numero_fi).text("Cambios realizados sin guardar.");
			   	$("#state").val("0");
		   	}
		});

		$('#main-container').delegate('.adicionar_col', 'click', function(e){
			// Id del botón que se le hace click, hace referencia a la fuente de información
			var id = $(this).attr("id");
			// Obtengo el id de la fuente de información: ejm: 1, 4, 5..
		   	var data_id = $('#'+id).data('id');
		   	var head = "<th data-col='"+iterador_columnas+"'>"+
						"<div class='input-group'>"+
  							"<input type='text' class='form-control columna bloquear' name='nombre_info_adicional_fi_"+data_id+"_col_"+iterador_columnas+"' required='required' maxlength='200' autofocus='autofocus' placeholder='Nombre de la columna'/>"+
  							"<span class='input-group-btn'>"+
    							"<button class='btn btn-default glyphicon glyphicon-trash eliminar_ia bloquear' id='eliminar_ia_"+iterador_columnas+"' data-col='"+iterador_columnas+"' type='button'></button>"+
						    "</span>"+
						"</div>"+
  					"</th>";
		   	var trs_body = $('#tbody_fi_'+data_id+' > tr');
		   	$.each(trs_body, function(k, v) {
	        	var input = "<td data-col='"+iterador_columnas+"'>"+
	        		"<input class='checkboxbtn checkfi bloquear' type='checkbox' id='preg_col_"+iterador_columnas+"_fi_"+data_id+"' name='preg_col_"+iterador_columnas+"_fi_"+data_id+"' /></td>";
	        	$(v).append(input);
			});
		   	$('#thead_fi_'+data_id+' > tr').append(head);
		   	iterador_columnas++
		});

		$('#main-container').delegate('.eliminar_ia', 'click', function(event){
			var id = $(this).attr("id");
			var col = $("#"+id).data('col');
			$("td[data-col='"+col+"']").remove();
			$("th[data-col='"+col+"']").remove();
		});

		$('#main-container').delegate('.formfi', 'submit', function(event){
			event.preventDefault();
		  	var enpaquetado = {};
		  	var id_plantilla = $('#id_plantilla').val();
			var container = $(this).children(".group-container");
			var numero_fi = container.data("fi");
			enpaquetado['id_plantilla'] = id_plantilla;
			// Recorro container que será cada uno de las fuentes de información que existan.
			fuentesinformacion = new Array();
			$.each(container, function(k, filafi) {
	        	var nombrefi = $(filafi).find(".nombrefi").val();
	        	var pesofi = $(filafi).find(".pesofi").val();
	        	var encuestafi = $(filafi).find(".encuestafi").val();
	        	var gruposfi = $(filafi).find("input:checked").val();
	        	var idfi = $(filafi).find("input[name='idfi']").val();
	        	var fi = {};
	        	fi['nombre_fi'] = nombrefi;
	        	fi['peso_fi'] = pesofi;
	        	fi['encuesta_fi'] = encuestafi;
	        	fi['grupos_fi'] = gruposfi;
	        	fi['id_fi'] = idfi;
	        	var informacionadicional = new Array();
	        	
	        	// Encuentro las columnas de la información adicional que se hayan agregado
	        	// con la expresión: columnasfi.length se obtiene la cantida de columnas
	        	var columnasfi = $(filafi).find(".columna");
	        	// Capturo los tr de la tabla de una fuente de información, pero solo los del cuerpo de la tabla.
	        	var trs_body = $(filafi).find("tbody tr");
	        	for (var i=2; i<columnasfi.length + 2; i++) {
		        	var nombrecolumna = $(this).find("th").eq(i).find(".columna").val();
		        	var preguntas = [];
		        	$(trs_body).each(function (index) {
        				var seleccionado = $(this).find("td").eq(i).find("input:checked").val();
						if(seleccionado == "on"){
							var pregid = $(this).find("td").eq(1).attr("id");
							preguntas.push(pregid);
						}
        			});
        			var ia = {};
        			ia['nombre'] = nombrecolumna;
		        	ia['preguntas'] = preguntas;
		        	informacionadicional.push(ia);
				}
				fi['ia'] = informacionadicional;
				fuentesinformacion.push(fi);
			});
			enpaquetado['fi'] = fuentesinformacion;
			var empaquetadojson = JSON.stringify(enpaquetado);
			$.ajax({
				url: '../../configurationinformationsource/templateid/<?php echo $id_plantilla; ?>',
			    data: { 
			    	'plantilla' : empaquetadojson 
			    },
			    type: 'POST',
			    dataType: 'json',
			    success: function(data) {
			    	if(data.state=="success"){
		    			$("#idfi_"+numero_fi).val(data.fuin_pk);
			    		container.find(".bloquear").prop('disabled', true);
			    		container.find("input").prop('style', "color: #000;");
			    		container.find("select").prop('style', "color: #000;");
			    		$('#state_fi_'+numero_fi).removeClass('label-danger label-warning');
			    		$('#state_fi_'+numero_fi).addClass('label-success');
			    		$('#state_fi_'+numero_fi).text("Almacenado con éxito.");
			    		$('#mensaje_general').hide();
			    		$("#state").val("1");
			    		$('#main-container').on("focus", ".survey");
			    	}
			    	else if(data.state == "error"){
			    		if(typeof data.message != "undefined"){
			    			swal({
								title: 'Oops... ¡Ha ocurrido un error!',
								text : data.message,
								type: 'error',
								confirmButtonText: 'OK',
								buttonsStyling: true
							});
			    		}
			    	}
			    },
			    // código a ejecutar si la petición falla;
			    // son pasados como argumentos a la función
			    // el objeto de la petición en crudo y código de estatus de la petición
			    error: function(xhr, status) {
			    },
			});
		});
		
   		$('#main-container').delegate('.nombrefi', 'change', function(event){
      		var id = $(this).attr("id");
			var fi = $("#"+id).data('fi');
			var texto = $("#"+id).val();
			if(texto == "")
				$("#titulo_fi_"+fi).text("Fuente de información - "+fi);
			else
				$("#titulo_fi_"+fi).text("Fuente de información - "+texto);
        });
	});

 	jQuery(function($) {
        jQuery(document).on("change", '#pageSize', function(){
            $.fn.yiiGridView.update('fi-grid',{ data:{ pageSize: $(this).val() }});
        });
    });
</script>