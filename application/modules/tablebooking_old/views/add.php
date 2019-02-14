<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('tablebooking/booking_add') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                </div>
                <div class="modal-body">
                    <div class="loaders">
                        <img src="<?php echo base_url().'assets/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Booking Date</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="booking_date" id="booking_date1" value="<?php echo date('d F Y',strtotime($booking_data['booking_date']));?>" readonly />
                                    </div>
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Time From</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="" value="<?php echo $booking_data['time_from'];?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Time To</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="time_to" value="<?php echo $booking_data['time_to'];?>" readonly>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Full Name</label>
                                    <div class="col-md-9">
                                        <input class="form-control" value="<?php echo $booking_data['name'];?>" type="text" name="name" id="name" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                       <input class="form-control" type="text" name="email" id="email" value="<?php echo $booking_data['email'];?>" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Phone Number</label>
                                    <div class="col-md-9">
                                        <input class="form-control" value="<?php echo $booking_data['mobile'];?>" type="text" name="mobile" id="mobile" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Place</label>
                                    <div class="col-md-9">
                                        <input class="form-control" value="<?php echo $booking_data['place'];?>" type="text" name="place" id="place" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Agent</label>
                                    <div class="col-md-9">
                                        <input class="form-control" value="<?php echo $booking_data['agentName'];?>" type="text" name="agent" id="agent" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Location</label>
                                    <div class="col-md-9">
                                        <input class="form-control" value="<?php echo $booking_data['roomName'];?>" type="text" name="floor" id="floor" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Table</label>
                                    <div class="col-md-9">
                                        <input class="form-control" value="<?php echo $booking_data['tableName'];?>" type="text" name="table" id="table" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Booking Details</label>
                                    <div class="col-md-9">
                                       <textarea class="form-control" name="comment" id="comment" readonly><?php echo $booking_data['comment'];?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('close_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    $("#booking_date").datepicker({format:'dd MM yyyy',todayHighlight:true,startDate: '-0d'});
</script>