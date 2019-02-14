<style>
    html,
    body,
    timeline {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
    }
    #sp-board .sp-table,
    .tableplan .sp-table {
        display: block;
        position: absolute;
        z-index: 9;
    }
</style>
<div class="wrapper wrapper-content">

    <?php if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'): ?>
<!--        <h3><?php //echo lang('welcome'); ?> <?php //echo SITE_NAME; ?></h3>-->
    <?php else: ?>
        <h3><?php echo lang('welcome'); ?> <span class='text-info'>
                <?php echo $this->session->userdata('full_name') . " <span class='text-success'>(" . $this->session->userdata('role_name') . ")<span>"; ?></span></h3>
    <?php endif; ?>
    <div class="row">
        <?php if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'): ?>
            <!--                <div class="col-md-3">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <span class="label label-success pull-right"><?php echo lang('total'); ?></span>
                                        <h5><?php echo lang('client'); ?></h5>
                                    </div>
                                    <div class="ibox-content">
                                        <h3 class="no-margins"><?php //echo getAllCount(USERS);  ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <span class="label label-primary pull-right"><?php echo lang('total'); ?></span>
                                        <h5><?php echo lang('booking'); ?></h5>
                                    </div>
                                    <div class="ibox-content">
                                        <h3 class="no-margins"><?php //echo getAllCount('mw_booking');  ?></h3>
                                    </div>
                                </div>
                            </div>-->
        <?php endif; ?>

        <!-- <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right"><?php echo lang('login'); ?></span>
                    <h5><?php echo lang('last_login'); ?></h5>
                </div>
                <div class="ibox-content">
                    <h3 class="no-margins"><?php echo convertDateTime(date('Y-m-d H:i:s', $this->session->userdata("last_login"))); ?></h3>
                </div>
            </div>
        </div> -->


        <!--        <div class="col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right"><?php echo lang('total'); ?></span>
                            <h5>Today Bookings</h5>
                        </div>
                        <div class="ibox-content">
                            <h3 class="no-margins">
        <?php //if ($this->session->userdata('role') == 'admin'): ?>
        <?php //echo getAllCount('mw_booking', array('booking_date' => date('Y-m-d'))); ?>
        <?php
        //else:
        //  echo getAllCount('mw_booking', array('booking_date' => date('Y-m-d'), 'agent_id' => $this->session->userdata('id')));
        // endif;
        ?>
                                </h3>
                            </div>
                        </div>
                    </div>-->

        <?php if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'): ?>
            <!--                <div class="col-md-3">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <span class="label label-success pull-right"><?php echo lang('total'); ?></span>
                                        <h5><?php echo lang('agents'); ?></h5>
                                    </div>
                                    <div class="ibox-content">
                                        <h3 class="no-margins"><?php //echo getAllCount(AGENTS);  ?></h3>
                                    </div>
                                </div>
                            </div>-->
        <?php endif; ?>  
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox float-e-margins">

                    <div class="ibox-title">
                        <h5><?php echo lang('booking_history'); ?></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>

                    <div class="ibox-content">
                        <div class="row">
                            <?php if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'): ?> 
                                <!--                                    <div class="col-md-12">
                                                                        <div class="panel panel-primary">
                                                                            <div class="panel-heading"><i class="fa fa-envelope"></i> Floor Manager Notification</div>
                                                                            <div class="panel-body">
                                                                                <form role="form" action="<?php echo base_url('admin/dashboard'); ?>" name="sendNotification" method="post">
                                                                                    <div class="col-md-4 form-group">  
                                                                                        <select id="floor" name="floor_id" class="form-control">
                                                                                            <option value=""><?php echo "Select Section"; ?></option>    
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
                                <?php echo form_error('floor_id'); ?>
                                                                                            <input type="checkbox" name="allsection" value="all"/> All Sections
                                                                                        </div>
                                                                                        <div class="col-md-6 form-group">
                                                                                            <textarea name="message" class="form-control" placeholder="What's on your mind?"><?php echo set_value('message'); ?></textarea>
                                <?php echo form_error('message'); ?>
                                                                                        </div>  
                                                                                        <div class="col-md-2 form-group">
                                                                                            <button type="submit" name="send" class="btn btn-primary">SEND</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                
                                                                            </div>
                                
                                                                        </div>-->
                            <?php endif; ?>  
                            <?php
                            $message = $this->session->flashdata('success');
                            if (!empty($message)):
                                ?><div class="alert alert-success">
                                    <?php //echo $message; ?></div><?php endif; ?>
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
                                <div class="col-md-9">
