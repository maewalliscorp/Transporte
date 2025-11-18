<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registro</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />

    <!-- Estilos personalizados -->
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .h-custom {
            height: calc(100% - 73px);
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
    </style>
</head>

<body>

<!-- Section: Design Block -->
<section class="text-center">
    <!-- Background image -->
    <div class="p-5 bg-image" style="
        background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
        height: 300px;
      "></div>
    <!-- Background image -->

    <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary" style="
        margin-top: -100px;
        backdrop-filter: blur(30px);
      ">
        <div class="card-body py-5 px-md-5">

            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-5">Regístrate ahora</h2>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Nombre -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <input type="text" name="name" class="form-control" required />
                                    <label class="form-label">Nombre</label>
                                </div>
                            </div>

                            <!-- Puedes omitir "apellidos" si tu modelo no lo usa -->
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <input type="text" name="lastname" class="form-control" />
                                    <label class="form-label">Apellidos</label>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-outline mb-4">
                            <input type="email" name="email" class="form-control" required />
                            <label class="form-label">Correo electrónico</label>
                        </div>

                        <!-- Contraseña y confirmación -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <input type="password" name="password" class="form-control" required />
                                    <label class="form-label">Contraseña</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <input type="password" name="password_confirmation" class="form-control" required />
                                    <label class="form-label">Confirmar contraseña</label>
                                </div>
                            </div>
                        </div>

                        <!-- Botón -->
                        <button type="submit" class="btn btn-primary btn-block mb-4">
                            Regístrate
                        </button>

                        <p class="small fw-bold mt-2 pt-1 mb-0">¿Ya tienes cuenta?
                            <a href="{{ route('login') }}" class="link-primary">Inicia sesión</a>
                        </p>

                        <!-- Divider -->
                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0 text-muted">o regístrate con</p>
                        </div>

                        <!-- Botón social (puedes conectar GitHub con Laravel Socialite) -->
                        <div class="text-center">
                            <button type="button" class="btn btn-link btn-floating mx-1">
                                <i class="fab fa-github"></i>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>
<!-- Section: Design Block -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
