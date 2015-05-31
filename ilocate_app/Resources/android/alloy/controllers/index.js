function __processArg(obj, key) {
    var arg = null;
    if (obj) {
        arg = obj[key] || null;
        delete obj[key];
    }
    return arg;
}

function Controller() {
    function doClick(e) {
        var controller = Alloy.createController("search_place");
        controller.setData($.b_search);
        openWindow(controller.getView());
    }
    function iterateThroughTemporalRoute(i, t) {
        setTimeout(function() {
            Ti.API.info("===> " + JSON.stringify(temporalRouteCOntainer[i]));
            setLocation(parseFloat(temporalRouteCOntainer[i]["latitude"]), parseFloat(temporalRouteCOntainer[i]["longitude"]));
            i += 1;
            i < temporalRouteCOntainer.length && iterateThroughTemporalRoute(i, t);
            return;
        }, 1e3 * t);
    }
    function getLastKnownBestLocation(callback) {
        Ti.API.info("===getLastKnownBestLocation===");
        toolkit.getLastKnownBestLocation(function(data) {
            Ti.API.info(JSON.stringify(data));
            if (data.message) {
                alert(data.message);
                callback();
            } else callback(JSON.parse(data.container));
        });
    }
    function speak(msg) {
        if (textToSpeech.isSpeaking()) return;
        textToSpeech.startSpeaking({
            text: msg
        });
    }
    function setLocation(lat, lon) {
        mapview.setLocation({
            latitude: lat,
            longitude: lon
        }, function(result) {
            null != b_ls_nav && b_ls_nav.fireEvent("update_row", {
                data: lat + "," + lon
            });
            (result.success = "true" && 0 == parseInt(result.marked)) && speak(result.message);
        });
    }
    function showStepByStepRoute() {
        if (1 == $.b_step_by_step_route.active) {
            var width = Ti.Platform.displayCaps.platformWidth;
            var win = Alloy.createController("navigation_list");
            win.setData(temporalRouteCOntainer, $.b_search);
            var anim = Ti.UI.createAnimation({
                right: width,
                duration: 1e3
            });
            win.getView().open({
                animate: anim
            });
        }
    }
    function openMapBuilding() {
        openWindow(Alloy.createController("indoor").getView());
    }
    function startNavigation() {
        temporalRouteCOntainer.length > 0 && iterateThroughTemporalRoute(0, 5);
    }
    function openLogin() {
        openWindow(Alloy.createController("login").getView());
    }
    require("alloy/controllers/base").apply(this, Array.prototype.slice.call(arguments));
    this.__controllerPath = "index";
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
    $.__views.index = Ti.UI.createWindow({
        backgroundColor: "gray",
        windowSoftInputMode: Ti.UI.Android.SOFT_INPUT_ADJUST_UNSPECIFIED,
        id: "index"
    });
    $.__views.index && $.addTopLevelView($.__views.index);
    $.__views.action_bar = Ti.UI.createView({
        top: 0,
        backgroundImage: "/header.jpg",
        left: 0,
        right: 0,
        height: "50dp",
        id: "action_bar"
    });
    $.__views.index.add($.__views.action_bar);
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
    $.__views.b_login = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        right: "10dp",
        title: "Login",
        id: "b_login"
    });
    $.__views.action_bar.add($.__views.b_login);
    openLogin ? $.__views.b_login.addEventListener("click", openLogin) : __defers["$.__views.b_login!click!openLogin"] = true;
    $.__views.v_map_container = Ti.UI.createView({
        top: "50dp",
        bottom: "65dp",
        id: "v_map_container"
    });
    $.__views.index.add($.__views.v_map_container);
    $.__views.v_map = Ti.UI.createView({
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        id: "v_map"
    });
    $.__views.v_map_container.add($.__views.v_map);
    $.__views.b_step_by_step_route = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        right: "10dp",
        bottom: "30dp",
        width: "50dp",
        height: "50dp",
        backgroundImage: "/b_route.png",
        backgroundColor: "transparent",
        avtive: 0,
        id: "b_step_by_step_route"
    });
    $.__views.v_map_container.add($.__views.b_step_by_step_route);
    showStepByStepRoute ? $.__views.b_step_by_step_route.addEventListener("click", showStepByStepRoute) : __defers["$.__views.b_step_by_step_route!click!showStepByStepRoute"] = true;
    $.__views.b_building = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        right: "10dp",
        bottom: "85dp",
        width: "50dp",
        height: "50dp",
        backgroundImage: "/b_building_active.png",
        backgroundColor: "transparent",
        active: 0,
        id: "b_building"
    });
    $.__views.v_map_container.add($.__views.b_building);
    openMapBuilding ? $.__views.b_building.addEventListener("click", openMapBuilding) : __defers["$.__views.b_building!click!openMapBuilding"] = true;
    $.__views.b_navigation = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        right: "10dp",
        bottom: "140dp",
        width: "50dp",
        height: "50dp",
        backgroundImage: "/b_navigation_active.png",
        backgroundColor: "transparent",
        active: 1,
        id: "b_navigation"
    });
    $.__views.v_map_container.add($.__views.b_navigation);
    startNavigation ? $.__views.b_navigation.addEventListener("click", startNavigation) : __defers["$.__views.b_navigation!click!startNavigation"] = true;
    $.__views.v_footer = Ti.UI.createView({
        bottom: 0,
        backgroundImage: "/footer.jpg",
        left: 0,
        right: 0,
        height: "65dp",
        id: "v_footer"
    });
    $.__views.index.add($.__views.v_footer);
    doClick ? $.__views.v_footer.addEventListener("click", doClick) : __defers["$.__views.v_footer!click!doClick"] = true;
    $.__views.b_search = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        left: "18%",
        width: "30dp",
        height: "30dp",
        backgroundImage: "/whiteSearch.png",
        touchEnabled: false,
        id: "b_search"
    });
    $.__views.v_footer.add($.__views.b_search);
    $.__views.lbl_search = Ti.UI.createLabel({
        font: {
            fontFamily: "Raleway-Medium",
            fontSize: "22dp"
        },
        color: "white",
        text: L("search_place"),
        touchEnabled: false,
        id: "lbl_search"
    });
    $.__views.v_footer.add($.__views.lbl_search);
    exports.destroy = function() {};
    _.extend($, $.__views);
    Alloy.Globals.MY_POSITION = "";
    exports.baseController = "base";
    var tiosm = require("eu.ilocate.tiosm");
    var toolkit = require("eu.ilocate.toolkit");
    var api = require("api");
    var mapview = tiosm.createView();
    var utterance = require("bencoding.utterance"), textToSpeech = utterance.createSpeech();
    var postData = {};
    postData["positionType"] = "relative";
    postData["objectId"] = Ti.Platform.macaddress;
    postData["localizationSystems"] = [];
    var temporalRouteCOntainer = [];
    var b_ls_nav = null;
    $.v_map.add(mapview);
    $.index.open();
    $.index.addEventListener("open", function() {
        getLastKnownBestLocation(function(data) {
            if (data) {
                setLocation(parseFloat(data.latitude), parseFloat(data.longitude));
                Alloy.Globals.MY_POSITION = data.latitude + "," + data.longitude;
            }
        });
    });
    $.b_search.addEventListener("gps", function(e) {
        Ti.API.info("BUTTON SEARCH EVENT ");
        Alloy.Globals.MY_POSITION.length > 0 && api.getRoutes(Alloy.Globals.MY_POSITION, e.data, e.mode, {
            success: function(data) {
                Ti.API.info(JSON.stringify(data["markers"]));
                temporalRouteCOntainer = data["markers"];
                mapview.polyline(data["legGeometry"]);
                mapview.setRoutesMarker(data["markers"]);
                $.b_step_by_step_route.backgroundImage = "/b_route_active.png";
                $.b_step_by_step_route.active = 1;
            }
        });
    });
    $.b_search.addEventListener("start", function(e) {
        temporalRouteCOntainer.length > 0 ? iterateThroughTemporalRoute(0, e.data) : alert("First assure that you have a visible route on the map, and than press start!!");
    });
    $.b_search.addEventListener("nav_list", function(e) {
        b_ls_nav = e.data;
    });
    __defers["$.__views.b_login!click!openLogin"] && $.__views.b_login.addEventListener("click", openLogin);
    __defers["$.__views.b_step_by_step_route!click!showStepByStepRoute"] && $.__views.b_step_by_step_route.addEventListener("click", showStepByStepRoute);
    __defers["$.__views.b_building!click!openMapBuilding"] && $.__views.b_building.addEventListener("click", openMapBuilding);
    __defers["$.__views.b_navigation!click!startNavigation"] && $.__views.b_navigation.addEventListener("click", startNavigation);
    __defers["$.__views.v_footer!click!doClick"] && $.__views.v_footer.addEventListener("click", doClick);
    _.extend($, exports);
}

var Alloy = require("alloy"), Backbone = Alloy.Backbone, _ = Alloy._;

module.exports = Controller;