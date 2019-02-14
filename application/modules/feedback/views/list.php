<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
               <!-- <div class="ibox-title">
                    <div class="btn-group">
                        <a href="javascript:void(0)"  onclick="open_modal('service')" class="btn btn-primary">
                            <?php echo lang('add_service');?>
                        <i class="fa fa-plus"></i>
                        </a>
                       
                    </div>
                   
                </div> -->
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
                                <th><?php echo lang('service_name');?></th>
                                <th><?php echo lang('name');?></th>
                                <th><?php echo lang('email');?></th>
                                <th><?php echo lang('contact_number');?></th>
                                <th><?php echo lang('feedback_msg');?></th>
                                <th><?php echo lang('feedback_time');?></th>
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
                            <td><?php echo $rows->store_name?></td>
                            <td><?php echo $rows->name?></td>
                            <td><?php echo $rows->email?></td>
                            <td><?php echo $rows->contact?></td>
                            <td><?php echo $rows->feedback?></td>
                            <td><?php echo convertDate($rows->feedback_time)?></td>
                            <td class="actions">
                            <a href="javascript:void(0)" onclick="deleteFn('feedback_of_store','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
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
