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
    this.__controllerPath = "navigation_list";
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
    $.__views.navigation_list = Ti.UI.createWindow({
        backgroundColor: "white",
        windowSoftInputMode: Ti.UI.Android.SOFT_INPUT_ADJUST_UNSPECIFIED,
        id: "navigation_list"
    });
    $.__views.navigation_list && $.addTopLevelView($.__views.navigation_list);
    $.__views.action_bar = Ti.UI.createView({
        backgroundImage: "/header.jpg",
        left: 0,
        right: 0,
        height: "50dp",
        top: 0,
        id: "action_bar"
    });
    $.__views.navigation_list.add($.__views.action_bar);
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
    $.__views.b_ls_nav = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        id: "b_ls_nav",
        visible: "false"
    });
    $.__views.navigation_list.add($.__views.b_ls_nav);
    var __alloyId2 = {};
    var __alloyId4 = [];
    var __alloyId5 = {
        type: "Ti.UI.View",
        bindId: "v_container",
        childTemplates: function() {
            var __alloyId6 = [];
            var __alloyId7 = {
                type: "Ti.UI.ImageView",
                bindId: "img",
                properties: {
                    left: "20dp",
                    width: "30dp",
                    height: "30dp",
                    bindId: "img"
                }
            };
            __alloyId6.push(__alloyId7);
            var __alloyId8 = {
                type: "Ti.UI.View",
                childTemplates: function() {
                    var __alloyId9 = [];
                    var __alloyId10 = {
                        type: "Ti.UI.Label",
                        bindId: "lbl_street_name",
                        properties: {
                            font: {
                                fontFamily: "Raleway-Medium"
                            },
                            color: "black",
                            left: 0,
                            top: 0,
                            height: "30dp",
                            bindId: "lbl_street_name"
                        }
                    };
                    __alloyId9.push(__alloyId10);
                    var __alloyId11 = {
                        type: "Ti.UI.Label",
                        bindId: "lbl_distance",
                        properties: {
                            font: {
                                fontFamily: "Raleway-Medium"
                            },
                            color: "black",
                            left: 0,
                            bottom: 0,
                            height: "30dp",
                            bindId: "lbl_distance"
                        }
                    };
                    __alloyId9.push(__alloyId11);
                    return __alloyId9;
                }(),
                properties: {
                    left: "100dp",
                    right: 0,
                    top: "10dp",
                    bottom: "10dp"
                }
            };
            __alloyId6.push(__alloyId8);
            return __alloyId6;
        }(),
        properties: {
            left: 0,
            right: 0,
            height: "70dp",
            bindId: "v_container"
        }
    };
    __alloyId4.push(__alloyId5);
    var __alloyId3 = {
        properties: {
            name: "template",
            bindId: "id_question"
        },
        childTemplates: __alloyId4
    };
    __alloyId2["template"] = __alloyId3;
    $.__views.listView = Ti.UI.createListView({
        top: "60dp",
        templates: __alloyId2,
        id: "listView",
        defaultItemTemplate: "template",
        separatorColor: "transparent"
    });
    $.__views.navigation_list.add($.__views.listView);
    exports.destroy = function() {};
    _.extend($, $.__views);
    var dict = {};
    exports.setData = function(data, b_index) {
        var rows = [];
        for (var i in data) {
            rows.push({
                id_question: data[i].latitude + "," + data[i].longitude,
                v_container: {
                    backgroundColor: "white"
                },
                img: {
                    image: "LEFT" == data[i].relativeDirection ? "/left.jpg" : "RIGHT" == data[i].relativeDirection ? "/right.jpg" : "/continue.jpg"
                },
                lbl_street_name: {
                    text: data[i].streetName
                },
                lbl_distance: {
                    text: parseInt(data[i].distance) + " meters"
                }
            });
            dict[data[i].latitude + "," + data[i].longitude] = i;
        }
        var section = Ti.UI.createListSection({
            items: rows
        });
        $.listView.sections = [ section ];
        null != b_index && b_index.fireEvent("nav_list", {
            data: $.b_ls_nav
        });
    };
    $.b_ls_nav.addEventListener("update_row", function(e) {});
    _.extend($, exports);
}

var Alloy = require("alloy"), Backbone = Alloy.Backbone, _ = Alloy._;

module.exports = Controller;