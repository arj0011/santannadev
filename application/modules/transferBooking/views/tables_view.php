<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('transferBooking/moveNow') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Transfer Booking Window <span class="text-success">(<?php echo date('d F Y')?>)</span></h4>
                </div>
                <div class="modal-body">
                    <div class="loaders">
                        <img src="<?php echo base_url().'assets/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
<!--                            <div class="col-md-12">Booking Details</div>-->
                             <div class="col-md-12">
                                 <div class="col-md-6"><b>Section : </b><?php echo $bookingDetails->roomName;?></div>
                                 <div class="col-md-6"><b>Booked Table : </b><?php echo $bookingDetails->name.' ('.$bookingDetails->seats.' Seats)';?></div>
                                 <div class="col-md-6"><b>No Of Persons : </b><?php echo $bookingDetails->no_of_persons;?></div>
                                 <div class="col-md-6"><b>Client : </b><?php echo $bookingDetails->personName;?></div>
                                 <div class="col-md-6"><b>From : </b><?php echo $bookingDetails->StartTime;?></div>
                                 <div class="col-md-6"><b>To : </b><?php echo $bookingDetails->EndTime;?></div>
                             </div>
                          <div class="clearfix"></div>
                          <hr>
                          <div class="col-md-12" >
                              
                                    <div class="form-group">
                                    <div id="dvUserSelect">
                                    <label class="col-md-3 control-label">
                                    <?php echo $bookingDetails->name.' ('.$bookingDetails->seats.' Seats)';?></label>
                                        <label class="col-md-1 control-label"><i class="fa fa-arrow-circle-o-right"></i></label>
                                    </div>
                                        <div id="dvUserSelect">
                                    <label class="col-md-2 control-label">
                                        Move To</label>
                                    <div class="col-md-6">
                                         <select class="formcontrol" multiple="" name="tables[]" id="tables" placeholder="Select Multiple Tables" style="width:100%">
                                             <?php if(!empty($tables)){foreach($tables as $rows){?>
                                             <option value="<?php echo $rows->id;?>"><?php echo $rows->name.' ('.$rows->seats.' Seats)'?></option>
                                             <?php }}?>
                                         </select>
                                       </div>
                                    </div>
                                 </div>
                                </div>
                            <div class="space-22"></div>
                            <input type="hidden" name="bookingId" value="<?php echo $bookingId;?>" />
                            <input type="hidden" name="roomId" value="<?php echo $roomId;?>" />
                            <input type="hidden" name="tableId" value="<?php echo $bookingDetails->id;?>" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="submit"  class="btn btn-primary" id="submit" >Move Now</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
  $('#tables').select2();
</script>