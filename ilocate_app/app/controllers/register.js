function register(){
    if($.field_email.value.length > 5){

        var api = require("api");

        var params = {
            "email": $.field_email.value,
            "subject": "iLocate registration",
            "message": "Follow this link to complete the registration to the iLocate service"
        };


        api.registerUser(JSON.stringify(params), {
            onSuccess: function(result){
                alert(JSON.stringify(result));
            },
            onError: function(e){
                alert(JSON.stringify(e));
            }
        });
    }



}
