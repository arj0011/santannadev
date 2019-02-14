<script>
    var deleteFn = function (table, field, id) {


        Ply.dialog("confirm", "<?php echo lang('delete'); ?>")
                .done(function (ui) {
                    // OK
                })
                .fail(function (ui) {
                    // Cancel
                })
                .always(function (ui) {
                    if (ui.state) {
                        var url = "<?php echo base_url() ?>newsCategory/delete";
                        $.ajax({
                            method: "POST",
                            url: url,
                            data: {id: id, id_name: field, table: table},
                            success: function (response) {
                                if (response == 200) {
                                    Ply.dialog("alert", "<?php echo lang('delete_success'); ?>");
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 2000);

                                }
                            },
                            error: function (error, ror, r) {
                                Ply.dialog("alert", error);
                            },
                        });
                    }
                });
    }

    var statusFn = function (table, field, id, status) {
        var message = "";
        if (status == 1) {
            message = '<?php echo lang('active_message'); ?>';
        } else if (status == 0) {
            message = '<?php echo lang('inactive_message'); ?>';
        }

        Ply.dialog("confirm", message)
                .done(function (ui) {
                    // OK
                })
                .fail(function (ui) {
                    // Cancel
                })
                .always(function (ui) {
                    if (ui.state) {
                        var url = "<?php echo base_url() ?>newsCategory/status";
                        $.ajax({
                            method: "POST",
                            url: url,
                            data: {id: id, id_name: field, table: table, status: status},
                            success: function (response) {
                                if (response == 200) {
                                    Ply.dialog("alert", "<?php echo lang('change_status_success'); ?>");
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 2000);
                                }
                            },
                            error: function (error, ror, r) {
                                Ply.dialog("alert", error);
                            },
                        });
                    }
                });
    }


    var statusChange = function (table, field, id, status) {
        var message = "";
        if (status == 0) {
            message = '<?php echo lang('active_message'); ?>';
        } else if (status == 1) {
            message = '<?php echo lang('inactive_message'); ?>';
        }

        Ply.dialog("confirm", message)
                .done(function (ui) {
                    // OK
                })
                .fail(function (ui) {
                    // Cancel
                })
                .always(function (ui) {
                    if (ui.state) {
                        var url = "<?php echo base_url() ?>newsCategory/new_status";
                        $.ajax({
                            method: "POST",
                            url: url,
                            data: {id: id, id_name: field, table: table, status: status},
                            success: function (response) {
                                if (response == 200) {
                                    Ply.dialog("alert", "<?php echo lang('change_status_success'); ?>");
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 2000);
                                }
                            },
                            error: function (error, ror, r) {
                                Ply.dialog("alert", error);
                            },
                        });
                    }
                });
    }

    var deleteFnRest = function (table, field, id) {

        Ply.dialog("confirm", "<?php echo lang('delete'); ?>")
                .done(function (ui) {
                    // OK
                })
                .fail(function (ui) {
                    // Cancel
                })
                .always(function (ui) {
                    if (ui.state) {
                        var url = "<?php echo base_url() ?>newsCategory/delete_rest";
                        $.ajax({
                            method: "POST",
                            url: url,
                            data: {id: id, id_name: field, table: table},
                            success: function (response) {
                                if (response == 200) {
                                    setTimeout(function () {
                                        $("#message").html("<div class='alert alert-success'>Successfully deletd records</div>");
                                    });
                                    window.location.reload();
                                }
                            },
                            error: function (error, ror, r) {
                                Ply.dialog("alert", error);

                            },
                        });
                    }
                });
    }

    var editFn = function (ctrl, method, id) {
        $.ajax({
            url: '<?php echo base_url(); ?>' + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                $(".loaders").fadeIn("slow");
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                //addFormBoot();
                $(".loaders").fadeOut("slow");
            }
        });
    }

    var viewFn = function (ctrl, method, id) {
        $.ajax({
            url: '<?php echo base_url(); ?>' + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                $(".loaders").fadeIn("slow");
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                //addFormBoot();
                $(".loaders").fadeOut("slow");
            }
        });
    }

    var open_modal = function (controller) {
        $.ajax({
            url: '<?php echo base_url(); ?>' + controller + "/open_model",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');



            }
        });
    }


    var open_other_modal = function (controller,method,id) {
        $.ajax({
            url: '<?php echo base_url(); ?>' + controller + "/"+method,
            type: 'POST',
            data:{id:id},
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#paymentModal").modal('show');
            }
        });
    }


    var changeLanguage = function (lang) {
        $.ajax({
            url: '<?php echo base_url(); ?>newsCategory/change_language',
            type: 'POST',
            data: {'language': lang},
            success: function (data, textStatus, jqXHR) {
                if (data == 1) {
                    Ply.dialog("alert", "<?php echo lang('change_language'); ?>");
                    window.location.reload();
                }
            }
        });
    }

    var redirectFn = function (ctrl, method, id) {
        window.location.href = '<?php echo base_url(); ?>' + ctrl + "/" + method + "/" + id;
    }

    function delBooking(id) {
        Ply.dialog("confirm", "<?php echo lang('delete'); ?>")
                .done(function (ui) {
                    // OK
                })
                .fail(function (ui) {
                    // Cancel
                })
                .always(function (ui) {
                    if (ui.state) {


                        var url = "<?php echo base_url() ?>tablebooking/open_model_feedback";
                        $.ajax({
                            method: "POST",
                            url: url,
                            data: {id: id},
                            success: function (data, textStatus, jqXHR) {
                                $('#form-modal-box').html(data);
                                $("#commonModal").modal('show');

                            }
                        });



//                        var url = "<?php echo base_url() ?>tablebooking/deletebooking";
//                        $.ajax({
//                            method: "POST",
//                            url: url,
//                            data: {id: id},
//                            success: function (response) {
//                                if (response == 1) {
//                                    Ply.dialog("alert", "<?php echo lang('delete_success'); ?>");
//                                    setTimeout(function () {
//                                        window.location.reload();
//                                    }, 2000);
//
//                                }
//                            },
//                            error: function (error, ror, r) {
//                                //Ply.dialog("alert", error);
//                            },
//                        });

                    }
                });
    }
    
    function bookingStatusAuthDashboard(bookingId, el) {
        var _flag = 0;
        var bookingStatus = el.value;
        var bookingMsg = el.options[el.selectedIndex].text;

        if (bookingStatus == 1) {
            Ply.dialog("confirm", "Do you want to edit Booking?")
                    .done(function (ui) {
                        // OK
                    })
                    .fail(function (ui) {
                        // Cancel
                    })
                    .always(function (ui) {
                        if (ui.state) {
                            window.location.href = '<?php echo base_url(); ?>' + "tablebooking/index/" + bookingId;
                        }
                        else {
                            $.ajax({
                                method: "POST",
                                url: "<?php echo base_url() . 'tablebooking/bookingStatus' ?>",
                                data: {status: bookingStatus, bookingId: bookingId},
                                dataType: "json",
                                beforeSend: function () {
                                    $(".loaders").fadeIn("slow");
                                },
                                success: function (response) {
                                    if (response.status == 1) {
                                        Ply.dialog("alert", response.message);
                                        window.setTimeout(function () {
                                            window.location.href = response.redirect;
                                        }, 2000);
                                        $(".loaders").fadeOut("slow");
                                    } else {
                                        Ply.dialog("alert", response.message);
                                        $(".loaders").fadeOut("slow");
                                    }
                                },
                                error: function (error, ror, r) {
                                    //Ply.dialog("alert", error);
                                },
                            });
                        }
                    });
        } else {
            Ply.dialog("confirm", " Are you sure want to " + bookingMsg + " booking?")
                    .done(function (ui) {
                        // OK
                    })
                    .fail(function (ui) {
                        // Cancel
                    })
                    .always(function (ui) {
                        if (ui.state) {
                            $.ajax({
                                method: "POST",
                                url: "<?php echo base_url() . 'tablebooking/bookingStatus' ?>",
                                data: {status: bookingStatus, bookingId: bookingId},
                                dataType: "json",
                                beforeSend: function () {
                                    $(".loaders").fadeIn("slow");
                                },
                                success: function (response) {
                                    if (response.status == 1) {
                                        Ply.dialog("alert", response.message);
                                        window.setTimeout(function () {
                                            window.location.href = '<?php echo base_url() . 'admin/dashboard' ?>';
                                        }, 2000);
                                        $(".loaders").fadeOut("slow");
                                    } else {
                                        Ply.dialog("alert", response.message);
                                        $(".loaders").fadeOut("slow");
                                    }
                                },
                                error: function (error, ror, r) {
                                    //Ply.dialog("alert", error);
                                },
                            });
                        }
                        else {

                        }
                    });
        }


    }
</script>

