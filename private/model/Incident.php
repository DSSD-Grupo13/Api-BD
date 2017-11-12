<?php
class Incident implements \JsonSerializable
{
  private $id;
  private $descripcion;
  private $cliente;
  private $tipo;
  private $estado;
  private $fecha;
  private $objetos;

  public function __construct($id, $descripcion, $tipo, $estado, $cliente, $fecha, $objetos)
  {
    $this->id = $id;
    $this->cliente = $cliente;
    $this->tipo = $tipo;
    $this->descripcion = $descripcion;
    $this->estado = $estado;
    $this->fecha = $fecha;
    $this->objetos = $objetos;
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}

class IncidentObject implements \JsonSerializable
{
  private $nombre;
  private $cantidad;
  private $descripcion;

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

