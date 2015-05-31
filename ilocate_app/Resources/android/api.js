function __httpRequest(method, url, postData, header, callback) {
    var xhr = Titanium.Network.createHTTPClient();
    if (null != header) for (var i in header) xhr.setRequestHeader(i, header[i]);
    xhr.open(method, url);
    xhr.onload = function(e) {
        log("request arrived..");
        var items = JSON.parse(this.responseText);
        callback.onSuccess(items);
    };
    xhr.onerror = function(e) {
        try {
            var items = JSON.parse(this.responseText);
            callback.onError(items.message);
        } catch (error) {
            callback.onError("error http request");
        }
    };
    if (Titanium.Network.online) {
        log("sending request ...");
        xhr.send(postData);
    } else {
        alert(Alloy.Globals.no_internet_error);
        callback.error && callback.onError();
    }
}

function log(msg) {
    Ti.API.info("=== DEBUG API ===");
    Ti.API.info(msg);
}

exports.getIndoorLocation = function(data, callback) {
    var header = {
        "X-ILOCATE-USER-TOKEN": Alloy.Globals.TOKEN
    };
    __httpRequest("POST", Alloy.CFG.url_proxy, JSON.stringify(data), header, callback);
};

exports.getGpsFromName = function(name, callback) {
    __httpRequest("GET", Alloy.CFG.url_geocoder + name, {}, null, callback);
};

exports.getRoutes = function(startGps, endGps, mode, callback) {
    var result_arr = {
        markers: [],
        legGeometry: []
    };
    var url = Alloy.CFG.url_route + "plan?fromPlace=" + startGps + "&toPlace=" + endGps + "&time=11%3A56am&date=11-18-2014&mode=" + mode + "&arriveBy=false&showIntermediateStops=false&_=1416308185460";
    __httpRequest("GET", url, {}, "application/json", {
        onSuccess: function(data) {
            Ti.API.info(JSON.stringify(data));
            ({
                date: data["requestParameters"]["fromPlace"],
                from: {
                    name: data["plan"]["from"]["name"],
                    lat: data["plan"]["from"]["lat"],
                    lon: data["plan"]["from"]["lon"]
                },
                to: {
                    name: data["plan"]["to"]["name"],
                    lat: data["plan"]["to"]["lat"],
                    lon: data["plan"]["to"]["lon"]
                },
                itineraries: [],
                legGeometry: ""
            });
            var itineraries = data["plan"]["itineraries"];
            for (var i = 0; i < itineraries.length; i++) {
                var legs = itineraries[i]["legs"];
                var _legs = {};
                for (var j = 0; j < legs.length; j++) {
                    log("geometry: " + JSON.stringify(legs[j]["legGeometry"]));
                    _legs["mode"] = legs[j]["mode"];
                    _legs["steps"] = [];
                    result_arr["legGeometry"] = legs[j]["legGeometry"]["points"];
                    var steps = legs[j]["steps"];
                    for (var k = 0; k < steps.length; k++) result_arr["markers"].push({
                        latitude: "" + steps[k]["lat"],
                        longitude: "" + steps[k]["lon"],
                        relativeDirection: steps[k]["relativeDirection"],
                        streetName: steps[k]["streetName"],
                        distance: "" + steps[k]["distance"]
                    });
                    result_arr["markers"].push({
                        latitude: "" + legs[j]["to"]["lat"],
                        longitude: "" + legs[j]["to"]["lon"],
                        relativeDirection: "",
                        streetName: legs[j]["to"]["name"],
                        distance: "0.000001"
                    });
                }
                log("=====> " + JSON.stringify(result_arr));
                callback && callback.success && callback.success(result_arr);
            }
        },
        onError: function(data) {}
    });
};

exports.registerUser = function(payload, callback) {
    var header = {
        "Content-Type": "application/json"
    };
    var url = Alloy.CFG.url_openam + "/openam/json/users?_action=register";
    __httpRequest("POST", url, payload, header, callback);
};

exports.loginUser = function(username, password, callback) {
    var header = {
        "X-OpenAM-Username": username,
        "X-OpenAM-Password": password
    };
    __httpRequest("POST", Alloy.CFG.url_openam + "/openam/json/authenticate", {}, header, callback);
};