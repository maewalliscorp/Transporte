<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Quejas y Sugerencias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>
<body>

@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="container-card">
        <div class="header-section">
            <h4 class="mb-0">Quejas y Sugerencias</h4>
            <p class="mb-0 mt-2">Tu opinión es importante para nosotros</p>
        </div>

        <div class="form-section">
            <form id="formQuejas" onsubmit="return enviarQueja(event)">
                <div class="row g-3">
                    <!-- Información Personal -->
                    <div class="col-md-4">
                        <label for="nombreQueja" class="form-label">Nombre</label>
                        <input type="text" id="nombreQueja" class="form-control" required />
                    </div>
                    <div class="col-md-4">
                        <label for="apellidoQueja" class="form-label">Apellido</label>
                        <input type="text" id="apellidoQueja" class="form-control" required />
                    </div>
                    <div class="col-md-4">
                        <label for="correoQueja" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correoQueja" class="form-control" required />
                    </div>

                    <!-- Tipo de comentario -->
                    <div class="col-12">
                        <label class="form-label">Tipo de comentario</label>
                        <div class="type-buttons text-center">
                            <input type="radio" class="btn-check" name="tipoComentario" id="queja" value="queja" checked>
                            <label class="btn btn-outline-danger" for="queja">Queja</label>

                            <input type="radio" class="btn-check" name="tipoComentario" id="sugerencia" value="sugerencia">
                            <label class="btn btn-outline-success" for="sugerencia">Sugerencia</label>

                            <input type="radio" class="btn-check" name="tipoComentario" id="felicitacion" value="felicitacion">
                            <label class="btn btn-outline-primary" for="felicitacion">Felicitación</label>
                        </div>
                    </div>

                    <!-- Área relacionada -->
                    <div class="col-12">
                        <label for="areaRelacionada" class="form-label">Área relacionada</label>
                        <select id="areaRelacionada" class="form-select">
                            <option value="">Seleccione un área</option>
                            <option value="conductor">Conductor</option>
                            <option value="unidad">Unidad</option>
                            <option value="ruta">Ruta</option>
                            <option value="tarifas">Tarifas</option>
                            <option value="atencion">Atención al cliente</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>

                    <!-- Comentario -->
                    <div class="col-12">
                        <label for="textoQueja" class="form-label">Tu comentario</label>
                        <textarea id="textoQueja" rows="6" class="form-control" placeholder="Por favor, describe detalladamente tu queja, sugerencia o felicitación..." required></textarea>
                        <div class="form-text">
                            <span id="contadorCaracteres">0</span>/500 caracteres
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Comentario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    // Contador de caracteres
    document.getElementById('textoQueja').addEventListener('input', function() {
        const contador = document.getElementById('contadorCaracteres');
        contador.textContent = this.value.length;

        if (this.value.length > 500) {
            contador.classList.add('text-danger');
        } else {
            contador.classList.remove('text-danger');
        }
    });

    // Enviar queja o sugerencia
    function enviarQueja(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombreQueja').value.trim();
        const apellido = document.getElementById('apellidoQueja').value.trim();
        const correo = document.getElementById('correoQueja').value.trim();
        const texto = document.getElementById('textoQueja').value.trim();
        const tipoComentario = document.querySelector('input[name="tipoComentario"]:checked').value;
        const areaRelacionada = document.getElementById('areaRelacionada').value;

        if (texto.length > 500) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El comentario no puede exceder los 500 caracteres.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }

        // Simulación de envío exitoso(pendiente backend)
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Comentario enviado exitosamente",
            text: `Tipo: ${tipoComentario.charAt(0).toUpperCase() + tipoComentario.slice(1)}`,
            showConfirmButton: false,
            timer: 1500
        });

        // Limpiar formulario después de 1.5 segundos
        setTimeout(() => {
            document.getElementById('formQuejas').reset();
            document.getElementById('contadorCaracteres').textContent = '0';
        }, 1500);

        return false;
    }
</script>

</body>
</html>
