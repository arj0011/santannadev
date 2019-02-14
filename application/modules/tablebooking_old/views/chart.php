<style>
    div.google-visualization-tooltip { /*transform: rotate(30deg);*/width:15%;height: 17%;line-height: 10px; }
</style>
<div class="container-fluid">

    <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
            </li>
            <li>
                <a href="<?php echo site_url('tablebooking');?>"><?php echo lang('booking');?></a>
            </li>
        </ol>
    </div>
    <div class="col-lg-2"> 

    </div>
    </div>

    <div class="row">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" id="home_1" href="#home"><?php echo lang('booking');?></a></li>
          <li><a data-toggle="tab" id="menu_2" href="#menu2"><?php echo lang('location_plan');?></a></li>
          <li><a data-toggle="tab" id="menu_1" href="#menu1"><?php echo lang('booking_plan');?></a></li>
        </ul>

        <div class="tab-content">
        
          <div id="home" class="tab-pane fade in active">
            <p></p>
            <form class="" role="form" id="addFormAjax" method="post" action="<?php echo base_url('tablebooking/booking_add') ?>" enctype="multipart/form-data">
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                    <div class="loaders">
                        <img src="<?php echo base_url().'assets/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                            <?php if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'):?>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="custom_chk chk_box">
                                        <div class="checkbox">
                                            <input type="radio" checked name="role" id="new_user" value="user">
                                            <label><?php echo lang('new_user');?></label>
                                        </div>
                                        <div class="checkbox">
                                            <input type="radio"  name="role" id="existing_user" value="organizer"> 
                                            <label><?php echo lang('existing_user');?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="col-md-4" id="userdropdownid" style="display: none;">
                                <div class="form-group">
                                    <!--<label class="col-md-3 control-label"><?php echo lang('user_name');?></label>-->
                                        <select class="form-control" name="user_id" id="user_id" style="width:100%">
                                            <option value=""><?php echo lang('select_user');?></option>
                                            <?php if(!empty($results)){foreach($results as $result){?>
                                              <option value="<?php echo $result->id;?>"><?php echo $result->name;?></option>
                                            <?php }}?>
                                        </select>
                                </div>
                            </div>

                            <div class="col-md-4" >
                                <div class="form-group">
                                        <input class="form-control" value="<?php if(!empty($requestData)){echo $requestData['name'];}?>" type="text" name="name" id="name" placeholder="<?php echo lang('full_name');?>"/>
                                </div>
                            </div>

                            <div class="col-md-4" >
                                <div class="form-group">
                                       <input class="form-control" value="<?php if(!empty($requestData)){echo $requestData['email'];}?>" type="text" name="email" id="email" placeholder="<?php echo lang('app_email');?>" />
                                </div>
                            </div>

                            <div class="col-md-4" >
                                <div class="form-group">
                                        <input class="form-control" type="text" value="<?php if(!empty($requestData)){echo $requestData['mobile'];}?>" name="mobile" id="mobile" placeholder="<?php echo lang('app_phone');?>" />
                                </div>
                            </div>
                            
                            <div class="col-md-4" >
                                <div class="form-group">
                                        <input type="text" class="form-control" placeholder="<?php echo lang('booking_date');?>" name="booking_date" id="booking_date" value="<?php if(!empty($requestData)){echo date('d F Y',strtotime($requestData['booking_date']));}?>" />
                                </div>
                            </div>
                            
                             <div class="col-md-4" >
                                <div class="form-group">
                                    <div class="input-group date form_time" data-date-format="hh:ii:ss" data-link-field="dtp_input1" data-link-format="hh:ii:ss">
                                        <input type="text" class="form-control" placeholder="<?php echo lang('select_time');?>" id="time1" name="time1" required readonly>

                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                    </div>
                                    <input type="hidden" name="time_from" placeholder="" id="dtp_input1" value="" />
                                    <label id="timecheck"></label>
                                </div>
                            </div>
                            
                            <div class="col-md-4" >
                                <div class="form-group">
                                        <div class="input-group date form_time" data-date-format="hh:ii:ss" data-link-field="dtp_input2" data-link-format="hh:ii:ss">
                                        <input type="text" name="time2" class="form-control" placeholder="<?php echo lang('select_time');?>" id="time2" required readonly>

                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                    </div>
                                    <input type="hidden" name="time_to" placeholder="" id="dtp_input2" value="" />
                                </div>
                            </div>
                            
                             <div class="col-md-4" >
                                <div class="form-group">
                                        <select class="form-control" name="no_of_persons" id="no_of_persons">
                                            <option value=""><?php echo lang('select_no_of_person');?></option>
                                    <?php 
                                    for($i=1; $i<=50; $i++){
                                        $select = '';
                                        if(!empty($requestData)):
                                            if($i == $requestData['no_of_persons']){
                                                $select = 'selected';  
                                            }
                                        endif;    
                                        ?>
                                            <option value="<?php echo $i;?>" <?php echo $select;?>><?php echo $i;?></option> 
                                   <?php 
                                        }
                                    ?> 
