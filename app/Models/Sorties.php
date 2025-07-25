<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sorties extends Model
{
     use HasFactory;
     protected $fillable = [
      
    'owner_name',
    'owner_phone',
    'plaque',
    'type',
    'montant',
    'paiement',
    'paiement_ok',
];
 
    
}
