<?php
session_start();
require "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = trim($_POST["matricula"]);
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $correo = trim($_POST["email"]);
    $password = trim($_POST["contrasena"]);
    $confirmar_password = trim($_POST["confirmar_contrasena"]);

    $errores = [];

    // Validar matrícula alfanumérica
    if (empty($matricula) || !preg_match("/^[A-Z]{1,2}\d{7,8}$/", $matricula)) {
        $errores[] = "La matrícula debe comenzar con 1 o 2 letras mayúsculas seguidas de 7 u 8 dígitos. Ejemplo: E21080747 o AB2089000.";
    }

    // Validar nombre
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }

    // Validar apellido
    if (empty($apellido)) {
        $errores[] = "El apellido es obligatorio.";
    }

    // Validar correo electrónico
    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico inválido.";
    }

    // Validar contraseña
    if (empty($password) || strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    // Verificar coincidencia de contraseñas
    if ($password !== $confirmar_password) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Si hay errores, redirigir al formulario
    if (!empty($errores)) {
        $_SESSION['datos_formulario'] = $_POST;
        $_SESSION['errores_registro'] = $errores;
        header("Location: registro.php");
        exit();
    }

    // Verificar si el correo ya está registrado
    $consulta_correo = $conectar->prepare("SELECT ID_usuario FROM usuarios WHERE correo = ? LIMIT 1");
    $consulta_correo->bind_param("s", $correo);
    $consulta_correo->execute();
    $resultado_correo = $consulta_correo->get_result();

    if (mysqli_num_rows($resultado_correo) > 0) {
        $_SESSION['errores_registro'] = ["El correo electrónico ya está registrado."];
        $_SESSION['datos_formulario'] = $_POST;
        header("Location: registro.php");
        exit();
    }

    // Insertar datos en la base de datos
    $password_cifrada = password_hash($password, PASSWORD_DEFAULT);
    $consulta_insertar = $conectar->prepare("INSERT INTO usuarios (nombre, apellido, matricula, correo, password) VALUES (?, ?, ?, ?, ?)");
    $consulta_insertar->bind_param("sssss", $nombre, $apellido, $matricula, $correo, $password_cifrada);

    if ($consulta_insertar->execute()) {
        echo '<script>
            alert("Registro exitoso.");
            location.href = "index.php";
        </script>';
    } else {
        $_SESSION['errores_registro'] = ["Error al registrar. Intente nuevamente."];
        $_SESSION['datos_formulario'] = $_POST;
        header("Location: registro.php");
    }

    // Liberar recursos
    $consulta_correo->close();
    $consulta_insertar->close();
    $conectar->close();
}
?>
