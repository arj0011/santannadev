<!--<link href="<?php echo base_url() . 'assets/css/' ?>jquery-ui.css" rel="stylesheet">-->
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>dataTables.buttons.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.flash.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.flash.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>jszip.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>pdfmake.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>vfs_fonts.js"></script>  
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.html5.min.js"></script>  
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.print.min.js"></script>  
<script src="<?php echo base_url() . 'assets/js/' ?>jquery.autocomplete.js"></script>  
<!--<script src="<?php echo base_url() . 'assets/js/' ?>jquery-ui.js"></script>  -->
<link href="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.dataTables.min.css" rel="stylesheet">
<style>
    .dt-button.buttons-csv.buttons-html5.green {
        background-color: #ed5565 !important;
        border: 0 none;
        color: #fff !important;
        font-size: 14px;
        padding: 8px 20px;
    }
    .dt-button.buttons-excel.buttons-html5.excelButton {
        background-color: #1ab394 !important;
        background-image: none;
        border: 0 none;
        color: #fff !important;
        display: inline-block;
        font-size: 15px;
        padding: 7px 20px;
    }
</style>
<script>
    $.validator.addMethod("regx", function(value, element, regexpr) {          
        if(value == ''){
            return true;
        }else{
            if(value <= 0){
                $('#new_payble').val(0);
            }
            if(typeof value === 'string'){
                $('#new_payble').val('');
            }
            return regexpr.test(value);    
        }

    }, "Please enter a valid positive integer non zero value.");
    
    jQuery('body').on('click', '#submit', function () {
        var sum = 0;
        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
            form_name = 'editFormAjax';
        $("#" + form_name).validate({
            errorPlacement: function (error, element) {
                element.attr('placeholder', error.text());    
            },
            rules: {
                time1: "required",
                time2: "required",
                name: "required",
                booking_date: "required",
                no_of_persons: "required",
                status: "required",
                floor: "required",
                "tables[]": "required",
                payment:{
                    number: true
                }
//                total_billing_amount:{
//                    regx: /^[1-9]\d*([1-9]\d*)*$/,
//                },
//                redeem_point:{
//                    regx: /^[1-9]\d*([1-9]\d*)*$/,
//                }
            },
            messages: {
                time1: 'Please select Start Time',
                time2: 'Please select End Time',
                name: 'Please enter Full Name',
                booking_date: 'Booking date is Required',
                no_of_persons: 'Please select No of Person',
                status: 'Please select Status',
                floor: 'Please select a Floor',
                'tables[]': 'Please choose a Table',
                payment:{
                    number: 'Number only'
                }
            },
            submitHandler: function (form) {
                var favorite = [];
                $("input[type=checkbox]:checked").each(function () {
                    sum += parseInt($(this).data("pers"));
                });
                var seat = parseInt($('#no_of_persons').val());
                jQuery(form).ajaxSubmit({
                });
            }
        });
    });

    jQuery('body').on('click', '#paymentsubmit', function () {
        var form_name = this.form.id;
        $("#"+form_name).validate({
            errorPlacement: function (error, element) {
                element.attr('placeholder', error.text());    
            },
            rules: {
                payment:{
                    required: true,
                    number: true
                },
                total_billing_amount:{
                    required: true,  
                    number: true  
                },
                redeem_point:{number:true}
            },
            messages: {
            },
            // submitHandler: function (form) {
            //     jQuery(form).ajaxSubmit({

            //     });
            // }
            submitHandler: function(form) {
                if ($("#"+form_name).valid()) {
                    $('.error').html('');
                    $.post(base_url+"tablebooking/payment",$("#"+form_name).serialize(),function(resp){
                        var status = resp.status; 
                        if(status == 0){
                            var data = resp.data;
                            $.each( data.errors, function( key, value ) {
                                $('[name="'+ key +'"]', form).after('<div class="error">'+value+'</div>');
                            });
                        }else{
                            if (status == 1) {
                                Ply.dialog("alert", resp.message);
                                window.setTimeout(function () {
                                    window.location.href = resp.url;
                                }, 2000);
                                $(".loaders").fadeOut("slow");
                            } else {
                                Ply.dialog("alert", resp.message);
                                $(".loaders").fadeOut("slow");
                            }
                        }
                    });
                }
            }
        });
    });






    jQuery('body').on('change', '.input_img2', function () {

        var file_name = jQuery(this).val();
        var fileObj = this.files[0];
        var calculatedSize = fileObj.size / (1024 * 1024);
        var split_extension = file_name.split(".");
        var ext = ["jpg", "gif", "png", "jpeg"];
        if (jQuery.inArray(split_extension[1].toLowerCase(), ext) == -1)
        {
            $(this).val(fileObj.value = null);
            //showToaster('error',"You Can Upload Only .jpg, gif, png, jpeg  files !");
            $('.ceo_file_error').html('<?php echo lang('image_upload_error'); ?>');
            return false;
        }
        if (calculatedSize > 1)
        {
            $(this).val(fileObj.value = null);
            //showToaster('error',"File size should be less than 1 MB !");
            $('.ceo_file_error').html(' 1MB');
            return false;
        }
        if (jQuery.inArray(split_extension[1].toLowerCase(), ext) != -1 && calculatedSize < 10)
        {
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });
    jQuery('body').on('change', '.input_img3', function () {

        var file_name = jQuery(this).val();
        var fileObj = this.files[0];
        var calculatedSize = fileObj.size / (1024 * 1024);
        var split_extension = file_name.split(".");
        var ext = ["jpg", "gif", "png", "jpeg"];
        if (jQuery.inArray(split_extension[1].toLowerCase(), ext) == -1)
        {
            $(this).val(fileObj.value = null);
            //showToaster('error',"You Can Upload Only .jpg, gif, png, jpeg  files !");
            $('.ceo_file_error').html('<?php echo lang('image_upload_error'); ?>');
            return false;
        }
        if (calculatedSize > 1)
        {
            $(this).val(fileObj.value = null);
            //showToaster('error',"File size should be less than 1 MB !");
            $('.ceo_file_error').html(' 1MB');
            return false;
        }
        if (jQuery.inArray(split_extension[1].toLowerCase(), ext) != -1 && calculatedSize < 10)
        {
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });
    function readURL(input) {
        var cur = input;
        if (cur.files && cur.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(cur).hide();
                $(cur).next('span:first').hide();
                $(cur).next().next('img').attr('src', e.target.result);
                $(cur).next().next('img').css("display", "block");
                $(cur).next().next().next('span').attr('style', "");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    jQuery('body').on('click', '.remove_img', function () {
        var img = jQuery(this).prev()[0];
        var span = jQuery(this).prev().prev()[0];
        var input = jQuery(this).prev().prev().prev()[0];
        jQuery(img).attr('src', '').css("display", "none");
        jQuery(span).css("display", "block");
        jQuery(input).css("display", "inline-block");
        jQuery(this).css("display", "none");
        jQuery(".image_hide").css("display", "block");
        jQuery("#user_image").val("");
    });


    // var open_other_modal = function (controller,method,id) {
    //     $.ajax({
    //         url: '<?php echo base_url(); ?>' + controller + "/"+method,
    //         type: 'POST',
    //         data:{id:id},
    //         success: function (data, textStatus, jqXHR) {

    //             $('#form-modal-box').html(data);
    //             $("#paymentModal").modal('show');
    //         }
    //     });
    // }



    $('#common_datatable_floors').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [0, 4]}]
    });

    $('#common_datatable_booking').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [0, 10]}],
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel'
        ]
    });

    var oTable = $('#common_datatable_booking_inner').DataTable({
        "processing": true,
        "bServerSide": true,
//            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
//            buttons: [
//                'csv', 'excel'
//            ],
        "searching": false,
        "bLengthChange": false,
        "bProcessing": true,
        "iDisplayLength": 20,
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        columnDefs: [{orderable: false, targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]}],
        "columns": [
            {"data": "name", "searchable": false, "order": true},
            {"data": "action", "searchable": false, "order": false},
            {"data": "status", "searchable": false, "order": false},
            {"data": "confirmation", "searchable": false, "order": false},
            {"data": "floor", "searchable": false, "order": false},
            {"data": "no_of_persons", "searchable": false, "order": false},
            {"data": "booking_date", "searchable": false, "order": false},
            {"data": "time_from", "searchable": false, "order": false},
            {"data": "time_to", "searchable": false, "order": false},
            {"data": "comment", "searchable": false, "order": false},
            {"data": "referrer", "searchable": false, "order": false},
            {"data": "email", "searchable": false, "order": false},
            {"data": "mobile", "order": false, orderable: false},
        ],
        "ajax": {
            "url": "<?php echo site_url('tablebooking/booking_ajax_table'); ?>",
            "type": "POST",
            "data": function (d) {
                d.searchstr = $("#search").val();
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
                d.floor = $("#floor").val();
            }
        }
    });

    $('#submit').on('click', function () {
        oTable.draw();
    });

    $('#search').on('keyup', function () {
        oTable.draw();
    });

    /*$('#common_datatable_history').dataTable({
     order: [],
     columnDefs: [ { orderable: false, targets: [4,6,8] } ]
     });*/

    $("#booking_date").datepicker({
        todayBtn: "linked",
        format: 'dd/mm/yyyy',
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        startDate: '-0m'
    });
    var sdate = '';
    var edate = '';