<option value="50+" <?php if(!empty($requestData)){if($requestData['no_of_persons'] == '50+'){echo 'selected';}}?>>50+</option>
                                        </select>
                                        <div id="seatcheck"></div>
                                </div>
                            </div>
                               
                            <div class="col-md-4" >
                                <div class="form-group">
                                        <select class="form-control" name="agent_id" id="agent_id">
                                            <option value=""><?php echo lang('select_agent');?></option>
                                            <?php if(!empty($agents)){foreach($agents as $result){?>
                                              <option value="<?php echo $result->id;?>"><?php echo $result->full_name;?></option>
                                            <?php }}?>
                                        </select>
                                </div>
                           </div>

                           <div class="col-md-4" >
                                <div class="form-group">
                                        <select id="status" name="status" size="1" class="form-control">
                              <?php if(!empty($requestData)){?>
                              <option value="1" <?php if(!empty($requestData)){if($requestData['status'] == 1) echo "selected";}?>>Confirm</option>
                              <?php }else{?>          
                              <option value="1"><?php echo lang('Confirm');?></option>
                              <option value="2"><?php echo lang('pending');?></option>
                              <option value="3"><?php echo lang('cancel');?></option>
                              <?php }?>
                                        </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                        <select id="floor" name="floor" size="1" class="form-control">
                                    <option value=""><?php echo lang('select_location');?></option>    
                              <?php if(!empty($allfloors)):
                                    foreach($allfloors as $room):
                              ?>
                                <option value="<?php echo $room['id'] ?>"><?php echo $room['name'] ?></option>
                              <?php endforeach;endif;?>
                                        </select>
                                </div>
                            </div>


                           <div class="col-md-12">
                           <!--<label class="lblbooktblpage">Tables</label>-->
                            <div class="newbooktblcls">

                            <!-- <p id="hiddentableId">0 seats</p> -->
                            </div>
                            </div>
                            
                              
                            <div class="col-md-12" >
                                <div class="form-group">
                                       <textarea class="form-control" placeholder="<?php echo lang('booking_details');?>" name="comment" id="comment"><?php if(!empty($requestData)){echo $requestData['comment'];}?></textarea>
                                </div>
                            </div>

                    <?php
                        $requestId = $this->uri->segment(3); 
                        if(isset($requestId)):
                            $request_id = decoding($requestId);
                        endif;
                     ?>
                     <div class="col-md-12">
                        <div class="bookingbtn">
                            <input type="hidden" name="requestId" value="<?php if(isset($requestId)):echo $requestId;endif;?>">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('reset_btn');?></button>
                            <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('submit_btn');?></button>
                        </div>
                    </div>
                            <div class="space-22"></div>
                        </div>
                    </div>
               <!--  </div> -->
            </form>
          </div>
           
           <div id="menu1" class="tab-pane fade clearfix">
           <div class="col-sm-3">
           <div class="form-group">
            <input type="text" class="form-control" name="mydatepicker" id="my-datepicker" value="<?php echo (isset($_GET['date']) ? date('m/d/Y',strtotime($_GET['date'])) : date('m/d/Y'));?>">
                
            </div>    
            </div>   
    
            <div class="col-sm-3">
            <div class="form-group">
            <select id="floordd" class="form-control">
                <option value=""><?php echo lang('select_location');?></option>
            <?php if(!empty($allfloors)):?>
                <?php foreach($allfloors as $flr):?>
                <option value="<?php echo $flr['id'];?>" <?php if(isset($_GET['floor'])){echo ($_GET['floor'] == $flr['id']) ? 'selected': '';}?>><?php echo $flr['name'];?></option>
            <?php endforeach;?>
            <?php endif;?>
            </select>
            </div>
            </div>

            <?php if($this->session->userdata('role') == 'admin'):?> 
            <div class="col-sm-3">
            <div class="form-group">
            <select id="agent" class="form-control" name="agent">
                <option value=""><?php echo lang('select_agent');?></option>
            <?php if(!empty($allagents)):?>
                <?php foreach($allagents as $agnt):?>
                <option value="<?php echo $agnt['id'];?>" <?php if(isset($_GET['agent'])){echo ($_GET['agent'] == $agnt['id']) ? 'selected': '';}?>><?php echo $agnt['full_name'];?></option>
            <?php endforeach;?>
            <?php endif;?>
            </select>
            </div>
            </div>
        <?php else: ?>
         <!--    <input type="hidden" name="agent" value="<?php echo $this->session->userdata('id');?>" id=""> -->
        <?php endif;
        ?>

            <div class="col-sm-3">
            <div class="form-group">
            <button id="filter" class="btn btn-primary" ><?php echo lang('submit_btn');?></button>
            </div>
            </div>

            <!-- <div id="jqChart" style="width: 100%; height: 300px;"></div> -->
            <?php if(isset($_GET['floor'])):?>
            <div  id="timelinechart" class="timeline_chart" style="width: 100%;height:auto;"></div> 
            <?php endif;?>
           </div>
          
          <div id="menu2" class="tab-pane fade">
            <h3></h3>
            
            <div class="col-sm-6">
            
            <?php if(!empty($allfloors)):?>
                <div class="form-group">
            <!-- <label class="floordropdown_label">Select Floor</label> --> 
            <select id="floordrop_down" class="form-control">
                <option value=""><?php echo lang('select_location');?></option>
                <?php foreach($allfloors as $flr):?>
                <option value="<?php echo $flr['id'];?>" <?php if(isset($_GET['floorplan'])){echo ($_GET['floorplan'] == $flr['id']) ? 'selected': '';}?>><?php echo $flr['name'];?></option>
            <?php endforeach;?>
            </select>
            </div>
            <?php endif;?>




            <?php if(!empty($tables)):?>
            <?php foreach($tables as $key=>$value):?>
                <div id="sp-board" style="width: <?php echo ($value[0]->roomWidth != '')? $value[0]->roomWidth.'px':'100%' ?>; height: <?php echo ($value[0]->roomHeight != '')? $value[0]->roomHeight.'px':'400px' ?>;" room="<?php //echo $roomId ?>" pers="pers." delete-warning="Are you sure you want to delete this table? The table will be removed from all associated bookings." saving="Saving..." name-missing="You must enter a name!" changes-saved="Changes saved..." changes-not-saved="The changes were not saved! Please try again.">
                  <img src="<?php echo base_url();?>uploads/floors/<?php echo $value[0]->roomImage;?>" class="floorimagecls" style="z-index: 999999;width: <?php echo ($value[0]->roomWidth != '')? $value[0]->roomWidth.'px':'100%' ?>; height: <?php echo ($value[0]->roomHeight != '')? $value[0]->roomHeight.'px':'400px' ?>;" />
                <?php //echo $value[0]->roomName;?>
                <?php foreach($value as $table):?>
                <div class="sp-table <?php echo ($table->canrotate) ? 'canrotate' : '' ?> <?php echo ($table->rotation != '')?'rotate'.$table->rotation:'' ?>" style="left: <?php echo $table->left ?>; top: <?php echo $table->top ?>; visibility: hidden;" type="<?php echo $table->type ?>" tableid="<?php echo $table->id ?>" priority="<?php echo $table->priority ?>" rotation="<?php echo $table->rotation ?>" seats="<?php echo $table->seats ?>">
                    <div class="rel">
                        <div class="display">
                            <div class="name"><?php echo $table->name ?></div>
                            <div class="seats"><?php echo $table->seats ?> pers.</div>  
                        </div>
                        <img src="<?php echo base_url() . 'uploads/tables/' . $table->image ?>" class="svg" style="width: 43.64px; height: 43.64px;" />
                    </div>
                </div>
            <?php endforeach ?>
            </div>
            <?php endforeach ?>
            <?php endif ?>
        </div>
          </div>

        </div>
        <!--Tab End -->
    </div>
    <!--row End -->
