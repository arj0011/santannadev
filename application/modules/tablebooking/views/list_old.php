<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : "" ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('home'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('tablebooking/view_booking'); ?>"><?php echo lang('booking'); ?></a>
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
                     <div class="btn-group">
                        <a href="javascript:void(0)"  onclick="open_modal('booking')" class="btn btn-primary">
                <?php echo lang('add_booking'); ?>
                        <i class="fa fa-plus"></i>
                        </a>
                       
                    </div> 
                   
                </div> -->
                <?php $user_id = $this->uri->segment(3);
                if (empty($user_id)) {
                    ?>
                    <form class="well" id="date_sortinng" action="<?php echo base_url('tablebooking/view_booking'); ?>" method="post">

                        <div class="row">
                            <div class="form-group clearfix ">

                                <div class="col-lg-3">
                                    <input class="form-control" type="text" name="start_date" id="start_date" value="<?php echo $booking['start_date']; ?>" placeholder="<?php echo lang('from_date'); ?>" readonly="readonly"></input>
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-control" type="text" name="end_date" id="end_date" value="<?php echo $booking['end_date']; ?>" placeholder="<?php echo lang('to_date'); ?>" readonly="readonly"></input>
                                </div>
                                <div class="col-lg-3">
                                    <select id="floor" name="floor_id_search" size="1" class="form-control">
                                        <option value=""><?php echo lang('select_location'); ?></option>    
                                        <?php
                                        if (!empty($floors)):
                                            foreach ($floors as $rows):
                                                ?>
                                                <option value="<?php echo $rows->id; ?>"><?php echo $rows->name; ?></option>
        <?php endforeach;
    endif; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <input type="submit"  class="btn btn-primary" type="submit" value="<?php echo lang('submit_btn'); ?>" name="submit" id="submit">


                                </div>
                            </div>
                        </div>
                        <div class="form-group clearfix ">

                        </div>
                    </form>
<?php }else { ?>

                    <form class="well" id="date_sortinng" action="<?php echo base_url('tablebooking/view_booking/') . $user_id; ?>" method="post">

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
                    </form>

                        <?php } ?>
                <div class="ibox-content">
                    <div class="row">
                        <?php $message = $this->session->flashdata('success');
                        if (!empty($message)):
                            ?><div class="alert alert-success">
    <?php echo $message; ?></div><?php endif; ?>
<?php $error = $this->session->flashdata('error');
if (!empty($error)):
    ?><div class="alert alert-danger">
    <?php echo $error; ?></div><?php endif; ?>
                        <div id="message"></div>
                        <div class="col-lg-12" style="overflow-x: auto">
                            <table class="table table-bordered table-responsive display nowrap" id="common_datatable_booking">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('serial_no'); ?></th>
                                        <th><?php echo lang('action'); ?></th>
                                        <th><?php echo lang('status'); ?></th> 
                                        <th style="width:5%"><?php echo lang('confirmation'); ?></th>
                                        <th><?php echo lang('user_name'); ?></th>

                                        <th><?php echo lang('location'); ?></th>
                                        <th><?php echo lang('no_of_persons'); ?></th>
        <!--                                <th><?php //echo lang('booking_details'); ?></th>-->
                                        <th><?php echo lang('booking_date'); ?></th>
                                        <th><?php echo lang('start_time'); ?></th>
                                        <th><?php echo lang('end_time'); ?></th>
                                        <th><?php echo lang('comment'); ?></th> 
                                        <th><?php echo lang('referrer'); ?></th> 

                                        <th style="width:15%"><?php echo lang('email'); ?></th>
                                        <th><?php echo lang('contact_number'); ?></th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($list) && !empty($list)):
                                        $rowCount = 0;

                                        foreach ($list as $rows):
                                            $class = '';
                                            $rowCount++;

                                            if ($rows['status'] == 1) {
                                                $class = "style='color:#0080ff'";
                                            }
                                            ?>

                                            <tr <?php echo $class; ?>>
                                                <td><?php echo $rowCount; ?></td>  
                                                <td class="actions">
                                                    <a href="javascript:void(0)" class="on-default edit-row" onclick="redirectFn('tablebooking', 'index', '<?php echo encoding($rows['id']) ?>');"><img width="20" src="<?php echo base_url() . EDIT_ICON; ?>" /></a>

                                                    <br>
                                                    <a href="javascript:void(0)" class="on-default edit-row" onclick="viewFn('tablebooking', 'open_model', '<?php echo encoding($rows['id']) ?>');"><img width="20" src="<?php echo base_url() . VIEW_ICON; ?>" /></a>
                                                    <br>
                                                    <a href="javascript:void(0)" onclick="delBooking('<?php echo encoding($rows['id']); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url() . DELETE_ICON; ?>" /></a>


                                                </td>
                                                <td>  
                                                    <select id="status" name="status" size="1" class="form-control" onchange="bookingStatusAuth('<?php echo encoding($rows['id']) ?>', this)">        
                                                        <option value="1" <?php if (!empty($rows['status'])) {
                                                if ($rows['status'] == 1) echo "selected";
                                            } ?>><?php echo lang('Confirm'); ?></option>
                                                        <option value="2" <?php if (!empty($rows['status'])) {
                                                if ($rows['status'] == 2) echo "selected";
                                            } ?>><?php echo lang('pending'); ?></option>
                                                        <option value="3" <?php if (!empty($rows['status'])) {
                                                if ($rows['status'] == 3) echo "selected";
                                            } ?>><?php echo lang('cancel'); ?></option>
                                                        <option value="4" <?php if (!empty($rows['status'])) {
                                                if ($rows['status'] == 4) echo "selected";
                                            } ?>><?php echo "Arrived"; ?></option>
                                                        <option value="5" <?php if (!empty($rows['status'])) {
                                                if ($rows['status'] == 5) echo "selected";
                                            } ?>><?php echo "No Show"; ?></option>
                                                    </select>
                                                </td> 
                                                <td>  <?php
                                            if ($rows['status'] == 1) {
                                                echo "Confirm";
                                            } else if ($rows['status'] == 2) {
                                                ?>
                                                        <a href="javascript:void(0)" class="btn btn-md btn-primary" onclick="redirectFn('tablebooking', 'index', '<?php echo encoding($rows['id']) ?>');"><i class= "fa fa-arrow-circle-right fa-lg"></i></a>

        <?php } else { ?>
                                                        <a href="javascript:void(0)" class="btn btn-md btn-primary" onclick="redirectFn('tablebooking', 'index', '<?php echo encoding($rows['id']) ?>');"><i class= "fa fa-arrow-circle-right fa-lg"></i></a>

                                                <?php }
                                                ?></td>
                                                <td><?php echo $rows['name']; ?></td>

                                                <td><?php
                                                $option = array('table' => 'mw_rooms', 'where' => array('id' => $rows['floor_id']), 'single' => true);
                                                $floors = $this->Common_model->customGet($option);

                                                echo (!empty($floors)) ? $floors->name : "";
                                                ?></td>
                                                <td><?php echo $rows['no_of_persons'] ?></td>
                    <!--                             <td style="width:25%;"><?php
