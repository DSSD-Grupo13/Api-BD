<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once './Slim/autoloader.php';
require_once 'private/model/PDORepository.php';

require_once 'private/model/Incidente.php';
require_once 'private/model/Usuario.php';
require_once 'private/model/Estado.php';
require_once 'private/model/TipoIncidente.php';

require_once 'private/model/IncidenteRepository.php';
require_once "private/model/UsuarioRepository.php";
require_once 'private/model/EstadoRepository.php';
require_once "private/model/TipoIncidente.php";
