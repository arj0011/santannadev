<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    
                </div>
                  <form class="well" id="date_sortinng" action="<?php echo base_url('referrer'); ?>" method="post">
         
             <div class="row">
                   <div class="form-group clearfix ">
                     
                    <label class="control-label col-lg-2" for="email">
                   <?php echo lang('booking_month');?>
                   
                    </label>
                  <div class="col-lg-4">
                      <input class="form-control" type="text" name="start_date" id="start_date" placeholder="<?php echo lang('booking_month');?>" readonly="readonly"></input>
                  </div>
                   <div class="col-lg-2">
                      <input type="submit"  class="btn btn-primary" type="submit" value="<?php echo lang('submit_btn');?>" name="submit" id="submit">
                      
                      
                  </div>
                  </div>
                  </div>
                  <div class="form-group clearfix ">
                       
                 </div>
                      </form>
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
                    <table class="table table-bordered table-responsive" id="common_datatable_agents">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('referrer');?></th>
                                <th><?php echo lang('total_count_booking');?></th> 
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
                            <td><?php echo $rows->referrer?></td>
                            <td><?php echo $rows->total_booking?></td> 
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
