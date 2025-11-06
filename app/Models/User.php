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
}
