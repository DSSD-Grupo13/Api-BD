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
        $element['usuario'],
        $element['tipoIncidente'],
        $element['descripcion'],
        $element['estado'],
        $element['fechaInicio'],
      );
    }
    return $answer;
  }

  public function __construct()
  {
    $this->stmtDelete = $this->newPreparedStmt("DELETE FROM inciente WHERE idIncidente = ?");
    $this->stmtCreate = $this->newPreparedStmt("INSERT INTO incidente (descripcion, idTipoIncidente, idUsuario, fechaInicio, fechaFin,
                                                idEstado)  VALUES (?, ?, ?, ?, ?, ?, ?)");
    $this->stmtUpdate = $this->newPreparedStmt("UPDATE incidente SET estado = ?
                                                WHERE idIncidente = ?");
  }

  public function getAll()
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente"));
  }

  public function delete($idIncidente)
  {
    return $this->stmtDelete->execute([$idIncidente]);
  }


//ASUMO QUE MANDA EL OBJETO USUARIO, TIPO INCIDENTE Y ESTADO (COMO OBJETOS PROPIAMENTE DICHOS)
  public function create($descripcion, $tipoIncidente, $usuario, $fechaInicio, $fechaFin, $estado)
  {
    $idTipoIncidente = $tipoIncidente -> idTipoIncidente;
    $idEstado = $estado -> idEstado;
    $usuario = $usuario -> id;

    return $this->stmtCreate->execute([$descripcion, $idTipoIncidente, $idUsuario, $fechaInicio, $fechaFin, $idEstado]);
  }

  public function update($estado)
  {
    $idEstado = $estado -> idEstado;
    return $this->stmtUpdate->execute([$idEstado]);
  }

  public function getIncidente($idIncidente)
  {
    return $this->queryToIncidenteArray($this->queryList("SELECT * FROM incidente where idIncidente = ?", [$idIncidente]))[0];
  }
}
