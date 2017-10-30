<?php
class TipoIncidente {
  private $idTipoIncidente;
  private $nombre;

  public function __construct( $idTipoIncidente, $nombre)
  {
    $this->idTipoIncidente = $idTipoIncidente;
    $this->nombre = $nombre;
  }

  public function getId()
  {
    return $this->idTipoIncidente;
  }

  public function getNombre()
  {
    return $this->nombre;
  }
}
