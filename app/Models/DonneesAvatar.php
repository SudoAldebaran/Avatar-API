<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonneesAvatar extends Model
{

    protected $primaryKey = 'id_avatar';

    // Spécifie les champs remplissables pour la création/mise à jour en masse
    protected $fillable = [
        'id_user',
        'name',
        'nose_size',
        'eye_type',
        'eye_color',
        'eye_size',
        'eyebrow_type',
        'eyebrow_color',
        'hair_type',
        'hair_color',
        'mouth_type',
        'mouth_size',
        'beard_type',
        'beard_color',
        'shirt_color',
        'glasses_type',
        'accessory_type',
        'background_type',
        'skin_color',
        'nose_type',
    ];

    // Définit la relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
