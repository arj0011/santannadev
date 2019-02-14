<script src="<?php echo base_url(); ?>assets/js/jquery-2.1.1.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/svgConvert.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/custom.js"></script>
<!--<script src="<?php //echo base_url() . 'assets/datatablepdf/' ?>dataTables.buttons.min.js"></script>   
<script src="<?php //echo base_url() . 'assets/datatablepdf/' ?>buttons.flash.min.js"></script>   
<script src="<?php //echo base_url() . 'assets/datatablepdf/' ?>buttons.flash.min.js"></script>   -->
<!--<script src="<?php //echo base_url() . 'assets/datatablepdf/' ?>jszip.min.js"></script>   -->
<!--<script src="<?php //echo base_url() . 'assets/datatablepdf/' ?>pdfmake.min.js"></script>   
<script src="<?php //echo base_url() . 'assets/datatablepdf/' ?>vfs_fonts.js"></script>  -->
<!--<script src="<?php //echo base_url() . 'assets/datatablepdf/' ?>buttons.html5.min.js"></script>  -->
<!--<script src="<?php //echo base_url() . 'assets/datatablepdf/' ?>buttons.print.min.js"></script>  -->
<script src="<?php echo base_url() . 'assets/js/' ?>jquery.fullscreen-min.js"></script>  
<link href="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.dataTables.min.css" rel="stylesheet">
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