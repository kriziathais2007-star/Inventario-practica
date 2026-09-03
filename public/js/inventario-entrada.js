document.addEventListener('DOMContentLoaded', () => {

    // ── DOM ────────────────────────────────────────────────────
    const input        = document.getElementById('codigo');
    const productName  = document.getElementById('productName');
    const productStock = document.getElementById('productStock');
    const contadorEl   = document.getElementById('contador');
    const mensajeEl    = document.getElementById('mensaje');
    const productBox   = document.getElementById('productBox');
    const productImg   = document.getElementById('productImg');

    // ── Estado ────────────────────────────────────────────────
    let productoActual = null;
    let cantidadAcum   = 0;
    let timerConfirmar = null;
    const DELAY_MS     = 3000;

    // Foco permanente en el input
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#cameraContainer') &&
            !e.target.closest('#btnCamara') &&
            !e.target.closest('#btnCerrarCamara')) {
            input.focus();
        }
    });
    input.focus();

    // ── Escaneo por teclado / pistola ──────────────────────────
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && input.value.trim()) {
            procesarEscaneo(input.value.trim());
            input.value = '';
        }
    });
    input.addEventListener('input', () => {
        if (input.value.length >= 4) {
            procesarEscaneo(input.value.trim());
            input.value = '';
        }
    });

    // ── Lógica de escaneo ─────────────────────────────────────
    function procesarEscaneo(codigo) {
        if (productoActual && productoActual.codigo === codigo) {
            cantidadAcum++;
            actualizarContador();
            reiniciarTimer();
            return;
        }
        if (productoActual && cantidadAcum > 0) {
            confirmarEntrada(() => buscarNuevo(codigo));
            return;
        }
        buscarNuevo(codigo);
    }

    function buscarNuevo(codigo) {
        fetch(BASE_URL + '/inventario/buscar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'codigo=' + encodeURIComponent(codigo),
        })
        .then(r => r.json())
        .then(data => {
            if (data.encontrado) {
                productoActual = data.producto;
                cantidadAcum   = 1;

                productName.textContent  = data.producto.nombre_producto;
                productStock.textContent = 'Stock actual: ' + data.producto.stock;
                productBox.classList.add('has-product');

                if (data.producto.imagen) {
                    productImg.innerHTML = '<img src="' + BASE_URL + '/public/image/productos/' + data.producto.imagen + '" alt="">';
                } else {
                    productImg.innerHTML = '<i class="fa-solid fa-box"></i>';
                }

                actualizarContador();
                reiniciarTimer();
                ocultarMensaje();
            } else {
                mostrarMensaje('Código no registrado: ' + codigo, 'error');
                productName.textContent = 'No encontrado';
                setTimeout(() => { ocultarMensaje(); resetPanel(); }, 3000);
            }
        });
    }

    function actualizarContador() {
        contadorEl.textContent = '×' + cantidadAcum;
        contadorEl.classList.add('visible');
    }

    function reiniciarTimer() {
        clearTimeout(timerConfirmar);
        timerConfirmar = setTimeout(() => confirmarEntrada(), DELAY_MS);
    }

    function confirmarEntrada(callback) {
        if (!productoActual || cantidadAcum === 0) return;
        const id = productoActual.id_producto, qty = cantidadAcum;
        productoActual = null; cantidadAcum = 0;
        clearTimeout(timerConfirmar);

        fetch(BASE_URL + '/inventario/agregarStock', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_producto=' + id + '&cantidad=' + qty,
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                mostrarMensaje('+' + qty + ' unidad(es) añadida(s). Stock: ' + data.producto.stock, 'ok');
            } else {
                mostrarMensaje(data.mensaje, 'error');
            }
            resetPanel();
            setTimeout(() => ocultarMensaje(), 3500);
            if (typeof callback === 'function') callback();
        });
    }

    function resetPanel() {
        productName.textContent  = 'Esperando escaneo…';
        productStock.textContent = '';
        contadorEl.textContent   = '';
        contadorEl.classList.remove('visible');
        productBox.classList.remove('has-product');
        productImg.innerHTML = '<i class="fa-solid fa-box"></i>';
    }

    function mostrarMensaje(texto, tipo) {
        mensajeEl.textContent = texto;
        mensajeEl.className   = 'inv-mensaje ' + tipo;
    }

    function ocultarMensaje() {
        mensajeEl.textContent = '';
        mensajeEl.className   = 'inv-mensaje';
    }

    // ── Cámara ────────────────────────────────────────────────
    const btnCamara       = document.getElementById('btnCamara');
    const cameraContainer = document.getElementById('cameraContainer');
    const cameraVideo     = document.getElementById('cameraVideo');
    const btnCerrar       = document.getElementById('btnCerrarCamara');

    let codeReader   = null;
    let streamActivo = null;
    let escaneando   = false;
    let ultimoCodigo = '';
    let cooldown     = false;

    btnCamara.addEventListener('click', () => {
        if (escaneando) { cerrarCamara(); return; }
        abrirCamara();
    });
    btnCerrar.addEventListener('click', cerrarCamara);

    async function abrirCamara() {
        if (!window.ZXing) {
            mostrarMensaje('La librería de lectura no está disponible.', 'error');
            return;
        }
        codeReader = new ZXing.BrowserMultiFormatReader();
        try {
            const devices  = await ZXing.BrowserCodeReader.listVideoInputDevices();
            const deviceId = elegirCamara(devices);

            cameraContainer.classList.add('visible');
            btnCamara.innerHTML = '<i class="fa-solid fa-camera-slash"></i> Cerrar cámara';
            escaneando = true;

            await codeReader.decodeFromVideoDevice(deviceId, cameraVideo, (result) => {
                if (result && !cooldown) {
                    const texto = result.getText();
                    if (texto !== ultimoCodigo) {
                        ultimoCodigo = texto;
                        cooldown = true;
                        procesarEscaneo(texto);
                        setTimeout(() => { cooldown = false; ultimoCodigo = ''; }, 2000);
                    }
                }
            });
        } catch (err) {
            mostrarMensaje('No se pudo acceder a la cámara. ' + (err.message || ''), 'error');
            cerrarCamara();
        }
    }

    function cerrarCamara() {
        if (codeReader) { codeReader.reset(); codeReader = null; }
        if (streamActivo) { streamActivo.getTracks().forEach(t => t.stop()); streamActivo = null; }
        cameraVideo.srcObject = null;
        cameraContainer.classList.remove('visible');
        btnCamara.innerHTML = '<i class="fa-solid fa-camera"></i> Usar cámara';
        escaneando = false;
        input.focus();
    }

    function elegirCamara(devices) {
        if (!devices || devices.length === 0) return undefined;
        const trasera = devices.find(d =>
            d.label.toLowerCase().includes('back') ||
            d.label.toLowerCase().includes('rear') ||
            d.label.toLowerCase().includes('trasera') ||
            d.label.toLowerCase().includes('environment')
        );
        return (trasera || devices[devices.length - 1]).deviceId;
    }
});
