<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <?php
                $user_id = $this->uri->segment(3);
                if (empty($user_id)) {
                    ?>
                    <form class="well" id="date_sortinng" action="<?php echo base_url() . 'tablebooking/exportExcelBookingByDate'; ?>" method="post">

                        <div class="row">
                            <div class="clearfix ">

                                <div class="col-sm-3">
                                    <input class="form-control" type="text" name="start_date" id="start_date" value="<?php echo $booking['start_date']; ?>" placeholder="<?php echo lang('from_date'); ?>" readonly="readonly"></input>
                                </div>
                                <div class="col-sm-3">
                                    <input class="form-control" type="text" name="end_date" id="end_date" value="<?php echo $booking['end_date']; ?>" placeholder="<?php echo lang('to_date'); ?>" readonly="readonly"></input>
                                </div>
                                <div class="col-sm-3">
                                    <select id="floor" name="floor_id_search" size="1" class="form-control">
                                        <option value=""><?php echo lang('select_location'); ?></option>    
                                        <?php
                                        if (!empty($floors)):
                                            foreach ($floors as $rows):
                                                ?>
                                                <option value="<?php echo $rows->id; ?>"><?php echo $rows->name; ?></option>
                                            <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="button"  class="btn btn-primary" value="<?php echo lang('submit_btn'); ?>" name="submit" id="submit">
                                    <input type="submit"  class="btn btn-warning" value="Export" name="export"/>

                                </div>
                            </div>
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
                                <input type="button"  class="btn btn-primary" value="Submit" name="submit" id="submit">


                            </div>
                        </div>
                    </form>

<?php } ?>
                <div class="ibox-content">
                    <div class="row">
                        <?php
                        $message = $this->session->flashdata('success');
                        if (!empty($message)):
                            ?><div class="alert alert-success">
                            <?php echo $message; ?></div><?php endif; ?>
                        <?php
                        $error = $this->session->flashdata('error');
                        if (!empty($error)):
                            ?><div class="alert alert-danger">
    <?php echo $error; ?></div><?php endif; ?>
                        <div id="message"></div>
                        <div class="clearfix">
<!--                                <div class="col-md-6"><select name="limitOffset" id="limitOffset">
                                       <option value="0">0</option>
                                       <option value="10">10</option>
                                       <option value="20">20</option>
                                       <option value="50">50</option>
                                       <option value="100">100</option>
                                       <option value="250">250</option>
                                       <option value="500">500</option>
                                   </select></div>-->
                            <!--                            <div class="col-md-7">
                                                            <a class="btn btn-info btn-sm" href="<?php echo base_url() . 'admin/exportCsvBooking/all' ?>">
                                                                <span>All Export CSV</span>
                                                            </a>
                                                            <a class="btn btn-info btn-sm" href="<?php echo base_url() . 'admin/exportCsvBooking/today' ?>">
                                                                <span>Today Export CSV</span>
                                                            </a>
                                                            <a class="btn btn-warning btn-sm" href="<?php echo base_url() . 'admin/exportExcelBooking/all' ?>">
                                                                <span>All Excel</span>
                                                            </a>
                                                            <a class="btn btn-warning btn-sm" href="<?php echo base_url() . 'admin/exportExcelBooking/today' ?>">
                                                                <span>Today Export Excel</span>
                                                            </a>
                                                        </div>-->
                            <div class="col-md-3 col-md-offset-9">
                                <input type="text" placeholder="search" id="search" name="search" class="form-control"/>
                            </div> 

                        </div>

                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-responsive display nowrap" id="common_datatable_booking_inner">
                                    <thead>
                                        <tr>
     <!--                                       <th><?php echo lang('serial_no'); ?></th>-->
                                            <th><?php echo lang('client'); ?></th>
                                            <th><?php echo lang('action'); ?></th>
                                            <th><?php echo lang('status'); ?></th> 
                                            <th><?php echo lang('confirmation'); ?></th>
                                            <th><?php echo lang('section'); ?></th>
                                            <th><?php echo lang('no_of_persons'); ?></th>
                                            <th><?php echo lang('booking_date'); ?></th>
                                            <th><?php echo lang('start_time'); ?></th>
                                            <th><?php echo lang('end_time'); ?></th>
<!--                                        <th><?php echo lang('special_request'); ?></th>-->
                                            <th><?php echo lang('referrer'); ?></th> 
                                            <th><?php echo lang('comment'); ?></th>
                                            <th><?php echo lang('email'); ?></th>
                                            <th><?php echo lang('contact_number'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
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
