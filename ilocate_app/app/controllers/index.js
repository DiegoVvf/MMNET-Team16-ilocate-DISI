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
Alloy.Globals.MY_POSITION = "";

exports.baseController = "base";
var tiosm = require('eu.ilocate.tiosm');
var toolkit = require("eu.ilocate.toolkit");
var api = require("api");
var mapview = tiosm.createView();

var utterance = require('bencoding.utterance'),
textToSpeech = utterance.createSpeech();


var postData = {};
postData["positionType"] = "relative";
postData["objectId"] = Ti.Platform.macaddress;
postData["localizationSystems"] = [];


var alertGps = false;
var alertWifi = false;


var temporalRouteCOntainer = [];

var b_ls_nav = null;


$.v_map.add(mapview);

$.index.open();

function doClick(e) {
	var controller = Alloy.createController("search_place");
	controller.setData($.b_search);
	openWindow(controller.getView());

	// mapview.polyline("cnfxGobzbAdEaBrD}@lCUz@Cn@CxBEL@`CPd@DXBD?vEfAJDvA^tAf@fAj@LFAPNJFD");
	// mapview.setRoutesMarker(
			// [{"latitude": 46.07730349, "longitude": 11.1160898,"relativeDirection": "DEPART", "streetName": "Lungadige Giacomo Leopardi","distance": 828.0460035273454},
	   		// {"latitude": 46.0700497, "longitude": 11.1159017, "distance": 16.935398230466255,"relativeDirection": "RIGHT", "streetName": "path"},
	   		// {"latitude": 46.0699714, "longitude": 11.1157534, "distance": 4.863301359724345, "relativeDirection": "CONTINUE", "streetName": "Pista Ciclabile Valle dell'Adige"}]
	// );
	// setLocation(46.07730179930095, 11.116082668304443);
}

$.index.addEventListener("open", function(){
	getLastKnownBestLocation(function(data){
		if(data){
			// data.latitude = 9.5828711;
			// data.longitude = 6.1183272;
			setLocation(parseFloat(data.latitude), parseFloat(data.longitude));
			// setLocation(46.075252,11.117449);
			Alloy.Globals.MY_POSITION = data.latitude+","+data.longitude;
		}
	});

	// getAccurateLocation();
});


$.b_search.addEventListener("gps", function(e){
	Ti.API.info("BUTTON SEARCH EVENT ");
	if(Alloy.Globals.MY_POSITION.length > 0){
		api.getRoutes(Alloy.Globals.MY_POSITION, e.data, e.mode, {
			success: function(data){
				Ti.API.info(JSON.stringify(data["markers"]));
				temporalRouteCOntainer = data["markers"];
				mapview.polyline(data["legGeometry"]);
				mapview.setRoutesMarker(data["markers"]);
				$.b_step_by_step_route.backgroundImage= "/b_route_active.png";
				$.b_step_by_step_route.active = 1;
			}
		});
	}
});


$.b_search.addEventListener("start", function(e){
	if(temporalRouteCOntainer.length > 0){
		iterateThroughTemporalRoute(0, e.data);
	}else{
		alert("First assure that you have a visible route on the map, and than press start!!");
	}

});

$.b_search.addEventListener("nav_list", function(e){
	b_ls_nav = e.data;
});

function iterateThroughTemporalRoute(i, t){
	setTimeout(function(){
		Ti.API.info("===> " + JSON.stringify(temporalRouteCOntainer[i]));
		setLocation(parseFloat(temporalRouteCOntainer[i]["latitude"]), parseFloat(temporalRouteCOntainer[i]["longitude"]));
		i+=1;
		if(i<temporalRouteCOntainer.length)
			iterateThroughTemporalRoute(i, t);

		return;
	}, t*1000);
}

function iterateThroughTemporalRoute_temp(i, f){
	if(i < temporalRouteCOntainer.length){
		var g1 = temporalRouteCOntainer[i];
		var g2 = temporalRouteCOntainer[i+1];

		var plat = parseFloat(g1["latitude"]) + (parseFloat(g2["latitude"]) - parseFloat(g1["latitude"]) * f);
		var plot = parseFloat(g1["longitude"]) + (parseFloat(g2["longitude"]) - parseFloat(g1["longitude"]) * f);
		Ti.API.info(JSON.stringify(g1) +" "+ JSON.stringify(g2) + " " + f);
		Ti.API.info(plat + " " + plot);
		setLocation(plat, plot);

		setTimeout(function(){
			if(f >= 1){
				iterateThroughTemporalRoute_temp(i+1, 0);
			}else{
				iterateThroughTemporalRoute_temp(i, f+0.00001);
			}
		}, 10000);
	}
}

