(function(a) {
    a.fn.jqChart = function(b, d, f) {
        if (typeof b === "object") d = b;
        else if (typeof b === "string") {
            b = b.toLowerCase();
            if (a.fn.jqChart.methods[b]) return a.fn.jqChart.methods[b].call(this, d, f);
            else a.error("Method " + method + " does not exist on jQuery.jqChart")
        }
        var c = this.data("data");
        if (!c) {
            c = new e(this);
            this.data("data", c)
        }
        c._processOptions(d);
        return this
    };
    a.fn.jqChart.methods = {
        chart: function() {
            return this.data("data")
        },
        destroy: function() {
            var a = this.data("data");
            if (a) {
                a.destroy();
                this.removeData("data")
            }
        },
        options: function() {
            var a = this.data("data");
            return !a ? void 0 : a.options
        },
        option: function(b, c) {
            var a = this.data("data");
            if (!a) return;
            if (!c) return a.options[b];
            a.options[b] = c;
            a._processOptions(a.options)
        },
        update: function(c) {
            var b = this.data("data");
            if (!b) return this;
            var d = a.extend(false, {}, b.options, c || {});
            b._processOptions(d)
        },
        todataurl: function(b) {
            var a = this.data("data");
            return !a ? null : a.toDataURL(b)
        },
        highlightdata: function(b) {
            var a = this.data("data");
            a && a.highlightData(b)
        },
        ismouseover: function() {
            var a = this.data("data");
            return a ? a.isMouseOver : false
        },
        exporttoimage: function(b) {
            var a = this.data("data");
            a && a.exportToImage(b)
        },
        exporttopdf: function(b) {
            var a = this.data("data");
            a && a.exportToPdf(b)
        }
    };
    a.fn.jqChart.defaults = {
        title: {
            margin: 8,
            font: "22px sans-serif"
        },
        tooltips: {
            disabled: false,
            type: "normal",
            borderColor: "auto",
            snapArea: 25,
            highlighting: true,
            highlightingFillStyle: "rgba(204, 204, 204, 0.5)",
            highlightingStrokeStyle: "rgba(204, 204, 204, 0.5)"
        },
        crosshairs: {
            enabled: false,
            snapToDataPoints: true,
            hLine: {
                visible: true,
                strokeStyle: "red"
            },
            vLine: {
                visible: true,
                strokeStyle: "red"
            }
        },
        globalAlpha: 1,
        mouseInteractionMode: "panning",
        mouseWheelInteractionMode: "zooming",
        selectionRect: {
            fillStyle: "rgba(125,125,125,0.2)",
            strokeStyle: "gray",
            lineWidth: 0
        },
        shadows: {
            enabled: false,
            shadowColor: "#cccccc",
            shadowBlur: 8,
            shadowOffsetX: 2,
            shadowOffsetY: 2
        },
       /* watermark: {
            hAlign: "right",
            vAlign: "bottom"
        },*/
        noDataMessage: {
            text: "No data available",
            font: "20px sans-serif"
        },
        exportConfig: {
            server: "http://www.jqchart.com/export/default.aspx",
            method: "post"
        }
    };
    a.fn.jqChart.labelFormatter = function(b, c) {
        return !b ? String(c) : a.jqChartSprintf(b, c)
    };
    a.fn.jqMouseCapture = function(b) {
        var c = a(document);
        this.each(function() {
            var d = a(this),
                e = {};
            d.mousedown(function(h) {
                var f;
                if (b.move) {
                    f = function(a) {
                        b.move.call(d, a, e)
                    };
                    c.mousemove(f)
                }
                var a, g = function() {
                    b.move && c.unbind("mousemove", f);
                    c.unbind("mouseup", a)
                };
                if (b.up) a = function(a) {
                    g();
                    return b.up.call(d, a, e)
                };
                else a = g;
                c.mouseup(a);
                h.preventDefault();
                return b.down.call(d, h, e)
            })
        });
        return this
    };
    a.jqChartSprintf = function() {
        function e(a, c, e, d) {
            var b = a.length >= c ? "" : Array(1 + c - a.length >>> 0).join(e);
            return d ? a + b : b + a
        }

        function d(a, d, b, c, g) {
            var f = c - a.length;
            if (f > 0)
                if (b || !g) a = e(a, c, " ", b);
                else a = a.slice(0, d.length) + e("", f, "0", true) + a.slice(d.length);
            return a
        }

        function c(b, f, a, g, i, h, j) {
            var c = b >>> 0;
            a = a && c && ({
                "2": "0b",
                "8": "0",
                "16": "0x"
            })[f] || "";
            b = a + e(c.toString(f), h || 0, "0", false);
            return d(b, a, g, i, j)
        }

        function g(a, c, e, b, f) {
            if (b != null) a = a.slice(0, b);
            return d(a, "", c, e, f)
        }
        var b = arguments,
            f = 0,
            h = b[f++];
        return h.replace(a.jqChartSprintf.regex, function(t, s, q, a, w, h, m) {
            if (t == "%%") return "%";
            for (var j = false, n = "", k = false, l = false, r = 0; q && r < q.length; r++) switch (q.charAt(r)) {
                case " ":
                    n = " ";
                    break;
                case "+":
                    n = "+";
                    break;
                case "-":
                    j = true;
                    break;
                case "0":
                    k = true;
                    break;
                case "#":
                    l = true
            }
            if (!a) a = 0;
            else if (a == "*") a = +b[f++];
            else if (a.charAt(0) == "*") a = +b[a.slice(1, -1)];
            else a = +a;
            if (a < 0) {
                a = -a;
                j = true
            }
            if (!isFinite(a)) throw new Error("sprintf: (minimum-)width must be finite");
            if (!h) h = "fFeE".indexOf(m) > -1 ? 6 : m == "d" ? 0 : void 0;
            else if (h == "*") h = +b[f++];
            else if (h.charAt(0) == "*") h = +b[h.slice(1, -1)];
            else h = +h;
            var i = s ? b[s.slice(0, -1)] : b[f++];
            switch (m) {
                case "s":
                    return g(String(i), j, a, h, k);
                case "c":
                    return g(String.fromCharCode(+i), j, a, h, k);
                case "b":
                    return c(i, 2, l, j, a, h, k);
                case "o":
                    return c(i, 8, l, j, a, h, k);
                case "x":
                    return c(i, 16, l, j, a, h, k);
                case "X":
                    return c(i, 16, l, j, a, h, k).toUpperCase();
                case "u":
                    return c(i, 10, l, j, a, h, k);
                case "i":
                case "d":
                    var o = parseInt(+i),
                        p = o < 0 ? "-" : n;
                    i = p + e(String(Math.abs(o)), h, "0", false);
                    return d(i, p, j, a, k);
                case "e":
                case "E":
                case "f":
                case "F":
                case "g":
                case "G":
                    var o = +i,
                        p = o < 0 ? "-" : n,
                        v = (["toExponential", "toFixed", "toPrecision"])["efg".indexOf(m.toLowerCase())],
                        u = (["toString", "toUpperCase"])["eEfFgG".indexOf(m) % 2];
                    i = p + Math.abs(o)[v](h);
                    return d(i, p, j, a, k)[u]();
                default:
                    return t
            }
        })
    };
    a.jqChartSprintf.regex = /%%|%(\d+\$)?([-+#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuidfegEG])/g;
    a.jqChartDateFormatter = function() {
        var e = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
            d = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
            c = /[^-+\dA-Z]/g,
            b = function(a, b) {
                a = String(a);
                b = b || 2;
                while (a.length < b) a = "0" + a;
                return a
            };
        return function(f, h, l) {
            var k = a.jqChartDateFormat,
                g = k.amPm;
            if (arguments.length == 1 && Object.prototype.toString.call(f) == "[object String]" && !/\d/.test(f)) {
                h = f;
                f = undefined
            }
            f = f ? new Date(f) : new Date;
            if (isNaN(f)) throw SyntaxError("invalid date");
            h = String(k.masks[h] || h || "ddd mmm dd yyyy HH:MM:ss");
            if (h.slice(0, 4) == "UTC:") {
                h = h.slice(4);
                l = true
            }
            var j = l ? "getUTC" : "get",
                o = f[j + "Date"](),
                r = f[j + "Day"](),
                n = f[j + "Month"](),
                u = f[j + "FullYear"](),
                i = f[j + "Hours"](),
                s = f[j + "Minutes"](),
                t = f[j + "Seconds"](),
                m = f[j + "Milliseconds"](),
                p = l ? 0 : f.getTimezoneOffset(),
                q = {
                    d: o,
                    dd: b(o),
                    ddd: k.dayNames[r],
                    dddd: k.dayNames[r + 7],
                    m: n + 1,
                    mm: b(n + 1),
                    mmm: k.monthNames[n],
                    mmmm: k.monthNames[n + 12],
                    yy: String(u).slice(2),
                    yyyy: u,
                    h: i % 12 || 12,
                    hh: b(i % 12 || 12),
                    H: i,
                    HH: b(i),
                    M: s,
                    MM: b(s),
                    s: t,
                    ss: b(t),
                    l: b(m, 3),
                    L: b(m > 99 ? Math.round(m / 10) : m),
                    t: i < 12 ? g[0].charAt(0) || "" : g[1].charAt(0) || "",
                    tt: i < 12 ? g[0] : g[1],
                    T: i < 12 ? g[2].charAt(0) || "" : g[3].charAt(0) || "",
                    TT: i < 12 ? g[2] : g[3],
                    Z: l ? "UTC" : (String(f).match(d) || [""]).pop().replace(c, ""),
                    o: (p > 0 ? "-" : "+") + b(Math.floor(Math.abs(p) / 60) * 100 + Math.abs(p) % 60, 4),
                    S: k.s(o)
                };
            return h.replace(e, function(a) {
                return a in q ? q[a] : a.slice(1, a.length - 1)
            })
        }
    }();
    a.jqChartDateFormat = {
        masks: {
            shortDate: "m/d/yyyy",
            shortTime: "h:MM TT",
            longTime: "h:MM:ss TT"
        },
        dayNames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        amPm: ["am", "pm", "AM", "PM"],
        s: function(a) {
            return a < 11 || a > 13 ? (["st", "nd", "rd", "th"])[Math.min((a - 1) % 10, 3)] : "th"
        },
        monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
    };

    function bc(a) {
        this.callback = a;
        this.animations = []
    }
    bc.prototype = {
        begin: function() {
            if (d.use_excanvas) return;
            var a = this.animations;
            if (!a || a.length == 0) return;
            this.stopped = false;
            for (var e = new Date, b = 0; b < a.length; b++) {
                var c = a[b];
                c.begin(e)
            }
            this.animate()
        },
        animate: function() {
            if (this.stopped) return;
            for (var d = this.animations, g = new Date, b = false, c = 0; c < d.length; c++) {
                var e = d[c],
                    f = e.animate(g);
                b = b || f
            }
            if (!b) return;
            this.callback();
            ac(a.proxy(this.animate, this))
        },
        stop: function() {
            this.stopped = true
        },
        clear: function() {
            this.animations = []
        },
        addAnimation: function(a) {
            this.animations.push(a)
        }
    };
    var ac = function() {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(a) {
            return window.setTimeout(function() {
                a()
            }, 25)
        }
    }();

    function hc(e) {
        var c = e,
            a = false,
            b = false;

        function d() {
            if (a) {
                c();
                ac(d);
                b = true;
                a = false
            } else b = false
        }
        this.kick = function() {
            a = true;
            !b && d()
        };
        this.end = function(d) {
            var e = c;
            if (!d) return;
            if (!b) d();
            else {
                c = a ? function() {
                    e();
                    d()
                } : d;
                a = true
            }
        }
    }

    function ub(a, b, c, d, e) {
        var f = {
            enabled: true,
            delayTime: 0,
            duration: 2
        };
        if (!a) return;
        this.enabled = !(a.enabled === false);
        this.delayTime = a.delayTime || 0;
        this.duration = a.duration || 2;
        this.from = d;
        this.to = e;
        this.object = b;
        this.option = c
    }
    ub.prototype = {
        begin: function(a) {
            this.startTime = g.addSeconds(a, this.delayTime);
            this.endTime = g.addSeconds(this.startTime, this.duration);
            this.timeDiff = this.endTime.getTime() - this.startTime.getTime();
            this.valueDiff = this.to - this.from;
            this.object[this.option] = this.from;
            this.lastIsSet = false
        },
        animate: function(a) {
            if (a >= this.endTime) {
                if (!this.lastIsSet) {
                    this.object[this.option] = this.to;
                    this.lastIsSet = true;
                    return true
                }
                return false
            }
            if (a > this.startTime) {
                var c = a.getTime() - this.startTime.getTime(),
                    b = this.from + this.valueDiff * c / this.timeDiff;
                if (b === this.to) this.lastIsSet = true;
                this.object[this.option] = b
            }
            return true
        }
    };

    function zb(a, b, c, d, e) {
        ub.call(this, a, b, c, d, e)
    }
    zb.prototype = new ub;
    zb.constructor = zb;
    zb.prototype.begin = function(a) {
        this.startTime = g.addSeconds(a, this.delayTime);
        this.endTime = g.addSeconds(this.startTime, this.duration);
        this.object[this.option] = this.from;
        this.lastIsSet = false
    };
    zb.prototype.animate = function(a) {
        if (a >= this.endTime) {
            if (!this.lastIsSet) {
                this.object[this.option] = this.to;
                this.lastIsSet = true;
                return true
            }
            return false
        }
        return true
    };

    function Q(a) {
        this.view = a
    }
    Q.prototype = {
        canStart: function() {
            return false
        },
        start: function() {},
        keyDown: function() {},
        keyUp: function() {},
        mouseMove: function() {},
        mouseDown: function() {},
        mouseUp: function() {},
        touchStart: function() {},
        touchMove: function() {},
        touchEnd: function() {},
        mouseWheel: function() {},
        stop: function() {},
        stopTool: function() {
            this.view.currentTool == this && this.view.setCurrentTool(null)
        }
    };

    function U(a) {
        Q.call(this, a)
    }
    U.prototype = new Q;
    U.constructor = U;
    U.prototype.mouseDown = function(d) {
        for (var b = this.view.mouseDownTools, a = 0; a < b.length; a++) {
            var c = b[a];
            if (c.canStart(d)) {
                this.view.setCurrentTool(c);
                return
            }
        }
    };
    U.prototype.mouseMove = function(d) {
        for (var b = this.view.mouseMoveTools, a = 0; a < b.length; a++) {
            var c = b[a];
            if (c.canStart(d)) {
                this.view.setCurrentTool(c);
                return
            }
        }
        this.view._processMouseMove(d)
    };
    U.prototype.touchStart = function(d) {
        for (var b = this.view.touchStartTools, a = 0; a < b.length; a++) {
            var c = b[a];
            if (c.canStart(d)) {
                this.view.setCurrentTool(c);
                return
            }
        }
        this.view._processTouchStart(d)
    };
    U.prototype.touchMove = function(d) {
        for (var b = this.view.touchMoveTools, a = 0; a < b.length; a++) {
            var c = b[a];
            if (c.canStart(d)) {
                this.view.setCurrentTool(c);
                return
            }
        }
        this.view._processTouchMove(d)
    };

    function d(a) {
        if (!a) return;
        this.shapes = [];
        this.mouseDownTools = [];
        this.mouseMoveTools = [];
        this.touchStartTools = [];
        this.touchMoveTools = [];
        this.defaultTool = new U(this);
        this.currentTool = this.defaultTool;
        this._createElements(a)
    }
    d.setShadows = function(d, a, e) {
        if (!e) return;
        var c = e.options.shadows;
        d.shadowColor = !b.isNull(a.shadowColor) ? a.shadowColor : c.shadowColor;
        d.shadowBlur = !b.isNull(a.shadowBlur) ? a.shadowBlur : c.shadowBlur;
        d.shadowOffsetX = !b.isNull(a.shadowOffsetX) ? a.shadowOffsetX : c.shadowOffsetX;
        d.shadowOffsetY = !b.isNull(a.shadowOffsetY) ? a.shadowOffsetY : c.shadowOffsetY
    };
    d.support_canvas = function() {
        return !!document.createElement("canvas").getContext
    };
    d.use_excanvas = !d.support_canvas() ? true : false;
    d.isMouseDevice = !!("onmousedown" in window || "onmousedown" in window.document);
    d.isTouchDevice = !!("ontouchstart" in window);
    d.isGestureDevice = !!("ongesturestart" in window);
    d.isPointerDevice = window.navigator.pointerEnabled || window.navigator.msPointerEnabled;
    d.checkIfRetinaDisplay = function() {
        var a = "(-webkit-min-device-pixel-ratio: 1.5),                      (min--moz-device-pixel-ratio: 1.5),                      (-o-min-device-pixel-ratio: 3/2),                      (min-resolution: 1.5dppx)";
        return window.devicePixelRatio >= 2 ? true : window.matchMedia && window.matchMedia(a).matches ? true : false
    };
    d.isRetinaDisplay = d.checkIfRetinaDisplay();
    d.prototype = {
        _createElements: function(c) {
            this.elem = c;
            var b = this;
            if (d.isMouseDevice) {
                c.mouseenter(function(a) {
                    b._mouseEnter(a)
                });
                c.mouseleave(function(a) {
                    b._mouseLeave(a)
                });
                c.jqMouseCapture({
                    down: a.proxy(b._mouseDown, this),
                    move: a.proxy(b._mouseMove, this),
                    up: a.proxy(b._mouseUp, this)
                });
                c.mousemove(function(a) {
                    !b.isMouseDown && b._mouseMove(a)
                })
            }
            c.keydown(function(a) {
                b._keyDown(a)
            });
            c.keyup(function(a) {
                b._keyUp(a)
            });
            c.click(function(a) {
                b._click(a)
            });
            c.dblclick(function(a) {
                b._dblClick(a)
            });
            c.resize(function() {
                b._resize()
            });
            a(window).bind("resize.jqChart", function() {
                b._resize()
            });
            a(window.document).bind("keydown.jqChart", function(a) {
                b._keyDown(a)
            });
            c.bind("mousewheel", function(e, d, a, c) {
                b._mouseWheel(e, d, a, c)
            });
            if (d.isTouchDevice) {
                c.bind("touchstart", function(a) {
                    b._touchStart(a)
                });
                c.bind("touchmove", function(a) {
                    b._touchMove(a)
                });
                c.bind("touchend", function(a) {
                    b._touchEnd(a)
                })
            } else if (d.isPointerDevice) {
                a.each(["pointerdown", "MSPointerDown"], function() {
                    c.bind(this, function(a) {
                        b._touchStart(a)
                    })
                });
                a.each(["pointermove", "MSPointerMove"], function() {
                    c.bind(this, function(a) {
                        b._touchMove(a)
                    })
                });
                a.each(["pointerup", "pointercancel", "MSPointerUp", "MSPointerCancel"], function() {
                    c.bind(this, function(a) {
                        b._touchEnd(a)
                    })
                })
            }
            this.canvas = this._createCanvas();
            this.tooltip = this._createTooltip();
            this.shapeRenderer = new O(this.canvas, this);
            this.ctx = this.shapeRenderer.ctx
        },
        _setOptions: function() {
            this.locOffset = null;
            var a = this.elem;
            this.originalCursor = "auto";
            !a.hasClass(this.pluginClass) && a.addClass(this.pluginClass);
            a.css("position") == "static" && a.css("position", "relative");
            !this.tooltip.hasClass(this.tooltipClass) && this.tooltip.addClass(this.tooltipClass)
        },
        _createHighlightRenderer: function() {
            if (d.use_excanvas) {
                this.hlCanvas = this._createCanvas(true);
                var b = a('<div style="position:absolute"></div>');
                this.elem.append(b);
                b.append(this.hlCanvas);
                this.hlRenderer = new O(this.hlCanvas, this);
                this.hlRenderer.div = b
            } else {
                this.hlCanvas = this._createCanvas();
                this.hlRenderer = new O(this.hlCanvas, this)
            }
            this.hlRenderer.isHighlighting = true
        },
        _createCanvas: function(c) {
            var b = document.createElement("canvas");
            b.width = 10;
            b.height = 10;
            a(b).css({
                position: "absolute",
                left: 0,
                top: 0
            });
            if (d.use_excanvas) {
                window.G_vmlCanvasManager.init_(document);
                window.G_vmlCanvasManager.initElement(b)
            }!c && this.elem.append(b);
            return b
        },
        _setCanvasSize: function(a, c, b) {
            a.width = c;
            a.height = b
        },
        _setRetinaDispOpts: function(a, d, c) {
            a.setAttribute("width", 2 * d);
            a.setAttribute("height", 2 * c);
            var b = a.getContext("2d");
            b.scale(2, 2)
        },
        _createTooltip: function() {
            var b = a('<div style="position:absolute;display:none"></div>');
            this.elem.append(b);
            return b
        },
        _addTrialWatermark: function(c) {
            var b = window.location.host.indexOf("www.jquerychart.com");
            if (b != -1) return;
            b = window.location.host.indexOf("www.jqchart.com");
            if (b != -1) return;
            var a = new l("www.jqchart.com");
            a.chart = this;
            a.font = "14px sans-serif";
            a.fillStyle = "gray";
            a.measure(this.ctx);
            a.x = this._width - a.width - 16;
            a.y = this._height - a.height;
            c.push(a)
        },
        _measure: function() {},
        _arrange: function() {},
        _keyDown: function(a) {
            this.currentTool.keyDown(a)
        },
        _keyUp: function(a) {
            this.currentTool.keyUp(a)
        },
        _mouseEnter: function() {
            this.isMouseOver = true
        },
        _mouseLeave: function() {
            this._clearRenderers();
            this.locOffset = null;
            this.isMouseOver = false
        },
        _mouseDown: function(a) {
            this.isMouseDown = true;
            this._oldShape != null && this._triggerShapeEvent("MouseDown", this._oldShape);
            this.currentTool.mouseDown(a)
        },
        _mouseMove: function(a) {
            this._initMouseInput(a);
            this._processMouseEvents();
            this.currentTool.mouseMove(a)
        },
        _mouseUp: function(a) {
            this._oldShape != null && this._triggerShapeEvent("MouseUp", this._oldShape);
            this.isMouseDown = false;
            this.currentTool.mouseUp(a)
        },
        _mouseWheel: function(d, c, a, b) {
            this.currentTool.mouseWheel(d, c, a, b)
        },
        _click: function() {
            this._oldShape != null && this._triggerShapeEvent("Click", this._oldShape)
        },
        _dblClick: function() {
            this._oldShape != null && this._triggerShapeEvent("DblClick", this._oldShape)
        },
        _touchStart: function(a) {
            this._initTouchInput(a);
            this.isTouchOver = true;
            this._processTouchEvents();
            this.currentTool.touchStart(a)
        },
        _touchMove: function(b) {
            this._initTouchInput(b);
            var a = this.touchInput[0];
            this.isTouchOver = this.contains(a.locX, a.locY);
            this._processTouchEvents();
            this.currentTool.touchMove(b)
        },
        _touchEnd: function(a) {
            this._initTouchInput(a);
            if (this._oldShape != null) {
                this._triggerShapeEvent("TouchEnd", this._oldShape);
                this._oldShape = null
            }
            this.currentTool.touchEnd(a)
        },
        _initMouseInput: function(b) {
            this.isMouseOver = true;
            var c = b.pageX,
                d = b.pageY;
            if (!this.locOffset) this.locOffset = this._getLocOffset();
            var a = this.locOffset,
                f = c - a.left,
                g = d - a.top,
                e = {
                    x: c,
                    y: d,
                    locX: f,
                    locY: g
                };
            this.mouseInput = e
        },
        _initTouchInput: function(j) {
            var b = j.originalEvent.touches;
            if (!this.locOffset) this.locOffset = this._getLocOffset();
            for (var c = this.locOffset, d = [], a = 0; a < b.length; a++) {
                var e = b[a],
                    f = e.pageX,
                    g = e.pageY,
                    h = f - c.left,
                    i = g - c.top;
                d.push({
                    x: f,
                    y: g,
                    locX: h,
                    locY: i
                })
            }
            this.touchInput = d
        },
        _getLocOffset: function() {
            var b = this.elem.offset(),
                c = parseFloat(a(document.body).css("borderLeftWidth"));
            if (!isNaN(c)) b.left += c;
            var d = parseFloat(a(document.body).css("borderTopWidth"));
            if (!isNaN(d)) b.top += d;
            return b
        },
        getAllTouches: function(h) {
            for (var f = ["touches", "changedTouches"], b = [], c = 0; c < f.length; c++)
                for (var e = h.originalEvent[f[c]], d = 0; d < e.length; d++) {
                    var g = e[d];
                    a.inArray(g, b) == -1 && b.push(g)
                }
            return b
        },
        _resize: function() {
            var c = this.elem,
                e = c.width(),
                d = c.height();
            if (e != this._width || d != this._height) {
                var a = this.options;
                if (a) {
                    if (!b.isNull(a.width)) a.width = e;
                    if (!b.isNull(a.height)) a.height = d
                }
                this._setOptions(this.options)
            }
        },
        _clearRenderers: function() {
            if (this._oldTShapes) {
                this._oldTShapes = null;
                this.elem.trigger("dataHighlighting", null)
            }
            this._oldShape = null;
            this._resetCursor();
            this.hideTooltip();
            this.hlRenderer && this.hlRenderer._clear()
        },
        _processMouseMove: function() {
            this._processTooltips(this.mouseInput)
        },
        _processMouseEvents: function() {
            var b = this.mouseInput;
            if (!b) return;
            var a = this.hitTest(b.locX, b.locY);
            if (this._oldShape != null && this._oldShape == a) this._triggerShapeEvent("MouseMove", this._oldShape);
            else {
                if (this._oldShape != null) {
                    this._triggerShapeEvent("MouseLeave", this._oldShape);
                    this._oldShape.cursor && this._resetCursor()
                }
                if (a != null) {
                    this._triggerShapeEvent("MouseEnter", a);
                    a.cursor && this.elem.css("cursor", a.cursor)
                }
                this._oldShape = a
            }
        },
        _processTouchEvents: function() {
            var b = this.touchInput[0];
            if (!b) return;
            var a = this.hitTest(b.locX, b.locY);
            if (this._oldShape != null && this._oldShape == a) this._triggerShapeEvent("TouchMove", this._oldShape);
            else {
                this._oldShape != null && this._triggerShapeEvent("TouchEnd", this._oldShape);
                a != null && this._triggerShapeEvent("TouchStart", a);
                this._oldShape = a
            }
        },
        _processTooltips: function(c) {
            var e = this.hasTooltips,
                f = this.hasHighlighting;
            if (!e && !f) return;
            var g = this.options.tooltips.snapArea,
                a = this._getTooltipShapes(c.locX, c.locY, g, c),
                d = true;
            if (this._oldTShapes == null || !b.compareArrays(this._oldTShapes, a)) {
                if (a != null) {
                    d = this._initTooltip(a);
                    this._highlightShapes(a)
                }
                if (a) this._oldTShapes = a
            } else d = false;
            a && e && d && this._setTooltipPos(a, c)
        },
        _setTooltipPos: function(e, j) {
            var i = this.tooltip.outerWidth(true),
                h = this.tooltip.outerHeight(true),
                g = this._width,
                f = this._height,
                d = e[0]._getTooltipPosition(j, i, h, g, f),
                c = d.y,
                k = e.length,
                l = this;
            if (k > 1) {
                c = 0;
                a.each(e, function() {
                    c += this._getTooltipPosition(j, i, h, g, f).y
                });
                c /= k
            }
            c = b.fitInRange(c, 0, f - h);
            d.x = b.fitInRange(d.x, 0, g - i);
            this.tooltip.stop();
            this.tooltip.animate({
                left: d.x,
                top: c
            }, 100)
        },
        _processTouchStart: function() {
            this._processTooltips(this.touchInput[0])
        },
        _processTouchMove: function() {
            this._processTooltips(this.touchInput[0])
        },
        _initTooltip: function(b) {
            if (!this.hasTooltips || !b || !b.length) return false;
            var g = b.length,
                f = "",
                d;
            if (g == 1) d = b[0].context;
            else {
                d = [];
                a.each(b, function() {
                    d.push(this.context)
                })
            }
            if (!d) return false;
            var e = new a.Event("tooltipFormat");
            this.elem.trigger(e, [d]);
            var h = this;
            if (e.result === false) {
                this.hideTooltip();
                return false
            }
            if (e.result) f = e.result;
            else f = this._getTooltipText(b);
            if (!f) return false;
            this.tooltip.html(f);
            var c = this.options.tooltips;
            if (g == 1)
                if (c.borderColor) {
                    c.borderColor == "auto" && this.tooltip.css("border-color", b[0].getTooltipColor());
                    this.tooltip.css("border-color", c.borderColor)
                }
            c.background && this.tooltip.css("background", c.background);
            this.showTooltip();
            return true
        },
        _highlightShapes: function(g) {
            if (!this.hasHighlighting) return;
            this.hlRenderer._clear();
            var f = this.options.tooltips,
                b = [];
            a.each(g, function(d, c) {
                var a = c._createHighlightShape(f.highlightingFillStyle, f.highlightingStrokeStyle);
                b.push(a)
            });
            var c;
            if (b.length == 1) {
                c = b[0].context;
                c.shape = b[0]
            } else {
                c = [];
                a.each(b, function() {
                    c.push(this.context);
                    c.shape = this
                })
            }
            var e = new a.Event("dataHighlighting");
            this.elem.trigger(e, [c]);
            if (e.result !== false)
                if (d.use_excanvas) this.hlRenderer._render(b);
                else {
                    this.hlRenderer.ctx.save();
                    this._setClip && this._setClip(this.hlRenderer.ctx);
                    this.hlRenderer._render(b);
                    this.hlRenderer.ctx.restore()
                }
        },
        _getClosestShape: function(b, g, f) {
            for (var a = b[0], d = 1; d < b.length; d++) {
                var e = b[d];
                if (c.compare(a, e, f, g) == false) a = e
            }
            return a
        },
        _getTooltip: function() {
            return "Tooltip"
        },
        _getTooltipText: function(c) {
            var b = "",
                d = this;
            a.each(c, function() {
                var a = d._getTooltip(this);
                if (a) b += a
            });
            return b
        },
        _getTooltipShapes: function(h, i, c, b) {
            if (!c) c = 0;
            for (var e = [], f = this.shapes.length - 1; f >= 0; f--) {
                var a = this.shapes[f];
                if (!a.context || a.isLegendItem || a.isAxisLabel) continue;
                var d = a.hitTest(b.locX, b.locY, c);
                if (typeof a.hitTestNonHV == "function") d = a.hitTestNonHV(b.locX, b.locY, c);
                if (d === true) e.push(a);
                else d && e.push(d)
            }
            var g = this._getClosestShape(e, c, b);
            return !g ? null : [g]
        },
        _resetCursor: function() {
            this.elem.css("cursor") != this.originalCursor && this.elem.css("cursor", this.originalCursor)
        },
        _triggerShapeEvent: function(b, a) {
            a.context.shape = a;
            this.elem.trigger("dataPoint" + b, a.context)
        },
        _exportToImage: function(d, e) {
            d = d || {};
            var c = a.extend({}, e, d);
            c.type = d.type || "image/png";
            if (d.fileName) c.fileName = b.replaceTextForExport(d.fileName);
            else switch (c.type) {
                case "image/jpeg":
                    c.fileName = "jqChart.jpg";
                    break;
                case "image/png":
                default:
                    c.fileName = "jqChart.png"
            }
            var f = this.toJSON(c);
            this._doRequest(c.server, f, c.method)
        },
        _exportToPdf: function(d, e) {
            d = d || {};
            var c = a.extend({}, e, d);
            c.type = "application/pdf";
            c.fileName = b.replaceTextForExport(d.fileName) || "chart.pdf";
            var f = this.toJSON(c);
            this._doRequest(c.server, f, c.method)
        },
        _toJSON: function(c, d) {
            var f = this,
                a = "{type:'" + d.type + "',fileName:'" + d.fileName + "'";
            a += ",width:" + f._width + ",height:" + f._height + ",shapes:[";
            for (var b = 0; b < c.length; b++) {
                var e = c[b].toJSON();
                if (e) {
                    a += "{" + e + "}";
                    if (b != c.length - 1) a += ","
                }
            }
            return a + "]}"
        },
        _doRequest: function(b, a, d) {
            if (b && a) {
                var c = '<input type="hidden" name="canvas" value="' + a + '" />';
                jQuery('<form action="' + b + '" method="' + (d || "post") + '">' + c + "</form>").appendTo("body").submit().remove()
            }
        },
        getCurrentTool: function() {
            return this.currentTool
        },
        setCurrentTool: function(a) {
            if (this.currentTool == a) return;
            this.currentTool != null && this.currentTool.stop();
            if (!a) this.currentTool = this.defaultTool;
            else this.currentTool = a;
            this.currentTool && this.currentTool.start()
        },
        contains: function(a, b) {
            return a >= 0 && a <= this._width && b >= 0 && b <= this._height
        },
        hitTest: function(d, e, b) {
            if (!b) b = 0;
            for (var c = this.shapes.length - 1; c >= 0; c--) {
                var a = this.shapes[c];
                if (!a.context) continue;
                if (a.hitTest(d, e, b)) return a
            }
        },
        showTooltip: function() {
            this.tooltip.show()
        },
        hideTooltip: function() {
            this.tooltip.hide()
        },
        stringFormat: function(c, b) {
            return a.jqChartSprintf(b, c)
        },
        clear: function() {
            this._clearRenderers();
            this.shapeRenderer._clear()
        },
        render: function() {},
        destroy: function() {
            var b = this.elem;
            b.empty();
            b.unbind();
            a(window).unbind("resize.jqChart");
            a(window.document).unbind("keydown.jqChart");
            var c = this.options;
            b.hasClass(this.pluginClass) && b.removeClass(this.pluginClass)
        },
        toDataURL: function(a) {
            return d.use_excanvas ? null : this.canvas.toDataURL(a)
        },
        getShapesPerData: function(d) {
            var b = [],
                c = this.shapes;
            a.each(d, function() {
                var d = this;
                a.each(c, function() {
                    if (this.context) {
                        var e = this.context.points;
                        if (e) {
                            for (var c = 0; c < e.length; c++)
                                if (e[c].dataItem == d) {
                                    var a = jQuery.extend({}, this);
                                    a.context = e[c];
                                    a.context.series = this.context.series;
                                    a.context.chart = this.context.chart;
                                    var f = {
                                        x: this.pts[2 * c],
                                        y: this.pts[2 * c + 1]
                                    };
                                    a.tooltipOrigin = f;
                                    a.getCenter = function() {
                                        return f
                                    };
                                    b.push(a);
                                    break
                                }
                        } else this.context.dataItem == d && b.push(this)
                    }
                })
            });
            return b
        },
        highlightData: function(b) {
            if (!b) {
                this._clearRenderers();
                return null
            }
            var a = this.getShapesPerData(b);
            if (a.length == 0) return null;
            this._highlightShapes(a);
            this._initTooltip(a);
            var c = a[0].getCenter();
            this._setTooltipPos(a, {
                locX: c.x,
                locY: c.y
            });
            return a
        }
    };

    function kb(a) {
        this._initDefs(a);
        this.setOptions(a)
    }
    kb.prototype = {
        _initDefs: function() {
            a.extend(this, {
                maxInter200Px: 8,
                lblMargin: 4,
                crossOffsetX: 0,
                crossOffsetY: 0,
                origin: 0,
                length: 300,
                x: 0,
                y: 0
            })
        },
        _calculateActualInterval: function(m, l) {
            if (this.interval) return this.interval;
            var h = 1;
            if (!this.getOrientation || this.getOrientation() == "x") h = .8;
            for (var k = h * this.maxInter200Px, e = Math.max(this.length * k / 200, 1), g = l - m, a = g / e, j = Math.pow(10, Math.floor(b.log10(a))), f = [10, 5, 2, 1], c = 0; c < f.length; c++) {
                var i = f[c],
                    d = j * i;
                if (e < g / d) break;
                a = d
            }
            return a
        },
        _setVisibleRanges: function() {
            this.actualVisibleMinimum = b.isNull(this.visibleMinimum) ? this.actualMinimum : this.visibleMinimum;
            this.actualVisibleMaximum = b.isNull(this.visibleMaximum) ? this.actualMaximum : this.visibleMaximum;
            if (a.type(this.actualVisibleMinimum) == "date") this.actualVisibleMinimum = this.actualVisibleMinimum.getTime();
            if (a.type(this.actualVisibleMaximum) == "date") this.actualVisibleMaximum = this.actualVisibleMaximum.getTime();
            if (this.options) {
                this.options.visibleMinimum = this.visibleMinimum;
                this.options.visibleMaximum = this.visibleMaximum
            }
        },
        _setMinMax: function(c, a) {
            if (this.logarithmic) {
                this.actualMinimum = b.isNull(this.minimum) ? c : b.log(this.minimum, this.logBase);
                this.actualMaximum = b.isNull(this.maximum) ? a : b.log(this.maximum, this.logBase)
            } else {
                this.actualMinimum = b.isNull(this.minimum) ? c : this.minimum;
                this.actualMaximum = b.isNull(this.maximum) ? a : this.maximum
            }
        },
        _getNextPosition: function(c, a) {
            return b.round(c + a)
        },
        _getMarkInterval: function(b, c) {
            var a;
            if (b.interval) a = b.interval;
            else if (c) a = this.actualInterval;
            else a = this.actualInterval / 2;
            return a
        },
        _getIntervals: function(c, b) {
            var d = 0;
            if (b && b.intervalOffset) d = b.intervalOffset;
            for (var e = [], f = this._getIntervalStart(this._getActualVisibleMinimum(), c), a = f + d; a <= this._getActualVisibleMaximum(); a = this._getNextPosition(a, c)) e.push(a);
            return e
        },
        _getIntervalStart: function(d, b) {
            var c = d - this.getCrossing(),
                a = this._alignToInterval(c, b);
            if (a < d) a = this._alignToInterval(c + b, b);
            return a
        },
        _alignToInterval: function(c, a) {
            return b.round(b.round(Math.floor(c / a)) * a) + this.getCrossing()
        },
        _createLabel: function(c, d) {
            var b = new l(c);
            b.isAxisLabel = true;
            b.context = {
                chart: this.chart,
                axis: this,
                text: c
            };
            a.extend(b, d);
            this.chart.elem.trigger("axisLabelCreating", b);
            b.measure(this.chart.ctx);
            return b
        },
        _getLabelIntervals: function(a, b) {
            return this._getIntervals(a, b)
        },
        _measureRotatedLabels: function(g) {
            for (var h = this.isAxisVertical, c = 0, b = 0, d = 0; d < g.length; d++) {
                var a = g[d],
                    e = Math.sqrt(a.width * a.width + a.height * a.height),
                    f = a.rotationAngle;
                if (h) {
                    var j = Math.abs(Math.cos(f) * e);
                    c = Math.max(c, j)
                } else {
                    var i = Math.abs(Math.sin(f) * e);
                    b = Math.max(b, i)
                }
            }
            if (this.labels.position == "inside") {
                this.lblsW = c;
                this.lblsH = b;
                return {
                    w: 0,
                    h: 0
                }
            }
            return {
                w: c,
                h: b
            }
        },
        _correctLabelsPositions: function(n) {
            var i = 0,
                h = 0,
                o = this.reversed === true,
                p = this.labels.position == "inside",
                q = this.isAxisVertical,
                g = this.lblMargin;
            if (q) {
                for (var e = [], d = 0; d < n.length; d++) {
                    var a = n[d],
                        f = false,
                        k = a.y;
                    switch (a.textBaseline) {
                        case "middle":
                            k -= a.height / 2;
                            break;
                        case "bottom":
                            k -= a.height
                    }
                    for (var c = 0, c = 0; c < e.length; c++) {
                        var b = e[c];
                        if (o) f = k > b.y + b.h;
                        else f = b.y > k + a.height;
                        if (f) {
                            b.y = k;
                            b.h = a.height;
                            b.w = Math.max(b.w, a.width + g);
                            b.labels.push(a);
                            break
                        }
                    }
                    if (f == false) e[c] = {
                        y: k,
                        h: a.height,
                        w: a.width + g,
                        labels: [a]
                    }
                }
                var m = this.location == "right";
                m = p ? !m : m;
                i = 0;
                for (var d = 0; d < e.length; d++) {
                    for (var b = e[d], c = 0; c < b.labels.length; c++) {
                        var a = b.labels[c];
                        if (m) a.x += i;
                        else a.x -= i
                    }
                    i += b.w
                }
            } else {
                for (var e = [], d = 0; d < n.length; d++) {
                    var a = n[d],
                        j = a.x;
                    switch (a.textAlign) {
                        case "center":
                            j -= a.width / 2;
                            break;
                        case "right":
                            j -= a.width
                    }
                    for (var f = false, c = 0, c = 0; c < e.length; c++) {
                        var b = e[c];
                        if (o) f = b.x > j + a.width + g;
                        else f = j > b.x + b.w + g;
                        if (f) {
                            b.x = j;
                            b.w = a.width;
                            b.h = Math.max(b.h, a.height + g);
                            b.labels.push(a);
                            f = true;
                            break
                        }
                    }
                    if (f == false) e[c] = {
                        x: j,
                        w: a.width,
                        h: a.height + g,
                        labels: [a]
                    }
                }
                var l = this.location == "bottom";
                l = p ? !l : l;
                h = 0;
                for (var d = 0; d < e.length; d++) {
                    for (var b = e[d], c = 0; c < b.labels.length; c++) {
                        var a = b.labels[c];
                        if (l) a.y += h;
                        else a.y -= h
                    }
                    h += b.h
                }
            }
            if (this.labels.position == "inside") {
                this.lblsW = i;
                this.lblsH = h;
                return {
                    w: 0,
                    h: 0
                }
            }
            return {
                w: i,
                h: h
            }
        },
        _removeOverlappedLabels: function(c) {
            var f = 0,
                e = 0,
                h = 0,
                o = 0,
                l = 0,
                i = 0,
                n = 0,
                m = j,
                s = this.reversed === true,
                u = this.labels.position == "inside",
                r = this.isAxisVertical,
                g = this.lblMargin,
                k = 2 * g;
            if (r)
                for (var v = [], b = 0; b < c.length; b++) {
                    var d = b;
                    if (this.reversed) d = c.length - b - 1;
                    var a = c[d],
                        t = false,
                        q = a.y;
                    switch (a.textBaseline) {
                        case "middle":
                            q -= a.height / 2;
                            break;
                        case "bottom":
                            q -= a.height
                    }
                    i = a.y;
                    n = i + a.height + k;
                    if (n < m) m = i;
                    else {
                        a.visible = false;
                        continue
                    }
                    f = Math.max(f, a.width + g)
                } else
                    for (var b = 0; b < c.length; b++) {
                        var d = b;
                        if (this.reversed) d = c.length - b - 1;
                        var a = c[d],
                            p = a.x;
                        switch (a.textAlign) {
                            case "center":
                                p -= a.width / 2;
                                break;
                            case "right":
                                p -= a.width
                        }
                        h = a.x;
                        o = h + a.width + k;
                        if (h > l) l = o;
                        else {
                            a.visible = false;
                            continue
                        }
                        e = Math.max(e, a.height + g)
                    }
            if (this.labels.position == "inside") {
                this.lblsW = f;
                this.lblsH = e;
                return {
                    w: 0,
                    h: 0
                }
            }
            return {
                w: f,
                h: e
            }
        },
        _measure: function() {
            var b = 0;
            if (this.zoomEnabled) b = this.rangeSlider.breadth;
            var a = {
                w: 0,
                h: 0
            };
            if (this.labels)
                if (this.labels.angle) a = this._measureRotatedLabels(this._getLabels());
                else switch (this.labels.resolveOverlappingMode) {
                    case "hide":
                        a = this._removeOverlappedLabels(this._getLabels());
                        break;
                    case "multipleRows":
                    default:
                        a = this._correctLabelsPositions(this._getLabels())
                }
            this.title._measure();
            var c = this.title.height + b + this.lineWidth / 2;
            if (this.isAxisVertical) a.w += c;
            else a.h += c;
            var d = this.margin + this._getMaxOutsideTickMarksLength();
            if (this.isAxisVertical) {
                if (this.isCustomWidth == false) {
                    var f = a.w + d;
                    if (this.width != f) {
                        this.width = f;
                        return true
                    }
                }
            } else if (this.isCustomHeight == false) {
                var e = a.h + d;
                if (this.height != e) {
                    this.height = e;
                    return true
                }
            }
            return false
        },
        _arrange: function() {
            var a = this.x,
                d = this.y,
                c = this.x + this.width,
                e = this.y + this.height;
            switch (this.location) {
                case "left":
                    c = a = this.x + this.width;
                    break;
                case "right":
                    c = a = this.x;
                    break;
                case "top":
                    e = d = this.y + this.height;
                    break;
                case "bottom":
                    e = d = this.y
            }
            if (this.title.text) switch (this.location) {
                case "left":
                    this.title.x = this.title.rotX = this.x;
                    this.title.y = this.title.rotY = this.y + (this.height + this.title.width) / 2;
                    this.title.rotationAngle = b.radians(-90);
                    break;
                case "right":
                    this.title.x = this.title.rotX = this.x + this.width;
                    this.title.y = this.title.rotY = this.y + (this.height - this.title.width) / 2;
                    this.title.rotationAngle = b.radians(90);
                    break;
                case "top":
                    this.title.x = this.x + (this.width - this.title.width) / 2;
                    this.title.y = this.y;
                    break;
                case "bottom":
                    this.title.x = this.x + (this.width - this.title.width) / 2;
                    this.title.y = this.y + this.height - this.title.height
            }
            this.x1 = a;
            this.y1 = d;
            this.x2 = c;
            this.y2 = e;
            this.offset = this.lineWidth / 2
        },
        _updateOrigin: function() {
            if (this.isAxisVertical) {
                this.origin = this.y;
                this.length = this.height
            } else {
                this.origin = this.x;
                this.length = this.width
            }
        },
        _render: function() {
            var b = [],
                e = this._getTickMarks(this.minorTickMarks, false);
            a.merge(b, e);
            var d = this._getTickMarks(this.majorTickMarks, true);
            a.merge(b, d);
            var f = this._getMainLine();
            b.push(f);
            var c = this._getLabels();
            if (this.labels && !this.labels.angle) switch (this.labels.resolveOverlappingMode) {
                case "hide":
                    this._removeOverlappedLabels(c);
                    break;
                case "multipleRows":
                default:
                    this._correctLabelsPositions(c)
            }
            this._filterLabels(c);
            this.title._render(b);
            a.merge(b, c);
            return {
                postShapes: b,
                contextShapes: c
            }
        },
        _getMainLine: function() {
            var b = this.crossOffsetX,
                c = this.crossOffsetY,
                a = new i(this.x1 + b, this.y1 + c, this.x2 + b, this.y2 + c);
            a.strokeStyle = this.strokeStyle;
            a.lineWidth = this.lineWidth;
            a.strokeDashArray = this.strokeDashArray;
            return a
        },
        _getMaxInsideTickMarksLength: function() {
            var a = 0;
            if (this.minorTickMarks != null && this.minorTickMarks.visible && this.minorTickMarks.isInside()) a = Math.max(a, this.minorTickMarks.length);
            if (this.majorTickMarks != null && this.majorTickMarks.visible && this.majorTickMarks.isInside()) a = Math.max(a, this.majorTickMarks.length);
            return a
        },
        _getMaxOutsideTickMarksLength: function() {
            var a = 0;
            if (this.minorTickMarks != null && this.minorTickMarks.visible && !this.minorTickMarks.isInside()) a = Math.max(a, this.minorTickMarks.length);
            if (this.majorTickMarks != null && this.majorTickMarks.visible && !this.majorTickMarks.isInside()) a = Math.max(a, this.majorTickMarks.length);
            return a
        },
        _getLabels: function() {
            var c = this.labels;
            if (c == null || c.visible === false) return [];
            var g = c.position == "inside",
                e = this.lblMargin,
                l = this.offset,
                i = this.crossOffsetX,
                j = this.crossOffsetY,
                n = this.isAxisVertical;
            if (n && c.vAlign == "center" || !n && c.hAlign == "center") e += g ? this._getMaxInsideTickMarksLength() + this.lineWidth / 2 : this._getMaxOutsideTickMarksLength();
            var o = [],
                q = this._getMarkInterval(c, true);
            if (!q) return [];
            for (var p = this._getLabelIntervals(q, c), r = p.length, t = c.showFirstLabel, u = c.showLastLabel, h = 0; h < r; h++) {
                if (!t && h == 0 || !u && h == r - 1) continue;
                var s = p[h],
                    v = this.getLabel(s),
                    a = this._createLabel(v, c),
                    m = this.getPosition(s);
                switch (this.location) {
                    case "left":
                        if (g) a.x = this.x + this.width + e + i;
                        else {
                            a.x = this.x + this.width - e - l + i;
                            a.textAlign = "right"
                        }
                        a.y = m;
                        switch (c.vAlign) {
                            case "bottom":
                                a.textBaseline = "top";
                                break;
                            case "top":
                                a.textBaseline = "bottom"
                        }
                        if (this.labels.angle) {
                            var d = Math.min(90, Math.max(-90, this.labels.angle)),
                                f = b.radians(d);
                            a.rotX = a.x;
                            a.rotY = a.y;
                            a.rotationAngle = f
                        }
                        break;
                    case "right":
                        if (g) {
                            a.x = this.x - e + i;
                            a.textAlign = "right"
                        } else a.x = this.x + e + l + i;
                        a.y = m;
                        switch (c.vAlign) {
                            case "bottom":
                                a.textBaseline = "top";
                                break;
                            case "top":
                                a.textBaseline = "bottom"
                        }
                        if (this.labels.angle) {
                            var d = Math.min(90, Math.max(-90, this.labels.angle)),
                                f = b.radians(d);
                            a.rotX = a.x;
                            a.rotY = a.y;
                            a.rotationAngle = f
                        }
                        break;
                    case "top":
                        a.x = m;
                        if (g) a.y = this.y + this.height + e + a.height / 2 + j;
                        else a.y = this.y + this.height - e - a.height / 2 - l + j;
                        a.textBaseline = "middle";
                        switch (c.hAlign) {
                            case "center":
                                a.textAlign = "center";
                                break;
                            case "left":
                                a.textAlign = "right";
                                break;
                            case "right":
                                a.textAlign = "left"
                        }
                        if (this.labels.angle) {
                            var d = Math.min(90, Math.max(-90, this.labels.angle));
                            a.flip = d > 0;
                            if (d > 0) d = -180 + d;
                            var f = b.radians(d),
                                k = Math.sqrt(a.width * a.width + a.height * a.height);
                            a.rotX = a.x + .5 * Math.cos(f) * k;
                            a.rotY = a.y + .5 * Math.sin(f) * k;
                            a.rotationAngle = f
                        }
                        break;
                    case "bottom":
                        a.x = m;
                        if (g) a.y = this.y - e - a.height / 2 + j;
                        else a.y = this.y + e + a.height / 2 + l + j;
                        a.textBaseline = "middle";
                        switch (c.hAlign) {
                            case "center":
                                a.textAlign = "center";
                                break;
                            case "left":
                                a.textAlign = "right";
                                break;
                            case "right":
                                a.textAlign = "left"
                        }
                        if (this.labels.angle) {
                            var d = Math.min(90, Math.max(-90, this.labels.angle));
                            a.flip = d < 0;
                            if (d < 0) d = 180 + d;
                            var f = b.radians(d),
                                k = Math.sqrt(a.width * a.width + a.height * a.height);
                            a.rotX = a.x + .5 * Math.cos(f) * k;
                            a.rotY = a.y + .5 * Math.sin(f) * k;
                            a.rotationAngle = f
                        }
                }
                o.push(a)
            }
            return o
        },
        _filterLabels: function(b) {
            if (!this.labels || this.labels.position != "inside") return;
            for (var c = this.chart.gridArea, i = c.x, j = c.y, h = c.width, g = c.height, d = b.length - 1; d >= 0; d--) {
                var e = b[d];
                if (!e.isInRect(i, j, h, g)) {
                    var f = a.inArray(e, b);
                    b.splice(f, 1)
                }
            }
        },
        _getTickMarks: function(c, p) {
            if (c == null || c.visible != true) return [];
            for (var n = [], g = this.crossOffsetX, h = this.crossOffsetY, k = this.offset, j = c.position == "inside", r = this._getMarkInterval(c, p), b = c.length, o = this._getIntervals(r, c, p), d, f, e, a, m = 0; m < o.length; m++) {
                var l = this.getPosition(o[m]);
                switch (this.location) {
                    case "left":
                        f = a = l;
                        if (j) e = this.x + this.width + b + this.lineWidth / 2 + g;
                        else e = this.x + this.width - k + g;
                        d = e - b;
                        break;
                    case "right":
                        f = a = l;
                        if (j) d = this.x - b - this.lineWidth / 2 + g;
                        else d = this.x + k + g;
                        e = d + b;
                        break;
                    case "top":
                        d = e = l;
                        if (j) a = this.y + this.height + b + this.lineWidth / 2 + h;
                        else a = this.y + this.height - k + h;
                        f = a - b;
                        break;
                    case "bottom":
                        d = e = l;
                        if (j) a = this.y - b - this.lineWidth / 2 + h;
                        else a = this.y + k + h;
                        f = a + b
                }
                var q = new i(d, f, e, a);
                c._setLineSettings(q);
                n.push(q)
            }
            return n
        },
        _setChart: function(a) {
            this.chart = a;
            this.title.chart = a
        },
        _getValue: function(a) {
            return a
        },
        getCrossing: function() {
            return this.crossing || 0
        },
        _initRadialMeasures: function() {
            var a;
            if (this.chart.options.halfPolar) {
                a = Math.min(2 * this.width - 10, this.height);
                this.cx = this.x + 10
            } else {
                a = Math.min(this.width, this.height);
                this.cx = this.x + this.width / 2
            }
            this.cy = this.y + this.height / 2;
            this.radius = a / 2
        },
        _getActualVisibleMinimum: function() {
            return this.actualVisibleMinimum
        },
        _getActualVisibleMaximum: function() {
            return this.actualVisibleMaximum
        },
        _getActualMinimum: function() {
            return this.actualMinimum
        },
        _getActualMaximum: function() {
            return this.actualMaximum
        },
        _addEmptyDaysOffset: function(a) {
            return a
        },
        getZoom: function() {
            if (!this.actualMaximum) return 1;
            var b = this._getActualMaximum() - this._getActualMinimum(),
                a = this._getActualVisibleMaximum() - this._getActualVisibleMinimum(),
                c = a / b;
            return c
        },
        setOptions: function(b) {
            if (b != null && typeof b.title == "string") {
                b.title = {
                    text: b.title
                };
                a.extend(b.title, this.defaults.title)
            }
            var c = a.extend(true, {}, this.defaults, b || {});
            a.extend(this, c);
            this.options = b;
            if (b) {
                this.isCustomWidth = b.width != null;
                this.isCustomHeight = b.height != null
            }
            this.majorTickMarks = new Ub(c.majorTickMarks);
            if (c.minorTickMarks) {
                this.minorTickMarks = new Ub(c.minorTickMarks);
                this.minorTickMarks.major = this.majorTickMarks
            }
            if (c.majorGridLines) this.majorGridLines = new Tb(c.majorGridLines);
            if (c.minorGridLines) {
                this.minorGridLines = new Tb(c.minorGridLines);
                this.minorGridLines.major = this.majorGridLines
            }
            this.isAxisVertical = this.isVertical();
            this.title = new ab(c.title)
        },
        getPosition: function(f) {
            var e = this._getActualVisibleMaximum(),
                d = this._getActualVisibleMinimum(),
                a = this.length / (e - d) * (f - d),
                b = this.reversed === true,
                c = this.isAxisVertical;
            if (c && b === false || c === false && b) a = this.origin + this.length - a;
            else a += this.origin;
            return a
        },
        getValue: function(f) {
            var e = this._getActualVisibleMaximum(),
                b = this._getActualVisibleMinimum(),
                a = (f - this.origin) * (e - b) / this.length + b,
                c = this.reversed === true,
                d = this.isAxisVertical;
            if (d && c === false || d === false && c) a = b + e - a;
            a = this._addEmptyDaysOffset(a);
            return a
        },
        getLabel: function(d) {
            var b = null;
            if (this.labels != null) b = this.labels.stringFormat;
            var c = a.fn.jqChart.labelFormatter(b, d);
            return c
        },
        isVertical: function() {
            return this.location == "left" || this.location == "right" ? true : false
        },
        isValueVisible: function(a) {
            if (this.logarithmic) a = b.log(a, this.logBase);
            return a >= this.actualVisibleMinimum && a <= this.actualVisibleMaximum
        },
        isInVisibleRange: function(d) {
            var c = this.visibleMinimum,
                a = this.visibleMaximum;
            if (b.isNull(c) || b.isNull(a)) return true;
            if (this.logarithmic) {
                c = b.log(c, this.logBase);
                a = b.log(a, this.logBase)
            }
            return d >= c && d <= a
        },
        defaults: {
            location: "left",
            labels: {
                visible: true,
                fillStyle: "black",
                lineWidth: 1,
                font: "11px sans-serif",
                position: "outside",
                showLastLabel: true,
                showFirstLabel: true,
                hAlign: "center",
                vAlign: "center"
            },
            title: {
                text: undefined,
                font: "14px sans-serif",
                margin: 2
            },
            strokeStyle: "black",
            lineWidth: 1,
            margin: 5,
            visible: true,
            reversed: false,
            zoomEnabled: false
        }
    };
    var m = -Number.MAX_VALUE,
        j = Number.MAX_VALUE;

    function b() {}
    b.isNull = function(b) {
        if (b === null || b === undefined) return true;
        if (!isNaN(b)) return false;
        var c = a.type(b);
        return c !== "date" && c !== "array"
    };
    b.roundH = function(a) {
        return Math.round(a) - .5
    };
    b.round = function(a) {
        var c = 1 / a;
        if (Math.abs(c) > 1e4) {
            var d = b.log10(Math.abs(c));
            if (d > 13) return a
        }
        var e = a.toPrecision(14),
            f = parseFloat(e);
        return f
    };
    b.log10 = function(a) {
        return Math.log(a) / Math.LN10
    };
    b.log = function(b, a) {
        return Math.log(b) / Math.log(a)
    };
    b.radians = function(a) {
        return a * (Math.PI / 180)
    };
    b.degrees = function(a) {
        return 180 * a / Math.PI
    };
    b.normalizeAngle = function(b) {
        var a = b % (2 * Math.PI);
        if (a < 0) a += 2 * Math.PI;
        return a
    };
    b.fitInRange = function(a, c, b) {
        if (a < c) a = c;
        else if (a > b) a = b;
        return a
    };
    b.sum = function(b) {
        for (var c = 0, a = 0; a < b.length; a++) c += b[a];
        return c
    };
    b.compareArrays = function(a, b) {
        if (!a && !b) return true;
        if (!a || !b) return false;
        if (a.length != b.length) return false;
        for (var c = 0; c < a.length; c++)
            if (a[c] !== b[c]) return false;
        return true
    };
    b.rotatePointAt = function(j, k, c, a, b) {
        var e = Math.sin(c),
            d = Math.cos(c),
            f = j - a,
            g = k - b,
            h = a + f * d - g * e,
            i = b + f * e + g * d;
        return {
            x: h,
            y: i
        }
    };
    b.rotatePointsAt = function(d, h, f, g) {
        for (var c = [], a = 0; a < d.length; a += 2) {
            var e = b.rotatePointAt(d[a], d[a + 1], h, f, g);
            c.push(e.x);
            c.push(e.y)
        }
        return c
    };
    b.reversePoints = function(c) {
        for (var b = [], a = c.length - 2; a >= 0; a -= 2) {
            b.push(c[a]);
            b.push(c[a + 1])
        }
        return b
    };
    b.trimPoints = function(d) {
        var a = d.slice(0),
            c = a.length;
        while (c > 1 && (b.isNull(a[0]) || b.isNull(a[1]))) {
            a.splice(0, 2);
            c = a.length
        }
        while (c > 1 && (b.isNull(a[c - 2]) || b.isNull(a[c - 1]))) {
            a.splice(c - 2, 2);
            c = a.length
        }
        return a
    };
    b.intersection = function(a, c, b, d) {
        var f, h = (d.x - b.x) * (a.y - b.y) - (d.y - b.y) * (a.x - b.x),
            i = (c.x - a.x) * (a.y - b.y) - (c.y - a.y) * (a.x - b.x),
            e = (d.y - b.y) * (c.x - a.x) - (d.x - b.x) * (c.y - a.y);
        if (e != 0) {
            var g = h / e,
                j = i / e;
            f = {
                x: a.x + g * (c.x - a.x),
                y: a.y + g * (c.y - a.y)
            }
        }
        return f
    };
    b.processDataValue = function(a, b) {
        switch (b) {
            case "numeric":
                if (typeof a == "string") a = parseFloat(a);
                break;
            case "dateTime":
                if (typeof a == "string") a = new Date(a);
                break;
            case "string":
                if (typeof a != "string") a = a.toString()
        }
        return a
    };
    b.processDataField = function(h, c) {
        if (!c) return null;
        var e, f, d, l, k = a.isPlainObject(c);
        if (k) {
            e = c.name;
            f = c.type;
            d = c.convert
        } else e = c;
        for (var i, b, j = [], g = 0; g < h.length; g++) {
            i = h[g];
            b = i[e];
            if (d) b = d(b);
            else if (f) switch (f) {
                case "numeric":
                    if (typeof b == "string") b = parseFloat(b);
                    break;
                case "dateTime":
                    if (typeof b == "string") b = new Date(b)
            }
            j.push(b)
        }
        return j
    };
    b.mergeArraysXY = function(c, e) {
        if (!c || c.length == 0) return e;
        for (var b = [], a = 0; a < c.length; a++) {
            var d = [];
            d.push(c[a]);
            b.push(d)
        }
        for (var g = b.length, f, a = 0; a < e.length; a++) {
            f = e[a];
            if (a < g) {
                var d = b[a];
                d.push(f)
            } else {
                var d = [null, f];
                b.Add(d)
            }
        }
        return b
    };
    b.mergeArrays = function(f) {
        for (var h = f.length, c = [], a = 0; a < h; a++) {
            var e = f[a];
            if (e == null) continue;
            for (var i = c.length, b = 0; b < e.length; b++) {
                var g = e[b];
                if (b < i) {
                    var d = c[b];
                    d[a] = g
                } else {
                    var d = [];
                    d[a] = g;
                    c.push(d)
                }
            }
        }
        return c
    };
    b.cloneArray = function(a) {
        return a.slice(0)
    };
    b.calcNullValue = function(h, m, l, o) {
        for (var c, d, a, p, g = m - 1; g >= 0; g--) {
            a = h[g];
            if (b.isNull(a)) continue;
            switch (l) {
                case "x":
                    a = a[0];
                    break;
                case "y":
                    a = a[1]
            }
            if (!b.isNull(a)) {
                c = a;
                break
            }
        }
        for (var f = m + 1; f < h.length; f++) {
            a = h[f];
            if (b.isNull(a)) continue;
            switch (l) {
                case "x":
                    a = a[0];
                    break;
                case "y":
                    a = a[1]
            }
            if (!b.isNull(a)) {
                d = a;
                break
            }
        }
        var j = b.isNull(c),
            i = b.isNull(d);
        if (j && i) return null;
        if (j) c = d;
        else if (i) d = c;
        var e;
        if (o == "DateTimeAxis") {
            var k = c.getTime(),
                n = d.getTime();
            e = k + (n - k) / (f - g);
            e = new Date(e)
        } else e = c + (d - c) / (f - g);
        return e
    };
    b.getDistances = function(e) {
        for (var c = [], d, b, f, a = 0; a < shpaes.length - 1; a++) {
            b = e[a];
            f = e[a + 1];
            d = f.y - b.y - b.height;
            c.push(d)
        }
        return c
    };
    b.replaceAll = function(c, b, a) {
        return c.replace(new RegExp(b, "g"), a)
    };
    b.replaceTextForExport = function(a) {
        if (!a) return a;
        if (typeof a != "string") a = a.toString();
        var c = b.replaceAll(a, "'", ";#39;");
        return b.replaceAll(c, '"', ";#34;")
    };
    b.replaceTextForTooltip = function(c) {
        if (!c) return c;
        if (typeof c != "string") c = c.toString();
        var a = b.replaceAll(c, "&", "&amp;");
        a = b.replaceAll(a, '"', "&quot;");
        a = b.replaceAll(a, "'", "&#39;");
        a = b.replaceAll(a, "<", "&lt;");
        return b.replaceAll(a, ">", "&gt;")
    };

    function g() {}
    g.ticksInDay = 24 * 60 * 60 * 1e3;
    g.getDaysInMonth = function(b, a) {
        return a == 1 ? (new Date(b, 1, 29)).getDate() == 29 ? 29 : 28 : ([31, undefined, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31])[a]
    };
    g.addSeconds = function(c, b) {
        return new Date(c.getTime() + b * 1e3)
    };
    g.addDays = function(c, a) {
        a = Math.max(a, 1);
        var b = new Date(c.getTime());
        b.setDate(c.getDate() + a);
        return b
    };
    g.addYears = function(b, c) {
        var a = new Date(b.getTime());
        a.setFullYear(b.getFullYear() + c);
        return a
    };
    g.addMonths = function(c, b) {
        var a = new Date(c.getTime()),
            d = a.getDate();
        a.setDate(1);
        a.setMonth(a.getMonth() + b);
        a.setDate(Math.min(d, g.getDaysInMonth(a.getFullYear(), a.getMonth())));
        return a
    };
    g.getDayOfWeek = function(b) {
        var a = b.getDay();
        return a === 0 ? 7 : a
    };
    g.fromDays = function(a) {
        return a * g.ticksInDay
    };
    g.fromHours = function(a) {
        return a * 60 * 60 * 1e3
    };
    g.fromMinutes = function(a) {
        return a * 60 * 1e3
    };
    g.fromSeconds = function(a) {
        return a * 1e3
    };
    g.roundToDay = function(a) {
        return new Date(a.getFullYear(), a.getMonth(), a.getDate())
    };

    function c() {
        this.fillStyle = "black";
        this.strokeStyle = "black";
        this.lineWidth = 1;
        this.lineCap = "butt";
        this.lineJoin = "miter";
        this.miterLimit = 10;
        this.visible = true;
        this.shadowColor = "rgba(0, 0, 0, 0)";
        this.shadowBlur = 0;
        this.shadowOffsetX = 0;
        this.shadowOffsetY = 0
    }
    c.compare = function(b, e, a, j) {
        if (!b.useHitTestArea && !e.useHitTestArea) return true;
        if (b.useHitTestArea && !e.useHitTestArea) return b.hitTest(a.locX, a.locY, j / 3);
        if (b.hitTest(a.locX, a.locY, 0)) return true;
        var f = b.getCenter(a),
            g = e.getCenter(a),
            c = a.locX - f.x,
            d = a.locY - f.y,
            h = Math.sqrt(c * c + d * d);
        c = a.locX - g.x;
        d = a.locY - g.y;
        var i = Math.sqrt(c * c + d * d);
        return h <= i
    };
    c.getColorFromFillStyle = function(a) {
        if (a == null) return "#dddddd";
        if (typeof a == "string") return a;
        if (a.colorStops && a.colorStops[0]) {
            var b = a.colorStops[0].color;
            return b != "white" && b != "#ffffff" ? b : a.colorStops[1].color
        }
        return "#dddddd"
    };
    c.prototype.hitTest = function() {
        return false
    };
    c.prototype.boundsHitTest = function(b, c, a) {
        if (!this.useHitTestArea) a = 0;
        return b >= this.x - a && b <= this.x + this.width + a && c >= this.y - a && c <= this.y + this.height + a
    };
    c.prototype.isInRect = function(a, b, f, e) {
        var c = this.x,
            d = this.y,
            h = this.width,
            g = this.height;
        return c >= a && d >= b && c + h <= a + f && d + g <= b + e
    };
    c.prototype.render = function(b, a) {
        !a && this.setProperties(b)
    };
    c.prototype.renderDashedLine = function(f, h, g, i, l, c) {
        var r = function(a, b) {
                return Math.round(a) <= Math.round(b)
            },
            q = function(a, b) {
                return Math.round(a) >= Math.round(b)
            },
            n = function(a, b) {
                return Math.min(a, b)
            },
            m = function(a, b) {
                return Math.max(a, b)
            },
            d = {
                thereYet: q,
                cap: n
            },
            e = {
                thereYet: q,
                cap: n
            };
        c.beginPath();
        if (h - i > 0) {
            e.thereYet = r;
            e.cap = m
        }
        if (f - g > 0) {
            d.thereYet = r;
            d.cap = m
        }
        c.moveTo(f, h);
        var a = f,
            b = h,
            k = 0,
            j = true,
            s = l.length;
        while (!(d.thereYet(a, g) && e.thereYet(b, i))) {
            var o = Math.atan2(i - h, g - f),
                p = l[k];
            a = d.cap(g, a + Math.cos(o) * p);
            b = e.cap(i, b + Math.sin(o) * p);
            if (j) c.lineTo(a, b);
            else c.moveTo(a, b);
            k = (k + 1) % s;
            j = !j
        }
        this.strokeStyle != null && this.lineWidth > 0 && c.stroke()
    };
    c.prototype.renderRectPath = function(c, a, b, e, d) {
        c.beginPath();
        var f = this.strokeDashArray;
        if (f) {
            c.moveTo(a, b);
            this.renderDashedLine(a, b, a + e, b, f, c);
            this.renderDashedLine(a + e, b, a + e, b + d, f, c);
            this.renderDashedLine(a + e, b + d, a, b + d, f, c);
            this.renderDashedLine(a, b + d, a, b, f, c)
        } else {
            c.moveTo(a, b);
            c.lineTo(a + e, b);
            c.lineTo(a + e, b + d);
            c.lineTo(a, b + d)
        }
        c.closePath()
    };
    c.prototype.setProperties = function(a) {
        a.fillStyle = this._createGradient(a, this.fillStyle) || "#000000";
        a.strokeStyle = this.strokeStyle || "#000000";
        a.lineWidth = this.lineWidth || 0;
        a.lineCap = this.lineCap;
        a.lineJoin = this.lineJoin;
        a.miterLimit = this.miterLimit;
        a.shadowColor = this.shadowColor;
        a.shadowBlur = this.shadowBlur;
        a.shadowOffsetX = this.shadowOffsetX;
        a.shadowOffsetY = this.shadowOffsetY
    };
    c.prototype.calculateBounds = function(a) {
        if (a == null) return;
        for (var c = j, d = j, g = m, h = m, f, i, e = 0; e < a.length; e += 2) {
            f = a[e];
            i = a[e + 1];
            if (b.isNull(f)) continue;
            c = Math.min(c, f);
            d = Math.min(d, i);
            g = Math.max(g, f);
            h = Math.max(h, i)
        }
        this.x = c;
        this.y = d;
        this.width = g - c;
        this.height = h - d;
        this.center = this.getCenter()
    };
    c.prototype.getCenter = function() {
        return this.center ? this.center : {
            x: this.x + this.width / 2,
            y: this.y + this.height / 2
        }
    };
    c.prototype.getTooltipColor = function() {
        return c.getColorFromFillStyle(this.fillStyle)
    };
    c.prototype.fillStyleToJSON = function(a) {
        if (typeof a == "string" || this.width == null || this.height == null || this.x == null || this.y == null) return "'" + a + "'";
        var c = "{type:'" + a.type + "'";
        if (!b.isNull(a.x0)) c += ",x0:" + a.x0;
        if (!b.isNull(a.x1)) c += ",x1:" + a.x1;
        if (!b.isNull(a.y0)) c += ",y0:" + a.y0;
        if (!b.isNull(a.y1)) c += ",y1:" + a.y1;
        if (a.type == "radialGradient") {
            if (!b.isNull(a.r0)) c += ",r0:" + a.r0;
            if (!b.isNull(a.r1)) c += ",r1:" + a.r1
        }
        c += ",colorStops:[";
        for (var f = a.colorStops.length, d = 0; d < f; d++) {
            var e = a.colorStops[d];
            c += "{color:'" + e.color + "'";
            if (!b.isNull(e.offset)) c += ",offset:" + e.offset;
            c += "}";
            if (d != f - 1) c += ","
        }
        c += "]}";
        return c
    };
    c.prototype.arrayToJSON = function(c) {
        for (var d, b = "[", a = 0; a < c.length; a++) {
            d = c[a];
            b += d;
            if (a != c.length - 1) b += ","
        }
        return b + "]"
    };
    c.prototype.toJSON = function() {
        var a = this,
            b = "";
        if (a.lineWidth) b = ",lineWidth:" + a.lineWidth;
        if (a.fillStyle) b += ",fillStyle:" + a.fillStyleToJSON(a.fillStyle);
        if (a.strokeStyle) b += ",strokeStyle:'" + a.strokeStyle + "'";
        if (!a.visible) b += ",visible:" + a.visible;
        if (a.shadowColor != "rgba(0, 0, 0, 0)") b += ",shadowColor:'" + a.shadowColor + "'";
        if (a.shadowBlur) b += ",shadowBlur:" + a.shadowBlur;
        if (a.shadowOffsetX) b += ",shadowOffsetX:" + a.shadowOffsetX;
        if (a.shadowOffsetY) b += ",shadowOffsetY:" + a.shadowOffsetY;
        if (a.strokeDashArray) b += ",strokeDashArray:" + a.arrayToJSON(a.strokeDashArray);
        return b
    };
    c.prototype._createGradient = function(j, e) {
        var c = this.x,
            d = this.y;
        if (e == null || typeof e == "string" || this.width == null || this.height == null || c == null || d == null) return e;
        if (this.xOffset) c += this.xOffset;
        if (this.yOffset) d += this.yOffset;
        var f, b = {
            x0: 0,
            y0: 0,
            x1: 1,
            y1: 1,
            r0: 0,
            r1: 1
        };
        a.extend(b, e);
        switch (e.type) {
            case "radialGradient":
                var m = c + b.x0 * this.width,
                    o = d + b.y0 * this.height,
                    k = b.r0 * this.width / 2,
                    n = c + b.x1 * this.width,
                    p = d + b.y1 * this.height,
                    l = b.r1 * this.width / 2;
                f = j.createRadialGradient(m, o, k, n, p, l);
                break;
            default:
                var s = c + b.x0 * this.width,
                    t = d + b.y0 * this.height,
                    r = c + b.x1 * this.width,
                    q = d + b.y1 * this.height;
                f = j.createLinearGradient(s, t, r, q)
        }
        var g = b.colorStops;
        if (g != null)
            for (var h = 0; h < g.length; h++) {
                var i = g[h];
                f.addColorStop(i.offset || 0, i.color)
            }
        return f
    };
    c.prototype._createHighlightShape = function(e, d) {
        var b = new c;
        a.extend(b, this);
        b.fillStyle = b.highlightingFillStyle || e;
        if (this instanceof i) b.strokeStyle = b.highlightingStrokeStyle || d;
        return b
    };
    c.prototype._getTooltipPosition = function(d, f, e, c, b) {
        var a = this._getTooltipOrigin(d);
        return this._getTooltipPositionFromOrigin(a.x, a.y, f, e, c, b)
    };
    c.prototype._getTooltipOrigin = function() {
        return this.tooltipOrigin ? this.tooltipOrigin : {
            x: this.x + this.width / 2,
            y: this.y
        }
    };
    c.prototype._getTooltipPositionFromOrigin = function(d, e, g, f) {
        var a = 15,
            b = d - g - a,
            c = e - f + 10;
        if (b < 0) b = Math.max(0, d + a);
        if (c < 0) c = Math.max(0, e - a);
        return {
            x: b,
            y: c
        }
    };
    c.prototype._getAnimationPoints = function(c, a) {
        if (c.length == a) return c;
        var d = a % 2;
        a -= d;
        var f = c.slice(0, a);
        d /= 2;
        var i = c[a - 2],
            g = c[a - 1],
            h = c[a],
            e = c[a + 1];
        if (!b.isNull(g) && !b.isNull(e)) {
            h = i + (h - i) * d;
            e = g + (e - g) * d;
            f.push(h);
            f.push(e)
        }
        return f
    };

    function i(a, d, b, e) {
        c.call(this);
        this.x1 = a;
        this.y1 = d;
        this.x2 = b;
        this.y2 = e;
        this.useHitTestArea = true
    }
    i.prototype = new c;
    i.constructor = new i;
    i.prototype.hitTest = function(f, g, j) {
        var b = this.x1,
            d = this.y1,
            c = this.x2,
            e = this.y2,
            a = Math.max(j, Math.max(3, this.lineWidth / 2));
        if (b == c) {
            var h = f + 1;
            return h > b - a && h < c + a && g >= d - a && g <= e + a ? true : false
        }
        if (d == e) {
            var i = g + .5;
            return f >= b - a && f <= c + a && i > d - a && i < e + a ? true : false
        }
        return false
    };
    i.prototype.hitTestNonHV = function(n, o, l) {
        var a = this.x1,
            b = this.y1,
            c = this.x2,
            d = this.y2;
        if (a < c) {
            this.x = a;
            this.width = c - a
        } else {
            this.x = c;
            this.width = a - c
        }
        if (b < d) {
            this.y = b;
            this.width = d - b
        } else {
            this.y = d;
            this.width = b - d
        }
        var g = c - a,
            h = d - b,
            e, f, i;
        if (g == 0) {
            e = 1;
            f = 0;
            i = -a
        } else if (h == 0) {
            e = 0;
            f = -1;
            i = -b
        } else if (Math.abs(g) < Math.abs(h)) {
            e = 1;
            f = g / h;
            i = -((a * d - b * c) / h)
        } else {
            e = -(h / g);
            f = -1;
            i = -((b * c - a * d) / g)
        }
        var k = Math.sqrt(e * e + f * f),
            j = (e * n - f * o + i) / k,
            m = Math.max(l, 3);
        return Math.abs(j) < m ? true : false
    };
    i.prototype.render = function(a, j) {
        if (!this.visible) return;
        c.prototype.render.call(this, a, j);
        var d = Math.floor(this.lineWidth % 2) ? b.roundH : Math.round;
        if (this.dontRound) d = function(a) {
            return a
        };
        var e = d(this.x1),
            g = d(this.y1),
            f = d(this.x2),
            h = d(this.y2);
        if (this.xOffset) {
            e += this.xOffset;
            f += this.xOffset
        }
        if (this.yOffset) {
            g += this.yOffset;
            h += this.yOffset
        }
        if (this.rotationAngle && !b.isNull(this.rotX) && !b.isNull(this.rotY)) {
            var i = b.rotatePointsAt([e, g, f, h], this.rotationAngle, this.rotX, this.rotY);
            e = i[0];
            g = i[1];
            f = i[2];
            h = i[3]
        }
        if (this.strokeDashArray) {
            this.renderDashedLine(e, g, f, h, this.strokeDashArray, a);
            return
        }
        a.beginPath();
        a.moveTo(e, g);
        a.lineTo(f, h);
        this.strokeStyle != null && this.lineWidth > 0 && a.stroke()
    };
    i.prototype.toJSON = function() {
        var a = this,
            b = "type:'line',x1:" + a.x1 + ",y1:" + a.y1 + ",x2:" + a.x2 + ",y2:" + a.y2;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function p(d, e, b, a) {
        c.call(this);
        this.x = d;
        this.y = e;
        this.width = b;
        this.height = a;
        this.cornerRadius = 0
    }
    p.prototype = new c;
    p.constructor = new p;
    p.prototype.hitTest = function(b, c, a) {
        return this.boundsHitTest(b, c, a)
    };
    p.prototype.render = function(a, k) {
        if (!this.visible) return;
        c.prototype.render.call(this, a, k);
        var h = this.strokeStyle != null && this.lineWidth > 0,
            j = h && Math.floor(this.lineWidth % 2) ? b.roundH : Math.round,
            i = this.correction || 0;
        i = Math.round(i);
        var f = j(this.x),
            g = j(this.y),
            e = Math.round(this.width),
            d = Math.round(this.height),
            l = this.context;
        if (this.xDecrease) {
            e -= this.xDecrease;
            if (this.isAnimReversed) f += this.xDecrease
        }
        if (this.yDecrease) {
            d -= this.yDecrease;
            if (this.isAnimReversed) g += this.yDecrease
        }
        if (this.xOffset) f += this.xOffset;
        if (this.yOffset) g += this.yOffset;
        if (e <= 0 || d <= 0) return;
        if (this.cornerRadius == 0) this.renderRectPath(a, f, g, e, d);
        else this.renderRoundedRectPath(a, f, g, e, d);
        this.fillStyle != null && a.fill();
        h && a.stroke()
    };
    p.prototype.renderRoundedRectPath = function(b, c, d, g, f) {
        var a = this.cornerRadius,
            e = Math.PI / 2;
        b.beginPath();
        b.moveTo(c + a, d);
        b.lineTo(c + g - a, d);
        b.arc(c + g - a, d + a, a, -e, 0, false);
        b.lineTo(c + g, d + f - a);
        b.arc(c + g - a, d + f - a, a, 0, e, false);
        b.lineTo(c + a, d + f);
        b.arc(c + a, d + f - a, a, e, 2 * e, false);
        b.lineTo(c, d + a);
        b.arc(c + a, d + a, a, 2 * e, -e, false);
        b.closePath()
    };
    p.prototype.toJSON = function() {
        var a = this,
            b = "type:'rectangle',x:" + a.x + ",y:" + a.y + ",width:" + a.width + ",height:" + a.height;
        if (a.cornerRadius) b += ",cornerRadius:" + a.cornerRadius;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function Bb(d, e, b, a) {
        c.call(this);
        this.x = d;
        this.y = e;
        this.width = b;
        this.height = a
    }
    Bb.prototype = new c;
    Bb.constructor = new Bb;
    Bb.prototype.hitTest = function(f, g, a) {
        if (this.boundsHitTest(f, g, a) == false) return false;
        var c = (this.width + a) / 2,
            b = (this.height + a) / 2,
            h = this.x + c,
            i = this.y + b,
            d = f - h,
            e = g - i,
            j = d * d / (c * c),
            k = e * e / (b * b);
        return j + k <= 1
    };
    Bb.prototype.render = function(a, m) {
        if (!this.visible) return;
        c.prototype.render.call(this, a, m);
        var b = this.x,
            e = this.y,
            l = this.width,
            k = this.height;
        if (this.xOffset) b += this.xOffset;
        if (this.yOffset) e += this.yOffset;
        var g = this.width / 2 * .5522848,
            h = this.height / 2 * .5522848,
            i = b + l,
            j = e + k,
            f = b + l / 2,
            d = e + k / 2;
        a.beginPath();
        a.moveTo(b, d);
        a.bezierCurveTo(b, d - h, f - g, e, f, e);
        a.bezierCurveTo(f + g, e, i, d - h, i, d);
        a.bezierCurveTo(i, d + h, f + g, j, f, j);
        a.bezierCurveTo(f - g, j, b, d + h, b, d);
        a.closePath();
        this.fillStyle != null && a.fill();
        this.strokeStyle != null && this.lineWidth > 0 && a.stroke()
    };
    Bb.prototype.toJSON = function() {
        var a = this,
            b = "type:'ellipse',x:" + a.x + ",y:" + a.y + ",width:" + a.width + ",height:" + a.height;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function Cb(e, f, a, b, d) {
        c.call(this);
        this.x = e;
        this.y = f;
        this.radius = a;
        this.startAngle = b;
        this.endAngle = d;
        this.width = this.height = 2 * a
    }
    Cb.prototype = new c;
    Cb.constructor = new Cb;
    Cb.prototype.render = function(a, d) {
        if (!this.visible) return;
        c.prototype.render.call(this, a, d);
        a.beginPath();
        var b = Math.max(0, this.radius);
        a.arc(Math.round(this.x + b), Math.round(this.y + b), Math.round(b), this.startAngle, this.endAngle, false);
        this.strokeStyle != null && this.lineWidth > 0 && a.stroke()
    };
    Cb.prototype.toJSON = function() {
        var a = this,
            b = "type:'arc',x:" + a.x + ",y:" + a.y + ",radius:" + a.radius + ",startAngle:" + a.startAngle + ",endAngle:" + a.endAngle;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function Y(b, d, a) {
        c.call(this);
        this.x = b;
        this.y = d;
        this.radius = a;
        this.width = this.height = 2 * a
    }
    Y.prototype = new c;
    Y.constructor = new Y;
    Y.prototype.hitTest = function(e, f, a) {
        if (!this.useHitTestArea) a = 0;
        var c = this.x + this.width / 2,
            d = this.y + this.height / 2,
            b = Math.pow(e - c, 2) + Math.pow(f - d, 2);
        return b > Math.pow(this.radius + a, 2) ? false : true
    };
    Y.prototype.render = function(a, d) {
        if (!this.visible) return;
        c.prototype.render.call(this, a, d);
        a.beginPath();
        var b = Math.max(0, this.radius);
        a.arc(Math.round(this.x + b), Math.round(this.y + b), Math.round(b), 0, Math.PI * 2, false);
        a.closePath();
        this.fillStyle != null && a.fill();
        this.strokeStyle != null && this.lineWidth > 0 && a.stroke()
    };
    Y.prototype.toJSON = function() {
        var a = this,
            b = "type:'circle',x:" + a.x + ",y:" + a.y + ",radius:" + a.radius;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function vb(f, g, h, a, b, d) {
        c.call(this);
        this.x = f;
        this.y = g;
        this.innerRadius = h;
        this.outerRadius = a;
        this.startAngle = b;
        this.endAngle = d;
        this.width = this.height = 2 * a;
        var e = (b + d) / 2,
            i = f + a * Math.cos(e),
            j = g + a * Math.sin(e);
        this.center = this.tooltipOrigin = {
            x: i,
            y: j
        }
    }
    vb.prototype = new c;
    vb.constructor = new vb;
    vb.prototype.hitTest = function(h, i) {
        var f = this.x,
            g = this.y,
            e = Math.pow(h - f, 2) + Math.pow(i - g, 2);
        if (e > Math.pow(this.outerRadius, 2) || e < Math.pow(this.innerRadius, 2)) return false;
        var j = f - h,
            k = g - i,
            a = Math.atan2(k, j) + Math.PI;
        a = b.normalizeAngle(a);
        var c = b.normalizeAngle(this.startAngle),
            d = b.normalizeAngle(this.endAngle);
        if (c == d && this.startAngle != this.endAngle) return true;
        if (c > d) {
            if (a >= c && a <= 2 * Math.PI || a < d && a >= 0) return true
        } else if (a >= c && a < d) return true;
        return false
    };
    vb.prototype.render = function(a, i) {
        if (!this.visible) return;
        if (this.startAngle == this.endAngle) return;
        c.prototype.render.call(this, a, i);
        a.beginPath();
        var f = Math.round(this.x),
            g = Math.round(this.y),
            b = this.startAngle,
            e = this.endAngle;
        if (d.use_excanvas) {
            var h = 2 * Math.PI - .000125;
            if (e - b >= h) e = b + h
        }
        if (this.innerRadius) a.arc(f, g, Math.round(this.innerRadius), e, b, true);
        else a.moveTo(f, g);
        a.arc(f, g, Math.round(this.outerRadius), b, e, false);
        a.closePath();
        this.fillStyle != null && a.fill();
        this.strokeStyle != null && this.lineWidth > 0 && a.stroke()
    };
    vb.prototype.toJSON = function() {
        var a = this,
            b = "type:'pieSlice',x:" + a.x + ",y:" + a.y + ",radius:" + a.outerRadius + ",outerRadius:" + a.outerRadius + ",innerRadius:" + a.innerRadius + ",startAngle:" + a.startAngle + ",endAngle:" + a.endAngle;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function z() {
        c.call(this)
    }
    z.prototype = new c;
    z.constructor = new z;
    z.prototype.hitTest = function(k, l, g) {
        var e = this.context;
        if (!e || !e.points) return this.boundsHitTest(k, l, g);
        for (var d = this.pts, m = Math.pow(g, 2), c = -1, f = j, h, i, b, n = this.isStepLine ? 4 : 2, a = 0; a < d.length; a += n) {
            h = d[a];
            i = d[a + 1];
            b = Math.pow(k - h, 2) + Math.pow(l - i, 2);
            if (b > f || b > m) continue;
            f = b;
            c = a
        }
        return c == -1 ? false : this.createHighlightMark(c)
    };
    z.prototype.createHighlightMark = function(c) {
        if (c == -1) return null;
        var d = this.context,
            g = this.pts,
            f = c / 2;
        if (this.isStepLine) f /= 2;
        var h = d.points[f],
            e = 5,
            b = new Y(g[c] - e, g[c + 1] - e, e);
        b.fillStyle = b.highlightingFillStyle = this.strokeStyle;
        b.strokeStyle = "white";
        b.lineWidth = 1;
        b.useHitTestArea = true;
        b.context = {
            series: d.series,
            chart: d.chart
        };
        a.extend(b.context, h);
        return b
    };
    z.prototype.getCenter = function(g) {
        if (this.center) return this.center;
        var e = this.context;
        if (!e || !e.points) return {
            x: this.x + this.width / 2,
            y: this.y + this.height / 2
        };
        for (var b = this.pts, a = 0, f = j, i = g.locX, d, h = this.isStepLine ? 4 : 2, c = 0; c < b.length; c += h) {
            d = Math.abs(b[c] - i);
            if (f > d) {
                f = d;
                a = c
            }
        }
        return {
            x: b[a],
            y: b[a + 1],
            mark: this.createHighlightMark(a)
        }
    };
    z.prototype.getLength = function() {
        return this.pts.length
    };

    function q(b, a, c) {
        z.call(this);
        this.pts = b;
        this.closed = c;
        if (a) {
            this.isBoundsHitTest = a;
            this.calculateBounds(b)
        }
    }
    q.prototype = new z;
    q.constructor = new q;
    q.prototype.renderPoints = function(a, c, i) {
        var g = c.length;
        if (g <= 2) return;
        if (this.strokeDashArray) {
            this.renderDashed(a, c);
            return
        }
        for (var h = this.nullHandling != "connect", e = true, b = 2; b < g; b += 2) {
            var d = c[b];
            if (d == null) {
                if (h) {
                    this.strokeStyle != null && this.lineWidth > 0 && a.stroke();
                    e = false
                }
                continue
            }
            var f = c[b + 1];
            if (!e) {
                a.beginPath();
                a.moveTo(d, f);
                e = true;
                continue
            }
            a.lineTo(d, f);
            if (b % 1e3 == 0 && !i) {
                this.strokeStyle != null && this.lineWidth > 0 && a.stroke();
                a.beginPath();
                a.moveTo(d, f)
            }
        }
    };
    q.prototype.render = function(e, g) {
        if (!this.visible) return;
        c.prototype.render.call(this, e, g);
        var a = b.trimPoints(this.pts);
        if (this.closed) {
            a.push(a[0]);
            a.push(a[1])
        }
        if (!b.isNull(this.length)) {
            var h = this.closed ? this.length + 2 : this.length;
            a = this._getAnimationPoints(a, h)
        }
        var f = a.length;
        if (f <= 2) return;
        if (this.xOffset) {
            a = a.slice(0);
            for (var d = 0; d < a.length; d += 2) a[d] += this.xOffset
        }
        if (this.yOffset) {
            a = a.slice(0);
            for (var d = 1; d < a.length; d += 2) a[d] += this.yOffset
        }
        e.beginPath();
        e.moveTo(a[0], a[1]);
        this.renderPoints(e, a);
        this.strokeStyle != null && this.lineWidth > 0 && e.stroke()
    };
    q.prototype.renderDashed = function(k, f) {
        for (var j = f.length, i = this.nullHandling != "connect", h = this.strokeDashArray, c = false, d, e, a = 0; a < j; a += 2) {
            var b = f[a];
            if (b == null) {
                if (i) c = false;
                continue
            }
            var g = f[a + 1];
            if (!c) {
                d = b;
                e = g;
                c = true;
                continue
            }
            this.renderDashedLine(d, e, b, g, h, k);
            d = b;
            e = g
        }
    };
    q.prototype.toJSON = function() {
        var a = this,
            b = "type:'polyline',pts:" + a.arrayToJSON(a.pts);
        if (this.nullHandling) b += ",nullHandling:'" + a.nullHandling + "'";
        if (a.closed) b += ",closed:" + a.closed;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function u(a, b) {
        z.call(this);
        if (!a) return;
        this.closed = b;
        this.pts = a;
        this.calculateBounds(a)
    }
    u.prototype = new z;
    u.constructor = new u;
    u.prototype.renderPoints = function(f, i) {
        var g = .4,
            h = this.closed,
            b = [];
        a.merge(b, i);
        var d = [],
            e = b.length;
        if (e <= 2) return;
        if (e == 4) {
            f.lineTo(b[2], b[3]);
            return
        }
        if (h) {
            b.push(b[0], b[1], b[2], b[3]);
            b.unshift(b[e - 1]);
            b.unshift(b[e - 1]);
            for (var c = 0; c < e; c += 2) d = d.concat(this.getControlPoints(b[c], b[c + 1], b[c + 2], b[c + 3], b[c + 4], b[c + 5], g));
            d = d.concat(d[0], d[1]);
            for (var c = 2; c < e + 2; c += 2) f.bezierCurveTo(d[2 * c - 2], d[2 * c - 1], d[2 * c], d[2 * c + 1], b[c + 2], b[c + 3])
        } else {
            for (var c = 0; c < e - 4; c += 2) d = d.concat(this.getControlPoints(b[c], b[c + 1], b[c + 2], b[c + 3], b[c + 4], b[c + 5], g));
            f.quadraticCurveTo(d[0], d[1], b[2], b[3]);
            for (var c = 2; c < e - 5; c += 2) f.bezierCurveTo(d[2 * c - 2], d[2 * c - 1], d[2 * c], d[2 * c + 1], b[c + 2], b[c + 3]);
            f.quadraticCurveTo(d[2 * e - 10], d[2 * e - 9], b[e - 2], b[e - 1])
        }
    };
    u.prototype.render = function(a, f) {
        if (!this.visible) return;
        c.prototype.render.call(this, a, f);
        var d = b.trimPoints(this.pts);
        if (!b.isNull(this.length)) d = this._getAnimationPoints(d, this.length);
        var e = d.length;
        if (e < 4) return;
        a.beginPath();
        a.moveTo(d[0], d[1]);
        this.renderPoints(a, d);
        if (this.closed) {
            a.closePath();
            this.fillStyle != null && a.fill()
        }
        this.strokeStyle != null && this.lineWidth > 0 && a.stroke()
    };
    u.prototype.getControlPoints = function(d, f, a, b, e, g, j) {
        var h = Math.sqrt(Math.pow(a - d, 2) + Math.pow(b - f, 2)),
            k = Math.sqrt(Math.pow(e - a, 2) + Math.pow(g - b, 2)),
            c = j * h / (h + k),
            i = j - c,
            l = a + c * (d - e),
            m = b + c * (f - g),
            n = a - i * (d - e),
            o = b - i * (f - g);
        return [l, m, n, o]
    };
    u.prototype.toJSON = function() {
        var a = this,
            b = "type:'curve',pts:" + a.arrayToJSON(a.pts);
        if (a.closed) b += ",closed:" + a.closed;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function bb(b, c, e, f) {
        z.call(this);
        this.pts = b;
        this.crossPos = c;
        this.vertical = e || false;
        this.isCurve = f || false;
        if (b && b.length >= 2) {
            var d = [];
            a.merge(d, b);
            if (e) a.merge(d, [c, b[b.length - 1], c, b[1]]);
            else a.merge(d, [b[b.length - 2], c, b[0], c]);
            this.calculateBounds(d)
        }
    }
    bb.prototype = new z;
    bb.constructor = new bb;
    bb.prototype.render = function(d, g) {
        if (!this.visible) return;
        c.prototype.render.call(this, d, g);
        var a = b.trimPoints(this.pts);
        if (!b.isNull(this.length)) a = this._getAnimationPoints(a, this.length);
        var f = a.length;
        if (f <= 2) return;
        var e;
        if (this.isCurve) e = new u(a);
        else {
            e = new q(a);
            e.nullHandling = "connect"
        }
        d.beginPath();
        d.moveTo(a[0], a[1]);
        e.renderPoints(d, a, true);
        if (this.vertical) {
            d.lineTo(this.crossPos, a[a.length - 1]);
            d.lineTo(this.crossPos, a[1])
        } else {
            d.lineTo(a[a.length - 2], this.crossPos);
            d.lineTo(a[0], this.crossPos)
        }
        d.closePath();
        this.fillStyle != null && d.fill();
        this.strokeStyle != null && this.lineWidth > 0 && d.stroke()
    };
    bb.prototype.toJSON = function() {
        var a = this,
            b = "type:'area',pts:" + a.arrayToJSON(a.pts) + ",crossPos:" + a.crossPos + ",vertical:" + a.vertical;
        if (a.isCurve) b += ",isCurve:" + a.isCurve;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function X(c, d, f, e) {
        z.call(this);
        if (!c) return;
        this.pts1 = c;
        this.pts2 = d;
        this.pts = [];
        a.merge(this.pts, c);
        !e && a.merge(this.pts, b.reversePoints(d));
        this.calculateBounds(this.pts);
        this.center = null;
        this.isCurve = f || false
    }
    X.prototype = new z;
    X.constructor = new X;
    X.prototype.getLength = function() {
        return this.pts1.length
    };
    X.prototype.render = function(d, h) {
        if (!this.visible) return;
        c.prototype.render.call(this, d, h);
        var e = this.pts1,
            a = this.pts2;
        if (!b.isNull(this.length)) {
            e = this._getAnimationPoints(e, this.length);
            a = this._getAnimationPoints(a, this.length)
        }
        a = b.reversePoints(a);
        var g = e.length;
        if (g < 2) return;
        var f;
        if (this.isCurve) f = new u(e);
        else f = new q(e);
        d.beginPath();
        d.moveTo(e[0], e[1]);
        f.renderPoints(d, e, true);
        d.lineTo(a[0], a[1]);
        if (this.isCurve) f = new u(a);
        else f = new q(a);
        f.renderPoints(d, a, true);
        d.closePath();
        this.fillStyle != null && d.fill();
        this.strokeStyle != null && this.lineWidth > 0 && d.stroke()
    };
    X.prototype.toJSON = function() {
        var a = this,
            b = "type:'rangeShape',pts1:" + a.arrayToJSON(a.pts1) + ",pts2:" + a.arrayToJSON(a.pts2);
        if (a.isCurve) b += ",isCurve:" + a.isCurve;
        b += c.prototype.toJSON.call(this);
        return b
    };

    function S(a) {
        z.call(this);
        this.pts = a;
        this.calculateBounds(a)
    }
    S.prototype = new z;
    S.constructor = new S;
    S.prototype.hitTest = function(j, a, h) {
        var k = this.context;
        if (k && k.points) return z.prototype.hitTest.call(this, j, a, h);
        var n = this.boundsHitTest(j, a, h);
        if (n == false) return false;
        if (this.isBoundsHitTest && h) return true;
        for (var b = this.pts, g = false, l = b.length, i, c, m, e, d = 0, f = 0; f < l; f += 2) {
            d += 2;
            if (d == l) d = 0;
            i = b[f];
            c = b[f + 1];
            m = b[d];
            e = b[d + 1];
            if (c < a && e >= a || e < a && c >= a)
                if (i + (a - c) / (e - c) * (m - i) < j) g = !g
        }
        return g
    };
    S.prototype.render = function(e, h) {
        if (!this.visible) return;
        c.prototype.render.call(this, e, h);
        var a = this.pts,
            g = a.length;
        if (g < 4) return;
        if (this.xOffset) {
            a = a.slice(0);
            for (var d = 0; d < a.length; d += 2) a[d] += this.xOffset
        }
        if (this.yOffset) {
            a = a.slice(0);
            for (var d = 1; d < a.length; d += 2) a[d] += this.yOffset
        }
        if (this.rotationAngle && !b.isNull(this.rotX) && !b.isNull(this.rotY)) a = b.rotatePointsAt(a, this.rotationAngle, this.rotX, this.rotY);
        var f = Math.floor(this.lineWidth % 2) ? b.roundH : Math.round;
        if (this.dontRound) f = function(a) {
            return a
        };
        e.beginPath();
        e.moveTo(f(a[0]), f(a[1]));
        for (var d = 2; d < g; d += 2) e.lineTo(f(a[d]), f(a[d + 1]));
        e.closePath();
        this.fillStyle != null && e.fill();
        this.strokeStyle != null && this.lineWidth > 0 && e.stroke()
    };
    S.prototype.toJSON = function() {
        var b = this,
            a = "type:'polygon',pts:" + b.arrayToJSON(b.pts);
        a += c.prototype.toJSON.call(this);
        return a
    };

    function l(a, b, c) {
        this.text = a;
        this.x = b;
        this.y = c;
        this.width = 0;
        this.height = 0;
        this.strokeStyle = null;
        this.textBaseline = "middle";
        this.font = "10px sans-serif";
        this.textAlign = "left"
    }
    l.prototype = new c;
    l.constructor = l;
    l.numbers = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
    l.separator = "\n";
    l.prototype.render = function(a, d) {
        if (!this.visible) return;
        c.prototype.render.call(this, a, d);
        var e = b.roundH(this.x),
            f = b.roundH(this.y);
        if (this.rotationAngle && !b.isNull(this.rotX) && !b.isNull(this.rotY)) {
            a.save();
            a.translate(this.rotX, this.rotY);
            a.rotate(this.rotationAngle);
            this.flip && a.scale(-1, -1);
            this.renderBg(a);
            this.fillStyle != null && a.fillText(this.text, 0, 0);
            this.strokeStyle != null && a.strokeText(this.text, 0, 0);
            a.restore()
        } else {
            this.renderBg(a);
            this.renderText(a, e, f)
        }
    };
    l.prototype.renderText = function(c, d, a) {
        var b = "" + this.text,
            i = b && b.search(l.separator) != -1;
        if (i) {
            var h = b.split(l.separator),
                e = this.getHeight(),
                f;
            switch (this.textBaseline) {
                case "middle":
                    a -= (this.height - e) / 2;
                    break;
                case "bottom":
                    a -= this.height - e
            }
            for (var g = 0; g < h.length; g++) {
                f = h[g];
                this.fillStyle != null && c.fillText(f, d, a);
                this.strokeStyle != null && c.strokeText(f, d, a);
                a += e
            }
        } else {
            this.fillStyle != null && c.fillText(b, d, a);
            this.strokeStyle != null && c.strokeText(b, d, a)
        }
    };
    l.prototype.renderBg = function(a) {
        var e = this.background;
        if (!e) return;
        var g = b.roundH(this.x),
            h = b.roundH(this.y);
        !this.width && this.measure(a);
        var d = Math.round(this.width),
            c = Math.round(this.height);
        a.fillStyle = this._createGradient(a, e) || "#000000";
        var f = this._correctXY(g, h, d, c);
        this.renderRectPath(a, f.x, f.y, d, c);
        a.fill();
        a.fillStyle = this._createGradient(a, this.fillStyle) || "#000000"
    };
    l.prototype.getHeight = function() {
        for (var e = 0, c = this.font.split(" "), b = 0; b < c.length; b++) {
            var d = c[b],
                f = d.charAt(0);
            if (a.inArray(f, l.numbers) != -1) {
                e = parseFloat(d) || 0;
                break
            }
        }
        return e
    };
    l.prototype.measure = function(e) {
        this.setProperties(e);
        var c = this.getHeight(),
            a, b, g = "" + this.text,
            h = g && g.search(l.separator) != -1;
        if (h) {
            var d = this.text.split(l.separator);
            c = d.length * c;
            a = 0;
            for (var i, f = 0; f < d.length; f++) {
                b = e.measureText(d[f]);
                a = Math.max(a, b.width)
            }
        } else {
            b = e.measureText(this.text);
            a = b.width
        }
        this.width = a;
        this.height = c;
        return {
            width: a,
            height: c
        }
    };
    l.prototype._correctXY = function(a, b, d, c) {
        switch (this.textAlign) {
            case "center":
                a -= d / 2;
                break;
            case "right":
                a -= d
        }
        switch (this.textBaseline) {
            case "middle":
                b -= c / 2;
                break;
            case "bottom":
                b -= c
        }
        return {
            x: a,
            y: b
        }
    };
    l.prototype.isInRect = function(e, f, h, g) {
        var a = this.x,
            b = this.y,
            d = this.width,
            c = this.height;
        switch (this.textAlign) {
            case "center":
                a -= d / 2;
                break;
            case "right":
                a -= d
        }
        switch (this.textBaseline) {
            case "middle":
                b -= c / 2;
                break;
            case "bottom":
                b -= c
        }
        return a >= e && b >= f && a + d <= e + h && b + c <= f + g
    };
    l.prototype.intersectWith = function(c, d, i, h) {
        var a = this.x,
            b = this.y,
            g = this.width,
            f = this.height,
            e = this._correctXY(a, b, g, f);
        a = e.x;
        b = e.y;
        return c < a + g && a < c + i && d < b + f && b < d + h
    };
    l.prototype.setProperties = function(a) {
        c.prototype.setProperties.call(this, a);
        a.font = this.font;
        a.textAlign = this.textAlign;
        a.textBaseline = this.textBaseline
    };
    l.prototype.hitTest = function(a, b, e) {
        if (!this.isLegendItem && !this.isAxisLabel) return false;
        var d = this.width,
            c = this.height;
        switch (this.textAlign) {
            case "center":
                a += d / 2;
                break;
            case "right":
                a += d
        }
        switch (this.textBaseline) {
            case "middle":
                b += c / 2;
                break;
            case "bottom":
                b += c
        }
        return this.boundsHitTest(a, b, e)
    };
    l.prototype.toJSON = function() {
        var a = this;
        if (!a.text || a.text == "") return false;
        var e = b.replaceTextForExport(this.text),
            d = "type:'textBlock',x:" + a.x + ",y:" + a.y + ",width:" + a.width + ",height:" + a.height + ",text:'" + e + "'";
        d += ",font:'" + a.font + "'";
        d += ",textBaseline:'" + a.textBaseline + "'";
        d += ",textAlign:'" + a.textAlign + "'";
        if (!b.isNull(a.rotationAngle)) d += ",rotationAngle:" + a.rotationAngle;
        if (!b.isNull(a.rotX)) d += ",rotX:" + a.rotX;
        if (!b.isNull(a.rotY)) d += ",rotY:" + a.rotY;
        if (a.flip) d += ",flip:" + a.flip;
        d += c.prototype.toJSON.call(this);
        return d
    };

    function sb(b, c, a) {
        this.x = b;
        this.y = c;
        this.src = a
    }
    sb.prototype = new c;
    sb.constructor = sb;
    sb.prototype.hitTest = function(b, c, a) {
        return this.boundsHitTest(b, c, a)
    };
    sb.prototype.render = function(e) {
        if (!this.visible) return;
        var b = new Image,
            c = this.x,
            d = this.y,
            a = this;
        b.onload = function() {
            if (a.deleted) return;
            var g = b.width,
                f = b.height;
            c -= g / 2;
            d -= f / 2;
            a.x = c;
            a.y = d;
            if (a.offsetX) c += a.offsetX;
            if (a.offsetY) d += a.offsetY;
            a.width = g;
            a.height = f;
            e.drawImage(b, c, d)
        };
        b.src = this.src
    };
    sb.prototype._createHighlightShape = function(b) {
        var a = new p;
        a.context = this.context;
        a.x = this.x;
        a.y = this.y;
        a.width = this.width;
        a.height = this.height;
        a.fillStyle = b;
        a.strokeStyle = "gray";
        return a
    };

    function Ab(a) {
        c.call(this);
        this.shapes = a
    }
    Ab.prototype = new c;
    Ab.constructor = new Ab;
    Ab.prototype.hitTest = function(e, f, c) {
        for (var b = this.shapes, a = 0; a < b.length; a++) {
            var d = b[a];
            if (d.hitTest(e, f, c)) return true
        }
        return false
    };
    Ab.prototype._getTooltipOrigin = function(a) {
        return {
            x: a.locX,
            y: a.locY
        }
    };
    Ab.prototype.render = function(d, e) {
        if (!this.visible) return;
        c.prototype.render.call(this, d, e);
        for (var b = this.shapes, a = 0; a < b.length; a++) {
            var f = b[a];
            f.render(d, true)
        }
    };

    function fc(c, d, b, a) {
        this.x = c;
        this.y = d;
        this.width = b;
        this.height = a
    }
    fc.prototype.toJSON = function() {
        var a = this,
            b = "type:'clip',x:" + a.x + ",y:" + a.y + ",width:" + a.width + ",height:" + a.height;
        return b
    };

    function cc() {}
    cc.prototype.toJSON = function() {
        var b = this,
            a = "type:'resetClip'";
        return a
    };

    function Xb(a) {
        this.renderShadows = a
    }
    Xb.prototype.toJSON = function() {
        var b = this,
            a = "type:'shadows',renderShadows:" + b.renderShadows;
        return a
    };

    function O(a, b) {
        if (this.canvas == null) {
            this.canvas = a;
            this.ctx = this._getContext(this.canvas)
        }
        this.chart = b
    }
    O.prototype._getContext = function(a) {
        return a.getContext ? a.getContext("2d") : null
    };
    O.emptyColor = "rgba(0, 0, 0, 0)";
    O.prototype._render = function(g) {
        var e = this.offsetX || this.offsetY,
            f = this.chart.options,
            c = this.ctx;
        if (!b.isNull(f.globalAlpha)) c.globalAlpha = f.globalAlpha;
        if (e) {
            c.save();
            c.translate(this.offsetX, this.offsetY)
        }
        for (var d = 0; d < g.length; d++) {
            var a = g[d];
            if (a) {
                !this.isHighlighting && a.context && a.context.series && !a.isLegendItem && !(a instanceof l) && this.chart.elem.trigger("shapeRendering", a);
                if (a.src && this.isExcanvas) {
                    a.offsetX = this.offsetX;
                    a.offsetY = this.offsetY;
                    c.translate(-this.offsetX, -this.offsetY);
                    a.render(c);
                    c.translate(this.offsetX, this.offsetY)
                } else {
                    var h = a.shadowColor;
                    a.shadowColor = O.emptyColor;
                    a.render(c);
                    a.shadowColor = h
                }
            }
        }
        e && c.restore()
    };
    O.prototype._renderShadows = function(h) {
        if (d.use_excanvas) return;
        var g = this.offsetX && this.offsetY,
            c = this.chart.options;
        if (!c.shadows || !c.shadows.enabled) return;
        var a = this.ctx;
        if (!b.isNull(c.globalAlpha)) a.globalAlpha = c.globalAlpha;
        if (g) {
            a.save();
            a.translate(this.offsetX, this.offsetY)
        }
        for (var f = 0; f < h.length; f++) {
            var e = h[f];
            e && e.shadowColor && e.shadowColor != "rgba(0, 0, 0, 0)" && e.render(a)
        }
        g && a.restore()
    };
    O.prototype._clear = function() {
        var a = this.shapes;
        if (a)
            for (var b = 0; b < a.length; b++) a[b].deleted = true;
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height)
    };

    function ab(a) {
        this.defaults = {
            text: "",
            font: "14px sans-serif",
            fillStyle: "black",
            lineWidth: 0,
            margin: 6
        };
        this.x = 0;
        this.y = 0;
        this.setOptions(a)
    }
    ab.prototype._render = function(a) {
        if (this.text == null) return;
        this.textBlock.x = this.x + this.margin;
        this.textBlock.y = this.y + this.margin;
        this.textBlock.rotX = this.rotX;
        this.textBlock.rotY = this.rotY;
        this.textBlock.rotationAngle = this.rotationAngle;
        a.push(this.textBlock)
    };
    ab.prototype._measure = function() {
        var a;
        if (!this.text) {
            this.width = 0;
            this.height = 0;
            return
        }
        a = this.textBlock.measure(this.chart.ctx);
        var b = 2 * this.margin;
        this.width = a.width + b;
        this.height = a.height + b
    };
    ab.prototype.setOptions = function(c) {
        var b = a.extend({}, this.defaults, c || {});
        a.extend(this, b);
        this.textBlock = new l(this.text);
        this.textBlock.textBaseline = "top";
        this.textBlock.font = this.font;
        this.textBlock.fillStyle = this.fillStyle;
        this.textBlock.strokeStyle = this.strokeStyle;
        this.textBlock.lineWidth = this.lineWidth
    };

    function Sb(b) {
        var c = a.extend(false, {}, this.defaults, {
            hAlign: "left",
            vAlign: "top"
        });
        this.defaults = c;
        this.chart = b
    }
    Sb.prototype = new ab;
    Sb.constructor = Sb;
    Sb.prototype._render = function(f) {
        var c = this.chart;
        if (this.text == null || !c) return;
        var e = c._width,
            d = c._height,
            a = this.margin + this.textBlock.width / 2,
            b = this.margin;
        switch (this.hAlign) {
            case "center":
                a += (e - this.width) / 2;
                break;
            case "right":
                a += e - this.width
        }
        switch (this.vAlign) {
            case "center":
                b += (d - this.height) / 2;
                break;
            case "bottom":
                b += d - this.height
        }
        this.textBlock.textAlign = "center";
        this.textBlock.x = a;
        this.textBlock.y = b;
        this.textBlock.rotX = a;
        this.textBlock.rotY = b;
        this.textBlock.rotationAngle = this.angle;
        f.push(this.textBlock)
    };

    function wb(a) {
        this.defaults = {
            visible: true,
            strokeStyle: "black",
            lineWidth: 1,
            lineCap: "butt",
            lineJoin: "miter",
            miterLimit: 10,
            cornerRadius: 10,
            padding: 4,
            ignoreOffset: false
        };
        this.x = 0;
        this.y = 0;
        this.setOptions(a)
    }
    wb.prototype._setShapeSettings = function(a) {
        a.fillStyle = this.fillStyle;
        if (this.visible) {
            a.strokeStyle = this.strokeStyle;
            a.lineWidth = this.lineWidth;
            a.lineCap = this.lineCap;
            a.lineJoin = this.lineJoin;
            a.miterLimit = this.miterLimit;
            a.cornerRadius = this.cornerRadius
        } else a.lineWidth = 0
    };
    wb.prototype._render = function(c) {
        var a = this.visible ? this.lineWidth / 2 : 0,
            f = this.x + a,
            g = this.y + a,
            e = this.width - 2 * a,
            d = this.height - 2 * a,
            b = new p(f, g, e, d);
        this._setShapeSettings(b);
        c.push(b)
    };
    wb.prototype.setOptions = function(c) {
        var b = a.extend({}, this.defaults, c || {});
        a.extend(this, b)
    };
    wb.prototype.getPaddings = function() {
        var b = {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
        };
        if (!this.visible) return b;
        var d = this.lineWidth + this.cornerRadius / 2;
        if (this.ignoreOffset === true) d = this.lineWidth;
        var g, h, f, e, c = this.padding;
        if (a.isPlainObject(c)) {
            g = c.left || 0;
            h = c.top || 0;
            f = c.right || 0;
            e = c.bottom || 0
        } else g = h = f = e = c || 0;
        b.left = g + d;
        b.top = h + d;
        b.right = f + d;
        b.bottom = e + d;
        return b
    };

    function Ub(a) {
        this.defaults = {
            strokeStyle: "black",
            lineWidth: 1,
            length: 6,
            position: "outside",
            visible: true,
            zIndex: 2,
            offset: .4
        };
        this.setOptions(a)
    }
    Ub.prototype.isInside = function() {
        return this.position == "inside"
    };
    Ub.prototype._setLineSettings = function(a) {
        a.strokeStyle = this.strokeStyle;
        a.lineWidth = this.lineWidth;
        a.strokeDashArray = this.strokeDashArray
    };
    Ub.prototype.setOptions = function(c) {
        var b = a.extend({}, this.defaults, c || {});
        a.extend(this, b)
    };

    function Kb(a) {
        U.call(this, a)
    }
    Kb.prototype = new U;
    Kb.constructor = Kb;
    Kb.prototype.mouseWheel = function(e, c) {
        if (!c) return;
        var d = this.view.options.mouseWheelInteractionMode;
        if (d != "scrolling" && d != "zooming") return;
        var a = this.view.axes.getZoomableAxes();
        a.length > 0 && e.preventDefault();
        for (var b = 0; b < a.length; b++)
            if (d == "scrolling") a[b]._mouseWheelScroll(c);
            else a[b]._mouseWheelZoom(c);
        this.view.partialDelayedUpdate()
    };

    function W(a) {
        Q.call(this, a)
    }
    W.prototype = new Q;
    W.constructor = W;
    W.prototype.canStart = function(a) {
        if (this.view.options.mouseInteractionMode != "zooming") return false;
        if (!this.view.gridArea.isMouseOver) return false;
        if (this.view.canZoom) {
            a.preventDefault();
            return true
        }
        return false
    };
    W.prototype.start = function() {
        this.view._clearRenderers();
        this.zoomableAxes = this.view.axes.getZoomableAxes();
        this.mouseInput1 = this.view.mouseInput;
        this.currCursor = this.view.elem.css("cursor").toString();
        if (this.view.canZoomHor && this.view.canZoomVer) this.view.elem.css("cursor", "crosshair");
        else if (this.view.canZoomHor) this.view.elem.css("cursor", "e-resize");
        else this.view.elem.css("cursor", "s-resize")
    };
    W.prototype.mouseMove = function() {
        var a = this.view.mouseInput;
        this.view._renderSelectionRect(this.mouseInput1, a)
    };
    W.prototype.mouseUp = function() {
        var a = this.mouseInput1,
            b = this.view.mouseInput,
            e = Math.abs(a.x - b.x),
            f = Math.abs(a.y - b.y);
        if (e > 1 || f > 1) {
            for (var d = this.zoomableAxes, c = 0; c < d.length; c++) d[c]._scaleToRegion(a, b);
            this.view.partialDelayedUpdate()
        }
        this.stopTool()
    };
    W.prototype.keyDown = function(a) {
        if (a.which == 27) {
            a.preventDefault();
            this.stopTool()
        }
    };
    W.prototype.stop = function() {
        this.zoomableAxes = null;
        this.view._clearSelectionRect();
        this.view.elem.css("cursor", this.currCursor);
        Q.prototype.stop.call(this)
    };

    function db(a) {
        Q.call(this, a)
    }
    db.prototype = new Q;
    db.constructor = db;
    db.prototype.canStart = function(a) {
        if (this.view.options.mouseInteractionMode != "panning") return false;
        if (!this.view.gridArea.isMouseOver) return false;
        if (this.view.canZoom) {
            a.preventDefault();
            return true
        }
        return false
    };
    db.prototype.start = function() {
        this.zoomableAxes = this.view.axes.getZoomableAxes();
        this.oldMouseInput = this.view.mouseInput;
        this.currCursor = this.view.originalCursor;
        var b = this.view.originalCursor = a.browser && a.browser.mozilla ? "-moz-grabbing" : "move";
        this.view.elem.css("cursor", b)
    };
    db.prototype.mouseMove = function() {
        for (var f = this.zoomableAxes, a = this.view.mouseInput, c = this.zoomableAxes, d = this.oldMouseInput.locX - a.locX, e = this.oldMouseInput.locY - a.locY, b = 0; b < c.length; b++) c[b]._moveVisibleRange(d, e);
        this.oldMouseInput = a;
        this.view.partialDelayedUpdate()
    };
    db.prototype.mouseUp = function() {
        this.stopTool()
    };
    db.prototype.stop = function() {
        this.zoomableAxes = null;
        this.view.originalCursor = this.currCursor;
        this.view._resetCursor();
        Q.prototype.stop.call(this)
    };

    function P(a) {
        Q.call(this, a)
    }
    P.prototype = new Q;
    P.constructor = P;
    P.prototype.canStart = function(d) {
        if (!this.view.canZoom) return false;
        for (var b = this.view.touchInput, a = 0; a < b.length; a++) {
            var c = b[a];
            if (!this.view.gridArea._contains(c.locX, c.locY)) return false
        }
        d.preventDefault();
        return true
    };
    P.prototype.start = function() {
        this.zoomableAxes = this.view.axes.getZoomableAxes();
        this.oldTouchInput = this.view.touchInput
    };
    P.prototype.touchMove = function(b) {
        b.preventDefault();
        if (!this.oldTouchInput) {
            this.oldTouchInput = this.view.touchInput;
            return
        }
        var c = this.view.touchInput.length;
        if (c > 2) return;
        var a = false;
        if (c == 2) a = true;
        if (this.isScaling != a) {
            this.oldTouchInput = this.view.touchInput;
            this.isScaling = a
        }
        if (a) this.doScale(b);
        else this.doPan(b)
    };
    P.prototype.touchEnd = function() {
        if (this.view.touchInput.length == 0) this.stopTool();
        else this.oldTouchInput = null
    };
    P.prototype.stop = function() {
        this.zoomableAxes = null;
        this.oldTouchInput = null;
        Q.prototype.stop.call(this)
    };
    P.prototype.doPan = function() {
        for (var e = this.zoomableAxes, c = this.view.touchInput, d = c[0], b = this.oldTouchInput[0], f = b.locX - d.locX, g = b.locY - d.locY, a = 0; a < e.length; a++) e[a]._moveVisibleRange(f, g);
        this.oldTouchInput = c;
        this.view.partialDelayedUpdate()
    };
    P.prototype.doScale = function() {
        var a = this.view.touchInput;
        if (!this.oldTouchInput || this.oldTouchInput.length != 2) {
            this.oldTouchInput = a;
            return
        }
        for (var d = this.getTwoTouchPointData(this.oldTouchInput), e = this.getTwoTouchPointData(a), c = this.zoomableAxes, b = 0; b < c.length; b++) c[b]._scaleVisibleRange(d, e);
        this.oldTouchInput = a;
        this.view.partialDelayedUpdate()
    };
    P.prototype.getTwoTouchPointData = function(b) {
        var a = {
            x1: b[0].locX,
            y1: b[0].locY,
            x2: b[1].locX,
            y2: b[1].locY
        };
        a.centerX = (a.x1 + a.x2) / 2;
        a.centerY = (a.y1 + a.y2) / 2;
        a.dx = Math.abs(a.x2 - a.x1);
        a.dy = Math.abs(a.y2 - a.y1);
        return a
    };

    function e(b) {
        this.pluginClass = "ui-jqchart";
        this.tooltipClass = "ui-jqchart-tooltip";
        d.call(this, b);
        this.timer = new hc(a.proxy(this.partialUpdate, this));
        this.storyboard = new bc(a.proxy(this._renderShapes, this));
        this.defaultTool = new Kb(this);
        this.currentTool = this.defaultTool;
        this.mouseDownTools.push(new db(this));
        this.mouseDownTools.push(new W(this));
        this.touchMoveTools.push(new P(this))
    }
    e.prototype = new d;
    e.constructor = e;
    e.prototype._createElements = function(e) {
        d.prototype._createElements.call(this, e);
        if (d.use_excanvas) {
            this.areaCanvas = this._createCanvas(true);
            var c = a('<div style="position:absolute"></div>');
            this.elem.append(c);
            c.append(this.areaCanvas);
            this.areaRenderer = new O(this.areaCanvas, this);
            this.areaRenderer.div = c;
            this.areaRenderer.isExcanvas = true;
            this.upperCanvas = this._createCanvas(true);
            var b = a('<div style="position:absolute"></div>');
            this.elem.append(b);
            b.append(this.upperCanvas);
            this.upperRenderer = new O(this.upperCanvas, this);
            this.upperRenderer.div = b;
            this.upperRenderer.isExcanvas = true
        }
        this.chCanvas = this._createCanvas();
        this.chRenderer = new O(this.chCanvas, this);
        this._createHighlightRenderer();
        this.gridArea = new B(this);
        this.border = new wb;
        this.paletteColors = new Vb;
        this.title = new ab;
        this.title.chart = this;
        this.legend = new lb;
        this.legend.chart = this;
        /*this.watermark = new Sb(this);*/
        this.toolbar = new Zb(this);
        this.noDataMessage = new ab;
        this.noDataMessage.chart = this;
        this.series = new x(this);
        this.axes = new r(this)
    };
    e.prototype._processOptions = function(c) {
        var d = this;
        c = c || {};
        d.arrDataSource = null;
        var b = c.dataSource;
        if (b && a.isArray(b)) {
            d.arrDataSource = b;
            d._setOptions(c);
            return
        }
        if (a.isPlainObject(b) && b.ajax) {
            var g = b.ajax,
                e = {};
            a.extend(e, g);
            e.success = function(a) {
                d.arrDataSource = a.hasOwnProperty("d") ? a.d : a
            };
            var f = e.complete;
            e.complete = function() {
                f && f();
                d._setOptions(c)
            };
            a.ajax(e);
            return
        }
        d._setOptions(c)
    };
    e.prototype._setOptions = function(h) {
        if (!this.elem || this.elem.length == 0) return;
        var c = h || {};
        if (typeof c.title == "string") c.title = {
            text: c.title
        };
        c.title = c.title || {};
        c.title = a.extend({}, a.fn.jqChart.defaults.title, c.title);
        c.crosshairs = a.extend(true, {}, a.fn.jqChart.defaults.crosshairs, c.crosshairs);
        c.tooltips = a.extend(true, {}, a.fn.jqChart.defaults.tooltips, c.tooltips);
        c.shadows = a.extend(true, {}, a.fn.jqChart.defaults.shadows, c.shadows);
        c.selectionRect = a.extend(true, {}, a.fn.jqChart.defaults.selectionRect, c.selectionRect);
        /*c.watermark = a.extend(true, {}, a.fn.jqChart.defaults.watermark, c.watermark);*/
        if (typeof c.noDataMessage == "string") c.noDataMessage = {
            text: c.noDataMessage
        };
        c.noDataMessage = a.extend(true, {}, a.fn.jqChart.defaults.noDataMessage, c.noDataMessage);
        c.globalAlpha = b.isNull(c.globalAlpha) ? a.fn.jqChart.defaults.globalAlpha : c.globalAlpha;
        c.mouseInteractionMode = c.mouseInteractionMode || a.fn.jqChart.defaults.mouseInteractionMode;
        c.mouseWheelInteractionMode = c.mouseWheelInteractionMode || a.fn.jqChart.defaults.mouseWheelInteractionMode;
        this.hasCrosshairs = c.crosshairs.enabled === true;
        this.hasTooltips = c.tooltips && !c.tooltips.disabled;
        this.hasHighlighting = c.tooltips && c.tooltips.highlighting;
        this.options = c;
        d.prototype._setOptions.call(this, c);
        var g = h || {};
        if (g.width) this.elem.css("width", g.width);
        else this.elem.width() == 0 && this.elem.css("width", "400px");
        if (g.height) this.elem.css("height", g.height);
        else this.elem.height() == 0 && this.elem.css("height", "250px");
        var f = this._width = this.elem.width(),
            e = this._height = this.elem.height(),
            c = this.options;
        this.border.setOptions(c.border);
        this.border.fillStyle = c.background || this.border.fillStyle;
        this.gridArea.fillStyle = c.chartAreaBackground;
        this.paletteColors.setOptions(c.paletteColors);
        this.title.setOptions(c.title);
        this.legend.setOptions(c.legend);
        /*this.watermark.setOptions(c.watermark);*/
        this.toolbar.setOptions(c.toolbar);
        this.noDataMessage.setOptions(c.noDataMessage);
        this.series.setOptions(c.series);
        this.axes.setOptions(c.axes);
        this._setCanvasSize(this.canvas, f, e);
        this._setCanvasSize(this.chCanvas, f, e);
        this._setCanvasSize(this.hlCanvas, f, e);
        this.areaCanvas && this._setCanvasSize(this.areaCanvas, f, e);
        this.upperCanvas && this._setCanvasSize(this.upperCanvas, f, e);
        this.update()
    };
    e.prototype.isServerFiltering = function() {
        var b = this.options.dataSource;
        return !b ? false : b.serverFiltering === true && a.isPlainObject(b) && b.ajax ? true : false
    };
    e.prototype._processAsyncData = function() {
        if (!this.isServerFiltering()) return;
        var d = this,
            i = d.options.dataSource;
        d.arrDataSource = null;
        var j = i.ajax,
            b = {};
        a.extend(b, j);
        b.url += "?";
        for (var h = this.axes.getZoomableAxes(), e = 0; e < h.length; e++) {
            var c = h[e];
            c.minimum = c.actualMinimum;
            c.maximum = c.actualMaximum;
            if (e > 0) b.url += "&";
            if (c.name) b.url += "name=" + c.name + "&";
            var f = 0;
            if (c.DataType == "DateTimeAxis") f = (new Date).getTimezoneOffset() * 60 * 1e3;
            b.url += "start=" + (c.visibleMinimum - f) + "&end=" + (c.visibleMaximum - f)
        }
        b.success = function(a) {
            d.arrDataSource = a.hasOwnProperty("d") ? a.d : a
        };
        var g = b.complete;
        b.complete = function() {
            g && g();
            d.series._processData();
            d.update()
        };
        a.ajax(b)
    };
    e.prototype._mouseEnter = function(a) {
        d.prototype._mouseEnter.call(this, a);
        this.toolbar.show()
    };
    e.prototype._mouseLeave = function(a) {
        d.prototype._mouseLeave.call(this, a);
        this.toolbar.hide()
    };
    e.prototype._measure = function() {
        this.title._measure();
        this.legend._measure();
        /*this.watermark._measure();*/
        this.noDataMessage._measure();
        return this.axes._measure()
    };
    e.prototype._arrange = function() {
        var d = this._width,
            g = this._height;
        this.border.width = d;
        this.border.height = g;
        var c = this.border.getPaddings();
        d -= c.left + c.right;
        g -= c.top + c.bottom;
        var e = c.left,
            f = c.top + this.title.height,
            o = this.axes._getTotalWidth(),
            n = this.axes._getTotalHeight();
        if (this.legend._isHorizontal()) {
            this.gridArea.width = Math.round(d - o);
            this.gridArea.height = Math.round(g - (n + this.title.height + this.legend.height))
        } else {
            this.gridArea.width = Math.round(d - (o + this.legend.width));
            this.gridArea.height = Math.round(g - (n + this.title.height))
        }
        switch (this.legend.location) {
            case "left":
                e += this.legend.width;
                break;
            case "top":
                f += this.legend.height
        }
        for (var l = this.axes.verCrossingAxis, k = this.axes.horCrossingAxis, i = this.axes._getAxesInLoc("left"), b = i.length - 1; b >= 0; b--) {
            var a = i[b];
            a.x = e;
            a.height = this.gridArea.height;
            if (!k) e = Math.ceil(e + a.width)
        }
        this.gridArea.x = e;
        e += this.gridArea.width;
        for (var j = this.axes._getAxesInLoc("right"), b = 0; b < j.length; b++) {
            var a = j[b];
            a.x = e;
            a.height = this.gridArea.height;
            if (!k) e = Math.ceil(e + a.width)
        }
        for (var p = this.axes._getAxesInLoc("top"), b = p.length - 1; b >= 0; b--) {
            var a = p[b];
            a.x = this.gridArea.x;
            a.y = f;
            a.width = this.gridArea.width;
            if (!l) f = Math.ceil(f + a.height)
        }
        this.gridArea.y = f;
        f += this.gridArea.height;
        for (var h = this.axes._getAxesInLoc("bottom"), b = 0; b < h.length; b++) {
            var a = h[b];
            a.x = this.gridArea.x;
            a.y = f;
            a.width = this.gridArea.width;
            if (!l) f = Math.ceil(f + a.height)
        }
        for (var m = i.concat(j), b = 0; b < m.length; b++) {
            var a = m[b];
            a.y = this.gridArea.y
        }
        for (var h = this.axes._getAxesInLoc("radial"), b = 0; b < h.length; b++) {
            var a = h[b];
            a.x = this.gridArea.x;
            a.y = this.gridArea.y;
            a.width = this.gridArea.width;
            a.height = this.gridArea.height
        }
        this.title.x = c.left + (d - this.title.width) / 2;
        this.title.y = c.top;
        switch (this.legend.location) {
            case "bottom":
                this.legend.x = c.left + (d - this.legend.width) / 2;
                this.legend.y = c.top + g - this.legend.height;
                break;
            case "left":
                this.legend.x = c.left;
                this.legend.y = c.top + this.gridArea.y + (this.gridArea.height - this.legend.height) / 2;
                break;
            case "top":
                this.legend.x = c.left + (d - this.legend.width) / 2;
                this.legend.y = c.top + this.title.height;
                break;
            case "right":
            default:
                this.legend.x = c.left + d - this.legend.width;
                this.legend.y = this.gridArea.y + (this.gridArea.height - this.legend.height) / 2
        }
        this.gridArea._arrange();
        this.axes._arrange();
        this.legend._arrange();
        this.noDataMessage.x = c.left + (d - this.noDataMessage.width) / 2;
        this.noDataMessage.y = c.top + (g - this.noDataMessage.height) / 2
    };
    e.prototype.setPointerAt = function(a) {
        if (!a) {
            this._clearRenderers();
            return
        }
        var b = {
            locX: a.x,
            locY: a.y
        };
        this.mouseInput = b;
        this._processMouseMove(b)
    };
    e.prototype._processMouseMove = function() {
        var a = this.mouseInput;
        if (this.gridArea) {
            var b = this.gridArea._contains(a.locX, a.locY);
            if (this.gridArea.isMouseOver != b) {
                !b && this._clearRenderers();
                this.gridArea.isMouseOver = b
            }
        }
        this._processMouseEvents();
        if (this.gridArea.isMouseOver) {
            this._processTooltips(a);
            this._initCrosshairs(a)
        } else this.hasCrosshairs && this.elem.trigger("crosshairsMove", null)
    };
    e.prototype._initTouchInput = function(c) {
        d.prototype._initTouchInput.call(this, c);
        var b = this.touchInput[0];
        if (!this.gridArea || !b) return;
        var a = this.gridArea._contains(b.locX, b.locY);
        if (this.gridArea.isTouchOver != a) {
            !a && this._clearRenderers();
            this.gridArea.isTouchOver = a
        }
    };
    e.prototype._processTouchStart = function(b) {
        if (!this.gridArea.isTouchOver) return;
        var a = this.touchInput[0];
        b.preventDefault();
        this._processTooltips(a);
        this._initCrosshairs(a)
    };
    e.prototype._processTouchMove = function(b) {
        if (!this.gridArea.isTouchOver) return;
        var a = this.touchInput[0];
        b.preventDefault();
        this._processTouchEvents();
        this._processTooltips(a);
        this._initCrosshairs(a)
    };
    e.prototype._clearRenderers = function() {
        d.prototype._clearRenderers.call(this);
        this.chRenderer && this.chRenderer._clear();
        if (this._oldChShape) this._oldChShape = null
    };
    e.prototype._getClosestShapeAtX = function(f, g) {
        for (var c = null, i = j, k = g.locX, d = f.length - 1; d >= 0; d--) {
            var a = f[d];
            if (!a.context || a.isLegendItem || a.isAxisLabel) continue;
            var e = a.context.series;
            if (e.tooltips && e.tooltips.disabled) continue;
            var b = a.getCenter(g),
                h = Math.abs(b.x - k);
            if (i > h) {
                i = h;
                c = a;
                if (b.mark) c = b.mark
            }
        }
        return c
    };
    e.prototype._getClosestShapeAtY = function(c, d) {
        for (var b = null, g = j, i = d.locY, a = c.length - 1; a >= 0; a--) {
            var e = c[a],
                h = e.getCenter(d).y,
                f = Math.abs(h - i);
            if (g > f) {
                g = f;
                b = e
            }
        }
        return b
    };
    e.prototype._getShapesAtX = function(o, h, k, n) {
        for (var e = [h], j = [h.context.series], g = [0], i = 0; i < k.length; i++) {
            var b = k[i];
            if (b == h || !b.context || b.isLegendItem || b.isAxisLabel) continue;
            var l = b.context.series,
                c = a.inArray(l, j);
            if (c == 0) continue;
            var d = b.getCenter(n),
                f = Math.abs(o - d.x);
            if (f >= 3) continue;
            if (c != -1) {
                var m = g[c];
                if (f < m) {
                    if (d.mark) e[c] = d.mark;
                    else e[c] = b;
                    g[c] = f
                }
                continue
            }
            if (d.mark) e.push(d.mark);
            else e.push(b);
            j.push(l);
            g.push(f)
        }
        return e
    };
    e.prototype._getTooltipShapes = function(h, i, g, c) {
        var b = null,
            f = this.options.tooltips;
        if (f.type == "shared") {
            var e = this._getClosestShapeAtX(this.shapes, c);
            if (e) b = this._getShapesAtX(e.getCenter(c).x, e, this.shapes, c)
        } else b = d.prototype._getTooltipShapes.call(this, h, i, g, c);
        if (b) {
            series = this.series.items;
            b.sort(function(d, e) {
                var b = a.inArray(d.context.series, series),
                    c = a.inArray(e.context.series, series);
                return b - c
            })
        }
        return b
    };
    e.prototype._getTooltip = function(a) {
        return a.context.series._getTooltip(a.context)
    };
    e.prototype._getTooltipText = function(d) {
        for (var e = "", i = this, g = [], f = 0; f < d.length; f++) {
            var c = d[f];
            if (!c.context.series) continue;
            var b = c.context.series.realXAxis;
            if (b && c.context.x && a.inArray(b, g) == -1) {
                var h = b._getTooltip(c.context.x);
                e += h;
                g.push(b)
            }
        }
        a.each(d, function() {
            var a = i._getTooltip(this);
            if (a) e += a
        });
        return e
    };
    e.prototype._initCrosshairs = function(d) {
        if (!this.hasCrosshairs) return;
        var h = this.options.crosshairs,
            c = {},
            e = d.locX,
            f = d.locY;
        if (h.snapToDataPoints) {
            var b = this._getClosestShapeAtX(this.shapes, d);
            if (b) {
                var i = this._getShapesAtX(b.getCenter(d).x, b, this.shapes, d);
                b = this._getClosestShapeAtY(i, d);
                if (b) {
                    var g = b.getCenter(d);
                    e = g.x;
                    f = g.y;
                    if (this._oldChShape != b) {
                        this._oldChShape = b;
                        a.extend(c, b.context);
                        c.locX = e;
                        c.locY = f;
                        this.elem.trigger("crosshairsMove", c)
                    }
                }
            }
        } else {
            c.x = e;
            c.y = f;
            this.elem.trigger("crosshairsMove", c)
        }
        this._renderCrosshairs(e, f)
    };
    e.prototype._renderCrosshairs = function(e, f) {
        if (!this.hasCrosshairs) return;
        var c = this.gridArea;
        if (!c._contains(e, f)) return;
        var b = this.options.crosshairs,
            d = [];
        if (b.hLine && b.hLine.visible) {
            var g = new i(c.x, f, c.x + c.width, f);
            b.hLine && a.extend(g, b.hLine);
            d.push(g)
        }
        if (b.vLine && b.vLine.visible) {
            var h = new i(e, c.y, e, c.y + c.height);
            b.vLine && a.extend(h, b.vLine);
            d.push(h)
        }
        this.chRenderer._clear();
        this.chRenderer._render(d)
    };
    e.prototype._renderSelectionRect = function(g, h) {
        var b = this.gridArea,
            c = b.fitHor(g.locX),
            e = b.fitVer(g.locY),
            d = b.fitHor(h.locX),
            f = b.fitVer(h.locY);
        if (!this.canZoomVer) {
            e = b.y;
            f = b.y + b.height
        } else if (!this.canZoomHor) {
            c = b.x;
            d = b.x + b.width
        }
        var l = Math.min(c, d),
            m = Math.min(e, f),
            k = Math.abs(c - d),
            j = Math.abs(e - f),
            i = new p(l, m, k, j);
        a.extend(i, this.options.selectionRect);
        this.chRenderer._clear();
        this.chRenderer._render([i])
    };
    e.prototype._clearSelectionRect = function() {
        this.chRenderer._clear()
    };
    e.prototype._initZooming = function() {
        var b = this;
        b.canZoomVer = false;
        b.canZoomHor = false;
        a.each(this.axes.items, function() {
            if (this.zoomEnabled)
                if (this.location != "radial")
                    if (this.isAxisVertical) b.canZoomVer = true;
                    else b.canZoomHor = true
        });
        this.canZoom = this.canZoomVer || this.canZoomHor
    };
    e.prototype._triggerShapeEvent = function(b, a) {
        if (a.isLegendItem) this.legend._handleEvent(b, a);
        else a.context.series && a.context.series._handleEvent(b, a);
        var c = a.isLegendItem ? "legendItem" : "dataPoint";
        if (a.isAxisLabel) c = "axisLabel";
        a.context.shape = a;
        this.elem.trigger(c + b, a.context)
    };
    e.prototype.setOptions = function() {};
    e.prototype.clear = function() {
        d.prototype.clear.call(this);
        if (d.use_excanvas) {
            this.areaRenderer._clear();
            this.upperRenderer._clear()
        }
    };
    e.prototype._setClip = function(b) {
        var a = this.gridArea;
        b.beginPath();
        b.rect(a.x, a.y, a.width, a.height);
        b.clip()
    };
    e.prototype._createShapes = function() {
        var b = {},
            d = [],
            f = this.gridArea,
            c = b.shapes = [];
        this.border._render(c);
        if (this.hasData) {
            this.title._render(c);
            var i = this.legend._render(c);
            b.gaShapes = [];
            f._render(b.gaShapes);
            b.axesShapes = [];
            var g = this.axes._render(b.axesShapes);
            b.postAxisShapes = g.postShapes;
            var e = b.nonGridAreaSerShapes = [];
            this.series._render(e);
            var h = b.serShapes = [],
                j = f._renderSeries(h);
            b.gaZIndexShapes = [];
            f._renderZIndex(b.gaZIndexShapes, 2);
            a.merge(e, j);
            a.merge(d, h);
            a.merge(d, e);
            a.merge(d, i);
            a.merge(d, g.contextShapes)
        } else this.noDataMessage._render(c);
        c = b.ws = [];
        /*this.watermark._render(c);*/
        /*this._addTrialWatermark(c);*/
        this.shapes = d;
        this.allShapes = b
    };
    e.prototype._renderShapes = function() {
        var j = d.isRetinaDisplay,
            f = this._width,
            e = this._height;
        if (j) {
            this._setRetinaDispOpts(this.canvas, f, e);
            this._setRetinaDispOpts(this.chCanvas, f, e);
            this._setRetinaDispOpts(this.hlCanvas, f, e)
        }
        this.shapeRenderer._clear();
        if (d.use_excanvas) {
            this.areaRenderer._clear();
            this.upperRenderer._clear()
        }
        var k = this.gridArea,
            b = this.allShapes;
        this.shapeRenderer._render(b.shapes);
        if (this.hasData) {
            var i = b.gaShapes;
            if (d.use_excanvas) this.areaRenderer._render(i);
            else {
                this.ctx.save();
                this._setClip(this.ctx);
                this.shapeRenderer._renderShadows(i);
                this.shapeRenderer._render(i);
                this.ctx.restore()
            }
            var g = b.serShapes;
            g.length > 0 && this.elem.trigger("seriesShapesRendering", [g]);
            if (d.use_excanvas) {
                this.areaRenderer._render(g);
                this.areaRenderer._render(b.gaZIndexShapes)
            } else {
                this.ctx.save();
                this._setClip(this.ctx);
                this.shapeRenderer._renderShadows(g);
                this.shapeRenderer._render(g);
                this.shapeRenderer._render(b.gaZIndexShapes);
                this.ctx.restore()
            }
            var c;
            if (d.use_excanvas) c = this.upperRenderer;
            else c = this.shapeRenderer;
            c._render(b.axesShapes);
            var h = b.nonGridAreaSerShapes;
            h.length > 0 && this.elem.trigger("seriesShapesRendering", [h]);
            c._renderShadows(h);
            c._render(h);
            c._render(b.postAxisShapes)
        }
        this.shapeRenderer._render(b.ws);
        this.shapeRenderer.shapes = this.shapes;
        if (j) {
            a(this.canvas).css({
                width: f,
                height: e
            });
            a(this.chCanvas).css({
                width: f,
                height: e
            });
            a(this.hlCanvas).css({
                width: f,
                height: e
            })
        }
    };
    e.prototype.stringFormat = function(b, c) {
        return a.type(b) == "date" ? a.jqChartDateFormatter(b, c) : a.jqChartSprintf(c, b)
    };
    e.prototype.render = function() {
        this._clearRenderers();
        this._createShapes();
        this._renderShapes()
    };
    e.prototype.findAxis = function(a) {
        if (this.axes) return this.axes.find(a)
    };
    e.prototype.update = function() {
        this.hasData = this.series.hasData();
        this.series._initData();
        this.axes._initSeriesAxes();
        this.axes._initSeries();
        this._initZooming();
        this.axes._initRanges();
        this.series._initVisibleData();
        this.series._initColors();
        this.legend._init();
        this.axes._resetWH();
        this.axes._initCrossingAxes();
        for (var a = false, b = 0; b < 10; b++) {
            a = this._measure();
            this._arrange();
            this.axes._updateOrigins();
            this.axes._initRanges();
            this.axes._updateCrossings();
            this.axes._correctOrigins();
            if (a == false) break
        }
        this.toolbar._init();
        this.render();
        this.storyboard.begin()
    };
    e.prototype.partialDelayedUpdate = function() {
        if (this.isServerFiltering()) this._processAsyncData();
        else this.timer.kick()
    };
    e.prototype.partialUpdate = function() {
        this.series._initVisibleData();
        this.axes._resetWH();
        var a = false;
        this.axes._initRanges();
        for (var b = 0; b < 10; b++) {
            a = this._measure();
            this._arrange();
            this.axes._updateOrigins();
            this.axes._initRanges();
            this.axes._updateCrossings();
            this.axes._correctOrigins();
            if (a == false) break
        }
        this.render()
    };
    e.prototype.highlightData = function(c) {
        var a = d.prototype.highlightData.call(this, c);
        if (a) {
            var b = a[0].getCenter();
            this._renderCrosshairs(b.x, b.y)
        }
    };
    e.prototype.destroy = function() {
        this.axes.clear();
        d.prototype.destroy.call(this)
    };
    e.prototype.exportToImage = function(b) {
        this._exportToImage(b, a.fn.jqChart.defaults.exportConfig)
    };
    e.prototype.exportToPdf = function(b) {
        this._exportToPdf(b, a.fn.jqChart.defaults.exportConfig)
    };
    e.prototype.toJSON = function(j) {
        var e = this.gridArea,
            d = this.options.shadows,
            f = d && d.enabled,
            i = new fc(e.x, e.y, e.width, e.height),
            h = new cc,
            d = new Xb(true),
            g = new Xb(false),
            c = this.allShapes,
            b = [];
        a.merge(b, c.shapes);
        if (this.hasData) {
            b.push(i);
            if (f) {
                b.push(d);
                a.merge(b, c.gaShapes);
                b.push(g)
            }
            a.merge(b, c.gaShapes);
            b.push(h);
            a.merge(b, c.axesShapes);
            b.push(i);
            if (f) {
                b.push(d);
                a.merge(b, c.serShapes);
                b.push(g)
            }
            a.merge(b, c.serShapes);
            a.merge(b, c.gaZIndexShapes);
            b.push(h);
            if (f) {
                b.push(d);
                a.merge(b, c.nonGridAreaSerShapes);
                b.push(g)
            }
            a.merge(b, c.nonGridAreaSerShapes);
            a.merge(b, c.postAxisShapes)
        }
        a.merge(b, c.ws);
        return this._toJSON(b, j)
    };

    function M(f, a, b, e, d, g) {
        c.call(this);
        this.high = a;
        this.low = b;
        this.open = e;
        this.close = d;
        this.x = f - g / 2;
        this.width = g;
        this.height = Math.abs(a - b);
        this.y = Math.min(a, b);
        this.isUp = d < e;
        this.tooltipOrigin = {
            x: f,
            y: Math.min(a, b)
        };
        this.center = {
            x: f,
            y: (e + d) / 2
        };
        this.createElements && this.createElements(f, a, b, e, d, g)
    }
    M.prototype = new c;
    M.constructor = new M;
    M.prototype.createElements = function(a, h, j, e, d, g) {
        var c = [],
            f = g / 2,
            b = new i(a, h, a, j);
        c.push(b);
        b = new i(a - f, e, a, e);
        c.push(b);
        b = new i(a, d, a + f, d);
        c.push(b);
        this.items = c
    };
    M.prototype.hitTest = function(c, d, a) {
        if (a) return this.boundsHitTest(c, d, a);
        for (var b = 0; b < this.items.length; b++) {
            var e = this.items[b];
            if (e.hitTest(c, d, a)) return true
        }
        return false
    };
    M.prototype.render = function(d) {
        if (!this.visible) return;
        c.prototype.render.call(this, d);
        for (var b = 0; b < this.items.length; b++) {
            var a = this.items[b];
            this.setProperties(a);
            if (this.isUp) {
                if (this.priceUpStrokeStyle) a.strokeStyle = this.priceUpStrokeStyle
            } else if (this.priceDownStrokeStyle) a.strokeStyle = this.priceDownStrokeStyle;
            a.render(d)
        }
    };
    M.prototype._createHighlightShape = function(c, d) {
        var b = new M;
        a.extend(b, this);
        b.fillStyle = c;
        b.priceUpFillStyle = c;
        b.priceDownFillStyle = c;
        b.strokeStyle = d;
        b.lineWidth += 2;
        return b
    };
    M.prototype.getTooltipColor = function() {
        if (this.isUp)
            if (this.priceUpStrokeStyle) return c.getColorFromFillStyle(this.priceUpStrokeStyle);
        return this.priceDownStrokeStyle ? c.getColorFromFillStyle(this.priceDownStrokeStyle) : c.prototype.getTooltipColor.call(this)
    };
    M.prototype.toJSON = function() {
        var a = this,
            b = "type:'stock',x:" + a.x + ",width:" + a.width + ",high:" + a.high + ",low:" + a.low + ",open:" + a.open + ",close:" + a.close;
        if (a.priceUpStrokeStyle) b += ",priceUpStrokeStyle:'" + a.priceUpStrokeStyle + "'";
        if (a.priceDownStrokeStyle) b += ",priceDownStrokeStyle:'" + a.priceDownStrokeStyle + "'";
        b += c.prototype.toJSON.call(this);
        return b
    };

    function hb(f, c, e, d, a, b) {
        M.call(this, f, c, e, d, a, b)
    }
    hb.prototype = new M;
    hb.constructor = new hb;
    hb.prototype.createElements = function(b, e, f, n, l, m) {
        var d = [],
            g = Math.floor(m / 2),
            b = Math.round(b),
            a = Math.round(n),
            c = Math.round(l);
        if (a > c) {
            var k = c;
            c = a;
            a = k
        }
        if (e > f) {
            var k = f;
            f = e;
            e = k
        }
        if (c - a >= 1) {
            var j = new p(b - g, a, 2 * g, c - a);
            j.useHitTestArea = true;
            d.push(j)
        } else {
            var h = new i(b - g, a, b + g, a);
            d.push(h)
        }
        var h = new i(b, e, b, a);
        d.push(h);
        var h = new i(b, c, b, f);
        d.push(h);
        this.items = d
    };
    hb.prototype.render = function(d) {
        if (!this.visible) return;
        c.prototype.render.call(this, d);
        for (var b = 0; b < this.items.length; b++) {
            var a = this.items[b];
            this.setProperties(a);
            if (a instanceof p)
                if (this.isUp) a.fillStyle = this.priceUpFillStyle;
                else a.fillStyle = this.priceDownFillStyle;
            a.render(d)
        }
    };
    hb.prototype.getTooltipColor = function() {
        return this.isUp ? c.getColorFromFillStyle(this.priceUpFillStyle) : c.getColorFromFillStyle(this.priceDownFillStyle)
    };
    hb.prototype.toJSON = function() {
        var a = this,
            b = "type:'candlestick',x:" + a.x + ",width:" + a.width + ",high:" + a.high + ",low:" + a.low + ",open:" + a.open + ",close:" + a.close;
        if (a.priceUpFillStyle) b += ",priceUpFillStyle:" + a.fillStyleToJSON(a.priceUpFillStyle);
        if (a.priceDownFillStyle) b += ",priceDownFillStyle:" + a.fillStyleToJSON(a.priceDownFillStyle);
        b += c.prototype.toJSON.call(this);
        return b
    };

    function Vb(a) {
        this.colorsDefault = ["#418CF0", "#FCB441", "#E0400A", "#056492", "#BFBFBF", "#1A3B69", "#FFE382", "#129CDD", "#CA6B4B", "#005CDB", "#F3D288", "#506381", "#F1B9A8", "#E0830A", "#7893BE"];
        this.colorsGrayScale = gc();
        this.defaults = {
            type: "default"
        };
        this.setOptions(a)
    }
    Vb.prototype.setOptions = function(c) {
        var b = a.extend({}, this.defaults, c || {});
        a.extend(this, b)
    };

    function gc() {
        for (var e = 16, c = [], b = 0; b < e; b++) {
            var a = 200 - b * 11;
            a = a.toString();
            var d = "rgb(" + a + "," + a + "," + a + ")";
            c.push(d)
        }
        return c
    }
    Vb.prototype.getColor = function(b) {
        var a = this.getColors(this.type),
            c = a.length;
        b %= c;
        return a[b]
    };
    Vb.prototype.getColors = function(a) {
        switch (a.toLowerCase()) {
            case "customcolors":
                return this.customColors;
            case "grayscale":
                return this.colorsGrayScale;
            case "default":
            default:
                return this.colorsDefault
        }
    };

    function Zb(a) {
        this.chart = a;
        this.defaults = {
            visibility: "auto",
            resetZoomTooltipText: "Reset Zoom (100%)",
            zoomingTooltipText: "Zoom in to selection area",
            panningTooltipText: "Pan the chart",
            hAlign: "right",
            vAlign: "top",
            resetZoomButtonVisible: true,
            zoomingButtonVisible: true,
            panningButtonVisible: true
        }
    }
    Zb.prototype = {
        setOptions: function(c) {
            var b = a.extend({}, this.defaults, c || {});
            a.extend(this, b)
        },
        _init: function() {
            var b = this.toolbar,
                d = this.chart;
            if (this.visibility == "hidden" || this.visibility == "auto" && !d.canZoom) {
                if (b) {
                    b.remove();
                    this.toolbar = null
                }
                return
            }
            if (!b) {
                this.toolbar = b = a('<ul onselectstart="return false;" class="ui-jqchart-toolbar ui-widget ui-widget-content ui-corner-all"></ul>');
                this._addButtons();
                b.hide();
                d.elem.append(b);
                b.mousedown(function(a) {
                    a.preventDefault();
                    return false
                });
                b.mouseenter(function(a) {
                    d._clearRenderers();
                    a.preventDefault();
                    return false
                });
                b.mousemove(function(a) {
                    a.preventDefault();
                    return false
                })
            }
            var h = b.outerWidth(),
                g = b.outerHeight(),
                c = d.gridArea,
                e = c.x,
                f = c.y;
            switch (this.hAlign) {
                case "center":
                    e += (c.width - h) / 2;
                    break;
                case "right":
                    e += c.width - h
            }
            switch (this.vAlign) {
                case "center":
                    f += (c.height - g) / 2;
                    break;
                case "bottom":
                    f += c.height - g
            }
            b.css({
                left: e,
                top: f
            });
            this.visibility == "visible" && b.show()
        },
        _addButtons: function() {
            var f = this.toolbar,
                d = this.chart;
            if (this.resetZoomButtonVisible) {
                var e = a("<li class='ui-corner-all ui-widget-header'><span class='ui-icon ui-icon-arrow-4-diag'></span></li>");
                f.append(e);
                this._addHover(e);
                e.attr("title", this.resetZoomTooltipText);
                e.mousedown(function(c) {
                    c.preventDefault();
                    for (var b = d.axes.items, a = 0; a < b.length; a++) b[a].resetZoom();
                    d.partialDelayedUpdate();
                    return false
                })
            }
            if (this.resetZoomButtonVisible && (this.zoomingButtonVisible || this.panningButtonVisible)) {
                var g = a("<li class='ui-jqchart-toolbar-separator'></li>");
                f.append(g)
            }
            if (this.panningButtonVisible) {
                var c = a("<li class='ui-corner-all ui-widget-header'><span class='ui-icon ui-icon-arrow-4'></span></li>");
                f.append(c);
                this._addHover(c);
                c.attr("title", this.panningTooltipText);
                c.mousedown(function(a) {
                    a.preventDefault();
                    d.options.mouseInteractionMode = "panning";
                    c.addClass("ui-state-active");
                    b.removeClass("ui-state-active");
                    return false
                })
            }
            if (this.zoomingButtonVisible) {
                var b = a("<li class='ui-corner-all ui-widget-header'><span class='ui-icon ui-icon-zoomin'></span></li>");
                f.append(b);
                this._addHover(b);
                b.attr("title", this.zoomingTooltipText);
                b.mousedown(function(a) {
                    a.preventDefault();
                    d.options.mouseInteractionMode = "zooming";
                    c.removeClass("ui-state-active");
                    b.addClass("ui-state-active");
                    return false
                })
            }
            switch (d.options.mouseInteractionMode) {
                case "zooming":
                    b && b.addClass("ui-state-active");
                    break;
                default:
                    c && c.addClass("ui-state-active")
            }
        },
        _addHover: function(b) {
            b.hover(function() {
                a(this).addClass("ui-state-hover")
            }, function() {
                a(this).removeClass("ui-state-hover")
            })
        },
        show: function() {
            this.toolbar && this.visibility == "auto" && this.toolbar.stop(true, true).fadeIn("slow")
        },
        hide: function() {
            this.toolbar && this.visibility == "auto" && this.toolbar.stop(true, true).fadeOut("slow")
        }
    };

    function Z(a) {
        this.defaults = {
            lineCap: "butt",
            lineJoin: "miter",
            miterLimit: 10,
            lineWidth: 1,
            size: 8,
            offset: 0,
            linkLineWidth: 1,
            type: "circle"
        };
        this.setOptions(a)
    }
    Z.prototype._setShapeSettings = function(a) {
        a.fillStyle = this.fillStyle;
        a.strokeStyle = this.strokeStyle;
        a.lineWidth = this.lineWidth;
        a.lineCap = this.lineCap;
        a.lineJoin = this.lineJoin;
        a.miterLimit = this.miterLimit;
        a.shadowColor = this.shadowColor;
        a.shadowBlur = this.shadowBlur;
        a.shadowOffsetX = this.shadowOffsetX;
        a.shadowOffsetY = this.shadowOffsetY;
        a.cursor = this.cursor
    };
    Z.prototype._setLineSettings = function(a) {
        a.lineWidth = this.linkLineWidth;
        a.strokeStyle = this.linkLineStrokeStyle
    };
    Z.prototype.setOptions = function(c) {
        var b = a.extend({}, this.defaults, c || {});
        a.extend(this, b)
    };
    Z.prototype.getSize = function() {
        return {
            width: this.size,
            height: this.size
        }
    };
    Z.prototype.getShape = function(c, d, b, j, f) {
        if (this.visible === false) return null;
        var h = 2 * b,
            e = null;
        f = f || this.type;
        switch (f) {
            case "circle":
                e = new Y(c - b, d - b, b);
                break;
            case "rectangle":
                e = new p(c - b, d - b, h, h);
                break;
            case "diamond":
                var a = [];
                a.push(c);
                a.push(d - b);
                a.push(c + b);
                a.push(d);
                a.push(c);
                a.push(d + b);
                a.push(c - b);
                a.push(d);
                e = new S(a);
                e.isBoundsHitTest = true;
                break;
            case "triangle":
                var a = [];
                a.push(c);
                a.push(d - b);
                a.push(c + b);
                a.push(d + b);
                a.push(c - b);
                a.push(d + b);
                e = new S(a);
                e.isBoundsHitTest = true;
                break;
            case "line":
                e = new i(c - b, d, c + b, d);
                break;
            case "plus":
                var a = [];
                a.push(c - b);
                a.push(d);
                a.push(c + b);
                a.push(d);
                a.push(null);
                a.push(null);
                a.push(c);
                a.push(d - b);
                a.push(c);
                a.push(d + b);
                e = new q(a, true);
                break;
            case "cross":
                var a = [];
                a.push(c - b);
                a.push(d - b);
                a.push(c + b);
                a.push(d + b);
                a.push(null);
                a.push(null);
                a.push(c - b);
                a.push(d + b);
                a.push(c + b);
                a.push(d - b);
                e = new q(a, true);
                break;
            case "image":
                var g = j || this.src;
                if (!g) return null;
                e = new sb(c, d, g)
        }
        if (e) {
            e.useHitTestArea = true;
            e.center = {
                x: Math.round(c),
                y: Math.round(d)
            }
        }
        return e
    };
    Z.prototype.isVisible = function() {
        return this.visible !== false && this.type != "none"
    };

    function Tb(a) {
        this.defaults = {
            strokeStyle: "gray",
            lineWidth: 1,
            visible: true
        };
        this.setOptions(a)
    }
    Tb.prototype._setLineSettings = function(a) {
        a.strokeStyle = this.strokeStyle;
        a.lineWidth = this.lineWidth;
        a.strokeDashArray = this.strokeDashArray
    };
    Tb.prototype.setOptions = function(c) {
        var b = a.extend({}, this.defaults, c || {});
        a.extend(this, b)
    };

    function ec(a) {
        this.defaults = {
            strokeStyle: "gray",
            lineWidth: 1,
            zIndex: 0,
            title: {
                margin: 2,
                hAlign: "left",
                vAlign: "top"
            }
        };
        this.setOptions(a)
    }
    ec.prototype = {
        _setLineSettings: function(a) {
            a.strokeStyle = this.strokeStyle;
            a.lineWidth = this.lineWidth;
            a.strokeDashArray = this.strokeDashArray
        },
        setOptions: function(b) {
            if (b != null && typeof b.title == "string") {
                b.title = {
                    text: b.title
                };
                a.extend(b.title, this.defaults.title)
            }
            var c = a.extend(true, {}, this.defaults, b || {});
            a.extend(this, c)
        }
    };

    function dc(a) {
        this.defaults = {
            lineWidth: 0,
            fillStyle: "gray",
            zIndex: 0,
            title: {
                margin: 2,
                hAlign: "left",
                vAlign: "top"
            }
        };
        this.setOptions(a)
    }
    dc.prototype = {
        _setShapeSettings: function(a) {
            a.fillStyle = this.fillStyle;
            a.strokeStyle = this.strokeStyle;
            a.lineWidth = this.lineWidth;
            a.strokeDashArray = this.strokeDashArray
        },
        setOptions: function(b) {
            if (b != null && typeof b.title == "string") {
                b.title = {
                    text: b.title
                };
                a.extend(b.title, this.defaults.title)
            }
            var c = a.extend(true, {}, this.defaults, b || {});
            a.extend(this, c)
        }
    };

    function lb(a) {
        this.defaults = {
            location: "right",
            title: {
                text: undefined,
                margin: 0
            },
            border: {
                padding: 2,
                strokeStyle: "gray",
                cornerRadius: 6
            },
            margin: 4,
            visible: true,
            font: "12px sans-serif",
            textLineWidth: 0,
            inactiveTextLineWidth: 0,
            itemsHorMargin: 4,
            itemsVerMargin: 0,
            allowHideSeries: true,
            inactiveTextFillStyle: "gray",
            inactiveFillStyle: "gray",
            inactiveStrokeStyle: "gray"
        };
        this.setOptions(a)
    }
    lb.prototype._isHorizontal = function() {
        return this.location == "top" || this.location == "bottom" ? true : false
    };
    lb.prototype._init = function() {
        this.items = [];
        if (this.visible == false) return;
        if (this.customItems)
            for (var d = 0; d < this.customItems.length; d++) {
                var b = this.customItems[d];
                if (b != null && typeof b.text == "string") b.text = {
                    text: b.text
                };
                var j = {
                    marker: {
                        type: "rectangle",
                        fillStyle: "#418CF0"
                    }
                };
                b = a.extend(true, {}, j, b || {});
                var k = new Z(b.marker);
                b.marker = k;
                var c = {};
                a.extend(c, b);
                c.text = b.text.text;
                c.font = b.text.font || "12px sans-serif";
                c.textFillStyle = b.text.fillStyle || "black";
                c.textStrokeStyle = b.text.strokeStyle;
                c.textLineWidth = b.text.lineWidth;
                c.context = {
                    chart: this.chart,
                    title: c.text
                };
                c.cursor = c.cursor || this.itemsCursor;
                var f = new tb(c);
                f.chart = this.chart;
                this.items.push(f)
            } else {
                var e = this.itemsCursor;
                if (this.allowHideSeries) e = e || "pointer";
                for (var i = {
                        font: this.font,
                        textStrokeStyle: this.textStrokeStyle,
                        textFillStyle: this.textFillStyle,
                        textLineWidth: this.textLineWidth,
                        cursor: e,
                        inactiveTextFillStyle: this.inactiveTextFillStyle,
                        inactiveTextStrokeStyle: this.inactiveTextStrokeStyle,
                        inactiveTextLineWidth: this.inactiveTextLineWidth,
                        inactiveFillStyle: this.inactiveFillStyle,
                        inactiveStrokeStyle: this.inactiveStrokeStyle
                    }, g = this.chart.series.items, d = 0; d < g.length; d++) {
                    var h = g[d];
                    if (!h.visible) continue;
                    a.merge(this.items, h._getLegendItems(i))
                }
            }
    };
    lb.prototype._measure = function() {
        if (this.visible == false) {
            this.width = 0;
            this.height = 0;
            return
        }
        var e = this.paddings = this.border.getPaddings();
        this.title._measure();
        var j = this.title.width,
            i = this.title.height;
        if (this.title.text) i += e.top;
        for (var l = this._isHorizontal(), g = this.itemsHorMargin, p = this.itemsVerMargin, b = 0, a = 0, d = 0, f = 0; f < this.items.length; f++) {
            var c = this.items[f];
            c._measure();
            if (l) {
                b += c.width;
                if (f > 0) b += g;
                a = Math.max(a, c.height);
                d = Math.max(d, c.width + g)
            } else {
                b = Math.max(b, c.width);
                a += c.height;
                if (f > 0) a += p
            }
        }
        var o = 2 * this.margin + e.left + e.right,
            q = 2 * this.margin + e.top + e.bottom,
            k = this.chart.border.getPaddings(),
            m = this.chart._width - k.left - k.right - o;
        if (l && b > m) {
            var h = Math.floor(m / d) || 1,
                n = Math.ceil(this.items.length / h);
            this.rows = h;
            this.cellHeight = a + g;
            this.cellWidth = d;
            b = h * d;
            a = n * a + (n - 1) * g
        } else this.rows = 0;
        j = Math.max(j, b);
        i += a;
        this.width = this.isCustomWidth || j + o;
        this.height = this.isCustomHeight || i + q
    };
    lb.prototype._arrange = function() {
        if (this.visible == false) return;
        var e = this.x + this.margin,
            b = this.y + this.margin,
            h = 2 * this.margin;
        this.border.x = e;
        this.border.y = b;
        var g = this.width - h;
        this.border.width = g;
        this.border.height = this.height - h;
        var c = this.paddings;
        g -= c.left + c.right;
        e += c.left;
        b += c.top;
        if (this.title.text) {
            this.title.x = e + (g - this.title.width) / 2;
            this.title.y = b;
            b += this.title.height + c.top
        }
        for (var i = this._isHorizontal(), f = this.rows, d = 0; d < this.items.length; d++) {
            var a = this.items[d];
            a.x = e;
            a.y = b;
            if (i)
                if (f) {
                    a.x += d % f * this.cellWidth;
                    a.y += Math.floor(d / f) * this.cellHeight
                } else e += a.width + this.itemsHorMargin;
            else b += a.height + this.itemsVerMargin;
            a._arrange()
        }
    };
    lb.prototype._render = function(b) {
        if (this.visible == false) return [];
        this.border._render(b);
        this.title._render(b);
        for (var d = [], c = 0; c < this.items.length; c++) {
            var f = this.items[c],
                e = f._render(b);
            a.merge(d, e)
        }
        return d
    };
    lb.prototype._handleEvent = function(e, d) {
        if (!this.allowHideSeries) return;
        var a = d.context,
            c = a.series,
            b = a.chart;
        if (!c) return;
        switch (e) {
            case "MouseDown":
            case "TouchEnd":
                c._hideFromLegend(a);
                var f = b.mouseInput;
                b.update();
                b._processMouseEvents()
        }
    };
    lb.prototype.setOptions = function(b) {
        if (b != null && typeof b.title == "string") {
            b.title = {
                text: b.title
            };
            a.extend(b.title, this.defaults.title)
        }
        var c = a.extend(true, {}, this.defaults, b || {});
        a.extend(this, c);
        this.margin = c.margin;
        if (b) {
            this.isCustomWidth = b.width;
            this.isCustomHeight = b.height
        }
        this.border = new wb(c.border);
        this.border.fillStyle = this.background || this.border.fillStyle;
        this.title = new ab(c.title);
        this.title.chart = this.chart
    };

    function tb(a) {
        this.defaults = {
            font: "12px sans-serif",
            textFillStyle: "black",
            textLineWidth: 0
        };
        this.lblMargin = 4;
        this.setOptions(a)
    }
    tb.prototype._measure = function() {
        var a;
        if (this.text) a = this.textBlock.measure(this.chart.ctx);
        else a = {
            width: 0,
            height: 0
        };
        this.width = a.width + this.marker.size + this.lblMargin;
        this.height = a.height
    };
    tb.prototype._arrange = function() {
        var a = this.marker.size / 2,
            b = this.x + a,
            c = this.y + a + (this.height - this.marker.size) / 2;
        this.markerShape = this.marker.getShape(b, c, a);
        if (this.markerShape) {
            this.marker._setShapeSettings(this.markerShape);
            this.markerShape.context = this.context;
            this.markerShape.cursor = this.cursor;
            this.markerShape.isLegendItem = true
        }
        this.textBlock.x = this.x + this.marker.size + this.lblMargin;
        this.textBlock.y = this.y
    };
    tb.prototype._render = function(c) {
        var b = [];
        this.markerShape && b.push(this.markerShape);
        this.textBlock && b.push(this.textBlock);
        a.merge(c, b);
        return b
    };
    tb.prototype.setOptions = function(c) {
        var b = a.extend(false, {}, this.defaults, c || {});
        a.extend(this, b);
        this.textBlock = new l(this.text);
        this.textBlock.textBaseline = "top";
        this.textBlock.font = this.font;
        this.textBlock.fillStyle = this.textFillStyle;
        this.textBlock.strokeStyle = this.textStrokeStyle;
        this.textBlock.lineWidth = this.textLineWidth;
        this.textBlock.cursor = this.cursor;
        this.textBlock.context = this.context;
        this.textBlock.isLegendItem = true
    };

    function B(a) {
        this.chart = a;
        this.border = new wb;
        this.border.cornerRadius = 0;
        this.border.lineWidth = 0;
        this.isMouseOver = false
    }
    B.prototype._arrange = function() {
        var a = this.x,
            b = this.y;
        this.border.x = a;
        this.border.y = b;
        this.border.width = this.width;
        this.border.height = this.height;
        this._arrangeRenderer(this.chart.areaRenderer);
        this._arrangeRenderer(this.chart.hlRenderer)
    };
    B.prototype._arrangeRenderer = function(a) {
        if (!d.use_excanvas) return;
        var f = this.x,
            g = this.y,
            b = a.canvas,
            h = a.div,
            e = Math.max(this.width, 0),
            c = Math.max(this.height, 0);
        h.css({
            left: f,
            top: g,
            width: e,
            height: c
        });
        b.width = e;
        b.height = c;
        a.offsetX = -f;
        a.offsetY = -g
    };
    B.prototype._renderZIndex = function(b, c) {
        var d = this._renderPlots(b, c);
        a.merge(b, d)
    };
    B.prototype._render = function(b) {
        this.border.fillStyle = this.fillStyle;
        this.border._render(b);
        var c = this._renderPlots(b, 0);
        this._renderGridLines(b);
        a.merge(b, c);
        this._renderZIndex(b, 1)
    };
    B.prototype._renderSeries = function(g) {
        for (var e = this.chart.series.items, d = [], b = 0; b < e.length; b++) {
            var f = e[b];
            if (!f.notInGridArea) {
                var c = f._render(g);
                c && a.merge(d, c)
            }
        }
        return d
    };
    B.prototype._renderStripes = function(e) {
        for (var c = this.chart.axes.items, b = 0; b < c.length; b++) {
            var f = c[b],
                d = this._getStripes(f);
            a.merge(e, d)
        }
    };
    B.prototype._renderPlots = function(f, g) {
        for (var d = [], h = this.chart.axes.items, e = 0; e < h.length; e++) {
            var i = h[e],
                b = this._getPlotBands(i, g);
            if (b) {
                a.merge(f, b.shapes);
                a.merge(d, b.texts)
            }
            var c = this._getPlotLines(i, g);
            if (c) {
                a.merge(f, c.shapes);
                a.merge(d, c.texts)
            }
        }
        return d
    };
    B.prototype._renderGridLines = function(e) {
        for (var f = this.chart.axes.items, d = 0; d < f.length; d++) {
            var b = f[d],
                c = b.majorGridLines;
            if (c == null && b.getOrientation() == "y") {
                c = new Tb;
                if (b.minorGridLines != null) b.minorGridLines.major = c
            }
            var h = this._getGridLines(b, b.minorGridLines, false);
            a.merge(e, h);
            var g = this._getGridLines(b, c, true);
            a.merge(e, g)
        }
    };
    B.prototype._getStripes = function(b) {
        var d = b.stripes;
        if (d == null || a.isArray(d) != true) return [];
        var c = this,
            e = [];
        a.each(d, function() {
            var a = new Stripe(this),
                j;
            if (a.interval) j = a.interval;
            else j = 2 * b.actualInterval;
            var i;
            if (a.width) i = a.width;
            else i = b.actualInterval;
            for (var h = a.lineWidth, n = h / 2, m = b._getIntervals(j, a, true), l = 0; l < m.length; l++) {
                var k = m[l];
                if (k >= b.actualVisibleMaximum) continue;
                var d = b.getPosition(k),
                    o = b._getNextPosition(k, i),
                    f = b.getPosition(o),
                    g;
                if (b.isAxisVertical) g = new p(c.x + n, Math.min(d, f), c.width - h, Math.abs(f - d));
                else g = new p(Math.min(d, f), c.y + n, Math.abs(f - d), c.height - h);
                a._setSettings(g);
                e.push(g)
            }
        });
        return e
    };
    B.prototype._getGridLines = function(a, b, k) {
        if (a.location == "radial" || b == null || b.visible != true) return [];
        for (var h = [], n = a._getMarkInterval(b, k), j = a._getIntervals(n, b, k), c, d, e, f, g = 0; g < j.length; g++) {
            var m = a.getPosition(j[g]);
            if (a.isAxisVertical) {
                d = f = m;
                c = this.x;
                e = c + this.width
            } else {
                c = e = m;
                d = this.y;
                f = d + this.height
            }
            var l = new i(c, d, e, f);
            b._setLineSettings(l);
            h.push(l)
        }
        return h
    };
    B.prototype._getPlotLines = function(h, o) {
        var g = h.plotLines;
        if (g == null || a.isArray(g) != true) return null;
        for (var e = this, k = [], l = [], j = 0; j < g.length; j++) {
            var d = new ec;
            if (!h.isAxisVertical) d.defaults.title.hAlign = "right";
            d.setOptions(g[j]);
            if (b.isNull(d.value)) continue;
            if (b.fitInRange(d.zIndex, 0, 2) != o) continue;
            var f = d.lineWidth / 2,
                n = h.getPosition(d.value),
                c = new ab(d.title);
            c.chart = this.chart;
            c._measure();
            if (h.isAxisVertical) {
                y1 = y2 = n;
                x1 = this.x;
                x2 = x1 + this.width;
                c.x = x1;
                c.y = y1;
                switch (c.hAlign) {
                    case "center":
                        c.x = e.x + (e.width - c.width) / 2;
                        break;
                    case "right":
                        c.x = e.x + e.width - c.width
                }
                switch (c.vAlign) {
                    case "bottom":
                        c.y += f;
                        break;
                    case "center":
                        c.y -= c.height / 2;
                        break;
                    case "top":
                        c.y -= c.height + f
                }
            } else {
                x1 = x2 = n;
                y1 = this.y;
                y2 = y1 + this.height;
                c.x = x1;
                c.y = y1;
                switch (c.hAlign) {
                    case "right":
                        c.x += f;
                        break;
                    case "center":
                        c.x -= c.height / 2;
                        break;
                    case "left":
                        c.x -= c.height + f
                }
                switch (c.vAlign) {
                    case "center":
                        c.y += (e.height - c.width) / 2;
                        break;
                    case "bottom":
                        c.y += e.height - c.width
                }
                c.rotX = c.x + c.height - c.margin;
                c.rotY = c.y + c.margin;
                c.rotationAngle = b.radians(90)
            }
            var m = new i(x1, y1, x2, y2);
            d._setLineSettings(m);
            k.push(m);
            c._render(l)
        }
        return {
            shapes: k,
            texts: l
        }
    };
    B.prototype._getPlotBands = function(g, u) {
        var j = g.plotBands;
        if (j == null || a.isArray(j) != true) return null;
        for (var k = this, q = [], r = [], o = 0; o < j.length; o++) {
            var d = new dc;
            if (!g.isAxisVertical) d.defaults.title.hAlign = "right";
            d.setOptions(j[o]);
            if (b.isNull(d.from) || b.isNull(d.to)) continue;
            if (b.fitInRange(d.zIndex, 0, 2) != u) continue;
            var n = d.lineWidth,
                t = n / 2,
                l = g.getPosition(d.from),
                m = g.getPosition(d.to),
                h, i, f, e;
            if (g.isAxisVertical) {
                h = k.x + t;
                i = Math.min(l, m);
                f = k.width - n;
                e = Math.abs(m - l)
            } else {
                h = Math.min(l, m);
                i = k.y + t;
                f = Math.abs(m - l);
                e = k.height - n
            }
            var s = new p(h, i, f, e);
            d._setShapeSettings(s);
            q.push(s);
            var c = new ab(d.title);
            c.chart = this.chart;
            c._measure();
            if (g.isAxisVertical) {
                c.x = h;
                c.y = i;
                switch (c.hAlign) {
                    case "center":
                        c.x += (f - c.width) / 2;
                        break;
                    case "right":
                        c.x += f - c.width
                }
                switch (c.vAlign) {
                    case "center":
                        c.y += (e - c.height) / 2;
                        break;
                    case "bottom":
                        c.y += e - c.height
                }
            } else {
                c.x = h;
                c.y = i;
                switch (c.hAlign) {
                    case "center":
                        c.x += (f - c.height) / 2;
                        break;
                    case "right":
                        c.x += f - c.height
                }
                switch (c.vAlign) {
                    case "center":
                        c.y += (e - c.width) / 2;
                        break;
                    case "bottom":
                        c.y += e - c.width
                }
                c.rotX = c.x + c.height - c.margin;
                c.rotY = c.y + c.margin;
                c.rotationAngle = b.radians(90)
            }
            c._render(r)
        }
        return {
            shapes: q,
            texts: r
        }
    };
    B.prototype._contains = function(a, b) {
        return a >= this.x && a <= this.x + this.width && b >= this.y && b <= this.y + this.height
    };
    B.prototype.getRight = function() {
        return this.x + this.width
    };
    B.prototype.fitHor = function(a) {
        return b.fitInRange(a, this.x, this.getRight())
    };
    B.prototype.fitVer = function(a) {
        return b.fitInRange(a, this.y, this.y + this.height)
    };

    function x(b, a) {
        this.chart = b;
        a && this.setOptions(a)
    }
    x.prototype.setOptions = function(e) {
        this.items = [];
        if (a.isArray(e) == false) return;
        for (var f = 0; f < e.length; f++) {
            var c = e[f];
            if (c == null) continue;
            var b, d = c.type || "column";
            d = d.toLowerCase();
            switch (d) {
                case "area":
                    b = new eb(c);
                    break;
                case "splinearea":
                    b = new yb(c);
                    break;
                case "bar":
                    b = new s(c);
                    break;
                case "bubble":
                    b = new cb(c);
                    break;
                case "line":
                    b = new A(c);
                    break;
                case "stepline":
                    b = new Pb(c);
                    break;
                case "steparea":
                    b = new Ob(c);
                    break;
                case "spline":
                    b = new Rb(c);
                    break;
                case "pie":
                    b = new h(c);
                    break;
                case "donut":
                case "doughnut":
                    b = new Wb(c);
                    break;
                case "scatter":
                    b = new Qb(c);
                    break;
                case "stackedcolumn":
                    b = new K(c);
                    break;
                case "stackedbar":
                    b = new H(c);
                    break;
                case "stacked100column":
                    b = new fb(c);
                    break;
                case "stacked100bar":
                    b = new rb(c);
                    break;
                case "rangecolumn":
                    b = new G(c);
                    break;
                case "rangebar":
                    b = new t(c);
                    break;
                case "gantt":
                    b = new V(c);
                    break;
                case "stock":
                    b = new F(c);
                    break;
                case "candlestick":
                    b = new xb(c);
                    break;
                case "radar":
                case "radarline":
                    b = new I(c);
                    break;
                case "radararea":
                    b = new Nb(c);
                    break;
                case "radarspline":
                    b = new Ib(c);
                    break;
                case "radarsplinearea":
                    b = new Eb(c);
                    break;
                case "polar":
                case "polarline":
                    b = new D(c);
                    break;
                case "polararea":
                    b = new Mb(c);
                    break;
                case "polarspline":
                    b = new Hb(c);
                    break;
                case "polarsplinearea":
                    b = new Db(c);
                    break;
                case "polarscatter":
                    b = new Gb(c);
                    break;
                case "trendline":
                    b = new T(c);
                    break;
                case "verticalline":
                    b = new N(c);
                    break;
                case "verticalspline":
                    b = new Lb(c);
                    break;
                case "verticalarea":
                    b = new ib(c);
                    break;
                case "verticalsplinearea":
                    b = new Fb(c);
                    break;
                case "range":
                    b = new y(c);
                    break;
                case "splinerange":
                    b = new Jb(c);
                    break;
                case "stackedline":
                    b = new v(c);
                    break;
                case "stackedspline":
                    b = new gb(c);
                    break;
                case "stacked100line":
                    b = new qb(c);
                    break;
                case "stacked100spline":
                    b = new ob(c);
                    break;
                case "stackedarea":
                    b = new R(c);
                    break;
                case "stackedsplinearea":
                    b = new nb(c);
                    break;
                case "stacked100area":
                    b = new pb(c);
                    break;
                case "stacked100splinearea":
                    b = new mb(c);
                    break;
                case "column":
                default:
                    b = new J(c)
            }
            b.type = d;
            b.chart = this.chart;
            this.items.push(b)
        }
        this._processData()
    };
    x.prototype._processData = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c.visible && c._processData()
        }
    };
    x.prototype._initData = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c.isInScene() && c._initData()
        }
    };
    x.prototype._initVisibleData = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c.isInScene() && c._initVisibleData()
        }
    };
    x.prototype._initCategories = function() {
        for (var d = [], e = 0, a = 0, f = this.items, c = 0; c < f.length; c++) {
            var b = f[c];
            if (!b.isInScene() || !b.categories) continue;
            for (a = e; a < b.categories.length; a++) {
                var g = b.categories[a];
                d.push(g)
            }
            e = a
        }
        this.categories = d
    };
    x.prototype._initRanges = function() {
        for (var e = j, d = m, c = j, b = m, g = this.items, f = 0; f < g.length; f++) {
            var a = g[f];
            if (!a.isInScene()) continue;
            if (e > a.min) e = a.min;
            if (d < a.max) d = a.max;
            if (c > a.minX) c = a.minX;
            if (b < a.maxX) b = a.maxX
        }
        this.min = e;
        this.max = d;
        this.minX = c;
        this.maxX = b
    };
    x.prototype._findClusters = function(g, f) {
        for (var d = -1, a = 0, e = this.items, c = 0; c < e.length; c++) {
            var b = e[c];
            if (!b.isInScene()) continue;
            if (b == g) d = a;
            if (b.type == f) a++
        }
        return {
            index: d,
            count: a
        }
    };
    x.prototype._findStackedClusters = function(h, e) {
        for (var f = h.stackedGroupName, g = this._getStackedGroupsFromType(e), l = a.inArray(f, g), k = g.length, i = -1, c = 0, j = this.items, d = 0; d < j.length; d++) {
            var b = j[d];
            if (!b.isInScene()) continue;
            if (b == h) i = c;
            if (b.type == e && b.stackedGroupName == f) c++
        }
        return {
            index: i,
            count: c,
            groupIndex: l,
            groupCount: k
        }
    };
    x.prototype._getSeriesFromType = function(e) {
        for (var c = [], d = this.items, b = 0; b < d.length; b++) {
            var a = d[b];
            if (!a.isInScene()) continue;
            a.type == e && c.push(a)
        }
        return c
    };
    x.prototype._getStackedSeriesFromType = function(e, f) {
        for (var c = [], d = this.items, b = 0; b < d.length; b++) {
            var a = d[b];
            if (!a.isInScene()) continue;
            a.type == e && a.stackedGroupName == f && c.push(a)
        }
        return c
    };
    x.prototype._getStackedGroupsFromType = function(c) {
        var b = [];
        a.each(this.items, function() {
            if (this.isInScene() && this.type == c) {
                var d = a.inArray(this.stackedGroupName, b);
                d == -1 && b.push(this.stackedGroupName)
            }
        });
        return b
    };
    x.prototype._initColors = function() {
        for (var b = this.chart.paletteColors, c = this.items, a = 0; a < c.length; a++) {
            var d = c[a];
            d._initColors(b.getColor(a), b)
        }
    };
    x.prototype._getPixelMargins = function(g) {
        for (var b = 0, a = 0, e = this.items, c = 0; c < e.length; c++) {
            var f = e[c];
            if (!f.isInScene()) continue;
            var d = f._getPixelMargins(g);
            b = Math.max(b, d.left);
            a = Math.max(a, d.right)
        }
        return {
            left: b,
            right: a
        }
    };
    x.prototype._isAnchoredToOrigin = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            if (!c.isInScene()) continue;
            if (c._isAnchoredToOrigin()) return true
        }
        return false
    };
    x.prototype._render = function(d) {
        for (var c = this.items, b = 0; b < c.length; b++) {
            var a = c[b];
            if (!a.visible) continue;
            a.notInGridArea && a._render(d)
        }
    };
    x.prototype.getSeries = function(a) {
        return this.items[a]
    };
    x.prototype.hasData = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            if (!c.visible) continue;
            if (c.hasRealData) return true
        }
        return false
    };

    function f(a) {
        this.setOptions(a)
    }
    f.prototype = {
        _getMarker: function(j, k, c, l, h, i, g) {
            if (c == null) c = this.markers.size / 2;
            var e = this.hasNullValues && a.inArray(i, this.nullIndexes) != -1,
                d = this.emptyPointStyleObj.marker,
                f;
            if (e) {
                f = d.visible ? d.type : "none";
                c = this.emptyPointStyleObj.marker.size / 2
            }
            var b = this.markers.getShape(j, k, c, h, f);
            if (b == null) return null;
            b.context = g;
            this.markers._setShapeSettings(b);
            this._setMarkerSettings(b);
            if (e) {
                a.extend(b, this.emptyPointStyleObj.marker);
                g.isEmptyData = true
            }
            return b
        },
        _addMarker: function(d, e, l, c, f, k, g) {
            var a = this._correctMarkerPosition(d, e, c),
                h = this._getMarker(a.x, a.y, l, c, f, k, g),
                j = this.markers.offset,
                b = null;
            if (j) {
                b = new i(d, e, a.x, a.y);
                this.markers._setLineSettings(b);
                this._setMarkerLinkLineSettings(b)
            }
            return {
                marker: h,
                line: b,
                offset: a.offset
            }
        },
        _correctMarkerPosition: function(e, b, d) {
            var a = this.markers.offset;
            if (a) {
                var c = d >= this.realYAxis.getCrossing();
                b = c ? b - a : b + a
            }
            return {
                x: e,
                y: b,
                offset: a
            }
        },
        _addMarkerAndLabel: function(i, p, l, m, a, k, e, f, o, c) {
            if (b.isNull(e) && !this.realXAxis.isValueVisible(a + .5)) return;
            if (!b.isNull(e) && !this.realXAxis.isValueVisible(e)) return;
            var j = 0,
                q = !b.isNull(e) ? e : a;
            if (!c) c = {
                chart: this.chart,
                series: this,
                dataItem: this.arrData[a],
                index: a,
                x: this.realXAxis._getValue(q),
                y: f
            };
            if (this.markers && this.markers.isVisible() && this.realYAxis.isValueVisible(f)) {
                var n = c.dataItem[2],
                    d = this._addMarker(l, m, null, f, n, a, c);
                if (d.marker) {
                    d.line && i.push(d.line);
                    i.push(d.marker);
                    j = this.markers.offset;
                    this._addShapeAnimation(d.marker, a, k)
                }
            }
            if (this.labels && this.labels.visible !== false) {
                var h = this._getLabelValue(f, a);
                if (this.labels.valueType == "percentage") h = c.percentage;
                var g = this._getDataPointLabel(f, l, m, o + j, h);
                g.context = c;
                this.chart.elem.trigger("dataPointLabelCreating", g);
                p.push(g);
                this._addShapeAnimation(g, a, k)
            }
        },
        _getAnimation: function() {
            return this.animation || this.chart.options.animation
        },
        _addShapeAnimation: function(e, g, f) {
            var b = this._getAnimation();
            if (!b || b.enabled === false) return;
            var a = new zb(b, e, "visible", false, true),
                c = a.duration / f,
                d = a.delayTime + g * c;
            a.delayTime = d;
            a.duration = c;
            this.chart.storyboard.addAnimation(a)
        },
        _addLengthAnimation: function(b) {
            var a = this._getAnimation();
            if (!a || a.enabled === false) return;
            var c = new ub(a, b, "length", 0, b.getLength());
            this.chart.storyboard.addAnimation(c)
        },
        _setMarkerSettings: function(a) {
            a.fillStyle = a.fillStyle || this.fillStyle;
            a.strokeStyle = a.strokeStyle || this.strokeStyle;
            var c = this.options;
            if (!c.markers || b.isNull(c.markers.lineWidth))
                if (!b.isNull(c.lineWidth)) a.lineWidth = c.lineWidth;
            a.cursor = a.cursor || this.cursor;
            var d = this.chart.options.shadows;
            if (b.isNull(a.shadowColor)) a.shadowColor = !b.isNull(this.shadowColor) ? this.shadowColor : d.shadowColor;
            if (b.isNull(a.shadowBlur)) a.shadowBlur = !b.isNull(this.shadowBlur) ? this.shadowBlur : d.shadowBlur;
            if (b.isNull(a.shadowOffsetX)) a.shadowOffsetX = !b.isNull(this.shadowOffsetX) ? this.shadowOffsetX : d.shadowOffsetX;
            if (b.isNull(a.shadowOffsetY)) a.shadowOffsetY = !b.isNull(this.shadowOffsetY) ? this.shadowOffsetY : d.shadowOffsetY
        },
        _setMarkerLinkLineSettings: function(a) {
            a.strokeStyle = a.strokeStyle || this.markers.strokeStyle || this.fillStyle
        },
        _setShapeSettings: function(c, e) {
            if (this.fillStyles && !b.isNull(e)) c.fillStyle = this.fillStyles[e % this.fillStyles.length];
            else c.fillStyle = this.fillStyle;
            c.strokeStyle = this.strokeStyle;
            c.lineWidth = this.lineWidth;
            c.lineCap = this.lineCap;
            c.lineJoin = this.lineJoin;
            c.miterLimit = this.miterLimit;
            c.strokeDashArray = this.strokeDashArray;
            d.setShadows(c, this, this.chart);
            c.cursor = this.cursor;
            c.nullHandling = this.nullHandling;
            if (c.context && this.hasNullValues && a.inArray(e, this.nullIndexes) != -1) {
                a.extend(c, this.emptyPointStyleObj);
                c.context.isEmptyData = true
            }
        },
        _processXAxisType: function() {
            var c = this.arrData,
                b = "none";
            this.isSingleArrData = false;
            if (a.isArray(c) == false) {
                this.xAxisType = b;
                return
            }
            for (var e = 0; e < c.length; e++) {
                var d = c[e];
                if (d == null) continue;
                if (a.isArray(d) == false) {
                    b = "CategoryAxis";
                    this.isSingleArrData = true;
                    break
                }
                var f = d[0];
                if (f == null) continue;
                var g = a.type(f);
                switch (g) {
                    case "number":
                        b = "LinearAxis";
                        break;
                    case "date":
                        b = "DateTimeAxis";
                        break;
                    case "string":
                        b = "CategoryAxis";
                        break;
                    default:
                        b = "none"
                }
                break
            }
            this.xAxisType = b
        },
        _resolveAxisType: function(b) {
            var a = b.location;
            if (!a) return;
            if (this.isVertical) {
                if (a == "bottom" || a == "top") return
            } else if (a == "left" || a == "right") return;
            switch (this.xAxisType) {
                case "LinearAxis":
                    b.type = "linear";
                    break;
                case "DateTimeAxis":
                    b.type = "dateTime";
                    break;
                case "CategoryAxis":
                    b.type = "category"
            }
        },
        _processData: function() {
            this.arrData = null;
            if (this.data)
                if (!this.xValuesType && !this.yValuesType) this.arrData = b.cloneArray(this.data);
                else {
                    this.arrData = [];
                    for (var d = 0; d < this.data.length; d++) {
                        var a = this.data[d].slice(0);
                        a[0] = b.processDataValue(a[0], this.xValuesType);
                        a[1] = b.processDataValue(a[1], this.yValuesType);
                        this.arrData.push(a)
                    }
                }
            else {
                var c = this.chart.arrDataSource;
                if (c) {
                    var f = this.xValuesField,
                        g = this.yValuesField,
                        h = b.processDataField(c, f),
                        e = b.processDataField(c, g);
                    if (e) this.arrData = b.mergeArraysXY(h, e)
                }
            }
            this._processXAxisType();
            this._processNullValues();
            this._processErrorBars()
        },
        _processErrorBars: function() {
            var a = this.errorBars;
            if (a && this.hasErrorBars && this.arrData) this.errorBarsObj = new jb(this, a)
        },
        _processNullValues: function() {
            this.hasRealData = this.hasData();
            if (!this.hasRealData) return;
            this.hasNullValues = false;
            var c = this.arrData;
            if (!c || this.nullHandling != "emptyPoint") return;
            this.hasNullValues = true;
            if (this.nullValuesIndexes) {
                this.nullIndexes = this.nullValuesIndexes;
                return
            }
            for (var i = this.nullIndexes = [], f, e, g, h, d, a = 0; a < c.length; a++) {
                f = c[a];
                e = b.isNull(f);
                if (this.isSingleArrData) {
                    if (e) {
                        d = b.calcNullValue(c, a, "vl");
                        if (a == 0 && b.isNull(d)) {
                            this.hasRealData = false;
                            return
                        }
                        i.push(a);
                        c[a] = d
                    }
                } else {
                    g = e || b.isNull(f[0]);
                    h = e || b.isNull(f[1]);
                    if (!g && !h) continue;
                    if (e) c[a] = [];
                    if (g) {
                        d = b.calcNullValue(c, a, "x", this.xAxisType);
                        if (a == 0 && b.isNull(d)) {
                            this.hasRealData = false;
                            return
                        }
                        c[a][0] = d
                    }
                    if (h) {
                        d = b.calcNullValue(c, a, "y");
                        if (a == 0 && b.isNull(d)) {
                            this.hasRealData = false;
                            return
                        }
                        c[a][1] = d
                    }
                    i.push(a)
                }
            }
        },
        _processDataXYZ: function() {
            this.arrData = null;
            if (this.data)
                if (!this.xValuesType && !this.fromValuesType && !this.toValuesType) this.arrData = b.cloneArray(this.data);
                else {
                    this.arrData = [];
                    for (var d = 0; d < this.data.length; d++) {
                        var a = this.data[d].slice(0);
                        a[0] = b.processDataValue(a[0], this.xValuesType);
                        a[1] = b.processDataValue(a[1], this.fromValuesType);
                        a[2] = b.processDataValue(a[2], this.toValuesType);
                        a[3] = b.processDataValue(a[3], this.labelValuesType);
                        this.arrData.push(a)
                    }
                }
            else {
                var c = this.chart.arrDataSource;
                if (c) {
                    var k = this.xValuesField,
                        i = this.fromValuesField,
                        j = this.toValuesField,
                        h = this.labelValuesField,
                        g = b.processDataField(c, k),
                        e = b.processDataField(c, i),
                        f = b.processDataField(c, j),
                        l = b.processDataField(c, h);
                    if (g && e && f) this.arrData = b.mergeArrays([g, e, f, l])
                }
            }
            this._processXAxisType();
            this.hasRealData = this.hasData()
        },
        _initXYData: function() {
            for (var l = this.arrData, d = j, c = m, h = j, g = m, n = l.length, k = 0; k < n; k++) {
                var i = l[k];
                if (i == null) continue;
                var e = i[0];
                if (h > e) h = e;
                if (g < e) g = e;
                var a = i[1];
                if (!b.isNull(a)) {
                    if (d > a) d = a;
                    if (c < a) c = a
                }
            }
            var f = this.errorBarsObj;
            if (f) {
                d -= f.getLowerError();
                c += f.getUpperError()
            }
            this.min = d;
            this.max = c;
            this.minX = h;
            this.maxX = g
        },
        _initCatValueData: function() {
            for (var k = this.arrData, h = j, g = m, f = [], l = k.length, e = 0; e < l; e++) {
                var d = k[e];
                if (d == null) {
                    f.push((e + 1).toString());
                    continue
                }
                var c = d;
                if (a.isArray(d) == false) f.push((e + 1).toString());
                else {
                    f.push(d[0]);
                    c = d[1]
                }
                if (!b.isNull(c)) {
                    if (h > c) h = c;
                    if (g < c) g = c
                }
            }
            var i = this.errorBarsObj;
            if (i) {
                h -= i.getLowerError();
                g += i.getUpperError()
            }
            this.min = h;
            this.max = g;
            this.categories = f
        },
        _initDateValueData: function() {
            for (var l = this.arrData, e = j, d = m, i = j, h = m, n = l.length, f, a, c, k = 0; k < n; k++) {
                f = l[k];
                if (b.isNull(f)) continue;
                a = f[0];
                if (b.isNull(a)) continue;
                a = a.getTime();
                if (i > a) i = a;
                if (h < a) h = a;
                c = f[1];
                if (!b.isNull(c)) {
                    if (e > c) e = c;
                    if (d < c) d = c
                }
            }
            var g = this.errorBarsObj;
            if (g) {
                e -= g.getLowerError();
                d += g.getUpperError()
            }
            this.min = e;
            this.max = d;
            this.minX = i;
            this.maxX = h
        },
        _initXYDataRange: function(n, p) {
            for (var l = this.arrData, h = j, g = m, f = j, e = m, o = l.length, i = 0; i < o; i++) {
                var d = l[i];
                if (d == null || a.isArray(d) == false) continue;
                var b = d[0];
                if (f > b) f = b;
                if (e < b) e = b;
                for (var k = n; k < p; k++) {
                    var c = d[k];
                    if (h > c) h = c;
                    if (g < c) g = c
                }
            }
            this.min = h;
            this.max = g;
            this.minX = f;
            this.maxX = e
        },
        _initCatValueDataRange: function(o, q, n) {
            for (var k = this.arrData, g = j, f = m, d = [], p = k.length, e = 0; e < p; e++) {
                var h = k[e];
                if (h == null) {
                    d.push((e + 1).toString());
                    continue
                }
                var l = h[0];
                (n || a.inArray(l, d) == -1) && d.push(l);
                for (var i = o; i < q; i++) {
                    var c = h[i];
                    if (b.isNull(c)) continue;
                    if (g > c) g = c;
                    if (f < c) f = c
                }
            }
            this.min = g;
            this.max = f;
            this.categories = d
        },
        _initDateValueDataRange: function(o, q) {
            for (var n = this.arrData, i = j, h = m, g = j, f = m, p = n.length, k = 0; k < p; k++) {
                var e = n[k];
                if (e == null || a.isArray(e) == false) continue;
                var d = e[0].getTime();
                if (g > d) g = d;
                if (f < d) f = d;
                for (var l = o; l < q; l++) {
                    var c = e[l];
                    if (b.isNull(c)) continue;
                    if (i > c) i = c;
                    if (h < c) h = c
                }
            }
            this.min = i;
            this.max = h;
            this.minX = g;
            this.maxX = f
        },
        _initData: function() {
            switch (this.xAxisType) {
                case "LinearAxis":
                    this._initXYData();
                    return;
                case "DateTimeAxis":
                    this._initDateValueData();
                    return;
                case "CategoryAxis":
                    this._initCatValueData();
                    return
            }
        },
        _initVisibleData: function() {
            switch (this.xAxisType) {
                case "LinearAxis":
                case "DateTimeAxis":
                    this._initVisibleXYData();
                    return;
                case "CategoryAxis":
                    this._initVisibleCatValueData();
                    return
            }
        },
        _initVisibleXYData: function() {
            if (this.realYAxis.zoomEnabled) return;
            var c = this.realXAxis,
                o = c.visibleMinimum || c.minimum,
                n = c.visibleMaximum || c.maximum,
                k = !b.isNull(o),
                i = !b.isNull(n);
            if (!k && !i) return;
            for (var l = this.arrData, e = j, d = m, q = l.length, h = 0; h < q; h++) {
                var g = l[h];
                if (g == null) continue;
                var a = g[1];
                if (b.isNull(a)) continue;
                var p = g[0];
                if (k && p < o || i && p > n) continue;
                if (e > a) e = a;
                if (d < a) d = a
            }
            var f = this.errorBarsObj;
            if (f) {
                e -= f.getLowerError();
                d += f.getUpperError()
            }
            this.min = e;
            this.max = d
        },
        _initVisibleCatValueData: function() {
            if (this.realYAxis.zoomEnabled) return;
            var e = this.realXAxis,
                p = e.visibleMinimum || e.minimum,
                o = e.visibleMaximum || e.maximum,
                l = !b.isNull(p),
                k = !b.isNull(o);
            if (!l && !k) return;
            for (var n = this.arrData, g = j, f = m, q = n.length, d = 0; d < q; d++) {
                var h = n[d];
                if (h == null) continue;
                var c = h;
                if (a.isArray(h)) c = h[1];
                if (b.isNull(c)) continue;
                if (l && d + 1 < p || k && d > o) continue;
                if (g > c) g = c;
                if (f < c) f = c
            }
            var i = this.errorBarsObj;
            if (i) {
                g -= i.getLowerError();
                f += i.getUpperError()
            }
            this.min = g;
            this.max = f
        },
        _initVisibleCatValueDataRange: function(p, r) {
            if (this.realYAxis.zoomEnabled) return;
            var d = this.realXAxis,
                n = d.visibleMinimum || d.minimum,
                l = d.visibleMaximum || d.maximum,
                i = !b.isNull(n),
                h = !b.isNull(l);
            if (!i && !h) return;
            for (var k = this.arrData, f = j, e = m, q = k.length, c = 0; c < q; c++) {
                var o = k[c];
                if (o == null) continue;
                if (i && c + 1 < n || h && c > l) continue;
                for (var g = p; g < r; g++) {
                    var a = o[g];
                    if (b.isNull(a)) continue;
                    if (f > a) f = a;
                    if (e < a) e = a
                }
            }
            this.min = f;
            this.max = e
        },
        _initVisibleXYDataRange: function(q, s) {
            if (this.realYAxis.zoomEnabled) return;
            var c = this.realXAxis,
                o = c.visibleMinimum || c.minimum,
                n = c.visibleMaximum || c.maximum,
                k = !b.isNull(o),
                i = !b.isNull(n);
            if (!k && !i) return;
            for (var l = this.arrData, e = j, d = m, r = l.length, g = 0; g < r; g++) {
                var f = l[g];
                if (f == null) continue;
                var p = f[0];
                if (k && p < o || i && p > n) continue;
                for (var h = q; h < s; h++) {
                    var a = f[h];
                    if (b.isNull(a)) continue;
                    if (e > a) e = a;
                    if (d < a) d = a
                }
            }
            this.min = e;
            this.max = d
        },
        _initStackedData: function(b) {
            var c = this.arrData;
            if (a.isArray(c) == false) return;
            var f = this.chart.series._findStackedClusters(this, b),
                e = this.chart.series._getStackedSeriesFromType(b, this.stackedGroupName),
                d = this._calcStackedData(c, f, e);
            a.extend(this, d)
        },
        _initVisibleStackedData: function(b) {
            var c = this.arrData;
            if (a.isArray(c) == false) return;
            var f = this.chart.series._findStackedClusters(this, b),
                e = this.chart.series._getStackedSeriesFromType(b, this.stackedGroupName),
                d = this._calcVisibleStackedData(c, f, e);
            a.extend(this, d)
        },
        _calcStackedData: function(t, v, u) {
            for (var o = j, n = m, l = j, k = m, h = [], w = t.length, r = {}, g = 0; g < w; g++) {
                var d = t[g];
                if (d == null) {
                    h.push((g + 1).toString());
                    continue
                }
                if (this.xAxisType == "CategoryAxis")
                    if (a.isArray(d) == false) h.push((g + 1).toString());
                    else h.push(d[0]);
                else {
                    var e = d[0];
                    if (a.type(e) == "date") e = e.getTime();
                    if (l > e) l = e;
                    if (k < e) k = e
                }
                for (var s = this._getXValue(d, g), c = {
                        positive: 0,
                        negative: 0
                    }, f = null, p = v.index - 1; p >= 0; p--) {
                    var q = u[p].dataValues;
                    if (!q) continue;
                    f = q[s];
                    if (f) {
                        c.positive = f.positive;
                        c.negative = f.negative;
                        break
                    }
                }
                if (a.isArray(d)) d = d[1];
                c.actualValue = d;
                if (!b.isNull(d))
                    if (d > 0) {
                        c.positive += d;
                        c.value = c.positive
                    } else if (d < 0) {
                    c.negative += d;
                    c.value = c.negative
                } else if (f != null) c.value = f.value;
                else c.value = 0;
                else c.value = null;
                r[s] = c;
                if (c.value) {
                    n = Math.max(n, c.value);
                    o = Math.min(o, c.value)
                }
            }
            var i = {
                min: o,
                max: n,
                dataValues: r
            };
            if (this.xAxisType == "CategoryAxis") i.categories = h;
            else {
                i.minX = l;
                i.maxX = k
            }
            return i
        },
        _calcVisibleStackedData: function(s, w, u) {
            if (this.realYAxis.zoomEnabled) return;
            var g = this.realXAxis;
            if (!g) return;
            var l = g.visibleMinimum || g.minimum,
                k = g.visibleMaximum || g.maximum,
                i = !b.isNull(l),
                h = !b.isNull(k);
            if (!i && !h) return;
            for (var o = j, n = m, x = s.length, q = {}, f = 0; f < x; f++) {
                var d = s[f];
                if (d == null) continue;
                if (this.xAxisType == "CategoryAxis") {
                    if (i && f + 1 < l || h && f > k) continue
                } else {
                    var t = d[0];
                    if (i && t < l || h && t > k) continue
                }
                for (var r = this._getXValue(d, f), c = {
                        positive: 0,
                        negative: 0
                    }, e = null, p = w.index - 1; p >= 0; p--) {
                    e = u[p].dataValues[r];
                    if (e) {
                        c.positive = e.positive;
                        c.negative = e.negative;
                        break
                    }
                }
                if (a.isArray(d)) d = d[1];
                c.actualValue = d;
                if (d > 0) {
                    c.positive += d;
                    c.value = c.positive
                } else if (d < 0) {
                    c.negative += d;
                    c.value = c.negative
                } else if (e != null) c.value = e.value;
                else c.value = 0;
                q[r] = c;
                n = Math.max(n, c.value);
                o = Math.min(o, c.value)
            }
            var v = {
                min: o,
                max: n,
                dataValues: q
            };
            return v
        },
        _createXAxis: function() {
            if (!this.hasRealData) return null;
            var b = {
                    location: "bottom",
                    orientation: "x"
                },
                a;
            switch (this.xAxisType) {
                case "DateTimeAxis":
                    a = new k(b);
                    break;
                case "CategoryAxis":
                    a = new E(b);
                    break;
                default:
                    a = new o(b)
            }
            a.chart = this.chart;
            return a
        },
        _createYAxis: function() {
            var a = new o({
                location: "left",
                orientation: "y"
            });
            a.chart = this.chart;
            return a
        },
        _initXAxis: function(b) {
            var a = this._findXAxis(b);
            if (a == null) {
                a = this._createXAxis();
                a && b.push(a)
            }
            this.realXAxis = a
        },
        _initYAxis: function(b) {
            var a = this._findYAxis(b);
            if (a == null) {
                a = this._createYAxis();
                b.push(a)
            }
            this.realYAxis = a
        },
        _initSharedAxes: function() {
            if (this.realXAxis && this.realYAxis) {
                this.realXAxis.sharedAxis = this.realYAxis;
                this.realYAxis.sharedAxis = this.realXAxis
            }
        },
        _findAxis: function(b, d) {
            if (d != null)
                for (var a = 0; a < b.length; a++) {
                    var c = b[a];
                    if (c.name == d) return c
                }
            return null
        },
        _findXAxis: function(b) {
            var a = this._findAxis(b, this.axisX);
            if (a != null) return a;
            for (var c = 0; c < b.length; c++) {
                a = b[c];
                if (a.getOrientation(this) != "x" || a.isVertical()) continue;
                if (a.DataType == this.xAxisType) return a
            }
            return null
        },
        _findYAxis: function(b) {
            var a = this._findAxis(b, this.axisY);
            if (a != null) return a;
            for (var c = 0; c < b.length; c++) {
                a = b[c];
                if (a.getOrientation(this) != "y" || a.isVertical() == false) continue;
                if (a.DataType == "LinearAxis") return a
            }
            return null
        },
        _getLegendItems: function(d) {
            var f = [];
            if (!this.showInLegend) return f;
            var g;
            if (this.title != null) g = this.title;
            else {
                var k = a.inArray(this, this.chart.series.items) + 1;
                g = "Series " + k.toString()
            }
            var c = new Z,
                i = this.options;
            if (!b.isNull(i.lineWidth)) c.lineWidth = i.lineWidth;
            if (this.showInScene) {
                c.fillStyle = this.fillStyle;
                c.strokeStyle = this.strokeStyle
            } else {
                c.fillStyle = d.inactiveFillStyle;
                c.strokeStyle = d.inactiveStrokeStyle
            }
            switch (this.type) {
                case "line":
                case "trendline":
                    c.type = "line";
                    c.lineWidth = this.lineWidth;
                    break;
                case "scatter":
                case "bubble":
                    if (this.markers) c.type = this.markers.type
            }
            var j = {
                    chart: this.chart,
                    series: this
                },
                e = a.extend(false, {}, d, {
                    context: j,
                    text: g,
                    marker: c
                });
            if (!this.showInScene) {
                e.textFillStyle = d.inactiveTextFillStyle;
                e.textStrokeStyle = d.inactiveTextStrokeStyle
            }
            var h = new tb(e);
            h.chart = this.chart;
            h.series = this;
            f.push(h);
            return f
        },
        _initColors: function(a) {
            this.fillStyle = this.fillStyle || a;
            this.strokeStyle = this.strokeStyle || a
        },
        _getPixelMargins: function(d) {
            var g = 4,
                h = 0,
                e;
            if (this.markers) {
                e = this.markers.getSize();
                h = this.markers.offset
            } else e = {
                width: 0,
                height: 0
            };
            var c;
            if (this.labels && this.labels.visible !== false) {
                var j = new l("TEST");
                a.extend(j, this.labels);
                c = j.measure(this.chart.ctx)
            } else c = {
                width: 0,
                height: 0
            };
            var f = d.isVertical(),
                k = this.isVertical,
                b;
            if (f) b = e.height / 2 + c.height + g;
            else b = e.width / 2 + c.width + g;
            if (k && !f || !k && f) {
                b += h;
                b *= 1.25;
                var m = b / d.length;
                b *= 1 + m
            }
            if (d.getOrientation(this) == "x") b = Math.max(b, 6);
            else b = Math.max(b, 12);
            var i = this.errorBarsObj;
            if (i && d.getOrientation(this) == "x") b = Math.max(b, i.capLength / 2 + g);
            return {
                left: b,
                right: b
            }
        },
        _isAnchoredToOrigin: function() {
            return false
        },
        _getLabelText: function(c) {
            return a.fn.jqChart.labelFormatter(this.labels.stringFormat, c)
        },
        _getLabelValue: function(a, b) {
            switch (this.labels.valueType) {
                case "percentage":
                    a = this.getPercentage(a, b)
            }
            return a
        },
        _getDataPointLabel: function(i, j, e, c, f) {
            var g = i >= this.realYAxis.getCrossing(),
                h = this._getLabelText(f),
                b = new l(h);
            d.setShadows(b, this, this.chart);
            a.extend(b, this.labels);
            b.measure(this.chart.ctx);
            b.textAlign = "center";
            b.x = j;
            if (g) {
                b.y = e - c;
                b.textBaseline = "bottom"
            } else {
                b.y = e + c;
                b.textBaseline = "top"
            }
            return b
        },
        _getTotal: function(h, i) {
            for (var f = this.chart.series._getSeriesFromType(h), e = 0, d = 0, c = 0; c < f.length; c++) {
                var j = f[c],
                    g = j.arrData;
                if (g == null) continue;
                var b = g[i];
                if (a.isArray(b)) b = b[1];
                if (b == null) continue;
                if (b > 0) e += b;
                else d += b
            }
            return {
                positive: e,
                negative: d
            }
        },
        _getStackedTotal: function(f, g) {
            for (var e = 0, d = 0, b = 0; b < f.length; b++) {
                var h = f[b],
                    c = h.dataValues;
                if (!c) continue;
                var a = c[g];
                if (!a) continue;
                if (a.actualValue > 0) e += a.actualValue;
                else d += a.actualValue
            }
            return {
                positive: e,
                negative: d
            }
        },
        _getPrevStackedPosition: function(d, j, e, i, g, h) {
            for (var b = j - 1; b >= 0; b--) {
                var c = d[b].dataValues;
                if (!c) continue;
                var a = c[e];
                if (!a) continue;
                var f = this._scaleValue(d, a.value, e);
                if (h) {
                    if (a.value == a.positive) return g.getPosition(f)
                } else if (a.value == a.negative) return g.getPosition(f)
            }
            return i
        },
        _getXValue: function(c, d) {
            var b, e = this.arrData;
            if (this.xAxisType == "CategoryAxis")
                if (a.isArray(c) == false) b = (d + 1).toString();
                else b = c[0];
            else {
                b = c[0];
                if (a.type(b) == "date") b = b.getTime()
            }
            if (b) b = b.toString();
            else b = "";
            return b
        },
        _getTooltip: function(b) {
            var a = "<b>" + b.y + "</b><br/>";
            if (this.title) {
                var d = c.getColorFromFillStyle(this.fillStyle);
                a = '<span style="color:' + d + '">' + this._getTooltipTitle() + "</span>: " + a
            }
            return a
        },
        _calcColumnScale: function(m) {
            for (var h = [], d = 0; d < m.length; d++) a.merge(h, m[d].arrData || []);
            h.sort(function(a, b) {
                return !a || !b ? 0 : a[0] - b[0]
            });
            for (var f, g, e, k = j, d = 0; d < h.length - 1; d++) {
                e = h[d];
                if (!e) continue;
                f = e[0];
                if (a.type(f) == "date") f = f.getTime();
                e = h[d + 1];
                if (!e) continue;
                g = e[0];
                if (a.type(g) == "date") g = g.getTime();
                if (f != g) k = Math.min(k, g - f)
            }
            var c = this.realXAxis,
                l = c.series,
                o = b.isNull(c.minimum) ? l.minX : c.minimum,
                n = b.isNull(c.maximum) ? l.maxX : c.maximum,
                i = n - o;
            if (c.skipEmptyDays) i -= c.totalEmptyDaysTicks;
            i = Math.min(.5, k / i);
            return i
        },
        _getSeriesFromThisType: function() {
            return this.chart.series._getSeriesFromType(this.type)
        },
        _hideFromLegend: function() {
            var a = !this.showInScene;
            this.showInScene = a;
            this.options.showInScene = a
        },
        _handleEvent: function() {},
        _render: function(a) {
            if (!this.hasRealData || !this.isInScene()) return;
            switch (this.xAxisType) {
                case "LinearAxis":
                case "DateTimeAxis":
                    return this._renderLinearData(a);
                case "CategoryAxis":
                    return this._renderCatData(a)
            }
        },
        _createErrorBars: function(a) {
            this.errorBarsObj && this.errorBarsObj._createShapes(a)
        },
        hasData: function() {
            return !!this.arrData && this.xAxisType != "none"
        },
        getPercentage: function(a, d) {
            var b = this._getTotal(this.type, d),
                c = a > 0 ? b.positive : b.negative;
            a = c != 0 ? 100 * Math.abs(a) / Math.abs(c) : 0;
            return a
        },
        setOptions: function(c) {
            var b = a.extend({}, this.defaults, c || {});
            a.extend(this, b);
            this.options = c;
            if (c) this.emptyPointStyleObj = new Yb(b.emptyPointStyle);
            if (b.markers != null) this.markers = new Z(b.markers)
        },
        isInScene: function() {
            return this.visible && this.showInScene
        },
        _getTooltipTitle: function() {
            return b.replaceTextForTooltip(this.title)
        },
        getLabelsOffset: function() {
            var c = this.markers && this.markers.isVisible(),
                a = c ? this.markers.size / 2 : 0,
                b = this.errorBarsObj;
            if (b) a = Math.max(a, b.getMaxLength());
            return a
        },
        defaults: {
            lineCap: "butt",
            lineJoin: "round",
            miterLimit: 10,
            pointWidth: .6,
            minimumWidth: 1,
            nullHandling: "break",
            visible: true,
            lineWidth: 1,
            strokeStyle: null,
            showInLegend: true,
            showInScene: true
        }
    };

    function A(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 2,
            markers: {}
        });
        this.defaults = c;
        f.call(this, b);
        this.hasErrorBars = true
    }
    A.prototype = new f;
    A.constructor = A;
    A.prototype._renderCatData = function(n) {
        for (var o = this.arrData, p = o.length, h = this.markers != null && this.markers.isVisible(), c = [], i = [], m = [], k = [], q = this.getLabelsOffset(), g, j, r, d, e = 0; e < p; e++) {
            var f = o[e];
            if (f === null) {
                c.push(null);
                c.push(null);
                !h && i.push(null);
                continue
            }
            r = g = e + .5;
            if (a.isArray(f) == false) d = f;
            else {
                d = f[1];
                if (b.isNull(d)) {
                    c.push(null);
                    c.push(null);
                    !h && i.push(null);
                    continue
                }
            }
            g = this.realXAxis.getPosition(g);
            j = this.realYAxis.getPosition(d);
            c.push(g);
            c.push(j);
            !h && i.push({
                dataItem: f,
                index: e,
                x: this.realXAxis._getValue(e),
                y: d
            });
            if (this.realYAxis.isValueVisible(d) === false) continue;
            this._addMarkerAndLabel(m, k, g, j, e, p, null, d, q)
        }
        var l = this._createShape(c, n);
        if (!h && l) l.context = {
            chart: this.chart,
            series: this,
            points: i
        };
        else a.merge(n, m);
        return k
    };
    A.prototype._renderLinearData = function(j) {
        var q = this.arrData,
            k = q.length,
            e = this.markers != null && this.markers.isVisible(),
            r = this.labels != null && this.labels.visible !== false;
        if (k > 1e3 && e == false && r == false) {
            this._renderLargeXYData(j);
            return
        }
        for (var c = [], h = [], p = [], n = [], s = this.getLabelsOffset(), l, m, f, d, g = 0; g < k; g++) {
            var i = q[g];
            if (i === null) {
                c.push(null);
                c.push(null);
                !e && h.push(null);
                continue
            }
            f = i[0];
            d = i[1];
            if (b.isNull(f) || b.isNull(d)) {
                c.push(null);
                c.push(null);
                !e && h.push(null);
                continue
            }
            l = this.realXAxis.getPosition(f);
            m = this.realYAxis.getPosition(d);
            c.push(l);
            c.push(m);
            !e && h.push({
                dataItem: i,
                index: g,
                x: f,
                y: d
            });
            if (this.realYAxis.isValueVisible(d) === false) continue;
            this._addMarkerAndLabel(p, n, l, m, g, k, f, d, s)
        }
        var o = this._createShape(c, j);
        if (!e && o) o.context = {
            chart: this.chart,
            series: this,
            points: h
        };
        else a.merge(j, p);
        return n
    };
    A.prototype._renderLargeXYData = function(t) {
        for (var n = this.arrData, x = n.length, w = this.chart.gridArea.width, s = this.chart.gridArea.height, i = this.realXAxis, j = this.realYAxis, u = 2 * (i.actualVisibleMaximum - i.actualVisibleMinimum) / w, v = 2 * (j.actualVisibleMaximum - j.actualVisibleMinimum) / s, a = [], q, r, c, b, o = 0, p = 0, k = 0, l = 0, d, f, g, e = [], h = 0; h < x; h++) {
            d = n[h];
            if (d === null) {
                a.push(null);
                a.push(null);
                e.push(null);
                continue
            }
            c = d[0];
            b = d[1];
            if (b === null) {
                a.push(null);
                a.push(null);
                e.push(null);
                continue
            }
            f = o - c;
            g = p - b;
            k += f < 0 ? -f : f;
            l += g < 0 ? -g : g;
            if (k < u && l < v) continue;
            k = 0;
            l = 0;
            o = c;
            p = b;
            q = i.getPosition(c);
            r = j.getPosition(b);
            a.push(q);
            a.push(r);
            e.push({
                dataItem: d,
                index: h,
                x: c,
                y: b
            })
        }
        var m = this._createShape(a, t);
        if (m) m.context = {
            chart: this.chart,
            series: this,
            points: e
        }
    };
    A.prototype._createShape = function(c, b) {
        this._createErrorBars(b);
        var a = new q(c);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };

    function eb(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null
        });
        this.defaults = c;
        f.call(this, b)
    }
    eb.prototype = new A;
    eb.constructor = eb;
    eb.prototype._createShape = function(i, f) {
        var h = [];
        a.merge(h, i);
        var c = new q(h);
        this._setShapeSettings(c);
        var g = this.chart.gridArea,
            k = g.y,
            j = g.y + g.height,
            e = this.realYAxis.getCrossingPosition();
        e = b.fitInRange(e, k, j);
        var d = new bb(i, e);
        this._setShapeSettings(d);
        d.lineWidth = 0;
        f.push(d);
        this._createErrorBars(f);
        f.push(c);
        this._addLengthAnimation(d);
        this._addLengthAnimation(c);
        return c
    };
    eb.prototype._isAnchoredToOrigin = function() {
        return true
    };

    function yb(a) {
        eb.call(this, a)
    }
    yb.prototype = new eb;
    yb.constructor = yb;
    yb.prototype._createShape = function(i, f) {
        var h = [];
        a.merge(h, i);
        var d = new u(h);
        this._setShapeSettings(d);
        var g = this.chart.gridArea,
            k = g.y,
            j = g.y + g.height,
            e = this.realYAxis.getCrossingPosition();
        e = b.fitInRange(e, k, j);
        var c = new bb(i, e, false, true);
        this._setShapeSettings(c);
        c.lineWidth = 0;
        f.push(c);
        this._createErrorBars(f);
        f.push(d);
        this._addLengthAnimation(c);
        this._addLengthAnimation(d);
        return d
    };

    function s(a) {
        this.isVertical = true;
        f.call(this, a);
        this.hasErrorBars = true
    }
    s.prototype = new f;
    s.constructor = s;
    s.prototype._createXAxis = function() {
        if (!this.hasRealData) return null;
        var b = {
                location: "left",
                orientation: "x"
            },
            a;
        switch (this.xAxisType) {
            case "DateTimeAxis":
                a = new k(b);
                break;
            case "CategoryAxis":
                a = new E(b);
                break;
            default:
                a = new o(b)
        }
        a.chart = this.chart;
        return a
    };
    s.prototype._createYAxis = function() {
        var a = new o({
            location: "bottom",
            orientation: "y"
        });
        a.chart = this.chart;
        return a
    };
    s.prototype._findXAxis = function(b) {
        var a = this._findAxis(b, this.axisX);
        if (a != null) return a;
        var c;
        if (this.categories) c = E;
        else c = o;
        for (var d = 0; d < b.length; d++) {
            a = b[d];
            if (a.getOrientation(this) != "x" || a.isVertical() == false) continue;
            if (a instanceof c) return a
        }
        return null
    };
    s.prototype._findYAxis = function(b) {
        var a = this._findAxis(b, this.axisY);
        if (a != null) return a;
        for (var c = 0; c < b.length; c++) {
            a = b[c];
            if (a.getOrientation(this) != "y" || a.isVertical()) continue;
            if (a instanceof o) return a
        }
        return null
    };
    s.prototype._renderCatData = function(o) {
        var r = this.arrData,
            i = this.chart.gridArea,
            z = i.x,
            E = z + i.width,
            e = this.realYAxis.getCrossingPosition();
        e = b.fitInRange(e, z, E);
        e = Math.round(e);
        var q = this.chart.series._findClusters(this, this.type),
            l = r.length,
            C = i.height / this.realXAxis.getZoom(),
            x = C / l,
            F = x / q.count,
            f = Math.round(this.pointWidth * F),
            B = q.count * f,
            n = (x - B) / 2;
        n = Math.round(n + q.index * f);
        f = Math.max(f, this.minimumWidth);
        for (var v = [], s = [], D = this.getLabelsOffset() + 2, c, k, d = 0; d < l; d++) {
            var m = r[d];
            if (m == null) continue;
            var G = d,
                h;
            if (a.isArray(m) == false) h = m;
            else h = m[1];
            if (h == null) continue;
            k = Math.round(this.realXAxis.getCatPosition(G) - n - f);
            c = Math.round(this.realYAxis.getPosition(h));
            var A = c,
                g;
            if (c <= e) {
                g = e - c;
                var w = i.x - 10;
                if (c < w) {
                    var y = w - c;
                    c += y;
                    g -= y
                }
            } else {
                g = c - e;
                c = e;
                var t = i.getRight() + 10;
                if (c + g > t) g = t - c
            }
            var u = {
                    chart: this.chart,
                    series: this,
                    dataItem: r[d],
                    index: d,
                    x: this.realXAxis._getValue(d),
                    y: h
                },
                j = new p(c, k, g, f);
            j.context = u;
            j.center = {
                x: Math.round(A),
                y: Math.round(k + f / 2)
            };
            this._setShapeSettings(j, d);
            o.push(j);
            this._addAnimation(j, d, l);
            k += f / 2;
            this._addMarkerAndLabel(v, s, A, k, d, l, null, h, D, u)
        }
        this._createErrorBars(o);
        a.merge(o, v);
        return s
    };
    s.prototype._renderLinearData = function(m) {
        var n = this.arrData,
            g = this.chart.gridArea,
            x = g.x,
            E = x + g.width,
            d = this.realYAxis.getCrossingPosition();
        d = b.fitInRange(d, x, E);
        d = Math.round(d);
        var o = n.length,
            A = g.height / this.realXAxis.getZoom(),
            D = this._getSeriesFromThisType(),
            C = this._calcColumnScale(D) * A,
            l = this.pointWidth * C;
        l = Math.max(l, this.minimumWidth);
        for (var u = [], r = [], B = this.getLabelsOffset(), c, q, i, j, k, e = 0; e < o; e++) {
            k = n[e];
            if (k == null || a.isArray(k) == false) continue;
            i = k[0];
            j = k[1];
            if (i == null || j == null) continue;
            var t = {
                chart: this.chart,
                series: this,
                dataItem: n[e],
                index: e,
                x: i,
                y: j
            };
            q = this.realXAxis.getPosition(i);
            c = Math.round(this.realYAxis.getPosition(j));
            var z = c,
                f;
            if (c <= d) {
                f = d - c;
                var v = g.x - 10;
                if (c < v) {
                    var w = v - c;
                    c += w;
                    f -= w
                }
            } else {
                f = c - d;
                c = d;
                var s = g.getRight() + 10;
                if (c + f > s) f = s - c
            }
            var y = q - l / 2,
                h = new p(c, y, f, l);
            h.context = t;
            h.center = {
                x: Math.round(z),
                y: Math.round(y + l / 2)
            };
            this._setShapeSettings(h, e);
            m.push(h);
            this._addAnimation(h, e, o);
            this._addMarkerAndLabel(u, r, z, q, e, o, i, j, B, t)
        }
        this._createErrorBars(m);
        a.merge(m, u);
        return r
    };
    s.prototype._addAnimation = function(c, g, f) {
        var b = this._getAnimation();
        if (!b || b.enabled === false) return;
        var a = new ub(b, c, "xDecrease", c.width, 0);
        this._setIsAnimReversed(c);
        var d = a.duration / f,
            e = a.delayTime + g * d;
        a.delayTime = e;
        a.duration = d;
        this.chart.storyboard.addAnimation(a)
    };
    s.prototype._setIsAnimReversed = function(c) {
        var a = c.context.y < this.realYAxis.getCrossing(),
            b = this.realYAxis.reversed;
        c.isAnimReversed = !b && a || b && !a
    };
    s.prototype._correctMarkerPosition = function(a, e, d) {
        var b = this.markers.offset;
        if (b) {
            var c = d >= this.realYAxis.getCrossing();
            a = c ? a + b : a - b
        }
        return {
            x: a,
            y: e
        }
    };
    s.prototype._getPixelMargins = function(a) {
        if (a.isVertical() == false) {
            var b = f.prototype._getPixelMargins.call(this, a),
                c = a.length / 10,
                k = Math.max(c, b.left),
                j = Math.max(c, b.right);
            return {
                left: k,
                right: j
            }
        }
        if (!this.hasData()) return {
            left: 0,
            right: 0
        };
        var h = 4,
            i = this._getSeriesFromThisType(),
            e = this._calcColumnScale(i),
            g = a.length,
            d = .5 * e * g + h;
        return {
            left: d,
            right: d
        }
    };
    s.prototype._isAnchoredToOrigin = function() {
        return true
    };
    s.prototype._getDataPointLabel = function(k, g, m, f, h) {
        var i = k <= this.realYAxis.getCrossing(),
            j = this._getLabelText(h),
            b = new l(j);
        d.setShadows(b, this, this.chart);
        a.extend(b, this.labels);
        b.measure(this.chart.ctx);
        b.y = m;
        var c = this.chart.gridArea;
        if (i) {
            b.x = g - f;
            b.textAlign = "right";
            if (b.x - b.width < c.x + 4) b.x = c.x + b.width + 4
        } else {
            b.x = g + f;
            b.textAlign = "left";
            var e = c.getRight() - 4;
            if (b.x + b.width > e) b.x = e - b.width
        }
        return b
    };
    s.prototype._initColors = function(a) {
        this.fillStyle = this.fillStyle || a
    };

    function cb(b) {
        var c = a.extend(true, {}, this.defaults, {
            markers: {
                strokeStyle: null
            }
        });
        this.defaults = c;
        f.call(this, b)
    }
    cb.prototype = new f;
    cb.constructor = cb;
    cb.prototype._processData = function() {
        this.arrData = null;
        if (this.data)
            if (!this.xValuesType && !this.yValuesType && !this.sizeValuesType) this.arrData = b.cloneArray(this.data);
            else {
                this.arrData = [];
                for (var d = 0; d < this.data.length; d++) {
                    var a = this.data[d].slice(0);
                    a[0] = b.processDataValue(a[0], this.xValuesType);
                    a[1] = b.processDataValue(a[1], this.yValuesType);
                    a[2] = b.processDataValue(a[2], this.sizeValuesType);
                    this.arrData.push(a)
                }
            }
        else {
            var c = this.chart.arrDataSource;
            if (c) {
                var i = this.xValuesField,
                    j = this.yValuesField,
                    h = this.sizeValuesField,
                    f = b.processDataField(c, i),
                    g = b.processDataField(c, j),
                    e = b.processDataField(c, h);
                if (f && g && e) this.arrData = b.mergeArrays([f, g, e])
            }
        }
        this._processXAxisType();
        this.hasRealData = this.hasData()
    };
    cb.prototype._initData = function() {
        if (!this.arrData) return;
        var c = [];
        a.merge(c, this.arrData);
        for (var p = this.chart.series.items, b = 0; b < p.length; b++) {
            var l = p[b];
            if (l == this || l.type != "bubble") continue;
            a.merge(c, l.arrData)
        }
        if (a.isArray(c) == false) return;
        for (var k = j, i = m, h = j, g = m, e = j, d = m, q = c.length, b = 0; b < q; b++) {
            var n = c[b][0],
                o = c[b][1],
                f = c[b][2];
            if (n == null || o == null || f == null) continue;
            h = Math.min(h, n);
            g = Math.max(g, n);
            k = Math.min(k, o);
            i = Math.max(i, o);
            e = Math.min(e, f);
            d = Math.max(d, f)
        }
        this.min = k;
        this.max = i;
        this.minX = h;
        this.maxX = g;
        this.minSize = e;
        this.maxSize = d
    };
    cb.prototype._render = function(m) {
        if (!this.hasRealData || !this.isInScene()) return;
        var h = this.arrData,
            o = this.chart,
            p = o.options,
            g = p.maxBubbleSize;
        if (!g) {
            var r = o.gridArea;
            g = Math.min(r.width, r.height) * .25
        }
        var f = p.maxBubbleScale;
        if (!f) f = this.maxSize;
        for (var i = h.length, j, k, e, c, b, a = 0; a < i; a++) {
            var d = h[a];
            if (d == null) continue;
            c = d[0];
            b = d[1];
            e = d[2];
            if (c == null || b == null || e == null) continue;
            var u = e / f,
                q = Math.max(u * g, 0);
            j = this.realXAxis.getPosition(c);
            k = this.realYAxis.getPosition(b);
            if (this.markers && this.markers.isVisible()) {
                var t = {
                        chart: this.chart,
                        series: this,
                        dataItem: h[a],
                        index: a,
                        x: c,
                        y: b,
                        size: e
                    },
                    l = this._getMarker(j, k, q / 2, null, null, a, t);
                m.push(l);
                this._addShapeAnimation(l, a, i)
            }
            if (this.labels && this.labels.visible !== false) {
                var s = this._getLabelValue(b, a),
                    n = this._getDataPointLabel(b, j, k, q / 2, s);
                m.push(n);
                this._addShapeAnimation(n, a, i)
            }
        }
    };
    cb.prototype._getPixelMargins = function() {
        var a = this.chart.gridArea;
        if (a.width == null) return {
            left: 0,
            right: 0
        };
        var c = Math.min(a.width, a.height) * .35,
            b = c / 2;
        return {
            left: b + 4,
            right: b + 4
        }
    };
    cb.prototype._getTooltip = function(b) {
        var a = "y: <b>" + b.y.toString() + "</b><br/>size: <b>" + b.size.toString() + "</b>";
        if (this.title) {
            var d = c.getColorFromFillStyle(this.fillStyle);
            a = '<div style="color:' + d + '">' + this._getTooltipTitle() + "</div>" + a
        }
        return a
    };

    function J(a) {
        f.call(this, a);
        this.hasErrorBars = true
    }
    J.prototype = new f;
    J.constructor = J;
    J.prototype._renderCatData = function(o) {
        var r = this.arrData,
            j = this.chart.gridArea,
            C = j.y,
            B = j.y + j.height,
            d = this.realYAxis.getCrossingPosition();
        d = b.fitInRange(d, C, B);
        d = Math.round(d);
        var q = this.chart.series._findClusters(this, this.type),
            k = r.length,
            y = j.width / this.realXAxis.getZoom(),
            v = y / k,
            A = v / q.count,
            f = Math.round(this.pointWidth * A),
            x = q.count * f,
            n = (v - x) / 2;
        n = Math.round(n + q.index * f);
        f = Math.max(f, this.minimumWidth);
        for (var u = [], s = [], z = this.getLabelsOffset(), g, h, e, c = 0; c < k; c++) {
            var l = r[c];
            if (l == null) continue;
            g = c;
            if (a.isArray(l) == false) e = l;
            else e = l[1];
            if (e == null) continue;
            g = Math.round(this.realXAxis.getCatPosition(g) + n);
            h = Math.round(this.realYAxis.getPosition(e));
            var w = h,
                m;
            if (h <= d) m = d - h;
            else {
                m = h - d;
                h = d
            }
            var t = {
                    chart: this.chart,
                    series: this,
                    dataItem: r[c],
                    index: c,
                    x: this.realXAxis._getValue(c),
                    y: e
                },
                i = new p(g, h, f, m);
            i.context = t;
            i.center = {
                x: Math.round(g + f / 2),
                y: Math.round(w)
            };
            this._setShapeSettings(i, c);
            o.push(i);
            this._addAnimation(i, c, k);
            if (this.realYAxis.isValueVisible(e) === false) continue;
            g += f / 2;
            this._addMarkerAndLabel(u, s, g, w, c, k, null, e, z, t)
        }
        this._createErrorBars(o);
        a.merge(o, u);
        return s
    };
    J.prototype._renderLinearData = function(m) {
        var n = this.arrData,
            j = this.chart.gridArea,
            B = j.y,
            z = j.y + j.height,
            c = this.realYAxis.getCrossingPosition();
        c = b.fitInRange(c, B, z);
        c = Math.round(c);
        var o = n.length,
            w = j.width / this.realXAxis.getZoom(),
            A = this._getSeriesFromThisType(),
            y = this._calcColumnScale(A) * w,
            i = this.pointWidth * y;
        i = Math.max(i, this.minimumWidth);
        for (var s = [], q = [], x = this.getLabelsOffset(), e, f, u, g, d = 0; d < o; d++) {
            var k = n[d];
            if (k == null || a.isArray(k) == false) continue;
            u = e = k[0];
            g = k[1];
            if (e == null || g == null) continue;
            var r = {
                chart: this.chart,
                series: this,
                dataItem: n[d],
                index: d,
                x: e,
                y: g
            };
            e = this.realXAxis.getPosition(e);
            f = Math.round(this.realYAxis.getPosition(g));
            var v = f,
                l;
            if (f <= c) l = c - f;
            else {
                l = f - c;
                f = c
            }
            var t = e - i / 2,
                h = new p(t, f, i, l);
            h.context = r;
            h.center = {
                x: Math.round(t + i / 2),
                y: Math.round(v)
            };
            this._setShapeSettings(h, d);
            m.push(h);
            this._addAnimation(h, d, o);
            if (this.realYAxis.isValueVisible(g) === false) continue;
            this._addMarkerAndLabel(s, q, e, v, d, o, u, g, x, r)
        }
        this._createErrorBars(m);
        a.merge(m, s);
        return q
    };
    J.prototype._addAnimation = function(c, g, f) {
        var b = this._getAnimation();
        if (!b || b.enabled === false) return;
        var a = new ub(b, c, "yDecrease", c.height, 0);
        this._setIsAnimReversed(c);
        var d = a.duration / f,
            e = a.delayTime + g * d;
        a.delayTime = e;
        a.duration = d;
        this.chart.storyboard.addAnimation(a)
    };
    J.prototype._setIsAnimReversed = function(c) {
        var a = c.context.y >= this.realYAxis.getCrossing(),
            b = this.realYAxis.reversed;
        c.isAnimReversed = !b && a || b && !a
    };
    J.prototype._getPixelMargins = function(a) {
        if (a.isVertical()) {
            var b = f.prototype._getPixelMargins.call(this, a),
                c = a.length / 10,
                k = Math.max(c, b.left),
                j = Math.max(c, b.right);
            return {
                left: k,
                right: j
            }
        }
        if (!this.hasData()) return {
            left: 0,
            right: 0
        };
        var h = 4,
            i = this._getSeriesFromThisType(),
            e = this._calcColumnScale(i),
            g = a.length,
            d = .5 * e * g + h;
        return {
            left: d,
            right: d
        }
    };
    J.prototype._isAnchoredToOrigin = function() {
        return true
    };
    J.prototype._initColors = function(a) {
        this.fillStyle = this.fillStyle || a
    };

    function h(b) {
        var c = a.extend(true, {}, this.defaults, {
            margin: 8,
            startAngle: -90,
            explodedRadius: 10,
            hiddenSlices: [],
            allowExplodeSlices: true,
            explodedSlices: [],
            labelsPosition: "inside",
            labelsAlign: "circle",
            labelsExtend: 20,
            leaderLineWidth: 1,
            leaderLineStrokeStyle: "black",
            innerExtent: 0,
            outerExtent: 1
        });
        this.defaults = c;
        f.call(this, b)
    }
    h.prototype = new f;
    h.constructor = h;
    h.prototype._initXAxis = function() {};
    h.prototype._initYAxis = function() {};
    h.prototype._initVisibleData = function() {};
    h.prototype._processData = function() {
        this.arrData = null;
        if (this.data) {
            this.expIndexes = this.explodedSlices;
            if (!this.dataLabelsType && !this.dataValuesType) this.arrData = b.cloneArray(this.data);
            else {
                this.arrData = [];
                for (var a = 0; a < this.data.length; a++) {
                    var d = this.data[a].slice(0);
                    d[0] = b.processDataValue(d[0], this.dataLabelsType);
                    d[1] = b.processDataValue(d[1], this.dataValuesType);
                    this.arrData.push(d)
                }
            }
        } else {
            var c = this.chart.arrDataSource;
            if (c) {
                var e = this.dataLabelsField,
                    f = this.dataValuesField,
                    g = b.processDataField(c, e),
                    h = b.processDataField(c, f);
                this.arrData = b.mergeArraysXY(g, h);
                this.expIndexes = [];
                for (var a = 0; a < c.length; a++) {
                    var i = c[a],
                        j = i[this.explodedField];
                    j && this.expIndexes.push(a)
                }
            }
        }
        this._processXAxisType();
        this._processNullValues()
    };
    h.prototype._getYValues = function() {
        for (var e = [], f = this.arrData, g = f.length, d = 0; d < g; d++) {
            var b = f[d];
            if (b == null) continue;
            var c;
            if (a.isArray(b) == false) c = b;
            else c = b[1];
            e.push(Math.abs(c))
        }
        return e
    };
    h.prototype._createLabels = function() {
        var D = this.arrData,
            f = {};
        if (!D) return f;
        var n = this.chart.gridArea,
            M = n.width,
            L = n.height,
            C = this._getYValues(),
            F = C.length,
            G = this.getTotal(),
            r = this.margin,
            m = this._calcRadius({
                h: r,
                v: r
            });
        if (m < 0) return f;
        var w = m * this.innerExtent,
            j = m * this.outerExtent,
            s = n.x + M / 2,
            t = n.y + L / 2,
            K = Math.PI * 2 / G,
            p = b.radians(this.startAngle),
            u = this.explodedRadius;
        if (!this.labels || this.labels.visible === false) return f;
        for (var k, A, q = [], p = b.radians(this.startAngle), B = this.labelsPosition == "outside", v = this.hiddenSlices, H = F - v.length, o = 0, d = 0; d < F; d++) {
            if (a.inArray(d, v) != -1) {
                o++;
                continue
            }
            var i = C[d],
                y = 100 * i / G,
                x = i,
                z = p + i * K;
            switch (this.labels.valueType) {
                case "percentage":
                    x = y
            }
            if (i == 0) continue;
            var J = this._getLabelText(x),
                e = (p + z) / 2,
                c = new l(J);
            c.textBaseline = "top";
            a.extend(c, this.labels);
            c.context = {
                chart: this.chart,
                series: this,
                dataItem: D[d],
                index: d,
                value: i,
                percentage: y
            };
            this.chart.elem.trigger("dataPointLabelCreating", c);
            var E = c.measure(this.chart.ctx),
                g = s,
                h = t;
            A = this.isExploded(d);
            if (A) {
                h = t + u * Math.sin(e);
                g = s + u * Math.cos(e)
            }
            if (B) k = this._getSliceCenter(g, h, e, j + this.labelsExtend);
            else {
                var I = w ? (w + j) / 2 : j * .6;
                k = this._getSliceCenter(g, h, e, I)
            }
            c.x = k.x - E.width / 2;
            c.y = k.y - E.height / 2;
            p = z;
            if (!B && this.isOverlap(c, q)) continue;
            q.push(c);
            segment = {
                pt: this._getSliceCenter(g, h, e, j),
                angle: e,
                index: d,
                animIndex: d - o,
                cx: g,
                cy: h
            };
            e = b.normalizeAngle(e + Math.PI / 2);
            segment.isLeft = e >= Math.PI;
            c.segment = segment;
            this._addShapeAnimation(c, d - o, H)
        }
        a.extend(f, {
            x: s,
            y: t,
            radius: m,
            margin: r,
            labels: q
        });
        return f
    };
    h.prototype._arrangeLabels = function(c) {
        var j = c.labels;
        if (!j || this.labelsPosition != "outside") return {
            margins: {
                h: this.margin,
                v: this.margin
            },
            lines: []
        };
        var f = [],
            e = [],
            u = this._calculateLabelsMargin(j, c),
            v = this._calcRadius(u),
            w = v * this.innerExtent,
            l = v * this.outerExtent;
        c.radius = l;
        for (var a, t, p = m, o = m, b = 0; b < j.length; b++) {
            a = j[b];
            t = a.width;
            if (a.segment.isLeft) {
                f.push(a);
                p = Math.max(p, t)
            } else {
                e.push(a);
                o = Math.max(o, t)
            }
        }
        f.sort(this.labelComparator(true));
        e.sort(this.labelComparator(false));
        var s = [],
            d, g, k, q, h, i, n = this.explodedRadius;
        for (b = 0; b < f.length; b++) {
            a = f[b];
            g = a.segment;
            h = c.x;
            i = c.y;
            d = g.angle;
            q = this.isExploded(g.index);
            if (q) {
                i += n * Math.sin(d);
                h += n * Math.cos(d)
            }
            k = this._getSliceCenter(h, i, d, l + this.labelsExtend);
            a.x = k.x - a.width - this.labelsExtend;
            a.y = k.y - a.height / 2;
            g.pt = this._getSliceCenter(h, i, d, l)
        }
        var r = this._distancesBetweenLabels(f);
        this._distributeLabels(r, f);
        for (b = 0; b < f.length; b++) {
            a = f[b];
            this._hLabelAlign(a, p, o, c);
            s.push(this._addLabelLine(a, c, j.length))
        }
        for (b = 0; b < e.length; b++) {
            a = e[b];
            g = a.segment;
            h = c.x;
            i = c.y;
            d = g.angle;
            q = this.isExploded(g.index);
            if (q) {
                i += n * Math.sin(d);
                h += n * Math.cos(d)
            }
            k = this._getSliceCenter(h, i, d, l + this.labelsExtend);
            a.x = k.x + this.labelsExtend;
            a.y = k.y - a.height / 2;
            g.pt = this._getSliceCenter(h, i, d, l)
        }
        r = this._distancesBetweenLabels(e);
        this._distributeLabels(r, e);
        for (b = 0; b < e.length; b++) {
            a = e[b];
            this._hLabelAlign(a, p, o, c);
            s.push(this._addLabelLine(a, c, j.length))
        }
        return {
            margins: u,
            lines: s
        }
    };
    h.prototype.labelComparator = function(a) {
        a = a ? -1 : 1;
        return function(e, f) {
            var c = e.segment.angle,
                d = f.segment.angle,
                c = b.normalizeAngle(c + 3 * Math.PI / 2),
                d = b.normalizeAngle(d + 3 * Math.PI / 2);
            return (c - d) * a
        }
    };
    h.prototype._addLabelLine = function(m, l, t) {
        var j = 4,
            p = 4,
            o = this.labelsAlign == "circle",
            d = m.segment,
            a = [],
            i = l.radius;
        if (this.isExploded(d.index)) i += this.explodedRadius;
        var k = l.x,
            s = l.y;
        a.push(d.pt.x);
        a.push(d.pt.y);
        var h = m.x,
            f = m.y + m.height / 2,
            c, e, g, r = new Y(k - i, s - i, i);
        if (d.isLeft) {
            h += m.width + p;
            e = h + j;
            c = b.intersection({
                x: k,
                y: s
            }, d.pt, {
                x: h,
                y: f
            }, {
                x: e,
                y: f
            });
            c = c || {
                x: e,
                y: f
            };
            c.x = Math.max(c.x, e);
            if (r.hitTest(c.x, c.y) || c.x > k) {
                g = k - i - j;
                if (o)
                    if (g > e) {
                        a.push(g);
                        a.push(d.pt.y)
                    } else {
                        a.push(d.pt.x - j * 2);
                        a.push(d.pt.y)
                    }
                else {
                    a.push(g);
                    a.push(d.pt.y)
                }
                a.push(e);
                a.push(f)
            } else {
                c.y = f;
                a.push(c.x);
                a.push(c.y)
            }
        } else {
            h -= p;
            e = h - j;
            c = b.intersection({
                x: l.x,
                y: l.y
            }, d.pt, {
                x: h,
                y: f
            }, {
                x: e,
                y: f
            });
            c = c || {
                x: e,
                y: f
            };
            c.x = Math.min(c.x, e);
            if (r.hitTest(c.x, c.y) || c.x < k) {
                g = k + i + j;
                if (o)
                    if (g < e) {
                        a.push(g);
                        a.push(d.pt.y)
                    } else {
                        a.push(d.pt.x + j * 2);
                        a.push(d.pt.y)
                    }
                else {
                    a.push(g);
                    a.push(d.pt.y)
                }
                a.push(e);
                a.push(f)
            } else {
                c.y = f;
                a.push(c.x);
                a.push(c.y)
            }
        }
        a.push(h);
        a.push(f);
        var n = new q(a);
        n.lineWidth = this.leaderLineWidth;
        n.strokeStyle = this.leaderLineStrokeStyle;
        this._addShapeAnimation(n, d.animIndex, t);
        return n
    };
    h.prototype._renderSlices = function(C, B) {
        var p = this.arrData;
        if (!p) return;
        var f = this.chart.gridArea,
            F = f.width,
            E = f.height,
            n = this._getYValues(),
            q = n.length,
            r = this.getTotal(),
            h = this._calcRadius(B);
        if (h < 0) return;
        for (var w = h * this.innerExtent, x = h * this.outerExtent, u = f.x + F / 2, v = f.y + E / 2, D = Math.PI * 2 / r, e = b.radians(this.startAngle), G = this.fillStyles, j = this.explodedRadius, l = this.hiddenSlices, z = q - l.length, k = 0, m, c = 0; c < q; c++) {
            if (a.inArray(c, l) != -1) {
                k++;
                continue
            }
            var i = n[c],
                y = 100 * i / r,
                g = e + i * D,
                s = u,
                t = v;
            m = this.isExploded(c);
            if (m) {
                var o = (e + g) / 2;
                t = v + j * Math.sin(o);
                s = u + j * Math.cos(o)
            }
            var d = new vb(s, t, w, x, e, g),
                A = {
                    chart: this.chart,
                    series: this,
                    dataItem: p[c],
                    index: c,
                    value: i,
                    percentage: y
                };
            d.context = A;
            this._setShapeSettings(d, c);
            d.fillStyle = this.getSliceColor(c);
            C.push(d);
            this._addSliceAnimation(d, c - k, z);
            e = g
        }
    };
    h.prototype._distancesBetweenLabels = function(b) {
        if (!b || b.length == 0) return [];
        var c = [],
            d, a = b[0],
            f = this.chart.gridArea,
            h = f.height,
            g = f.y;
        c.push(a.y - g);
        for (var e = 1; e < b.length; e++) {
            d = b[e];
            c.push(d.y - a.y - a.height);
            a = d
        }
        c.push(g + h - a.y - a.height);
        return c
    };
    h.prototype._distributeLabels = function(c, g) {
        for (var f = c.length, a, e, d, b = 0; b < f; b++) {
            e = d = b;
            a = -c[b];
            while (a > 0 && (e >= 0 || d < f)) {
                a = this._takeDistance(c, b, --e, a);
                a = this._takeDistance(c, b, ++d, a)
            }
        }
        this._reflowLabels(c, g)
    };
    h.prototype._hLabelAlign = function(a, j, i, d) {
        var e = a.segment,
            k = this.labelsAlign == "circle";
        if (k) {
            var g = d.x,
                h = d.y,
                b = d.radius + this.labelsExtend;
            if (this.isExploded(e.index)) b += this.explodedRadius;
            var c = Math.min(Math.abs(h - a.y), Math.abs(h - a.y - a.height));
            if (c < b)
                if (e.isLeft) a.x = g - a.width - Math.sqrt(b * b - c * c);
                else a.x = g + Math.sqrt(b * b - c * c)
        } else {
            var f = this.chart.gridArea;
            if (e.isLeft) a.x = f.x + j - a.width;
            else a.x = f.x + f.width - i
        }
    };
    h.prototype._reflowLabels = function(d, e) {
        var g = e.length,
            b, f = this.chart.gridArea,
            h = f.height,
            c = f.y;
        d[0] += 2;
        for (var a = 0; a < g; a++) {
            b = e[a];
            c += d[a];
            b.y = c;
            c += b.height
        }
    };
    h.prototype._takeDistance = function(a, e, c, d) {
        if (a[c] > 0) {
            var b = Math.min(a[c], d);
            d -= b;
            a[c] -= b;
            a[e] += b
        }
        return d
    };
    h.prototype._calculateLabelsMargin = function(e, g) {
        if (this.labelsPosition != "outside") return {
            h: 0,
            v: 0
        };
        for (var a = g.margin, b = this.margin, d = this.labelsExtend, c = 0; c < e.length; c++) {
            var f = e[c];
            a = Math.max(a, f.width + 2 * d);
            b = Math.max(b, f.height + d)
        }
        return {
            h: a,
            v: b
        }
    };
    h.prototype._render = function(b) {
        if (!this.hasRealData || !this.isInScene()) return;
        var c = this._createLabels(b),
            d = this._arrangeLabels(c);
        this._renderSlices(b, d.margins);
        c.labels && a.merge(b, c.labels);
        a.merge(b, d.lines)
    };
    h.prototype._calcRadius = function(b) {
        var f = b.v || this.margin,
            c = this.chart.gridArea,
            e = c.width - 2 * b.h,
            d = c.height - 2 * f,
            a;
        if (e < d) a = e / 2;
        else a = d / 2;
        if (this.expIndexes && this.expIndexes.length > 0) a -= this.explodedRadius;
        return a
    };
    h.prototype.getSliceColor = function(b) {
        var a = this.fillStyles;
        return a && a.length ? a[b % a.length] : this.palette.getColor(b)
    };
    h.prototype.isExploded = function(c) {
        var b = this.expIndexes;
        return !b || !b.length ? false : a.inArray(c, b) != -1
    };
    h.prototype.isOverlap = function(a, c) {
        for (var b = 0; b < c.length; b++) {
            var d = c[b];
            if (d.intersectWith(a.x, a.y, a.width, a.height)) return true
        }
        return false
    };
    h.prototype._getSliceCenter = function(c, d, b, a) {
        return {
            x: c + a * Math.cos(b),
            y: d + a * Math.sin(b)
        }
    };
    h.prototype._getLegendItems = function(c) {
        var d = [];
        if (!this.showInLegend) return d;
        var i = this.arrData;
        if (!i) return d;
        for (var m = this._getYValues(), h, o = i.length, b = 0; b < o; b++) {
            var e = i[b];
            if (e == null) continue;
            var k = a.inArray(b, this.hiddenSlices) == -1,
                p;
            if (a.isArray(e) == false) {
                var n = b + 1;
                h = n.toString()
            } else h = e[0];
            var g = new Z;
            if (k) g.fillStyle = this.getSliceColor(b);
            else g.fillStyle = c.inactiveFillStyle;
            var l = {
                    chart: this.chart,
                    series: this,
                    dataItem: e,
                    index: b,
                    value: m[b]
                },
                f = a.extend(false, {}, c, {
                    context: l,
                    text: h,
                    marker: g
                });
            if (!k) {
                f.textFillStyle = c.inactiveTextFillStyle;
                f.textStrokeStyle = c.inactiveTextStrokeStyle
            }
            var j = new tb(f);
            j.chart = this.chart;
            j.series = this;
            d.push(j)
        }
        return d
    };
    h.prototype._initColors = function(b, a) {
        this.palette = a
    };
    h.prototype._getTooltip = function(a) {
        var e = this.chart.stringFormat(a.percentage, "%.2f%%"),
            c = "<b>" + a.value + " (" + e + ")</b><br/>",
            d = a.dataItem[0];
        if (d) c = b.replaceTextForTooltip(d) + "<br/>" + c;
        return c
    };
    h.prototype._addSliceAnimation = function(c, g, f) {
        var b = this._getAnimation();
        if (!b || b.enabled === false) return;
        var a = new ub(b, c, "endAngle", c.startAngle, c.endAngle),
            d = a.duration / f,
            e = a.delayTime + g * d;
        a.delayTime = e;
        a.duration = d;
        this.chart.storyboard.addAnimation(a)
    };
    h.prototype._hideFromLegend = function(e) {
        var d = e.index,
            b = this.hiddenSlices,
            c = a.inArray(d, b);
        if (c > -1) b.splice(c, 1);
        else b.push(d)
    };
    h.prototype._handleEvent = function(j, i) {
        if (!this.allowExplodeSlices) return;
        var h = i.context,
            f = h.index,
            e = this,
            c = e.expIndexes,
            b = e.chart;
        switch (j) {
            case "MouseDown":
            case "TouchEnd":
                var d = a.inArray(f, c);
                if (d == -1) c.push(f);
                else c.splice(d, 1);
                var g = b.options.animation;
                b.options.animation = null;
                b.update();
                b._processMouseEvents();
                b.options.animation = g
        }
    };
    h.prototype.setOptions = function(a) {
        f.prototype.setOptions.call(this, a);
        if (this.allowExplodeSlices) this.cursor = this.cursor || "pointer"
    };
    h.prototype.getTotal = function() {
        for (var c = this._getYValues(), d = 0, b = 0; b < c.length; b++) {
            if (a.inArray(b, this.hiddenSlices) != -1) continue;
            d += c[b]
        }
        return d
    };
    h.prototype.getPercentage = function(b) {
        var a = this.getTotal();
        return 100 * b / a
    };

    function Wb(b) {
        h.call(this, b);
        var c = a.extend(true, {}, this.defaults, {
            innerExtent: .5,
            outerExtent: 1
        });
        this.defaults = c;
        this.setOptions(b)
    }
    Wb.prototype = new h;
    Wb.constructor = Wb;

    function Qb(b) {
        var c = a.extend(true, {}, this.defaults, {
            markers: {
                type: "diamond"
            }
        });
        this.defaults = c;
        f.call(this, b)
    }
    Qb.prototype = new A;
    Qb.constructor = Qb;
    Qb.prototype._createShape = function(b, a) {
        this._createErrorBars(a);
        return null
    };

    function Rb(a) {
        A.call(this, a)
    }
    Rb.prototype = new A;
    Rb.constructor = Rb;
    Rb.prototype._createShape = function(c, b) {
        this._createErrorBars(b);
        var a = new u(c);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };

    function Pb(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null,
            stepDirection: "forward"
        });
        this.defaults = c;
        A.call(this, b)
    }
    Pb.prototype = new A;
    Pb.constructor = Pb;
    Pb.prototype._createShape = function(e, l) {
        for (var i, g, f, h, c, a = [], j = e.length, k = this.stepDirection == "forward", b = 0; b < j; b += 2) {
            i = e[b];
            g = e[b + 1];
            if (!i || !g) continue;
            a.push(i);
            a.push(g);
            if (b >= j - 2) break;
            f = null;
            h = null;
            for (c = b + 2; c < j; c += 2) {
                f = e[c];
                h = e[c + 1];
                if (f && h) break
            }
            if (c != b + 2 && this.nullHandling == "break") {
                a.push(null);
                a.push(null)
            } else if (f && g)
                if (k) {
                    a.push(f);
                    a.push(g)
                } else {
                    a.push(i);
                    a.push(h)
                }
            b = c - 2
        }
        var d = new q(a);
        d.isStepLine = true;
        this._setShapeSettings(d);
        l.push(d);
        this._addLengthAnimation(d);
        return d
    };

    function Ob(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null,
            stepDirection: "forward"
        });
        this.defaults = c;
        f.call(this, b)
    }
    Ob.prototype = new A;
    Ob.constructor = Ob;
    Ob.prototype._createShape = function(g, l) {
        for (var n, j, c = [], m = g.length, d = 0; d < m; d += 2) {
            n = g[d];
            j = g[d + 1];
            c.push(n);
            c.push(j);
            if (d >= m - 2) break;
            c.push(g[d + 2]);
            c.push(j)
        }
        var k = [];
        a.merge(k, c);
        var e = new q(k);
        e.isStepLine = true;
        this._setShapeSettings(e);
        var i = this.chart.gridArea,
            p = i.y,
            o = i.y + i.height,
            h = this.realYAxis.getCrossingPosition();
        h = b.fitInRange(h, p, o);
        var f = new bb(c, h);
        f.isStepLine = true;
        this._setShapeSettings(f);
        f.lineWidth = 0;
        l.push(f);
        l.push(e);
        this._addLengthAnimation(f);
        this._addLengthAnimation(e);
        return e
    };

    function v(b) {
        var c = a.extend(true, {}, this.defaults, {
            stackedGroupName: ""
        });
        this.defaults = c;
        A.call(this, b);
        this.hasErrorBars = false
    }
    v.prototype = new A;
    v.constructor = v;
    v.prototype._initData = function() {
        this._initStackedData(this.type)
    };
    v.prototype._initVisibleData = function() {
        this._initVisibleStackedData(this.type)
    };
    v.prototype._render = function(t) {
        if (!this.hasRealData || !this.isInScene()) return;
        for (var i = this.arrData, A = this.chart.series._findStackedClusters(this, this.type), m = this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName), v = i.length, n = this.markers != null && this.markers.isVisible(), j = [], r = [], s = [], p = [], x = this.getLabelsOffset(), y = this.xAxisType, k, l, b, g, d, f, c = 0; c < v; c++) {
            b = i[c];
            if (b == null) continue;
            var h = this._getXValue(b, c);
            b = this.dataValues[h];
            if (b == null || b.value == null) continue;
            d = this._scaleValue(m, b.value, h);
            if (y == "CategoryAxis") {
                g = c + .5;
                f = this.realXAxis._getValue(c)
            } else {
                g = i[c][0];
                f = g
            }
            k = this.realXAxis.getPosition(g);
            l = this.realYAxis.getPosition(d);
            j.push(k);
            j.push(l);
            var e = b.actualValue,
                u = this._getStackedTotal(m, h),
                w = e > 0 ? u.positive : u.negative,
                o = w != 0 ? 100 * Math.abs(e) / Math.abs(w) : 0,
                z = {
                    chart: this.chart,
                    series: this,
                    dataItem: b,
                    index: c,
                    x: f,
                    y: d,
                    value: e,
                    percentage: o
                };
            !n && r.push({
                dataItem: b,
                index: c,
                x: f,
                y: d,
                value: e,
                percentage: o
            });
            if (this.realYAxis.isValueVisible(d) === false) continue;
            this._addMarkerAndLabel(s, p, k, l, c, v, null, d, x, z)
        }
        var q = this._createShape(j, t);
        if (!n && q) q.context = {
            chart: this.chart,
            series: this,
            points: r
        };
        else a.merge(t, s);
        return p
    };
    v.prototype._getSeriesFromThisType = function() {
        return this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName)
    };
    v.prototype._getTooltip = function(b) {
        var a = "<b>" + b.value + "</b><br/>";
        if (this.title) {
            var d = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + d + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };
    v.prototype._scaleValue = function(b, a) {
        return a
    };
    v.prototype._createShape = function(c, b) {
        var a = new q(c);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };

    function gb(a) {
        v.call(this, a)
    }
    gb.prototype = new v;
    gb.constructor = gb;
    gb.prototype._createShape = function(c, b) {
        var a = new u(c);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };

    function ob(a) {
        gb.call(this, a)
    }
    ob.prototype = new gb;
    ob.constructor = ob;
    ob.prototype._initVisibleData = function() {
        gb.prototype._initVisibleData.call(this);
        this.min = 0;
        this.max = 100
    };
    ob.prototype._scaleValue = function(a, d, b) {
        var c = this._getStackedTotal(a, b).positive || 1;
        return 100 * d / c
    };
    ob.prototype._getTooltip = function(b) {
        var d = this.chart.stringFormat(b.percentage, "%.2f%%"),
            a = "<b>" + b.value + " (" + d + ")</b><br/>";
        if (this.title) {
            var e = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + e + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };

    function qb(a) {
        v.call(this, a)
    }
    qb.prototype = new v;
    qb.constructor = qb;
    qb.prototype._initVisibleData = function() {
        v.prototype._initVisibleData.call(this);
        this.min = 0;
        this.max = 100
    };
    qb.prototype._scaleValue = function(a, d, b) {
        var c = this._getStackedTotal(a, b).positive || 1;
        return 100 * d / c
    };
    qb.prototype._getTooltip = function(b) {
        var d = this.chart.stringFormat(b.percentage, "%.2f%%"),
            a = "<b>" + b.value + " (" + d + ")</b><br/>";
        if (this.title) {
            var e = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + e + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };

    function R(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null,
            stackedGroupName: ""
        });
        this.defaults = c;
        f.call(this, b)
    }
    R.prototype = new v;
    R.constructor = R;
    R.prototype._render = function(v) {
        if (!this.hasRealData || !this.isInScene()) return;
        for (var i = this.arrData, o = this.chart.series._findStackedClusters(this, this.type), f = this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName), u = this.xAxisType, w = i.length, j = [], k = [], n = [], l, r, s, a, e, d, x, h, b = 0; b < w; b++) {
            a = i[b];
            if (a == null) continue;
            var c = this._getXValue(a, b);
            a = this.dataValues[c];
            if (a == null || a.value == null) continue;
            if (u == "CategoryAxis") {
                e = b + .5;
                h = this.realXAxis._getValue(b)
            } else {
                e = i[b][0];
                h = e
            }
            d = this._scaleValue(f, a.value, c);
            l = this.realXAxis.getPosition(e);
            r = this.realYAxis.getPosition(d);
            j.push(l);
            j.push(r);
            if (o.index != 0) {
                s = this._getPrevStackedPosition(f, o.index, c, 0, this.realYAxis, d >= 0);
                k.push(l);
                k.push(s)
            }
            var g = a.actualValue,
                p = this._getStackedTotal(f, c),
                q = g > 0 ? p.positive : p.negative,
                t = q != 0 ? 100 * Math.abs(g) / Math.abs(q) : 0;
            n.push({
                dataItem: a,
                index: b,
                x: h,
                y: d,
                value: g,
                percentage: t
            })
        }
        var m = this._createShape(j, k, v);
        if (m) m.context = {
            chart: this.chart,
            series: this,
            points: n
        }
    };
    R.prototype._isAnchoredToOrigin = function() {
        return true
    };
    R.prototype._createShape = function(c, d, b) {
        if (!d.length) return eb.prototype._createShape.call(this, c, b);
        var a = new X(c, d, false, true);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };

    function nb(a) {
        R.call(this, a)
    }
    nb.prototype = new R;
    nb.constructor = nb;
    nb.prototype._createShape = function(c, d, b) {
        if (!d.length) return yb.prototype._createShape.call(this, c, b);
        var a = new X(c, d, true, true);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };

    function pb(a) {
        R.call(this, a)
    }
    pb.prototype = new R;
    pb.constructor = pb;
    pb.prototype._initVisibleData = function() {
        v.prototype._initVisibleData.call(this);
        this.min = 0;
        this.max = 100
    };
    pb.prototype._scaleValue = function(a, d, b) {
        var c = this._getStackedTotal(a, b).positive || 1;
        return 100 * d / c
    };
    pb.prototype._getTooltip = function(b) {
        var d = this.chart.stringFormat(b.percentage, "%.2f%%"),
            a = "<b>" + b.value + " (" + d + ")</b><br/>";
        if (this.title) {
            var e = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + e + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };

    function mb(a) {
        nb.call(this, a)
    }
    mb.prototype = new nb;
    mb.constructor = mb;
    mb.prototype._initVisibleData = function() {
        v.prototype._initVisibleData.call(this);
        this.min = 0;
        this.max = 100
    };
    mb.prototype._scaleValue = function(a, d, b) {
        var c = this._getStackedTotal(a, b).positive || 1;
        return 100 * d / c
    };
    mb.prototype._getTooltip = function(b) {
        var d = this.chart.stringFormat(b.percentage, "%.2f%%"),
            a = "<b>" + b.value + " (" + d + ")</b><br/>";
        if (this.title) {
            var e = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + e + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };

    function K(b) {
        var c = a.extend(true, {}, this.defaults, {
            stackedGroupName: ""
        });
        this.defaults = c;
        J.call(this, b);
        this.hasErrorBars = false
    }
    K.prototype = new J;
    K.constructor = K;
    K.prototype._initData = function() {
        this._initStackedData(this.type)
    };
    K.prototype._initVisibleData = function() {
        this._initVisibleStackedData(this.type)
    };
    K.prototype._renderCatData = function(E) {
        var s = this.arrData;
        if (!s) return;
        var r = this.chart.gridArea,
            O = r.y,
            N = r.y + r.height,
            i = this.realYAxis.getCrossingPosition();
        i = b.fitInRange(i, O, N);
        i = Math.round(i);
        var d = this.chart.series._findStackedClusters(this, this.type),
            G = s.length,
            J = r.width / this.realXAxis.getZoom(),
            C = J / G,
            M = C / d.groupCount,
            g = Math.round(this.pointWidth * M),
            I = d.groupCount * g,
            v = (C - I) / 2;
        v = Math.round(v + d.groupIndex * g);
        g = Math.max(g, this.minimumWidth);
        for (var B = [], A = [], t = this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName), K = this.getLabelsOffset(), f, h, e, c = 0; c < G; c++) {
            e = s[c];
            if (e == null) continue;
            var q = this._getXValue(e, c);
            e = this.dataValues[q];
            if (e == null || e.value == null) continue;
            var l = this._scaleValue(t, e.value, q);
            f = c;
            f = Math.round(this.realXAxis.getCatPosition(f) + v);
            h = Math.round(this.realYAxis.getPosition(l));
            var n = this._getPrevStackedPosition(t, d.index, q, i, this.realYAxis, l >= 0),
                w = h,
                o;
            if (h <= n) o = n - h;
            else {
                o = h - n;
                h = n
            }
            var m = e.actualValue,
                F = this._getStackedTotal(t, q),
                H = m > 0 ? F.positive : F.negative,
                y = H != 0 ? 100 * Math.abs(m) / Math.abs(H) : 0,
                u = {
                    chart: this.chart,
                    series: this,
                    dataItem: s[c],
                    index: c,
                    value: m,
                    x: this.realXAxis._getValue(c),
                    y: l,
                    percentage: y
                },
                k = new p(f, h, g, o);
            k.context = u;
            k.center = {
                x: Math.round(f + g / 2),
                y: Math.round(w)
            };
            this._setShapeSettings(k, c);
            E.push(k);
            this._addAnimation(k, d.index, d.count);
            f += g / 2;
            if (this.markers && this.realYAxis.isValueVisible(l)) {
                var D = this._getMarker(f, w, null, l, null, c, u);
                B.push(D);
                this._addShapeAnimation(D, d.index, d.count)
            }
            if (this.labels && this.labels.visible !== false && e.actualValue != 0 && this.realXAxis.isValueVisible(c + .5)) {
                var x = m;
                switch (this.labels.valueType) {
                    case "percentage":
                        x = y
                }
                var z = this.labels.position == "outside",
                    L = z ? K : -o / 2,
                    j = this._getDataPointLabel(e.actualValue, f, w, L, x);
                if (!z) j.textBaseline = "middle";
                j.context = u;
                this.chart.elem.trigger("dataPointLabelCreating", j);
                A.push(j);
                this._addShapeAnimation(j, d.index, d.count)
            }
        }
        a.merge(E, B);
        return A
    };
    K.prototype._renderLinearData = function(C) {
        var h = this.arrData;
        if (!h) return;
        var s = this.chart.gridArea,
            M = s.y,
            J = s.y + s.height,
            i = this.realYAxis.getCrossingPosition();
        i = b.fitInRange(i, M, J);
        i = Math.round(i);
        var e = this.chart.series._findStackedClusters(this, this.type),
            m = this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName),
            L = h.length,
            F = s.width / this.realXAxis.getZoom(),
            I = this._calcColumnScale(m) * F,
            t = this.pointWidth * I;
        t = Math.max(t, this.minimumWidth);
        for (var A = [], z = [], G = this.getLabelsOffset(), f, g, c, d = 0; d < L; d++) {
            c = h[d];
            if (c == null) continue;
            var r = this._getXValue(c, d);
            c = this.dataValues[r];
            if (c == null || c.value == null) continue;
            var l = this._scaleValue(m, c.value, r);
            f = h[d][0];
            f = Math.round(this.realXAxis.getPosition(f));
            g = Math.round(this.realYAxis.getPosition(l));
            var o = this._getPrevStackedPosition(m, e.index, r, i, this.realYAxis, l >= 0),
                v = g,
                q;
            if (g <= o) q = o - g;
            else {
                q = g - o;
                g = o
            }
            var n = c.actualValue,
                D = this._getStackedTotal(m, r),
                E = n > 0 ? D.positive : D.negative,
                x = E != 0 ? 100 * Math.abs(n) / Math.abs(E) : 0,
                u = {
                    chart: this.chart,
                    series: this,
                    dataItem: h[d],
                    index: d,
                    value: n,
                    x: h[d][0],
                    y: l,
                    percentage: x
                },
                K = f - t / 2,
                k = new p(K, g, t, q);
            k.context = u;
            k.center = {
                x: Math.round(f),
                y: Math.round(v)
            };
            this._setShapeSettings(k, d);
            C.push(k);
            this._addAnimation(k, e.index, e.count);
            if (this.markers && this.realYAxis.isValueVisible(l)) {
                var B = this._getMarker(f, v, null, l, null, d, u);
                A.push(B);
                this._addShapeAnimation(B, e.index, e.count)
            }
            if (this.labels && this.labels.visible !== false && c.actualValue != 0) {
                var w = n;
                switch (this.labels.valueType) {
                    case "percentage":
                        w = x
                }
                var y = this.labels.position == "outside",
                    H = y ? G : -q / 2,
                    j = this._getDataPointLabel(c.actualValue, f, v, H, w);
                if (!y) j.textBaseline = "middle";
                j.context = u;
                this.chart.elem.trigger("dataPointLabelCreating", j);
                z.push(j);
                this._addShapeAnimation(j, e.index, e.count)
            }
        }
        a.merge(C, A);
        return z
    };
    K.prototype._getSeriesFromThisType = function() {
        return this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName)
    };
    K.prototype._getTooltip = function(b) {
        var a = "<b>" + b.value + "</b><br/>";
        if (this.title) {
            var d = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + d + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };
    K.prototype._scaleValue = function(b, a) {
        return a
    };

    function H(b) {
        var c = a.extend(true, {}, this.defaults, {
            stackedGroupName: ""
        });
        this.defaults = c;
        s.call(this, b);
        this.hasErrorBars = false
    }
    H.prototype = new s;
    H.constructor = H;
    H.prototype._initData = function() {
        this._initStackedData(this.type)
    };
    H.prototype._initVisibleData = function() {
        this._initVisibleStackedData(this.type)
    };
    H.prototype._renderCatData = function(E) {
        var r = this.arrData;
        if (!r) return;
        var v = this.chart.gridArea,
            G = v.x,
            N = G + v.width,
            j = this.realYAxis.getCrossingPosition();
        j = b.fitInRange(j, G, N);
        j = Math.round(j);
        var d = this.chart.series._findStackedClusters(this, this.type),
            H = r.length,
            K = v.height / this.realXAxis.getZoom(),
            C = K / H,
            O = C / d.groupCount,
            g = Math.round(this.pointWidth * O),
            J = d.groupCount * g,
            u = (C - J) / 2;
        u = Math.round(u + d.groupIndex * g);
        g = Math.max(g, this.minimumWidth);
        for (var B = [], A = [], s = this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName), L = this.getLabelsOffset() + 2, f, i, e, c = 0; c < H; c++) {
            e = r[c];
            if (e == null) continue;
            var o = this._getXValue(e, c);
            e = this.dataValues[o];
            if (e == null || e.value == null) continue;
            var l = this._scaleValue(s, e.value, o);
            f = c;
            i = Math.round(this.realXAxis.getCatPosition(f) - u - g);
            f = Math.round(this.realYAxis.getPosition(l));
            var n = this._getPrevStackedPosition(s, d.index, o, j, this.realYAxis, l >= 0),
                w = f,
                q;
            if (f <= n) q = n - f;
            else {
                q = f - n;
                f = n
            }
            var m = e.actualValue,
                F = this._getStackedTotal(s, o),
                I = m > 0 ? F.positive : F.negative,
                y = I != 0 ? 100 * Math.abs(m) / Math.abs(I) : 0,
                t = {
                    chart: this.chart,
                    series: this,
                    dataItem: r[c],
                    index: c,
                    value: m,
                    x: this.realXAxis._getValue(c),
                    y: l,
                    percentage: y
                },
                k = new p(f, i, q, g);
            k.context = t;
            k.center = {
                x: Math.round(w),
                y: Math.round(i + g / 2)
            };
            this._setShapeSettings(k, c);
            E.push(k);
            this._addAnimation(k, d.index, d.count);
            i += g / 2;
            if (this.markers && this.realYAxis.isValueVisible(l)) {
                var D = this._getMarker(w, i, null, l, null, c, t);
                B.push(D);
                this._addShapeAnimation(D, d.index, d.count)
            }
            if (this.labels && this.labels.visible !== false && e.actualValue != 0 && this.realXAxis.isValueVisible(c + .5)) {
                var x = m;
                switch (this.labels.valueType) {
                    case "percentage":
                        x = y
                }
                var z = this.labels.position == "outside",
                    M = z ? L : -q / 2,
                    h = this._getDataPointLabel(e.actualValue, w, i, M, x);
                if (!z) {
                    h.textBaseline = "middle";
                    h.textAlign = "center"
                }
                h.context = t;
                this.chart.elem.trigger("dataPointLabelCreating", h);
                A.push(h);
                this._addShapeAnimation(h, d.index, d.count)
            }
        }
        a.merge(E, B);
        return A
    };
    H.prototype._renderLinearData = function(C) {
        var h = this.arrData;
        if (!h) return;
        var u = this.chart.gridArea,
            E = u.x,
            L = E + u.width,
            i = this.realYAxis.getCrossingPosition();
        i = b.fitInRange(i, E, L);
        i = Math.round(i);
        var s = this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName),
            f = this.chart.series._findStackedClusters(this, this.type),
            N = h.length,
            G = u.height / this.realXAxis.getZoom(),
            K = this._getSeriesFromThisType(),
            J = this._calcColumnScale(K) * G,
            r = this.pointWidth * J;
        r = Math.max(r, this.minimumWidth);
        for (var A = [], z = [], H = this.getLabelsOffset() + 2, e, l, c, d = 0; d < N; d++) {
            c = h[d];
            if (c == null) continue;
            var o = this._getXValue(c, d);
            c = this.dataValues[o];
            if (c == null || c.value == null) continue;
            var k = this._scaleValue(s, c.value, o);
            e = h[d][0];
            l = Math.round(this.realXAxis.getCatPosition(e));
            e = Math.round(this.realYAxis.getPosition(k));
            var n = this._getPrevStackedPosition(s, f.index, o, i, this.realYAxis, k >= 0),
                v = e,
                q;
            if (e <= n) q = n - e;
            else {
                q = e - n;
                e = n
            }
            var m = c.actualValue,
                D = this._getStackedTotal(s, o),
                F = m > 0 ? D.positive : D.negative,
                x = F != 0 ? 100 * Math.abs(m) / Math.abs(F) : 0,
                t = {
                    chart: this.chart,
                    series: this,
                    dataItem: h[d],
                    index: d,
                    value: m,
                    x: h[d][0],
                    y: k,
                    percentage: x
                },
                M = l - r / 2,
                j = new p(e, M, q, r);
            j.context = t;
            j.center = {
                x: Math.round(v),
                y: Math.round(l)
            };
            this._setShapeSettings(j, d);
            C.push(j);
            this._addAnimation(j, f.index, f.count);
            if (this.markers && this.realYAxis.isValueVisible(k)) {
                var B = this._getMarker(v, l, null, k, null, d, t);
                A.push(B);
                this._addShapeAnimation(B, f.index, f.count)
            }
            if (this.labels && this.labels.visible !== false && c.actualValue != 0) {
                var w = m;
                switch (this.labels.valueType) {
                    case "percentage":
                        w = x
                }
                var y = this.labels.position == "outside",
                    I = y ? H : -q / 2,
                    g = this._getDataPointLabel(c.actualValue, v, l, I, w);
                if (!y) {
                    g.textBaseline = "middle";
                    g.textAlign = "center"
                }
                g.context = t;
                this.chart.elem.trigger("dataPointLabelCreating", g);
                z.push(g);
                this._addShapeAnimation(g, f.index, f.count)
            }
        }
        a.merge(C, A);
        return z
    };
    H.prototype._getSeriesFromThisType = function() {
        return this.chart.series._getStackedSeriesFromType(this.type, this.stackedGroupName)
    };
    H.prototype._getTooltip = function(b) {
        var a = "<b>" + b.value + "</b><br/>";
        if (this.title) {
            var d = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + d + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };
    H.prototype._scaleValue = function(b, a) {
        return a
    };

    function fb(a) {
        K.call(this, a)
    }
    fb.prototype = new K;
    fb.constructor = fb;
    fb.prototype._initVisibleData = function() {
        K.prototype._initVisibleData.call(this);
        this.min = 0;
        this.max = 100
    };
    fb.prototype._getPixelMargins = function(b) {
        if (b.isVertical() || !this.hasData()) return {
            left: 0,
            right: 0
        };
        var e = 4,
            f = this._getSeriesFromThisType(),
            c = this._calcColumnScale(f),
            d = b.length,
            a = .5 * c * d + e;
        return {
            left: a,
            right: a
        }
    };
    fb.prototype._scaleValue = function(a, d, b) {
        var c = this._getStackedTotal(a, b).positive || 1;
        return 100 * d / c
    };
    fb.prototype._getTooltip = function(b) {
        var d = this.chart.stringFormat(b.percentage, "%.2f%%"),
            a = "<b>" + b.value + " (" + d + ")</b><br/>";
        if (this.title) {
            var e = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + e + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };

    function rb(a) {
        H.call(this, a)
    }
    rb.prototype = new H;
    rb.constructor = H;
    rb.prototype._initVisibleData = function() {
        H.prototype._initVisibleData.call(this);
        this.min = 0;
        this.max = 100
    };
    rb.prototype._getPixelMargins = function(b) {
        if (b.isVertical() == false || !this.hasData()) return {
            left: 0,
            right: 0
        };
        var e = 4,
            f = this._getSeriesFromThisType(),
            c = this._calcColumnScale(f),
            d = b.length,
            a = .5 * c * d + e;
        return {
            left: a,
            right: a
        }
    };
    rb.prototype._scaleValue = function(a, d, b) {
        var c = this._getStackedTotal(a, b).positive || 1;
        return 100 * d / c
    };
    rb.prototype._getTooltip = function(b) {
        var d = this.chart.stringFormat(b.percentage, "%.2f%%"),
            a = "<b>" + b.value + " (" + d + ")</b><br/>";
        if (this.title) {
            var e = c.getColorFromFillStyle(this.fillStyle);
            a = '<span style="color:' + e + '">' + this._getTooltipTitle() + "</span>: " + a
        }
        return a
    };

    function G(a) {
        J.call(this, a)
    }
    G.prototype = new J;
    G.constructor = G;
    G.prototype._initXYData = function() {
        this._initXYDataRange(1, 3)
    };
    G.prototype._initCatValueData = function() {
        this._initCatValueDataRange(1, 3)
    };
    G.prototype._initDateValueData = function() {
        this._initDateValueDataRange(1, 3)
    };
    G.prototype._initVisibleCatValueData = function() {
        this._initVisibleCatValueDataRange(1, 3)
    };
    G.prototype._initVisibleXYData = function() {
        this._initVisibleXYDataRange(1, 3)
    };
    G.prototype._processData = function() {
        this._processDataXYZ()
    };
    G.prototype._renderCatData = function(z) {
        var h = this.arrData,
            A = this.chart.gridArea,
            v = this.categories,
            k = v.length,
            x = A.width / this.realXAxis.getZoom(),
            t = x / k,
            f = Math.round(this.pointWidth * t),
            y = Math.round((t - f) / 2);
        f = Math.max(f, this.minimumWidth);
        for (var q = [], n, l, m, d, i, j, b = 0; b < h.length; b++) {
            var e = h[b];
            if (e == null) continue;
            var r = a.inArray(e[0], v);
            if (r > -1) d = r;
            else d = b;
            i = e[1];
            j = e[2];
            n = Math.round(this.realXAxis.getCatPosition(d) + y);
            l = Math.round(this.realYAxis.getPosition(i));
            m = Math.round(this.realYAxis.getPosition(j));
            var s = {
                    chart: this.chart,
                    series: this,
                    dataItem: h[b],
                    index: b,
                    catIndex: d,
                    x: this.realXAxis._getValue(d),
                    from: i,
                    to: j
                },
                w = Math.min(l, m),
                u = Math.abs(l - m),
                g = new p(n, w, f, u);
            g.context = s;
            this._setShapeSettings(g, b);
            z.push(g);
            this._addAnimation(g, b, k);
            if (this.labels && this.labels.visible !== false && this.realXAxis.isValueVisible(b + .5)) {
                var o = e[3];
                if (o === undefined) continue;
                var c = this._getDataPointLabel(undefined, n + f / 2, w + u / 2, 0, o);
                c.textBaseline = "middle";
                c.textAlign = "center";
                c.context = s;
                this.chart.elem.trigger("dataPointLabelCreating", c);
                q.push(c);
                this._addShapeAnimation(c, b, k)
            }
        }
        return q
    };
    G.prototype._renderLinearData = function(x) {
        var h = this.arrData,
            y = this.chart.gridArea,
            k = h.length,
            u = y.width / this.realXAxis.getZoom(),
            w = this._getSeriesFromThisType(),
            v = this._calcColumnScale(w) * u,
            g = this.pointWidth * v;
        g = Math.max(g, this.minimumWidth);
        for (var q = [], n, l, m, f, i, j, b = 0; b < k; b++) {
            var d = h[b];
            if (d == null || a.isArray(d) == false) continue;
            f = d[0];
            i = d[1];
            j = d[2];
            var r = {
                chart: this.chart,
                series: this,
                dataItem: h[b],
                index: b,
                catIndex: b,
                x: f,
                from: i,
                to: j
            };
            n = this.realXAxis.getPosition(f);
            l = Math.round(this.realYAxis.getPosition(i));
            m = Math.round(this.realYAxis.getPosition(j));
            var z = n - g / 2,
                t = Math.min(l, m),
                s = Math.abs(l - m),
                e = new p(z, t, g, s);
            e.context = r;
            this._setShapeSettings(e, b);
            x.push(e);
            this._addAnimation(e, b, k);
            if (this.labels && this.labels.visible !== false && this.realXAxis.isValueVisible(f)) {
                var o = d[3];
                if (o === undefined) continue;
                var c = this._getDataPointLabel(undefined, n, t + s / 2, 0, o);
                c.textBaseline = "middle";
                c.textAlign = "center";
                c.context = r;
                this.chart.elem.trigger("dataPointLabelCreating", c);
                q.push(c);
                this._addShapeAnimation(c, b, k)
            }
        }
        return q
    };
    G.prototype._setIsAnimReversed = function(c) {
        var a = c.context,
            b = this.realYAxis.reversed;
        c.isAnimReversed = !b && a.from < a.to || b && a.from > a.to
    };
    G.prototype._setShapeSettings = function(a) {
        f.prototype._setShapeSettings.call(this, a, a.context.catIndex);
        if (a.context.from <= a.context.to) {
            if (this.priceUpStrokeStyle) a.strokeStyle = this.priceUpStrokeStyle;
            if (this.priceUpFillStyle) a.fillStyle = this.priceUpFillStyle
        } else {
            if (this.priceDownStrokeStyle) a.strokeStyle = this.priceDownStrokeStyle;
            if (this.priceDownFillStyle) a.fillStyle = this.priceDownFillStyle
        }
    };
    G.prototype._getTooltip = function(b) {
        var a = "From: <b>" + b.from.toString() + "</b><br/>To: <b>" + b.to.toString() + "</b>";
        if (this.title) {
            var d = c.getColorFromFillStyle(this.fillStyle);
            a = '<div style="color:' + d + '">' + this._getTooltipTitle() + "</div>" + a
        }
        return a
    };

    function t(a) {
        s.call(this, a)
    }
    t.prototype = new s;
    t.constructor = t;
    t.prototype._initXYData = function() {
        this._initXYDataRange(1, 3)
    };
    t.prototype._initCatValueData = function() {
        this._initCatValueDataRange(1, 3)
    };
    t.prototype._initDateValueData = function() {
        this._initDateValueDataRange(1, 3)
    };
    t.prototype._initVisibleCatValueData = function() {
        this._initVisibleCatValueDataRange(1, 3)
    };
    t.prototype._initVisibleXYData = function() {
        this._initVisibleXYDataRange(1, 3)
    };
    t.prototype._processData = function() {
        this._processDataXYZ()
    };
    t.prototype._renderCatData = function(A) {
        var k = this.arrData,
            g = this.chart.gridArea,
            w = this.categories,
            n = w.length,
            y = g.height / this.realXAxis.getZoom(),
            u = y / n,
            d = this.pointWidth * u,
            z = Math.round((u - d) / 2);
        d = Math.max(d, this.minimumWidth);
        for (var r = [], i, j, o, e, l, m, b = 0; b < k.length; b++) {
            var f = k[b];
            if (f == null) continue;
            var s = a.inArray(f[0], w);
            if (s > -1) e = s;
            else e = b;
            l = f[1];
            m = f[2];
            o = Math.round(this.realXAxis.getCatPosition(e) - z - d);
            i = Math.round(this.realYAxis.getPosition(l));
            j = Math.round(this.realYAxis.getPosition(m));
            if (j < g.x || i > g.x + g.width) continue;
            var t = {
                    chart: this.chart,
                    series: this,
                    dataItem: k[b],
                    index: b,
                    catIndex: e,
                    x: this.realXAxis._getValue(e),
                    from: l,
                    to: m
                },
                x = Math.min(i, j),
                v = Math.abs(i - j),
                h = new p(x, o, v, d);
            h.context = t;
            this._setShapeSettings(h, b);
            A.push(h);
            this._addAnimation(h, b, n);
            if (this.labels && this.labels.visible !== false && this.realXAxis.isValueVisible(b + .5)) {
                var q = f[3];
                if (q === undefined) continue;
                var c = this._getDataPointLabel(undefined, x + v / 2, o + d / 2, 0, q);
                c.textBaseline = "middle";
                c.textAlign = "center";
                c.context = t;
                this.chart.elem.trigger("dataPointLabelCreating", c);
                r.push(c);
                this._addShapeAnimation(c, b, n)
            }
        }
        return r
    };
    t.prototype._renderLinearData = function(y) {
        var k = this.arrData,
            e = this.chart.gridArea,
            n = k.length,
            v = e.height / this.realXAxis.getZoom(),
            x = this._getSeriesFromThisType(),
            w = this._calcColumnScale(x) * v,
            j = this.pointWidth * w;
        j = Math.max(j, this.minimumWidth);
        for (var r = [], h, i, o, g, l, m, b = 0; b < n; b++) {
            var d = k[b];
            if (d == null || a.isArray(d) == false) continue;
            g = d[0];
            l = d[1];
            m = d[2];
            var s = {
                chart: this.chart,
                series: this,
                dataItem: k[b],
                index: b,
                catIndex: b,
                x: g,
                from: l,
                to: m
            };
            o = this.realXAxis.getPosition(g);
            h = Math.round(this.realYAxis.getPosition(l));
            i = Math.round(this.realYAxis.getPosition(m));
            if (i < e.x || h > e.x + e.width) continue;
            var z = o - j / 2,
                u = Math.min(h, i),
                t = Math.abs(h - i),
                f = new p(u, z, t, j);
            f.context = s;
            this._setShapeSettings(f, b);
            y.push(f);
            this._addAnimation(f, b, n);
            if (this.labels && this.labels.visible !== false && this.realXAxis.isValueVisible(g)) {
                var q = d[3];
                if (q === undefined) continue;
                var c = this._getDataPointLabel(undefined, u + t / 2, o, 0, q);
                c.textBaseline = "middle";
                c.textAlign = "center";
                c.context = s;
                this.chart.elem.trigger("dataPointLabelCreating", c);
                r.push(c);
                this._addShapeAnimation(c, b, n)
            }
        }
        return r
    };
    t.prototype._setIsAnimReversed = function(c) {
        var a = c.context,
            b = this.realYAxis.reversed;
        c.isAnimReversed = !b && a.from > a.to || b && a.from < a.to
    };
    t.prototype._setShapeSettings = function(a) {
        f.prototype._setShapeSettings.call(this, a, a.context.catIndex);
        if (a.context.from <= a.context.to) {
            if (this.priceUpStrokeStyle) a.strokeStyle = this.priceUpStrokeStyle;
            if (this.priceUpFillStyle) a.fillStyle = this.priceUpFillStyle
        } else {
            if (this.priceDownStrokeStyle) a.strokeStyle = this.priceDownStrokeStyle;
            if (this.priceDownFillStyle) a.fillStyle = this.priceDownFillStyle
        }
    };
    t.prototype._getTooltip = function(b) {
        var a = "From: <b>" + b.from.toString() + "</b><br/>To: <b>" + b.to.toString() + "</b>";
        if (this.title) {
            var d = c.getColorFromFillStyle(this.fillStyle);
            a = '<div style="color:' + d + '">' + this._getTooltipTitle() + "</div>" + a
        }
        return a
    };

    function V(a) {
        t.call(this, a)
    }
    V.prototype = new t;
    V.constructor = V;
    V.prototype._createYAxis = function() {
        var a = new k({
            location: "bottom",
            orientation: "y"
        });
        a.chart = this.chart;
        return a
    };
    V.prototype._initData = function() {
        t.prototype._initData.call(this);
        var c = this.min,
            b = this.max;
        if (a.type(c) == "date") c = c.getTime();
        if (a.type(b) == "date") b = b.getTime();
        this.min = c;
        this.max = b
    };
    V.prototype._initVisibleData = function() {
        t.prototype._initVisibleData.call(this);
        var c = this.min,
            b = this.max;
        if (a.type(c) == "date") c = c.getTime();
        if (a.type(b) == "date") b = b.getTime();
        this.min = c;
        this.max = b
    };
    V.prototype._findYAxis = function(b) {
        var a = this._findAxis(b, this.axisY);
        if (a != null) return a;
        for (var c = 0; c < b.length; c++) {
            a = b[c];
            if (a.getOrientation(this) != "y" || a.isVertical()) continue;
            if (a instanceof k) return a
        }
        return null
    };
    V.prototype._resolveAxisType = function(a) {
        var b = a.location;
        if (!b) return;
        if (b == "bottom" || b == "top") {
            a.type = "dateTime";
            return
        }
        t.prototype._resolveAxisType.call(this, a)
    };
    V.prototype._getTooltip = function(b) {
        var d = this.realYAxis,
            a = d._getTooltip(b.from).replace("<br/>", "") + " - " + d._getTooltip(b.to);
        if (this.title) {
            var e = c.getColorFromFillStyle(this.fillStyle);
            a = '<div style="color:' + e + '">' + this._getTooltipTitle() + "</div>" + a
        }
        return a
    };

    function F(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 2
        });
        this.defaults = c;
        f.call(this, b)
    }
    F.prototype = new f;
    F.constructor = F;
    F.prototype._initXYData = function() {
        this._initXYDataRange(1, 5)
    };
    F.prototype._initCatValueData = function() {
        this._initCatValueDataRange(1, 5, true)
    };
    F.prototype._initDateValueData = function() {
        this._initDateValueDataRange(1, 5)
    };
    F.prototype._initVisibleCatValueData = function() {
        this._initVisibleCatValueDataRange(1, 5)
    };
    F.prototype._initVisibleXYData = function() {
        this._initVisibleXYDataRange(1, 5)
    };
    F.prototype._processData = function() {
        this.arrData = null;
        if (this.data)
            if (!this.xValuesType && !this.highValuesType && !this.lowValuesType && !this.openValuesType && !this.closeValuesType) this.arrData = b.cloneArray(this.data);
            else {
                this.arrData = [];
                for (var d = 0; d < this.data.length; d++) {
                    var a = this.data[d].slice(0);
                    a[0] = b.processDataValue(a[0], this.xValuesType);
                    a[1] = b.processDataValue(a[1], this.highValuesType);
                    a[2] = b.processDataValue(a[2], this.lowValuesType);
                    a[3] = b.processDataValue(a[3], this.openValuesType);
                    a[4] = b.processDataValue(a[4], this.closeValuesType);
                    this.arrData.push(a)
                }
            }
        else {
            var c = this.chart.arrDataSource;
            if (c) {
                var n = this.xValuesField,
                    k = this.highValuesField,
                    m = this.lowValuesField,
                    l = this.openValuesField,
                    j = this.closeValuesField,
                    i = b.processDataField(c, n),
                    f = b.processDataField(c, k),
                    h = b.processDataField(c, m),
                    g = b.processDataField(c, l),
                    e = b.processDataField(c, j);
                if (i && f && h && g && e) this.arrData = b.mergeArrays([i, f, h, g, e])
            }
        }
        this._processXAxisType();
        this.hasRealData = this.hasData()
    };
    F.prototype._render = function(q) {
        if (!this.hasRealData || !this.isInScene()) return;
        var n = this.arrData,
            r = this.chart.gridArea,
            j = n.length,
            d, g, i, h, e, l = r.width / this.realXAxis.getZoom(),
            f;
        if (this.xAxisType == "CategoryAxis") f = this.pointWidth * l / j;
        else {
            var o = this._calcColumnScale([this]) * l;
            f = this.pointWidth * o
        }
        f = Math.max(f, this.minimumWidth);
        for (var c = 0; c < j; c++) {
            var b = n[c];
            if (b == null || a.isArray(b) == false) continue;
            var m = c;
            switch (this.xAxisType) {
                case "LinearAxis":
                case "DateTimeAxis":
                    d = b[0];
                    m = d;
                    break;
                case "CategoryAxis":
                    d = c + .5
            }
            g = b[1];
            i = b[2];
            h = b[3];
            e = b[4];
            var p = {
                chart: this.chart,
                series: this,
                dataItem: b,
                index: c,
                x: this.realXAxis._getValue(m),
                high: g,
                low: i,
                open: h,
                close: e
            };
            d = this.realXAxis.getPosition(d);
            g = this.realYAxis.getPosition(g);
            i = this.realYAxis.getPosition(i);
            h = this.realYAxis.getPosition(h);
            e = this.realYAxis.getPosition(e);
            var k = this._createShape(d, g, i, h, e, f);
            k.context = p;
            this._addShapeAnimation(k, c, j);
            q.push(k)
        }
    };
    F.prototype._setShapeSettings = function(a) {
        f.prototype._setShapeSettings.call(this, a);
        a.priceDownStrokeStyle = this.priceDownStrokeStyle;
        a.priceUpStrokeStyle = this.priceUpStrokeStyle
    };
    F.prototype._createShape = function(g, d, f, e, b, c) {
        var a = new M(g, d, f, e, b, c);
        this._setShapeSettings(a);
        return a
    };
    F.prototype._getPixelMargins = function(a) {
        if (a.isVertical()) return f.prototype._getPixelMargins.call(this, a);
        if (!this.hasData()) return {
            left: 0,
            right: 0
        };
        var e = 4,
            c = this._calcColumnScale([this]),
            d = a.length,
            b = .5 * c * d + e;
        return {
            left: b,
            right: b
        }
    };
    F.prototype._getTooltip = function(a) {
        var b = "Open: <b>" + a.open.toString() + "</b><br/>High: <b>" + a.high.toString() + "</b><br/>Low: <b>" + a.low.toString() + "</b><br/>Close: <b>" + a.close.toString() + "</b>";
        if (this.title) {
            var d = c.getColorFromFillStyle(this.fillStyle);
            b = '<div style="color:' + d + '">' + this._getTooltipTitle() + "</div>" + b
        }
        return b
    };

    function xb(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 1
        });
        this.defaults = c;
        f.call(this, b)
    }
    xb.prototype = new F;
    xb.constructor = xb;
    xb.prototype._setShapeSettings = function(a) {
        a.priceDownFillStyle = this.priceDownFillStyle || this.fillStyle;
        a.priceUpFillStyle = this.priceUpFillStyle;
        a.strokeStyle = this.strokeStyle;
        a.lineWidth = this.lineWidth;
        a.lineCap = this.lineCap;
        a.lineJoin = this.lineJoin;
        a.miterLimit = this.miterLimit;
        d.setShadows(a, this, this.chart);
        a.cursor = this.cursor
    };
    xb.prototype._createShape = function(g, d, f, e, b, c) {
        var a = new hb(g, d, f, e, b, c);
        this._setShapeSettings(a);
        return a
    };

    function I(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 2,
            markers: {}
        });
        this.defaults = c;
        f.call(this, b);
        this.notInGridArea = true
    }
    I.prototype = new f;
    I.constructor = I;
    I.prototype._resolveAxisType = function() {};
    I.prototype._createXAxis = function() {
        var a = new L;
        a.chart = this.chart;
        return a
    };
    I.prototype._createYAxis = function() {
        var a = new w;
        a.chart = this.chart;
        return a
    };
    I.prototype._findXAxis = function(b) {
        var a = this._findAxis(b, this.axisX);
        if (a != null) return a;
        for (var c = 0; c < b.length; c++) {
            a = b[c];
            if (a instanceof L) return a
        }
        return null
    };
    I.prototype._findYAxis = function(b) {
        var a = this._findAxis(b, this.axisY);
        if (a != null) return a;
        for (var c = 0; c < b.length; c++) {
            a = b[c];
            if (a instanceof w) return a
        }
        return null
    };
    I.prototype._render = function(l) {
        if (!this.hasRealData || !this.isInScene()) return;
        for (var o = this.arrData, p = o.length, i = this.markers != null && this.markers.isVisible(), v = this.labels != null && this.labels.visible !== false, c = [], j = [], n = [], m = [], s = this.getLabelsOffset(), g, h, e, u = this.realYAxis.cx, q = this.realYAxis.cy, d = 0; d < p; d++) {
            var f = o[d];
            if (f === null) {
                c.push(null);
                c.push(null);
                !i && j.push(null);
                continue
            }
            if (a.isArray(f) == false) e = f;
            else {
                e = f[1];
                if (e === null) {
                    c.push(null);
                    c.push(null);
                    !i && j.push(null);
                    continue
                }
            }
            var t = this.realXAxis._getAngle(d);
            g = this.realYAxis.getPosition(e);
            h = q;
            var r = b.rotatePointAt(g, h, t, u, q);
            g = r.x;
            h = r.y;
            c.push(g);
            c.push(h);
            !i && j.push({
                dataItem: f,
                index: d,
                x: this.realXAxis._getValue(d),
                y: e
            });
            this._addMarkerAndLabel(n, m, g, h, d, p, null, e, s)
        }
        var k = this._createShape(c);
        if (k) {
            l.push(k);
            if (!i) k.context = {
                chart: this.chart,
                series: this,
                points: j
            }
        }
        a.merge(l, n);
        a.merge(l, m)
    };
    I.prototype._createShape = function(b) {
        var a = new q(b, false, true);
        this._setShapeSettings(a);
        this._addLengthAnimation(a);
        return a
    };
    I.prototype._getPixelMargins = function() {
        return {
            left: 0,
            right: 0
        }
    };

    function Nb(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null
        });
        this.defaults = c;
        f.call(this, b)
    }
    Nb.prototype = new I;
    Nb.constructor = Nb;
    Nb.prototype._createShape = function(b) {
        var a = new S(b);
        this._setShapeSettings(a);
        return a
    };

    function Eb(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null
        });
        this.defaults = c;
        f.call(this, b)
    }
    Eb.prototype = new I;
    Eb.constructor = Eb;
    Eb.prototype._createShape = function(b) {
        var a = new u(b, true);
        this._setShapeSettings(a);
        return a
    };

    function Ib(a) {
        f.call(this, a)
    }
    Ib.prototype = new I;
    Ib.constructor = Ib;
    Ib.prototype._createShape = function(b) {
        var a = new u(b, true);
        this._setShapeSettings(a);
        a.fillStyle = null;
        return a
    };

    function D(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 2,
            markers: {}
        });
        this.defaults = c;
        f.call(this, b);
        this.notInGridArea = true
    }
    D.prototype = new f;
    D.constructor = D;
    D.prototype._resolveAxisType = function() {};
    D.prototype._createXAxis = function() {
        var a = new C;
        a.chart = this.chart;
        return a
    };
    D.prototype._createYAxis = function() {
        var a = new w;
        a.chart = this.chart;
        return a
    };
    D.prototype._findXAxis = function(b) {
        var a = this._findAxis(b, this.axisX);
        if (a != null) return a;
        for (var c = 0; c < b.length; c++) {
            a = b[c];
            if (a instanceof C) return a
        }
        return null
    };
    D.prototype._findYAxis = function(b) {
        var a = this._findAxis(b, this.axisY);
        if (a != null) return a;
        for (var c = 0; c < b.length; c++) {
            a = b[c];
            if (a instanceof w) return a
        }
        return null
    };
    D.prototype._render = function(m) {
        if (!this.hasRealData || !this.isInScene()) return;
        for (var n = this.arrData, q = n.length, i = this.markers != null && this.markers.isVisible(), w = this.labels != null && this.labels.visible !== false, c = [], j = [], p = [], o = [], t = this.getLabelsOffset(), g, h, e, f, v = this.realYAxis.cx, r = this.realYAxis.cy, d = 0; d < q; d++) {
            var k = n[d];
            if (k === null) {
                c.push(null);
                c.push(null);
                !i && j.push(null);
                continue
            }
            e = k[0];
            f = k[1];
            if (e === null || f === null) {
                c.push(null);
                c.push(null);
                !i && j.push(null);
                continue
            }
            var u = this.realXAxis._getAngle(e);
            g = this.realYAxis.getPosition(f);
            h = r;
            var s = b.rotatePointAt(g, h, u, v, r);
            g = s.x;
            h = s.y;
            c.push(g);
            c.push(h);
            var x = {
                chart: this.chart,
                series: this,
                dataItem: n[d],
                index: d,
                x: e,
                y: f
            };
            !i && j.push({
                dataItem: k,
                index: d,
                x: e,
                y: f
            });
            this._addMarkerAndLabel(p, o, g, h, d, q, e, f, t)
        }
        var l = this._createShape(c);
        if (l) {
            m.push(l);
            if (!i) l.context = {
                chart: this.chart,
                series: this,
                points: j
            }
        }
        a.merge(m, p);
        a.merge(m, o)
    };
    D.prototype._createShape = function(b) {
        var a = new q(b);
        this._setShapeSettings(a);
        this._addLengthAnimation(a);
        return a
    };
    D.prototype._getPixelMargins = function() {
        return {
            left: 0,
            right: 0
        }
    };

    function Mb(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null
        });
        this.defaults = c;
        f.call(this, b)
    }
    Mb.prototype = new D;
    Mb.constructor = Mb;
    Mb.prototype._createShape = function(b) {
        var a = new S(b);
        this._setShapeSettings(a);
        return a
    };

    function Hb(a) {
        f.call(this, a)
    }
    Hb.prototype = new D;
    Hb.constructor = Hb;
    Hb.prototype._createShape = function(b) {
        var a = new u(b, false);
        this._setShapeSettings(a);
        a.fillStyle = null;
        this._addLengthAnimation(a);
        return a
    };

    function Db(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null
        });
        this.defaults = c;
        f.call(this, b)
    }
    Db.prototype = new D;
    Db.constructor = Db;
    Db.prototype._createShape = function(b) {
        var a = new u(b, true);
        this._setShapeSettings(a);
        return a
    };

    function Gb(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: {
                type: "diamond"
            }
        });
        this.defaults = c;
        f.call(this, b)
    }
    Gb.prototype = new D;
    Gb.constructor = Gb;
    Gb.prototype._createShape = function() {
        return null
    };

    function T(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 2,
            trendlineType: "linear",
            markers: {}
        });
        this.defaults = c;
        f.call(this, b)
    }
    T.prototype = new f;
    T.constructor = T;
    T.prototype._initData = function() {
        for (var i = this.tData = this._getTrendlineResult(), f = j, e = m, d = j, c = m, k = i.length, h = 0; h < k; h++) {
            var g = i[h];
            if (g == null) continue;
            var a = g[0];
            if (d > a) d = a;
            if (c < a) c = a;
            var b = g[1];
            if (f > b) f = b;
            if (e < b) e = b
        }
        this.min = f;
        this.max = e;
        this.minX = d;
        this.maxX = c
    };
    T.prototype._getTrendlineResult = function() {
        for (var j = this.arrData, k = j.length, d, f, i = [], c = [], h = [], b = 0; b < k; b++) {
            var e = j[b];
            if (e == null) continue;
            if (a.isArray(e) == false) {
                c.push(b + .5);
                h.push(e)
            } else {
                var g = e[0];
                switch (this.xAxisType) {
                    case "CategoryAxis":
                        g = b + .5;
                        break;
                    case "DateTimeAxis":
                        g = g.getTime()
                }
                c.push(g);
                h.push(e[1])
            }
        }
        switch (this.trendlineType) {
            case "exp":
            case "exponential":
                d = this._getExpRegression(c, h);
                for (var b = 0; b < c.length; b++) {
                    f = d[1] * Math.pow(d[0], c[b]);
                    i.push([c[b], f])
                }
                break;
            case "linear":
            default:
                d = this._getLinearRegression(c, h);
                for (var b = 0; b < c.length; b++) {
                    f = d[0] * c[b] + d[1];
                    i.push([c[b], f])
                }
        }
        return i
    };
    T.prototype._getRegression = function(m, g) {
        var h = this.trendlineType,
            e = this.arrData.length,
            b = 0,
            f = 0,
            i = 0,
            j = 0,
            l = 0,
            d = [],
            c = [];
        if (h == "linear") {
            c = m;
            d = g
        } else if (h == "exp" || h == "exponential")
            for (var a = 0; a < g.length; a++)
                if (g[a] <= 0) e--;
                else {
                    c.push(m[a]);
                    d.push(Math.log(g[a]))
                }
        for (var a = 0; a < e; a++) {
            b = b + c[a];
            f = f + d[a];
            j = j + c[a] * d[a];
            i = i + c[a] * c[a];
            l = l + d[a] * d[a]
        }
        var k = (e * j - b * f) / (e * i - b * b),
            n = (f - k * b) / e;
        return [k, n]
    };
    T.prototype._getLinearRegression = function(a, b) {
        return this._getRegression(a, b)
    };
    T.prototype._getExpRegression = function(d, e) {
        var a = this._getRegression(d, e),
            c = Math.exp(a[0]),
            b = Math.exp(a[1]);
        return [c, b]
    };
    T.prototype._render = function(k) {
        if (!this.hasRealData || !this.isInScene()) return;
        var l = this.arrData,
            c = this.tData,
            b = [],
            g = 1;
        if (this.trendlineType == "linear") g = c.length - 1;
        for (var i, j, e, f, d = 0; d < c.length; d += g) {
            var h = c[d];
            e = h[0];
            f = h[1];
            i = this.realXAxis.getPosition(e);
            j = this.realYAxis.getPosition(f);
            b.push(i);
            b.push(j)
        }
        var a;
        switch (this.trendlineType) {
            case "exp":
            case "exponential":
                a = new u(b);
                break;
            case "linear":
            default:
                a = new q(b)
        }
        this._setShapeSettings(a);
        k.push(a);
        this._addLengthAnimation(a)
    };

    function N(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 2,
            markers: {}
        });
        this.defaults = c;
        f.call(this, b)
    }
    N.prototype = new s;
    N.constructor = N;
    N.prototype._renderCatData = function(n) {
        for (var o = this.arrData, p = o.length, h = this.markers != null && this.markers.isVisible(), c = [], i = [], m = [], k = [], r = this.getLabelsOffset(), g, j, q, d, e = 0; e < p; e++) {
            var f = o[e];
            if (f === null) {
                c.push(null);
                c.push(null);
                !h && i.push(null);
                continue
            }
            q = g = e + .5;
            if (a.isArray(f) == false) d = f;
            else {
                d = f[1];
                if (b.isNull(d)) {
                    c.push(null);
                    c.push(null);
                    !h && i.push(null);
                    continue
                }
            }
            j = this.realXAxis.getPosition(g);
            g = this.realYAxis.getPosition(d);
            c.push(g);
            c.push(j);
            !h && i.push({
                dataItem: f,
                index: e,
                x: q,
                y: d
            });
            if (this.realYAxis.isValueVisible(d) === false) continue;
            this._addMarkerAndLabel(m, k, g, j, e, p, null, d, r)
        }
        var l = this._createShape(c, n);
        if (!h && l) l.context = {
            chart: this.chart,
            series: this,
            points: i
        };
        else a.merge(n, m);
        return k
    };
    N.prototype._renderLinearData = function(o) {
        for (var p = this.arrData, q = p.length, g = this.markers != null && this.markers.isVisible(), s = this.labels != null && this.labels.visible !== false, c = [], h = [], n = [], l = [], r = this.getLabelsOffset(), j, k, e, d, f = 0; f < q; f++) {
            var i = p[f];
            if (i === null) {
                c.push(null);
                c.push(null);
                !g && h.push(null);
                continue
            }
            e = i[0];
            d = i[1];
            if (b.isNull(e) || b.isNull(d)) {
                c.push(null);
                c.push(null);
                !g && h.push(null);
                continue
            }
            k = this.realXAxis.getPosition(e);
            j = this.realYAxis.getPosition(d);
            c.push(j);
            c.push(k);
            !g && h.push({
                dataItem: i,
                index: f,
                x: e,
                y: d
            });
            if (this.realYAxis.isValueVisible(d) === false) continue;
            this._addMarkerAndLabel(n, l, j, k, f, q, e, d, r)
        }
        var m = this._createShape(c, o);
        if (!g && m) m.context = {
            chart: this.chart,
            series: this,
            points: h
        };
        else a.merge(o, n);
        return l
    };
    N.prototype._createShape = function(c, b) {
        this._createErrorBars(b);
        var a = new q(c);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };
    N.prototype._initColors = function(a) {
        this.fillStyle = this.fillStyle || a;
        this.strokeStyle = this.strokeStyle || a
    };
    N.prototype._getPixelMargins = function(a) {
        return f.prototype._getPixelMargins.call(this, a)
    };

    function Lb(a) {
        N.call(this, a)
    }
    Lb.prototype = new N;
    Lb.constructor = Lb;
    Lb.prototype._createShape = function(c, b) {
        this._createErrorBars(b);
        var a = new u(c);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };

    function ib(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null
        });
        this.defaults = c;
        f.call(this, b)
    }
    ib.prototype = new N;
    ib.constructor = ib;
    ib.prototype._createShape = function(i, f) {
        var h = [];
        a.merge(h, i);
        var c = new q(h);
        this._setShapeSettings(c);
        var g = this.chart.gridArea,
            k = g.x,
            j = g.x + g.width,
            e = this.realYAxis.getCrossingPosition();
        e = b.fitInRange(e, k, j);
        var d = new bb(i, e, true);
        this._setShapeSettings(d);
        d.lineWidth = 0;
        f.push(d);
        this._createErrorBars(f);
        f.push(c);
        this._addLengthAnimation(d);
        this._addLengthAnimation(c);
        return c
    };
    ib.prototype._isAnchoredToOrigin = function() {
        return true
    };

    function Fb(a) {
        ib.call(this, a)
    }
    Fb.prototype = new ib;
    Fb.constructor = Fb;
    Fb.prototype._createShape = function(i, f) {
        var h = [];
        a.merge(h, i);
        var d = new u(h);
        this._setShapeSettings(d);
        var g = this.chart.gridArea,
            k = g.x,
            j = g.x + g.width,
            e = this.realYAxis.getCrossingPosition();
        e = b.fitInRange(e, k, j);
        var c = new bb(i, e, true, true);
        this._setShapeSettings(c);
        c.lineWidth = 0;
        f.push(c);
        this._createErrorBars(f);
        f.push(d);
        this._addLengthAnimation(c);
        this._addLengthAnimation(d);
        return d
    };

    function y(b) {
        var c = a.extend(true, {}, this.defaults, {
            lineWidth: 0,
            markers: null
        });
        this.defaults = c;
        f.call(this, b)
    }
    y.prototype = new f;
    y.constructor = y;
    y.prototype._initXYData = function() {
        this._initXYDataRange(1, 3)
    };
    y.prototype._initCatValueData = function() {
        this._initCatValueDataRange(1, 3, true)
    };
    y.prototype._initDateValueData = function() {
        this._initDateValueDataRange(1, 3)
    };
    y.prototype._initVisibleCatValueData = function() {
        this._initVisibleCatValueDataRange(1, 3)
    };
    y.prototype._initVisibleXYData = function() {
        this._initVisibleXYDataRange(1, 3)
    };
    y.prototype._processData = function() {
        this._processDataXYZ()
    };
    y.prototype._renderCatData = function(r) {
        for (var s = this.arrData, k = s.length, j = this.markers != null && this.markers.isVisible(), c = [], d = [], e = [], q = [], o = [], t = this.getLabelsOffset(), i, m, n, l, f, g, b = 0; b < k; b++) {
            var h = s[b];
            if (h === null) {
                c.push(null);
                c.push(null);
                d.push(null);
                d.push(null);
                !j && e.push(null);
                continue
            }
            l = b + .5;
            f = h[1];
            g = h[2];
            i = this.realXAxis.getPosition(l);
            m = this.realYAxis.getPosition(f);
            n = this.realYAxis.getPosition(g);
            c.push(i);
            c.push(m);
            d.push(i);
            d.push(n);
            if (!j) e[b] = e[2 * k - b - 1] = {
                dataItem: h,
                index: b,
                x: l,
                from: f,
                to: g
            };
            this._addMarkersAndLabels(q, o, i, m, n, b, k, null, f, g, t)
        }
        var p = this._createShape(c, d, r);
        if (!j && p) p.context = {
            chart: this.chart,
            series: this,
            points: e
        };
        else a.merge(r, q);
        return o
    };
    y.prototype._renderLinearData = function(r) {
        for (var s = this.arrData, k = s.length, h = this.markers != null && this.markers.isVisible(), u = this.labels != null && this.labels.visible !== false, i = [], j = [], d = [], q = [], o = [], t = this.getLabelsOffset(), g, m, n, l, e, f, b = 0; b < k; b++) {
            var c = s[b];
            if (c === null) {
                pts.push(null);
                pts.push(null);
                !h && d.push(null);
                continue
            }
            l = c[0];
            e = c[1];
            f = c[2];
            g = this.realXAxis.getPosition(l);
            m = this.realYAxis.getPosition(e);
            n = this.realYAxis.getPosition(f);
            i.push(g);
            i.push(m);
            j.push(g);
            j.push(n);
            if (!h) d[b] = d[2 * k - b - 1] = {
                dataItem: c,
                index: b,
                x: l,
                from: e,
                to: f
            };
            this._addMarkersAndLabels(q, o, g, m, n, b, k, null, e, f, t)
        }
        var p = this._createShape(i, j, r);
        if (!h && p) p.context = {
            chart: this.chart,
            series: this,
            points: d
        };
        else a.merge(r, q);
        return o
    };
    y.prototype._createShape = function(c, d, b) {
        var a = new X(c, d);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };
    y.prototype._addMarkersAndLabels = function(g, n, i, q, r, a, h, p, d, e, m) {
        var k = 0,
            o = this.arrData,
            s = p ? p : a;
        if (this.markers && this.markers.isVisible()) {
            var f = {
                chart: this.chart,
                series: this,
                dataItem: o[a],
                index: a,
                x: this.realXAxis._getValue(s),
                from: d,
                to: e
            };
            k = this.markers.offset;
            var l = f.dataItem[3],
                b = this._addMarker(i, q, null, d, l, a, f);
            if (b.marker) {
                b.line && g.push(b.line);
                g.push(b.marker);
                this._addShapeAnimation(b.marker, a, h)
            }
            b = this._addMarker(i, r, null, e, l, a, f);
            if (b.marker) {
                b.line && g.push(b.line);
                g.push(b.marker);
                this._addShapeAnimation(b.marker, a, h)
            }
        }
        if (this.labels && this.labels.visible !== false) {
            var j = this._getLabelValue(d, a),
                c = this._getDataPointLabel(d, i, q, m + k, j, d > e),
                f = {
                    chart: this.chart,
                    series: this,
                    dataItem: o[a],
                    index: a
                };
            c.context = f;
            this.chart.elem.trigger("dataPointLabelCreating", c);
            n.push(c);
            this._addShapeAnimation(c, a, h);
            j = this._getLabelValue(e, a);
            c = this._getDataPointLabel(e, i, r, m + k, j, e > d);
            c.context = f;
            this.chart.elem.trigger("dataPointLabelCreating", c);
            n.push(c);
            this._addShapeAnimation(c, a, h)
        }
    };
    y.prototype._getDataPointLabel = function(j, i, e, c, f, g) {
        var h = this._getLabelText(f),
            b = new l(h);
        d.setShadows(b, this, this.chart);
        a.extend(b, this.labels);
        b.measure(this.chart.ctx);
        b.textAlign = "center";
        b.x = i;
        if (g) {
            b.y = e - c;
            b.textBaseline = "bottom"
        } else {
            b.y = e + c;
            b.textBaseline = "top"
        }
        return b
    };
    y.prototype._getTooltip = function(b) {
        var a = "From: <b>" + b.from.toString() + "</b><br/>To: <b>" + b.to.toString() + "</b>";
        if (this.title) {
            var d = c.getColorFromFillStyle(this.fillStyle);
            a = '<div style="color:' + d + '">' + this._getTooltipTitle() + "</div>" + a
        }
        return a
    };

    function Jb(a) {
        y.call(this, a)
    }
    Jb.prototype = new y;
    Jb.constructor = Jb;
    Jb.prototype._createShape = function(c, d, b) {
        var a = new X(c, d, true);
        this._setShapeSettings(a);
        b.push(a);
        this._addLengthAnimation(a);
        return a
    };

    function r(b, a) {
        this.chart = b;
        a && this.setOptions(a)
    }
    r.prototype.setOptions = function(c) {
        this.clear();
        c = c || {};
        for (var e = [], d = 0; d < c.length; d++) {
            var a = c[d];
            this._resolveType(a);
            var b;
            switch (a.type) {
                case "category":
                    b = new E(a);
                    break;
                case "dateTime":
                    b = new k(a);
                    break;
                case "linearRadius":
                    b = new w(a);
                    break;
                case "categoryAngle":
                    b = new L(a);
                    break;
                case "linearAngle":
                    b = new C(a);
                    break;
                case "linear":
                default:
                    b = new o(a)
            }
            b._setChart(this.chart);
            e.push(b)
        }
        this.userAxes = e
    };
    r.prototype._resolveType = function(a) {
        if (a.type) return;
        var b = this.chart.series.items;
        if (b.length < 1) return;
        b[0]._resolveAxisType(a)
    };
    r.prototype._initSeriesAxes = function() {
        var b = [];
        a.merge(b, this.userAxes);
        for (var e = this.chart.series.items, d = 0; d < e.length; d++) {
            var c = e[d];
            if (!c.isInScene()) continue;
            c._initXAxis(b);
            c._initYAxis(b);
            c._initSharedAxes()
        }
        this.items = b
    };
    r.prototype._initSeries = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c._initSeries()
        }
    };
    r.prototype._initRanges = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c._initRange()
        }
    };
    r.prototype._resetWH = function() {
        for (var c = this.items, b = 0; b < c.length; b++) {
            var a = c[b];
            if (!a.isCustomWidth) {
                a.width = 0;
                a.height = 0
            }
        }
    };
    r.prototype._measure = function() {
        for (var c = this.items, a = false, b = 0; b < c.length; b++) {
            var e = c[b],
                d = e._measure();
            a = a || d
        }
        return a
    };
    r.prototype._arrange = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c._arrange()
        }
    };
    r.prototype._getAxesInLoc = function(e) {
        for (var d = [], b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c.location == e && d.push(c)
        }
        return d
    };
    r.prototype._getVAxes = function() {
        for (var d = [], b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c.isVertical() && d.push(c)
        }
        return d
    };
    r.prototype._getHAxes = function() {
        for (var d = [], b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c.isVertical() == false && d.push(c)
        }
        return d
    };
    r.prototype._getTotalWidth = function() {
        if (this.horCrossingAxis) return 0;
        for (var a = 0, c = this.items, b = 0; b < c.length; b++) {
            var d = c[b];
            if (d.isVertical()) a = a + d.width
        }
        return a
    };
    r.prototype._getTotalHeight = function() {
        if (this.verCrossingAxis) return 0;
        for (var a = 0, c = this.items, b = 0; b < c.length; b++) {
            var d = c[b];
            if (d.isVertical() == false) a = a + d.height
        }
        return a
    };
    r.prototype._render = function(g) {
        for (var f = this.items, e = [], d = [], c = 0; c < f.length; c++) {
            var h = f[c],
                b = h._render(g);
            b.postShapes && a.merge(e, b.postShapes);
            b.contextShapes && a.merge(d, b.contextShapes)
        }
        return {
            postShapes: e,
            contextShapes: d
        }
    };
    r.prototype._updateOrigins = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c._updateOrigin()
        }
    };
    r.prototype._correctOrigins = function() {
        for (var b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c._correctOrigin && c._correctOrigin()
        }
    };
    r.prototype._updateCrossings = function() {};
    r.prototype._initCrossingAxes = function() {};
    r.prototype.getZoomableAxes = function() {
        for (var d = [], b = this.items, a = 0; a < b.length; a++) {
            var c = b[a];
            c.zoomEnabled && d.push(c)
        }
        return d
    };
    r.prototype.find = function(d) {
        var b = this.items;
        if (d != null)
            for (var a = 0; a < b.length; a++) {
                var c = b[a];
                if (c.name == d) return c
            }
        return null
    };
    r.prototype.clear = function() {
        if (!this.items) return;
        a.each(this.items, function() {
            this.clear()
        })
    };

    function n(a) {
        kb.call(this, a)
    }
    n.prototype = new kb;
    n.constructor = n;
    n.prototype._initDefs = function() {
        kb.prototype._initDefs.call(this);
        var b = a.extend(true, {}, this.defaults, {
            rangeSlider: {
                visible: true,
                breadth: 20
            },
            margin: 5,
            strokeStyle: "black",
            lineWidth: 1,
            zoomEnabled: false,
            visible: true
        });
        this.defaults = b
    };
    n.prototype._initSeries = function() {
        for (var c = new x(null, this.chart), d = this.chart.series.items, b = 0; b < d.length; b++) {
            var a = d[b];
            (a.realXAxis == this || a.realYAxis == this) && c.items.push(a)
        }
        this.series = c
    };
    n.prototype._setVisibleRanges = function() {
        kb.prototype._setVisibleRanges.call(this);
        if (!this.jqRangeSlider) return;
        var a = (this.actualMaximum - this.actualMinimum) / 10;
        this.jqRangeSlider.jqRangeSlider("update", {
            minimum: this.actualMinimum,
            maximum: this.actualMaximum,
            smallChange: a,
            largeChange: 2 * a,
            minRange: a / 100,
            range: {
                minimum: this.actualVisibleMinimum,
                maximum: this.actualVisibleMaximum
            }
        })
    };
    n.prototype._arrange = function() {
        kb.prototype._arrange.call(this);
        if (!this.zoomEnabled) {
            this.clear();
            return
        }
        var c = this.rangeSlider.breadth;
        if (this.rangeSlider.visible === false) return;
        this.offset += c;
        var g = this.offset;
        if (!this.jqRangeSlider) {
            var d = a('<div style="position:absolute"></div>').jqRangeSlider({}),
                e = this,
                f = e.chart.options;
            (!f.dataSource || f.dataSource.serverFiltering !== true) && d.bind("rangeChanging", function(b, a) {
                e._sliderZoom(a)
            });
            d.bind("rangeChanged", function(b, a) {
                e._sliderZoom(a)
            });
            this.chart.elem.append(d);
            this.jqRangeSlider = d
        }
        var b;
        switch (this.location) {
            case "left":
                b = {
                    left: this.x + this.width - g,
                    top: this.y,
                    width: c,
                    height: this.height
                };
                break;
            case "right":
                b = {
                    left: this.x + this.lineWidth / 2,
                    top: this.y,
                    width: c,
                    height: this.height
                };
                break;
            case "top":
                b = {
                    left: this.x,
                    top: this.y + this.height - g,
                    width: this.width,
                    height: c
                };
                break;
            case "bottom":
                b = {
                    left: this.x,
                    top: this.y + this.lineWidth / 2,
                    width: this.width,
                    height: c
                }
        }
        if (b) {
            var h = this.isAxisVertical ? "vertical" : "horizontal";
            this.jqRangeSlider.css(b).jqRangeSlider("update", {
                orientation: h,
                reversed: this.reversed
            })
        }
    };
    n.prototype._moveVisibleRange = function(f, g) {
        var e = this.isAxisVertical,
            c = this.actualVisibleMinimum,
            b = this.actualVisibleMaximum,
            d = b - c,
            a = 0;
        if (e) a = -d * g / this.length;
        else a = d * f / this.length;
        if (this.reversed) a = -a;
        a = Math.max(a, this.actualMinimum - c);
        a = Math.min(a, this.actualMaximum - b);
        this.visibleMinimum = c + a;
        this.visibleMaximum = b + a;
        this._setVisibleRanges();
        this._zoom()
    };
    n.prototype._mouseWheelZoom = function(f) {
        var b = this.actualVisibleMinimum,
            a = this.actualVisibleMaximum,
            c = (this.actualMaximum - this.actualMinimum) / 1e3,
            e = f * 50 * c;
        b = Math.max(this.actualMinimum, b + e);
        a = Math.min(this.actualMaximum, a - e);
        if (b > a - c) {
            c /= 2;
            var d = (b + a) / 2;
            b = d - c;
            a = d + c
        }
        this.visibleMinimum = b;
        this.visibleMaximum = a;
        this._setVisibleRanges();
        this._zoom()
    };
    n.prototype._mouseWheelScroll = function(d) {
        var c = this.actualVisibleMinimum,
            b = this.actualVisibleMaximum,
            a = d * (this.actualMaximum - this.actualMinimum) / 20;
        if (c + a < this.actualMinimum) a = this.actualMinimum - c;
        if (b + a > this.actualMaximum) a = this.actualMaximum - b;
        c += a;
        b += a;
        this.visibleMinimum = c;
        this.visibleMaximum = b;
        this._setVisibleRanges();
        this._zoom()
    };
    n.prototype._scaleVisibleRange = function(a, f) {
        var e = this.actualVisibleMinimum,
            d = this.actualVisibleMaximum,
            h = d - e,
            i = this.getZoom(),
            l, b, c, m = this.isAxisVertical;
        if (m) {
            l = a.dy / f.dy;
            var p = f.y1 - a.y1,
                q = f.y2 - a.y2;
            b = -h * q / this.length / i;
            c = -h * p / this.length / i;
            if (a.y1 > a.y2) {
                var j = b;
                b = c;
                c = j
            }
        } else {
            l = a.dx / f.dx;
            var n = f.x1 - a.x1,
                o = f.x2 - a.x2;
            b = h * n / this.length / i;
            c = h * o / this.length / i;
            if (a.x1 > a.x2) {
                var j = b;
                b = c;
                c = j
            }
        }
        if (this.reversed) {
            var j = b;
            b = -c;
            c = -j
        }
        var k = (e + d) / 2,
            g = (this.actualMaximum - this.actualMinimum) / 1e3;
        e = Math.max(this.actualMinimum, e - b);
        d = Math.min(this.actualMaximum, d - c);
        if (e > d - g) {
            g /= 2;
            e = k - g;
            d = k + g
        }
        this.visibleMinimum = e;
        this.visibleMaximum = d;
        this._setVisibleRanges();
        this._zoom()
    };
    n.prototype._scaleToRegion = function(j, k) {
        var c = this.chart.gridArea,
            d = c.fitHor(j.locX),
            f = c.fitVer(j.locY),
            e = c.fitHor(k.locX),
            g = c.fitVer(k.locY),
            i = this.reversed,
            a, b;
        if (this.isAxisVertical)
            if (i) {
                a = Math.min(f, g);
                b = Math.max(f, g)
            } else {
                a = Math.max(f, g);
                b = Math.min(f, g)
            }
        else if (i) {
            a = Math.max(d, e);
            b = Math.min(d, e)
        } else {
            a = Math.min(d, e);
            b = Math.max(d, e)
        }
        a = this.getValue(a);
        b = this.getValue(b);
        var h = (this.actualMaximum - this.actualMinimum) / 1e3;
        if (b - a < h) {
            var l = (h - (b - a)) / 2;
            a -= l;
            b += l
        }
        this.visibleMinimum = a;
        this.visibleMaximum = b;
        this._setVisibleRanges();
        this._zoom()
    };
    n.prototype._sliderZoom = function(a) {
        this.visibleMinimum = this.options.visibleMinimum = a.minimum;
        this.visibleMaximum = this.options.visibleMaximum = a.maximum;
        this.chart.partialDelayedUpdate();
        this._zoom()
    };
    n.prototype._zoom = function() {
        this.chart.elem.trigger("axisZoom", {
            chart: this.chart,
            axis: this
        })
    };
    n.prototype._getTooltip = function(a) {
        return "<b>" + b.replaceTextForTooltip(a) + "</b><br/>"
    };
    n.prototype.getCatPosition = function(a) {
        return kb.prototype.getPosition.call(this, a)
    };
    n.prototype.resetZoom = function() {
        if (!this.zoomEnabled) return;
        this.visibleMinimum = this.actualMinimum;
        this.visibleMaximum = this.actualMaximum;
        this._setVisibleRanges();
        this._zoom()
    };
    n.prototype.clear = function() {
        if (this.jqRangeSlider) {
            this.jqRangeSlider.jqRangeSlider("destroy");
            this.jqRangeSlider.remove();
            this.jqRangeSlider = null
        }
    };

    function E(a) {
        n.call(this, a);
        this.DataType = "CategoryAxis"
    }
    E.prototype = new n;
    E.constructor = E;
    E.prototype.getCategories = function() {
        if (this.categories) return this.categories;
        var a = this.chart.arrDataSource;
        if (!a) return null;
        var c = this.categoriesField,
            d = b.processDataField(a, c);
        return d
    };
    E.prototype._initRange = function() {
        var c = this.series;
        c._initCategories();
        var d = c.categories;
        this.arrCats = this.getCategories();
        var a = d.length;
        if (this.arrCats) a = Math.max(a, this.arrCats.length);
        var f = 0,
            e = a;
        if (!b.isNull(this.minimum)) f = this.minimum;
        if (!b.isNull(this.maximum)) e = this.maximum;
        this.actualMinimum = f;
        this.actualMaximum = e;
        this._setVisibleRanges();
        this.actualInterval = this.interval || 1;
        this.seriesCategories = d
    };
    E.prototype._getLabelIntervals = function(d, c) {
        var e = 0;
        if (c && c.intervalOffset) e = c.intervalOffset;
        for (var f = [], h = Math.round(this.actualVisibleMinimum), g = this._getIntervalStart(h, d) + .5, a = g + e; a <= this.actualVisibleMaximum; a = b.round(a + d)) f.push(a);
        return f
    };
    E.prototype._getIntervalCount = function() {
        return this.arrCats.length
    };
    E.prototype._getValue = function(c) {
        var b = Math.round(c),
            a;
        if (this.arrCats && b < this.arrCats.length) a = this.arrCats[b];
        else a = this.seriesCategories[b];
        return a
    };
    E.prototype.getLabel = function(d) {
        var b;
        if (a.type(d) == "string") b = d;
        else {
            var c = Math.round(d - .5);
            if (this.arrCats && c < this.arrCats.length) b = this.arrCats[c];
            else b = this.seriesCategories[c]
        }
        b = b || "";
        return n.prototype.getLabel.call(this, b)
    };
    E.prototype.getCatPosition = function(a) {
        if (this.reversed) a++;
        return kb.prototype.getPosition.call(this, a)
    };
    E.prototype.getOrientation = function() {
        return "x"
    };

    function o(a) {
        n.call(this, a);
        this.DataType = "LinearAxis"
    }
    o.prototype = new n;
    o.constructor = o;
    o.prototype._initDefs = function() {
        n.prototype._initDefs.call(this);
        var b = a.extend(true, {}, this.defaults, {
            extendRangeToOrigin: false,
            logarithmic: false,
            logBase: 10,
            labels: {
                resolveOverlappingMode: "hide"
            }
        });
        this.defaults = b
    };
    o.prototype._initRange = function() {
        var i = this.series;
        i._initRanges();
        var e, d;
        if (this.getOrientation() == "x") {
            e = i.minX;
            d = i.maxX
        } else {
            e = i.min;
            d = i.max
        }
        if (e == j && d == m) {
            e = 0;
            d = 10
        }
        var r = this._addPlotsInRange(e, d);
        e = r.min;
        d = r.max;
        if (!b.isNull(this.minimum)) e = this.minimum;
        if (!b.isNull(this.maximum)) d = this.maximum;
        var h = Math.abs(d - e),
            u = this.DataType == "DateTimeAxis" ? 1e11 : 1e6;
        if (h < Math.abs(e) / u) h = 0;
        if (this.skipEmptyDays) h -= this.totalEmptyDaysTicks;
        if (h <= 0) {
            h = Math.max(1, d / 10);
            e -= h / 2;
            d += h / 2
        }
        var o = 0,
            n = 0,
            f = i._getPixelMargins(this);
        if (this.isAxisVertical) {
            f.left = b.isNull(this.bottomMargin) ? f.left + .5 : this.bottomMargin;
            f.right = b.isNull(this.topMargin) ? f.right + .5 : this.topMargin
        } else {
            f.left = b.isNull(this.leftMargin) ? f.left + .5 : this.leftMargin;
            f.right = b.isNull(this.rightMargin) ? f.right + .5 : this.rightMargin
        }
        var q = h / this.length;
        o = q * f.left;
        n = q * f.right;
        if (this.logarithmic === true) {
            o = Math.max(0, b.log(o, this.logBase));
            n = Math.max(0, b.log(n, this.logBase))
        }
        var a = e - o,
            c = d + n,
            t = this.series._isAnchoredToOrigin(),
            g = this.getCrossing();
        if (t && this.getOrientation() == "y")
            if (e >= g && a < g) a = g;
            else if (d <= g && c > g) c = g;
        if (this.extendRangeToOrigin)
            if (a > g) a = g;
            else if (c < g) c = g;
        if (this.logarithmic === true) {
            var s = 1;
            if (a < s) a = s;
            a = b.log(a, this.logBase);
            c = b.log(c, this.logBase);
            var l = this._calculateActualIntervalLogarithmic(a, c);
            a = b.round(Math.floor(a / l) * l);
            c = b.round(Math.ceil(c / l) * l)
        }
        var p = this.minimumRange;
        if (p) {
            var k = c - a;
            if (p > k) {
                k = (p - k) / 2;
                a -= k;
                c += k
            }
        }
        this._setMinMax(a, c);
        this._setVisibleRanges();
        if (this.logarithmic === true) this.actualInterval = this._calculateActualIntervalLogarithmic(this.actualVisibleMinimum, this.actualVisibleMaximum);
        else this.actualInterval = this._calculateActualInterval(this.actualVisibleMinimum, this.actualVisibleMaximum)
    };
    o.prototype._addPlotsInRange = function(c, a) {
        var f = this.plotLines;
        if (f)
            for (var d = 0; d < f.length; d++) {
                var i = f[d].value;
                if (!b.isNull(i)) {
                    c = Math.min(c, i);
                    a = Math.max(a, i)
                }
            }
        var e = this.plotBands;
        if (e)
            for (var d = 0; d < e.length; d++) {
                var g = e[d].from,
                    h = e[d].to;
                if (!b.isNull(g)) {
                    c = Math.min(c, g);
                    a = Math.max(a, g)
                }
                if (!b.isNull(h)) {
                    c = Math.min(c, h);
                    a = Math.max(a, h)
                }
            }
        return {
            min: c,
            max: a
        }
    };
    o.prototype._calculateActualIntervalLogarithmic = function(e, d) {
        if (this.interval) return this.interval;
        var c = (d - e) / 3,
            a = Math.floor(b.log10(Math.abs(c)));
        if (a == 0) a = 1;
        return b.round(Math.floor(c / a) * a)
    };
    o.prototype._getIntervals = function(c, a, h) {
        if (this.customTickMarks) return this.customTickMarks;
        if (this.logarithmic === false) return n.prototype._getIntervals.call(this, c, a);
        if (h === false) return this._getLogarithmicMinorIntervals(c, a);
        var e = 0;
        if (a && a.intervalOffset) e = a.intervalOffset;
        for (var f = [], g = this._getIntervalStart(this._getActualVisibleMinimum(), c), d = g + e; d <= this._getActualVisibleMaximum(); d = b.round(d + c)) f.push(Math.pow(this.logBase, d));
        return f
    };
    o.prototype._getLogarithmicMinorIntervals = function(m, k) {
        for (var l = this._getMarkInterval(k.major, true), h = this._getIntervals(l, k.major, true), j = [], d = null, g = 0; g < h.length; g++) {
            var f = h[g];
            if (d == null) {
                d = f;
                continue
            }
            var a = d,
                c = f;
            if (a < c) {
                var n = a;
                a = c;
                c = n
            }
            var i = (a - c) * m / 10,
                e = c + i;
            while (e < a) {
                j.push(b.round(e));
                e += i
            }
            d = f
        }
        return j
    };
    o.prototype._getIntervalCount = function() {
        return Math.ceil(this._getActualMaximum() - this._getActualMinimum())
    };
    o.prototype.getCrossingPosition = function() {
        return this.getPosition(this.getCrossing())
    };
    o.prototype.getOrientation = function(b) {
        var a = this.isVertical();
        if (this.series)
            for (var c = 0; c < this.series.items.length; c++) b = this.series.items[c];
        if (b && b.isVertical) a = !a;
        return a ? "y" : "x"
    };
    o.prototype.getPosition = function(a) {
        if (this.logarithmic == true) a = b.log(a, this.logBase);
        var c = n.prototype.getPosition.call(this, a);
        return c
    };

    function k(a) {
        o.call(this, a);
        this.DataType = "DateTimeAxis"
    }
    k.prototype = new o;
    k.constructor = k;
    k.prototype._initDefs = function() {
        o.prototype._initDefs.call(this);
        var b = a.jqChartDateFormat.masks,
            c = a.extend(true, {}, this.defaults, {
                labels: {
                    yearsIntervalStringFormat: "yyyy",
                    monthsIntervalStringFormat: b.shortDate,
                    weeksIntervalStringFormat: b.shortDate,
                    daysIntervalStringFormat: b.shortDate,
                    hoursIntervalStringFormat: b.shortDate + " " + b.shortTime,
                    minutesIntervalStringFormat: b.shortTime,
                    secondsIntervalStringFormat: b.longTime,
                    millisecondsIntervalStringFormat: b.longTime
                },
                skipEmptyDays: false
            });
        this.defaults = c
    };
    k.prototype._initRange = function() {
        if (this.skipEmptyDays) {
            this.emptyDays = this._getEmptyDays();
            this.totalEmptyDaysTicks = this.emptyDays.length * g.ticksInDay
        } else this.totalEmptyDaysTicks = 0;
        o.prototype._initRange.call(this);
        this._initActualStringFormat()
    };
    k.prototype._setMinMax = function(c, b) {
        if (this.minimum != null)
            if (a.type(this.minimum) == "date") this.actualMinimum = this.minimum.getTime();
            else this.actualMinimum = this.minimum;
        else this.actualMinimum = c;
        if (this.maximum != null)
            if (a.type(this.minimum) == "date") this.actualMaximum = this.maximum.getTime();
            else this.actualMaximum = this.maximum;
        else this.actualMaximum = b
    };
    k.prototype._calculateActualInterval = function(c, b) {
        var a = this._calculateDateTimeInterval(c, b);
        if (this.intervalType != null) this.actualIntervalType = this.intervalType;
        else this.actualIntervalType = this.type;
        if (this.interval != null) a = this.interval;
        return a
    };
    k.prototype._calculateDateTimeInterval = function(j, i) {
        var h = i - j,
            f = .8 * this.maxInter200Px,
            g = Math.max(1, this.length),
            e = g / (200 * 10 / f),
            b = h / e;
        this.type = "year";
        var a = b / (1e3 * 60);
        if (a <= 1) {
            if (b <= 10) {
                this.type = "milliseconds";
                return 1
            }
            if (b <= 50) {
                this.type = "milliseconds";
                return 4
            }
            if (b <= 200) {
                this.type = "milliseconds";
                return 20
            }
            if (b <= 500) {
                this.type = "milliseconds";
                return 50
            }
            var c = b / 1e3;
            if (c <= 7) {
                this.type = "seconds";
                return 1
            }
            if (c <= 15) {
                this.type = "seconds";
                return 2
            }
            if (c <= 30) {
                this.type = "seconds";
                return 5
            }
            if (c <= 60) {
                this.type = "seconds";
                return 10
            }
        } else if (a <= 2) {
            this.type = "seconds";
            return 20
        }
        if (a <= 3) {
            this.type = "seconds";
            return 30
        }
        if (a <= 10) {
            this.type = "minutes";
            return 1
        }
        if (a <= 20) {
            this.type = "minutes";
            return 2
        }
        if (a <= 60) {
            this.type = "minutes";
            return 5
        }
        if (a <= 120) {
            this.type = "minutes";
            return 10
        }
        if (a <= 180) {
            this.type = "minutes";
            return 30
        }
        if (a <= 60 * 12) {
            this.type = "hours";
            return 1
        }
        if (a <= 60 * 24) {
            this.type = "hours";
            return 4
        }
        if (a <= 60 * 24 * 2) {
            this.type = "hours";
            return 6
        }
        if (a <= 60 * 24 * 3) {
            this.type = "hours";
            return 12
        }
        if (a <= 60 * 24 * 10) {
            this.type = "days";
            return 1
        }
        if (a <= 60 * 24 * 20) {
            this.type = "days";
            return 2
        }
        if (a <= 60 * 24 * 30) {
            this.type = "days";
            return 3
        }
        if (a <= 60 * 24 * 30.5 * 2) {
            this.type = "weeks";
            return 1
        }
        if (a <= 60 * 24 * 30.5 * 5) {
            this.type = "weeks";
            return 2
        }
        if (a <= 60 * 24 * 30.5 * 12) {
            this.type = "months";
            return 1
        }
        if (a <= 60 * 24 * 30.5 * 24) {
            this.type = "months";
            return 3
        }
        if (a <= 60 * 24 * 30.5 * 48) {
            this.type = "months";
            return 6
        }
        this.type = "years";
        var d = a / 60 / 24 / 365;
        return d < 5 ? 1 : d < 10 ? 2 : Math.floor(d / 5)
    };
    k.prototype._getNextPosition = function(b, a) {
        return this._incrementDateTime(b, a, this.actualIntervalType)
    };
    k.prototype._incrementDateTime = function(h, b, c) {
        var a = new Date(h),
            d = 0;
        if (c == "days") a = g.addDays(a, b);
        else if (c == "hours") d = g.fromHours(b);
        else if (c == "milliseconds") d = b;
        else if (c == "seconds") d = g.fromSeconds(b);
        else if (c == "minutes") d = g.fromMinutes(b);
        else if (c == "weeks") a = g.addDays(a, 7 * b);
        else if (c == "months") {
            var e = false;
            if (a.getDate() == g.getDaysInMonth(a.getFullYear(), a.getMonth())) e = true;
            a = g.addMonths(a, Math.floor(b));
            d = g.fromDays(30 * (b - Math.floor(b)));
            if (e && d == 0) {
                var f = g.getDaysInMonth(a.getFullYear(), a.getMonth());
                a = g.addDays(a, f - a.getDate())
            }
        } else if (c == "years") {
            a = g.addYears(a, Math.floor(b));
            d = g.fromDays(365 * (b - Math.floor(b)))
        }
        return a.getTime() + d
    };
    k.prototype._getIntervalStart = function(j, b, d) {
        if (d == null) return j;
        var a = new Date(j);
        if (b > 0 && b != 1)
            if (d == "months" && b <= 12 && b > 1) {
                var i = a,
                    c = new Date(a.getFullYear(), 0, 1, 0, 0, 0);
                while (c < a) {
                    i = c;
                    c = g.addMonths(c, b)
                }
                a = i;
                return a.getTime()
            }
        switch (d) {
            case "years":
                var f = a.getFullYear() / b * b;
                if (f <= 0) f = 1;
                a = new Date(f, 0, 1, 0, 0, 0);
                break;
            case "months":
                var e = a.getMonth() / b * b;
                if (e < 0) e = 0;
                a = new Date(a.getFullYear(), e, 1, 0, 0, 0);
                break;
            case "days":
                var h = a.getDate() / b * b;
                if (h <= 0) h = 1;
                a = new Date(a.getFullYear(), a.getMonth(), h, 0, 0, 0);
                break;
            case "hours":
                var n = a.getHours() / b * b;
                a = new Date(a.getFullYear(), a.getMonth(), a.getDate(), n, 0, 0);
                break;
            case "minutes":
                var l = a.getMinutes() / b * b;
                a = new Date(a.getFullYear(), a.getMonth(), a.getDate(), a.getHours(), l, 0);
                break;
            case "seconds":
                var m = a.getSeconds() / b * b;
                a = new Date(a.getFullYear(), a.getMonth(), a.getDate(), a.getHours(), a.getMinutes(), m, 0);
                break;
            case "milliseconds":
                var k = a.getMilliseconds() / b * b;
                a = new Date(a.getFullYear(), a.getMonth(), a.getDate(), a.getHours(), a.getMinutes(), a.getSeconds(), k);
                break;
            case "weeks":
                a = new Date(a.getFullYear(), a.getMonth(), a.getDate(), 0, 0, 0);
                a = g.addDays(a, -g.getDayOfWeek(a))
        }
        return a.getTime()
    };
    k.prototype._initActualStringFormat = function() {
        if (!this.labels || this.labels.visible === false) return;
        if (this.labels.stringFormat) {
            this.actualStringFormat = this.labels.stringFormat;
            return
        }
        switch (this.actualIntervalType) {
            case "years":
                this.actualStringFormat = this.labels.yearsIntervalStringFormat;
                break;
            case "months":
                this.actualStringFormat = this.labels.monthsIntervalStringFormat;
                break;
            case "weeks":
                this.actualStringFormat = this.labels.weeksIntervalStringFormat;
                break;
            case "days":
                this.actualStringFormat = this.labels.daysIntervalStringFormat;
                break;
            case "hours":
                this.actualStringFormat = this.labels.hoursIntervalStringFormat;
                break;
            case "minutes":
                this.actualStringFormat = this.labels.minutesIntervalStringFormat;
                break;
            case "seconds":
                this.actualStringFormat = this.labels.secondsIntervalStringFormat;
                break;
            case "milliseconds":
                this.actualStringFormat = this.labels.millisecondsIntervalStringFormat;
                break;
            default:
                this.actualStringFormat = "default"
        }
    };
    k.prototype._getIntervals = function(e, c) {
        if (this.customTickMarks) return this.customTickMarks;
        var g = [],
            h = this.actualVisibleMinimum,
            j = this.actualVisibleMaximum,
            a = this._getIntervalStart(h, e, this.actualIntervalType);
        while (a < h) a = this._incrementDateTime(a, e, this.actualIntervalType);
        if (c && c.intervalOffset) {
            var f = this.actualIntervalType,
                i = c.intervalOffset;
            if (c.intervalOffsetType) f = c.intervalOffsetType;
            a = this._incrementDateTime(a, i, f)
        }
        for (var b = a; b <= j; b = this._incrementDateTime(b, e, this.actualIntervalType)) {
            var d = this._getNextNonEmptyDay(b);
            if (d) {
                if (this.skipEmptyDays && b < d) b = d;
                g.push(d)
            }
        }
        return g
    };
    k.prototype._getIntervalCount = function() {
        var a = this._getActualMaximum() - this._getActualMinimum();
        a = Math.ceil(a / g.ticksInDay);
        return a
    };
    k.prototype._getNextNonEmptyDay = function(b) {
        if (!this.emptyDays) return b;
        var d = a.inArray(b, this.emptyDays);
        if (d == -1) return b;
        for (var f = g.addDays(new Date(b), 1), h = this.actualVisibleMaximum, c = f; c <= h; c = g.addDays(c, 1)) {
            var e = c.getTime();
            d = a.inArray(e, this.emptyDays);
            if (d == -1) return e
        }
        return null
    };
    k.prototype._getEmptyDaysOffset = function(c) {
        if (!this.emptyDays) return 0;
        for (var b = 0, a = 0; a < this.emptyDays.length; a++) {
            var d = this.emptyDays[a];
            if (d < c) b++;
            else break
        }
        return b * g.ticksInDay
    };
    k.prototype._addEmptyDaysOffset = function(c) {
        if (b.isNull(c)) return null;
        var a = c + this._getEmptyDaysOffset(c),
            d = this._getEmptyDaysOffset(a) - this._getEmptyDaysOffset(c);
        c = a;
        a += d;
        while (d) {
            d = this._getEmptyDaysOffset(a) - this._getEmptyDaysOffset(c);
            c = a;
            a += d
        }
        return a
    };
    k.prototype._getEmptyDays = function() {
        for (var b = [], a = 0; a < this.series.items.length; a++) {
            var c = this.series.items[a];
            if (a == 0) b = this._getEmptyDaysFromSeries(c);
            else this._excludeDaysFromSeries(c, b)
        }
        return b
    };
    k.prototype._getEmptyDaysFromSeries = function(i) {
        var f = [],
            b = [];
        a.merge(b, i.arrData);
        b.sort(function(a, b) {
            return a[0] - b[0]
        });
        for (var c = g.roundToDay(b[0][0]), d = 1; d < b.length; d++) {
            for (var h = g.roundToDay(b[d][0]), j = (h - c) / g.ticksInDay, e = 1; e < j; e++) f.push(g.addDays(c, e).getTime());
            c = h
        }
        return f
    };
    k.prototype._excludeDaysFromSeries = function(c, b) {
        a.each(c.arrData, function(f, d) {
            var e = g.roundToDay(d[0]).getTime(),
                c = a.inArray(e, b);
            c != -1 && b.splice(c, 1)
        })
    };
    k.prototype._getTooltip = function(b) {
        var c = a.jqChartDateFormat.masks,
            d = "";
        if (b.getSeconds() != 0) d += c.shortDate + " " + c.longTime;
        else if (b.getHours() != 0 || b.getMinutes() != 0) d += c.shortDate + " " + c.shortTime;
        else d = c.shortDate;
        b = this.chart.stringFormat(b, d);
        return "<b>" + b + "</b><br/>"
    };
    k.prototype._getActualVisibleMinimum = function() {
        var a = this.actualVisibleMinimum;
        return a - this._getEmptyDaysOffset(a)
    };
    k.prototype._getActualVisibleMaximum = function() {
        var a = this.actualVisibleMaximum;
        return a - this._getEmptyDaysOffset(a)
    };
    k.prototype._getActualMinimum = function() {
        var a = this.actualMinimum;
        return a - this._getEmptyDaysOffset(a)
    };
    k.prototype._getActualMaximum = function() {
        var a = this.actualMaximum;
        return a - this._getEmptyDaysOffset(a)
    };
    k.prototype.getPosition = function(b) {
        if (a.type(b) == "date") b = b.getTime();
        var c = 0;
        if (this.skipEmptyDays) c = this._getEmptyDaysOffset(b);
        var d = o.prototype.getPosition.call(this, b - c);
        return d
    };
    k.prototype.getLabel = function(b) {
        if (!this.labels || this.labels.visible === false || !this.actualStringFormat) return;
        var c = new Date(b);
        return a.jqChartDateFormatter(c, this.actualStringFormat)
    };

    function w(a) {
        o.call(this, a);
        this.DataType = "LinearRadiusAxis"
    }
    w.prototype = new o;
    w.constructor = w;
    w.prototype._initDefs = function() {
        o.prototype._initDefs.call(this);
        var b = a.extend(true, {}, this.defaults, {
            innerExtent: .2,
            renderStyle: "circle",
            majorTickMarks: {
                visible: false
            },
            location: "radial",
            majorGridLines: {
                strokeStyle: "gray",
                lineWidth: 1,
                visible: true
            }
        });
        this.defaults = b
    };
    w.prototype.getOrientation = function() {
        return "y"
    };
    w.prototype._measure = function() {
        this.width = 0;
        this.height = 0;
        return false
    };
    w.prototype._arrange = function() {
        this._initRadialMeasures()
    };
    w.prototype._updateOrigin = function() {
        var a = this.innerExtent * this.radius;
        this.origin = this.cx + a;
        this.length = this.radius - a;
        this.extent = a
    };
    w.prototype._getLabels = function() {
        var a = this.labels;
        if (a == null || a.visible === false) return [];
        for (var m = this._getMaxOutsideTickMarksLength() + this.lblMargin, e = [], n = this._getMarkInterval(a, true), f = this._getLabelIntervals(n, a), g = f.length, j = a.showFirstLabel, k = a.showLastLabel, c = 0; c < g; c++) {
            if (!j && c == 0 || !k && c == g - 1) continue;
            var h = f[c],
                l = this.getLabel(h),
                d = this._createLabel(l, a),
                o = this.getPosition(h),
                q = this.cy,
                p = o,
                i = b.rotatePointAt(p, q, -Math.PI / 2, this.cx, this.cy);
            d.x = i.x - m;
            d.y = i.y;
            d.textAlign = "right";
            e.push(d)
        }
        return e
    };
    w.prototype._getTickMarks = function(a, l) {
        if (a == null || a.visible != true) return [];
        for (var h = [], s = this._getMarkInterval(a, l), t = a.length, k = this._getIntervals(s, a, l), g = this.sharedAxis, f = g._getIntervals(g.actualInterval), p, r, q, c, d = 0; d < k.length; d++) {
            var u = this.getPosition(k[d]);
            p = q = u;
            c = this.cy;
            r = c - t;
            for (var e = 0; e < f.length; e++) {
                var v = f[e],
                    j = this.sharedAxis._getAngle(v),
                    n = b.rotatePointAt(p, r, j, this.cx, this.cy),
                    o = b.rotatePointAt(q, c, j, this.cx, this.cy),
                    m = new i(n.x, n.y, o.x, o.y);
                a._setLineSettings(m);
                h.push(m)
            }
        }
        return h
    };
    w.prototype._getGridLines = function(a, g) {
        if (a == null || a.visible != true) return [];
        for (var d = [], h = this._getMarkInterval(a, g), e = this._getIntervals(h, a, true), c = 0; c < e.length; c++) {
            var f = e[c],
                b = this._getRenderShape(f);
            a._setLineSettings(b);
            b.fillStyle = null;
            d.push(b)
        }
        return d
    };
    w.prototype._render = function(d) {
        var g = this._getGridLines(this.minorGridLines, false);
        a.merge(d, g);
        var f = this._getGridLines(this.majorGridLines, true);
        a.merge(d, f);
        var c = [],
            i = this._getTickMarks(this.minorTickMarks, false);
        a.merge(c, i);
        var h = this._getTickMarks(this.majorTickMarks, true);
        a.merge(c, h);
        var b = this._getRenderShape(this.actualMinimum);
        b.strokeStyle = this.strokeStyle;
        b.lineWidth = this.lineWidth;
        b.strokeDashArray = this.strokeDashArray;
        b.fillStyle = null;
        d.push(b);
        var e = this._getLabels();
        a.merge(c, e);
        return {
            postShapes: c,
            contextShapes: e
        }
    };
    w.prototype._getRenderShape = function(b) {
        var d = this.getPosition(b),
            a = d - this.origin + this.extent,
            c = this._createRenderShape(this.cx - a, this.cy - a, a);
        return c
    };
    w.prototype._createRenderShape = function(f, g, e) {
        var a = this.sharedAxis;
        if (this.renderStyle != "polygon") {
            if (!b.isNull(a.startAngle) && !b.isNull(a.endAngle)) {
                var h = b.radians(a.startAngle),
                    i = b.radians(a.endAngle),
                    m = i - h;
                if (m < 2 * Math.PI - .000125) {
                    var l = h - Math.PI / 2,
                        n = i - Math.PI / 2;
                    return new Cb(f, g, e, l, n)
                }
            }
            return new Y(f, g, e)
        }
        for (var c = [], j = this.sharedAxis.actualMaximum, o = 2 * Math.PI / j, f = this.cx, g = this.cy - e, d = 0; d < j; d++) {
            var k = b.rotatePointAt(f, g, d * o, this.cx, this.cy);
            c.push(k.x);
            c.push(k.y)
        }
        return new S(c)
    };

    function L(a) {
        n.call(this, a);
        this.DataType = "CategoryAngleAxis"
    }
    L.prototype = new E;
    L.constructor = L;
    L.prototype._initDefs = function() {
        n.prototype._initDefs.call(this);
        var b = a.extend(true, {}, this.defaults, {
            strokeStyle: "gray",
            renderLinesOverGraph: true,
            location: "radial"
        });
        this.defaults = b
    };
    L.prototype._measure = function() {
        this.width = 0;
        this.height = 0;
        return false
    };
    L.prototype._arrange = function() {
        this._initRadialMeasures()
    };
    L.prototype._updateOrigin = function() {
        this.origin = this.cx;
        this.length = 2 * Math.PI * this.radius
    };
    L.prototype._correctOrigin = function() {
        for (var a = 0, j = this.x, k = this.y, i = this.x + this.width, f = this.y + this.height, g = this._getLabels(), c = 0; c < g.length; c++) {
            var b = g[c];
            if (b.x < j) a = Math.max(a, j - b.x);
            var e = b.x + b.width;
            if (e > i) a = Math.max(a, e - i);
            var h = b.y - b.height / 2;
            if (h < k) a = Math.max(a, k - h);
            var d = b.y + b.height / 2;
            if (d > f) a = Math.max(a, d - f)
        }
        this.radius -= a;
        this.length = 2 * Math.PI * this.radius;
        if (this.sharedAxis) {
            this.sharedAxis.radius = this.radius;
            this.sharedAxis._updateOrigin();
            this.sharedAxis._initRange()
        }
    };
    L.prototype._getAngle = function(b) {
        var d = this.actualMaximum,
            c = 2 * Math.PI / d,
            a = b * c;
        if (this.reversed === true) a = 2 * Math.PI - a;
        return a - Math.PI / 2
    };
    L.prototype._getLabels = function() {
        var f = this.labels;
        if (f == null || f.visible === false) return [];
        var g = this.actualMaximum;
        if (g == 0) return;
        for (var i = this.cx, j = this.cy, n = i, o = j - this.radius, m = 2 * Math.PI / g, c = 8, h = [], f = this.labels, e = 0; e < g; e++) {
            var d = e * m,
                k = b.rotatePointAt(n, o, d, i, j),
                l = this.getLabel(e),
                a = this._createLabel(l, f);
            a.x = k.x;
            a.y = k.y;
            if (d == Math.PI) {
                a.x -= a.width / 2;
                a.y += c
            } else if (d == 0) {
                a.x -= a.width / 2;
                a.y -= c
            } else if (d > Math.PI) a.x -= a.width + c;
            else a.x += c;
            h.push(a)
        }
        return h
    };
    L.prototype._render = function(n) {
        var f = [],
            g = this.actualMaximum;
        if (g == 0) return;
        for (var d = this.cx, e = this.cy, q = d, s = e - this.sharedAxis.extent, r = d, t = e - this.radius, o = this.renderLinesOverGraph, p = 2 * Math.PI / g, h = 0; h < g; h++) {
            var k = h * p,
                l = b.rotatePointAt(q, s, k, d, e),
                m = b.rotatePointAt(r, t, k, d, e),
                c = new i(l.x, l.y, m.x, m.y);
            c.strokeStyle = this.strokeStyle;
            c.lineWidth = this.lineWidth;
            c.strokeDashArray = this.strokeDashArray;
            if (o) f.push(c);
            else n.push(c)
        }
        var j = this._getLabels();
        a.merge(f, j);
        return {
            postShapes: f,
            contextShapes: j
        }
    };

    function C(a) {
        n.call(this, a);
        this.DataType = "LinearAngleAxis";
        this.location = "radial"
    }
    C.prototype = new o;
    C.constructor = C;
    C.prototype._initDefs = function() {
        n.prototype._initDefs.call(this);
        var b = a.extend(true, {}, this.defaults, {
            minimum: 0,
            maximum: 360,
            startAngle: 0,
            endAngle: 360,
            renderLinesOverGraph: true,
            strokeStyle: "gray"
        });
        this.defaults = b
    };
    C.prototype._initRange = function() {
        this._setMinMax(this.minimum, this.maximum);
        this._setVisibleRanges();
        if (this.logarithmic === true) this.actualInterval = this._calculateActualIntervalLogarithmic(this.actualVisibleMinimum, this.actualVisibleMaximum);
        else this.actualInterval = this._calculateActualInterval(this.actualVisibleMinimum, this.actualVisibleMaximum)
    };
    C.prototype._measure = function() {
        this.width = 0;
        this.height = 0;
        return false
    };
    C.prototype._arrange = function() {
        this._initRadialMeasures()
    };
    C.prototype._updateOrigin = function() {
        this.origin = this.cx;
        this.length = 2 * Math.PI * this.radius
    };
    C.prototype._correctOrigin = function() {
        for (var a = 0, j = this.x, k = this.y, i = this.x + this.width, f = this.y + this.height, g = this._getLabels(), c = 0; c < g.length; c++) {
            var b = g[c];
            if (b.x < j) a = Math.max(a, j - b.x);
            var e = b.x + b.width;
            if (e > i) a = Math.max(a, e - i);
            var h = b.y - b.height / 2;
            if (h < k) a = Math.max(a, k - h);
            var d = b.y + b.height / 2;
            if (d > f) a = Math.max(a, d - f)
        }
        this.radius -= a;
        this.length = 2 * Math.PI * this.radius;
        if (this.sharedAxis) {
            this.sharedAxis.radius = this.radius;
            this.sharedAxis._updateOrigin();
            this.sharedAxis._initRange()
        }
    };
    C.prototype._getAngle = function(g) {
        var e = this.actualMaximum - this.actualMinimum,
            f = this.endAngle - this.startAngle,
            d = b.radians(f),
            c = b.radians(this.startAngle),
            a;
        if (e === 0) a = c + d / 2;
        else a = c + d * (g - this.actualMinimum) / e;
        if (this.reversed === true) a = 2 * Math.PI - a;
        return a - Math.PI / 2
    };
    C.prototype._getLabels = function() {
        var d = this.labels;
        if (d == null || d.visible === false) return [];
        for (var j = this.cx, k = this.cy, p = j + this.radius, q = k, e = 8, g = [], n = this._getMarkInterval(d, true), h = this._getLabelIntervals(n, d), o = this._getIntervalsLength(h), f = 0; f < o; f++) {
            var i = h[f],
                m = this.getLabel(i),
                c = this._getAngle(i),
                l = b.rotatePointAt(p, q, c, j, k),
                a = this._createLabel(m, d);
            a.x = l.x;
            a.y = l.y;
            c += Math.PI / 2;
            c = b.normalizeAngle(c);
            if (c == Math.PI) {
                a.x -= a.width / 2;
                a.y += e
            } else if (c == 0) {
                a.x -= a.width / 2;
                a.y -= e
            } else if (c > Math.PI) a.x -= a.width + e;
            else a.x += e;
            g.push(a)
        }
        return g
    };
    C.prototype._getIntervalsLength = function(a) {
        return this.endAngle - this.startAngle == 360 ? a.length - 1 : a.length
    };
    C.prototype._render = function(o) {
        var f = [],
            d = this.cx,
            e = this.cy,
            s = d,
            n = e;
        if (this.sharedAxis) n -= this.sharedAxis.extent;
        for (var t = d, u = e - this.radius, p = this.renderLinesOverGraph, k = this._getIntervals(this.actualInterval), q = this._getIntervalsLength(k), g = 0; g < q; g++) {
            var r = k[g],
                j = this._getAngle(r) + Math.PI / 2,
                l = b.rotatePointAt(s, n, j, d, e),
                m = b.rotatePointAt(t, u, j, d, e),
                c = new i(l.x, l.y, m.x, m.y);
            c.strokeStyle = this.strokeStyle;
            c.lineWidth = this.lineWidth;
            c.strokeDashArray = this.strokeDashArray;
            if (p) f.push(c);
            else o.push(c)
        }
        var h = this._getLabels();
        a.merge(f, h);
        return {
            postShapes: f,
            contextShapes: h
        }
    };

    function jb(b, a) {
        this.series = b;
        this.defaults = {
            calculationType: "standardError",
            displayType: "both",
            value: 10,
            strokeStyle: "black",
            lineWidth: 2,
            capLength: 10
        };
        this.setOptions(a);
        this.errorAmount = this._calculateErrorAmount()
    }
    jb.prototype.setOptions = function(c) {
        var b = a.extend({}, this.defaults, c || {});
        a.extend(this, b)
    };
    jb.prototype._getYValues = function() {
        for (var h = this.series, e = h.arrData, j = e.length, i = h.xAxisType, c, d, g = [], f = 0; f < e.length; f++) {
            c = e[f];
            if (c === null) continue;
            if (i == "CategoryAxis")
                if (a.isArray(c) == false) d = c;
                else d = c[1];
            else d = c[1];
            !b.isNull(d) && g.push(d)
        }
        return g
    };
    jb.prototype._calculateErrorAmount = function() {
        var o = this.series;
        if (!o.arrData) return 0;
        var e = this._getYValues(o),
            a = e.length;
        switch (this.calculationType) {
            case "standardError":
                if (a <= 1) return 0;
                for (var g = 0, c = 0; c < a; c++) g += Math.pow(e[c], 2);
                g = Math.sqrt(g / (a * (a - 1))) / 2;
                return g;
            case "standardDeviation":
                if (a <= 1) return 0;
                for (var s = b.sum(e), r = s / a, f = 0, c = 0; c < a; c++) f += Math.pow(e[c] - r, 2);
                f = Math.sqrt(f / (a - 1));
                return f;
            case "percentage":
                for (var l = 0, n = 0, p = m, q = j, h, d, k, i, c = 0; c < a; c++) {
                    h = e[c];
                    d = Math.abs(h) * this.value / 100;
                    k = h - d;
                    i = h + d;
                    if (p < i) {
                        p = i;
                        n = d
                    }
                    if (q > k) {
                        q = k;
                        l = d
                    }
                }
                return {
                    lower: l,
                    upper: n
                };
            case "fixedValue":
                return this.value
        }
    };
    jb.prototype.getUpperError = function() {
        var a = this.displayType;
        return a == "both" || a == "upper" ? this.calculationType == "percentage" ? this.errorAmount.upper : this.errorAmount : 0
    };
    jb.prototype.getLowerError = function() {
        var a = this.displayType;
        return a == "both" || a == "lower" ? this.calculationType == "percentage" ? this.errorAmount.lower : this.errorAmount : 0
    };
    jb.prototype.getMaxLength = function() {
        var a = this.series,
            e = a.isVertical,
            f = 0;
        if (a.realYAxis._getActualVisibleMinimum() === undefined) return 0;
        var d = this.getUpperError(),
            b = a.realYAxis.getPosition(0),
            c = a.realYAxis.getPosition(d);
        return isNaN(b) || isNaN(c) ? 0 : Math.abs(b - c) + this.lineWidth / 2 + 2
    };
    jb.prototype._createShapes = function(n) {
        for (var f = this.series, y = f.isVertical, x = f.arrData, A = x.length, z = f.xAxisType, m, o, h, c, p, q, d, r, s, v, w, e, j, k, g = this.displayType, u = this.errorAmount, l = this.capLength / 2, t = 0; t < x.length; t++) {
            m = x[t];
            if (m === null) continue;
            if (z == "CategoryAxis") {
                o = t + .5;
                if (a.isArray(m) == false) h = m;
                else h = m[1]
            } else {
                o = m[0];
                h = m[1]
            }
            if (b.isNull(o) || b.isNull(h)) continue;
            if (this.calculationType == "percentage") u = Math.abs(h) * this.value / 100;
            v = h - u;
            w = h + u;
            if (y) {
                d = f.realXAxis.getPosition(o);
                c = f.realYAxis.getPosition(h);
                p = f.realYAxis.getPosition(v);
                q = f.realYAxis.getPosition(w);
                switch (g) {
                    case "lower":
                        e = new i(c, d, p, d);
                        break;
                    case "upper":
                        e = new i(q, d, c, d);
                        break;
                    default:
                        e = new i(p, d, q, d)
                }
                e.lineWidth = this.lineWidth;
                e.strokeStyle = this.strokeStyle;
                n.push(e);
                if (g == "both" || g == "lower") {
                    j = new i(p, d - l, p, d + l);
                    j.lineWidth = this.lineWidth;
                    j.strokeStyle = this.strokeStyle;
                    n.push(j)
                }
                if (g == "both" || g == "upper") {
                    k = new i(q, d - l, q, d + l);
                    k.lineWidth = this.lineWidth;
                    k.strokeStyle = this.strokeStyle;
                    n.push(k)
                }
            } else {
                c = f.realXAxis.getPosition(o);
                d = f.realYAxis.getPosition(h);
                r = f.realYAxis.getPosition(v);
                s = f.realYAxis.getPosition(w);
                switch (g) {
                    case "lower":
                        e = new i(c, d, c, r);
                        break;
                    case "upper":
                        e = new i(c, s, c, d);
                        break;
                    default:
                        e = new i(c, r, c, s)
                }
                e.lineWidth = this.lineWidth;
                e.strokeStyle = this.strokeStyle;
                n.push(e);
                if (g == "both" || g == "lower") {
                    j = new i(c - l, r, c + l, r);
                    j.lineWidth = this.lineWidth;
                    j.strokeStyle = this.strokeStyle;
                    n.push(j)
                }
                if (g == "both" || g == "upper") {
                    k = new i(c - l, s, c + l, s);
                    k.lineWidth = this.lineWidth;
                    k.strokeStyle = this.strokeStyle;
                    n.push(k)
                }
            }
        }
    };

    function Yb(a) {
        this.defaults = {
            marker: {
                visible: true,
                type: "cross",
                fillStyle: "red",
                strokeStyle: "red",
                lineWidth: 4,
                size: 10
            },
            fillStyle: "#cccccc",
            strokeStyle: "red",
            lineWidth: 2
        };
        this.setOptions(a)
    }
    Yb.prototype.setOptions = function(c) {
        var b = a.extend(true, {}, this.defaults, c || {});
        a.extend(this, b)
    }
})(jQuery)