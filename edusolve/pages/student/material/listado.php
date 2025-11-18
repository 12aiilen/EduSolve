listado: <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../../assets/dal/UsuariosDAL.php';
require_once __DIR__ . '/../../../assets/controllers/AlumnoController.php';

// ✅ Verificar sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Recuperar el usuario logueado
$usuario = unserialize($_SESSION["usuario"]);
$idTipo = (int)$usuario->getIdTiposUsuarios();

// ✅ Solo tipo 5 (alumno) puede acceder
if ($idTipo !== 5) {
    header("Location: ../auth/login.php");
    exit();
}

$controller = new AlumnoController();



// ✅ Buscar el alumno correspondiente al usuario logueado
$idAlumno = $controller->obtenerIdAlumnoPorNombreApellido($usuario->getNombre(), $usuario->getApellido());

// ⚠ Verificar que el ID del alumno exista
if ($idAlumno === null) {
    die("❌ Error: No se encontró el alumno con el nombre y apellido proporcionados.");
}

// ✅ Obtener los datos del alumno
$estudiante = $controller->obtenerPorId((int)$idAlumno);

// ⚠ Validar que se haya encontrado el alumno
if ($estudiante === null) {
    die("❌ Error: No se pudieron obtener los datos del alumno con ID $idAlumno.");
}

// ✅ Obtener materias y calificaciones
$materias = $controller->obtenerMaterias($idAlumno);
$calificaciones = $controller->obtenerCalificaciones($idAlumno);

$iniciales = strtoupper(substr($estudiante->getNombre(), 0, 1) . substr($estudiante->getApellido(), 0, 1));

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Materias - EduSolve</title>
     <link rel="icon" href="../../../edusolve/assets/images/escudo.png" type="image/png">
    <link rel="stylesheet" href="../../../assets/css/materias.css">
    <link rel="stylesheet" href="../../../assets/css/main.css">


    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            box-shadow: var(--shadow);
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            text-align: left;
        }

        .table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #eee;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        .button {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .button:hover {
            background-color: var(--primary-hover);
        }

        .alert {
            background: #ffeaea;
            color: #c0392b;
            border-left: 5px solid #c0392b;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .current-user {
            color: var(--primary-color);
            font-weight: 600;
        }
    </style>
</head>

<body>
<header class="header">
    <div class="container">
        <div class="menu">
            <div class="logo-container">
            <link rel="icon" href="/edusolve/assets/images/escudo.png" type="image/png">
                <a href="../../../index.php" class="logo">EduSolve<span class="school-name">Estudiante</span></a>
            </div>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="../dashboard.php" class="nav-link active">Inicio</a></li>
                    <li><a href="../perfil/perfil.php" class="user-avatar"><?= $iniciales ?></a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
                </ul>
            </nav>
        </div>
    </div>
</header>


<main class="student-container">
    <div class="container">
        <h1 class="admin-title">
            Mis Materias — 
            <span class="current-user">
                <?= htmlspecialchars($estudiante->getNombre() . ' ' . $estudiante->getApellido()) ?>
            </span>
        </h1>

        <?php if (empty($materias)): ?>
            <div class="alert">
                <p>No tienes materias asignadas actualmente.</p>
            </div>
        <?php else: ?>
<table class="table">
    <thead>
        <tr>
            <th><strong>Nombre</strong></th>
            <th><strong>Año</strong></th>
            <th><strong>Horas Semanales</strong></th>
            <th><strong>Estado</strong></th>
            <th><strong>📚 Ver Material</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($materias as $m): ?>
            <tr>
                <td><strong><?= htmlspecialchars($m['nombre'] ?? '') ?></strong></td>
                <td><?= htmlspecialchars($m['año'] ?? '') ?></td>
                <td><?= htmlspecialchars($m['horas_semanales'] ?? '') ?> horas</td>
                <td>
                    <span style="color: var(--success); font-weight: 500;">
                        ✅ Activa
                    </span>
                </td>
                <td>
                    <a href="../material/material.php?id=<?= htmlspecialchars($m['id']) ?>" class="button">
                        📚 Ver Material
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


            <!-- <div style="margin-top: 2rem; text-align: center;">
                <a href="dashboard.php" class="button">← Volver al Dashboard</a>
            </div> -->
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ Página de materias cargada correctamente');
});
</script>

</body>
</html>