function getAccurateLocation(){
	Ti.API.info("===getAccurateLocation===");
	toolkit.getLocation(function(gpsdata){
		// Ti.API.info(JSON.stringify(gpsdata));
		// alert("getAccurateLocation call");
		if(gpsdata.success == "true"){
			var container = JSON.parse(gpsdata.container);
			postData["localizationSystems"].push({
							"localizationSystem_id":"gps",
							"entries":[ {
											"lat": container.latitude,
											"lon": container.longitude,
											"accuracy": container.accuracy
										} ]
						});

			// setLocation(parseFloat(container.latitude), parseFloat(container.longitude));

			toolkit.getWiFiConnectionInfo(function(wifidata){
				if(wifidata.success == "true"){
					Ti.API.info(wifidata.container);
					postData["localizationSystems"].push({
							"localizationSystem_id":"wifi",
							"entries":JSON.parse(wifidata["container"])
						});
					getIndoorLocation(postData);
				}else if(!alertWifi){
					alertWifi = true;
					alert("WIFI is disable");
					getIndoorLocation(postData);
				}
			});

		}else if(!alertGps){
			alertGps = true;
			alert("GPS is disable");
		}
	});
}


function getIndoorLocation(data){
	api.getIndoorLocation(data, {
		onSuccess: function(res){
			var response = res.response;
			// if(!res.indoor){
// 				46.07527,11.11759
// 				46.075333, 11.117386
//				46.075192, 11.117923
			// Ti.API.info((response.position.lat - 46.07527) + "  " + (response.position.lon - 11.11759));
			if(Math.abs(response.position.lat - 46.07527) < 0.0002 &&
				Math.abs(response.position.lon - 11.11759) < 0.0004){
				$.b_building.active = 1;
				$.b_building.backgroundImage = "/b_building_active.png";
			}else if($.b_building.active == 1){
				$.b_building.active = 0;
				$.b_building.backgroundImage = "/b_building.png";
			}


			setLocation(parseFloat(response.position.lat), parseFloat(response.position.lon));
			// }
			// Ti.API.info(JSON.stringify(response));
			// Ti.API.info(response.indoor + "  " + (response.indoor == true));

			// if(response.indoor){

			// setTimeout(function(){
				// getAccurateLocation();
			// }, 1000);
		},
		onError: function(res){
			alert("KO");
		}
	});
}


function getLastKnownBestLocation(callback){
	Ti.API.info("===getLastKnownBestLocation===");
	toolkit.getLastKnownBestLocation(function(data){
		Ti.API.info(JSON.stringify(data));
		if(!data.message){
			callback(JSON.parse(data.container));
		}else{
			alert(data.message);
			callback();
		}
	});
}

// da decidere se tenere o meno
function getWifiConnectionInfo(){
	Ti.API.info("===getWifiConnectionInfo===");
	toolkit.getWiFiConnectionInfo(function(data){
		if(data.success == "true"){
			// Ti.API.info(data.container);
			postData["localizationSystems"].push({
					"localizationSystem_id":"wifi",
					"entries":JSON.parse(data["container"])
				});
		}else if(!alertWifi){
			alertWifi = true;
			alert("WIFI is disable");
		}
	});
}

function speak(msg){
	if(textToSpeech.isSpeaking()){
		return;
	}

	textToSpeech.startSpeaking({
		text: msg
	});
}


function setLocation(lat, lon){
	mapview.setLocation({"latitude": lat,
						 "longitude": lon},
						 function(result){
							 if(b_ls_nav != null){
							 	b_ls_nav.fireEvent("update_row", {"data": lat +","+ lon});
							 }
							 if(result.success = "true" && parseInt(result.marked) == 0){
							 	speak(result.message);
							 }
						 });
}



function openSetting(){
	var controller = Alloy.createController("settings");
	controller.setData($.b_search);
	openWindow(controller.getView());
}



function showStepByStepRoute(){
	if($.b_step_by_step_route.active == 1){
		var width = Ti.Platform.displayCaps.platformWidth;
		var win = Alloy.createController("navigation_list");
		win.setData(temporalRouteCOntainer, $.b_search);
		var anim = Ti.UI.createAnimation({
            right: width,
            duration: 1000
		});

		win.getView().open({animate: anim});
	}
}

function openMapBuilding(){
	// if($.b_building.active == 1){
		openWindow(Alloy.createController("indoor").getView());
	// }
}

function startNavigation(){
	if(temporalRouteCOntainer.length > 0){
		iterateThroughTemporalRoute(0, 5);
	}
}

function openLogin(){
	openWindow(Alloy.createController("login").getView());
}




















