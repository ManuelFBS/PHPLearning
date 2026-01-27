<?php
/**
 * Vista de Login - Unipro 1.0
 *
 * Esta vista SOLO muestra el formulario de login.
 * La lógica (validar POST, llamar al AuthController, crear sesión)
 * está en Public/index.php. Este archivo es incluido por index.php
 * cuando ?page=login, por lo que la variable $error ya viene definida.
 */

// Por si se incluye desde otro lugar, garantizar que $error exista...
$error = $error ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Unipro</title>
        <!-- Bootstrap desde CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- CSS de la app: ruta relativa al documento (index.php está en Public/, luego Css/ está en Public/Css/) -->
        <link rel="stylesheet" href="Css/styles.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100 login-page">
        <div class="card shadow-sm" style="width: 24rem;">
                <div class="card-body">
                        <h3 class="text-center mb-4">Unipro 1.0</h3>

                        <?php if ($error !== ''): ?>
                                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <!-- El action vacío envía el POST a la misma URL (index.php?page=login) -->
                        <form method="POST" action="">
                                <div class="mb-3">
                                        <label for="username" class="form-label">Usuario</label>
                                        <input type="text" id="username" name="username" class="form-control"
                                               autocomplete="username" required>
                                </div>
                                <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" id="password" name="password" class="form-control"
                                               autocomplete="current-password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                        </form>
                </div>
        </div>
</body>
</html>
