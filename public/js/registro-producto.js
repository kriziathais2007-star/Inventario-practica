document.addEventListener('DOMContentLoaded', () => {

    // ── Elementos DOM ─────────────────────────────────────────
    const imgDrop      = document.getElementById('imgDrop');
    const imgInput     = document.getElementById('imgInput');
    const imgPreview   = document.getElementById('imgPreview');
    const imgPH        = document.getElementById('imgPlaceholder');
    const btnQuitarImg = document.getElementById('btnQuitarImg');
    const form         = document.getElementById('formRegistro');
    const btnGuardar   = document.getElementById('btnGuardar');

    // Toast SweetAlert2 reutilizable (esquina inferior derecha, sin bloquear)
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    // Guarda el archivo seleccionado para enviarlo con FormData
    let archivoImagen = null;

    // ── Upload de imagen ──────────────────────────────────────
    imgDrop.addEventListener('click', (e) => {
        if (e.target === imgInput) return;
        imgInput.click();
    });

    imgInput.addEventListener('change', () => {
        if (imgInput.files[0]) cargarImagen(imgInput.files[0]);
    });

    // Drag & drop
    imgDrop.addEventListener('dragover', (e) => {
        e.preventDefault();
        imgDrop.classList.add('drag-over');
    });
    imgDrop.addEventListener('dragleave', () => imgDrop.classList.remove('drag-over'));
    imgDrop.addEventListener('drop', (e) => {
        e.preventDefault();
        imgDrop.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) cargarImagen(file);
    });

    function cargarImagen(file) {
        if (file.size > 2 * 1024 * 1024) {
            Toast.fire({ icon: 'error', title: 'La imagen no puede superar 2 MB.' });
            return;
        }
        archivoImagen = file;
        const reader  = new FileReader();
        reader.onload = (e) => {
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('hidden');
            imgPH.classList.add('hidden');
            imgDrop.classList.add('has-img');
            btnQuitarImg.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    btnQuitarImg.addEventListener('click', () => {
        archivoImagen = null;
        imgInput.value = '';
        imgPreview.src = '';
        imgPreview.classList.add('hidden');
        imgPH.classList.remove('hidden');
        imgDrop.classList.remove('has-img');
        btnQuitarImg.classList.add('hidden');
    });

    // ── Validación inline ─────────────────────────────────────
    function setError(inputId, errId, msg) {
        const el  = document.getElementById(inputId);
        const err = document.getElementById(errId);
        if (msg) {
            el.classList.add('invalid');
            err.textContent = msg;
        } else {
            el.classList.remove('invalid');
            err.textContent = '';
        }
    }

    function limpiarErrores() {
        [['codigo','err-codigo'],['nombre_producto','err-nombre'],
         ['stock','err-stock'],['precio','err-precio']].forEach(([id, err]) => setError(id, err, ''));
    }

    function validar() {
        let ok = true;
        const codigo = document.getElementById('codigo').value.trim();
        const nombre = document.getElementById('nombre_producto').value.trim();
        const stock  = document.getElementById('stock').value;
        const precio = document.getElementById('precio').value;

        if (!codigo) { setError('codigo', 'err-codigo', 'El código es obligatorio.'); ok = false; }
        else          { setError('codigo', 'err-codigo', ''); }

        if (!nombre) { setError('nombre_producto', 'err-nombre', 'El nombre es obligatorio.'); ok = false; }
        else          { setError('nombre_producto', 'err-nombre', ''); }

        if (stock === '' || Number(stock) < 0) { setError('stock', 'err-stock', 'Ingresa un stock válido (≥ 0).'); ok = false; }
        else { setError('stock', 'err-stock', ''); }

        if (precio === '' || Number(precio) < 0) { setError('precio', 'err-precio', 'Ingresa un precio válido (≥ 0).'); ok = false; }
        else { setError('precio', 'err-precio', ''); }

        return ok;
    }

    // Limpiar error al escribir
    ['codigo','nombre_producto','stock','precio'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', () => {
            el.classList.remove('invalid');
            const errMap = { codigo:'err-codigo', nombre_producto:'err-nombre', stock:'err-stock', precio:'err-precio' };
            const errEl  = document.getElementById(errMap[id]);
            if (errEl) errEl.textContent = '';
        });
    });

    // ── Envío del formulario ──────────────────────────────────
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        limpiarErrores();
        if (!validar()) return;

        const fd = new FormData();
        fd.append('codigo',          document.getElementById('codigo').value.trim());
        fd.append('nombre_producto', document.getElementById('nombre_producto').value.trim());
        fd.append('descripcion',     document.getElementById('descripcion').value.trim());
        fd.append('stock',           document.getElementById('stock').value);
        fd.append('precio',          document.getElementById('precio').value);
        if (archivoImagen) fd.append('imagen', archivoImagen, archivoImagen.name);

        // Estado cargando
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = `<div class="rp-spinner"></div><span>Guardando…</span>`;

        fetch(BASE_URL + '/productos/guardarAjax', {
            method: 'POST',
            body: fd,
        })
        .then(r => r.text())
        .then(texto => {
            const match = texto.match(/\{[\s\S]*\}/);
            if (!match) throw new Error('Respuesta inesperada del servidor.');
            return JSON.parse(match[0]);
        })
        .then(res => {
            if (res.ok) {
                Toast.fire({ icon: 'success', title: 'Producto guardado correctamente.' });
                setTimeout(() => { window.location.href = BASE_URL + '/productos'; }, 1500);
            } else {
                Toast.fire({ icon: 'error', title: res.mensaje || 'No se pudo guardar el producto.' });
                restaurarBtn();
            }
        })
        .catch(() => {
            Toast.fire({ icon: 'error', title: 'Error de conexión. Intenta de nuevo.' });
            restaurarBtn();
        });
    });

    function restaurarBtn() {
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = `<i class="fa-solid fa-floppy-disk"></i><span>Guardar producto</span>`;
    }

});
