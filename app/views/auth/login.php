<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/login.css">
</head>
<body>

<div class="container">
    <!-- IZQUIERDA -->
    <div class="left">
        <img src="https://insidercontrol.es/upload/content/gif_stati/attendance/attendance.gif" alt="Login">
    </div>

    <!-- DERECHA -->
    <div class="right">

        <p class="auth-welcome">Bienvenido</p>
        <h2><?php echo TITLE_BUSINESS; ?></h2>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert--error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- ══════════════════════════════
             INICIAR SESIÓN
        ══════════════════════════════ -->
        <?php if (($modo ?? 'login') === 'login'): ?>

        <form action="<?php echo BASE_URL; ?>/login" method="POST">
            <label for="user">Usuario</label>
            <input id="user" type="text" name="user" placeholder="Tu nombre de usuario"
                   value="<?php echo htmlspecialchars($_POST['user'] ?? ''); ?>" required autofocus>

            <label for="pass">Contraseña</label>
            <input id="pass" type="password" name="pass" placeholder="Tu contraseña" required>

            <button type="submit" class="btn-submit">Ingresar</button>
        </form>

        <!-- ══════════════════════════════
             CREAR CUENTA (solo superadmin)
        ══════════════════════════════ -->
        <?php else: ?>

        <form action="<?php echo BASE_URL; ?>/login/registro" method="POST">
            <label for="user">Usuario</label>
            <input id="user" type="text" name="user" placeholder="Nombre de usuario"
                   value="<?php echo htmlspecialchars($_POST['user'] ?? ''); ?>"
                   minlength="3" required autofocus>

            <label for="pass">Contraseña</label>
            <input id="pass" type="password" name="pass" placeholder="Mínimo 6 caracteres"
                   minlength="6" required>

            <label for="pass2">Confirmar contraseña</label>
            <input id="pass2" type="password" name="pass2" placeholder="Repite la contraseña"
                   minlength="6" required>

            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol">
                    <option value="admin">Admin</option>
                    <option value="superadmin">Superadmin</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Crear cuenta</button>
        </form>

        <p class="auth-switch">
            <a href="<?php echo BASE_URL; ?>/login">← Volver al inicio de sesión</a>
        </p>

        <?php endif; ?>

    </div>
</div>

</body>
</html>
