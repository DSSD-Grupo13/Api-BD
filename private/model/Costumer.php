<?php
class Costumer implements \JsonSerializable
{
  protected $id;
  protected $apellido;
  protected $nombre;
  protected $mail;

  public function __construct($id, $apellido, $nombre, $mail)
  {
    $this->id = $id;
    $this->apellido = $apellido;
    $this->nombre = $nombre;
    $this->mail = $mail;
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}