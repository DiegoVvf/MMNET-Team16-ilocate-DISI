function changeFocusBorder(){

}


function rememberme(){

}

function login(){
    var api = require("api");
    api.loginUser($.field_codice_partner.value, $.password.value, {
        onSuccess: function(result){
            // alert(JSON.stringify(result));
            Alloy.Globals.TOKEN = result.tokenId;
            $.win_login.close();

        },
        onError: function(e){
            alert(JSON.stringify(e));
        }
    });


}

function focusPassword(){

}


function register(){
    openWindow(Alloy.createController("register").getView());
}
// 43860
