<?php
//vos brisa necesito que agregues los enlaces del modulo mio y de las chicas.

// require_once('../../pages/Administracion/Administracion.php');

//require_once "AbstractMapper.php";
// require_once __DIR__ . "/../config/AbstractMapper.php";

// class AdministracionDAL extends AbstractMapper
// {  
//     // Obtener todos los registros
//     public function getAll(): array
//     {
//         $consulta = "SELECT * FROM administracion";
//         $this->setConsulta($consulta);
//         return $this->FindAll();
//     }
    
//     // Obtener un registro por ID
//     public function getById($id)
//     {
//         $consulta = "SELECT * FROM administracion WHERE id = '$id'";
//         $this->setConsulta($consulta);
//         return $this->Find();
//     }

//     // Insertar nuevo registro
//     public function insertar($usuario_id, $fecha_alta)
//     {
//         $sql = "INSERT INTO administracion (usuario_id, fecha_alta)
//                 VALUES ('$usuario_id', '$fecha_alta')";
//         $this->setConsulta($sql);
//         return $this->Execute();
//     }

//     // Actualizar
//     public function actualizar($id, $usuario_id, $fecha_alta)
//     {
//         $sql = "UPDATE administracion 
//                 SET usuario_id = '$usuario_id', fecha_alta = '$fecha_alta'
//                 WHERE id = '$id'";
//         $this->setConsulta($sql);
//         return $this->Execute();
//     }

//     // Eliminar
//     public function eliminar($id)
//     {
//         $sql = "DELETE FROM administracion WHERE id = '$id'";
//         $this->setConsulta($sql);
//         return $this->Execute();
//     }

//     // Método obligatorio para AbstractMapper
//     public function doLoad($columna)
//     {
//         return new Administracion(
//             (int)$columna['id'],
//             (int)$columna['usuario_id'],
//             (string)$columna['fecha_alta']
//         );
//     }
// }



//vos brisa necesito que agregues los enlaces del modulo mio y de las chicas.

//require_once _DIR_ . '/../edusolve/assets/clases/Admin.php';

//require_once "AbstractMapper.php";
require_once __DIR__ . "/../config/AbstractMapper.php";

class AdministracionDAL extends AbstractMapper
{  
    // Obtener todos los registros
    public function getAll(): array
    {
        $consulta = "SELECT * FROM administracion";
        $this->setConsulta($consulta);
        return $this->FindAll();
    }
    
    // Obtener un registro por ID
    public function getById($id)
    {
        $consulta = "SELECT * FROM administracion WHERE id = '$id'";
        $this->setConsulta($consulta);
        return $this->Find();
    }

    // Insertar nuevo registro
    public function insertar($usuario_id, $fecha_alta)
    {
        $sql = "INSERT INTO administracion (usuario_id, fecha_alta)
                VALUES ('$usuario_id', '$fecha_alta')";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // Actualizar
    public function actualizar($id, $usuario_id, $fecha_alta)
    {
        $sql = "UPDATE administracion 
                SET usuario_id = '$usuario_id', fecha_alta = '$fecha_alta'
                WHERE id = '$id'";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // Eliminar
    public function eliminar($id)
    {
        $sql = "DELETE FROM administracion WHERE id = '$id'";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // Método obligatorio para AbstractMapper
    public function doLoad($columna)
    {
        return new Administracion(
            (int)$columna['id'],
            (int)$columna['usuario_id'],
            (string)$columna['fecha_alta']
        );
    }
}
?>
