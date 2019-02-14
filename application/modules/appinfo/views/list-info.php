<div class="row wrapper border-bottom white-bg page-heading custom_pgheading">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('home'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('appinfo'); ?>"><?php echo lang('app_info'); ?></a>
            </li>
        </ol>
        <ul class="nav navbar-top-links navbar-right custom_message">

            <li class="dropdown" id="notification-list-view">

            </li>
            <li><a style="display:none;" id="exitFullScreen" href="javascript:void(0)" onclick="ExitfullScreen()"><img  width="30" alt="Exit Full Screen" class="" src="<?php echo base_url(); ?>assets/img/exit-full-screen-arrows.png"  title="Exit Full Screen"/></a></li>
            <li><a href="javascript:void(0)" onclick="fullScreen()"><img  width="30" alt="Full Screen" class="" src="<?php echo base_url(); ?>assets/img/switch-to-full-screen-button.png"  title="Full Screen"/></a></li>

        </ul>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <?php if(empty($list)){?>
                        <div class="btn-group " href="#">
                            <a href="javascript:void(0)"  onclick="open_modal_appinfo('appinfo')" class="btn btn-primary">
                                <?php echo lang('add_app_info');?>
                            <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    <?php }?>
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
                    <table class="table table-bordered table-responsive" id="common_datatable_info">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('app_name');?></th>
                                <th><?php echo lang('legal_text');?></th>
                                <th><?php echo lang('version');?></th>
                                <th><?php echo lang('copyright');?></th>
                                <th><?php echo lang('contact_images');?></th>
                                <th><?php echo lang('info_files');?></th>
                                <th><?php echo lang('contact_title');?></th>
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
                            <td style="width:25%;"><?php
                             if(strlen($rows->legal_text)>120){
                                  $content=$rows->legal_text;
                                  echo mb_substr($rows->legal_text,0,120,'UTF-8').'...<br>';
                                  ?>
                                  <a style="cursor:pointer" onclick="show_message('<?php echo base64_encode($content);?>')"><?php echo lang('view');?></a>
                                  <?php
                                }
                                else if(strlen($rows->legal_text)>0){
                                  echo $rows->legal_text;
                                }
                                
                            ?></td>

                            <td><?php echo $rows->version;?></td>
                            <td><?php echo $rows->copyright;?></td>
                            <td> <?php if(!empty($rows->contact_images)){
                                    $company_image = json_decode($rows->contact_images);
                                    foreach($company_image as $img){?>
                                      <a data-fancybox="gallery" href="<?php if(!empty($img)){echo base_Url()?>uploads/app/<?php echo $img;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>"><img width="50" src="<?php if(!empty($img)){echo base_Url()?>uploads/app/<?php echo $img;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>"></a>
                                    <hr>
                                    <?php }
                                }?>
                            </td>
                            <td>    
                          <?php 
                            if(!empty($rows->info_files)){ 
                                  $all_files=unserialize($rows->info_files);
                                  for($j=0;$j<count($all_files);$j++){
                                    $f_extension='';
                                    $f_extension = explode('.', $all_files[$j]['filename']); //To breaks the string into array
                                            $f_extension = end($f_extension);
                                            if(strtolower($f_extension)!='png' && strtolower($f_extension)!='jpg' && strtolower($f_extension)!='gif'  && strtolower($f_extension)!='jpeg' && strtolower($f_extension)!='bmp'){
                                                  $file_icon='document.png';
                                            }
                                            else{
                                                  $file_icon='img.png';
                                            }	
                                    ?>
                                  <a target = '_blank' href="<?php echo base_url() ?>uploads/app/<?php echo  $all_files[$j]['filename']; ?>"  class="btn">
                                      <img width="30" src="<?php echo base_url() ?>assets/img/<?php echo  $file_icon; ?>" class="file_icon" title="<?php echo  $all_files[$j]['title']; ?>"/>
                                  </a>
                                  <?php 
                                 }
                          }
                        
                        ?> 
                            </td>
                            <td><?php echo $rows->contact_title;?></td>
                           
                            <td class="actions">
                             <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('appinfo','info_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
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