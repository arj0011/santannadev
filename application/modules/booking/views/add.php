<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>

  <div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('booking/booking_add') ?>" enctype="multipart/form-data">
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

                                        <select class="" name="user_id" id="user_id" style="width:100%">
                                            <option value=""><?php echo lang('select_user');?></option>
                                            <?php if(!empty($results)){foreach($results as $result){?>
                                              <option value="<?php echo $result->id;?>"><?php echo $result->name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('full_name');?></label>
                                    <div class="col-md-9">
                                      
                                        <input type="text" class="form-control" name="full_name" id="full_name" value="" placeholder="<?php echo lang('full_name');?>"/>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                         </div>  
                             <div class="col-md-12" >
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('contact_number');?></label>
                                    <div class="col-md-9">
                                       
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="" placeholder="<?php echo lang('contact_number');?>" />
                                       
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="col-md-6" >
                                
                                    <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('email');?></label>
                                    <div class="col-md-9">
                                       <!--  <select class="form-control " id="email" name="email">
                                         </select> -->
                                        <input type="text" class="form-control" name="email" id="email" placeholder="<?php echo lang('email');?>"/>
                                       
                                    </div>
                                     
                                </div>
                            </div>
                          </div>
                             <div class="col-md-12" >
                              <div class="col-md-6" >
                              <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('place');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="place" id="place" placeholder="<?php echo lang('place');?>" />
                                    </div>
                                    
                                </div>
                            </div>
                            
                          <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('booking_details');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="booking_details" id="booking_details" placeholder="<?php echo lang('booking_details');?>"></textarea>
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
                                       <option value=""><?php echo lang('select_no_of_person');?></option>
                                    <?php 
                                    for($i=1; $i<=50; $i++)
                                    {
                                        ?>
                                         <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                         
                                   <?php }

                                    ?> 
                                     <option value="50+">50+</option>
                                          
                                    </select> 

                                    </div>
                                </div>
                            </div>   
                            
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('reservation_date');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="reservation_date" id="reservation_date" readonly="" placeholder="<?php echo lang('reservation_date');?>"/>
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
                                            <option value=""><?php echo lang('select_agent');?></option>
                                            <?php if(!empty($agents)){foreach($agents as $result){?>
                                              <option value="<?php echo $result->id;?>"><?php echo $result->full_name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                           </div>
 
                         
                                 <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger"><?php echo lang('reset_btn');?></button>
                    <button type="submit"  class="btn btn-primary" id="submit"><?php echo lang('submit_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>

     $("#reservation_date").datetimepicker({
                   todayBtn: "linked",
                    keyboardNavigation: false,
                    forceParse: false,
                    calendarWeeks: true,
                    autoclose: true,
                   
                    startDate: '-0m'
        
       
      });
      
   
</script>
<script type="text/javascript">
  $('#user_id').select2();
</script>