<?php
class PresupuestosRepository extends PDORepository
{
  private $stmtCreatePresupuesto;
  private $stmtLinkPresupuestoIncidente;
  private $stmtCreateObjeto;
  private $qryFetchIdPresupuesto;

  public function __construct()
  {
    $this->qryFetchIdPresupuesto = $this->newPreparedStmt("SELECT idPresupuesto FROM presupuesto ORDER BY idPresupuesto DESC LIMIT 1");
    $this->stmtCreatePresupuesto = $this->newPreparedStmt("INSERT INTO presupuesto (total) VALUES (?)");
    $this->stmtLinkPresupuestoIncidente = $this->newPreparedStmt("INSERT INTO presupuestos_incidente (idIncidente, idPresupuesto) VALUES (?, ?)");
    $this->stmtCreateObjeto = $this->newPreparedStmt("INSERT INTO objetos_presupuesto (idPresupuesto, nombre, cantidad, descripcion, precio, total) VALUES (?, ?, ?, ?, ?, ?)");
  }

  public function create($idIncidente, $objetos, $total_final)
  {
    $this->stmtCreatePresupuesto->execute([$total_final]);
    $idPresupuesto = $this->fetchIdPresupuesto();
    $this->stmtLinkPresupuestoIncidente->execute([$idIncidente, $idPresupuesto]);
    $this->createObjects($idPresupuesto, $objetos);
    return $idPresupuesto;
  }

  private function fetchIdPresupuesto()
  {
    $this->qryFetchIdPresupuesto->execute();
    return $this->qryFetchIdPresupuesto->fetchColumn();
  }

  private function createObjects($idPresupuesto, $objetos)
  {
    foreach ($objetos as &$each) {
      $this->stmtCreateObjeto->execute([
              $idPresupuesto, $each['nombre'], $each['cantidad'], $each['descripcion'], $each['precio'], $each['total']
              ]);
    }
  }
}