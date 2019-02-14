<div id="notificationModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="sendPushNotificationAjax" method="post" action="<?php echo base_url('notification/sendPushNotification') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo lang('floor_manager_push_notification'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <!--                                    <label class="col-md-3 control-label">Sections</label>-->
                                    <div class="col-md-12">
                                        <select id="floor" name="floor_id" class="form-control">
                                            <option value=""><?php echo "Select Section"; ?></option>    
                                            <?php
                                            $option = array('table' => 'mw_rooms',
                                            );
                                            $floors = CommonGet($option);
                                            if (!empty($floors)):
                                                foreach ($floors as $rows):
                                                    ?>
                                                    <option value="<?php echo $rows->id; ?>"><?php echo $rows->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <?php echo form_error('floor_id'); ?>
                                        <input type="checkbox" name="allsection" value="all"/> All Sections
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <!--                                    <label class="col-md-3 control-label">Message</label>-->
                                    <div class="col-md-12">
                                        <textarea name="message" class="form-control" placeholder="What's on your mind?"><?php echo set_value('message'); ?></textarea>
                                        <?php echo form_error('message'); ?>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="btn btn-primary" ><i class="fa fa-send"></i> SEND</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="loaders common-loader">
    <img src="<?php echo base_url() . 'assets/images/Preloader_3.gif'; ?>" class="loaders-img" class="img-responsive">
</div>
<div class="footer">
    <div class="pull-right">

    </div>
    <div>
        <strong>Copyright</strong> © <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>
    </div>
</div>
</div>
</div>
<!-- Mainly scripts -->
<script src="<?php echo base_url(); ?>assets/js/jquery-2.1.1.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- jQuery UI -->
<!-- Flot -->
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.spline.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.pie.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.symbol.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.time.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Peity -->
<script src="<?php echo base_url(); ?>assets/js/plugins/peity/jquery.peity.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/demo/peity-demo.js"></script>
<!-- Custom and plugin javascript -->
<script src="<?php echo base_url(); ?>assets/js/inspinia.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/pace/pace.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/plugins/jeditable/jquery.jeditable.js"></script>
<!-- Data Tables -->
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.responsive.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.tableTools.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/bootbox/bootbox.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/chartJs/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>  

<script src="<?php echo base_url(); ?>assets/plugins/summernote/dist/summernote.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/Ply.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/select2.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/summernote/dist/summernote.min.js"></script>
<?php
$cls = $this->router->fetch_class();
$method = $this->router->fetch_method();
// if($cls == "admin" && $method == "dashboard"){
?>

<script src="<?php echo base_url(); ?>/assets/js/svgConvert.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/custom.js"></script>
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>dataTables.buttons.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.flash.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.flash.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>jszip.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>pdfmake.min.js"></script>   
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>vfs_fonts.js"></script>  
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.html5.min.js"></script>  
<script src="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.print.min.js"></script>  
<script src="<?php echo base_url() . 'assets/js/' ?>jquery.fullscreen-min.js"></script>  
<link href="<?php echo base_url() . 'assets/datatablepdf/' ?>buttons.dataTables.min.css" rel="stylesheet">

<?php //}  ?>
<script>
    var base_url = '<?php echo base_url(); ?>';
<?php if ($this->session->flashdata('error')) { ?>
        Ply.dialog("alert", "<?php echo $this->session->flashdata('error') ?>");
<?php } ?>
<?php if ($this->session->flashdata('success')) { ?>
        Ply.dialog("alert", "<?php echo $this->session->flashdata('success') ?>");
<?php } ?>
    function base_url()
    {
        url = "<?php echo base_url(); ?>";
        return url;
    }

    $(document).ready(function () {

        $(document).on('submit', "#addFormAjax", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".loaders").fadeIn("slow");
                },
                success: function (response, textStatus, jqXHR) {
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
                            Ply.dialog("alert", data.message);
                            window.setTimeout(function () {
                                window.location.href = data.url;
                            }, 2000);
                            $(".loaders").fadeOut("slow");

                        } else {
                            /*$('#error-box').show();
                             $("#error-box").html(data.message);
                             $(".loaders").fadeOut("slow");
                             Ply.dialog("alert", data.message);
                             setTimeout(function () {
                             $('#error-box').hide(800);
                             }, 1000);*/

                            Ply.dialog("alert", data.message);
                            window.setTimeout(function () {

                            }, 2000);
                            $(".loaders").fadeOut("slow");
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });

        $(document).on('submit', "#editFormAjax", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".loaders").fadeIn("slow");
                },
                success: function (response, textStatus, jqXHR) {
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
                            Ply.dialog("alert", data.message);

                            $("#commonModal").modal('hide');
                            window.setTimeout(function () {
                                window.location.href = data.url;
                            }, 2000);
                            $(".loaders").fadeOut("slow");

                        } else {
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            $(".loaders").fadeOut("slow");
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });


        $(document).on('submit', "#sendPushNotificationAjax", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".loaders").fadeIn("slow");
                },
                success: function (response, textStatus, jqXHR) {
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
                            Ply.dialog("alert", data.message);
                            $("#notificationModal").modal('hide');
                            $(".loaders").fadeOut("slow");

                        } else {
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            $(".loaders").fadeOut("slow");
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });

        $('#common_datatable_booking_dashboard1').dataTable({
            order: [],
            columnDefs: [{orderable: false, targets: [0, 10]}],
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel'
            ]
        });

        var oTable = $('#common_datatable_booking_dashboard').DataTable({
            "processing": true,
            "bServerSide": true,
            "searching": false,
            "bLengthChange": false,
            "bProcessing": true,
            "iDisplayLength": 10,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            columnDefs: [{orderable: false, targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]}],
            "columns": [
                {"data": "name", "searchable": false, "order": true},
                {"data": "action", "searchable": false, "order": false},
                {"data": "status", "searchable": false, "order": false},
                {"data": "confirmation", "searchable": false, "order": false},
                {"data": "floor", "searchable": false, "order": false},
                {"data": "no_of_persons", "searchable": false, "order": false},
                {"data": "booking_date", "searchable": false, "order": false},
                {"data": "time_from", "searchable": false, "order": false},
                {"data": "time_to", "searchable": false, "order": false},
                {"data": "comment", "searchable": false, "order": false},
                {"data": "referrer", "searchable": false, "order": false},
                {"data": "email", "searchable": false, "order": false},
                {"data": "mobile", "order": false, orderable: false},
            ],
            "ajax": {
                "url": "<?php echo site_url('admin/booking_ajax'); ?>",
                "type": "POST",
                "data": function (d) {
                    d.searchstr = $("#search").val();
                }
            }
        });




