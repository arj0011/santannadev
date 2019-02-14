<style> 
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
.datepicker{z-index:9999 !important;}
</style> 
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('loyalty/loyalty_update') ?>" enctype="multipart/form-data">
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
                         <!--     <div class="col-md-12" >
                            <div class="col-md-6" >
                                <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang('store_name');?></label>
                                    <div class="col-md-9">
                                         <select class="form-control" name="store_name" id="store_name">
                                            <option value="">Select Service</option>
                                            <?php if(!empty($results)){foreach($results as $result){?>
                                              <option value="<?php echo $result->store_id;?>"><?php echo $result->store_name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                   
                                </div>
                            </div>-->

                            <!-- <div class="col-md-12" >
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_name');?></label>
                                    <div class="col-md-9">

                                        <select class="formcontrol" multiple="" name="user_id[]" id="user_id" style="width:100%">
                                            <option value="">Select Users</option>
                                            <?php echo "<pre>";print_r($loyalty);
                                            if(!empty($loyalty)){foreach($loyalty as $result){?>
                                               <option <?php if($result->id==$results->user_id) echo "selected";?> value="<?php echo $result->id;?>"><?php echo $result->name;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            
                            <div class="col-md-12" >
                              <div class="col-md-6" >
                                <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang('title_en');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title_en" id="title_en" value="<?php echo $results->title_en;?>"/>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                   
                                </div>
                            </div>

                                <div class="col-md-6" >
                                <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang('title_el');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title_el" id="title_el" value="<?php echo $results->title_el;?>" />
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                   
                                </div>
                            </div>
                          </div>

                            <div class="col-md-12" >
                             <div class="col-md-6" >
                               <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang('description_en');?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="description_en" id="description_en"><?php echo $results->description_en;?></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span>
                                    
                                </div>
                            </div>

                              <div class="col-md-6" >
                                <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo lang('description_el');?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="description_el" id="description_el"><?php echo $results->description_el;?></textarea>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('greek_note');?></span>
                                   
                                   </div> 
                                </div>
                            </div>
                            
                              <div class="col-md-12" >
                               <div class="col-md-6" >
                                <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo lang('no_of_scane');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="no_of_scane" id="no_of_scane" value="<?php echo $results->no_of_scane;?>"  <?php echo ($is_edit == 1) ? "readonly": "";?>/>
                                    </div>
                                   
                                </div>
                            </div>
                            

                             <div class="col-md-6" >
                              <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('qr_code');?></label>
                                    <!-- <?php $characters = '1234567890';

                                    $string = '';

                                    for ($i = 0; $i < 6; $i++) {
                                    $string .= $characters[rand(0, strlen($characters) - 1)];
                                    }   
                                    ?> -->
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" readonly="" name="qr_code" id="qr_code" value="<?Php echo $results->qr_code;?>" />
                                    </div>
                                </div>
                            </div>
                         </div>
                        
                          <div class="col-md-12" >
                            <div class="col-md-6" >
                             <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('start_date');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="start_date" id="<?php echo ($is_edit == 1) ? "": "start_date";?>" readonly="" value="<?Php echo date(DEFAULT_DATE,strtotime($results->start_date));?>" />
                                    </div>
                              </div>
                           </div>
                            
                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('end_date');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="end_date" id="<?php echo ($is_edit == 1) ? "": "end_date";?>" readonly="" value="<?Php echo date(DEFAULT_DATE,strtotime($results->end_date));?>"/>
                                    </div>
                                </div>
                            </div>
                         </div>

                          <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                          <div class="space-22"><p class="text-danger"><?php if($is_edit == 1) {echo lang('loyalty_is_edit_message');}?><p></div>
                        </div>
                    </div>
                    </div>
              
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('close_btn');?></button>
                    <button type="submit" id="submit" class="btn btn-primary"><?php echo lang('update_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
$('input[name="no_of_scane"]').keyup(function(e)
                                {
  if (/\D/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
  }
});

$("#start_date").datepicker({ 
               todayBtn: "linked",
               format: 'dd/mm/yyyy',
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
               
                startDate: '-0m',
                //format: 'yyyy-mm-dd',
   });

$("#end_date").datepicker({
                startDate: '-0m',
                format: 'dd/mm/yyyy',
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                //format: 'yyyy-mm-dd',
                
                  });

</script>