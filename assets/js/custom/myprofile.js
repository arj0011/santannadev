$(document).ready(function(){

/**************** Admin Profile Script Start *************/

var form_object = jQuery(".profile_form");
form_object.validate({
  rules:{
        old_pswd:{
            required: true,
        },
        new_pswd:{
            required: true,
            minlength:6,
            maxlength:14
        },
        confirm_pswd:{
            required: true,
            minlength:6,
            maxlength:14,
            equalTo  :"#new_pswd"
        }
  },
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
      jQuery(element[0]).remove();
    },
    submitHandler: function() {
        var old_pswd     = btoa($('#old_pswd').val());
        var new_pswd     = btoa($('#new_pswd').val());
        var confirm_pswd = btoa($('#confirm_pswd').val());
        $.ajax({
            url:get_url()+ "admin/dochangepassword",
            type:"POST",
            data:{new_pswd:new_pswd,confirm_pswd:confirm_pswd,old_pswd:old_pswd},
            dataType:"JSON",
            beforeSend: function() {
                $('.loading').show();
                $('.loading_icon').show();
            }, 
            success: function(data)
            {
                $('.loading').hide();
                $('.loading_icon').hide();
                if(data.type != 'failed'){
                    $('#password_form')[0].reset();
                }
                Ply.dialog("alert", data.msg);
            },
            error:function(error)
            {
                console.log(error);
                $('.loading').hide();
                $('.loading_icon').hide();
            }
        });
    }
});

/**************** Admin Profile Script End ***************/

});