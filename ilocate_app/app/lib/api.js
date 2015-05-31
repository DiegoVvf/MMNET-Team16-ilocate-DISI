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

exports.getIndoorLocation = function(data, callback){
	var header = {"X-ILOCATE-USER-TOKEN" : Alloy.Globals.TOKEN};
	__httpRequest("POST", Alloy.CFG.url_proxy, JSON.stringify(data), header, callback);
};

/*
exports.getQR = function(data, callback){
	var header = {"QR" : Alloy.Globals.TOKEN};
	__httpRequest("POST", Alloy.CFG.url_db, JSON.stringify(data), header, callback);
};
*/

exports.getGpsFromName = function(name, callback){
	__httpRequest('GET',  Alloy.CFG.url_geocoder + name, {}, null, callback);
};


exports.getRoutes = function(startGps, endGps, mode,  callback){
	var result = {};
	var result_arr = {"markers":[], "legGeometry": []};
	// var from = plan.getElementsByTagName("from"); 8000
	var url = Alloy.CFG.url_route + "plan?fromPlace="+startGps+"&toPlace="+endGps+"&time=11%3A56am&date=11-18-2014&mode="+mode+"&arriveBy=false&showIntermediateStops=false&_=1416308185460";
	// Ti.API.info(url);
	__httpRequest("GET", url, {}, "application/json", {
		onSuccess: function(data){

			Ti.API.info(JSON.stringify(data));
			var result = {
				"date": data["requestParameters"]["fromPlace"],
				"from" : {
						"name": data["plan"]["from"]["name"],
						"lat": data["plan"]["from"]["lat"],
						"lon": data["plan"]["from"]["lon"]
					},
				"to" : {
						"name": data["plan"]["to"]["name"],
						"lat": data["plan"]["to"]["lat"],
						"lon": data["plan"]["to"]["lon"]
				},
				"itineraries":[],
				"legGeometry": ""
			};

			var itineraries = data["plan"]["itineraries"];
			for(var i=0; i< itineraries.length; i++){
				var legs = itineraries[i]["legs"];
				var _legs = {};
				for(var j=0; j<legs.length; j++){
					log("geometry: " + JSON.stringify(legs[j]["legGeometry"]));
					_legs["mode"] = legs[j]["mode"];
					_legs["steps"] = [];
					result_arr["legGeometry"] = legs[j]["legGeometry"]["points"];
					var steps = legs[j]["steps"];
					for(var k=0; k<steps.length; k++){
						result_arr["markers"].push(
							{
								"latitude": ""+steps[k]["lat"],
								"longitude": ""+steps[k]["lon"],
								"relativeDirection": steps[k]["relativeDirection"],
								"streetName": steps[k]["streetName"],
								"distance": ""+steps[k]["distance"]
							}
						);

					}
					result_arr["markers"].push(
						{
							"latitude": ""+legs[j]["to"]["lat"],
							"longitude": ""+legs[j]["to"]["lon"],
							"relativeDirection": "",
							"streetName": legs[j]["to"]["name"],
							"distance": "0.000001"
						}
					);

				}
				log("=====> " + JSON.stringify(result_arr));
				// result["itineraries"].push(_legs);
				if(callback && callback.success){
					callback.success(result_arr);
				}
			}


		},
		onError: function(data){

		}
	});
};




exports.registerUser = function(payload, callback){
	var header = {"Content-Type": "application/json"};
	var url = Alloy.CFG.url_openam + "/openam/json/users?_action=register";
	__httpRequest("POST", url, payload, header, callback);
};


exports.loginUser = function(username, password, callback){
	var header = {  "X-OpenAM-Username": username,
					"X-OpenAM-Password": password};

	__httpRequest("POST", Alloy.CFG.url_openam + "/openam/json/authenticate", {}, header, callback);
};


function __httpRequest(method, url, postData, header, callback){
	// log("postData: " + JSON.stringify(postData));
	var xhr = Titanium.Network.createHTTPClient();
	if(header != null){
		for(var i in header){
			xhr.setRequestHeader( i, header[i] );
		}
	}

	xhr.open(method, url);

	xhr.onload = function(e) {
		log("request arrived..");
		var items = JSON.parse(this.responseText);

		callback.onSuccess(items);
	};

	xhr.onerror = function(e) {
		// log("ERROR " + JSON.stringify(e));
		try{
			var items = JSON.parse(this.responseText);
			callback.onError(items.message);
		}catch(error){
			callback.onError("error http request");
		}
	};

	if (Titanium.Network.online) {
		log("sending request ...");
		xhr.send(postData);
    } else {
        alert(Alloy.Globals.no_internet_error);
		if (callback.error) { callback.onError(); }
    };

}





//============================================================
//============== DEBUG FUNCTION ==============================
//============================================================
function log(msg){
	Ti.API.info("=== DEBUG API ===");
	Ti.API.info(msg);
}
