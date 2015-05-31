function __processArg(obj, key) {
    var arg = null;
    if (obj) {
        arg = obj[key] || null;
        delete obj[key];
    }
    return arg;
}

function Controller() {
    require("alloy/controllers/BaseController").apply(this, Array.prototype.slice.call(arguments));
    this.__controllerPath = "base";
    if (arguments[0]) {
        {
            __processArg(arguments[0], "__parentSymbol");
        }
        {
            __processArg(arguments[0], "$model");
        }
        {
            __processArg(arguments[0], "__itemTemplate");
        }
    }
    var $ = this;
    var exports = {};
    exports.destroy = function() {};
    _.extend($, $.__views);
    Alloy.Globals.openWindows = [];
    openWindow = function(win, animate) {
        var last_win_id = Alloy.Globals.openWindows[Alloy.Globals.openWindows.length - 1];
        Ti.API.info(win.id + "   " + last_win_id);
        if (last_win_id === win.id) {
            Ti.API.info(" ###########################################################");
            Ti.API.info(" ######## This window is already open, nothing to do! ######");
            Ti.API.info(" ###########################################################");
        } else {
            win.open({
                animated: false
            });
            Alloy.Globals.openWindows.push(win.id);
        }
        win.addEventListener("close", function() {
            Ti.API.info("Got close event for window ");
            Alloy.Globals.openWindows.pop();
        });
    };
    close = function(win) {
        win.close({
            animated: false
        });
    };
    log = function(msg) {
        if (Alloy.Globals.DEBUG) {
            Ti.API.info("=== DEBUG API ===");
            Ti.API.info(msg);
        }
    };
    _.extend($, exports);
}

var Alloy = require("alloy"), Backbone = Alloy.Backbone, _ = Alloy._;

module.exports = Controller;