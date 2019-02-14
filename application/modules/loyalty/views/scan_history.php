<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
            </li>
            <li>
                <a href="<?php echo site_url('loyalty');?>"><?php echo lang('loyalty');?></a>
            </li>
            <li>
            <?php $loyalty_id = $this->uri->segment(3);?>
                <a href="<?php echo site_url('loyalty/scan_history/'.$loyalty_id);?>"><?php echo lang('scan_history');?></a>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
 
    </div>
</div>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <!-- <div class="ibox-title">
                    <div class="btn-group " href="#">
                        <a href="javascript:void(0)"  onclick="open_modal('loyalty')" class="btn btn-primary">
                            <?php echo lang('add_loyalty');?>
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
                    <table class="table table-bordered table-responsive" id="common_datatable_scan">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('user');?></th>
                                <th><?php echo lang('loyalty_scane');?></th>
                                <th><?php echo lang('user_scane');?></th>
                                <th><?php echo lang('remaining_scane');?></th>
                               
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
                           <td><?php echo $rows->loyalty_scane;?></td>
                           <td><?php echo $rows->user_scane;?></td>
                           <td><?php $total_scan= $rows->loyalty_scane;
                                     $user_scan= $rows->user_scane;
                                     echo $remaining_scan=$total_scan-$user_scan;?></td>
                          
                                 
                           <!--  <td class="actions">
                             <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('loyalty','loyalty_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                            <a href="javascript:void(0)" onclick="deleteFn('loyalty','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
                             <hr>
                             <a class="btn btn-sm btn-primary"  href="<?php echo base_url(); ?>loyalty/scan_history/<?php echo $rows->id; ?>"><?php echo lang('scan_history');?></a>
                            </td> -->
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