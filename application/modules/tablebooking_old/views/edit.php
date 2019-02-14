<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('agents/agents_update') ?>" enctype="multipart/form-data">
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
                             <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('full_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo $results->full_name;?>"/>
                                    </div>
                                    
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('email');?></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $results->email;?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_number');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo $results->phone_number;?>"/>
                                    </div>
                                </div>
                            </div>
                            
                             
                           
                           <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_gender');?></label>
                                    <div class="col-md-9">
                                        <label class="checkbox-inline"><input type="radio" name="gender" id="gender" <?php if($results->gender=='MALE') echo 'checked="checked"';?> value="MALE">MALE</label>
                                        <label class="checkbox-inline"><input type="radio" name="gender" id="gender" <?php if($results->gender=='FEMALE') echo 'checked="checked"';?> value="FEMALE">FEMALE</label>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-12" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('date_of_birth');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="date_of_birth" id="date_of_birth" value="<?php echo $results->date_of_birth;?>" readonly=""/>
                                    </div>
                                </div>
                            </div>
                            
                                <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('profile_image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="image" name="image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                    <?php if(!empty($results->image)){ ?>
                                                        <img src="<?php echo base_url().'/uploads/agents/'.$results->image;?>">
                                                    <?php }else{ ?>
                                                        <img src="<?php echo base_url().'assets/img/default.jpg';?>">
                                                   <?php }?>
                                                    
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
                                
                            
                             <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                           
                            <input type="hidden" name="exists_image" value="<?php echo $results->image;?>" />
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit"  class="btn btn-primary" id="submit">Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script type="text/javascript">
 $('#date_of_birth').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                endDate:'today',
                format: 'yyyy-mm-dd',
       
       
       });

</script>