<!--                                    <a class="btn btn-info btn-sm" href="<?php echo base_url() . 'admin/exportCsvBooking/all' ?>">
                                    <span>All Export CSV</span>
                                </a>
                                <a class="btn btn-info btn-sm" href="<?php echo base_url() . 'admin/exportCsvBooking/today' ?>">
                                    <span>Today Export CSV</span>
                                </a>-->
                    
<!--                                    <a class="btn btn-warning btn-sm" href="<?php echo base_url() . 'admin/exportExcelBooking/all' ?>">
                                        <span>All Excel</span>
                                    </a>
                                    <a class="btn btn-warning btn-sm" href="<?php echo base_url() . 'admin/exportExcelBooking/today' ?>">
                                        <span>Today Export Excel</span>
                                    </a>-->
                                    <?php $floorId= (!empty($floors)) ? (isset($floors[0]->id)) ? $floors[0]->id : 0 : 0;?>
                                  <a class="btn btn-sm btn-primary"  href="<?php echo base_url(); ?>admin/current_booking"><?php echo lang('walk_in'); ?></a>
<!--                                    <a class="btn btn-sm btn-success"  href="<?php echo base_url(); ?>tablebooking"><?php echo lang('booking'); ?></a>-->
                                    <a class="btn btn-sm btn-danger"  href="<?php echo base_url(); ?>tablebooking?date=<?php echo date('d/m/Y'); ?>&floor=<?php echo $floorId; ?>"><?php echo lang('booking_plan'); ?></a>
                                    <a class="btn btn-sm btn-info"  href="<?php echo base_url(); ?>transferBooking"><?php echo lang('location_plan'); ?></a>
<!--                                    <a class="btn btn-sm btn-info"  href="<?php echo base_url(); ?>transferBooking">Transfer Booking</a>-->
                                  

                                </div>
<!--                                <div class="col-md-3">
                                    <input type="text" placeholder="select Month" id="fiterByMonth" name="fiterByMonth" class="form-control"/>
                                </div>-->
                                    <div class="col-md-3">
                                    <input type="text" placeholder="search" id="search" name="search" class="form-control"/>
                                </div>
                              
                            </div>
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-responsive display nowrap" id="common_datatable_booking_dashboard">
                                        <thead>
                                            <tr>
                                                <!--                                            <th><?php echo lang('serial_no'); ?></th>-->
                                                <th><?php echo lang('client'); ?></th>
                                                <th><?php echo lang('action'); ?></th>
                                                <th><?php echo lang('status'); ?></th> 
                                                <th><?php echo lang('confirmation'); ?></th>
                                                <th><?php echo lang('section'); ?></th>
                                                <th><?php echo lang('no_of_persons'); ?></th>
                                                <th><?php echo lang('booking_date'); ?></th>
                                                <th><?php echo lang('start_time'); ?></th>
                                                <th><?php echo lang('end_time'); ?></th>
                                                <!--                                            <th><?php echo lang('special_request'); ?></th>-->
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

                    <div class="ibox-content">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="panel panel-primary">
                                    <div class="panel-heading"><?php echo lang('walk_in'); ?></div>
                                    <div class="clearfix"></div>
                                    <br>
                                    <!--                                    <div class="col-md-3 col-md-offset-9">
                                                                        <input type="text" placeholder="search" id="search_walkin" name="search_walkin" class="form-control"/>
                                                                    </div> -->
                                    <div class="panel-body">  

                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-responsive display nowrap" id="common_datatable_current_booking_dashboard">
                                                    <thead>
                                                        <tr>
                                                            <!--                                            <th><?php echo lang('serial_no'); ?></th>-->
                                                            <th><?php echo lang('client'); ?></th>
                                                            <th><?php echo lang('action'); ?></th>
                                                            <th><?php echo lang('status'); ?></th> 
                                                            <th><?php echo lang('confirmation'); ?></th>
                                                            <th><?php echo lang('section'); ?></th>
                                                            <th><?php echo lang('no_of_persons'); ?></th>
                                                            <th><?php echo lang('booking_date'); ?></th>
                                                            <th><?php echo lang('start_time'); ?></th>
                                                            <th><?php echo lang('end_time'); ?></th>
                                                            <!--                                            <th><?php echo lang('special_request'); ?></th>-->
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> 

    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript">
        $('body').on('change', '#floordrop_down', function () {
            var floor_id = $('#floordrop_down option:selected').val();
            var date = $('#my-datepicker').val();
            window.location.href = 'dashboard?floorplan=' + floor_id;
        });

        function rmtool(i) {
            $("#tool" + i).css('display', 'none');

        }
        $('.sp-table').click(function () {

            $(this).find('.infotooltip').show();

            $('.ttClose').click(function () {
                $(this).parent('div').hide();
            });
        });
    </script>



