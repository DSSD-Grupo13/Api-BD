<?php
class Estado {
  private $idEstado;
  private $nombre;
  
  public function __construct( $idEstado, $nombre)
  {
    $this->idEstado = $idEstado;
    $this->nombre = $nombre;
  }

  public function getId()
  {
    return $this->idEstado;
  }

  public function getNombre()
  {
    return $this->nombre;
  }
}
