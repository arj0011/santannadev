<script>

    function show_message(msg) {
        var Base64 = {_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
                var t = "";
                var n, r, i, s, o, u, a;
                var f = 0;
                e = Base64._utf8_encode(e);
                while (f < e.length) {
                    n = e.charCodeAt(f++);
                    r = e.charCodeAt(f++);
                    i = e.charCodeAt(f++);
                    s = n >> 2;
                    o = (n & 3) << 4 | r >> 4;
                    u = (r & 15) << 2 | i >> 6;
                    a = i & 63;
                    if (isNaN(r)) {
                        u = a = 64
                    } else if (isNaN(i)) {
                        a = 64
                    }
                    t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
                }
                return t
            }, decode: function (e) {
                var t = "";
                var n, r, i;
                var s, o, u, a;
                var f = 0;
                e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                while (f < e.length) {
                    s = this._keyStr.indexOf(e.charAt(f++));
                    o = this._keyStr.indexOf(e.charAt(f++));
                    u = this._keyStr.indexOf(e.charAt(f++));
                    a = this._keyStr.indexOf(e.charAt(f++));
                    n = s << 2 | o >> 4;
                    r = (o & 15) << 4 | u >> 2;
                    i = (u & 3) << 6 | a;
                    t = t + String.fromCharCode(n);
                    if (u != 64) {
                        t = t + String.fromCharCode(r)
                    }
                    if (a != 64) {
                        t = t + String.fromCharCode(i)
                    }
                }
                t = Base64._utf8_decode(t);
                return t
            }, _utf8_encode: function (e) {
                e = e.replace(/\r\n/g, "\n");
                var t = "";
                for (var n = 0; n < e.length; n++) {
                    var r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r)
                    } else if (r > 127 && r < 2048) {
                        t += String.fromCharCode(r >> 6 | 192);
                        t += String.fromCharCode(r & 63 | 128)
                    } else {
                        t += String.fromCharCode(r >> 12 | 224);
                        t += String.fromCharCode(r >> 6 & 63 | 128);
                        t += String.fromCharCode(r & 63 | 128)
                    }
                }
                return t
            }, _utf8_decode: function (e) {
                var t = "";
                var n = 0;
                var r = c1 = c2 = 0;
                while (n < e.length) {
                    r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r);
                        n++
                    } else if (r > 191 && r < 224) {
                        c2 = e.charCodeAt(n + 1);
                        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                        n += 2
                    } else {
                        c2 = e.charCodeAt(n + 1);
                        c3 = e.charCodeAt(n + 2);
                        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                        n += 3
                    }
                }
                return t
            }}

        msg = Base64.decode(msg);
        $('#message_container').text(msg);
        $('#message_div').show();
    }
    function close_message() {
        $('#message_container').text('');
        $('#message_div').hide();
    }

    var oTabless = $('#common_datatable_notification').DataTable({
        "processing": true,
        "bServerSide": true,
        "searching": false,
        "bLengthChange": false,
        "bProcessing": true,
        "iDisplayLength": 20,
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        "columnDefs": [{orderable: false, targets: [0, 1, 2, 3, 4,5]}],
        "columns": [
            {"data": "user", "searchable": false, "order": true},
            {"data": "title", "searchable": false, "order": false},
            {"data": "message", "searchable": false, "order": false},
            {"data": "notification_type", "searchable": false, "order": false},
            {"data": "dates", "searchable": false, "order": false},
            {"data": "action", "searchable": false, "order": false},
        ],
        "ajax": {
            "url": "<?php echo site_url('notification/notification_ajaxs'); ?>",
            "type": "POST",
            "data": function (d) {
                d.searchstr = $("#search").val();
            }
        }
    });

    $('#search').on('keyup', function () {
        oTabless.draw();
    });

    jQuery('body').on('click', '#send', function () {

        $.ajax({
            url: "<?php echo base_url(); ?>notification/sendPush",
            type: "post",
            data: $("#sendNotification").serialize(),
            dataType:'json',
            success: function (data) {
                if(data.status == 1){
                    $("#pushMessageShow").html("<div class='alert alert-success'>"+data.message+"</div><div id='background-wrap'><div class='bubble x1'></div><div class='bubble x2'></div><div class='bubble x3'></div><div class='bubble x4'></div><div class='bubble x5'></div><div class='bubble x6'></div><div class='bubble x7'></div><div class='bubble x8'></div><div class='bubble x9'></div><div class='bubble x10'></div></div>");
                }else{
                    $("#pushMessageShow").html(data.message);
                }
                setTimeout(function(){
                $("#pushMessageShow").html("");
               }, 3000);
            }
        });
    });

</script>

