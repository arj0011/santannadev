<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
.datepicker{z-index:9999 !important;}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('store/store_add') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label"><?php echo lang('store_Name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_name" id="store_name" placeholder="<?php echo lang('store_Name');?>" />
                                    </div>
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('store_email');?></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="store_email" id="store_email" placeholder="<?php echo lang('store_email');?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('store_place');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_place" id="store_place" placeholder="<?php echo lang('store_place');?>"/>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('store_password');?></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="store_password" id="store_password" placeholder="<?php echo lang('password');?>" />
                                    </div>
                                </div>
                            </div>
                            
                              <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('confirm_password');?></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="<?php echo lang('confirm_password');?>" />
                                    </div>
                                </div>
                            </div>
                        
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('reset_btn');?></button>
                    <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('submit_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
