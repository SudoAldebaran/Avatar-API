<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SvgElement extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'svg_elements';

    // Clé primaire personnalisée
    protected $primaryKey = 'id_svg';

    // Désactiver les timestamps (si pas de created_at/updated_at)
    public $timestamps = false;

    // Attributs autorisés en création/mise à jour (si tu veux utiliser create/update)
    protected $fillable = [
        'element_type',
        'element_name',
        'svg_content',
    ];
}
