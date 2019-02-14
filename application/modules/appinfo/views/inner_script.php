<link href="<?php echo base_url(); ?>assets/plugins/fencybox/jquery.fancybox.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/plugins/fencybox/jquery.fancybox.min.js"></script>
<script>
        
       jQuery('body').on('click', '#submit', function () {
        
        var form_name= this.form.id;
        if(form_name=='[object HTMLInputElement]')
            form_name='editFormAjax';
        $("#"+form_name).validate({
            rules: {
                ceo_message_en: "required",
                ceo_message_el: "required",
                phone_number:{
                              required:true,  
                              minlength:10,
                              maxlength:12,
                        },
                email: {
                    required: true,
                    email: true
                },
                legal_text_en: "required",
                legal_text_el: "required",
                version: "required",
                copyright: "required",
                contact_title_en: "required",
                contact_title_el: "required",
                
            },
            messages: {
                ceo_message_en: '<?php echo lang('ceo_message_en_validation');?>',
                ceo_message_el: '<?php echo lang('ceo_message_el_validation');?>',
                phone_number: {
                            required:"<?php echo lang('phone_number_required_validation');?>",
                            minlength:"<?php echo lang('phone_number_min_validation');?>",
                            maxlength:"<?php echo lang('phone_number_max_validation');?>",
                   },
                email: {
                    required: '<?php echo lang('app_email_required_validation');?>',
                    email: '<?php echo lang('app_email_validation');?>'
                },
                
            },
            submitHandler: function (form) {
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

    });

    $(document).on('change', "#files", function (e) {
        var files = e.target.files,
                filesLength = files.length;
        for (var i = 0; i < filesLength; i++) {
            var f = files[i]
            var fileReader = new FileReader();
            fileReader.onload = (function (e) {
                var file = e.target;
                $("<span class=\"pip\">" +
                        "<img width='100' class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                        "<br/><span class=\"remove\">Remove image</span>" +
                        "</span>").insertAfter("#files");
                $(".remove").click(function () {
                    $(this).parent(".pip").remove();
                });

            });
            fileReader.readAsDataURL(f);
        }
    });

    function removeImg(i) {
        $(".rm" + i).remove();
    }

    var open_modal_appinfo = function (controller) {
        $.ajax({
            url: '<?php echo base_url(); ?>' + controller + "/open_model_appinfo",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModalInfo").modal('show');


            }
        });
    }

    var add_more_file = function () {
        var total_files = $('.info_file_div').length;
        var new_file_div = 'file' + total_files;
        var new_file_id = 'info_file' + total_files;
        var new_file_title_id = 'info_file_title' + total_files;
        var info_word = "<?php echo lang('file'); ?>";
        var str = $('#file0').html();
        var str = '<div id="' + new_file_div + '" class="info_file_div">' + str + '</div>';
        str = str.replace('info_file0', new_file_id);
        str = str.replace('info_file_title0', new_file_title_id);
        //str=str.replace('old_info_file0','old_info_file'+total_files);
        str = str.replace('remove_file(0)', 'remove_file(' + parseInt(total_files) + ')');
        $('#files_div').append(str);
        $('#' + new_file_div + ' .remove_file').show();
    }
    var remove_file = function(sno) {
        if (sno > 0) {
            $('#file' + sno).remove();
        } else {
            //alert('You cannot remove first file');
        }
    }
    var remove_old_file = function (id) {
        $('#old_file' + id).remove();
    }

    function show_message(msg){
     var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

   msg=Base64.decode(msg);
   $('#message_container').text(msg);
   $('#message_div').show();
 }
  function close_message(){
     $('#message_container').text('');
    $('#message_div').hide();
 }

 $('#common_datatable_app').dataTable({
      order: [],
      columnDefs: [ { orderable: false, targets: [2,3,5,6,9] } ]
    });

 $('#common_datatable_info').dataTable({
      order: [],
      columnDefs: [ { orderable: false, targets: [5,6,8] } ]
    });
</script>

