<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="btn-group " href="#">
                        <a href="javascript:void(0)"  onclick="open_modal('offer')" class="btn btn-primary">
                            <?php echo lang('add_offer');?>
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
                    <table class="table table-bordered table-responsive" id="common_datatable_offer">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th> 
                                <th><?php echo lang('offer_code');?></th>
                                <th><?php echo lang('qr_image');?></th>
                                <th><?php echo lang('name');?></th>
<!--                                <th><?php //echo lang('description');?></th>-->
                                <th>Offer Type</th>
                                <th><?php echo 'Discounts / Points';?></th>
                                <th><?php echo lang('offer_limit');?></th>
                                <th><?php echo lang('from_date');?></th>
                                <th><?php echo lang('to_date');?></th>
                                <th><?php echo lang('image');?></th>
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
                           <td><?php echo $rows->offer_code;?></td>
                           <td> <img width="70" src="<?php if(!empty($rows->qr_image)){echo base_Url()?>tmp/qr_codes/<?php echo $rows->qr_image;}?>" /> 
                           <a style="padding-left:40%" target="_blank" href="<?php echo base_url().'tmp/qr_codes/'.$rows->qr_image; ?>"><i class="fa fa-download fa-lg"></i></a></td>
                           <td><?php echo $rows->offer_name;?></td>
<!--                            <td style="width:25%;"><?php
                             if(strlen($rows->description)>120){
                                  $content=$rows->description;
                                  echo mb_substr($rows->description,0,120,'UTF-8').'...<br>';
                                  ?>
                                  <a style="cursor:pointer" onclick="show_message('<?php echo base64_encode($content);?>')"><?php echo lang('view');?></a>
                                  <?php
                                }
                                else if(strlen($rows->description)>0){
                                  echo $rows->description;
                                }
                                
                            ?></td>-->
                           <td><?php echo ($rows->offer_type == "PERCENTAGE") ? "Percentage" : "Loyalty Points";?></td>
                           <td><?php echo ($rows->offer_type == "PERCENTAGE") ? $rows->discounts_in_percent : $rows->offer_points;?></td>
                           <td><?php echo $rows->offer_limit;?></td>
                           <td><?php echo date(DEFAULT_DATE,strtotime($rows->from_date));?></td>
                           <td><?php echo date(DEFAULT_DATE,strtotime($rows->to_date));?></td>
                             <td><img width="100" src="<?php if(!empty($rows->image)){echo base_Url()?>uploads/offer/<?php echo $rows->image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>     
                            <td class="actions">
                             <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('offer','offer_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                            <a href="javascript:void(0)" onclick="deleteFn('offer','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
                           
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