<?php
class IncidentTypesRepository extends PDORepository
{
  private $stmtDelete;
  private $stmtCreate;
  private $stmtUpdate;

  private function queryToTipoIncidenteArray($query)
  {
    $answer = [];
    foreach ($query as &$element) {
      $answer[] = new IncidentType(
        $element['idTipoIncidente'],
        $element['nombre']
      );
    }
    return $answer;
  }

  public function __construct()
  {
    $this->stmtDelete = $this->newPreparedStmt("DELETE FROM tipoincidente WHERE idTipoIncidente = ?");
    $this->stmtCreate = $this->newPreparedStmt("INSERT INTO tipoincidente (nombre)  VALUES (?)");
    $this->stmtUpdate = $this->newPreparedStmt("UPDATE tipoincidente SET nombre = ? WHERE idTipoIncidente = ?");
  }

  public function getIncidentTypes()
  {
    return $this->queryToTipoIncidenteArray($this->queryList("SELECT * FROM tipoincidente"));
  }

  public function delete($idTipoIncidente)
  {
    return $this->stmtDelete->execute([$idTipoIncidente]);
  }

  public function create($nombre)
  {
    return $this->stmtCreate->execute([$nombre]);
  }

  public function update($idTipoIncidente, $nombre)
  {
    return $this->stmtUpdate->execute([$nombre, $idTipoIncidente]);
  }

  public function getIncidentType($idTipoIncidente)
  {
    return $this->queryToTipoIncidenteArray($this->queryList("SELECT * FROM tipoincidente where idTipoIncidente = ?", [$idTipoIncidente]))[0];
  }

  public function incidentTypeExists($idTipoIncidente)
  {
    return !empty($this->queryList("SELECT * FROM tipoincidente WHERE idTipoIncidente = ?", [$idTipoIncidente]));
  }

  public function findByName($name)
  {
    $data = $this->queryList("SELECT * FROM tipoincidente where nombre = ?", [$name]);
    if (empty($data))
      return null;
    else
      return $data[0]['idTipoIncidente'];
  }
}