//                             if(strlen($rows['comment'])>120){
//                                  $content=$rows['comment'];
//                                  echo mb_substr($rows['comment'],0,120,'UTF-8').'...<br>';
//                                  
                                        ?>
                                                      <a style="cursor:pointer" onclick="show_message('//<?php //echo base64_encode($content); ?>')"><?php //echo lang('view'); ?></a>
                                                      //<?php
//                                }
//                                else if(strlen($rows['comment'])>0){
//                                  echo $rows['comment'];
//                                }
                                        ?></td>-->

         <!-- <td><?php echo convertDateTime($rows['booking_date']) ?></td> -->
                                                <td><?php echo date(DEFAULT_DATE, strtotime($rows['booking_date'])) ?></td>
                                                <td><?php echo date('H:i', strtotime($rows['time_from'])); ?></td>
                                                <td><?php echo date('H:i', strtotime($rows['time_to'])); ?></td>
                                                <td><?php echo ucfirst($rows['comment']); ?></td>
                                                <td><?php echo ucwords($rows['referrer']); ?></td>



                                                <td><?php echo $rows['email']; ?></td>
                                                <td><?php echo $rows['mobile'] ?></td>



                                            </tr>
    <?php endforeach;
endif; ?>
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
        <span id="close_button"><img src="<?php echo base_url(); ?>assets/img/close.png" onclick="close_message();"></span>
        <div id="message_container"></div>
    </div>
    <script>
        if (typeof (Storage) !== "undefined") {
            if (localStorage.getItem("editFromURL") !== null) {
                url = localStorage.getItem("editFromURL");
                localStorage.removeItem("editFromURL");
                window.location.href = url;
            }
        } else {

        }
    </script>
