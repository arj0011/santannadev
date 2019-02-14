<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('booking/booking_view') ?>" enctype="multipart/form-data">
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
                                 <div class="col-md-6" >
                                 <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_name');?> :</label>
                                    <div class="col-md-9">
                                        <?php echo $result->name;?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                             <div class="form-group">
                               <label class="col-md-3 control-label"><?php echo lang('full_name');?> :</label>
                                    <div class="col-md-9">
                                         <?php echo $result->full_name;?>
                                    </div>
                                    
                                </div>
                              </div>  
                            </div>

                             <div class="col-md-12" >
                              <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_number');?> :</label>
                                    <div class="col-md-9">
                                       <?php echo $result->phone_number;?>
                                    </div>
                                </div>
                            </div>
                           
                             <div class="col-md-6" >
                                
                                 <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('email');?> :</label>
                                    <div class="col-md-9">
                                         <?php echo $result->email;?>
                                    </div>
                                </div>
                            </div>
                           </div> 
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                     <label class="col-md-3 control-label"><?php echo lang('place');?> :</label>
                                    <div class="col-md-9">
                                       <?php echo $result->place;?>
                                    </div>
                                </div>
                                 
                            </div>
                           
                             <div class="col-md-6" >
                                <div class="form-group">
                                     <label class="col-md-3 control-label"><?php echo lang('booking_details');?> :</label>
                                    <div class="col-md-9">
                                        <?php echo $result->booking_details;?>
                                    </div>
                                </div>
                            </div>
                           </div>
                           
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('no_of_persons');?> :</label>
                                    <div class="col-md-9">
                                       <?php echo $result->no_of_persons;?>
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('reservation_date');?>:</label>
                                    <div class="col-md-9">
                                       <?php echo convertDateTime($result->reservation_date);?>
                                    </div>
                                </div>
                            </div>
                           </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('status');?>:</label>
                                    <div class="col-md-9">
                                       <?php if($result->status==1){
                                        echo "confirm" ;
                                        }else if($result->status==2){
                                        echo "pending" ;
                                         }else{
                                            echo "cancel";
                                            }?>
                                    </div>
                                </div>
                            </div>
                           
                             
                
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                   
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

