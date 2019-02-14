<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
            <div class="ibox-title">
<!--                    <div class="btn-group " href="#">
                        <a href="javascript:void(0)"  onclick="open_modal('notification')" class="btn btn-primary">
                            <?php echo lang('send_notification');?>
                        
                        </a>
                    </div>-->
                </div>
               <div class="ibox-content">
                 <div class="row">

                 <!-- <form method="post" action="<?php echo  base_url() ?>notification/sendNotification " id="send_notification" class="profile_form">

                            <div class="form-group ">
                                <label class="col-lg-3 control-label"> 
                                <?php echo lang('select_option');?></label>
                                 <div class="col-md-9">
                                 <input type="radio" class="all_user" name="notification" value="1"><?php echo lang('all_devices');?>
                                 <input type="radio" class="all_user" name="notification" value="2"> <?php echo lang('all_users');?>
                                 <input type="radio" class="all_user" name="notification" value="3" checked value="3"> <?php echo lang('select_group');?>
                                 <input type="radio" class="all_user" name="notification" value="4"> <?php echo lang('select_user');?>
                                 <div id="dvUser" style="display: none">
                                 <select class="formcontrol" multiple="" name="user[]" id="user" style="width:100%">
                                            
                                 </select>

                                  </div>
                                  </div>
                                
                                </div>
                    
                        <div class="form-group col-sm-12">
                             <div class="col-sm-5"><label><h4> <?php echo lang('notification');?></h4></label></div> 
                            <div class="col-sm-12">
                              <input type="text" name='title'  placeholder="<?php echo lang('title');?>" id='title' class="form-control"/>
                            </div>
                            <div class="col-sm-12">
                              <textarea name='notification'  placeholder="<?php echo lang('notification');?>" id='notification' class="form-control"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group col-sm-12 text-center">
                            <input class="btn btn-primary  create_btn" id="submit" type="submit" name="submit" value="<?php echo lang('send');?>">
                        </div>
                    </form> -->
                      <?php $message = $this->session->flashdata('success');
                            if(!empty($message)):?><div class="alert alert-success">
                                <?php echo $message;?></div><?php endif; ?>
                       <?php $error = $this->session->flashdata('error');
                            if(!empty($error)):?><div class="alert alert-danger">
                                <?php echo $error;?></div><?php endif; ?>
                     <div id="message"></div>
                    <div class="col-lg-12" style="overflow-x: auto">
                    <table class="table table-bordered table-responsive" id="common_datatable">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo "User";?></th>
                                <th><?php echo lang('title');?></th>
                                 <th><?php echo lang('message');?></th>
                                <th><?php echo lang('notification_type');?></th>
                                <th><?php echo lang('date');?></th>
                               
                                <th style="width: 18%"><?php echo lang('action');?></th>
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
                            <td><?php echo $rows->name;?></td>
                             <td><?php echo $rows->title;?></td>
                             <td><?php

                                if(strlen($rows->message)>120){
                                  $content=$rows->message;
                                  echo mb_substr($rows->message,0,120,'UTF-8').'...<br>';
                                  ?>
                                  <a style="cursor:pointer" onclick="show_message('<?php echo base64_encode($content);?>')"><?php echo lang('view');?></a>
                                  <?php
                                }
                                else if(strlen($rows->message)>0){
                                  echo $rows->message;
                                }
                                
                        
                        
                        ?></td>
                           
                            <td><?php echo $rows->notification_type;?></td>
                            <td><?php echo date(DEFAULT_DATE,strtotime($rows->sent_time));?></td>
                           
                            
                            <td class="actions">
                             <!-- <?php 
                                  if($rows->is_read==1){
                                    ?>
                                    <img height="16px" width="16px" src="<?php echo base_url() ?>/assets/img/read.png">
                                    <?php  
                                  }
                                  else{
                                    ?>

                                    <a  href="<?php echo  base_url() ?>/read_notification/<?php echo $rows->id;  ?>" class=" btn btn-success ">
                                      <img height="16px" width="16px" src="<?php echo base_url() ?>/assets/img/unread.png" title="<?php echo lang('mark_read');?>">
                                    </a>
                                    <?php
                                  }

                                ?> -->
<!--                                <a title="Read" href="javascript:void(0)" onclick="readFn('users_notifications','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-primary"><i class="fa fa-book"></i></a>-->
                              <a href="javascript:void(0)" onclick="deleteFn('users_notifications','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
                              <?php if(!empty($rows->booking_id)){?>
                              <a href="<?php echo site_url('tablebooking/index/'.  encoding($rows->booking_id))?>" class="btn btn-info btn-sm"><i class='fa fa-arrow-circle-o-right'></i> GO Booking</a>
                              <?php }?>
                            </td>
                            </tr>
                            <?php endforeach; endif;?>
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
                <div id="form-modal-box"></div>
        </div>
    </div>
</div>
<div id="message_div">
    <span id="close_button"><img src="<?php echo base_url();?>assets/img/close.png" onclick="close_message();"></span>
    <div id="message_container"></div>
</div>
