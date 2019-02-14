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
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('offer/offer_update') ?>" enctype="multipart/form-data">
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
                                <!--                            <div class="col-md-6" >
                                                                <div class="form-group">
                                                                 <label class="col-md-3 control-label"><?php echo lang('store_name'); ?></label>
                                                                    <div class="col-md-9">
                                                                         <select class="form-control" name="store_name" id="store_name">
                                                                            <option value="">Select Service</option>
                                <?php if (!empty($results)) {
                                    foreach ($results as $result) { ?>
                                                                                      <option value="<?php echo $result->store_id; ?>"><?php echo $result->store_name; ?></option>
    <?php }
} ?>
                                                                        </select>
                                                                    </div>
                                                                   
                                                                </div>
                                                            </div>-->

                                         <div class="col-md-6" >
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Offer Type</label>
                                            <div class="col-md-9">
                                                <select name="offer_type" id="offer_type" class="form-control" onchange="showFieldType(this.value)">
                                                    <option value="PERCENTAGE" <?php echo ($results->offer_type == "PERCENTAGE") ? "selected" : ""; ?>>Percentage %</option>
                                                    <option value="LOYALTY_POINT" <?php echo ($results->offer_type == "LOYALTY_POINT") ? "selected" : ""; ?>>Loyalty Point</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="col-md-6" style="<?php echo ($results->offer_type == "LOYALTY_POINT") ? "display:block" : "display:none"; ?>" id="offerPoints">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Loyalty Points</label>
                                            <div class="col-md-9">
                                                <input minlength="1" type="text" class="form-control" name="offer_points" id="offer_points" value="<?php echo $results->offer_points;?>"  placeholder="0" onkeyup="if (/\D/g.test(this.value))
                                                    this.value = this.value.replace(/\D/g, '')"/>
                                            </div>
                                        </div>
                                    </div>
                                
                                 <div class="col-md-6" id="offerPercentage" style="<?php echo ($results->offer_type == "PERCENTAGE") ? "display:block" : "display:none"; ?>">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('discounts_in_percent'); ?></label>
                                        <div class="col-md-9">
                                            <input minlength="1" maxlength="3" type="text" class="form-control" name="discounts_in_percent" id="discounts_in_percent" value="<?php echo $results->discounts_in_percent; ?>" placeholder="00" onkeyup="if (/\D/g.test(this.value))
                                                    this.value = this.value.replace(/\D/g, '')"/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('offer_name_en'); ?></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="offer_name_en" id="offer_name_en" value="<?php echo $results->offer_name_en; ?>"/>
                                        </div>
                                        <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note'); ?></span>

                                    </div>
                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('offer_name_el'); ?></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="offer_name_el" id="offer_name_el" value="<?php echo $results->offer_name_el; ?>" />
                                        </div>
                                        <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note'); ?></span>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12" >

                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('offer_code'); ?></label>
                                        <!--  <?php
                                        $characters = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';

                                        $string = '';

                                        for ($i = 0; $i < 6; $i++) {
                                            $string .= $characters[rand(0, strlen($characters) - 1)];
                                        }
                                        ?> -->
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" readonly="" name="offer_code" id="offer_code" value="<?Php echo $results->offer_code; ?>" />
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
<!--                            <div class="col-md-12" >

                                
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('description_el'); ?></label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="description_el" id="description_el"><?php echo $results->description_el; ?></textarea>
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
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="<?php echo date(DEFAULT_DATE, strtotime($results->from_date)); ?>" readonly=""/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('to_date'); ?></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="<?php echo date(DEFAULT_DATE, strtotime($results->to_date)); ?>" readonly=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                               <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('description_en'); ?></label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="description_en" id="description_en"><?php echo $results->description_en; ?></textarea>
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
                                                            <?php if (!empty($results->image)) { ?>
                                                                <img src="<?php echo base_url() . 'uploads/offer/' . $results->image; ?>">
<?php } else { ?>
                                                                <img src="<?php echo base_url() . 'assets/img/default.jpg'; ?>">
<?php } ?>


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

                                <input type="hidden" name="exists_image" value="<?php echo $results->image; ?>" />
                                <div class="space-22"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $results->id; ?>" />
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
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
        startDate: '-0m',
        //format: 'yyyy-mm-dd',
    });

    $("#from_date").datepicker({
        startDate: '-0m',
        format: 'dd/mm/yyyy',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        // format: 'yyyy-mm-dd',

    });

</script>