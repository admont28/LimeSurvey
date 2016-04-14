<?php
/**
 * Vista para responder la solicitud de una encuesta con el estado de rechazada
 * Es llamada por SurveyAdmin::rejected
 * It's called from SurveyAdmin::rejected
 *
 * @var $surveyid                  Id de la encuesta
 * @var $urlsubmit                 Url donde se enviarán los datos proporcionados
 *
 */
?>
<div class='side-body container col-sm-10'>
	<div class="message-box">
		<div class="row">
		            <h3 class="col-sm-12 col-xs-12 text-center">Responder a la solicitud de encuesta (<?php echo $surveyid; ?>)</h3>
		            <p class='lead col-sm-12 col-xs-12 text-center'>
		                La solicitud de encuesta es rechazada.
		            </p>
		            <p class="lead col-sm-12 col-xs-12 text-center">
		            	Por favor proporcione más información al dueño de la encuesta sobre la asignación de este estado a la solicitud.
		            </p>
		</div>
		<?php echo CHtml::form(array($urlsubmit), 'post', array('class'=>'form-horizontal')); ?>
	        <div class="row">
	            <div class="col-sm-3 col-sm-offset-3">
	                <div class="row">
	                    <div class='form-group'>
	                        <label class='control-label col-sm-6' for='request_response'>Respuesta:</label>
	                        <div class='col-sm-6'>
	                            <textarea rows="4" cols="50" name="request_response" placeholder="Responde al dueño de la encuesta" required="required"></textarea>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class='row'>
		        <div class='col-sm-7 col-sm-offset-5'>
		            <input type='hidden' name='information' value='Y' />
		            <input type='submit' class="btn btn-default btn-lg " value="Guardar y responder" />
		            <a class="btn btn-default btn-lg" href="<?php echo $this->createUrl("admin/survey/sa/view/surveyid/$surveyid"); ?>" role="button">
		            <?php eT("Cancel"); ?>
		            </a>
		            <p></p>
		        </div>
		    </div>
		</form>
	</div>
</div>
</div>