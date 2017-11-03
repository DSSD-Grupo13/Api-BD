<?php
class IncidenteRepository extends PDORepository
{
  private $stmtDelete;
  private $stmtCreate;
  private $stmtUpdate;

  private function queryToIncidenteArray($query)
  {
    $answer = [];
    foreach ($query as &$element) {
      $answer[] = new Incidente(
        $element['idIncidente'],
        $element['idUsuario'],
        $element['idTipoIncidente'],
        $element['descripcion'],
        $element['idEstado'],
        $element['fechaInicio']);
    }
    return $answer;
  }

  public function __construct()
  {
    $this->stmtDelete = $this->newPreparedStmt("DELETE FROM inciente WHERE idIncidente = ?");
    $this->stmtCreate = $this->newPreparedStmt("INSERT INTO incidente (descripcion, idTipoIncidente, idUsuario, fechaInicio, fechaFin,
                                                idEstado)  VALUES (?, ?, ?, ?, ?, ?, ?)");
    $this->stmtUpdate = $this->newPreparedStmt("UPDATE incidente SET idEstado = ? WHERE idIncidente = ?");
  }

  public function getIncidentes()
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente"));
  }

  public function getAll()
  {
    return $this->getIncidentes();
  }

  public function delete($idIncidente)
  {
    return $this->stmtDelete->execute([$idIncidente]);
  }

  public function create($descripcion, $idTipoIncidente, $idUsuario, $fechaInicio, $fechaFin, $idEstado)
  {
    return $this->stmtCreate->execute([$descripcion, $idTipoIncidente, $idUsuario, $fechaInicio, $fechaFin, $idEstado]);
  }

  public function update($idEstado, $idIncidente)
  {
    return $this->stmtUpdate->execute([$idEstado, $idIncidente]);
  }

  public function getIncidente($idIncidente)
  {
    $data = $this->queryList("SELECT * FROM incidente where idIncidente = ?", [$idIncidente]);
    return $this->queryToIncidenteArray($data)[0];
  }
}
