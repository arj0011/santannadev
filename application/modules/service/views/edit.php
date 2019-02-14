<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('service/service_update') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label"><?php echo lang('service_category');?></label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="service_category" id="service_category">
                                            <option value="">Select Service Category</option>
                                            <?php if(!empty($service_category)){foreach($service_category as $result){?>
                                              <option <?php if($results->store_category_id==$result->id) echo "selected";?> value="<?php echo $result->id;?>"><?php echo $result->category_name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_name_en');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="service_name_en" id="service_name_en" placeholder="<?php echo lang('store_name_en');?>" value="<?php echo $results->store_name_en;?>"/>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                           </div> 
                             <div class="col-md-12" >
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_name_el');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="service_name_el" id="service_name_el" placeholder="<?php echo lang('store_name_el');?>" value="<?php echo $results->store_name_en;?>"/>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_address_en');?></label>
                                    <div class="col-md-9">
                                        <textarea  class="form-control" name="service_address_en" id="service_address_en" placeholder="<?php echo lang('store_address_en');?>"><?php echo $results->store_address_en;?></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                           </div> 
                             <div class="col-md-12" >
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_address_el');?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="service_address_el" id="service_address_el" placeholder="<?php echo lang('store_address_el');?>"><?php echo $results->store_address_el;?></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                            
  <!--                          <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('latitude');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="latitude" id="latitude" placeholder="<?php echo lang('latitude');?>" value="<?php echo $results->store_lat;?>"/>
                                    </div>
                                </div>
                            </div>
                          </div>  
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('longitude');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="longitude" id="longitude" placeholder="<?php echo lang('longitude');?>" value="<?php echo $results->store_lang;?>"/>
                                    </div>
                                </div>
                            </div>  -->
                            
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('email');?></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="<?php echo lang('email');?>" value="<?php echo $results->email;?>"/>
                                    </div>
                                </div>
                            </div>
                         </div>   
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('opening');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control closing_sub" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii" name="opening" id="opening" placeholder="<?php echo lang('opening');?>" onchange="time_check()" value="<?php echo $results->open_time;?>"/>
                                    </div>
                                </div>
                               
                            </div>
                            
                            <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('closing');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control closing_sub2" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii" name="closing" id="closing" placeholder="<?php echo lang('closing');?>" onchange="time_check()" value="<?php echo $results->close_time;?>"/>
                                    </div>
                                </div>
                            </div>
                         </div>   
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_number');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control"  name="contact_number" id="contact_number" placeholder="<?php echo lang('contact_number');?>" value="<?php echo $results->contact_number;?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('service_image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="service_image" name="service_image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                    <?php if(!empty($results->store_file)){ ?>
                                                        <img src="<?php echo base_url().'uploads/service/'.$results->store_file;?>">
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
                             <input type="hidden" name="id" value="<?php echo $results->store_id;?>" />
                            <input type="hidden" name="exists_image" value="<?php echo $results->store_file;?>" />
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="btn btn-primary" onclick="time_check()">Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $('.closing_sub').datetimepicker({
        
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });
    $('.closing_sub2').datetimepicker({
        
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });
</script>