document.addEventListener('DOMContentLoaded', () => {

    // ── Reloj ──────────────────────────────────────────────────
    const reloj = document.getElementById('reloj');
    const tick  = () => reloj.textContent = new Date().toLocaleTimeString('es-PE', { hour12: false });
    tick();
    setInterval(tick, 1000);

    // ── DOM ────────────────────────────────────────────────────
    const input         = document.getElementById('codigo');
    const mensajeBox    = document.getElementById('mensaje');
    const msjEl         = document.getElementById('msj');
    const nombreEl      = document.getElementById('empleado-nombre');
    const avatarBox     = document.querySelector('.avatar-box');
    const avatarImg     = avatarBox ? avatarBox.querySelector('img') : null;

    // Mantener foco en el input siempre
    document.addEventListener('click', () => input.focus());
    input.focus();

    // ── Estado ────────────────────────────────────────────────
    let bloqueado = false; // evita doble escaneo mientras se procesa

    // ── Escaneo por teclado / pistola ─────────────────────────
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && input.value.trim() && !bloqueado) {
            procesarDni(input.value.trim());
            input.value = '';
        }
    });

    // Para pistolas que no envían Enter, se dispara al llegar a 8+ caracteres
    input.addEventListener('input', () => {
        if (input.value.length >= 8 && !bloqueado) {
            procesarDni(input.value.trim());
            input.value = '';
        }
    });

    // ── Paso 1: Buscar empleado por DNI ───────────────────────
    function procesarDni(dni) {
        bloqueado = true;
        mostrarMensaje('Buscando…', 'info');

        fetch(BASE_URL + '/asistencias/buscar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'dni=' + encodeURIComponent(dni),
        })
        .then(r => r.json())
        .then(data => {
            if (!data.encontrado) {
                mostrarMensaje('❌ DNI no registrado: ' + dni, 'error');
                resetearPanel();
                setTimeout(() => { limpiarMensaje(); bloqueado = false; }, 3000);
                return;
            }

            // Mostrar nombre y foto del empleado
            const emp = data.empleado;
            nombreEl.textContent = emp.nombre + ' ' + emp.apellido;
            if (avatarBox) avatarBox.classList.add('activo');
            // Si el empleado tuviera foto propia se podría cargar aquí

            // ── Paso 2: Registrar asistencia ──────────────────
            fetch(BASE_URL + '/asistencias/registradito', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id_empleadito=' + encodeURIComponent(emp.id_empleado),
            })
            .then(r => r.json())
            .then(res => {
                if (!res.registrado) {
                    mostrarMensaje('⚠️ No se pudo registrar la asistencia.', 'error');
                } else {
                    switch (res.tipo) {
                        case 'entrada':
                            mostrarMensaje('✅ Entrada registrada — Bienvenido, ' + emp.nombre + '!', 'ok');
                            break;
                        case 'salida':
                            mostrarMensaje('👋 Salida registrada — Hasta luego, ' + emp.nombre + '!', 'ok');
                            break;
                        case 'ya_completo':
                            mostrarMensaje('ℹ️ Ya registraste entrada y salida hoy.', 'info');
                            break;
                        default:
                            mostrarMensaje('✅ Asistencia registrada.', 'ok');
                    }
                }

                setTimeout(() => {
                    limpiarMensaje();
                    resetearPanel();
                    bloqueado = false;
                    input.focus();
                }, 4000);
            })
            .catch(() => {
                mostrarMensaje('❌ Error de conexión al registrar.', 'error');
                setTimeout(() => { limpiarMensaje(); resetearPanel(); bloqueado = false; }, 3000);
            });
        })
        .catch(() => {
            mostrarMensaje('❌ Error de conexión al buscar.', 'error');
            setTimeout(() => { limpiarMensaje(); bloqueado = false; }, 3000);
        });
    }

    // ── Helpers ───────────────────────────────────────────────
    function mostrarMensaje(texto, tipo) {
        msjEl.textContent = texto;
        mensajeBox.className = 'mensaje ' + tipo;
    }

    function limpiarMensaje() {
        msjEl.textContent = '';
        mensajeBox.className = 'mensaje';
    }

    function resetearPanel() {
        nombreEl.textContent = '— — —';
        if (avatarBox) avatarBox.classList.remove('activo');
    }
});
