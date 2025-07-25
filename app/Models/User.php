<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'tb_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the transactions associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Transaction>
     */
    public function transaksi()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Get the finance records associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Finance>
     */
    public function keuangan()
    {
        return $this->hasMany(Finance::class, 'user_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
