<?php
class Incidente implements \JsonSerializable
{
  public $idIncidente;
  public $idUsuario;
  public $idTipoIncidente;
  public $descripcion;
  public $estado;
  public $fechaInicio;

  public function __construct($idIncidente, $idUsuario, $idTipoIncidente, $descripcion, $estado, $fechaInicio)
  {
    $this->idIncidente = $idIncidente;
    $this->idUsuario = $idUsuario;
    $this->idTipoIncidente = $idTipoIncidente;
    $this->descripcion = $descripcion;
    $this->estado = $estado;
    $this->fechaInicio = $fechaInicio;
  }

  public function getId()
  {
    return $this->idIncidente;
  }

  public function getidUsuario()
  {
    return $this->idUsuario;
  }

  public function getIdTipoIncidente()
  {
    return $this->idTipoIncidente;
  }

  public function getDescripcion()
  {
    return $this->descripcion;
  }

  public function getEstado()
  {
    return $this->estado;
  }

  public function getFechaInicio()
  {
    return $this->fechaInicio;
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
