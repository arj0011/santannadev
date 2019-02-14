$(document).ready(function () {
//    $('input:radio[name="new_user"]').change(function () {
//     if ($(this).val() == 'Yes') {
//         alert("hello");
//     }
// });


});


function loadTableplan() {
    updateTablePlan(!0), $(".tableplan").each(function () {
        if ("0" != $(this).attr("roomheight")) {
            var a = 100 * $(this).attr("roomheight") / $(this).attr("roomwidth");
            $(this).css("padding-top", a + "%")
        } else
            $(this).css("padding", "10px")
    }), $("#roomSelector .room").click(function () {
        changeRoom($(this).attr("room"))
    }), $(".tableplan:first").addClass("current"), $("#roomSelector .room:first").addClass("current"), $(".tableplan .sp-table").click(function (a) {
        if ($("#body").hasClass("booking"))
            $(this).hasClass("unavailable") || $(this).parents(".tableplan").hasClass("locked") || ($("#tables").attr("preselected", ""), $(this).hasClass("selected") ? ($(this).removeClass("selected"), $("#tables #table" + $(this).attr("tableid")).removeClass("selected")) : ($(this).addClass("selected"), $("#tables #table" + $(this).attr("tableid")).addClass("selected")), countSeats());
        else {
            $(".tableInfo").remove(), $(".tableplan .sp-table").removeClass("selected"), $(this).addClass("selected");
            var d, b = "",
                    c = "";
            void 0 != $(this).find(".active").attr("booking") && (d = void 0 == $(this).find(".active").attr("name") ? "(" + $("#roomSelector").attr("noname") + ")" : $(this).find(".active").attr("name"), b = '<div class="active" booking="' + $(this).find(".active").attr("booking") + '"><div class="label">' + $("#roomSelector").attr("current") + '</div><div class="booking"><div class="time">' + $(this).find(".active").attr("fromtime") + "-" + $(this).find(".active").attr("totime") + '</div><div class="guest">' + d + '</div><div class="persons">' + $(this).find(".active").attr("persons") + " " + $("#roomSelector").attr("pers") + '</div></div><div class="tableButtons"><span class="early-free"><i class="fa fa-sign-out" aria-hidden="true"></i>' + $("#roomSelector").attr("early-free") + "</span></div></div>"), void 0 != $(this).find(".next").attr("booking") && (d = void 0 == $(this).find(".next").attr("name") ? "(" + $("#roomSelector").attr("noname") + ")" : $(this).find(".next").attr("name"), c = '<div class="next" booking="' + $(this).find(".next").attr("booking") + '"><div class="label">' + $("#roomSelector").attr("next") + '</div><div class="booking"><div class="time">' + $(this).find(".next").attr("fromtime") + "-" + $(this).find(".next").attr("totime") + '</div><div class="guest">' + d + '</div><div class="persons">' + $(this).find(".next").attr("persons") + " " + $("#roomSelector").attr("pers") + "</div></div>", void 0 == $(this).find(".active").attr("booking") && (c += '<div class="tableButtons">', void 0 != $("#roomSelector").attr("time") && ($(this).find(".next").attr("from") - $("#roomSelector").attr("time") <= 60 && (c += '<span class="arrived"><i class="fa fa-sign-in" aria-hidden="true"></i>' + $("#roomSelector").attr("arrived") + "</span>"), 1 * $("#roomSelector").attr("time") > 1 * $(this).find(".next").attr("from") && (c += '<span class="noshow"><i class="fa fa-ban" aria-hidden="true"></i>' + $("#roomSelector").attr("noshow") + "</span>")), c += "</div>"), c += "</div>");
            var e = '<div class="tableInfo popup" tableid="' + $(this).attr("tableid") + '"><div class="name">' + $(this).find(".name").html() + '</div><div class="tableButtons"><span class="new-booking"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>' + $("#roomSelector").attr("new-booking") + "</span>";
            void 0 == $(this).find(".active").attr("booking") && "-1" != $("#roomSelector").attr("time") && (void 0 == $(this).find(".next").attr("booking") || $(this).find(".next").attr("from") - $("#roomSelector").attr("time") > 10) && (e += '<span class="walkin"><i class="fa fa-sign-in" aria-hidden="true"></i>' + $("#roomSelector").attr("walkin") + "</span>"), e += "</div>" + b + c + "</div>", $("body").append(e), $(".tableInfo .tableButtons span").click(function () {
                $(this).hasClass("new-booking") ? openBooking(null, null, 0, null, $(this).parents(".tableInfo").attr("tableid")) : $(this).hasClass("walkin") ? openBooking(null, null, 1, null, $(this).parents(".tableInfo").attr("tableid")) : $(this).hasClass("early-free") ? bookingAction($(this).parents(".active").attr("booking"), "early_free") : $(this).hasClass("arrived") ? bookingAction($(this).parents(".next").attr("booking"), "arrived") : $(this).hasClass("noshow") && bookingAction($(this).parents(".next").attr("booking"), "no_show"), $(".tableInfo").remove()
            }), $(".tableInfo .active .booking, .tableInfo .next .booking").click(function () {
                openBooking($(this).parent().attr("booking"))
            }), $(".tableInfo").css("top", a.pageY + "px").css("left", a.pageX - $(".tableInfo").width() / 2 + "px").show("blind", 300)
        }
    }), $(".tableplan img.svg").svgConvert({
        onComplete: function () {
            $(".tableplan .sp-table").hide().css("visibility", "visible").show("fade", 300)
        }
    })
}

