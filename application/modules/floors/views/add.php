<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('floors/floors_add') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo lang('close_btn');?></span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                </div>
                <div class="modal-body addlocbodycls">
                    <div class="loaders">
                        <img src="<?php echo base_url().'assets/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="<?php echo lang('location_name');?>" />
                                    </div>
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_width');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="roomWidth" id="roomWidth" placeholder="<?php echo lang('location_width');?>" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_height');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="roomHeight" id="roomHeight" placeholder="<?php echo lang('location_height');?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_image');?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="image" name="image" style="display: inline-block;">
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
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('agents');?></label>
                                    <div class="col-md-9">
                                        <select name="agent" id="agent" class="form-control">
                                        <option value=""><?php echo lang('select_agents');?></option>
                                        <?php
                                            if(!empty($agentslist)):
                                                foreach ($agentslist as $agent):
                                        ?>            
                                            <option value="<?php echo $agent->id;?>"><?php echo $agent->full_name;?></option>
                                        <?php
                                                endforeach;
                                            endif;
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('store');?></label>
                                    <div class="col-md-9">
                                        <select name="store" id="store" class="form-control">
                                        <option value=""><?php echo lang('select_stores');?></option>
                                        <?php
                                            if(!empty($storeslist)):
                                                foreach ($storeslist as $store):
                                        ?>            
                                            <option value="<?php echo $store->id;?>"><?php echo $store->store_name;?></option>
                                        <?php
                                                endforeach;
                                            endif;
                                        ?>
                                        </select>
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

<script type="text/javascript">

 $('#date_of_birth').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                endDate:'today'
       
       
       });
</script>