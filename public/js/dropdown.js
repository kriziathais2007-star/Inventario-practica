// Esperar a que cargue el DOM
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los botones dropdown
    const dropbtns = document.querySelectorAll('.sidebar .dropbtn');
    
    // Para cada botón, agregar evento click
    dropbtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Evitar que recargue la página
            
            // Encontrar el li padre con clase 'dropdown'
            const dropdown = this.closest('.dropdown');
            
            // Alternar la clase 'show'
            dropdown.classList.toggle('show');
        });
    });
    
    // =============== esto lo puedes borrar, es para cerrar el dropdown si se hace clic fuera de él  ===============
    // YO LE COMENTE PERO SI DESEAS LE PUEDES DESCOMENTAR
    // Cerrar dropdown si se hace clic fuera ("opcional")  
    // document.addEventListener('click', function(e) {
    //     // Si el click NO es dentro de un dropdown
    //     if (!e.target.closest('.sidebar .dropdown')) {
    //         // Cerrar todos los dropdowns
    //         const dropdowns = document.querySelectorAll('.sidebar .dropdown');
    //         dropdowns.forEach(function(dropdown) {
    //             dropdown.classList.remove('show');
    //         });
    //     }
    // });
    // ==============================================================================================================
});