//        $('#limitOffset').change(function () {
//           oTable.draw();
//        });

        $('#search').on('keyup', function () {
            oTable.draw();

        });



    });

    var oTableWalk = $('#common_datatable_current_booking_dashboard').DataTable({
        "processing": true,
        "bServerSide": true,
        "searching": false,
        "bLengthChange": false,
        "bProcessing": true,
        "iDisplayLength": 10,
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        columnDefs: [{orderable: false, targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]}],
        "columns": [
            {"data": "name", "searchable": false, "order": true},
            {"data": "action", "searchable": false, "order": false},
            {"data": "status", "searchable": false, "order": false},
            {"data": "confirmation", "searchable": false, "order": false},
            {"data": "floor", "searchable": false, "order": false},
            {"data": "no_of_persons", "searchable": false, "order": false},
            {"data": "booking_date", "searchable": false, "order": false},
            {"data": "time_from", "searchable": false, "order": false},
            {"data": "time_to", "searchable": false, "order": false},
            {"data": "comment", "searchable": false, "order": false},
            {"data": "referrer", "searchable": false, "order": false},
            {"data": "email", "searchable": false, "order": false},
            {"data": "mobile", "order": false, orderable: false},
        ],
        "ajax": {
            "url": "<?php echo site_url('admin/booking_ajax'); ?>",
            "type": "POST",
            "data": function (d) {
                d.searchstr = $("#search_walkin").val();
                d.booking_type = 'current';
            }
        }
    });

    $('#search_walkin').on('keyup', function () {
        oTableWalk.draw();


    });

    function fullScreen() {
        $(document).fullScreen(true);
        $("#exitFullScreen").show();
    }
    function ExitfullScreen() {
        $(document).fullScreen(false);
        $("#exitFullScreen").hide();
    }

    function isPushNotificationAdmin() {
        $.ajax({
            type: "GET",
            url: '<?php echo base_url() . 'admin/NotificationAdmin' ?>',
            processData: false,
            contentType: false,
            success: function (response, textStatus, jqXHR) {
                if (response != 2) {
                    try {
                        $("#notification-list-view").html(response);
                    } catch (e) {

                    }
                } else {
                    window.location.href = '<?php echo base_url() . 'siteadmin' ?>'
                }
            }
        });
    }
    isPushNotificationAdmin();
    setInterval(function () {
        isPushNotificationAdmin();
    }, 30 * 1000);
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('timeTable').innerHTML =
                h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }
        ;  // add zero in front of numbers < 10
        return i;
    }
    startTime();
</script>    
<script src="<?php echo base_url(); ?>assets/js/admin.js"></script>
</body>
</html>
