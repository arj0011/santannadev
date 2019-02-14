<script> 

    jQuery('body').on('click', '#submit', function () {

        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
                form_name = 'editFormAjax';
            $("#" + form_name).validate({
            rules: {
                mtop_money:{
                    // required:true,
                    number: true,
                    min:0.01
                },
                mtop_point:{
                    // required:true,
                    number: true,
                    digits: true
                },
                ptom_point:{
                    required:true,
                    number: true,
                    digits: true
                },
                ptom_money:{
                    required:true,
                    number: true,
                    min:0.01
                }
            },
            messages:{
                // mtop_money:{
                //     required:'<?php echo lang('money_req') ?>'
                // },
                // mtop_point:{
                //     required:'<?php echo lang('point_req') ?>'
                // },
                ptom_point:{
                    required:'<?php echo lang('point_req') ?>'
                },
                ptom_money:{
                    required:'<?php echo lang('money_req') ?>'
                }

            },
            submitHandler: function (form) {
                jQuery(form).ajaxSubmit({
               });
            }
        });
    });
    
 
  
    
 
  
    

    

 

     
   
   


   
</script>


