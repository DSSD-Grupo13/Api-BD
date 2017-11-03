<?php
class IncidentsRepository extends PDORepository
{
  private static $mysql_datetime_format = 'Y-m-d';
  private static $date_only_format = 'd-m-Y';
  private $stmtDelete;
  private $stmtCreate;
  private $stmtUpdateState;

  public function __construct()
  {
    $this->stmtCreate = $this->newPreparedStmt("INSERT INTO incidente (descripcion, idTipoIncidente, idUsuario, fechaInicio, fechaFin, idEstado)
                                                                                 VALUES (?, ?, ?, NOW(), NOW(), ?) ");
    $this->stmtUpdateState = $this->newPreparedStmt("UPDATE incidente SET idEstado = ? WHERE idIncidente = ?");
    $this->stmtDelete = $this->newPreparedStmt("DELETE FROM inciente WHERE idIncidente = ?");
  }

  public function newIncident($idUsuario, $descripcion, $tipo_incidente)
  {
    $this->stmtCreate->execute([$descripcion, $tipo_incidente, $idUsuario, '1']);
    $qry = $this->newPreparedStmt("SELECT idincidente FROM incidente ORDER BY idincidente DESC LIMIT 1");
    $qry->execute();
    return $qry->fetchColumn();
  }

  public function getIncidentes()
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente"));
  }

  public function getIncidentesUsuario($idUsuario)
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente WHERE I.idUsuario = ?", [$idUsuario]));
  }

  public function getIncidente($idIncidente)
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente where idIncidente = ?", [$idIncidente]))[0];
  }

  public function delete($idIncidente)
  {
    return $this->stmtDelete->execute([$idIncidente]);
  }

  public function updateState($idEstado, $idIncidente)
  {
    return $this->stmtUpdateState->execute([$idEstado, $idIncidente]);
  }

  private function queryToIncidenteArray($query)
  {
    $answer = [];
    foreach ($query as &$element) {
      $answer[] = new Incident(
        $element['idIncidente'],
        $element['idUsuario'],
        $element['idTipoIncidente'],
        $element['descripcion'],
        $element['idEstado'],
        $this->mysqldate_to_datetime($element['fechaInicio'])
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