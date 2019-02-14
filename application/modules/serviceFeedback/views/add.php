<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
    <link rel="stylesheet" type="text/css" media="screen"
     href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
    <script type="text/javascript"
     src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js">
    </script>
  <div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('service/service_add') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                </div>
                <div class="modal-body">
                    <div class="loaders">
                        <img src="<?php echo base_url().'assets/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_category');?></label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="service_category" id="service_category">
                                            <option value="">Select Service Category</option>
                                            <?php if(!empty($results)){foreach($results as $result){?>
                                              <option value="<?php echo $result->id;?>"><?php echo $result->category_name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_name_en');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="service_name_en" id="service_name_en" placeholder="<?php echo lang('service_name_en');?>"/>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_name_el');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="service_name_el" id="service_name_el" placeholder="<?php echo lang('service_name_el');?>"/>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_address_en');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="service_address_en" id="service_address_en" placeholder="<?php echo lang('service_address_en');?>"></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_address_el');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="service_address_el" id="service_address_el" placeholder="<?php echo lang('service_address_el');?>"></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('latitude');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="latitude" id="latitude" placeholder="<?php echo lang('latitude');?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('longitude');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="longitude" id="longitude" placeholder="<?php echo lang('longitude');?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('email');?></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="<?php echo lang('email');?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_number');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="<?php echo lang('contact_number');?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('opening');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control closing_sub" onfocusout="time_check()" name="opening" id="opening" placeholder="<?php echo lang('opening');?>"/>
                                    </div>
                                </div>
                            </div>
                             
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('closing');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control closing_sub" onfocusout="time_check()" name="closing" id="closing" placeholder="<?php echo lang('closing');?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="service_image" name="service_image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                  <img src="<?php echo base_url().'assets/img/default.jpg';?>">
                                                </span>
                                                <i class="fa fa-camera"></i>
                                              </span>
                                              <img class="show_company_img2" style="display:none" alt="img" src="<?php echo base_url() ?>/assets/img/logo.png">
                                              <span style="display:none" class="fa fa-close remove_img"></span>
                                            </div>
                                          </div>
                                          <div class="ceo_file_error file_error text-danger"></div>
                                    </div>
                                </div>
                            </div>
                                 <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger"><?php echo lang('reset_btn');?></button>
                    <button type="submit"  class="btn btn-primary" onclick="time_check()"><?php echo lang('submit_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
  jQuery('#opening').timepicker();
  jQuery('#closing').timepicker();
  
function time_check()
{
var firstTime  =jQuery('#opening').val();
var secondTime =jQuery('#closing').val();

//how do i compare time
            if(firstTime > secondTime || firstTime == secondTime)
            {
               Ply.dialog("alert", 'Closing time always greater then Opening time');
               jQuery('#closing').val('');
               $('#'+addFormAjax+' :input[type=submit]').attr('disabled',true);
            }
        }
</script>