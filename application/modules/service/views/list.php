<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="btn-group">
                        <a href="javascript:void(0)"  onclick="open_modal('service')" class="btn btn-primary">
                            <?php echo lang('add_service');?>
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
                    <table class="table table-bordered table-responsive" id="common_datatable">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('service_name') .'/'. lang('category_name') .'/'. lang('service_address');?></th>
                                <th><?php echo lang('email');?></th>
                                <th><?php echo lang('contact_number');?></th>
                                <th><?php echo lang('opening').'/'.lang('closing');?></th>
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
                            <td><?php echo $rows->service_name .'/'. $rows->category_name .'/'. $rows->service_address?></td>
                            <td><?php echo $rows->email?></td>
                            <td><?php echo $rows->contact_number?></td>
                            <td><?php echo $rows->open_time.'/'.$rows->close_time?></td>
                            <td><img width="100" src="<?php if(!empty($rows->store_file)){echo base_Url()?>uploads/service/<?php echo $rows->store_file;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                            <td class="actions">
                             <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('service','service_edit','<?php echo encoding($rows->store_id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                            <a href="javascript:void(0)" onclick="deleteFn('service','store_id','<?php echo encoding($rows->store_id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
                            <a class="btn btn-primary"  href="<?php echo base_url(); ?>menu/menu_list/<?php echo $rows->store_id; ?>">Menus</a>
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
