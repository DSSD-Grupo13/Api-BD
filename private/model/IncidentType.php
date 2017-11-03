<?php
class IncidentType implements \JsonSerializable
{
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

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
