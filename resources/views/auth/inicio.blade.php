<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Transporte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .filter-section {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

@include('layouts.menuPrincipal')

<div class="container">

    <!-- Filtro de vista -->
    <div class="filter-section">
        <label class="form-label me-3">Ver:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="verDisponibles" value="disponibles" checked>
            <label class="form-check-label" for="verDisponibles">Disponibles</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="verAsignados" value="asignados">
            <label class="form-check-label" for="verAsignados">Asignados</label>
        </div>
    </div>

    <!-- Formulario de asignación -->
    <form method="POST" action="{{ route('asignar') }}">
        @csrf
        <div class="row mb-4" id="seccionSeleccion">
            <div class="col-md-3 mb-2">
                <label for="unidad" class="form-label">Unidad</label>
                <select class="form-select" id="unidad" name="unidad" required>
                    <option selected disabled>Selecciona...</option>
                    @foreach ($unidades as $unidad)
                        <option value="{{ $unidad->id_unidad }}">{{ $unidad->placa }} - {{ $unidad->modelo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label for="operador" class="form-label">Operador</label>
                <select class="form-select" id="operador" name="operador" required>
                    <option selected disabled>Selecciona...</option>
                    @foreach ($operadores as $operador)
                        <option value="{{ $operador->id_operator }}">
                            {{ $operador->licencia }} - {{ $operador->estado }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label for="ruta" class="form-label">Ruta</label>
                <select class="form-select" id="ruta" name="ruta" required>
                    <option selected disabled>Selecciona...</option>
                    @foreach ($rutas as $ruta)
                        <option value="{{ $ruta->id_ruta }}">{{ $ruta->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label for="horario" class="form-label">Horario</label>
                <select class="form-select" id="horario" name="horario" required>
                    <option selected disabled>Selecciona...</option>
                    @foreach ($horarios as $horario)
                        <option value="{{ $horario->id_horario }}"> {{ $horario->horaSalida }} - {{ $horario->horaLlegada }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4 text-end">
            <button type="submit" class="btn btn-primary">Asignar</button>
        </div>
    </form>

    <!-- Tabla DISPONIBLES -->
    <div id="tablaDisponibles">
        <h5>DISPONIBLES</h5>
        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Horario</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($disponibles as $item)
                <tr>
                    <td>
                        Placa: {{ $item->unidad->placa }}<br>
                        Modelo: {{ $item->unidad->modelo }}<br>
                        Capacidad: {{ $item->unidad->capacidad }}
                    </td>
                    <td>{{ $item->operador->usuario->name ?? '—' }}</td>
                    <td>{{ $item->ruta->nombre ?? '—' }}</td>
                    <td>
                        Salida: {{ $item->horario->horaSalida ?? '—' }}<br>
                        Llegada: {{ $item->horario->horaLlegada ?? '—' }}<br>
                        Fecha: {{ $item->horario->fecha ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay unidades disponibles.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tabla ASIGNADOS -->
    <div id="tablaAsignados" style="display: none;">
        <h5>ASIGNADOS</h5>
        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Horario</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($asignados as $item)
                <tr>
                    <td>
                        Placa: {{ $item->unidad->placa }}<br>
                        Modelo: {{ $item->unidad->modelo }}<br>
                        Capacidad: {{ $item->unidad->capacidad }}
                    </td>
                    <td>

                        Licencia: {{ $item->operador->licencia ?? '—' }}<br>
                        Teléfono: {{ $item->operador->telefono ?? '—' }}<br>
                        Estado: {{ $item->operador->estado ?? '—' }}
                    </td>
                    <td>{{ $item->ruta->nombre ?? '—' }}</td>
                    <td>
                        Salida: {{ $item->horario->horaSalida ?? '—' }}<br>
                        Llegada: {{ $item->horario->horaLlegada ?? '—' }}<br>
                        Fecha: {{ $item->horario->fecha ?? '—' }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const radioDisponibles = document.getElementById('verDisponibles');
    const radioAsignados = document.getElementById('verAsignados');
    const tablaDisponibles = document.getElementById('tablaDisponibles');
    const tablaAsignados = document.getElementById('tablaAsignados');
    const seccionSeleccion = document.getElementById('seccionSeleccion');

    function actualizarVista() {
        if (radioDisponibles.checked) {
            tablaDisponibles.style.display = 'block';
            tablaAsignados.style.display = 'none';
            seccionSeleccion.style.display = 'flex';
        } else {
            tablaDisponibles.style.display = 'none';
            tablaAsignados.style.display = 'block';
            seccionSeleccion.style.display = 'none';
        }
    }

    radioDisponibles.addEventListener('change', actualizarVista);
    radioAsignados.addEventListener('change', actualizarVista);
    window.onload = actualizarVista;
</script>

</body>
</html>
