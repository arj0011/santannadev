<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
.datepicker{z-index:9999 !important;}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('store/store_update') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label"><?php echo lang('store_Name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_name" id="store_name" value="<?php echo $results->store_name;?>"/>
                                    </div>
                                    
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('store_email');?></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="store_email" id="store_email" value="<?php echo $results->email;?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('store_place');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_place" id="store_place" value="<?php echo $results->store_place;?>"/>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('new_password');?></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="new_password" id="new_password" />
                                    </div>
                                </div>
                            </div>
                            
                              <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('confirm_password');?></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="confirm_password1" id="confirm_password1" />
                                    </div>
                                </div>
                            </div>

                             <input type="hidden" name="id" value="<?php echo $results->id;?>" />
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
