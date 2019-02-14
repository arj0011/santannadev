<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <?php if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'):?>
                <div class="ibox-title">
                    <div class="btn-group " href="#">
                        <a href="javascript:void(0)"  onclick="open_modal('floors')" class="btn btn-primary">
                            <?php echo lang('add_location');?>
                        <i class="fa fa-plus"></i>
                        </a>
                        
                    </div>
                    
                </div>
            <?php endif; ?>
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
                    <table class="table table-bordered table-responsive" id="common_datatable_floors">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('name');?></th>
                                <th><?php echo lang('width');?></th>
                                <th><?php echo lang('height');?></th>
                                <th style="width: 12%"><?php echo lang('action');?></th>
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
                            <td><?php echo $rows->name?></td>
                            <td><?php echo $rows->roomWidth?></td>
                            <td><?php echo $rows->roomHeight?></td>
                            
                            <td class="actions">
                             
                            <?php if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'):?>
                                   <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('floors','floors_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                                <a href="javascript:void(0)" onclick="deleteFn('mw_rooms','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
                           <?php endif;?>
                                <hr>
                            <a class="btn btn-sm btn-primary"  href="<?php echo base_url(); ?>floors/table/<?php echo $rows->id; ?>"><?php echo lang('table_plan');?></a>
                            <a class="btn btn-sm btn-warning"  href="javascript:void(0)" onclick="getTablePlan('<?php echo $rows->id; ?>')"><?php echo lang('view_table_plan');?></a>
                            </td>
                            </tr>
                            <?php endforeach; endif;?>
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
          
        </div>
    </div>
</div>
      <div id="form-modal-box"></div>