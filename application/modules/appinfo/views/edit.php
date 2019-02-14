<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}

</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('appinfo/appinfo_update') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label"><?php echo lang('app_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="company_name" id="company_name" value="<?php echo $results->company_name;?>" readonly=""/>
                                    </div>
                                </div>
                            </div>
                            
                           <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('app_mayor_greeting_en');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="ceo_message_en" id="ceo_message_en" placeholder="<?php echo lang('app_mayor_greeting_en');?>"><?php echo $results->ceo_message_en;?></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('app_mayor_greeting_el');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="ceo_message_el" id="ceo_message_el" placeholder="<?php echo lang('app_mayor_greeting_el');?>"><?php echo $results->ceo_message_el;?></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('app_email');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="email" id="email" value="<?php echo $results->email;?>" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('app_phone');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo $results->phone_number;?>" />
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('app_mayor_image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" name="ceo_image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                    <?php if(!empty($results->ceo_image)){ ?>
                                                        <img src="<?php echo base_url().'uploads/app/'.$results->ceo_image;?>">
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
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('app_news_main_image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img3" name="app_news_main_image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                   <?php if(!empty($results->news_event_image)){ ?>
                                                        <img src="<?php echo base_url().'uploads/app/'.$results->news_event_image;?>">
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
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('app_logo'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img3" name="app_logo" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                   <?php if(!empty($results->news_event_image)){ ?>
                                                        <img src="<?php echo base_url().'uploads/app/'.$results->company_logo;?>">
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


                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('app_images');?></label>
                                    <div class="col-md-9">
                                            <div class="field" align="left">
                                            <input type="file" id="files" name="files[]" multiple />
                                            <?php if(!empty($results->company_image)){
                                                $i=1;$company_image = json_decode($results->company_image);
                                                foreach($company_image as $img){?>
                                                <span class="pip rm<?php echo $i;?>">
                                                    <img class="imageThumb" width="100" src="<?php if(!empty($img)){echo base_Url()?>uploads/app/<?php echo $img;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" />
                                                    <br>
                                                    <span class="remove" onclick="removeImg('<?php echo $i;?>')">Remove image</span>
                                                    <input type="hidden" name="exisisCompanyImage[]" value="<?php echo $img;?>" />
                                                </span>

                                                <?php $i++;}
                                            }?>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            
                          
                              
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('close_btn');?></button>
                    <button type="submit" id="submit" class="btn btn-primary"><?php echo lang('update_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>