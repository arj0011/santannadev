<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="btn-group " href="#">
                        <a href="javascript:void(0)"  onclick="open_modal('users')" class="btn btn-primary">
                            <?php echo lang('add_user');?>
                        <i class="fa fa-plus"></i>
                        </a>
                    <!-- <form style="padding-left:90%" class="print_report" action="<?php echo base_url('users/export_user'); ?>" method="post">

                          <input type="submit"  class="btn btn-primary export" type="submit" value="Export Users" name="submit" id="export" onclick="export_user()">
                         
                  </form> -->
                        
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
                    <table class="table table-bordered table-responsive" id="common_datatable_users">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('user_name');?></th>
                                <th><?php echo lang('wallet_point');?></th>
                                <th><?php echo lang('user_email');?></th>
                                <th><?php echo lang('date_of_birth');?></th>
                                <th><?php echo lang('qr_image');?></th>
                                <th><?php echo lang('user_createdate');?></th>
                                <th><?php echo lang('status');?></th>
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
                            <td><?php echo $rows->user_name?></td>
                            <td><?php echo $rows->total_point;?></td>
                            <td><?php echo $rows->email?></td>
                            <td><?php echo ($rows->date_of_birth != "1970-01-01") ? date(DEFAULT_DATE,strtotime($rows->date_of_birth)) : "";?></td>
                            <td><img width="100" src="<?php if(!empty($rows->qr_image)){echo base_Url()?>tmp/qr_codes/<?php echo $rows->qr_image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                            <td><?php echo date(DEFAULT_DATE,strtotime($rows->created_date));?></td>
                            <td><?php if($rows->is_deactivated == 1) echo '<p class="text-danger">'.lang('deactive').'</p>'; else echo '<p  class="text-success">'.lang('active').'</p>';?></td>
                            <td class="actions">
                                <a href="javascript:void(0)" class="on-default edit-row" onclick="viewFn('users','user_view','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().VIEW_ICON;?>" /></a>
                                <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('users','user_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                            <?php if($rows->is_deactivated == 1) {?>
                            <a href="javascript:void(0)" class="on-default edit-row" onclick="statusFn('users','id','<?php echo $rows->id?>','<?php echo $rows->is_deactivated;?>')" title="active Now"><img width="20" src="<?php echo base_url().INACTIVE_ICON;?>" /></a>
                            <?php } else { ?>
                            <a href="javascript:void(0)" class="on-default edit-row text-danger" onclick="statusFn('users','id','<?php echo $rows->id?>','<?php echo $rows->is_deactivated;?>')" title="Inactive Now"><img width="20" src="<?php echo base_url().ACTIVE_ICON;?>" /></a>
                            <?php } ?>
                            <a href="javascript:void(0)" onclick="deleteFn('users','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
                            <hr>
                            <a class="btn btn-sm btn-primary"  href="<?php echo base_url(); ?>tablebooking/view_booking/<?php echo $rows->id; ?>"><?php echo lang('booking_history');?></a><br/>
                            <a class="btn btn-sm btn-primary"  href="<?php echo base_url(); ?>loyalty/index/<?php echo $rows->id; ?>"><?php echo lang('loyalty_history');?></a><br/>
                            <a class="btn btn-sm btn-primary"  href="<?php echo base_url(); ?>offer/index/<?php echo $rows->id; ?>"><?php echo lang('offer_history');?></a>

                             <a class="btn btn-sm btn-primary"  href="<?php echo base_url(); ?>wallethistory/index/<?php echo $rows->id; ?>"><?php echo lang('wallet_history');?></a>

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
<script type="text/javascript">
  function export_user() {   
        //window.open("<?php echo base_url('booking/export_user');?>", "_blank");
        alert("Users Export Successfully");
    }
</script>