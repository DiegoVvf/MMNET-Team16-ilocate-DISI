#iLocate Android 

----------------------------------

This is an Android application build in Titanium. It is used Alloy framework. 
This application uses Open Street Map. Indoor/outdoor location and asset management through open geodata. An application based on indoor and outdoor localization of people and objects.

To make this project work you will need 3 external modules. 

1. [bencoding.uterance](https://github.com/benbahrenburg/Utterance) lets you use your device's native Text to Speech and Speech to Text capabilities in your Titanium projects.

2. [eu.ilocate.tiosm](https://gitlab.com/ilocate/ilocate-app-tiosm) lets you use OSM libraries in Titanium. 

3. [eu.ilocate.toolkit](https://gitlab.com/ilocate/ilocate-app-outdoorlocalization) allows to access all wifi details, gps, and mac adress of your mobile.  

Information on how to include a module in your Titanium application can be found [here](http://docs.appcelerator.com/titanium/3.0/#!/guide/Titanium_Module_Concepts).

Once the modules are successfully included you can run and modify the application. 


## How it works

Once the application is open, for the outdoor localization, it get's gps and wifi data if they are enabled. 
This data is send in the below format to the proxy: 

<code> 

     {
       "positionType":"relative",
       "objectId":"mac_address of device",
       "localizationSystems":[
         {
            "localizationSystem_id":"gps",
            "entries":[
                {
                 "latitude":"value",
                 "longitude": "value"
                }
             ]
          },
          {
            "localizationSystem_id":"wifi",
            "entries":[
                {
                 "wifi_name":"value",
                 "rssi": "value"
                }
             ]
          }
        ]
    }
    

The proxy url can be changed at **_app/config.json_**
<code>

	"url_proxy": "http://ilocate.u-hopper.com/api/locationbasedondata"

The proxy will answer back with the best position

<code>
         
    "response":
        {"position":{
             "lat":43.774469,
             "lon":11.252209},
         "accuracy":"0.3",
         "indoor":true,
         "source":"gps, wifi"
         }

Once we get the position we draw on map. 

If we want to serach for a place, and have step by step navigation. Press search button. It will open a page were you can start writing the name of the place, and autocomlplete will start suggesting for the place you may be searching. 

This is done by http request to the geocoder url, you can find it at **_app/config.json_**  url_geocoder

<code>
         
    "url_geocoder": "http://5.249.155.8:8080/geocoder/geocodeList/"
    
Once we found the right place and click on it, the application will call the routing service. That will send a step by step naviation and draw it on the map. 

The routing url can be found at **_config.json_**
<code>
    
    "url_route": "http://178.63.0.136:9800/otp/routers/default/" 
    
The user can decide to register and login. This is done by an http request to the url of openam. 

The openam url can be found in **_config.json_**
<code>

    "url_openam": "http://01.cloud.i-locate.eu:8180"
    
If the user is indoor, the indoor button to view the map of the building will be enabled. 
    
    
All the http request functions are found in **_app/lib/api.js_**

The logic of the application is found at **_app/controllers_**

All the application configurations are found at **_tiapp.xml_**

----------------------------------
Remark: the application currently assumes the routing engine is run locally. Further, it uses an unofficial deployment of the proxy component. This can be changed by editing config.json

config.json is the file where all cinfigurations url are setted. 
