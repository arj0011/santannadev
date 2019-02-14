<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('news/news_add') ?>" enctype="multipart/form-data">
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
                           
      
                              <div class="form-group ">
                                <label class="col-lg-3 control-label"> 
                                <?php echo lang('select_option');?></label>
                                 <div class="col-md-9">
                                  <div class="minus_left custom_chk chk_box">
                                     <div class="checkbox">
                                     <input type="radio" class="all_user" name="notification" value="1"><label class="checkbox-inline"><?php echo lang('all_devices');?></label>
                                     </div>
                                     <div class="checkbox">
                                     <input type="radio" class="all_user" name="notification" value="2"><label class="checkbox-inline"><?php echo lang('all_users');?></label>
                                     </div>
                                     <div class="checkbox">
                                     <input type="radio" class="all_user" name="notification" value="3" checked value="3"><label class="checkbox-inline"><?php echo lang('select_group');?></label>
                                     </div>
                                   </div>  
                                     
                                  <div id="dvUser" style="display: none">
                                 <select class="formcontrol" multiple="" name="user[]" id="user" style="width:100%">
                                            
                                           <!-- <?php if(!empty($results)){foreach($results as $result){?>
                                              <option value="<?php echo $result->group_id;?>"><?php echo $result->group_name;?></option>
                                            <?php }}?>  -->
                                        </select>

                                  </div>
                                  </div>
                                 
                                </div>
                                                        <div class="form-group">
                                 <div id="dvGroupSelect">
                                 <label class="col-md-3 control-label"><?php echo lang('select_group');?></label>
                                    <div class="col-md-9">
                                         <select class="form-control" name="group_name" id="group_name">
                                            <option value=""><?php echo lang('select_group');?></option>

                                            <?php 
                                            $options_data = array('table' => GROUPS,'select' => 'group_name,group_id');
                                          if(getDefaultLanguage() == "el"){
                                             $options_data['select'] = 'group_name,group_id';
                                          }
                                          $results = $this->Common_model->customGet($options_data);

                                            if(!empty($results)){foreach($results as $result){?>
                                              <option value="<?php echo $result->group_id;?>"><?php echo $result->group_name;?></option>
                                            <?php }}?>
                                        </select>
                                        <span class="help-block m-b-none"><?php echo form_error('group_name'); ?></span> 
                                    </div>
                                    </div>
                                    </div>
                                    <div class="form-group">
                                     <div id="dvUserSelect">
                                    <label class="col-md-3 control-label">
                                    <?php echo lang('select_user');?></label>
                                    <div class="col-md-9">
                                      
                                         <select class="formcontrol" multiple="" name="user_name[]" id="user_name" placeholder="<?php echo lang('select_user');?>" style="width:100%">
                                            
                                           <!-- <?php if(!empty($results)){foreach($results as $result){?>
                                              <option value="<?php echo $result->group_id;?>"><?php echo $result->group_name;?></option>
                                            <?php }}?>  -->
                                        </select>
                                       </div>
                                    </div>
                                 </div>
                                </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('news_category');?></label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="news_category" id="news_category">
                                            <option value=""><?php echo lang('select_news_cat');?></option>
                                            <?php if(!empty($news)){foreach($news as $result){?>
                                              <option value="<?php echo $result->id;?>"><?php echo $result->category_name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('news_title_en');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="news_title_en" id="news_title_en" placeholder="<?php echo lang('news_title_en');?>"/>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('news_title_el');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="news_title_el" id="news_title_el" placeholder="<?php echo lang('news_title_el');?>"/>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('news_content_en');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="news_content_en" id="news_content_en" placeholder="<?php echo lang('news_content_en');?>"></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('news_content_el');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="news_content_el" id="news_content_el" placeholder="<?php echo lang('news_content_el');?>"></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('news_location');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="news_location" id="news_location" placeholder="<?php echo lang('news_location');?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="news_image" name="news_image" style="display: inline-block;">
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
                    <button type="submit"  class="btn btn-primary" id="submit" ><?php echo lang('submit_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
  $('#user_name').select2();
</script>

<script type="text/javascript">
  $('#user').select2();
</script>