<?php
require "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiar y validar entradas
    $identificador = trim($_POST["email"]);
    $contrasena = trim($_POST["contrasena"]);

    // Validar campos no vacíos
    if (empty($identificador) || empty($contrasena)) {
        header("Location: index.php?errorusuario=SI");
        exit();
    }

    // Determinar si es email o matrícula
    $esEmail = filter_var($identificador, FILTER_VALIDATE_EMAIL);
    
    // Preparar consulta según el tipo de identificador
    if ($esEmail) {
        $consulta = $conectar->prepare("SELECT ID_usuario, password FROM usuarios WHERE correo = ? LIMIT 1");
    } else {
        $consulta = $conectar->prepare("SELECT ID_usuario, password FROM usuarios WHERE matricula = ? LIMIT 1");
    }
    
    $consulta->bind_param("s", $identificador);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if (mysqli_num_rows($resultado) > 0) {
        $fila = $resultado->fetch_assoc();
        
        if (password_verify($contrasena, $fila["password"])) {
            session_start();
            $_SESSION["autentificado"] = "SI";
            $_SESSION["ID_usuario"] = $fila["ID_usuario"];
            header("Location: principal.php");
            exit();
        } else {
            header("Location: index.php?errorcontrasena=SI");
            exit();
        }
    } else {
        header("Location: index.php?errorusuario=SI");
        exit();
    }

    mysqli_free_result($resultado);
    $conectar->close();
}