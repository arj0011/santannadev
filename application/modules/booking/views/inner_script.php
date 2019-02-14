
<script>


    jQuery('body').on('click', '#submit', function () {

        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
            form_name = 'editFormAjax';
        $("#" + form_name).validate({
            rules: {
                full_name: "required",
                email: {
                    required: true,
                    email: true
                },
                phone_number: {
                    required: true,
                    minlength: 10,
                    maxlength: 20,
                    number: true


                },
                place: "required",
                booking_details: "required",
                no_of_persons: "required",
                reservation_date: "required",
            },
            messages: {
                full_name: '<?php echo lang('full_name_validation'); ?>',
                email: {
                    required: '<?php echo lang('email_validation'); ?>',
                    email: '<?php echo lang('valid_email_validation'); ?>'
                },
                phone_number: {
                    required: '<?php echo lang('phone_number_validation'); ?>',
                },
                place: '<?php echo lang('place_validation'); ?>',
                booking_details: '<?php echo lang('booking_details_validation'); ?>',
                no_of_persons: '<?php echo lang('no_of_persons_validation'); ?>',
                reservation_date: '<?php echo lang('reservation_date_validation'); ?>',
            },
            submitHandler: function (form) {
                jQuery(form).ajaxSubmit({
                });
            }
        });

    });



    function show_message(msg) {
        var Base64 = {_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
                var t = "";
                var n, r, i, s, o, u, a;
                var f = 0;
                e = Base64._utf8_encode(e);
                while (f < e.length) {
                    n = e.charCodeAt(f++);
                    r = e.charCodeAt(f++);
                    i = e.charCodeAt(f++);
                    s = n >> 2;
                    o = (n & 3) << 4 | r >> 4;
                    u = (r & 15) << 2 | i >> 6;
                    a = i & 63;
                    if (isNaN(r)) {
                        u = a = 64
                    } else if (isNaN(i)) {
                        a = 64
                    }
                    t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
                }
                return t
            }, decode: function (e) {
                var t = "";
                var n, r, i;
                var s, o, u, a;
                var f = 0;
                e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                while (f < e.length) {
                    s = this._keyStr.indexOf(e.charAt(f++));
                    o = this._keyStr.indexOf(e.charAt(f++));
                    u = this._keyStr.indexOf(e.charAt(f++));
                    a = this._keyStr.indexOf(e.charAt(f++));
                    n = s << 2 | o >> 4;
                    r = (o & 15) << 4 | u >> 2;
                    i = (u & 3) << 6 | a;
                    t = t + String.fromCharCode(n);
                    if (u != 64) {
                        t = t + String.fromCharCode(r)
                    }
                    if (a != 64) {
                        t = t + String.fromCharCode(i)
                    }
                }
                t = Base64._utf8_decode(t);
                return t
            }, _utf8_encode: function (e) {
                e = e.replace(/\r\n/g, "\n");
                var t = "";
                for (var n = 0; n < e.length; n++) {
                    var r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r)
                    } else if (r > 127 && r < 2048) {
                        t += String.fromCharCode(r >> 6 | 192);
                        t += String.fromCharCode(r & 63 | 128)
                    } else {
                        t += String.fromCharCode(r >> 12 | 224);
                        t += String.fromCharCode(r >> 6 & 63 | 128);
                        t += String.fromCharCode(r & 63 | 128)
                    }
                }
                return t
            }, _utf8_decode: function (e) {
                var t = "";
                var n = 0;
                var r = c1 = c2 = 0;
                while (n < e.length) {
                    r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r);
                        n++
                    } else if (r > 191 && r < 224) {
                        c2 = e.charCodeAt(n + 1);
                        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                        n += 2
                    } else {
                        c2 = e.charCodeAt(n + 1);
                        c3 = e.charCodeAt(n + 2);
                        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                        n += 3
                    }
                }
                return t
            }}

        msg = Base64.decode(msg);
        $('#message_container').text(msg);
        $('#message_div').show();
    }
    function close_message() {
        $('#message_container').text('');
        $('#message_div').hide();
    }



    jQuery('body').on('change', '#status', function () {

        var value = jQuery(this).val();
        var id = jQuery(this).attr("update_id");
        var message = "";
        if (value == 1) {
            message = '<?php echo lang('confirm_message'); ?>';
        } else if (value == 2) {
            message = '<?php echo lang('pending_message'); ?>';
        } else if (value == 3) {
            message = '<?php echo lang('cancel_message'); ?>';
        }
        bootbox.confirm(message, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>booking/changestatus",
                    data: "id=" + id + "&value=" + value,
                    success: function (response)
                    {

                        if (response == 1) {
                            Ply.dialog("alert", "<?php echo lang('change_status_success'); ?>");
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
                        }
                    },
                    error: function (error)
                    {

                    }
                });
            } else
                window.location.reload();
        });

    });


    jQuery('body').on('click', '#submit', function () {

        var base_url = '<?php echo base_url() ?>';

        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        //var user_id = $("#user_id").val();
        $.post(base_url + "booking", {start_date: start_date, end_date: end_date}, function (url_resp) {

            if (url_resp != 1) {
                $("#postList").html(url_resp);
            }
        })


    });

    $("#start_date").datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        startDate: '-0m'
    });

    $("#end_date").datepicker({
        startDate: '-0m',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
    });




    $('#common_datatable_booking').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [4, 6, 8, 9]}]
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
                $('input[name="full_name"]').val(data);

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
                $('input[name="phone_number"]').val(data);

            }
        });
    });
    
</script>

