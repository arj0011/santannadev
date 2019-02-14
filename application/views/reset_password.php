<!DOCTYPE html>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <meta name="format-detection" content="telephone=no">
        <meta charset="UTF-8">

        <meta name="description" content="<?php echo SITE_NAME; ?>">
        <meta name="keywords" content="<?php echo SITE_NAME; ?>">
        <title><?php echo SITE_NAME; ?> Reset Password</title>
        
        <link href="<?php echo base_url(); ?>assets/plugins/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/plugins/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        
        <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/custom_login.css" rel="stylesheet">
    </head>
    <body class="login-content">

    <div class="lc-block toggled" id="l-login">
    
      <form class="login-form" action="<?php echo base_url()?>users/reset_password/<?php echo $id_user_encode;?>" method="post">        
        <div class="login-wrap">
           <?php $logo=CommonGet(array('table' =>ADMIN,'single'=>true));?>
                <div class="lcb-float"><img src="<?php if(!empty($logo->company_logo)){echo base_Url()?>uploads/app/<?php echo $logo->company_logo;}else{echo base_url().DEFAULT_LOGO;}?>"></div>
                <br>
             <?php if($this->session->flashdata("passupdate")){ ?>
                <div class="alert alert-danger fadein">
                   <?php echo $this->session->flashdata("passupdate"); ?>
                </div>
            <?php } ?>

            <?php if($this->session->flashdata("error_passupdate")){ ?>
                <div class="alert alert-danger fadein">
                   <?php echo $this->session->flashdata("error_passupdate"); ?>
                </div>
            <?php } ?>

            <?php if($this->session->flashdata("passupdatematch")){ ?>
                <div id="alert alert-danger fadein">
                   <?php echo $this->session->flashdata("passupdatematch"); ?>
                </div>
            <?php } ?>
            
  
            <div class="form-group">
                
                <input type="password" class="form-control input"  name="new_password" placeholder="New Password">
            </div>
           <!--  <?php if(form_error('new_password')) { ?>
            <div id="alert alert-danger fadein"><?php echo form_error('new_password'); ?></div>
           <?php } ?> -->
             <div class="form-group">
                <input type="password" class="form-control input"  name="confirm_password" placeholder="confirm Password">
            </div>
           <!--  <?php if(form_error('conf_user_pass')) { ?>
            <div id="alert alert-danger fadein"><?php echo form_error('confirm_password'); ?></div>
           <?php } ?> -->
           <?php
                $user_id = $this->uri->segment(3);
                if(empty($user_id)){
                    $user_id = $id_user_encode;
                }

            ?>
           <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"></input>
            <input class="btn btn-primary btn-lg btn-block" type="submit" id="submit" value="Submit">
        </div>
      </form>

    </div>

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


            jQuery(document).ready(function(){
            setTimeout(function(){
                jQuery('div.fadein').fadeOut(1000,function(){
                    //window.location.href = "http://43.229.224.74/ci/crisis/admin/";
                });
            },1500);
        });
         

        </script> 
    </body>
</html>
