<?php
class UserRepository extends PDORepository
{
  private $stmtDelete;
  private $stmtCreate;

  public function __construct()
  {
    $this->stmtDelete = $this->newPreparedStmt("DELETE FROM usuario WHERE idUsuario = ?");
    $this->stmtCreate = $this->newPreparedStmt("INSERT INTO usuario (nombreUsuario, mail, contrasena, nombre, apellido, dni)
                                                                                 VALUES (?, ?, ?, ?, ?, ?)");
  }

  public function getUsers()
  {
    return $this->queryToUserArray($this->queryList("SELECT * FROM usuario"));
  }

  public function delete($idUsuario)
  {
    return $this->stmtDelete->execute([$idUsuario]);
  }

  public function create($nombreUsuario, $mail, $contrasena, $nombre, $apellido, $dni)
  {
    return $this->stmtCreate->execute([$nombreUsuario, $mail, $contrasena, $nombre, $apellido, $dni]);
  }

  public function update($mail, $contrasena, $nombre, $apellido, $idUsuario)
  {
    return $this->stmtUpdate->execute([$mail, $contrasena, $nombre, $apellido, $idUsuario]);
  }

  public function getUser($idUsuario)
  {
    return $this->queryToUserArray($this->queryList("SELECT * FROM usuario WHERE idUsuario = ?", [$idUsuario]))[0];
  }

  public function getCostumer($idUsuario)
  {
    return $this->queryToCostumerArray($this->queryList("SELECT * FROM usuario WHERE idUsuario = ?", [$idUsuario]))[0];
  }

  private function queryUser($nombreUsuario, $contrasena)
  {
    return $this->queryToUserArray($this->queryList("SELECT * FROM usuario WHERE nombreUsuario = ? AND contrasena = ?", [$nombreUsuario, $contrasena]));
  }

  public function containsUser($nombreUsuario, $contrasena)
  {
    return count($this->queryUser($nombreUsuario, $contrasena)) > 0;
  }

  public function findUser($nombreUsuario, $contrasena)
  {
    return $this->queryUser($nombreUsuario, $contrasena)[0];
  }

  public function userNameExists($nombreUsuario)
  {
    return count($this->queryList("SELECT * FROM usuario WHERE nombreUsuario = ?", [$nombreUsuario]));
  }

  public function userExists($idUsuario)
  {
    return !empty($this->queryList("SELECT * FROM usuario WHERE idUsuario = ?", [$idUsuario]));
  }

  private function queryToUserArray($query)
  {
    $answer = [];
    foreach ($query as &$element) {
      $answer[] = new User(
        $element['idUsuario'],
        $element['nombreUsuario'],
        $element['contrasena'],
        $element['mail'],
        $element['dni'],
        $element['apellido'],
        $element['nombre']
      );
    }
    return $answer;
  }

  private function queryToCostumerArray($query)
  {
    $answer = [];
    foreach ($query as &$element) {
      $answer[] = new Costumer(
        $element['idUsuario'],
        $element['apellido'],
        $element['nombre'],
        $element['mail']
      );
    }
    return $answer;
  }

}

