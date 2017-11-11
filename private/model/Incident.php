<?php
class Incident implements \JsonSerializable
{
  public $idIncidente;
  public $idUsuario;
  public $idTipoIncidente;
  public $descripcion;
  public $estado;
  public $fechaInicio;
  public $objetos;

  public function __construct($idIncidente, $idUsuario, $idTipoIncidente, $descripcion, $estado, $fechaInicio, $objetos)
  {
    $this->idIncidente = $idIncidente;
    $this->idUsuario = $idUsuario;
    $this->idTipoIncidente = $idTipoIncidente;
    $this->descripcion = $descripcion;
    $this->estado = $estado;
    $this->fechaInicio = $fechaInicio;
    $this->objetos = $objetos;
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}

class IncidentObject implements \JsonSerializable
{
  public $nombre;
  public $cantidad;
  public $descripcion;

  public function __construct($nombre, $cantidad, $descripcion)
  {
    $this->nombre = $nombre;
    $this->cantidad = $cantidad;
    $this->descripcion = $descripcion;
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}

