<?php
class Costumer implements \JsonSerializable
{
  protected $id;
  protected $apellido;
  protected $nombre;

  public function __construct($id, $apellido, $nombre)
  {
    $this->id = $id;
    $this->apellido = $apellido;
    $this->nombre = $nombre;
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}