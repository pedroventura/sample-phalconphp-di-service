<?php
use GeoIp2\Database\Reader;

// -- Registramos los servicios necesarios --

/**
* Servicio para recuperar el country code de una ip 
*/
$di->set('getGEO', function($ip) use ($app) {
	if (!empty($ip)) {
		$reader = new Reader(PATH_BASE . '/files/maxmind/GeoLite2-City.mmdb');
		$record = $reader->city($ip);
		$res = array(
			'countryCode' => $record->country->isoCode,
			'city' => $record->city->names['es'],
			'location' => array(
				'latitud' => $record->location->latitude,
				'longitud' => $record->location->longitude,
				'timeZone' => $record->location->timeZone)
			);
		return $res;
	}
	return false;
});

/**
* Servicio para obtener la IP del cliente
*/
$di->set('ipUsuario', function() use ($app) {
	return $app->request->get('ip') !== null  ? $app->request->get('ip') : $app->request->getClientAddress(true);
});