function changeRoom(a) {
    $("#roomSelector .room, .tableplan").removeClass("current"), $("#roomSelector .room[room=" + a + "]").addClass("current"), $(".tableplan[room=" + a + "]").addClass("current")
}

function selectTable(a, b) {
    $("#sp-board .sp-table").removeClass("selected"), $("#sp-board .rotate").remove(), $(a).addClass("selected"), $("#select-room, #sp-tables-add").hide(), $("#tablename").val($(a).find(".name").html()), $("#seats").val($(a).attr("seats")), $("#priority").val($(a).attr("priority")), $("#shapes .shape").removeClass("selected"), $("#shapes .shape.type" + $(a).attr("type")).addClass("selected"), $("#properties").show(), b && $("#tablename").focus(), $(a).hasClass("canrotate") && addRotation(a)
}

function deleteTable() {
    var a = "#sp-board .sp-table.selected";
    if (1 == $(a).length && !saving) {
        var b = !0;
        "" != $(a).attr("tableid") && (confirm($("#sp-board").attr("delete-warning")) ? $("#sp-board").append('<input type="hidden" class="deleted" value="' + $(a).attr("tableid") + '" />') : b = !1), b && ($(a).remove(), $("#select-room, #sp-tables-add").show(), $("#properties").hide())
    }
}

function addRotation(a) {
    0 == $("#sp-board .rotate").length && ($(a).append('<div class="rotate"><i class="fa fa-repeat" aria-hidden="true"></i></div>'), $("#sp-board .rotate").off("click").click(function () {
        var a = $("#sp-board .sp-table.selected"),
                b = $(a).attr("rotation");
        void 0 == b && (b = 0), b = 1 * b + 45, b >= 360 && (b = 0), a.attr("rotation", b), a.removeClass("rotate45 rotate90 rotate135 rotate180 rotate225 rotate270 rotate315"), b > 0 && a.addClass("rotate" + b), changed = !0
    }))
}

