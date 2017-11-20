<?php
class PresupuestosRepository extends PDORepository
{
  private $stmtCreatePresupuesto;
  private $stmtCreateObjeto;

  public function __construct()
  {
    $this->stmtCreatePresupuesto = $this->newPreparedStmt("INSERT INTO presupuesto (total) VALUES (?)");
    $this->stmtCreateObjeto = $this->newPreparedStmt("INSERT INTO objetos_presupuesto
                    (idPresupuesto, nombre, cantidad, descripcion, precio, total) VALUES (?, ?, ?, ?, ?, ?)");
  }

  public function create($idIncidente, $objetos, $total_final)
  {
    $this->stmtCreatePresupuesto->execute([$total_final]);
    $qry = $this->newPreparedStmt("SELECT idPresupuesto FROM presupuesto ORDER BY idPresupuesto DESC LIMIT 1");
    $qry->execute();
    $idPresupuesto = $qry->fetchColumn();
    $this->createObjects($idPresupuesto, $objetos);
    return $idPresupuesto;
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