<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'rating', 'tokens','tokens_used','feedback'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }
    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }
}