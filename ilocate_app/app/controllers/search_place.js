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
var btn_index = null;
var api = require("api");

var routeOption = "WALK";

var section = null; 
// $.listView.sections = [section];

exports.setData = function(btn){
	btn_index = btn;
};

$.field_search.value = "ruote de thionville, luxembourg";

 
function setWalk(){  
	$.b_walk.backgroundImage = "/b_walk_active.jpg";
	routeOption= "WALK"; 
	
	$.b_car.backgroundImage = "/b_car.jpg";
	$.b_bus.backgroundImage = "/b_bus.jpg";
}

function setCar(){
	$.b_car.backgroundImage = "/b_car_active.jpg";
	routeOption= "CAR"; 
	
	$.b_walk.backgroundImage = "/b_walk.jpg";
	$.b_bus.backgroundImage = "/b_bus.jpg";
}

function setBus(){
	$.b_bus.backgroundImage = "/b_bus_active.jpg";
	routeOption= "BUS"; 
	
	$.b_car.backgroundImage = "/b_car.jpg";
	$.b_walk.backgroundImage = "/b_walk.jpg";
}


function onChange(e){
	Ti.API.info(JSON.stringify(e.value));
	if(e.value.length > 3){
		api.getGpsFromName($.field_search.value, {
		 	onSuccess: function(data){
		 		var format_data = prepareDataForList(data);
		 		section = Ti.UI.createListSection({items: format_data});
		 		$.listView.sections = [section]; 
		 	},
		 	onError: function(data){
		 	 	alert("KO");
		 	}
		 });
	}
	
}


function prepareDataForList(data){
	var result = [];
	
	for(var i in data){
		Ti.API.info(data.geocodeAddress);
		result.push({
			"id_question": data[i].latitude + "," + data[i].longitude,
			"lbl_title":{text: data[i].geocodeAddress}
		});
	}
	
	return result;
}


function onItemClick(e){
	var item = e.section.getItemAt(e.itemIndex);   
	Ti.API.info("ON ITEM CLICK " + JSON.stringify(item));
	btn_index.fireEvent("gps", {"data": item.id_question, "mode": routeOption});
	$.win_search_place.close();
}
