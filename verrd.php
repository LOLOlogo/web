<?php
// Incluir la conexión a la base de datos (usa la variable $pdo de tu db.php)
include 'db.php';

// Obtener el ID del niño
$id_nino = $_GET['id'] ?? null;
if (!$id_nino) {
    die("No se ha seleccionado un niño.");
}

// Consulta con LEFT JOIN a todas las tablas que guardan datos relacionados
$query = "
    SELECT 
        -- Datos de la tabla ninos
        n.id AS nino_id,
        n.nombre,
        n.edad,
        n.fecha_nacimiento,
        n.motivo_consulta,
        n.foto,
        
        -- Datos de historia_embarazo
        he.apgar,
        he.controles,
        he.posibles_enfermedades,
        he.cuidados,
        
        -- Datos de historial_medico
        hm.percentiles,
        hm.vacunas,
        hm.enfermedades,
        hm.cirugias,
        hm.medicacion,
        
        -- Datos de educacion
        e.clases,
        e.colegio,
        e.asociaciones,
        e.crianza,
        
        -- Datos de comunicacion
        c.manejo_tablet,
        c.responde_nombre,
        c.descripcion_habla,
        c.audio_problema,  -- si lo necesitas
        
        -- Datos de info_familiar
        i.intereses_familia,
        i.preocupaciones_familia,
        i.ayuda,
        i.miedos
        
    FROM ninos AS n
    -- Unir tabla historia_embarazo
    LEFT JOIN historia_embarazo AS he ON n.id = he.nino_id
    -- Unir tabla historial_medico
    LEFT JOIN historial_medico AS hm ON n.id = hm.nino_id
    -- Unir tabla educacion
    LEFT JOIN educacion AS e ON n.id = e.nino_id
    -- Unir tabla comunicacion
    LEFT JOIN comunicacion AS c ON n.id = c.nino_id
    -- Unir tabla info_familiar
    LEFT JOIN info_familiar AS i ON n.id = i.nino_id
    
    WHERE n.id = ?
";

$stmt = $pdo->prepare($query);
$stmt->execute([$id_nino]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("No se encontraron datos para el niño seleccionado.");
}

// Ahora, cada columna de todas las tablas estará en $row.
// Asignamos valores a las variables que se mostrarán en el HTML.
$nombre_nino           = $row['nombre']              ?? 'No especificado';
$edad                  = $row['edad']                ?? 'No especificado';
$fecha_nacimiento      = $row['fecha_nacimiento']    ?? 'No especificado';
$motivo_consulta       = $row['motivo_consulta']     ?? 'No especificado';

// historia_embarazo
$apgar                 = $row['apgar']               ?? 'No especificado';
$controles             = $row['controles']           ?? 'No especificado';
$posibles_enfermedades = $row['posibles_enfermedades'] ?? 'No especificado';
$cuidados              = $row['cuidados']            ?? 'No especificado';

// historial_medico
$percentiles           = $row['percentiles']         ?? 'No especificado';
$vacunas               = $row['vacunas']             ?? 'No especificado';
$enfermedades          = $row['enfermedades']        ?? 'No especificado';
$cirugias              = $row['cirugias']            ?? 'No especificado';
$medicacion            = $row['medicacion']          ?? 'No especificado';

// educacion
$clases                = $row['clases']              ?? 'No especificado';
$colegio               = $row['colegio']             ?? 'No especificado';
$asociaciones          = $row['asociaciones']        ?? 'No especificado';
$crianza               = $row['crianza']             ?? 'No especificado';

// comunicacion
$manejo_tablet         = $row['manejo_tablet']       ?? 'No especificado';
$responde_nombre       = $row['responde_nombre']     ?? 'No especificado';
$descripcion_habla     = $row['descripcion_habla']   ?? 'No especificado';
$audio_problema        = $row['audio_problema']      ?? 'No especificado'; // si lo necesitas

// info_familiar
$intereses_familia     = $row['intereses_familia']   ?? 'No especificado';
$preocupaciones_familia= $row['preocupaciones_familia'] ?? 'No especificado';
$ayuda                 = $row['ayuda']               ?? 'No especificado';
$miedos                = $row['miedos']              ?? 'No especificado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos del Niño</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #2196f3; }
        h3 { margin-top: 30px; }
        p { margin: 5px 0; }
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
    <h2>Datos del Niño</h2>

    <h3>Datos Básicos</h3>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre_nino) ?></p>
    <p><strong>Edad:</strong> <?= htmlspecialchars($edad) ?></p>
    <p><strong>Fecha de Nacimiento:</strong> <?= htmlspecialchars($fecha_nacimiento) ?></p>
    <p><strong>Motivo de Consulta:</strong> <?= htmlspecialchars($motivo_consulta) ?></p>

    <h3>Historia del Embarazo</h3>
    <p><strong>Apgar:</strong> <?= htmlspecialchars($apgar) ?></p>
    <p><strong>Controles:</strong> <?= htmlspecialchars($controles) ?></p>
    <p><strong>Posibles Enfermedades:</strong> <?= htmlspecialchars($posibles_enfermedades) ?></p>
    <p><strong>Cuidados Durante el Embarazo:</strong> <?= htmlspecialchars($cuidados) ?></p>

    <h3>Historial Médico</h3>
    <p><strong>Percentiles:</strong> <?= htmlspecialchars($percentiles) ?></p>
    <p><strong>Vacunas:</strong> <?= htmlspecialchars($vacunas) ?></p>
    <p><strong>Enfermedades Previas:</strong> <?= htmlspecialchars($enfermedades) ?></p>
    <p><strong>Cirugías:</strong> <?= htmlspecialchars($cirugias) ?></p>
    <p><strong>Medicación:</strong> <?= htmlspecialchars($medicacion) ?></p>

    <h3>Educación</h3>
    <p><strong>Clases Particulares:</strong> <?= htmlspecialchars($clases) ?></p>
    <p><strong>Colegio y Curso:</strong> <?= htmlspecialchars($colegio) ?></p>
    <p><strong>Asistencia a Asociaciones:</strong> <?= htmlspecialchars($asociaciones) ?></p>
    <p><strong>Explicación de la Crianza:</strong> <?= htmlspecialchars($crianza) ?></p>

    <h3>Comunicación</h3>
    <p><strong>Manejo de la Tablet/Móvil:</strong> <?= htmlspecialchars($manejo_tablet) ?></p>
    <p><strong>¿Responde al Nombre?:</strong> <?= htmlspecialchars($responde_nombre) ?></p>
    <p><strong>Descripción del Habla:</strong> <?= htmlspecialchars($descripcion_habla) ?></p>
    <p><strong>Audio/Problema:</strong> <?= htmlspecialchars($audio_problema) ?></p>

    <h3>Información Familiar</h3>
    <p><strong>Intereses Familiares:</strong> <?= htmlspecialchars($intereses_familia) ?></p>
    <p><strong>Preocupaciones Familiares:</strong> <?= htmlspecialchars($preocupaciones_familia) ?></p>
    <p><strong>¿Cómo Podemos Ayudarte?:</strong> <?= htmlspecialchars($ayuda) ?></p>
    <p><strong>Miedos y Ansiedades:</strong> <?= htmlspecialchars($miedos) ?></p>

    <br>
    <a href="listado.php" class="boton">Volver a la lista</a>
</body>
</html>
