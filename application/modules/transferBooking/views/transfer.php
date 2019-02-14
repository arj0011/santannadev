<style>
    div.google-visualization-tooltip { /*transform: rotate(30deg);*/width:15%;height: auto;line-height: 10px; }
    #sp-board .sp-table,
    .tableplan .sp-table {
        display: block;
        position: absolute;
        z-index: 9;
    }
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
    .active-btn{ background-color: #EC4758;}
    .text-info-success{ color: #4BBF24}
    .loaders {
      background-color: #000 !important;
     }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="ibox-content">
            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-primary">
<!--                        <div class="panel-heading"> Transfer Booking</div>-->
                        <div class="panel-body">

                            <div class="form-group custom_bkplane">
                                <?php 
                                if (!empty($floors)):
                                    foreach ($floors as $rows):
                                        ?>
                                        <div class="radio">    
<!--                                            <input type="radio" class="floordrop_down" <?php echo ($floor_id_us == $rows->id) ? "checked" : "";?> value="<?php echo $rows->id; ?>"  name="floordrop_down"> <label><?php echo $rows->name; ?></label>-->
                                            <button type="button" id="floor-btn<?php echo $rows->id; ?>" data-floor="<?php echo $rows->id; ?>" class="btn-xs floordrop_down btn btn-default" style='<?php echo ($floor_id_us == $rows->id) ? "background-color: #EC4758;border:1px solid #EC4758;" : "";?>' name="floordrop_down"><?php echo $rows->name; ?></button>
                                        </div>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                                <div class="radio col-md-offset-1"> 
                                   <button type="button" class="btn btn-primary  btn-xs allactionbutton" name="allactionbutton" data-actionbtn="free">Free Table</button>
                                   <button type="button" class="btn btn-primary  btn-xs allactionbutton" name="allactionbutton" data-actionbtn="move">Move Table</button>
                                   <button type="button" class="btn btn-primary  btn-xs allactionbutton" name="allactionbutton" data-actionbtn="view">View Table</button>
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <label><span style="color:#03A678"><i class="fa fa-check-square"></i></span> Free</label>
                                <label><span style="color:#C91F37"><i class="fa fa-check-square"></i></span> Booked</label>
                                <label><span style="color:#FFA400"><i class="fa fa-check-square"></i></span> Pending</label>
                                <input type="hidden" id="actionTypeId" name="actionTypeId"/>
                                <input type="hidden" id="flootTypeId" name="flootTypeId" value="<?php echo $floor_id_us;?>"/>
                                <input type="hidden" id="tableTypeId" name="tableTypeId"/>
                                <input type="hidden" id="bookedTableId" name="bookedTableId"/>
                            </div>
<!--                            <div class="col-sm-2">
                                <label><span class="text-info"><i class="fa fa-clock-o"></i></span> <span id="timeTable"></span></label>
                            </div>-->
                               <div class="loaders">
                                    <img src="<?php echo base_url().'assets/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                                </div>
                            <div id="floor_plan_view_display_section">
                         <?php $tablesBook=array(); if (!empty($tables)): ?>
    <?php $i = 1; foreach ($tables as $rowsTab): foreach ($rowsTab as $tb): $tablesBook[] = $tb->id;endforeach; endforeach;
    foreach ($tables as $key => $value): ?>
                            <div id="sp-board" style="width: <?php echo ($value[0]->roomWidth != '') ? $value[0]->roomWidth . 'px' : '100%' ?>; height: <?php echo ($value[0]->roomHeight != '') ? $value[0]->roomHeight . 'px' : '400px' ?>;" room="<?php //echo $roomId  ?>" pers="pers." delete-warning="Are you sure you want to delete this table? The table will be removed from all associated bookings." saving="Saving..." name-missing="You must enter a name!" changes-saved="Changes saved..." changes-not-saved="The changes were not saved! Please try again.">
                                <div class="chart_overlay"></div>
                                
                                <img src="<?php echo base_url(); ?>uploads/floors/<?php echo $value[0]->roomImage; ?>" class="floorimagecls" style="z-index: 1;width: <?php echo ($value[0]->roomWidth != '') ? $value[0]->roomWidth . 'px' : '100%' ?>; height: <?php echo ($value[0]->roomHeight != '') ? $value[0]->roomHeight . 'px' : '400px' ?>;" />
        
        <?php $ct=0; foreach ($value as $table): ?>
                                    <div class="<?php echo ($table->personName != '') ? 'newtooltip' : ''; ?> sp-table <?php echo ($table->canrotate) ? 'canrotate' : '' ?> <?php echo ($table->rotation != '') ? 'rotate' . $table->rotation : '' ?>" style="outline:<?php echo $table->border; ?>;left: <?php echo $table->left ?>; top: <?php echo $table->top ?>; visibility: hidden;" type="<?php echo $table->type ?>" tableid="<?php echo $table->id ?>" priority="<?php echo $table->priority ?>" rotation="<?php echo $table->rotation ?>" seats="<?php echo $table->seats ?>">
                                        <div class="box">
                                            <span class="text-info-success" id="tableOk<?php echo $i;?>"></span>
                                            <div class="display">
                                                
            <?php if ($table->personName == "") { ?>
                        <a href="javascript:void(0)" onclick="actionTableFree('<?php echo $table->id; ?>','<?php echo $table->roomId;?>')">
                              <div class="name"><?php echo $table->name ?></div>
                              <div class="seats"><?php echo $table->seats ?> pers.</div>  
                         </a>
            <?php } else { ?>
                                                <a href="javascript:void(0)" onclick="actionTableBooking('<?php echo $table->id; ?>','<?php echo $table->bookingID; ?>','<?php echo encoding($tablesBook);?>',<?php echo $table->roomId;?>,'<?php echo encoding($value[$ct]);?>','<?php echo $i;?>','<?php echo encoding($table->bookingID); ?>')">                      
                                                    <div class="name"><?php echo $table->name ?></div>
                                                    <div class="seats"><?php echo $table->seats ?> pers.</div>  
                               </a>                     
             <?php } ?>

                                            </div>
            <?php if ($table->personName == "") { ?>
             <a href="javascript:void(0)" onclick="actionTableFree('<?php echo $table->id; ?>','<?php echo $table->roomId;?>')">
                   <div class="name"><?php echo substr($table->personName, 0,10) ?></div>    
                    <img src="<?php echo base_url() . 'uploads/tables/' . $table->image ?>" class="svg" style="width: 43.64px; height: 43.64px;" />
            </a>     
             <?php }else{ ?>
                    <a href="javascript:void(0)" onclick="actionTableBooking('<?php echo $table->id; ?>','<?php echo $table->bookingID; ?>','<?php echo encoding($tablesBook);?>',<?php echo $table->roomId;?>,'<?php echo encoding($value[$ct]);?>','<?php echo $i;?>','<?php echo encoding($table->bookingID); ?>')">
                   <div class="name"><?php echo substr($table->personName, 0,10) ?></div>    
                    <img src="<?php echo base_url() . 'uploads/tables/' . $table->image ?>" class="svg" style="width: 43.64px; height: 43.64px;" />
            </a>     
             <?php }?>
                 
          
                                        </div>
                                        <!--Tooltip -->
            <?php //if ($table->personName != ''): ?>
<!--                                            <div style="padding:10px" class="infotooltip" id="tool<?php echo $i; ?>">
                                                <a href="javascript:void(0)" class="ttClose" style="position: absolute;right: 5px;top: 5px;" onclick="hideTools('<?php echo $i; ?>')">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                                <span style="font-weight:bold;margin-bottom: 10px;display:block; line-height: 30px;font-size: 14px;">
                                                    <i class="fa fa-user"></i> &nbsp;&nbsp;<?php echo $table->personName; ?> </span>
                                                <span style="font-weight:bold;margin-bottom: 10px;display:block; line-height: 30px;font-size: 11px;">
                                                    <i class="fa fa-clock-o"></i> &nbsp;&nbsp;<?php echo $table->StartTime . " to " . $table->EndTime; ?> </span>
                                                <a href="javascript:void(0)" onclick="transferTable('<?php echo $table->bookingID; ?>','<?php echo encoding($tablesBook);?>',<?php echo $table->roomId;?>,'<?php echo encoding($value[$ct]);?>','<?php echo $i;?>')" class="btn btn-danger btn-sm">Move Now</a>
                                            </div>-->
            <?php //endif; ?>  
                                        <!--End-->


                                    </div>
            <?php $i++; $ct++;
        endforeach ?>
                            </div>
    <?php endforeach ?>
<?php endif ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<div id="transferViewModel"></div>
