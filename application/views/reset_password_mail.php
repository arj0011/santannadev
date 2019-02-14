<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Reset password for Loyalty app</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<div>
<?php $url = base_url().'users/reset_password/'.$user_id; ?>
<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Hello <?php echo $name; ?>, </p> 

<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> 
 As per your request for the Reset Password, Click on the link below to reset your password: <br>

 <a href="<?php echo $url; ?>" >click here</a><br>
</p>
</div>
</body>
</html>
