<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('users/user_view') ?>" enctype="multipart/form-data">
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
                                 <div class="col-md-6" >
                                 <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_name');?> </label>
                                    <div class="col-md-9">
                                        <?php echo $result->name;?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                             <div class="form-group">
                               <label class="col-md-3 control-label"><?php echo lang('user_email');?> </label>
                                    <div class="col-md-9">
                                         <?php echo $result->email;?>
                                    </div>
                                    
                                </div>
                              </div>  
                            </div>

                             <div class="col-md-12" >
                              <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_number');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->phone_number;?>
                                    </div>
                                </div>
                            </div>
                           
                             <div class="col-md-6" >
                                
                                 <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_gender');?> </label>
                                    <div class="col-md-9">
                                         <?php echo $result->gender;?>
                                    </div>
                                </div>
                            </div>
                           </div> 
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                     <label class="col-md-3 control-label"><?php echo lang('date_of_birth');?> </label>
                                    <div class="col-md-9">
                                       <?php echo convertDate($result->date_of_birth);?>
                                    </div>
                                </div>
                                 
                            </div>
                           
                             <div class="col-md-6" >
                                <div class="form-group">
                                     <label class="col-md-3 control-label"><?php echo lang('title');?> </label>
                                    <div class="col-md-9">
                                        <?php echo $result->title;?>
                                    </div>
                                </div>
                            </div>
                           </div>
                            <hr>
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('add_member_first_name');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->add_member_first_name;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('add_member_last_name');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->add_member_last_name;?>
                                    </div>
                                </div>
                            </div>
                           </div>

                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('company_name');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->company_name;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('name_day');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->name_day;?>
                                    </div>
                                </div>
                            </div>
                           </div>

                           <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('nationality');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->nationality;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('other_email');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->other_email;?>
                                    </div>
                                </div>
                            </div>
                           </div>

                           <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('zip_code');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->zip_code;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('country_code');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->country_code;?>
                                    </div>
                                </div>
                            </div>
                           </div>

                           <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('country_code_secondary');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->country_code_secondary;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('phone_number_secondary');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->phone_number_secondary;?>
                                    </div>
                                </div>
                            </div>
                           </div>

                           <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('mobile_number_country_code');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->mobile_number_country_code;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('mobile_number');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->mobile_number;?>
                                    </div>
                                </div>
                            </div>
                           </div>
                            <hr>
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('fax_country_code');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->fax_country_code;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('fax');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->fax;?>
                                    </div>
                                </div>
                            </div>
                           </div>

                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('group_name');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->group_name;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('reference');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->reference;?>
                                    </div>
                                </div>
                            </div>
                           </div>

                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('suppliers');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->suppliers;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('hoteliers');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->hoteliers;?>
                                    </div>
                                </div>
                            </div>
                           </div>

                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('concierge');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->concierge;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('points');?> </label>
                                    <div class="col-md-9">
                                       <?php echo $result->points;?>
                                    </div>
                                </div>
                            </div>
                           </div>


                              <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label">Notes </label>
                                    <div class="col-md-9">
                                       <?php echo $result->notes;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('profile_image');?> </label>
                                    <div class="col-md-9">
                                       <?php if(!empty($result->user_image)){ ?>
                                                        <img width="60px;" height="60px;" src="<?php echo base_url().$result->user_image;?>">
                                                    <?php }else{ ?>
                                                        <img width="60px;" height="60px;" src="<?php echo base_url().'assets/img/default.jpg';?>">
                                                   <?php }?>
                                    </div>
                                </div>
                            </div>
                           </div>


                           
                           
                             
                
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                   
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

