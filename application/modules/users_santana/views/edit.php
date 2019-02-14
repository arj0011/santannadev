<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('users/user_update') ?>" enctype="multipart/form-data">
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
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="user_name" id="user_name" value="<?php echo $results->name;?>"/>
                                    </div>
                                    <!-- <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span> -->
                                </div>
                            </div>
                            
                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_email');?></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="user_email" id="user_email" value="<?php echo $results->email;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 
                            
                            <div class="col-md-12" >
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_number');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo $results->phone_number;?>"/>
                                    </div>
                                </div>
                            </div>
                            
                             
                            
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('new_password');?></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="new_password" id="new_password" />
                                    </div>
                                </div>
                            </div>
                           </div> 
                            
                              <div class="col-md-12" >
                               <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('confirm_password');?></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="confirm_password1" id="confirm_password1" />
                                    </div>
                                </div>
                            </div>
                           
                           <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_gender');?></label>
                                    <div class="col-md-9">
                                    <div class="minus_left custom_chk chk_box">
                                    <div class="checkbox">
                                        <input type="radio" name="user_gender" id="user_gender" <?php if($results->gender=='MALE') echo 'checked="checked"';?> value="MALE"><label class="checkbox-inline"><?php echo lang('male');?></label>
                                        </div>
                                        <div class="checkbox">
                                        <input type="radio" name="user_gender" id="user_gender" <?php if($results->gender=='FEMALE') echo 'checked="checked"';?> value="FEMALE"><label class="checkbox-inline"><?php echo lang('female');?></label>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           </div> 

                             <div class="col-md-12" >
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('date_of_birth');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="date_of_birth" id="date_of_birth" value="<?php echo ($results->date_of_birth != "1970-01-01") ? date(DEFAULT_DATE,strtotime($results->date_of_birth)) : "";?>" readonly=""/>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('title');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title" id="title" value="<?php echo $results->title;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                             <div class="col-md-12" >
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('add_member_first_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="add_member_first_name" id="add_member_first_name" value="<?php echo $results->add_member_first_name;?>"/>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('add_member_last_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="add_member_last_name" id="add_member_last_name" value="<?php echo $results->add_member_last_name;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                             <div class="col-md-12" >
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('company_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="company_name" id="company_name" value="<?php echo $results->company_name;?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('name_day');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name_day" id="name_day" value="<?php echo $results->name_day;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                             <div class="col-md-12" >
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('nationality');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="nationality" id="nationality" value="<?php echo $results->nationality;?>"/>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('other_email');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="other_email" id="other_email" value="<?php echo $results->other_email;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                            <div class="col-md-12" >
                             <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('zip_code');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="zip_code" id="zip_code" value="<?php echo $results->zip_code;?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('country_code');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="country_code" id="country_code" value="<?php echo $results->country_code;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                             <div class="col-md-12" >
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('country_code_secondary');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="country_code_secondary" id="country_code_secondary" value="<?php echo $results->country_code_secondary;?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('phone_number_secondary');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone_number_secondary" id="phone_number_secondary" value="<?php echo $results->phone_number_secondary;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                            <div class="col-md-12" >
                             <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('mobile_number_country_code');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="mobile_number_country_code" id="mobile_number_country_code" value="<?php echo $results->mobile_number_country_code;?>"/>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('mobile_number');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="mobile_number" id="mobile_number" value="<?php echo $results->mobile_number;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                             <div class="col-md-12" >
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('fax_country_code');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="fax_country_code" id="fax_country_code" value="<?php echo $results->fax_country_code;?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('fax');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="fax" id="fax" value="<?php echo $results->fax;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                             <div class="col-md-12" >
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('group_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="group_name" id="group_name" value="<?php echo $results->group_name;?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('reference');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="reference" id="reference" value="<?php echo $results->reference;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                            <div class="col-md-12" >
                             <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('suppliers');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="suppliers" id="suppliers" value="<?php echo $results->suppliers;?>"/>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('hoteliers');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="hoteliers" id="hoteliers" value="<?php echo $results->hoteliers;?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                             <div class="col-md-12" >
                              <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('concierge');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="concierge" id="concierge" value="<?php echo $results->concierge;?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('points');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="points" id="points" value="<?php echo $results->points;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 

                            <div class="col-md-12" >
                            <div class="col-md-6">
                               <div class="form-group">
                                    <label class="col-md-3 control-label">Notes</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="notes" id="notes"><?php echo $results->notes;?></textarea>
                                    </div>
                                </div>
                            </div>
                           
                                <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('profile_image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="user_image" name="user_image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                    <?php if(!empty($results->user_image)){ ?>
                                                        <img src="<?php echo base_url().$results->user_image;?>">
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
                              </div>  
                                
                            
                             <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                             <input type="hidden" name="password" value="<?php echo $results->password;?>" />
                            <input type="hidden" name="exists_image" value="<?php echo $results->user_image;?>" />
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
                //format: 'yyyy-mm-dd',
       
       
       });
</script>