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
var b_index = null;

function start(){
	if(b_index != null){
		b_index.fireEvent('start', {data: parseInt($.txt.value)});
		$.win_settings.close();
	}
}

exports.setData = function(b){
	b_index = b;
};
