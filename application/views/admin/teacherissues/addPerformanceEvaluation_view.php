<?php ?>
<h2 class="pagetitle">Creación de evaluación de desempeño</h2>
<?php echo CHtml::form(array('admin/teacherissues/sa/performanceevaluation'), 'post', array('id'=>'addnewperformanceevaluation', 'name'=>'addnewperformanceevaluation', 'class'=>'form-horizontal')); ?>
	<div class='col-sm-12 col-md-12 col-xs-12'>

      <!-- Text elements -->
      <div class="row">
         <!-- Plantilla de evaluación de desempeño -->
         <div class="form-group">
            <label for="plantilla_evaluado" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Plantilla de evaluación: </label>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
               <select name="plantilla_evaluado" required="required" class="form-control" id="plantilla_evaluado">
                  <option selected="selected" value="">Por favor seleccione...</option>
                  <?php foreach ($plantillas as $p): ?>
                     <option value="<?php echo $p->plev_pk; ?>"><?php echo $p->plev_nombre; ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>
         <!-- Facultades -->
         <div class="form-group">
            <label for="facultad" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Facultad: </label>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
               <?php echo CHtml::dropDownList("facultad","",$facultades,array('class'=>'form-control', 'min'=>0, 'required'=>'required','id'=>"facultad", "disabled" => "disabled")); ?>
            </div>
         </div>
         <!-- Programas -->
         <div class="form-group">
            <label for="programa" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Programa: </label>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
               <select name="programa"  id="programa" required="required" class="form-control bloquear" disabled="disabled">
               </select>
            </div>
         </div>
         <!-- Datos del evaluado -->
         <div class="form-group">
            <label for="tipo_identificacion" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Tipo de identificación: </label>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
               <?php echo CHtml::dropDownList("tipo_identificacion","",$tipo_documentos,array('class'=>'form-control', 'min'=>0, 'required'=>'required','id'=>"tipo_identificacion")); ?>
            </div>
            <label for="identificacion" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 control-label">Identificación: </label>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
               <?php echo CHtml::numberField("identificacion","",array('class'=>'form-control', 'min'=>0, 'required'=>'required','autofocus'=>'autofocus','id'=>"identificacion", "placeholder" => "Ingrese el número de identificación del evaluado","title" => "Introduzca el número de identificación de la persona a evaluar.")); ?>
               <p class="text-warning" id="mensaje_identificacion">Obligatorio</p>
            </div>
            <div class="col-md-1 col-lg-1 col-sm-1 col-xs-12">
               <button class="btn btn-success btn-block" type="button" id="buscar_evaludo">Buscar</button>
            </div>
         </div>
         <div class="form-group">
         	<label for="nombre_completo" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Nombre completo: </label>
         	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
         		<?php echo CHtml::textField("nombre_completo","",array('class'=>'form-control','maxlength'=>"200",'required'=>'required','autofocus'=>'autofocus','id'=>"nombre_completo", "disabled" => "disabled", "style" => "color: black;", "title" => "Nombre completo de la persona a evaluar, este será obtenido si el número del documento es encontrado.")); ?>
         	</div>
         </div>
         <h3 class="pagetitle">Fuentes de información</h3>
         <div id="main-container">
            
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
<script type="text/javascript">
   var tipolabor = 0;
   $("#plantilla_evaluado").change(function(e){
      $("#main-container").html("");
      $('#facultad').prop("disabled", true);
      $("#programa").html("");
      $('#programa').prop("disabled", true);
      $("#nombre_completo").val("");
      $('#facultad > option[value=""]').prop('selected', true);
      var idplantilla = $("#plantilla_evaluado").val();
      if(idplantilla != ""){
         $.ajax({
            url: 'gettemplateinformation',
            data: {
               'idplantilla': idplantilla
            },
            type: "POST",
            dataType: "JSON",
            success: function(data){
               if(data.state == "success"){
                  if(data.tipolabor == 5){
                     $('#facultad').prop("disabled", true);
                     $("#programa").html("");
                     $('#programa').prop("disabled", true);
                  }else{
                     $('#facultad').prop("disabled", false);
                     $("#programa").html("");
                     $('#programa').prop("disabled", true);
                  }
                  tipolabor = data.tipolabor;
                  var fuentesinformacion = data.fuentesinformacion;
                  $.each(fuentesinformacion, function (index, value) { 
                     var fuin_nombre = value.fuin_nombre;
                     var fuin_permitegrupos = value.fuin_permitegrupos;
                     var fuin_peso = value.fuin_peso;
                     var sid = value.survey.surv_fuin_fk;
                     var surveytitle = value.survey.nombre;
                     var ias = value.ia;
                     var preguntas_encuesta = value.preguntas;
                     var fuin_pk = value.fuin_pk;
                     var html = "<div class='row group-container' data-pg='"+fuin_permitegrupos+"'>"+
                                    "<div class='col-md-12 box'>"+
                                       "<div class='header' style='text-align: left;'>"+
                                          "<span class='groupTitle' style='font-size: 1.5em;'>"+
                                             "Fuente de información - "+fuin_nombre+
                                          "</span>"+
                                          "<span id='state_fi_"+fuin_pk+"' style='margin-left: 10px; font-size: 1.2em;' class='label label-success'>"+
                                             "Se aplicará esta fuente de información."+
                                          "</span>"+
                                          "<button type='button' class='btn btn-default noaplicarfi' style='float: right; margin-right: 15px;' title='Si desea puede no aplicar la fuente de información, su peso se establecerá en 0 y deberá modificar los pesos de las demás fuentes de información.' id='no_aplicar_fi_"+fuin_pk+"' data-fi='"+fuin_pk+"' >"+
                                             "<span class='glyphicon glyphicon-off' aria-hidden='false'></span> No aplicar esta fuente de información"+
                                          "</button>"+
                                       "</div>"+
                                    "</div>"+ // Cierre div box
                                 "<div class='col-md-12 questionContainer'>"+
                                    "<div class='form-group'>"+
                                       "<label for='nombre_fi_"+fuin_pk+"' class='col-md-1'>Nombre: </label>"+
                                       "<div class='col-md-7'>"+
                                          "<input type='hidden' name='idfi' id='idfi_"+fuin_pk+"' value='"+fuin_pk+"' />"+
                                          "<input type='text' class='form-control nombrefi bloquear' name='nombre_fi_"+fuin_pk+"' id='nombre_fi_"+fuin_pk+"' required='required' disabled='disabled' maxlength='200' autofocus='autofocus' placeholder='Ingrese el nombre de la fuente de información' value='"+fuin_nombre+"' style='color: #000;'/>"+
                                       "</div>"+
                                       "<label for='peso_fi_"+fuin_pk+"' class='col-md-1'>Peso: </label>"+
                                       "<div class='col-md-3'>"+
                                          "<div class='input-group'>"+
                                             "<input min='0' max='100' step='1' placeholder='Peso de esta fuente de información' required='required' disabled='disabled' type='number' class='form-control currency pesofi bloquear' name='peso_fi_"+fuin_pk+"' id='peso_fi_"+fuin_pk+"' value='"+fuin_peso+"' style='color: #000;' data-state='off' />"+
                                               "<span class='input-group-addon'>%</span>"+
                                             "</div>"+
                                          "</div>"+
                                    "</div>"+
                                    "<div class='form-group'>"+
                                       "<label for='encuesta_fi_"+fuin_pk+"' class='col-md-1'>Encuesta: </label>"+
                                       "<div class='col-md-11' >"+
                                          "<input type='text' id='encuesta_fi_"+fuin_pk+"' name='encuesta_fi_"+fuin_pk+"' required='required' class='form-control survey encuestafi bloquear' disabled='disabled' data-sid='"+sid+"' value='"+surveytitle+"' style='color: #000;' />"+
                                       "</div>"+
                                    "</div>"+
                                 
                                    "<div class='form-group'>"+
                                       "<div class='btn btn-default' data-toggle='collapse' data-target='#table_"+fuin_pk+"'>Ocultar o mostrar</div>"+
                                       "<label class='col-md-2'>Información Adicional: </label>"+
                                       "<div class='col-md-12'>"+
                                          "<div class='table-responsive collapse' id='table_"+fuin_pk+"' >"+
                                             "<table class='table table-hover'>"+
                                                "<thead id='thead_fi_"+fuin_pk+"'>"+
                                                   "<tr>"+
                                                      "<th>ID</th>"+
                                                      "<th>Preguntas de la encuesta seleccionada</th>";
                                                      $.each(ias, function (index, value){ 
                                                         var inad_pk = value.inad_pk;
                                                         var fuin_inad_fk = value.fuin_inad_fk;
                                                         var inad_nombre = value.inad_nombre;
                                                         html+="<th data-col='"+inad_pk+"' >"+
                                                            "<div class='input-group'>"+
                                                               "<input type='text' class='form-control columna bloquear' name='nombre_info_adicional' required='required' disabled='disabled' maxlength='200' autofocus='autofocus' placeholder='Nombre de la información adicional' style='color: #000;' value='"+inad_nombre+"'/>"+
                                                            "</div>"+
                                                         "</th>";
                                                      });
                                          html+= "</tr>"+
                                                "</thead>"+
                                                "<tbody id='tbody_fi_"+fuin_pk+"' >";
                                                $.each(preguntas_encuesta, function(index, value){
                                                   var qid = index;
                                                   var pregunta = value;
                                                   html+="<tr>"+
                                                            "<td>"+
                                                               qid+
                                                            "</td>"+
                                                            "<td id='"+qid+"'>"+
                                                               pregunta+
                                                            "</td>";
                                                   $.each(ias, function (index, value) {
                                                      var inad_pk = value.inad_pk; 
                                                      var preguntas = value.preguntas;
                                                      $.each(preguntas, function(index, value){
                                                         var id_pregunta = value.id;
                                                         if(id_pregunta == qid){
                                                            html+="<td data-col='"+inad_pk+"' >";
                                                            html+="<input class='checkboxbtn checkfi bloquear' type='checkbox' id='preg_"+id_pregunta+"' name='preg_"+id_pregunta+"' disabled='disabled' ";
                                                            if(value.checked == true ){
                                                               html+="checked='checked' ";
                                                            }
                                                            html+="/>";
                                                            html+="</td>";
                                                         }
                                                      });
                                                   });
                                                   html+="</tr>"
                                                });
                                          html+="</tbody>"+
                                             "</table>"+
                                          "</div>"+
                                       "</div>"+
                                    "</div>"; // Cierre formgroup
                                    if(fuin_permitegrupos == true){
                                       html += "<label>Seleccione los grupos que desea que contesten la evaluación</label>"+
                                                "<div id='grupos' class='col-md-12 col-lg-12'>"+
                                                "</div>";
                                    }
                        html +=  "</div>"+ //Cierre questionContainer
                              "</div>"; // Cierre row groupContainer
                              
                     $("#main-container").append(html);
                  });
               }
            }
         });
      }
   });

   $('#facultad').change(function (e){
      $("#programa").html("");
      $('#programa').prop("disabled", true);
      var idfacultad = $("#facultad").val();
      var idplantilla = $("#plantilla_evaluado").val();
      if(idfacultad != "" && (tipolabor == 1 || tipolabor == 3) ){
         $.ajax({
            url: "getprogramas",
            data: {
               'idfacultad': idfacultad,
               'idplantilla': idplantilla
            },
            type: "POST",
            dataType: "JSON",
            success: function(data){
               if(data.state == "success"){
                  $("#programa").html(data.html);
                  $('#programa').prop("disabled", false);
               }else if(data.state == "error"){
                  $('#programa').prop("disabled", true);
               }
            },
            error: function(xhr, status) {
               console.log(status);
            }
         });
      }
   });

   var solicitudBusqueda = null;
   $("#buscar_evaludo").click(function () {
      var identificacion = $("#identificacion").val();
      var idplantilla = $("#plantilla_evaluado").val();
      if(identificacion != "" && idplantilla != ""){
         var idfacultad = $('#facultad').val();
         var idprograma = $("#programa").val();
         var tipo_identificacion = $('#tipo_identificacion').val();
         if (solicitudBusqueda != null) 
             solicitudBusqueda.abort();
         solicitudBusqueda = $.ajax({
            url: 'getajaxinformationevaluated',
            data: $("#addnewperformanceevaluation").serialize(),
            type: 'POST',
            dataType: 'json',
            success: function(data) {
               $("#grupos").html("");
               if(data.state == "error"){
                  $("#mensaje_identificacion").addClass("text-warning");
                  $("#mensaje_identificacion").removeClass("text-success");
                  $("#nombre_completo").val("");
               }
               else if(data.state == "success"){
                  var datos = data.datos;
                  var grupos = datos.grupos;
                  $("#nombre_completo").val(datos['nombre']);
                  $("#mensaje_identificacion").removeClass("text-warning");
                  $("#mensaje_identificacion").addClass("text-success");
                  $.each(grupos, function (index, value){ 
                     var html =  "<div class='col-md-1'>"+
                                    "<input class='checkboxbtn ' type='checkbox' id='"+value.grup_id+"' name='grupos' data-materia='"+value.mate_nombre+"' data-grupo='"+value.grup_nombre+"' />"+
                                 "</div>"+
                                 "<div class='col-md-11'>"+
                                    "<p>"+value.mate_codigomateria+" ---- "+value.mate_nombre+" ---- "+value.grup_nombre+"</p>"+
                                 "</div>";
                     $("#grupos").append(html);
                  });
               }
               if(data.message != ""){
                  $("#mensaje_identificacion").text(data.message);
               }
            },
            // código a ejecutar si la petición falla;
            // son pasados como argumentos a la función
            // el objeto de la petición en crudo y código de estatus de la petición
            error: function(xhr, status) {
               console.log(status);
            }
         });
      }else{
         $("#nombre_completo").val("");
         $("#grupos").html("");
         $("#mensaje_identificacion").text("Obligatorio");
         $("#mensaje_identificacion").addClass("text-warning");
         $("#mensaje_identificacion").removeClass("text-success");
      }
   });

   $("#tipo_identificacion").change(function (){
      $("#identificacion").val("");
      $("#nombre_completo").val("");
      $("#mensaje_identificacion").text("Obligatorio");
   }); 

   $("#main-container").delegate('.noaplicarfi', 'click', function(e){
      var id = $(this).attr("id");
      var fi = $("#"+id).data('fi');
      swal({
        title: '¿Confirmación de acción?',
        text: "Si selecciona no aplicar la fuente de información, deberá modificar los pesos de las demás fuentes de información que vaya a aplicar, ¿Desea continuar?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22722b',
        cancelButtonColor: '#a0352f',
        confirmButtonText: 'Si, aceptar',
        cancelButtonText: 'No, cancelar',
        buttonsStyling: true
      }).then(function(isConfirm) {
         if (isConfirm === true) {
            $("#peso_fi_"+fi).val("0");
            $("#peso_fi_"+fi).data("state", "on");
            $("#state_fi_"+fi).removeClass('label-danger label-success');
            $("#state_fi_"+fi).addClass('label-warning');
            $("#state_fi_"+fi).text("No se aplicará esta fuente de información.");
            $(".pesofi").each(function(index, value){
               var state = $(value).data('state');
               if(state == "off"){
                  $(value).prop("disabled", false);
               }else if(state == "on"){
                  $(value).prop("disabled", true);
               }
            });
            return true;
         }
      });
   });

   var peticion = null;
   $("#addnewperformanceevaluation").submit(function(e){
      $("#cargando").show();
      $("#save").prop("disabled", true);
      e.preventDefault();
      var identificacion      = $("#identificacion").val();
      var idplantilla         = $("#plantilla_evaluado").val();
      var idfacultad          = $('#facultad').val();
      var idprograma          = $("#programa").val();
      var tipo_identificacion = $('#tipo_identificacion').val();
      var nombre              = $("#nombre_completo").val();
      var group_container     = $("#main-container").children(".group-container");
      var plantilla           = {};
      plantilla['idplantilla']         = idplantilla;
      plantilla['idfacultad']          = idfacultad;
      plantilla['idprograma']          = idprograma;
      plantilla['tipo_identificacion'] = tipo_identificacion;
      plantilla['identificacion']      = identificacion;
      plantilla['nombre']              = nombre;
      var fi = new Array();
      var error = false;
      $.each(group_container, function(k, filafi) {
         var fuentesinformacion ={};
         var pesofi = $(filafi).find(".pesofi").val();
         var gruposfi = $(filafi).find("input[name='grupos']:checked");
         var existe_grupos = $(filafi).find($("input[name='grupos']")).length;
         var grupos = new Array();
         if(existe_grupos > 0){
            var length_grupos = $(filafi).find($("input[name='grupos']:checked")).length;
            if(length_grupos <= 0){
               error = true;
               swal({
                  title: 'Oops... ¡Ha ocurrido un error!',
                  text : "Debe seleccionar al menos 1 grupo.",
                  type: 'error',
                  confirmButtonColor: '#22722b',
                  confirmButtonText: 'OK',
                  buttonsStyling: true
               });
               $("#cargando").hide();
               $("#save").prop("disabled", false);
               e.stopPropagation();
            }else{
               $.each(gruposfi, function(index, value){
                  var grupo = {};
                  grupo['grup_id'] = value.id;
                  grupo['mate_nombre'] = $(value).data('materia');
                  grupo['grup_nombre'] = $(value).data('grupo');
                  grupos.push(grupo);
               });
            } 
         }
         var idfi = $(filafi).find("input[name='idfi']").val();
         fuentesinformacion['gruposfi']   = grupos;
         fuentesinformacion['idfi']   = idfi;
         fuentesinformacion['pesofi'] = pesofi;
         fi.push(fuentesinformacion);
      });
      if(!error){
         plantilla['fuentesinformacion'] = fi;
         var empaquetado = {};
         empaquetado['evaluacion'] = plantilla;
         var empaquetadojson = JSON.stringify(empaquetado);
         console.log(empaquetado);
         if(peticion != null)
            peticion.abort();
         peticion = $.ajax({
            url  : "addperformanceevaluation",
            type : "POST",
            dataType: "JSON",
            data:{
               'evaluacion' : empaquetadojson
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
                     window.location = data.url;
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
               }
            },
            error: function(xhr, status){
               console.log(status);
            }
         });
      }
   });
</script>