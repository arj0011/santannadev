<script>

    jQuery('body').on('click', '#submit', function () {

        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
            form_name = 'editFormAjax';
        $("#" + form_name).validate({
            errorPlacement: function (error, element) {
                if (element.attr("type") == "file") {
                    error.insertBefore($('.profile_content'));
                } else {
                    $(error).insertAfter(element);
                }
            },
            rules: {
                name: "required",
                /*roomWidth: {required: true,number: true,max:1200,min:400},
                 roomHeight:{required: true,number: true,max:1200,min:400},*/
                agent: {required: true},
                store: {required: true}
            },
            messages: {
                name: '<?php echo lang('user_name_validation'); ?>',
                /*roomWidth: {
                 required: 'Room Width is Required',
                 number: 'Room Width is must be a Number'
                 },
                 roomHeight: {
                 required: 'Room Height is Required',
                 number: 'Room Height is must be a Number'
                 },*/
                agent: '<?php echo lang('agentrequired'); ?>',
                store: '<?php echo lang('store_Name_validation'); ?>'
                        /*image: {required: 'Room Image is Required',accept:'Not an image'}*/

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
        jQuery("#user_image").val("");
    });


    $('#common_datatable_floors').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [0, 4]}]
    });




    $('#common_datatable_history').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [4, 6, 8]}]
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

    function getTablePlan(ids) {
        $.ajax({
            url: "<?php echo base_url(); ?>floors/getTablePlan",
            type: "POST",
            data: {floor_id: ids},
            success: function (data) {
                if(data != 1){
                  $("#form-modal-box").html(data);
                  $("#commonModalFloorTable").modal('show');
                }else{
                    Ply.dialog("alert", "Tables Not Found");
                }
            }
        });
    }
    
    function deleteFnTable(tableId,floorId){
        $.ajax({
            url: "<?php echo base_url(); ?>floors/deleteTables",
            type: "POST",
            data: {table_id: tableId,floor_id: floorId},
            success: function (data) {
                if(data == 1){
                    Ply.dialog("alert", "Table not deleted");
                }else if(data == 2){
                    Ply.dialog("alert", "The table today already booked by user");
                }else{
                     $("#onTableList").html(data);
                     Ply.dialog("alert", "Successfully Table deleted");
                }
            }
        });
    }
</script>


