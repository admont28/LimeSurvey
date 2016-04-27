<?php
/**
 * Vista para el mensaje de caja después de solicitar activación de una encuesta.
 * Es llamada por SurveyAdmin::request
 * It's called from SurveyAdmin::request
 *
 * @var $iSurveyID                  Id de la encuesta
 * @var $closedOnclickAction        convertGETtoPOST(Yii::app()->getController()->createUrl("admin/tokens/sa/index/surveyid/".$iSurveyID))
 *
 */
?>
<div class="side-body">
<div class="row welcome survey-action">
    <div class="col-lg-12 content-right">
        <div class='jumbotron message-box'>
            <h3>Solicitud de activación de Encuesta(<?php echo $iSurveyID; ?>)</h3>
            <p class='lead'>
                La solicitud de activación se ha registrado exitosamente.
            </p>
            <p>
                <?php eT("Un super administrador revisará la encuesta y decidirá si se activa o no."); ?>
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
