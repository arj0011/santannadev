<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('notification/notification_add') ?>" enctype="multipart/form-data">
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
                                 <input type="radio" class="all_user" name="notification" value="1"> <label class="checkbox-inline"><?php echo lang('all_devices');?></label>
                                 </div>
                                 <div class="checkbox">
                                 <input type="radio" class="all_user" name="notification" value="2"><label class="checkbox-inline"> <?php echo lang('all_users');?></label>
                                 </div>
                                 <div class="checkbox">
                                 <input type="radio" class="all_user" name="notification" value="3" checked value="3"><label class="checkbox-inline"><?php echo lang('select_group');?></label>
                                 </div>
                                 <div class="checkbox">
                                  <input type="radio" class="all_user" name="notification" value="4"><label class="checkbox-inline"><?php echo lang('select_name');?></label>
                                 </div>
                                 </div>
                                 <div id="dvUser" style="display: none">
                                 <select class="formcontrol" multiple="" name="user[]" id="user" style="width:100%">
                                           
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
                                            <?php if(!empty($results)){foreach($results as $result){?>
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
                                          
                                        </select>
                                       </div>
                                    </div>
                                 </div>


                               <div class="form-group">
                                     <div id="singleUser" style="display: none">
                                    <label class="col-md-3 control-label">
                                    <?php echo lang('select_name');?></label>
                                    <div class="col-md-9">
                                      
                                        <select class="" name="user_id" id="user_id" style="width:100%">
                                            <option value=""><?php echo lang('select_name');?></option>
                                            <?php if(!empty($users)){foreach($users as $user){?>
                                              <option value="<?php echo $user->id;?>"><?php echo $user->name;?></option>
                                            <?php }}?>
                                        </select>
                                       </div>
                                    </div>
                                </div>
                           
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang('title');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title" id="title" placeholder="<?php echo lang('title');?>"/>
                                    </div>
                                    
                                   
                                </div>
                            </div>
                                     
                 

                              <div class="col-md-12" >
                                  
                                <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang('message');?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="message" id="message"  placeholder="<?php echo lang('message');?>"></textarea>
                                    </div>
                                    
                                </div>
                            </div>
                                

                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('reset_btn');?></button>
                    <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('send');?></button>
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

<script type="text/javascript">
  $('#user_id').select2();
</script>