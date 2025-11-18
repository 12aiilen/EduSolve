<?php
require_once __DIR__ . "/../config/AbstractMapper.php";
require_once __DIR__ . "/../clases/Preceptor.php";

class PreceptorDAL extends AbstractMapper {

    public function getAll(): array {
        $sql = "SELECT * FROM usuarios WHERE idTiposUsuarios = 4"; // 4 = Preceptor
        $this->setConsulta($sql);
        return $this->FindAll();
    }

    public function getById($id): ?Preceptor {
        $sql = "SELECT * FROM usuarios WHERE idUsuarios = '$id' AND idTiposUsuarios = 4";
        $this->setConsulta($sql);
        return $this->Find();
    }

public function doLoad($columna) {
        return new Preceptor(
            (int)$columna['idUsuarios'],
            $columna['Nombre'],
            $columna['Apellido'],
            $columna['Email'],
            (int)$columna['idTiposUsuarios']
        );
    }
}
?>
