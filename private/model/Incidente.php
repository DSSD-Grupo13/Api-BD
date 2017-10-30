<?php
class Incidente {
  private $idIncidente;
  private $usuario;
  private $tipoIncidente;
  private $descripcion;
  private $estado;
  private $fechaInicio;

  public function __construct( $idIncidente, $usuario, $tipoIncidente, $descripcion, $estado, $fechaInicio)
  {
    $this->idIncidente = $idIncidente;
    $this->usuario = $usuario;
    $this->tipoIncidente = $tipoIncidente;
    $this->descripcion = $descripcion;
    $this->estado = $estado;
    $this->fechaInicio = $fechaInicio;
  }

  public function getId()
  {
    return $this->idIncidente;
  }

  public function getUsuario()
  {
    return $this->usuario;
  }

  public function getTipoIncidente()
  {
    return $this->tipoIncidente;
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
}
