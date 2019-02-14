
<script>

    $('body').on('click', '.floordrop_down', function () {
        var flootTypeId = document.querySelector("#flootTypeId");
        var actionTypeId = document.querySelector("#actionTypeId");
        var floorId = $(this).data('floor'); 
        $('.floordrop_down').css({ "background-color": "#c2c2c2", "border": "1px solid #c2c2c2" });
        $(this).css({ "background-color": "#EC4758", "border": "1px solid #EC4758" });
        flootTypeId.value = floorId;
        actionTypeId.value="";
        $('.allactionbutton').css({ "background-color": "#1ab394", "border": "1px solid #1ab394" });
        $.ajax({
            url: "<?php echo base_url(); ?>transferBooking/floorPlanView",
            type: "post",
            data: {floorId: floorId},
            "async": false,
            "crossDomain": true,
            beforeSend: function () {
                $(".loaders").fadeIn("slow");
            },
            success: function (res) {
                $("#floor_plan_view_display_section").html(res);
                $(".loaders").fadeOut("slow");
            }
        });

    });

    $('body').on('click', '.allactionbutton', function () {
        var actionTypeId = document.querySelector("#actionTypeId");
        var actiontype = $(this).data('actionbtn');
        $('.allactionbutton').css({ "background-color": "#1ab394", "border": "1px solid #1ab394" });
        $(this).css({ "background-color": "#EC4758", "border": "1px solid #EC4758" });
        actionTypeId.value = actiontype;

    });

    $('.sp-table').click(function () {
        $(this).find('.infotooltip').show();
        $('.ttClose').click(function () {
            $(this).parent('div').hide();
        });
    });

    $("body").toggleClass("mini-navbar");

    function hideTools(ids) {
        $("#tool" + ids).hide();
    }
                                            
    function actionTableBooking(fromBookedTableId, bookingId, tableId, roomId, bookingDetails, id,encodeBookingId) {
        var tableTypeId = document.querySelector("#tableTypeId");
        var bookedTableId = document.querySelector("#bookedTableId");
        var actionTypeId = document.querySelector("#actionTypeId");
        tableTypeId.value = bookingId;
        bookedTableId.value = fromBookedTableId;


        if (actionTypeId.value != "") {

            if (actionTypeId.value == 'move') {
                $(".text-info-success").html("");
                $("#tableOk" + id).html('<i class="fa fa-check"></i>');
            } else if (actionTypeId.value == 'free') {

                Ply.dialog("confirm", "Are You Sure Want To Free This Booking All Tables?")
                        .done(function (ui) {
                            // OK
                        })
                        .fail(function (ui) {
                            // Cancel
                        })
                        .always(function (ui) {
                            if (ui.state) {

                                $.ajax({
                                    url: "<?php echo base_url(); ?>transferBooking/freeTable",
                                    type: "post",
                                    data: {bookingId: bookingId},
                                    "async": false,
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.status == 1) {
                                            Ply.dialog("alert", response.message);
                                            $.ajax({
                                                url: "<?php echo base_url(); ?>transferBooking/floorPlanView",
                                                type: "post",
                                                data: {floorId: roomId},
                                                "async": false,
                                                "crossDomain": true,
                                                beforeSend: function () {
                                                    $(".loaders").fadeIn("slow");
                                                },
                                                success: function (res) {
                                                    $("#floor_plan_view_display_section").html(res);
                                                    $(".loaders").fadeOut("slow");
                                                }
                                            });
                                        } else {
                                            Ply.dialog("alert", response.message);
                                        }
                                    }
                                });
                            }
                            else {
                            }
                        });
            } else if (actionTypeId.value == 'view') {
                window.location.href="<?php echo base_url();?>tablebooking/index/"+encodeBookingId;
            }
        } else {
            window.location.href="<?php echo base_url();?>tablebooking?floor_table="+roomId+"&table="+fromBookedTableId;
        }

    }

    function actionTableFree(tableId, roomId) {
        var actionTypeId = document.querySelector("#actionTypeId");
        var flootTypeId = document.querySelector("#flootTypeId");
        var fromBookingId = document.querySelector("#tableTypeId");
        var bookedTableId = document.querySelector("#bookedTableId");
        if(actionTypeId.value == 'view'){
            window.location.href="<?php echo base_url();?>tablebooking?floor_table="+roomId+"&table="+tableId; 
        }

        if (actionTypeId.value != "") {

            if (actionTypeId.value == "move") {

                if (fromBookingId.value != "") {
                    Ply.dialog("confirm", "Are You Sure Want To Move In?")
                            .done(function (ui) {
                                // OK
                            })
                            .fail(function (ui) {
                                // Cancel
                            })
                            .always(function (ui) {
                                if (ui.state) {

                                    $.ajax({
                                        url: "<?php echo base_url(); ?>transferBooking/moveTable",
                                        type: "post",
                                        data: {fromBookingId: fromBookingId.value, tableId: tableId, roomId: roomId, bookedTableId: bookedTableId.value},
                                        "async": false,
                                        dataType: 'json',
                                        success: function (response) {
                                            if (response.status == 1) {
                                                Ply.dialog("alert", response.message);
                                                
                                                bookedTableId.value = "";
                                                fromBookingId.value = "";
                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>transferBooking/floorPlanView",
                                                    type: "post",
                                                    data: {floorId: roomId},
                                                    "async": false,
                                                    "crossDomain": true,
                                                    beforeSend: function () {
                                                        $(".loaders").fadeIn("slow");
                                                    },
                                                    success: function (res) {
                                                        $("#floor_plan_view_display_section").html(res);
                                                        $(".loaders").fadeOut("slow");
                                                    }
                                                });

                                            } else {
                                                Ply.dialog("alert", response.message);
                                            }
                                        }
                                    });
                                }
                                else {



                                }
                            });

                } else {
                    Ply.dialog("alert", "Please Select First Booked Table");
                }

            }

        } else {
            window.location.href="<?php echo base_url();?>tablebooking?floor_table="+roomId+"&table="+tableId;
        }


    }

    function transferTable(bookingId, tableId, roomId, bookingDetails, id) {

        Ply.dialog("confirm", "Are You Sure Want To Move Table?")
                .done(function (ui) {
                    // OK
                })
                .fail(function (ui) {
                    // Cancel
                })
                .always(function (ui) {
                    if (ui.state) {

                        $.ajax({
                            url: "<?php echo base_url(); ?>transferBooking/moveTo",
                            type: "post",
                            data: {bookingId: bookingId, tableId: tableId, roomId: roomId, bookingDetails: bookingDetails},
                            success: function (res) {
                                $("#transferViewModel").html(res);
                                $("#commonModal").modal('show');
                                $("#tool" + id).hide();
                            }
                        });
                    }
                    else {



                    }
                });




    }

    function freeBooking(p)
    {
        var targeturl = '<?php echo base_url('tablebooking/freeTable'); ?>';

        var r = confirm("Are you sure you want to make this table free!");
        if (r == true) {
            var date = new Date(Math.round((new Date()).getTime() / 1000) * 1000);
            // Hours part from the timestamp
            var hours = date.getHours();
            // Minutes part from the timestamp
            var minutes = "0" + date.getMinutes();
            // Seconds part from the timestamp
            var seconds = "0" + date.getSeconds();

            // Will display time in 10:30:23 format
            var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

            $.ajax({
                url: "<?php echo base_url(); ?>tablebooking/freeTable",
                type: "post",
                data: {id: p, time: formattedTime},
                success: function (res) {
                    if (res == 1) {
                        $('.ttClose').parent('div').hide();
                        Ply.dialog("alert", "<?php echo lang('change_status_success'); ?>");
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                        return false;

                    } else {
//                        Ply.dialog("alert", "something went wrong, Please ask administrator");
//                            setTimeout(function () {
//                            }, 2000);
                        console.log("something went wrong, Please ask administrator");
                    }
                }
            });
        } else {

        }
    }
</script>


