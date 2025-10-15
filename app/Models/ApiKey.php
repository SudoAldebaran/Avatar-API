<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $table = 'cle_apis';
    protected $primaryKey = 'id_cle_api';
    public $timestamps = true; 
    protected $fillable = ['cle_api', 'raison_sociale', 'status'];
}
