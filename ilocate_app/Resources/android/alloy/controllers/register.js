function __processArg(obj, key) {
    var arg = null;
    if (obj) {
        arg = obj[key] || null;
        delete obj[key];
    }
    return arg;
}

function Controller() {
    function register() {
        if ($.field_email.value.length > 5) {
            var api = require("api");
            var params = {
                email: $.field_email.value,
                subject: "iLocate registration",
                message: "Follow this link to complete the registration to the iLocate service"
            };
            api.registerUser(JSON.stringify(params), {
                onSuccess: function(result) {
                    alert(JSON.stringify(result));
                },
                onError: function(e) {
                    alert(JSON.stringify(e));
                }
            });
        }
    }
    require("alloy/controllers/BaseController").apply(this, Array.prototype.slice.call(arguments));
    this.__controllerPath = "register";
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
    $.__views.win_register = Ti.UI.createWindow({
        backgroundColor: "white",
        windowSoftInputMode: Ti.UI.Android.SOFT_INPUT_ADJUST_UNSPECIFIED,
        titleImage: "/icon_navbar.png",
        layout: "vertical",
        id: "win_register"
    });
    $.__views.win_register && $.addTopLevelView($.__views.win_register);
    $.__views.action_bar = Ti.UI.createView({
        backgroundImage: "/header.jpg",
        left: 0,
        right: 0,
        height: "50dp",
        id: "action_bar"
    });
    $.__views.win_register.add($.__views.action_bar);
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
    $.__views.lbl_email = Ti.UI.createLabel({
        font: {
            fontFamily: "Raleway-Medium",
            fontSize: "20dp"
        },
        color: "black",
        top: "50dp",
        left: "5dp",
        text: "Email",
        id: "lbl_email"
    });
    $.__views.win_register.add($.__views.lbl_email);
    $.__views.custom_txt_field = Ti.UI.createView({
        top: "30dp",
        left: "5dp",
        right: "7dp",
        height: "40dp",
        id: "custom_txt_field"
    });
    $.__views.win_register.add($.__views.custom_txt_field);
    $.__views.field_email = Ti.UI.createTextField({
        height: "40dp",
        autocapitalization: Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,
        borderWidth: 1,
        borderColor: "#525252",
        borderRadius: 5,
        color: "black",
        keyboardType: Titanium.UI.KEYBOARD_EMAIL,
        returnKeyType: Titanium.UI.RETURNKEY_NEXT,
        left: "20dp",
        right: "20dp",
        id: "field_email",
        value: "erinda.jaupaj@u-hopper.com"
    });
    $.__views.custom_txt_field.add($.__views.field_email);
    $.__views.b_register = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        top: "50dp",
        left: "20dp",
        right: "20dp",
        height: "50dp",
        font: {
            fontSize: "20dp"
        },
        backgroundImage: "/footer.jpg",
        title: "Register",
        id: "b_register"
    });
    $.__views.win_register.add($.__views.b_register);
    register ? $.__views.b_register.addEventListener("click", register) : __defers["$.__views.b_register!click!register"] = true;
    exports.destroy = function() {};
    _.extend($, $.__views);
    __defers["$.__views.b_register!click!register"] && $.__views.b_register.addEventListener("click", register);
    _.extend($, exports);
}

var Alloy = require("alloy"), Backbone = Alloy.Backbone, _ = Alloy._;

module.exports = Controller;