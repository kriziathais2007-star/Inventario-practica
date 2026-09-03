document.addEventListener('DOMContentLoaded', () => {
    const overlay      = document.getElementById('modalOverlay');
    const btnCerrar    = document.getElementById('modalCerrar');
    const editImgWrap  = document.getElementById('editImgWrap');
    const editImgInput = document.getElementById('edit-imagen');
    const editImgPrev  = document.getElementById('editImgPreview');
    const editImgPlac  = document.getElementById('editImgPlaceholder');

    // ── Preview imagen en modal ──────────────────────────────
    editImgWrap.addEventListener('click', () => editImgInput.click());

    editImgInput.addEventListener('change', () => {
        const file = editImgInput.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            editImgPrev.src = e.target.result;
            editImgPrev.classList.remove('hidden');
            editImgPlac.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });

    // ── Abrir modal editar ───────────────────────────────────
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit-id').value            = btn.dataset.id;
            document.getElementById('edit-codigo').value        = btn.dataset.codigo;
            document.getElementById('edit-nombre').value        = btn.dataset.nombre;
            document.getElementById('edit-descripcion').value   = btn.dataset.descripcion;
            document.getElementById('edit-stock').value         = btn.dataset.stock;
            document.getElementById('edit-precio').value        = btn.dataset.precio;
            document.getElementById('edit-imagen-actual').value = btn.dataset.imagen || '';

            // Mostrar imagen actual
            const imgSrc = btn.dataset.imagenSrc || '';
            if (imgSrc) {
                editImgPrev.src = imgSrc;
                editImgPrev.classList.remove('hidden');
                editImgPlac.classList.add('hidden');
            } else {
                editImgPrev.src = '';
                editImgPrev.classList.add('hidden');
                editImgPlac.classList.remove('hidden');
            }

            editImgInput.value = '';
            overlay.classList.add('active');
        });
    });

    // ── Cerrar modal ─────────────────────────────────────────
    btnCerrar.addEventListener('click', () => overlay.classList.remove('active'));
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('active');
    });

    // ── Guardar edición ──────────────────────────────────────
    document.getElementById('btnGuardarEdit').addEventListener('click', () => {
        const form     = document.getElementById('formEditar');
        const formData = new FormData(form);
        const btn      = document.getElementById('btnGuardarEdit');

        btn.disabled    = true;
        btn.textContent = 'Guardando…';

        fetch(BASE_URL + '/productos/editar', {
            method: 'POST',
            body: formData,
        })
        .then(r => r.text())
        .then(texto => {
            const match = texto.match(/\{[\s\S]*\}/);
            if (!match) throw new Error('Respuesta inesperada del servidor.');
            return JSON.parse(match[0]);
        })
        .then(res => {
            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: 'Los cambios se guardaron correctamente.',
                    confirmButtonColor: '#4f46e5',
                    timer: 1800,
                    showConfirmButton: false,
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.mensaje || 'No se pudo guardar.',
                    confirmButtonColor: '#4f46e5',
                });
                btn.disabled    = false;
                btn.textContent = 'Guardar cambios';
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: err.message,
                confirmButtonColor: '#4f46e5',
            });
            btn.disabled    = false;
            btn.textContent = 'Guardar cambios';
        });
    });

    // ── Eliminar ─────────────────────────────────────────────
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => {
            Swal.fire({
                title: '¿Eliminar producto?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch(BASE_URL + '/productos/eliminar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id_producto=' + btn.dataset.id,
                })
                .then(r => r.json())
                .then(res => { if (res.ok) location.reload(); });
            });
        });
    });
});
