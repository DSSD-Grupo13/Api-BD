<?php
class IncidentState implements \JsonSerializable
{
  private $idEstado;
  private $nombre;

  public function __construct($idEstado, $nombre)
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

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
