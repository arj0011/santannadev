<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <!-- <?php if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'):?>
                <div class="ibox-title">
                    <div class="btn-group " href="#">
                        <a href="javascript:void(0)"  onclick="open_modal('wallethistory')" class="btn btn-primary">
                            <?php echo lang('add_location');?>
                        <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?> -->
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
                    <table class="table table-bordered table-responsive" id="common_datatable_wallethistory">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('user');?></th>
                                <th><?php echo lang('booking');?></th>
                                <th>Total Billing Amount</th>
                                <th>Actual Payment</th>
                                <th>Earn Point</th>
                                <th>Use Point</th>
                                <!-- <th style="width: 12%"><?php echo lang('action');?></th> -->
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
                            <td><?php echo $rows->order_id?></td>
                            <td><?php echo $rows->total_billing_amount?></td>
                            <td><?php echo $rows->actual_payment?></td>
                            <td><?php echo $rows->earn_point?></td>
                            <td><?php echo $rows->use_point?></td>
                            <!-- <td class="actions"></td> -->
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