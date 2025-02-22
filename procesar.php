<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php'; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction(); // Iniciar transacción

        echo "Recibiendo datos del formulario...<br>";

        // Insertar los datos del tutor (pueden ser hasta 2)
        foreach ($_POST['tutor_nombre'] as $key => $tutor_nombre) {
            echo "Guardando tutor: $tutor_nombre <br>";
            $stmt = $pdo->prepare("INSERT INTO tutores (nombre, email, telefono) VALUES (?, ?, ?)");
            $stmt->execute([$tutor_nombre, $_POST['email'], $_POST['telefono']]);
            $tutor_id = $pdo->lastInsertId();
        }

        // Guardar la foto del niño
        if (!empty($_FILES["foto"]["name"])) {
            $fotoRuta = "uploads/" . basename($_FILES["foto"]["name"]);
            move_uploaded_file($_FILES["foto"]["tmp_name"], $fotoRuta);
            echo "Foto guardada en: $fotoRuta <br>";
        } else {
            $fotoRuta = ""; // Si no se sube una foto
        }

        // Insertar los datos del niño
        echo "Guardando datos del niño...<br>";
        $stmt = $pdo->prepare("INSERT INTO ninos (nombre, edad, fecha_nacimiento, motivo_consulta, foto, tutor_id) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nombre_nino'], $_POST['edad'], $_POST['fecha_nacimiento'], 
            $_POST['motivo_consulta'], $fotoRuta, $tutor_id
        ]);
        $nino_id = $pdo->lastInsertId();

        echo "Datos del niño guardados con ID: $nino_id <br>";

        // Insertar datos de la historia del embarazo
        $stmt = $pdo->prepare("INSERT INTO historia_embarazo (nino_id, apgar, controles, posibles_enfermedades, cuidados) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $nino_id, $_POST['apgar'], $_POST['controles'], $_POST['posibles_enfermedades'], $_POST['cuidados']
        ]);

        // Insertar historial médico
        $stmt = $pdo->prepare("INSERT INTO historial_medico (nino_id, percentiles, vacunas, enfermedades, cirugias, medicacion) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $nino_id, $_POST['percentiles'], $_POST['vacunas'], $_POST['enfermedades'], $_POST['cirugias'], $_POST['medicacion']
        ]);

        // Insertar educación
        $stmt = $pdo->prepare("INSERT INTO educacion (nino_id, clases, colegio, asociaciones, crianza) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $nino_id, $_POST['clases'], $_POST['colegio'], $_POST['asociaciones'], $_POST['crianza']
        ]);

        // Guardar el audio
        if (!empty($_FILES["audio_problema"]["name"])) {
            $audioRuta = "uploads/" . basename($_FILES["audio_problema"]["name"]);
            move_uploaded_file($_FILES["audio_problema"]["tmp_name"], $audioRuta);
        } else {
            $audioRuta = ""; // Si no se sube un audio
        }

        // Insertar comunicación
        $stmt = $pdo->prepare("INSERT INTO comunicacion (nino_id, manejo_tablet, responde_nombre, descripcion_habla, audio_problema) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $nino_id, $_POST['manejo_tablet'], $_POST['responde_nombre'], $_POST['descripcion_habla'], $audioRuta
        ]);

        // Insertar información familiar
        $stmt = $pdo->prepare("INSERT INTO info_familiar (nino_id, intereses_familia, preocupaciones_familia, ayuda, miedos) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $nino_id, $_POST['intereses_familia'], $_POST['preocupaciones_familia'], $_POST['ayuda'], $_POST['miedos']
        ]);

        $pdo->commit(); // Guardar los cambios en la base de datos

        echo "<h2 style='color: green;'>✅ Registro exitoso.</h2>";
        echo "<p>Serás redirigido en 3 segundos...</p>";
        header("refresh:3;url=formulario.html");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("<h2 style='color: red;'>❌ Error: " . $e->getMessage() . "</h2>");
    }
} else {
    die("<h2 style='color: red;'>❌ Acceso no permitido.</h2>");
}
?>
