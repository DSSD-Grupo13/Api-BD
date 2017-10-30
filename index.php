<?php
require './private/autoloader.php';

$app = new \Slim\Slim();

$app->get('/', function () use ($app) {
  $res = $app->response;
  $res->setStatus(200);
  $res->setBody(file_get_contents('index.html'));
  $res->headers->set('Content-Type', 'text/html');
  $res->finalize();
});

$app->post('/solicitar-presupuesto', function () use ($app) {
  try {
    $productos = json_decode($app->request->getBody());
    $res = $app->response;
    $res->setStatus(200);
    $res->setBody(crearPresupuestoJSON($productos));
    $res->headers->set('Content-Type', 'application/json');
    $res->finalize();
  } catch (\Exception $e) {
    $res = $app->response;
    $res->setBody($e->getMessage());
    $res->headers->set('Content-Type', 'text/plain');
    $res->setStatus(500);
    $res->finalize();
  }
});

$app->run();