<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
    <i class="fa fa-envelope"></i>  <span class="label label-warning" id="total-notification"><?php echo count($notification); ?></span>
</a>
<ul class="dropdown-menu dropdown-messages" id="notification-list-show">
    <?php $delId = array();if (!empty($notification)) {
        $i = 1;
        foreach ($notification as $notify) {
            $delId[] = $notify->id; 
            if($i <= 5){  
            ?>
            <li>
                <div class="dropdown-messages-box">
                    <a href="<?php echo site_url('notification/push_message');?>" class="pull-left">

                        <img alt="image" class="img-circle" src="<?php if (!empty($notify->user_image)) {
            echo base_Url() ?><?php echo $notify->user_image;
        } else {
            echo base_url() . DEFAULT_NO_IMG_PATH;
        } ?>" />
                    </a>
                     <a href="<?php echo site_url('notification/push_message');?>">
                    <div class="media-body">
                        
        <!--                <small class="pull-right">46h ago</small>-->
                        <strong><?php echo $notify->name; ?></strong> <?php echo substr($notify->message_en,0,80); ?>. <br>
                        <small class="text-muted"><?php echo time_ago($notify->sent_time); ?> at <?php echo date('h:i A', strtotime($notify->sent_time)); ?> - <?php echo date('d/m/Y', strtotime($notify->sent_time)); ?></small>
                    
                    </div>
                     </a>
                </div>
            </li>
            <li class="divider"></li>
        <?php }$i++;}
} ?>
    <li>
        <div class="text-center link-block">
           
                <?php if (!empty($notification)) {?>
            <a href="<?php echo base_url().'notification/read_notification_admin?q='.  encoding($delId);?>">
                <i class="fa fa-envelope"></i>
                <strong>Read All Messages</strong>
                </a>
                <?php }else{?>
                 <a href="<?php echo base_url().'notification/push_message';?>">
                     <strong><i class="fa fa-arrow-circle-o-right"></i> Notification</strong>
                <a/>
                <?php }?>
            
        </div>
    </li>
</ul>
