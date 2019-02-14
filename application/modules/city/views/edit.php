<style> 
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style> 
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('city/city_update') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label"><?php echo lang('title_en');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title_en" id="title_en" value="<?php echo $results->title_en;?>" />
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('title_el');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title_el" id="title_el" value="<?php echo $results->title_el;?>" />
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                          </div>
                            <div class="col-md-12" >
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('description_en');?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="description_en" id="description_en"><?php echo $results->description_en;?></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                            
                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('description_el');?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="description_el" id="description_el"><?php echo $results->description_el;?></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                          </div>
                            <div class="col-md-12" >
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('site_name_en');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="site_name_en" id="site_name_en" value="<?php echo $results->site_name_en;?>" />
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                            
                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('site_name_el');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="site_name_el" id="site_name_el" value="<?php echo $results->site_name_el;?>" />
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                          </div>  
                            <div class="col-md-12" >
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_en');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="location_en" id="location_en" value="<?php echo $results->location_en;?>" />
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                </div>
                            </div>
                            
                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('location_el');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="location_el" id="location_el" value="<?php echo $results->location_el;?>" />
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                </div>
                            </div>
                         </div>
                         <div class="col-md-12" >   
                           <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-1 control-label"><?php echo lang('contact_images'); ?></label>
                                    <div class="col-md-11">
                                        <div class="field" align="left">
                                            <input type="file" id="files" name="files[]" multiple />
                                             <?php if(!empty($results->company_images)){
                                                $i=1;$company_image = json_decode($results->company_images);
                                                foreach($company_image as $img){?>
                                                <span class="pip rm<?php echo $i;?>">
                                                    <img class="imageThumb" width="100" src="<?php if(!empty($img)){echo base_Url()?>uploads/city/<?php echo $img;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" />
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
                          </div>  
                            <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                           
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit_edit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>