<?php ?>
<div class="side-body">
    <h3 class="pagetitle">Modificación de fechas</h3>
    <?php echo CHtml::form(array('admin/survey/sa/changedate/surveyid/'.$surveyid), 'post', array('id'=>'changedate', 'name'=>'changedate', 'class'=>'form-horizontal')); ?>
        <div class='col-sm-12 col-md-12 col-xs-12'>
            <!-- Text elements -->
            <div class="row">
              <div class="form-group">
                    <input type='hidden' name='surveyid' id='surveyid' value='<?php echo $surveyid;?>' />
                    <label class="col-sm-6 control-label" for='startdate'><?php  eT("Start date/time:"); ?></label>
                    <div class="col-sm-6">
                        <input type='text' class='popupdatetime' id='startdate' size='20' name='startdate' value="<?php echo $startdate; ?>"  />
                    </div>
                </div>

                <!-- Expiry date/time -->
                <div class="form-group">
                    <label class="col-sm-6 control-label" for='expires'><?php  eT("Expiry date/time:"); ?></label>
                    <div class="col-sm-6">
                        <input type='text' class='popupdatetime' id='expires' size='20' name='expires' value="<?php echo $expires; ?>"  />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-10 text-center">
                          <button type="submit" name="save"  class="btn btn-success btn-block" value='save' id="save"><?php eT("Save"); ?></button>
                    </div>
                </div>
            </div>
        </div>
</div>
<?php echo CHtml::endForm(); ?>