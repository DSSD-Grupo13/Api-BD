<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once './private/autoloader.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$incidentsRepository = new \IncidenteRepository();
$app = new \Slim\App;
$container = $app->getContainer();
$container['errorHandler'] = function ($container) {
  return new \ErrorHandler;
};

$app->get("/", function (Request $request, Response $response)
 {
  return $response->withStatus(200)->withHeader('Content-Type', 'text/html')->getBody()->write(file_get_contents('index.html'));
});

$app->get("/incidente/{id_incidente}", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  $id_incidente =  $request->getAttribute('id_incidente');
  if (is_null($id_incidente))
    throw new \Exception('Parametro id_incidente no seteado');

  return $response->withStatus(200)->withJson($incidentsRepository->getIncidente($id_incidente));
});

$app->get("/incidentes", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  return $response->withStatus(200)->withJson($incidentsRepository->getIncidentes());
});

/*$app->get("/error_code/{id_error_code}", function (Request $request, Response $response, $args)
{
  $error_code =  $request->getAttribute('id_error_code');
  return $response->withStatus(200)->withJson(array('error_code' => $error_code, 'description' => \TurnosAPIException::getDescription($error_code)));
});

$app->get("/turnos[/[{fecha}]]", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  $date =  $request->getAttribute('fecha', date('d-m-Y'));
  \TurnosAPIHelper::isValidDate($date);
  return $response->withStatus(200)->withJson($incidentsRepository->getAvailableAppointments($date));
});

$app->post("/turnos", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  $date = $request->getParsedBodyParam('fecha');
  $time = $request->getParsedBodyParam('hora');
  $dni = $request->getParsedBodyParam('dni');
  \TurnosAPIHelper::isValidDate($date);
  \TurnosAPIHelper::isValidTime($time);
  \TurnosAPIHelper::isValidDni($dni);
  $appointment = array('dni' => $dni, 'hora' => $time, 'fecha' => $date, 'id' => '');

  $success = $incidentsRepository->appoint($date, $time, $dni);
  if (!$success)
    throw new \TurnosAPIException(TurnosAPIException::ALREADY_APPOINTED);

  $id_turno = $incidentsRepository->getLastId();
  $appointment['id'] = $id_turno;
  $message = "Te confirmamos el turno nro $id_turno para $dni, a las $time del dia $date";

  return $response->withStatus(200)->withJson(array('message' => $message, 'appointment' => $appointment));
});*/

$app->run();