<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'shelter_id','name','species','breed','gender',
        'age_years','age_months','size','color','weight_kg',
        'description','health_notes',
        'is_vaccinated','is_neutered','is_microchipped',
        'good_with_kids','good_with_dogs','good_with_cats',
        'status','featured','cover_image',
    ];

    protected $casts = [
        'is_vaccinated'   => 'boolean',
        'is_neutered'     => 'boolean',
        'is_microchipped' => 'boolean',
        'good_with_kids'  => 'boolean',
        'good_with_dogs'  => 'boolean',
        'good_with_cats'  => 'boolean',
        'featured'        => 'boolean',
        'weight_kg'       => 'float',
    ];

    /* ---- Relationships ---- */

    public function shelter()
    {
        return $this->belongsTo(Shelter::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'pet_tag');
    }

    public function images()
    {
        return $this->hasMany(PetImage::class)->orderBy('sort_order');
    }

    public function adoptions()
    {
        return $this->hasMany(Adoption::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /* ---- Scopes ---- */

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeBySpecies($query, string $species)
    {
        return $query->where('species', $species);
    }

    public function scopeBySize($query, string $size)
    {
        return $query->where('size', $size);
    }

    /* ---- Accessors ---- */

    public function getAgeStringAttribute(): string
    {
        if ($this->age_years > 0) {
            return $this->age_years . ' yr' . ($this->age_years > 1 ? 's' : '');
        }
        return $this->age_months . ' month' . ($this->age_months !== 1 ? 's' : '');
    }

    public function getAgeGroupAttribute(): string
    {
        $months = ($this->age_years * 12) + $this->age_months;
        if ($months < 12)  return 'Baby';
        if ($months < 36)  return 'Young';
        if ($months < 96)  return 'Adult';
        return 'Senior';
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/pet-placeholder.jpg');
    }

    public function getSpeciesLabelAttribute(): string
    {
        return ucfirst($this->species);
    }
}
