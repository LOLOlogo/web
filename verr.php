<?php
require 'db.php'; // Conectar a la base de datos

try {
    $stmt = $pdo->query("SELECT * FROM ninos");
    $ninos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("❌ Error al recuperar datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Niños</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #e3f2fd;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background: #2196f3;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .boton {
            background: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
        }
        .boton:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<h2>Lista de Registros de Niños</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Edad</th>
        <th>Fecha de Nacimiento</th>
        <th>Motivo de Consulta</th>
        <th>Foto</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($ninos as $nino): ?>
    <tr>
        <td><?= $nino['id'] ?></td>
        <td><?= htmlspecialchars($nino['nombre']) ?></td>
        <td><?= $nino['edad'] ?></td>
        <td><?= $nino['fecha_nacimiento'] ?></td>
        <td><?= htmlspecialchars($nino['motivo_consulta']) ?></td>
        <td>
            <?php if (!empty($nino['foto'])): ?>
                <img src="<?= $nino['foto'] ?>" width="50">
            <?php else: ?>
                Sin foto
            <?php endif; ?>
        </td>
        <td>
            <a href="verrd.php?id=<?= $nino['id'] ?>" class="boton">Ver Detalles</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<a href="formulario.html" class="boton">Volver al Formulario</a>

</body>
</html>
