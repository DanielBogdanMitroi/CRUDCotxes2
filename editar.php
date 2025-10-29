<?php
session_start();
require_once "connexio.php";

if (!isset($_SESSION['usuari_id'])) {
    header("Location: login.php");
    exit();
}

$idUsuari = $_SESSION['usuari_id'];

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Verificar que el horario pertenece al usuario actual
$sql = "SELECT * FROM horaris WHERE id = ? AND usuari_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $idUsuari);
$stmt->execute();
$resultat = $stmt->get_result();

if ($resultat->num_rows === 0) {
    echo "<p class='error center'>❌ No pots editar aquest horari (no és teu).</p>";
    echo "<p class='center'><a href='index.php' class='boto'>Tornar</a></p>";
    exit();
}

$horari = $resultat->fetch_assoc();

// Si se envía el formulario para actualizar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = $_POST['data'];
    $hora_inici = $_POST['hora_inici'];
    $hora_fi = $_POST['hora_fi'];
    $comentaris = $_POST['comentaris'];

    $update = $conn->prepare("UPDATE horaris SET data=?, hora_inici=?, hora_fi=?, comentaris=? WHERE id=? AND usuari_id=?");
    $update->bind_param("ssssii", $data, $hora_inici, $hora_fi, $comentaris, $id, $idUsuari);

    if ($update->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<p class='error center'>Error al actualitzar l'horari.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Editar Horari</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Horari</h1>
        <form method="post">
            <label>Data:</label>
            <input type="date" name="data" value="<?php echo $horari['data']; ?>" required>

            <label>Hora Inici:</label>
            <input type="time" name="hora_inici" value="<?php echo $horari['hora_inici']; ?>" required>

            <label>Hora Fi:</label>
            <input type="time" name="hora_fi" value="<?php echo $horari['hora_fi']; ?>">

            <label>Comentaris:</label>
            <textarea name="comentaris"><?php echo htmlspecialchars($horari['comentaris']); ?></textarea>

            <div class="center mt-3">
                <button type="submit">💾 Guardar Canvis</button>
                <a href="index.php" class="boto danger">❌ Cancel·lar</a>
            </div>
        </form>
    </div>
</body>
</html>
