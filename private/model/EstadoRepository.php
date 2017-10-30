<?php
class EstadoRepository extends PDORepository
{
  private $stmtDelete;
  private $stmtCreate;
  private $stmtUpdate;

  private function queryToEstadoArray($query)
  {
    $answer = [];
    foreach ($query as &$element) {
      $answer[] = new Estado(
        $element['idEstado'],
        $element['nombre']
      );
    }
    return $answer;
  }

  public function __construct()
  {
    $this->stmtDelete = $this->newPreparedStmt("DELETE FROM estado WHERE idEstado = ?");
    $this->stmtCreate = $this->newPreparedStmt("INSERT INTO estado (nombre)  VALUES (?)");
    $this->stmtUpdate = $this->newPreparedStmt("UPDATE estado SET nombre = ? WHERE idEstado = ?");
  }

  public function getAll()
  {
    return $this->queryToEstadoArray($this->queryList("SELECT * FROM estado"));
  }

  public function delete($idEstado)
  {
    return $this->stmtDelete->execute([$idEstado]);
  }


  public function create($nombre)
  {
    return $this->stmtCreate->execute([$nombre]);
  }

  public function update($idEstado, $nombre)
  {
    return $this->stmtUpdate->execute([$nombre, $idEstado]);
  }

  public function getEstado($idEstado)
  {
    return $this->queryToEstadoArray($this->queryList("SELECT * FROM estado where idEstado = ?", [$idEstado]))[0];
  }
}
