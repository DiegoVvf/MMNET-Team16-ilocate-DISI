function __processArg(obj, key) {
    var arg = null;
    if (obj) {
        arg = obj[key] || null;
        delete obj[key];
    }
    return arg;
}

function Controller() {
    function changeFocusBorder() {}
    function rememberme() {}
    function login() {
        var api = require("api");
        api.loginUser($.field_codice_partner.value, $.password.value, {
            onSuccess: function(result) {
                Alloy.Globals.TOKEN = result.tokenId;
                $.win_login.close();
            },
            onError: function(e) {
                alert(JSON.stringify(e));
            }
        });
    }
    function focusPassword() {}
    function register() {
        openWindow(Alloy.createController("register").getView());
    }
    require("alloy/controllers/BaseController").apply(this, Array.prototype.slice.call(arguments));
    this.__controllerPath = "login";
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
    $.__views.win_login = Ti.UI.createWindow({
        backgroundColor: "white",
        windowSoftInputMode: Ti.UI.Android.SOFT_INPUT_ADJUST_UNSPECIFIED,
        titleImage: "/icon_navbar.png",
        layout: "vertical",
        id: "win_login"
    });
    $.__views.win_login && $.addTopLevelView($.__views.win_login);
    $.__views.action_bar = Ti.UI.createView({
        backgroundImage: "/header.jpg",
        left: 0,
        right: 0,
        height: "50dp",
        id: "action_bar"
    });
    $.__views.win_login.add($.__views.action_bar);
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
    $.__views.__alloyId0 = Ti.UI.createScrollView({
        scrollType: "vertical",
        id: "__alloyId0"
    });
    $.__views.win_login.add($.__views.__alloyId0);
    $.__views.__alloyId1 = Ti.UI.createView({
        height: "370dp",
        width: "300dp",
        id: "__alloyId1"
    });
    $.__views.__alloyId0.add($.__views.__alloyId1);
    $.__views.lbl_username = Ti.UI.createLabel({
        font: {
            fontFamily: "Raleway-Medium",
            fontSize: "20dp"
        },
        color: "black",
        top: "1dp",
        left: "5dp",
        text: "Username",
        id: "lbl_username"
    });
    $.__views.__alloyId1.add($.__views.lbl_username);
    $.__views.custom_txt_field = Ti.UI.createView({
        top: "30dp",
        left: "5dp",
        right: "7dp",
        height: "40dp",
        id: "custom_txt_field"
    });
    $.__views.__alloyId1.add($.__views.custom_txt_field);
    $.__views.field_codice_partner = Ti.UI.createTextField({
        height: "40dp",
        autocapitalization: Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,
        borderWidth: 1,
        borderColor: "#525252",
        borderRadius: 5,
        color: "black",
        left: "5dp",
        right: "5dp",
        keyboardType: Titanium.UI.KEYBOARD_DEFAULT,
        returnKeyType: Titanium.UI.RETURNKEY_NEXT,
        id: "field_codice_partner",
        value: ""
    });
    $.__views.custom_txt_field.add($.__views.field_codice_partner);
    focusPassword ? $.__views.field_codice_partner.addEventListener("return", focusPassword) : __defers["$.__views.field_codice_partner!return!focusPassword"] = true;
    $.__views.lbl_password = Ti.UI.createLabel({
        font: {
            fontFamily: "Raleway-Medium",
            fontSize: "20dp"
        },
        color: "black",
        top: "90dp",
        left: "5dp",
        text: "Password",
        id: "lbl_password"
    });
    $.__views.__alloyId1.add($.__views.lbl_password);
    $.__views.custom_pwd_field = Ti.UI.createView({
        top: "120dp",
        left: "5dp",
        right: "7dp",
        height: "40dp",
        id: "custom_pwd_field"
    });
    $.__views.__alloyId1.add($.__views.custom_pwd_field);
    $.__views.password = Ti.UI.createTextField({
        height: "40dp",
        autocapitalization: Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,
        borderWidth: 1,
        borderColor: "#525252",
        borderRadius: 5,
        color: "black",
        left: "5dp",
        right: "5dp",
        passwordMask: true,
        keyboardType: Titanium.UI.KEYBOARD_DEFAULT,
        returnKeyType: Ti.UI.RETURNKEY_DONE,
        id: "password",
        value: ""
    });
    $.__views.custom_pwd_field.add($.__views.password);
    login ? $.__views.password.addEventListener("return", login) : __defers["$.__views.password!return!login"] = true;
    changeFocusBorder ? $.__views.password.addEventListener("click", changeFocusBorder) : __defers["$.__views.password!click!changeFocusBorder"] = true;
    $.__views.remembermeChk = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        top: "180dp",
        left: "5dp",
        width: "25dp",
        height: "25dp",
        borderColor: "black",
        borderWidth: 1,
        backgroundColor: "white",
        value: false,
        id: "remembermeChk"
    });
    $.__views.__alloyId1.add($.__views.remembermeChk);
    rememberme ? $.__views.remembermeChk.addEventListener("click", rememberme) : __defers["$.__views.remembermeChk!click!rememberme"] = true;
    $.__views.remembermeLbl = Ti.UI.createLabel({
        font: {
            fontFamily: "Raleway-Medium",
            fontSize: "18dp"
        },
        color: "black",
        top: "180dp",
        left: "35dp",
        text: "Memorizza dati di accesso",
        id: "remembermeLbl"
    });
    $.__views.__alloyId1.add($.__views.remembermeLbl);
    rememberme ? $.__views.remembermeLbl.addEventListener("click", rememberme) : __defers["$.__views.remembermeLbl!click!rememberme"] = true;
    $.__views.buttonLogin = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        top: "250dp",
        left: "5dp",
        right: "5dp",
        height: "50dp",
        font: {
            fontSize: "20dp"
        },
        backgroundImage: "/footer.jpg",
        title: "Accedi",
        id: "buttonLogin"
    });
    $.__views.__alloyId1.add($.__views.buttonLogin);
    login ? $.__views.buttonLogin.addEventListener("click", login) : __defers["$.__views.buttonLogin!click!login"] = true;
    $.__views.buttonRegister = Ti.UI.createButton({
        borderRadius: 5,
        color: "white",
        top: "310dp",
        left: "5dp",
        right: "5dp",
        height: "50dp",
        font: {
            fontSize: "20dp"
        },
        backgroundImage: "/footer.jpg",
        title: "Register",
        id: "buttonRegister"
    });
    $.__views.__alloyId1.add($.__views.buttonRegister);
    register ? $.__views.buttonRegister.addEventListener("click", register) : __defers["$.__views.buttonRegister!click!register"] = true;
    exports.destroy = function() {};
    _.extend($, $.__views);
    __defers["$.__views.field_codice_partner!return!focusPassword"] && $.__views.field_codice_partner.addEventListener("return", focusPassword);
    __defers["$.__views.password!return!login"] && $.__views.password.addEventListener("return", login);
    __defers["$.__views.password!click!changeFocusBorder"] && $.__views.password.addEventListener("click", changeFocusBorder);
    __defers["$.__views.remembermeChk!click!rememberme"] && $.__views.remembermeChk.addEventListener("click", rememberme);
    __defers["$.__views.remembermeLbl!click!rememberme"] && $.__views.remembermeLbl.addEventListener("click", rememberme);
    __defers["$.__views.buttonLogin!click!login"] && $.__views.buttonLogin.addEventListener("click", login);
    __defers["$.__views.buttonRegister!click!register"] && $.__views.buttonRegister.addEventListener("click", register);
    _.extend($, exports);
}

var Alloy = require("alloy"), Backbone = Alloy.Backbone, _ = Alloy._;

module.exports = Controller;