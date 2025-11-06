<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('users:hash-passwords', function () {
    $this->info('Iniciando proceso de hasheo de contraseñas...');

    $users = User::all();
    $hashed = 0;
    $skipped = 0;

    foreach ($users as $user) {
        // Acceder directamente al atributo sin pasar por el cast
        $rawPassword = $user->getAttributes()['password'] ?? null;

        if (!$rawPassword) {
            $this->warn("⚠ Usuario sin contraseña: {$user->email}");
            continue;
        }

        // Verificar si la contraseña ya está hasheada (bcrypt comienza con $2y$, $2a$, $2b$)
        if (!str_starts_with($rawPassword, '$2y$') &&
            !str_starts_with($rawPassword, '$2a$') &&
            !str_starts_with($rawPassword, '$2b$')) {
            // La contraseña no está hasheada, así que la hasheamos
            // Actualizar directamente en la base de datos para evitar el cast automático
            DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => Hash::make($rawPassword)]);
            $hashed++;
            $this->line("✓ Contraseña hasheada para: {$user->email}");
        } else {
            $skipped++;
            $this->line("- Saltado (ya hasheada): {$user->email}");
        }
    }

    $this->info("\n✓ Proceso completado!");
    $this->info("  - Contraseñas hasheadas: {$hashed}");
    $this->info("  - Contraseñas ya hasheadas (saltadas): {$skipped}");
    $this->info("  - Total usuarios: " . ($hashed + $skipped));
})->purpose('Hashea todas las contraseñas de usuarios que no estén hasheadas con bcrypt');
