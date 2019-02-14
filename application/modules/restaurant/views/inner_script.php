<script>

function time_check()
{
var firstTime  =jQuery('#opening').val();
var secondTime =jQuery('#closing').val();

if(firstTime != "" && secondTime != ""){
    if(firstTime > secondTime || firstTime == secondTime)
    {
       Ply.dialog("alert", 'Closing time always greater then Opening time');
       $('#submit').attr('disabled',true);

    }else{
       
       $('#submit').attr('disabled',false);
    }
 }
}
        



        
    jQuery('body').on('click', '#submit', function () {
        
        var form_name= this.form.id;
        if(form_name=='[object HTMLInputElement]')
            form_name='editFormAjax';
        $("#"+form_name).validate({
            rules: {
                service_category: "required",
                service_name_en: "required",
                service_name_el: "required",
                service_address_en: "required",
                service_address_el: "required",
                opening: "required",
                closing: "required",
            },
            messages: {
                service_category: '<?php echo lang('service_category_validation');?>',
                service_name_en: '<?php echo lang('service_name_en_validation');?>',
                service_name_el: '<?php echo lang('service_name_el_validation');?>',
                service_address_en: '<?php echo lang('service_address_en_validation');?>',
                service_address_el: '<?php echo lang('service_address_el_validation');?>',
                opening: '<?php echo lang('opening_validation');?>',
                closing: '<?php echo lang('closing_validation');?>',
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
        jQuery("#news_image").val("");
    });

    $('#common_datatable_restaurant').dataTable({
      order: [],
      columnDefs: [ { orderable: false, targets: [5,6] } ]
    });

</script>

