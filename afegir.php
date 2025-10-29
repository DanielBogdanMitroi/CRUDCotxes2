<?php
session_start();
require_once "connexio.php";

if (!isset($_SESSION['usuari_id'])) {
    header("Location: login.php");
    exit();
}

$idUsuari = $_SESSION['usuari_id'];

// Obtener las rutas disponibles
$rutes = $conn->query("SELECT id, origen, desti FROM rutes ORDER BY origen");

// Obtener los coches del usuario logueado
$cotxes = $conn->prepare("SELECT id, marca, model, matricula FROM cotxes WHERE usuari_id = ?");
$cotxes->bind_param("i", $idUsuari);
$cotxes->execute();
$cotxesResult = $cotxes->get_result();

// Si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ruta_id = $_POST['ruta_id'];
    $cotxe_id = $_POST['cotxe_id'] ?: null;
    $data = $_POST['data'];
    $hora_inici = $_POST['hora_inici'];
    $hora_fi = $_POST['hora_fi'];
    $comentaris = $_POST['comentaris'];

    $sql = "INSERT INTO horaris (usuari_id, ruta_id, cotxe_id, data, hora_inici, hora_fi, comentaris)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiissss", $idUsuari, $ruta_id, $cotxe_id, $data, $hora_inici, $hora_fi, $comentaris);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "❌ Error al afegir l’horari. Comprova les dades.";
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Afegir Horari</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h1>➕ Afegir Nou Horari</h1>

        <?php if (isset($error)): ?>
            <p class="error center"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="ruta_id">Ruta:</label>
            <select name="ruta_id" id="ruta_id" required>
                <option value="">-- Selecciona una ruta --</option>
                <?php while ($r = $rutes->fetch_assoc()): ?>
                    <option value="<?php echo $r['id']; ?>">
                        <?php echo htmlspecialchars($r['origen'] . " → " . $r['desti']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="cotxe_id">Cotxe (opcional):</label>
            <select name="cotxe_id" id="cotxe_id">
                <option value="">-- Sense cotxe --</option>
                <?php while ($c = $cotxesResult->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>">
                        <?php echo htmlspecialchars($c['marca'] . " " . $c['model'] . " (" . $c['matricula'] . ")"); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="data">Data:</label>
            <input type="date" name="data" id="data" required>

            <label for="hora_inici">Hora Inici:</label>
            <input type="time" name="hora_inici" id="hora_inici" required>

            <label for="hora_fi">Hora Fi:</label>
            <input type="time" name="hora_fi" id="hora_fi">

            <label for="comentaris">Comentaris:</label>
            <textarea name="comentaris" id="comentaris" placeholder="Ex: Reunió a Girona, entrega, etc."></textarea>

            <div class="center mt-3">
                <button type="submit">💾 Afegir Horari</button>
                <a href="index.php" class="boto danger">❌ Cancel·lar</a>
            </div>
        </form>
    </div>
</body>
</html>
