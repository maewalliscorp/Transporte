<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registro de Pasajeros Frecuentes</title>
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
            <h4 class="mb-0">Registro de Pasajeros Frecuentes</h4>
        </div>

        <div class="form-section">
            <form id="formRegistro" onsubmit="return enviarRegistroPasajero(event)">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" class="form-control" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="correoConfirmacion" class="form-label">Confirmación de Correo Electrónico</label>
                        <input type="email" id="correoConfirmacion" class="form-control" required />
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tipoPasajero" class="form-label">Tipo de Pasajero</label>
                        <select id="tipoPasajero" class="form-select" required>
                            <option value="">Seleccione tipo</option>
                            <option value="estudiante">Estudiante</option>
                            <option value="adulto_mayor">Adulto Mayor</option>
                            <option value="regular">Regular</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="idTarjeta" class="form-label">ID de Tarjeta</label>
                        <input type="text" id="idTarjeta" class="form-control" required />
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Registrar Pasajero</button>
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
    // Validar y enviar registro de pasajeros frecuentes
    function enviarRegistroPasajero(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombre').value.trim();
        const apellido = document.getElementById('apellido').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const correoConfirmacion = document.getElementById('correoConfirmacion').value.trim();
        const tipoPasajero = document.getElementById('tipoPasajero').value;
        const idTarjeta = document.getElementById('idTarjeta').value.trim();

        if (correo !== correoConfirmacion) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Los correos electrónicos no coinciden.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }

        // Simulación de envío exitoso
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Pasajero registrado exitosamente",
            showConfirmButton: false,
            timer: 1500
        });

        // Limpiar formulario después de 1.5 segundos
        setTimeout(() => {
            document.getElementById('formRegistro').reset();
        }, 1500);

        return false;
    }
</script>

</body>
</html>
