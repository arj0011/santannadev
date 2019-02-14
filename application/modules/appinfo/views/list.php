<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
<!--                    <div class="btn-group " href="#">
                        <a href="javascript:void(0)"  onclick="open_modal('appinfo')" class="btn btn-primary">
                            <?php //echo lang('add_app_content');?>
                        <i class="fa fa-plus"></i>
                        </a>
                    </div>-->
                </div>
                <div class="ibox-content">
                 <div class="row">
                      <?php $message = $this->session->flashdata('success');
                            if(!empty($message)):?><div class="alert alert-success">
                                <?php echo $message;?></div><?php endif; ?>
                       <?php $error = $this->session->flashdata('error');
                            if(!empty($error)):?><div class="alert alert-danger">
                                <?php echo $error;?></div><?php endif; ?>
                     <div id="message"></div>
                    <div class="col-lg-12" style="overflow-x: auto">
                    <table class="table table-bordered table-responsive" id="common_datatable_app">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('app_name');?></th>
                                <th><?php echo lang('app_images');?></th>
                                <th><?php echo lang('app_mayor_image');?></th>
                                <th><?php echo lang('app_mayor_greeting');?></th>
                                <th><?php echo lang('app_news_main_image');?></th>
                                <th><?php echo lang('app_logo');?></th>
                                <th><?php echo lang('app_email');?></th>
                                <th><?php echo lang('app_phone');?></th>
                                <th><?php echo lang('action');?></th>
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
                            <td><?php echo $rows->company_name;?></td>
                            <td> <?php if(!empty($rows->company_image)){
                                    $company_image = json_decode($rows->company_image);
                                    foreach($company_image as $img){?>
                                      <a data-fancybox="gallery" href="<?php if(!empty($img)){echo base_Url()?>uploads/app/<?php echo $img;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>"><img width="50" src="<?php if(!empty($img)){echo base_Url()?>uploads/app/<?php echo $img;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>"></a>
                                    <hr>
                                    <?php }
                                }?>
                            </td>
                            <td><img width="100" src="<?php if(!empty($rows->ceo_image)){echo base_Url()?>uploads/app/<?php echo $rows->ceo_image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                           
                            <td style="width:25%;"><?php
                             if(strlen($rows->ceo_message)>120){
                                  $content=$rows->ceo_message;
                                  echo mb_substr($rows->ceo_message,0,120,'UTF-8').'...<br>';
                                  ?>
                                  <a style="cursor:pointer" onclick="show_message('<?php echo base64_encode($content);?>')"><?php echo lang('view');?></a>
                                  <?php
                                }
                                else if(strlen($rows->ceo_message)>0){
                                  echo $rows->ceo_message;
                                }
                                
                           ?></td>

                            <td><img width="100" src="<?php if(!empty($rows->news_event_image)){echo base_Url()?>uploads/app/<?php echo $rows->news_event_image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                             <td><img width="100" src="<?php if(!empty($rows->company_logo)){echo base_Url()?>uploads/app/<?php echo $rows->company_logo;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                            <td><?php echo $rows->email;?></td>
                            <td><?php echo $rows->phone_number;?></td>
                            <td class="actions">
                             <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('appinfo','appinfo_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                             <a href="<?php echo base_url().'admin/changepassword';?>" class="on-default edit-row text-danger" title="<?php echo lang('change_password');?>"><img width="20" src="<?php echo base_url().PASSWORD_ICON;?>" /></a>
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