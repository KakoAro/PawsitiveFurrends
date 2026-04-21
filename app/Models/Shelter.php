<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelter extends Model
{
    protected $fillable = ['name','address','city','province','phone','email','website','logo','description','is_active'];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
