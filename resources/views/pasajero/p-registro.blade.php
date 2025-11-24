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

    <style>
        body {
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #E3F2FD 0%, #F3E5F5 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #E1F5FE;
            margin: 0 auto;
            max-width: 1200px;
        }
        .header-section {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
        }
        .header-section h4 {
            margin: 0;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-size: 1.8rem;
        }
        .form-section {
            padding: 2.5rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #E1F5FE;
            transition: all 0.3s ease;
            background: white;
            padding: 12px 16px;
            font-size: 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4FC3F7;
            box-shadow: 0 0 0 0.3rem rgba(79, 195, 247, 0.2);
            background: white;
        }
        .form-label {
            font-weight: 600;
            color: #37474F;
            margin-bottom: 0.8rem;
            font-size: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            border: none;
            border-radius: 12px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 15px rgba(79, 195, 247, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 195, 247, 0.4);
            background: linear-gradient(135deg, #29B6F6 0%, #6A45B2 100%);
        }
        .form-text {
            text-align: right;
            margin-top: 0.5rem;
            color: #6c757d;
            font-weight: 500;
        }
    </style>

</head>
<body>

@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="container-card">
        <div class="header-section">
            <h4 class="mb-0">Registro de Pasajeros Frecuentes</h4>
        </div>

        <div class="form-section">
            <form id="formRegistro" action="{{ route('pasajero.registro.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label for="correoConfirmacion" class="form-label">Confirmación de Correo Electrónico</label>
                        <input type="email" id="correoConfirmacion" name="correo_confirmacion" class="form-control" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" class="form-control" required />
                        <div class="form-text">Mínimo 8 caracteres</div>
                    </div>
                    <div class="col-md-6">
                        <label for="contrasenaConfirmacion" class="form-label">Confirmación de Contraseña</label>
                        <input type="password" id="contrasenaConfirmacion" name="contrasena_confirmation" class="form-control" required />
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tipoPasajero" class="form-label">Tipo de Pasajero</label>
                        <select id="tipoPasajero" name="tipoPasajero" class="form-select" required>
                            <option value="">Seleccione tipo</option>
                            <option value="estudiante">Estudiante</option>
                            <option value="adulto_mayor">Adulto Mayor</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="idTarjeta" class="form-label">ID de Tarjeta</label>
                        <input type="text" id="idTarjeta" name="idTarjeta" class="form-control" required />
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
    document.getElementById('formRegistro').addEventListener('submit', function(e) {
        e.preventDefault();
        enviarRegistroPasajero(e);
    });

    // Validar y enviar registro de pasajeros frecuentes
    function enviarRegistroPasajero(event) {
        const nombre = document.getElementById('nombre').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const correoConfirmacion = document.getElementById('correoConfirmacion').value.trim();
        const contrasena = document.getElementById('contrasena').value;
        const contrasenaConfirmacion = document.getElementById('contrasenaConfirmacion').value;
        const tipoPasajero = document.getElementById('tipoPasajero').value;
        const idTarjeta = document.getElementById('idTarjeta').value.trim();

        // Validaciones básicas
        if (correo !== correoConfirmacion) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Los correos electrónicos no coinciden.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }

        if (contrasena !== contrasenaConfirmacion) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Las contraseñas no coinciden.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }

        if (contrasena.length < 8) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La contraseña debe tener al menos 8 caracteres.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }

        // Mostrar loading
        Swal.fire({
            title: 'Registrando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Crear FormData
        const formData = new FormData(document.getElementById('formRegistro'));

        // Enviar datos al servidor
        fetch('{{ route("pasajero.registro.store") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error del servidor');
                }
                return data;
            })
            .then(data => {
                Swal.close();

                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Limpiar formulario después de 1.5 segundos
                    setTimeout(() => {
                        document.getElementById('formRegistro').reset();
                    }, 1500);
                } else {
                    if (data.errors) {
                        let errorMessage = '';
                        for (let field in data.errors) {
                            errorMessage += data.errors[field].join('<br>') + '<br>';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            html: errorMessage,
                            confirmButtonColor: '#667eea'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#667eea'
                        });
                    }
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error completo:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error de conexión con el servidor',
                    confirmButtonColor: '#667eea'
                });
            });

        return false;
    }
</script>

</body>
</html>
