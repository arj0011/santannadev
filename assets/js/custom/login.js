$(document).ready(function(){

/**************** Admin Login Script Start *************/

$("body").on("click",".submit_login", function(event){
        event.preventDefault();
	$("#login_form .input").each(function(){
		if($(this).val() == "")
		{
			$(this).addClass("p_error");
		}
	});
	if(!$("#login_form .input").hasClass("p_error"))
	{
		
		var data = $("#login_form").serialize();
		$.ajax({
			url:get_url()+ "siteadmin/login",
			type:"post",
			data:data,
			success: function(data)
			{       
				if(data == "success")
				{
                                        $("#login_form").submit(); 
					window.location.href=get_url()+"admin/dashboard";
				}else
				{
					$("#errors").show();
				}
			}
		});
	}
});

$("body").on("keyup blur","#email",function() {
    var email = $(this).val();
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (regex.test(email)) {
        $(this).removeClass("p_error");
    } else {
        $(this).addClass("p_error");
    }
});

$("body").on("keyup blur", "#password", function(){
	$(this).removeClass("p_error");
});

$("#email,#password").keyup(function(event){
    if(event.keyCode == 13){
        $(".submit_login").trigger("click");
    }
});


/**************** Admin Login Script End **************/

});