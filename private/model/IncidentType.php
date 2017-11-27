<?php
class IncidentType implements \JsonSerializable
{
  public $id;
  public $nombre;

  public function __construct($id, $nombre)
  {
    $this->id = $id;
    $this->nombre = $nombre;
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
