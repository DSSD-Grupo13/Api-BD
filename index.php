<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once './private/autoloader.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$incidentsRepository = new \IncidentsRepository();

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

$app->get("/incidentes/{id_usuario}", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  $id_usuario =  $request->getAttribute('id_usuario');
  \Validations::isValidUserId($id_usuario);
  return $response->withStatus(200)->withJson($incidentsRepository->getIncidentesUsuario($id_usuario));
});

$app->post("/incidentes", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  $idUsuario = $request->getParsedBodyParam('idUsuario');
  $idTipoIncidente = $request->getParsedBodyParam('idTipoIncidente');
  $descripcion = $request->getParsedBodyParam('descripcion');

  \Validations::isValidIncidentTypeId($idTipoIncidente);
  \Validations::isValidUserId($idUsuario);

  $id_incidente = $incidentsRepository->newIncident($idUsuario, $descripcion, $idTipoIncidente);
  $message = "Se confirmó el expediente # $id_incidente";
  return $response->withStatus(200)->withJson(['message' => $message, 'id_incidente' => $id_incidente]);
});

$app->get("/error_code/{id_error_code}", function (Request $request, Response $response, $args)
{
  $error_code =  $request->getAttribute('id_error_code');
  return $response->withStatus(200)->withJson(array('error_code' => $error_code, 'description' => \InvalidArgException::getDescription($error_code)));
});

$app->run();