<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Pasajeros Frecuentes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

@include('layouts.menuPrincipal')

<div class="container mt-4">
    <h4 class="mb-3">Gestión de Pasajeros Frecuentes</h4>

    <!-- Filtro -->
    <div class="mb-4">
        <label class="form-label me-3">Selecciona opción:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vistaPasajeros" id="registroPasajeros" value="registro" checked />
            <label class="form-check-label" for="registroPasajeros">Registro de Pasajeros Frecuentes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vistaPasajeros" id="historialPasajeros" value="historial" />
            <label class="form-check-label" for="historialPasajeros">Historial de Pasajeros</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vistaPasajeros" id="quejasSugerencias" value="quejas" />
            <label class="form-check-label" for="quejasSugerencias">Quejas y Sugerencias</label>
        </div>
    </div>

    <!-- REGISTRO DE PASAJEROS FRECUENTES -->
    <div id="seccionRegistro">
        <form>
            <!-- Nombre y Apellido en una fila -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" class="form-control" />
                </div>
            </div>

            <!-- Correo Electronico solo en una fila -->
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" id="correo" class="form-control" />
            </div>

            <!-- Confirmacion de correo electronico solo en una fila -->
            <div class="mb-3">
                <label for="correoConfirmacion" class="form-label">Confirmación de Correo Electrónico</label>
                <input type="email" id="correoConfirmacion" class="form-control" />
            </div>

            <!-- Tipo de pasajero y ID de tarjeta en una fila -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tipoPasajero" class="form-label">Tipo de Pasajero</label>
                    <input type="text" id="tipoPasajero" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="idTarjeta" class="form-label">ID de Tarjeta</label>
                    <input type="text" id="idTarjeta" class="form-control" />
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>



    </div>

    <!-- HISTORIAL DE PASAJEROS -->
    <div id="seccionHistorial" style="display:none;">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="fechaHistorial" class="form-label">Selecciona Fecha</label>
                <input type="date" id="fechaHistorial" class="form-control" onchange="filtrarHistorial()" />
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Fecha</th>
                <th>Ruta</th>
                <th>Hora</th>
                <th>Monto que pagó</th>
                <th>Medio de pago</th>
                <th>Origen</th>
                <th>Destino</th>
            </tr>
            </thead>
            <tbody id="tablaHistorial">
            <!-- Aquí se cargarán los datos de historial -->
            <tr>
                <td>Unidad 12</td>
                <td>2025-09-01</td>
                <td>Ruta A</td>
                <td>08:00</td>
                <td>$25.00</td>
                <td>Tarjeta</td>
                <td>Centro</td>
                <td>Plaza</td>
            </tr>
            <tr>
                <td>Unidad 15</td>
                <td>2025-09-01</td>
                <td>Ruta B</td>
                <td>09:30</td>
                <td>$30.00</td>
                <td>Efectivo</td>
                <td>Plaza</td>
                <td>Centro</td>
            </tr>
            <tr>
                <td>Unidad 12</td>
                <td>2025-09-02</td>
                <td>Ruta A</td>
                <td>08:00</td>
                <td>$25.00</td>
                <td>Tarjeta</td>
                <td>Centro</td>
                <td>Plaza</td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- QUEJAS Y SUGERENCIAS -->
    <div id="seccionQuejas" style="display:none;">
        <form id="formQuejas" onsubmit="return enviarQueja(event)">
            <div class="row g-3">
                <div class="md-3">
                    <label for="nombreQueja" class="form-label">Nombre</label>
                    <input type="text" id="nombreQueja" class="form-control" required />
                </div>

                <div class="md-3">
                    <label for="apellidoQueja" class="form-label">Apellido</label>
                    <input type="text" id="apellidoQueja" class="form-control" required />
                </div>
                <div class="md-4">
                    <label for="correoQueja" class="form-label">Correo Electrónico</label>
                    <input type="email" id="correoQueja" class="form-control" required />
                </div>
                <div class="md-3>
                    <label for="textoQueja" class="form-label">Queja y Sugerencia</label>
                    <textarea id="textoQueja" rows="4" class="form-control" required></textarea>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Alternar secciones según filtro
    const radios = document.querySelectorAll('input[name="vistaPasajeros"]');
    const secciones = {
        registro: document.getElementById('seccionRegistro'),
        historial: document.getElementById('seccionHistorial'),
        quejas: document.getElementById('seccionQuejas')
    };

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            Object.keys(secciones).forEach(key => {
                secciones[key].style.display = radio.value === key ? 'block' : 'none';
            });
        });
    });

    // Validar y enviar registro de pasajeros frecuentes
    function enviarRegistroPasajero(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombre').value.trim();
        const apellido = document.getElementById('apellido').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const correoConfirmacion = document.getElementById('correoConfirmacion').value.trim();
        const tipoPasajero = document.getElementById('tipoPasajero').value.trim();
        const idTarjeta = document.getElementById('idTarjeta').value.trim();

        if (correo !== correoConfirmacion) {
            alert('Los correos electrónicos no coinciden.');
            return false;
        }

        // Aquí enviar datos a servidor con AJAX o por formulario normal (pendiente integrar backend)

        alert(`Pasajero registrado:\n${nombre} ${apellido}\nTipo: ${tipoPasajero}\nID Tarjeta: ${idTarjeta}`);
        event.target.reset();
        return false;
    }

    // Filtrar historial por fecha (simulado)
    function filtrarHistorial() {
        const fechaFiltro = document.getElementById('fechaHistorial').value;
        const filas = document.querySelectorAll('#tablaHistorial tr');

        filas.forEach(fila => {
            const fecha = fila.cells[1].textContent;
            if (!fechaFiltro || fecha === fechaFiltro) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }

    // Enviar queja o sugerencia
    function enviarQueja(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombreQueja').value.trim();
        const apellido = document.getElementById('apellidoQueja').value.trim();
        const correo = document.getElementById('correoQueja').value.trim();
        const texto = document.getElementById('textoQueja').value.trim();

        // Aquí enviar datos al servidor (pendiente backend)

        alert(`Queja o sugerencia enviada por:\n${nombre} ${apellido}\nCorreo: ${correo}`);
        event.target.reset();
        return false;
    }
</script>

</body>
</html>

