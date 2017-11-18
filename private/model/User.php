<?php
class User extends Costumer implements \JsonSerializable
{
  protected $nombreUsuario;
  protected $contrasena;
  protected $dni;

  public function __construct($id, $nombreUsuario, $contrasena, $mail, $dni, $apellido, $nombre)
  {
    $this->nombreUsuario = $nombreUsuario;
    $this->contrasena = $contrasena;
    $this->dni = $dni;
    parent::__construct($id, $apellido, $nombre, $mail);
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
