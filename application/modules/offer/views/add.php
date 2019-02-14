<style>
    .modal-footer .btn + .btn {
        margin-bottom: 5px !important;
        margin-left: 5px;
    }
    .datepicker{z-index:9999 !important;}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('offer/offer_add') ?>" enctype="multipart/form-data">
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
                                <div class="form-group ">
                                    <label class="col-lg-3 control-label" style="padding-right:5%"> 
                                        <?php echo lang('select_option'); ?></label>
                                    <div class="col-md-9">
                                        <div class="minus_left custom_chk chk_box">
                                            <div class="checkbox">
                                                <input type="radio" class="all_user" name="notification" value="1"><label class="checkbox-inline"><?php echo lang('all_devices'); ?></label>
                                            </div>
                                            <div class="checkbox">
                                                <input type="radio" class="all_user" name="notification" value="2"><label class="checkbox-inline"> <?php echo lang('all_users'); ?></label>
                                            </div>
                                            <div class="checkbox">
                                                <input type="radio" class="all_user" name="notification" value="3" checked value="3"><label class="checkbox-inline"><?php echo lang('select_group'); ?></label>
                                            </div>

                                        </div>

                                    </div>
                                </div>  
                                <div class="clearfix"></div>



                                <div id="dvUser" style="display: none">
                                    <select class="formcontrol" multiple="" name="user[]" id="user" style="width:100%">

                                        <!-- <?php if (!empty($results)) {
                                            foreach ($results as $result) { ?>
                                                   <option value="<?php echo $result->group_id; ?>"><?php echo $result->group_name; ?></option>
    <?php }
} ?>  -->
                                    </select>

                                </div>



                                <div class="col-md-12" > 
                                    <div class="col-md-6" >

                                        <div class="form-group">
                                            <div id="dvGroupSelect">
                                                <label class="col-md-3 control-label"><?php echo lang('select_group'); ?></label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="group_name" id="group_name">
                                                        <option value=""><?php echo lang('select_group'); ?></option>
                                                        <?php if (!empty($results)) {
                                                            foreach ($results as $result) { ?>
                                                                <option value="<?php echo $result->group_id; ?>"><?php echo $result->group_name; ?></option>
    <?php }
} ?>
                                                    </select>
                                                    <span class="help-block m-b-none"><?php echo form_error('group_name'); ?></span>  
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" >

                                        <div class="form-group">
                                            <div id="dvUserSelect">
                                                <label class="col-md-3 control-label">
