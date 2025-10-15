<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AvatarComplet extends Model
{
    protected $table = 'avatar_complet'; 
    protected $fillable = ['avatar_id', 'user_id', 'avatar_svg', 'avatar_name'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($avatar) {
            $avatar->avatar_id = Str::uuid(); // Génère un UUID pour avatar_id
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}