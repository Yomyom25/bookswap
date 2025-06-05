<?php
session_start();

// Verificar si el usuario ya está autenticado
if (isset($_SESSION["autentificado"]) && $_SESSION["autentificado"] === "SI") {
    header("Location: principal.php");
    exit();
}

// Recuperar datos del formulario si hubo errores
$datos_formulario = $_SESSION['datos_formulario'] ?? [];
$errores = $_SESSION['errores_registro'] ?? [];
unset($_SESSION['errores_registro'], $_SESSION['datos_formulario']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <div class="div-login">
        <div class="background_login">
            <h1>Regístrate</h1>
            <div class="img-logo">
                <a href="index.php">
                    <img class="logo" src="assets/img/ilustracion1.png" alt="Logo">
                </a>
            </div>
            <p>Ingrese sus datos:</p>
            <span class="text-small">Todos los campos con * son obligatorios</span>

            <?php if (!empty($errores)): ?>
                <div class="error-container">
                    <?php foreach ($errores as $error): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="agregar_registro.php" method="post" id="registroForm">
                <input class="input-login" type="text" id="matricula" name="matricula" 
                       placeholder="Matrícula de 9 dígitos*" maxlength="9" required
                       value="<?php echo htmlspecialchars($datos_formulario['matricula'] ?? ''); ?>">

                <input class="input-login" type="text" id="nombre" name="nombre" 
                       placeholder="Nombre(s)*" required
                       value="<?php echo htmlspecialchars($datos_formulario['nombre'] ?? ''); ?>">

                <input class="input-login" type="text" id="apellido" name="apellido" 
                       placeholder="Apellidos*" required
                       value="<?php echo htmlspecialchars($datos_formulario['apellido'] ?? ''); ?>">

                <input class="input-login" type="email" id="email" name="email" 
                       placeholder="Correo electrónico*" required
                       value="<?php echo htmlspecialchars($datos_formulario['email'] ?? ''); ?>">

                <input class="input-login" type="password" id="password" name="contrasena" 
                       placeholder="Contraseña (mínimo 8 caracteres)*" minlength="8" required>

                <input class="input-login" type="password" id="confirm_password" name="confirmar_contrasena" 
                       placeholder="Confirmar contraseña*" minlength="8" required>

                <input type="submit" value="Registrarse" class="registro ancho-uniforme btn">
            </form>

            <div class="login-link">
                <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión</a></p>
            </div>
        </div>
    </div>

    <script src="assets/js/validacion_registro.js"></script>
</body>
</html>