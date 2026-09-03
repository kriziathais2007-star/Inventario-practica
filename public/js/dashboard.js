document.addEventListener("DOMContentLoaded", () => {
    // Hamburger / Sidebar toggle (móvil) 
    const hamburger = document.querySelector(".hamburger");
    const sidebar   = document.querySelector(".sidebar");
    const overlay   = document.querySelector(".overlay");

    if (hamburger && sidebar && overlay) {
        function openSidebar() {
            sidebar.classList.add("open");
            overlay.classList.add("show");
        }

        function closeSidebar() {
            sidebar.classList.remove("open");
            overlay.classList.remove("show");
        }

        hamburger.addEventListener("click", openSidebar);
        overlay.addEventListener("click", closeSidebar);
    }

    //  Cerrar sesión (solo si el botón existe en esta página)
    const btnLogout = document.getElementById("btn-logout");
    if (btnLogout) {
        btnLogout.addEventListener("click", (e) => {
            e.preventDefault();
            const href = e.currentTarget.href;
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: 'Se cerrará tu sesión actual.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) window.location.href = href;
            });
        });
    }

});