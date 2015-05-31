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
var dict = {};



exports.setData = function(data, b_index){
	
	var rows = [];
	
	for(var i in data){ 
		rows.push({
			id_question: data[i].latitude +","+ data[i].longitude,
			v_container:{backgroundColor : "white"},
			img:{"image": (data[i].relativeDirection == "LEFT" ? "/left.jpg" : 
							(data[i].relativeDirection == "RIGHT" ? "/right.jpg" : "/continue.jpg"))},
			lbl_street_name : {text : data[i].streetName},
			lbl_distance: {text : parseInt(data[i].distance) + " meters"}
		});
		
		dict[data[i].latitude +","+ data[i].longitude] = i;
	}
	
	
	var section = Ti.UI.createListSection({items: rows}); 
	$.listView.sections = [section];
	
	
	if(b_index != null){ 
		b_index.fireEvent('nav_list', {"data": $.b_ls_nav});
	}

	
};


$.b_ls_nav.addEventListener("update_row", function(e){  
	// Ti.API.info(JSON.stringify($.listView.sections[0]));
	// Ti.API.info(e.data);
	// var section = $.listView.sections[0];
	// var item = section.items[parseInt(dict[e.data])];
	// Ti.API.info(JSON.stringify(item));
	// item.v_container.backgroundColor = "gray"; 
// 	
	// section.updateItemAt(parseInt(dict[e.data]), item);
});

