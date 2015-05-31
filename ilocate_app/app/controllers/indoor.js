/*
Copyright 2015 U-Hopper srl

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/
$.img_map.image= "/layout.png";

function scan(){

    var titaniumBarcode = require('com.mywaysolutions.barcode');

    titaniumBarcode.scan({
          success:function(e) {
                Ti.API.info('Success called with barcode: ' + JSON.stringify(e));
                var api = require("api");
                api.getIndoorLocation(prepareDataForProxy(), {
                    onSuccess: function(result){
                        alert(JSON.stringify(result.response));
                    },
                    onError: function(e){
                        alert(e);
                    }
                });

          },

          error:function(err) {
                Ti.API.info(JSON.stringify(err));
          },

          cancel:function() {
                Ti.API.info('Cancel received');
          }
    });

}


function prepareDataForProxy(id){
    var postData = {};
    postData["positionType"] = "relative";
    postData["objectId"] = Ti.Platform.macaddress;
    postData["localizationSystems"] = [];

    postData["localizationSystems"].push(
                                         {
                "localizationSystem_id":"qrcode",
                "entries":
                [
                  {
                    "id":"637" //id
                  }
                ]
              }
         );

return postData;

}

