<?php
require_once 'vendor/autoload.php';

require_once 'private/ErrorHandler.php';
require_once 'private/Validations.php';

require_once 'private/model/PDORepository.php';
require_once 'private/model/Incident.php';
require_once 'private/model/IncidentType.php';
require_once 'private/model/Costumer.php';
require_once 'private/model/User.php';
require_once 'private/model/IncidentState.php';

require_once 'private/model/IncidentsRepository.php';
require_once 'private/model/UserRepository.php';
require_once 'private/model/IncidentStatesRepository.php';
require_once 'private/model/IncidentTypesRepository.php';
require_once 'private/model/PresupuestosRepository.php';

require_once 'private/api-bonita/Bonita.php';
