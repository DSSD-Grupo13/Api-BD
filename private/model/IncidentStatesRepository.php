<?php
class IncidentStatesRepository extends PDORepository
{
  private $stmtDelete;
  private $stmtCreate;

  public function __construct()
  {
    $this->stmtDelete = $this->newPreparedStmt("DELETE FROM estado WHERE idEstado = ?");
    $this->stmtCreate = $this->newPreparedStmt("INSERT INTO estado (nombre)  VALUES (?)");
  }

  public function getIncidentStates()
  {
    return $this->queryToIncidentState($this->queryList("SELECT * FROM estado"));
  }

  public function getIncidentState($idEstado)
  {
    return $this->queryToIncidentState($this->queryList("SELECT * FROM estado where idEstado = ?", [$idEstado]))[0];
  }

  public function incidentStateExists($idEstado)
  {
    return !empty($this->queryList("SELECT * FROM estado WHERE idEstado = ?", [$idEstado]));
  }

  public function delete($idEstado)
  {
    return $this->stmtDelete->execute([$idEstado]);
  }

  public function newIncidentState($nombre)
  {
    return $this->stmtCreate->execute([$nombre]);
  }

  public function findByName($name)
  {
    $data = $this->queryList("SELECT * FROM estado where nombre = ?", [$name]);
    if (empty($data))
      return null;
    else
      return $data[0]['idEstado'];
  }

  private function queryToIncidentState($query)
  {
    $answer = [];
    foreach ($query as &$element) {
      $answer[] = new IncidentState(
        $element['idEstado'],
        $element['nombre']
      );
    }
    return $answer;
  }
}
