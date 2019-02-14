<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
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
                                <th><?php echo lang('app_name');?></th>
                                <th><?php echo lang('service_name');?></th>
                                <th><?php echo lang('fullname');?></th>
                                <th><?php echo lang('email');?></th>
                                <th><?php echo lang('contact_no');?></th>
                                <th><?php echo lang('store_feedback');?></th>
                                <th><?php echo lang('store_feedback_time');?></th>
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
                            <td><?php echo $rows->company_name?></td>
                            <td><?php echo $rows->store_name?></td>
                            <td><?php echo $rows->full_name?></td>
                            <td><?php echo $rows->email?></td>
                            <td><?php echo $rows->contact?></td>
                            <td><?php echo $rows->feedback?></td>
                            <td><?php echo date(DEFAULT_DATE, strtotime($rows->feedback_time))?></td>
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
