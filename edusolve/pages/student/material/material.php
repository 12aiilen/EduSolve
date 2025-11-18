material.php:  <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once _DIR_ . '/../../../assets/config/AbstractMapper.php';
require_once _DIR_ . '/../../../assets/clases/Usuario.php';

// Verificar sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit();
}

$usuario = unserialize($_SESSION["usuario"]);
$idTipo = (int)$usuario->getIdTiposUsuarios();

if ($idTipo !== 5) {
    header("Location: ../auth/login.php");
    exit();
}

// Obtener ID de materia
$idMateria = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($idMateria <= 0) {
    die("❌ Materia no válida.");
}

// Clase rápida para consultar la BD
class MaterialMapper extends AbstractMapper {
    protected function doLoad($columna) { return $columna; }

    public function obtenerMateriales($idMateria) {
        $sql = "SELECT titulo, descripcion, nombre_archivo, ruta_archivo, fecha_subida 
                FROM materiales 
                WHERE idMateria = $idMateria
                ORDER BY fecha_subida DESC";
        $this->setConsulta($sql);
        return $this->FindAll();
    }

    public function obtenerNombreMateria($idMateria) {
        $sql = "SELECT nombre FROM materias WHERE id = $idMateria LIMIT 1";
        $this->setConsulta($sql);
        $res = $this->FindAll();
        return $res[0]['nombre'] ?? 'Materia Desconocida';
    }
}

$mapper = new MaterialMapper();
$materiales = $mapper->obtenerMateriales($idMateria);
$nombreMateria = $mapper->obtenerNombreMateria($idMateria);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Material de <?= htmlspecialchars($nombreMateria) ?> - EduSolve</title>
    <link rel="stylesheet" href="/edusolve/assets/css/main.css">
    <link rel="icon" href="/edusolve/assets/images/escudo.png" type="image/png">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            padding: 2rem;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            color: var(--primary-color, #2c3e50);
            margin-bottom: 1.5rem;
        }
        .material {
            border-bottom: 1px solid #eee;
            padding: 1rem 0;
        }
        .material:last-child {
            border-bottom: none;
        }
        .material h3 {
            margin: 0;
            color: #2c3e50;
        }
        .material p {
            margin: 0.5rem 0;
            color: #555;
        }
        .button {
            background: var(--primary-color, #3498db);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }
        .button:hover {
            background: #2980b9;
        }
        .back {
            display: inline-block;
            margin-top: 1.5rem;
            background: #2c3e50;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            text-decoration: none;
        }
        .no-material {
            color: #999;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Material Didáctico — <?= htmlspecialchars($nombreMateria) ?></h1>

        <?php if (empty($materiales)): ?>
            <p class="no-material">No hay materiales disponibles para esta materia.</p>
        <?php else: ?>
            <?php foreach ($materiales as $mat): ?>
                <div class="material">
                    <h3><?= htmlspecialchars($mat['titulo']) ?></h3>
                    <p><?= htmlspecialchars($mat['descripcion'] ?? 'Sin descripción') ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($mat['fecha_subida']))) ?></p>
                    <a class="button" href="<?= str_replace(_DIR_ . '/../../..', '..', $mat['ruta_archivo']) ?>" target="_blank">
                        📎 Ver / Descargar
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="materias.php" class="back">← Volver a mis materias</a>
    </div>
</body>
</html>