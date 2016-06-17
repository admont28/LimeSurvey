<?php if (!defined('BASEPATH'))
    die('No direct script access allowed');?>
<?php if(isset($fuenteinformacion, $informacionadicional, $preguntas)): ?>
<?php echo CHtml::form(array('admin/teacherissues/sa/viewconfigurationinformationsources/informationsourceid/'.$fuenteinformacion->fuin_pk), 'post', array('id'=>'editfi', 'name'=>'editfi', 'class'=>'form-horizontal')); ?>
<div class='row group-container' id='row'>
	<div class='col-md-12 box'>
		<div class='header' style='text-align: left;'>
			<span class='groupTitle' id='titulo_fi' style='font-size: 1.5em;'>
				Fuente de información - <?php echo $fuenteinformacion->fuin_nombre; ?>
			</span>
			<span id='state_fi' style='margin-left: 10px; font-size: 1.2em;' class='label label-success'>
				Información almacenada.
			</span>
			<span class='glyphicon glyphicon-trash eliminar' style='float: right; cursor: pointer;' id='eliminar'></span>
			<span class='glyphicon glyphicon-pencil editar' style='float: right; cursor: pointer; margin-right: 10px;' id='editar'></span>
		</div>
	</div>
	<div class='col-md-12 questionContainer'>
		<div class='form-group'>
			<label for='nombre_fi' class='col-md-1'>Nombre: </label>
			<div class='col-md-7'>
				<input type='hidden' name='idfi' id='idfi' value='<?php echo $fuenteinformacion->fuin_pk;?>' />
				<input type='text' class='form-control nombrefi bloquear' name='nombre_fi' id='nombre_fi' required='required' disabled='disabled' maxlength='200' autofocus='autofocus' placeholder='Ingrese el nombre de la fuente de información' value="<?php echo $fuenteinformacion->fuin_nombre; ?>" style='color: #000;'/>
			</div>
			<label for='peso_fi' class='col-md-1'>Peso: </label>
			<div class='col-md-3'>
				<div class='input-group'>
			        <input min='0' max='100' step='1' placeholder='Peso de esta fuente de información' required='required' type='number' disabled='disabled' class='form-control currency pesofi bloquear' name='peso_fi' id='peso_fi' value='<?php echo $fuenteinformacion->fuin_peso ?>' style='color: #000;'/>
			        <span class='input-group-addon'>%</span>
		   		</div>
		   	</div>
		</div>
		<div class='form-group'>
			<label for='encuesta_fi' class='col-md-1'>Encuesta: </label>
			<div class='col-md-11 selects'>
					<select id='encuesta_fi' name='encuesta_fi' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' style='color: #000;'><?php echo getSurveyList(false,$fuenteinformacion->surv_fuin_fk); ?></select>
			</div>
		</div>
		<div class='form-group'>
			<label for='grupos_fi' class='col-md-6 col-md-offset-1'>¿Desea permitir la creación de grupos de encuestados para esta fuente de información?</label>
			<div class='col-md-2'>
				<label class='radio-inline'><input type='radio' class='gruposfi bloquear' disabled='disabled' name='grupos_fi' value='true' required='required' <?php if($fuenteinformacion->fuin_permitegrupos) echo "checked='checked'"; 	?> >Si</label>
				<label class='radio-inline'><input type='radio' class='gruposfi bloquear' disabled='disabled' name='grupos_fi'  value='false' required='required' <?php if(!$fuenteinformacion->fuin_permitegrupos) echo "checked='checked'"; 	?>>No</label>
			</div>
		</div>
		<div class='form-group'>
			<label class='col-md-2'>Información Adicional: </label>
			<span class='col-md-2 col-md-offset-8'>
				<button type='button' disabled='disabled' style='width: 100%;' class='adicionar_col btn btn-primary bloquear' id='adicionar_col_fi'>Adicionar columna</button>
			</span>
			<div class='col-md-12'>
				<div class='table-responsive'>
					<table class='table table-hover'>
						<thead id='thead_fi'>
							<tr>
								<th>ID</th>
								<th>Preguntas de la encuesta seleccionada</th>
								<?php foreach ($informacionadicional as $key => $value): ?>
									<th data-col='<?php echo $value->inad_pk; ?>'>
										<div class='input-group'>
				  							<input type='text' class='form-control columna bloquear' name='nombre_info_adicional' required='required' disabled='disabled' maxlength='200' autofocus='autofocus' placeholder='Nombre de la información adicional' style='color: #000;' value='<?php echo $value->inad_nombre; ?>'/>
				  							<span class='input-group-btn'>
				    							<button class='btn btn-default glyphicon glyphicon-trash eliminar_ia bloquear' disabled='disabled' id='eliminar_ia_<?php echo $value->inad_pk ?>' data-col='<?php echo $value->inad_pk; ?>' type='button'></button>
										    </span>
										</div>
									</th>
									
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody id='tbody_fi'>
							<?php foreach ($preguntas as $filas => $fila): ?>
								<tr>
									<td>
										<?php echo $fila['id']; ?>
									</td>
									<td id='<?php echo $fila['id']; ?>'>
										<?php echo $fila['pregunta'];?>
									</td>
									<?php foreach ($informacionadicional as $key => $value): ?>
										<td data-col='<?php echo $value->inad_pk; ?>'>
											<input class='checkboxbtn checkfi bloquear' type='checkbox' id='preg_' name='preg_' disabled='disabled'  <?php if(isset($fila['seleccionado'.$value->inad_pk]) && $fila['seleccionado'.$value->inad_pk] == true) echo "checked='checked'" ?> />
										</td>
									<?php endforeach;?>
								</tr>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
			<span class='col-md-2 col-md-offset-10'>
				<button type='submit' style='width: 100%;' class='btn btn-primary aceptar_fi bloquear' disabled='disabled' id='aceptar_fi' name="aceptar_fi">Aceptar</button>
			</span>
		</div>
	</div>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
	
	var iterador_columnas = 1;
	$('.editar').click(function(e){
	   	var container = $("#row");
	   	container.find(".bloquear").prop('disabled', false);
	   	$('#state_fi').removeClass('label-danger label-success');
		$('#state_fi').addClass('label-warning');
		$('#state_fi').text("Cambios realizados sin guardar.");
	   	$("#state").val("0");
	});
	
	$("#encuesta_fi").focus(function () {
        // Store the current value on focus, before it changes
        $('#encuesta_fi').data('lastSelected', $('#encuesta_fi').find('option:selected'));
    }).change(function(e){
		$("#encuesta_fi").unbind('focus');
		swal({
		  title: '¿Confirmación de acción?',
		  text: "Si selecciona otra encuesta perderá la información de las columnas que haya agregado, ¿Desea continuar?",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#22722b',
		  cancelButtonColor: '#a0352f',
		  confirmButtonText: 'Si, aceptar',
		  cancelButtonText: 'No, cancelar',
		  buttonsStyling: true
		}).then(function(isConfirm) {
			if (isConfirm === false) {
		  		$("#encuesta_fi").data('lastSelected').prop('selected', true);
		  		$("#encuesta_fi").bind('focus');
                return false;
		  	}
		  	else if(isConfirm === true) {
			    var surveyid = $('#encuesta_fi').val();
		        $('#thead_fi').empty();
		        $('#thead_fi').append("<tr><th>#</th><th>Preguntas de la encuesta seleccionada</th></tr>");
		        if(surveyid != ""){
		        	$("button[id='adicionar_col_fi").attr("disabled", false);
		        }
		        else{
		        	$("button[id='adicionar_col_fi").attr("disabled", true);
		        }
		        $.ajax({
				    url: '../../getajaxquestions/surveyid/'+surveyid, 
				    data: { 
				    	surveyid : surveyid 
				    },
				    type: 'POST',
				    dataType: 'json',
				    success: function(data) {
				    	$('#tbody_fi').empty();
				    	if(data.state == "success"){
				    		var html = "";
				    		var preguntas = data.questions;
				    		var iterador = 1;
					        $.each(preguntas, function(k, v) {
					        	html += "<tr>";
					        	html += "<td>"+iterador+"</td>";
					        	html += "<td id='"+v.id+"' >"+v.pregunta+"</td>";
					        	html += "</tr>";
					        	iterador++;
							});
							$('#tbody_fi').append(html);
				    	}
				    	$("#encuesta_fi").bind('focus');
						$('#encuesta_fi').data('lastSelected', $('#encuesta_fi').find('option:selected'));
						return true;
				    },
				    // código a ejecutar si la petición falla;
				    // son pasados como argumentos a la función
				    // el objeto de la petición en crudo y código de estatus de la petición
				    error: function(xhr, status) {
				    	$('#tbody_fi').empty();
				    },
		        });
		  	} else {
			    // Esc, close button or outside click
			    // isConfirm is undefined
		   		$("#encuesta_fi").data('lastSelected').prop('selected', true);
		  		$("#encuesta_fi").bind('focus');
                return false;
		  	}
		});
	});
	$('#adicionar_col_fi').click(function(e){
	   	var head = "<th><input type='text' class='form-control columna bloquear' name='nombre_info_adicional_fi' required='required' maxlength='200' autofocus='autofocus' placeholder='Nombre de la columna'/></th>";
	   	var head = "<th data-col='"+iterador_columnas+"'>"+
						"<div class='input-group'>"+
  							"<input type='text' class='form-control columna bloquear' name='nombre_info_adicional_fi' required='required' maxlength='200' autofocus='autofocus' placeholder='Nombre de la columna'/>"+
  							"<span class='input-group-btn'>"+
    							"<button class='btn btn-default glyphicon glyphicon-trash eliminar_ia bloquear' id='eliminar_ia_"+iterador_columnas+"' data-col='"+iterador_columnas+"' type='button'></button>"+
						    "</span>"+
						"</div>"+
  					"</th>";
	   	var trs_body = $('#tbody_fi > tr');
	   	$.each(trs_body, function(k, v) {
        	var input = "<td data-col='"+iterador_columnas+"'>"+
        		"<input class='checkboxbtn checkfi bloquear' type='checkbox' id='preg_col_"+iterador_columnas+"_fi' name='preg_col_"+iterador_columnas+"_fi' /></td>";
        	$(v).append(input);
		});
	   	$('#thead_fi > tr').append(head);
	   	iterador_columnas++
	});

	$('.eliminar_ia').click(function(event){
		var id = $(this).attr("id");
		var col = $("#"+id).data('col');
		swal({
		  title: '¿Eliminar?',
		  text: "¿Realmente está seguro de eliminar la información adicional y todas sus asociaciones con las preguntas? Esta acción no se podrá deshacer.",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#22722b',
		  cancelButtonColor: '#a0352f',
		  confirmButtonText: 'Si, eliminar',
		  cancelButtonText: 'No, cancelar',
		  buttonsStyling: true
		}).then(function(isConfirm) {
			if (isConfirm === true) {
				$("td[data-col='"+col+"']").remove();
				$("th[data-col='"+col+"']").remove();
                return true;
		  	}
		});
	});

	$('#editfi').submit(function(e){
		e.preventDefault();
		var enpaquetado = {};
	  	var id_plantilla = <?php echo $fuenteinformacion->plev_fuin_fk ?>;
		enpaquetado['id_plantilla'] = id_plantilla;
		// Recorro container que será cada uno de las fuentes de información que existan.
		fuentesinformacion = new Array();
    	var nombrefi = $("#nombre_fi").val();
    	var pesofi = $("#peso_fi").val();
    	var encuestafi = $("#encuesta_fi").val();
    	var gruposfi = $('input:radio[name=grupos_fi]:checked').val();
    	var idfi = $('#editfi').find("input[name='idfi']").val();
    	var fi = {};
    	fi['nombre_fi'] = nombrefi;
    	fi['peso_fi'] = pesofi;
    	fi['encuesta_fi'] = encuestafi;
    	fi['grupos_fi'] = gruposfi;
    	fi['id_fi'] = idfi;
    	var informacionadicional = new Array();
    	// Encuentro las columnas de la información adicional que se hayan agregado
    	// con la expresión: columnasfi.length se obtiene la cantida de columnas
    	var columnasfi = $("#editfi").find(".columna");
    	// Capturo los tr de la tabla de una fuente de información, pero solo los del cuerpo de la tabla.
    	var trs_body = $("#editfi").find("tbody tr");
    	for (var i=2; i<columnasfi.length + 2; i++) {
        	var nombrecolumna = $("#editfi").find("th").eq(i).find(".columna").val();
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
		enpaquetado['fi'] = fuentesinformacion;
		var empaquetadojson = JSON.stringify(enpaquetado);
		$.ajax({
			url: '../../configurationinformationsource/templateid/'+id_plantilla,
		    data: { 
		    	'plantilla' : empaquetadojson 
		    },
		    type: 'POST',
		    dataType: 'json',
		    success: function(data) {
		    	if(data.state=="success"){
	    			$("#idfi").val(data.fuin_pk);
		    		$('#editfi').find(".bloquear").prop('disabled', true);
		    		$('#state_fi').removeClass('label-danger label-warning');
		    		$('#state_fi').addClass('label-success');
		    		$('#state_fi').text("Almacenado con éxito.");
		    	}else if(data.state == "error"){
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
	$('#eliminar').click(function(e){
		e.preventDefault();
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
			if (isConfirm === true) {
		  		var id = $(this).attr("id");
			   	var data_eliminar = $('#'+id).data('eliminar');
			   	var numero_fi = $('#'+id).data('fi');
			   	var idfi = $("#idfi").val();
			   	var row = $('#'+data_eliminar);
			   	if(idfi != null && idfi != ""){
			   		$.ajax({
						url: '../../configurationinformationsource/templateid/<?php echo $fuenteinformacion->plev_fuin_fk; ?>',
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
									window.location.href = "../../configurationinformationsource/templateid/<?php echo $fuenteinformacion->plev_fuin_fk; ?>";
								});
					    	}else if (data.state == "error"){
					    		swal({
								  title: 'Oops... Ha ocurrido un error',
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
					    },
					});
			   	}
		  	}
		});
	});
</script>
<?php endif; ?>