function saveTableplan() {
    var a = !1;
    if ($("#sp-board .sp-table").each(function () {
        if ("" == $(this).find(".name").html())
            return statusMsg($("#sp-board").attr("name-missing")), selectTable($(this), !0), a = !0, !1
    }), !a && !saving) {
        var c = $("#save-button").val();
        $("#save-button").prop("disabled", !0).val($("#sp-board").attr("saving")), $("#sp-tables-add .sp-table, #unallocated-tables .sp-table").draggable("disable"), saving = !0;
        var d = new Object,
                e = new Array,
                f = new Array;
        $("#sp-board .deleted").each(function () {
            e.push($(this).val())
        }), $("#sp-board .sp-table").each(function () {
            var a = new Object;
            a.tableId = $(this).attr("tableid"), a.type = $(this).attr("type"), a.seats = $(this).attr("seats"), a.priority = $(this).attr("priority"), a.rotation = $(this).attr("rotation"), a.top = $(this).css("top"), a.left = $(this).css("left"), a.name = $(this).find(".name").html(), f.push(a)
        }), d.roomId = $("#sp-board").attr("room"), d.roomWidth = $("#sp-board").width(), d.roomHeight = $("#sp-board").height(), d.deleted = e, d.tables = f,
                $.ajax({
                    type: "POST",
                    url: base_url + "floors/saveTablePlan",
                    data: {myData:JSON.stringify(d)},
                    cache: !1,
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    dataType: "json",
                    success: function (a) {
                        if (1 == a[0].Status) {
                            var b = $("#sp-board .sp-table[tableid='']");
                            for (i = 0; i < a[0].NewTables.length; i++)
                                $(b[i]).attr("tableid", a[0].NewTables[i]);
                            statusMsg($("#sp-board").attr("changes-saved")), changed = !1
                        } else
                            statusMsg($("#sp-board").attr("changes-not-saved"));
                        $("#save-button").val(c).prop("disabled", !1), $("#sp-tables-add .sp-table, #unallocated-tables .sp-table").draggable("enable"), saving = !1
                    },
                    error: function (a) {
                        statusMsg($("#sp-board").attr("changes-not-saved")), $("#save-button").val(c).prop("disabled", !1), $("#sp-tables-add .sp-table, #unallocated-tables .sp-table").draggable("enable"), saving = !1
                    },
                    jsonp: "jsonp"
                })
    }
}
$(document).ready(function () {
    $("#sp-board img.svg, #sp-tables-add img.svg, #unallocated-tables img.svg, #shapes img.svg").svgConvert({
        onComplete: function () {
            $("#sp-board .sp-table").hide().css("visibility", "visible").show("fade", 300)
        }
    }), $("#sp-board").resizable({
        maxHeight: 2e3,
        maxWidth: 2e3,
        minHeight: 200,
        minWidth: 300,
        stop: function (a, b) {}
    }), $("#sp-board").droppable({
        accept: "#sp-tables-add .sp-table, #unallocated-tables .sp-table",
        drop: function (a, b) {
            var c, d = "",
                    e = "",
                    f = "2",
                    g = b.draggable.attr("seats"),
                    h = b.draggable.attr("type");
            b.draggable.hasClass("new") || (d = b.draggable.find(".name").html(), e = b.draggable.attr("tableid"), f = b.draggable.attr("priority"), b.draggable.remove(), 0 == $("#unallocated-tables .sp-table").length && $("#unallocated-tables").remove());
            var i = "sp-table";
            "1" == b.draggable.attr("canrotate") && (i += " canrotate");
            var j = b.offset.top - $(this).offset().top + "px",
                    k = b.offset.left - $(this).offset().left + "px",
                    l = $("<div></div>").addClass("rel").click(function (a) {
                $(a.target).hasClass("rotate") || selectTable($(this).parent())
            }).html('<div class="display"><div class="name">' + d + '</div><div class="seats">' + g + " " + $("#sp-board").attr("pers") + "</div></div>").append(b.draggable.find("svg").clone());
            c = $("<div></div>").append(l).appendTo(this).addClass(i).css("top", j).css("left", k).attr("tableid", e).attr("seats", g).attr("type", h).attr("priority", f).addClass("priority" + f).draggable({
                containment: "#sp-board",
                revert: !1,
                grid: [5, 5],
                distance: 5,
                scroll: !0,
                cancel: ".rotate",
                drag: function (a, b) {
                    selectTable(this), changed = !0
                }
            }), selectTable(c, !0), changed = !0
        }
    }), $("#sp-tables-add .sp-table").draggable({
        containment: "#tableplan-design",
        helper: "clone",
        revert: "invalid",
        revertDuration: 300,
        grid: [5, 5],
        scroll: !0
    }), $("#unallocated-tables .sp-table").draggable({
        containment: "#tableplan-design",
        revert: "invalid",
        revertDuration: 300,
        grid: [5, 5],
        scroll: !0
    }), $("#sp-board .sp-table").draggable({
        containment: "#sp-board",
        grid: [5, 5],
        distance: 5,
        scroll: !0,
        cancel: ".rotate",
        drag: function (a, b) {
            selectTable(this), changed = !0
        }
    }), $("#sp-board").click(function (a) {
        a.target == this && ($("#sp-board .sp-table").removeClass("selected"), $("#sp-board .rotate").remove(), $("#select-room, #sp-tables-add").show(), $("#properties").hide())
    }), $("#sp-board .sp-table .rel").click(function (a) {
        $(a.target).hasClass("rotate") || selectTable($(this).parent())
    }), $("#done-button").click(function () {
        $("#sp-board .sp-table").removeClass("selected"), $("#sp-board .rotate").remove(), $("#select-room, #sp-tables-add").show(), $("#properties").hide()
    }), $("#tablename").keyup(function () {
        $("#sp-board .sp-table.selected .name").html($(this).val())
    }), $("#tablename").change(function () {
        $("#sp-board .sp-table.selected .name").html($(this).val())
    }), $("#seats").change(function () {
        $("#sp-board .sp-table.selected .seats").html($(this).val() + " " + $("#sp-board").attr("pers")), $("#sp-board .sp-table.selected").attr("seats", $(this).val())
    }), $("#priority").change(function () {
        $("#sp-board .sp-table.selected").attr("priority", $(this).val()), $("#sp-board .sp-table.selected").removeClass("priority0 priority1 priority2 priority3 priority4").addClass("priority" + $(this).val())
    }), $("#shapes .shape").click(function () {
        if (!$(this).hasClass("selected")) {
            $("#shapes .shape").removeClass("selected"), $(this).addClass("selected");
            var a = $("#sp-board .sp-table.selected");
            $(a).find(".svg").remove(), $(a).find(".rel").append($(this).find("svg").clone().css("width", $(this).attr("svgwidth") + "px").css("height", $(this).attr("svgheight") + "px"));
            var b = $("#sp-board .sp-table.selected svg");
            $(b).css("margin-top", "0").css("margin-left", "0"), $(a).removeClass("canrotate rotate45 rotate90 rotate135 rotate180 rotate225 rotate270 rotate315").attr("rotation", "0"), "1" == $(this).attr("canrotate") ? ($(a).addClass("canrotate"), addRotation(a)) : $("#sp-board .rotate").remove(), $(a).width(b[0].getBoundingClientRect().width), $(a).height(b[0].getBoundingClientRect().height), $(a).attr("type", $(this).attr("type")), changed = !0
        }
    }), $(".properties input, .formular select").change(function () {
        changed = !0
    }), $("#room").change(function (a) {
        location = base_url+"floors/table/" + $(this).val(), $(this).val($("#sp-board").attr("room"))
    }), $("#delete-table").click(function () {
        deleteTable()
    }), $("#save-button").click(function () {
        saveTableplan()
    })
}), $(document).on("keydown", function (a) {
    var b = !1;
    if ($(":focus").length > 0) {
        var c = $(":focus")[0];
        ("text" == $(c).attr("type") || "tel" == $(c).attr("type") || "email" == $(c).attr("type") || $(c).is("textarea")) && (b = !0)
    }
    b || 46 != a.keyCode || deleteTable(), (a.metaKey || a.ctrlKey) && "s" === String.fromCharCode(a.which).toLowerCase() && (a.preventDefault(), saveTableplan())
});
var saving = !1



function statusMsg(a) {
    Ply.dialog("alert", a);

}
function changesHandle() {
    $(".formular input, .formular select, #booking input, #booking select").change(function() {
        changed = !0
    }), $(".formular input[type=submit]").off("click").click(function() {
        changed = !1
    })
    

}