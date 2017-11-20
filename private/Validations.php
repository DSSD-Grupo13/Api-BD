<?php
class InvalidArgException extends Exception
{
  const INVALID_DATE_FORMAT = 1;
  const INVALID_DATE_RANGE = 2;
  const INVALID_USER = 3;
  const INVALID_INCIDENT_TYPE = 4;
  const INVALID_INCIDENT_STATE = 5;
  const USERNAME_EXISTS = 6;
  const EMPTY_ARG = 7;

  private static $descriptions = [
    self::INVALID_DATE_FORMAT => 'Formato de fecha inválido, usar dd-mm-aaaa. Ejemplo <25-10-2017>',
    self::INVALID_DATE_RANGE => 'La fecha no puede ser anterior al dia actual',
    self::INVALID_USER => 'El Usuario no existe en el sistema',
    self::INVALID_INCIDENT_TYPE => 'El Tipo de Incidente no existe en el sistema',
    self::INVALID_INCIDENT_STATE => 'El Estado de Incidente no existe en el sistema',
    self::USERNAME_EXISTS => 'El nombre de usuario se encuentra en uso',
    self::EMPTY_ARG => 'Valor inválido, vacio o nulo'
  ];

  public static function getDescription($error_code)
  {
    return self::$descriptions[$error_code];
  }

  public function __construct($code)
  {
    parent::__construct(self::getDescription($code), $code);
  }
}

class EmptyArgException extends Exception
{
  public function __construct($argName)
  {
    parent::__construct("Valor inválido, vacio o nulo en el parametro $argName", \InvalidArgException::EMPTY_ARG);
  }
}

class Validations
{
  private static function getUsersRepository()
  {
      return new \UserRepository;
  }

  private static function getIncidentRepository()
  {
      return new \IncidentsRepository(self::getIncidentTypeRepository(), self::getIncidentStateRepository(), self::getUsersRepository());
  }

  private static function getIncidentTypeRepository()
  {
      return new \IncidentTypesRepository;
  }

  private static function getIncidentStateRepository()
  {
    return new \IncidentStatesRepository;
  }

  private static function isBetween($value, $min, $max)
  {
    return ($value >= $min && $value <= $max);
  }

  public static function isValidDate($date)
  {
    $d = \DateTime::createFromFormat('d-m-Y', $date);
    if (!($d && $d->format('d-m-Y') == $date))
      throw new \InvalidArgException(InvalidArgException::INVALID_DATE_FORMAT);
  }

  public static function isValidUserId($userId)
  {
    if (!isset($userId) || empty($userId))
      throw new \InvalidArgException(InvalidArgException::INVALID_USER);

    if (!self::getUsersRepository()->userExists($userId))
      throw new \InvalidArgException(InvalidArgException::INVALID_USER);
  }

  public static function isValidIncidentTypeId($incidentTypeId)
  {
    if (!isset($incidentTypeId) || empty($incidentTypeId))
      throw new \InvalidArgException(InvalidArgException::INVALID_INCIDENT_TYPE);

    if (!self::getIncidentTypeRepository()->incidentTypeExists($incidentTypeId))
      throw new \InvalidArgException(InvalidArgException::INVALID_INCIDENT_TYPE);
  }

  public static function isValidIncidentStateId($incidentStateId)
  {
    if (!isset($incidentStateId) || empty($incidentStateId))
      throw new \InvalidArgException(InvalidArgException::INVALID_INCIDENT_STATE);

    if (!self::getIncidentStateRepository()->incidentStateExists($incidentStateId))
      throw new \InvalidArgException(InvalidArgException::INVALID_INCIDENT_STATE);
  }

  public static function isValidIncidentId($incidentId)
  {
    if (!isset($incidentId) || empty($incidentId))
      throw new \InvalidArgException(InvalidArgException::INVALID_INCIDENT_STATE);

    if (!self::getIncidentRepository()->incidentExists($incidentId))
      throw new \InvalidArgException(InvalidArgException::INVALID_INCIDENT_STATE);
  }

  public static function userNameExists($userName)
  {
    if (self::getUsersRepository()->userNameExists($userName))
      throw new \InvalidArgException(InvalidArgException::USERNAME_EXISTS);
  }

  public static function IsNotEmpty($value, $valueName)
  {
    if (empty($value))
      throw new \EmptyArgException($valueName);
  }
}