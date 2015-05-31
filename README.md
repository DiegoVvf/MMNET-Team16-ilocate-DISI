# MMNET-Team16-ilocate-DISI

Nowadays, in the Internet, there are lots of application that provide a navigation tool for smartphone, but they are usually limited to the outdoor part.
The I-locate project aimed to allow the people to have an application for navigation also in the indoor part of their travel.
The project consists in map the whole buildings of DISI (Povo1 and Povo2) and develop an application for smartphone that takes the students, professors or visitors to the right room or the right office exploiting the QR codes spread in the structures for the localization. It should work like a normal navigator, with the capability to guide the users in indoor environments.
Outdoor the application uses the GPS to track the person and public means of transport, autos or a walk path to create the route.
Indoor it uses the internal maps and the path is created starting from a position obtained after a scan of a QR code with the smartphone.
The i-locate toolkit and its templates apps permit to develop the application, configuring all the components that are necessary for various operations. The proxy and routing parts that will be put in a remote server, have a duty to respond to requests of localization, search the destination and compute the route. The base app that will be installed on the device by mean of Titanium will be able to take inputs from the users, give the current position scanning QR codes and display the map with the route. The maps of the floors, that will be upload in the i-locate’s portal, represent the environments and each room has a label with the name and all the locals are linked with some graphs.

In order to use this application you need to install the apk file on your smartphone, but you need to have our laptop vith the local server running and connected to the same network of your device
