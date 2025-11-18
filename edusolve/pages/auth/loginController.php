<?php
// MOSTRAR TODOS LOS ERRORES
error_reporting(E_ALL);
ini_set('display_errors', 1);

// INICIAMOS LA SESIÓN
session_start();

if (!empty($_POST["btningresar"])) {

    // INCLUIMOS LAS CLASES NECESARIAS
    require_once __DIR__ . '/../../assets/clases/Usuario.php';
    require_once __DIR__ . '/../../assets/dal/UsuariosDAL.php';
    require_once __DIR__ . '/../../assets/BLL/UsuariosBLL.php';
    require_once __DIR__ . '/../../assets/DAL/CursoDAL.php'; // 🔹 Para preceptores

    // CREAMOS EL OBJETO BLL
    $usuarioBLL = new UsuariosBLL();

    // TOMAMOS DATOS DEL FORMULARIO
    $usuarioInput = trim($_POST["usuario"] ?? '');
    $contrasenaInput = trim($_POST["contrasena"] ?? '');

    if ($usuarioInput !== '' && $contrasenaInput !== '') {

        // AUTENTICAMOS USUARIO
        $usuarioObj = $usuarioBLL->AuthUsuario($usuarioInput, $contrasenaInput);

        if ($usuarioObj !== null) {
            // ✅ Autenticación exitosa
            $_SESSION["usuario"] = serialize($usuarioObj);
            $idTipo = (int)$usuarioObj->getIdTiposUsuarios();

            switch ($idTipo) {
                case 1: // PRECEPTOR
                    $_SESSION["idPreceptor"] = $usuarioObj->getIdUsuarios();

                    $cursoDAL = new CursoDAL();
                    $curso = $cursoDAL->getCursoPorPreceptor($usuarioObj->getIdUsuarios());

                    $_SESSION['idCurso'] = $curso['idCursos'] ?? null;
                    $_SESSION['nombreCurso'] = $curso['nombre'] ?? '';

                    header('Location: ../preceptor/homePreceptor.php');
                    exit;

                case 3: // ADMINISTRACIÓN
                    header('Location: ../Administracion/Administracion.php');
                    exit;

                case 4: // PROFESOR
                    $_SESSION['profesor_id'] = $usuarioObj->getIdUsuarios();
                    $_SESSION['profesor_nombre'] = $usuarioObj->getNombre() . ' ' . $usuarioObj->getApellido();
                    $_SESSION['TipoUsuario'] = $idTipo;
                    // Redirigir al dashboard del profesor (ruta relativa desde pages/auth)
                    header('Location: ../teacher/dashboard.php');
                    exit;


                case 5: // ALUMNO
                    header('Location: ../../pages/student/dashboard.php');
                    exit;

                default:
                    $_SESSION['error_message'] = "ROL DE USUARIO NO RECONOCIDO.";
                    header('Location: login.php');
                    exit;
            }

        } else {
            // ❌ Usuario o contraseña incorrectos
            $_SESSION['error_message'] = "USUARIO O CONTRASEÑA INCORRECTOS.";
            header('Location: login.php');
            exit;
        }

    } else {
        $_SESSION['error_message'] = "DEBE COMPLETAR TODOS LOS CAMPOS.";
        header('Location: login.php');
        exit;
    }

} else {
    // SI NO VIENE POR POST, REDIRIGIR
    header('Location: login.php');
    exit;
}
?>
