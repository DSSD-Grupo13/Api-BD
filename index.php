<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once './private/autoloader.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$incidentTypesRepository = new \IncidentTypesRepository;
$incidetStatesRepository = new \IncidentStatesRepository;
$usersRepository = new \UserRepository;
$incidentsRepository = new \IncidentsRepository($incidentTypesRepository, $incidetStatesRepository, $usersRepository);

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
  $incident_id =  $request->getAttribute('id_incidente');
  if (is_null($incident_id))
    throw new \Exception('Parametro id_incidente no seteado');

  return $response->withJson($incidentsRepository->getIncidente($incident_id), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/incidentes", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  return $response->withJson($incidentsRepository->getIncidentes(), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/tipos-incidente/{id_tipo_incidente}", function (Request $request, Response $response, $args) use ($incidentTypesRepository)
{
  $id_tipo_incidente =  $request->getAttribute('id_tipo_incidente');
  \Validations::isValidIncidentTypeId($id_tipo_incidente);
  return $response->withJson($incidentTypesRepository->getIncidentType($id_tipo_incidente), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/tipos-incidente", function (Request $request, Response $response, $args) use ($incidentTypesRepository)
{
  return $response->withJson($incidentTypesRepository->getIncidentTypes(), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/estados-incidente/{id_estado_incidente}", function (Request $request, Response $response, $args) use ($incidetStatesRepository)
{
  $id_estado_incidente =  $request->getAttribute('id_estado_incidente');
  \Validations::isValidIncidentStateId($id_estado_incidente);
  return $response->withJson($incidetStatesRepository->getIncidentState($id_estado_incidente), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/estados-incidente", function (Request $request, Response $response, $args) use ($incidetStatesRepository)
{
  return $response->withJson($incidetStatesRepository->getIncidentStates(), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/incidentes/{id_usuario}", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  $user_id =  $request->getAttribute('id_usuario');
  \Validations::isValidUserId($user_id);
  return $response->withJson($incidentsRepository->getIncidentesUsuario($user_id), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/usuarios/{id_usuario}", function (Request $request, Response $response, $args) use ($usersRepository)
{
  $user_id =  $request->getAttribute('id_usuario');
  \Validations::isValidUserId($user_id);
  return $response->withJson($usersRepository->getUser($user_id), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/usuarios", function (Request $request, Response $response, $args) use ($usersRepository)
{
    return $response->withJson($usersRepository->getUsers(), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->post("/usuarios", function (Request $request, Response $response, $args) use ($usersRepository)
{
  $nombreUsuario = $request->getParsedBodyParam('nombreUsuario');
  $mail = $request->getParsedBodyParam('mail');
  $contrasena = $request->getParsedBodyParam('contrasena');
  $nombre = $request->getParsedBodyParam('nombre');
  $apellido = $request->getParsedBodyParam('apellido');
  $dni = $request->getParsedBodyParam('dni');

  \Validations::IsNotEmpty($nombreUsuario, 'nombreUsuario');
  \Validations::IsNotEmpty($mail, 'mail');
  \Validations::IsNotEmpty($contrasena, 'contrasena');
  \Validations::IsNotEmpty($nombre, 'nombre');
  \Validations::IsNotEmpty($apellido, 'apellido');
  \Validations::IsNotEmpty($dni, 'dni');
  \Validations::userNameExists($nombreUsuario);

  $usersRepository->create($nombreUsuario, $mail, $contrasena, $nombre, $apellido, $dni);
  return $response->withJson(['message' => 'usuario creado correctamente'], 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->post("/incidentes", function (Request $request, Response $response, $args) use ($incidentsRepository)
{
  $user_id = $request->getParsedBodyParam('idUsuario');
  $description = $request->getParsedBodyParam('descripcion');
  $objects = $request->getParsedBodyParam('objetos', []);

  \Validations::isValidUserId($user_id);

  $incident_id = $incidentsRepository->newIncident($user_id, $description, $objects);
  $bonita = new Bonita('ortu.agustin', 'bpm');
  $process_id = $bonita->obtenerIdProceso();
  $case_id = $bonita->instanciarProcesoConVariable($process_id, 'idIncidente', $incident_id);
  $message = "Se confirmó el expediente # $incident_id";
  return $response->withJson(['message' => $message, 'id_incidente' => $incident_id, 'case_id' => $case_id], 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->post("/actualizar-tipo-incidente", function (Request $request, Response $response, $args) use ($incidentsRepository, $incidentTypesRepository)
{
  $incident_id = $request->getParsedBodyParam('id_incidente');
  $incident_type_id = $request->getParsedBodyParam('id_tipo_incidente');
  if (is_null($incident_type_id))
  {
    $incident_type =  $request->getParsedBodyParam('tipo_incidente', '');
    $incident_type_id = $incidentTypesRepository->findByName($incident_type);
  }

  \Validations::IsNotEmpty($incident_id, 'id_incidente');
  \Validations::isValidIncidentTypeId($incident_type_id);

  $incidentsRepository->updateType($incident_type_id, $incident_id);
  return $response->withJson($incidentsRepository->getIncidente($incident_id), 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->get("/error-code/{id_error_code}", function (Request $request, Response $response, $args)
{
  $error_code =  $request->getAttribute('id_error_code');
  return $response->withJson(['error_code' => $error_code, 'description' => \InvalidArgException::getDescription($error_code)], 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

$app->run();