<?php
class User extends Costumer implements \JsonSerializable
{
  protected $nombreUsuario;
  protected $contrasena;
  protected $email;
  protected $dni;

  public function __construct($id, $nombreUsuario, $contrasena, $email, $dni, $apellido, $nombre)
  {
    $this->nombreUsuario = $nombreUsuario;
    $this->contrasena = $contrasena;
    $this->email = $email;
    $this->dni = $dni;
    parent::__construct($id, $apellido, $nombre);
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