<script src="http://code.jquery.com/jquery-latest.js"></script>    
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['timeline']}]}"></script><link href="https://www.google.com/uds/api/visualization/1.1/cc5d8333ad9d2dca8ea31ac15ed4e2df/ui+en_GB.css" type="text/css" rel="stylesheet"><script src="https://www.google.com/uds/api/visualization/1.1/cc5d8333ad9d2dca8ea31ac15ed4e2df/dygraph,format+en_GB,default+en_GB,ui+en_GB,timeline+en_GB.I.js" type="text/javascript"></script>
<script type="text/javascript">
setTimeout(function(){

function drawVisualization() {
  var paddingHeight = 400;
  // set the height to be covered by the rows
  var rowCount = (<?php echo count($finaldata);?> == '') ? 0 : <?php echo count($finaldata);?>;
  rowCount = parseInt(rowCount);
  var rowHeight = rowCount * 100;
  // set the total chart height
  var chartHeight = rowHeight + paddingHeight;     
  var Container = document.getElementById('timelinechart');
  var chart = new google.visualization.Timeline(Container);
  var dataTable = new google.visualization.DataTable();

  dataTable.addColumn({ type: 'string', id: 'Room' });
    dataTable.addColumn({ type: 'string', id: 'Name' });
    dataTable.addColumn({ type: 'string', role:'tooltip','p': {'html': true}});
    dataTable.addColumn({ type: 'date', id: 'Start' });
    dataTable.addColumn({ type: 'date', id: 'End' });
  
 dataTable.addRows([['Tables','','',new Date(0,0,0,10,0,0),new Date(0,0,0,22,0,0)]]);
    dataTable.addRows([
        <?php if(!empty($finaldata)):?>
        <?php foreach($finaldata as $final):
                $ft = array();
                $tt = array();
                $ft = explode(':',$final['time_from']);
                $ft1 = ($ft[1] == '00') ? 0 : $ft[1];
                $tt = explode(':',$final['time_to']);
                $tt1 = ($tt[1] == '00') ? 0 : $tt[1];
                $floorid = '';
                $agentid = '';
                $bId = -1;
                if(isset($final['id']))
                {
                    $bId = encoding($final['id']);
                }
                if(isset($_GET['floor']))
                    {
                        $floorid = $_GET['floor'];
                    }
                if(isset($_GET['agent']))
                    {
                        $agentid = $_GET['agent'];
                    }
                    

                echo '[';
        ?>
             '<?php echo $final['tableName']?>','<?php echo $final['name'];?>','<div style="padding:10px"><a href="javascript:void(0)" class="ttClose" onclick="hideTooltip()" style="position: absolute;right: 5px;top: 5px;"><i class="fa fa-close"></i></a>'+'<span style="font-weight:bold;margin-bottom: 10px;display:block; line-height: 30px;font-size: 14px;"><i class="fa fa-user"></i> &nbsp;&nbsp;<?php echo $final['name'];?> </span><span style="font-weight:bold;margin-bottom: 10px;display:block; line-height: 30px;font-size: 11px;"><i class="fa fa-clock-o"></i> &nbsp;&nbsp;<?php echo $final['time_from']." to ".$final['time_to'];?> </span><a href="javascript:void(0)" onclick="customrredirectFn(\'tablebooking\',\'index\',\'<?php if(isset($final['id'])){ echo encoding($final['id']);}else{echo ''; } ?>\',\'<?=$floorid ?>\',\'<?=$agentid?>\');" target="_blank" class="btn btn-primary btn-sm">Edit Booking</a>'+'<br/><a href="javascript:void(0)" target="_blank" onclick="freeBooking(\'<?=$bId;?>\')" class="btn btn-danger btn-sm">Free Table</a></div>',new Date(0,0,0,<?php echo $ft[0]?>,<?php echo $ft1;?>,0),new Date(0,0,0,<?php echo $tt[0]?>,<?php echo $tt1;?>,0)
        <?php echo '],';?>    
        <?php endforeach;?>    
        <?php endif;?>    
      ]);
  //select-handler
  google.visualization.events.addListener(chart, 'select', function(e) {
    //the built-in tooltip
    var tooltip = document.querySelector('.google-visualization-tooltip:not([clone])');
    //remove previous clone when there is any
    if (chart.ttclone) {
      chart.ttclone.parentNode.removeChild(chart.ttclone)
    }
    //create a clone of the built-in tooltip
    chart.ttclone = tooltip.cloneNode(true);
    //create a custom attribute to be able to distinguish
    //built-in tooltip and clone
    chart.ttclone.setAttribute('clone', true);

    //inject clone into document
    tooltip.parentNode.insertBefore(chart.ttclone, chart.tooltip);
  });
   var options = {
      timeline: { colorByRowLabel: true },
      backgroundColor: '#ffd',
      colorByRowLabel:true,
      height: chartHeight,
      tooltip: { isHtml: true}
    };
  chart.draw(dataTable,options );

}
drawVisualization();

},2000);
/* Function to Hide ToolTip*/
function hideTooltip(){
   [].forEach.call(document.querySelectorAll('.google-visualization-tooltip[clone]'), function (el) {
  el.style.display = 'none';
});
   [].forEach.call(document.querySelectorAll('.google-visualization-tooltip'), function (el) {
  el.style.display = 'none';
});
}
/* Function to Redirect Dynamically*/
function customrredirectFn(a,b,c,fl,ag)
{
    if (typeof(Storage) !== "undefined") {
    localStorage.setItem("editFromURL", window.location.href);
    localStorage.setItem("floor",fl);
    localStorage.setItem("agent",ag);
    redirectFn(a,b,c)
    } else {
       
    }
}
function freeBooking(p)
    {   var targeturl = '<?=base_url('tablebooking/freeTable');?>';
        
        var r = confirm("Are you sure you want to make this table free!");
        if (r == true) {
            var date = new Date(Math.round((new Date()).getTime() / 1000)*1000);
            // Hours part from the timestamp
            var hours = date.getHours();
            // Minutes part from the timestamp
            var minutes = "0" + date.getMinutes();
            // Seconds part from the timestamp
            var seconds = "0" + date.getSeconds();

            // Will display time in 10:30:23 format
            var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
            $.ajax({url:targeturl,method:'post',data:{id:p,time:formattedTime}}).done(function(res){
            res = JSON.parse(res);
            if(res.hasOwnProperty('success'))
            {
                location.reload();
            }
            else
            {
                alert('something went wrong, Please ask administrator');
                console.log(res);
            }
        })
        } else {
            
        }


       
    }
 /*Script for AutoRendering*/
 $(document).ready(function(){
     if (typeof(Storage) !== "undefined") {
    var $fl = '';
    var $ag = '';
        if(localStorage.getItem("floor")!==null)
        {
            $fl = localStorage.getItem("floor");
            localStorage.removeItem("floor");
            $('#floor').val($fl).trigger('change');
        }
        if(localStorage.getItem("agent"))
        {
            $ag = localStorage.getItem("agent");
            localStorage.removeItem("agent");
            $('#agent_id').val($ag).trigger('change');
        }
    } else {
       
    }

 });
</script>
<style>
    .google-visualization-tooltip {
  opacity: 0 !important;
  max-width: 200px !important;
}
.google-visualization-tooltip[clone] {
  opacity: 1 !important;
}
html,
body,
timeline {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}
#timelinechart .google-visualization-tooltip{
        pointer-events: auto!important;
}
#timelinechart .btn, .btn-sm {
    padding: 1px 14px !important;
    margin-bottom: 10px !important;
}

</style>
