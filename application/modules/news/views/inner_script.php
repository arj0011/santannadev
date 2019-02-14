<script>

    jQuery('body').on('click', '#submit', function () {

        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
            form_name = 'editFormAjax';
        $("#" + form_name).validate({
            rules: {
                news_category: "required",
                news_title_el: "required",
                news_title_en: "required",
                group_name: "required",
            },
            messages: {
                news_category: '<?php echo lang('news_category_validation'); ?>',
                news_title_el: '<?php echo lang('news_title_el_validation'); ?>',
                news_title_en: '<?php echo lang('news_title_en_validation'); ?>',
                group_name: '<?php echo lang('select_group_validation'); ?>',
            },
            submitHandler: function (form) {
                jQuery(form).ajaxSubmit({
                });
            }
        });

    });
    var show_news_image = function (controller) {
        $.ajax({
            url: '<?php echo base_url(); ?>news/open_model_news',
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModalImage").modal('show');


            }
        });
    }

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
        jQuery("#service_image").val("");
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

    $('#common_datatable_news').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [5, 7]}]
    });


    $(document).on('change', '#group_name', function () {

        var _this = $(this).val();


        $.ajax({
            type: "POST",
            url: '<?php echo base_url('news/getUser') ?>/' + _this,
            dataType: 'html'
        }).done(function (user_name) {

            $('#user_name').html(user_name);
            $('#user_name option').prop('selected', true);
            $('#user_name option[value=""]').prop('selected', false);
            $('#user_name').select2();

        });

    });



    $(document).on('click', '.all_user', function () {

        if ($(this).is(':checked'))
        {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url('news/all_users') ?>',
                dataType: 'html'
            }).done(function (user) {
                // alert(user_name_list);

                $('#user').html(user);
                $('#user option').prop('selected', true);
                $('#user option[value=""]').prop('selected', false);
                $('#user').select2();


            });
        } else {
            $('#user option').prop('selected', false);
            $('#user').select2();
        }

    });

    $(document).on('click', '.all_user', function () {

        if ($(this).is(":checked")) {
            $("#dvUser").hide();
        } else {
            $("#dvUser").hide();
        }
    });
    
       $(document).on('change', '.all_user', function () {
           $("#dvGroupSelect").hide();
           $("#dvUserSelect").hide();
           if($(this).val() == 3){
              $("#dvUserSelect").show();
              $("#dvGroupSelect").show();
           }
    });

    $(document).on('click', '.all_user', function () {

        if ($(this).is(":checked")) {
            $("#dvUserSelect").hide();
        } else {
            $("#dvUserSelect").show();
        }
    });
    $(document).on('click', '.all_user', function () {

        if ($(this).is(":checked")) {
            $("#dvGroupSelect").hide();
        } else {
            $("#dvGroupSelect").show();
        }
    });

</script>

