<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetImage extends Model
{
    protected $fillable = ['pet_id', 'image_path', 'caption', 'sort_order'];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}