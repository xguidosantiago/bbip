document.addEventListener('DOMContentLoaded', function() {
    var formularioSelector = document.getElementById('formularioSelector');
    var formularioContainer = document.getElementById('formularioContainer');

    formularioSelector.addEventListener('change', function() {
        var selectedValue = formularioSelector.value;

        formularioContainer.innerHTML = ''; // Limpia el contenedor

        if (selectedValue === 'J2') {
            cargarFormulario('formJ2.php');
        } else if (selectedValue === 'H2') {
            cargarFormulario('formH2.php');
        }
    });

    function cargarFormulario(url) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    formularioContainer.innerHTML = xhr.responseText;
                } else {
                    formularioContainer.innerHTML = 'Error al cargar el formulario.';
                }
            }
        };
        xhr.send();
    }
});


/*
Este código utiliza JavaScript para detectar cambios en el selector, carga el contenido del formulario 
correspondiente a través de una petición XMLHttpRequest y lo muestra en el contenedor. 
Cada vez que cambies la opción seleccionada en el <select>, se cargará el formulario 
apropiado sin necesidad de recargar toda la página ni utilizar AJAX.
*/
