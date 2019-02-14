<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="btn-group">
                        <a href="javascript:void(0)"  onclick="open_modal('news')" class="btn btn-primary">
                            <?php echo lang('add_news');?>
                        <i class="fa fa-plus"></i>
                        </a>
                       
                    </div>
                    <div class="btn-group">
                     <a href="javascript:void(0)"  onclick="show_news_image()" class="btn btn-primary">
                            <?php echo lang('news_main_image');?>
                        <i class="fa fa-plus"></i>
                        </a>
                    </div>
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
                    <table class="table table-bordered table-responsive" id="common_datatable_news">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('news_category');?></th>
                                <th><?php echo lang('news_title');?></th>
<!--                                <th><?php echo lang('news_title');?></th>-->
                                <th><?php echo lang('news_content');?></th>
<!--                                <th><?php echo lang('news_content');?></th>-->
                                <th><?php echo lang('news_location');?></th>
                                <th><?php echo lang('image');?></th>
                                <th><?php echo lang('date');?></th>
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
                            <td><?php echo $rows->category_name?></td>
                            <td><?php echo $rows->news_heading?></td>
<!--                            <td><?php echo $rows->news_heading_el?></td>-->
                            <td style="width:25%;"><?php
                             if(strlen($rows->news_content)>120){
                                  $content=$rows->news_content;
                                  echo mb_substr($rows->news_content,0,120,'UTF-8').'...<br>';
                                  ?>
                                  <a style="cursor:pointer" onclick="show_message('<?php echo base64_encode($content);?>')"><?php echo lang('view');?></a>
                                  <?php
                                }
                                else if(strlen($rows->news_content)>0){
                                  echo $rows->news_content;
                                }
                                
                            ?></td>
                            
<!--                            <td><?php echo $rows->news_content_el?></td>-->
                            <td><?php echo $rows->news_location?></td>
                            <td><img width="100" src="<?php if(!empty($rows->news_image)){echo base_Url()?>uploads/news/<?php echo $rows->news_image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                            <td><?php echo date(DEFAULT_DATE,strtotime($rows->date));?></td>
                            <td class="actions">
                             <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('news','news_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                            <a href="javascript:void(0)" onclick="deleteFn('news','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
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