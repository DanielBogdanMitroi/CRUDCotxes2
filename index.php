<?php
require_once "connexio.php";

// ✅ Comprobar si el usuario está logueado
if (!isset($_SESSION['usuari_id'])) {
    header("Location: login.php");
    exit();
}

$idUsuari = $_SESSION['usuari_id'];

// ✅ Consulta principal: unir usuarios, rutas, cotxes y horaris
$sql = "
SELECT 
    h.id, h.usuari_id, h.data, h.hora_inici, h.hora_fi, h.comentaris,
    u.nom AS nom_usuari,
    r.origen, r.desti, r.distancia_km, r.duracio_estimada,
    c.marca, c.model, c.matricula
FROM horaris h
INNER JOIN usuaris u ON h.usuari_id = u.id
INNER JOIN rutes r ON h.ruta_id = r.id
INNER JOIN cotxes c ON h.cotxe_id = c.id
ORDER BY h.data DESC, h.hora_inici ASC";

$resultat = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió d'Horaris</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #fafafa;
        }
        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .meu {
            background-color: #e7f8e7;
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
        .boto.success { background: #2ecc71; }
        .boto.danger { background: #e74c3c; }
        .boto:hover { opacity: 0.9; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestió d'Horaris</h1>
        <p class="center">
            Benvingut, <strong><?php echo htmlspecialchars($_SESSION['nom']); ?></strong>
        </p>

        <div class="center">
            <a href="afegir.php" class="boto success">➕ Afegir Horari</a>
            <a href="perfil.php" class="boto">👤 Perfil</a>
            <a href="principal.php" class="boto">🏠 Menú</a>
            <a href="logout.php" class="boto danger">🚪 Tancar Sessió</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Usuari</th>
                    <th>Data</th>
                    <th>Hora Inici</th>
                    <th>Hora Fi</th>
                    <th>Ruta</th>
                    <th>Cotxe</th>
                    <th>Comentaris</th>
                    <th>Accions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultat && $resultat->num_rows > 0) {
                    while ($fila = $resultat->fetch_assoc()) {
                        $esMeu = ($fila['usuari_id'] == $idUsuari);

                        echo "<tr" . ($esMeu ? " class='meu'" : "") . ">";
                        echo "<td>" . htmlspecialchars($fila['nom_usuari']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['data']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['hora_inici']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['hora_fi']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['origen']) . " → " . htmlspecialchars($fila['desti']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['marca']) . " " . htmlspecialchars($fila['model']) . " (" . htmlspecialchars($fila['matricula']) . ")</td>";
                        echo "<td>" . htmlspecialchars($fila['comentaris']) . "</td>";

                        echo "<td class='center'>";
                        if ($esMeu) {
                            echo "<a href='editar.php?id=" . $fila['id'] . "' class='boto'>✏️ Editar</a> ";
                            echo "<a href='eliminar.php?id=" . $fila['id'] . "' class='boto danger' onclick='return confirm(\"Segur que vols eliminar aquest horari?\")'>🗑️ Eliminar</a>";
                        } else {
                            echo "<span style='color:#777;'>Només lectura</span>";
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='center'>No hi ha horaris disponibles.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
