<?php
/**
 * The welcome page is the home page
 * TODO : make a recursive function, taking any number of box in the database, calculating how much rows are needed.
 */
?>

<?php
    // Boxes are defined by user. We still want the default boxes to be translated.
    gT('Create survey');
    gT('Create a new survey');
    gT('List surveys');
    gT('List available surveys');
    gT('Global settings');
    gT('Edit global settings');
    gT('ComfortUpdate');
    gT('Stay safe and up to date');
    gT('Label sets');
    gT('Edit label sets');
    gT('Template editor');
    gT('Edit LimeSurvey templates');
?>

<!-- Welcome view -->
<div class="container-fluid welcome full-page-wrapper">

    <!-- Logo & Presentation -->
    <?php if($bShowLogo):?>
        <div class="row">
            <div class="jumbotron" id="welcome-jumbotron" style="margin-top: 10px;">
                <!-- ORIGINAL <img alt="logo" src="<?php echo LOGO_URL;?>" id="lime-logo"  class="profile-img-card img-responsive center-block" style="display: inline;" /> -->
                <img alt="logo" src="<?php echo IMAGE_BASE_URL;?>GESEN-UQ.png" id="lime-logo" width="130" class="profile-img-card img-responsive center-block" style="display: inline;" />
                <p class="hidden-xs" ><?php echo PRESENTATION; // Defined in AdminController?></p>
            </div>
        </div>
    <?php endif;?>

    <!-- Politices Modal -->
    <?php if(isset($show_politices) && $show_politices): ?>
        <!-- Politicas de GESEN-UQ -->
        <div class="modal fade" id="politicesModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Políticas de GESEN-UQ</h4>
                    </div>
                    <div class="modal-body">
                        <h4>GESEN-UQ podrá ser utilizada teniendo en consideración las siguientes políticas:</h4>
                        <h5>Usuarios y contraseñas:</h5>
                        <p>
                            <ul>
                                <li class="text-justify">A cada dependencia que lo requiera se le activará solo un usuario y contraseña.</li>
                                <li class="text-justify">La solicitud de esta activación deberá realizarla solamente el jefe/director/coordinador de la dependencia.</li>
                                <li class="text-justify">El estándar del nombre de usuario será acorde con el nombre de la dependencia.</li>
                                <li class="text-justify">La plataforma permite asignar una clave inicial, el usuario es responsable de cambiar la clave al ingresar la primera vez y debe realizar este proceso periódicamente.</li>
                            </ul>
                        </p>
                        <h5>Control de aprobación de la encuesta:</h5>
                        <p>
                            <ul>
                                <li class="text-justify">El responsable de la gobernabilidad de la plataforma validará que no hayan encuesta solicitadas para un mismo público objetivo y en fechas iguales.</li>
                                <li class="text-justify">Si los correos pertenecientes al público objetivo no corresponden al dominio uniquindio.edu.co y uqvirtual.edu.co y además es mayor a 1.000 personas se deberá solicitar soporte y validación al Centro de Sistemas y Nuevas Tecnologías.</li>
                                <li class="text-justify">Los objetivos y contenidos de la encuesta deben ser acordes con las necesidades de la institución acatando normas, reglamentos, políticas y valores institucionales.</li>
                            </ul>
                        </p>
                        <h5>Tiempo máximo de almacenamiento:</h5>
                        <p>
                            <ul>
                                <li class="text-justify">Los resultados obtenidos estarán disponibles en línea máximo 12 meses a partir de la fecha de cierre de la encuesta.</li>
                            </ul>
                        </p>
                        <h5>Confidencialidad de la información:</h5>
                        <p>
                            <ul>
                                <li class="text-justify">La persona que gobierna la encuesta no deberá manipular o alterar datos de resultados obtenidos en las encuestas. Así como tampoco deberá difundir información de resultados a otras dependencias diferentes al dueño de la encuesta.</li>
                                <li class="text-justify">Siempre se deberá velar por la confidencialidad de la información exceptuando requerimientos de auditoria especiales.</li>
                            </ul>
                        </p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" onclick="abrirPasos()" class="btn btn-success" data-dismiss="modal">Entendido</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal fade" id="welcomeModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Bienvenido a la aplicación para gestionar encuestas de la Universidad del Quindío. (GESEN-UQ)</h4>
                    </div>
                    <div class="modal-body">
                        <p><?php eT("Some piece-of-cake steps to create your very own first survey:"); ?></p>
                        <ol>
                            <li><?php echo sprintf(gT('Create a new survey clicking on the %s icon.'),
                                        "<span class='icon-add text-success'></span>"); ?></li>
                            <li><?php eT('Create a new question group inside your survey.'); ?></li>
                            <li><?php eT('Create one or more questions inside the new question group.'); ?></li>
                            <li><?php echo sprintf(gT('Done. Test your survey using the %s icon.'), "<span class='icon-do text-success'></span>"); ?></li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal"><?php eT('Close');?></button>
                      <a href="<?php echo $this->createUrl("admin/survey/sa/newsurvey") ?>" class="btn btn-primary"><?php eT('Create a new survey');?></a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script type="text/javascript">
            $(window).load(function () {
                $('#politicesModal').modal('show');
                
            });
            function abrirPasos(){
                    console.log('Abriendo pasos');
                    $('#welcomeModal').modal('show');
                    console.log('Abrí pasos');
            }
        </script>
    <?php endif;?>

    <!-- Last visited survey/question -->
    <?php if( $bShowLastSurveyAndQuestion && ($showLastSurvey || $showLastQuestion)): // bShowLastSurveyAndQuestion is the homepage setting, showLastSurvey & showLastQuestion are about if infos are available ?>
        <div class="row text-right">
            <div class="col-lg-9 col-sm-9  ">
                <div class='pull-right'>
                <?php if($showLastSurvey):?>
                    <span id="last_survey" class="rotateShown">
                    <?php eT("Last visited survey:");?>
                    <a href="<?php echo $surveyUrl;?>" class=""><?php echo $surveyTitle;?></a>
                    </span>
                <?php endif; ?>

                <?php if($showLastQuestion):?>
                    <span id="last_question" class="rotateHidden">
                    <?php eT("Last visited question:");?>
                    <a href="<?php echo $last_question_link;?>" class=""><?php echo viewHelper::flatEllipsizeText($last_question_name, true, 60); ?></a>
                    </span>
                <?php endif; ?>
                </div>
                <br/><br/>
            </div>
        </div>
    <?php endif;?>

    <!-- Rendering all boxes in database -->
    <?php $this->widget('ext.PannelBoxWidget.PannelBoxWidget', array(
            'display'=>'allboxesinrows',
            'boxesbyrow'=>$iBoxesByRow,
            'offset'=>$sBoxesOffSet,
        ));
    ?>


    <!-- Boxes for smartphones -->
    <div class="row  hidden-sm  hidden-md hidden-lg ">
        <div class="panel panel-primary panel-clickable" id="pannel-7" data-url="/limesurvey/LimeSurveyNext/index.php/admin/survey/sa/listsurveys" style="opacity: 1; top: 0px;">
            <div class="panel-heading">
                <h3 class="panel-title"><?php eT('List surveys');?></h3>
            </div>
            <div class="panel-body">
                <a href='<?php echo $this->createUrl("admin/survey/sa/listsurveys") ?>'>
                    <span class="icon-list" style="font-size: 4em"></span>
                </a><br><br>
                <a href='<?php echo $this->createUrl("admin/survey/sa/listsurveys") ?>'><?php eT('List surveys');?></a>
            </div>
        </div>

        <div class="panel panel-primary panel-clickable" id="pannel-8" data-url="/limesurvey/LimeSurveyNext/index.php/admin/globalsettings" style="opacity: 1; top: 0px;">
            <div class="panel-heading">
                <h3 class="panel-title"><?php eT('Edit global settings');?></h3>
            </div>
            <div class="panel-body">
                <a href='<?php echo $this->createUrl("admin/globalsettings") ?>'>
                    <span class="icon-settings" style="font-size: 4em">
                    </span>
                </a><br><br>
                <a href='<?php echo $this->createUrl("admin/globalsettings") ?>'><?php eT('Edit global settings');?></a>
            </div>
        </div>

    </div>
</div>

<!-- Notification setting -->
<input type="hidden" id="absolute_notification" />
