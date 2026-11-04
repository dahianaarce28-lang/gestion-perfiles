<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// ---------- Conexión a la base de datos (XAMPP por defecto) ----------
$host = "localhost";
$user = "root";
$pass = "";
$db   = "gestion_perfiles";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($host, $user, $pass, $db);
$errorDB = $conn->connect_error ? "No se pudo conectar a la base de datos. ¿Está MySQL encendido y creaste la base?" : "";

$mensaje = "";
$error   = $errorDB;

// ---------- Guardar perfil ----------
if (!$errorDB && $_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $rol    = $_POST["rol"] ?? "";
    $roles  = ["Estudiante", "Docente", "Administrativo"];

    if ($nombre === "" || !filter_var($correo, FILTER_VALIDATE_EMAIL) || !in_array($rol, $roles)) {
        $error = "Revisá los datos: nombre, correo válido y rol son obligatorios.";
    } else {
        $stmt = $conn->prepare("INSERT INTO perfiles (nombre, correo, rol) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $rol);
        if ($stmt->execute()) {
            header("Location: index.php?ok=1");
            exit;
        } else {
            $error = ($conn->errno === 1062) ? "Ese correo ya está registrado." : "No se pudo guardar el perfil.";
        }
    }
}

if (isset($_GET["ok"])) {
    $mensaje = "¡Perfil guardado con éxito!";
}

// ---------- Listar perfiles ----------
$perfiles = [];
if (!$errorDB) {
    $res = $conn->query("SELECT id, nombre, correo, rol, estado FROM perfiles ORDER BY id ASC");
    if ($res) {
        while ($fila = $res->fetch_assoc()) {
            $perfiles[] = $fila;
        }
    }
}
function e($txt) { return htmlspecialchars($txt, ENT_QUOTES, "UTF-8"); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Perfiles</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;800&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- El CSS debe estar en la MISMA carpeta que este archivo -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="stars-container" id="stars"></div>
    <div class="snow-container" id="snow"></div>

    <main class="main-container">

        <header class="header">
            <div class="garland">🎄 ❄️ ✨ 🎅 ✨ ❄️ 🎄</div>
            <h1 class="glow-title">Gestión de Perfiles</h1>
            <p class="subtitle">Colegio Nacional E.M.D. Asunción Escalada</p>
        </header>

        <?php if ($mensaje): ?>
            <div class="alert-box" id="alerta">🎁 <?= e($mensaje) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert-box alert-error" id="alerta">⚠️ <?= e($error) ?></div>
        <?php endif; ?>

        <section class="card-form">
            <h2>✨ Registro de Usuario ✨</h2>
            <form method="POST" action="index.php" id="formPerfil" novalidate>
                <div class="input-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Dahiana Arce" required>
                </div>

                <div class="input-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="usuario@ejemplo.com" required>
                </div>

                <div class="input-group">
                    <label for="rol">Rol del Perfil</label>
                    <select id="rol" name="rol">
                        <option value="Estudiante">Estudiante</option>
                        <option value="Docente">Docente</option>
                        <option value="Administrativo">Administrativo</option>
                    </select>
                </div>

                <button type="submit" class="btn-gold">🎁 Guardar Perfil</button>
            </form>
        </section>

        <section class="card-table">
            <h2>📜 Perfiles Registrados</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($perfiles) === 0): ?>
                            <tr><td colspan="5" class="vacio">Todavía no hay perfiles registrados.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($perfiles as $i => $p): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= e($p["nombre"]) ?></td>
                                <td><?= e($p["correo"]) ?></td>
                                <td><span class="badge-gold"><?= e($p["rol"]) ?></span></td>
                                <td><span class="status-active">✨ <?= e($p["estado"]) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <script src="script.js"></script>
</body>

</html>
