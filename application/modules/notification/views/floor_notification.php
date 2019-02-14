<style>
    #background-wrap {
        bottom: 0;
        left: 0;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 999;
    }

    /* KEYFRAMES */

    @-webkit-keyframes animateBubble {
        0% {
            margin-top: 1000px;
        }
        100% {
            margin-top: -100%;
        }
    }

    @-moz-keyframes animateBubble {
        0% {
            margin-top: 1000px;
        }
        100% {
            margin-top: -100%;
        }
    }

    @keyframes animateBubble {
        0% {
            margin-top: 1000px;
        }
        100% {
            margin-top: -100%;
        }
    }

    @-webkit-keyframes sideWays { 
        0% { 
            margin-left:0px;
        }
        100% { 
            margin-left:50px;
        }
    }

    @-moz-keyframes sideWays { 
        0% { 
            margin-left:0px;
        }
        100% { 
            margin-left:50px;
        }
    }

    @keyframes sideWays { 
        0% { 
            margin-left:0px;
        }
        100% { 
            margin-left:50px;
        }
    }

    /* ANIMATIONS */

    .x1 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 1s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 1s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 1s ease-in-out infinite alternate;

        left: -5%;
        top: 5%;

        -webkit-transform: scale(0.6);
        -moz-transform: scale(0.6);
        transform: scale(0.6);
    }

    .x2 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 1s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 1s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 1s ease-in-out infinite alternate;

        left: 5%;
        top: 80%;

        -webkit-transform: scale(0.4);
        -moz-transform: scale(0.4);
        transform: scale(0.4);
    }

    .x3 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 1s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 1s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 2s ease-in-out infinite alternate;

        left: 10%;
        top: 40%;

        -webkit-transform: scale(0.7);
        -moz-transform: scale(0.7);
        transform: scale(0.7);
    }

    .x4 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 3s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 3s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 3s ease-in-out infinite alternate;

        left: 20%;
        top: 0;

        -webkit-transform: scale(0.3);
        -moz-transform: scale(0.3);
        transform: scale(0.3);
    }

    .x5 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 4s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 4s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 4s ease-in-out infinite alternate;

        left: 30%;
        top: 50%;

        -webkit-transform: scale(0.5);
        -moz-transform: scale(0.5);
        transform: scale(0.5);
    }

    .x6 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 2s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 2s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 2s ease-in-out infinite alternate;

        left: 50%;
        top: 0;

        -webkit-transform: scale(0.8);
        -moz-transform: scale(0.8);
        transform: scale(0.8);
    }

    .x7 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 2s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 2s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 2s ease-in-out infinite alternate;

        left: 65%;
        top: 70%;

        -webkit-transform: scale(0.4);
        -moz-transform: scale(0.4);
        transform: scale(0.4);
    }

    .x8 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 3s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 3s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 3s ease-in-out infinite alternate;

        left: 80%;
        top: 10%;

        -webkit-transform: scale(0.3);
        -moz-transform: scale(0.3);
        transform: scale(0.3);
    }

    .x9 {
        -webkit-animation: animateBubble 2s linear infinite, sideWays 4s ease-in-out infinite alternate;
        -moz-animation: animateBubble 2s linear infinite, sideWays 4s ease-in-out infinite alternate;
        animation: animateBubble 2s linear infinite, sideWays 4s ease-in-out infinite alternate;

        left: 90%;
        top: 50%;

        -webkit-transform: scale(0.6);
        -moz-transform: scale(0.6);
        transform: scale(0.6);
    }

    .x10 {
        -webkit-animation: animateBubble 5s linear infinite, sideWays 2s ease-in-out infinite alternate;
        -moz-animation: animateBubble 5s linear infinite, sideWays 2s ease-in-out infinite alternate;
        animation: animateBubble 5s linear infinite, sideWays 2s ease-in-out infinite alternate;

        left: 80%;
        top: 80%;

        -webkit-transform: scale(0.3);
        -moz-transform: scale(0.3);
        transform: scale(0.3);
    }

    /* OBJECTS */

    .bubble {
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;

        -webkit-box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2), inset 0px 10px 30px 5px rgba(255, 255, 255, 1);
        -moz-box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2), inset 0px 10px 30px 5px rgba(255, 255, 255, 1);
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2), inset 0px 10px 30px 5px rgba(255, 255, 255, 1);

        height: 200px;
        position: absolute;
        width: 200px;
    }

    .bubble:after {
        background: -moz-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.5) 0%, rgba(255,255,255,0) 70%); /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(255,255,255,0.5)), color-stop(70%,rgba(255,255,255,0))); /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%); /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%); /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%); /* IE10+ */
        background: radial-gradient(ellipse at center,  rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#80ffffff', endColorstr='#00ffffff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */

        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;

        -webkit-box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.3);
        -moz-box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.3);
        box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.3);

        content: "";
        height: 180px;
        left: 10px;
        position: absolute;
        width: 180px;
    }</style>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="row">
                        <?php if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'): ?> 
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"><i class="fa fa-envelope"></i> <?php echo lang('floor_manager_push_notification'); ?></div>
                                    <div class="panel-body">
                                        <div id="pushMessageShow" class="col-md-12"></div>
                                        <form role="form" action="#<?php //echo base_url('notification/push_message');  ?>" name="sendNotification" id="sendNotification" method="post">
                                            <div class="col-md-4 form-group">  
                                                <select id="floor" name="floor_id" class="form-control">
                                                    <option value=""><?php echo "Select Section"; ?></option>    
                                                    <?php
                                                    if (!empty($floors)):
                                                        foreach ($floors as $rows):
                                                            ?>
                                                            <option value="<?php echo $rows->id; ?>"><?php echo $rows->name; ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                                <?php echo form_error('floor_id'); ?>
                                                <input type="checkbox" name="allsection" value="all"/> All Sections
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <textarea name="message" class="form-control" placeholder="What's on your mind?"><?php echo set_value('message'); ?></textarea>
                                                <?php echo form_error('message'); ?>
                                            </div>  
                                            <div class="col-md-2 form-group">
                                                <button type="button" name="send" id="send" class="btn btn-primary">SEND</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <?php $message = $this->session->flashdata('success');
                    if (!empty($message)):
                        ?><div class="alert alert-success">
                        <?php echo $message; ?></div><?php endif; ?>
                        <?php $error = $this->session->flashdata('error');
                        if (!empty($error)):
                            ?><div class="alert alert-danger">
    <?php echo $error; ?></div><?php endif; ?>
                    <div id="message"></div>
                    <div class="col-lg-12" style="overflow-x: auto">
                        <table class="table table-bordered table-responsive" id="common_datatable">
                            <thead>
                                <tr>
                                    <th><?php echo lang('serial_no'); ?></th>
                                    <th><?php echo "User"; ?></th>
                                    <th><?php echo lang('title'); ?></th>
                                    <th><?php echo lang('message'); ?></th>
                                    <th><?php echo lang('notification_type'); ?></th>
                                    <th><?php echo lang('date'); ?></th>

                                    <th style="width: 18%"><?php echo lang('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($list) && !empty($list)):
                                    $rowCount = 0;
                                    foreach ($list as $rows):
                                        $rowCount++;
                                        ?>
                                        <tr>
                                            <td><?php echo $rowCount; ?></td>            
                                            <td><?php echo $rows->name; ?></td>
                                            <td><?php echo $rows->title; ?></td>
                                            <td><?php
                                                if (strlen($rows->message) > 120) {
                                                    $content = $rows->message;
                                                    echo mb_substr($rows->message, 0, 120, 'UTF-8') . '...<br>';
                                                    ?>
                                                    <a style="cursor:pointer" onclick="show_message('<?php echo base64_encode($content); ?>')"><?php echo lang('view'); ?></a>
                                                    <?php
                                                } else if (strlen($rows->message) > 0) {
                                                    echo $rows->message;
                                                }
                                                ?></td>

                                            <td><?php echo $rows->notification_type; ?></td>
                                            <td><?php echo date(DEFAULT_DATE, strtotime($rows->sent_time)); ?></td>


                                            <td class="actions">
                                                <!-- <?php
                                        if ($rows->is_read == 1) {
                                            ?>
                                                           <img height="16px" width="16px" src="<?php echo base_url() ?>/assets/img/read.png">
                                                    <?php
                                                } else {
                                                    ?>
                       
                                                           <a  href="<?php echo base_url() ?>/read_notification/<?php echo $rows->id; ?>" class=" btn btn-success ">
                                                             <img height="16px" width="16px" src="<?php echo base_url() ?>/assets/img/unread.png" title="<?php echo lang('mark_read'); ?>">
                                                           </a>
            <?php
        }
        ?> -->
                   <!--                                <a title="Read" href="javascript:void(0)" onclick="readFn('users_notifications','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-primary"><i class="fa fa-book"></i></a>-->
                                                <a href="javascript:void(0)" onclick="deleteFn('users_notifications', 'id', '<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url() . DELETE_ICON; ?>" /></a>
                                                <?php if (!empty($rows->booking_id)) { ?>
                                                    <a href="<?php echo site_url('tablebooking/index/' . encoding($rows->booking_id)) ?>" class="btn btn-info btn-sm"><i class='fa fa-arrow-circle-o-right'></i> GO Booking</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                            <?php endforeach;
                                        endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="form-modal-box"></div>
        </div>
    </div>
</div> 



