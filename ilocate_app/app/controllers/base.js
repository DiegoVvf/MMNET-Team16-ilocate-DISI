Alloy.Globals.openWindows = [];

openWindow = function(win, animate){ 
	var last_win_id = Alloy.Globals.openWindows[Alloy.Globals.openWindows.length-1];
	Ti.API.info(win.id + "   " + last_win_id);
	if(last_win_id === win.id){
		Ti.API.info(" ###########################################################");
		Ti.API.info(" ######## This window is already open, nothing to do! ######");
		Ti.API.info(" ###########################################################"); 
	}
	else{
		win.open({animated:false});
		Alloy.Globals.openWindows.push(win.id);
	} 
	
	win.addEventListener('close', function(){
		Ti.API.info('Got close event for window ' );
		Alloy.Globals.openWindows.pop();
	});
};

close = function(win){
	win.close({animated: false});
};






//============================================================
//============== DEBUG FUNCTION ==============================
//============================================================
log = function(msg){
	if(Alloy.Globals.DEBUG){
		Ti.API.info("=== DEBUG API ===");
		Ti.API.info(msg);
	}
};