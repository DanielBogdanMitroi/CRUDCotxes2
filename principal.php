<?php
include 'connexio.php';
if (!isset($_SESSION['usuari_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Menú principal</title>
    <style>
        body { font-family: Arial; background: #f3f4f6; text-align: center; padding: 50px; }
        h1 { color: #333; }
        .menu { display: flex; flex-direction: column; gap: 10px; max-width: 300px; margin: 0 auto; }
        a { text-decoration: none; background: #007bff; color: white; padding: 10px; border-radius: 5px; }
        a:hover { background: #0056b3; }
        .logout { background: #dc3545; }
    </style>
</head>
<body>

<h1>👋 Benvingut, <?php echo $_SESSION['nom']; ?>!</h1>
<div class="menu">
    <a href="index.php">📅 Veure horaris</a>
    <a href="afegir.php">➕ Afegir horari</a>
    <a href="logout.php" class="logout">🚪 Tancar sessió</a>
    <a href="perfil.php" class="boto">👤 Perfil</a>

</div>

</body>
</html>
