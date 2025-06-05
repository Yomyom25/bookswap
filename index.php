<?php
session_start();

// Verificar si el usuario ya está autenticado
if (isset($_SESSION["autentificado"]) && $_SESSION["autentificado"] === "SI") {
    header("Location: principal.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Login</title>
</head>

<body>

    <div class="div-login">

        <div class="background_login">
            <h1>Bienvenido</h1>
            <div class="img-logo">
                <a href="index.php">
                    <img class="logo" src="assets/img/image.png" alt="Logo">
                </a>
            </div>
            <p>Ingrese sus credenciales para acceder al sistema</p>

            <form action="autentificar.php" method="post" name="login_plantilla">
                <?php
                $errorusuario = isset($_GET["errorusuario"]) && $_GET["errorusuario"] === "SI";
                if ($errorusuario) {
                    echo "<p class='erroruser'>Usuario o matrícula incorrectos</p>";
                }
                
                $errorcontrasena = isset($_GET["errorcontrasena"]) && $_GET["errorcontrasena"] === "SI";
                if ($errorcontrasena) {
                    echo "<p class='erroruser'>Contraseña incorrecta</p>";
                }
                ?>
                <input type="text" name="email" placeholder="Correo o matricula" class="input-login ancho-uniforme">
                <input type="password" name="contrasena" placeholder="Contraseña" class="input-login ancho-uniforme">
                <br>
                <input type="submit" value="Iniciar sesión" class="btn-login ancho-uniforme btn">
                <p class="letras">  ____________________ o ____________________  </p>
                <br>
                <footer>
                    <a href="registro.php" class="btn-registro">Crear una cuenta</a>
                </footer>
                <br>
            </form>
        </div>
    </div>

    <script src="scripts/validacion.js"></script>
</body>

</html>