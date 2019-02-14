<style>
    .danger {
        color:red;
    }
</style>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">

                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo lang('current_booking'); ?></div>
                    <div class="panel-body">

                        <?php $message = $this->session->flashdata('error');
                        if (!empty($message)):
                            ?><div class="alert alert-danger">
    <?php echo $message; ?></div><?php endif; ?>
                        <div class="loaders">
                            <img src="<?php echo base_url() . 'backend_asset/images/Preloader_3.gif'; ?>" class="loaders-img" class="img-responsive">
                        </div>
                        <div class="alert alert-danger" id="error-box" style="display: none">
                        </div>
                        <form role="form" action="<?php echo base_url('admin/current_booking'); ?>" name="current_booking" method="post"  enctype="multipart/form-data">
                            <div class="rows"> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php echo lang('email'); ?>:</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="email" id="email" placeholder="<?php echo lang('email'); ?>" <?php echo set_value('email'); ?> />
<?php echo form_error('email'); ?>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group clearfix">
                                        <label class="col-md-4 control-label"><?php echo lang('app_phone'); ?>:</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="mobile" id="mobiles" placeholder="<?php echo lang('app_phone'); ?>" <?php echo set_value('mobile'); ?>/>
                                        </div>

                                    </div></div>
                                <div class="clearfix"></div>
                                <div class="col-md-6 clearfix">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php echo lang('select_no_of_person'); ?>:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="no_of_persons" id="no_of_persons">
                                                <option value=""><?php echo lang('select_no_of_person'); ?></option>
                                                <?php
                                                for ($i = 1; $i <= 50; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option> 
                                                    <?php
                                                }
                                                ?> 
                                                <option value="50+" >50+</option>
                                            </select>
<?php echo form_error('no_of_persons'); ?>

                                            <div id="seatcheck"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php echo lang('select_agent'); ?>:</label>
                                        <div class="col-md-8">

                                            <select class="form-control" name="agent_id" id="agent_id">
                                                <option value=""><?php echo lang('select_agent'); ?></option>
                                                <?php if (!empty($agents)) {
                                                    foreach ($agents as $result) { ?>
                                                        <?php if ($this->session->userdata('role') == 'agent') {
                                                            if ($result->id == $this->session->userdata('id')) {
                                                                ?>
                                                                <option value="<?php echo $result->id; ?>"><?php echo $result->full_name; ?></option>
            <?php }
        } else { ?>
                                                            <option value="<?php echo $result->id; ?>"><?php echo $result->full_name; ?></option>
        <?php }
    }
} ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-md-12 col-sm-12">

                                    <label class="col-md-2 col-sm-2 control-label">Sections:</label>      

                                    <?php
                                        if (!empty($allfloors)):
                                            foreach ($allfloors as $room):
                                                ?>
                                            <div class="col-md-2 col-sm-2">
                                                <div class="radio">    
                                                    <button type="button" id="floor-btn<?php echo $room['id']; ?>" data-floor="<?php echo $room['id']; ?>" class="floordrop_down btn-xs btn btn-default btn-sm" style='<?php //echo ($floor_id_us == $rows->id) ? "background-color: #EC4758;border:1px solid #EC4758;" : ""; ?>' name="floordrop_down"><?php echo $room['name']; ?></button>
                                                </div>
                                            </div>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?> 
                                    <input type="hidden" name="floor" id="floor" />
                                    <?php echo form_error('floor'); ?>
                                </div>
                                <div class="col-md-12">
                                    <!--<label class="lblbooktblpage">Tables</label>-->
                                    <div class="newbooktblcls ">

                                             <!-- <p id="hiddentableId">0 seats</p> -->
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <hr>

                                <div class="form-group clearfix">
                                    <div class="col-sm-12 text-right">
                                        <button type="reset" class="btn btn-danger"><?php echo lang('reset_btn'); ?></button>

                                        <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('submit_btn'); ?></button>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
    var tableArr = [];

    $(document).on('change', '.chk_table_id', function () {

        if ($(this).prop("checked") == true) {
            var num = parseInt($(this).data('pers'));
            tableArr.push(num);
            var sum = 0;
            for (var x in tableArr) {
                sum = sum + tableArr[x];
            }
            $('#hiddentableId').html(sum + ' seats');
        } else {
            var num = parseInt($(this).data('pers'));
            tableArr = $.grep(tableArr, function (value) {
                return value != num;
            });
            var sum = 0;
            for (var x in tableArr) {
                sum = sum + tableArr[x];
            }
            $('#hiddentableId').html(sum + ' seats');
        }
    });

    jQuery('body').on('change', '#floor', function () {
        var floor_id = $("#floor").val();

        $.ajax({
            url: "<?php echo base_url(); ?>tablebooking/gettablebyfloor",
            type: "post",
            data: {floor_id: floor_id},
            success: function (data) {

                $('.custom_checkbox').css('display', 'inline-block');
                $('.newbooktblcls').html(data);
            }
        });
    });

    $('body').on('change', '#existing_user', function () {
        if ($(this).is(':checked') == true) {
            $('#userdropdownid').css('display', 'block');
        }
    });

    $('body').on('change', '#new_user', function () {
        if ($(this).is(':checked') == true) {
            $('#userdropdownid').css('display', 'none');
        }
    });

    jQuery('body').on('change', '#user_id', function () {

        var user_id = $("#user_id").val();

        $.ajax({
            url: "<?php echo base_url(); ?>booking/user_email",
            type: "post",
            data: {user_id: user_id},
            success: function (data) {

                $('input[name="email"]').val(data);

            }
        });
    });

    jQuery('body').on('change', '#user_id', function () {


        var user_id = $("#user_id").val();


        $.ajax({
            url: "<?php echo base_url(); ?>booking/user_name",
            type: "post",
            data: {user_id: user_id},
            success: function (data) {

                $('input[name="name"]').val(data);

            }
        });
    });

    jQuery('body').on('change', '#user_id', function () {


        var user_id = $("#user_id").val();

        $.ajax({
            url: "<?php echo base_url(); ?>booking/user_phone_number",
            type: "post",
            data: {user_id: user_id},
            success: function (data) {
                $('input[name="mobile"]').val(data);

            }
        });
    });

    jQuery('body').on('click', '#submit', function () {
        var sum = 0;
        $("input[type=checkbox]:checked").each(function () {

            sum += parseInt($(this).data("pers"));

        });
        var seat = parseInt($('#no_of_persons').val());

        if (seat > sum) {
            $('#seatcheck').html('<p>' + '<?php echo lang('available_seat_check'); ?>' + '</p>');
            return false;
        } else {
            $('#seatcheck').html('');
        }

    });

    $('body').on('click', '.floordrop_down', function () {
        var floorId = $(this).data('floor');
        $("#floor").val(floorId);
        $('.floordrop_down').css({"background-color": "#c2c2c2", "border": "1px solid #c2c2c2"});
        $(this).css({"background-color": "#EC4758", "border": "1px solid #EC4758"});

        $.ajax({
            url: "<?php echo base_url(); ?>tablebooking/gettablebyfloor",
            type: "post",
            data: {floor_id: floorId},
            beforeSend: function () {
                $(".loaders").fadeIn("slow");
            },
            success: function (data) {

                $('.custom_checkbox').css('display', 'inline-block');
                $('.newbooktblcls').html(data);
                $(".loaders").fadeOut("slow");
            }
        });

    });

</script>