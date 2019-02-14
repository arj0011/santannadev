<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
            </li>
            <li>
                <a href="<?php echo site_url('agents');?>"><?php echo lang('agents');?></a>
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
               <!--  <div class="ibox-title">
                     <div class="btn-group">
                        <a href="javascript:void(0)"  onclick="open_modal('booking')" class="btn btn-primary">
                            <?php echo lang('add_booking');?>
                        <i class="fa fa-plus"></i>
                        </a>
                       
                    </div> 
                   
                </div> -->
                
                 <?php $agent_id= $this->uri->segment(3);?>

                  <form class="well" id="date_sortinng" action="<?php echo base_url('agents/booking_history/').$agent_id; ?>" method="post">
         
                <div class="row">
                   <div class="form-group clearfix ">
                     
                    <label class="control-label col-lg-2" for="email">
                   Select date
                   
                    </label>
                  <div class="col-lg-4">
                      <input class="form-control" type="text" name="start_date" id="start_date" value="<?php echo $booking['start_date']; ?>" placeholder="From Date" readonly="readonly"></input>
                  </div>
                    <div class="col-lg-4">
                        <input class="form-control" type="text" name="end_date" id="end_date" value="<?php echo $booking['end_date']; ?>" placeholder="To Date" readonly="readonly"></input>
                  </div>

                  </div>
                  </div>
                  <div class="form-group clearfix ">
                       <div class="col-lg-offset-2 col-lg-10">
                      <input type="submit"  class="btn btn-primary" type="submit" value="Submit" name="submit" id="submit">
                      
                      
                  </div>
                 </div>
                 <!-- <div class="col-lg-12" style="float-left:50%">
                 <label class="control-label col-lg-2" for="email" style="padding-left:70%,-top: 20%">
                 <?php echo lang('total_bookings');?> :</label>
                   <?php getAllCount(BOOKING);?>
                 </div> -->
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
                    <table class="table table-bordered table-responsive" id="common_datatable_history">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('user_name');?></th>
                                <th><?php echo lang('email');?></th>
                                <th><?php echo lang('contact_number');?></th>
                                <th><?php echo lang('place');?></th>
                                <th><?php echo lang('no_of_persons');?></th>
                                <th><?php echo lang('booking_details');?></th>
                                <th><?php echo lang('booking_date');?></th>
                                <th><?php echo lang('status');?></th>
                               <!--  <th><?php echo lang('action');?></th> -->
                               
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
                            <td><?php echo $rows['name'];?></td>
                            <td><?php echo $rows['email'];?></td>
                            <td><?php echo $rows['mobile']?></td>
                            <td><?php echo $rows['place']?></td>
                            <td><?php echo $rows['no_of_persons']?></td>
                             <td style="width:25%;"><?php
                             if(strlen($rows['comment'])>120){
                                  $content=$rows['comment'];
                                  echo mb_substr($rows['comment'],0,120,'UTF-8').'...<br>';
                                  ?>
                                  <a style="cursor:pointer" onclick="show_message('<?php echo base64_encode($content);?>')"><?php echo lang('view');?></a>
                                  <?php
                                }
                                else if(strlen($rows['comment'])>0){
                                  echo $rows['comment'];
                                }
                                
                            ?></td>
                           
                            <td><?php echo date(DEFAULT_DATE,strtotime($rows['booking_date']))?></td>
                           <td>
                             <?php if($rows['status']==1){
                                echo  '<p class="text-success">Confirm</p>';
                                 
                              }else if($rows['status']==2){

                                echo  '<p class="text-warning">Pending</p>';
                                  
                              }else{
                                 echo  '<p class="text-danger">Cancel</p>';
                                  
                                }?>
                                
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