<?php if ($this->session->userdata('role_id') == 2) { ?>
        sdate = '-0d';
        edate = '+1d';
<?php } ?>
    $("#start_date,#end_date").datepicker({
        todayBtn: "linked",
        format: 'dd/mm/yyyy',
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        startDate: sdate,
        endDate: edate
    });

    $("#my-datepicker").datepicker({
        /*startDate: '-0m',*/
        todayBtn: "linked",
        format: 'dd/mm/yyyy',
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
    });


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
                //alert(data);
                $('input[name="name"]').val(data);

                // $('#full_name').html(data);

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

        /*Get Total point of customer*/
        $.ajax({
            url: "<?php echo base_url(); ?>users/get_user_totalpoint",
            type: "post",
            data: {user_id: user_id},
            success: function (resp) {
                if(resp > 0){
                    $('.billingdivcls').css('display','block');
                    $('#total_point').attr('value',resp);
                    $('#total_user_point').text(resp);
                    $('#total_billing_amount').prop('disabled',false); 
                    $('#redeem_point').prop('disabled',false); 
                    $('#redeembtn').prop('disabled',false); 
                    // $('#new_payble').prop('disabled',false); 
                    // $('#new_payble').prop('readonly',true); 
                    // $('#new_payble').css('cursor','not-allowed'); 
                }
            }
        });

    });


    $("#user_id").select2({
        allowClear: true
    });


    /*Redeem point*/
     jQuery('body').on('click', '#redeembtn', function () {
        var tba = Number($('#total_billing_amount').val());
        var tpoint = Number($('#total_user_point').text());
        var rpoint = Number($('#redeem_point').val());
        if((tba != '' && tba != undefined && tba != 0) && (tpoint != '' && tpoint != undefined && tpoint != 0) && (rpoint != '' && rpoint != undefined && rpoint > 0)){
            if(tpoint >= rpoint){

                $.ajax({
                    url: "<?php echo base_url(); ?>tablebooking/get_money_by_point",
                    type: "post",
                    data: {redeem_point: rpoint},
                    success: function (resp) {
                        if(resp > 0){
                           if(tba >= resp){
                                var npybl = Number(tba - resp);      
                                $('#payment').attr('value',npybl); 
                                // $('#payment').prop('readonly',true); 
                                // $('#new_payble').css('cursor','not-allowed'); 
                           }else{
                                Ply.dialog("alert", '<?php echo lang('pymnt_grtr_billng_amt_val');?>');
                           }
                            
                        }else{
                            Ply.dialog("alert", '<?php echo lang('invalid_values');?>');
                        }
                    }
                });    
            }else{
                Ply.dialog("alert", '<?php echo lang('rpoint_grtr_tpoint');?>');
            }
        }else{
            Ply.dialog("alert", '<?php echo lang('invalid_values');?>');
        }
     });


    /*In case of request id existing user is checked*/
    $(document).ready(function () {
<?php
$requestId = $this->uri->segment(3);
if ($requestId):
    ?>
        $('#new_user').attr('checked', false);
        $('#existing_user').attr('checked', true);
<?php endif; ?>
    });
    
    var requestBookingId = $("#requestBookingId").attr('value');
    if(requestBookingId == ""){
      $("#payment").prop('readonly',false);  
    }
    $("#existing_user").on('change',function(){
        $("#payment").prop('readonly',true);
    })
        
  


    /*Following script used for changing tab from booking to booking chart*/
    var date;
    date = "<?php echo (isset($_GET['date']) ? $_GET['date'] : ''); ?>";
    if (date != '') {
        $('#home').removeClass('in active');
        $('#home_1').parent('li').removeClass('active');
        $('#menu1').addClass('in active');
        $('#menu_1').parent('li').addClass('active');
    }

    /*Get all tables of floor*/
    jQuery('body').on('change', '#floor', function () {

        var floor_id = $("#floor").val();
        var booking_date = $("#booking_date").val();
        var requestBookingId = $("#requestBookingId").val();
        $.ajax({
            url: "<?php echo base_url(); ?>tablebooking/gettablebyfloor",
            type: "post",
            data: {floor_id: floor_id, booking_date: booking_date, requestBookingId: requestBookingId},
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

    $('#booking_date').datepicker().on('changeDate', function (ev) {
        var floor_id = $("#floor").val();
        var booking_date = $("#booking_date").val();
        var requestBookingId = $("#requestBookingId").val();
        if(floor_id != ""){
            $.ajax({
                url: "<?php echo base_url(); ?>tablebooking/gettablebyfloor",
                type: "post",
                data: {floor_id: floor_id, booking_date: booking_date,requestBookingId: requestBookingId},
                beforeSend: function () {
                    $(".loaders").fadeIn("slow");
                },
                success: function (data) {
                    $('.custom_checkbox').css('display', 'inline-block');
                    $('.newbooktblcls').html(data);
                    $(".loaders").fadeOut("slow");
                }
            });
        }
    });


    /*Check whether start time isn't greaterthan end time*/
    $('body').on('change', '#time1,#time2', function () {
        var time_from = parseInt($('#time1').val());
        var time_to = parseInt($('#time2').val());
        if (time_from >= time_to) {
            $('#timecheck').html('<p>Start time is not greater than end time</p>');
            //$('#timecheck').html('<p>'+'<?php echo lang('timecheck'); ?>'+'</p>');
            return false;
        } else {
            $('#timecheck').html('');
        }
    });

    /*show hide new/existing user */
    $('body').on('change', '#existing_user', function () {
        if ($(this).is(':checked') == true) {
            $('#userdropdownid').css('display', 'block');
            $('.billingdivcls').css('display', 'block');
            var btn = '';
            btn += '<div class="col-md-8 qrbtndivcls">'; 
            btn += '<button type="button" class="btn btn-primary">Scan QR</button>'; 
            btn += '</div>'; 
            $('#userdropdownid').after(btn);
        }
    });

    $('body').on('change', '#new_user', function () {
        if ($(this).is(':checked') == true) {
            $('#userdropdownid').css('display', 'none');
            $('.billingdivcls').css('display', 'none');
            $('.qrbtndivcls').remove(); 
            $("#payment").prop('readonly',false);
        }
    });


    /*Timepicker*/
    $('.form_time').datetimepicker({
        language: 'en',
        weekStart: 0,
        todayBtn: 0,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0,
        minuteStep: 30,
        format: 'hh:ii:ss'
    });
    $('.form_time').datetimepicker('setHoursDisabled', [0]);
    /*end*/

    /*Get Chart Data by Floor*/
    /*$('body').on('change','#floordd',function(){
     var floor_id = $('#floordd option:selected').val();
     var agent_id = $('#agent option:selected').val();
     var date = $('#my-datepicker').val();
     if(floor_id != ''){
     var str = 'tablebooking?date=' + date +'&floor='+floor_id;
     if(agent_id != ''){
     str += '&agent='+agent_id;
     }
     window.location.href = str;    
     }
     
     });*/
    $('body').on('click', '#filter', function () {
        var floor_id = $('input[name="floordd"]:checked').val();
        var from_time_search = $('#time1_search').val();
        var to_time_search = $('#time2_search').val();
<?php if ($this->session->userdata('role') == 'admin'): ?>
            var agent_id = $('#agent option:selected').val();
<?php else: ?>
            var agent_id = '<?php echo $this->session->userdata('id'); ?>';
<?php endif; ?>
        var date = $('#my-datepicker').val();
        if (floor_id != '') {
            var str = 'tablebooking?date=' + date + '&floor=' + floor_id;
            if (agent_id != '') {
                str += '&agent=' + agent_id;
            }
            if (from_time_search != '' && to_time_search != '') {
                str += '&from=' + from_time_search + '&to=' + to_time_search;
            }
            window.location.href = str;
        } else {
            Ply.dialog("alert", "Please select location");
        }
    });

    /*Get Floor Plan by Floor*/
    $('body').on('change', '.floordrop_down', function () {
        var floor_id = document.querySelector('input[name="floordrop_down"]:checked').value;
        var date = $('#my-datepicker').val();
        window.location.href = 'tablebooking?floorplan=' + floor_id;
    });

    /*Get Floor Plan by Floor*/
    var floorplan;
    floorplan = "<?php echo (isset($_GET['floorplan']) ? $_GET['floorplan'] : ''); ?>";
    if (floorplan != '') {
        $('#home').removeClass('in active');
        $('#home_1').parent('li').removeClass('active');

        $('#menu1').removeClass('in active');
        $('#menu_1').parent('li').removeClass('active');

        $('#menu2').addClass('in active');
        $('#menu_2').parent('li').addClass('active');
    }

    /*Delete booked table and entry from mw_booking_tables*/





    $('svg').removeAttr('style');

    $("#referrer").autocomplete({
        source: "<?php echo base_url() . 'tablebooking/getReffer' ?>",
        minLength: 1,
        select: function (event, ui) {

        }
    });


    function getRefferss(str) {

        $.ajax({
            method: "POST",
            url: "<?php echo base_url() . 'tablebooking/getReffer' ?>",
            data: {str: str},
            beforeSend: function () {
                $("#referrer-search").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
            },
            success: function (response) {
                $("#suggesstion-box").html(response);
                $("#search-box").css("background", "#FFF");
            },
            error: function (error, ror, r) {
                //Ply.dialog("alert", error);
            },
        });
    }

    function bookingStatusAuth(bookingId, el) {
        var _flag = 0;
        var bookingStatus = el.value;
        var bookingMsg = el.options[el.selectedIndex].text;

        if (bookingStatus == 1) {
            Ply.dialog("confirm", "Do you want to edit Booking?")
                    .done(function (ui) {
                        // OK
                    })
                    .fail(function (ui) {
                        // Cancel
                    })
                    .always(function (ui) {
                        if (ui.state) {
                            window.location.href = '<?php echo base_url(); ?>' + "tablebooking/index/" + bookingId;
                        }
                        else {
                            $.ajax({
                                method: "POST",
                                url: "<?php echo base_url() . 'tablebooking/bookingStatus' ?>",
                                data: {status: bookingStatus, bookingId: bookingId},
                                dataType: "json",
                                beforeSend: function () {
                                    $(".loaders").fadeIn("slow");
                                },
                                success: function (response) {
                                    if (response.status == 1) {
                                        Ply.dialog("alert", response.message);
                                        window.setTimeout(function () {
                                            window.location.href = response.redirect;
                                        }, 2000);
                                        $(".loaders").fadeOut("slow");
                                    } else {
                                        Ply.dialog("alert", response.message);
                                        $(".loaders").fadeOut("slow");
                                    }
                                },
                                error: function (error, ror, r) {
                                    //Ply.dialog("alert", error);
                                },
                            });
                        }
                    });
        } else {
            Ply.dialog("confirm", " Are you sure want to " + bookingMsg + " booking?")
                    .done(function (ui) {
                        // OK
                    })
                    .fail(function (ui) {
                        // Cancel
                    })
                    .always(function (ui) {
                        if (ui.state) {
                            $.ajax({
                                method: "POST",
                                url: "<?php echo base_url() . 'tablebooking/bookingStatus' ?>",
                                data: {status: bookingStatus, bookingId: bookingId},
                                dataType: "json",
                                beforeSend: function () {
                                    $(".loaders").fadeIn("slow");
                                },
                                success: function (response) {
                                    if (response.status == 1) {
                                        Ply.dialog("alert", response.message);
                                        window.setTimeout(function () {
                                            window.location.href = response.redirect;
                                        }, 2000);
                                        $(".loaders").fadeOut("slow");
                                    } else {
                                        Ply.dialog("alert", response.message);
                                        $(".loaders").fadeOut("slow");
                                    }
                                },
                                error: function (error, ror, r) {
                                    //Ply.dialog("alert", error);
                                },
                            });
                        }
                        else {

                        }
                    });
        }


    }

</script>