<?php echo lang('select_user'); ?></label>
                                                <div class="col-md-9">

                                                    <select class="formcontrol" multiple="" name="user_name[]" id="user_name" placeholder="<?php echo lang('select_user'); ?>" style="width:100%">

                                                        <!-- <?php if (!empty($results)) {
                                                            foreach ($results as $result) { ?>
                                                                   <option value="<?php echo $result->group_id; ?>"><?php echo $result->group_name; ?></option>
    <?php }
} ?>  -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>





                                <div class="col-md-12" >
                                    
                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Offer Type</label>
                                            <div class="col-md-9">
                                                <select name="offer_type" id="offer_type" class="form-control" onchange="showFieldType(this.value)">
                                                    <option value="PERCENTAGE">Percentage %</option>
                                                    <option value="LOYALTY_POINT">Loyalty Point</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="col-md-6" style="display:none" id="offerPoints">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Loyalty Points</label>
                                            <div class="col-md-9">
                                                <input minlength="1" type="text" class="form-control" name="offer_points" id="offer_points"  placeholder="0" onkeyup="if (/\D/g.test(this.value))
                                                    this.value = this.value.replace(/\D/g, '')"/>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" style="display:block" id="offerPercentage">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('discounts_in_percent'); ?></label>
                                            <div class="col-md-9">
                                                <input minlength="1" maxlength="3" type="text" class="form-control" name="discounts_in_percent" id="discounts_in_percent"  placeholder="00" onkeyup="if (/\D/g.test(this.value))
                                                    this.value = this.value.replace(/\D/g, '')"/>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('offer_name_en'); ?></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="offer_name_en" id="offer_name_en" placeholder="<?php echo lang('offer_name_en'); ?>"/>
                                            </div>
                                            <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note'); ?></span>

                                        </div>
                                    </div>

                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('offer_name_el'); ?></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="offer_name_el" id="offer_name_el"  placeholder="<?php echo lang('offer_name_el'); ?>"/>
                                            </div>
                                            <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note'); ?></span>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-12" >
                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('offer_limit'); ?></label>
                                            <div class="col-md-9">
                                                <input  type="text" class="form-control" name="offer_limit" id="offer_limit"  placeholder="<?php echo lang('offer_limit'); ?>" onkeyup="if (/\D/g.test(this.value))
                                                    this.value = this.value.replace(/\D/g, '')"/>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('offer_code'); ?></label>
                                            <?php
                                            $characters = '1234567890';

                                            $string = '';

                                            for ($i = 0; $i < 6; $i++) {
                                                $string .= $characters[rand(0, strlen($characters) - 1)];
                                            }
                                            ?>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" readonly="" name="offer_code" id="offer_code" value="<?Php echo $string; ?>" placeholder="<?php echo lang('offer_code'); ?>" />
                                            </div>
                                        </div>
                                    </div>

                                    <!--                                     <div class="col-md-6" >
                                                                    <div class="form-group">
                                                                    <label class="col-md-3 control-label"><?php echo lang('no_of_scane'); ?></label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control" name="no_of_scane" id="no_of_scane" />
                                                                        </div>
                                                                       
                                                                    </div>
                                                                </div>-->

                                </div>
<!--                                <div class="col-md-12" >


                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('description_el'); ?></label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" name="description_el" id="description_el" placeholder="<?php echo lang('description_el'); ?>"></textarea>
                                            </div>
                                            <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note'); ?></span>


                                        </div>
                                    </div>


                                </div>-->

                                <div class="col-md-12" >
                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('from_date'); ?></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="from_date" id="from_date" placeholder="<?php echo lang('from_date'); ?>" readonly=""/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('to_date'); ?></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="to_date" id="to_date" placeholder="<?php echo lang('to_date'); ?>" readonly=""/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" >
                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('description_en'); ?></label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" name="description_en" id="description_en"  placeholder="<?php echo lang('description_en'); ?>"></textarea>
                                            </div>
                                            <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note'); ?></span>

                                        </div>
                                    </div>

                                    <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo lang('image'); ?></label>
                                            <div class="col-md-9">
                                                <div class="profile_content edit_img">
                                                    <div class="file_btn file_btn_logo">
                                                        <input type="file"  class="input_img2" name="service_image" style="display: inline-block;">
                                                        <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                            <div id="show_company_img"></div>
                                                            <span class="ceo_logo">

                                                                <img src="<?php echo base_url() . 'assets/img/default.jpg'; ?>">


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
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('reset_btn'); ?></button>
                            <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('submit_btn'); ?></button>
                        </div>
                        </form>
                    </div> <!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
        </div>
        <script type="text/javascript">
            $('input[name="no_of_scane"]').keyup(function (e)
            {
                if (/\D/g.test(this.value))
                {
                    // Filter non-digits from input value.
                    this.value = this.value.replace(/\D/g, '');
                }
            });

            $("#to_date").datepicker({
                todayBtn: "linked",
                format: 'dd/mm/yyyy',
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                startDate: '-0m'
            });

            $("#from_date").datepicker({
                startDate: '-0m',
                format: 'dd/mm/yyyy',
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
            });





        </script>

        <script type="text/javascript">
            $('#user_name').select2();
        </script>

        <script type="text/javascript">
            $('#user').select2();
        </script>