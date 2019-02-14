
<!--<link href="<?php echo base_url();?>/assets/css/select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url();?>/assets/js/select2.min.js"></script>-->
<script>
 jQuery('body').on('click', '#submit', function () {
        
        var form_name= this.form.id;
        if(form_name=='[object HTMLInputElement]')
            form_name='addFormAjax';
        $("#"+form_name).validate({
            rules: {
                page_id: "required",
                description_en: "required",
                description_el: "required",
                
            },
            messages: {
                page_id: '<?php echo lang('page_id_validation');?>',
                description_en: '<?php echo lang('description_en_validation');?>',
                description_el: '<?php echo lang('description_el_validation');?>',
                
            },
            submitHandler: function (form) {
                jQuery(form).ajaxSubmit({
                });
            }
        });

    });    


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
 var remove_user = function (id) {
 
        $('#old_user' + id).remove();
  }

 

        
  $("#chkAge").click(function () {

      if ($(this).is(":checked")) {
          $("#dvAge").show();
      } else {
          $("#dvAge").hide();
      }
  });

  $("#chkGender").click(function () {
   
      if ($(this).is(":checked")) {
          $("#dvGender").show();

          
      } else {
          $("#dvGender").hide();
      }
  });



// to get userlist by age 


jQuery('body').on('change', '#group_age', function () {
 
         var age=$('#group_age').val();
         var gender=$('#user_gender').val();
         getUserList(age,gender);


});
jQuery('body').on('click', '#gender_both', function () {
    // if ($(this).is(":checked")){
         var age=$('#group_age').val();
         var gender=$('#user_gender').val();
         getUserList(age,gender);
          $('#all_user').attr('checked', false);
//}        

});




// to get userlist by gender 


// jQuery('body').on('change', '#user_gender', function () {
     
//          // var age=$('#group_age').val();

//          // var gender=$('#user_gender').val();
//          // getUserList(age,gender);
//        //$('#user_name option[value=""]').prop('selected', false);
//         $('#all_user').click().click();
//        $('#all_user').attr('checked', false);
//        //$('#user_name').select2();
          

// });


// get userlist 

function getUserList(age,gender)
{
     
       $.ajax({
                type: "POST",
                url: "<?php echo  base_url()?>group/user_filter",
                data: "gender="+gender+"&age="+age,
                //dataType: 'html'
                //dataType: "json",
                success: function (data) {
                  
                   // console.log(data);
                    $('#user_name').html(data);

                    $('#all_user').click().click();

                     $('#user_name').select2();


                   
                    
                }
            });


}



// make all user selected
jQuery('body').on('click', '#all_user', function () {

  if($(this).is(':checked'))
  { 
     $('#user_name option').prop('selected', true);
     $('#user_name option[value=""]').prop('selected', false);
      $('#user_name').select2();
     

  }
  else{
     $('#user_name option').prop('selected', false);
      $('#user_name').select2();
  }
 
});

//  view user group User List

jQuery('body').on('click', '.view_group_users', function () {

  var groupID=$(this).attr('groupid');
  $.ajax({
                type: "POST",
                url: "<?php echo  base_url()?>group/group_user_list",
                data: "groupID="+groupID,
                //dataType: 'html'
                //dataType: "json",
                success: function (data) {

                  
                 
                    $('#commonModal').modal('show');
                    $('.form-body').html(data);
                    
                }
            });


});




   jQuery('body').on('change' , '#user_gender' , function () {

        
           var gender = jQuery(this).val();
           var age=$('#group_age').val();

      
            $.ajax({
                type: "POST",
                url: "<?php echo  base_url()?>group/group_filter",
                data: "gender="+gender+"&age="+age,
                //dataType: 'html'
                //dataType: "json",
                success: function (data) {
                 
                   // console.log(data);
                    $('#user_name').html(data);
                    
                }
            });
        })
   
 
</script>
<script type="text/javascript">
  $('#user_name').select2();
</script>


