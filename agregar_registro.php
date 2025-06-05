<?php
session_start();
require "conn.php";

// Mostrar todos los datos recibidos por POST (solo para depuración)
echo "<pre>Datos recibidos:\n";
print_r($_POST);
echo "</pre>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y limpiar datos del formulario
    $matricula = trim($_POST["matricula"]);
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $email = trim($_POST["email"]);
    $contrasena = trim($_POST["contrasena"]);
    $confirmar_contrasena = trim($_POST["confirmar_contrasena"]);

    // Mostrar datos limpios (solo para depuración)
    echo "<pre>Datos después de trim:\n";
    echo "Matrícula: $matricula\n";
    echo "Nombre: $nombre\n";
    echo "Apellido: $apellido\n";
    echo "Email: $email\n";
    echo "Contraseña: [protegida]\n";
    echo "Confirmar contraseña: [protegida]</pre>";

    // Array para almacenar errores
    $errores = [];

    // Validación de matrícula (9 dígitos exactos)
    if (!preg_match('/^\d{9}$/', $matricula)) {
        $errores[] = "La matrícula debe tener exactamente 9 dígitos";
        echo "<p>Error: Matrícula no válida</p>";
    }

    // Validación de nombre y apellido
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
        echo "<p>Error: Nombre vacío</p>";
    }

    if (empty($apellido)) {
        $errores[] = "El apellido es obligatorio";
        echo "<p>Error: Apellido vacío</p>";
    }

    // Validación de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido";
        echo "<p>Error: Email no válido</p>";
    }

    // Validación de contraseña
    if (strlen($contrasena) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres";
        echo "<p>Error: Contraseña demasiado corta</p>";
    }

    if ($contrasena !== $confirmar_contrasena) {
        $errores[] = "Las contraseñas no coinciden";
        echo "<p>Error: Contraseñas no coinciden</p>";
    }

    // Mostrar errores encontrados (solo para depuración)
    if (!empty($errores)) {
        echo "<pre>Errores de validación:\n";
        print_r($errores);
        echo "</pre>";
    }

    // Verificar si matrícula o email ya existen
    $consulta = $conectar->prepare("SELECT ID_usuario FROM usuarios WHERE matricula = ? OR correo = ? LIMIT 1");
    $consulta->bind_param("ss", $matricula, $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if (mysqli_num_rows($resultado) > 0) {
        $errores[] = "La matrícula o correo electrónico ya están registrados";
        echo "<p>Error: Matrícula o email ya registrados</p>";
    }

    // Si no hay errores, proceder con el registro
    if (empty($errores)) {
        $password_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        echo "<p>Hash de contraseña generado: [protegido]</p>";
        
        $stmt = $conectar->prepare("INSERT INTO usuarios (matricula, nombre, apellido, correo, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $matricula, $nombre, $apellido, $email, $password_hash);
        
        echo "<p>Ejecutando consulta SQL...</p>";
        
        if ($stmt->execute()) {
            echo "<p>Registro exitoso en la base de datos</p>";
            // Registro exitoso - redirigir a index.php con mensaje de éxito
            $_SESSION['registro_exitoso'] = true;
            header("Location: index.php");
            exit();
        } else {
            $errores[] = "Error al registrar el usuario. Por favor, inténtelo nuevamente.";
            echo "<p>Error en la consulta SQL: " . $stmt->error . "</p>";
        }
    }

    // Si hay errores, guardarlos en sesión y redirigir de vuelta al formulario
    $_SESSION['errores_registro'] = $errores;
    $_SESSION['datos_formulario'] = [
        'matricula' => $matricula,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'email' => $email
    ];
    
    echo "<p>Redirigiendo de vuelta al formulario...</p>";
    header("Location: registro.php");
    exit();
}

// Si se accede directamente al archivo sin enviar formulario
echo "<p>Acceso directo detectado, redirigiendo...</p>";
header("Location: registro.php");
exit();
?>