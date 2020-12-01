<?php

require __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Clases\Usuario;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use Clases\Token;
use Clases\Archivos;

use App\Controllers\UserController;
use App\Controllers\MateriaController;
use App\Controllers\Alumnos_MateriasController;
use App\Controllers\TurnoController;
use App\Middlewares\AuthAdminMiddleware;
use App\Middlewares\AuthClienteMiddleWare;
use App\Middlewares\JsonMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\AuthProfesorMiddleware;
use App\Models\Alumnos_Materias;
use App\Models\Turno;
use Config\Database;

new Database;


$app = AppFactory::create();
$app->setBasePath('/RSP/SegundoParcial/public');
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();




$app->post('/users', UserController::class.':add');//Punto 1 agregar comprobacion de tipo
$app->post('/login', UserController::class.':login');//Punto 2
$app->post('/mascota', MascotaController::class.':addMascota')->add(new AuthAdminMiddleware);//Punto 3 agregar generador de Id

$app->group('/turno', function (RouteCollectorProxy $group) {
    
    $group->post('[/]',TurnoController::class.":addTurno")->add(new AuthClienteMiddleware);//Punto 4
    
    $group->get('[/]',TurnoController::class.":getAll")->add(new AuthAdminMiddleware);//Punto 5
    
    $group->get('/{id}',TurnoController::class.":getTurnos");//Punto 6
    
});

$app->get('/factura', TurnoController::class.':getFactura')->add(new AuthAdminMiddleware);//Punto 7

$app->add(new JsonMiddleware);




$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run();


