<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'idConcesionariaFK',
    ];

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'idConcesionariaFK' => 0,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Set the password attribute (hashes automatically)
     */
    public function setPasswordAttribute($value)
    {
        // Solo hashear si el valor no está vacío y no está ya hasheado
        if (!empty($value)) {
            // Verificar si ya está hasheado (bcrypt siempre empieza con $2y$, $2a$ o $2b$ seguido de números)
            if (preg_match('/^\$2[ayb]\$\d{2}\$.{53}$/', $value)) {
                // Ya está hasheado, guardarlo tal cual
                $this->attributes['password'] = $value;
            } else {
                // No está hasheado, hashearlo
                $this->attributes['password'] = \Illuminate\Support\Facades\Hash::make($value);
            }
        }
    }


    /**
     * Verificar si el usuario es administrador
     */
    public function isAdministrador(): bool
    {
        return \Illuminate\Support\Facades\DB::table('administrador')
            ->where('id', $this->id)
            ->exists();
    }

    /**
     * Verificar si el usuario es contador
     */
    public function isContador(): bool
    {
        return \Illuminate\Support\Facades\DB::table('contador')
            ->where('id', $this->id)
            ->exists();
    }

    /**
     * Verificar si el usuario es operador
     */
    public function isOperador(): bool
    {
        return \Illuminate\Support\Facades\DB::table('operador')
            ->where('id', $this->id)
            ->exists();
    }

    /**
     * Verificar si el usuario es supervisor
     */
    public function isSupervisor(): bool
    {
        return \Illuminate\Support\Facades\DB::table('supervisor')
            ->where('id', $this->id)
            ->exists();
    }

    /**
     * Verificar si el usuario es pasajero
     */
    public function isPasajero(): bool
    {
        return \Illuminate\Support\Facades\DB::table('pasajero')
            ->where('id', $this->id)
            ->exists();
    }

    /**
     * Obtener el rol del usuario
     */
    public function getRole(): ?string
    {
        if ($this->isAdministrador()) {
            return 'administrador';
        }
        if ($this->isContador()) {
            return 'contador';
        }
        if ($this->isOperador()) {
            return 'operador';
        }
        if ($this->isSupervisor()) {
            return 'supervisor';
        }
        if ($this->isPasajero()) {
            return 'pasajero';
        }
        return null;
    }
}
