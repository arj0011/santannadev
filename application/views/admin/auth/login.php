<!DOCTYPE html>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <meta name="format-detection" content="telephone=no">
        <meta charset="UTF-8">

        <meta name="description" content="<?php echo SITE_NAME; ?>">
        <meta name="keywords" content="<?php echo SITE_NAME; ?>">
        <title><?php echo SITE_NAME; ?> Admin</title>
        
        <link href="<?php echo base_url(); ?>assets/plugins/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/plugins/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        
        <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/custom_login.css" rel="stylesheet">
    </head>
    <body class="login-content">
        <!-- Login -->

        <div class="lc-block toggled" id="l-login">
            <form id="login_form" name="login_form">
                 <?php $logo=CommonGet(array('table' =>ADMIN,'single'=>true));?>
                <div class="lcb-float"><img src="<?php if(!empty($logo->company_logo)){echo base_Url()?>uploads/app/<?php echo $logo->company_logo;}else{echo base_url().DEFAULT_LOGO;}?>"></div>
                <br>
	            <div class="form-group">
                    <div class=" show_error danger messages-show">
                        <h6 class="alert alert-danger" style="display: none" id="errors">Invalid Login Credentials</h6> 
                         <?php
                        $session_expire = $this->session->flashdata('session_expire');
                        if(!empty($session_expire))
                        { ?>
                           <div class="alert alert-danger">
                                <p><?php echo $session_expire; ?></p>
                            </div> 
                    <?php  } ?>
                    </div>
                   
	            </div>
	            <div class="form-group">
	                <input type="text" class="form-control input" id="email" name="email" placeholder="Email">
	            </div>
	            
	            <div class="form-group">
                        <input type="password" class="form-control input" id="password" name="password" placeholder="Password" autocomplete="off">
	            </div>
	            
	            <div class="clearfix"></div>
	            
	            <div class="p-relative ">
	                <div class="checkbox cr-alt">
	                    <label class="c-gray">
	                        <input type="checkbox" checked name="remember_me" value="">
	                        <i class="input-helper"></i>
	                        Keep me signed in
	                    </label>
	                </div>
	            </div>
                    <input type="submit" class="btn btn-block btn-primary btn-float m-t-25 submit_login" placeholder="" value="Sign In" id="button">
<!--	            <a href="javascript:void(0)" class="btn btn-block btn-primary btn-float m-t-25 submit_login">Sign In</a>-->
        	</form>
        </div>
        <!-- Javascript Libraries -->
        <script src="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/functions.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/custom/login.js"></script>
    	<script type="text/javascript">
            function get_url()
            {
                var url = "<?php echo base_url(); ?>";
                return url;
            }
        </script> 
    </body>
</html>