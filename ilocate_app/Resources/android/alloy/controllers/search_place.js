function __processArg(obj, key) {
    var arg = null;
    if (obj) {
        arg = obj[key] || null;
        delete obj[key];
    }
    return arg;
}

function Controller() {
    function setWalk() {
        $.b_walk.backgroundImage = "/b_walk_active.jpg";
        routeOption = "WALK";
        $.b_car.backgroundImage = "/b_car.jpg";
        $.b_bus.backgroundImage = "/b_bus.jpg";
    }
    function setCar() {
        $.b_car.backgroundImage = "/b_car_active.jpg";
        routeOption = "CAR";
        $.b_walk.backgroundImage = "/b_walk.jpg";
        $.b_bus.backgroundImage = "/b_bus.jpg";
    }
    function setBus() {
        $.b_bus.backgroundImage = "/b_bus_active.jpg";
        routeOption = "BUS";
        $.b_car.backgroundImage = "/b_car.jpg";
        $.b_walk.backgroundImage = "/b_walk.jpg";
    }
    function onChange(e) {
        Ti.API.info(JSON.stringify(e.value));
        e.value.length > 3 && api.getGpsFromName($.field_search.value, {
            onSuccess: function(data) {
                var format_data = prepareDataForList(data);
                section = Ti.UI.createListSection({
                    items: format_data
                });
                $.listView.sections = [ section ];
            },
            onError: function(data) {
                alert("KO");
            }
        });
    }
    function prepareDataForList(data) {
        var result = [];
        for (var i in data) {
            Ti.API.info(data.geocodeAddress);
            result.push({
                id_question: data[i].latitude + "," + data[i].longitude,
                lbl_title: {
                    text: data[i].geocodeAddress
                }
            });
        }
        return result;
    }
    function onItemClick(e) {
        var item = e.section.getItemAt(e.itemIndex);
        Ti.API.info("ON ITEM CLICK " + JSON.stringify(item));
        btn_index.fireEvent("gps", {
            data: item.id_question,
            mode: routeOption
        });
        $.win_search_place.close();
    }
    require("alloy/controllers/BaseController").apply(this, Array.prototype.slice.call(arguments));
    this.__controllerPath = "search_place";
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
    $.__views.win_search_place = Ti.UI.createWindow({
        backgroundColor: "white",
        windowSoftInputMode: Ti.UI.Android.SOFT_INPUT_ADJUST_UNSPECIFIED,
        layout: "vertical",
        id: "win_search_place"
    });
    $.__views.win_search_place && $.addTopLevelView($.__views.win_search_place);
    $.__views.action_bar = Ti.UI.createView({
        backgroundImage: "/header.jpg",
        left: 0,
        right: 0,
        height: "50dp",
        id: "action_bar"
    });
    $.__views.win_search_place.add($.__views.action_bar);
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
    $.__views.v_option = Ti.UI.createView({
        left: 0,
        right: 0,
        height: "60dp",
        id: "v_option"
    });
    $.__views.win_search_place.add($.__views.v_option);
    $.__views.b_walk = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        top: 0,
        bottom: 0,
        width: "33%",
        left: 0,
        backgroundImage: "/b_walk_active.jpg",
        id: "b_walk"
    });
    $.__views.v_option.add($.__views.b_walk);
    setWalk ? $.__views.b_walk.addEventListener("click", setWalk) : __defers["$.__views.b_walk!click!setWalk"] = true;
    $.__views.b_car = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        top: 0,
        bottom: 0,
        width: "34%",
        backgroundImage: "/b_car.jpg",
        left: "33%",
        id: "b_car"
    });
    $.__views.v_option.add($.__views.b_car);
    setCar ? $.__views.b_car.addEventListener("click", setCar) : __defers["$.__views.b_car!click!setCar"] = true;
    $.__views.b_bus = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        top: 0,
        bottom: 0,
        width: "33%",
        backgroundImage: "/b_bus.jpg",
        right: 0,
        id: "b_bus"
    });
    $.__views.v_option.add($.__views.b_bus);
    setBus ? $.__views.b_bus.addEventListener("click", setBus) : __defers["$.__views.b_bus!click!setBus"] = true;
    $.__views.v_search = Ti.UI.createView({
        left: "20dp",
        right: "20dp",
        top: "20dp",
        height: "40dp",
        borderRadius: "15dp",
        borderWidth: 1,
        borderColor: "gray",
        id: "v_search"
    });
    $.__views.win_search_place.add($.__views.v_search);
    $.__views.b_search = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        backgroundImage: "/graySearch.png",
        width: "20dp",
        height: "20dp",
        left: "20dp",
        id: "b_search"
    });
    $.__views.v_search.add($.__views.b_search);
    $.__views.field_search = Ti.UI.createTextField({
        height: "40dp",
        autocapitalization: Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,
        borderWidth: 1,
        borderColor: "#525252",
        borderRadius: 5,
        color: "black",
        backgroundColor: "transparent",
        font: {
            fontFamily: "Raleway-Medium",
            fontSize: "20dp"
        },
        left: "40dp",
        right: "10dp",
        id: "field_search"
    });
    $.__views.v_search.add($.__views.field_search);
    onChange ? $.__views.field_search.addEventListener("change", onChange) : __defers["$.__views.field_search!change!onChange"] = true;
    $.__views.v_devider = Ti.UI.createView({
        left: 0,
        right: 0,
        height: 1,
        backgroundColor: "black",
        top: "20dp",
        id: "v_devider"
    });
    $.__views.win_search_place.add($.__views.v_devider);
    var __alloyId12 = {};
    var __alloyId14 = [];
    var __alloyId15 = {
        type: "Ti.UI.View",
        childTemplates: function() {
            var __alloyId16 = [];
            var __alloyId17 = {
                type: "Ti.UI.View",
                childTemplates: function() {
                    var __alloyId18 = [];
                    var __alloyId19 = {
                        type: "Ti.UI.Label",
                        bindId: "lbl_title",
                        properties: {
                            font: {
                                fontFamily: "Raleway-Medium",
                                fontSize: "18dp"
                            },
                            color: "black",
                            left: 0,
                            text: "asd",
                            bindId: "lbl_title"
                        }
                    };
                    __alloyId18.push(__alloyId19);
                    return __alloyId18;
                }(),
                properties: {
                    left: "90dp",
                    right: "50dp",
                    top: "10dp",
                    bottom: "10dp"
                }
            };
            __alloyId16.push(__alloyId17);
            var __alloyId20 = {
                type: "Ti.UI.Button",
                properties: {
                    borderRadius: 5,
                    color: "white",
                    right: "10dp",
                    width: "30dp",
                    height: "30dp",
                    backgroundColor: "red"
                }
            };
            __alloyId16.push(__alloyId20);
            return __alloyId16;
        }(),
        properties: {
            height: "90dp",
            left: 0,
            right: 0
        }
    };
    __alloyId14.push(__alloyId15);
    var __alloyId13 = {
        properties: {
            height: "90dp",
            left: 0,
            right: 0,
            name: "template",
            bindId: "id_question"
        },
        childTemplates: __alloyId14
    };
    __alloyId12["template"] = __alloyId13;
    $.__views.listView = Ti.UI.createListView({
        templates: __alloyId12,
        id: "listView",
        defaultItemTemplate: "template",
        separatorColor: "transparent"
    });
    $.__views.win_search_place.add($.__views.listView);
    onItemClick ? $.__views.listView.addEventListener("itemclick", onItemClick) : __defers["$.__views.listView!itemclick!onItemClick"] = true;
    exports.destroy = function() {};
    _.extend($, $.__views);
    var btn_index = null;
    var api = require("api");
    var routeOption = "WALK";
    var section = null;
    exports.setData = function(btn) {
        btn_index = btn;
    };
    $.field_search.value = "ruote de thionville, luxembourg";
    __defers["$.__views.b_walk!click!setWalk"] && $.__views.b_walk.addEventListener("click", setWalk);
    __defers["$.__views.b_car!click!setCar"] && $.__views.b_car.addEventListener("click", setCar);
    __defers["$.__views.b_bus!click!setBus"] && $.__views.b_bus.addEventListener("click", setBus);
    __defers["$.__views.field_search!change!onChange"] && $.__views.field_search.addEventListener("change", onChange);
    __defers["$.__views.listView!itemclick!onItemClick"] && $.__views.listView.addEventListener("itemclick", onItemClick);
    _.extend($, exports);
}

var Alloy = require("alloy"), Backbone = Alloy.Backbone, _ = Alloy._;

module.exports = Controller;