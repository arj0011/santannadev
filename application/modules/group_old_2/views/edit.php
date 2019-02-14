<style> 
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style> 
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('group/group_update') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label"><?php echo lang('type');?></label>
                                    <div class="col-md-9">
                                          <select class="form-control" name="type" id="type">
                                           
                                            <?php $groups = allGroups();foreach($groups as $key=>$val){?>
                                                <option value="<?php echo $key;?>" <?php echo ($results->type == $key) ? "selected" : "";?>><?php echo $val;?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_name');?></label>
                                    <div class="col-md-9">
                                       <select  class="js-example-basic-multiple"   id="user_name" name="user_name[]" multiple="" style="width:100%;">
                                            <?php foreach($users as $user) { ?>
                                                      <option value="<?php echo $user->id; ?>" <?php if(!empty($results->user_name) && is_array(json_decode($results->user_name))){ if(in_array($user->id,json_decode($results->user_name))){echo "selected";}}?>><?php echo $user->name; ?></option>
                                               <?php } ?>

    
                                         </select>
                                    </div>
                                    
                                </div>
                            </div> 

                             
                          
                          
                            
                             
                            <input type="hidden" name="id" value="<?php echo $results->group_id;?>" />
                           
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script type="text/javascript">
$(".js-example-basic-multiple").select2({
  placeholder: 'Select User',
  allowClear: true
  
});
</script>