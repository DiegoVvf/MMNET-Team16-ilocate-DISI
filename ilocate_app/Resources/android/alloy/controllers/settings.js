function __processArg(obj, key) {
    var arg = null;
    if (obj) {
        arg = obj[key] || null;
        delete obj[key];
    }
    return arg;
}

function Controller() {
    function start() {
        if (null != b_index) {
            b_index.fireEvent("start", {
                data: parseInt($.txt.value)
            });
            $.win_settings.close();
        }
    }
    require("alloy/controllers/BaseController").apply(this, Array.prototype.slice.call(arguments));
    this.__controllerPath = "settings";
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
    var __defers = {};
    $.__views.win_settings = Ti.UI.createWindow({
        backgroundColor: "white",
        windowSoftInputMode: Ti.UI.Android.SOFT_INPUT_ADJUST_UNSPECIFIED,
        id: "win_settings"
    });
    $.__views.win_settings && $.addTopLevelView($.__views.win_settings);
    $.__views.__alloyId21 = Ti.UI.createView({
        layout: "vertical",
        id: "__alloyId21"
    });
    $.__views.win_settings.add($.__views.__alloyId21);
    $.__views.__alloyId22 = Ti.UI.createLabel({
        font: {
            fontFamily: "Raleway-Medium"
        },
        color: "white",
        text: "Time in seconds",
        id: "__alloyId22"
    });
    $.__views.__alloyId21.add($.__views.__alloyId22);
    $.__views.txt = Ti.UI.createTextField({
        height: "40dp",
        autocapitalization: Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,
        borderWidth: 1,
        borderColor: "#525252",
        borderRadius: 5,
        color: "black",
        id: "txt",
        value: "10"
    });
    $.__views.__alloyId21.add($.__views.txt);
    $.__views.b_start = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        title: "Start",
        id: "b_start"
    });
    $.__views.__alloyId21.add($.__views.b_start);
    start ? $.__views.b_start.addEventListener("click", start) : __defers["$.__views.b_start!click!start"] = true;
    exports.destroy = function() {};
    _.extend($, $.__views);
    var b_index = null;
    exports.setData = function(b) {
        b_index = b;
    };
    __defers["$.__views.b_start!click!start"] && $.__views.b_start.addEventListener("click", start);
    _.extend($, exports);
}

var Alloy = require("alloy"), Backbone = Alloy.Backbone, _ = Alloy._;

module.exports = Controller;