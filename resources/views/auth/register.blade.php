<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registro - Sistema de Transporte</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          rel="stylesheet" />

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('build/assets/estilos.css')}}">

    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            padding: 2rem 1rem;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #E1F5FE;
            max-width: 1200px;
            width: 100%;
        }
        .register-header {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
        }
        .register-header h2 {
            margin: 0;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .register-body {
            padding: 3rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4FC3F7;
            box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 0.75rem 2.5rem;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
        }
        .register-image {
            text-align: center;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-image img {
            max-width: 100%;
            height: auto;
        }
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }
        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .divider-text {
            padding: 0 1rem;
            color: #6c757d;
            font-weight: 500;
        }
        .btn-github {
            background-color: #333;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s;
        }
        .btn-github:hover {
            background-color: #24292e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>

</head>
<body>

<div class="register-container">
    <div class="register-card">
        <div class="row g-0">
            <!-- Columna con formulario -->
            <div class="col-md-7">
                <div class="register-body">
                    <div class="register-header mb-4">
                        <h2><i class="fas fa-bus me-2"></i>Crear Cuenta</h2>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Nombre Completo -->
                        <div class="mb-4">
                            <label class="form-label" for="name">
                                <i class="fas fa-user me-2"></i>Nombre Completo
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   placeholder="Ingrese su nombre completo"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus />
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label" for="email">
                                <i class="fas fa-envelope me-2"></i>Correo Electrónico
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   placeholder="Ingrese su correo electrónico"
                                   value="{{ old('email') }}"
                                   required />
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- Contraseña y confirmación -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="password">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       placeholder="Ingrese su contraseña"
                                       required />
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="password_confirmation">
                                    <i class="fas fa-lock me-2"></i>Confirmar Contraseña
                                </label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control form-control-lg"
                                       placeholder="Confirme su contraseña"
                                       required />
                            </div>
                        </div>

                        <!-- Botón registro -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                            </button>
                        </div>

                        <!-- Inicio de sesión -->
                        <div class="text-center">
                            <p class="mb-0">¿Ya tienes cuenta?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    Inicia sesión aquí
                                </a>
                            </p>
                        </div>

                        <!-- Divider -->
                        <div class="divider">
                            <span class="divider-text">o regístrate con</span>
                        </div>

                        <!-- Botón social -->
                        <div class="text-center">
                            <a href="#" class="btn btn-github">
                                <i class="fab fa-github me-2"></i>GitHub
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna con imagen -->
            <div class="col-md-5 register-image d-none d-md-flex">
                <img src="/imagenes/bus.png" alt="Sistema de Transporte" class="img-fluid" />
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>
</html>
