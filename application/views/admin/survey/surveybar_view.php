<?php
/**
 * Display the survey bar.
 * Used for all survey editing action, and group / questions lists.
 */
?>


<div class='menubar surveybar' id="surveybarid">
    <div class='row container-fluid'>
        <div class="col-md-8 col-xs-6">

            <!-- Add a new group -->
            <?php if(isset($surveybar['buttons']['newgroup'])):?>
                <?php if ($activated == "Y" || (!$canmodify && !$issuperadmin) ): ?>
                    <span class="btntooltip" data-toggle="tooltip" data-placement="bottom" title="<?php eT("Esta encuesta esta actualmente activa o usted no tiene permisos para hacer esto"); ?>" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Survey cannot be activated. Either you have no permission or there are no questions.'); ?>">
                        <button type="button" class="btn btn-default btntooltip" disabled="disabled">
                            <span class="icon-add"></span>
                            <?php eT("Add new group"); ?>
                        </button>
                    </span>
                <?php elseif(Permission::model()->hasSurveyPermission($surveyid,'surveycontent','create')): ?>
                    <a class="btn btn-default" href="<?php echo $this->createUrl("admin/questiongroups/sa/add/surveyid/$surveyid"); ?>" role="button">
                        <span class="icon-add"></span>
                        <?php eT("Add new group");?>
                    </a>
                    <a class="btn btn-default" href="<?php echo $this->createUrl("admin/questiongroups/sa/importview/surveyid/$surveyid"); ?>" role="button">

                        <span class="icon-import"></span>
                        <?php eT("Import a group");?>
                    </a>
                <?php endif;?>
            <?php endif;?>

            <!-- Add a new question -->
            <?php if(isset($surveybar['buttons']['newquestion'])):?>
                <?php if ($activated == "Y" || (!$canmodify && !$issuperadmin) ): ?>
                    <span class="btntooltip" data-toggle="tooltip" data-placement="bottom" title="<?php eT("Esta encuesta está actualmente activa o usted no tiene permisos para hacer esto"); ?>" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Survey cannot be activated. Either you have no permission or there are no questions.'); ?>">
                        <button type="button" class="btn btn-default btntooltip" disabled="disabled">
                            <span class="icon-add"></span>
                            <?php eT("Add new question"); ?>
                        </button>
                    </span>
                <?php elseif(Permission::model()->hasSurveyPermission($surveyid,'surveycontent','create')): ?>
                    <?php if(!$surveyHasGroup): ?>
                        <span class="btntooltip" data-toggle="tooltip" data-placement="bottom" title="<?php eT("You must first create a question group."); ?>" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Survey cannot be activated. Either you have no permission or there are no questions.'); ?>">
                            <button type="button" class="btn btn-default btntooltip" disabled="disabled">
                                <span class="icon-add"></span>
                                <?php eT("Add new question"); ?>
                            </button>
                        </span>
                    <?php else:?>
                    <a class="btn btn-default" href='<?php echo $this->createUrl("admin/questions/sa/newquestion/surveyid/".$surveyid); ?>' role="button">
                        <span class="icon-add"></span>
                        <?php eT("Add new question"); ?>
                    </a>
                    <a class="btn btn-default" href='<?php echo $this->createUrl("admin/questions/sa/importview/surveyid/".$surveyid); ?>' role="button">
                        <span class="icon-import"></span>
                        <?php eT("Import a question"); ?>
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif;?>

            <!-- Left buttons for survey summary -->
            <?php if(isset($surveybar['buttons']['view'])):?>

                <!-- survey activation -->
                <?php if(!$activated): ?>

                    <!-- solicitar activación -->
                    <?php if($canrequest): ?>
                        <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Solicitar al super administrador la revisión y activación de la encuesta'); ?>">
                            <a class="btn btn-success" href="<?php echo $this->createUrl("admin/survey/sa/request/surveyid/$surveyid"); ?>" role="button">
                                <?php eT("Solicitar activación"); ?>
                            </a>
                        </span>
                    <?php elseif(!$issuperadmin && !$canactivate): ?>
                        <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="No puede solicitar la activación o bien porque ya la ha solicitado antes, no posee permismos o porque su encuesta no posee preguntas">
                            <button type="button" class="btn btn-warning btntooltip" disabled="disabled">
                                Solicitar Activación
                            </button>
                        </span>
                    <?php endif; ?>
                    
                    <!-- activate -->
                    <?php if($canactivate): ?>

                        <?php if(isset($issuperadminandowner) && $issuperadminandowner): ?>
                            <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Activar la encuesta'); ?>">
                                <a href="<?php echo $this->createUrl("admin/survey/sa/activate/surveyid/$surveyid"); ?>" class="btn btn-success">
                                    <span class="glyphicon glyphicon-ok" ></span>
                                    <?php eT("Activar esta encuesta");?>
                                </a>
                            </span>
                        <?php else: ?>
                            <div class="btn-group hidden-xs">

                                <!-- Main button dropdown -->
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="glyphicon glyphicon-th" ></span>
                                     <?php eT('Acciones sobre la encuesta');?> <span class="caret"></span>
                                </button>

                                <!-- dropdown -->
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo $this->createUrl("admin/survey/sa/activate/surveyid/$surveyid"); ?>">
                                            <span class="glyphicon glyphicon-ok" ></span>
                                            <?php eT("Activar esta encuesta");?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $this->createUrl("admin/survey/sa/requiresadjustments/surveyid/$surveyid"); ?>">
                                            <span class="glyphicon glyphicon-cog" ></span>
                                            <?php eT("La encuesta requiere ajustes");?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $this->createUrl("admin/survey/sa/rejected/surveyid/$surveyid"); ?>">
                                            <span class="glyphicon glyphicon-remove" ></span>
                                            <?php eT("Rechazar esta encuesta");?>
                                        </a>
                                    </li>
                                      
                                </ul>
                            </div>
                        <?php endif; ?>

                    <!-- can't activate and is superadmin -->
                    <?php elseif($issuperadmin): ?>
                        <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="La encuesta no podrá ser activada. O bien no tiene suficientes privilegios para hacerlo, la encuesta aún no tiene preguntas o no se ha solicitado la activación.">
                            <button type="button" class="btn btn-warning btntooltip" disabled="disabled">
                                <?php eT("Activar esta encuesta"); ?>
                            </button>
                        </span>

                    <?php endif; ?>
                <?php else : ?>

                    <!-- activate expired survey -->
                    <?php if($expired) : ?>
                        <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('This survey is active but expired.'); ?>">
                            <button type="button" class="btn btn-success btntooltip" disabled="disabled">
                                <span class="fa fa-ban">&nbsp;</span>
                                <?php eT("Activate this survey"); ?>
                            </button>
                        </span>
                    <?php elseif($notstarted) : ?>
                        <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title='<?php eT("This survey is active but has a start date."); ?>'>
                            <button type="button" class="btn btn-success btntooltip" disabled="disabled" >
                                <span class="fa fa-clock-o">&nbsp;</span>
                                <?php eT("Activate this survey"); ?>
                            </button>
                        </span>
                    <?php endif; ?>

                    <!-- Stop survey -->
                    <?php if($candeactivate): ?>
                        <a class="btn btn-danger btntooltip" href="<?php echo $this->createUrl("admin/survey/sa/deactivate/surveyid/$surveyid"); ?>" role="button">
                            <?php eT("Stop this survey"); ?>
                        </a>
                    <?php else :?>
                        <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="La encuesta ha sido activada por el super administrador">
                            <button type="button" class="btn btn-success btntooltip" disabled="disabled">
                                Encuesta Activada
                            </button>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>

                <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="<?php eT('Ver el historial de solicitudes de esta encuesta'); ?>">
                            <a class="btn btn-default" href="<?php echo $this->createUrl("admin/survey/sa/listrequestshistory/surveyid/$surveyid"); ?>" role="button">
                                <?php eT("Historial de solicitudes"); ?>
                            </a>
                    </span>
                <!-- Preview/Execute survey -->
                <?php if($activated || $surveycontent) : ?>

                    <!-- Multinlinguage -->
                    <?php if(count($languagelist)>1): ?>
                        <div class="btn-group">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <span class="icon-do" ></span>
                            <?php echo $icontext;?> <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" style="min-width : 252px;">
                            <?php foreach ($languagelist as $tmp_lang): ?>
                                <li>
                                    <a target='_blank' href='<?php echo $this->createUrl("survey/index",array('sid'=>$surveyid,'newtest'=>"Y",'lang'=>$tmp_lang));?>'>
                                        <?php echo getLanguageNameFromCode($tmp_lang,false); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                          </ul>
                        </div>

                    <!-- uniq language -->
                    <?php else: ?>
                        <a accesskey='d' class="btn btn-default  btntooltip" href="<?php echo $this->createUrl("survey/index/sid/$surveyid/newtest/Y/lang/$baselang"); ?>" role="button"  accesskey='d' target='_blank'>
                            <span class="icon-do" ></span>
                            <?php echo $icontext;?>
                        </a>
                    <?php endif;?>
                <?php endif; ?>

                <!-- Survey Properties -->
                <?php if( !isset($surveybar['active_survey_properties']) ):?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="icon-edit" ></span>
                          <?php eT("Survey properties");?> <span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu">
                            <?php if($surveylocale && $surveysettings): ?>

                                <!-- Edit text elements and general settings -->
                                <li>
                                    <a href='<?php echo $this->createUrl("admin/survey/sa/editlocalsettings/surveyid/$surveyid");?>'>
                                        <span class="icon-edit" ></span>
                                         <?php eT("General settings & texts");?>
                                    </a>
                                </li>
                            <?php endif; ?>
                                <li>
                                    <a href='<?php echo $this->createUrl("admin/survey/sa/changedate/surveyid/$surveyid");?>'>
                                        <span class="glyphicon glyphicon-calendar" ></span>
                                         <?php echo "Cambiar fechas de encuesta";?>
                                    </a>
                                </li>
                            <?php if($surveysecurity): ?>

                                <!-- Survey permissions -->
                                <li>
                                    <a href='<?php echo $this->createUrl("admin/surveypermission/sa/view/surveyid/$surveyid");?>' >
                                        <span class="icon-security" ></span>
                                        <?php eT("Survey permissions");?>
                                    </a>
                                 </li>
                            <?php endif; ?>

                            <?php if($quotas): ?>

                                <!-- Quotas -->
                                <li>
                                    <a href='<?php echo $this->createUrl("admin/quotas/sa/index/surveyid/$surveyid/");?>' >
                                        <span class="icon-quota" ></span>
                                        <?php eT("Quotas");?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if($assessments): ?>

                                <!-- Assessments -->
                                <li>
                                    <a href='<?php echo $this->createUrl("admin/assessments/sa/index/surveyid/$surveyid");?>' >
                                        <span class="icon-assessments" ></span>
                                        <?php eT("Assessments");?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if($surveylocale): ?>

                                <!-- Email templates -->
                                <li>
                                    <a href='<?php echo $this->createUrl("admin/emailtemplates/sa/index/surveyid/$surveyid");?>' >
                                        <span class="icon-emailtemplates" ></span>
                                        <?php eT("Email templates");?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if($surveycontent): ?>
                                <!-- survey content -->

                                <?php if($onelanguage): ?>
                                    <!-- one language -->

                                    <!-- Survey logic file -->
                                    <li>
                                        <a href='<?php echo $this->createUrl("admin/expressions/sa/survey_logic_file/sid/$surveyid/");?>' >
                                            <span class="icon-expressionmanagercheck" ></span>
                                            <?php eT("Survey logic file");?>
                                        </a>
                                    </li>
                                <?php else : ?>
                                    <!-- multilangue  -->

                                    <li role="separator" class="divider"></li>

                                    <!-- Survey logic file -->
                                    <li class="dropdown-header"><?php eT("Survey logic file");?></li>
                                        <?php foreach ($languagelist as $tmp_lang): ?>
                                            <!-- Languages -->

                                            <li>
                                                <a  href='<?php echo $this->createUrl("admin/expressions/sa/survey_logic_file/sid/$surveyid/lang/$tmp_lang");?>'>
                                                       <span class="icon-expressionmanagercheck" ></span>
                                                       <?php echo getLanguageNameFromCode($tmp_lang,false);?>
                                                   </a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                            <?php endif; ?>
                      </ul>
                    </div>
                <?php else:?>
                        <button type="button" class="btn btn-default btntooltip active">
                            <span class="icon-expressionmanagercheck" ></span>
                            <?php echo $surveybar['active_survey_properties']['txt'];?>
                        </button>
                <?php endif;?>


                <!-- TOOLS  -->
                <div class="btn-group hidden-xs">

                    <!-- Main button dropdown -->
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="icon-tools" ></span>
                         <?php eT('Tools');?><span class="caret"></span>
                    </button>

                    <!-- dropdown -->
                    <ul class="dropdown-menu">
                          <?php if ($surveydelete): ?>

                              <!-- Delete survey -->
                              <li>
                                  <a href="<?php echo $this->createUrl("admin/survey/sa/delete/surveyid/{$surveyid}"); ?>">
                                    <span class="glyphicon glyphicon-trash" ></span>
                                    <?php eT("Delete survey");?>
                                  </a>
                              </li>
                          <?php endif; ?>

                          <?php if ($surveytranslate): ?>
                              <!-- surveytranslate -->

                              <?php if($hasadditionallanguages): ?>

                                    <!-- Quick-translation -->
                                    <li>
                                        <a href="<?php echo $this->createUrl("admin/translate/sa/index/surveyid/{$surveyid}");?>">
                                        <span class="fa fa-language" ></span>
                                        <?php eT("Quick-translation");?>
                                        </a>
                                    </li>

                              <?php else: ?>

                                    <!-- Quick-translation disabled -->
                                    <li>
                                        <a href="#" onclick="alert('<?php eT("Currently there are no additional languages configured for this survey.", "js");?>');" >
                                          <span class="fa fa-language" ></span>
                                          <?php eT("Quick-translation");?>
                                        </a>
                                    </li>
                              <?php endif; ?>
                          <?php endif; ?>

                          <?php if (Permission::model()->hasSurveyPermission($surveyid,'surveycontent','update')): ?>
                              <li>
                                  <?php if ($conditionscount>0):?>

                                      <!-- condition -->
                                      <a href="<?php echo $this->createUrl("/admin/conditions/sa/index/subaction/resetsurveylogic/surveyid/{$surveyid}"); ?>">
                                        <span class="icon-resetsurveylogic" ></span>
                                        <?php eT("Reset conditions");?>
                                      </a>
                                  <?php else: ?>

                                      <!-- condition disabled -->
                                      <a href="#" onclick="alert('<?php eT("Currently there are no conditions configured for this survey.", "js"); ?>');" >
                                        <span class="icon-resetsurveylogic" ></span>
                                        <?php eT("Reset conditions");?>
                                      </a>
                                  <?php endif; ?>
                              </li>

                              <?php if(!$activated): ?>
                                  <li>
                                          <li role="separator" class="divider"></li>

                                          <!-- Regenerate question codes -->
                                          <li class="dropdown-header">
                                              <?php eT("Regenerate question codes");?>
                                          </li>

                                          <!-- Straight -->
                                          <li>
                                              <a href="<?php echo $this->createUrl("/admin/survey/regenquestioncodes/surveyid/{$surveyid}/subaction/straight"); ?>">
                                                <span class="icon-resetsurveylogic" ></span>
                                                <?php eT("Straight");?>
                                              </a>
                                          </li>

                                          <!-- By question group -->
                                          <li>
                                            <a href="<?php echo $this->createUrl("/admin/survey/regenquestioncodes/surveyid/{$surveyid}/subaction/bygroup"); ?>">
                                                <span class="icon-resetsurveylogic" ></span>
                                                <?php eT("By question group");?>
                                            </a>
                                         </li>
                                  </li>
                              <?php endif; ?>
                          <?php endif; ?>
                    </ul>
                </div>


                <!-- Display / Export -->
                <div class="btn-group hidden-xs">

                    <!-- Main dropdown -->
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="icon-display_export" ></span>
                      <?php eT("Display / Export");?> <span class="caret"></span>
                    </button>

                    <!-- dropdown -->
                    <ul class="dropdown-menu">

                          <?php if($surveyexport): ?>
                              <!-- survey export -->

                              <!-- Export -->
                              <li class="dropdown-header"> <?php eT("Export...");?></li>

                                  <?php if($surveyexport): ?>

                                      <!-- Survey structure -->
                                      <li>
                                          <a href='<?php echo $this->createUrl("admin/export/sa/survey/action/exportstructurexml/surveyid/$surveyid");?>' >
                                            <span class="icon-export" ></span>
                                            <?php eT("Survey structure (.lss)");?>
                                          </a>
                                      </li>
                                  <?php endif; ?>

                                  <?php if($respstatsread && $surveyexport): ?>
                                      <?php if ($activated):?>

                                          <!-- Survey archive -->
                                          <li>
                                              <a href='<?php echo $this->createUrl("admin/export/sa/survey/action/exportarchive/surveyid/$surveyid");?>' >
                                                  <span class="icon-export" ></span>
                                                  <?php eT("Survey archive (.lsa)");?>
                                              </a>
                                          </li>
                                      <?php else: ?>

                                          <!-- Survey archive unactivated -->
                                          <li>
                                              <a href="#" onclick="alert('<?php eT("You can only archive active surveys.", "js");?>');" >
                                                <span class="icon-export" ></span>
                                                <?php eT("Survey archive (.lsa)");?>
                                              </a>
                                          </li>
                                      <?php endif;?>
                                  <?php endif; ?>

                                  <?php if($surveyexport): ?>

                                      <!-- queXML -->
                                      <li>
                                          <a href='<?php echo $this->createUrl("admin/export/sa/survey/action/exportstructurequexml/surveyid/$surveyid");?>' >
                                              <span class="icon-export" ></span>
                                              <?php eT("queXML format (*.xml)");?>
                                          </a>
                                      </li>

                                      <!-- Tab-separated-values -->
                                      <li>
                                          <a href='<?php echo $this->createUrl("admin/export/sa/survey/action/exportstructuretsv/surveyid/$surveyid");?>' >
                                              <span class="icon-export" ></span>
                                              <?php eT("Tab-separated-values format (*.txt)");?>
                                          </a>
                                      </li>
                                  <?php endif; ?>

                              <?php endif;?>

                          <?php if(Permission::model()->hasSurveyPermission($surveyid,'surveycontent','read')): ?>
                              <?php if($onelanguage):?>

                                  <!-- Printable version -->
                                  <li>
                                      <a target='_blank' href='<?php echo $this->createUrl("admin/printablesurvey/sa/index/surveyid/$surveyid");?>' >
                                          <span class="glyphicon glyphicon-print"></span>
                                          <?php eT("Printable version");?>
                                      </a>
                                  </li>
                              <?php else: ?>
                                  <li role="separator" class="divider"></li>

                                  <!-- Printable version multilangue -->
                                  <li class="dropdown-header"><?php eT("Printable version");?></li>
                                      <?php foreach ($languagelist as $tmp_lang): ?>
                                          <li>
                                              <a accesskey='d' target='_blank' href='<?php echo $this->createUrl("admin/printablesurvey/sa/index/surveyid/$surveyid/lang/$tmp_lang");?>'>
                                                  <span class="glyphicon glyphicon-print"></span>
                                                  <?php echo getLanguageNameFromCode($tmp_lang,false);?>
                                              </a>
                                          </li>
                                      <?php endforeach; ?>
                              <?php endif; ?>
                          <?php endif; ?>
                    </ul>
                </div>

                <!-- Token -->
                <?php if($tokenmanagement):?>
                    <a class="btn btn-default  btntooltip hidden-xs" href="<?php echo $this->createUrl("admin/tokens/sa/index/surveyid/$surveyid"); ?>" role="button">
                        <span class="glyphicon glyphicon-user"></span>
                        <?php eT("Token management");?>
                    </a>
                <?php endif; ?>

                <!-- Statistics -->
                <?php if($respstatsread || $responsescreate || $responsesread):?>

                    <div class="btn-group">
                        <!-- main  dropdown header -->
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="icon-responses"></span>
                            <?php eT("Responses");?><span class="caret"></span>
                        </button>

                        <!-- dropdown -->
                        <ul class="dropdown-menu">
                            <?php if($respstatsread):?>
                                <?php if($activated):?>

                                    <!-- Responses & statistics -->
                                    <li>
                                        <a href='<?php echo $this->createUrl("admin/responses/sa/index/surveyid/$surveyid/");?>' >
                                            <span class="icon-browse"></span>
                                            <?php eT("Responses & statistics");?>
                                        </a>
                                    </li>
                                <?php else:?>

                                    <!-- Responses & statistics -->
                                    <li>
                                        <a href="#" onclick="alert('<?php eT("This survey is not active - no responses are available.","js");?>');" >
                                            <span class="icon-browse"></span>
                                            <?php eT("Responses & statistics");?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if($responsescreate): ?>
                                <?php if($activated): ?>

                                    <!-- Data entry screen -->
                                    <li>
                                        <a href='<?php echo $this->createUrl("admin/dataentry/sa/view/surveyid/$surveyid");?>' >
                                            <span class="fa fa-keyboard-o"></span>
                                            <?php eT("Data entry screen");?>
                                        </a>
                                    </li>
                                <?php else: ?>

                                    <!-- Data entry screen disabled -->
                                    <li>
                                        <a href="#" onclick="alert('<?php eT("This survey is not active, data entry is not allowed","js");?>');" >
                                            <span class="fa fa-keyboard-o"></span>
                                            <?php eT("Data entry screen");?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if($responsesread): ?>
                                <?php if($activated): ?>

                                    <!-- Partial (saved) responses -->
                                    <li>
                                        <a href='<?php echo $this->createUrl("admin/saved/sa/view/surveyid/$surveyid");?>' >
                                            <span class="icon-saved"></span>
                                            <?php eT("Partial (saved) responses");?>
                                        </a>
                                    </li>
                                <?php else :?>

                                    <!-- Partial (saved) responses disabled -->
                                    <li>
                                        <a href="#" onclick="alert('<?php eT("This survey is not active - no responses are available","js");?>');" >
                                            <span class="icon-saved"></span>
                                            <?php eT("Partial (saved) responses");?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif;?>
                
                <!-- Teacher Evaluation Export -->
                <a class="btn btn-default" href="<?php echo Yii::App()->createUrl('admin/statistics/sa/exportteacherevaluation/surveyid/'.$surveyid);?>" role="button">
                    <span class="icon-display_export"></span>
                    Exportar evaluación docente
                </a>
                <?php if($permission):?>
                    <!-- List Groups -->
                        <!-- admin/survey/sa/view/surveyid/838454 listquestiongroups($iSurveyID)-->
                        <a class="btn btn-default hidden-sm  hidden-md hidden-lg" href="<?php echo $this->createUrl("admin/survey/sa/listquestiongroups/surveyid/$surveyid"); ?>">
                            <span class="glyphicon glyphicon-list"></span>
                            <?php eT("List question groups");?>
                        </a>

                    <!-- List Questions -->
                        <a class="btn btn-default hidden-sm  hidden-md hidden-lg" href="<?php echo $this->createUrl("admin/survey/sa/listquestions/surveyid/$surveyid"); ?>">
                            <span class="glyphicon glyphicon-list"></span>
                            <?php eT("List questions");?>
                        </a>
                    </li>
                <?php endif; ?>


            <?php endif;?>
            <?php if(isset($surveybar['importquestion'])):?>
                <a class="btn btn-default" href="<?php echo Yii::App()->createUrl('admin/questions/sa/importview/surveyid/'.$surveyid);?>" role="button">
                    <span class="icon-import"></span>
                    <?php eT('Import a question'); ?>
                </a>
            <?php endif;?>

            <?php if(isset($surveybar['importquestiongroup'])):?>
                <a class="btn btn-default" href="<?php echo Yii::App()->createUrl('admin/questiongroups/sa/importview/surveyid/'.$surveyid);?>" role="button">
                    <span class="icon-import"></span>
                    <?php eT('Import a group'); ?>
                </a>
            <?php endif;?>
        </div>

        <!-- right action buttons -->
        <div class=" col-md-4 text-right">
            <?php if(isset($request_state)): ?>
                <!-- Estado de la solicitud de activación de la encuesta -->
                <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="
                    <?php if($request_state == 'pendiente') echo 'El estado de la solicitud de la encuesta se encuetra pendiente de revisión';
                    elseif($request_state == 'requiere ajustes') echo 'Debe realizar los ajustes orientados por el super administrador y volver a solicitar la revisión y activación';
                    elseif($request_state == 'rechazada') echo 'El estado de la solicitud de la encuesta se encuentra rechazada, revisa el historial de la solicitud para más información';
                    elseif($request_state == 'aprobada') echo 'La encuesta ha sido revisada y activada por el super administrador';
                    else echo 'btn-default'; ?>" id="request_state">
                    
                    <div class="btn 
                    <?php if($request_state == 'pendiente') echo 'btn-primary-bootstrap';
                    elseif($request_state == 'requiere ajustes') echo 'btn-warning';
                    elseif($request_state == 'rechazada') echo 'btn-danger';
                    elseif($request_state == 'aprobada') echo 'btn-success';
                    else echo 'btn-default'; ?>" id="request_state">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        Estado: <?php echo $request_state;?>
                    </div>
                </span>
            <?php else: ?>
                <span class="btntooltip" style="display: inline-block" data-toggle="tooltip" data-placement="bottom" title="No se ha solicitado activación" id="request_state">
                    
                    <div class="btn btn-default" id="request_state">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        Estado: SIN SOLICITUD
                    </div>
                </span>
            <?php endif;?>
            <?php if(isset($surveybar['savebutton']['form'])):?>

                <!-- Save -->
                <a class="btn btn-success" href="#" role="button" id="save-button" >
                    <span class="glyphicon glyphicon-ok"></span>
                    <?php eT("Save");?>
                </a>

                <!-- Save and close -->
                <?php if(isset($surveybar['saveandclosebutton'])):?>
                    <a class="btn btn-default" href="#" role="button" id='save-and-close-button'>
                        <span class="glyphicon glyphicon-saved"></span>
                        <?php eT("Save and close");?>
                    </a>
                <?php endif; ?>
            <?php endif;?>

            <!-- Close -->
            <?php if(isset($surveybar['closebutton']['url'])):?>
                <a class="btn btn-danger" href="<?php echo $surveybar['closebutton']['url']; ?>" role="button">
                    <span class="glyphicon glyphicon-close"></span>
                    <?php eT("Close");?>
                </a>
            <?php endif;?>
        </div>
    </div>
</div>
