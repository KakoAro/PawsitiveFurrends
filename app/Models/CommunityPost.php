<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'image',
        'location',
        'contact',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'stray'        => '🐾 Stray',
            'rescued'      => '💚 Rescued',
            'lost'         => '🔍 Lost',
            'found'        => '✅ Found',
            default        => ucfirst($this->category),
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'stray'        => '#c4714a',
            'rescued'      => '#4a7c59',
            'lost'         => '#d97706',
            'found'        => '#2563eb',
            'for_adoption' => '#7c3aed',
            default        => '#666',
        };
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return 'https://images.unsplash.com/photo-1548681528-6a5c45b66063?w=400&q=80';
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('storage/' . $this->image);
    }
}