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
$sql = "SELECT id FROM horaris WHERE id = ? AND usuari_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $idUsuari);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='error center'>❌ No pots eliminar aquest horari (no és teu).</p>";
    echo "<p class='center'><a href='index.php' class='boto'>Tornar</a></p>";
    exit();
}

// Si pasa la validación, eliminarlo
$delete = $conn->prepare("DELETE FROM horaris WHERE id = ? AND usuari_id = ?");
$delete->bind_param("ii", $id, $idUsuari);

if ($delete->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "<p class='error center'>Error al eliminar l'horari.</p>";
    echo "<p class='center'><a href='index.php' class='boto'>Tornar</a></p>";
}
?>
