<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require './private/autoloader.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$appointmentsRepository = \TurnosAPIHelper::getAppointmentsRepository();

$app = new \Slim\App;
$container = $app->getContainer();
$container['errorHandler'] = function ($container) {
  return new \ErrorHandler;
};

$app->get("/", function (Request $request, Response $response)
 {
  return $response->withStatus(200)->withHeader('Content-Type', 'text/html')->getBody()->write(file_get_contents('index.html'));
});

$app->get("/consulta-turnos[/[{fecha}]]", function (Request $request, Response $response, $args) use ($appointmentsRepository)
{
  $date =  $request->getAttribute('fecha', date('d-m-Y'));
  \TurnosAPIHelper::isValidDate($date);
  return $response->withStatus(200)->withJson($appointmentsRepository->getAppointments($date));
});

$app->get("/error_code/{id_error_code}", function (Request $request, Response $response, $args)
{
  $error_code =  $request->getAttribute('id_error_code');
  return $response->withStatus(200)->withJson(array('error_code' => $error_code, 'description' => \TurnosAPIException::getDescription($error_code)));
});

$app->get("/turnos[/[{fecha}]]", function (Request $request, Response $response, $args) use ($appointmentsRepository)
{
  $date =  $request->getAttribute('fecha', date('d-m-Y'));
  \TurnosAPIHelper::isValidDate($date);
  return $response->withStatus(200)->withJson($appointmentsRepository->getAvailableAppointments($date));
});

$app->post("/turnos", function (Request $request, Response $response, $args) use ($appointmentsRepository)
{
  $date = $request->getParsedBodyParam('fecha');
  $time = $request->getParsedBodyParam('hora');
  $dni = $request->getParsedBodyParam('dni');
  \TurnosAPIHelper::isValidDate($date);
  \TurnosAPIHelper::isValidTime($time);
  \TurnosAPIHelper::isValidDni($dni);
  $appointment = array('dni' => $dni, 'hora' => $time, 'fecha' => $date, 'id' => '');

  $success = $appointmentsRepository->appoint($date, $time, $dni);
  if (!$success)
    throw new \TurnosAPIException(TurnosAPIException::ALREADY_APPOINTED);

  $id_turno = $appointmentsRepository->getLastId();
  $appointment['id'] = $id_turno;
  $message = "Te confirmamos el turno nro $id_turno para $dni, a las $time del dia $date";

  return $response->withStatus(200)->withJson(array('message' => $message, 'appointment' => $appointment));
});

$app->run();