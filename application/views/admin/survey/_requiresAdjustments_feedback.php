<?php
/**
 * Vista para el mensaje de caja después de responder a la activación de una encuesta con el mensaje de requiere ajustes
 * Es llamada por SurveyAdmin::requiresadjustments
 * It's called from SurveyAdmin::requiresadjustments
 *
 * @var $iSurveyID                  Id de la encuesta
 * @var $closedOnclickAction        convertGETtoPOST(Yii::app()->getController()->createUrl("admin/survey/sa/view/surveyid/".$surveyid));
 *
 */
?>
<div class="side-body">
<div class="row welcome survey-action">
    <div class="col-lg-12 content-right">
        <div class='jumbotron message-box'>
            <h3>Respuesta a la Solicitud de Activación de Encuesta(<?php echo $iSurveyID; ?>)</h3>
            <p class='lead'>
                La respuesta a la solicitud se registró correctamente.
            </p>
            <p>
                <?php eT("Se ha registrado la respuesta a la solicitud, el dueño de la encuesta podrá observar la respuesta."); ?>
                <br />
                <br />
                <input
                    type='submit'
                    class='btn btn-default'
                    value='Cerrar'
                    onclick="<?php echo $closedOnclickAction;?>"
            </p>
        </div>
    </div>
</div>
</div>
