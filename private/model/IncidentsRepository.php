<?php
class IncidentsRepository extends PDORepository
{
  private static $mysql_datetime_format = 'Y-m-d';
  private static $date_only_format = 'd-m-Y';
  private $stmtDeleteIncidente;
  private $stmtDeleteObjetoIncidente;
  private $stmtCreate;
  private $stmtUpdateState;
  private $stmtUpdateType;
  private $stmtNewIncidentObject;
  private $typesRepository;
  private $statesRepository;
  private $userRepository;

  public function __construct($typesRepository, $statesRepository, $userRepository)
  {
    $this->typesRepository = $typesRepository;
    $this->statesRepository = $statesRepository;
    $this->userRepository = $userRepository;
    $this->stmtCreate = $this->newPreparedStmt("INSERT INTO incidente (descripcion, idTipoIncidente, idUsuario, fechaInicio, fechaFin, idEstado)
                                                                                 VALUES (?, ?, ?, NOW(), NOW(), ?) ");
    $this->stmtUpdateState = $this->newPreparedStmt("UPDATE incidente SET idEstado = ? WHERE idIncidente = ?");
    $this->stmtUpdateType = $this->newPreparedStmt("UPDATE incidente SET idTipoIncidente = ? WHERE idIncidente = ?");
    $this->stmtDeleteIncidente = $this->newPreparedStmt("DELETE FROM inciente WHERE idIncidente = ?");
    $this->stmtDeleteObjetoIncidente = $this->newPreparedStmt("DELETE FROM objetoIncidente WHERE idIncidente = ?");
    $this->stmtNewIncidentObject = $this->newPreparedStmt("INSERT INTO objetoIncidente (idIncidente, nombre, cantidad, descripcion)
                                                                                                     VALUES (?, ?, ?, ?)");
  }

  public function obtenerObjetosIncidente($idIncidente)
  {
    $answer = [];
    $query = $this->queryList("SELECT * FROM objetoIncidente WHERE idIncidente = ?", [$idIncidente]);
    foreach ($query as &$element) {
      $answer[] = new IncidentObject(
        $element['nombre'],
        $element['cantidad'],
        $element['descripcion']
      );
    };
    return $answer;
  }

  public function newIncident($idUsuario, $descripcion, $objects)
  {
    $this->stmtCreate->execute([$descripcion, '1', $idUsuario, '1']);
    $qry = $this->newPreparedStmt("SELECT idincidente FROM incidente ORDER BY idincidente DESC LIMIT 1");
    $qry->execute();
    $id = $qry->fetchColumn();
    $this->saveIncidentObjects($id, $objects);
    return $id;
  }

  public function getIncidentes()
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente"));
  }

  public function getIncidentesUsuario($idUsuario)
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente WHERE idUsuario = ?", [$idUsuario]));
  }

  public function getIncidente($idIncidente)
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente where idIncidente = ?", [$idIncidente]))[0];
  }

  public function getIncidentesByState($idEstado)
  {
    $qry = $this->newPreparedStmt("SELECT COUNT(*) FROM incidente where idEstado = ?");
    $qry->execute([$idEstado]);
    return intval($qry->fetchColumn());
  }

  public function getIncidentesByType($idTipoIncidente)
  {
    $qry = $this->newPreparedStmt("SELECT COUNT(*) FROM incidente where idTipoIncidente = ?");
    $qry->execute([$idTipoIncidente]);
    return intval($qry->fetchColumn());
  }

  public function delete($idIncidente)
  {
    $this->stmtDeleteObjetoIncidente->execute([$idIncidente]);
    return $this->stmtDeleteIncidente->execute([$idIncidente]);
  }

  public function updateState($idEstado, $idIncidente)
  {
    return $this->stmtUpdateState->execute([$idEstado, $idIncidente]);
  }

  public function updateType($idTipoIncidente, $idIncidente)
  {
    return $this->stmtUpdateType->execute([$idTipoIncidente, $idIncidente]);
  }

  public function incidentExists($idIncidente)
  {
    return !empty($this->queryList("SELECT * FROM incidente WHERE idIncidente = ?", [$idIncidente]));
  }

  private function saveIncidentObjects($idIncidente, $objects)
  {
    foreach ($objects as &$each) {
      $this->stmtNewIncidentObject->execute([$idIncidente, $each['nombre'], $each['cantidad'], $each['descripcion']]);
    }
  }

  private function queryToIncidenteArray($query)
  {
    $answer = [];
    foreach ($query as &$element) {
      $answer[] = new Incident(
        $element['idIncidente'],
        $element['descripcion'],
        $this->typesRepository->getIncidentType($element['idTipoIncidente']),
        $this->statesRepository->getIncidentState($element['idEstado']),
        $this->userRepository->getCostumer($element['idUsuario']),
        $this->mysqldate_to_datetime($element['fechaInicio']),
        $this->obtenerObjetosIncidente($element['idIncidente'])
      );
    }
    return $answer;
  }

  function datetime_to_mysqldate($datetime)
  {
    return $this->convertDate($datetime, self::$date_only_format, self::$mysql_datetime_format);
  }

  function mysqldate_to_datetime($datetime)
  {
    return $this->convertDate($datetime, self::$mysql_datetime_format, self::$date_only_format);
  }

  function convertDate($value, $format_from, $format_to)
  {
    return DateTime::createFromFormat($format_from, $value)->format($format_to);
  }
}