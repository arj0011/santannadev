<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('booking/booking_update') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label"><?php echo lang('user_name');?></label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="user_id" id="user_id">
                                            <option value="">Select Users</option>
                                            <?php if(!empty($users)){foreach($users as $result){?>
                                              <option <?php if($results->user_id==$result->id) echo "selected";?> value="<?php echo $result->id;?>"><?php echo $result->name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                             <div class="form-group">
                               <label class="col-md-3 control-label"><?php echo lang('full_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="full_name" id="full_name" placeholder="<?php echo lang('full_name');?>" value="<?php echo $results->full_name;?>"/>
                                    </div>
                                    
                                </div>
                              </div>  
                            </div>

                             <div class="col-md-12" >
                              <div class="col-md-6" >
                               <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_number');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="<?php echo lang('contact_number');?>" value="<?php echo $results->phone_number;?>"/>
                                    </div>
                                </div>
                            </div>
                           
                             <div class="col-md-6" >
                                
                                 <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('email');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="email" id="email" placeholder="<?php echo lang('email');?>" value="<?php echo $results->email;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div> 
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                     <label class="col-md-3 control-label"><?php echo lang('place');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="place" id="place" placeholder="<?php echo lang('place');?>" value="<?php echo $results->place;?>"/>
                                    </div>
                                </div>
                                 
                            </div>
                           
                             <div class="col-md-6" >
                                <div class="form-group">
                                     <label class="col-md-3 control-label"><?php echo lang('booking_details');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="booking_details" id="booking_details" placeholder="<?php echo lang('booking_details');?>"><?php echo $results->booking_details;?></textarea>
                                    </div>
                                </div>
                            </div>
                           </div>
                           
                            <div class="col-md-12" >
                             <div class="col-md-6" >
                             <div class="form-group">
                                   <label class="col-md-3 control-label"><?php echo lang('no_of_persons');?></label>
                                    <div class="col-md-9">
                                       <select class="form-control" name="no_of_persons" id="no_of_persons">
                                       <option value="">Select no of Persons</option>
                                    <?php 
                                    for($i=1; $i<=50; $i++)
                                    {
                                        ?>
                                         <option  <?php if($results->no_of_persons== $i){ echo "selected";}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                                         
                                   <?php }
                                    ?> 
                                           <option <?php if($results->no_of_persons== '50+'){ echo "selected";}?> value="50+">50+</option>
                                    </select> 
                                </div>
                            </div>
                           </div>
                               <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('reservation_date');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="reservation_date" id="reservation_date" placeholder="<?php echo lang('reservation_date');?>" readonly="" value="<?php echo $results->reservation_date;?>"/>
                                    </div>
                                </div>
                            </div>
                           </div>

                           <div class="col-md-12" >
                                 <div class="col-md-6" >
                                 <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('agent');?></label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="agent_id" id="agent_id">
                                            <option value="">Select Agent</option>
                                            <?php if(!empty($agents)){foreach($agents as $result){?>
                                              <option <?php if($results->agent_id==$result->id) echo "selected";?> value="<?php echo $result->id;?>"><?php echo $result->full_name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                             <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="btn btn-primary" >Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>

    $("#reservation_date").datetimepicker({
        format: 'yyyy-mm-dd hh:ii'
      });
      
</script>