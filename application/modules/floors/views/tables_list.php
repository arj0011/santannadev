         <table class="table table-bordered table-responsive" id="common_datatable_floors">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('serial_no'); ?></th>
                                        <th>Table Name</th>
                                        <th>Seats</th>
                                        <th style="width: 12%"><?php echo lang('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($list) && !empty($list)):
                                        $rowCount = 0;
                                        foreach ($list as $rows):
                                            $rowCount++;
                                            ?>
                                            <tr>
                                                <td><?php echo $rowCount; ?></td>            
                                                <td><?php echo $rows->name ?></td>
                                                <td><?php echo $rows->seats ?></td>

                                                <td class="actions">
                                                        <a href="javascript:void(0)" onclick="deleteFnTable('<?php echo $rows->id; ?>','<?php echo $floor_id;?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url() . DELETE_ICON; ?>" /></a>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
