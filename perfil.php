<?php
require_once "connexio.php";

if (!isset($_SESSION['usuari_id'])) {
    header("Location: login.php");
    exit();
}

$idUsuari = $_SESSION['usuari_id'];

// Obtener datos personales
$sqlUsuari = $conn->prepare("SELECT nom, correu, imatge_perfil FROM usuaris WHERE id = ?");
$sqlUsuari->bind_param("i", $idUsuari);
$sqlUsuari->execute();
$dadesUsuari = $sqlUsuari->get_result()->fetch_assoc();

// Procesar subida de imagen
if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] === UPLOAD_ERR_OK) {
    $dir = "uploads/";
    if (!is_dir($dir)) mkdir($dir);

    $nomFitxer = "perfil_" . $idUsuari . "_" . time() . ".jpg";
    move_uploaded_file($_FILES['imatge']['tmp_name'], $dir . $nomFitxer);

    $update = $conn->prepare("UPDATE usuaris SET imatge_perfil = ? WHERE id = ?");
    $update->bind_param("si", $nomFitxer, $idUsuari);
    $update->execute();

    header("Location: perfil.php");
    exit();
}

// Añadir coche
if (isset($_POST['accio']) && $_POST['accio'] === 'afegir_cotxe') {
    $marca = $_POST['marca'];
    $model = $_POST['model'];
    $matricula = $_POST['matricula'];
    $any = $_POST['any'];
    $color = $_POST['color'];

    $stmt = $conn->prepare("INSERT INTO cotxes (usuari_id, marca, model, matricula, any, color) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssis", $idUsuari, $marca, $model, $matricula, $any, $color);
    $stmt->execute();
    header("Location: perfil.php");
    exit();
}

// Eliminar coche
if (isset($_GET['delete_cotxe'])) {
    $idCotxe = $_GET['delete_cotxe'];
    $delete = $conn->prepare("DELETE FROM cotxes WHERE id = ? AND usuari_id = ?");
    $delete->bind_param("ii", $idCotxe, $idUsuari);
    $delete->execute();
    header("Location: perfil.php");
    exit();
}

// Obtener coches del usuario
$cotxes = $conn->prepare("SELECT * FROM cotxes WHERE usuari_id = ?");
$cotxes->bind_param("i", $idUsuari);
$cotxes->execute();
$cotxesResult = $cotxes->get_result();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Perfil d'Usuari</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #fafafa;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .perfil-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .perfil-header img {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3498db;
            margin-bottom: 10px;
        }

        .perfil-header form {
            margin-top: 10px;
        }

        .perfil-section {
            background: #f5f5f5;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .perfil-section h3 {
            margin-bottom: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5rem;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .boto {
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            margin: 2px;
            display: inline-block;
        }

        .boto.danger { background: #e74c3c; }
        .boto.success { background: #2ecc71; }

        .center {
            text-align: center;
        }

        input, select {
            margin: 4px;
            padding: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="center">👤 Perfil de <?php echo htmlspecialchars($dadesUsuari['nom']); ?></h1>

    <div class="center">
        <a href="index.php" class="boto">⬅️ Tornar</a>
        <a href="principal.php" class="boto">🏠 Menú</a>
        <a href="logout.php" class="boto danger">🚪 Sortir</a>
    </div>

    <!-- Imagen de perfil -->
    <div class="perfil-header">
        <img src="uploads/<?php echo htmlspecialchars($dadesUsuari['imatge_perfil']); ?>" alt="Perfil">
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="imatge" accept="image/*" required>
            <button type="submit" class="boto success">📷 Actualitzar Foto</button>
        </form>
    </div>

    <!-- Información personal -->
    <div class="perfil-section">
        <h3>📄 Informació Personal</h3>
        <p><strong>Nom:</strong> <?php echo htmlspecialchars($dadesUsuari['nom']); ?></p>
        <p><strong>Correu:</strong> <?php echo htmlspecialchars($dadesUsuari['correu']); ?></p>
    </div>

    <!-- Gestión de coches -->
    <div class="perfil-section">
        <h3>🚗 Els meus cotxes</h3>
        <table>
            <thead>
            <tr>
                <th>Marca</th>
                <th>Model</th>
                <th>Matricula</th>
                <th>Any</th>
                <th>Color</th>
                <th>Accions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($c = $cotxesResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c['marca']); ?></td>
                    <td><?php echo htmlspecialchars($c['model']); ?></td>
                    <td><?php echo htmlspecialchars($c['matricula']); ?></td>
                    <td><?php echo htmlspecialchars($c['any']); ?></td>
                    <td><?php echo htmlspecialchars($c['color']); ?></td>
                    <td>
                        <a href="?delete_cotxe=<?php echo $c['id']; ?>" class="boto danger" onclick="return confirm('Eliminar cotxe?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <h4 style="margin-top:1rem;">➕ Afegir Nou Cotxe</h4>
        <form method="post">
            <input type="hidden" name="accio" value="afegir_cotxe">
            <input type="text" name="marca" placeholder="Marca" required>
            <input type="text" name="model" placeholder="Model" required>
            <input type="text" name="matricula" placeholder="Matrícula" required>
            <input type="number" name="any" placeholder="Any" min="1900" max="2025">
            <input type="text" name="color" placeholder="Color">
            <button type="submit" class="boto success">💾 Afegir Cotxe</button>
        </form>
    </div>
</div>
</body>
</html>
0