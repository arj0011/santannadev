<script>

    jQuery('body').on('click', '#submit', function () {
        var sum = 0;
        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
                form_name = 'editFormAjax';
        $("#" + form_name).validate({
            errorPlacement: function(error, element) {
                element.attr('placeholder',error.text());
            },
            rules: {
                time1:"required",
                time2:"required",
                name: "required",
                email: {required:true,email: true},
                booking_date:"required",
                no_of_persons:"required",
                status:"required",
                floor: "required",
                "tables[]":"required"
            },
            messages: {
                time1: 'Please select Start Time',
                time2: 'Please select End Time',
                name: 'Please enter Full Name',
                email: {
                    required: '<?php echo lang('user_email_validation'); ?>',
                    email: '<?php echo lang('user_email_field_validation'); ?>'
                },
                booking_date: 'Booking date is Required',
                no_of_persons: 'Please select No of Person',
                status: 'Please select Status',
                floor: 'Please select a Floor',
                'tables[]':'Please choose a Table'
            },
            submitHandler: function (form) {
                var favorite = [];
                $("input[type=checkbox]:checked").each(function(){
                    sum += parseInt($(this).data("pers"));
                });
                var seat = parseInt($('#no_of_persons').val());
                if(seat > sum){
                    //$('#seatcheck').html('<p>No of persons are greater than available seats.</p>');
                    $('#seatcheck').html('<p>'+'<?php echo lang('available_seat_check'); ?>'+'</p>');
                    return false;
                }else{
                    $('#seatcheck').html('');
                }
                jQuery(form).ajaxSubmit({
                });
            }    
        });
   });
    
    jQuery('body').on('change', '.input_img2', function () {

    var file_name = jQuery(this).val();
    var fileObj = this.files[0];
    var calculatedSize = fileObj.size / (1024 * 1024);
    var split_extension = file_name.split(".");
    var ext = ["jpg", "gif", "png", "jpeg"];
    if (jQuery.inArray(split_extension[1].toLowerCase(), ext) == - 1)
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
    if (jQuery.inArray(split_extension[1].toLowerCase(), ext) != - 1 && calculatedSize < 10)
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
    if (jQuery.inArray(split_extension[1].toLowerCase(), ext) == - 1)
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
    if (jQuery.inArray(split_extension[1].toLowerCase(), ext) != - 1 && calculatedSize < 10)
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
    
    
    $('#common_datatable_floors').dataTable({
      order: [],
      columnDefs: [ { orderable: false, targets: [0,4] } ]
    });

    $('#common_datatable_booking').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [4, 6, 8, 9]}]
    });


    /*$('#common_datatable_history').dataTable({
      order: [],
      columnDefs: [ { orderable: false, targets: [4,6,8] } ]
    });*/

    $("#start_date,#end_date,#booking_date").datepicker({ 
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        startDate: '-0m'
    });

    $("#my-datepicker").datepicker({
        /*startDate: '-0m',*/
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
    });


    var tableArr = [];
    $(document).on('change','.chk_table_id',function(){
        if($(this).prop("checked") == true){
            var num = parseInt($(this).data('pers'));
            tableArr.push(num); 
            var sum = 0;
            for(var x in tableArr){
                sum = sum + tableArr[x];
            }
            $('#hiddentableId').html(sum + ' seats');
        }else{
            var num = parseInt($(this).data('pers'));
            tableArr = $.grep(tableArr, function(value) {
              return value != num;
            });
            var sum = 0;
            for(var x in tableArr){
                sum = sum + tableArr[x];
            }
            $('#hiddentableId').html(sum +' seats');
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
    });


    $("#user_id").select2({
        allowClear: true
    });

    /*In case of request id existing user is checked*/
    $(document).ready(function(){
        <?php $requestId = $this->uri->segment(3);if($requestId):?>
        $('#new_user').attr('checked',false);
        $('#existing_user').attr('checked',true);
        <?php endif;?>
    });



  /*Following script used for changing tab from booking to booking chart*/
    var date;
    date = "<?php echo (isset($_GET['date']) ? $_GET['date'] : '');?>";
    if(date != ''){
        $('#home').removeClass('in active');
        $('#home_1').parent('li').removeClass('active');
        $('#menu1').addClass('in active');
        $('#menu_1').parent('li').addClass('active');
    }  

    /*Get all tables of floor*/
    jQuery('body').on('change', '#floor', function () {

        var floor_id = $("#floor").val();

        $.ajax({
            url: "<?php echo base_url(); ?>tablebooking/gettablebyfloor",
            type: "post",
            data: {floor_id: floor_id},
            success: function (data) {
                $('.custom_checkbox').css('display','inline-block');
                $('.newbooktblcls').html(data);
            }
        });
    });

    /*Check whether start time isn't greaterthan end time*/
    $('body').on('change','#time1,#time2',function(){
        var time_from = parseInt($('#time1').val());
        var time_to = parseInt($('#time2').val());
        if(time_from >= time_to){
            $('#timecheck').html('<p>Start time is not greater than end time</p>');
            //$('#timecheck').html('<p>'+'<?php echo lang('timecheck'); ?>'+'</p>');
            return false;
        }else{
            $('#timecheck').html('');
        }
    });

    /*show hide new/existing user */
    $('body').on('change','#existing_user',function(){
        if($(this).is(':checked') == true){
            $('#userdropdownid').css('display','block');    
        }
    });

    $('body').on('change','#new_user',function(){
        if($(this).is(':checked') == true){
            $('#userdropdownid').css('display','none');    
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
        minuteStep:30,
        format:'hh:ii:ss'
    });
    $('.form_time').datetimepicker('setHoursDisabled', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 23]);
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
    $('body').on('click','#filter',function(){
        var floor_id = $('#floordd option:selected').val();
         <?php if($this->session->userdata('role') == 'admin'):?> 
        var agent_id = $('#agent option:selected').val();
        <?php else:?>
        var agent_id = '<?php echo $this->session->userdata('id');?>';
        <?php endif;?>
        var date = $('#my-datepicker').val();
        if(floor_id != ''){
            var str = 'tablebooking?date=' + date +'&floor='+floor_id;
            if(agent_id != ''){
                str += '&agent='+agent_id;
            }
            window.location.href = str;    
        }else{
            Ply.dialog("alert", "Please select location");
        }    
    });

    /*Get Floor Plan by Floor*/
    $('body').on('change','#floordrop_down',function(){
        var floor_id = $('#floordrop_down option:selected').val();
        var date = $('#my-datepicker').val();
        window.location.href = 'tablebooking?floorplan='+floor_id;
    });

    /*Get Floor Plan by Floor*/
    var floorplan;
    floorplan = "<?php echo (isset($_GET['floorplan']) ? $_GET['floorplan'] : '');?>";
    if(floorplan != ''){
        $('#home').removeClass('in active');
        $('#home_1').parent('li').removeClass('active');

        $('#menu1').removeClass('in active');
        $('#menu_1').parent('li').removeClass('active');

        $('#menu2').addClass('in active');
        $('#menu_2').parent('li').addClass('active');
    }  

    /*Delete booked table and entry from mw_booking_tables*/
    function delBooking(id) {
        bootbox.confirm("<?php echo lang('delete'); ?>", function (result) {
            if (result) {
                var url = "<?php echo base_url() ?>tablebooking/deletebooking";
                $.ajax({
                    method: "POST",
                    url: url,
                    data: {id: id},
                    success: function (response) {
                        if (response == 1) {
                            Ply.dialog("alert", "<?php echo lang('delete_success'); ?>");
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);

                        }
                    },
                    error: function (error, ror, r) {
                        Ply.dialog("alert", error);
                    },
                });

            }
        });
    }


    $('svg').removeAttr('style');


</script>


