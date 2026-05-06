<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShelterPost extends Model
{
    protected $fillable = ['user_id','title','description','image','category','is_published'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return 'https://images.unsplash.com/photo-1548681528-6a5c45b66063?w=400&q=80';
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('storage/' . $this->image);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'news'      => 'News',
            'event'     => 'Event',
            'spotlight' => 'Pet Spotlight',
            'update'    => 'Shelter Update',
            default     => ucfirst($this->category),
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'news'      => '#2563eb',
            'event'     => '#7c3aed',
            'spotlight' => '#c4714a',
            'update'    => '#4a7c59',
            default     => '#666',
        };
    }
}