<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">

                <div class="ibox-content">
                    <div class="row">

                        <div id="message"></div>
                        <div class="col-lg-12" style="overflow-x: auto">
                            <table class="table table-bordered table-responsive display nowrap" id="common_datatable_booking">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('serial_no'); ?></th>
                                        <th style="width:5%"><?php echo lang('status'); ?></th>
                                        <th>Remove Reason</th>
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

                                            <tr class="even" style="color:#0080ff" role="row">
                                                <td><?php echo $rowCount; ?></td>  
                                  
                                                <td>  <?php
                                            if ($rows['status'] == 1) {
                                                echo "Confirm";
                                            } else if ($rows['status'] == 2) {
                                                echo "Pending";
                                             }else if ($rows['status'] == 3) {
                                                echo "Cancel";
                                             }else if ($rows['status'] == 4) {
                                                echo "Arrived";
                                             }else if ($rows['status'] == 5) {
                                                echo "No Show";
                                             } ?>
                                                      

                                                <?php 
                                                ?></td>
                                                <td><?php echo $rows['delete_comment']; ?></td>
                                                <td><?php echo $rows['name']; ?></td>

                                                <td><?php
                                                $option = array('table' => 'mw_rooms', 'where' => array('id' => $rows['floor_id']), 'single' => true);
                                                $floors = $this->Common_model->customGet($option);

                                                echo $floors->name;
                                                ?></td>
                                                <td><?php echo $rows['no_of_persons'] ?></td>
                    <!--                             <td style="width:25%;"><?php
//                             if(strlen($rows['comment'])>120){
//                                  $content=$rows['comment'];
//                                  echo mb_substr($rows['comment'],0,120,'UTF-8').'...<br>';
//                                  
                                        ?>
                                                      <a style="cursor:pointer" onclick="show_message('//<?php echo base64_encode($content); ?>')"><?php echo lang('view'); ?></a>
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
