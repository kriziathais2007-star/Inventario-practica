document.addEventListener('DOMContentLoaded', () => {

    const overlay   = document.getElementById('modalEditarOverlay');
    const btnCerrar = document.getElementById('modalCerrar');

    // Toast reutilizable (esquina inferior derecha)
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (t) => {
            t.onmouseenter = Swal.stopTimer;
            t.onmouseleave = Swal.resumeTimer;
        },
    });

    // ── Abrir modal editar ───────────────────────────────────
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit-id').value       = btn.dataset.id;
            document.getElementById('edit-nombre').value   = btn.dataset.nombre;
            document.getElementById('edit-apellido').value = btn.dataset.apellido;
            document.getElementById('edit-dni').value      = btn.dataset.dni;
            document.getElementById('edit-celular').value  = btn.dataset.celular;
            document.getElementById('edit-correo').value   = btn.dataset.correo;

            const selectCargo = document.getElementById('edit-cargo');
            if (selectCargo) selectCargo.value = btn.dataset.id_cargo;

            overlay.classList.add('active');
        });
    });

    // ── Cerrar modal ─────────────────────────────────────────
    if (btnCerrar) btnCerrar.addEventListener('click', () => overlay.classList.remove('active'));
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('active');
    });

    // ── Guardar edición ──────────────────────────────────────
    const btnGuardarModal = document.querySelector('.btn-guardar-modal');
    if (btnGuardarModal) {
        btnGuardarModal.addEventListener('click', () => {
            const body = new URLSearchParams({
                id_empleado: document.getElementById('edit-id').value,
                nombre:      document.getElementById('edit-nombre').value,
                apellido:    document.getElementById('edit-apellido').value,
                dni:         document.getElementById('edit-dni').value,
                celular:     document.getElementById('edit-celular').value,
                correo:      document.getElementById('edit-correo').value,
                id_cargo:    document.getElementById('edit-cargo')?.value ?? '',
            });

            btnGuardarModal.disabled    = true;
            btnGuardarModal.textContent = 'Guardando…';

            fetch(BASE_URL + '/empleados/editar_empleado', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString(),
            })
            .then(r => r.json())
            .then(res => {
                overlay.classList.remove('active');
                if (res.ok) {
                    Toast.fire({ icon: 'success', title: res.mensaje || 'Empleado actualizado.' });
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Toast.fire({ icon: 'error', title: res.mensaje || 'No se pudo actualizar.' });
                    btnGuardarModal.disabled    = false;
                    btnGuardarModal.textContent = 'Guardar cambios';
                }
            })
            .catch(() => {
                Toast.fire({ icon: 'error', title: 'Error de conexión.' });
                btnGuardarModal.disabled    = false;
                btnGuardarModal.textContent = 'Guardar cambios';
            });
        });
    }

    // ── Eliminar empleado ────────────────────────────────────
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => {
            Swal.fire({
                title: '¿Eliminar empleado?',
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

                fetch(BASE_URL + '/empleados/eliminar_empleado', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id_empleadito=' + btn.dataset.id,
                })
                .then(r => r.json())
                .then(res => {
                    if (res.eliminar) {
                        Toast.fire({ icon: 'success', title: 'Empleado eliminado.' });
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        Toast.fire({ icon: 'error', title: 'No se pudo eliminar.' });
                    }
                })
                .catch(() => Toast.fire({ icon: 'error', title: 'Error de conexión.' }));
            });
        });
    });

});
