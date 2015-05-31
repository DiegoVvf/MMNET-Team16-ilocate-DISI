<?php

return array(
	'db.connectionString' => 'mysql:host=proxy.local;dbname=ilocatedb',
	'db.username' => 'root',
	'db.password' => '',
	// mqtt config
	'publish.to.mqtt' => true,
	'mqtt.domain' => '5.249.155.8',
	'mqtt.port' => 1883,
	'mqtt.name' => 'Proxy',
	'mqtt.pilot' => 'pilot',
	'mqtt.asset' => 'asset',
	'mqtt.person' => 'person',
	// qrcode config
	'qrcode.position.service' => 'http://portal.i-locate.eu:8080/portal-web/service/qrposition?',
	// openam config
	'openam.auth' => false,
);