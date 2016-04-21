<?php
/**
 * This view render the main menu bar, with configuration menu
 * @var $sitename
 * @var $activesurveyscount
 * @var $dataForConfigMenu
 */
?>
<div>
<!-- admin menu bar -->
<nav class="navbar" style="margin-bottom: -5px; background-color: #fff;">
  <div class="navbar-header" style="background-color: #fff;">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#small-screens-menus" style="padding-bottom: 11px;">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>


        <a href="http://www.uniquindio.edu.co" target="_blank">
            <img alt="logo" id="" class="navbar-brand hidden-xs" style="height: 60px; padding: 3px 3px 6px 6px;" src="<?php echo LOGO_URL;?>">
        </a>
        <a class="navbar-brand" href="<?php echo $this->createUrl("/admin/"); ?>" style="padding-left: 3px;">
            <?php echo $sitename; ?>
        </a>
    </div>


    <!-- Only on xs screens -->
    <div class="collapse navbar-collapse pull-left hidden-sm  hidden-md hidden-lg" id="small-screens-menus">
        <ul class="nav navbar-nav hidden-sm  hidden-md hidden-lg">

            <li><br/><br/></li>
            <!-- active surveys -->
            <?php if ($activesurveyscount > 0): ?>
                <li>
                    <a href="<?php echo $this->createUrl('admin/survey/sa/listsurveys/active/Y');?>">
                        <?php neT("{n} active survey|{n} active surveys",$activesurveyscount); ?>
                    </a>
                </li>
            <?php endif;?>

            <!-- List surveys -->
            <li>
                <a href="<?php echo $this->createUrl("admin/survey/sa/listsurveys"); ?>">
                    <?php eT("List surveys");?>
                </a>
            </li>

            <!-- Logout -->
            <li>
                <a href="<?php echo $this->createUrl("admin/authentication/sa/logout"); ?>">
                    <?php eT("Logout");?>
                </a>
            </li>
        </ul>
    </div>

    <div class="collapse navbar-collapse js-navbar-collapse pull-right">
        <ul class="nav navbar-nav navbar-right">

            <!-- Configuration menu -->
            <?php $this->renderPartial( "/admin/super/_configuration_menu", $dataForConfigMenu ); ?>
            <li class="dropdown-split-left">
                <a style="" href="<?php echo $this->createUrl("admin/survey/sa/listrequests"); ?>">
                    <?php eT("Listar solicitudes");?>
                </a>
            </li>

            <!-- Surveys menus -->
            <li class="dropdown-split-left">
                <a style="" href="<?php echo $this->createUrl("admin/survey/sa/listsurveys"); ?>">
                    <?php eT("Surveys");?>
                </a>
            </li>
            <li class="dropdown dropdown-split-right">
                <a  style="padding-left: 5px;padding-right: 5px;" href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span style="margin-left: 0px;" class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                         <?php if (Permission::model()->hasGlobalPermission('surveys','create')): ?>
                         <!-- Create a new survey -->
                         <li>
                             <a href="<?php echo $this->createUrl("admin/survey/sa/newsurvey"); ?>">
                                 <?php eT("Create a new survey");?>
                             </a>
                         </li>

                         <!-- Import a survey -->
                         <li>
                           <a href="<?php echo $this->createUrl("admin/survey/sa/newsurvey/tab/import"); ?>">
                               <?php eT("Import a survey");?>
                           </a>
                         </li>

                         <!-- Import a survey -->
                         <li>
                           <a href="<?php echo $this->createUrl("admin/survey/sa/newsurvey/tab/copy"); ?>">
                               <?php eT("Copy a survey");?>
                           </a>
                         </li>

                         <li class="divider"></li>
                        <?php endif;?>
                         <!-- List surveys -->
                         <li>
                             <a href="<?php echo $this->createUrl("admin/survey/sa/listsurveys"); ?>">
                                 <?php eT("List surveys");?>
                             </a>
                         </li>

                       </ul>
                     </li>

            <!-- user menu -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" ><?php echo Yii::app()->session['user'];?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">

                    <!-- Edit your profile -->
                    <li>
                        <a href="<?php echo $this->createUrl("/admin/user/sa/modifyuser/uid/".Yii::app()->user->getId()); ?>"><?php eT("Edit your profile");?></a>
                    </li>

                    <!-- Edit your personal preferences -->
                    <li>
                        <a href="<?php echo $this->createUrl("/admin/user/sa/personalsettings"); ?>"><?php eT("Edit your personal preferences");?></a>
                    </li>
                    <!-- Manuales de usuario -->
                    <li>
                        <a href="https://drive.google.com/a/uniquindio.edu.co/folderview?id=0B6izFhsgbJgaamt0ZElXNWZFeGM&usp=sharing" target="_blank">Manuales de usuario</a>
                    </li>


                    <li class="divider"></li>

                    <!-- Logout -->
                    <li>
                        <a href="<?php echo $this->createUrl("admin/authentication/sa/logout"); ?>">
                            <?php eT("Logout");?>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- active surveys -->
            <?php if ($activesurveyscount > 0): ?>
                <li>
                    <a href="<?php echo $this->createUrl('admin/survey/sa/listsurveys/active/Y');?>">
                        <?php neT("{n} active survey|{n} active surveys",$activesurveyscount); ?>
                    </a>
                </li>
            <?php endif;?>

            <?php if($showupdate): ?>
            <li class="">
                <a href="#notifications">
                    <?php if($showupdate): ?>
                        <span class=" label update-small-notification <?php if(Yii::app()->session['notificationstate']=='1' || Yii::app()->session['unstable_update'] ){echo 'hidden';};?>" >1</span>
                    <?php endif;?>
                    <i class="nav-icon fa fa-bullhorn"></i>
                </a>

                <!-- NOTIFICATIONS -->
                <?php if($showupdate): ?>
                <ul class="dropdown-menu update-small-notification <?php if(Yii::app()->session['notificationstate']=='1' || Yii::app()->session['unstable_update'] ){echo 'hidden';};?>" role="menu">
                    <li class="hidden-xs  notifications-list " id="main-navbar-notifications" >
                        <strong><?php eT("A new update is available.");?> </strong> <a href="<?php echo Yii::app()->createUrl("admin/update"); ?>"><?php eT('Click here to use ComfortUpdate.');?></a>
                    </li>
                </ul> <!-- / .dropdown-menu -->
                <?php endif;?>
            </li>
            <?php endif;?>
        </ul>
    </div><!-- /.nav-collapse -->
</nav>
<img src="<?php echo IMAGE_BASE_URL?>LINEA-COLORES-UQ.png" alt="Linea colores institucionales" style="height: 5px; width: 100%;" class="navbar-header">
</div>
<br>
