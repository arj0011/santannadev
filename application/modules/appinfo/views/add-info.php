<style>
    .modal-footer .btn + .btn {
        margin-bottom: 5px !important;
        margin-left: 5px;
    }
    .remove_file{color: #000000;
                 cursor: pointer;
                 float: left;
                 font-weight: bolder !important;
                 margin-left: 20px;
                 margin-top: 7px !important;
                 display:none;
    }
    .remove_old{cursor: pointer;
                float: left;
                padding-left: 20px;
                padding-top: 7px;}
    .old_info_file{ float: left;
                    width: 250px !important;float:left;margin-left:5px;margin-top: 5px;}
    .old_info_file_title{
        width: 150px !important;float:left;margin-top: 5px;}

    .old_div{float:left;margin-top:10px;margin-bottom:10px;}   
    .info_file{float:left;width:250px;height:auto !important;margin-left:5px;} 
    .contact_file{float:left;width:250px;height:auto !important;margin-left:5px;} 
    .old_contact_file{ float: left;
                       width: 250px !important;float:left;margin-left:5px;margin-top: 5px;}
    .info_file_title{float:left;width:150px;height:42px !important;} 
    .info_file_div{float:left;padding-top:5px;padding-bottom:5px;}
    .file_icon{width:30px;height:30px;}
    #contact_files_div{margin-top:5px;}
    .old_contact_div img{ float: left;
                          height: 35px;
                          margin-top: 5px;
                          width: 35px;}
    </style>
    <div id="commonModalInfo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('appinfo/add_app_info') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                </div>
                <div class="modal-body">
                    <div class="loaders">
                        <img src="<?php echo base_url() . 'assets/images/Preloader_3.gif'; ?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('legal_text_en'); ?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="legal_text_en" id="legal_text_en" placeholder="<?php echo lang('legal_text_en'); ?>"></textarea>
                                    </div>
                                    <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note'); ?></span>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('legal_text_el'); ?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="legal_text_el" id="legal_text_el" placeholder="<?php echo lang('legal_text_el'); ?>"></textarea>
                                    </div>
                                    <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note'); ?></span>
                                </div>
                            </div>


                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('version'); ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="version" id="version" />
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('copyright'); ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="copyright" id="copyright" />
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_title_en'); ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="contact_title_en" id="contact_title_en" />
                                    </div>
                                    <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note'); ?></span>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_title_el'); ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="contact_title_el" id="contact_title_el" />
                                    </div>
                                    <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note'); ?></span>
                                </div>
                            </div>


                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_images'); ?></label>
                                    <div class="col-md-9">
                                        <div class="field" align="left">
                                            <input type="file" id="files" name="files[]" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('info_files'); ?></label>
                                    <div class="col-md-9">
                                        <span class="add_more_file btn btn-danger" onclick="add_more_file()"><i class="fa fa-plus"></i><?php echo lang('add_more_files'); ?></span>
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-md-offset-2">

                                        <div id="old_files_div" class="col-sm-12">
                                        </div>
                                        <div class="col-sm-12 file_required" id="files_div">
                                            <div id="file0" class="info_file_div">
                                                <span class="store_file"></span>
                                                <input type="text" class="form-control info_file_title" placeholder="<?php echo lang('title'); ?>" id="info_file_title0" name="info_file_title[]">
                                                <input type="file" class="form-control info_file" id="info_file0" name="info_file[]">
                                                <span class="file_error" ></span>
                                                <span class="remove_file" onclick="remove_file(0)" >x</span> 
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>






                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('reset_btn'); ?></button>
                    <button type="submit"  class="btn btn-primary" ><?php echo lang('submit_btn'); ?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

