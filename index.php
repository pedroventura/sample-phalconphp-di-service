<?php

use Phalcon\Mvc\Micro;
use Phalcon\Http\Response;
use GeoIp2\Database\Reader;

define('PATH_BASE', __DIR__);

// iniciamos la micro-aplicación de Phalcon para servicios web
$app = new Phalcon\Mvc\Micro();
// inyector de dependencias de Phalcon
$di = new \Phalcon\DI\FactoryDefault();

// incluimos el autocargador de composer y dependencias internas
require_once __DIR__ . '/vendor/autoload.php';
// incluimos nuestro fichero de servicios ad-hoc
require_once __DIR__ . '/config/services.php';

// seteamos el inyector de dependencias la aplicación
$app->setDI($di);

// recuperamos las llamadas a la URL base y las entregamos vacías
$app->get('/', function() use($app) {
	// empty
	// podemos incluir una cabecera 204
});

// petición [GET] para obtener datos GEO de usuario. 
// código demos para demostración uso inyector de servicios
$app->get('/testIp', function() use($app) {

	$ipUsuario = $app->di->get('ipUsuario');
	$countryCode = $app->di->get('getGEO', [$ipUsuario]);

	$app->response->setContentType('application/json', 'UTF-8')
	->setHeader('Access-Control-Allow-Origin', '*')
	->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');
	
	$app->response->setStatusCode(200)->setContent(json_encode(array(
		'ipUser' => $ipUsuario,
		'geoData' => $countryCode))
	);
	$app->response->sendHeaders()->send();
	
});

// response para peticiones no registradas
$app->notFound(function () use ($app) {
	$app->response->setStatusCode(404, "Not Found")->sendHeaders();
	echo 'This is crazy, but this page was not found!';
});

$app->handle();
