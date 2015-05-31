function __processArg(obj, key) {
    var arg = null;
    if (obj) {
        arg = obj[key] || null;
        delete obj[key];
    }
    return arg;
}

function Controller() {
    function scan() {
        var titaniumBarcode = require("com.mywaysolutions.barcode");
        titaniumBarcode.scan({
            success: function(e) {
                Ti.API.info("Success called with barcode: " + JSON.stringify(e));
                var api = require("api");
                api.getIndoorLocation(prepareDataForProxy(), {
                    onSuccess: function(result) {
                        alert(JSON.stringify(result.response));
                    },
                    onError: function(e) {
                        alert(e);
                    }
                });
            },
            error: function(err) {
                Ti.API.info(JSON.stringify(err));
            },
            cancel: function() {
                Ti.API.info("Cancel received");
            }
        });
    }
    function prepareDataForProxy(id) {
        var postData = {};
        postData["positionType"] = "relative";
        postData["objectId"] = Ti.Platform.macaddress;
        postData["localizationSystems"] = [];
        postData["localizationSystems"].push({
            localizationSystem_id: "qrcode",
            entries: [ {
                id: "637"
            } ]
        });
        return postData;
    }
    require("alloy/controllers/BaseController").apply(this, Array.prototype.slice.call(arguments));
    this.__controllerPath = "indoor";
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
    $.__views.win_indoor = Ti.UI.createWindow({
        backgroundColor: "white",
        windowSoftInputMode: Ti.UI.Android.SOFT_INPUT_ADJUST_UNSPECIFIED,
        id: "win_indoor",
        layout: "vertical"
    });
    $.__views.win_indoor && $.addTopLevelView($.__views.win_indoor);
    $.__views.action_bar = Ti.UI.createView({
        backgroundImage: "/header.jpg",
        left: 0,
        right: 0,
        height: "50dp",
        id: "action_bar"
    });
    $.__views.win_indoor.add($.__views.action_bar);
    $.__views.lbl_bar_title = Ti.UI.createLabel({
        font: {
            fontFamily: "Raleway-Medium",
            fontSize: "30dp"
        },
        color: "white",
        text: L("guide_me"),
        bottom: 0,
        id: "lbl_bar_title"
    });
    $.__views.action_bar.add($.__views.lbl_bar_title);
    $.__views.b_scan = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        height: "auto",
        width: "auto",
        right: 0,
        title: "scan",
        id: "b_scan"
    });
    $.__views.action_bar.add($.__views.b_scan);
    scan ? $.__views.b_scan.addEventListener("click", scan) : __defers["$.__views.b_scan!click!scan"] = true;
    $.__views.img_map = Ti.UI.createImageView({
        left: 0,
        right: 0,
        top: 0,
        bottom: 0,
        id: "img_map"
    });
    $.__views.win_indoor.add($.__views.img_map);
    exports.destroy = function() {};
    _.extend($, $.__views);
    $.img_map.image = "/layout.png";
    __defers["$.__views.b_scan!click!scan"] && $.__views.b_scan.addEventListener("click", scan);
    _.extend($, exports);
}

var Alloy = require("alloy"), Backbone = Alloy.Backbone, _ = Alloy._;

module.exports = Controller;