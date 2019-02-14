<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('floors/floors_update') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo lang('close_btn');?></span></button>
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
                                    <label class="col-md-3 control-label"><?php echo lang('location_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $results->name;?>"/>
                                    </div>
                                    
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_width');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="roomWidth" id="roomWidth" value="<?php echo $results->roomWidth;?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_height');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="roomHeight" id="roomHeight" value="<?php echo $results->roomHeight;?>"/>
                                    </div>
                                </div>
                            </div>
                            
                                <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_image');?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file" class="input_img2" id="image" name="file" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                    <?php if(!empty($results->image)){ ?>
                                                        <img src="<?php echo base_url().'/uploads/floors/'.$results->image;?>">
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
                                    <label class="col-md-3 control-label"><?php echo lang('agents');?></label>
                                    <div class="col-md-9">
                                        <select name="agent" id="agent" class="form-control">
                                        <option value=""><?php echo lang('select_agents');?></option>
                                        <?php
                                            if(!empty($agentslist)):
                                                foreach ($agentslist as $agent):
                                        ?>            
                                            <option value="<?php echo $agent->id;?>" <?php echo ($agent->id == $results->agent) ? 'selected' : '';?>><?php echo $agent->full_name;?></option>
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
                                            <option value="<?php echo $store->id;?>" <?php echo ($store->id == $results->store) ? 'selected' : '';?>><?php echo $store->store_name;?></option>
                                        <?php
                                                endforeach;
                                            endif;
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                             <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                           
                            <input type="hidden" name="exists_image" value="<?php echo $results->image;?>" />
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('close_btn');?></button>
                    <button type="submit"  class="btn btn-primary" id="submit"><?php echo lang('update_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
